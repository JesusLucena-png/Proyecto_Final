<?php
include "MySql_php.php";

$data = json_decode(file_get_contents("php://input"), true);

$matricula = intval($data["matricula"]);
$imagen = $data["imagen"];

if(!$matricula || !$imagen) die("Datos incompletos");

// Decodificar imagen
$imagen = str_replace("data:image/png;base64,", "", $imagen);
$imagen = base64_decode($imagen);

// Carpeta de diplomas
$dir = __DIR__ . "/../uploads/diplomas/";
if(!is_dir($dir)) mkdir($dir, 0777, true);

$filename = "diploma_" . $matricula . "_" . time() . ".png";
$path = $dir . $filename;

// Guardar la imagen
file_put_contents($path, $imagen);

// Crear ruta relativa web
$relativePath = "/uploads/diplomas/" . $filename;

// Insertar o actualizar diploma en la base de datos
$sqlCheck = "SELECT id FROM diplomas WHERE enrollment_id = $matricula";
$res = $conn->query($sqlCheck);

if($res->num_rows > 0){
    $row = $res->fetch_assoc();
    $sqlUpdate = "UPDATE diplomas 
                  SET certificate_file = '$relativePath', 
                      status = 'Expedido', 
                      issue_date = NOW() 
                  WHERE id = ".$row['id'];
    $conn->query($sqlUpdate);
} else {
    $sqlInsert = "INSERT INTO diplomas (enrollment_id, diploma_tammplates_id, certificate_file, status, issue_date)
                  VALUES ($matricula, 1, '$relativePath', 'Expedido', NOW())";
    $conn->query($sqlInsert);
}

// Devolver ruta relativa para abrir en navegador
echo $relativePath;
?>
