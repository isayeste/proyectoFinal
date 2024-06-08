<?php
    require_once '../vendor/autoload.php';
    use Google\Client;
    use Google\Service\Calendar;
    use Google\Service\Calendar\Event;
    use Google\Service\Gmail;
    use Google\Service\Gmail\Message;
    use Google\Service\Gmail\MessagePart;
    use Google\Service\Gmail\MessagePartHeader;

    $json = file_get_contents('php://input');
    $data = json_decode($json, true); 

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
        $conexion = new PDO("mysql:host=$servidor;dbname=$nombreBD", $usuario, $password);
        $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // SQL para actualizar el estado del horario a "ocupado"
        $sql = "UPDATE horarios SET estado = 'libre' WHERE idHorario = ?";
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

    //Generar evento en google calendar
    // Iniciar sesión si no está activa
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    //Instancia de google client -> obtener credenciales
    $client = new Google\Client();
    $client->setAuthConfig('../config/client_secret_817642552550-grgavacspiedvqco6uu785u561bepi4o.apps.googleusercontent.com.json');
    $client->addScope(Google\Service\Calendar::CALENDAR);
    $client->addScope('https://www.googleapis.com/auth/gmail.send');
    $redirect_uri = 'http://' . $_SERVER['HTTP_HOST'] . '/proyectoFinal/proyectoFinal/php/aceptarCita.php';
    $client->setRedirectUri($redirect_uri);

    //Ver si ha caducado el token, si esta caducado -> iniciar sesion
    if (isset($_SESSION['access_token'])) {
        $client->setAccessToken($_SESSION['access_token']);
    }
    if ($client->isAccessTokenExpired() || !$client->getAccessToken()) {
        $authUrl = $client->createAuthUrl();
        header('Location: ' . $authUrl);
        exit;
    }

    

//----------------------------------------------------------------------------------------------------------------------

    //Enviar email
    $subject = 'Cita confirmada';
    $body = 'Tu cita ha sido confirmada correctamente. Fecha: '. $fechaEvento . " a las: " . $horaInicio . " horas.";
    $to = $emailPaciente;
    $client->setAccessType('offline');

    $serviceGmail = new Gmail($client);
    $message = new Message();
    $message->setRaw(
        base64_encode(
            "From: Tu Nombre <psicologodaw@gmail.com>\r\n" .
            "To: " . $to . "\r\n" .
            "Subject: " . $subject . "\r\n" .
            "MIME-Version: 1.0\r\n" .
            "Content-Type: text/plain; charset=UTF-8\r\n" .
            "Content-Transfer-Encoding: base64\r\n\r\n" .
            rtrim(strtr(base64_encode($body), '+/', '-_'), '=') . "\r\n"
        )
    );

    try {
        $serviceGmail->users_messages->send('me', $message);
        echo 'Correo electrónico enviado correctamente.';
    } catch (Exception $e) {
        echo 'Error al enviar el correo electrónico: ' . $e->getMessage();
    }

?>