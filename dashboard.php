<?php
session_start();

// ── Data file registry ────────────────────────────────────────────────────────
$dataFiles = [
    'prepay'      => __DIR__ . '/data/prepay_plans.php',
    'abonament'   => __DIR__ . '/data/abonament_plans.php',
    'internet'    => __DIR__ . '/data/internet_plans.php',
    'internet_tv' => __DIR__ . '/data/internet_tv_plans.php',
];

$categoryLabels = [
    'prepay'      => 'Prepay',
    'abonament'   => 'Abonament',
    'internet'    => 'Internet',
    'internet_tv' => 'Internet + TV',
];

// ── Helpers ───────────────────────────────────────────────────────────────────
function loadPlans(string $file): array
{
    return require $file;
}

function savePlans(string $file, array $plans): void
{
    $php  = "<?php\n";
    $php .= "/**\n * Plan data.\n * @return array[]\n */\n";
    $php .= "return [\n";
    foreach ($plans as $plan) {
        $php .= "    [\n";
        $php .= "        'operator'       => " . var_export($plan['operator'],       true) . ",\n";
        $php .= "        'operator_color' => " . var_export($plan['operator_color'], true) . ",\n";
        $php .= "        'operator_key'   => " . var_export($plan['operator_key'],   true) . ",\n";
        $php .= "        'name'           => " . var_export($plan['name'],           true) . ",\n";
        $php .= "        'price'          => " . (int) $plan['price']                      . ",\n";
        $php .= "        'period'         => " . var_export($plan['period'],         true) . ",\n";
        $php .= "        'features'       => [\n";
        foreach ($plan['features'] as $feat) {
            $php .= "            " . var_export($feat, true) . ",\n";
        }
        $php .= "        ],\n";
        $php .= "        'link'           => " . var_export($plan['link'],           true) . ",\n";
        $php .= "        'data_val'       => " . (int) $plan['data_val']                   . ",\n";
        $php .= "        'speed_val'      => " . (int) $plan['speed_val']                  . ",\n";
        $php .= "    ],\n";
    }
    $php .= "];\n";
    file_put_contents($file, $php);
}

function periodLabel(int $days): string
{
    $months = (int) round($days / 30);
    if ($months >= 1 && abs($days - $months * 30) <= 1) {
        return $months === 1 ? '1 lună' : "{$months} luni";
    }
    return "{$days} zile";
}

function displayPeriod($period): string
{
    if (is_int($period) || (is_string($period) && ctype_digit((string) $period) && (int) $period > 0)) {
        return periodLabel((int) $period);
    }
    return (string) $period; // backward compat with old string values
}

function parseFeatureString(string $feat, array $catOptions): array
{
    $pos = strpos($feat, ': ');
    if ($pos === false) {
        return ['type' => 'altele', 'emoji' => '', 'label' => $feat, 'num' => 0];
    }
    $prefix = trim(substr($feat, 0, $pos));
    $value  = trim(substr($feat, $pos + 2));
    $isNel  = mb_stripos($value, 'nelimitat') !== false;
    preg_match('/(\d+)/', $value, $nm);
    $num = $isNel ? 0 : (int) ($nm[1] ?? 0);

    foreach ($catOptions as $name => $emoji) {
        if ($prefix === $emoji . ' ' . $name) {
            return ['type' => $name, 'emoji' => $emoji, 'label' => $name, 'num' => $num];
        }
    }

    // Altele: split first grapheme cluster as emoji, rest as label
    if (preg_match('/^(\S+)\s*(.*)/su', $prefix, $m)) {
        return ['type' => 'altele', 'emoji' => trim($m[1]), 'label' => trim($m[2]), 'num' => $num];
    }
    return ['type' => 'altele', 'emoji' => '', 'label' => $prefix, 'num' => $num];
}

function planFromPost(): array
{
    $features = array_values(array_filter(
        array_map('trim', (array) ($_POST['features'] ?? [])),
        fn($f) => $f !== ''
    ));

    $operator    = trim($_POST['operator'] ?? '');
    $operatorKey = strtolower(preg_replace('/\s+/', '_', $operator));

    return [
        'operator'       => $operator,
        'operator_color' => trim($_POST['operator_color'] ?? '#000000'),
        'operator_key'   => $operatorKey,
        'name'           => trim($_POST['name']       ?? ''),
        'price'          => (int) ($_POST['price']    ?? 0),
        'period'         => (int) ($_POST['days']     ?? 30),
        'features'       => $features,
        'link'           => trim($_POST['link']       ?? ''),
        'data_val'       => (int) ($_POST['data_val']  ?? 0),
        'speed_val'      => (int) ($_POST['speed_val'] ?? 0),
    ];
}

// ── Handle POST (CRUD actions) ────────────────────────────────────────────────
$action = $_POST['action'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action !== '') {
    $cat = $_POST['category'] ?? '';

    if (!array_key_exists($cat, $dataFiles)) {
        $_SESSION['flash'] = ['type' => 'error', 'msg' => 'Categorie invalidă.'];
        header('Location: dashboard.php');
        exit;
    }

    $plans = loadPlans($dataFiles[$cat]);

    if ($action === 'delete') {
        $idx = (int) $_POST['index'];
        array_splice($plans, $idx, 1);
        savePlans($dataFiles[$cat], $plans);
        $_SESSION['flash'] = ['type' => 'success', 'msg' => 'Plan șters cu succes.'];

    } elseif ($action === 'create') {
        $plans[] = planFromPost();
        savePlans($dataFiles[$cat], $plans);
        $_SESSION['flash'] = ['type' => 'success', 'msg' => 'Plan adăugat cu succes.'];

    } elseif ($action === 'update') {
        $idx         = (int) $_POST['index'];
        $plans[$idx] = planFromPost();
        savePlans($dataFiles[$cat], $plans);
        $_SESSION['flash'] = ['type' => 'success', 'msg' => 'Plan actualizat cu succes.'];
    }

    header('Location: dashboard.php?tab=' . urlencode($cat));
    exit;
}

// ── Read state ────────────────────────────────────────────────────────────────
$flash     = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);

$activeTab = $_GET['tab'] ?? 'prepay';
if (!array_key_exists($activeTab, $dataFiles)) {
    $activeTab = 'prepay';
}

$editCat  = $_GET['edit_cat'] ?? '';
$editIdx  = isset($_GET['edit_idx']) ? (int) $_GET['edit_idx'] : -1;
$editPlan = null;
if ($editCat !== '' && array_key_exists($editCat, $dataFiles) && $editIdx >= 0) {
    $tmp = loadPlans($dataFiles[$editCat]);
    if (isset($tmp[$editIdx])) {
        $editPlan = $tmp[$editIdx];
    }
}

$currentPlans = loadPlans($dataFiles[$activeTab]);

// ── Aggregate stats ───────────────────────────────────────────────────────────
$totalPerCat = [];
foreach ($dataFiles as $cat => $file) {
    $totalPerCat[$cat] = count(loadPlans($file));
}
$totalAll = array_sum($totalPerCat);

// ── Helpers for HTML output ───────────────────────────────────────────────────
function h(string $s): string { return htmlspecialchars($s, ENT_QUOTES, 'UTF-8'); }

function extractFeatureValue(array $features, string $emoji): string
{
    foreach ($features as $feat) {
        if (mb_strpos($feat, $emoji) === 0) {
            $pos = strpos($feat, ': ');
            if ($pos !== false) {
                return trim(substr($feat, $pos + 2));
            }
        }
    }
    return '—';
}
?>
<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard — MolldSIM</title>
    <link rel="stylesheet" href="css/dashboard.css">
</head>
<body>
<div class="layout">

    <!-- ── Sidebar ─────────────────────────────────────────────────── -->
    <aside class="sidebar">
        <div class="sidebar-logo">
            MolldSIM
            <span>Admin Dashboard</span>
        </div>
        <nav class="sidebar-nav">
            <?php foreach ($categoryLabels as $cat => $label): ?>
                <a href="dashboard.php?tab=<?= h($cat) ?>"
                   class="<?= $activeTab === $cat ? 'active' : '' ?>">
                    <?= h($label) ?>
                    <span class="badge"><?= $totalPerCat[$cat] ?></span>
                </a>
            <?php endforeach; ?>
        </nav>
        <div class="sidebar-back">
            <a href="index.php">&#8592; Înapoi la site</a>
        </div>
    </aside>

    <!-- ── Main ────────────────────────────────────────────────────── -->
    <main class="main">

        <!-- Top bar -->
        <div class="topbar">
            <div>
                <h1>Gestionare planuri</h1>
                <div class="category-label"><?= h($categoryLabels[$activeTab]) ?></div>
            </div>
            <button class="btn btn-primary" onclick="openAddModal()">+ Adaugă plan in <?= h($categoryLabels[$activeTab]) ?></button>
        </div>

        <div class="content">

            <!-- Flash message -->
            <?php if ($flash): ?>
                <div class="flash <?= h($flash['type']) ?>">
                    <?= $flash['type'] === 'success' ? '✓' : '✕' ?>
                    <?= h($flash['msg']) ?>
                </div>
            <?php endif; ?>

            <!-- Stats -->
            <div class="stats-row">
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

            <!-- Plans table -->
            <div class="table-wrap">
                <div class="table-header">
                    <h2><?= h($categoryLabels[$activeTab]) ?></h2>
                    <span class="count"><?= count($currentPlans) ?> planuri</span>
                </div>
                <?php $isMobileTab = in_array($activeTab, ['prepay', 'abonament']); ?>
                <div class="table-scroll">
                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Operator</th>
                            <th>Nume plan</th>
                            <th>Preț</th>
                            <th>Durata</th>
                            <?php if ($isMobileTab): ?>
                                <th>Date (GB)</th>
                                <th>Minute</th>
                                <th>SMS</th>
                            <?php else: ?>
                                <th>Viteză</th>
                            <?php endif; ?>
                            <th>Caracteristici</th>
                            <th>Acțiuni</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($currentPlans as $idx => $plan): ?>
                        <tr>
                            <td class="muted-cell"><?= $idx + 1 ?></td>
                            <td>
                                <span class="op-badge" style="background:<?= h($plan['operator_color']) ?>">
                                    <?= h($plan['operator']) ?>
                                </span>
                            </td>
                            <td class="plan-name-cell"><?= h($plan['name']) ?></td>
                            <td class="price-cell"><?= (int) $plan['price'] ?> MDL</td>
                            <td class="muted-cell"><?= h(displayPeriod($plan['period'])) ?></td>
                            <?php if ($isMobileTab): ?>
                                <td class="muted-cell">
                                    <?= $plan['data_val'] > 0 ? $plan['data_val'] . ' GB' : 'Nelimitat' ?>
                                </td>
                                <td class="muted-cell"><?= h(extractFeatureValue($plan['features'], '📞')) ?></td>
                                <td class="muted-cell"><?= h(extractFeatureValue($plan['features'], '💬')) ?></td>
                            <?php else: ?>
                                <td class="muted-cell">
                                    <?php if ($plan['speed_val'] > 0): ?>
                                        <?= $plan['speed_val'] >= 1000
                                            ? round($plan['speed_val'] / 1000, 1) . ' Gbps'
                                            : $plan['speed_val'] . ' Mbps' ?>
                                    <?php else: ?>
                                        —
                                    <?php endif; ?>
                                </td>
                            <?php endif; ?>
                            <td>
                                <div class="features-preview">
                                    <?= h(implode(' · ', $plan['features'])) ?>
                                </div>
                            </td>
                            <td>
                                <div class="actions-cell">
                                    <a class="btn btn-edit"
                                       href="dashboard.php?tab=<?= h($activeTab) ?>&edit_cat=<?= h($activeTab) ?>&edit_idx=<?= $idx ?>">
                                       ✏️ Edit
                                    </a>
                                    <form class="delete-form" method="POST" action="dashboard.php"
                                          onsubmit="return confirm('Ștergi planul «<?= h(addslashes($plan['name'])) ?>»?')">
                                        <input type="hidden" name="action"   value="delete">
                                        <input type="hidden" name="category" value="<?= h($activeTab) ?>">
                                        <input type="hidden" name="index"    value="<?= $idx ?>">
                                        <button type="submit" class="btn btn-danger">🗑 Șterge</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (empty($currentPlans)): ?>
                        <tr>
                            <td colspan="<?= $isMobileTab ? 10 : 8 ?>"
                                style="text-align:center;color:var(--muted);padding:2rem">
                                Niciun plan în această categorie.
                            </td>
                        </tr>
                    <?php endif; ?>
                    </tbody>
                </table>
                </div>
            </div>

        </div><!-- /content -->
    </main>
</div><!-- /layout -->

<!-- ── Add Modal ──────────────────────────────────────────────────────────── -->
<div class="modal-backdrop" id="addModal">
    <div class="modal">
        <div class="modal-title">+ Adaugă plan nou</div>
        <form class="plan-form" method="POST" action="dashboard.php">
            <input type="hidden" name="action"   value="create">
            <input type="hidden" name="category" value="<?= h($activeTab) ?>">
            <?php renderPlanForm(null, $activeTab); ?>
            <div class="form-actions">
                <button type="button" class="btn btn-ghost" onclick="closeAddModal()">Anulează</button>
                <button type="submit" class="btn btn-primary">Adaugă plan</button>
            </div>
        </form>
    </div>
</div>

<!-- ── Edit Modal ─────────────────────────────────────────────────────────── -->
<?php if ($editPlan !== null): ?>
<div class="modal-backdrop open" id="editModal">
    <div class="modal">
        <div class="modal-title">✏️ Editează plan</div>
        <form class="plan-form" method="POST" action="dashboard.php">
            <input type="hidden" name="action"   value="update">
            <input type="hidden" name="category" value="<?= h($editCat) ?>">
            <input type="hidden" name="index"    value="<?= $editIdx ?>">
            <?php renderPlanForm($editPlan, $editCat); ?>
            <div class="form-actions">
                <a href="dashboard.php?tab=<?= h($editCat) ?>" class="btn btn-ghost">Anulează</a>
                <button type="submit" class="btn btn-primary">Salvează modificările</button>
            </div>
        </form>
    </div>
</div>
<?php endif; ?>

<script>
    // ── Modal ─────────────────────────────────────────────────────────────────
    function openAddModal()  { document.getElementById('addModal').classList.add('open'); }
    function closeAddModal() { document.getElementById('addModal').classList.remove('open'); }
    document.getElementById('addModal').addEventListener('click', function (e) {
        if (e.target === this) closeAddModal();
    });

    // ── Period label (mirrors PHP periodLabel()) ──────────────────────────────
    function periodLabel(days) {
        days = parseInt(days) || 0;
        if (days <= 0) return '';
        const months = Math.round(days / 30);
        if (months >= 1 && Math.abs(days - months * 30) <= 1) {
            return months === 1 ? '1 lună' : `${months} luni`;
        }
        return `${days} zile`;
    }

    function updateDaysHint(input) {
        const hint = input.parentElement.querySelector('.days-hint');
        if (hint) hint.textContent = periodLabel(input.value);
    }

    // ── Feature builder ───────────────────────────────────────────────────────
    const FEAT_EMOJIS = {
        'Date':      '📊',
        'Roaming':   '🌍',
        'Minute':    '📞',
        'SMS':       '💬',
        'Viteză':    '⚡',
        'Canale TV': '📺',
    };
    const FEAT_UNITS = {
        'Date':   ' GB',
        'Viteză': ' Mbps',
    };
    const CAT_OPTS = {
        mobile:   [['Date','📊'], ['Roaming','🌍'], ['Minute','📞'], ['SMS','💬']],
        internet: [['Viteză','⚡'], ['Canale TV','📺']],
    };

    function buildFeatureString(row) {
        const sel = row.querySelector('.feat-select').value;
        if (sel === 'altele') {
            const emoji = row.querySelector('.feat-emoji').value.trim();
            const label = row.querySelector('.feat-label-txt').value.trim();
            const num   = parseInt(row.querySelector('.feat-altele-num').value) || 0;
            if (!label) return '';
            const val = num === 0 ? 'Nelimitat' : String(num);
            return `${emoji} ${label}: ${val}`;
        }
        const emoji = FEAT_EMOJIS[sel] || '';
        const unit  = FEAT_UNITS[sel]  || '';
        const num   = parseInt(row.querySelector('.feat-num').value) || 0;
        const val   = num === 0 ? 'Nelimitat' : (num + unit);
        return `${emoji} ${sel}: ${val}`;
    }

    function onFeatSelectChange(sel) {
        const row   = sel.closest('.feature-row');
        const isAlt = sel.value === 'altele';
        row.querySelector('.feat-standard').style.display = isAlt ? 'none' : '';
        row.querySelector('.feat-altele').style.display   = isAlt ? ''     : 'none';
    }

    function addFeature(btn) {
        const list    = btn.previousElementSibling; // .features-list
        const catType = list.dataset.cat;            // 'mobile' or 'internet'
        const opts    = CAT_OPTS[catType] || CAT_OPTS.mobile;

        const optHtml = opts.map(([name]) =>
            `<option value="${name}">${name}</option>`
        ).join('') + '<option value="altele">Altele</option>';

        const row = document.createElement('div');
        row.className = 'feature-row';
        row.innerHTML = `
            <div class="feat-top">
                <select class="feat-select" onchange="onFeatSelectChange(this)">${optHtml}</select>
                <button type="button" class="btn-remove-feature" onclick="removeFeature(this)">✕</button>
            </div>
            <div class="feat-standard">
                <input type="number" class="feat-num" value="0" min="0" placeholder="0">
                <span class="feat-unit-hint">0 = Nelimitat</span>
            </div>
            <div class="feat-altele" style="display:none">
                <input type="text" class="feat-emoji" placeholder="🔥" maxlength="4">
                <input type="text" class="feat-label-txt" placeholder="Label">
                <input type="number" class="feat-altele-num" value="0" min="0" placeholder="0">
            </div>`;
        list.appendChild(row);
        row.querySelector('.feat-num').focus();
    }

    function removeFeature(btn) {
        const row  = btn.closest('.feature-row');
        const list = row.parentElement;
        if (list.querySelectorAll('.feature-row').length > 1) {
            row.remove();
        } else {
            // Keep at least one row — just clear it
            const sel = row.querySelector('.feat-select');
            if (sel) sel.selectedIndex = 0;
            row.querySelectorAll('input[type="number"]').forEach(i => i.value = '0');
            row.querySelectorAll('input[type="text"]').forEach(i => i.value = '');
            onFeatSelectChange(sel);
        }
    }

    // ── Serialize features on submit ──────────────────────────────────────────
    document.querySelectorAll('form.plan-form').forEach(function (form) {
        form.addEventListener('submit', function () {
            // Remove any previously injected hidden inputs
            form.querySelectorAll('input[data-feat]').forEach(el => el.remove());
            // Build one hidden input per non-empty feature row
            form.querySelectorAll('.feature-row').forEach(function (row) {
                const str = buildFeatureString(row);
                if (!str) return;
                const inp = document.createElement('input');
                inp.type  = 'hidden';
                inp.name  = 'features[]';
                inp.setAttribute('data-feat', '1');
                inp.value = str;
                form.appendChild(inp);
            });
        });
    });
</script>
</body>
</html>
<?php
// ── Shared form renderer ──────────────────────────────────────────────────────
function renderPlanForm(?array $plan, string $category): void
{
    $v        = fn(string $key, $default = '') => $plan[$key] ?? $default;
    $isMobile = in_array($category, ['prepay', 'abonament']);
    $catType  = $isMobile ? 'mobile' : 'internet';

    $catOptions = $isMobile
        ? ['Date' => '📊', 'Roaming' => '🌍', 'Minute' => '📞', 'SMS' => '💬']
        : ['Viteză' => '⚡', 'Canale TV' => '📺'];

    // Days value: new data stores int, old data may be a string like 'MDL/lună'
    $daysVal = is_numeric($v('period', 30)) ? (int) $v('period', 30) : 30;

    $featuresList = $v('features', []);
    if (empty($featuresList)) {
        $featuresList = [null]; // ensure at least one empty row
    }
    ?>
    <div class="form-grid">

        <div class="field">
            <label>Operator</label>
            <input type="text" name="operator" value="<?= h($v('operator')) ?>" placeholder="ex: Orange" required>
        </div>

        <div class="field">
            <label>Culoare operator</label>
            <input type="color" name="operator_color" value="<?= h($v('operator_color', '#888888')) ?>">
        </div>

        <div class="field">
            <label>Nume plan</label>
            <input type="text" name="name" value="<?= h($v('name')) ?>" placeholder="ex: One 170" required>
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
                <?php foreach ($featuresList as $feat):
                    if ($feat === null) {
                        $parsed   = ['type' => array_key_first($catOptions), 'emoji' => '', 'label' => '', 'num' => 0];
                        $isAltele = false;
                    } else {
                        $parsed   = parseFeatureString($feat, $catOptions);
                        $isAltele = $parsed['type'] === 'altele';
                    }
                ?>
                <div class="feature-row">
                    <div class="feat-top">
                        <select class="feat-select" onchange="onFeatSelectChange(this)">
                            <?php foreach ($catOptions as $name => $emoji): ?>
                            <option value="<?= h($name) ?>"
                                <?= (!$isAltele && ($parsed['type'] ?? '') === $name) ? 'selected' : '' ?>>
                                <?= h($name) ?>
                            </option>
                            <?php endforeach; ?>
                            <option value="altele" <?= $isAltele ? 'selected' : '' ?>>Altele</option>
                        </select>
                        <button type="button" class="btn-remove-feature" onclick="removeFeature(this)">✕</button>
                    </div>

                    <div class="feat-standard"<?= $isAltele ? ' style="display:none"' : '' ?>>
                        <input type="number" class="feat-num"
                               value="<?= (int) ($parsed['num'] ?? 0) ?>" min="0" placeholder="0">
                        <span class="feat-unit-hint">0 = Nelimitat</span>
                    </div>

                    <div class="feat-altele"<?= !$isAltele ? ' style="display:none"' : '' ?>>
                        <input type="text" class="feat-emoji"
                               value="<?= h($parsed['emoji'] ?? '') ?>" placeholder="🔥" maxlength="4">
                        <input type="text" class="feat-label-txt"
                               value="<?= h($parsed['label'] ?? '') ?>" placeholder="Label">
                        <input type="number" class="feat-altele-num"
                               value="<?= (int) ($parsed['num'] ?? 0) ?>" min="0" placeholder="0">
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <button type="button" class="btn-add-feature" onclick="addFeature(this)">+ Adaugă caracteristică</button>
        </div>

        <div class="field full">
            <label>Link (URL operator)</label>
            <input type="url" name="link" value="<?= h($v('link')) ?>" placeholder="https://...">
        </div>

    </div>
    <?php
}
?>
