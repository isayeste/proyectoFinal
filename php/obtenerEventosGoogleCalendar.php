<?php
// Incluir el autoloader de Composer
require_once '../vendor/autoload.php';

// Importar las clases necesarias de la biblioteca de Google API Client
use Google\Client;
use Google\Service\Calendar;
use Google\Service\Calendar\Event;

// Crear una instancia del cliente
$client = new Google\Client();

// Establecer la ruta al archivo JSON de credenciales descargado desde la Consola de Desarrolladores de Google
$client->setAuthConfig('../config/client_secret_817642552550-grgavacspiedvqco6uu785u561bepi4o.apps.googleusercontent.com.json');

// Añadir el alcance necesario para acceder a Google Calendar
$client->addScope(Google\Service\Calendar::CALENDAR);

// Establecer la URL de redireccionamiento después de la autorización
$redirect_uri = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];
$client->setRedirectUri($redirect_uri);

// Si se recibió un código de autorización, intercambia el código por un token de acceso
if (isset($_GET['code'])) {
    $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
    // Verifica si hay errores
    if (array_key_exists('error', $token)) {
        die('Error al intentar obtener el token de acceso: ' . $token['error']);
    }
}

// Si no hay un token de acceso, redirige al usuario a loguearse
if (!isset($token['access_token'])) {
    $authUrl = $client->createAuthUrl();
    header('Location: ' . $authUrl);
    exit;
}

// Crea una instancia del servicio de Google Calendar
$calendarService = new Google\Service\Calendar($client);

// Crear un nuevo evento para el 8 de julio
$fecha = new DateTime('2024-07-08');

$newEvent = new Event([
    'summary' => 'Nuevo evento',
    'start' => ['dateTime' => $fecha->format('Y-m-d') . 'T18:00:00', 'timeZone' => 'Europe/Madrid'],
    'end' => ['dateTime' => $fecha->format('Y-m-d') . 'T22:00:00', 'timeZone' => 'Europe/Madrid'],
]);

// Inserta el evento en el calendario del usuario
$calendarId = 'primary';
$createdEvent = $calendarService->events->insert($calendarId, $newEvent);

// Mostrar el ID del evento creado
echo 'Evento creado: ' . $createdEvent->getId();

// Hacer llamadas a la API de Google Calendar
$events = $calendarService->events->listEvents('primary');

// Almacena los eventos
$eventos = [];
foreach ($events->getItems() as $event) {
    $eventos[] = [
        'summary' => $event->getSummary(),
        'start' => $event->getStart()->dateTime,
        'end' => $event->getEnd()->dateTime
    ];
}

// Devuelve los eventos como JSON
header('Content-Type: application/json');
echo json_encode($eventos);
