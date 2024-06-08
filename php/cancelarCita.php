<?php

    $json = file_get_contents('php://input');
    $data = json_decode($json, true); 

    // Imprimir el contenido recibido para depuración
    echo '<pre>';
    print_r($data);
    echo '</pre>';

    // Extraer el idHorario de los datos recibidos
    // $idHorario = $data;

    if ($data === null) {
        echo "Error: idHorario no está presente en los datos recibidos.";
        exit;
    }

    $servidor = 'localhost';
    $usuario = 'root';
    $password = '';
    $nombreBD = 'psyconnect';

    try {
        // Crear una nueva conexión PDO
        $pdo = new PDO("mysql:host=$servidor;dbname=$nombreBD", $usuario, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Actualizar el estado del horario a 'libre'
        $sqlUpdate = "UPDATE horarios SET estado = 'libre' WHERE idHorario = :idHorario";
        $stmtUpdate = $pdo->prepare($sqlUpdate);
        $stmtUpdate->bindParam(':idHorario', $data, PDO::PARAM_INT);
        $stmtUpdate->execute();

        // Verificar si la actualización fue exitosa
        if ($stmtUpdate->rowCount() > 0) {
            // Consulta para obtener todos los horarios
            $sql2 = "SELECT idHorario, fechaInicio, fechaFin, estado FROM horarios";
            $stmt = $pdo->prepare($sql2);
            $stmt->execute();
            $horarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Eliminar la cita cuyo idHorario $data
            // $sqlDeleteCita = "DELETE FROM citas WHERE idHorario = :idHorario";
            // $stmtDeleteCita = $pdo->prepare($sqlDeleteCita);
            // $stmtDeleteCita->bindParam(':idHorario', $data, PDO::PARAM_INT);
            // $stmtDeleteCita->execute();

            // Convertir los resultados a JSON
            $jsonHorarios = json_encode($horarios, JSON_PRETTY_PRINT);

            // Guardar los datos en el archivo JSON
            $filePath = '../js/lecturaHorario.json';
            file_put_contents($filePath, $jsonHorarios);

            echo "Actualización exitosa y JSON sobrescrito correctamente.";
        } else {
            echo "No se encontró ningún registro con idHorario = $data.";
        }

    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }

    // Cerrar la conexión
    $pdo = null;

?>
