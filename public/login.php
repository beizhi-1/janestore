<?php
require_once __DIR__ . '/../auth.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    $stmt = $pdo->prepare('SELECT * FROM users WHERE username = ?');
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($user && password_verify($password, $user['password_hash'])) {
        $_SESSION['user'] = ['id' => $user['id'], 'username' => $user['username'], 'is_admin' => $user['is_admin']];
        if ($user['is_admin']) {
            header('Location: /admin/dashboard.php');
        } else {
            header('Location: /');
        }
        exit;
    }
    $error = '用户名或密码错误';
}
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>登录</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css" rel="stylesheet">
</head>
<body class="container">
<h3>登录</h3>
<form method="post">
    <div class="input-field">
        <input type="text" name="username" id="username" required>
        <label for="username">用户名</label>
    </div>
    <div class="input-field">
        <input type="password" name="password" id="password" required>
        <label for="password">密码</label>
    </div>
    <button class="btn waves-effect" type="submit">登录</button>
</form>
<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
<?php if (!empty($error)) : ?>
<script>M.toast({html: '<?php echo $error; ?>', classes: 'red'});</script>
<?php endif; ?>
</body>
</html>
