<?php
session_start();

if (!isset($_SESSION['access_token'])) {
    header('Location: ../index.html');
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../css/index.css">
</head>
<body>
<script>
        // Función para obtener el valor de un parámetro GET por su nombre
        function getQueryParam(name) {
            const urlParams = new URLSearchParams(window.location.search);
            return urlParams.get(name);
        }

        // Obtener el token de acceso de la URL
        const token = getQueryParam('token');
        if (token) {
            // Guardar el token de acceso en localStorage
            localStorage.setItem('googleAccessToken', token);
        }
    </script>
    <div class="cont">
        <div class="contenedor psi">
            <div class="superior">
                <a href="../index.html">
                    <div class="contSup">
                        <img src="../imagenes/logoFiloBlanco-removebg-preview.png" alt="logo">
                    </div>
                </a>
            </div>
            <div class="contenidoPsi">
                <div class="interiorContenidoPsi">
                    <a href="listadoCitas.php">
                        <div class="enlace">
                            <p>Listado de Citas</p>
                        </div>
                    </a>
                    <a href="formularioHorario.html">
                        <div class="enlace">
                            <p>Insertar Horario Trimestral</p>
                        </div>
                    </a>
                    <a href="formularioDiasNoLaborales.html">
                        <div class="enlace">
                            <p>Insertar Días No Laborables</p>
                        </div>
                    </a>
                </div>
            </div>
        </div>
        <footer>
            <p>PsyConnect © 2024. Desarrollado por Isabel Apolonia Yeste Sánchez.</p>
        </footer>
    </div>
</body>
</html>
