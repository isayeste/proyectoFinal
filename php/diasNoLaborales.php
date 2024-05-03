<?php
    $fecha = $_POST["fecha"];
    //Conexión con la base de datos
    $servidor = 'localhost';
    $usuario = 'root';
    $password = "";
    $nombreBD = 'psyconnect';

    echo $fecha;

    try{
        $conexion = new PDO("mysql:host=$servidor;dbname=$nombreBD", $usuario, $password);
        $sql = "DELETE FROM horarios WHERE fechaInicio LIKE ?";
        //Preparar la consulta y devuelve un  objeto PDOStatement. execute ejecuta la consulta
        $stmt = $conexion->prepare($sql);
        $stmt->execute(["$fecha%"]);
    
    
    }
    catch(PDOException $e){
        echo "Error al insertar los datos ". $e ->getMessage();
    }
    finally {
    // Cerrar la conexión
        $conexion = null;


    }
    header("Location: ../html/formularioDiasNoLaborales.html");
?>