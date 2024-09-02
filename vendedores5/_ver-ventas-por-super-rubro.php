<?php
$super_rubros = get_super_rubros();
$super_rubros_especiales = get_super_rubros_especiales();

$html_general = '';

$rubros_activos_vendedor_mes_actual = 0;
$rubros_activos_vendedor_mes_anterior = 0;
$rubros_activos_vendedor_promedio_6 = 0;
$rubros_activos_vendedor_promedio_12 = 0;
$rubros_activos_vendedor_promedio_24 = 0;

$monto_cliente_activo = get_config('monto_cliente_activo');

foreach( $clientes_ordenados as $k=>$v ){
	$cliente = $clientes[$k];

	$ventas_x_cliente_x_super_rubros_actual = ventas_x_cliente_x_super_rubros( $k, 0 );
	$ventas_x_cliente_x_super_rubros_anterior = ventas_x_cliente_x_super_rubros( $k, 1 );
	$ventas_x_cliente_x_super_rubros_6 = ventas_x_cliente_x_super_rubros( $k, 6 );
	$ventas_x_cliente_x_super_rubros_12 = ventas_x_cliente_x_super_rubros( $k, 12 );
	$ventas_x_cliente_x_super_rubros_24 = ventas_x_cliente_x_super_rubros( $k, 24 );

	$ventas_x_cliente_x_super_rubros = array_merge(
		array_keys($super_rubros_especiales), 
		array_keys($ventas_x_cliente_x_super_rubros_actual), 
		array_keys($ventas_x_cliente_x_super_rubros_anterior), 
		array_keys($ventas_x_cliente_x_super_rubros_6),
		array_keys($ventas_x_cliente_x_super_rubros_12),
		array_keys($ventas_x_cliente_x_super_rubros_24)
	);

	if( $ventas_mes_corriente_x_cliente[$k]['monto'] >= $monto_cliente_activo ){
		$cliente_activo_mes_corriente = true;
	}else{
		$cliente_activo_mes_corriente = false;
	}

	if( $ventas_mes_anterior_x_cliente[$k]['monto'] >= $monto_cliente_activo ){
		$cliente_activo_mes_anterior = true;
	}else{
		$cliente_activo_mes_anterior = false;
	}

	if( $promedio_por_cliente_6[$k]['monto'] >= $monto_cliente_activo ){
		$cliente_activo_6 = true;
	}else{
		$cliente_activo_6 = false;
	}

	if( $promedio_por_cliente_12[$k]['monto'] >= $monto_cliente_activo ){
		$cliente_activo_12 = true;
	}else{
		$cliente_activo_12 = false;
	}

	if( $promedio_por_cliente_24[$k]['monto'] >= $monto_cliente_activo ){
		$cliente_activo_24 = true;
	}else{
		$cliente_activo_24 = false;
	}

	$ventas_x_cliente_x_super_rubros = array_unique($ventas_x_cliente_x_super_rubros);

	$html_general .= '<table class="tabla-coloreada table table-bordered table-striped table-condensed table-hover">' . "\n";
	$html_general .= '<tr class="info">' . "\n";
	$html_general .= '<th></th>' . "\n";
	$html_general .= '<th class="text-right columna-info boton-reordenar">Actual</th>' . "\n";
	$html_general .= '<th class="text-right columna-info boton-reordenar">Mes anterior</th>' . "\n";
	$html_general .= '<th class="text-right columna-info boton-reordenar">Prom 6</th>' . "\n";
	$html_general .= '<th class="text-right columna-info boton-reordenar">Prom 12</th>' . "\n";
	$html_general .= '<th class="text-right columna-info boton-reordenar">Prom 24</th>' . "\n";
	$html_general .= '<th class="por-que-no-compra">¿Por qué no compra?</th>' . "\n";
	$html_general .= '</tr>' . "\n";
	$html_general .= '<tr>' . "\n";

	//fila general cliente 
	if( tiene_op_esp($k) ){
		$html_general .= '<th class="text-left columna-info"><span class="crop-1">' . $cliente['nombre_cliente'] . ' - (Dto. ' . floatval($cliente['bonificacion']) . "%)</span>\n";
		$html_general .= "<a href='/precios-congelados-7/movimientos-cliente.php?id=$k' class='btn btn-primary btn-xs pull-right' target='_blank'>Op Esp</a></th>\n";
	}else{
		$html_general .= '<th class="text-left columna-info"><span class="crop-1">' . $cliente['nombre_cliente'] . ' - (Dto. ' . floatval($cliente['bonificacion']) . "%)</span></th>\n";
	}

	$color_monto = color_promedio($ventas_mes_corriente_x_cliente[$k]['monto'], $promedio_por_cliente_24[$k]['monto'], 'monto');
	$html_general .= '<th class="text-right columna-info ' . $color_monto . '">' . numero_legible_redondeado($ventas_mes_corriente_x_cliente[$k]['unidades']) . ' (' . numero_en_miles($ventas_mes_corriente_x_cliente[$k]['monto']) . ")</th>\n";

	$color_monto = color_promedio($ventas_mes_anterior_x_cliente[$k]['monto'], $promedio_por_cliente_24[$k]['monto'], 'monto');
	$html_general .= '<th class="text-right columna-info ' . $color_monto . '">' . numero_legible_redondeado($ventas_mes_anterior_x_cliente[$k]['unidades']) . ' (' . numero_en_miles($ventas_mes_anterior_x_cliente[$k]['monto']) . ")</th>\n";

	$color_monto = color_promedio($promedio_por_cliente_6[$k]['monto'], $promedio_por_cliente_24[$k]['monto'], 'monto');
	$html_general .= '<th class="text-right columna-info ' . $color_monto . '">' . numero_legible_redondeado($promedio_por_cliente_6[$k]['unidades']) . ' (' . numero_en_miles($promedio_por_cliente_6[$k]['monto']) . ")</th>\n";

	$color_monto = color_promedio($promedio_por_cliente_12[$k]['monto'], $promedio_por_cliente_24[$k]['monto'], 'monto');
	$html_general .= '<th class="text-right columna-info ' . $color_monto . '">' . numero_legible_redondeado($promedio_por_cliente_12[$k]['unidades']) . ' (' . numero_en_miles($promedio_por_cliente_12[$k]['monto']) . ")</th>\n";

	$color_monto = color_promedio($promedio_por_cliente_24[$k]['monto'], $promedio_por_cliente_24[$k]['monto'], 'monto');
	$html_general .= '<th class="text-right columna-info ' . $color_monto . '">' . numero_legible_redondeado($promedio_por_cliente_24[$k]['unidades']) . ' (' . numero_en_miles($promedio_por_cliente_24[$k]['monto']) . ")</th>\n";
	
	$por_que_no_compra = get_por_que_no_compra($k, '');
	$html_general .= '<th class="por-que-no-compra" data-cliente="' . $k . '" contenteditable>' . $por_que_no_compra . '</th>' . "\n";
	$html_general .= "</tr>\n";
	
	$linea_html = array();
	$rubros_activos_mes_actual = 0;
	$rubros_activos_mes_anterior = 0;
	$rubros_activos_promedio_6 = 0;
	$rubros_activos_promedio_12 = 0;
	$rubros_activos_promedio_24 = 0;

	$descuento_maximo_marca = descuento_maximo_marca($k);

	foreach($ventas_x_cliente_x_super_rubros as $super_rubro){
		$id_super_rubro = strval($super_rubro);
		if( @$super_rubros[$id_super_rubro]['orden'] ){
			$valor_para_ordenar[$id_super_rubro] = $super_rubros[$id_super_rubro]['orden'];
		}else{
			$valor_para_ordenar[$id_super_rubro] = 999999;
		}

		$unidades_actual = $ventas_x_cliente_x_super_rubros_actual[$id_super_rubro]['unidades'];
		$monto_actual = $ventas_x_cliente_x_super_rubros_actual[$id_super_rubro]['monto'];

		$unidades_anterior = $ventas_x_cliente_x_super_rubros_anterior[$id_super_rubro]['unidades'];
		$monto_anterior = $ventas_x_cliente_x_super_rubros_anterior[$id_super_rubro]['monto'];

		$unidades_6 = $ventas_x_cliente_x_super_rubros_6[$id_super_rubro]['unidades']/6;
		$monto_6 = $ventas_x_cliente_x_super_rubros_6[$id_super_rubro]['monto']/6;

		$unidades_12 = $ventas_x_cliente_x_super_rubros_12[$id_super_rubro]['unidades']/12;
		$monto_12 = $ventas_x_cliente_x_super_rubros_12[$id_super_rubro]['monto']/12;

		$unidades_24 = $ventas_x_cliente_x_super_rubros_24[$id_super_rubro]['unidades']/24;
		$monto_24 = $ventas_x_cliente_x_super_rubros_24[$id_super_rubro]['monto']/24;

		if( $unidades_actual > 5 && $unidades_actual < 10 ){
			$es_proximo = 'es-proximo';
		}else{
			$es_proximo = 'no-es-proximo';
		}

		if( $valor_para_ordenar[$id_super_rubro] <= 25 || $unidades_actual >= 1 ){
			$linea_html[$id_super_rubro] = "<tr class='row-super-rubro $es_proximo'>\n";
		}else{
			$linea_html[$id_super_rubro] = "<tr class='hidden'>\n";
		}
		
		$descuento_marca = '';
		if(isset($descuento_maximo_marca[$id_super_rubro])){
			$descuento_marca = ' (' . floatval($descuento_maximo_marca[$id_super_rubro]) . '%)';
		}

		if( $unidades_actual >= 10 ){
			$linea_html[$id_super_rubro] .= '<td class="alert alert-success detalle-marcas" data-cliente="' . $k . '" data-sr="' . $id_super_rubro . '">
			<span class="crop-1">' . @$super_rubros[$id_super_rubro]['nombre'] . "$descuento_marca</span></td>\n";
			$rubros_activos_mes_actual++;
			$extra_class = ' activo_a';
		}else{
			$linea_html[$id_super_rubro] .= '<td class="detalle-marcas" data-cliente="' . $k . '" data-sr="' . $id_super_rubro . '">
			<span class="crop-1">' . @$super_rubros[$id_super_rubro]['nombre'] . "$descuento_marca</span></td>\n";
			$extra_class = '';
		}

		$color_monto = color_promedio($monto_actual, $monto_24, 'monto');
		$linea_html[$id_super_rubro] .= '<td class="text-right ' . $color_monto . $extra_class. '"><div class="cantidad">' . numero_legible_redondeado( $unidades_actual ) .
		' (' . numero_en_miles($monto_actual) . ")</div></td>\n";

		$extra_class = '';
		if( $unidades_anterior >= 10 ){
			$rubros_activos_mes_anterior++;
			$extra_class = ' activo_a';
		}
		$color_monto = color_promedio($monto_anterior, $monto_24, 'monto');
		$linea_html[$id_super_rubro] .= '<td class="text-right ' . $color_monto . $extra_class . '"><div class="cantidad">' . numero_legible_redondeado( $unidades_anterior ) .
		' (' . numero_en_miles($monto_anterior) . ")</div></td>\n";
		
		$extra_class = '';
		if( $unidades_6 >= 10 ){
			$rubros_activos_promedio_6++;
			$extra_class = ' activo_a';
		}
		$color_monto = color_promedio($monto_6, $monto_24, 'monto');
		$linea_html[$id_super_rubro] .= '<td class="text-right ' . $color_monto . $extra_class . '"><div class="cantidad">' . numero_legible_redondeado( $unidades_6 ) .
		' (' . numero_en_miles($monto_6) . ")</div></td>\n";

		$extra_class = '';
		if( $unidades_12 >= 10 ){
			$rubros_activos_promedio_12++;
			$extra_class = ' activo_a';
		}
		$color_monto = color_promedio($monto_12, $monto_24, 'monto');
		$linea_html[$id_super_rubro] .= '<td class="text-right ' . $color_monto . $extra_class . '"><div class="cantidad">' . numero_legible_redondeado( $unidades_12 ) .
		' (' . numero_en_miles($monto_12) . ")</div></td>\n";

		$extra_class = '';
		if( $unidades_24 >= 10 ){
			$rubros_activos_promedio_24++;
			$extra_class = ' activo_a';
		}
		$color_monto = color_promedio($monto_24, $monto_24, 'monto');
		$linea_html[$id_super_rubro] .= '<td class="text-right ' . $color_monto . $extra_class . '"><div class="cantidad">' . numero_legible_redondeado( $unidades_24 ) .
		' (' . numero_en_miles($monto_24) . ")</div></td>\n";
		
		if( strlen(trim($id_super_rubro)) > 0 ){
			$por_que_no_compra = get_por_que_no_compra($k, $id_super_rubro);
			$linea_html[$id_super_rubro] .= '<td class="por-que-no-compra" data-cliente="' . $k . '" data-sr="' . $id_super_rubro . '" contenteditable>' . $por_que_no_compra . "</td>\n";	
		}else{
			$linea_html[$id_super_rubro] .= '<td disabled>' . "</td>\n";	
		}

		$linea_html[$id_super_rubro] .= "</tr>\n";
	}

	//fila totales promedios cliente
	$detalles_cliente = get_cliente($k);
	if( $cliente_activo_mes_corriente ){ // ver acá lucas // solo suma los rubros activos de clientes activos, en objetivos-ventas suma todos
		$rubros_activos_vendedor_mes_actual += $rubros_activos_mes_actual;
	}
	if( $cliente_activo_mes_anterior ){
		$rubros_activos_vendedor_mes_anterior += $rubros_activos_mes_anterior;
	}
	if( $cliente_activo_6 ){
		$rubros_activos_vendedor_promedio_6 += $rubros_activos_promedio_6;
	}
	if( $cliente_activo_12 ){
		$rubros_activos_vendedor_promedio_12 += $rubros_activos_promedio_12;
	}
	if( $cliente_activo_24 ){
		$rubros_activos_vendedor_promedio_24 += $rubros_activos_promedio_24;
	}

	$linea_totales_html = '<tr>' . "\n";
	$linea_totales_html .= '<th class="text-left columna-info">Rubros Activos</th>' . "\n";

	$color_monto = color_promedio($rubros_activos_mes_actual, $rubros_activos_promedio_24, 'monto');
	$linea_totales_html .= '<th class="text-right columna-info ' . $color_monto . '">' . $rubros_activos_mes_actual . "</th>\n";

	$color_monto = color_promedio($rubros_activos_mes_anterior, $rubros_activos_promedio_24, 'monto');
	$linea_totales_html .= '<th class="text-right columna-info ' . $color_monto . '">' . $rubros_activos_mes_anterior . "</th>\n";

	$color_monto = color_promedio($rubros_activos_promedio_6, $rubros_activos_promedio_24, 'monto');
	$linea_totales_html .= '<th class="text-right columna-info ' . $color_monto . '">' . $rubros_activos_promedio_6 . "</th>\n";

	$color_monto = color_promedio($rubros_activos_promedio_12, $rubros_activos_promedio_24, 'monto');
	$linea_totales_html .= '<th class="text-right columna-info ' . $color_monto . '">' . $rubros_activos_promedio_12 . "</th>\n";

	$color_monto = color_promedio($rubros_activos_promedio_24, $rubros_activos_promedio_24, 'monto');
	$linea_totales_html .= '<th class="text-right columna-info ' . $color_monto . '">' . $rubros_activos_promedio_24 . "</th>\n";
	
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
		$html_general .= $linea_html[$id_super_rubro];
	}

	$html_general .= '</table>' . "\n";
	$html_general .= '<br />' . "\n";

}


$html_totales_vendedor = '<table class="tabla-coloreada table table-bordered table-striped table-condensed table-hover">' . "\n";
$html_totales_vendedor .= '<tr class="info">' . "\n";
$html_totales_vendedor .= '<th></th>' . "\n";
$html_totales_vendedor .= '<th class="text-right columna-infor">Actual</th>' . "\n";
$html_totales_vendedor .= '<th class="text-right columna-infor">Mes anterior</th>' . "\n";
$html_totales_vendedor .= '<th class="text-right columna-infor">Prom 6</th>' . "\n";
$html_totales_vendedor .= '<th class="text-right columna-infor">Prom 12</th>' . "\n";
$html_totales_vendedor .= '<th class="text-right columna-infor">Prom 24</th>' . "\n";
$html_totales_vendedor .= '<th class="por-que-no-compra"></th>' . "\n";
$html_totales_vendedor .= '</tr>' . "\n";
$html_totales_vendedor .= '<tr>' . "\n";
$html_totales_vendedor .= '<td class="text-left columna-info">Totales Super Rubros</td>' . "\n";

$color_monto = color_promedio($rubros_activos_vendedor_mes_actual, $rubros_activos_vendedor_promedio_24, 'monto');
$html_totales_vendedor .= '<td class="text-right ' . $color_monto . '">' . numero_legible_redondeado($rubros_activos_vendedor_mes_actual) . "</td>\n";

$color_monto = color_promedio($rubros_activos_vendedor_mes_anterior, $rubros_activos_vendedor_promedio_24, 'monto');
$html_totales_vendedor .= '<td class="text-right ' . $color_monto . '">' . numero_legible_redondeado($rubros_activos_vendedor_mes_anterior) . "</td>\n";

$color_monto = color_promedio($rubros_activos_vendedor_promedio_6, $rubros_activos_vendedor_promedio_24, 'monto');
$html_totales_vendedor .= '<td class="text-right ' . $color_monto . '">' . numero_legible_redondeado($rubros_activos_vendedor_promedio_6) . "</td>\n";

$color_monto = color_promedio($rubros_activos_vendedor_promedio_12, $rubros_activos_vendedor_promedio_24, 'monto');
$html_totales_vendedor .= '<td class="text-right ' . $color_monto . '">' . numero_legible_redondeado($rubros_activos_vendedor_promedio_12) . "</td>\n";

$color_monto = color_promedio($rubros_activos_vendedor_promedio_24, $rubros_activos_vendedor_promedio_24, 'monto');
$html_totales_vendedor .= '<td class="text-right ' . $color_monto . '">' . numero_legible_redondeado($rubros_activos_vendedor_promedio_24) . "</td>\n";

$html_totales_vendedor .= '<td></td>' . "\n";

$html_totales_vendedor .= '</tr>' . "\n";
$html_totales_vendedor .= '</table>' . "\n";

$html_totales_vendedor .= '<br />' . "\n";
$html_totales_vendedor .= '&nbsp;<input type="checkbox" id="ver-proximos" />' . "\n";
$html_totales_vendedor .= '&nbsp;<label for="ver-proximos">Ver solo aquellos super rubros que tienen entre 5 y 9 unidades vendidas en el mes actual</label><br />' . "\n";