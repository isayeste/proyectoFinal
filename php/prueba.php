<?php
// Cargar el autoload de composer para cargar automáticamente las clases
require_once '../vendor/autoload.php';

// Importa las clases necesarias de la biblioteca de Google API Client
use Google\Client;
use Google\Service\Calendar;

// Crea una instancia del cliente
$cliente = new Google\Client();

// Establece la ruta al archivo JSON de credenciales descargado desde la Consola de Desarrolladores de Google
$cliente->setAuthConfig('../config/client_secret_817642552550-grgavacspiedvqco6uu785u561bepi4o.apps.googleusercontent.com.json');

// Añade los permisos necesarios para acceder a Google Calendar
$cliente->addScope(Google\Service\Calendar::CALENDAR);

// Establece la URL de redireccionamiento después de la autorización
$redirect_uri = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];
$cliente->setRedirectUri($redirect_uri);

// Si se recibió un código de autorización, intercambia el código por un token de acceso
if (isset($_GET['code'])) {
    $token = $cliente->fetchAccessTokenWithAuthCode($_GET['code']);
    // Verifica si hay errores
    if (array_key_exists('error', $token)) {
        die('Error al intentar obtener el token de acceso: ' . $token['error']);
    }
    // Guarda el token de acceso en algún lugar seguro para su uso futuro
    // ...
}

// Si no hay un token de acceso, redirige al usuario a la página de autorización
if (!isset($token['access_token'])) {
    $authUrl = $cliente->createAuthUrl();
    header('Location: ' . $authUrl);
    exit;
}

// Crea una instancia del servicio de Google Calendar
$calendarService = new Google\Service\Calendar($cliente);

// Ahora puedes hacer llamadas a la API de Google Calendar aquí
// Por ejemplo, puedes obtener la lista de eventos del calendario del usuario
$events = $calendarService->events->listEvents('primary');

// Muestra los eventos
echo "Eventos del Calendario:\n";
foreach ($events->getItems() as $event) {
    echo "- " . $event->getSummary() . "\n";
}