<?php
require_once __DIR__ . '/../auth.php';
require_admin();

// 处理审核操作
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $appId = (int)($_POST['app_id'] ?? 0);
    $action = $_POST['action'] ?? '';
    if ($appId && in_array($action, ['approve','reject'])) {
        $stmt = $pdo->prepare('UPDATE apps SET status = ? WHERE id = ?');
        $stmt->execute([$action === 'approve' ? 'approved' : 'rejected', $appId]);
    }
}

$stmt = $pdo->query("SELECT apps.*, users.username FROM apps LEFT JOIN users ON apps.submitter_id = users.id ORDER BY created_at DESC");
$apps = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>后台管理</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css" rel="stylesheet">
</head>
<body>
<nav>
    <div class="nav-wrapper container">
        <a href="/admin/dashboard.php" class="brand-logo">后台管理</a>
        <ul id="nav-mobile" class="right hide-on-med-and-down">
            <li><a href="/">首页</a></li>
            <li><a href="/logout.php">退出</a></li>
        </ul>
    </div>
</nav>
<div class="container">
    <h4>待审核应用</h4>
    <table class="highlight responsive-table">
        <thead>
            <tr><th>名称</th><th>描述</th><th>链接</th><th>截图</th><th>提交者</th><th>状态</th><th>操作</th></tr>
        </thead>
        <tbody>
        <?php foreach ($apps as $app): ?>
            <tr>
                <td><?php echo htmlspecialchars($app['name']); ?></td>
                <td><?php echo htmlspecialchars($app['description']); ?></td>
                <td><a href="<?php echo htmlspecialchars($app['url']); ?>" target="_blank">链接</a></td>
                <td>
                    <?php if ($app['screenshot']): ?>
                    <img src="<?php echo htmlspecialchars($app['screenshot']); ?>" alt="s" style="height:50px;width:auto">
                    <?php endif; ?>
                </td>
                <td><?php echo htmlspecialchars($app['username']); ?></td>
                <td><?php echo htmlspecialchars($app['status']); ?></td>
                <td>
                    <?php if ($app['status'] === 'pending'): ?>
                        <form method="post" style="display:inline">
                            <input type="hidden" name="app_id" value="<?php echo $app['id']; ?>">
                            <button class="btn green" name="action" value="approve" onclick="return confirm('确认通过?');">通过</button>
                        </form>
                        <form method="post" style="display:inline">
                            <input type="hidden" name="app_id" value="<?php echo $app['id']; ?>">
                            <button class="btn red" name="action" value="reject" onclick="return confirm('确认拒绝?');">拒绝</button>
                        </form>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
<?php if ($_SERVER['REQUEST_METHOD'] === 'POST') : ?>
<script>M.toast({html: '状态已更新'});</script>
<?php endif; ?>
</body>
</html>
