<?php
require('ini.php');
ini_set('display_errors', 0);

// print_r($_GET);
if( is_numeric($_GET['id-cliente']) && is_numeric($_GET['id-sr'])){
    $marcas = get_marcas();
    // $ids_asociados = implode(',', array_values(ids_clientes_asociados($_GET['id-cliente'])));
    $ventas_sr_cliente_marcas = ventas_sr_cliente_marcas($_GET['id-sr'], $_GET['id-cliente']);
    $string_marcas = "'" . implode("','", array_keys($ventas_sr_cliente_marcas)) . "'";
    $stock_por_marcas = get_stock_marcas( $string_marcas );
    $promedio_ventas_6m_por_marcas = promedio_ventas_6m_por_marcas( $string_marcas );
    $marca_mayor_descuento = get_marca_mayor_descuento( $_GET['id-cliente'],$_GET['id-sr'] );


    if(count($ventas_sr_cliente_marcas)){
        echo '<table class="table table-bordered table-striped table-condensed" style="width: 100%;margin:0">' . "\n";
        echo '<tr class="info">' . "\n";
        echo "<th>Marca</th>";
        echo "<th>% Desc</th>";
        echo "<th>Actual</th>";
        echo "<th>Mes anterior</th>";
        echo "<th>Mes 2 al 6</th>";
        echo "<th>Mes 7 al 12</th>";
        echo "<th>Mes 13 al 24</th>";
        echo "<th>Meses Stock</th>";
        echo "</tr>\n";
        if(isset($marca_mayor_descuento)){
            if(!isset($ventas_sr_cliente_marcas[$marca_mayor_descuento['codigo_marca']])){
                echo "<tr>";
                echo "<td>" . $marca_mayor_descuento['nombre_marca'] . "</td>";
                echo "<td class='text-right'>" . floatval($marca_mayor_descuento['porcentaje_descuento']) . "</td>";
                echo "<td class='text-right'>" . 0 . ' (' . 0 . ")</td>";
                echo "<td class='text-right'>" . 0 . ' (' . 0 . ")</td>";
                echo "<td class='text-right'>" . 0 . ' (' . 0 . ")</td>";
                echo "<td class='text-right'>" . 0 . ' (' . 0 . ")</td>";
                echo "<td class='text-right'>" . 0 . ' (' . 0 . ")</td>";
                // echo "<td class='text-right'>" . round(@$stock_en_meses['unidades'],1) . ' (' . round(@$stock_en_meses['monto'],1) . ")</td>";
                echo "<td class='text-right strong'>" . round(@$stock_en_meses['unidades'],1) . "</td>";
                echo "</tr>\n";
            }
        }
        foreach($ventas_sr_cliente_marcas as $marca=>$detalles){
            $descuento_marca = descuento_marca($_GET['id-cliente'], $_GET['id-sr'], $marca);
            $stock_en_meses['monto'] = divide(@$stock_por_marcas[$marca]['monto'], @$promedio_ventas_6m_por_marcas[$marca]['monto']);
            $stock_en_meses['unidades'] = divide(@$stock_por_marcas[$marca]['unidades'], @$promedio_ventas_6m_por_marcas[$marca]['unidades']);
            echo "<tr>";
            echo "<td>" . $marcas[$marca] . "</td>";
            echo "<td class='text-right'>" . floatval($descuento_marca) . "</td>";
            echo "<td class='text-right'>" . @$detalles['actual']['unidades'] . ' (' . numero_en_miles(@$detalles['actual']['monto']) . ")</td>";
            echo "<td class='text-right'>" . @$detalles['anterior']['unidades'] . ' (' . numero_en_miles(@$detalles['anterior']['monto']) . ")</td>";
            echo "<td class='text-right'>" . @$detalles['from_2_to_6']['unidades'] . ' (' . numero_en_miles(@$detalles['from_2_to_6']['monto']) . ")</td>";
            echo "<td class='text-right'>" . @$detalles['from_7_to_12']['unidades'] . ' (' . numero_en_miles(@$detalles['from_7_to_12']['monto']) . ")</td>";
            echo "<td class='text-right'>" . @$detalles['from_13_to_24']['unidades'] . ' (' . numero_en_miles(@$detalles['from_13_to_24']['monto']) . ")</td>";
            // echo "<td class='text-right'>" . round(@$stock_en_meses['unidades'],1) . ' (' . round(@$stock_en_meses['monto'],1) . ")</td>";
            echo "<td class='text-right strong'>" . round(@$stock_en_meses['unidades'],1) . "</td>";
            echo "</tr>\n";
        }
        echo "</table>\n";
    }else{
        if(isset($marca_mayor_descuento)){
            echo '<table class="table table-bordered table-striped table-condensed" style="width: 100%;margin:0">' . "\n";
            echo '<tr class="info">' . "\n";
            echo "<th>Marca</th>";
            echo "<th>% Desc</th>";
            echo "<th>Actual</th>";
            echo "<th>Mes anterior</th>";
            echo "<th>Mes 2 al 6</th>";
            echo "<th>Mes 7 al 12</th>";
            echo "<th>Mes 13 al 24</th>";
            echo "<th>Meses Stock</th>";
            echo "</tr>\n";
            if(!isset($ventas_sr_cliente_marcas[$marca_mayor_descuento['codigo_marca']])){
                echo "<tr>";
                echo "<td>" . $marca_mayor_descuento['nombre_marca'] . "</td>";
                echo "<td class='text-right'>" . floatval($marca_mayor_descuento['porcentaje_descuento']) . "</td>";
                echo "<td class='text-right'>" . 0 . ' (' . 0 . ")</td>";
                echo "<td class='text-right'>" . 0 . ' (' . 0 . ")</td>";
                echo "<td class='text-right'>" . 0 . ' (' . 0 . ")</td>";
                echo "<td class='text-right'>" . 0 . ' (' . 0 . ")</td>";
                echo "<td class='text-right'>" . 0 . ' (' . 0 . ")</td>";
                // echo "<td class='text-right'>" . round(@$stock_en_meses['unidades'],1) . ' (' . round(@$stock_en_meses['monto'],1) . ")</td>";
                echo "<td class='text-right strong'>" . round(@$stock_en_meses['unidades'],1) . "</td>";
                echo "</tr>\n";
            }
        } else {
            echo "<p>No hay datos para mostrar</p>";
        }
    }

}else{
    echo "<p>Error al recuperar los datos</p>";
}

