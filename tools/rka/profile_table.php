<?php
function render_profile_table($profileResults, $totalL, $totalCost, $totalCO2, $totHours) {
    echo '<h2>Lastprofil – summering</h2>
    <table class="striped">
      <thead>
        <tr>
          <th>Tid (h)</th>
          <th>Last (kW)</th>
          <th>Liter</th>
          <th>Kostnad</th>
          <th>CO₂ (kg)</th>
          <th>⚠️</th>
        </tr>
      </thead>
      <tbody>';
    foreach ($profileResults as $r) {
      echo '<tr>
        <td>'.number_format($r['hours'],1).'</td>
        <td>'.number_format($r['loadkW'],1).'</td>
        <td>'.number_format($r['L'],1).'</td>
        <td>'.number_format($r['cost'],0).'</td>
        <td>'.number_format($r['co2'],1).'</td>
        <td>'.$r['warn'].'</td>
      </tr>';
    }
    echo '</tbody>
    </table>
    <strong>Totalt: '
      .number_format($totalL,1).' L, '
      .number_format($totalCost,0).' kr, '
      .number_format($totalCO2,1).' kg CO₂, på '
      .$totHours.' h.</strong>';
}
?>
