<?php
session_start();
include "MySql_php.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // --- Datos PERSONA ---
    $document_type    = isset($_POST["document_type_id"]) ? intval($_POST["document_type_id"]) : null;
    $document_id      = trim($_POST["document_id"] ?? '');
    $first_name       = trim($_POST["first_name"] ?? '');
    $second_name      = trim($_POST["second_name"] ?? '');
    $last_name        = trim($_POST["last_name"] ?? '');
    $second_last_name = trim($_POST["second_last_name"] ?? '');
    $email_primary    = trim($_POST["email_primary"] ?? '');
    $email_secondary  = trim($_POST["email_secondary"] ?? '');
    $address          = trim($_POST["address"] ?? '');

    $second_name     = $second_name === '' ? null : $second_name;
    $email_secondary = $email_secondary === '' ? null : $email_secondary;
    $address         = $address === '' ? null : $address;

    // --- Teléfonos ---
    $telefono_principal  = isset($_POST["telefono_principal"])  ? trim($_POST["telefono_principal"]) : null;
    $telefono_secundario = isset($_POST["telefono_secundario"]) ? trim($_POST["telefono_secundario"]) : null;


    /* ==========================================================
       VALIDACIONES
    ========================================================== */

    // Validar campos obligatorios
    if (!$document_type || !$document_id || !$first_name || !$last_name || !$second_last_name || !$email_primary) {
        $_SESSION['form_error'] = ['msg' => 'campos_vacios', 'type' => 'error'];
        $_SESSION['old_persona'] = $_POST;
        header("Location: Persona_Crear.php");
        exit;
    }

    // Documento inválido
    if (strlen($document_id) < 5 || strlen($document_id) > 12 || !ctype_digit($document_id)) {
        $_SESSION['form_error'] = ['msg' => 'documento_invalido', 'type' => 'error'];
        $_SESSION['old_persona'] = $_POST;
        header("Location: Persona_Crear.php");
        exit;
    }

    // Email principal inválido
    if (!filter_var($email_primary, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['form_error'] = ['msg' => 'email_principal_invalido', 'type' => 'error'];
        $_SESSION['old_persona'] = $_POST;
        header("Location: Persona_Crear.php");
        exit;
    }

    // Email secundario inválido
    if ($email_secondary && !filter_var($email_secondary, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['form_error'] = ['msg' => 'email_secundario_invalido', 'type' => 'error'];
        $_SESSION['old_persona'] = $_POST;
        header("Location: Persona_Crear.php");
        exit;
    }

    // Teléfono principal requerido
    if (!$telefono_principal) {
        $_SESSION['form_error'] = ['msg' => 'telefono_principal_vacio', 'type' => 'error'];
        $_SESSION['old_persona'] = $_POST;
        header("Location: Persona_Crear.php");
        exit;
    }

    // Documento duplicado
    $stmt = $conn->prepare("SELECT id FROM people WHERE document_id = ? LIMIT 1");
    $stmt->bind_param("s", $document_id);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        $_SESSION['form_error'] = ['msg' => 'documento_duplicado', 'type' => 'error'];
        $_SESSION['old_persona'] = $_POST;
        header("Location: Persona_Crear.php");
        exit;
    }

    // Email principal duplicado
    $stmt = $conn->prepare("SELECT id FROM people WHERE email_primary = ? LIMIT 1");
    $stmt->bind_param("s", $email_primary);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        $_SESSION['form_error'] = ['msg' => 'email_principal_duplicado', 'type' => 'error'];
        $_SESSION['old_persona'] = $_POST;
        header("Location: Persona_Crear.php");
        exit;
    }

    // Email secundario duplicado
    if ($email_secondary) {
        $stmt = $conn->prepare("SELECT id FROM people WHERE email_secondary = ? LIMIT 1");
        $stmt->bind_param("s", $email_secondary);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $_SESSION['form_error'] = ['msg' => 'email_secundario_duplicado', 'type' => 'error'];
            $_SESSION['old_persona'] = $_POST;
            header("Location: Persona_Crear.php");
            exit;
        }
    }

    // Usuario duplicado (username = documento)
    $username = $document_id;
    $chkUser = $conn->prepare("SELECT Id FROM USUARIOS WHERE Usuario_Nombre = ? LIMIT 1");
    $chkUser->bind_param("s", $username);
    $chkUser->execute();
    $chkUser->store_result();

    if ($chkUser->num_rows > 0) {
        $_SESSION['form_error'] = ['msg' => 'usuario_duplicado', 'type' => 'error'];
        $_SESSION['old_persona'] = $_POST;
        header("Location: Persona_Crear.php");
        exit;
    }


    /* ==========================================================
       INSERTAR EN PEOPLE
    ========================================================== */

    $sql = "INSERT INTO people (
                document_type_id, document_id, first_name, second_name,
                last_name, second_last_name, email_primary, email_secondary, address
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param(
        "issssssss",
        $document_type,
        $document_id,
        $first_name,
        $second_name,
        $last_name,
        $second_last_name,
        $email_primary,
        $email_secondary,
        $address
    );

    if (!$stmt->execute()) {
        $_SESSION['form_error'] = ['msg' => 'error_sql', 'type' => 'error'];
        $_SESSION['old_persona'] = $_POST;
        header("Location: Persona_Crear.php");
        exit;
    }

    $person_id = $conn->insert_id;

    // Insertar teléfonos
    if ($telefono_principal) {
        $sqlTel = "INSERT INTO phones (people_id, phone_number, priority) VALUES (?, ?, 1)";
        $stmtTel = $conn->prepare($sqlTel);
        $stmtTel->bind_param("is", $person_id, $telefono_principal);
        $stmtTel->execute();
    }

    if ($telefono_secundario) {
        $sqlTel2 = "INSERT INTO phones (people_id, phone_number, priority) VALUES (?, ?, 0)";
        $stmtTel2 = $conn->prepare($sqlTel2);
        $stmtTel2->bind_param("is", $person_id, $telefono_secundario);
        $stmtTel2->execute();
    }


    /* ==========================================================
       CREAR USUARIO
    ========================================================== */

    try {
        $token = null;
        $fechaExp = date("Y-m-d H:i:s", strtotime("+1 year"));

        // Insert en USUARIOS
        $insUserSql = "INSERT INTO USUARIOS (Persona_id, Usuario_Nombre, Token, Fecha_Expiracion, Usado, Fecha_creacion)
                       VALUES (?, ?, ?, ?, 0, NOW())";

        $insUser = $conn->prepare($insUserSql);
        $insUser->bind_param("isss", $person_id, $username, $token, $fechaExp);
        $insUser->execute();

        $usuario_id = $conn->insert_id;

        // Insert password hash
        $passwordHash = password_hash($document_id, PASSWORD_DEFAULT);

        $insPassSql = "INSERT INTO RECUPEACIONES_PASSWORD (Usuario_id, Nombre_Usuario, Email, pasword_hash, Fecha_creacion)
                       VALUES (?, ?, ?, ?, NOW())";

        $insPass = $conn->prepare($insPassSql);
        $insPass->bind_param("isss", $usuario_id, $username, $email_primary, $passwordHash);
        $insPass->execute();

        // Asignar rol Visitante si existe
        $roleName = "Visitante";
        $roleId = null;

        $rStmt = $conn->prepare("SELECT Id FROM ROLES WHERE LOWER(Nombre) = LOWER(?) LIMIT 1");
        $rStmt->bind_param("s", $roleName);
        $rStmt->execute();
        $res = $rStmt->get_result();
        if ($res && $r = $res->fetch_assoc()) {
            $roleId = $r["Id"];
        }

        if ($roleId) {
            $upd = $conn->prepare("UPDATE people SET Rol_id = ? WHERE id = ?");
            $upd->bind_param("ii", $roleId, $person_id);
            $upd->execute();
        }

    } catch (Exception $e) {
        // evitar romper flujo
    }


    /* ==========================================================
       TODO OK → REDIRIGIR
    ========================================================== */

    header("Location: Personas.php?msg=creado&type=success");
    exit;
}
?>
