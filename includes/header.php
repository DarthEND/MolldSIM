<?php
    $pageDescription = $pageDescription ?? 'Compară ofertele de telefonie, internet și TV din Moldova într-un singur loc.';
    $currentPage = basename($_SERVER['SCRIPT_NAME'] ?? 'index.php');
    $currentPath = str_replace('\\', '/', $_SERVER['SCRIPT_NAME'] ?? '');

    function navClass(string $page, string $path = ''): string
    {
        global $currentPage, $currentPath;
        $active = $currentPage === $page && ($path === '' || str_contains($currentPath, $path));
        return $active ? ' class="is-active" aria-current="page"' : '';
    }
?>
<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?= htmlspecialchars($pageDescription, ENT_QUOTES) ?>">
    <meta name="theme-color" content="#6a1b9a">
    <meta property="og:type" content="website">
    <meta property="og:title" content="<?= htmlspecialchars($pageTitle, ENT_QUOTES) ?>">
    <meta property="og:description" content="<?= htmlspecialchars($pageDescription, ENT_QUOTES) ?>">
    <meta property="og:site_name" content="MolldSIM">
    <meta name="twitter:card" content="summary">
    <title><?= htmlspecialchars($pageTitle) ?></title>
    <link rel="icon" href="<?= $basePath ?>favicon.svg" type="image/svg+xml">
    <link rel="stylesheet" href="<?= $basePath ?>css/variables.css">
    <link rel="stylesheet" href="<?= $basePath ?>css/base.css">
    <link rel="stylesheet" href="<?= $basePath ?>css/layout.css">
    <link rel="stylesheet" href="<?= $basePath ?>css/components.css">
    <link rel="stylesheet" href="<?= $basePath ?>css/styles.css">
    <link rel="stylesheet" href="<?= $basePath ?>style.php">
</head>
<body>
    <a class="skip-link" href="#main-content">Sari la conținut</a>
    <header>
        <div class="header-content">
            <div class="logo-container">
                <a href="<?= $basePath ?>index.php">
                    <div class="logo-text">MolldSIM</div>
                </a>
            </div>
            <button
                class="menu-toggle"
                type="button"
                aria-expanded="false"
                aria-controls="site-navigation"
                aria-label="Deschide meniul"
            >
                <span class="menu-toggle-line"></span>
                <span class="menu-toggle-line"></span>
                <span class="menu-toggle-line"></span>
            </button>
            <nav class="site-nav" id="site-navigation" aria-label="Navigare principala">
                <a href="<?= $basePath ?>index.php"<?= navClass('index.php') ?>>Acasă</a>
                <a href="<?= $basePath ?>pages/prepay.php"<?= navClass('prepay.php', '/pages/') ?>>Prepay</a>
                <a href="<?= $basePath ?>pages/abonament.php"<?= navClass('abonament.php', '/pages/') ?>>Abonament</a>
                <a href="<?= $basePath ?>pages/internet.php"<?= navClass('internet.php', '/pages/') ?>>Internet</a>
                <a href="<?= $basePath ?>pages/internet-tv.php"<?= navClass('internet-tv.php', '/pages/') ?>>Internet + TV</a>
                <a href="<?= $basePath ?>pages/compare.php"<?= navClass('compare.php', '/pages/') ?>>Compară</a>
            </nav>
        </div>
    </header>
    <script src="<?= $basePath ?>script/menu.js" defer></script>
