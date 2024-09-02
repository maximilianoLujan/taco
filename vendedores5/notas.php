<?php
include_once('ini.php');
ini_set('display_errors', 0);

function guardar_nota( $path, $nota ){
	global $db_mysql;
	$path = trim( $path );
	if( strlen($path) > 1 ){
		$path = str_replace( "'", "\'", $path );
		$nota = str_replace( "'", "\'", $nota );
		$sql="INSERT INTO `notas` SET `path` = '$path', `nota` = '$nota';";
		if( mysqli_query( $db_mysql, $sql ) ){
			return true;
		}
	}
	return false;
}

function get_pass_nota(){
	global $db_mysql;
	$sql="SELECT `v` FROM `variables` WHERE `k` = 'pass_nota' LIMIT 1;";
	$result=mysqli_query($db_mysql,$sql);
	if( $dat = @mysqli_fetch_array($result) ){
		@mysqli_free_result($result);
		return $dat['v'];
	}
	return '';
}

function get_nota( $path ){
	global $db_mysql;
	$path = trim( $path );
	if( strlen($path) > 1 ){
		$path = str_replace( "'", "\'", $path );
		$sql="SELECT `nota` FROM `notas` WHERE `path` = '$path' ORDER BY `id` DESC LIMIT 1;";
		$result=mysqli_query($db_mysql,$sql);
		if( $dat = @mysqli_fetch_array($result) ){
			@mysqli_free_result($result);
			$nota = str_replace( "\'", "'", $dat['nota'] );
			return $nota;
		}
		@mysqli_free_result($result);
		return '';
	}
	return '';
}

if( strlen( @$_POST['path-nota'] ) > 1 ){
	$path = trim( $_POST['path-nota'] );
	$nota = $_POST['texto-nota'];
	if( guardar_nota( $path, $nota ) ){
		$nueva_nota = get_nota( $path );
		$data = (object) array('mensaje' => 'ok', 'textoNota' => $nueva_nota);
//		echo "<mensaje>ok</mensaje>\n";
//		echo "<texto-nota>$nueva_nota</texto-nota>\n";
//		echo "{ 'nuevaNota' : " . $nuevaNota . " }";
	}else{
		$data = (object) array('mensaje' => 'Error al guardar la nota', 'textoNota' => $nota);
//		echo "<mensaje>Error al guardar la nota</mensaje>\n";
//		echo "<texto-nota>$nota</texto-nota>\n";
//		echo "{ 'nuevaNota' : " . $nota . " }";
	}
	header('Content-Type: application/json');
	echo json_encode($data);
}else{
	$pass_nota = get_pass_nota();
?>
	<style>
		.boton-general-notas{
			margin: 0;
			outline: 0;
			display: inline-block;
			padding: 6px 12px;
			font-size: 14px;
			font-weight: 400;
			line-height: 1.42857143;
			text-align: center;
			white-space: nowrap;
			vertical-align: middle;
			font: inherit;
			border: 1px solid transparent;
			border-radius: 4px;
			background-image: linear-gradient(to bottom,#337ab7 0,#265a88 100%);
			filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#ff337ab7', endColorstr='#ff265a88', GradientType=0);
			filter: progid:DXImageTransform.Microsoft.gradient(enabled=false);
			background-repeat: repeat-x;
			border-color: #245580;
			color: #fff;
			background-color: #337ab7;
			box-shadow: inset 0 3px 5px rgba(0,0,0,.125);
		}
		.boton-general-notas:hover{
			background-position: 0 -15px;
			background-color: #204d74;
			border-color: #122b40;
			background-image: none;
		}
		.boton-general-notas:active{
			background-color: #265a88;
			border-color: #245580;
		}
		.contenedor-general-notas{
			position:relative;
			display:inline-block;
		}
		.contenedor-notas{
			position:absolute;
			background:#fff;
			-webkit-box-shadow: 0px 0px 7px 7px rgba(0,0,0,0.3);
			-moz-box-shadow: 0px 0px 7px 7px rgba(0,0,0,0.3);
			box-shadow: 0px 0px 7px 7px rgba(0,0,0,0.3);
			position:absolute;
			display:none;
			right:10px;
			padding:9px;
			left:auto;
			background:#fff;
			color:#000;
		}
		.contenedor-notas textarea{
			font-weight:bold;
			font-family:"Comic Sans MS", "Comic Sans", cursive, sans-serif;
			font-style: italic;
			font-size:1.5rem;
			margin:6px;
			padding:6px;
			height: 70vh;
			width: 50vw;
			background:#fff;
			color:#000;
		}
		@media screen and (max-width: 640px){
			.contenedor-notas textarea{
				width: 95vw;
			}
		}
	</style>
	<script>
		function guardarNota(){
			pathNota = q('#path-nota').val();
			textoNota = q('#texto-nota').val();
			if( pathNota.length > 1 ){
				var request = q.ajax({
					url: 'notas.php',
					type: "POST",
					data: {'path-nota' : pathNota, 'texto-nota' : textoNota },
					dataType: "json",
					cache: false
				});
				request.done(function(response){
					var nuevaNota = response.textoNota;
					//console.log( nuevaNota );
					q('#texto-nota').val( nuevaNota );
					q('#texto-nota').css({'background': '#fff'});
				});
				request.fail(function(response){
					alert('Error: No se pudo establecer la comunicación con el servidor');
				});
			}
		}
		q(document).ready(function(){
			q('#guardar-nota').click( function(){
				var passwordIngresado = prompt('Ingrese la contraseña para guardar los datos');
				if( passwordIngresado == '<?php echo $pass_nota;?>' && passwordIngresado.length > 1 ){
					guardarNota();
				}else{
					alert('Contraseña erronea');
				}
			});
			q('#ver-notas').click( function(){
				q('.contenedor-notas').toggle();
			});
			q('body').click( function(){
				if( !q('.contenedor-general-notas').is(':hover') ){
					q('.contenedor-notas').hide();
				}
			});
			q('#texto-nota').keydown( function(){
				q('#texto-nota').css({'background': '#fee'});
			});
		});
	</script>
	<div class="contenedor-general-notas">
		<form method="post">
			<input type="button" id="ver-notas" class="boton-general-notas" value="Ver Notas" />
			<div class="contenedor-notas">
				<input type="hidden" name="path-nota" id="path-nota" value="<?php echo $_SERVER['SCRIPT_NAME']; ?>" />
				<textarea name="texto-nota" id="texto-nota"><?php echo get_nota( $_SERVER['SCRIPT_NAME'] ); ?></textarea><br />
				<div style="text-align:right;">
					<input type="button" name="guardar-nota" id="guardar-nota" class="boton-general-notas" value="Guardar" />
				</div>
			</div>
		</form>
	</div>
<?php
}










?>
