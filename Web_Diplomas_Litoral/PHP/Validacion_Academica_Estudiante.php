<?php
include "access_control.php";
include "MySql_php.php";


// Validar que el usuario sea Estudiante y tenga acceso a esta página
validar_acceso('Student', __FILE__);

if (!isset($_SESSION["people_id"])) {
    die("Error: No hay sesión de usuario activa.");
}

    $Id_Persona = intval($_SESSION["people_id"]);

$sql = "SELECT
            e.id AS Id_Matricula,
            

            pr.code AS program_code,
            pr.name AS program_name,

            CONCAT_WS(' ', p.first_name, p.second_name, p.last_name, p.second_last_name) AS student_full_name,
            
            p.id AS People_id,

            CONCAT(it.code,' ', p.document_id) AS Identidad,

            g.group_name AS nombre_grupo,

            COUNT(DISTINCT prq.id) AS total_requirements,

            SUM(CASE WHEN sv.documen IS NOT NULL AND sv.documen != '' THEN 1 ELSE 0 END) AS uploaded_requirements,

            SUM(CASE WHEN sv.status = 'Approved' THEN 1 ELSE 0 END) AS approved_requirements

        FROM enrollments e
        JOIN people p             ON p.id = e.people_id
        JOIN identity_types it    ON it.id = p.document_type_id
        JOIN academic_group g     ON g.id = e.academic_group_id
        JOIN programs pr          ON pr.id = g.program_id
        LEFT JOIN program_requirements prq ON prq.program_id = pr.id
        LEFT JOIN student_validations sv 
               ON sv.enrollment_id = e.id
              AND sv.program_requirement_id = prq.id

        WHERE p.id = $Id_Persona
        
        GROUP BY
            e.id, pr.code, pr.name, student_full_name,
            Identidad, nombre_grupo, People_id

        ORDER BY uploaded_requirements ASC;
    ";
    $resultado = $conn->query($sql);

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
<header class="top-header">
    <div class="Titulo-Haeder">
        <button id="toggleSidebar" class="menu-btn"><i class='bx bx-menu'></i></button>
        <img src="../IMG/Vector_Logo (1).svg" alt="">
    </div>

    <div class="bottom-header">
        <h3>Módulo de Diplomas - Grupo</h3>
        <p>Aquí puedes gestionar y consultar los diplomas de cada estudiante.</p>
    </div>
</header>

<?php include "Header_copy.php"; ?>

<main class="contenido">
    <section class="Contenedor_Filtros">

        <div class="Contenedor_Buscador">
            <div class="Buscador">
                <button><i class='bx bx-search-alt-2'></i></button>
                <input type="text" id="buscador" placeholder="Buscar..." maxlength="100">
            </div>
        </div>
    </section>

    <section class="Tablas">
        <div class="content_Tablas">

            <table id="miTabla">
                <thead>
                    <tr>
                        <th>code Grupo</th>
                        <th>Programa</th>
                        <th>nombre Del Estudiante</th>
                        <th class="Tabla_none">Identidad    </th>
                        <th>R Totales</th>
                        <th>N° R montados</th>
                        <th class="Tabla_none">requeriminetos aprobados </th>
                        <th>Funciones      </th>
                    </tr>
                </thead>

                <tbody>
                    <?php while ($fila = $resultado->fetch_assoc()) { ?>
                        <tr>
                            <td><?= $fila['nombre_grupo'] ?></td>
                            <td><?= $fila['program_name'] ?></td>
                            <td><a href="Usuario_Ver_copy.php" class="Name_link"><?= $fila['student_full_name'] ?></a></td>
                            <td class="Tabla_none"><?= $fila['Identidad'] ?></td>
                            <td><?= $fila['total_requirements'] ?></td>
                            <td><?= $fila['uploaded_requirements'] ?></td>
                            <td class="Tabla_none"><?= $fila['approved_requirements'] ?></td>

                            <td class="acciones">
                                <a href="../PHP/Validar_Validacion_Academica_Estudiante.php?id=<?= $fila['Id_Matricula'] ?>" class="btn amarillo">
                                <i class='bx bx-task'></i>Montar DOC
                                </a>
                            </td>
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