<?php
include "access_control.php";
include "MySql_php.php";

header('Content-Type: application/json');

// Bloquear salida de warnings para no romper JSON
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);

// Validar acceso para Admin (sin redirección)
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'Admin') {
    echo json_encode(['error' => 'Acceso denegado']);
    exit;
}

// Validar parámetros
if (!isset($_GET['id']) || !is_numeric($_GET['id']) || !isset($_GET['accion'])) {
    echo json_encode(['error' => 'Parámetros inválidos']);
    exit;
}

$validacion_id = intval($_GET['id']);
$accion = $_GET['accion']; // 'Approved' o 'Rejected'
$usuario_id = $_SESSION['usuario_id'];
$fecha = date('Y-m-d H:i:s');

// Obtener ruta del archivo si existe
$res = $conn->query("SELECT documen FROM student_validations WHERE id=$validacion_id");
if ($res->num_rows == 0) {
    echo json_encode(['error' => 'Validación no encontrada']);
    exit;
}
$row = $res->fetch_assoc();
$rutaArchivo = $row['documen'];

if ($accion === 'Approved') {
    // Cambiar estado a Approved
    $stmt = $conn->prepare("UPDATE student_validations SET status='Approved', validated_by=?, validated_at=? WHERE id=?");
    $stmt->bind_param("isi", $usuario_id, $fecha, $validacion_id);
    if ($stmt->execute()) {
        echo json_encode(['success' => 'Documento aprobado']);
    } else {
        echo json_encode(['error' => 'Error al actualizar BD: ' . $conn->error]);
    }
} elseif ($accion === 'Rejected') {
    // Eliminar archivo del servidor si existe
    if (!empty($rutaArchivo) && file_exists(__DIR__ . "/../$rutaArchivo")) {
        unlink(__DIR__ . "/../$rutaArchivo");
    }
    // Cambiar estado a Pending y limpiar documen
    $stmt = $conn->prepare("UPDATE student_validations SET status='Pending', documen='', validated_by=?, validated_at=? WHERE id=?");
    $stmt->bind_param("isi", $usuario_id, $fecha, $validacion_id);
    if ($stmt->execute()) {
        echo json_encode(['success' => 'Documento rechazado y eliminado']);
    } else {
        echo json_encode(['error' => 'Error al actualizar BD: ' . $conn->error]);
    }
} else {
    echo json_encode(['error' => 'Acción no válida']);
}
?>

