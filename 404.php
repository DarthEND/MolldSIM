<?php
    http_response_code(404);
    $pageTitle = 'Pagina nu a fost găsită - MolldSIM';
    $pageDescription = 'Pagina solicitată nu există sau a fost mutată.';
    $basePath = '';
    require __DIR__ . '/includes/header.php';
?>
<main id="main-content" class="error-page">
    <div class="error-card">
        <span class="error-code">404</span>
        <p class="kicker">Pagină indisponibilă</p>
        <h1>Ai ajuns la o adresă care nu există.</h1>
        <p>
            Linkul poate fi vechi sau adresa a fost introdusă greșit. Revino la
            pagina principală sau continuă direct cu planurile.
        </p>
        <div class="error-actions">
            <a href="index.php" class="btn-primary btn-large">Pagina principală</a>
            <a href="pages/prepay.php" class="btn-outline btn-large">Explorează planurile</a>
        </div>
    </div>
</main>
<?php require __DIR__ . '/includes/footer.php'; ?>
</body>
</html>
