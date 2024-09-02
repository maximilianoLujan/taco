<?php
ini_set('display_errors', 1);
require('ini.php');
set_time_limit(2000);



$vendedores = get_vendedores();
$super_rubros = get_super_rubros();

$vendedores_zonas = vendedores_zonas();

$codigo_plan = isset($_GET['codigo-plan']) ? $_GET['codigo-plan'] : '';

$clientes_cupo = 0;
$clientes_suscripciones = 0;

if( @$_GET['vendedor'] && @$vendedores_zonas[@$_GET['vendedor']] && $_GET['vendedor'] != 'TODOS'){
	$zonas = $vendedores_zonas[$_GET['vendedor']];
}else{
	$zonas = get_zonas();
}

?><!DOCTYPE html>
<html lang="es-ES">
<head>
<meta charset="UTF-8" />
<title>Distrisuper</title>
<meta name="viewport" content="initial-scale=1.0, width=device-width" />
<link rel="stylesheet" href="../css/bootstrap.min.css" type="text/css" />
<link rel="stylesheet" href="../css/bootstrap-theme.min.css" type="text/css" />
<link rel="stylesheet" href="/general2.css?" type="text/css" />
<link rel="stylesheet" href="./est.css" type="text/css" />
<script src="../js/jquery-3.2.1.min.js"></script>
<script src="../js/bootstrap.min.js"></script>
<script>
	<?php
		$js = json_encode($vendedores_zonas);
		echo "var vendedoresZonas = " . $js . ";";
	?>
	// console.log( vendedoresZonas );
</script>
<script src="./script-2.js"></script>
<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-7NMF37E0CD"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-7NMF37E0CD');
</script>
<link rel="shortcut icon" href="/favicon.ico" />
</head>
<body>
<div id="indicador-de-carga"></div>
<div id="wrapper">
	<div id="contenedor">
		<div style="float:right;display:inline-block;">
			<?php include('notas.php');?>
		</div>
		<h3>EVOLUCIÓN x VENTAS</h3>
		<a href="/objetivos-ventas" target="_blank" class="btn btn-primary">Objetivos</a>
		<br />

        <form id="formulario-opciones">
			<select name="vendedor" id="vendedor">
				<option value="TODOS">Selecciona el Vendedor</option>
				<?php
					foreach($vendedores as $k=>$v){
						if( @$_GET['vendedor'] == $k ){
							echo '<option value="' . $k . '" selected>' . $v . '</option>' . "\n";
						}else{
							echo '<option value="' . $k . '">' . $v . '</option>' . "\n";
						}
					}
				?>
				<option value="TODOS" <?php echo (@$_GET['vendedor'] == 'TODOS') ? 'selected' : '';?>>TODOS</option>
			</select>

			<select name="zona" id="zona">
				<option>Selecciona la zona</option>
				<?php
					foreach($zonas as $k=>$v){
						if( @$_GET['zona'] == $k ){
							echo '<option value="' . $k . '" selected>' . $v . '</option>' . "\n";
						}else{
							echo '<option value="' . $k . '">' . $v . '</option>' . "\n";
						}
					}
				?>
			</select>

			<select name="codigo-plan" id="codigo-plan">
				<option value="0">Plan</option>
				<option value="1" <?php echo ($codigo_plan == 1) ? 'selected' : '';?>>CUPO</option>
				<option value="2" <?php echo ($codigo_plan == 2) ? 'selected' : '';?>>SUSCRIPCION</option>
				<option value="0" <?php echo ($codigo_plan == 0) ? 'selected' : '';?>>Todos</option>
			</select>

			<?php
				$total_clientes_criticos = 0;
				$total_clientes_activos = 0;
				$total_clientes_inactivos = 0;
				$total_sr_activos_parche = 0;
                $clientes = [];
				if (isset($_GET['vendedor'])) {
					$vendedor = addslashes($_GET['vendedor']);
					$zona = isset($_GET['zona']) ? addslashes($_GET['zona']) : '';
					$clientes = get_clientes_x_vendedor_zona($vendedor, $zona, $codigo_plan);
					foreach ($clientes as $cliente) {
						$k = strval($cliente['id_cliente']);
						$total_clientes_activos += $cliente['activo_taco'];
						if ($cliente['activo_taco'] == 0) {
							$total_clientes_inactivos++;
						}
						$total_clientes_criticos += $cliente['critico_taco'];
				
						// parche para ver el numero de sr activos
						$parche_ventas_x_cliente_x_super_rubros_actual = ventas_x_cliente_x_super_rubros($k, 0);
						foreach ($parche_ventas_x_cliente_x_super_rubros_actual as $id_super_rubro => $v) {
							if (isset($v['unidades']) && $v['unidades'] >= $super_rubros[$id_super_rubro]['criterio_activo'] && $super_rubros[$id_super_rubro]['criterio_activo'] > 0) {
								$total_sr_activos_parche++;
							}
						}
						// fin del parche para sr activos
					}
				}
                
			?>
			<button type="submit" name="ver-rojos" id="ver-rojos" class="btn btn-danger" value="Críticos">
				Críticos<br />
				<small><?php echo $total_clientes_criticos;?></small>
			</button>
			<button type="submit" name="ver-evolucion-clientes" id="ver-evolucion-clientes" class="btn btn-success" value="Activos">
				Activos<br />
				<small><?php echo $total_clientes_activos;?></small>
			</button>
			<button type="submit" name="ver-evolucion-clientes-inactivos" id="ver-evolucion-clientes-inactivos" class="btn btn-success" value="Inactivos">
				Inactivos<br />
				<small><?php echo $total_clientes_inactivos;?></small>
			</button>
        
			<button type="submit" name="ver-evolucion-clientes-todos" id="ver-evolucion-clientes-todos" class="btn btn-primary" value="Todos">
				Todos<br />
                <small><?php echo count($clientes);?></small>
			</button>

			<!--<input type="submit" name="ver-ventas-por-marca" id="ver-ventas-por-marca" class="btn btn-primary" value="Por Marca" />-->
			<button type="submit" name="ver-ventas-por-super-rubro" id="ver-ventas-por-super-rubro" class="btn btn-primary" value="Por Rubro">
				Por Rubro<br />
				<small><?php echo $total_sr_activos_parche;?></small>
			</button>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<input type="button" name="orden-por" id="orden-por" class="btn btn-info" value="Orden por U" />
			<a href="/precios-congelados-7" class="btn btn-primary">Precios congelados</a>
		</form>
		<?php
			$html_general = '';
			$html_totales_vendedor = '';
			if( @$_GET['vendedor'] ){
				$clientes_1m = floatval(0);
				$clientes_600k = 0;
				$clientes_300k = 0;
				$clientes_200k = 0;
				$clientes_100k = 0;
				$clientes_50k = 0;
				$clientes_20k = 0;
				foreach( $clientes as $cliente ){
					$k = strval($cliente['id_cliente']);

					$promedio_por_cliente_3[$k] = promedio_ventas( $k, 3 );
					$promedio_por_cliente_6[$k] = promedio_ventas( $k, 6 );
					$promedio_por_cliente_12[$k] = promedio_ventas( $k, 12 );
					$promedio_por_cliente_24[$k] = promedio_ventas( $k, 24 );

					$ventas_mes_corriente = ventas_mes_corriente( $k );
					$ventas_mes_corriente_x_cliente[$k] = $ventas_mes_corriente;
					$ventas_mes_anterior_x_cliente[$k] = ventas_mes_anterior( $k );
					$color_mes_corriente[$k] = color_promedio($ventas_mes_corriente['monto'], $promedio_por_cliente_12[$k]['monto'], 'monto');

					$clientes_por_monto[$k] = $promedio_por_cliente_12[$k]['monto'];

					if($ventas_mes_corriente['monto'] >= 1000000 ){
						$clientes_1m += calculo_clientes_millon($ventas_mes_corriente['monto']);
					}
					if($ventas_mes_corriente['monto'] >= 600000 && $ventas_mes_corriente['monto'] < 1000000 ){
						$clientes_600k++;
					}
					if($ventas_mes_corriente['monto'] >= 300000 && $ventas_mes_corriente['monto'] < 600000 ){
						$clientes_300k++;
					}
					if($ventas_mes_corriente['monto'] >= 200000 && $ventas_mes_corriente['monto'] < 300000 ){
						$clientes_200k++;
					}
					if($ventas_mes_corriente['monto'] >= 100000 && $ventas_mes_corriente['monto'] < 200000 ){
						$clientes_100k++;
					}
					if($ventas_mes_corriente['monto'] >= 50000 && $ventas_mes_corriente['monto'] < 100000 ){
						$clientes_50k++;
					}
					if($ventas_mes_corriente['monto'] >= 20000 && $ventas_mes_corriente['monto'] < 50000 ){
						$clientes_20k++;
					}

					if($cliente['codigo_plan'] == 1 ){
						$clientes_cupo++;
					}
					if($cliente['codigo_plan'] == 2 ){
						$clientes_suscripciones++;
					}
				}

				$html_top_info = "<div class='row' style='margin: 12px!important;'>\n";
				$html_top_info .= "<div class='col-sm-4 col-lg-2 col-xl-1'>\n";
				$html_top_info .= "<h4>" . $clientes_20k . " clientes > $20k</h4>\n";
				$html_top_info .= "<h4>" . $clientes_50k . " clientes > $50k</h4>\n";
				$html_top_info .= "<h4>" . $clientes_100k . " clientes > $100k</h4>\n";
				$html_top_info .= "<h4>" . $clientes_200k . " clientes > $200k</h4>\n";
				$html_top_info .= "</div>\n";

				$html_top_info .= "<div class='col-sm-4 col-lg-2 col-xl-1'>\n";
				$html_top_info .= "<h4>" . $clientes_300k . " clientes > $300k</h4>\n";
				$html_top_info .= "<h4>" . $clientes_600k . " clientes > $600k</h4>\n";
				$html_top_info .= "<h4>" . $clientes_1m . " clientes > $1M</h4>\n";
				$html_top_info .= "</div>\n";

				$html_top_info .= "<div class='col-sm-4 col-lg-2 col-xl-1'>\n";
				$html_top_info .= "<h4>" . $clientes_cupo . " Cupos</h4>\n";
				$html_top_info .= "<h4>" . $clientes_suscripciones . " Suscripciones</h4>\n";
				$html_top_info .= "</div>\n";

				$html_top_info .= "</div>\n";

				arsort ( $clientes_por_monto );
				$clientes_ordenados = $clientes_por_monto;

				$ventas_x_clientes = ventas_x_clientes( $clientes_ordenados, 0 );

				if( @$_GET['ver-ventas-por-marca'] ){
					#include('ver-ventas-por-marca.php');
				}elseif( @$_GET['ver-ventas-por-super-rubro'] ){
					include('ver-ventas-por-super-rubro.php');
				}elseif( @$_GET['ver-evolucion-clientes'] ){
					include('ver-evolucion-clientes.php');
				}elseif( @$_GET['ver-evolucion-clientes-inactivos'] ){
					include('ver-evolucion-clientes-inactivos.php');
				}elseif( @$_GET['ver-evolucion-clientes-todos'] ){
					include('ver-evolucion-clientes-todos.php');
				}elseif( @$_GET['ver-rojos'] ){
					include('ver-rojos.php');
				}
			}
			echo "<p>" . $total_clientes_criticos . " clientes criticos </p>\n";

			echo $html_top_info;

			echo $html_totales_vendedor;

			echo $html_general;
		?>
		<br />
		<br />
		<div class="alert alert-info">
			<ul>
				<li>Cuando el valor es un 10% superior al promedio el color es verde fuerte</li>
				<li>Cuando el valor está entre el +10% y -10% del promedio el color es verde suave</li>
				<li>Cuando el valor está entre el -10% y -30% del promedio el color es amarillo</li>
				<li>Cuando el valor está entre el -30% y -80% del promedio el color es anaranjado</li>
				<li>Cuando el valor es mas del 80% inferior al promedio o el valor es cero el color es rojo</li>
				<li>Los clientes críticos (rojo y anaranjado) se determinan por las ventas en pesos del mes corriente. Para el mes corriente se proyectan las ventas hasta el final tomando en cuenta el periodo de tiempo transcurrido</li>
			</ul>
		</div>
		<div class="alert alert-info">
			<p>Los colores representan $</p>
		</div>
		<div class="alert alert-info">
			<p>Los clientes activos son los que tienen al menos un SR activo</p>
		</div>
		<div class="alert alert-info">
			<p>Los clientes criticos son los que tienen un 70% menos de ventas en $ en el mes actual que el promedio de los ultimos 3 meses</p>
		</div>
		<div class="alert alert-info">
			<p>La información se saca de las facturas de ventas y remitos en caso de GM. Las tablas de Flexxus que se usan son CABEZACOMPROBANTES y CUERPOCOMPROBANTES, en Taco Ventas</p>
		</div>
	</div>
</div>
<br />
<br />
<br />
</body>
</html>