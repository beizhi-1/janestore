<?php
require_once __DIR__ . '/../auth.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    if ($username && $password) {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        try {
            $stmt = $pdo->prepare('INSERT INTO users (username, password_hash) VALUES (?, ?)');
            $stmt->execute([$username, $hash]);
            $success = true;
        } catch (PDOException $e) {
            $error = '用户名已存在';
        }
    } else {
        $error = '用户名和密码不能为空';
    }
}
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>注册</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css" rel="stylesheet">
</head>
<body class="container">
<h3>注册</h3>
<?php if (!empty($success)) : ?>
<p class="green-text">注册成功，<a href="/login.php">立即登录</a></p>
<?php endif; ?>
<form method="post">
    <div class="input-field">
        <input type="text" name="username" id="username" required>
        <label for="username">用户名</label>
    </div>
    <div class="input-field">
        <input type="password" name="password" id="password" required>
        <label for="password">密码</label>
    </div>
    <button class="btn waves-effect" type="submit">注册</button>
</form>
<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
<?php if (!empty($error)) : ?>
<script>M.toast({html: '<?php echo $error; ?>', classes: 'red'});</script>
<?php endif; ?>
</body>
</html>
