<?php
include "access_control.php";

// Validar que el usuario sea Admin y tenga acceso a esta página
validar_acceso('Admin', __FILE__);

if (!isset($_SESSION["people_id"])) {
    die("Error: No hay sesión de usuario activa.");
}

$Id_Persona = intval($_SESSION["people_id"]);

$sql = "
    SELECT 
        ag.id AS group_id,
        ag.group_name,
        pg.code AS program_code,
        pg.name AS program_name,
        pt.name AS program_type_name,
        COUNT(el.id) AS total_estudiantes,
        SUM(CASE WHEN d.status = 'Expedido' THEN 1 ELSE 0 END) AS diplomas_generados
    FROM academic_group ag
    JOIN programs pg ON ag.program_id = pg.id
    JOIN program_types pt ON pg.program_type_id = pt.id
    LEFT JOIN enrollments el ON el.academic_group_id = ag.id
    LEFT JOIN diplomas d ON d.enrollment_id = el.id
    GROUP BY ag.id
    ORDER BY ag.group_name ASC;
";
$resultado = $conn->query($sql);

// Listas
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
    <link rel="icon" type="image/svg+xml" href="../ICONS/Union (2).svg" sizes="12x12">

    <!-- Estilos -->
    <link rel="stylesheet" href="../CSS/Tablas.CSS">

    <!-- Fuentes -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@800&family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">

    <!-- DataTables -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

    <!-- Iconos -->
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
</head>

<body>

<!-- HEADER SUPERIOR -->
<header class="top-header">
    <div class="Titulo-Haeder">
        <button id="toggleSidebar" class="menu-btn">
            <i class='bx bx-menu'></i>
        </button>
        <img src="../IMG/Vector_Logo (1).svg" alt="">
    </div>

    <div class="bottom-header">
        <h3>Modulo De Diplomas</h3>
        <p>Consulta los grupos académicos y revisa los diplomas asignados a cada estudiante.</p>
    </div>
</header>

<?php include "Header.php"; ?>

<main class="contenido">

    <!-- FILTROS -->
    <section class="Contenedor_Filtros">

        <div class="Contenedor_Buscador">
            <div class="Buscador">
                <button><i class='bx bx-search-alt-2'></i></button>
                <input type="text" id="buscador" placeholder="Buscar..." maxlength="100">
            </div>
        </div>

        <div class="Contenedor_select">
            
            <!-- Tipo de Programa -->
            <select id="filtroTipoPrograma" class="filtro">
                <option value="">Tipo de programa</option>
                <?php while ($tipo = $tiposProgResultado->fetch_assoc()) { ?>
                    <option value="<?= $tipo['name'] ?>"><?= $tipo['name'] ?></option>
                <?php } ?>
            </select>

            <!-- Programa -->
            <select id="filtroPrograma" class="filtro">
                <option value="">Programa</option>
                <?php while ($p = $programasResultado->fetch_assoc()) { ?>
                    <option value="<?= $p['code'] ?>">
                        <?= $p['code'] ?> - <?= $p['name'] ?>
                    </option>
                <?php } ?>
            </select>

        </div>
    </section>

    <!-- TABLA -->
    <section class="Tablas">

        <div class="content_Tablas">
            <table id="miTabla">
                <thead>
                    <tr>
                        <th>Grupo</th>
                        <th>Programa</th>
                        <th>Tipo</th>
                        <th>Estudiantes</th>
                        <th>Diplomas Emitidos</th>
                        <th>Acciones</th>
                    </tr>
                </thead>

                <tbody>
                    <?php while ($fila = $resultado->fetch_assoc()) { ?>
                        <tr>
                            <td><?= $fila['group_name'] ?></td>
                            <td><?= $fila['program_code'] ?> - <?= $fila['program_name'] ?></td>
                            <td><?= $fila['program_type_name'] ?></td>
                            <td><?= $fila['total_estudiantes'] ?></td>
                            <td><?= $fila['diplomas_generados'] ?></td>
                            <td class="acciones">
                                <a href="Diplomas_Grupo.php?id=<?= $fila['group_id'] ?>" class="btn azul">
                                    <i class='bx bx-show'></i> Ver estudiantes
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

<script>
$(document).ready(function () {

    let tabla = $("#miTabla").DataTable({
        paging: true,
        searching: true,
        info: false,
        ordering: false,
        dom: "t",
        pageLength: 50
    });

    function actualizarInfoPaginador() {
        let info = tabla.page.info();
        $("#infoPagina").text(`${info.page + 1} de ${info.pages}`);
    }

    // Buscador general
    $("#buscador").on("keyup", function () {
        tabla.search(this.value).draw();
        actualizarInfoPaginador();
    });

    // Filtro Tipo Programa
    $("#filtroTipoPrograma").on("change", function () {
        tabla.column(2).search(this.value).draw();
        actualizarInfoPaginador();
    });

    // Filtro Programa
    $("#filtroPrograma").on("change", function () {
        tabla.column(1).search(this.value).draw();
        actualizarInfoPaginador();
    });

    // Filtro Estado Diploma
    $("#filtroEstado").on("change", function () {
        tabla.column(4).search(this.value).draw();
        actualizarInfoPaginador();
    });

    // Paginación personalizada
    $("#btnPrev").click(function () {
        tabla.page("previous").draw("page");
        actualizarInfoPaginador();
        $(".content_Tablas").scrollTop(0);
    });

    $("#btnNext").click(function () {
        tabla.page("next").draw("page");
        actualizarInfoPaginador();
        $(".content_Tablas").scrollTop(0);
    });

    actualizarInfoPaginador();
});
</script>

</body>
</html>
