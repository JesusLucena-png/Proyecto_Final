<?php
session_start();
include "MySql_php.php";
// 1. Validar sesión
if (!isset($_SESSION["usuario_id"])) {
    header("Location: index.php");
    exit;
}

// 2. CONSULTA DE TIPOS DE DOCUMENTO
$sqlTipos = "SELECT id, code FROM identity_types ORDER BY code ASC";
$tipos = $conn->query($sqlTipos);

// 3. Recuperar valores previos y errores
$old = $_SESSION["old_persona"] ?? [];
$formError = $_SESSION["form_error"] ?? null;

// 4. Función para recuperar valores del formulario
function old($key, $default = "") {
    global $old;
    return isset($old[$key]) ? htmlspecialchars($old[$key]) : $default;
}

$errores = [
    "campos_vacios"               => "Debes completar todos los campos obligatorios.",
    "documento_invalido"          => "El documento debe ser numérico y tener entre 5 y 12 dígitos.",
    "email_principal_invalido"    => "El correo principal no tiene un formato válido.",
    "email_secundario_invalido"   => "El correo secundario no tiene un formato válido.",
    "telefono_principal_vacio"    => "Debes ingresar un teléfono principal.",
    "documento_duplicado"         => "Ya existe una persona registrada con este número de documento.",
    "email_principal_duplicado"   => "Este correo principal ya está registrado.",
    "email_secundario_duplicado"  => "Este correo secundario ya está registrado.",
    "usuario_duplicado"           => "Ya existe un usuario con este mismo documento.",
    "error_sql"                   => "Error al guardar los datos. Inténtalo nuevamente.",
];

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Diplomas Litoral</title>

    <link rel="icon" type="image/svg+xml" href="../ICONS/Union (2).svg" sizes="32x32">

    <link rel="stylesheet" href="../CSS/Formularios.CSS">
    <link rel="stylesheet" href="../CSS/Haeder.CSS">

    <!-- SweetAlert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- intl-tel-input -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/css/intlTelInput.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/intlTelInput.min.js"></script>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>

<body>
<header class="top-header">
    <div class="Titulo-Haeder">
        <button id="toggleSidebar" class="menu-btn"><i class='bx bx-menu'></i></button>
        <img src="../IMG/Vector_Logo (1).svg" alt="">
    </div>

    <div class="bottom-header">
        <h3>Crear Usuario</h3>
        <p>Registra nuevos usuarios y define sus datos principales.</p>
    </div>
</header>

<?php include "Header.php"; ?>

<!-- CONTENIDO -->
<main class="contenido2">
    <section class="Contenedor_Form">

        <!-- ALERTA -->
         <?php if (isset($_SESSION['form_error'])): ?>
            <?php
                $code = $_SESSION['form_error']['msg'];
                $type = $_SESSION['form_error']['type'];
                $texto = $errores[$code] ?? "Error desconocido.";
            ?>
            <div class='alert alert-<?= $type ?>' style='padding:12px; margin-bottom:12px; border-radius:6px;'>
                <strong>Error:</strong> <?= $texto ?>
            </div>
            <?php unset($_SESSION['form_error']); ?>
        <?php endif; ?>

        <form id="miFormulario" action="Persona_Crear_Guardar.php" method="POST" autocomplete="off">

            <h3><i class='bx bxs-user'></i> Crear Persona</h3>

            <div class="Form_info">
                <label>Primer Nombre *</label>
                <input type="text" name="first_name" required minlength="2" maxlength="50"
                       pattern="^[A-Za-zÁÉÍÓÚÑáéíóúñ ]+$"
                       value="<?= old('first_name') ?>">
            </div>

            <div class="Form_info">
                <label>Segundo Nombre</label>
                <input type="text" name="second_name" minlength="2" maxlength="50"
                       pattern="^[A-Za-zÁÉÍÓÚÑáéíóúñ ]*$"
                       value="<?= old('second_name') ?>">
            </div>

            <div class="Form_info">
                <label>Primer Apellido *</label>
                <input type="text" name="last_name" required minlength="2" maxlength="50"
                       pattern="^[A-Za-zÁÉÍÓÚÑáéíóúñ ]+$"
                       value="<?= old('last_name') ?>">
            </div>

            <div class="Form_info">
                <label>Segundo Apellido *</label>
                <input type="text" name="second_last_name" required minlength="2" maxlength="50"
                       pattern="^[A-Za-zÁÉÍÓÚÑáéíóúñ ]+$"
                       value="<?= old('second_last_name') ?>">
            </div>

            <div class="Form_info">
                <label>Tipo Documento *</label>
                <select name="document_type_id" required>
                    <option value="">Seleccione...</option>
                    <?php while ($row = $tipos->fetch_assoc()) { ?>
                        <option value="<?= $row['id'] ?>"
                            <?= old('document_type_id') == $row['id'] ? 'selected' : '' ?>>
                            <?= $row['code'] ?>
                        </option>
                    <?php } ?>
                </select>
            </div>

            <div class="Form_info">
                <label>N° Documento *</label>
                <input type="text" name="document_id" required minlength="5" maxlength="12"
                       pattern="^[0-9]+$"
                       value="<?= old('document_id') ?>">
            </div>

            <div class="Form_info">
                <label>Dirección</label>
                <input type="text" name="address" maxlength="50" pattern="^[A-Za-z0-9# .-]*$"
                       value="<?= old('address') ?>">
            </div>

            <br><h3><i class='bx bx-phone'></i> Contacto</h3>

            <div class="Form_info">
                <label>Email Principal *</label>
                <input type="email" name="email_primary" required maxlength="50"
                       value="<?= old('email_primary') ?>">
            </div>

            <div class="Form_info">
                <label>Email Secundario</label>
                <input type="email" name="email_secondary" maxlength="50"
                       value="<?= old('email_secondary') ?>">
            </div>

            <div class="Form_info">
                <label>Teléfono Principal *</label>
                <input type="tel" id="telefono_principal" pattern="^[0-9]+$" required value="<?= old('telefono_principal') ?>">
            </div>

            <input type="hidden" name="telefono_principal" pattern="^[0-9]+$" id="telefono_principal_final">

            <div class="Form_info">
                <label>Teléfono Secundario</label>
                <input type="tel" id="telefono_secundario" value="<?= old('telefono_secundario') ?>">
            </div>

            <input type="hidden" name="telefono_secundario" id="telefono_secundario_final">

            <br>

            <div class="content_button">
                <button class="btn verde" type="submit">Crear Persona <i class='bx bxs-save'></i></button>
                <a href="../PHP/Administración_Usuarios.php" class="btn rojo">Cancelar <i class='bx bx-exit'></i></a>
            </div>

        </form>
    </section>
</main>

<script src="../JS/Telefono.js"></script>
<script src="../JS/Validacion_Personas.js"></script>
</body>
</html>
