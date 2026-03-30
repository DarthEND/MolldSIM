<?php
$pageTitle = 'Internet - MolldSIM';
$basePath  = '../';
require __DIR__ . '/../includes/header.php';
require __DIR__ . '/../includes/plan_card.php';

$plans = require __DIR__ . '/../data/internet_plans.php';

// Build dynamic filter values from actual plan data
$maxPrice = 0;
$maxSpeed = 0;
$operatorMap = []; // key => ['name' => ..., 'color' => ...]
foreach ($plans as $plan) {
    if ($plan['price']     > $maxPrice) $maxPrice = $plan['price'];
    if ($plan['speed_val'] > $maxSpeed) $maxSpeed = $plan['speed_val'];
    $k = $plan['operator_key'];
    if (!isset($operatorMap[$k])) {
        $operatorMap[$k] = ['name' => $plan['operator'], 'color' => $plan['operator_color']];
    }
}
// Round max price up to nearest 10, min sensible value
$maxPrice = max(350, (int)(ceil($maxPrice / 10) * 10));
// Round max speed up to nearest 100
$maxSpeed = max(2000, (int)(ceil($maxSpeed / 100) * 100));
?>
    <section class="internet-section">
        <div class="subsection">
            <div class="section-header" style="padding-top: calc(var(--header-height) + 1rem);">
                <p class="kicker">Internet</p>
                <h2 class="section-title">Stabil, rapid și la preț corect</h2>
                <p class="section-subtitle">Selectează furnizorul și vezi vitezele disponibile la adresa ta.</p>
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
                            <?php foreach ($operatorMap as $key => $op): ?>
                            <label class="operator-option">
                                <input type="checkbox" class="operator-checkbox" value="<?= htmlspecialchars($key) ?>" checked>
                                <span class="op-dot" style="background:<?= htmlspecialchars($op['color']) ?>;"></span>
                                <?= htmlspecialchars($op['name']) ?>
                            </label>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <div class="filter-group">
                        <label>Preț maxim: <span id="price-label"><?= $maxPrice ?> MDL</span></label>
                        <div class="range-wrapper">
                            <input type="range" id="filter-price" class="filter-range"
                                   min="0" max="<?= $maxPrice ?>" step="10" value="<?= $maxPrice ?>">
                            <div class="range-labels">
                                <span>0 MDL</span><span><?= $maxPrice ?> MDL</span>
                            </div>
                        </div>
                    </div>
                    <div class="filter-group">
                        <label>Viteză min: <span id="speed-label">0 Mbps</span></label>
                        <div class="range-wrapper">
                            <input type="range" id="filter-speed" class="filter-range"
                                   min="0" max="<?= $maxSpeed ?>" step="100" value="0">
                            <div class="range-labels">
                                <span>0 Mbps</span><span><?= $maxSpeed ?> Mbps</span>
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
