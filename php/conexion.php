<?php
    //Cargar autoload de composer para obtener las clases necesarias
    require_once '../vendor/autoload.php'; //Usar require_once para que no haya conflictos
    
    //Importar las clases necesarias para la conexión con Google Calendar
    use Google\Client; //punto de entrada para interactuar con los servicios de Google
    use Google\Service\Calendar; //Interactuar específicamente con Google Calendar
    use Google\Service\CloudSearch\PushItem;

    //Crea una instancia de la clase Client para la autenticación y comunicación con los servicios de Google
    $cliente = new Google\Client();
    //Establecer las credenciales de autenticación para que la app pueda acceder a los servicios de Google con el método setAuthConfig() y el JSON de la consola
    $cliente->setAuthConfig('../config/client_secret_817642552550-grgavacspiedvqco6uu785u561bepi4o.apps.googleusercontent.com.json');
    //addScope -> agrega autorizaciones a la aplicación para el acceso a los servicios de google
    //Accede a la constante CALENDAR dentro de la clase Calendar ubicado en esa ruta
    $cliente->addScope(Google\Service\Calendar::CALENDAR);

    //Variable que almacena la URL en la que Google redirecciona al usuario después de que haya autorizado la aplicación (la misma que en la consola de google)
    $redireccion = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];
    $cliente->setRedirectUri($redireccion);
    //echo($_SERVER['HTTP_HOST']);
    //echo($_SERVER['PHP_SELF']);

    // Si se recibió un código de autorización, intercambia el código por un token de acceso
    //Cuando un usuario autoriza una aplicación a través de OAuth 2.0, Google redirige al usuario de vuelta a la URL de redireccionamiento y un parámetro llamado 'code' en esta URL
    if (isset($_GET['code'])) { //Verifica que hay un parámetro en la URL llamado 'code'
        //Se intercambia el código de la autorización por un token de acceso a través de ese code
        $token = $cliente->fetchAccessTokenWithAuthCode($_GET['code']);
        // Verifica si hay errores
        if (array_key_exists('error', $token)) {
            die('Error al intentar obtener el token de acceso: ' . $token['error']);
        }
    }

    // Si no hay un token de acceso, redirige al usuario a loguearse
    if (!isset($token['access_token'])) {
        $authUrl = $cliente->createAuthUrl();
        header('Location: ' . $authUrl);
        exit;
    }

    //Se crea una instancia del servicio de Google Calendar
    $calendario = new Google\Service\Calendar($cliente);

    //Obtener la lista de eventos del calendario
    $eventos = $calendario->events->listEvents('primary');
    $eventosConFechas = [];
    
    //Obtener las fechas de cada evento
    foreach($eventos->getItems() as $evento){
        $inicio = $evento->getStart()->getDateTime();
        $nombreEvento = $evento->getSummary(); // Obtener el nombre del evento
    
        // Crear un array con el datetime y el nombre del evento
        $eventoConFecha = ['fechaInicio' => $inicio, 'nombreEvento' => $nombreEvento];
    
        // Agregar el array al array de eventos con fechas
        $eventosConFechas[] = $eventoConFecha;
    }
    


    //var_dump($eventosConFechas);
    $jsonEventosConFechas = json_encode($eventosConFechas);

    echo $jsonEventosConFechas;
    $fichero = "../js/eventos.json";
    file_put_contents($fichero, $jsonEventosConFechas);


  
?>