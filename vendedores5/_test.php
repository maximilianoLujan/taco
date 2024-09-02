<?php
ini_set('display_errors', 1);

$totales_sr = [];

$totales_sr['333']['actual']['unidades'] = $totales_sr['333']['actual']['unidades'] + 4 ?? 99;

echo $totales_sr['333']['actual']['unidades'] . '<br />';


$totales_sr['333']['actual']['unidades'] = $totales_sr['333']['actual']['unidades'] + 4 ?? 99;



echo $totales_sr['333']['actual']['unidades'];