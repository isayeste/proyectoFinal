<?php
    // Recibir los datos del formulario
    $email = $_POST['email'];
    $contrasenia = $_POST['contrasenia'];
    
    $servidor = 'localhost';
    $usuario = 'root';
    $password = "";
    $nombreBD = 'psyconnect';

    try {
        $conexion = new PDO("mysql:host=$servidor;dbname=$nombreBD", $usuario, $password);
        $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql = "SELECT contrasenia FROM pacientes WHERE emailPaciente = ?";
        $stmt = $conexion->prepare($sql);
        $stmt->execute([$email]);

        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($resultado) {
            // Verificar si la contraseña es correcta
            if ($resultado['contrasenia'] === $contrasenia) {
                // Redirigir al usuario a inicioPaciente.html con el email como parametro
                header("Location: ../html/inicioPaciente.php?email=" . urlencode($email));
                exit();
            } else {
                header("Location: ../html/preInicioSesion.html?error=Contraseña+incorrecta");
                exit();
            }
        } else {
            header("Location: ../html/preInicioSesion.html?error=Email+no+registrado");
            exit();
        }
    } catch (PDOException $e) {
        header("Location: ../html/preInicioSesion.html?error=Error+al+iniciar+sesión:+".urlencode($e->getMessage()));
        exit();
    } finally {
        $conexion = null;
    }
?>
