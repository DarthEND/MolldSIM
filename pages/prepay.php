<?php
    $pageTitle = 'Prepay - MolldSIM';
    $basePath  = '../';
    require __DIR__ . '/../includes/header.php';
?>
    <main id="main-content">
    <section class="mobile-section" data-plans-page data-category="prepay" data-api="../api/plans.php">
        <div class="subsection">
            <div class="section-header" style="padding-top: calc(var(--header-height) + 1rem);">
                <p class="kicker">Telefonie mobilă Prepay</p>
                <h2 class="section-title">Alege operatorul, găsește planul</h2>
                <p class="section-subtitle">Compară traficul de date, minutele și beneficiile incluse.</p>
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
                            <option value="data-desc">Date (mai mult)</option>
                        </select>
                    </div>
                    <div class="filter-group">
                        <label>Operator</label>
                        <div class="operator-options" id="operator-options"></div>
                    </div>
                    <div class="filter-group">
                        <label>Preț maxim: <span id="price-label">0 MDL</span></label>
                        <div class="range-wrapper">
                            <input type="range" id="filter-price" class="filter-range"
                                   min="0" max="0" step="5" value="0">
                            <div class="range-labels">
                                <span>0 MDL</span><span id="price-range-max">0 MDL</span>
                            </div>
                        </div>
                    </div>
                    <div class="filter-group">
                        <label>Date min: <span id="data-label">0 GB</span></label>
                        <div class="range-wrapper">
                            <input type="range" id="filter-data" class="filter-range"
                                   min="0" max="100" step="5" value="0">
                            <div class="range-labels">
                                <span>0 GB</span><span>100 GB</span>
                            </div>
                        </div>
                    </div>
                    <div class="filter-group">
                        <label>Minute min: <span id="minutes-label">0 min</span></label>
                        <div class="range-wrapper">
                            <input type="range" id="filter-minutes" class="filter-range" min="0" max="0" step="100" value="0">
                            <div class="range-labels">
                                <span>0 min</span><span>0 min</span>
                            </div>
                        </div>
                    </div>
                    <div class="filter-group">
                        <label>SMS min: <span id="sms-label">0 SMS</span></label>
                        <div class="range-wrapper">
                            <input type="range" id="filter-sms" class="filter-range" min="0" max="0" step="100" value="0">
                            <div class="range-labels">
                                <span>0 SMS</span><span>0 SMS</span>
                            </div>
                        </div>
                    </div>
                    <div class="filter-group">
                        <label>Roaming min: <span id="roaming-label">0 GB</span></label>
                        <div class="range-wrapper">
                            <input type="range" id="filter-roaming" class="filter-range" min="0" max="0" step="0.5" value="0">
                            <div class="range-labels">
                                <span>0 GB</span><span>0 GB</span>
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
