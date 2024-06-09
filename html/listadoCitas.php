<?php

// Conexión a la base de datos (reemplaza los valores con los de tu entorno)
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "psyconnect";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Consulta SQL para obtener la información de horarios en espera con detalles de cita y paciente
$sqlEspera = "SELECT h.idHorario, h.fechaInicio, h.fechaFin, p.nombre, p.emailPaciente, c.motivo, c.via 
              FROM horarios h
              JOIN citas c ON h.idHorario = c.idHorario
              JOIN pacientes p ON c.emailPaciente = p.emailPaciente
              WHERE h.estado = 'espera'";
$resultEspera = $conn->query($sqlEspera);

// Consulta SQL para obtener la información de horarios ocupados con detalles de cita y paciente
$sqlOcupado = "SELECT h.idHorario, h.fechaInicio, h.fechaFin, p.nombre, p.emailPaciente, c.motivo, c.via 
               FROM horarios h
               JOIN citas c ON h.idHorario = c.idHorario
               JOIN pacientes p ON c.emailPaciente = p.emailPaciente
               WHERE h.estado = 'ocupado'";
$resultOcupado = $conn->query($sqlOcupado);

// Función para generar la tabla de horarios
function generarTablaHorarios($result, $aceptadas = false) {
    $tabla = '<table class="tablaCitas">';
    $tabla .= '<thead><tr>';
    $encabezados = ['Fecha Inicio', 'Fecha Fin', 'Nombre', 'Email', 'Motivo Consulta', 'Vía', 'Acciones'];
    foreach ($encabezados as $encabezado) {
        $tabla .= '<th>' . $encabezado . '</th>';
    }
    $tabla .= '</tr></thead>';
    $tabla .= '<tbody>';

    while ($row = $result->fetch_assoc()) {
        $tabla .= '<tr>';
        $tabla .= '<td>' . $row['fechaInicio'] . '</td>';
        $tabla .= '<td>' . $row['fechaFin'] . '</td>';
        $tabla .= '<td>' . $row['nombre'] . '</td>';
        $tabla .= '<td>' . $row['emailPaciente'] . '</td>';
        $tabla .= '<td>' . $row['motivo'] . '</td>';
        $tabla .= '<td>' . $row['via'] . '</td>';
        $tabla .= '<td>';

        if (!$aceptadas) {
            $tabla .= '<button class="btnAceptar" data-idHorario="' . $row['idHorario'] . '">Aceptar</button>';
            $tabla .= '<button class="btnCancelarEspera" data-idHorario="' . $row['idHorario'] . '">Cancelar</button>';
        } else {
            $tabla .= '<button class="btnCancelarOcupado" data-idHorario="' . $row['idHorario'] . '">Cancelar</button>';
        }
        
        $tabla .= '</td>';
        $tabla .= '</tr>';
    }

    $tabla .= '</tbody>';
    $tabla .= '</table>';

    return $tabla;
}

// Generar tablas de horarios en espera y ocupados
$tablaEspera = generarTablaHorarios($resultEspera);
$tablaOcupado = generarTablaHorarios($resultOcupado, true);

// Cerrar conexión
$conn->close();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../css/index.css">
</head>
<body>
    <div class="cont">
        <div class="contenedor contenedorHorario">
            <div class="superior">
                <a href="../index.html">
                    <div class="contSup">
                        <img src="../imagenes/logoFiloBlanco-removebg-preview.png" alt="logo">
                    </div>
                </a>
            </div>
            <div class="contenidoListado contenidoF">
                <h2>Horarios en espera</h2>
                <?php echo $tablaEspera; ?>
            </div>
            <div class="contenidoListadoAceptadas">
                <h2>Horarios ocupados</h2>
                <?php echo $tablaOcupado; ?>
            </div>
        </div>
        <div>
            <button id="volverPagAntPsi">Volver</button>
        </div>
        <footer>
            <p>PsyConnect © 2024. Desarrollado por Isabel Apolonia Yeste Sánchez.</p>
        </footer>
    </div>
    <script src="../js/listadoCitas.js"></script>
    <script src="../js/botonVolverPagAntPsi.js"></script>
</body>
</html>
