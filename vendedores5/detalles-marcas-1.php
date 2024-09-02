<?php
require('ini.php');
ini_set('display_errors', 0);

// print_r($_GET);
if( is_numeric($_GET['id-cliente']) && is_numeric($_GET['id-sr'])){

    $marcas = get_marcas();
    // $ids_asociados = implode(',', array_values(ids_clientes_asociados($_GET['id-cliente'])));
    $ventas_sr_cliente_marcas = ventas_sr_cliente_marcas($_GET['id-sr'], $_GET['id-cliente']);

    if(count($ventas_sr_cliente_marcas)){
        echo '<table class="table table-bordered table-striped table-condensed" style="width: 100%;margin:0">' . "\n";
        echo '<tr class="info">' . "\n";
        echo "<th>Marca</th>";
        echo "<th>% Desc</th>";
        echo "<th>Ventas</th>";
        echo "</tr>\n";
        foreach($ventas_sr_cliente_marcas as $marca=>$detalles){
            $descuento_marca = descuento_marca($_GET['id-cliente'], $_GET['id-sr'], $marca);
            echo "<tr>";
            echo "<td>" . $marcas[$marca] . "</td>";
            echo "<td class='text-right'>" . floatval($descuento_marca) . "</td>";
            echo "<td class='text-right'>" . $detalles['unidades'] . ' (' . numero_en_miles($detalles['monto']) . ")</td>";
            echo "</tr>\n";
        }
        echo "</table>\n";
    }else{
        echo "<p>No hay datos para mostrar</p>";
    }

}else{
    echo "<p>Error al recuperar los datos</p>";
}

