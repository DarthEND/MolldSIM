<?php
$pageTitle = 'Internet + TV - MolldSIM';
$basePath  = '../';
require __DIR__ . '/../includes/header.php';
require __DIR__ . '/../includes/plan_card.php';

$plans = require __DIR__ . '/../data/internet_tv_plans.php';
?>
    <section class="internet-section">
        <div class="subsection">
            <div class="section-header" style="padding-top: calc(var(--header-height) + 1rem);">
                <p class="kicker">Internet + TV</p>
                <h2 class="section-title">Internet rapid și TV pentru toată familia</h2>
                <p class="section-subtitle">Compară pachetele combinate și alege cel mai bun raport calitate/preț.</p>
            </div>
            <div class="page-layout">
                <aside class="filter-aside" id="filter-aside">
                    <h3>Filtrează</h3>
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
                        <div class="operator-options">
                            <label class="operator-option">
                                <input type="checkbox" class="operator-checkbox" value="starnet" checked>
                                <span class="op-dot" style="background:#ff6600;"></span>
                                StarNet
                            </label>
                            <label class="operator-option">
                                <input type="checkbox" class="operator-checkbox" value="moldtelecom" checked>
                                <span class="op-dot" style="background:#004b93;"></span>
                                Moldtelecom
                            </label>
                            <label class="operator-option">
                                <input type="checkbox" class="operator-checkbox" value="orange" checked>
                                <span class="op-dot" style="background:#ff7900;"></span>
                                Orange
                            </label>
                        </div>
                    </div>
                    <div class="filter-group">
                        <label>Preț maxim: <span id="price-label">600 MDL</span></label>
                        <div class="range-wrapper">
                            <input type="range" id="filter-price" class="filter-range"
                                   min="150" max="600" step="10" value="600">
                            <div class="range-labels">
                                <span>150 MDL</span><span>600 MDL</span>
                            </div>
                        </div>
                    </div>
                    <div class="filter-group">
                        <label>Viteză min: <span id="speed-label">0 Mbps</span></label>
                        <div class="range-wrapper">
                            <input type="range" id="filter-speed" class="filter-range"
                                   min="0" max="2000" step="100" value="0">
                            <div class="range-labels">
                                <span>0 Mbps</span><span>2000+ Mbps</span>
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
                        <?php foreach ($plans as $plan): ?>
                        <?= renderPlanCard($plan) ?>
                        <?php endforeach; ?>
                        <div class="no-results" id="no-results">
                            Niciun plan nu corespunde filtrelor selectate.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

<?php require __DIR__ . '/../includes/footer.php'; ?>
    <script src="../script/filter.js"></script>
    <script src="../script/compare.js?v=2"></script>
</body>
</html>
