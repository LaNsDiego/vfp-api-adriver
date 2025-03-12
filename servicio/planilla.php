<?php
require_once("lib/odbc.php");   
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");

// if(isset($_GET['dni'])){

	$conexion_odbc = "Provider=vfpoledb;DSN=siaf;";
	$odbc = new odbc();
	$odbc->configurar($conexion_odbc, "", "");
	$odbc->ejecutar_cmd("set exclusive off");
    $sql = "SELECT categoria_monto_id, categoria_cod, conlab_cod, rubro_cod, cat_monto_valor, estado, sys_log, descripcion, fech_ini, fech_fin, incremento, sec_eje, formula, valor, inicio, fin
	FROM rrhh.categoria_monto where conlab_cod='L'";

	$result = $odbc->ejecutar_sql($sql);
	
	// return $result2;
    echo var_dump($sql);
// }