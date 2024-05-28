<?php
//Datos del formulario
$emailPaciente = $_POST['email'];
$contrasenia = $_POST['password'];
$nombre = $_POST['nombre'];
$fechaNacimiento = $_POST['fechaNacimiento'];
$fotoPerfil = $_FILES['fotoPerfil']['tmp_name']; //ruta temporal del archivo

//Conexión con la base de datos
$servidor = 'localhost';
$usuario = 'root';
$password = "";
$nombreBD = 'psyconnect';

try {
    $conexion = new PDO("mysql:host=$servidor;dbname=$nombreBD", $usuario, $password);
    $sql = "INSERT INTO pacientes (emailPaciente, contrasenia, nombre, fechaNacimiento, fotoPerfil) VALUES (?, ?, ?, ?, ?)";
    //Preparar la consulta y devuelve un objeto PDOStatement. execute ejecuta la consulta
    $stmt = $conexion->prepare($sql);
    $stmt->execute([$emailPaciente, $contrasenia, $nombre, $fechaNacimiento, $fotoPerfil]);
} catch (PDOException $e) {
    echo "Error al insertar los datos " . $e->getMessage();
} finally {
    // Cerrar la conexión
    $conexion = null;
}
?>
