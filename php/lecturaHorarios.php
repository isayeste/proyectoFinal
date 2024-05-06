<?php
    //Conexión con la base de datos
    $servidor = 'localhost';
    $usuario = 'root';
    $password = "";
    $nombreBD = 'psyconnect';

    try{
        $conexion = new PDO("mysql:host=$servidor;dbname=$nombreBD", $usuario, $password);
        $sql = "SELECT idHorario, fechaInicio, fechaFin, estado FROM horarios";
        $stmt = $conexion->prepare($sql);
        $stmt->execute();
        // Obtener las fechas como un array
        $fechas = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $jsonFechas = json_encode($fechas);
        
        // Devolver las fechas en formato JSON
        echo json_encode($jsonFechas);

        $fichero = "../js/lecturaHorario.json";
        file_put_contents($fichero, $jsonFechas);


    }
    catch(PDOException $e){
        echo "Error al insertar los datos ". $e ->getMessage();
    }
    finally {
    // Cerrar la conexión
        $conexion = null;
    }
?>
