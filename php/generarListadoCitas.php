<?php

// Datos de la base de datos
$servidor = 'localhost';
$usuario = 'root';
$password = '';
$nombreBD = 'psyconnect';


// Código para actualizar el archivo JSON
try {
    $conexion = new PDO("mysql:host=$servidor;dbname=$nombreBD", $usuario, $password);
    $sql = "SELECT idCita, motivo, via, emailPaciente, idHorario FROM citas";
    $stmt = $conexion->prepare($sql);
    $stmt->execute();
    // Obtener las fechas como un array
    $fechas = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $jsonFechas = json_encode($fechas);
    
    // Guardar las fechas en formato JSON
    $fichero = "../js/listadoCitas.json";
    file_put_contents($fichero, $jsonFechas);
    echo "Horarios actualizados correctamente";
} catch (PDOException $e) {
    echo "Error al leer los datos " . $e->getMessage();
} finally {
    $conexion = null;
}

//header("Location: ../html/inicioPsicologo.html");
exit;

?>
