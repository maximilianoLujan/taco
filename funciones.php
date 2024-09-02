<?php
function validador($expresion,$string){
	$expresion = '/' . $expresion . '/';
	if( preg_match( "$expresion" , $string ) ){
		return true;
	}else{
		return false;
	}
}
function hay_comillas($string){
	return validador( '(\')|(\")' , $string );
}

function porcentaje_periodo($tipo){
	if( $tipo=='Años' ){
		$porcentaje_periodo = date("z") / 365;
	}elseif( $tipo=='Trimestre' ){
		$porcentaje_periodo = dia_trimestre_actual() / total_dias_trimestre_actual();
	}else{
		$porcentaje_periodo = date("d") / date("d",mktime(0,0,0,date('n')+1,0,date('Y')));
	}
	return $porcentaje_periodo;
}

function periodo_actual($tipo){
	if( $tipo=='Años' ){
		return date('Y');
	}elseif( $tipo=='Trimestre' ){
		return fecha_trimestre();
	}else{
		return date('Y-n');
	}
}

function periodo_anterior( $periodo_actual, $tipo ){
	if( $tipo=='Años' ){
		return ano_anterior($periodo_actual);
	}elseif( $tipo=='Trimestre' ){
		return trimestre_anterior($periodo_actual);
	}else{
		return mes_anterior($periodo_actual);
	}
}

function fecha_trimestre(){
	return date('Y') . '-' . trimestre_actual();
}
function trimestre_actual(){
	return ceil( date('n')/3 );
}
function trimestre_mes($mes){
	$mes = intval($mes);
	if( $mes >= 1 && $mes <= 12 ){
		return ceil( $mes/3 );
	}
	return false;
}
function meses_trimestre($trimestre){
	$array_trimestre = explode('-',$trimestre);
	$ano = $array_trimestre[0];
	$q = $array_trimestre[1];
	$mese_3 = $q * 3;
	$mese_2 = $mese_3 - 1;
	$mese_1 = $mese_2 - 1;
	return array($mese_1,$mese_2,$mese_3);
}
function dia_trimestre_actual(){
	$dias_trimestre = date('j');
	$q = $q_actual = trimestre_actual();
	$mes = date('n');
	while( $mes > 1 && $q_actual == $q ){
		$mes--;
		$q = trimestre_mes($mes);
		$dias_trimestre = $dias_trimestre + cal_days_in_month(CAL_GREGORIAN, $mes, date('Y'));
	}
	return $dias_trimestre;
}
function total_dias_trimestre_actual(){
	$dias_trimestre = 0;
	$q = trimestre_actual();
	$ultimo_mes = 3 * $q;
	$primer_mes = $ultimo_mes - 2;
	$mes_a_calcular = $ultimo_mes;
	while( $mes_a_calcular >= $primer_mes ){
		$dias_trimestre = $dias_trimestre + cal_days_in_month(CAL_GREGORIAN, $mes_a_calcular, date('Y'));
		$mes_a_calcular--;
	}
	return $dias_trimestre;
}
function trimestre_anterior($trimestre){
	$array_trimestre = explode('-',$trimestre);
	if( count($array_trimestre)==2 ){
		$y = intval($array_trimestre[0]);
		$q = intval($array_trimestre[1]);
		$q--;
		if($q<=0){
			$q=4;
			$y--;
		}
		return $y . '-' . $q;
	}else{
		return false;
	}
}
function trimestre_siguiente($trimestre){
	$array_trimestre = explode('-',$trimestre);
	if( count($array_trimestre)==2 ){
		$y = intval($array_trimestre[0]);
		$q = intval($array_trimestre[1]);
		$q++;
		if($q>4){
			$q=1;
			$y++;
		}
		return $y . '-' . $q;
	}else{
		return false;
	}
}
function resta_trimestres($trimestre,$trimestres_a_restar){
	while($trimestres_a_restar > 0){
		$trimestre = trimestre_anterior($trimestre);
		$trimestres_a_restar--;
	}
	return $trimestre;
}

function mes_anterior($fecha){
	$time=strtotime($fecha);
	$nuevafecha=strtotime('-1 month',$time);
	return date('Y-n',$nuevafecha);
}
function ano_anterior($fecha){
	$int_fecha=intval($fecha);
	$int_fecha--;
	return $int_fecha;
}
function ano_siguiente($fecha){
	$int_fecha=intval($fecha);
	$int_fecha++;
	return $int_fecha;
}
function resta_meses($fecha,$meses_a_restar){
	$meses_a_restar=intval($meses_a_restar);
	$time=strtotime($fecha);
	$nuevafecha=strtotime('-' . $meses_a_restar . ' month',$time);
	return date('Y-n',$nuevafecha);
}
function resta_anos($fecha,$anos_a_restar){
	$int_anos_a_restar=intval($anos_a_restar);
	$int_fecha=intval($fecha);
	return $int_fecha - $int_anos_a_restar;
}
function reemplazar_punto($texto){
	return str_replace('.','&#46;',$texto);
}
function devolver_punto($texto){
	return str_replace('&#46;','.',$texto);
}
function es_par($numero){
	if($numero%2==0){
		return true;
	}else{
		return false;
	}
}
function fechas_a_mostrar($unidad_tiempo,$comienzo_tiempo,$cantidad_tiempo){
	$comienzo_tiempo = intval($comienzo_tiempo);
	$cantidad_tiempo = intval($cantidad_tiempo);
	if($unidad_tiempo=='Años'){
		$fecha_actual=date('Y');
		$fecha_comienzo=resta_anos($fecha_actual,$comienzo_tiempo);
		$anos_restantes=$cantidad_tiempo;
		$ano=$fecha_comienzo;
		while($anos_restantes>0){
			$anos[]=$ano;
			$ano=ano_anterior($ano);
			$anos_restantes--;
		}
		return $anos;
	}elseif($unidad_tiempo=='Trimestre'){
		$fecha_trimestre = fecha_trimestre();
		$fecha_comienzo=resta_trimestres($fecha_trimestre,$comienzo_tiempo);
		$trimestres_restantes=$cantidad_tiempo;
		$trimestre=$fecha_comienzo;
		while($trimestres_restantes>0){
			$trimestres[]=$trimestre;
			$trimestre=trimestre_anterior($trimestre);
			$trimestres_restantes--;
		}
		return $trimestres;
	}else{
		$fecha_actual=date('Y-n');
		$fecha_comienzo=resta_meses($fecha_actual,$comienzo_tiempo);
		$meses_restantes=$cantidad_tiempo;
		$mes=$fecha_comienzo;
		while($meses_restantes>0){
			$meses[]=$mes;
			$mes=mes_anterior($mes);
			$meses_restantes--;
		}
		return $meses;
	}
}
function fecha_legible($fecha){
	if(strlen($fecha)>4){
		if($f = explode('-',$fecha)){
			$fecha = $f[1] . '-' . $f[0];
		}
	}
	return $fecha;
}
function fecha_img($fecha){
	if(strlen($fecha)>4){
		if($f = explode('-',$fecha)){
			$fecha = $f[1] . '/' . ($f[0]-2000);//para que la fecha no muestre el 20 al inicio del año
		}
	}
	return $fecha;
}
function promedio_array($array, $ignorar){
	$ignorar = intval($ignorar);
	if( count($array) - $ignorar > 0 ){
		$nuevo_array = array_slice($array, $ignorar);
		return array_sum($nuevo_array)/count($nuevo_array);
	}
	return false;
}

function colores_por_promedio($valores,$fechas,$periodo,$ajuste_ultimo_periodo){
	$color_bueno = array("R"=>110,"G"=>255,"B"=>110);
	$color_regular = array("R"=>170,"G"=>255,"B"=>170);
	$color_malo = array("R"=>255,"G"=>255,"B"=>120);
	$color_mm = array("R"=>255,"G"=>165,"B"=>70);
	$color_mmm = array("R"=>255,"G"=>90,"B"=>90);

	$promedio = round( array_sum($valores) / numero_para_promedio($fechas,$periodo) );
	if($ajuste_ultimo_periodo==1){
		$primero = array_shift($valores);
		$primero = round( $primero / porcentaje_periodo($periodo) ); // para equiparar el PRIMER periodo incompleto con los otros completos
		$null = array_unshift($valores,$primero);
	}elseif($ajuste_ultimo_periodo==2){
		$ultimo = array_pop($valores);
		$ultimo = round( $ultimo / porcentaje_periodo($periodo) ); // para equiparar el ULTIMO periodo incompleto con los otros completos
		$valores[] = $ultimo;
	}
	foreach($valores as $v){
		if( $v >= ($promedio * 1.1) ){
			$colores[] = $color_bueno;
		}elseif( $v < ($promedio * 1.1) && $v >= ($promedio * 0.9) ){
			$colores[] = $color_regular;
		}elseif( $v < ($promedio * 0.9) && $v >= ($promedio * 0.7) ){
			$colores[] = $color_malo;
		}elseif( $v < ($promedio * 0.7) && $v >= ($promedio * 0.2) ){
			$colores[] = $color_mm;
		}elseif( $v < ($promedio * 0.2) || $v <= 0 ){
			$colores[] = $color_mmm;
		}
	}
	return $colores;
}
function colores_inversos_por_promedio($valores,$fechas,$periodo,$ajuste_ultimo_periodo){
	$color_bueno = array("R"=>110,"G"=>255,"B"=>110);
	$color_regular = array("R"=>170,"G"=>255,"B"=>170);
	$color_malo = array("R"=>255,"G"=>255,"B"=>120);
	$color_mm = array("R"=>255,"G"=>165,"B"=>70);
	$color_mmm = array("R"=>255,"G"=>90,"B"=>90);

	$promedio = round( array_sum($valores) / numero_para_promedio($fechas,$periodo) );
	if($ajuste_ultimo_periodo==1){
		$primero = array_shift($valores);
		$primero = round( $primero / porcentaje_periodo($periodo) ); // para equiparar el PRIMER periodo incompleto con los otros completos
		$null = array_unshift($valores,$primero);
	}elseif($ajuste_ultimo_periodo==2){
		$ultimo = array_pop($valores);
		$ultimo = round( $ultimo / porcentaje_periodo($periodo) ); // para equiparar el ULTIMO periodo incompleto con los otros completos
		$valores[] = $ultimo;
	}
	foreach($valores as $v){
		if( $v >= ($promedio * 1.1) ){
			$colores[] = $color_malo;
		}elseif( $v < ($promedio * 1.1) && $v >= ($promedio * 0.9) ){
			$colores[] = $color_regular;
		}elseif( $v < ($promedio * 0.9) && $v >= ($promedio * 0.7) ){
			$colores[] = $color_bueno;
		}elseif( $v < ($promedio * 0.7) && $v >= ($promedio * 0.2) ){
			$colores[] = $color_bueno;
		}elseif( $v < ($promedio * 0.2) || $v <= 0 ){
			$colores[] = $color_bueno;
		}
	}
	return $colores;
}

function precio( $precio ){
	return number_format( $precio , 2 , ',', '');
}
function numero_legible( $valor ){
	$valor = number_format( $valor , 2 , ',', '.');
	return $valor;
}
function numero_legible_redondeado( $valor ){
	$valor = number_format( $valor , 0 , ',', '.');
	return $valor;
}
function numero_legible_precio( $valor ){
	$valor = number_format( $valor , 2 , ',', '.');
	return $valor;
}
function precio_millones( $valor ){
	$valor = number_format( $valor , 0 , ',', '.');
	return $valor;
}

function numero_para_promedio($array_fechas, $tipo){
	$numero_columnas = 0.0;
	$numero_columnas = count($array_fechas);
	$ultima_importacion = ultima_importacion();
	if($ultima_importacion){
		$array_ultima_importacion = explode("-",$ultima_importacion);
		$ano = $array_ultima_importacion[0];
		$mes = $array_ultima_importacion[1];
		$dia = $array_ultima_importacion[2];
		if( $tipo == 'Años' ){
			if( $array_fechas[0] == date('Y') ){
				$dia_ano = date("z");
				$total_dias_ano = date("z",mktime(0,0,0,12,31,$ano));
				$numero_columnas = ($numero_columnas-1)+($dia_ano/$total_dias_ano);
			}
		}elseif( $tipo == 'Trimestre' ){
			if( $array_fechas[0] == fecha_trimestre() ){
				$dia_trimestre = dia_trimestre_actual();
				$total_dias_trimestre = total_dias_trimestre_actual();
				$numero_columnas = ($numero_columnas-1)+($dia_trimestre/$total_dias_trimestre);
			}
		}else{
			if( $array_fechas[0] == date('Y-n') ){
				$total_dias = date("d",mktime(0,0,0,$mes+1,0,$ano));
				$numero_columnas = ($numero_columnas-1)+($dia/$total_dias);
			}
		}
	}
	return $numero_columnas;
}

function ajuste_inflacion($fechas_a_mostrar,$tipo){
	if( $tipo == 'Años' ){
		foreach($fechas_a_mostrar as $f){
			$inflacion[$f] = ajuste_inflacion_por_ano( ano_siguiente($f) );
			$GLOBALS['msg'] .= "f $f: " . $inflacion[$f] . "<br />";
		}
	}elseif( $tipo == 'Trimestre' ){
		foreach($fechas_a_mostrar as $f){
			$inflacion[$f] = ajuste_inflacion_por_trimestre( trimestre_siguiente($f) );
			$GLOBALS['msg'] .= "f $f: " . $inflacion[$f] . "<br />";
		}
	}else{ //meses
		$inflacion_por_meses = ajuste_inflacion_por_meses();
		foreach($fechas_a_mostrar as $f){
			if( $inflacion_por_meses[$f] ){
				$inflacion[$f] = $inflacion_por_meses[ $f ];
				$GLOBALS['msg'] .= "f $f: " . $inflacion[$f] . "<br />";
			}else{
				$inflacion[$f] = 1;
				$GLOBALS['msg'] .= "f $f: " . $inflacion[$f] . "<br />";
			}
		}
	}
	return $inflacion;
}

function ajuste_inflacion_periodo($periodo,$tipo){
	if( $tipo == 'Años' ){ //Años
		return ajuste_inflacion_por_ano($periodo);
	}elseif( $tipo == 'Trimestre' ){
		return ajuste_inflacion_por_trimestre($periodo);
	}else{ //meses
		return ajuste_inflacion_por_mes($periodo);
	}
}

function disponibilidad($fecha){
	if( $tipo=='Años' ){
		$ano = $fecha;
		$mes = 12;
	}elseif( $tipo=='Trimestre' ){
		$array_fecha = explode('-',$fecha);
		$ano = $array_fecha[0];
		$mes = $array_fecha[1] * 3;
	}else{
		$array_fecha = explode('-',$fecha);
		$ano = $array_fecha[0];
		$mes = $array_fecha[1];
	}
	$fecha = strval($ano.'-'.$mes);
	$stock = stock($fecha);
	$caja = caja($fecha);
	$deudas_clientes = deudas_clientes($fecha);
	$deudas_proveedores = deudas_proveedores($fecha);
	$disponibilidad = $stock + $caja + $deudas_clientes - $deudas_proveedores;
	return $disponibilidad;
}













?>
