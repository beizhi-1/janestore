<?php
require_once __DIR__ . '/../auth.php';

$stmt = $pdo->query("SELECT apps.*, users.username FROM apps LEFT JOIN users ON apps.submitter_id = users.id WHERE status = 'approved' ORDER BY created_at DESC");
$apps = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>应用商店</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css" rel="stylesheet">
</head>
<body>
<nav>
    <div class="nav-wrapper container">
        <a href="/" class="brand-logo">应用商店</a>
        <ul id="nav-mobile" class="right hide-on-med-and-down">
            <?php if (current_user()) : ?>
                <li><a href="/submit.php">提交应用</a></li>
                <li><a href="/logout.php">退出</a></li>
                <?php if (current_user()['is_admin']) : ?>
                    <li><a href="/admin/dashboard.php">管理</a></li>
                <?php endif; ?>
            <?php else: ?>
                <li><a href="/login.php">登录</a></li>
                <li><a href="/register.php">注册</a></li>
            <?php endif; ?>
        </ul>
    </div>
</nav>
<div class="container">
    <h4>已审核应用</h4>
    <div class="row">
    <?php foreach ($apps as $app): ?>
        <div class="col s12 m6 l4">
            <div class="card small">
                <?php if (!empty($app['screenshot'])): ?>
                <div class="card-image">
                    <img src="<?php echo htmlspecialchars($app['screenshot']); ?>" alt="screenshot">
                </div>
                <?php endif; ?>
                <div class="card-content">
                    <span class="card-title"><?php echo htmlspecialchars($app['name']); ?></span>
                    <p><?php echo nl2br(htmlspecialchars($app['description'])); ?></p>
                </div>
                <div class="card-action">
                    <a href="<?php echo htmlspecialchars($app['url']); ?>" target="_blank">访问</a>
                    <a href="/app.php?id=<?php echo $app['id']; ?>">详情</a>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
    </div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
</body>
</html>
