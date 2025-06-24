<?php
const COS_PHI_DEF = 0.8;
const BASE_SC     = 0.25;
$fuelFac = ['DIESEL'=>1.00,'HVO100'=>1.04,'ECOPAR'=>0.93];
$voltage = ['1'=>230, '3'=>400]; // 1=Enfas, 3=Trefas

function derate_factor($ambient, $altitude) {
    $factor = 1.0;
    if ($ambient > 40) {
        $factor -= 0.02 * (($ambient - 40)/10);
    }
    if ($altitude > 1000) {
        $factor -= 0.01 * (($altitude - 1000)/100);
    }
    return max($factor, 0.6);
}

function calc_generator_effect($rating, $unit, $cosphi, $derate) {
    $ratingKW = $unit==='kW' ? $rating : $rating*$cosphi;
    return [$ratingKW, $ratingKW*$derate];
}

function calc_current($phasemode, $kW, $cosphi) {
    global $voltage;
    $U = $voltage[$phasemode];
    if ($phasemode==='1') {
        return $kW * 1000 / ($U * $cosphi);
    }
    return $kW * 1000 / (sqrt(3) * $U * $cosphi);
}

function calc_profile($profile, $fuel, $price, $co2, $ratingKW_derated) {
    global $fuelFac;
    $BASE_SC = 0.25;
    $results = [];
    $totalL = $totalCost = $totalCO2 = $totHours = 0;
    foreach ($profile as $seg) {
        $segSc = $BASE_SC * $fuelFac[$fuel];
        $segL  = $seg['loadkW'] * $segSc * $seg['hours'];
        $segCost = $segL * $price;
        $segCO2  = $segL * $co2;
        $results[] = [
            'hours' => $seg['hours'],
            'loadkW' => $seg['loadkW'],
            'L' => $segL,
            'cost' => $segCost,
            'co2' => $segCO2,
            'warn' => ($seg['loadkW'] > $ratingKW_derated) ? "⚠️ Över max tillgänglig effekt!" : ""
        ];
        $totalL += $segL;
        $totalCost += $segCost;
        $totalCO2 += $segCO2;
        $totHours += $seg['hours'];
    }
    return [$results, $totalL, $totalCost, $totalCO2, $totHours];
}

function calc_economics($totalL, $totHours, $price, $hours_per_year, $extra_invest) {
    $avg_Lph = ($totHours > 0) ? $totalL / $totHours : 0;
    $annual_cost = $avg_Lph * $hours_per_year * $price;
    $payback = ($extra_invest > 0 && $annual_cost > 0) ? $extra_invest / $annual_cost : null;
    return [$avg_Lph, $annual_cost, $payback];
}

function calc_environment($totalL, $totHours, $co2, $hours_per_year) {
    $co2_per_km = 0.12; // kg CO₂/km (bil)
    $tree_kg = 25;      // kg CO₂/år och träd
    $avg_Lph = ($totHours > 0) ? $totalL / $totHours : 0;
    $annual_co2 = $avg_Lph * $hours_per_year * $co2;
    $eq_km = ($co2_per_km > 0) ? $annual_co2 / $co2_per_km : 0;
    $eq_trees = ($annual_co2 > 0) ? ceil($annual_co2 / $tree_kg) : 0;
    return [$annual_co2, $eq_km, $eq_trees];
}
?>
