<?php
session_start();
require_once __DIR__ . '/../../db.php';
require_once __DIR__ . '/../classes/BlogAuth.php';
require_once __DIR__ . '/../classes/BlogPost.php';
require_once __DIR__ . '/../classes/BlogMedia.php';
require_once __DIR__ . '/../classes/BlogStats.php';

use Blog\BlogAuth;
use Blog\BlogPost;
use Blog\BlogMedia;
use Blog\BlogStats;

$auth = new BlogAuth($pdo);
$user = $auth->requireAuth();

$postManager = new BlogPost($pdo);
$stats = new BlogStats($pdo);

// Get dashboard data
$dashboardData = [
    'total_posts' => $stats->getTotalPosts(),
    'total_views' => $stats->getTotalViews(),
    'total_comments' => $stats->getTotalComments(),
    'pending_comments' => $stats->getPendingComments(),
    'recent_posts' => $postManager->getList(['per_page' => 5, 'status' => null]),
    'popular_posts' => $postManager->getPopular(5),
    'recent_activity' => $stats->getRecentActivity(10)
];

$page_title = 'Dashboard | Blog Admin';
include '../includes/admin-header.php';
?>

<style>
.admin-dashboard {
    max-width: 1400px;
    margin: 0 auto;
    padding: 20px;
}

.dashboard-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 30px;
    padding: 20px;
    background: white;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
}

.dashboard-stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
    margin-bottom: 30px;
}

.stat-card {
    background: white;
    padding: 25px;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 5px 20px rgba(0,0,0,0.1);
}

.stat-card .stat-icon {
    width: 50px;
    height: 50px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 15px;
    font-size: 24px;
}

.stat-card.posts .stat-icon {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.stat-card.views .stat-icon {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    color: white;
}

.stat-card.comments .stat-icon {
    background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    color: white;
}

.stat-card.pending .stat-icon {
    background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
    color: white;
}

.stat-card h3 {
    font-size: 32px;
    margin: 0;
    color: #2c3e50;
}

.stat-card p {
    margin: 5px 0 0;
    color: #7f8c8d;
}

.dashboard-grid {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 30px;
}

.dashboard-section {
    background: white;
    border-radius: 10px;
    padding: 25px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
}

.section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
    padding-bottom: 15px;
    border-bottom: 2px solid #f0f0f0;
}

.section-header h2 {
    margin: 0;
    color: #2c3e50;
    font-size: 20px;
}

.posts-table {
    width: 100%;
    border-collapse: collapse;
}

.posts-table th {
    text-align: left;
    padding: 12px;
    background: #f8f9fa;
    color: #6c757d;
    font-weight: 600;
    font-size: 14px;
    text-transform: uppercase;
}

.posts-table td {
    padding: 12px;
    border-bottom: 1px solid #f0f0f0;
}

.post-title {
    font-weight: 600;
    color: #2c3e50;
}

.post-meta {
    font-size: 13px;
    color: #6c757d;
    margin-top: 5px;
}

.status-badge {
    display: inline-block;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
}

.status-badge.published {
    background: #d4edda;
    color: #155724;
}

.status-badge.draft {
    background: #fff3cd;
    color: #856404;
}

.status-badge.scheduled {
    background: #d1ecf1;
    color: #0c5460;
}

.activity-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.activity-item {
    padding: 12px 0;
    border-bottom: 1px solid #f0f0f0;
    display: flex;
    align-items: start;
    gap: 12px;
}

.activity-icon {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.activity-icon.create {
    background: #d4edda;
    color: #155724;
}

.activity-icon.update {
    background: #cce5ff;
    color: #004085;
}

.activity-icon.delete {
    background: #f8d7da;
    color: #721c24;
}

.activity-content {
    flex: 1;
}

.activity-title {
    font-weight: 600;
    color: #2c3e50;
    margin-bottom: 3px;
}

.activity-time {
    font-size: 12px;
    color: #6c757d;
}

.quick-actions {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 10px;
    margin-top: 20px;
}

.quick-action-btn {
    padding: 12px;
    border: 2px solid #e0e0e0;
    background: white;
    border-radius: 8px;
    text-align: center;
    text-decoration: none;
    color: #2c3e50;
    font-weight: 600;
    transition: all 0.3s ease;
}

.quick-action-btn:hover {
    border-color: #d32f2f;
    color: #d32f2f;
    transform: translateY(-2px);
}

@media (max-width: 1024px) {
    .dashboard-grid {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 768px) {
    .dashboard-stats {
        grid-template-columns: 1fr;
    }
    
    .dashboard-header {
        flex-direction: column;
        gap: 15px;
        text-align: center;
    }
    
    .quick-actions {
        grid-template-columns: 1fr;
    }
}
</style>

<div class="admin-dashboard">
    <div class="dashboard-header">
        <div>
            <h1>Dashboard</h1>
            <p>Welcome back, <?php echo htmlspecialchars($user['full_name'] ?: $user['username']); ?>!</p>
        </div>
        <div>
            <a href="/blog/admin/posts/new" class="btn btn-primary">
                <i class="fas fa-plus"></i> New Post
            </a>
        </div>
    </div>
    
    <div class="dashboard-stats">
        <div class="stat-card posts">
            <div class="stat-icon">
                <i class="fas fa-file-alt"></i>
            </div>
            <h3><?php echo number_format($dashboardData['total_posts']); ?></h3>
            <p>Total Posts</p>
        </div>
        
        <div class="stat-card views">
            <div class="stat-icon">
                <i class="fas fa-eye"></i>
            </div>
            <h3><?php echo number_format($dashboardData['total_views']); ?></h3>
            <p>Total Views</p>
        </div>
        
        <div class="stat-card comments">
            <div class="stat-icon">
                <i class="fas fa-comments"></i>
            </div>
            <h3><?php echo number_format($dashboardData['total_comments']); ?></h3>
            <p>Total Comments</p>
        </div>
        
        <div class="stat-card pending">
            <div class="stat-icon">
                <i class="fas fa-clock"></i>
            </div>
            <h3><?php echo number_format($dashboardData['pending_comments']); ?></h3>
            <p>Pending Comments</p>
        </div>
    </div>
    
    <div class="dashboard-grid">
        <div class="dashboard-section">
            <div class="section-header">
                <h2>Recent Posts</h2>
                <a href="/blog/admin/posts" class="text-link">View All</a>
            </div>
            
            <?php if (!empty($dashboardData['recent_posts']['posts'])): ?>
                <table class="posts-table">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Status</th>
                            <th>Views</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($dashboardData['recent_posts']['posts'] as $post): ?>
                            <tr>
                                <td>
                                    <div class="post-title">
                                        <?php echo htmlspecialchars($post['title']); ?>
                                    </div>
                                    <div class="post-meta">
                                        <?php echo date('M d, Y', strtotime($post['created_at'])); ?>
                                    </div>
                                </td>
                                <td>
                                    <span class="status-badge <?php echo $post['status']; ?>">
                                        <?php echo ucfirst($post['status']); ?>
                                    </span>
                                </td>
                                <td><?php echo number_format($post['views']); ?></td>
                                <td>
                                    <a href="/blog/admin/posts/edit?id=<?php echo $post['id']; ?>" 
                                       class="text-link">Edit</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p class="text-muted">No posts yet. Create your first post!</p>
            <?php endif; ?>
        </div>
        
        <div>
            <div class="dashboard-section">
                <div class="section-header">
                    <h2>Recent Activity</h2>
                </div>
                
                <?php if (!empty($dashboardData['recent_activity'])): ?>
                    <ul class="activity-list">
                        <?php foreach ($dashboardData['recent_activity'] as $activity): ?>
                            <li class="activity-item">
                                <div class="activity-icon <?php echo $activity['action']; ?>">
                                    <i class="fas fa-<?php 
                                        echo $activity['action'] === 'create' ? 'plus' : 
                                            ($activity['action'] === 'update' ? 'edit' : 'trash');
                                    ?>"></i>
                                </div>
                                <div class="activity-content">
                                    <div class="activity-title">
                                        <?php echo htmlspecialchars($activity['description']); ?>
                                    </div>
                                    <div class="activity-time">
                                        <?php echo $activity['time_ago']; ?>
                                    </div>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p class="text-muted">No recent activity</p>
                <?php endif; ?>
            </div>
            
            <div class="dashboard-section" style="margin-top: 20px;">
                <div class="section-header">
                    <h2>Quick Actions</h2>
                </div>
                
                <div class="quick-actions">
                    <a href="/blog/admin/posts/new" class="quick-action-btn">
                        <i class="fas fa-plus"></i> New Post
                    </a>
                    <a href="/blog/admin/media" class="quick-action-btn">
                        <i class="fas fa-image"></i> Media
                    </a>
                    <a href="/blog/admin/comments" class="quick-action-btn">
                        <i class="fas fa-comments"></i> Comments
                    </a>
                    <a href="/blog/admin/settings" class="quick-action-btn">
                        <i class="fas fa-cog"></i> Settings
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/admin-footer.php'; ?>