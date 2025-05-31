<?php
require_once __DIR__ . '/../auth.php';
$id = (int)($_GET['id'] ?? 0);
$stmt = $pdo->prepare("SELECT apps.*, users.username FROM apps LEFT JOIN users ON apps.submitter_id = users.id WHERE apps.id = ? AND status = 'approved'");
$stmt->execute([$id]);
$app = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$app) {
    http_response_code(404);
    echo '未找到应用';
    exit;
}
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title><?php echo htmlspecialchars($app['name']); ?></title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css" rel="stylesheet">
</head>
<body class="container">
    <h3><?php echo htmlspecialchars($app['name']); ?></h3>
    <?php if (!empty($app['screenshot'])): ?>
    <img src="<?php echo htmlspecialchars($app['screenshot']); ?>" style="max-width:100%" alt="screenshot" class="responsive-img">
    <?php endif; ?>
    <p><?php echo nl2br(htmlspecialchars($app['description'])); ?></p>
    <p>发布者：<?php echo htmlspecialchars($app['username']); ?></p>
    <a href="<?php echo htmlspecialchars($app['url']); ?>" class="btn" target="_blank">访问</a>
    <a href="/" class="btn grey">返回</a>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
</body>
</html>
