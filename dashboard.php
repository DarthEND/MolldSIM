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
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --bg:        #0f1117;
            --surface:   #1a1d27;
            --surface2:  #22263a;
            --border:    #2e3250;
            --accent:    #6c63ff;
            --accent2:   #a78bfa;
            --green:     #22c55e;
            --red:       #ef4444;
            --yellow:    #f59e0b;
            --text:      #e2e8f0;
            --muted:     #8892a4;
            --radius:    10px;
            --shadow:    0 4px 24px rgba(0,0,0,.45);
        }

        body { font-family: 'Segoe UI', system-ui, sans-serif; background: var(--bg); color: var(--text); min-height: 100vh; }

        /* ── Layout ── */
        .layout { display: flex; min-height: 100vh; }

        /* ── Sidebar ── */
        .sidebar {
            width: 220px; flex-shrink: 0;
            background: var(--surface);
            border-right: 1px solid var(--border);
            display: flex; flex-direction: column;
            padding: 1.5rem 0;
            position: sticky; top: 0; height: 100vh;
        }
        .sidebar-logo {
            font-size: 1.3rem; font-weight: 800;
            color: var(--accent2); letter-spacing: .03em;
            padding: 0 1.4rem 1.6rem;
            border-bottom: 1px solid var(--border);
        }
        .sidebar-logo span { color: var(--muted); font-weight: 400; font-size: .75rem; display: block; margin-top: .2rem; }
        .sidebar-nav { padding: 1.2rem 0; flex: 1; }
        .sidebar-nav a {
            display: flex; align-items: center; gap: .7rem;
            padding: .65rem 1.4rem;
            color: var(--muted); text-decoration: none;
            font-size: .9rem; font-weight: 500;
            border-left: 3px solid transparent;
            transition: all .15s;
        }
        .sidebar-nav a:hover { color: var(--text); background: var(--surface2); }
        .sidebar-nav a.active { color: var(--accent2); border-left-color: var(--accent); background: rgba(108,99,255,.08); }
        .sidebar-nav .badge {
            margin-left: auto; background: var(--surface2);
            color: var(--muted); font-size: .72rem;
            padding: .15rem .5rem; border-radius: 99px;
        }
        .sidebar-nav a.active .badge { background: rgba(108,99,255,.25); color: var(--accent2); }
        .sidebar-back { padding: 1.2rem 1.4rem 0; }
        .sidebar-back a {
            display: flex; align-items: center; gap: .5rem;
            color: var(--muted); font-size: .82rem; text-decoration: none;
        }
        .sidebar-back a:hover { color: var(--text); }

        /* ── Main ── */
        .main { flex: 1; overflow-x: hidden; }

        /* ── Top bar ── */
        .topbar {
            display: flex; align-items: center; justify-content: space-between;
            padding: 1.1rem 2rem;
            border-bottom: 1px solid var(--border);
            background: var(--surface);
            position: sticky; top: 0; z-index: 10;
        }
        .topbar h1 { font-size: 1.15rem; font-weight: 700; }
        .topbar .category-label { font-size: .8rem; color: var(--muted); margin-top: .1rem; }
        .btn {
            display: inline-flex; align-items: center; gap: .4rem;
            padding: .55rem 1.1rem; border-radius: var(--radius);
            font-size: .85rem; font-weight: 600; cursor: pointer;
            border: none; text-decoration: none; transition: opacity .15s;
        }
        .btn:hover { opacity: .85; }
        .btn-primary { background: var(--accent);  color: #fff; }
        .btn-danger  { background: var(--red);     color: #fff; padding: .35rem .8rem; font-size: .78rem; }
        .btn-edit    { background: var(--surface2); color: var(--accent2); padding: .35rem .8rem; font-size: .78rem; border: 1px solid var(--border); }
        .btn-ghost   { background: transparent; color: var(--muted); border: 1px solid var(--border); }

        /* ── Content area ── */
        .content { padding: 2rem; }

        /* ── Stats row ── */
        .stats-row { display: flex; gap: 1rem; margin-bottom: 2rem; flex-wrap: wrap; }
        .stat-card {
            flex: 1; min-width: 130px;
            background: var(--surface); border: 1px solid var(--border);
            border-radius: var(--radius); padding: 1rem 1.3rem;
        }
        .stat-card .stat-label { font-size: .75rem; color: var(--muted); text-transform: uppercase; letter-spacing: .05em; }
        .stat-card .stat-value { font-size: 1.9rem; font-weight: 800; color: var(--accent2); line-height: 1.2; }
        .stat-card .stat-sub   { font-size: .75rem; color: var(--muted); margin-top: .1rem; }

        /* ── Flash ── */
        .flash {
            display: flex; align-items: center; gap: .7rem;
            padding: .85rem 1.2rem; border-radius: var(--radius);
            margin-bottom: 1.5rem; font-size: .9rem; font-weight: 500;
        }
        .flash.success { background: rgba(34,197,94,.12); border: 1px solid rgba(34,197,94,.3); color: #86efac; }
        .flash.error   { background: rgba(239,68,68,.12);  border: 1px solid rgba(239,68,68,.3);  color: #fca5a5; }

        /* ── Table ── */
        .table-wrap { background: var(--surface); border: 1px solid var(--border); border-radius: var(--radius); overflow: hidden; }
        .table-header {
            display: flex; align-items: center; justify-content: space-between;
            padding: 1rem 1.4rem;
            border-bottom: 1px solid var(--border);
        }
        .table-header h2 { font-size: 1rem; font-weight: 700; }
        .table-header .count { font-size: .8rem; color: var(--muted); }
        table { width: 100%; border-collapse: collapse; }
        th {
            text-align: left; padding: .7rem 1rem;
            font-size: .73rem; font-weight: 600; color: var(--muted);
            text-transform: uppercase; letter-spacing: .06em;
            border-bottom: 1px solid var(--border);
            background: rgba(255,255,255,.02);
        }
        td { padding: .75rem 1rem; font-size: .87rem; border-bottom: 1px solid var(--border); vertical-align: middle; }
        tr:last-child td { border-bottom: none; }
        tr:hover td { background: rgba(255,255,255,.02); }

        .op-badge {
            display: inline-block; padding: .25rem .65rem;
            border-radius: 99px; font-size: .73rem; font-weight: 700;
            color: #fff; white-space: nowrap;
        }
        .plan-name-cell { font-weight: 600; }
        .price-cell { font-weight: 700; color: var(--accent2); }
        .muted-cell { color: var(--muted); font-size: .8rem; }
        .features-preview {
            max-width: 260px; font-size: .78rem; color: var(--muted);
            white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
        }
        .actions-cell { display: flex; gap: .4rem; align-items: center; }

        /* ── Modal ── */
        .modal-backdrop {
            display: none; position: fixed; inset: 0;
            background: rgba(0,0,0,.65); z-index: 100;
            align-items: center; justify-content: center;
        }
        .modal-backdrop.open { display: flex; }
        .modal {
            background: var(--surface); border: 1px solid var(--border);
            border-radius: 14px; box-shadow: var(--shadow);
            width: 100%; max-width: 640px; max-height: 90vh;
            overflow-y: auto; padding: 2rem;
        }
        .modal-title { font-size: 1.15rem; font-weight: 700; margin-bottom: 1.5rem; }

        /* ── Form ── */
        .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
        .form-grid .full { grid-column: 1 / -1; }
        .field label {
            display: block; font-size: .78rem; font-weight: 600; color: var(--muted);
            text-transform: uppercase; letter-spacing: .05em; margin-bottom: .4rem;
        }
        .field input, .field select, .field textarea {
            width: 100%; padding: .6rem .85rem;
            background: var(--bg); border: 1px solid var(--border);
            border-radius: 7px; color: var(--text); font-size: .88rem;
            outline: none; transition: border-color .15s;
            font-family: inherit;
        }
        .field input:focus, .field select:focus, .field textarea:focus { border-color: var(--accent); }
        .field input[type="color"] { height: 40px; padding: .2rem .4rem; cursor: pointer; }
        .field-hint { font-size: .73rem; color: var(--muted); margin-top: .3rem; }
        .days-hint { color: var(--accent2); font-weight: 600; }

        /* ── Feature builder ── */
        .features-list { display: flex; flex-direction: column; gap: .55rem; margin-bottom: .5rem; }
        .feature-row {
            display: flex; flex-direction: column; gap: .4rem;
            background: rgba(255,255,255,.02); border: 1px solid var(--border);
            border-radius: 8px; padding: .5rem;
        }
        .feat-top { display: flex; gap: .4rem; align-items: center; }
        .feat-select { flex: 1; }
        .feat-standard { display: flex; align-items: center; gap: .4rem; }
        .feat-standard .feat-num { flex: 1; }
        .feat-unit-hint { font-size: .71rem; color: var(--muted); white-space: nowrap; flex-shrink: 0; }
        .feat-altele { display: flex; gap: .3rem; align-items: center; }
        .feat-emoji { width: 52px !important; flex-shrink: 0; text-align: center; }
        .feat-label-txt { flex: 1; }
        .feat-altele-num { width: 76px !important; flex-shrink: 0; }
        .btn-remove-feature {
            flex-shrink: 0; width: 30px; height: 30px;
            background: rgba(239,68,68,.12); border: 1px solid rgba(239,68,68,.3);
            color: #fca5a5; border-radius: 6px; cursor: pointer;
            font-size: .8rem; display: flex; align-items: center; justify-content: center;
            transition: background .15s;
        }
        .btn-remove-feature:hover { background: rgba(239,68,68,.28); }
        .btn-add-feature {
            margin-top: .1rem; padding: .42rem .9rem;
            background: rgba(108,99,255,.1); border: 1px dashed var(--accent);
            color: var(--accent2); border-radius: 7px; cursor: pointer;
            font-size: .82rem; font-weight: 600; width: 100%;
            transition: background .15s;
        }
        .btn-add-feature:hover { background: rgba(108,99,255,.2); }

        /* ── Form actions ── */
        .form-actions {
            display: flex; gap: .7rem; justify-content: flex-end;
            margin-top: 1.5rem; padding-top: 1.2rem;
            border-top: 1px solid var(--border);
        }

        /* ── Delete confirm ── */
        .delete-form { display: inline; }

        /* ── Responsive ── */
        @media (max-width: 768px) {
            .sidebar { display: none; }
            .content { padding: 1rem; }
            .stats-row { flex-direction: column; }
            .form-grid { grid-template-columns: 1fr; }
        }
    </style>
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
