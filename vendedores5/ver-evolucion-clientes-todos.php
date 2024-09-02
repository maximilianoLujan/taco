<?php
	$html_general = '<table class="tabla-coloreada table table-bordered table-striped table-condensed table-hover">' . "\n";
	$html_general .= '<tr class="info">' . "\n";
	$html_general .= '<th>Cliente</th>' . "\n";
	$html_general .= '<th class="text-right columna-info boton-reordenar">Actual</th>' . "\n";
	$html_general .= '<th class="text-right columna-info boton-reordenar">Mes anterior</th>' . "\n";
	$html_general .= '<th class="text-right columna-info boton-reordenar">Prom 3</th>' . "\n";
	$html_general .= '<th class="text-right columna-info boton-reordenar">Prom 6</th>' . "\n";
	$html_general .= '<th class="text-right columna-info boton-reordenar">Prom 12</th>' . "\n";
	$html_general .= '<th class="text-right columna-info boton-reordenar">Prom 24</th>' . "\n";
	$html_general .= '<th class="por-que-no-compra">¿Por qué no compra?</th>' . "\n";
	$html_general .= '</tr>' . "\n";

	foreach($clientes_ordenados as $k=>$v){
		$k = strval($k);
		$cliente = $clientes[$k];
		$html_general .= "<tr>\n";

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
			$html_general .= '<td><span class="crop-1">' . $cliente['nombre_cliente'] . ' - (Dto. ' . floatval($cliente['bonificacion']) . "%)</span>\n";
			$html_general .= "<a href='/precios-congelados-7/movimientos-cliente.php?id=$k' class='btn btn-xs pull-right $color' target='_blank'>" . k($saldo_fric_rot['saldo']) . ' / ' . k($saldo_distrisuper['saldo']) . "</a>\n";
			$html_general .= $html_plan_cliente;
			$html_general .= "</th>\n";
		}else{
			$html_general .= '<td><span class="crop-1">' . $cliente['nombre_cliente'] . ' - (Dto. ' . floatval($cliente['bonificacion']) . "%)</span>$html_plan_cliente</td>\n";
		}

		$color_monto = color_promedio($ventas_mes_corriente_x_cliente[$k]['monto'], $promedio_por_cliente_24[$k]['monto'], 'monto');
		$html_general .= '<td class="text-right ' . $color_monto . '">' . numero_legible_redondeado($ventas_mes_corriente_x_cliente[$k]['unidades']) . ' (' . numero_en_miles($ventas_mes_corriente_x_cliente[$k]['monto']) . ")</td>\n";

		$color_monto = color_promedio($ventas_mes_anterior_x_cliente[$k]['monto'], $promedio_por_cliente_24[$k]['monto'], 'monto');
		$html_general .= '<td class="text-right ' . $color_monto . '">' . numero_legible_redondeado($ventas_mes_anterior_x_cliente[$k]['unidades']) . ' (' . numero_en_miles($ventas_mes_anterior_x_cliente[$k]['monto']) . ")</td>\n";

		$color_monto = color_promedio($promedio_por_cliente_3[$k]['monto'], $promedio_por_cliente_24[$k]['monto'], 'monto');
		$html_general .= '<td class="text-right ' . $color_monto . '">' . numero_legible_redondeado($promedio_por_cliente_3[$k]['unidades']) . ' (' . numero_en_miles($promedio_por_cliente_3[$k]['monto']) . ")</td>\n";

		$color_monto = color_promedio($promedio_por_cliente_6[$k]['monto'], $promedio_por_cliente_24[$k]['monto'], 'monto');
		$html_general .= '<td class="text-right ' . $color_monto . '">' . numero_legible_redondeado($promedio_por_cliente_6[$k]['unidades']) . ' (' . numero_en_miles($promedio_por_cliente_6[$k]['monto']) . ")</td>\n";

		$color_monto = color_promedio($promedio_por_cliente_12[$k]['monto'], $promedio_por_cliente_24[$k]['monto'], 'monto');
		$html_general .= '<td class="text-right ' . $color_monto . '">' . numero_legible_redondeado($promedio_por_cliente_12[$k]['unidades']) . ' (' . numero_en_miles($promedio_por_cliente_12[$k]['monto']) . ")</td>\n";

		$color_monto = color_promedio($promedio_por_cliente_24[$k]['monto'], $promedio_por_cliente_24[$k]['monto'], 'monto');
		$html_general .= '<td class="text-right ' . $color_monto . '">' . numero_legible_redondeado($promedio_por_cliente_24[$k]['unidades']) . ' (' . numero_en_miles($promedio_por_cliente_24[$k]['monto']) . ")</td>\n";

		$por_que_no_compra = get_por_que_no_compra($k, '');
		$html_general .= '<td class="por-que-no-compra" data-cliente="' . $k . '" contenteditable>' . $por_que_no_compra . '</td>' . "\n";

		$html_general .= "</tr>\n";
	}

	$html_general .= '</table>' . "\n";

