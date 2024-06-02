<?php
// session_start();

// if (!isset($_SESSION['access_token'])) {
//     header('Location: ../index.html');
//     exit();
// }


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../css/index.css">
    <script src="../js/generarListadoCitas.js"></script>
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
                <!-- INSERTAR AQUÍ LA TABLA DE LAS CITAS CON LA OPCIÓN PARA ACEPTAR/CANCELAR -->
            </div>
        </div>
        <footer>
            <p>PsyConnect © 2024. Desarrollado por Isabel Apolonia Yeste Sánchez.</p>
        </footer>
    </div>
    <script src="../js/listadoCitas.js"></script>
</body>
</html>
