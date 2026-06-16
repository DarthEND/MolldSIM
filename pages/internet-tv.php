<?php
$pageTitle = 'Internet + TV - MolldSIM';
$basePath  = '../';
require __DIR__ . '/../includes/header.php';
?>
    <main id="main-content">
    <section class="internet-section" data-plans-page data-category="internet_tv" data-api="../api/plans.php">
        <div class="subsection">
            <div class="section-header" style="padding-top: calc(var(--header-height) + 1rem);">
                <p class="kicker">Internet + TV</p>
                <h2 class="section-title">Internet rapid și TV pentru toată familia</h2>
                <p class="section-subtitle">Compară pachetele combinate și alege cel mai bun raport calitate/preț.</p>
            </div>
            <div class="catalog-toolbar">
                <button class="filter-drawer-toggle" type="button" aria-controls="filter-aside" aria-expanded="false">
                    Filtre și sortare <span class="filter-active-count" hidden></span>
                </button>
                <span class="catalog-status" id="catalog-status" role="status" aria-live="polite"></span>
            </div>
            <div class="page-layout">
                <aside class="filter-aside" id="filter-aside" aria-label="Filtre pentru planuri">
                    <div class="filter-heading">
                        <h3>Filtrează</h3>
                        <button class="filter-drawer-close" type="button" aria-label="Închide filtrele">×</button>
                    </div>
                    <div class="filter-group">
                        <label for="filter-sort">Sortare</label>
                        <select id="filter-sort" class="filter-select">
                            <option value="default">Implicit</option>
                            <option value="price-asc">Preț crescător</option>
                            <option value="price-desc">Preț descrescător</option>
                            <option value="speed-desc">Viteză (mai mare)</option>
                        </select>
                    </div>
                    <div class="filter-group">
                        <label>Furnizor</label>
                        <div class="operator-options" id="operator-options"></div>
                    </div>
                    <div class="filter-group">
                        <label>Preț maxim: <span id="price-label">0 MDL</span></label>
                        <div class="range-wrapper">
                            <input type="range" id="filter-price" class="filter-range"
                                   min="0" max="0" step="10" value="0">
                            <div class="range-labels">
                                <span>0 MDL</span><span>0 MDL</span>
                            </div>
                        </div>
                    </div>
                    <div class="filter-group">
                        <label>Viteză min: <span id="speed-label">0 Mbps</span></label>
                        <div class="range-wrapper">
                            <input type="range" id="filter-speed" class="filter-range"
                                   min="0" max="0" step="100" value="0">
                            <div class="range-labels">
                                <span>0 Mbps</span><span>0 Mbps</span>
                            </div>
                        </div>
                    </div>
                    <div class="filter-group">
                        <label>Upload min: <span id="upload-label">0 Mbps</span></label>
                        <div class="range-wrapper">
                            <input type="range" id="filter-upload" class="filter-range" min="0" max="0" step="100" value="0">
                            <div class="range-labels">
                                <span>0 Mbps</span><span>0 Mbps</span>
                            </div>
                        </div>
                    </div>
                    <div class="filter-group">
                        <label>Canale TV min: <span id="tv-channels-label">0 canale</span></label>
                        <div class="range-wrapper">
                            <input type="range" id="filter-tv-channels" class="filter-range" min="0" max="0" step="10" value="0">
                            <div class="range-labels">
                                <span>0 canale</span><span>0 canale</span>
                            </div>
                        </div>
                    </div>
                    <div class="filter-group">
                        <label>Canale HD min: <span id="hd-channels-label">0 HD</span></label>
                        <div class="range-wrapper">
                            <input type="range" id="filter-hd-channels" class="filter-range" min="0" max="0" step="10" value="0">
                            <div class="range-labels">
                                <span>0 HD</span><span>0 HD</span>
                            </div>
                        </div>
                    </div>
                    <div class="filter-footer">
                        <span class="filter-count" id="filter-count"></span>
                        <button id="filter-reset" class="filter-reset-btn">Resetează filtrele</button>
                    </div>
                </aside>
                <div class="plans-content">
                    <div class="plans-grid-all" id="plans-grid">
                        <div class="no-results" id="no-results">
                            Niciun plan nu corespunde filtrelor selectate.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    </main>
    <div class="filter-backdrop" hidden></div>

<?php require __DIR__ . '/../includes/footer.php'; ?>
    <script src="../script/recommendations.js"></script>
    <script src="../script/filter.js"></script>
    <script src="../script/compare.js?v=2"></script>
</body>
</html>
