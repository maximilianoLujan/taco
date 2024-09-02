<?php
// $clientes_vendedor = get_clientes_vendedor($_GET['vendedor']);

// $super_rubros = get_super_rubros();
$super_rubros_especiales = get_super_rubros_especiales();

$ventas_clientes_sr_marcas = get_ventas_clientes_sr_marcas();
$descuentos_clientes_marcas = get_descuentos_clientes_marcas();

$marcas_super_rubros = get_marcas_super_rubros();

// echo "<pre>";
// print_r($ventas_clientes_sr_marcas);
// echo "</pre>";

// echo "<pre>";
// print_r($descuentos_clientes_marcas);
// echo "</pre>";


$html_general = '';

$rubros_activos_vendedor_mes_actual = 0;
$rubros_activos_vendedor_mes_anterior = 0;
$rubros_activos_vendedor_promedio_3 = 0;
$rubros_activos_vendedor_promedio_6 = 0;
$rubros_activos_vendedor_promedio_12 = 0;
$rubros_activos_vendedor_promedio_24 = 0;
$total_sr_activos = [];

$totales_sr = [];
$totales_act_sr = [];

$stock_super_rubros = stock_super_rubros();
$promedio_ventas_6m = promedio_ventas_6m();

$stock_linea_clientes = numero_legible_redondeado( divide( $stock_super_rubros['total']['unidades'], $promedio_ventas_6m['total']['unidades'] ) );
$monto_linea_clientes = numero_legible_redondeado( divide( $stock_super_rubros['total']['monto'], $promedio_ventas_6m['total']['monto'] ) );


foreach( $clientes_ordenados as $k=>$v ){
	$cliente = $clientes[$k];

	//Obtengo las ventas por super rubro para el cliente 
	$ventas_x_cliente_x_super_rubros_actual = ventas_x_cliente_x_super_rubros( $k, 0 );
	$ventas_x_cliente_x_super_rubros_anterior = ventas_x_cliente_x_super_rubros( $k, 1 );
	$ventas_x_cliente_x_super_rubros_3 = ventas_x_cliente_x_super_rubros( $k, 3 );
	$ventas_x_cliente_x_super_rubros_6 = ventas_x_cliente_x_super_rubros( $k, 6 );
	$ventas_x_cliente_x_super_rubros_12 = ventas_x_cliente_x_super_rubros( $k, 12 );
	$ventas_x_cliente_x_super_rubros_24 = ventas_x_cliente_x_super_rubros( $k, 24 );

	$ventas_x_cliente_x_super_rubros = array_merge(
		array_keys($super_rubros_especiales), 
		array_keys($ventas_x_cliente_x_super_rubros_actual), 
		array_keys($ventas_x_cliente_x_super_rubros_anterior), 
		array_keys($ventas_x_cliente_x_super_rubros_3),
		array_keys($ventas_x_cliente_x_super_rubros_6),
		array_keys($ventas_x_cliente_x_super_rubros_12),
		array_keys($ventas_x_cliente_x_super_rubros_24)
	);

	$ventas_x_cliente_x_super_rubros = array_unique($ventas_x_cliente_x_super_rubros);

	$html_general .= '<table class="tabla-coloreada table table-bordered table-striped table-condensed table-hover">' . "\n";
	$html_general .= '<tr class="info">' . "\n";
	$html_general .= '<th></th>' . "\n";
	$html_general .= '<th class="text-right columna-info boton-reordenar">Actual</th>' . "\n";
	$html_general .= '<th class="text-right columna-info boton-reordenar">Mes anterior</th>' . "\n";
	$html_general .= '<th class="text-right columna-info boton-reordenar">Prom 3</th>' . "\n";
	$html_general .= '<th class="text-right columna-info boton-reordenar">Prom 12</th>' . "\n";
	$html_general .= '<th class="text-right columna-info boton-reordenar">Stock en Meses</th>' . "\n";
	$html_general .= '<th class="por-que-no-compra">¿Por qué no compra?</th>' . "\n";
	$html_general .= '</tr>' . "\n";
	$html_general .= '<tr>' . "\n";

	//fila general cliente 
	$saldo_fric_rot = saldo_inicial_fric_rot($k);
	$saldo_distrisuper = saldo_inicial_distrisuper($k);
	if( $cliente['codigo_plan'] == 1 ){
		$html_plan_cliente = "<span class='btn btn-xs pull-right red-badge'>CUPO</span>\n";
	} elseif ( $cliente['codigo_plan'] == 2 ){
		$html_plan_cliente = "<span class='btn btn-xs pull-right blue-badge'>SUSCRIPCION</span>\n";
	}else{
		$html_plan_cliente = '';
	}

	if( $saldo_fric_rot['saldo'] != 0 || $saldo_distrisuper['saldo'] != 0 ){

		$color = 'opesp-azul';
		if( $saldo_fric_rot['porcentaje'] <= 30 || $saldo_distrisuper['porcentaje'] <= 30 ){
			$color = 'opesp-rojo';
		}elseif(  $saldo_fric_rot['porcentaje'] < 50 || $saldo_distrisuper['porcentaje'] < 50 ){
			$color = 'opesp-amarillo';
		}
		$html_general .= '<th class="text-left columna-info"><span class="crop-1">' . $cliente['nombre_cliente'] . ' - (Dto. ' . floatval($cliente['bonificacion']) . "%)</span>\n";
		$html_general .= "<a href='/precios-congelados-7/movimientos-cliente.php?id=$k' class='btn btn-xs pull-right $color' target='_blank'>" . k($saldo_fric_rot['saldo']) . ' / ' . k($saldo_distrisuper['saldo']) . "</a>\n";
		$html_general .= $html_plan_cliente;
		$html_general .= "</th>\n";
	}else{
		$html_general .= '<th class="text-left columna-info"><span class="crop-1">' . $cliente['nombre_cliente'] . ' - (Dto. ' . floatval($cliente['bonificacion']) . "%)</span>$html_plan_cliente</th>\n";
	}

	$color_monto = color_promedio($ventas_mes_corriente_x_cliente[$k]['monto'], $promedio_por_cliente_24[$k]['monto'], 'monto');
	$html_general .= '<th class="text-right columna-info ' . $color_monto . '">' . numero_legible_redondeado($ventas_mes_corriente_x_cliente[$k]['unidades']) . ' (' . numero_en_miles($ventas_mes_corriente_x_cliente[$k]['monto']) . ")</th>\n";

	$color_monto = color_promedio($ventas_mes_anterior_x_cliente[$k]['monto'], $promedio_por_cliente_24[$k]['monto'], 'monto');
	$html_general .= '<th class="text-right columna-info ' . $color_monto . '">' . numero_legible_redondeado($ventas_mes_anterior_x_cliente[$k]['unidades']) . ' (' . numero_en_miles($ventas_mes_anterior_x_cliente[$k]['monto']) . ")</th>\n";

	$color_monto = color_promedio($promedio_por_cliente_3[$k]['monto'], $promedio_por_cliente_24[$k]['monto'], 'monto');
	$html_general .= '<th class="text-right columna-info ' . $color_monto . '">' . numero_legible_redondeado($promedio_por_cliente_3[$k]['unidades']) . ' (' . numero_en_miles($promedio_por_cliente_3[$k]['monto']) . ")</th>\n";

	$color_monto = color_promedio($promedio_por_cliente_12[$k]['monto'], $promedio_por_cliente_24[$k]['monto'], 'monto');
	$html_general .= '<th class="text-right columna-info ' . $color_monto . '">' . numero_legible_redondeado($promedio_por_cliente_12[$k]['unidades']) . ' (' . numero_en_miles($promedio_por_cliente_12[$k]['monto']) . ")</th>\n";
	
	//En vez de el promedio de venta para ese cliente, el promedio de venta general
	$html_general .= '<th class="text-right columna-info">';
	//$html_general .= numero_legible_redondeado( divide( $stock_super_rubros['total']['unidades'], $promedio_por_cliente_6[$k]['unidades'] ) );
	//$html_general .= ' (' . numero_legible_redondeado( divide( $stock_super_rubros['total']['monto'], $promedio_por_cliente_6[$k]['monto'] ) ) . ")</th>\n";
	$html_general .= $stock_linea_clientes;
	
	$por_que_no_compra = get_por_que_no_compra($k, '');
	$html_general .= '<th class="por-que-no-compra" data-cliente="' . $k . '" contenteditable>' . $por_que_no_compra . '</th>' . "\n";
	$html_general .= "</tr>\n";
	
	$linea_html = array();
	$rubros_activos_mes_actual = 0;
	$rubros_activos_mes_anterior = 0;
	$rubros_activos_promedio_3 = 0;
	$rubros_activos_promedio_6 = 0;
	$rubros_activos_promedio_12 = 0;
	$rubros_activos_promedio_24 = 0;

	// $descuento_maximo_marca = descuento_maximo_marca($k, $ventas_clientes_sr_marcas_todas);
	$indexArreglo = 0;

	foreach($ventas_x_cliente_x_super_rubros as $super_rubro){
		$id_super_rubro = strval($super_rubro);

		if (is_null($super_rubros[$id_super_rubro]['nombre'])) {
			continue;
		}

		if( @$super_rubros[$id_super_rubro]['orden'] ){
			$valor_para_ordenar[$id_super_rubro] = $super_rubros[$id_super_rubro]['orden'];
		}else{
			$valor_para_ordenar[$id_super_rubro] = 99999;
		}

		$unidades_actual = @$ventas_x_cliente_x_super_rubros_actual[$id_super_rubro]['unidades'];
		$monto_actual = @$ventas_x_cliente_x_super_rubros_actual[$id_super_rubro]['monto'];

		$unidades_anterior = @$ventas_x_cliente_x_super_rubros_anterior[$id_super_rubro]['unidades'];
		$monto_anterior = @$ventas_x_cliente_x_super_rubros_anterior[$id_super_rubro]['monto'];

		$unidades_3 = @$ventas_x_cliente_x_super_rubros_3[$id_super_rubro]['unidades']/3;
		$monto_3 = @$ventas_x_cliente_x_super_rubros_3[$id_super_rubro]['monto']/3;

		$unidades_6 = @$ventas_x_cliente_x_super_rubros_6[$id_super_rubro]['unidades']/6;
		$monto_6 = @$ventas_x_cliente_x_super_rubros_6[$id_super_rubro]['monto']/6;

		$unidades_12 = @$ventas_x_cliente_x_super_rubros_12[$id_super_rubro]['unidades']/12;
		$monto_12 = @$ventas_x_cliente_x_super_rubros_12[$id_super_rubro]['monto']/12;

		$unidades_24 = @$ventas_x_cliente_x_super_rubros_24[$id_super_rubro]['unidades']/24;
		$monto_24 = @$ventas_x_cliente_x_super_rubros_24[$id_super_rubro]['monto']/24;

		$totales_sr[$id_super_rubro]['actual'] = $totales_sr[$id_super_rubro]['actual'] ?? 0;
		$totales_sr[$id_super_rubro]['actual'] += $unidades_actual;

		$totales_sr[$id_super_rubro]['anterior'] = $totales_sr[$id_super_rubro]['anterior'] ?? 0;
		$totales_sr[$id_super_rubro]['anterior'] += $unidades_anterior;

		$totales_sr[$id_super_rubro]['promedio_3'] = $totales_sr[$id_super_rubro]['promedio_3'] ?? 0;
		$totales_sr[$id_super_rubro]['promedio_3'] += $unidades_3;

		$totales_sr[$id_super_rubro]['promedio_6'] = $totales_sr[$id_super_rubro]['promedio_6'] ?? 0;
		$totales_sr[$id_super_rubro]['promedio_6'] += $unidades_6;

		$totales_sr[$id_super_rubro]['promedio_12'] = $totales_sr[$id_super_rubro]['promedio_12'] ?? 0;
		$totales_sr[$id_super_rubro]['promedio_12'] += $unidades_12;

		$totales_sr[$id_super_rubro]['promedio_24'] = $totales_sr[$id_super_rubro]['promedio_24'] ?? 0;
		$totales_sr[$id_super_rubro]['promedio_24'] += $unidades_24;

		if( $unidades_actual >= ($super_rubros[$id_super_rubro]['criterio_activo']/2) && $unidades_actual < $super_rubros[$id_super_rubro]['criterio_activo'] ){
			$es_proximo = 'es-proximo';
		}else{
			$es_proximo = 'no-es-proximo';
		}

		//Si el indice es > 15 lo oculto
		$mostrar = '';
		if($indexArreglo > 14){
			$mostrar = "quince-menos-importantes-$k no-mostrar";
		}


		$linea_html[$id_super_rubro] = "<tr class='row-super-rubro $es_proximo $mostrar'>\n";
		
		$descuento_marca = '';
		$descuento_maximo_marca = descuento_maximo_marca($descuentos_clientes_marcas, $marcas_super_rubros, $k, $id_super_rubro);
		if( $descuento_maximo_marca > 0 ){
			$descuento_marca = ' (' . floatval($descuento_maximo_marca) . '%)';
		}

		if( $unidades_actual >= $super_rubros[$id_super_rubro]['criterio_activo'] && $super_rubros[$id_super_rubro]['criterio_activo'] > 0 ){
			if( isset($total_sr_activos[$id_super_rubro]) ){
				$total_sr_activos[$id_super_rubro]++;
			}else{
				$total_sr_activos[$id_super_rubro] = 1;
			}

			$linea_html[$id_super_rubro] .= '<td class="alert alert-success detalle-marcas" data-cliente="' . $k . '" data-sr="' . $id_super_rubro . '">
			<span class="crop-1">' . @$super_rubros[$id_super_rubro]['nombre'] . "$descuento_marca</span></td>\n";
			$rubros_activos_mes_actual++;
			$extra_class = ' activo_a';

			$totales_act_sr[$id_super_rubro]['actual'] = $totales_act_sr[$id_super_rubro]['actual'] ?? 0;
			$totales_act_sr[$id_super_rubro]['actual']++;
		}else{
			$linea_html[$id_super_rubro] .= '<td class="detalle-marcas" data-cliente="' . $k . '" data-sr="' . $id_super_rubro . '">
			<span class="crop-1">' . @$super_rubros[$id_super_rubro]['nombre'] . "$descuento_marca</span></td>\n";
			$extra_class = '';
		}

		$color_monto = color_promedio($monto_actual, $monto_24, 'monto');
		$linea_html[$id_super_rubro] .= '<td class="text-right ' . $color_monto . $extra_class. '"><div class="cantidad">' . numero_legible_redondeado( $unidades_actual ) .
		' (' . numero_en_miles($monto_actual) . ")</div></td>\n";

		$extra_class = '';
		if( $unidades_anterior >= $super_rubros[$id_super_rubro]['criterio_activo'] && $super_rubros[$id_super_rubro]['criterio_activo'] > 0 ){
			$rubros_activos_mes_anterior++;
			$extra_class = ' activo_a';

			$totales_act_sr[$id_super_rubro]['anterior'] = $totales_act_sr[$id_super_rubro]['anterior'] ?? 0;
			$totales_act_sr[$id_super_rubro]['anterior']++;
		}
		$color_monto = color_promedio($monto_anterior, $monto_24, 'monto');
		$linea_html[$id_super_rubro] .= '<td class="text-right ' . $color_monto . $extra_class . '"><div class="cantidad">' . numero_legible_redondeado( $unidades_anterior ) .
		' (' . numero_en_miles($monto_anterior) . ")</div></td>\n";
		
		$extra_class = '';
		if( $unidades_3 >= $super_rubros[$id_super_rubro]['criterio_activo'] && $super_rubros[$id_super_rubro]['criterio_activo'] > 0 ){
			$rubros_activos_promedio_3++;
			$extra_class = ' activo_a';

			$totales_act_sr[$id_super_rubro]['promedio_3'] = $totales_act_sr[$id_super_rubro]['promedio_3'] ?? 0;
			$totales_act_sr[$id_super_rubro]['promedio_3']++;
		}
		$color_monto = color_promedio($monto_3, $monto_24, 'monto');
		$linea_html[$id_super_rubro] .= '<td class="text-right ' . $color_monto . $extra_class . '"><div class="cantidad">' . numero_legible_redondeado( $unidades_3 ) .
		' (' . numero_en_miles($monto_3) . ")</div></td>\n";

		$extra_class = '';
		if( $unidades_6 >= $super_rubros[$id_super_rubro]['criterio_activo'] && $super_rubros[$id_super_rubro]['criterio_activo'] > 0 ){
			$rubros_activos_promedio_6++;
			$extra_class = ' activo_a';

			$totales_act_sr[$id_super_rubro]['promedio_6'] = $totales_act_sr[$id_super_rubro]['promedio_6'] ?? 0;
			$totales_act_sr[$id_super_rubro]['promedio_6']++;
		}

		$extra_class = '';
		if( $unidades_12 >= $super_rubros[$id_super_rubro]['criterio_activo'] && $super_rubros[$id_super_rubro]['criterio_activo'] > 0 ){
			$rubros_activos_promedio_12++;
			$extra_class = ' activo_a';

			$totales_act_sr[$id_super_rubro]['promedio_12'] = $totales_act_sr[$id_super_rubro]['promedio_12'] ?? 0;
			$totales_act_sr[$id_super_rubro]['promedio_12']++;
		}
		$color_monto = color_promedio($monto_12, $monto_24, 'monto');
		$linea_html[$id_super_rubro] .= '<td class="text-right ' . $color_monto . $extra_class . '"><div class="cantidad">' . numero_legible_redondeado( $unidades_12 ) .
		' (' . numero_en_miles($monto_12) . ")</div></td>\n";

		$extra_class = '';
		if( $unidades_24 >= $super_rubros[$id_super_rubro]['criterio_activo'] && $super_rubros[$id_super_rubro]['criterio_activo'] > 0 ){
			$rubros_activos_promedio_24++;
			$extra_class = ' activo_a';

			$totales_act_sr[$id_super_rubro]['promedio_24'] = $totales_act_sr[$id_super_rubro]['promedio_24'] ?? 0;
			$totales_act_sr[$id_super_rubro]['promedio_24']++;
		}

		$linea_html[$id_super_rubro] .= '<td class="text-right">';
		//$linea_html[$id_super_rubro] .= numero_legible_redondeado( divide( $stock_super_rubros[$id_super_rubro]['unidades'], $unidades_6 ) );
		//$linea_html[$id_super_rubro] .= ' (' . numero_legible_redondeado( divide( $stock_super_rubros[$id_super_rubro]['monto'], $monto_6 ) ) . ")</td>\n";
		$linea_html[$id_super_rubro] .= numero_legible_redondeado( divide( $stock_super_rubros[$id_super_rubro]['unidades'], $promedio_ventas_6m[$id_super_rubro]['unidades'] ) );


		if( strlen(trim($id_super_rubro)) > 0 ){
			$por_que_no_compra = get_por_que_no_compra($k, $id_super_rubro);
			$linea_html[$id_super_rubro] .= '<td class="por-que-no-compra" data-cliente="' . $k . '" data-sr="' . $id_super_rubro . '" contenteditable>' . $por_que_no_compra . "</td>\n";	
		}else{
			$linea_html[$id_super_rubro] .= '<td disabled>' . "</td>\n";	
		}

		$linea_html[$id_super_rubro] .= "</tr>\n";
		$indexArreglo = $indexArreglo + 1;
	}

	//fila totales promedios cliente
	$rubros_activos_vendedor_mes_actual += $rubros_activos_mes_actual;
	$rubros_activos_vendedor_mes_anterior += $rubros_activos_mes_anterior;
	$rubros_activos_vendedor_promedio_3 += $rubros_activos_promedio_3;
	$rubros_activos_vendedor_promedio_6 += $rubros_activos_promedio_6;
	$rubros_activos_vendedor_promedio_12 += $rubros_activos_promedio_12;
	$rubros_activos_vendedor_promedio_24 += $rubros_activos_promedio_24;

	$linea_totales_html = '<tr>' . "\n";
	$linea_totales_html .= '<th class="text-left columna-info">Rubros Activos</th>' . "\n";

	$color_monto = color_promedio($rubros_activos_mes_actual, $rubros_activos_promedio_24, 'monto');
	$linea_totales_html .= '<th class="text-right columna-info ' . $color_monto . '">' . $rubros_activos_mes_actual . "</th>\n";

	$color_monto = color_promedio($rubros_activos_mes_anterior, $rubros_activos_promedio_24, 'monto');
	$linea_totales_html .= '<th class="text-right columna-info ' . $color_monto . '">' . $rubros_activos_mes_anterior . "</th>\n";

	$color_monto = color_promedio($rubros_activos_promedio_3, $rubros_activos_promedio_24, 'monto');
	$linea_totales_html .= '<th class="text-right columna-info ' . $color_monto . '">' . $rubros_activos_promedio_3 . "</th>\n";

	$color_monto = color_promedio($rubros_activos_promedio_12, $rubros_activos_promedio_24, 'monto');
	$linea_totales_html .= '<th class="text-right columna-info ' . $color_monto . '">' . $rubros_activos_promedio_12 . "</th>\n";

	$linea_totales_html .= '<th class="text-right columna-info">' . "</th>\n";
	
	$linea_totales_html .= '<th></th>' . "\n";
	$linea_totales_html .= "</tr>\n";

	// $linea_super_rubros_activos_html = "<tr>\n";
	// $linea_super_rubros_activos_html .= "<td></td>\n";
	// $color_monto = color_promedio($monto_actual, $monto_24, 'monto');
	// $linea_super_rubros_activos_html .= '<td class="text-right ' . $color_monto . '">' . "</td>\n";
	// $color_monto = color_promedio($monto_actual, $monto_24, 'monto');
	// $linea_super_rubros_activos_html .= '<td class="text-right ' . $color_monto . '">' . "</td>\n";
	// $color_monto = color_promedio($monto_actual, $monto_24, 'monto');
	// $linea_super_rubros_activos_html .= '<td class="text-right ' . $color_monto . '">' . "</td>\n";
	// $color_monto = color_promedio($monto_actual, $monto_24, 'monto');
	// $linea_super_rubros_activos_html .= '<td class="text-right ' . $color_monto . '">' . "</td>\n";
	// $linea_super_rubros_activos_html .= "<td></td>\n";
	// $linea_super_rubros_activos_html .= "</tr>\n";

	$html_general .= $linea_totales_html;
	asort($valor_para_ordenar);
	foreach($valor_para_ordenar as $id_super_rubro=>$orden){
		$html_general .= @$linea_html[$id_super_rubro];
	}
	
	$html_general .= '</table>' . "\n";
	$html_general .= '<button type="button" class="mostrar-quince-menos-importantes" data-cliente="' . $k . '">Ver rubros restantes</button>';
	$html_general .= '<br />' . "\n";

}


$html_totales_vendedor = '<table class="tabla-coloreada table table-bordered table-striped table-condensed table-hover">' . "\n";
$html_totales_vendedor .= '<tr class="info">' . "\n";
$html_totales_vendedor .= '<th>Total vendedor</th>' . "\n";
$html_totales_vendedor .= '<th class="text-right columna-infor">Actual</th>' . "\n";
$html_totales_vendedor .= '<th class="text-right columna-infor">Mes anterior</th>' . "\n";
$html_totales_vendedor .= '<th class="text-right columna-infor">Prom 3</th>' . "\n";
$html_totales_vendedor .= '<th class="text-right columna-infor">Prom 12</th>' . "\n";
// este es total de activos, no lleva stock
$html_totales_vendedor .= '<th class="por-que-no-compra"></th>' . "\n";
$html_totales_vendedor .= '</tr>' . "\n";
$html_totales_vendedor .= '<tr>' . "\n";
$html_totales_vendedor .= '<td class="text-left columna-info">Totales Super Rubros</td>' . "\n";

$color_monto = color_promedio($rubros_activos_vendedor_mes_actual, $rubros_activos_vendedor_promedio_24, 'monto');
$html_totales_vendedor .= '<td class="text-right ' . $color_monto . '">' . numero_legible_redondeado($rubros_activos_vendedor_mes_actual) . "</td>\n";

$color_monto = color_promedio($rubros_activos_vendedor_mes_anterior, $rubros_activos_vendedor_promedio_24, 'monto');
$html_totales_vendedor .= '<td class="text-right ' . $color_monto . '">' . numero_legible_redondeado($rubros_activos_vendedor_mes_anterior) . "</td>\n";

$color_monto = color_promedio($rubros_activos_vendedor_promedio_3, $rubros_activos_vendedor_promedio_24, 'monto');
$html_totales_vendedor .= '<td class="text-right ' . $color_monto . '">' . numero_legible_redondeado($rubros_activos_vendedor_promedio_3) . "</td>\n";

$color_monto = color_promedio($rubros_activos_vendedor_promedio_12, $rubros_activos_vendedor_promedio_24, 'monto');
$html_totales_vendedor .= '<td class="text-right ' . $color_monto . '">' . numero_legible_redondeado($rubros_activos_vendedor_promedio_12) . "</td>\n";

// este es total de activos, no lleva stock

$html_totales_vendedor .= '<td></td>' . "\n";

$html_totales_vendedor .= '</tr>' . "\n";
$html_totales_vendedor .= '</table>' . "\n";

$html_totales_vendedor .= '<table class="tabla-coloreada table table-bordered table-striped table-condensed table-hover">' . "\n";
$html_totales_vendedor .= '<tr class="info">' . "\n";
$html_totales_vendedor .= '<th>Total vendedor</th>' . "\n";
$html_totales_vendedor .= '<th class="text-right columna-infor">Actual</th>' . "\n";
$html_totales_vendedor .= '<th class="text-right columna-infor">Mes anterior</th>' . "\n";
$html_totales_vendedor .= '<th class="text-right columna-infor">Prom 3</th>' . "\n";
$html_totales_vendedor .= '<th class="text-right columna-infor">Prom 12</th>' . "\n";
$html_totales_vendedor .= '<th class="text-right columna-infor">Total Activos</th>' . "\n";
$html_totales_vendedor .= '<th class="text-right columna-infor">Stock en meses</th>' . "\n";
$html_totales_vendedor .= '<th class="por-que-no-compra"></th>' . "\n";
$html_totales_vendedor .= '</tr>' . "\n";

$total_unidades_vendedor = [];
$total_unidades_vendedor['actual'] = 0;
$total_unidades_vendedor['anterior'] = 0;
$total_unidades_vendedor['promedio_3'] = 0;
$total_unidades_vendedor['promedio_6'] = 0;
$total_unidades_vendedor['promedio_12'] = 0;
$total_unidades_vendedor['promedio_24'] = 0;
foreach( $totales_sr as $k=>$v ){
	$total_unidades_vendedor['actual'] += $v['actual'] ?? 0;
	$total_unidades_vendedor['anterior'] += $v['anterior'] ?? 0;
	$total_unidades_vendedor['promedio_3'] += $v['promedio_3'] ?? 0;
	$total_unidades_vendedor['promedio_6'] += $v['promedio_6'] ?? 0;
	$total_unidades_vendedor['promedio_12'] += $v['promedio_12'] ?? 0;
	$total_unidades_vendedor['promedio_24'] += $v['promedio_24'] ?? 0;
}

$html_totales_vendedor .= '<tr>' . "\n";
$html_totales_vendedor .= '<td class="text-left columna-info">TOTAL UNIDADES</td>' . "\n";
$color_monto = color_promedio($total_unidades_vendedor['actual'], $total_unidades_vendedor['promedio_24'], 'monto');
$html_totales_vendedor .= '<td class="text-right ' . $color_monto . '">' . numero_legible_redondeado($total_unidades_vendedor['actual']) . "</td>\n";
$color_monto = color_promedio($total_unidades_vendedor['anterior'], $total_unidades_vendedor['promedio_24'], 'monto');
$html_totales_vendedor .= '<td class="text-right ' . $color_monto . '">' . numero_legible_redondeado($total_unidades_vendedor['anterior']) . "</td>\n";
$color_monto = color_promedio($total_unidades_vendedor['promedio_3'], $total_unidades_vendedor['promedio_24'], 'monto');
$html_totales_vendedor .= '<td class="text-right ' . $color_monto . '">' . numero_legible_redondeado($total_unidades_vendedor['promedio_6']) . "</td>\n";
$color_monto = color_promedio($total_unidades_vendedor['promedio_12'], $total_unidades_vendedor['promedio_24'], 'monto');
$html_totales_vendedor .= '<td class="text-right ' . $color_monto . '">' . numero_legible_redondeado($total_unidades_vendedor['promedio_12']) . "</td>\n";
$html_totales_vendedor .= '<td class="text-right">' . numero_legible_redondeado( array_sum( $total_sr_activos ) ) . '</td>' . "\n";
//$html_totales_vendedor .= '<td class="text-right">' . numero_legible_redondeado( divide( $stock_super_rubros['total']['unidades'], $total_unidades_vendedor['promedio_6']) ) . "</td>\n";
$html_totales_vendedor .= '<td class="text-right">' . $stock_linea_clientes . "</td>\n";
$html_totales_vendedor .= '<td></td>' . "\n";
$html_totales_vendedor .= '</tr>' . "\n";

foreach( $totales_sr as $k=>$v ){
	if (is_null($super_rubros[$k]['nombre'])) {
		continue;
	}
	$html_totales_vendedor .= '<tr>' . "\n";
	$html_totales_vendedor .= '<td class="text-left columna-info">' . @$super_rubros[$k]['nombre'] . '</td>' . "\n";
	$color_monto = color_promedio($v['actual'], $v['promedio_24'], 'monto');
	$html_totales_vendedor .= '<td class="text-right ' . $color_monto . '">' . numero_legible_redondeado($v['actual']) . "</td>\n";
	$color_monto = color_promedio($v['anterior'], $v['promedio_24'], 'monto');
	$html_totales_vendedor .= '<td class="text-right ' . $color_monto . '">' . numero_legible_redondeado($v['anterior']) . "</td>\n";
	$color_monto = color_promedio($v['promedio_3'], $v['promedio_24'], 'monto');
	$html_totales_vendedor .= '<td class="text-right ' . $color_monto . '">' . numero_legible_redondeado($v['promedio_3']) . "</td>\n";
	$color_monto = color_promedio($v['promedio_12'], $v['promedio_24'], 'monto');
	$html_totales_vendedor .= '<td class="text-right ' . $color_monto . '">' . numero_legible_redondeado($v['promedio_12']) . "</td>\n";
	$html_totales_vendedor .= '<td class="text-right">' . numero_legible_redondeado( $total_sr_activos[$k] ) . "</td>\n";
	//$html_totales_vendedor .= '<td class="text-right">' . numero_legible_redondeado( divide( $stock_super_rubros[$k]['unidades'], $v['promedio_6']) ) . "</td>\n";
	$html_totales_vendedor .= '<td class="text-right">' . numero_legible_redondeado( divide( $stock_super_rubros[$k]['unidades'],$promedio_ventas_6m[$k]['unidades']) ) . "</td>\n";
	$html_totales_vendedor .= '<td></td>' . "\n";
	$html_totales_vendedor .= '</tr>' . "\n";
}

$html_totales_vendedor .= '</table>' . "\n";

$html_totales_vendedor .= '<br />' . "\n";
$html_totales_vendedor .= '&nbsp;<input type="checkbox" id="ver-proximos" />' . "\n";
$html_totales_vendedor .= '&nbsp;<label for="ver-proximos">Ver solo aquellos super rubros que tienen la mitad de unidades vendidas para llegar a SR Activo</label><br />' . "\n";