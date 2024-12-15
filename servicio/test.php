<?php
require_once("lib/odbc.php");
// require_once('lib/nusoap.php');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");

function lista()
{
    // http://localhost:9000/servicio/test.php?sec=0770&clasif=6.8.1.4.1
	// $anio = $_GET['anio'];
	$clasif = $_GET['clasif'];
	$sec = $_GET['sec'];

    if (!isset($clasif) || !isset($sec)) {
        return ("Missing required parameters.");
    }

    if (!is_numeric($sec)) {
        return ("Invalid 'sec' parameter.");
    }

    if (!preg_match('/^\d+(\.\d+)*$/', $clasif)) {
        return ("Invalid 'clasif' parameter.");
    }

	$conexion_odbc = "Provider=vfpoledb;DSN=siaf;";
	$odbc = new odbc();
	$odbc->configurar($conexion_odbc, "", "");
	$odbc->ejecutar_cmd("set exclusive off");
	
	// $sql = "SELECT ano_eje,sec_func,modificacion,ejecucion, (modificacion-ejecucion) as SaldoPIM FROM gasto WHERE sec_func='$sec' AND id_clasificador = '$clasif'";

    // $especific = "3.8.1.4.1";
    $especific = $clasif;
    $parts = explode('.', $especific);

    $ano_eje = '2024';
    // $tipo_transaccion = '2';
    // $generica = '6';
    // $subgenerica = '8';
    // $subgenerica_det = '1';
    // $especifica = '4';
    // $especifica_det = '1';
    //6.8.1.4.1

    $tipo_transaccion = '2';
    $generica = $parts[0];
    $subgenerica = $parts[1];
    $subgenerica_det = $parts[2];
    $especifica = $parts[3];
    $especifica_det = $parts[4];

    // $sql2 = "SELECT id_clasificador FROM especifica_det 
    // WHERE ano_eje='".$ano_eje."'
    // AND ALLTRIM(tipo_transaccion)='".$tipo_transaccion."'
    // AND ALLTRIM(generica)='".$generica."' 
    // AND ALLTRIM(subgenerica)='".$subgenerica."' 
    // AND ALLTRIM(subgenerica_det)='".$subgenerica_det."' 
    // AND ALLTRIM(especifica)='".$especifica."'
    // AND ALLTRIM(especifica_det)='1'";

    // $sql = "SELECT * FROM gasto WHERE sec_func='0770' AND id_clasificador = 'ACbcxSh'";

    $sql3 = "SELECT gasto.modificacion - monto_certificado as saldo  FROM gasto WHERE sec_func='".$sec."' AND id_clasificador =
    (SELECT id_clasificador FROM especifica_det 
    WHERE ano_eje='".$ano_eje."'
    AND ALLTRIM(tipo_transaccion)='".$tipo_transaccion."'
    AND ALLTRIM(generica)='".$generica."' 
    AND ALLTRIM(subgenerica)='".$subgenerica."' 
    AND ALLTRIM(subgenerica_det)='".$subgenerica_det."' 
    AND ALLTRIM(especifica)='".$especifica."'
    AND ALLTRIM(especifica_det)='".$especifica_det."')";

    $sql = "SELECT act_proy,nombre FROM act_proy_nombre 
    WHERE ano_eje='2024' 
    AND act_proy=(SELECT act_proy FROM meta WHERE ano_eje='".$ano_eje."' AND sec_ejec='000931' AND sec_func='".$sec."')";

	$result = $odbc->ejecutar_sql($sql3);
	$result2 = $odbc->ejecutar_sql($sql);
	return array_merge($result2[0],$result[0]);
	return $result2;
}

//print_r(lista());

// echo "<pre>".json_encode(lista())."</pre>";
echo json_encode(lista());

