<?php
namespace Blog;

class BlogCore {
    protected $pdo;
    protected $config;
    protected $cache;
    
    public function __construct($pdo, $config = []) {
        $this->pdo = $pdo;
        $this->config = array_merge($this->getDefaultConfig(), $config);
        $this->cache = new BlogCache();
    }
    
    protected function getDefaultConfig() {
        return [
            'posts_per_page' => 12,
            'excerpt_length' => 200,
            'upload_path' => __DIR__ . '/../../uploads/blog/',
            'upload_url' => '/uploads/blog/',
            'allowed_image_types' => ['jpg', 'jpeg', 'png', 'gif', 'webp'],
            'max_upload_size' => 5 * 1024 * 1024, // 5MB
            'enable_cache' => true,
            'cache_ttl' => 3600,
            'enable_comments' => true,
            'moderate_comments' => true,
            'akismet_key' => null,
            'recaptcha_site_key' => null,
            'recaptcha_secret_key' => null
        ];
    }
    
    public function sanitizeInput($input, $type = 'string') {
        switch($type) {
            case 'int':
                return filter_var($input, FILTER_SANITIZE_NUMBER_INT);
            case 'email':
                return filter_var($input, FILTER_SANITIZE_EMAIL);
            case 'url':
                return filter_var($input, FILTER_SANITIZE_URL);
            case 'html':
                return $this->purifyHTML($input);
            default:
                return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
        }
    }
    
    protected function purifyHTML($html) {
        // Use HTMLPurifier library for safe HTML
        require_once __DIR__ . '/HTMLPurifier/HTMLPurifier.auto.php';
        $config = \HTMLPurifier_Config::createDefault();
        $config->set('HTML.Allowed', 'p,br,strong,em,u,h1,h2,h3,h4,h5,h6,ul,ol,li,blockquote,a[href|title],img[src|alt|title|width|height],pre,code');
        $purifier = new \HTMLPurifier($config);
        return $purifier->purify($html);
    }
    
    public function generateSlug($title, $id = null) {
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title), '-'));
        
        // Check for uniqueness
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM blog_posts WHERE slug = ? AND id != ?");
        $stmt->execute([$slug, $id ?: 0]);
        
        if ($stmt->fetchColumn() > 0) {
            $counter = 1;
            while (true) {
                $newSlug = $slug . '-' . $counter;
                $stmt->execute([$newSlug, $id ?: 0]);
                if ($stmt->fetchColumn() == 0) {
                    return $newSlug;
                }
                $counter++;
            }
        }
        
        return $slug;
    }
    
    public function calculateReadingTime($text) {
        $wordCount = str_word_count(strip_tags($text));
        $readingTime = ceil($wordCount / 200); // Average reading speed
        return max(1, $readingTime);
    }
    
    public function logActivity($adminId, $action, $entityType, $entityId, $details = []) {
        $stmt = $this->pdo->prepare("
            INSERT INTO blog_activity_log 
            (admin_id, action, entity_type, entity_id, ip_address, user_agent, details) 
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");
        
        $stmt->execute([
            $adminId,
            $action,
            $entityType,
            $entityId,
            $_SERVER['REMOTE_ADDR'] ?? null,
            $_SERVER['HTTP_USER_AGENT'] ?? null,
            json_encode($details)
        ]);
    }
}