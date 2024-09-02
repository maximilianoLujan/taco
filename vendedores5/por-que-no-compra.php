<?php
require('ini.php');
ini_set('display_errors', 1);

if( @$_POST['id_cliente'] ){
    $id_cliente = trim($_POST['id_cliente']);
    $por_que_no_compra = trim($_POST['texto']);
    if( strlen($por_que_no_compra) <= 200 ){
        if( @$_POST['id_super_rubro'] && strlen($_POST['id_super_rubro']) > 0 ){
            $id_super_rubro = $_POST['id_super_rubro'];
            if( update_por_que_no_compra( $id_cliente, $id_super_rubro, $por_que_no_compra ) ){
                echo '{"status": "ok", "message": "Super Rubro guardado"}';
            }else{
                echo '{"status": "error", "message": "Error guardado Super Rubro"}';
            }
        }else{
            if( update_por_que_no_compra( $id_cliente, '', $por_que_no_compra ) ){
                echo '{"status": "ok", "message": "Cliente guardado"}';
            }else{
                echo '{"status": "error", "message": "Error guardado Cliente"}';
            }
        }
    }else{
        echo '{""status": "error", "message": "El texto ingresado es demasiado largo (Máximo 200 caracteres)"}';
    }
}else{
    echo '{"status": "error", "message": "Cliente no encontrado"}';
}