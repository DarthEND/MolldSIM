<?php
header('Content-Type: text/css; charset=UTF-8');

$dataFiles = [
    __DIR__ . '/data/prepay_plans.php',
    __DIR__ . '/data/abonament_plans.php',
    __DIR__ . '/data/internet_plans.php',
    __DIR__ . '/data/internet_tv_plans.php',
];

// Collect unique operators across all categories
$operators = [];
foreach ($dataFiles as $file) {
    $plans = require $file;
    foreach ($plans as $plan) {
        $key = $plan['operator_key'];
        if (!isset($operators[$key])) {
            $operators[$key] = $plan['operator_color'];
        }
    }
}

// Output a CSS rule block for each operator
foreach ($operators as $key => $color) {
    // Sanitize: key must be a valid CSS class name, color must be a valid hex/named color
    $safeKey   = preg_replace('/[^a-z0-9_-]/i', '', $key);
    $safeColor = preg_replace('/[^a-zA-Z0-9#()%., ]/', '', $color);

    if ($safeKey === '' || $safeColor === '') continue;

    echo ".plan-button.{$safeKey} {\n";
    echo "  border: 1px solid {$safeColor};\n";
    echo "  color: {$safeColor};\n";
    echo "}\n";
    echo ".plan-button.{$safeKey}:hover {\n";
    echo "  background-color: {$safeColor};\n";
    echo "  color: #fff;\n";
    echo "}\n";
}
