<?php

    require_once '../vendor/autoload.php';
    use Google\Client;
    use Google\Service\Calendar;
    use Google\Service\Calendar\Event;

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

        //ELIMINAR EL EVENTO DE GOOGLE CALENDAR
        $sqlIdGoogleCalendar = "SELECT idGoogleCalendar FROM citas WHERE idHorario = :idHorario";
        $stmt = $pdo->prepare($sqlIdGoogleCalendar);
        $stmt->bindParam(':idHorario', $data, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result && !empty($result['idGoogleCalendar'])) {
            $idGoogleCalendar = $result['idGoogleCalendar'];

            // Iniciar sesión si no está activa
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }

            // Instancia de google client -> obtener credenciales
            $client = new Google\Client();
            $client->setAuthConfig('../config/client_secret_817642552550-grgavacspiedvqco6uu785u561bepi4o.apps.googleusercontent.com.json');
            $client->addScope(Google\Service\Calendar::CALENDAR);
            $client->addScope('https://www.googleapis.com/auth/gmail.send');
            $redirect_uri = 'http://' . $_SERVER['HTTP_HOST'] . '/proyectoFinal/proyectoFinal/php/aceptarCita.php';
            $client->setRedirectUri($redirect_uri);

            // Ver si ha caducado el token, si esta caducado -> iniciar sesion
            if (isset($_SESSION['access_token'])) {
                $client->setAccessToken($_SESSION['access_token']);
            }
            if ($client->isAccessTokenExpired() || !$client->getAccessToken()) {
                $authUrl = $client->createAuthUrl();
                header('Location: ' . $authUrl);
                exit;
            }

            // Crea una instancia del servicio de Google Calendar
            $calendarService = new Google\Service\Calendar($client);

            // Eliminar el evento de Google Calendar
            $calendarId = 'primary';
            $calendarService->events->delete($calendarId, $idGoogleCalendar);
            echo 'Evento de Google Calendar eliminado correctamente.';
        } else {
            echo 'No se encontró el idGoogleCalendar para el idHorario especificado.';
        }
        
//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

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





// ----------------------------------------------------------------------------------------------------------------------------------
    // require_once '../vendor/autoload.php';
    // use Google\Client;
    // use Google\Service\Calendar;
    // use Google\Service\Calendar\Event;
    


    // $json = file_get_contents('php://input');
    // $data = json_decode($json, true); 

    // // Imprimir el contenido recibido
    // echo '<pre>';
    // print_r($data);
    // echo '</pre>';

    // $idCita = $data['idCita'];
    // $idHorario = $data['idHorario'];
    // $fechaInicio = $data['fechaInicio'];
    // $fechaFin = $data['fechaFin'];
    // $nombre = $data['nombre'];
    // $emailPaciente = $data['emailPaciente'];
    // $motivo = $data['motivo'];
    // $via = $data['via'];

    // $servidor = 'localhost';
    // $usuario = 'root';
    // $password = '';
    // $nombreBD = 'psyconnect';

    // try {
    //     $conexion = new PDO("mysql:host=$servidor;dbname=$nombreBD", $usuario, $password);
    //     $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    //     // SQL para actualizar el estado del horario a "ocupado"
    //     $sql = "UPDATE horarios SET estado = 'libre' WHERE idHorario = ?";
    //     $stmt = $conexion->prepare($sql);
    //     $stmt->execute([$idHorario]);

    //     // Leer los datos de la tabla horarios
    //     $sqlHorarios = "SELECT * FROM horarios";
    //     $stmtHorarios = $conexion->query($sqlHorarios);
    //     $horarios = $stmtHorarios->fetchAll(PDO::FETCH_ASSOC);

    //     // Escribir el archivo JSON lecturaHorario.json
    //     file_put_contents('../js/lecturaHorario.json', json_encode($horarios));

    //     // Leer los datos de la tabla citas
    //     $sqlCitas = "SELECT * FROM citas";
    //     $stmtCitas = $conexion->query($sqlCitas);
    //     $citas = $stmtCitas->fetchAll(PDO::FETCH_ASSOC);

    //     // Escribir el archivo JSON listadoCitas.json
    //     file_put_contents('../js/listadoCitas.json', json_encode($citas));

    //     echo "Conexión exitosa. Archivos JSON actualizados correctamente.";
    // } catch (PDOException $e) {
    //     echo "Error al conectar con la base de datos: " . $e->getMessage();
    // }

    // //Generar evento en google calendar
    // // Iniciar sesión si no está activa
    // if (session_status() === PHP_SESSION_NONE) {
    //     session_start();
    // }

    // //Instancia de google client -> obtener credenciales
    // $client = new Google\Client();
    // $client->setAuthConfig('../config/client_secret_817642552550-grgavacspiedvqco6uu785u561bepi4o.apps.googleusercontent.com.json');
    // $client->addScope(Google\Service\Calendar::CALENDAR);
    // $client->addScope('https://www.googleapis.com/auth/gmail.send');
    // $redirect_uri = 'http://' . $_SERVER['HTTP_HOST'] . '/proyectoFinal/proyectoFinal/php/aceptarCita.php';
    // $client->setRedirectUri($redirect_uri);

    // //Ver si ha caducado el token, si esta caducado -> iniciar sesion
    // if (isset($_SESSION['access_token'])) {
    //     $client->setAccessToken($_SESSION['access_token']);
    // }
    // if ($client->isAccessTokenExpired() || !$client->getAccessToken()) {
    //     $authUrl = $client->createAuthUrl();
    //     header('Location: ' . $authUrl);
    //     exit;
    // }

    // // Crea una instancia del servicio de Google Calendar
    // $calendarService = new Google\Service\Calendar($client);

    // $fechaEvento = strstr($fechaInicio, ' ', true);
    // $nombreEvento = $nombre. ", ". $via;
    // $horaInicio = strstr($fechaInicio, ' ');
    // $horaInicio = ltrim($horaInicio);
    // //echo $horaInicio;
    // $horaFin = strstr($fechaFin, ' ');
    // $horaFin = ltrim($horaFin);
    // //echo $nombreEvento;
    // //echo '<br>'. $fechaEvento;
    // // Crear un nuevo evento para el 8 de julio
    // $fecha = new DateTime($fechaEvento);

    // $newEvent = new Event([
    //     'summary' => $nombreEvento,
    //     'start' => ['dateTime' => $fecha->format('Y-m-d') . 'T'. $horaInicio, 'timeZone' => 'Europe/Madrid'],
    //     'end' => ['dateTime' => $fecha->format('Y-m-d') . 'T' . $horaFin, 'timeZone' => 'Europe/Madrid'],
    // ]);

    // //var_dump($newEvent);

    // // // Insertar el evento en el calendario del usuario
    // $calendarId = 'primary';
    // $createdEvent = $calendarService->events->insert($calendarId, $newEvent);

    // // // Mostrar el ID del evento creado
    // echo 'Evento creado: ' . $createdEvent->getId();

    // //cambiar mensaje de confirmacion



?>
