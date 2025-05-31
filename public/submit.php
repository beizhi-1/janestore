<?php
require_once __DIR__ . '/../auth.php';
require_login();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $url = trim($_POST['url'] ?? '');
    $screenshot = trim($_POST['screenshot'] ?? '');
    if ($name && $url) {
        $stmt = $pdo->prepare('INSERT INTO apps (name, description, url, screenshot, submitter_id) VALUES (?, ?, ?, ?, ?)');
        $stmt->execute([$name, $description, $url, $screenshot, current_user()['id']]);
        $success = true;
    } else {
        $error = '名称和链接不能为空';
    }
}
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>提交应用</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css" rel="stylesheet">
</head>
<body class="container">
<h3>提交应用</h3>
<?php if (!empty($success)) : ?>
<p class="green-text">应用已提交，等待审核。</p>
<?php endif; ?>
<form method="post">
    <div class="input-field">
        <input type="text" name="name" id="name" required>
        <label for="name">应用名称</label>
    </div>
    <div class="input-field">
        <textarea name="description" id="description" class="materialize-textarea"></textarea>
        <label for="description">应用描述</label>
    </div>
    <div class="input-field">
        <input type="url" name="url" id="url" required>
        <label for="url">链接</label>
    </div>
    <div class="input-field">
        <input type="url" name="screenshot" id="screenshot">
        <label for="screenshot">截图地址(可选)</label>
    </div>
    <button class="btn waves-effect" type="submit">提交</button>
</form>
<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
<?php if (!empty($error)) : ?>
<script>M.toast({html: '<?php echo $error; ?>', classes: 'red'});</script>
<?php elseif (!empty($success)) : ?>
<script>M.toast({html: '提交成功，等待审核', classes: 'green'});</script>
<?php endif; ?>
</body>
</html>
