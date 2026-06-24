<?php
$pageTitle = 'Compară planuri - MolldSIM';
$pageDescription = 'Compară până la patru planuri telecom și evidențiază rapid diferențele de preț, date și viteză.';
$basePath  = '../';
require __DIR__ . '/../includes/header.php';
?>
    <main id="main-content">
    <section class="compare-page">
        <div class="container">
            <div class="section-header">
                <p class="kicker">Comparare planuri</p>
                <h2 class="section-title">Alege cel mai bun plan pentru tine</h2>
                <p class="section-subtitle">
                    Poți compara până la 4 planuri simultan. Valorile marcate cu
                    <span style="color:#16a34a;font-weight:700;">verde ✓</span>
                    reprezintă cel mai bun raport din selecția ta.
                </p>
            </div>

            <!-- JS renders the comparison table here -->
            <div id="compare-root"></div>
        </div>
    </section>
    </main>

<?php require __DIR__ . '/../includes/footer.php'; ?>
    <script src="../script/compare.js?v=2"></script>
</body>
</html>
