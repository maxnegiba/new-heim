<?php
// blog/classes/BlogPost.php
namespace Blog;

class BlogPost extends BlogCore {
    
    public function create($data) {
        try {
            $this->pdo->beginTransaction();
            
            // Validate required fields
            if (empty($data['title']) || empty($data['body'])) {
                throw new \Exception('Title and body are required');
            }
            
            // Prepare data
            $slug = $data['slug'] ?? $this->generateSlug($data['title']);
            $excerpt = $data['excerpt'] ?? $this->generateExcerpt($data['body']);
            $readingTime = $this->calculateReadingTime($data['body']);
            $publishDate = $data['publish_date'] ?? date('Y-m-d H:i:s');
            
            // Insert post
            $stmt = $this->pdo->prepare("
                INSERT INTO blog_posts 
                (title, slug, body, excerpt, featured_image, status, publish_date, 
                 author_id, category_id, reading_time, meta_title, meta_description, 
                 meta_keywords, is_featured, allow_comments)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");
            
            $stmt->execute([
                $this->sanitizeInput($data['title']),
                $slug,
                $this->sanitizeInput($data['body'], 'html'),
                $excerpt,
                $data['featured_image'] ?? null,
                $data['status'] ?? 'draft',
                $publishDate,
                $data['author_id'],
                $data['category_id'] ?? null,
                $readingTime,
                $data['meta_title'] ?? $data['title'],
                $data['meta_description'] ?? $excerpt,
                $data['meta_keywords'] ?? null,
                $data['is_featured'] ?? false,
                $data['allow_comments'] ?? true
            ]);
            
            $postId = $this->pdo->lastInsertId();
            
            // Handle tags
            if (!empty($data['tags'])) {
                $this->attachTags($postId, $data['tags']);
            }
            
            // Create revision
            $this->createRevision($postId, $data['title'], $data['body'], $data['author_id'], 'Initial version');
            
            // Update category post count
            if (!empty($data['category_id'])) {
                $this->updateCategoryPostCount($data['category_id']);
            }
            
            // Clear cache
            $this->cache->clear('blog_posts_*');
            
            $this->pdo->commit();
            
            // Log activity
            $this->logActivity($data['author_id'], 'create_post', 'post', $postId, ['title' => $data['title']]);
            
            return $postId;
            
        } catch (\Exception $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }
    
    public function update($id, $data) {
        try {
            $this->pdo->beginTransaction();
            
            // Get current post data for revision
            $currentPost = $this->getById($id);
            if (!$currentPost) {
                throw new \Exception('Post not found');
            }
            
            // Prepare update query
            $updates = [];
            $params = [];
            
            $allowedFields = [
                'title', 'slug', 'body', 'excerpt', 'featured_image', 
                'status', 'publish_date', 'category_id', 'meta_title', 
                'meta_description', 'meta_keywords', 'is_featured', 'allow_comments'
            ];
            
            foreach ($allowedFields as $field) {
                if (isset($data[$field])) {
                    $updates[] = "$field = ?";
                    if ($field === 'body') {
                        $params[] = $this->sanitizeInput($data[$field], 'html');
                        // Recalculate reading time
                        $updates[] = "reading_time = ?";
                        $params[] = $this->calculateReadingTime($data[$field]);
                    } elseif ($field === 'slug') {
                        $params[] = $this->generateSlug($data[$field], $id);
                    } else {
                        $params[] = $data[$field];
                    }
                }
            }
            
            if (!empty($updates)) {
                $params[] = $id;
                $stmt = $this->pdo->prepare("
                    UPDATE blog_posts 
                    SET " . implode(', ', $updates) . ", updated_at = NOW()
                    WHERE id = ?
                ");
                $stmt->execute($params);
            }
            
            // Handle tags
            if (isset($data['tags'])) {
                $this->updateTags($id, $data['tags']);
            }
            
            // Create revision
            $this->createRevision(
                $id, 
                $currentPost['title'], 
                $currentPost['body'], 
                $data['author_id'], 
                $data['revision_message'] ?? 'Updated'
            );
            
            // Update category post counts if changed
            if (isset($data['category_id']) && $data['category_id'] != $currentPost['category_id']) {
                $this->updateCategoryPostCount($currentPost['category_id']);
                $this->updateCategoryPostCount($data['category_id']);
            }
            
            // Clear cache
            $this->cache->clear('blog_post_' . $id);
            $this->cache->clear('blog_posts_*');
            
            $this->pdo->commit();
            
            // Log activity
            $this->logActivity($data['author_id'], 'update_post', 'post', $id, ['title' => $data['title'] ?? $currentPost['title']]);
            
            return true;
            
        } catch (\Exception $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }
    
    public function delete($id, $adminId) {
        try {
            $post = $this->getById($id);
            if (!$post) {
                throw new \Exception('Post not found');
            }
            
            $stmt = $this->pdo->prepare("DELETE FROM blog_posts WHERE id = ?");
            $stmt->execute([$id]);
            
            // Update category post count
            if ($post['category_id']) {
                $this->updateCategoryPostCount($post['category_id']);
            }
            
            // Clear cache
            $this->cache->clear('blog_post_' . $id);
            $this->cache->clear('blog_posts_*');
            
            // Log activity
            $this->logActivity($adminId, 'delete_post', 'post', $id, ['title' => $post['title']]);
            
            return true;
            
        } catch (\Exception $e) {
            throw $e;
        }
    }
    
    public function getById($id, $incrementViews = false) {
        $cacheKey = 'blog_post_' . $id;
        
        if (!$incrementViews && $cached = $this->cache->get($cacheKey)) {
            return $cached;
        }
        
        $stmt = $this->pdo->prepare("
            SELECT p.*, c.name as category_name, c.slug as category_slug,
                   a.full_name as author_name, a.avatar as author_avatar, a.bio as author_bio
            FROM blog_posts p
            LEFT JOIN blog_categories c ON p.category_id = c.id
            LEFT JOIN blog_admins a ON p.author_id = a.id
            WHERE p.id = ?
        ");
        $stmt->execute([$id]);
        $post = $stmt->fetch(\PDO::FETCH_ASSOC);
        
        if ($post) {
            // Get tags
            $post['tags'] = $this->getPostTags($id);
            
            // Increment views if requested
            if ($incrementViews) {
                $this->incrementViews($id);
            }
            
            // Cache the result
            $this->cache->set($cacheKey, $post, $this->config['cache_ttl']);
        }
        
        return $post;
    }
    
    public function getBySlug($slug, $incrementViews = false) {
        $stmt = $this->pdo->prepare("SELECT id FROM blog_posts WHERE slug = ? AND status = 'published'");
        $stmt->execute([$slug]);
        $post = $stmt->fetch();
        
        if ($post) {
            return $this->getById($post['id'], $incrementViews);
        }
        
        return null;
    }
    
    public function getList($options = []) {
        $defaults = [
            'page' => 1,
            'per_page' => $this->config['posts_per_page'],
            'status' => 'published',
            'category_id' => null,
            'tag_id' => null,
            'author_id' => null,
            'search' => null,
            'order_by' => 'publish_date',
            'order_dir' => 'DESC',
            'is_featured' => null
        ];
        
        $options = array_merge($defaults, $options);
        $cacheKey = 'blog_posts_' . md5(serialize($options));
        
        if ($cached = $this->cache->get($cacheKey)) {
            return $cached;
        }
        
        $where = ['1=1'];
        $params = [];
        
        if ($options['status']) {
            $where[] = "p.status = ?";
            $params[] = $options['status'];
        }
        
        if ($options['category_id']) {
            $where[] = "p.category_id = ?";
            $params[] = $options['category_id'];
        }
        
        if ($options['author_id']) {
            $where[] = "p.author_id = ?";
            $params[] = $options['author_id'];
        }
        
        if ($options['is_featured'] !== null) {
            $where[] = "p.is_featured = ?";
            $params[] = $options['is_featured'];
        }
        
        if ($options['search']) {
            $where[] = "MATCH(p.title, p.body, p.excerpt) AGAINST(? IN BOOLEAN MODE)";
            $params[] = $options['search'];
        }
        
        if ($options['tag_id']) {
            $where[] = "EXISTS (SELECT 1 FROM blog_post_tags pt WHERE pt.post_id = p.id AND pt.tag_id = ?)";
            $params[] = $options['tag_id'];
        }
        
        // Count total
        $countSql = "SELECT COUNT(*) FROM blog_posts p WHERE " . implode(' AND ', $where);
        $stmt = $this->pdo->prepare($countSql);
        $stmt->execute($params);
        $total = $stmt->fetchColumn();
        
        // Get posts
        $offset = ($options['page'] - 1) * $options['per_page'];
        $sql = "
            SELECT p.*, c.name as category_name, c.slug as category_slug,
                   a.full_name as author_name, a.avatar as author_avatar
            FROM blog_posts p
            LEFT JOIN blog_categories c ON p.category_id = c.id
            LEFT JOIN blog_admins a ON p.author_id = a.id
            WHERE " . implode(' AND ', $where) . "
            ORDER BY p.{$options['order_by']} {$options['order_dir']}
            LIMIT {$offset}, {$options['per_page']}
        ";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        $posts = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        
        // Get tags for each post
        foreach ($posts as &$post) {
            $post['tags'] = $this->getPostTags($post['id']);
        }
        
        $result = [
            'posts' => $posts,
            'total' => $total,
            'page' => $options['page'],
            'per_page' => $options['per_page'],
            'total_pages' => ceil($total / $options['per_page'])
        ];
        
        // Cache the result
        $this->cache->set($cacheKey, $result, $this->config['cache_ttl']);
        
        return $result;
    }
    
    public function getRelated($postId, $limit = 3) {
        $post = $this->getById($postId);
        if (!$post) return [];
        
        $sql = "
            SELECT p.*, c.name as category_name, c.slug as category_slug
            FROM blog_posts p
            LEFT JOIN blog_categories c ON p.category_id = c.id
            WHERE p.id != ? 
            AND p.status = 'published'
            AND (p.category_id = ? OR EXISTS (
                SELECT 1 FROM blog_post_tags pt1
                JOIN blog_post_tags pt2 ON pt1.tag_id = pt2.tag_id
                WHERE pt1.post_id = ? AND pt2.post_id = p.id
            ))
            ORDER BY p.publish_date DESC
            LIMIT ?
        ";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$postId, $post['category_id'], $postId, $limit]);
        
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
    
    public function search($query, $page = 1, $perPage = 10) {
        return $this->getList([
            'search' => $query,
            'page' => $page,
            'per_page' => $perPage
        ]);
    }
    
    protected function attachTags($postId, $tags) {
        foreach ($tags as $tagName) {
            $tagId = $this->getOrCreateTag($tagName);
            
            $stmt = $this->pdo->prepare("
                INSERT IGNORE INTO blog_post_tags (post_id, tag_id) VALUES (?, ?)
            ");
            $stmt->execute([$postId, $tagId]);
        }
    }
    
    protected function updateTags($postId, $tags) {
        // Remove existing tags
        $stmt = $this->pdo->prepare("DELETE FROM blog_post_tags WHERE post_id = ?");
        $stmt->execute([$postId]);
        
        // Attach new tags
        $this->attachTags($postId, $tags);
    }
    
    protected function getPostTags($postId) {
        $stmt = $this->pdo->prepare("
            SELECT t.* FROM blog_tags t
            JOIN blog_post_tags pt ON t.id = pt.tag_id
            WHERE pt.post_id = ?
        ");
        $stmt->execute([$postId]);
        
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
    
    protected function getOrCreateTag($name) {
        $slug = $this->generateSlug($name);
        
        $stmt = $this->pdo->prepare("SELECT id FROM blog_tags WHERE slug = ?");
        $stmt->execute([$slug]);
        $tag = $stmt->fetch();
        
        if ($tag) {
            return $tag['id'];
        }
        
        $stmt = $this->pdo->prepare("INSERT INTO blog_tags (name, slug) VALUES (?, ?)");
        $stmt->execute([$name, $slug]);
        
        return $this->pdo->lastInsertId();
    }
    
    protected function createRevision($postId, $title, $body, $authorId, $message) {
        $stmt = $this->pdo->prepare("
            INSERT INTO blog_post_revisions 
            (post_id, title, body, author_id, revision_message) 
            VALUES (?, ?, ?, ?, ?)
        ");
        $stmt->execute([$postId, $title, $body, $authorId, $message]);
    }
    
    protected function updateCategoryPostCount($categoryId) {
        if (!$categoryId) return;
        
        $stmt = $this->pdo->prepare("
            UPDATE blog_categories 
            SET post_count = (
                SELECT COUNT(*) FROM blog_posts 
                WHERE category_id = ? AND status = 'published'
            )
            WHERE id = ?
        ");
        $stmt->execute([$categoryId, $categoryId]);
    }
    
    protected function incrementViews($postId) {
        $stmt = $this->pdo->prepare("UPDATE blog_posts SET views = views + 1 WHERE id = ?");
        $stmt->execute([$postId]);
    }
    
    protected function generateExcerpt($body, $length = null) {
        $length = $length ?: $this->config['excerpt_length'];
        $text = strip_tags($body);
        
        if (strlen($text) <= $length) {
            return $text;
        }
        
        $excerpt = substr($text, 0, $length);
        $lastSpace = strrpos($excerpt, ' ');
        
        if ($lastSpace !== false) {
            $excerpt = substr($excerpt, 0, $lastSpace);
        }
        
        return $excerpt . '...';
    }
    
    public function getPopular($limit = 5) {
        $stmt = $this->pdo->prepare("
            SELECT p.*, c.name as category_name, c.slug as category_slug
            FROM blog_posts p
            LEFT JOIN blog_categories c ON p.category_id = c.id
            WHERE p.status = 'published'
            ORDER BY p.views DESC
            LIMIT ?
        ");
        $stmt->execute([$limit]);
        
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
    
    public function getArchive() {
        $stmt = $this->pdo->query("
            SELECT 
                YEAR(publish_date) as year,
                MONTH(publish_date) as month,
                COUNT(*) as count
            FROM blog_posts
            WHERE status = 'published'
            GROUP BY YEAR(publish_date), MONTH(publish_date)
            ORDER BY year DESC, month DESC
        ");
        
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}