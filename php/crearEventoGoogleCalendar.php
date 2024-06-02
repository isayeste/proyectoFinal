<?php  
    // Incluir el autoloader de Composer
    require_once '../vendor/autoload.php';

    // Importar las clases necesarias
    use Google\Client;
    use Google\Service\Calendar;

    // Decodificar los datos JSON recibidos
    header('Content-Type: application/json');
    $entrada = file_get_contents('php://input');
    $datos = json_decode($entrada, true);

    $token = $datos['token'];
    $idHorario = $datos['idHorario'];

    // Verificar que se recibieron los datos necesarios
    if (empty($token) || empty($idHorario)) {
        echo json_encode(['success' => false, 'message' => 'Token o ID de la cita no proporcionado']);
        exit;
    }

    // Conexión a la base de datos
    $nombreServidor = "localhost";
    $nombreUsuario = "root";
    $contrasenia = "";
    $nombreBaseDatos = "psyconnect";

    $conn = new mysqli($nombreServidor, $nombreUsuario, $contrasenia, $nombreBaseDatos);

    // Verificar la conexión
    if ($conn->connect_error) {
        echo json_encode(['success' => false, 'message' => 'Error de conexión: ' . $conn->connect_error]);
        exit;
    }

    // Consultar los detalles de la cita
    $sql = "SELECT horarios.fechaInicio, horarios.fechaFin, pacientes.nombre, citas.via FROM citas
            JOIN horarios ON citas.idHorario = horarios.idHorario
            JOIN pacientes ON citas.emailPaciente = pacientes.emailPaciente
            WHERE horarios.idHorario = ?";

    $declaracion = $conn->prepare($sql);
    $declaracion->bind_param("i", $idHorario); //Indicar que idHorario es un entero (i)
    $declaracion->execute();
    $resultado = $declaracion->get_result();

    if ($resultado->num_rows > 0) {
        $fila = $resultado->fetch_assoc();
        $fechaInicio = $fila['fechaInicio'];
        $fechaFin = $fila['fechaFin'];
        $nombrePaciente = $fila['nombre'];
        $via = $fila['via'];
    } else {
        echo json_encode(['success' => false, 'message' => 'No se encontraron detalles de la cita']);
        exit;
    }

    $declaracion->close();
    $conn->close();

    // Verificar si las variables están definidas
    if (!isset($fechaInicio, $fechaFin, $nombrePaciente, $via)) {
        echo json_encode(['success' => false, 'message' => 'Variables no definidas']);
        exit;
    }

    // Crear una instancia del cliente de Google
    $cliente = new Client();
    $cliente->setAuthConfig('../config/client_secret_817642552550-grgavacspiedvqco6uu785u561bepi4o.apps.googleusercontent.com.json');
    $cliente->addScope(Calendar::CALENDAR);
    $cliente->setAccessToken($token);

    // Verificar si el token ha expirado y renovarlo si es necesario
    if ($cliente->isAccessTokenExpired()) {
        $refreshToken = $cliente->getRefreshToken();
        if ($refreshToken) {
            $cliente->fetchAccessTokenWithRefreshToken($refreshToken);
            $token = $cliente->getAccessToken();
        } else {
            echo json_encode(['success' => false, 'message' => 'El token de acceso ha expirado y no hay token de actualización disponible']);
            exit;
        }
    }

    // Crear una instancia del servicio de Google Calendar
    $servicio = new Calendar($cliente);

    // Crear los detalles del evento
    $evento = new Google_Service_Calendar_Event(array(
      'summary' => "Cita con $nombrePaciente",
      'description' => "Tipo de cita: $via",
      'start' => array(
        'dateTime' => $fechaInicio, 
        'timeZone' => 'Europe/Madrid',
      ),
      'end' => array(
        'dateTime' => $fechaFin,
        'timeZone' => 'Europe/Madrid',
      ),
    ));

    // Insertar el evento en el calendario
    $idCalendario = 'primary';
    try {
        $evento = $servicio->events->insert($idCalendario, $evento);
        // Verificar si el evento se ha creado correctamente
        if ($evento) {
            echo json_encode(['success' => true, 'message' => 'Evento creado en Google Calendar']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al crear el evento en Google Calendar']);
        }
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Error al crear el evento: ' . $e->getMessage()]);
    }
?>
