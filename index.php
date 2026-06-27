<?php
    $pageTitle = 'MolldSIM - Compară servicii telecom din Moldova';
    $pageDescription = 'Compară planuri Prepay, abonamente mobile, internet și televiziune de la operatorii din Moldova.';
    $basePath = '';
    require __DIR__ . '/includes/plan_helpers.php';

    function homeH($value): string
    {
        return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
    }

    function homeCategoryUrls(): array
    {
        return [
            'prepay'      => 'pages/prepay.php',
            'abonament'   => 'pages/abonament.php',
            'internet'    => 'pages/internet.php',
            'internet_tv' => 'pages/internet-tv.php',
        ];
    }

    function homeMaxValue(array $plans, string $key): float
    {
        return array_reduce($plans, static function (float $max, array $plan) use ($key): float {
            return max($max, (float) ($plan[$key] ?? 0));
        }, 0.0);
    }

    function homeMinPrice(array $plans): float
    {
        $prices = array_filter(array_map(static function (array $plan): float {
            return (float) ($plan['price'] ?? 0);
        }, $plans), static fn(float $price): bool => $price > 0);

        return $prices ? min($prices) : 0.0;
    }

    function homeSpeedLabel(float $speed): string
    {
        if ($speed >= 1000) {
            return formatPlanNumber($speed / 1000) . ' Gbps';
        }

        return formatPlanNumber($speed) . ' Mbps';
    }

    function homePlanCountLabel(int $count): string
    {
        return $count === 1 ? '1 plan disponibil' : "{$count} planuri disponibile";
    }

    function homeUnlimitedAwareValue(array $plan, string $key, float $max): float
    {
        $value = (float) ($plan[$key] ?? 0);
        return $value == 0.0 && $max > 0 ? $max * 1.25 : $value;
    }

    function homePlanScore(array $plan, string $category, array $max): float
    {
        $price = max((float) ($plan['price'] ?? 0), 1.0);

        if ($category === 'prepay' || $category === 'abonament') {
            $benefit = homeUnlimitedAwareValue($plan, 'data_val', $max['data']) * 4
                + homeUnlimitedAwareValue($plan, 'minutes_val', $max['minutes']) * 0.025
                + homeUnlimitedAwareValue($plan, 'sms_val', $max['sms']) * 0.01
                + (float) ($plan['roaming_val'] ?? 0) * 2;
            $cost = $category === 'prepay'
                ? $price / max((float) ($plan['period'] ?? 1), 1.0)
                : $price;

            return $benefit / $cost;
        }

        $benefit = (float) ($plan['speed_val'] ?? 0) * 1.2
            + (float) ($plan['upload_speed_mbps'] ?? 0) * 0.8
            + (float) ($plan['tv_channels'] ?? 0) * 1.5
            + (float) ($plan['hd_channels'] ?? 0) * 2;

        return $benefit / $price;
    }

    function homeRecommendedPlan(string $category, ?array $plans = null): ?array
    {
        $plans = $plans ?? loadPlans($category);
        if (!$plans) {
            return null;
        }

        foreach ($plans as $plan) {
            if ((int) ($plan['is_recommended'] ?? 0) === 1) {
                return $plan;
            }
        }

        $max = [
            'data'    => homeMaxValue($plans, 'data_val'),
            'minutes' => homeMaxValue($plans, 'minutes_val'),
            'sms'     => homeMaxValue($plans, 'sms_val'),
        ];

        $best = null;
        $bestScore = -1.0;
        foreach ($plans as $plan) {
            $score = homePlanScore($plan, $category, $max);
            if ($score > $bestScore) {
                $best = $plan;
                $bestScore = $score;
            }
        }

        return $best;
    }

    $categoryLabels = planCategoryLabels();
    $categoryUrls = homeCategoryUrls();
    $homePlansByCategory = [];
    $categoryCounts = [];

    foreach (array_keys($categoryUrls) as $category) {
        try {
            $homePlansByCategory[$category] = loadPlans($category);
        } catch (Throwable $error) {
            $homePlansByCategory[$category] = [];
        }

        $categoryCounts[$category] = count($homePlansByCategory[$category]);
    }

    $mobilePlans = array_merge($homePlansByCategory['prepay'] ?? [], $homePlansByCategory['abonament'] ?? []);
    $internetPlans = array_merge($homePlansByCategory['internet'] ?? [], $homePlansByCategory['internet_tv'] ?? []);
    $heroMobileMinPrice = homeMinPrice($mobilePlans);
    $heroMaxInternetSpeed = homeMaxValue($internetPlans, 'speed_val');
    $recommendedPlans = [];

    foreach (array_keys($categoryUrls) as $category) {
        try {
            $plan = homeRecommendedPlan($category, $homePlansByCategory[$category]);
            if ($plan !== null) {
                $recommendedPlans[] = [
                    'category' => $category,
                    'label'    => $categoryLabels[$category],
                    'url'      => $categoryUrls[$category],
                    'plan'     => $plan,
                ];
            }
        } catch (Throwable $error) {
            continue;
        }
    }

    require __DIR__ . '/includes/header.php';
?>
<main id="main-content">
    <section class="hero">
        <div class="hero-content hero-layout">
            <div class="hero-copy">
                <p class="hero-eyebrow">Comparator telecom pentru Moldova</p>
                <h1>
                    Găsește oferta potrivită,
                    <span class="hero-highlight">fără zeci de tab-uri</span>.
                </h1>
                <p class="hero-lead">
                    Compară transparent prețuri, trafic, viteze și beneficii pentru
                    telefonie mobilă, internet și televiziune.
                </p>
                <div class="hero-buttons">
                    <a href="pages/prepay.php" class="btn-primary btn-large">Explorează planurile</a>
                    <a href="pages/compare.php" class="btn-outline btn-large">Deschide comparația</a>
                </div>
                <ul class="hero-trust" aria-label="Avantajele platformei">
                    <li>Filtre rapide</li>
                    <li>Comparație până la 4 planuri</li>
                    <li>Experiență optimizată pentru mobil</li>
                </ul>
            </div>
            <div class="hero-preview" aria-label="Exemplu de comparație">
                <div class="preview-header">
                    <span>Comparație rapidă</span>
                    <span class="preview-status">Actualizat</span>
                </div>
                <div class="preview-card preview-card-featured">
                    <div>
                        <span class="preview-label">Cel mai bun preț</span>
                        <strong>Plan mobil flexibil</strong>
                    </div>
                    <span class="preview-price"><?= $heroMobileMinPrice > 0 ? 'de la ' . homeH(formatPlanNumber($heroMobileMinPrice)) . ' MDL' : 'planuri actualizate' ?></span>
                </div>
                <div class="preview-card">
                    <div>
                        <span class="preview-label">Internet pentru acasă</span>
                        <strong>Viteză pentru orice ritm</strong>
                    </div>
                    <span class="preview-price"><?= $heroMaxInternetSpeed > 0 ? 'până la ' . homeH(homeSpeedLabel($heroMaxInternetSpeed)) : 'viteze actualizate' ?></span>
                </div>
                <a href="#categorii" class="preview-link">Vezi toate categoriile <span aria-hidden="true">→</span></a>
            </div>
        </div>
    </section>

    <section id="categorii" class="category-section">
        <div class="container">
            <div class="section-header">
                <p class="kicker">Explorează ofertele</p>
                <h2 class="section-title">Alege categoria potrivită</h2>
                <p class="section-subtitle">
                    Folosește filtrele pentru a restrânge rapid lista, apoi adaugă
                    favoritele într-o comparație clară.
                </p>
            </div>
            <div class="category-grid">
                <a class="category-card" href="pages/prepay.php">
                    <span class="category-icon" aria-hidden="true">SIM</span>
                    <span class="category-kicker">Flexibilitate</span>
                    <strong>Prepay</strong>
                    <span class="category-count"><?= homeH(homePlanCountLabel($categoryCounts['prepay'] ?? 0)) ?></span>
                    <span>Trafic, minute și valabilitate fără contract lunar.</span>
                    <span class="category-link">Vezi planurile →</span>
                </a>
                <a class="category-card" href="pages/abonament.php">
                    <span class="category-icon" aria-hidden="true">5G</span>
                    <span class="category-kicker">Mobil</span>
                    <strong>Abonament</strong>
                    <span class="category-count"><?= homeH(homePlanCountLabel($categoryCounts['abonament'] ?? 0)) ?></span>
                    <span>Planuri lunare cu date, minute și beneficii incluse.</span>
                    <span class="category-link">Vezi planurile →</span>
                </a>
                <a class="category-card" href="pages/internet.php">
                    <span class="category-icon" aria-hidden="true">Wi-Fi</span>
                    <span class="category-kicker">Acasă</span>
                    <strong>Internet</strong>
                    <span class="category-count"><?= homeH(homePlanCountLabel($categoryCounts['internet'] ?? 0)) ?></span>
                    <span>Compară prețul și viteza conexiunilor disponibile.</span>
                    <span class="category-link">Vezi planurile →</span>
                </a>
                <a class="category-card" href="pages/internet-tv.php">
                    <span class="category-icon" aria-hidden="true">TV+</span>
                    <span class="category-kicker">Pachet complet</span>
                    <strong>Internet + TV</strong>
                    <span class="category-count"><?= homeH(homePlanCountLabel($categoryCounts['internet_tv'] ?? 0)) ?></span>
                    <span>Internet rapid și divertisment într-o singură ofertă.</span>
                    <span class="category-link">Vezi planurile →</span>
                </a>
            </div>
        </div>
    </section>

    <section id="cum-functioneaza" class="how-it-works">
        <div class="container">
            <div class="section-header">
                <p class="kicker">Cum funcționează</p>
                <h2 class="section-title">De la opțiuni multe la o alegere clară</h2>
            </div>
            <div class="how-it-works-grid">
                <article class="step-card">
                    <span class="step-number-static">01</span>
                    <h3 class="step-title">Filtrează</h3>
                    <p class="step-description">Alege operatorii, bugetul și beneficiile importante pentru tine.</p>
                </article>
                <article class="step-card">
                    <span class="step-number-static">02</span>
                    <h3 class="step-title">Compară</h3>
                    <p class="step-description">Pune până la patru planuri alături și observă rapid diferențele.</p>
                </article>
                <article class="step-card">
                    <span class="step-number-static">03</span>
                    <h3 class="step-title">Decide</h3>
                    <p class="step-description">Accesează oferta furnizorului după ce ai găsit varianta potrivită.</p>
                </article>
            </div>
        </div>
    </section>

    <?php if ($recommendedPlans): ?>
    <section class="recommended-section" id="recomandate">
        <div class="container">
            <div class="section-header">
                <p class="kicker">Recomandări</p>
                <h2 class="section-title">Planuri recomandate pe categorii</h2>
                <p class="section-subtitle">
                    O selecție rapidă pentru fiecare categorie, bazată pe planul marcat în dashboard
                    sau pe cel mai bun raport beneficii/preț.
                </p>
            </div>

            <div class="recommended-grid">
                <?php foreach ($recommendedPlans as $item): ?>
                <?php
                    $plan = $item['plan'];
                    $link = trim((string) ($plan['link'] ?? '')) !== '' ? $plan['link'] : $item['url'];
                    $features = array_slice((array) ($plan['features'] ?? []), 0, 4);
                ?>
                <article class="plan-card home-recommended-card is-recommended">
                    <span class="recommended-badge">Recomandat</span>
                    <span class="category-pill"><?= homeH($item['label']) ?></span>
                    <span class="operator-badge" style="background:<?= homeH($plan['operator_color'] ?? '#6a1b9a') ?>;">
                        <?= homeH($plan['operator'] ?? '') ?>
                    </span>
                    <div class="plan-name"><?= homeH(capitalizeFirstLetter((string) ($plan['name'] ?? ''))) ?></div>
                    <div class="plan-price-container">
                        <div class="plan-price"><?= homeH(formatPlanNumber($plan['price'] ?? 0)) ?> <span class="plan-currency">MDL</span></div>
                        <div class="plan-period"><?= homeH(displayPeriod($plan['period'] ?? 30)) ?></div>
                    </div>
                    <ul class="plan-features">
                        <?php foreach ($features as $feature): ?>
                        <li><?= homeH(capitalizeFirstLetter((string) $feature)) ?></li>
                        <?php endforeach; ?>
                    </ul>
                    <a href="<?= homeH($link) ?>" class="plan-button <?= homeH($plan['operator_key'] ?? '') ?>" style="--plan-color: <?= homeH($plan['operator_color'] ?? '#d61f69') ?>;" target="_blank" rel="noopener noreferrer">Alege planul</a>
                    <a href="<?= homeH($item['url']) ?>" class="recommended-category-link">Vezi categoria</a>
                </article>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <section class="comparison-cta">
        <div class="container comparison-cta-inner">
            <div>
                <p class="kicker">Instrument de decizie</p>
                <h2>Nu alege doar după preț.</h2>
                <p>Compară și datele, viteza, perioada și beneficiile incluse.</p>
            </div>
            <a href="pages/prepay.php" class="btn-primary btn-large">Începe comparația</a>
        </div>
    </section>

</main>

<?php require __DIR__ . '/includes/footer.php'; ?>
</body>
</html>
