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
            it.name AS document_type_name,
            CONCAT( it.code,' - ', p.document_id) AS identidad,
            CONCAT( p.first_name,' ', p.last_name,' / ', it.code, '-',p.document_id) AS titulo_persona,
            CONCAT_WS( ' ',p.first_name, p.second_name,p.last_name, p.second_last_name) AS nombre_completo,
            IFNULL(NULLIF(p.email_secondary, ''), '-') AS email_secundario,
            IFNULL(NULLIF(ph.phone_number, ''), '-') AS primary_phone,
            p.email_primary,
            p.address,
            ph.phone_number,
            ph.priority
        FROM people p
        LEFT JOIN identity_types it ON it.id = p.document_type_id
        LEFT JOIN phones ph ON ph.people_id = p.id
        WHERE p.id = $persona_id
        ";
$persona = $conn->query($sql)->fetch_assoc();

    $sql2 = "SELECT
            u.id AS user_id,
            u.username,
            ur.password AS password_real,
            REPEAT('*', 12) AS password_oculta,
            ur.user_status,
            r.name AS rol_nombre,
            r.is_active AS rol_activo
        FROM users u
        JOIN user_roles ur ON ur.users_id = u.id
        JOIN roles r ON r.id = ur.roles_id
        WHERE u.people_id = $persona_id";

$usuario = $conn->query($sql2)->fetch_assoc();

    $resultado = $conn->query($sql);
    $resultado2 = $conn->query($sql2);
    $sqlTipos = "SELECT id, code FROM identity_types ORDER BY code ASC";
    $tiposResultado = $conn->query($sqlTipos);
?>



<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Diplomas Litoral</title>
    <link rel="icon" type="image/svg+xml" href="../ICONS/Union (2).svg" sizes="12x12">

    <link rel="stylesheet" href="../CSS/Haeder.CSS">
    <link rel="stylesheet" href="../CSS/Tablas.CSS">
    <link rel="stylesheet" href="../CSS/Formularios.CSS">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@800&family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <!-- ICONOS -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <!-- DataTables CORE sin estilos -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

    <!-- ICONOS -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>

<body>
<!-- =============== HEADER =============== -->
<header class="top-header">
    <div class="Titulo-Haeder">
        <button id="toggleSidebar" class="menu-btn"><i class='bx bx-menu'></i></button>
        <img src="../IMG/Vector_Logo (1).svg" alt="">
    </div>

    <div class="bottom-header">
        <h3>Vista de Usuario</h3>
        <p>Actualiza la información y permisos de los usuarios existentes.</p>
    </div>
</header>

<?php include "Header.php"; ?>

<main class="contenido">
    <section class="Contenedor_Form">
        <form action="">
            <h3 class="h3"><i class='bx bxs-user'></i>Vista De Persona - <?= $persona['titulo_persona'] ?></h3>
            <div class="Form_info">
                <label for="">Nombre </label>
                <input type="text" value="<?= $persona['nombre_completo'] ?>" disabled>
            </div>
            <div class="Form_info">
                <label for="">Identidad </label>
                <input type="text" value="<?= $persona['identidad'] ?>" disabled>
            </div>            <div class="Form_info">
                <label for="">Email</label>
                <input type="text" value="<?= $persona['email_primary'] ?>" disabled>
            </div>
            <div class="Form_info">
                <label for="">Email</label>
                <input type="text" value="<?= $persona['email_secundario'] ?>" disabled>
            </div>
            <div class="Form_info">
                <label for="">Telefono</label>
                <input type="text" value="<?= $persona['phone_number'] ?>" disabled>
            </div>
            <div class="Form_info">
                <label for="">Direccion</label>
                <input type="text" value="<?= $persona['address'] ?>" disabled>
            </div>
            <div class="content_button">
                <a href="../PHP/Administración_Editar_Usuarios.php?id=<?= $persona['people_id'] ?>"  class="btn amarillo">
                    Editar Información <i class='bx bx-refresh'></i>
                </a>
                <a class="btn rojo" href="../PHP/Administración_Usuarios.php">Regresar <i class='bx bx-redo bx-flip-horizontal'></i></i></a>
            </div>
        </form>
    </section>
    <section class="Tablas">
        <div class="content_Tablas">
            <table id="miTabla">
                <thead>
                    <tr>
                        <th>Nombre De usuario</th>
                        <th>password</th>
                        <th>Rol</th>
                        <th class="Tabla_none">Estado De Cuenta</th>
                    </tr>
                </thead>

                <tbody>
                    <?php while ($fila = $resultado2->fetch_assoc()) { ?>
                        <tr>
                            <td><?= $fila['username'] ?></td>
                            <td data-pass="<?= $fila['password_real'] ?>">
                                <?= $fila['password_oculta'] ?>
                            </td>
                            <td><?= $fila['rol_nombre'] ?></td>
                            <td class="Tabla_none"><?= $fila['user_status'] ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </section>
</main>
<script>
function mostrarPassword(boton) {
    // Buscar la fila (tr) donde está el botón
    let fila = boton.closest("tr");

    // En esa fila, buscar la columna donde está la contraseña
    let celdaPass = fila.querySelector("td[data-pass]");

    let passwordReal = celdaPass.getAttribute("data-pass");

    // Mostrar contraseña real
    celdaPass.innerText = passwordReal;
}
</script>

<script src="../JS/Tablas.js"></script>

</body>
</html>