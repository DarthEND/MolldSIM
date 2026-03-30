<?php
$pageTitle = 'Prepay - MolldSIM';
$basePath  = '../';
require __DIR__ . '/../includes/header.php';
require __DIR__ . '/../includes/plan_card.php';

$plans = require __DIR__ . '/../data/prepay_plans.php';
?>
    <section class="mobile-section">
        <div class="subsection">
            <div class="section-header" style="padding-top: calc(var(--header-height) + 1rem);">
                <p class="kicker">Telefonie mobilă Prepay</p>
                <h2 class="section-title">Alege operatorul, găsește planul</h2>
                <p class="section-subtitle">Compară traficul de date, minutele și beneficiile incluse.</p>
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
                            <option value="data-desc">Date (mai mult)</option>
                        </select>
                    </div>
                    <div class="filter-group">
                        <label>Operator</label>
                        <div class="operator-options">
                            <label class="operator-option">
                                <input type="checkbox" class="operator-checkbox" value="orange" checked>
                                <span class="op-dot" style="background:#ff7900;"></span>
                                Orange
                            </label>
                            <label class="operator-option">
                                <input type="checkbox" class="operator-checkbox" value="moldcell" checked>
                                <span class="op-dot" style="background:#6a1b9a;"></span>
                                Moldcell
                            </label>
                            <label class="operator-option">
                                <input type="checkbox" class="operator-checkbox" value="moldtelecom" checked>
                                <span class="op-dot" style="background:#004b93;"></span>
                                Moldtelecom
                            </label>
                        </div>
                    </div>
                    <div class="filter-group">
                        <label>Preț maxim: <span id="price-label">180 MDL</span></label>
                        <div class="range-wrapper">
                            <input type="range" id="filter-price" class="filter-range"
                                   min="25" max="180" step="5" value="180">
                            <div class="range-labels">
                                <span>25 MDL</span><span>180 MDL</span>
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
