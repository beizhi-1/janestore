<?php
// db.php - database connection and initialization

$dbFile = __DIR__ . '/data/app_store.db';
$initNeeded = !file_exists($dbFile);

try {
    $pdo = new PDO('sqlite:' . $dbFile);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (Exception $e) {
    die('Database connection failed: ' . $e->getMessage());
}

// ensure screenshot column exists
try {
    $cols = $pdo->query("PRAGMA table_info(apps)")->fetchAll(PDO::FETCH_COLUMN, 1);
    if ($cols && !in_array('screenshot', $cols)) {
        $pdo->exec("ALTER TABLE apps ADD COLUMN screenshot TEXT");
    }
} catch (Exception $e) {
    // table may not exist yet, handled below
}

if ($initNeeded) {
    $pdo->exec("CREATE TABLE IF NOT EXISTS users (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        username TEXT UNIQUE,
        password_hash TEXT,
        is_admin INTEGER DEFAULT 0
    )");

    $pdo->exec("CREATE TABLE IF NOT EXISTS apps (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        name TEXT,
        description TEXT,
        url TEXT,
        screenshot TEXT,
        status TEXT DEFAULT 'pending',
        submitter_id INTEGER,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    )");

    // create default admin account: admin/admin
    $hash = password_hash('admin', PASSWORD_DEFAULT);
    $stmt = $pdo->prepare('INSERT INTO users (username, password_hash, is_admin) VALUES (?, ?, 1)');
    $stmt->execute(['admin', $hash]);
}
?>
