<?php
require_once '../vendor/autoload.php';

use Google\Client;
use Google\Service\Calendar;

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    $cita = $input['cita'];
    $token = $input['token'];

    $client = new Google_Client();
    $client->setAccessToken($token);

    $calendarService = new Google_Service_Calendar($client);

    $event = new Google_Service_Calendar_Event([
        'summary' => 'Cita con ' . $cita['nombre'],
        'start' => [
            'dateTime' => $cita['fechaInicio'],
            'timeZone' => 'Europe/Madrid',
        ],
        'end' => [
            'dateTime' => $cita['fechaFin'],
            'timeZone' => 'Europe/Madrid',
        ],
        'description' => $cita['motivo'],
        'attendees' => [['email' => $cita['emailPaciente']]],
        'reminders' => [
            'useDefault' => false,
            'overrides' => [
                ['method' => 'email', 'minutes' => 24 * 60],
                ['method' => 'popup', 'minutes' => 10],
            ],
        ],
    ]);

    try {
        $createdEvent = $calendarService->events->insert('primary', $event);
        echo json_encode(['success' => true, 'eventLink' => $createdEvent->htmlLink]);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid request method']);
}
?>
