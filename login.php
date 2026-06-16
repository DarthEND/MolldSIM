<?php

session_start();

if (!empty($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit;
}

require __DIR__ . '/db/connection.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim((string) ($_POST['username'] ?? ''));
    $password = (string) ($_POST['password'] ?? '');

    if ($username === '' || $password === '') {
        $error = 'Completați toate câmpurile.';
    } else {
        try {
            $stmt = db()->prepare('SELECT id, username, password_hash FROM users WHERE username = ? LIMIT 1');
            $stmt->execute([$username]);
            $user = $stmt->fetch();

            if ($user && password_verify($password, $user['password_hash'])) {
                session_regenerate_id(true);
                $_SESSION['user_id']  = $user['id'];
                $_SESSION['username'] = $user['username'];
                header('Location: dashboard.php');
                exit;
            } else {
                $error = 'Utilizator sau parolă incorectă.';
            }
        } catch (PDOException $e) {
            $error = 'Eroare la conectarea cu baza de date. Verificați configurația .env.';
        }
    }
}

function h(string $v): string
{
    return htmlspecialchars($v, ENT_QUOTES, 'UTF-8');
}
?>
<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Autentificare — MolldSIM</title>
    <link rel="stylesheet" href="css/variables.css">
    <link rel="stylesheet" href="css/login.css">
</head>
<body>
<div class="login-card">
    <div class="login-logo">
        MolldSIM
        <span>Panou de administrare</span>
    </div>

    <div class="demo-banner">
        <strong>Cont demo pentru prezentare</strong>
        Utilizator: <span class="demo-cred">demo</span>
        &nbsp;&nbsp;
        Parolă: <span class="demo-cred">demo123</span>
    </div>

    <?php if ($error): ?>
        <div class="error-msg"><?= h($error) ?></div>
    <?php endif; ?>

    <form method="POST" autocomplete="on">
        <div class="field">
            <label for="username">Utilizator</label>
            <input type="text" id="username" name="username"
                   value="<?= h((string) ($_POST['username'] ?? '')) ?>"
                   autocomplete="username" autofocus required>
        </div>
        <div class="field">
            <label for="password">Parolă</label>
            <input type="password" id="password" name="password"
                   autocomplete="current-password" required>
        </div>
        <button type="submit" class="btn-login">Autentificare</button>
    </form>

    <div class="back-link"><a href="index.php">&#8592; Înapoi la site</a></div>
</div>
</body>
</html>
