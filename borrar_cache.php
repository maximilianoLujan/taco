<pre><?php
$path = './cache/';

$dir = scandir( $path );
//print_r($dir);

foreach($dir as $file){
	$nuevo_path = $path . $file;
	if( strlen($file) > 3 && is_file($nuevo_path) ){
		chmod( $nuevo_path , 0666 );
		unlink( $nuevo_path );
	}
}

//$dir = scandir( $path );
//print_r($dir);

?></pre>
