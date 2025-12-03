<?php
session_start();
include "MySql_php.php";

// Helper: intentar preparar una sentencia sin lanzar excepción
function try_prepare($conn, $sql) {
    try {
        return $conn->prepare($sql);
    } catch (mysqli_sql_exception $e) {
        return false;
    }
}

if (!isset($_POST['persona_id'])) {
    die("Error: No se recibió el ID de la persona.");
}

$persona_id = intval($_POST['persona_id']);

// Datos editables
$document_type_id = isset($_POST['document_type_id']) ? intval($_POST['document_type_id']) : null;
$document_id      = isset($_POST['document_id']) ? trim($_POST['document_id']) : null;
$email1           = isset($_POST['email_primary']) ? trim($_POST['email_primary']) : null;
$email2           = isset($_POST['email_secondary']) ? trim($_POST['email_secondary']) : null;
$direccion        = isset($_POST['address']) ? trim($_POST['address']) : null;

// Teléfonos (los campos vienen desde inputs hidden o inputs normales)
$telefono_principal  = isset($_POST['telefono_principal'])  ? trim($_POST['telefono_principal'])  : "";
$telefono_secundario = isset($_POST['telefono_secundario']) ? trim($_POST['telefono_secundario']) : "";

// VALIDACIONES BÁSICAS
if (!$document_type_id || !$document_id || !$email1) {
    $_SESSION['old_persona_edit'] = $_POST;
    $_SESSION['form_error'] = ['msg' => 'campos_vacios', 'type' => 'error'];
    header("Location: Persona_Editar_Guardar.php?id={$persona_id}");
    exit;
}

if (!ctype_digit($document_id) || strlen($document_id) < 5 || strlen($document_id) > 12) {
    $_SESSION['old_persona_edit'] = $_POST;
    $_SESSION['form_error'] = ['msg' => 'documento_invalido', 'type' => 'error'];
    header("Location: Persona_Editar_Guardar.php?id={$persona_id}");
    exit;
}

if (!filter_var($email1, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['old_persona_edit'] = $_POST;
    $_SESSION['form_error'] = ['msg' => 'email_principal_invalido', 'type' => 'error'];
    header("Location: Persona_Editar_Guardar.php?id={$persona_id}");
    exit;
}

if ($email2 && !filter_var($email2, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['old_persona_edit'] = $_POST;
    $_SESSION['form_error'] = ['msg' => 'email_secundario_invalido', 'type' => 'error'];
    header("Location: Persona_Editar_Guardar.php?id={$persona_id}");
    exit;
}

// COMPROBAR UNICIDAD EXCLUYENDO LA PROPIA PERSONA
$chk = $conn->prepare("SELECT id FROM people WHERE document_id = ? AND id != ?");
$chk->bind_param("si", $document_id, $persona_id);
$chk->execute();
$chk->store_result();
if ($chk->num_rows > 0) {
    $_SESSION['old_persona_edit'] = $_POST;
    $_SESSION['form_error'] = ['msg' => 'documento_duplicado', 'type' => 'error'];
    header("Location: Persona_Editar_Guardar.php?id={$persona_id}");
    exit;
}

// COMPROBAR UNICIDAD EN LA TABLA DE USUARIOS (si existe otro usuario con ese nombre de usuario/documento)
$chkU = false;

// Intentar diferentes esquemas/nombres de tabla/columnas
$queries = [
    // esquema en español (USUARIOS)
    "SELECT Id FROM USUARIOS WHERE Usuario_Nombre = ? AND Persona_id != ? LIMIT 1",
    // esquema en inglés (users)
    "SELECT id FROM users WHERE username = ? AND people_id != ? LIMIT 1",
    // otra variante: users with person_id
    "SELECT id FROM users WHERE username = ? AND person_id != ? LIMIT 1",
];

foreach ($queries as $q) {
    $stmt = try_prepare($conn, $q);
    if ($stmt) { $chkU = $stmt; break; }
}

if ($chkU) {
    $chkU->bind_param("si", $document_id, $persona_id);
    $chkU->execute();
    $chkU->store_result();
    if ($chkU->num_rows > 0) {
        $_SESSION['old_persona_edit'] = $_POST;
        $_SESSION['form_error'] = ['msg' => 'usuario_duplicado', 'type' => 'error'];
        header("Location: Persona_Editar_Guardar.php?id={$persona_id}");
        exit;
    }
}

$chk = $conn->prepare("SELECT id FROM people WHERE email_primary = ? AND id != ?");
$chk->bind_param("si", $email1, $persona_id);
$chk->execute();
$chk->store_result();
if ($chk->num_rows > 0) {
    $_SESSION['old_persona_edit'] = $_POST;
    $_SESSION['form_error'] = ['msg' => 'email_principal_duplicado', 'type' => 'error'];
    header("Location: Persona_Editar_Guardar.php?id={$persona_id}");
    exit;
}

if ($email2) {
    $chk = $conn->prepare("SELECT id FROM people WHERE email_secondary = ? AND id != ?");
    $chk->bind_param("si", $email2, $persona_id);
    $chk->execute();
    $chk->store_result();
    if ($chk->num_rows > 0) {
        $_SESSION['old_persona_edit'] = $_POST;
        $_SESSION['form_error'] = ['msg' => 'email_secundario_duplicado', 'type' => 'error'];
        header("Location: Persona_Editar_Guardar.php?id={$persona_id}");
        exit;
    }
}

/* ======================================================
   ACTUALIZAR DATOS PRINCIPALES DE LA PERSONA
====================================================== */

$sql = "UPDATE people SET 
            document_type_id = ?, 
            document_id = ?, 
            email_primary = ?, 
            email_secondary = ?, 
            address = ?
        WHERE id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param(
    "issssi",
    $document_type_id,
    $document_id,
    $email1,
    $email2,
    $direccion,
    $persona_id
);
if (!$stmt->execute()) {
    $_SESSION['old_persona_edit'] = $_POST;
    $_SESSION['form_error'] = ['msg' => 'error_sql', 'type' => 'error'];
    header("Location: Persona_Editar_Guardar.php?id={$persona_id}");
    exit;
}

/* ======================================================
   GUARDAR TELÉFONO PRINCIPAL (priority = 1)
====================================================== */

// TELEFONO PRINCIPAL (priority = 1)
// Si viene vacío -> eliminar registro existente. Si viene con valor -> insertar o actualizar.
$sql = "SELECT id FROM phones WHERE people_id=? AND priority=1";
$pstmt = $conn->prepare($sql);
$pstmt->bind_param("i", $persona_id);
$pstmt->execute();
$pstmt->store_result();
$exists = $pstmt->num_rows;

if (!empty($telefono_principal)) {
    if ($exists > 0) {
        $sql2 = "UPDATE phones SET phone_number=? WHERE people_id=? AND priority=1";
        $stmt2 = $conn->prepare($sql2);
        $stmt2->bind_param("si", $telefono_principal, $persona_id);
        $stmt2->execute();
    } else {
        $sql2 = "INSERT INTO phones (phone_number, people_id, priority) VALUES (?, ?, 1)";
        $stmt2 = $conn->prepare($sql2);
        $stmt2->bind_param("si", $telefono_principal, $persona_id);
        $stmt2->execute();
    }
} else {
    if ($exists > 0) {
        $del = $conn->prepare("DELETE FROM phones WHERE people_id=? AND priority=1");
        $del->bind_param("i", $persona_id);
        $del->execute();
    }
}

/* ======================================================
   GUARDAR TELÉFONO SECUNDARIO (priority = 0)
====================================================== */

// TELEFONO SECUNDARIO (priority = 0)
$sql = "SELECT id FROM phones WHERE people_id=? AND priority=0";
$pstmt = $conn->prepare($sql);
$pstmt->bind_param("i", $persona_id);
$pstmt->execute();
$pstmt->store_result();
$exists2 = $pstmt->num_rows;

if (!empty($telefono_secundario)) {
    if ($exists2 > 0) {
        $sql2 = "UPDATE phones SET phone_number=? WHERE people_id=? AND priority=0";
        $stmt2 = $conn->prepare($sql2);
        $stmt2->bind_param("si", $telefono_secundario, $persona_id);
        $stmt2->execute();
    } else {
        $sql2 = "INSERT INTO phones (phone_number, people_id, priority) VALUES (?, ?, 0)";
        $stmt2 = $conn->prepare($sql2);
        $stmt2->bind_param("si", $telefono_secundario, $persona_id);
        $stmt2->execute();
    }
} else {
    if ($exists2 > 0) {
        $del = $conn->prepare("DELETE FROM phones WHERE people_id=? AND priority=0");
        $del->bind_param("i", $persona_id);
        $del->execute();
    }
}
/* ======================================================
   REDIRECCIÓN
====================================================== */

header("Location: Administración_Ver_Usuarios.php?id=" . $persona_id . "&msg=actualizado&type=success");
exit;
?>

