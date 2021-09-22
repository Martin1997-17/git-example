<?php

	$fechaInicio11=$_GET['fechaStart11'];
	$fechaFin11=$_GET['fechaEnd11'];
    $hourInicio11=$_GET['hourStart11'];
    $hourFin11=$_GET['hourEnd11'];
	
	$datos = array();
    $response = array();

    //Realizar la conexion a la base de datos
    $link = new mysqli("localhost", "root", "", "infosensors");
    //$conn = new PDO("mysql:host=localhost;dbname=infosensors","root","");
    //Ejecuta la consulta
    //----Comporbar la conexion----
    if($link->connect_errno){
    	echo"Fallo la conexion: ".$mysqli->connect_error;
    	exit();
    }

    
    //Consulta 12
    $query = "SELECT AVG(CO), DateRCT, HourRCT FROM infosensors WHERE DateRCT BETWEEN '$fechaInicio11' AND '$fechaFin11' AND HourRCT BETWEEN '$hourInicio11' AND '$hourFin11' GROUP BY HOUR( HourRCT ) + FLOOR( MINUTE( HourRCT ) / 60 ) ORDER BY DateRCT, HourRCT ASC";


    //Consultas de seleccion que devuelve un conjunto de resultados
    if($resultados = mysqli_query($link, $query)){
    	for($num_fila = $resultados->num_rows - 1; $num_fila >= 0; $num_fila--){
    		$resultados->data_seek($num_fila);
    		$fila = $resultados->fetch_assoc();

            $CO=$fila['AVG(CO)'];
    		$DateRCT=$fila['DateRCT'];
            $HourRCT=$fila['HourRCT'];

            settype( $CO, "integer");//Esta funcion me permite acceder al campo y modificar su tipo de variable

    		$datos[] = array('CO'=> $CO, 'DateRCT'=> $DateRCT, 'HourRCT'=> $HourRCT); 

    	}
        //Hora: 11:00, 14 Sabado
    	//Liberar el conjunto de resultados
    	mysqli_free_result($resultados);
    }

    $response["nivelesPMList"] = $datos;
    echo json_encode($response);
?>