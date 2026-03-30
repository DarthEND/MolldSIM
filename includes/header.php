<?php
/**
 * Shared header include.
 * Expects: $pageTitle (string), $basePath (string, '' for root, '../' for pages/)
 */
?>
<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle) ?></title>
    <link rel="stylesheet" href="<?= $basePath ?>css/styles.css">
    <link rel="stylesheet" href="<?= $basePath ?>style.php">
</head>
<body>
    <header>
        <div class="header-content">
            <div class="logo-container">
                <a href="<?= $basePath ?>index.php">
                    <div class="logo-text">MolldSIM</div>
                </a>
            </div>
            <nav>
                <a href="<?= $basePath ?>index.php">Acasă</a>
                <a href="<?= $basePath ?>pages/prepay.php">Prepay</a>
                <a href="<?= $basePath ?>pages/abonament.php">Abonament</a>
                <a href="<?= $basePath ?>pages/internet.php">Internet</a>
                <a href="<?= $basePath ?>pages/internet-tv.php">Internet + TV</a>
                <a href="<?= $basePath ?>pages/compare.php" style="color:#d81b60;">Compară</a>
            </nav>
        </div>
    </header>
