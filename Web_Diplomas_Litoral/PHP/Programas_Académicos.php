<?php
include "access_control.php";

// Validar que el usuario sea Admin y tenga acceso a esta página
validar_acceso('Admin', __FILE__);

// === CONSULTA PRINCIPAL DE PROGRAMAS ===
$sql = "
    SELECT 
        pg.id AS program_id,
        pg.code,
        pg.name,
        pg.number_of_semesters,
        pg.mode,

        pt.name AS program_type,
        ws.name AS work_shift,

        COUNT(ag.id) AS total_grupos

    FROM programs pg
    JOIN program_types pt ON pg.program_type_id = pt.id
    JOIN work_shifts ws ON pg.schedule_id = ws.id
    LEFT JOIN academic_group ag ON pg.id = ag.program_id

    GROUP BY pg.id
    ORDER BY pg.id DESC;
";
$resultado = $conn->query($sql);

// === FILTROS ===
$sqlTiposProg = "SELECT id, name FROM program_types ORDER BY name ASC";
$tiposProgResultado = $conn->query($sqlTiposProg);

$sqlProgramas = "SELECT id, code, name FROM programs ORDER BY code ASC";
$programasResultado = $conn->query($sqlProgramas);

$sqlJornadas = "SELECT id, name FROM work_shifts ORDER BY name ASC";
$jornadasResultado = $conn->query($sqlJornadas);

$sqlModalidades = "SELECT DISTINCT mode FROM programs ORDER BY mode ASC";
$modalidadesResultado = $conn->query($sqlModalidades);

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Módulo de Gestión de Programas</title>

    <link rel="icon" type="image/x-icon" href="../ICONS/Union (2).svg">

    <link rel="stylesheet" href="../CSS/Tablas.CSS">
    <link rel="stylesheet" href="../CSS/Haeder.CSS">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@800&family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">

    <!-- DataTables -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

    <!-- ICONOS -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>

<body>

<!-- ================= HEADER ================= -->
<header class="top-header">
    <div class="Titulo-Haeder">
        <button id="toggleSidebar" class="menu-btn"><i class='bx bx-menu'></i></button>
        <img src="../IMG/Vector_Logo (1).svg" alt="Logo">
    </div>

    <div class="bottom-header">
        <h3>Programas Académicos</h3>
        <p>Explora y administra los programas educativos disponibles.</p>
    </div>
</header>

<?php include "Header.php"; ?>

<!-- ================= CONTENIDO PRINCIPAL ================= -->
<main class="contenido">

    <!-- === FILTROS === -->
    <section class="Contenedor_Filtros">

        <!-- Buscador general -->
        <div class="Contenedor_Buscador">
            <div class="Buscador">
                <button><i class='bx bx-search-alt-2'></i></button>
                <input type="text" id="buscador" placeholder="Buscar..." maxlength="100">
            </div>
        </div>

        <div class="Contenedor_select">

            <!-- Tipo de programa -->
            <select id="filtroTipoPrograma2" class="filtro">
                <option value="">Tipo de programa</option>
                <?php while ($tipo = $tiposProgResultado->fetch_assoc()) { ?>
                    <option value="<?= $tipo['name'] ?>"><?= $tipo['name'] ?></option>
                <?php } ?>
            </select>

            <!-- Jornada -->
            <select id="filtroJornada2" class="filtro">
                <option value="">Jornada</option>
                <?php while ($j = $jornadasResultado->fetch_assoc()) { ?>
                    <option value="<?= $j['name'] ?>"><?= $j['name'] ?></option>
                <?php } ?>
            </select>

            <!-- Modalidad -->
            <select id="filtroModalidad2" class="filtro">
                <option value="">Modalidad</option>
                <?php while ($m = $modalidadesResultado->fetch_assoc()) { ?>
                    <option value="<?= $m['mode'] ?>"><?= $m['mode'] ?></option>
                <?php } ?>
            </select>

        </div>
    </section>

    <!-- === TABLA === -->
    <section class="Tablas">
        <div class="content_Tablas">

            <table id="miTabla">
                <thead>
                    <tr>
                        <th>Código</th>
                        <th>Programa</th>
                        <th>Tipo</th>
                        <th>Semestres</th>
                        <th>Modalidad</th>
                        <th>Jornada / Turno</th>
                        <th>Grupos creados</th>
                    </tr>
                </thead>

                <tbody>
                    <?php while ($p = $resultado->fetch_assoc()) { ?>
                    <tr>
                        <td><?= $p['code'] ?></td>
                        <td><?= $p['name'] ?></td>
                        <td><?= $p['program_type'] ?></td>
                        <td><?= $p['number_of_semesters'] ?></td>
                        <td><?= $p['mode'] ?></td>
                        <td><?= $p['work_shift'] ?></td>
                        <td><?= $p['total_grupos'] ?></td>
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
document.addEventListener("DOMContentLoaded", () => {
    const inputBuscador = document.getElementById("buscador");
    const filtroTipo = document.getElementById("filtroTipoPrograma");
    const filtroJornada = document.getElementById("filtroJornada");
    const filtroModalidad = document.getElementById("filtroModalidad");

    function aplicarFiltros() {
        const texto = inputBuscador.value.toLowerCase();
        const tipo = filtroTipo.value.toLowerCase();
        const jornada = filtroJornada.value.toLowerCase();
        const modalidad = filtroModalidad.value.toLowerCase();

        const filas = document.querySelectorAll("#miTabla tbody tr");

        filas.forEach(fila => {
            const celdas = fila.getElementsByTagName("td");

            const colPrograma = celdas[1].innerText.toLowerCase();
            const colTipo = celdas[2].innerText.toLowerCase();
            const colModalidad = celdas[4].innerText.toLowerCase();
            const colJornada = celdas[5].innerText.toLowerCase();

            let visible = true;

            if (texto && !colPrograma.includes(texto)) visible = false;
            if (tipo && colTipo !== tipo) visible = false;
            if (modalidad && colModalidad !== modalidad) visible = false;
            if (jornada && colJornada !== jornada) visible = false;

            fila.style.display = visible ? "" : "none";
        });
    }

    inputBuscador.addEventListener("input", aplicarFiltros);
    filtroTipo.addEventListener("change", aplicarFiltros);
    filtroJornada.addEventListener("change", aplicarFiltros);
    filtroModalidad.addEventListener("change", aplicarFiltros);
});

</script>
<script src="../JS/Tablas.js"></script>
</body>
</html>
