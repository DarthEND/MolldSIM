<?php
/**
 * Renders a single plan card as HTML.
 *
 * @param array $plan {
 *   operator: string,
 *   operator_color: string,
 *   operator_key: string,
 *   name: string,
 *   price: int,
 *   period: string,
 *   features: string[],
 *   link: string,
 *   data_val: int,
 *   speed_val: int,
 * }
 * @return string
 */
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

function renderPlanCard(array $plan): string
{
    $operator     = htmlspecialchars($plan['operator'],       ENT_QUOTES);
    $color        = htmlspecialchars($plan['operator_color'], ENT_QUOTES);
    $key          = htmlspecialchars($plan['operator_key'],   ENT_QUOTES);
    $name         = htmlspecialchars($plan['name'],           ENT_QUOTES);
    $price        = (int) $plan['price'];
    $periodStr    = htmlspecialchars(displayPeriod($plan['period']), ENT_QUOTES);
    $link         = htmlspecialchars($plan['link'],           ENT_QUOTES);
    $dataVal      = (int) ($plan['data_val']  ?? 0);
    $speedVal     = (int) ($plan['speed_val'] ?? 0);

    $featuresHtml = '';
    foreach ($plan['features'] as $feat) {
        $featuresHtml .= '<li>' . htmlspecialchars($feat) . '</li>';
    }

    $html  = '<div class="plan-card"';
    $html .= ' data-operator="' . $key . '"';
    $html .= ' data-price="' . $price . '"';
    $html .= ' data-data="' . $dataVal . '"';
    $html .= ' data-speed="' . $speedVal . '"';
    $html .= '>';
    $html .= '<span class="operator-badge" style="background:' . $color . ';">' . $operator . '</span>';
    $html .= '<div class="plan-name">' . $name . '</div>';
    $html .= '<div class="plan-price-container">';
    $html .= '<div class="plan-price">' . $price . ' <span class="plan-currency">MDL</span></div>';
    $html .= '<div class="plan-period">' . $periodStr . '</div>';
    $html .= '</div>';
    $html .= '<ul class="plan-features">' . $featuresHtml . '</ul>';
    $html .= '<a href="' . $link . '" class="plan-button ' . $key . '" target="_blank">Alege planul</a>';
    $html .= '</div>';

    return $html;
}
