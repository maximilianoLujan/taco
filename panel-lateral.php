<?php
ini_set('display_errors', 0);
include_once('conexion-mysql.php');
include_once('funciones-base-de-datos.php');
include_once('funciones.php');

$tipo = @$_GET['tipo']; //Años, Trimestre, ...
// falta ajuste inflacion

if( $tipo == 'Años'){
	$columnas = 4;
}elseif( $tipo == 'Trimestre' ){
	$columnas = 8;
}else{
	$columnas = 36;
}

for($i=1;$i<=$columnas;$i++){
	if( !isset($fechas) ){
		$f = periodo_actual( $tipo );
	}else{
		$f = periodo_anterior( $f, $tipo );
	}
	$fechas[] = $f;

	$v = calcular_ventas('Total',$f, $tipo);
	$ventas[] = $v['monto'];

	$st = calcular_stock('Total',$f, $tipo);
	$stock[] = $st['monto'];

	$costos_fijos[] = calcular_costos_fijos('Total',$f, $tipo);
	$rentabilidad[] = calcular_rentabilidad('Total',$f, $tipo);
//	$rentabilidad[] = calcular_rentabilidad_stock('Total',$f, $tipo);
	$calcular_rentabilidad_sv[] = calcular_rentabilidad_sv('Total',$f, $tipo);
//	$calcular_rentabilidad_sv[] = calcular_rentabilidad_stock_sv('Total',$f, $tipo);
	$calcular_deudas_clientes[] = calcular_deudas_clientes('Total',$f, $tipo);
	$calcular_deudas_proveedores[] = calcular_deudas_proveedores($f, $tipo);
	$calcular_cobranzas_total[] = calcular_cobranzas_total($f, $tipo);
	$calcular_r_total_cobranzas[] = calcular_r_total_cobranzas($f, $tipo);
	$calcular_r_porcentaje_cobranzas[] = calcular_r_porcentaje_cobranzas($f, $tipo);
	$caja[] = saldo_caja($f, $tipo);
	$disponibilidad[] = disponibilidad($f, $tipo);
}

$ajuste_inflacion = array();
foreach($fechas as $fecha){
	$ajuste_inflacion[$fecha] = 1;
}

if( @$_GET['ajuste-inflacion'] == 'A.I Si'){
	$ajuste_inflacion = ajuste_inflacion($fechas,$tipo);
}
?>
<div class="recuadro">
	<div class="scrollable">
		<table>
			<colgroup>
				<col class="separador-col" />
				<col class="separador-col" />
				<?php foreach($fechas as $f){ ?>
				<col class="separador-col" />
				<?php } ?>
			</colgroup>
			<tr>
				<th></th>
				<th>Promedio</th>
				<?php 
					foreach($fechas as $f){
						echo '<th>' . fecha_legible( $f ) . '</th>';
					}
				?>
			</tr>
			<tr>
				<td><a href="./ventas" class="titulo">Ventas:</a></td>
				<?php
					$inflacion_acumulada = 1;
					$i = 0;
					$fila = array();
					foreach($ventas as $v){
						if( is_numeric($ajuste_inflacion[$fechas[$i]]) ){
							$inflacion_acumulada = $inflacion_acumulada * $ajuste_inflacion[$fechas[$i]];
							$v = $v * $inflacion_acumulada;
						}
						$i++;
						$fila[] = $v;
					}
					$array_ventas = $fila;
					$promedio_ventas = promedio_array($fila,2);
				?>
				<td><strong>$ <?php echo numero_legible_redondeado( $promedio_ventas ); ?></strong></td>
				<?php
					foreach($fila as $v){
						echo '<td>$ ' . numero_legible_redondeado( $v ) . '</td>';
					}
				?>
			</tr>
			<tr>
				<td>Gastos:</td>
				<?php
					$inflacion_acumulada = 1;
					$i = 0;
					$fila = array();
					foreach($costos_fijos as $cf){
						if( is_numeric($ajuste_inflacion[$fechas[$i]]) ){
							$inflacion_acumulada = $inflacion_acumulada * $ajuste_inflacion[$fechas[$i]];
							$cf = $cf * $inflacion_acumulada;
						}
						$i++;
						$fila[] = $cf;
					}
					$array_gastos = $fila;
					$promedio_gastos = promedio_array($fila,2);
				?>
				<td><strong>$ <?php echo numero_legible_redondeado( $promedio_gastos ); ?></strong></td>
				<?php
					foreach($fila as $v){
						echo '<td>$ ' . numero_legible_redondeado( $v ) . '</td>';
					}
				?>
			</tr>
			<tr>
				<td>Gastos %:</td>
				<?php
					$fila = array();
					for($i=0;$i<count($array_gastos);$i++){
						if( $array_ventas[$i]!=0 ){
							$fila[] = ($array_gastos[$i] / $array_ventas[$i]) * 100;
						}else{
							$fila[] = 0;
						}
					}
					$promedio_gastos_porcentaje = 0; 
					if($promedio_ventas!=0){
						$promedio_gastos_porcentaje = ($promedio_gastos / $promedio_ventas) * 100;
					}
				?>
				<td><strong>% <?php echo numero_legible( $promedio_gastos_porcentaje ); ?></strong></td>
				<?php
					foreach($fila as $v){
						echo '<td>% ' . numero_legible( $v ) . '</td>';
					}
				?>
			</tr>
			<tr>
				<td><a href="./rentabilidad" class="titulo">Rent. Neta:</a></td>
				<?php
					$inflacion_acumulada = 1;
					$i = 0;
					$fila = array();
					foreach($rentabilidad as $r){
						if( is_numeric($ajuste_inflacion[$fechas[$i]]) ){
							$inflacion_acumulada = $inflacion_acumulada * $ajuste_inflacion[$fechas[$i]];
							$r = $r * $inflacion_acumulada;
						}
						$i++;
						$fila[] = $r;
					}
					$promedio_rent_neta = promedio_array($fila,2);
				?>
				<td><strong>$ <?php echo numero_legible_redondeado( $promedio_rent_neta ); ?></strong></td>
				<?php
					foreach($fila as $v){
						echo '<td>$ ' . numero_legible_redondeado( $v ) . '</td>';
					}
				?>
			</tr>
			<tr>
				<td><a href="./rentabilidad" class="titulo">Rent. Neta %:</a></td>
				<?php
					$fila = array();
					foreach($calcular_rentabilidad_sv as $rsv){
						$fila[] = $rsv;
					}
				?>
				<td><strong>% <?php if($promedio_ventas!=0){ echo numero_legible( (100*$promedio_rent_neta/$promedio_ventas) );}else{ echo '0';} ?></strong></td>
				<?php
					foreach($fila as $v){
						echo '<td>% ' . numero_legible( $v ) . '</td>';
					}
				?>
			</tr>
			<tr>
				<td><a href="./stock" class="titulo">Stock:</a></td>
				<?php
					$inflacion_acumulada = 1;
					$i = 0;
					$fila = array();
					foreach($stock as $st){
						if( is_numeric($ajuste_inflacion[$fechas[$i]]) ){
							$inflacion_acumulada = $inflacion_acumulada * $ajuste_inflacion[$fechas[$i]];
							$st = $st * $inflacion_acumulada;
						}
						$i++;
						$fila[] = $st;
					}
				?>
				<td><strong>$ <?php echo numero_legible_redondeado( promedio_array($fila,2) ); ?></strong></td>
				<?php
					foreach($fila as $v){
						echo '<td>$ ' . numero_legible_redondeado( $v ) . '</td>';
					}
				?>
			</tr>
			<tr>
				<td><a href="./deudas-clientes" class="titulo">Deudas Cli:</a></td>
				<?php
					$inflacion_acumulada = 1;
					$i = 0;
					$fila = array();
					foreach($calcular_deudas_clientes as $dc){
						if( is_numeric($ajuste_inflacion[$fechas[$i]]) ){
							$inflacion_acumulada = $inflacion_acumulada * $ajuste_inflacion[$fechas[$i]];
							$dc = $dc * $inflacion_acumulada;
						}
						$i++;
						$fila[] = $dc;
					}
				?>
				<td><strong>$ <?php echo numero_legible_redondeado( promedio_array($fila,2) ); ?></strong></td>
				<?php
					foreach($fila as $v){
						echo '<td>$ ' . numero_legible_redondeado( $v ) . '</td>';
					}
				?>
			</tr>
			<tr>
				<td><a href="./deudas-proveedores" class="titulo">Deudas Prov:</a></td>
				<?php
					$inflacion_acumulada = 1;
					$i = 0;
					$fila = array();
					foreach($calcular_deudas_proveedores as $dp){
						if( is_numeric($ajuste_inflacion[$fechas[$i]]) ){
							$inflacion_acumulada = $inflacion_acumulada * $ajuste_inflacion[$fechas[$i]];
							$dp = $dp * $inflacion_acumulada;
						}
						$i++;
						$fila[] = $dp;
					}
				?>
				<td><strong>$ <?php echo numero_legible_redondeado( promedio_array($fila,2) ); ?></strong></td>
				<?php
					foreach($fila as $v){
						echo '<td>$ ' . numero_legible_redondeado( $v ) . '</td>';
					}
				?>
			</tr>
			<tr>
				<td><a href="./cobranzas" class="titulo">Cobranza:</a></td>
				<?php
					$inflacion_acumulada = 1;
					$i = 0;
					$fila = array();
					foreach($calcular_cobranzas_total as $ct){
						if( is_numeric($ajuste_inflacion[$fechas[$i]]) ){
							$inflacion_acumulada = $inflacion_acumulada * $ajuste_inflacion[$fechas[$i]];
							$ct = $ct * $inflacion_acumulada;
						}
						$i++;
						$fila[] = $ct;
					}
					$promedio_cobranzas = promedio_array($fila,2);
				?>
				<td><strong>$ <?php echo numero_legible_redondeado( $promedio_cobranzas ); ?></strong></td>
				<?php
					foreach($fila as $v){
						echo '<td>$ ' . numero_legible_redondeado( $v ) . '</td>';
					}
				?>
			</tr>
			<tr>
				<td>R bruta s/cob:</td>
				<?php
					$inflacion_acumulada = 1;
					$i = 0;
					$fila = array();
					foreach($calcular_r_total_cobranzas as $rtc){
						if( is_numeric($ajuste_inflacion[$fechas[$i]]) ){
							$inflacion_acumulada = $inflacion_acumulada * $ajuste_inflacion[$fechas[$i]];
							$rtc = $rtc * $inflacion_acumulada;
						}
						$i++;
						$fila[] = $rtc;
					}
					$promedio_r_bruta = promedio_array($fila,2);
				?>
				<td><strong>$ <?php echo numero_legible_redondeado( $promedio_r_bruta ); ?></strong></td>
				<?php
					foreach($fila as $v){
						echo '<td>$ ' . numero_legible_redondeado( $v ) . '</td>';
					}
				?>
			</tr>
			<tr>
				<td>R bruta %:</td>
				<?php
					$fila = array();
					foreach($calcular_r_porcentaje_cobranzas as $rpc){
						$fila[] = $rpc;
					}
				?>
				<td><strong>% <?php if($promedio_cobranzas!=0){ echo numero_legible( (100*$promedio_r_bruta/$promedio_cobranzas) );}else{ echo '0';} ?></strong></td>
				<?php
					foreach($fila as $v){
						echo '<td>% ' . numero_legible( $v ) . '</td>';
					}
				?>
			</tr>
			<tr>
				<td><a href="./caja" class="titulo">Caja:</a></td>
				<?php
					$inflacion_acumulada = 1;
					$i = 0;
					$fila = array();
					foreach($caja as $c){
						if( is_numeric($ajuste_inflacion[$fechas[$i]]) ){
							$inflacion_acumulada = $inflacion_acumulada * $ajuste_inflacion[$fechas[$i]];
							$c = $c * $inflacion_acumulada;
						}
						$i++;
						$fila[] = $c;
					}
					$promedio_caja = promedio_array($fila,2);
				?>
				<td><strong>$ <?php echo numero_legible_redondeado( $promedio_caja ); ?></strong></td>
				<?php
					foreach($fila as $v){
						echo '<td>$ ' . numero_legible_redondeado( $v ) . '</td>';
					}
				?>
			</tr>
			<tr>
				<td><a href="./disponibilidad" class="titulo">Disponibilidad:</a></td>
				<?php
					$inflacion_acumulada = 1;
					$i = 0;
					$fila = array();
					foreach($disponibilidad as $d){
						if( is_numeric($ajuste_inflacion[$fechas[$i]]) ){
							$inflacion_acumulada = $inflacion_acumulada * $ajuste_inflacion[$fechas[$i]];
							$d = $d * $inflacion_acumulada;
						}
						$i++;
						$fila[] = $d;
					}
					$promedio_disponibilidad = promedio_array($fila,2);
				?>
				<td><strong>$ <?php echo numero_legible_redondeado( $promedio_disponibilidad ); ?></strong></td>
				<?php
					foreach($fila as $v){
						echo '<td>$ ' . numero_legible_redondeado( $v ) . '</td>';
					}
				?>
		</table>
	</div>
	<p>(No se tienen en cuenta los ultimos dos meses para el cálculo del promedio)<br />
	La información se actualiza a las 6AM.</p>
	<?php
		$ano_actual = intval(date('Y'));
		echo "<!--" . ajuste_inflacion_por_ano($ano_actual-1) . "-->";
	?>
	<p class="textright">Inflación acumulada <?php echo $ano_actual;?>: <strong><?php echo round( (ajuste_inflacion_por_ano($ano_actual)-1)*100, 2);?></strong> %&nbsp;</p>
	<p class="textright">Inflación <?php echo $ano_actual - 1;?>: <strong><?php echo round( (ajuste_inflacion_por_ano($ano_actual-1)-1)*100, 2);?></strong> %&nbsp;
	- <?php echo $ano_actual - 2;?>: <strong><?php echo round( (ajuste_inflacion_por_ano($ano_actual-2)-1)*100, 2);?></strong> %&nbsp;
	- <?php echo $ano_actual - 3;?>: <strong><?php echo round( (ajuste_inflacion_por_ano($ano_actual-3)-1)*100, 2);?></strong> %&nbsp;
	- <?php echo $ano_actual - 4;?>: <strong><?php echo round( (ajuste_inflacion_por_ano($ano_actual-4)-1)*100, 2);?></strong> %&nbsp;
	- <?php echo $ano_actual - 5;?>: <strong><?php echo round( (ajuste_inflacion_por_ano($ano_actual-5)-1)*100, 2);?></strong> %&nbsp;
	</p>
</div>
