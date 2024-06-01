<?php
// // Leer los datos JSON de la solicitud
// $json = file_get_contents('php://input');
// $data = json_decode($json, true); // Convertir JSON a array asociativo

// // Imprimir todos los datos recibidos
// echo "Datos recibidos desde JavaScript:";
// echo "<pre>";
// print_r($data);
// echo "</pre>";

// // Acceder a los datos individuales
// $idHorario = $data['idHorario'];
// $motivo = $data['motivo'];
// $via = $data['via'];
// $emailPaciente = $data['emailPaciente'];

// // Imprimir datos individuales
// echo "ID Horario: " . $idHorario . "<br>";
// echo "Motivo: " . $motivo . "<br>";
// echo "Vía: " . $via . "<br>";
// echo "Email del Paciente: " . $emailPaciente . "<br>";

// Leer los datos JSON de la solicitud
$json = file_get_contents('php://input');
$data = json_decode($json, true); // Convertir JSON a array asociativo

// Acceder a los datos individuales
$idHorario = $data['idHorario'];
$motivo = $data['motivo'];
$via = $data['via'];
$emailPaciente = $data['emailPaciente'];

// Conexión con la base de datos
$servidor = 'localhost';
$usuario = 'root';
$password = "";
$nombreBD = 'psyconnect';

try {
    // Crear conexión
    $conexion = new PDO("mysql:host=$servidor;dbname=$nombreBD", $usuario, $password);
    // Establecer el modo de error de PDO a excepción
    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // SQL para insertar los datos
    $sql = "INSERT INTO citas (motivo, via, emailPaciente, idHorario) VALUES (?, ?, ?, ?)";
    // Preparar la consulta y devolver un objeto PDOStatement
    $stmt = $conexion->prepare($sql);
    // Ejecutar la consulta
    $stmt->execute([$motivo, $via, $emailPaciente, $idHorario]);

    echo "Cita guardada con éxito";
} catch (PDOException $e) {
    echo "Error al insertar los datos: " . $e->getMessage();
} finally {
    // Cerrar la conexión
    $conexion = null;
}


?>
