<?php
include "MySql_php.php";

// Verificar que llega la validación
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("ID de validación no recibido.");
}
$validacion_id = intval($_GET['id']);

// Verificar archivo
if (!isset($_FILES['archivo'])) {
    die("No se recibió archivo.");
}

$archivo = $_FILES['archivo'];

// Validar PDF
if ($archivo['type'] !== 'application/pdf') {
    die("Solo se permiten archivos PDF.");
}

// Carpeta de guardado
$carpeta = __DIR__ . "/../uploads/validaciones/";

if (!is_dir($carpeta)) mkdir($carpeta, 0777, true);

// Nombre único
$nombre = time() . "_" . basename($archivo['name']);
$rutaCompleta = $carpeta . $nombre;

// Ruta que se guarda en la base de datos (relativa)
$rutaBD = "uploads/validaciones/" . $nombre;

// Mover archivo
if (!move_uploaded_file($archivo['tmp_name'], $rutaCompleta)) {
    die("Error al guardar el archivo.");
}

// Guardar en BD y poner status a 'Submitted'
$sql = "
    UPDATE student_validations
    SET documen = ?, status = 'Submitted'
    WHERE id = ?
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("si", $rutaBD, $validacion_id);

if ($stmt->execute()) {
    echo "Archivo guardado correctamente.";
} else {
    echo "Error al actualizar BD: " . $conn->error;
}
