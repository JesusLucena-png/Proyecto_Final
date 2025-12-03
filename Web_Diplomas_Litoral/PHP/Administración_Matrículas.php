<?php
include "access_control.php";

// Validar que el usuario sea Admin y tenga acceso a esta página
validar_acceso('Admin', __FILE__);

$sql = "
        SELECT 
                ag.id AS group_id,
                ag.group_name,
                ag.status,
                ag.start_period,
                ag.end_period,
                ag.schedule,

                pg.code AS program_code,
                pg.name AS program_name,

                pt.name AS program_type,

                -- Contador de matriculados
                COUNT(e.id) AS total_matriculados

            FROM academic_group ag
            JOIN programs pg 
                    ON ag.program_id = pg.id
            JOIN program_types pt 
                    ON pg.program_type_id = pt.id
            LEFT JOIN enrollments e 
                    ON ag.id = e.academic_group_id
            GROUP BY ag.id
            ORDER BY ag.id DESC;
    ";
    $resultado = $conn->query($sql);

    $sqlTipos = "SELECT id, code FROM identity_types ORDER BY code ASC";
    $tiposResultado = $conn->query($sqlTipos);

    $sqlTiposProg = "SELECT id, name FROM program_types ORDER BY name ASC";
    $tiposProgResultado = $conn->query($sqlTiposProg);

    $sqlProgramas = "SELECT id, code, name FROM programs ORDER BY code ASC";
    $programasResultado = $conn->query($sqlProgramas);
?>



<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Diplomas Litoral</title>
    <link rel="icon" type="image/x-icon" href="../ICONS/Union (2).svg">

    <link rel="stylesheet" href="../CSS/Tablas.CSS">
    <link rel="stylesheet" href="../CSS/Haeder.CSS">

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
<!-- =============== HEADER =============== -->
<header class="top-header">
    <div class="Titulo-Haeder">
        <button id="toggleSidebar" class="menu-btn"><i class='bx bx-menu'></i></button>
        <img src="../IMG/Vector_Logo (1).svg" alt="">
    </div>

    <div class="bottom-header">
        <h3>Administración de Matrículas</h3>
        <p>Visualiza los estudiantes matriculados por programa y grupo académico.</p>
    </div>
</header>

<?php include "Header.php"; ?>
<main class="contenido">
    <section class="Contenedor_Filtros">

        <div class="Contenedor_Buscador">
            <div class="Buscador">
                <button><i class='bx bx-search-alt-2'></i></button>
                <input type="text" id="buscador" placeholder="Buscar..." maxlength="100">
            </div>
        </div>

        <div class="Contenedor_select">

            <!-- FILTRO: Tipo de Programa -->
            <select id="filtroTipoPrograma" class="filtro">
                <option value="">Tipo de programa</option>
                <?php while ($tipo = $tiposProgResultado->fetch_assoc()) { ?>
                    <option value="<?= $tipo['name'] ?>"><?= $tipo['name'] ?></option>
                <?php } ?>
            </select>
        </div>
    </section>

    <section class="Tablas">
        <div class="content_Tablas">

            <table id="miTabla">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Grupo</th>
                        <th>Programa</th>
                        <th>Tipo Prog.</th>
                        <th>Código</th>
                        <th>Periodo</th>
                        <th>Horario</th>
                        <th>Matriculados</th>
                        <th>Estado</th>
                    </tr>
                </thead>

                <tbody>
                    <?php while ($g = $resultado->fetch_assoc()) { ?>
                    <tr>
                        <td><?= $g['group_id'] ?></td>
                        <td><?= $g['group_name'] ?></td>
                        <td><?= $g['program_name'] ?></td>
                        <td><?= $g['program_type'] ?></td>
                        <td><?= $g['program_code'] ?></td>
                        <td><?= $g['start_period'] ?> - <?= $g['end_period'] ?></td>
                        <td><?= $g['schedule'] ?></td>
                        <td><?= $g['total_matriculados'] ?></td>
                        <td><?= $g['status'] ?></td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>


        </div>

        <!-- PAGINADOR PERSONALIZADO -->
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