<?php

    // Leer los datos JSON de la solicitud
    $json = file_get_contents('php://input');
    $data = json_decode($json, true); // Convertir JSON a array asociativo

    // Imprimir el contenido recibido
    echo '<pre>';
    print_r($data);
    echo '</pre>';

    $idCita = $data['idCita'];
    $idHorario = $data['idHorario'];
    $fechaInicio = $data['fechaInicio'];
    $fechaFin = $data['fechaFin'];
    $nombre = $data['nombre'];
    $emailPaciente = $data['emailPaciente'];
    $motivo = $data['motivo'];
    $via = $data['via'];

    $servidor = 'localhost';
    $usuario = 'root';
    $password = '';
    $nombreBD = 'psyconnect';


    try {
        // Crear conexión con PDO
        $conexion = new PDO("mysql:host=$servidor;dbname=$nombreBD", $usuario, $password);
        // Establecer el modo de error de PDO a excepción
        $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    

        // SQL para actualizar el estado del horario a "ocupado"
        $sql = "UPDATE horarios SET estado = 'ocupado' WHERE idHorario = ?";
        echo "<br>";
        echo $sql;
        $stmt = $conexion->prepare($sql);
        $stmt->execute([$idHorario]);
    
        echo "Conexión exitosa y datos guardados correctamente en variables.";
    } catch (PDOException $e) {
        echo "Error al conectar con la base de datos: " . $e->getMessage();
    }

?>
