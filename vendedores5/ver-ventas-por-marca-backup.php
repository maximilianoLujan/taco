<?php
$marcas = get_marcas();

foreach( $clientes_ordenados as $k=>$v ){
	$cliente = $clientes[$k];
?>
<table class="tabla-coloreada table table-bordered table-striped table-condensed table-hover">
	<tr class="info">
		<th></th>
		<?php
		$ventas_x_cliente = array();
		$ventas_x_cliente_x_marca = array();
		$promedio_por_marca = array();
        $promedio_monto_cliente_mostrar = $promedio_monto_cliente[$k] / 1000;
		echo '<th class="text-right columna-info">Promedio' . "</th>\n";
		echo '<th class="text-right columna-info">% $' . "</th>\n";
		foreach($array_fechas_legibles as $fecha_interna=>$fecha_legible){
			echo '<th class="text-right columna-info">' . $fecha_legible . "</th>\n";
		}
		echo "</tr>\n<tr>";
		echo '<th class="text-left columna-info"><span class="crop-1">' . $cliente['nombre_cliente'] . "</span></th>\n";
		echo '<th class="text-right columna-info">' . numero_legible_redondeado($promedio_unidades_cliente[$k]) .
        ' (' . numero_legible_redondeado($promedio_monto_cliente_mostrar) . ")</th>\n";
		echo '<th class="text-right columna-info">%100' . "</th>\n";
		$i=0;
		foreach($array_fechas_legibles as $fecha_interna=>$fecha_legible){
			$ventas_x_cliente[$fecha_interna] = ventas_x_cliente( $k, $fecha_interna ); ////////////////////// OPTIMIZAR ACA
			$ventas_x_cliente_x_marca[$fecha_interna] = ventas_x_cliente_x_marca( $k, $fecha_interna ); ////////////////////// OPTIMIZAR ACA
			if( $fecha_interna == date('Y-n') ){
				$porcentaje_periodo = date("d") / date("d",mktime(0,0,0,date('n')+1,0,date('Y')));
				if($porcentaje_periodo==0){
					$porcentaje_periodo = 0.033;
				}
				$monto = $ventas_x_cliente[$fecha_interna]['monto'] / $porcentaje_periodo;
				$color_monto = color_promedio($monto, $promedio_por_cliente[$k]['monto'], 'monto');
			}else{
				$color_monto = color_promedio( $ventas_x_cliente[$fecha_interna]['monto'], $promedio_por_cliente[$k]['monto'], 'monto' );
			}
			echo '<th class="text-right columna-info ' . $color_monto . '">' . numero_legible_redondeado($ventas_x_cliente[$fecha_interna]['unidades']);
			if($i==0){
				echo " (" . numero_legible_redondeado($ventas_x_cliente[$fecha_interna]['monto']/1000 ) . ") \n";
				$i=1;
			}
			echo "</th>\n";
		}
		?>
	</tr>
	<?php
		$linea_html = array();
		$promedio_monto_por_marca = array();
		$ventas_x_cliente_x_marca = rotar_matriz( $ventas_x_cliente_x_marca );
		foreach($ventas_x_cliente_x_marca as $marca=>$linea){
			$id_marca = strval($marca);
			$linea_html[$id_marca] = "<tr>\n";
			$linea_html[$id_marca] .= '<td><span class="crop-1">' . @$marcas[$id_marca] . "</span></td>\n";
			$total_marca['monto'] = 0;
			$total_marca['unidades'] = 0;
			foreach($array_fechas_legibles as $fecha_interna=>$fecha_legible){
				$total_marca['monto'] = $total_marca['monto'] + floatval(@$linea[$fecha_interna]['monto']);
				$total_marca['unidades'] = $total_marca['unidades'] + floatval(@$linea[$fecha_interna]['unidades']);
			}
			$promedio_unidades = $total_marca['unidades'] / $numero_para_promedio;
			$promedio_monto = $total_marca['monto'] / $numero_para_promedio;
			$promedio_monto_por_marca[$id_marca] = $promedio_monto;
            $promedio_monto_marca_mostrar = $promedio_monto / 1000;
			$linea_html[$id_marca] .= '<td class="text-right"><div class="cantidad">' . numero_legible_redondeado( $promedio_unidades ) .
            ' (' . numero_legible_redondeado($promedio_monto_marca_mostrar) . ")</div></td>\n";
			$linea_html[$id_marca] .= '<td class="text-right"><div class="cantidad">%' . numero_legible_centecimales( ($promedio_monto/$promedio_por_cliente[$k]['monto']) * 100 ) . "</div></td>\n";
			$i=0;
			foreach($array_fechas_legibles as $fecha_interna=>$fecha_legible){
				$monto = floatval(@$linea[$fecha_interna]['monto']);
				$unidades = floatval(@$linea[$fecha_interna]['unidades']);
				if( $fecha_interna == date('Y-n') ){
					$porcentaje_periodo = date("d") / date("d",mktime(0,0,0,date('n')+1,0,date('Y')));
					if($porcentaje_periodo==0){
						$porcentaje_periodo = 0.033;
					}
					$monto = $monto / $porcentaje_periodo;
					$color_monto = color_promedio($monto, $promedio_monto, 'monto');
				}else{
					$color_monto = color_promedio($monto, $promedio_monto, 'monto');
				}
//				$linea_html[$id_marca] .= '<td class="text-right ' . $color_monto . '"><div class="cantidad">' . numero_legible_redondeado($unidades) . "</div></td>\n";
				$linea_html[$id_marca] .= '<td class="text-right ' . $color_monto . '">' . numero_legible_redondeado($unidades) . "\n";
				if($i==0){
					$linea_html[$id_marca] .= " (" . numero_legible_redondeado($monto/1000 ) . ") \n";
					$i=1;
				}
				$linea_html[$id_marca] .= "</td>\n";
			}
			$linea_html[$id_marca] .= "</tr>\n";
		}
		arsort($promedio_monto_por_marca);
		foreach($promedio_monto_por_marca as $id_marca=>$promedio){
			echo $linea_html[$id_marca];
		}
	?>
</table>
<br />
<?php
}
?>
