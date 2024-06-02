<?php



//ACTUALIZAR LA BASE DE DATOS
// Datos de la base de datos
$servidor = 'localhost';
$usuario = 'root';
$password = '';
$nombreBD = 'psyconnect';

try {
    $conexion = new PDO("mysql:host=$servidor;dbname=$nombreBD", $usuario, $password);
    // Habilitar el modo de errores
    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Leer los datos JSON de la solicitud
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);

    // Obtener idHorario de los datos JSON
    $idHorario = $data['idHorario'];

    // Actualizar el estado en la tabla horarios
    $sql = "UPDATE horarios SET estado = 'ocupado' WHERE idHorario = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->execute([$idHorario]);

    echo json_encode(["message" => "Estado actualizado correctamente"]);
} catch (PDOException $e) {
    echo json_encode(["message" => "Error al actualizar el estado: " . $e->getMessage()]);
} finally {
    $conexion = null;
}

?>
