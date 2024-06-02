<?php

// Datos de la base de datos
$servidor = 'localhost';
$usuario = 'root';
$password = '';
$nombreBD = 'psyconnect';

// Código para actualizar el archivo JSON
try {
    $conexion = new PDO("mysql:host=$servidor;dbname=$nombreBD", $usuario, $password);
    // Habilitar el modo de errores
    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Consulta para obtener las citas junto con las fechas de la tabla horarios
    $sql = "SELECT c.idCita, c.motivo, c.via, c.emailPaciente, c.idHorario, h.fechaInicio, h.fechaFin, p.nombre 
        FROM citas c JOIN horarios h ON c.idHorario = h.idHorario JOIN pacientes p ON c.emailPaciente = p.emailPaciente";
    
    $stmt = $conexion->prepare($sql);
    $stmt->execute();
    
    // Obtener los datos como un array
    $citas = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $jsonCitas = json_encode($citas);
    
    // Guardar las citas en formato JSON
    $fichero = "../js/listadoCitas.json";
    file_put_contents($fichero, $jsonCitas);
    
    echo "Horarios actualizados correctamente";
} catch (PDOException $e) {
    echo "Error al leer los datos " . $e->getMessage();
} finally {
    $conexion = null;
}

exit;

?>
