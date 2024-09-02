<?php
$marcas = get_marcas();

foreach( $clientes_ordenados as $k=>$v ){
if($k=='02872'){
	$get_cliente = get_cliente($k);
?>
<table class="tabla-coloreada table table-bordered table-striped table-condensed table-hover">
	<tr class="info">
		<th></th>
		<?php
		$ventas_x_cliente = array();
		$ventas_x_cliente_x_marca = array();
		$promedio_por_marca = array();
		echo '<th class="text-right columna-info">Promedio' . "</th>\n";
		echo '<th class="text-right columna-info">% $' . "</th>\n";
		foreach($array_fechas_legibles as $fecha_interna=>$fecha_legible){
			echo '<th class="columna-info">' . $fecha_legible . "</th>\n";
		}
		echo "</tr>\n<tr>";
		echo '<th class="text-left columna-info">' . $get_cliente['nombre_cliente'] . "</th>\n";
		echo '<th class="text-right columna-info">' . numero_legible_redondeado($promedio_unidades_cliente[$k]) . "</th>\n";
		echo '<th class="text-right columna-info">%100' . "</th>\n";
		foreach($array_fechas_legibles as $fecha_interna=>$fecha_legible){
			$ventas_x_cliente[$fecha_interna] = ventas_x_cliente( $k, $fecha_interna );
			$ventas_x_cliente_x_marca[$fecha_interna] = ventas_x_cliente_x_marca( $k, $fecha_interna );
			$color_monto = color_promedio( $ventas_x_cliente[$fecha_interna]['monto'], $promedio_por_cliente[$k]['monto'], 'monto' );
			echo '<th class="text-right columna-info ' . $color_monto . '">' . $ventas_x_cliente[$fecha_interna]['unidades'] . "</th>\n";
		}
		?>
	</tr>
</table>
<pre>
	<?php
		$linea_html = array();
		$ventas_x_cliente_x_marca = rotar_matriz( $ventas_x_cliente_x_marca );

echo "$k - " . $get_cliente['nombre_cliente'] . "\n";
print_R($v);
print_R($ventas_x_cliente_x_marca);

/*
		foreach($ventas_x_cliente_x_marca as $marca=>$linea){
			$id_marca = strval($marca);
			$linea_html[$id_marca] = "<tr>\n";
			$linea_html[$id_marca] .= '<td>' . $marcas[$id_marca] . "</td>\n";
			$total_marca['monto'] = 0;
			$total_marca['unidades'] = 0;
			foreach($array_fechas_legibles as $fecha_interna=>$fecha_legible){
				$total_marca['monto'] = $total_marca['monto'] + floatval(@$linea[$fecha_interna]['monto']);
				$total_marca['unidades'] = $total_marca['unidades'] + floatval(@$linea[$fecha_interna]['unidades']);
			}
			$promedio_unidades = $total_marca['unidades'] / count($array_fechas);
			$promedio_monto = $total_marca['monto'] / count($array_fechas);
			$promedio_monto_por_marca[$id_marca] = $promedio_monto;
			$linea_html[$id_marca] .= '<td class="text-right"><div class="cantidad">' . numero_legible_redondeado( $promedio_unidades ) . "</div></td>\n";
			$linea_html[$id_marca] .= '<td class="text-right"><div class="cantidad">%' . numero_legible_centecimales( ($promedio_monto/$promedio_por_cliente[$k]['monto']) * 100 ) . "</div></td>\n";
			foreach($array_fechas_legibles as $fecha_interna=>$fecha_legible){
				$monto = floatval(@$linea[$fecha_interna]['monto']);
				$unidades = floatval(@$linea[$fecha_interna]['unidades']);
				$color_monto = color_promedio($monto, $promedio_monto, 'monto');
				$linea_html[$id_marca] .= '<td class="text-right ' . $color_monto . '"><div class="cantidad">' . numero_legible_redondeado($unidades) . "</div></td>\n";
			}
			$linea_html[$id_marca] .= "</tr>\n";
		}
		arsort($promedio_monto_por_marca);
		foreach($promedio_monto_por_marca as $id_marca=>$promedio){
			echo $linea_html[$id_marca];
		}
*/

	?>
</table>
<br />
</pre>
<?php
}
}
?>
