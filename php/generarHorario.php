<?php

function guardarInicioFin($entrada, $salida, $fechaActual, $duracionCita, $duracion, $servidor, $usuario, $password, $nombreBD) {
    $inicio = strtotime(date('Y-m-d', $fechaActual) . ' ' . $entrada);
    $fin = strtotime(date('Y-m-d', $fechaActual) . ' ' . $salida);

    while ($inicio <= $fin) {
        $inicioCita = $inicio;
        $finCita = strtotime("+" . $duracionCita . " minutes", $inicioCita);
        $fechaInicio = date("Y-m-d H:i:s", $inicioCita);
        $fechaFin = date("Y-m-d H:i:s", $finCita);
        $inicio = strtotime("+" . $duracion . " minutes", $inicio);

        // Conexión con la base de datos
        try {
            $conexion = new PDO("mysql:host=$servidor;dbname=$nombreBD", $usuario, $password);
            $sql = "INSERT INTO horarios (fechaInicio, fechaFin, estado) VALUES (?, ?, ?)";
            $stmt = $conexion->prepare($sql);
            $stmt->execute([$fechaInicio, $fechaFin, "libre"]);
        } catch (PDOException $e) {
            echo "Error al insertar los datos " . $e->getMessage();
        } finally {
            $conexion = null;
        }
    }
}

// Datos del formulario
$dias = $_POST["dias"];
$entradaManana = $_POST["entradaManana"];
$salidaManana = $_POST["salidaManana"];
$entradaTarde = $_POST["entradaTarde"];
$salidaTarde = $_POST["salidaTarde"];
$entradaNoche = $_POST["entradaNoche"];
$salidaNoche = $_POST["salidaNoche"];
$duracion = $_POST["duracion"];
$duracionCita = $duracion - 1;

$diasInt = [];
foreach ($dias as $dia) {
    $diaInt = (int)$dia;
    array_push($diasInt, $diaInt);
}

$fechaActual = time();
$fechaFinTrimestre = strtotime("+3 months", $fechaActual);

// Datos de la base de datos
$servidor = 'localhost';
$usuario = 'root';
$password = '';
$nombreBD = 'psyconnect';

// Iterar sobre cada día entre la fecha actual y la fecha final del trimestre
while ($fechaActual <= $fechaFinTrimestre) {
    $diaSemanaFechaActual = date('N', $fechaActual);
    if (in_array($diaSemanaFechaActual, $diasInt)) {
        if ($entradaManana != null && $salidaManana != null) {
            guardarInicioFin($entradaManana, $salidaManana, $fechaActual, $duracionCita, $duracion, $servidor, $usuario, $password, $nombreBD);
        }
        if ($entradaTarde != null && $salidaTarde != null) {
            guardarInicioFin($entradaTarde, $salidaTarde, $fechaActual, $duracionCita, $duracion, $servidor, $usuario, $password, $nombreBD);
        }
        if ($entradaNoche != null && $salidaNoche != null) {
            guardarInicioFin($entradaNoche, $salidaNoche, $fechaActual, $duracionCita, $duracion, $servidor, $usuario, $password, $nombreBD);
        }
    }
    $fechaActual = strtotime("+1 day", $fechaActual);
}

// Código para actualizar el archivo JSON
try {
    $conexion = new PDO("mysql:host=$servidor;dbname=$nombreBD", $usuario, $password);
    $sql = "SELECT idHorario, fechaInicio, fechaFin, estado FROM horarios";
    $stmt = $conexion->prepare($sql);
    $stmt->execute();
    // Obtener las fechas como un array
    $fechas = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $jsonFechas = json_encode($fechas);
    
    // Guardar las fechas en formato JSON
    $fichero = "../js/lecturaHorario.json";
    file_put_contents($fichero, $jsonFechas);
    echo "Horarios actualizados correctamente";
} catch (PDOException $e) {
    echo "Error al leer los datos " . $e->getMessage();
} finally {
    $conexion = null;
}

//header("Location: ../html/inicioPsicologo.html");
exit;

?>
