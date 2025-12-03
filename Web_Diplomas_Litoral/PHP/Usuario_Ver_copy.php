<?php
include "access_control.php";
include "MySql_php.php";

// Página accesible para todos los roles autenticados
validar_acceso(null, __FILE__);

if (!isset($_SESSION["people_id"])) {
    die("No se recibió un ID válido.");
}
$persona_id = intval($_SESSION["people_id"]);

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
    <title>Usuario</title>
    <link rel="icon" type="image/svg+xml" href="../ICONS/Union (2).svg" sizes="12x12">

    <link rel="stylesheet" href="../CSS/Formularios.CSS">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@800&family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">

    <!-- DataTables CORE sin estilos -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

    <!-- ICONOS -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>

<body>
<header class="top-header">
    <div class="Titulo-Haeder">
        <button id="toggleSidebar" class="menu-btn"><i class='bx bx-menu'></i></button>
        <img src="../IMG/Vector_Logo (1).svg" alt="">
    </div>

    <div class="bottom-header">
        <h3>Vista de Usuario</h3>
        <p>Consulta de forma detallada los datos.</p>
    </div>
</header>

<?php include "Header_copy.php"; ?>

<main class="contenido">
    <div class="Content_Ver">
        <section class="Contenedor_Form">
            <form action="">
                <h3><i class='bx bxs-user'></i>Vista De Persona - <?= $persona['titulo_persona'] ?></h3>
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
                    <a class="btn rojo" href="Validacion_Academica_Estudiante.php">Regresar <i class='bx bx-redo bx-flip-horizontal'></i></i></a>
                </div>
            </form>
        </section>
    </div>
</main>
    <script src="../JS/Tablas.js"></script>
</body>
</html>