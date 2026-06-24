<?php

require __DIR__ . '/../includes/plan_helpers.php';
require __DIR__ . '/../includes/auth.php';

header('Content-Type: application/json; charset=UTF-8');

$dataFiles = planDataFiles();
$labels    = planCategoryLabels();

function respond(int $status, array $payload): void
{
    http_response_code($status);
    echo json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit;
}

function readCategory(array $dataFiles): string
{
    $category = $_REQUEST['category'] ?? '';
    if (!is_string($category) || !array_key_exists($category, $dataFiles)) {
        respond(422, [
            'success' => false,
            'message' => 'Categorie invalidă.',
        ]);
    }

    return $category;
}

function buildCatalogResponse(string $category, array $dataFiles, array $labels, array $plans): array
{
    $sort           = (string) ($_GET['sort'] ?? 'default');
    $operatorValues = $_GET['operators'] ?? [];
    if (!is_array($operatorValues)) {
        $operatorValues = [$operatorValues];
    }

    $filtered = filterPlans($plans, [
        'operators' => $operatorValues,
        'max_price' => $_GET['max_price'] ?? null,
        'min_data'  => $_GET['min_data']  ?? null,
        'min_speed' => $_GET['min_speed'] ?? null,
        'min_minutes' => $_GET['min_minutes'] ?? null,
        'min_sms' => $_GET['min_sms'] ?? null,
        'min_roaming' => $_GET['min_roaming'] ?? null,
        'min_upload' => $_GET['min_upload'] ?? null,
        'min_tv_channels' => $_GET['min_tv_channels'] ?? null,
        'min_hd_channels' => $_GET['min_hd_channels'] ?? null,
    ]);
    $filtered = sortPlans($filtered, $sort);

    return [
        'success'   => true,
        'category'  => $category,
        'label'     => $labels[$category],
        'plans'     => array_map(static fn(array $plan, int $index): array => normalizePlan($plan, $index), $filtered, array_keys($filtered)),
        'all_plans' => array_map(static fn(array $plan, int $index): array => normalizePlan($plan, $index), $plans, array_keys($plans)),
        'meta'      => array_merge(collectCategoryMeta($category, $plans), [
            'total'   => count($plans),
            'visible' => count($filtered),
        ]),
        'counts'    => categoryCounts($dataFiles),
    ];
}

$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

if ($method === 'GET') {
    $category = readCategory($dataFiles);
    $plans    = loadPlans($category);
    respond(200, buildCatalogResponse($category, $dataFiles, $labels, $plans));
}

if ($method === 'POST') {
    // Require authentication for all write operations
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    if (empty($_SESSION['user_id'])) {
        respond(401, ['success' => false, 'message' => 'Autentificare necesară.']);
    }

    $category = readCategory($dataFiles);
    $action   = $_POST['action'] ?? '';

    if ($action === 'create') {
        insertPlan($category, planFromInput($_POST));
        $message = 'Plan adăugat cu succes.';

    } elseif ($action === 'update') {
        $id = isset($_POST['id']) ? (int) $_POST['id'] : 0;
        if ($id <= 0) {
            respond(422, ['success' => false, 'message' => 'ID plan invalid.']);
        }
        updatePlanById($id, $category, planFromInput($_POST));
        $message = 'Plan actualizat cu succes.';

    } elseif ($action === 'delete') {
        $id = isset($_POST['id']) ? (int) $_POST['id'] : 0;
        if ($id <= 0) {
            respond(422, ['success' => false, 'message' => 'ID plan invalid.']);
        }
        if (!deletePlanById($id, $category)) {
            respond(404, ['success' => false, 'message' => 'Planul pentru ștergere nu a fost găsit.']);
        }
        $message = 'Plan șters cu succes.';

    } else {
        respond(422, ['success' => false, 'message' => 'Acțiune invalidă.']);
    }

    $plans    = loadPlans($category);
    $response = buildCatalogResponse($category, $dataFiles, $labels, $plans);
    $response['message'] = $message;
    respond(200, $response);
}

respond(405, ['success' => false, 'message' => 'Metodă HTTP neacceptată.']);
