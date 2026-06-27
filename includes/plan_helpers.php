<?php

    require_once __DIR__ . '/../db/connection.php';

    function planDataFiles(): array
    {
        return [
            'prepay' => 'prepay',
            'abonament' => 'abonament',
            'internet' => 'internet',
            'internet_tv' => 'internet_tv',
        ];
    }

    function planCategoryLabels(): array
    {
        return [
            'prepay' => 'Prepay',
            'abonament' => 'Abonament',
            'internet' => 'Internet',
            'internet_tv' => 'Internet + TV',
        ];
    }

    function loadPlans(string $category): array
    {
        if ($category === 'prepay') {
            return loadPrepayPlans();
        }
        if ($category === 'abonament') {
            return loadAbonamentPlans();
        }
        if ($category === 'internet') {
            return loadInternetPlans();
        }
        if ($category === 'internet_tv') {
            return loadInternetTvPlans();
        }

        $stmt = db()->prepare('SELECT * FROM plans WHERE category = ? ORDER BY id');
        $stmt->execute([$category]);
        $rows = $stmt->fetchAll();

        return array_map(static function (array $row): array {
            $row['features'] = json_decode($row['features'], true) ?? [];
            return $row;
        }, $rows);
    }

    function formatPlanNumber($value): string
    {
        $number = (float) $value;
        return floor($number) === $number
            ? (string) (int) $number
            : rtrim(rtrim(number_format($number, 2, '.', ''), '0'), '.');
    }

    function capitalizeFirstLetter(string $text): string
    {
        return preg_replace_callback(
            '/\p{L}/u',
            static fn(array $match): string => mb_strtoupper($match[0], 'UTF-8'),
            $text,
            1
        ) ?? $text;
    }

    function additionalFeatureToString(array $feature): string
    {
        $icon  = trim((string) ($feature['icon'] ?? ''));
        $label = trim((string) ($feature['label'] ?? ''));
        $value = trim((string) ($feature['value'] ?? ''));
        $unit  = trim((string) ($feature['unit'] ?? ''));
        $prefix = trim($icon . ' ' . $label);
        $detail = trim($value . ($unit !== '' ? ' ' . $unit : ''));

        if ($prefix === '') {
            return capitalizeFirstLetter($detail);
        }

        return capitalizeFirstLetter($detail === '' ? $prefix : "{$prefix}: {$detail}");
    }

    function prepayRowToPlan(array $row): array
    {
        $data = (float) $row['data_gb'];
        $minutes = (int) $row['minutes'];
        $sms = (int) $row['sms'];
        $roaming = (float) $row['roaming_gb'];
        $additional = json_decode((string) $row['additional_features'], true);
        $additional = is_array($additional) ? $additional : [];

        $features = [
            'Date: ' . ($data == 0.0 ? 'Nelimitat' : formatPlanNumber($data) . ' GB'),
            'Minute: ' . ($minutes === 0 ? 'Nelimitat' : "{$minutes} min"),
            'SMS: ' . ($sms === 0 ? 'Nelimitat' : (string) $sms),
        ];

        if ($roaming > 0) {
            $features[] = 'Roaming: ' . number_format($roaming, 2, '.', '') . ' GB';
        }

        foreach ($additional as $feature) {
            if (!is_array($feature)) {
                continue;
            }
            $formatted = additionalFeatureToString($feature);
            if ($formatted !== '') {
                $features[] = $formatted;
            }
        }

        return [
            'id' => (int) $row['id'],
            'category' => 'prepay',
            'operator' => (string) $row['operator'],
            'operator_color' => (string) $row['operator_color'],
            'operator_key' => (string) $row['operator_key'],
            'name' => (string) $row['name'],
            'price' => (float) $row['price'],
            'period' => (int) $row['duration_days'],
            'features' => $features,
            'link' => (string) $row['link'],
            'data_val' => $data,
            'speed_val' => 0,
            'minutes_val' => $minutes,
            'sms_val' => $sms,
            'roaming_val' => $roaming,
            'is_recommended' => (int) ($row['is_recommended'] ?? 0),
            'additional_features' => $additional,
        ];
    }

    function loadPrepayPlans(): array
    {
        $rows = db()->query('SELECT * FROM prepay_plans ORDER BY id')->fetchAll();
        return array_map('prepayRowToPlan', $rows);
    }

    function abonamentRowToPlan(array $row): array
    {
        $plan = prepayRowToPlan($row);
        $plan['category'] = 'abonament';
        return $plan;
    }

    function loadAbonamentPlans(): array
    {
        $rows = db()->query('SELECT * FROM abonament_plans ORDER BY id')->fetchAll();
        return array_map('abonamentRowToPlan', $rows);
    }

    function internetRowToPlan(array $row): array
    {
        $durationMonths = max(1, (int) $row['duration_months']);
        $downloadSpeed = (int) $row['download_speed_mbps'];
        $uploadSpeed = (int) $row['upload_speed_mbps'];
        $installationPrice = (float) $row['installation_price'];
        $routerName = trim((string) ($row['router_name'] ?? ''));
        $additional = json_decode((string) ($row['additional_features'] ?? '[]'), true);
        $additional = is_array($additional) ? $additional : [];

        $features = [
            'Download: ' . $downloadSpeed . ' Mbps',
            'Upload: ' . $uploadSpeed . ' Mbps',
            'Instalare: ' . ($installationPrice == 0.0 ? 'Gratuita' : formatPlanNumber($installationPrice) . ' MDL'),
        ];

        if ($routerName !== '') {
            $features[] = 'Router: ' . $routerName;
        }

        foreach ($additional as $feature) {
            if (!is_array($feature)) {
                continue;
            }
            $formatted = additionalFeatureToString($feature);
            if ($formatted !== '') {
                $features[] = $formatted;
            }
        }

        return [
            'id' => (int) $row['id'],
            'category' => 'internet',
            'operator' => (string) $row['operator'],
            'operator_color' => (string) $row['operator_color'],
            'operator_key' => (string) $row['operator_key'],
            'name' => (string) $row['name'],
            'price' => (float) $row['price'],
            'period' => $durationMonths * 30,
            'features' => $features,
            'link' => (string) ($row['link'] ?? ''),
            'data_val' => 0,
            'speed_val' => $downloadSpeed,
            'duration_months' => $durationMonths,
            'download_speed_mbps' => $downloadSpeed,
            'upload_speed_mbps' => $uploadSpeed,
            'installation_price' => $installationPrice,
            'router_name' => $routerName,
            'is_recommended' => (int) ($row['is_recommended'] ?? 0),
            'additional_features' => $additional,
        ];
    }

    function loadInternetPlans(): array
    {
        $rows = db()->query('SELECT * FROM internet_plans ORDER BY id')->fetchAll();
        return array_map('internetRowToPlan', $rows);
    }

    function internetTvRowToPlan(array $row): array
    {
        $plan = internetRowToPlan($row);
        $tvChannels = (int) $row['tv_channels'];
        $hdChannels = (int) $row['hd_channels'];

        array_splice($plan['features'], 2, 0, [
            'Canale TV: ' . $tvChannels,
            'Canale HD: ' . $hdChannels,
        ]);

        $plan['category'] = 'internet_tv';
        $plan['tv_channels'] = $tvChannels;
        $plan['hd_channels'] = $hdChannels;

        return $plan;
    }

    function loadInternetTvPlans(): array
    {
        $rows = db()->query('SELECT * FROM internet_tv_plans ORDER BY id')->fetchAll();
        return array_map('internetTvRowToPlan', $rows);
    }

    function insertPlan(string $category, array $plan): int
    {
        if ($category === 'prepay') {
            return insertPrepayPlan($plan);
        }
        if ($category === 'abonament') {
            return insertDedicatedMobilePlan('abonament_plans', $plan);
        }
        if ($category === 'internet') {
            return insertInternetPlan($plan);
        }
        if ($category === 'internet_tv') {
            return insertInternetTvPlan($plan);
        }

        $db   = db();
        $stmt = $db->prepare(
            'INSERT INTO plans (category, operator, operator_color, operator_key, name, price, period, features, link, data_val, speed_val)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)'
        );
        $stmt->execute([
            $category,
            $plan['operator'],
            $plan['operator_color'],
            $plan['operator_key'],
            $plan['name'],
            (int) $plan['price'],
            (int) $plan['period'],
            json_encode($plan['features'], JSON_UNESCAPED_UNICODE),
            $plan['link'],
            (int) $plan['data_val'],
            (int) $plan['speed_val'],
        ]);

        return (int) $db->lastInsertId();
    }

    function insertPrepayPlan(array $plan): int
    {
        return insertDedicatedMobilePlan('prepay_plans', $plan);
    }

    function insertDedicatedMobilePlan(string $table, array $plan): int
    {
        if (!in_array($table, ['prepay_plans', 'abonament_plans'], true)) {
            throw new InvalidArgumentException('Invalid mobile plan table.');
        }

        if (!empty($plan['is_recommended'])) {
            db()->exec("UPDATE {$table} SET is_recommended = 0");
        }

        $stmt = db()->prepare(
            "INSERT INTO {$table}
            (operator, operator_key, operator_color, name, price, duration_days, data_gb, minutes, sms, roaming_gb, is_recommended, additional_features, link)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
        );
        $stmt->execute([
            $plan['operator'],
            $plan['operator_key'],
            $plan['operator_color'],
            $plan['name'],
            $plan['price'],
            $plan['duration_days'],
            $plan['data_gb'],
            $plan['minutes'],
            $plan['sms'],
            $plan['roaming_gb'],
            $plan['is_recommended'],
            json_encode($plan['additional_features'], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
            $plan['link'],
        ]);

        return (int) db()->lastInsertId();
    }

    function insertInternetPlan(array $plan): int
    {
        if (!empty($plan['is_recommended'])) {
            db()->exec('UPDATE internet_plans SET is_recommended = 0');
        }

        $stmt = db()->prepare(
            'INSERT INTO internet_plans
            (operator, operator_key, operator_color, name, price, duration_months,
            download_speed_mbps, upload_speed_mbps, installation_price, router_name,
            is_recommended, additional_features, link)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)'
        );
        $stmt->execute([
            $plan['operator'],
            $plan['operator_key'],
            $plan['operator_color'],
            $plan['name'],
            $plan['price'],
            $plan['duration_months'],
            $plan['download_speed_mbps'],
            $plan['upload_speed_mbps'],
            $plan['installation_price'],
            $plan['router_name'] !== '' ? $plan['router_name'] : null,
            $plan['is_recommended'],
            json_encode($plan['additional_features'], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
            $plan['link'] !== '' ? $plan['link'] : null,
        ]);

        return (int) db()->lastInsertId();
    }

    function insertInternetTvPlan(array $plan): int
    {
        if (!empty($plan['is_recommended'])) {
            db()->exec('UPDATE internet_tv_plans SET is_recommended = 0');
        }

        $stmt = db()->prepare(
            'INSERT INTO internet_tv_plans
            (operator, operator_key, operator_color, name, price, duration_months,
            download_speed_mbps, upload_speed_mbps, tv_channels, hd_channels,
            router_name, installation_price, is_recommended, additional_features, link)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)'
        );
        $stmt->execute([
            $plan['operator'],
            $plan['operator_key'],
            $plan['operator_color'],
            $plan['name'],
            $plan['price'],
            $plan['duration_months'],
            $plan['download_speed_mbps'],
            $plan['upload_speed_mbps'],
            $plan['tv_channels'],
            $plan['hd_channels'],
            $plan['router_name'] !== '' ? $plan['router_name'] : null,
            $plan['installation_price'],
            $plan['is_recommended'],
            json_encode($plan['additional_features'], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
            $plan['link'] !== '' ? $plan['link'] : null,
        ]);

        return (int) db()->lastInsertId();
    }

    function updatePlanById(int $id, string $category, array $plan): void
    {
        if ($category === 'prepay') {
            updatePrepayPlanById($id, $plan);
            return;
        }
        if ($category === 'abonament') {
            updateDedicatedMobilePlanById('abonament_plans', $id, $plan);
            return;
        }
        if ($category === 'internet') {
            updateInternetPlanById($id, $plan);
            return;
        }
        if ($category === 'internet_tv') {
            updateInternetTvPlanById($id, $plan);
            return;
        }

        $stmt = db()->prepare(
            'UPDATE plans
            SET operator=?, operator_color=?, operator_key=?, name=?, price=?, period=?, features=?, link=?, data_val=?, speed_val=?
            WHERE id=? AND category=?'
        );
        $stmt->execute([
            $plan['operator'],
            $plan['operator_color'],
            $plan['operator_key'],
            $plan['name'],
            (int) $plan['price'],
            (int) $plan['period'],
            json_encode($plan['features'], JSON_UNESCAPED_UNICODE),
            $plan['link'],
            (int) $plan['data_val'],
            (int) $plan['speed_val'],
            $id,
            $category,
        ]);
    }

    function updatePrepayPlanById(int $id, array $plan): void
    {
        updateDedicatedMobilePlanById('prepay_plans', $id, $plan);
    }

    function updateDedicatedMobilePlanById(string $table, int $id, array $plan): void
    {
        if (!in_array($table, ['prepay_plans', 'abonament_plans'], true)) {
            throw new InvalidArgumentException('Invalid mobile plan table.');
        }

        if (!empty($plan['is_recommended'])) {
            db()->exec("UPDATE {$table} SET is_recommended = 0");
        }

        $stmt = db()->prepare(
            "UPDATE {$table}
            SET operator=?, operator_key=?, operator_color=?, name=?, price=?, duration_days=?,
                data_gb=?, minutes=?, sms=?, roaming_gb=?, is_recommended=?, additional_features=?, link=?
            WHERE id=?"
        );
        $stmt->execute([
            $plan['operator'],
            $plan['operator_key'],
            $plan['operator_color'],
            $plan['name'],
            $plan['price'],
            $plan['duration_days'],
            $plan['data_gb'],
            $plan['minutes'],
            $plan['sms'],
            $plan['roaming_gb'],
            $plan['is_recommended'],
            json_encode($plan['additional_features'], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
            $plan['link'],
            $id,
        ]);
    }

    function updateInternetPlanById(int $id, array $plan): void
    {
        if (!empty($plan['is_recommended'])) {
            db()->exec('UPDATE internet_plans SET is_recommended = 0');
        }

        $stmt = db()->prepare(
            'UPDATE internet_plans
            SET operator=?, operator_key=?, operator_color=?, name=?, price=?, duration_months=?,
                download_speed_mbps=?, upload_speed_mbps=?, installation_price=?, router_name=?,
                is_recommended=?, additional_features=?, link=?
            WHERE id=?'
        );
        $stmt->execute([
            $plan['operator'],
            $plan['operator_key'],
            $plan['operator_color'],
            $plan['name'],
            $plan['price'],
            $plan['duration_months'],
            $plan['download_speed_mbps'],
            $plan['upload_speed_mbps'],
            $plan['installation_price'],
            $plan['router_name'] !== '' ? $plan['router_name'] : null,
            $plan['is_recommended'],
            json_encode($plan['additional_features'], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
            $plan['link'] !== '' ? $plan['link'] : null,
            $id,
        ]);
    }

    function updateInternetTvPlanById(int $id, array $plan): void
    {
        if (!empty($plan['is_recommended'])) {
            db()->exec('UPDATE internet_tv_plans SET is_recommended = 0');
        }

        $stmt = db()->prepare(
            'UPDATE internet_tv_plans
            SET operator=?, operator_key=?, operator_color=?, name=?, price=?, duration_months=?,
                download_speed_mbps=?, upload_speed_mbps=?, tv_channels=?, hd_channels=?,
                router_name=?, installation_price=?, is_recommended=?, additional_features=?, link=?
            WHERE id=?'
        );
        $stmt->execute([
            $plan['operator'],
            $plan['operator_key'],
            $plan['operator_color'],
            $plan['name'],
            $plan['price'],
            $plan['duration_months'],
            $plan['download_speed_mbps'],
            $plan['upload_speed_mbps'],
            $plan['tv_channels'],
            $plan['hd_channels'],
            $plan['router_name'] !== '' ? $plan['router_name'] : null,
            $plan['installation_price'],
            $plan['is_recommended'],
            json_encode($plan['additional_features'], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
            $plan['link'] !== '' ? $plan['link'] : null,
            $id,
        ]);
    }

    function deletePlanById(int $id, string $category): bool
    {
        if ($category === 'prepay') {
            $stmt = db()->prepare('DELETE FROM prepay_plans WHERE id = ?');
            $stmt->execute([$id]);
            return $stmt->rowCount() > 0;
        }
        if ($category === 'abonament') {
            $stmt = db()->prepare('DELETE FROM abonament_plans WHERE id = ?');
            $stmt->execute([$id]);
            return $stmt->rowCount() > 0;
        }
        if ($category === 'internet') {
            $stmt = db()->prepare('DELETE FROM internet_plans WHERE id = ?');
            $stmt->execute([$id]);
            return $stmt->rowCount() > 0;
        }
        if ($category === 'internet_tv') {
            $stmt = db()->prepare('DELETE FROM internet_tv_plans WHERE id = ?');
            $stmt->execute([$id]);
            return $stmt->rowCount() > 0;
        }

        $stmt = db()->prepare('DELETE FROM plans WHERE id = ? AND category = ?');
        $stmt->execute([$id, $category]);

        return $stmt->rowCount() > 0;
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

        return (string) $period;
    }

    function isMobileCategory(string $category): bool
    {
        return in_array($category, ['prepay', 'abonament'], true);
    }

    function parseFeatureString(string $feature, array $categoryOptions): array
    {
        $pos = strpos($feature, ': ');
        if ($pos === false) {
            return ['type' => 'altele', 'emoji' => '', 'label' => $feature, 'num' => 0];
        }

        $prefix      = trim(substr($feature, 0, $pos));
        $value       = trim(substr($feature, $pos + 2));
        $isUnlimited = mb_stripos($value, 'nelimitat') !== false;
        preg_match('/(\d+)/', $value, $matches);
        $num = $isUnlimited ? 0 : (int) ($matches[1] ?? 0);

        foreach ($categoryOptions as $name => $emoji) {
            if ($prefix === $emoji . ' ' . $name) {
                return ['type' => $name, 'emoji' => $emoji, 'label' => $name, 'num' => $num];
            }
        }

        if (preg_match('/^(\S+)\s*(.*)/su', $prefix, $matches)) {
            return ['type' => 'altele', 'emoji' => trim($matches[1]), 'label' => trim($matches[2]), 'num' => $num];
        }

        return ['type' => 'altele', 'emoji' => '', 'label' => $prefix, 'num' => $num];
    }

    function planFromInput(array $input): array
    {
        if (in_array(($input['category'] ?? ''), ['prepay', 'abonament'], true)) {
            return prepayPlanFromInput($input);
        }
        if (($input['category'] ?? '') === 'internet') {
            return internetPlanFromInput($input);
        }
        if (($input['category'] ?? '') === 'internet_tv') {
            return internetTvPlanFromInput($input);
        }

        $features = array_values(array_filter(
            array_map('trim', (array) ($input['features'] ?? [])),
            static fn($feature) => $feature !== ''
        ));

        $operator    = trim((string) ($input['operator'] ?? ''));
        $operatorKey = strtolower(preg_replace('/\s+/', '_', $operator));

        return [
            'operator' => $operator,
            'operator_color' => trim((string) ($input['operator_color'] ?? '#000000')),
            'operator_key' => $operatorKey,
            'name' => trim((string) ($input['name'] ?? '')),
            'price' => (int) ($input['price'] ?? 0),
            'period' => (int) ($input['days'] ?? 30),
            'features' => $features,
            'link' => trim((string) ($input['link'] ?? '')),
            'data_val' => (int) ($input['data_val'] ?? 0),
            'speed_val' => (int) ($input['speed_val'] ?? 0),
        ];
    }

    function prepayPlanFromInput(array $input): array
    {
        $operator = trim((string) ($input['operator'] ?? ''));
        $additional = json_decode((string) ($input['additional_features'] ?? '[]'), true);
        if (!is_array($additional)) {
            $additional = [];
        }

        $additional = array_values(array_filter(array_map(static function ($feature): ?array {
            if (!is_array($feature)) {
                return null;
            }

            $normalized = [
                'label' => trim((string) ($feature['label'] ?? '')),
            ];

            return $normalized['label'] === '' ? null : $normalized;
        }, $additional)));

        return [
            'operator' => $operator,
            'operator_key' => trim(strtolower(preg_replace('/[^a-z0-9]+/i', '_', $operator)), '_'),
            'operator_color' => trim((string) ($input['operator_color'] ?? '#888888')),
            'name' => trim((string) ($input['name'] ?? '')),
            'price' => max(0, (float) ($input['price'] ?? 0)),
            'duration_days' => max(1, (int) ($input['duration_days'] ?? 30)),
            'data_gb' => max(0, (float) ($input['data_gb'] ?? 0)),
            'minutes' => max(0, (int) ($input['minutes'] ?? 0)),
            'sms' => max(0, (int) ($input['sms'] ?? 0)),
            'roaming_gb' => max(0, (float) ($input['roaming_gb'] ?? 0)),
            'is_recommended' => isset($input['is_recommended']) ? 1 : 0,
            'additional_features' => $additional,
            'link' => trim((string) ($input['link'] ?? '')),
        ];
    }

    function normalizeAdditionalFeatures($value): array
    {
        $additional = json_decode((string) $value, true);
        if (!is_array($additional)) {
            return [];
        }

        return array_values(array_filter(array_map(static function ($feature): ?array {
            if (!is_array($feature)) {
                return null;
            }

            $label = trim((string) ($feature['label'] ?? ''));
            return $label === '' ? null : ['label' => $label];
        }, $additional)));
    }

    function internetPlanFromInput(array $input): array
    {
        $operator = trim((string) ($input['operator'] ?? ''));

        return [
            'operator' => $operator,
            'operator_key' => trim(strtolower(preg_replace('/[^a-z0-9]+/i', '_', $operator)), '_'),
            'operator_color' => trim((string) ($input['operator_color'] ?? '#888888')),
            'name' => trim((string) ($input['name'] ?? '')),
            'price' => max(0, (float) ($input['price'] ?? 0)),
            'duration_months' => max(1, (int) ($input['duration_months'] ?? 1)),
            'download_speed_mbps' => max(0, (int) ($input['download_speed_mbps'] ?? 0)),
            'upload_speed_mbps' => max(0, (int) ($input['upload_speed_mbps'] ?? 0)),
            'installation_price' => max(0, (float) ($input['installation_price'] ?? 0)),
            'router_name' => trim((string) ($input['router_name'] ?? '')),
            'is_recommended' => isset($input['is_recommended']) ? 1 : 0,
            'additional_features' => normalizeAdditionalFeatures($input['additional_features'] ?? '[]'),
            'link' => trim((string) ($input['link'] ?? '')),
        ];
    }

    function internetTvPlanFromInput(array $input): array
    {
        $plan = internetPlanFromInput($input);
        $plan['tv_channels'] = max(0, (int) ($input['tv_channels'] ?? 0));
        $plan['hd_channels'] = max(0, (int) ($input['hd_channels'] ?? 0));
        return $plan;
    }

    function extractFeatureValue(array $features, string $emoji): string
    {
        foreach ($features as $feature) {
            if (mb_strpos($feature, $emoji) === 0) {
                $pos = strpos($feature, ': ');
                if ($pos !== false) {
                    return trim(substr($feature, $pos + 2));
                }
            }
        }

        return '-';
    }

    function normalizePlan(array $plan, int $index): array
    {
        return [
            'id' => (int) ($plan['id'] ?? 0),
            'index' => $index,
            'operator' => (string) $plan['operator'],
            'operator_color' => (string) $plan['operator_color'],
            'operator_key' => (string) $plan['operator_key'],
            'name' => (string) $plan['name'],
            'price' => (float) $plan['price'],
            'period' => $plan['period'],
            'period_display' => displayPeriod($plan['period']),
            'features' => array_values((array) $plan['features']),
            'link' => (string) $plan['link'],
            'data_val' => (float) ($plan['data_val'] ?? 0),
            'speed_val' => (int) ($plan['speed_val'] ?? 0),
            'minutes_val' => (int) ($plan['minutes_val'] ?? 0),
            'sms_val' => (int) ($plan['sms_val'] ?? 0),
            'roaming_val' => (float) ($plan['roaming_val'] ?? 0),
            'duration_months' => (int) ($plan['duration_months'] ?? 0),
            'download_speed_mbps' => (int) ($plan['download_speed_mbps'] ?? $plan['speed_val'] ?? 0),
            'upload_speed_mbps' => (int) ($plan['upload_speed_mbps'] ?? 0),
            'installation_price' => (float) ($plan['installation_price'] ?? 0),
            'router_name' => (string) ($plan['router_name'] ?? ''),
            'tv_channels' => (int) ($plan['tv_channels'] ?? 0),
            'hd_channels' => (int) ($plan['hd_channels'] ?? 0),
            'is_recommended' => (int) ($plan['is_recommended'] ?? 0),
            'additional_features' => array_values((array) ($plan['additional_features'] ?? [])),
            'minutes' => extractFeatureValue((array) $plan['features'], 'Minute'),
            'sms' => extractFeatureValue((array) $plan['features'], 'SMS'),
        ];
    }

    function collectCategoryMeta(string $category, array $plans): array
    {
        $operators = [];
        $prices = [];
        $dataValues = [];
        $speedValues = [];
        $minutesValues = [];
        $smsValues = [];
        $roamingValues = [];
        $uploadValues = [];
        $tvValues = [];
        $hdValues = [];

        foreach ($plans as $plan) {
            $key = $plan['operator_key'];
            if (!isset($operators[$key])) {
                $operators[$key] = [
                    'key' => $key,
                    'name' => $plan['operator'],
                    'color' => $plan['operator_color'],
                ];
            }

            $prices[] = (int) $plan['price'];
            $dataValues[] = (int) ($plan['data_val'] ?? 0);
            $speedValues[] = (int) ($plan['speed_val'] ?? 0);
            $minutesValues[] = (int) ($plan['minutes_val'] ?? 0);
            $smsValues[] = (int) ($plan['sms_val'] ?? 0);
            $roamingValues[] = (float) ($plan['roaming_val'] ?? 0);
            $uploadValues[] = (int) ($plan['upload_speed_mbps'] ?? 0);
            $tvValues[] = (int) ($plan['tv_channels'] ?? 0);
            $hdValues[] = (int) ($plan['hd_channels'] ?? 0);
        }

        $maxPrice = $prices ? max($prices) : 0;
        $maxData  = $dataValues ? max($dataValues) : 0;
        $maxSpeed = $speedValues ? max($speedValues) : 0;
        $maxMinutes = $minutesValues ? max($minutesValues) : 0;
        $maxSms = $smsValues ? max($smsValues) : 0;
        $maxRoaming = $roamingValues ? max($roamingValues) : 0;
        $maxUpload = $uploadValues ? max($uploadValues) : 0;
        $maxTv = $tvValues ? max($tvValues) : 0;
        $maxHd = $hdValues ? max($hdValues) : 0;

        if ($category === 'internet') {
            $maxPrice = max(350, (int) (ceil($maxPrice / 10) * 10));
            $maxSpeed = max(2000, (int) (ceil($maxSpeed / 100) * 100));
            $maxUpload = max(1000, (int) (ceil($maxUpload / 100) * 100));
        } elseif ($category === 'internet_tv') {
            $maxPrice = max(600, (int) (ceil($maxPrice / 10) * 10));
            $maxSpeed = max(2000, (int) (ceil($maxSpeed / 100) * 100));
            $maxUpload = max(1000, (int) (ceil($maxUpload / 100) * 100));
            $maxTv = max(200, (int) (ceil($maxTv / 10) * 10));
            $maxHd = max(100, (int) (ceil($maxHd / 10) * 10));
        } elseif ($category === 'abonament') {
            $maxPrice = max(200, (int) (ceil($maxPrice / 5) * 5));
            $maxData  = max(200, (int) (ceil($maxData / 10) * 10));
            $maxMinutes = max(1000, (int) (ceil($maxMinutes / 100) * 100));
            $maxSms = max(1000, (int) (ceil($maxSms / 100) * 100));
            $maxRoaming = max(20, (float) ceil($maxRoaming));
        } else {
            $maxPrice = max(180, (int) (ceil($maxPrice / 5) * 5));
            $maxData  = max(100, (int) (ceil($maxData / 5) * 5));
            $maxMinutes = max(1000, (int) (ceil($maxMinutes / 100) * 100));
            $maxSms = max(1000, (int) (ceil($maxSms / 100) * 100));
            $maxRoaming = max(20, (float) ceil($maxRoaming));
        }

        return [
            'operators' => array_values($operators),
            'bounds' => [
                'min_price' => 0,
                'max_price' => $maxPrice,
                'min_data' => 0,
                'max_data' => $maxData,
                'min_speed' => 0,
                'max_speed' => $maxSpeed,
                'min_minutes' => 0,
                'max_minutes' => $maxMinutes,
                'min_sms' => 0,
                'max_sms' => $maxSms,
                'min_roaming' => 0,
                'max_roaming' => $maxRoaming,
                'min_upload' => 0,
                'max_upload' => $maxUpload,
                'min_tv_channels' => 0,
                'max_tv_channels' => $maxTv,
                'min_hd_channels' => 0,
                'max_hd_channels' => $maxHd,
            ],
            'is_mobile' => isMobileCategory($category),
        ];
    }

    function filterPlans(array $plans, array $filters): array
    {
        $operators = array_values(array_filter(array_map('strval', $filters['operators'] ?? [])));
        $maxPrice = isset($filters['max_price']) ? (int) $filters['max_price'] : PHP_INT_MAX;
        $minData = isset($filters['min_data'])  ? (int) $filters['min_data']  : 0;
        $minSpeed = isset($filters['min_speed']) ? (int) $filters['min_speed'] : 0;
        $minMinutes = isset($filters['min_minutes']) ? (int) $filters['min_minutes'] : 0;
        $minSms = isset($filters['min_sms']) ? (int) $filters['min_sms'] : 0;
        $minRoaming = isset($filters['min_roaming']) ? (float) $filters['min_roaming'] : 0;
        $minUpload = isset($filters['min_upload']) ? (int) $filters['min_upload'] : 0;
        $minTv = isset($filters['min_tv_channels']) ? (int) $filters['min_tv_channels'] : 0;
        $minHd = isset($filters['min_hd_channels']) ? (int) $filters['min_hd_channels'] : 0;

        return array_values(array_filter($plans, static function (array $plan) use ($operators, $maxPrice, $minData, $minSpeed, $minMinutes, $minSms, $minRoaming, $minUpload, $minTv, $minHd): bool {
            $operatorOk = !$operators || in_array($plan['operator_key'], $operators, true);
            $priceOk = (int) $plan['price'] <= $maxPrice;
            $dataOk = (int) ($plan['data_val'] ?? 0) >= $minData;
            $speedOk = (int) ($plan['speed_val'] ?? 0) >= $minSpeed;
            $minutesOk = (int) ($plan['minutes_val'] ?? 0) >= $minMinutes;
            $smsOk = (int) ($plan['sms_val'] ?? 0) >= $minSms;
            $roamingOk = (float) ($plan['roaming_val'] ?? 0) >= $minRoaming;
            $uploadOk = (int) ($plan['upload_speed_mbps'] ?? 0) >= $minUpload;
            $tvOk = (int) ($plan['tv_channels'] ?? 0) >= $minTv;
            $hdOk = (int) ($plan['hd_channels'] ?? 0) >= $minHd;

            return $operatorOk && $priceOk && $dataOk && $speedOk && $minutesOk && $smsOk && $roamingOk && $uploadOk && $tvOk && $hdOk;
        }));
    }

    function sortPlans(array $plans, string $sort): array
    {
        $sorted = $plans;

        usort($sorted, static function (array $a, array $b) use ($sort): int {
            $aPrice = (float) $a['price'];
            $bPrice = (float) $b['price'];
            $aSpeed = (int) ($a['speed_val'] ?? 0);
            $bSpeed = (int) ($b['speed_val'] ?? 0);
            $aData = (int) ($a['data_val'] ?? 0);
            $bData = (int) ($b['data_val'] ?? 0);

            if ($sort === '' || $sort === 'default') {
                $operatorOrder = strnatcasecmp((string) $a['operator'], (string) $b['operator']);
                if ($operatorOrder !== 0) {
                    return $operatorOrder;
                }

                $priceOrder = $aPrice <=> $bPrice;
                return $priceOrder !== 0
                    ? $priceOrder
                    : ((int) ($a['id'] ?? 0) <=> (int) ($b['id'] ?? 0));
            }

            if ($sort === 'price-asc')  return $aPrice <=> $bPrice;
            if ($sort === 'price-desc') return $bPrice <=> $aPrice;
            if ($sort === 'speed-desc') return $bSpeed <=> $aSpeed;
            if ($sort === 'data-desc')  return $bData  <=> $aData;

            return 0;
        });

        return $sorted;
    }

    function categoryCounts(array $dataFiles): array
    {
        $counts = array_fill_keys(array_keys($dataFiles), 0);

        $counts['prepay'] = (int) db()->query('SELECT COUNT(*) FROM prepay_plans')->fetchColumn();
        $counts['abonament'] = (int) db()->query('SELECT COUNT(*) FROM abonament_plans')->fetchColumn();
        $counts['internet'] = (int) db()->query('SELECT COUNT(*) FROM internet_plans')->fetchColumn();
        $counts['internet_tv'] = (int) db()->query('SELECT COUNT(*) FROM internet_tv_plans')->fetchColumn();

        return $counts;
    }
