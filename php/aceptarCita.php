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
        $stmt = $conexion->prepare($sql);
        $stmt->execute([$idHorario]);
    
        // Leer los datos de la tabla horarios
        $sqlHorarios = "SELECT * FROM horarios";
        $stmtHorarios = $conexion->query($sqlHorarios);
        $horarios = $stmtHorarios->fetchAll(PDO::FETCH_ASSOC);
    
        // Escribir el archivo JSON lecturaHorario.json
        file_put_contents('../js/lecturaHorario.json', json_encode($horarios));
    
        // Leer los datos de la tabla citas
        $sqlCitas = "SELECT * FROM citas";
        $stmtCitas = $conexion->query($sqlCitas);
        $citas = $stmtCitas->fetchAll(PDO::FETCH_ASSOC);
    
        // Escribir el archivo JSON listadoCitas.json
        file_put_contents('../js/listadoCitas.json', json_encode($citas));
    
        echo "Conexión exitosa. Archivos JSON actualizados correctamente.";
    } catch (PDOException $e) {
        echo "Error al conectar con la base de datos: " . $e->getMessage();
    }

?>
