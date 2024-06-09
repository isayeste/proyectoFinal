<?php
// Recibir los datos del formulario
$email = $_POST['email'];
$contrasenia = $_POST['contrasenia'];

// Conexión con la base de datos
$servidor = 'localhost';
$usuario = 'root';
$password = "";
$nombreBD = 'psyconnect';

try {
    // Crear una nueva conexión PDO
    $conexion = new PDO("mysql:host=$servidor;dbname=$nombreBD", $usuario, $password);
    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Preparar la consulta para obtener el usuario por email
    $sql = "SELECT contrasenia FROM pacientes WHERE emailPaciente = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->execute([$email]);
    
    // Obtener la contraseña almacenada
    $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($resultado) {
        // Verificar si la contraseña es correcta
        if ($resultado['contrasenia'] === $contrasenia) {
            // Redirigir al usuario a inicioPaciente.html con el email como parametro
            header("Location: ../html/inicioPaciente.php?email=" . urlencode($email));
            exit();
        } else {
            // Redirigir con mensaje de error de contraseña incorrecta
            header("Location: ../html/preInicioSesion.html?error=Contraseña+incorrecta");
            exit();
        }
    } else {
        // Redirigir con mensaje de error de email no encontrado
        header("Location: ../html/preInicioSesion.html?error=Email+no+registrado");
        exit();
    }
} catch (PDOException $e) {
    // Redirigir con mensaje de error de excepción
    header("Location: ../html/preInicioSesion.html?error=Error+al+iniciar+sesión:+".urlencode($e->getMessage()));
    exit();
} finally {
    // Cerrar la conexión
    $conexion = null;
}
?>
