<?php
session_start();
include "MySql_php.php";

// 1. Validar método POST
if ($_SERVER["REQUEST_METHOD"] != "POST") {
    header("Location: index.php");
    exit();
}

// 2. Recibir datos
$rol = $_POST['roles'] ?? null;
$usuario = $_POST['usuario'] ?? null;
$contraseña = $_POST['contraseña'] ?? null;

$errores = [];

// 3. Validar campos vacíos
if (empty($usuario) || empty($contraseña) || empty($rol)) {
    $errores["input"] = "Por favor complete todos los campos antes de continuar.";
}

// 4. Si no hay errores de campos vacíos → buscar en BD
if (empty($errores)) {

    $sql = "
        SELECT 
            u.id,
            u.username,
            u.people_id,

            ur.password,
            ur.user_status,
            ur.token,

            r.name AS rol,
            r.is_active AS rol_activo
        FROM users u
        JOIN user_roles ur ON u.id = ur.users_id
        JOIN roles r ON ur.roles_id = r.id
        WHERE u.username = ?
        AND r.name = ?
        LIMIT 1
    ";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $usuario, $rol);
    $stmt->execute();
    $resultado = $stmt->get_result();

    // Si no encontró usuario + rol juntos
    if ($resultado->num_rows == 0) {
        $errores["login"] = "Usuario no encontrado. Por favor inténtelo nuevamente.";
    } else {
        $fila = $resultado->fetch_assoc();

        // Verificar si el rol está desactivado
        if ($fila["rol_activo"] != 1) {
            $errores["login"] = "El rol está desactivado.";
        }
        else if ($fila["user_status"] == "Bloqueado") {
            $errores["login"] = "Este rol está bloqueado. Comuníquese con el área de sistemas para más información.";
        }
        // Verificar si el user_role está desactivado
        else if ($fila["user_status"] !== "Active") {
            $errores["login"] = "Su rol asignado está desactivado.";
        }
        else {
            // Verificar contraseña
            if (!password_verify($contraseña, $fila["password"])) {

                // 1. Aumentar token SOLO en el user_role correspondiente
                $sqlToken = "
                    UPDATE user_roles
                    SET token = token + 1
                    WHERE users_id = ?
                    AND roles_id = (SELECT id FROM roles WHERE name = ? LIMIT 1)
                ";
                $stmtToken = $conn->prepare($sqlToken);
                $stmtToken->bind_param("is", $fila["id"], $rol);
                $stmtToken->execute();

                // 2. Revisar cuántos intentos lleva
                $sqlCheck = "
                    SELECT token
                    FROM user_roles
                    WHERE users_id = ?
                    AND roles_id = (SELECT id FROM roles WHERE name = ? LIMIT 1)
                    LIMIT 1
                ";
                $stmtCheck = $conn->prepare($sqlCheck);
                $stmtCheck->bind_param("is", $fila["id"], $rol);
                $stmtCheck->execute();
                $resCheck = $stmtCheck->get_result()->fetch_assoc();

                // 3. Si llegó a 5 → bloquear rol
                if ($resCheck["token"] >= 5) {
                    $sqlBloquear = "
                        UPDATE user_roles
                        SET user_status = 'Bloqueado'
                        WHERE users_id = ?
                        AND roles_id = (SELECT id FROM roles WHERE name = ? LIMIT 1)
                    ";
                    $stmtBloquear = $conn->prepare($sqlBloquear);
                    $stmtBloquear->bind_param("is", $fila["id"], $rol);
                    $stmtBloquear->execute();

                    $errores["login"] = "Su rol ha sido bloqueado por demasiados intentos fallidos.";
                } else {
                    $errores["login"] = "Contraseña incorrecta.";
                }

            } else {
                // Contraseña correcta → resetear token solo en este user_role
                $sqlReset = "
                    UPDATE user_roles
                    SET token = 0
                    WHERE users_id = ?
                    AND roles_id = (SELECT id FROM roles WHERE name = ? LIMIT 1)
                ";
                $stmtReset = $conn->prepare($sqlReset);
                $stmtReset->bind_param("is", $fila["id"], $rol);
                $stmtReset->execute();
            }

        }       
    }
}

// 5. Si hay errores → redirigir con sesión
if (!empty($errores)) {
    $_SESSION["errores"] = $errores;
    header("Location: index.php");
    exit;
}

// 6. Si todo está bien → establecer sesión
$_SESSION["people_id"] = $fila["people_id"];
$_SESSION["usuario_id"] = $fila["id"];
$_SESSION["rol"] = $fila["rol"];

// Cargar nombre
$idPersona = intval($fila["people_id"]);
$sqlNombre = "SELECT CONCAT_WS(' ',first_name, second_name) AS User_Name FROM people WHERE id = $idPersona LIMIT 1";
$resNombre = $conn->query($sqlNombre);

if ($resNombre && $resNombre->num_rows > 0) {
    $rowNombre = $resNombre->fetch_assoc();
    $_SESSION["Nombre"] = $rowNombre["User_Name"];
}

switch ($_SESSION["rol"]) {
    case "Admin":
        header("Location: Modulo_Diplomas_Admin.php");
        exit;
    case "Student":
        header("Location: Validacion_Academica_Estudiante.php");
        exit;
}

?>
