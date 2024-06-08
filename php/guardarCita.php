<?php
$json = file_get_contents('php://input');
$data = json_decode($json, true); 

//Datos del JSON
$idHorario = $data['idHorario'];
$motivo = $data['motivo'];
$via = $data['via'];
$emailPaciente = $data['emailPaciente'];

$servidor = 'localhost';
$usuario = 'root';
$password = "";
$nombreBD = 'psyconnect';

try {
    $conexion = new PDO("mysql:host=$servidor;dbname=$nombreBD", $usuario, $password);
    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $conexion->beginTransaction();

    $sqlCitas = "INSERT INTO citas (motivo, via, emailPaciente, idHorario) VALUES (?, ?, ?, ?)";
    $stmtCitas = $conexion->prepare($sqlCitas);
    $stmtCitas->execute([$motivo, $via, $emailPaciente, $idHorario]);

    $sqlHorarios = "UPDATE horarios SET estado = 'espera' WHERE idHorario = ?";
    $stmtHorarios = $conexion->prepare($sqlHorarios);
    $stmtHorarios->execute([$idHorario]);

    $conexion->commit();

    //Sobreescribir los json
    $sqlListadoCitas = "SELECT * FROM citas INNER JOIN horarios ON citas.idHorario = horarios.idHorario";
    $stmtListadoCitas = $conexion->query($sqlListadoCitas);
    $listadoCitas = $stmtListadoCitas->fetchAll(PDO::FETCH_ASSOC);

    file_put_contents('../js/listadoCitas.json', json_encode($listadoCitas));

    $sqlLecturaHorario = "SELECT * FROM horarios";
    $stmtLecturaHorario = $conexion->query($sqlLecturaHorario);
    $lecturaHorario = $stmtLecturaHorario->fetchAll(PDO::FETCH_ASSOC);

    file_put_contents('../js/lecturaHorario.json', json_encode($lecturaHorario));

    echo "Cita guardada con éxito";
} catch (PDOException $e) {
    $conexion->rollBack();
    echo "Error al insertar los datos: " . $e->getMessage();
} finally {
    $conexion = null;
}





?>
