<?php
include "access_control.php";

// Validar que el usuario sea Admin y tenga acceso a esta página
validar_acceso('Admin', __FILE__);

if (!isset($_SESSION["people_id"])) {
    die("Error: No hay sesión de usuario activa.");
}

$Id_Persona = intval($_SESSION["people_id"]);

// === CONSULTA PRINCIPAL DE PERSONAS ===
$sql = "
    SELECT 
        p.id,
        CONCAT(it.code,'-',p.document_id) AS documento_completo,
        CONCAT_WS(' ',p.first_name,p.second_name,p.last_name,p.second_last_name) AS nombre_completo,
        p.email_primary,
        IFNULL(NULLIF(p.email_secondary, ''), '-') AS email_secundario,
        IFNULL(NULLIF(ph.phone_number, ''), '-') AS primary_phone,
        CASE 
            WHEN EXISTS (
                SELECT 1 
                FROM enrollments e
                WHERE e.people_id = p.id
                AND e.enrollment_status = 'Matriculado'
            )
            THEN 'Activo'
            ELSE 'Inactivo'
        END AS estado_matricula
    FROM people p
    JOIN identity_types it ON p.document_type_id = it.id
    LEFT JOIN phones ph ON ph.people_id = p.id AND ph.priority = 1
    WHERE p.id != $Id_Persona
";

$resultado = $conn->query($sql);

// === TIPOS DE DOCUMENTO PARA EL FILTRO ===
$sqlTipos = "SELECT id, code FROM identity_types ORDER BY code ASC";
$tiposResultado = $conn->query($sqlTipos);

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Módulo de Gestión de Personas</title>

    <link rel="icon" type="image/svg+xml" href="../ICONS/Union (2).svg" sizes="12x12">

    <link rel="stylesheet" href="../CSS/Tablas.CSS">
    <link rel="stylesheet" href="../CSS/Haeder.CSS">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@800&family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">

    <!-- DataTables CORE -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

    <!-- ICONOS -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>

<body>

<!-- =================== HEADER =================== -->
<header class="top-header">
    <div class="Titulo-Haeder">
        <button id="toggleSidebar" class="menu-btn"><i class='bx bx-menu'></i></button>
        <img src="../IMG/Vector_Logo (1).svg" alt="Logo">
    </div>

    <div class="bottom-header">
        <h3>Administración de Usuarios</h3>
        <p>Gestiona la información de las personas registradas en la plataforma.</p>
    </div>
</header>

<?php include "Header.php"; ?>

<!-- =================== CONTENIDO =================== -->
<main class="contenido">

    <!-- === FILTROS === -->
    <section class="Contenedor_Filtros">

        <!-- Buscador -->
        <div class="Contenedor_Buscador">
            <div class="Buscador">
                <button><i class='bx bx-search-alt-2'></i></button>
                <input type="text" id="buscador" placeholder="Buscar..." maxlength="100">
            </div>
        </div>

        <!-- Select tipo de documento -->
        <div class="Contenedor_select">
            <select id="filtroDocumento" class="filtro">
                <option value="">Tipo de documento</option>
                <?php while ($tipo = $tiposResultado->fetch_assoc()) { ?>
                    <option value="<?= $tipo['code'] ?>"><?= $tipo['code'] ?></option>
                <?php } ?>
            </select>
        </div>

        <!-- Botón crear persona -->
        <div class="Contenedor_btn">
            <a href="Persona_Crear.php" 
               title="Este botón sirve para crear una nueva persona en el sistema" 
               class="btn verde">
               Crear Persona <i class='bx bx-user-plus'></i>
            </a>
        </div>
    </section>

    <!-- === TABLA DE PERSONAS === -->
    <section class="Tablas">
        <div class="content_Tablas">

            <table id="miTabla">
                <thead>
                    <tr>
                        <th>Identificación</th>
                        <th>Nombre</th>
                        <th>P. Email</th>
                        <th class="Tabla_none">S. Email</th>
                        <th>Teléfono</th>
                        <th class="Tabla_none">Estado</th>
                        <th>Funciones</th>
                    </tr>
                </thead>

                <tbody>
                    <?php while ($fila = $resultado->fetch_assoc()) { ?>
                        <tr>
                            <td><?= $fila['documento_completo'] ?></td>
                            <td>
                                <a href="Administración_Ver_Usuarios.php?id=<?= $fila['id'] ?>" class="Name_link">
                                    <?= $fila['nombre_completo'] ?>
                                </a>
                            </td>
                            <td><?= $fila['email_primary'] ?></td>
                            <td class="Tabla_none"><?= $fila['email_secundario'] ?></td>
                            <td><?= $fila['primary_phone'] ?></td>
                            <td class="Tabla_none"><?= $fila['estado_matricula'] ?></td>

                            <td class="acciones">
                                <a href="Administración_Ver_Usuarios.php?id=<?= $fila['id'] ?>" class="btn azul">
                                    <i class='bx bx-show'></i>
                                </a>

                                <a href="Administración_Editar_Usuarios.php?id=<?= $fila['id'] ?>" class="btn amarillo">
                                    <i class='bx bx-edit-alt'></i>
                                </a>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>

        </div>

        <!-- PAGINADOR -->
        <div id="paginador">
            <button id="btnPrev"><i class='bx bxs-left-arrow'></i></button>
            <span id="infoPagina">1 de 1</span>
            <button id="btnNext"><i class='bx bxs-left-arrow bx-rotate-180'></i></button>
        </div>

    </section>
</main>

<script src="../JS/Tablas.js"></script>
</body>
</html>
