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
        <div class="contenedor inicioPaciente">
            <a href="../index.html">
                <div class="superior">
                    <div class="contSup">
                        <img src="../imagenes/logoFiloBlanco-removebg-preview.png" alt="logo">
                    </div> 
                </div>
            </a>

            <div class="contenedorMensaje">
                <?php
                    //hacer consulta de aquellas citas que tenga el paciente
                    if(isset($_GET['email'])) {
                        $emailPaciente = $_GET['email'];
                    
                        // Datos de conexión a la base de datos
                        $host = 'localhost';
                        $db = 'psyconnect';
                        $user = 'root';
                        $password = '';
                        $charset = 'utf8mb4';
                    
                        // DSN (Data Source Name) para la conexión a la base de datos
                        $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
                        $options = [
                            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                            PDO::ATTR_EMULATE_PREPARES => false,
                        ];
                    
                        try {
                            // Crear una instancia de PDO para la conexión
                            $pdo = new PDO($dsn, $user, $password, $options);
                        
                            // Consulta para obtener las citas del paciente con información de la tabla horarios
                            $sql = "SELECT c.idCita, c.via, h.fechaInicio, h.estado 
                                    FROM citas c
                                    JOIN horarios h ON c.idHorario = h.idHorario
                                    WHERE c.emailPaciente = :emailPaciente";
                        
                            // Preparar la consulta
                            $stmt = $pdo->prepare($sql);
                        
                            // Ejecutar la consulta con el correo electrónico del paciente como parámetro
                            $stmt->execute(['emailPaciente' => $emailPaciente]);
                        
                            // Obtener todas las citas del paciente con información de la tabla horarios como un array
                            $citas = $stmt->fetchAll();
                        
                            // Recorrer el array de citas
                            foreach ($citas as $cita) {
                                // Obtener la fecha de inicio y la vía de la cita
                                $fechaInicio = $cita['fechaInicio'];
                                $via = $cita['via'];
                                $fechaEvento = strstr($fechaInicio, ' ', true);
                                $horaInicio = strstr($fechaInicio, ' ');
                                $horaInicio = ltrim($horaInicio);
                        
                                // Verificar el valor del campo estado y mostrar el mensaje correspondiente
                                if ($cita['estado'] == 'espera') {
                                    echo "<p>Cita Pendiente de Confirmación. Fecha: ". $fechaEvento. ", hora: ". $horaInicio .";  Vía: " . $via ."</p>";
                                } elseif ($cita['estado'] == 'libre') {
                                    echo "<p>Cita Cancelada. Fecha: ". $fechaEvento. ", hora: ". $horaInicio .";  Vía: " .$via. ". Elija otra cita. Perdone por las molestias.</p>";
                                }
                            }
                        } catch (PDOException $e) {
                            // Manejar errores de conexión o consulta
                            echo 'Error: ' . $e->getMessage();
                        }
                    } else {
                        // Si no se proporciona el correo electrónico en la URL
                        echo 'Por favor proporciona el correo electrónico del paciente en la URL.';
                    }
                ?>
            </div>
            
            <div class="contenedorCalendario">
                
                <!-- Contenedor calendario -->
                <div class="h2">
                    <h2 id="nombreMes"></h2>
                </div>
                <table class="calendar" id="calendario"></table>
                <div id="botones" class="botones">
                    <div><button class="boton" id="anterior"></button></div>
                    <div class="final"><button class="boton" id="siguiente"></button></div>
                </div>
                <div id="horas" class="horas">
                    <h3 id="tituloHorariosDisponibles"></h3>
                    <div id="horasDisponibles" class="horasDisponibles"></div>
                </div>
            </div>
        </div>
        <footer>
            <p>PsyConnect © 2024. Desarrollado por Isabel Apolonia Yeste Sánchez.</p>
        </footer>
    </div>

    <!-- Modales -->
    <div id="myModal" class="modal">
        <div class="modal-content">
            <h3 id="modalText"></h3>
            <div class="formularioModal">
                <label for="motivoConsulta">Motivo de consulta:</label>
                <div>
                    <textarea id="motivoConsulta" rows="4" cols="50"></textarea>
                </div>
                <br>
                <label for="tipoConsulta">Tipo de consulta:</label><br>
                <div>
                    <input type="radio" id="consultaOnline" name="tipoConsulta" value="online">
                    <label for="consultaOnline">Online</label><br>
                    <input type="radio" id="consultaPresencial" name="tipoConsulta" value="presencial">
                    <label for="consultaPresencial">Presencial</label>
                </div>
                <br>
            </div>
            <div class="botonesModal">
                <button id="acceptButton">Aceptar</button>
                <button id="cancelButton">Cancelar</button>
            </div>
        </div>
    </div>
    <!--  -->
    <!-- <div id="myModal2" class="modal">
        <div class="modal-content">
            <h3 id="">Espera la confirmación de la cita. Será indicada en el inicio de esta misma página</h3>
            <button id="botonSalir">Salir</button>
        </div>
    </div> -->
    

    <script src="../js/calendar.js"></script>
</body>
</html>
