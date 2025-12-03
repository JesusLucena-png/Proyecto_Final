<?php
include "access_control.php";

// Validar que el usuario sea Admin y tenga acceso a esta página
validar_acceso('Admin', __FILE__);

if (!isset($_GET['id'])) {
    die("No se recibió un ID válido.");
}
$persona_id = intval($_GET['id']);

    $sql = "SELECT 
            p.id AS people_id,
            p.document_type_id,
            it.code AS tipo_documento,
            it.name AS document_type_name,
            p.document_id,
            p.first_name,
            p.second_name,
            p.last_name,
            p.second_last_name,
            p.email_primary,
            p.email_secondary,
            p.address
        FROM people p
        LEFT JOIN identity_types it ON it.id = p.document_type_id
        WHERE p.id = $persona_id
        ";
    $persona = $conn->query($sql)->fetch_assoc();

    $sqlPhones = "SELECT 
            ph.id,
            ph.phone_number,
            ph.priority
        FROM phones ph
        WHERE ph.people_id = $persona_id
        ORDER BY ph.priority DESC
        ";
        $telefono1 = "";
        $telefono2 = "";

    $telefonos = $conn->query($sqlPhones);

        while ($tel = $telefonos->fetch_assoc()) {
        if ($tel['priority'] == "1") {
            $telefono1 = $tel['phone_number'];
        } else {
            $telefono2 = $tel['phone_number'];
        }
    }

    $sqlTipos = "SELECT id, code FROM identity_types ORDER BY code ASC";
    $tipos = $conn->query($sqlTipos);

    $sqlTipos = "SELECT id, code FROM identity_types ORDER BY code ASC";
    $tiposResultado = $conn->query($sqlTipos);
    // Revisar si hay datos previos en sesión (por error al enviar)
    $old = $_SESSION['old_persona_edit'] ?? null;
    $formError = $_SESSION['form_error'] ?? null;

    if ($formError) {
        $msg = $formError['msg'] ?? '';
        $type = $formError['type'] ?? 'error';
        $icon = $type === 'success' ? 'success' : 'error';
        $title = $type === 'success' ? 'Operación exitosa' : 'Error';
        $texto = '';
        switch ($msg) {
            case 'campos_vacios': $texto = 'Faltan campos obligatorios.'; break;
            case 'documento_invalido': $texto = 'El número de documento no cumple el formato requerido.'; break;
            case 'email_principal_invalido': $texto = 'El email principal no es válido.'; break;
            case 'email_secundario_invalido': $texto = 'El email secundario no es válido.'; break;
            case 'documento_duplicado': $texto = 'El número de documento ya está registrado.'; break;
            case 'email_principal_duplicado': $texto = 'El email principal ya está registrado.'; break;
            case 'email_secundario_duplicado': $texto = 'El email secundario ya está registrado.'; break;
            case 'error_sql': $texto = 'Ocurrió un error al guardar la persona.'; break;
            case 'actualizado': $texto = 'La persona fue actualizada correctamente.'; break;
        }
        if ($texto) {
            echo "
            <script>
                Swal.fire({
                    icon: '$icon',
                    title: '$title',
                    text: '$texto',
                    timer: 3000,
                    showConfirmButton: false
                });
            </script>";
        }

        // Limpiar sesión usada
        unset($_SESSION['old_persona_edit']);
        unset($_SESSION['form_error']);
    }
?>



<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Diplomas Litoral</title>
    <link rel="icon" type="image/svg+xml" href="../ICONS/Union (2).svg" sizes="12x12">

    <link rel="stylesheet" href="../CSS/Formularios.CSS">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@800&family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">


    <!-- ICONOS -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <!-- intl-tel-input -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/css/intlTelInput.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/intlTelInput.min.js"></script>
</head>

<body>
<!-- =============== HEADER =============== -->
<header class="top-header">
    <div class="Titulo-Haeder">
        <button id="toggleSidebar" class="menu-btn"><i class='bx bx-menu'></i></button>
        <img src="../IMG/Vector_Logo (1).svg" alt="">
    </div>

    <div class="bottom-header">
        <h3>Editar Usuario</h3>
        <p>Actualiza la información y permisos de los usuarios existentes.</p>
    </div>
</header>

<?php include "Header.php"; ?>

<main class="contenido2">
    <section class="Contenedor_Form">
        <form action="Persona_Editar_Guardar.php" method="POST" id="miFormulario" autocomplete="off">
            
            <input type="hidden" name="persona_id" value="<?= $persona['people_id'] ?>">

            <h3 class="h3"><i class='bx bxs-user'></i> Editar Persona</h3>

            <div class="Form_info">
                <label>Primer Nombre <i class='bx bx-block'></i></label>
                <input type="text" name="first_name" value="<?= htmlspecialchars($old['first_name'] ?? $persona['first_name']) ?>" disabled>
                <input type="hidden" name="first_name" value="<?= htmlspecialchars($old['first_name'] ?? $persona['first_name']) ?>">
            </div>

            <div class="Form_info">
                <label>Segundo Nombre <i class='bx bx-block'></i></label>
                <input type="text" name="second_name" value="<?= htmlspecialchars($old['second_name'] ?? $persona['second_name']) ?>" disabled>
                <input type="hidden" name="second_name" value="<?= htmlspecialchars($old['second_name'] ?? $persona['second_name']) ?>">
            </div>

            <div class="Form_info">
                <label>Primer Apellido <i class='bx bx-block'></i></label>
                <input type="text" name="last_name" value="<?= htmlspecialchars($old['last_name'] ?? $persona['last_name']) ?>" disabled>
                <input type="hidden" name="last_name" value="<?= htmlspecialchars($old['last_name'] ?? $persona['last_name']) ?>">
            </div>

            <div class="Form_info">
                <label>Segundo Apellido <i class='bx bx-block'></i></label>
                <input type="text" name="second_last_name" value="<?= htmlspecialchars($old['second_last_name'] ?? $persona['second_last_name']) ?>" disabled>
                <input type="hidden" name="second_last_name" value="<?= htmlspecialchars($old['second_last_name'] ?? $persona['second_last_name']) ?>">
            </div>

            <!-- SELECT TIPO DOCUMENTO -->
            <div class="Form_info">
                <label>Tipo Documento</label>

                <?php 
                    $tipoActual = $persona['tipo_documento'];      // Código del tipo (CC, TI, etc.)
                    $idTipoActual = $persona['document_type_id'];  // ID numérico del tipo actual
                ?>

                <?php if ($tipoActual === 'CC'): ?>

                    <!-- SELECT SOLO LECTURA -->
                    <select disabled>
                        <option value="<?= $idTipoActual ?>" selected><?= $tipoActual ?></option>
                    </select>

                    <!-- Se envía el valor real -->
                    <input type="hidden" name="document_type_id" value="<?= $idTipoActual ?>">

                    <div class="Form_info">
                        <label>N° Documento <i class='bx bx-block'></i></label>
                        <input type="text" name="document_id" pattern="[0-9]{5,12}" value="<?= htmlspecialchars($old['document_id'] ?? $persona['document_id']) ?>" disabled>
                        <input type="hidden" name="document_id" pattern="[0-9]{5,12}" value="<?= htmlspecialchars($old['document_id'] ?? $persona['document_id']) ?>">
                    </div>

                <?php else: ?>

                    <!-- SELECT EDITABLE, PERO SOLO ENTRE ACTUAL → CC -->
                    <select name="document_type_id">
                        <!-- Tipo actual -->
                        <option value="<?= $idTipoActual ?>" selected><?= $tipoActual ?></option>

                        <?php
                            // Buscar la opción CC en la tabla
                            $sqlCC = "SELECT id, code FROM identity_types WHERE code='CC' AND is_active='1' LIMIT 1";
                            $resultadoCC = $conn->query($sqlCC)->fetch_assoc();

                            if ($resultadoCC):
                        ?>
                            <option value="<?= $resultadoCC['id'] ?>">CC</option>
                        <?php endif; ?>
                    </select>
                    <div class="Form_info">
                        <label>N° Documento</label>
                                <input type="text" name="document_id" id="document_id" pattern="[0-9]{5,12}" value="<?= htmlspecialchars($old['document_id'] ?? $persona['document_id']) ?>">
                                <small id="error_document_id" class="input_error" style="color:red; display:none;"></small>
                    </div>

                <?php endif; ?>
            </div>

            <div class="Form_info">
                <label>Dirección</label>
                <input type="text" name="address" value="<?= htmlspecialchars($old['address'] ?? $persona['address']) ?>">
            </div>

            <br>

            <h3><i class='bx bx-phone'></i> Teléfonos</h3>

            <div class="Form_info">
                <label>Email Principal</label>
                <input type="email" name="email_primary" id="email_primary" value="<?= htmlspecialchars($old['email_primary'] ?? $persona['email_primary']) ?>">
                <small id="error_email_primary" class="input_error" style="color:red; display:none;"></small>
            </div>

            <div class="Form_info">
                <label>Email Secundario</label>
                <input type="email" name="email_secondary" id="email_secondary" value="<?= htmlspecialchars($old['email_secondary'] ?? $persona['email_secondary']) ?>">
                <small id="error_email_secondary" class="input_error" style="color:red; display:none;"></small>
            </div>

            <div class="Form_info">
                <label>Teléfono 1 (Principal)</label>
                <input id="telefono_principal" type="tel" value="<?= htmlspecialchars($old['telefono_principal'] ?? $telefono1) ?>">
                <small id="error_tel1" style="color:red; display:none;"></small>
            </div>
            <input type="hidden" name="telefono_principal" id="telefono_principal_final" value="<?= htmlspecialchars($old['telefono_principal'] ?? $telefono1) ?>">

            <div class="Form_info">
                <label>Teléfono 2 (Secundario)</label>
                <input id="telefono_secundario" type="tel" value="<?= htmlspecialchars($old['telefono_secundario'] ?? $telefono2) ?>">
                <small id="error_tel2" style="color:red; display:none;"></small>
            </div>
            <input type="hidden" name="telefono_secundario" id="telefono_secundario_final" value="<?= htmlspecialchars($old['telefono_secundario'] ?? $telefono2) ?>">

            <br>

            <div class="content_button">
                <button class="btn verde" type="submit">Guardar Cambios <i class='bx bxs-save'></i></button> 
                <a href="../PHP/Administración_Usuarios.php" class="btn rojo" >Cancelar <i class='bx bx-exit'></i></a>
            </div>
        </form>
    </section>
</main>

<script src="../JS/Telefono.js"></script>
<script src="../JS/Validacion_Personas.js"></script>
</body>
</html>