<?php
$marcas = get_marcas();

foreach( $clientes_ordenados as $k=>$v ){
	$cliente = $clientes[$k];
	$ventas_x_cliente_x_marcas_actual = ventas_x_cliente_x_marcas( $k, 0 );
	$ventas_x_cliente_x_marcas_3 = ventas_x_cliente_x_marcas( $k, 3 );
	$ventas_x_cliente_x_marcas_6 = ventas_x_cliente_x_marcas( $k, 6 );
	$ventas_x_cliente_x_marcas_12 = ventas_x_cliente_x_marcas( $k, 12 );
	$ventas_x_cliente_x_marcas_24 = ventas_x_cliente_x_marcas( $k, 24 );
?>
	<table class="tabla-coloreada table table-bordered table-striped table-condensed table-hover">
		<tr class="info">
			<th></th>
			<th class="text-right columna-info boton-reordenar">Actual</th>
			<th class="text-right columna-info boton-reordenar">Prom 3</th>
			<th class="text-right columna-info boton-reordenar">Prom 6</th>
			<th class="text-right columna-info boton-reordenar">Prom 12</th>
			<th class="text-right columna-info boton-reordenar">Prom 24</th>
			<th class="por-que-no-compra">¿Por qué no compra?</th>
		</tr>
		<tr>
		<?php
		if( tiene_op_esp($k) ){
			echo '<th class="text-left columna-info"><span class="crop-1">' . $cliente['nombre_cliente'] . "</span>\n";
			echo "<a href='/precios-congelados-7/movimientos-cliente.php?id=$k' class='btn btn-primary btn-xs pull-right' target='_blank'>Op Esp</a></th>\n";
		}else{
			echo '<th class="text-left columna-info"><span class="crop-1">' . $cliente['nombre_cliente'] . "</span></th>\n";
		}
		echo '<th class="text-right columna-info">' . numero_legible_redondeado($ventas_mes_corriente_x_cliente[$k]['unidades']) . ' (' . numero_legible_redondeado($ventas_mes_corriente_x_cliente[$k]['monto']) . ")</th>\n";
		echo '<th class="text-right columna-info">' . numero_legible_redondeado($promedio_por_cliente_3[$k]['unidades']) . ' (' . numero_legible_redondeado($promedio_por_cliente_3[$k]['monto']) . ")</th>\n";
		echo '<th class="text-right columna-info">' . numero_legible_redondeado($promedio_por_cliente_6[$k]['unidades']) . ' (' . numero_legible_redondeado($promedio_por_cliente_6[$k]['monto']) . ")</th>\n";
		echo '<th class="text-right columna-info">' . numero_legible_redondeado($promedio_por_cliente_12[$k]['unidades']) . ' (' . numero_legible_redondeado($promedio_por_cliente_12[$k]['monto']) . ")</th>\n";
		echo '<th class="text-right columna-info">' . numero_legible_redondeado($promedio_por_cliente_24[$k]['unidades']) . ' (' . numero_legible_redondeado($promedio_por_cliente_24[$k]['monto']) . ")</th>\n";
		?>
		<th class="por-que-no-compra"></th>
	</tr>
	<?php
		$linea_html = array();
		foreach($ventas_x_cliente_x_marcas as $marca=>$linea){
			$id_marca = strval($marca);
			$valor_para_ordenar[$id_marca] = $ventas_x_cliente_x_marcas_actual[$k]['unidades']/6;

			$linea_html[$id_marca] = "<tr>\n";
			$linea_html[$id_marca] .= '<td><span class="crop-1">' . @$marcas[$id_marca] . "</span></td>\n";

			$unidades_actual = $ventas_x_cliente_x_marcas_actual[$k]['unidades'];
			$monto_actual = $ventas_x_cliente_x_marcas_actual[$k]['monto'];

			$unidades_3 = $ventas_x_cliente_x_marcas_3[$k]['unidades'];
			$monto_3 = $ventas_x_cliente_x_marcas_3[$k]['monto'];

			$unidades_6 = $ventas_x_cliente_x_marcas_6[$k]['unidades'];
			$monto_6 = $ventas_x_cliente_x_marcas_6[$k]['monto'];

			$unidades_12 = $ventas_x_cliente_x_marcas_12[$k]['unidades'];
			$monto_12 = $ventas_x_cliente_x_marcas_12[$k]['monto'];

			$unidades_24 = $ventas_x_cliente_x_marcas_24[$k]['unidades'];
			$monto_24 = $ventas_x_cliente_x_marcas_24[$k]['monto'];

			$linea_html[$id_marca] .= '<td class="text-right"><div class="cantidad">' . numero_legible_redondeado( $unidades_actual ) .
			' (' . numero_legible_redondeado($monto_actual) . ")</div></td>\n";
			
			$linea_html[$id_marca] .= '<td class="text-right"><div class="cantidad">' . numero_legible_redondeado( $unidades_3 ) .
			' (' . numero_legible_redondeado($monto_3) . ")</div></td>\n";

			$linea_html[$id_marca] .= '<td class="text-right"><div class="cantidad">' . numero_legible_redondeado( $unidades_6 ) .
			' (' . numero_legible_redondeado($monto_6) . ")</div></td>\n";

			$linea_html[$id_marca] .= '<td class="text-right"><div class="cantidad">' . numero_legible_redondeado( $unidades_12 ) .
			' (' . numero_legible_redondeado($monto_12) . ")</div></td>\n";

			$linea_html[$id_marca] .= '<td class="text-right"><div class="cantidad">' . numero_legible_redondeado( $unidades_24 ) .
			' (' . numero_legible_redondeado($monto_24) . ")</div></td>\n";
			
			$linea_html[$id_marca] .= '<td class="por-que-no-compra">' . "</td>\n";

			$linea_html[$id_marca] .= "</tr>\n";
		}
		arsort($valor_para_ordenar);
		foreach($valor_para_ordenar as $id_marca=>$promedio){
			echo $linea_html[$id_marca];
		}
	?>
</table>
<br />
<?php
}
?>
