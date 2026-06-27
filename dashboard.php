<?php
require __DIR__ . '/includes/plan_helpers.php';
require __DIR__ . '/includes/auth.php';
requireAuth('login.php');

$dataFiles = planDataFiles();
$categoryLabels = planCategoryLabels();

$activeTab = $_GET['tab'] ?? 'prepay';
if (!array_key_exists($activeTab, $dataFiles)) {
    $activeTab = 'prepay';
}

$currentPlans = loadPlans($activeTab);
$totalPerCat = categoryCounts($dataFiles);
$totalAll = array_sum($totalPerCat);

function h(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

function renderPlanForm(?array $plan, string $category): void
{
    $v = fn(string $key, $default = '') => $plan[$key] ?? $default;
    $isMobile = isMobileCategory($category);
    $catType = $isMobile ? 'mobile' : 'internet';

    $categoryOptions = $isMobile
        ? ['Date' => '📊', 'Roaming' => '🌍', 'Minute' => '📞', 'SMS' => '💬']
        : ['Viteză' => '⚡', 'Canale TV' => '📺'];

    $daysVal = is_numeric($v('period', 30)) ? (int) $v('period', 30) : 30;
    $featuresList = $v('features', []);
    $additionalFeatures = (array) $v('additional_features', []);
    if (empty($featuresList)) {
        $featuresList = [null];
    }
    ?>
    <div class="form-grid">
        <?php if (in_array($category, ['prepay', 'abonament'], true)): ?>

        <div class="field">
            <label>Operator</label>
            <input type="text" name="operator" class="operator-input" list="operator-suggestions"
                   value="<?= h((string) $v('operator')) ?>" placeholder="ex: Orange" required>
        </div>

        <div class="field">
            <label>Culoare operator</label>
            <input type="color" name="operator_color" class="operator-color-input"
                   value="<?= h((string) $v('operator_color', '#888888')) ?>">
            <div class="field-hint operator-color-hint">Culoarea se completează automat pentru operatorii existenți.</div>
        </div>

        <div class="field">
            <label>Nume plan</label>
            <input type="text" name="name" value="<?= h((string) $v('name')) ?>" placeholder="ex: Opțiune 10 GB" required>
        </div>

        <div class="field">
            <label>Preț (MDL)</label>
            <input type="number" name="price" value="<?= h(formatPlanNumber($v('price', 0))) ?>" min="0" step="0.01" required>
        </div>

        <div class="field">
            <label>Durata (zile)</label>
            <input type="number" name="duration_days" class="days-input"
                   value="<?= $daysVal ?>" min="1" placeholder="ex: 30"
                   oninput="updateDaysHint(this)">
            <div class="field-hint days-hint"><?= h(periodLabel($daysVal)) ?></div>
        </div>

        <div class="field">
            <label>Date (GB) <span style="color:var(--muted);font-weight:400">(0 = nelimitat)</span></label>
            <input type="number" name="data_gb" value="<?= h(formatPlanNumber($v('data_val', 0))) ?>" min="0" step="0.1">
        </div>

        <div class="field">
            <label>Minute <span style="color:var(--muted);font-weight:400">(0 = nelimitat)</span></label>
            <input type="number" name="minutes" value="<?= (int) $v('minutes_val', 0) ?>" min="0">
        </div>

        <div class="field">
            <label>SMS <span style="color:var(--muted);font-weight:400">(0 = nelimitat)</span></label>
            <input type="number" name="sms" value="<?= (int) $v('sms_val', 0) ?>" min="0">
        </div>

        <div class="field">
            <label>Roaming (GB)</label>
            <input type="number" name="roaming_gb"
                   value="<?= h(number_format((float) $v('roaming_val', 0), 2, '.', '')) ?>"
                   min="0" step="0.01">
            <div class="field-hint">Lasă 0 dacă planul nu include trafic în roaming.</div>
        </div>

        <div class="field full">
            <label class="checkbox-field">
                <input type="checkbox" name="is_recommended" value="1" <?= (int) $v('is_recommended', 0) === 1 ? 'checked' : '' ?>>
                Marchează ca plan recomandat
            </label>
            <div class="field-hint">Doar un plan poate fi recomandat în această categorie.</div>
        </div>

        <div class="field full">
            <label>Caracteristici suplimentare</label>
            <div class="additional-features-list">
                <?php foreach ($additionalFeatures as $feature): ?>
                <div class="additional-feature-row">
                    <input type="text" class="additional-label"
                           value="<?= h(additionalFeatureToString((array) $feature)) ?>"
                           placeholder="ex: Acces gratuit la aplicație">
                    <button type="button" class="btn-remove-feature" onclick="removeAdditionalFeature(this)" aria-label="Șterge caracteristica">×</button>
                </div>
                <?php endforeach; ?>
            </div>
            <button type="button" class="btn-add-feature" onclick="addAdditionalFeature(this)">+ Adaugă caracteristică suplimentară</button>
            <input type="hidden" name="additional_features" value="[]">
        </div>

        <div class="field full">
            <label>Link (URL operator)</label>
            <input type="url" name="link" value="<?= h((string) $v('link')) ?>" placeholder="https://...">
        </div>

        <?php elseif (in_array($category, ['internet', 'internet_tv'], true)): ?>

        <div class="field">
            <label>Operator</label>
            <input type="text" name="operator" class="operator-input" list="operator-suggestions"
                   value="<?= h((string) $v('operator')) ?>" placeholder="ex: StarNet" required>
        </div>

        <div class="field">
            <label>Culoare operator</label>
            <input type="color" name="operator_color" class="operator-color-input"
                   value="<?= h((string) $v('operator_color', '#888888')) ?>">
            <div class="field-hint operator-color-hint">Culoarea se completează automat pentru operatorii existenți.</div>
        </div>

        <div class="field">
            <label>Nume plan</label>
            <input type="text" name="name" value="<?= h((string) $v('name')) ?>" placeholder="ex: Internet 1000" required>
        </div>

        <div class="field">
            <label>Preț (MDL)</label>
            <input type="number" name="price" value="<?= h(formatPlanNumber($v('price', 0))) ?>" min="0" step="0.01" required>
        </div>

        <div class="field">
            <label>Durata contractului (luni)</label>
            <input type="number" name="duration_months"
                   value="<?= max(1, (int) $v('duration_months', 1)) ?>" min="1" required>
        </div>

        <div class="field">
            <label>Viteză download (Mbps)</label>
            <input type="number" name="download_speed_mbps"
                   value="<?= (int) $v('download_speed_mbps', 0) ?>" min="0" required>
        </div>

        <div class="field">
            <label>Viteză upload (Mbps)</label>
            <input type="number" name="upload_speed_mbps"
                   value="<?= (int) $v('upload_speed_mbps', 0) ?>" min="0" required>
        </div>

        <?php if ($category === 'internet_tv'): ?>
        <div class="field">
            <label>Număr canale TV</label>
            <input type="number" name="tv_channels"
                   value="<?= (int) $v('tv_channels', 0) ?>" min="0" required>
        </div>

        <div class="field">
            <label>Număr canale HD</label>
            <input type="number" name="hd_channels"
                   value="<?= (int) $v('hd_channels', 0) ?>" min="0" required>
        </div>
        <?php endif; ?>

        <div class="field">
            <label>Preț instalare (MDL)</label>
            <input type="number" name="installation_price"
                   value="<?= h(formatPlanNumber($v('installation_price', 0))) ?>" min="0" step="0.01">
            <div class="field-hint">Introdu 0 dacă instalarea este gratuită.</div>
        </div>

        <div class="field full">
            <label>Router inclus</label>
            <input type="text" name="router_name" value="<?= h((string) $v('router_name')) ?>"
                   placeholder="ex: TP-Link Archer AX23">
            <div class="field-hint">Lasă gol dacă planul nu include router.</div>
        </div>

        <div class="field full">
            <label class="checkbox-field">
                <input type="checkbox" name="is_recommended" value="1" <?= (int) $v('is_recommended', 0) === 1 ? 'checked' : '' ?>>
                Marchează ca plan recomandat
            </label>
            <div class="field-hint">Doar un plan poate fi recomandat în această categorie.</div>
        </div>

        <div class="field full">
            <label>Caracteristici suplimentare</label>
            <div class="additional-features-list">
                <?php foreach ($additionalFeatures as $feature): ?>
                <div class="additional-feature-row">
                    <input type="text" class="additional-label"
                           value="<?= h(additionalFeatureToString((array) $feature)) ?>"
                           placeholder="ex: IP static inclus">
                    <button type="button" class="btn-remove-feature" onclick="removeAdditionalFeature(this)" aria-label="Șterge caracteristica">×</button>
                </div>
                <?php endforeach; ?>
            </div>
            <button type="button" class="btn-add-feature" onclick="addAdditionalFeature(this)">+ Adaugă caracteristică suplimentară</button>
            <input type="hidden" name="additional_features" value="[]">
        </div>

        <div class="field full">
            <label>Link (URL operator)</label>
            <input type="url" name="link" value="<?= h((string) $v('link')) ?>" placeholder="https://...">
        </div>

        <?php else: ?>
        <div class="field">
            <label>Operator</label>
            <input type="text" name="operator" value="<?= h((string) $v('operator')) ?>" placeholder="ex: Orange" required>
        </div>

        <div class="field">
            <label>Culoare operator</label>
            <input type="color" name="operator_color" value="<?= h((string) $v('operator_color', '#888888')) ?>">
        </div>

        <div class="field">
            <label>Nume plan</label>
            <input type="text" name="name" value="<?= h((string) $v('name')) ?>" placeholder="ex: One 170" required>
        </div>

        <div class="field">
            <label>Preț (MDL)</label>
            <input type="number" name="price" value="<?= (int) $v('price', 0) ?>" min="0" required>
        </div>

        <div class="field">
            <label>Durata (zile)</label>
            <input type="number" name="days" class="days-input"
                   value="<?= $daysVal ?>" min="1" placeholder="ex: 30"
                   oninput="updateDaysHint(this)">
            <div class="field-hint days-hint"><?= h(periodLabel($daysVal)) ?></div>
        </div>

        <?php if ($isMobile): ?>
        <div class="field">
            <label>Date (GB) <span style="color:var(--muted);font-weight:400">(0 = nelimitat)</span></label>
            <input type="number" name="data_val" value="<?= (int) $v('data_val', 0) ?>" min="0">
        </div>
        <?php else: ?>
        <div class="field">
            <label>Viteză (Mbps)</label>
            <input type="number" name="speed_val" value="<?= (int) $v('speed_val', 0) ?>" min="0">
        </div>
        <?php endif; ?>

        <div class="field full">
            <label>Caracteristici</label>
            <div class="features-list" data-cat="<?= h($catType) ?>">
                <?php foreach ($featuresList as $feature):
                    if ($feature === null) {
                        $parsed = ['type' => array_key_first($categoryOptions), 'emoji' => '', 'label' => '', 'num' => 0];
                        $isOther = false;
                    } else {
                        $parsed = parseFeatureString($feature, $categoryOptions);
                        $isOther = $parsed['type'] === 'altele';
                    }
                ?>
                <div class="feature-row">
                    <div class="feat-top">
                        <select class="feat-select" onchange="onFeatSelectChange(this)">
                            <?php foreach ($categoryOptions as $name => $emoji): ?>
                            <option value="<?= h($name) ?>" <?= (!$isOther && ($parsed['type'] ?? '') === $name) ? 'selected' : '' ?>>
                                <?= h($name) ?>
                            </option>
                            <?php endforeach; ?>
                            <option value="altele" <?= $isOther ? 'selected' : '' ?>>Altele</option>
                        </select>
                        <button type="button" class="btn-remove-feature" onclick="removeFeature(this)">✕</button>
                    </div>

                    <div class="feat-standard"<?= $isOther ? ' style="display:none"' : '' ?>>
                        <input type="number" class="feat-num" value="<?= (int) ($parsed['num'] ?? 0) ?>" min="0" placeholder="0">
                        <span class="feat-unit-hint">0 = Nelimitat</span>
                    </div>

                    <div class="feat-altele"<?= !$isOther ? ' style="display:none"' : '' ?>>
                        <input type="text" class="feat-emoji" value="<?= h($parsed['emoji'] ?? '') ?>" placeholder="🔥" maxlength="4">
                        <input type="text" class="feat-label-txt" value="<?= h($parsed['label'] ?? '') ?>" placeholder="Label">
                        <input type="number" class="feat-altele-num" value="<?= (int) ($parsed['num'] ?? 0) ?>" min="0" placeholder="0">
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <button type="button" class="btn-add-feature" onclick="addFeature(this)">+ Adaugă caracteristică</button>
        </div>

        <div class="field full">
            <label>Link (URL operator)</label>
            <input type="url" name="link" value="<?= h((string) $v('link')) ?>" placeholder="https://...">
        </div>
        <?php endif; ?>
    </div>
    <?php
}
?>
<!DOCTYPE html>
<html lang="ro">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="theme-color" content="#6a1b9a">
        <title>Dashboard — MolldSIM</title>
        <link rel="icon" href="favicon.svg" type="image/svg+xml">
        <link rel="stylesheet" href="css/variables.css">
        <link rel="stylesheet" href="css/dashboard.css">
    </head>
    <body>
        <div class="layout">
            <aside class="sidebar">
                <div class="sidebar-logo">
                    MolldSIM
                    <span>Admin Dashboard</span>
                </div>
                <nav class="sidebar-nav">
                    <?php foreach ($categoryLabels as $cat => $label): ?>
                        <a href="dashboard.php?tab=<?= h($cat) ?>" class="<?= $activeTab === $cat ? 'active' : '' ?>" data-sidebar-link="<?= h($cat) ?>">
                            <?= h($label) ?>
                            <span class="badge" data-category-badge="<?= h($cat) ?>"><?= $totalPerCat[$cat] ?></span>
                        </a>
                    <?php endforeach; ?>
                </nav>
                <div class="sidebar-back">
                    <a href="index.php">&#8592; Înapoi la site</a>
                </div>
                <div class="sidebar-user">
                    <span class="sidebar-username"><?= h((string) ($_SESSION['username'] ?? '')) ?></span>
                    <a href="logout.php" class="sidebar-logout">Deconectare</a>
                </div>
            </aside>

            <main class="main">
                <div class="topbar">
                    <div>
                        <h1>Gestionare planuri</h1>
                        <div class="category-label" id="active-category-label"><?= h($categoryLabels[$activeTab]) ?></div>
                    </div>
                    <button class="btn btn-primary" type="button" onclick="openAddModal()">+ Adaugă plan în <?= h($categoryLabels[$activeTab]) ?></button>
                </div>

                <div class="content">
                    <div id="dashboard-flash"></div>

                    <div class="stats-row" id="stats-row">
                        <div class="stat-card">
                            <div class="stat-label">Total planuri</div>
                            <div class="stat-value"><?= $totalAll ?></div>
                            <div class="stat-sub">toate categoriile</div>
                        </div>
                        <?php foreach ($categoryLabels as $cat => $label): ?>
                            <div class="stat-card">
                                <div class="stat-label"><?= h($label) ?></div>
                                <div class="stat-value"><?= $totalPerCat[$cat] ?></div>
                                <div class="stat-sub">planuri</div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <div class="table-wrap">
                        <div class="table-header">
                            <h2 id="table-title"><?= h($categoryLabels[$activeTab]) ?></h2>
                            <span class="count" id="table-count"><?= count($currentPlans) ?> planuri</span>
                        </div>
                        <div class="table-scroll">
                            <table>
                                <thead id="dashboard-table-head"></thead>
                                <tbody id="dashboard-table-body"></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </main>
        </div>

        <datalist id="operator-suggestions"></datalist>

        <div class="modal-backdrop" id="addModal">
            <div class="modal">
                <div class="modal-title">+ Adaugă plan nou <?= h($categoryLabels[$activeTab]) ?></div>
                <form class="plan-form" id="add-form">
                    <input type="hidden" name="action" value="create">
                    <input type="hidden" name="category" value="<?= h($activeTab) ?>">
                    <?php renderPlanForm(null, $activeTab); ?>
                    <div class="form-actions">
                        <button type="button" class="btn btn-ghost" onclick="closeAddModal()">Anulează</button>
                        <button type="submit" class="btn btn-primary">Adaugă plan</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="modal-backdrop" id="editModal">
            <div class="modal">
                <div class="modal-title">✏️ Editează plan <?= h($categoryLabels[$activeTab]) ?></div>
                <form class="plan-form" id="edit-form">
                    <input type="hidden" name="action" value="update">
                    <input type="hidden" name="category" value="<?= h($activeTab) ?>">
                    <input type="hidden" name="id" value="">
                    <?php renderPlanForm(null, $activeTab); ?>
                    <div class="form-actions">
                        <button type="button" class="btn btn-ghost" onclick="closeEditModal()">Anulează</button>
                        <button type="submit" class="btn btn-primary">Salvează modificările</button>
                    </div>
                </form>
            </div>
        </div>

        <script>
            window.dashboardState = <?= json_encode([
                'activeCategory' => $activeTab,
                'labels' => $categoryLabels,
                'counts' => $totalPerCat,
                'plans' => array_map(static fn(array $plan, int $index): array => normalizePlan($plan, $index), $currentPlans, array_keys($currentPlans)),
                'apiUrl' => 'api/plans.php',
            ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?>;
        </script>
        <script src="script/dashboard.js" defer></script>
    </body>
</html>
