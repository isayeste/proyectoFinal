<?php

    function guardarInicioFin($entrada, $salida, $fechaActual, $duracionCita, $duracion){
        $inicio = strtotime(date('Y-m-d', $fechaActual) . ' ' . $entrada);
        $fin = strtotime(date('Y-m-d', $fechaActual) . ' ' . $salida);
        // echo date("Y-m-d H:i:s", $inicio). "<br>";
        // echo date("Y-m-d H:i:s", $fin). "<br>";
        // echo "<hr>";
        while($inicio<=$fin){
            $inicioCita = $inicio;
            $finCita = strtotime("+" . $duracionCita . " minutes", $inicioCita);
            $fechaInicio = date("Y-m-d H:i:s", $inicioCita);
            $fechaFin = date("Y-m-d H:i:s", $finCita);
            // echo $duracionCita. "<br>";
            // echo date("Y-m-d H:i:s", $inicioCita). "<br>";
            // echo date("Y-m-d H:i:s", $finCita). "<br><hr>";
            $inicio =  strtotime("+" . $duracion . " minutes", $inicio);
            // --------------------------------------------------------------------------------
            //Conexión con la base de datos
            $servidor = 'localhost';
            $usuario = 'root';
            $password = "";
            $nombreBD = 'psyconnect';

            try{
                $conexion = new PDO("mysql:host=$servidor;dbname=$nombreBD", $usuario, $password);
                $sql = "INSERT INTO horarios (fechaInicio, fechaFin, estado) VALUES (?, ?, ?)";
                //Preparar la consulta y devuelve un  objeto PDOStatement. execute ejecuta la consulta
                $stmt = $conexion->prepare($sql);
                $stmt->execute([$fechaInicio, $fechaFin, "libre"]);
            
            
            }
            catch(PDOException $e){
                echo "Error al insertar los datos ". $e ->getMessage();
            }
            finally {
            // Cerrar la conexión
                $conexion = null;
            }
        }
    }

    //Datos del formulario
    $dias = $_POST["dias"];
    $entradaManana = $_POST["entradaManana"];
    $salidaManana = $_POST["salidaManana"];
    $entradaTarde = $_POST["entradaTarde"];
    $salidaTarde = $_POST["salidaTarde"];
    $entradaNoche = $_POST["entradaNoche"];
    $salidaNoche = $_POST["salidaNoche"];
    $duracion = $_POST["duracion"];
    $duracionCita =$duracion -1;

    // echo  $duracion;

    $diasInt = [];
    foreach($dias as $dia){
        $diaInt = (int)$dia;
        array_push($diasInt, $diaInt);
    }

    $fechaActual = time();

    $fechaFinTrimestre = strtotime("+3 months", $fechaActual);


    

    //Iterar sobre cada día entre la fecha actual y la fecha final del trimestre
    while($fechaActual<=$fechaFinTrimestre){
        $diaSemanaFechaActual = date('N', $fechaActual);
        if(in_array($diaSemanaFechaActual, $diasInt)){
            if($entradaManana !=null && $salidaManana!=null){
                guardarInicioFin($entradaManana, $salidaManana, $fechaActual, $duracionCita, $duracion);
            }
            else if($entradaTarde !=null && $salidaTarde!=null){
                guardarInicioFin($entradaTarde, $salidaTarde, $fechaActual, $duracionCita, $duracion);
            }
            else if($entradaNoche !=null && $salidaNoche!=null){
                guardarInicioFin($entradaNoche, $salidaNoche, $fechaActual, $duracionCita, $duracion);
            }
            
        }

        $fechaActual = strtotime("+1 day", $fechaActual);

    }

    
    
    
?>