<?php
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

    // Iniciar una transacción
    $conexion->beginTransaction();

    // SQL para insertar los datos en la tabla citas
    $sqlCitas = "INSERT INTO citas (motivo, via, emailPaciente, idHorario) VALUES (?, ?, ?, ?)";
    // Preparar la consulta para citas y ejecutarla
    $stmtCitas = $conexion->prepare($sqlCitas);
    $stmtCitas->execute([$motivo, $via, $emailPaciente, $idHorario]);

    // SQL para actualizar el estado del horario a "espera"
    $sqlHorarios = "UPDATE horarios SET estado = 'espera' WHERE idHorario = ?";
    // Preparar la consulta para horarios y ejecutarla
    $stmtHorarios = $conexion->prepare($sqlHorarios);
    $stmtHorarios->execute([$idHorario]);

    // Confirmar la transacción
    $conexion->commit();

    // Obtener los datos de las citas
    $sqlListadoCitas = "SELECT * FROM citas INNER JOIN horarios ON citas.idHorario = horarios.idHorario";
    $stmtListadoCitas = $conexion->query($sqlListadoCitas);
    $listadoCitas = $stmtListadoCitas->fetchAll(PDO::FETCH_ASSOC);

    // Escribir el archivo JSON listadoCitas.json
    file_put_contents('../js/listadoCitas.json', json_encode($listadoCitas));

    // Obtener los datos de los horarios
    $sqlLecturaHorario = "SELECT * FROM horarios";
    $stmtLecturaHorario = $conexion->query($sqlLecturaHorario);
    $lecturaHorario = $stmtLecturaHorario->fetchAll(PDO::FETCH_ASSOC);

    // Escribir el archivo JSON lecturaHorario.json
    file_put_contents('../js/lecturaHorario.json', json_encode($lecturaHorario));

    echo "Cita guardada con éxito";
} catch (PDOException $e) {
    // Si hay un error, revertir la transacción
    $conexion->rollBack();
    echo "Error al insertar los datos: " . $e->getMessage();
} finally {
    // Cerrar la conexión
    $conexion = null;
}
?>
