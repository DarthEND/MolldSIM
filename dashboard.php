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
<script>
    const FEAT_EMOJIS = {
        'Date': '📊',
        'Roaming': '🌍',
        'Minute': '📞',
        'SMS': '💬',
        'Viteză': '⚡',
        'Canale TV': '📺',
    };
    const FEAT_UNITS = {
        'Date': ' GB',
        'Viteză': ' Mbps',
    };
    const CAT_OPTS = {
        mobile: [['Date', '📊'], ['Roaming', '🌍'], ['Minute', '📞'], ['SMS', '💬']],
        internet: [['Viteză', '⚡'], ['Canale TV', '📺']],
    };

    const dashboard = {
        activeCategory: window.dashboardState.activeCategory,
        labels: window.dashboardState.labels,
        counts: window.dashboardState.counts,
        plans: window.dashboardState.plans,
        apiUrl: window.dashboardState.apiUrl,
        operators: {},
        sort: {
            key: '',
            direction: 'asc'
        },
    };

    function rebuildOperatorMemory() {
        dashboard.operators = {};
        dashboard.plans.forEach(function (plan) {
            const key = String(plan.operator || '').trim().toLowerCase();
            if (key && !dashboard.operators[key]) {
                dashboard.operators[key] = {
                    name: plan.operator,
                    color: plan.operator_color
                };
            }
        });

        const suggestions = document.getElementById('operator-suggestions');
        if (suggestions) {
            suggestions.innerHTML = Object.keys(dashboard.operators).map(function (key) {
                return '<option value="' + escapeHtml(dashboard.operators[key].name) + '"></option>';
            }).join('');
        }
    }

    function applyRememberedOperator(input) {
        const form = input.closest('form');
        const colorInput = form ? form.querySelector('.operator-color-input') : null;
        const hint = form ? form.querySelector('.operator-color-hint') : null;
        const remembered = dashboard.operators[input.value.trim().toLowerCase()];

        if (remembered && colorInput) {
            colorInput.value = remembered.color;
            colorInput.dataset.autoFilled = 'true';
            if (hint) {
                hint.textContent = 'Culoare reutilizată automat: ' + remembered.color;
            }
        } else if (colorInput) {
            colorInput.dataset.autoFilled = 'false';
            if (hint) {
                hint.textContent = 'Operator nou: alege culoarea o singură dată.';
            }
        }
    }

    function escapeHtml(value) {
        return String(value).replace(/[&<>"']/g, function (char) {
            return {
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": '&#39;'
            }[char];
        });
    }

    function isMobileCategory(category) {
        return category === 'prepay' || category === 'abonament';
    }

    function periodLabel(days) {
        days = parseInt(days, 10) || 0;
        if (days <= 0) return '';
        const months = Math.round(days / 30);
        if (months >= 1 && Math.abs(days - months * 30) <= 1) {
            return months === 1 ? '1 lună' : months + ' luni';
        }
        return days + ' zile';
    }

    function updateDaysHint(input) {
        if (!input) return;
        const hint = input.parentElement.querySelector('.days-hint');
        if (hint) {
            hint.textContent = periodLabel(input.value);
        }
    }

    function showFlash(type, message) {
        const root = document.getElementById('dashboard-flash');
        root.innerHTML = '<div class="flash ' + escapeHtml(type) + '">' + (type === 'success' ? '✓ ' : '✕ ') + escapeHtml(message) + '</div>';
        window.clearTimeout(showFlash._timer);
        showFlash._timer = window.setTimeout(function () {
            root.innerHTML = '';
        }, 4000);
    }

    function renderStats() {
        const root = document.getElementById('stats-row');
        let totalAll = 0;
        Object.keys(dashboard.counts).forEach(function (category) {
            totalAll += dashboard.counts[category];
        });

        let html = '' +
            '<div class="stat-card">' +
                '<div class="stat-label">Total planuri</div>' +
                '<div class="stat-value">' + totalAll + '</div>' +
                '<div class="stat-sub">toate categoriile</div>' +
            '</div>';

        Object.keys(dashboard.labels).forEach(function (category) {
            html += '' +
                '<div class="stat-card">' +
                    '<div class="stat-label">' + escapeHtml(dashboard.labels[category]) + '</div>' +
                    '<div class="stat-value">' + dashboard.counts[category] + '</div>' +
                    '<div class="stat-sub">planuri</div>' +
                '</div>';
        });

        root.innerHTML = html;
    }

    function sortableHeader(label, key) {
        const isActive = dashboard.sort.key === key;
        const direction = isActive ? dashboard.sort.direction : 'none';
        const indicator = isActive
            ? (dashboard.sort.direction === 'asc' ? '▲' : '▼')
            : '↕';

        return '<th class="sortable-column" aria-sort="' +
            (direction === 'asc' ? 'ascending' : (direction === 'desc' ? 'descending' : 'none')) +
            '"><button type="button" class="sort-button" data-sort-key="' + escapeHtml(key) + '">' +
            '<span>' + escapeHtml(label) + '</span><span class="sort-indicator" aria-hidden="true">' +
            indicator + '</span></button></th>';
    }

    function planSortValue(plan, key) {
        const values = {
            operator: String(plan.operator || '').trim().toLocaleLowerCase('ro'),
            name: String(plan.name || '').trim().toLocaleLowerCase('ro'),
            price: Number(plan.price) || 0,
            duration: parseInt(plan.period, 10) || 0,
            data: Number(plan.data_val) || 0,
            minutes: Number(plan.minutes_val) || 0,
            sms: Number(plan.sms_val) || 0,
            roaming: Number(plan.roaming_val) || 0,
            speed: Number(plan.speed_val) || 0,
            upload: Number(plan.upload_speed_mbps) || 0,
            installation: Number(plan.installation_price) || 0,
            router: String(plan.router_name || '').trim().toLocaleLowerCase('ro'),
            tvChannels: Number(plan.tv_channels) || 0,
            hdChannels: Number(plan.hd_channels) || 0
        };

        return values[key];
    }

    function sortedPlans() {
        const indexedPlans = dashboard.plans.map(function (plan, originalIndex) {
            return { plan: plan, originalIndex: originalIndex };
        });

        if (!dashboard.sort.key) {
            return indexedPlans;
        }

        const direction = dashboard.sort.direction === 'desc' ? -1 : 1;
        return indexedPlans.sort(function (left, right) {
            const leftValue = planSortValue(left.plan, dashboard.sort.key);
            const rightValue = planSortValue(right.plan, dashboard.sort.key);
            let result;

            if (typeof leftValue === 'string' || typeof rightValue === 'string') {
                result = String(leftValue).localeCompare(String(rightValue), 'ro', {
                    numeric: true,
                    sensitivity: 'base'
                });
            } else {
                result = leftValue - rightValue;
            }

            return result === 0
                ? left.originalIndex - right.originalIndex
                : result * direction;
        });
    }

    function changeTableSort(key) {
        if (dashboard.sort.key === key) {
            dashboard.sort.direction = dashboard.sort.direction === 'asc' ? 'desc' : 'asc';
        } else {
            dashboard.sort.key = key;
            dashboard.sort.direction = 'asc';
        }

        renderTable();
    }

    function renderTable() {
        const isMobile = isMobileCategory(dashboard.activeCategory);
        const isDedicatedMobile = dashboard.activeCategory === 'prepay' || dashboard.activeCategory === 'abonament';
        const isDedicatedInternet = dashboard.activeCategory === 'internet';
        const isDedicatedInternetTv = dashboard.activeCategory === 'internet_tv';
        const head = document.getElementById('dashboard-table-head');
        const body = document.getElementById('dashboard-table-body');
        const tableTitle = document.getElementById('table-title');
        const tableCount = document.getElementById('table-count');
        const categoryLabel = document.getElementById('active-category-label');

        tableTitle.textContent = dashboard.labels[dashboard.activeCategory];
        tableCount.textContent = dashboard.plans.length + ' planuri';
        categoryLabel.textContent = dashboard.labels[dashboard.activeCategory];

        head.innerHTML = isDedicatedMobile
            ? '<tr>' +
                '<th>#</th>' +
                sortableHeader('Operator', 'operator') +
                sortableHeader('Nume plan', 'name') +
                sortableHeader('Preț', 'price') +
                sortableHeader('Durata', 'duration') +
                sortableHeader('Date (GB)', 'data') +
                sortableHeader('Minute', 'minutes') +
                sortableHeader('SMS', 'sms') +
                sortableHeader('Roaming (GB)', 'roaming') +
                '<th>Suplimentar</th>' +
                '<th>Acțiuni</th>' +
              '</tr>'
            : isDedicatedInternet
            ? '<tr>' +
                '<th>#</th>' +
                sortableHeader('Operator', 'operator') +
                sortableHeader('Nume plan', 'name') +
                sortableHeader('Preț', 'price') +
                sortableHeader('Durata', 'duration') +
                sortableHeader('Download', 'speed') +
                sortableHeader('Upload', 'upload') +
                sortableHeader('Instalare', 'installation') +
                sortableHeader('Router', 'router') +
                '<th>Suplimentar</th>' +
                '<th>Acțiuni</th>' +
              '</tr>'
            : isDedicatedInternetTv
            ? '<tr>' +
                '<th>#</th>' +
                sortableHeader('Operator', 'operator') +
                sortableHeader('Nume plan', 'name') +
                sortableHeader('Preț', 'price') +
                sortableHeader('Durata', 'duration') +
                sortableHeader('Download', 'speed') +
                sortableHeader('Upload', 'upload') +
                sortableHeader('Canale TV', 'tvChannels') +
                sortableHeader('Canale HD', 'hdChannels') +
                sortableHeader('Instalare', 'installation') +
                sortableHeader('Router', 'router') +
                '<th>Suplimentar</th>' +
                '<th>Acțiuni</th>' +
              '</tr>'
            : '<tr>' +
                '<th>#</th>' +
                sortableHeader('Operator', 'operator') +
                sortableHeader('Nume plan', 'name') +
                sortableHeader('Preț', 'price') +
                sortableHeader('Durata', 'duration') +
                (isMobile
                    ? sortableHeader('Date (GB)', 'data') +
                      sortableHeader('Minute', 'minutes') +
                      sortableHeader('SMS', 'sms')
                    : sortableHeader('Viteză', 'speed')) +
                '<th>Caracteristici</th>' +
                '<th>Acțiuni</th>' +
              '</tr>';

        if (!dashboard.plans.length) {
            body.innerHTML = '<tr><td colspan="' + (isDedicatedInternetTv ? 13 : ((isDedicatedMobile || isDedicatedInternet) ? 11 : (isMobile ? 10 : 8))) + '" style="text-align:center;color:var(--muted);padding:2rem">Niciun plan în această categorie.</td></tr>';
            return;
        }

        body.innerHTML = sortedPlans().map(function (item, displayIndex) {
            const plan = item.plan;
            const originalIndex = item.originalIndex;
            const speedLabel = plan.speed_val > 0
                ? (plan.speed_val >= 1000 ? (Math.round(plan.speed_val / 100) / 10) + ' Gbps' : plan.speed_val + ' Mbps')
                : '—';

            const additional = (plan.additional_features || []).map(function (feature) {
                const label = String(feature.label || '').trim();
                const value = String(feature.value || '').trim();
                const unit = String(feature.unit || '').trim();
                if (!label) return '';
                return label + (value ? ': ' + value + (unit ? ' ' + unit : '') : '');
            }).filter(Boolean).join(' · ');

            const commonCells = '' +
                '<tr>' +
                    '<td class="muted-cell">' + (displayIndex + 1) + '</td>' +
                    '<td><span class="op-badge" style="background:' + escapeHtml(plan.operator_color) + '">' + escapeHtml(plan.operator) + '</span></td>' +
                    '<td class="plan-name-cell">' + escapeHtml(plan.name) + (Number(plan.is_recommended || 0) === 1 ? ' <span class="dashboard-recommended-badge">Recomandat</span>' : '') + '</td>' +
                    '<td class="price-cell">' + plan.price + ' MDL</td>' +
                    '<td class="muted-cell">' + escapeHtml(plan.period_display) + '</td>';

            const categoryCells = isDedicatedMobile
                ? '<td class="muted-cell">' + (plan.data_val > 0 ? plan.data_val + ' GB' : 'Nelimitat') + '</td>' +
                  '<td class="muted-cell">' + (plan.minutes_val > 0 ? plan.minutes_val : 'Nelimitat') + '</td>' +
                  '<td class="muted-cell">' + (plan.sms_val > 0 ? plan.sms_val : 'Nelimitat') + '</td>' +
                  '<td class="muted-cell">' + (plan.roaming_val > 0 ? Number(plan.roaming_val).toFixed(2) + ' GB' : '-') + '</td>' +
                  '<td><div class="features-preview">' + (additional ? escapeHtml(additional) : '-') + '</div></td>'
                : isDedicatedInternet
                ? '<td class="muted-cell">' + plan.download_speed_mbps + ' Mbps</td>' +
                  '<td class="muted-cell">' + plan.upload_speed_mbps + ' Mbps</td>' +
                  '<td class="muted-cell">' + (Number(plan.installation_price) > 0 ? plan.installation_price + ' MDL' : 'Gratuită') + '</td>' +
                  '<td class="muted-cell">' + (plan.router_name ? escapeHtml(plan.router_name) : '-') + '</td>' +
                  '<td><div class="features-preview">' + (additional ? escapeHtml(additional) : '-') + '</div></td>'
                : isDedicatedInternetTv
                ? '<td class="muted-cell">' + plan.download_speed_mbps + ' Mbps</td>' +
                  '<td class="muted-cell">' + plan.upload_speed_mbps + ' Mbps</td>' +
                  '<td class="muted-cell">' + plan.tv_channels + '</td>' +
                  '<td class="muted-cell">' + plan.hd_channels + '</td>' +
                  '<td class="muted-cell">' + (Number(plan.installation_price) > 0 ? plan.installation_price + ' MDL' : 'Gratuită') + '</td>' +
                  '<td class="muted-cell">' + (plan.router_name ? escapeHtml(plan.router_name) : '-') + '</td>' +
                  '<td><div class="features-preview">' + (additional ? escapeHtml(additional) : '-') + '</div></td>'
                : (isMobile
                        ? '<td class="muted-cell">' + (plan.data_val > 0 ? plan.data_val + ' GB' : 'Nelimitat') + '</td>' +
                          '<td class="muted-cell">' + escapeHtml(plan.minutes) + '</td>' +
                          '<td class="muted-cell">' + escapeHtml(plan.sms) + '</td>'
                        : '<td class="muted-cell">' + escapeHtml(speedLabel) + '</td>') +
                  '<td><div class="features-preview">' + escapeHtml((plan.features || []).join(' · ')) + '</div></td>';

            return commonCells + categoryCells +
                    '<td>' +
                        '<div class="actions-cell">' +
                            '<button type="button" class="btn btn-edit" data-edit-index="' + originalIndex + '">✏️ Edit</button>' +
                            '<button type="button" class="btn btn-danger" data-delete-index="' + originalIndex + '">🗑 Șterge</button>' +
                        '</div>' +
                    '</td>' +
                '</tr>';
        }).join('');
    }

    function updateSidebarCounts() {
        document.querySelectorAll('[data-category-badge]').forEach(function (badge) {
            const category = badge.dataset.categoryBadge;
            badge.textContent = dashboard.counts[category] || 0;
        });
    }

    function syncDashboard(payload) {
        dashboard.plans = payload.all_plans || [];
        dashboard.counts = payload.counts || dashboard.counts;
        rebuildOperatorMemory();
        renderStats();
        renderTable();
        updateSidebarCounts();
    }

    function modalFor(form) {
        return form.id === 'edit-form' ? document.getElementById('editModal') : document.getElementById('addModal');
    }

    function openAddModal() {
        const form = document.getElementById('add-form');
        form.reset();
        const additionalList = form.querySelector('.additional-features-list');
        if (additionalList) {
            additionalList.innerHTML = '';
        }
        const featureList = form.querySelector('.features-list');
        if (featureList) {
            resetFeatureList(featureList);
        }
        updateDaysHint(form.querySelector('.days-input'));
        document.getElementById('addModal').classList.add('open');
    }

    function closeAddModal() {
        document.getElementById('addModal').classList.remove('open');
    }

    function openEditModal(index) {
        const plan = dashboard.plans[index];
        if (!plan) return;

        const form = document.getElementById('edit-form');
        form.elements.action.value = 'update';
        form.elements.category.value = dashboard.activeCategory;
        form.elements.id.value = String(plan.id);
        form.elements.operator.value = plan.operator;
        form.elements.operator_color.value = plan.operator_color;
        form.elements.name.value = plan.name;
        form.elements.price.value = plan.price;
        const durationInput = form.elements.duration_days || form.elements.days;
        if (durationInput) {
            durationInput.value = parseInt(plan.period, 10) || 30;
        }
        if (form.elements.duration_months) {
            form.elements.duration_months.value = plan.duration_months || 1;
        }
        if (form.elements.data_gb) form.elements.data_gb.value = plan.data_val || 0;
        if (form.elements.data_val) form.elements.data_val.value = plan.data_val || 0;
        if (form.elements.speed_val) form.elements.speed_val.value = plan.speed_val || 0;
        if (form.elements.download_speed_mbps) form.elements.download_speed_mbps.value = plan.download_speed_mbps || 0;
        if (form.elements.upload_speed_mbps) form.elements.upload_speed_mbps.value = plan.upload_speed_mbps || 0;
        if (form.elements.installation_price) form.elements.installation_price.value = plan.installation_price || 0;
        if (form.elements.router_name) form.elements.router_name.value = plan.router_name || '';
        if (form.elements.tv_channels) form.elements.tv_channels.value = plan.tv_channels || 0;
        if (form.elements.hd_channels) form.elements.hd_channels.value = plan.hd_channels || 0;
        if (form.elements.is_recommended) form.elements.is_recommended.checked = Number(plan.is_recommended || 0) === 1;
        if (form.elements.minutes) form.elements.minutes.value = plan.minutes_val || 0;
        if (form.elements.sms) form.elements.sms.value = plan.sms_val || 0;
        if (form.elements.roaming_gb) {
            form.elements.roaming_gb.value = Number(plan.roaming_val || 0).toFixed(2);
        }
        form.elements.link.value = plan.link || '';
        const additionalList = form.querySelector('.additional-features-list');
        if (additionalList) {
            rebuildAdditionalFeatures(additionalList, plan.additional_features || []);
        }
        const featureList = form.querySelector('.features-list');
        if (featureList) {
            rebuildFeatureRows(featureList, plan.features || []);
        }
        updateDaysHint(form.querySelector('.days-input'));
        document.getElementById('editModal').classList.add('open');
    }

    function closeEditModal() {
        document.getElementById('editModal').classList.remove('open');
    }

    function buildFeatureString(row) {
        const select = row.querySelector('.feat-select').value;
        if (select === 'altele') {
            const emoji = row.querySelector('.feat-emoji').value.trim();
            const label = row.querySelector('.feat-label-txt').value.trim();
            const num = parseInt(row.querySelector('.feat-altele-num').value, 10) || 0;
            if (!label) return '';
            return emoji + ' ' + label + ': ' + (num === 0 ? 'Nelimitat' : String(num));
        }

        const emoji = FEAT_EMOJIS[select] || '';
        const unit = FEAT_UNITS[select] || '';
        const num = parseInt(row.querySelector('.feat-num').value, 10) || 0;
        return emoji + ' ' + select + ': ' + (num === 0 ? 'Nelimitat' : (num + unit));
    }

    function appendFeatureInput(form, value) {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'features[]';
        input.value = value;
        input.setAttribute('data-feat', '1');
        form.appendChild(input);
    }

    function additionalFeatureHtml(feature) {
        feature = feature || {};
        const label = String(feature.label || '').trim();
        const value = String(feature.value || '').trim();
        const unit = String(feature.unit || '').trim();
        const icon = String(feature.icon || '').trim();
        const prefix = (icon + ' ' + label).trim();
        const detail = (value + (unit ? ' ' + unit : '')).trim();
        const text = prefix ? (detail ? prefix + ': ' + detail : prefix) : detail;

        return '' +
            '<div class="additional-feature-row">' +
                '<input type="text" class="additional-label" value="' + escapeHtml(text) + '" placeholder="ex: Acces gratuit la aplicație">' +
                '<button type="button" class="btn-remove-feature" onclick="removeAdditionalFeature(this)" aria-label="Șterge caracteristica">×</button>' +
            '</div>';
    }

    function rebuildAdditionalFeatures(list, features) {
        list.innerHTML = (features || []).map(additionalFeatureHtml).join('');
    }

    function addAdditionalFeature(button) {
        const list = button.previousElementSibling;
        list.insertAdjacentHTML('beforeend', additionalFeatureHtml({}));
    }

    function removeAdditionalFeature(button) {
        button.closest('.additional-feature-row').remove();
    }

    function prepareAdditionalFeatures(form) {
        const features = Array.from(form.querySelectorAll('.additional-feature-row')).map(function (row) {
            return {
                label: row.querySelector('.additional-label').value.trim()
            };
        }).filter(function (feature) {
            return feature.label !== '';
        });

        form.elements.additional_features.value = JSON.stringify(features);
    }

    function onFeatSelectChange(select) {
        const row = select.closest('.feature-row');
        const isOther = select.value === 'altele';
        row.querySelector('.feat-standard').style.display = isOther ? 'none' : '';
        row.querySelector('.feat-altele').style.display = isOther ? '' : 'none';
    }

    function featureRowHtml(catType, parsed) {
        const options = (CAT_OPTS[catType] || CAT_OPTS.mobile).map(function (item) {
            const selected = !parsed.isOther && parsed.type === item[0] ? ' selected' : '';
            return '<option value="' + escapeHtml(item[0]) + '"' + selected + '>' + escapeHtml(item[0]) + '</option>';
        }).join('') + '<option value="altele"' + (parsed.isOther ? ' selected' : '') + '>Altele</option>';

        return '' +
            '<div class="feature-row">' +
                '<div class="feat-top">' +
                    '<select class="feat-select" onchange="onFeatSelectChange(this)">' + options + '</select>' +
                    '<button type="button" class="btn-remove-feature" onclick="removeFeature(this)">✕</button>' +
                '</div>' +
                '<div class="feat-standard"' + (parsed.isOther ? ' style="display:none"' : '') + '>' +
                    '<input type="number" class="feat-num" value="' + (parsed.num || 0) + '" min="0" placeholder="0">' +
                    '<span class="feat-unit-hint">0 = Nelimitat</span>' +
                '</div>' +
                '<div class="feat-altele"' + (parsed.isOther ? '' : ' style="display:none"') + '>' +
                    '<input type="text" class="feat-emoji" value="' + escapeHtml(parsed.emoji || '') + '" placeholder="🔥" maxlength="4">' +
                    '<input type="text" class="feat-label-txt" value="' + escapeHtml(parsed.label || '') + '" placeholder="Label">' +
                    '<input type="number" class="feat-altele-num" value="' + (parsed.num || 0) + '" min="0" placeholder="0">' +
                '</div>' +
            '</div>';
    }

    function parseFeature(feature, catType) {
        const options = (CAT_OPTS[catType] || CAT_OPTS.mobile);
        const parts = String(feature).split(': ');
        const prefix = parts[0] || '';
        const value = parts[1] || '';
        const unlimited = /nelimitat/i.test(value);
        const match = value.match(/(\d+)/);
        const num = unlimited ? 0 : parseInt(match ? match[1] : '0', 10);

        for (let i = 0; i < options.length; i++) {
            if (prefix === options[i][1] + ' ' + options[i][0]) {
                return { type: options[i][0], emoji: options[i][1], label: options[i][0], num: num, isOther: false };
            }
        }

        const emojiMatch = prefix.match(/^(\S+)\s*(.*)$/);
        return {
            type: 'altele',
            emoji: emojiMatch ? emojiMatch[1] : '',
            label: emojiMatch ? emojiMatch[2] : prefix,
            num: num,
            isOther: true
        };
    }

    function rebuildFeatureRows(list, features) {
        const catType = list.dataset.cat || 'mobile';
        const values = (features && features.length ? features : ['']).map(function (feature) {
            return feature ? parseFeature(feature, catType) : {
                type: (CAT_OPTS[catType] || CAT_OPTS.mobile)[0][0],
                emoji: '',
                label: '',
                num: 0,
                isOther: false
            };
        });

        list.innerHTML = values.map(function (parsed) {
            return featureRowHtml(catType, parsed);
        }).join('');
    }

    function resetFeatureList(list) {
        rebuildFeatureRows(list, []);
    }

    function addFeature(button) {
        const list = button.previousElementSibling;
        const catType = list.dataset.cat || 'mobile';
        const wrapper = document.createElement('div');
        wrapper.innerHTML = featureRowHtml(catType, {
            type: (CAT_OPTS[catType] || CAT_OPTS.mobile)[0][0],
            emoji: '',
            label: '',
            num: 0,
            isOther: false
        });
        list.appendChild(wrapper.firstElementChild);
    }

    function removeFeature(button) {
        const row = button.closest('.feature-row');
        const list = row.parentElement;
        if (list.querySelectorAll('.feature-row').length > 1) {
            row.remove();
            return;
        }
        resetFeatureList(list);
    }

    function prepareFeatureInputs(form) {
        form.querySelectorAll('input[data-feat]').forEach(function (input) {
            input.remove();
        });

        if (form.elements.category.value === 'prepay' ||
            form.elements.category.value === 'abonament' ||
            form.elements.category.value === 'internet' ||
            form.elements.category.value === 'internet_tv') {
            prepareAdditionalFeatures(form);
            return;
        }

        form.querySelectorAll('.feature-row').forEach(function (row) {
            const value = buildFeatureString(row);
            if (!value) return;
            appendFeatureInput(form, value);
        });
    }

    function submitForm(form) {
        prepareFeatureInputs(form);
        const submitButton = form.querySelector('button[type="submit"]');
        submitButton.disabled = true;

        fetch(dashboard.apiUrl, {
            method: 'POST',
            body: new FormData(form),
            headers: { 'Accept': 'application/json' }
        })
            .then(function (response) {
                return response.json().then(function (payload) {
                    if (!response.ok || payload.success === false) {
                        throw new Error(payload.message || 'Request failed');
                    }
                    return payload;
                });
            })
            .then(function (payload) {
                syncDashboard(payload);
                showFlash('success', payload.message || 'Modificările au fost salvate.');
                modalFor(form).classList.remove('open');
            })
            .catch(function (error) {
                showFlash('error', error.message || 'Operația a eșuat.');
            })
            .finally(function () {
                submitButton.disabled = false;
            });
    }

    function deletePlan(index) {
        const plan = dashboard.plans[index];
        if (!plan) return;
        if (!window.confirm('Ștergi planul «' + plan.name + '»?')) {
            return;
        }

        const formData = new FormData();
        formData.set('action', 'delete');
        formData.set('category', dashboard.activeCategory);
        formData.set('id', String(plan.id));

        fetch(dashboard.apiUrl, {
            method: 'POST',
            body: formData,
            headers: { 'Accept': 'application/json' }
        })
            .then(function (response) {
                return response.json().then(function (payload) {
                    if (!response.ok || payload.success === false) {
                        throw new Error(payload.message || 'Delete failed');
                    }
                    return payload;
                });
            })
            .then(function (payload) {
                syncDashboard(payload);
                showFlash('success', payload.message || 'Plan șters cu succes.');
            })
            .catch(function (error) {
                showFlash('error', error.message || 'Ștergerea a eșuat.');
            });
    }

    document.getElementById('addModal').addEventListener('click', function (event) {
        if (event.target === this) closeAddModal();
    });
    document.getElementById('editModal').addEventListener('click', function (event) {
        if (event.target === this) closeEditModal();
    });

    document.getElementById('dashboard-table-body').addEventListener('click', function (event) {
        const editButton = event.target.closest('[data-edit-index]');
        if (editButton) {
            openEditModal(parseInt(editButton.dataset.editIndex, 10));
            return;
        }

        const deleteButton = event.target.closest('[data-delete-index]');
        if (deleteButton) {
            deletePlan(parseInt(deleteButton.dataset.deleteIndex, 10));
        }
    });

    document.getElementById('dashboard-table-head').addEventListener('click', function (event) {
        const sortButton = event.target.closest('[data-sort-key]');
        if (sortButton) {
            changeTableSort(sortButton.dataset.sortKey);
        }
    });

    document.querySelectorAll('form.plan-form').forEach(function (form) {
        form.addEventListener('submit', function (event) {
            event.preventDefault();
            submitForm(form);
        });
    });

    document.querySelectorAll('.operator-input').forEach(function (input) {
        input.addEventListener('input', function () {
            applyRememberedOperator(input);
        });
        input.addEventListener('change', function () {
            applyRememberedOperator(input);
        });
    });

    rebuildOperatorMemory();
    renderStats();
    renderTable();

    window.openAddModal = openAddModal;
    window.closeAddModal = closeAddModal;
    window.closeEditModal = closeEditModal;
    window.updateDaysHint = updateDaysHint;
    window.onFeatSelectChange = onFeatSelectChange;
    window.addFeature = addFeature;
    window.removeFeature = removeFeature;
    window.addAdditionalFeature = addAdditionalFeature;
    window.removeAdditionalFeature = removeAdditionalFeature;
</script>
</body>
</html>
