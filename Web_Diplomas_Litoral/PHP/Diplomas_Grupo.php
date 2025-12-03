<?php
include "access_control.php";
include "MySql_php.php";

validar_acceso('Admin', __FILE__);

if (!isset($_GET["id"])) die("Grupo no válido");
$grupo_id = intval($_GET["id"]);

// Consulta principal
$sql = "
SELECT 
    el.id AS enrollment_id,
    p.id AS people_id,
    CONCAT_WS(' ', p.first_name, p.second_name, p.last_name, p.second_last_name) AS nombre,
    pg.name AS programa,
    d.id AS diploma_id,
    d.status AS diploma_status,
    d.certificate_file,
    (SELECT COUNT(*) FROM student_validations sv
     WHERE sv.enrollment_id = el.id AND sv.status = 'Approved') AS aprobadas,
    (SELECT COUNT(*) FROM student_validations sv
     WHERE sv.enrollment_id = el.id) AS total_validaciones
FROM enrollments el
JOIN people p ON el.people_id = p.id
JOIN academic_group ag ON el.academic_group_id = ag.id
JOIN programs pg ON ag.program_id = pg.id
LEFT JOIN diplomas d ON d.enrollment_id = el.id
WHERE ag.id = $grupo_id
ORDER BY 
    aprobadas DESC,
    total_validaciones DESC,
    nombre ASC;
";


$res = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Diplomas Litoral</title>
    <link rel="icon" type="image/svg+xml" href="../ICONS/Union (2).svg" sizes="12x12">

    <!-- CSS -->
    <link rel="stylesheet" href="../CSS/Tablas.CSS">

    <!-- Jquery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

    <!-- Fuentes -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@800&family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">

    <!-- Iconos -->
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
        <h3>Módulo de Diplomas - Grupo</h3>
        <p>Aquí puedes gestionar y consultar los diplomas de cada estudiante.</p>
    </div>
</header>

<?php include "Header.php"; ?>

<!-- =============== CONTENIDO =============== -->
<main class="contenido">

    <!-- =================== FILTROS =================== -->
    <section class="Contenedor_Filtros">
        <!-- Buscador -->
        <div class="Contenedor_Buscador">
            <div class="Buscador">
                <button><i class='bx bx-search-alt-2'></i></button>
                <input type="text" id="buscador" placeholder="Buscar estudiante..." maxlength="100">
            </div>
        </div>
    </section>

    <!-- =================== TABLA =================== -->
    <section class="Tablas">
        <div class="content_Tablas">
            <table id="miTabla">
                <thead>
                    <tr>
                        <th>Estudiante</th>
                        <th>Programa</th>
                        <th>Validaciones</th>
                        <th>Estado Diploma</th>
                        <th>Acción</th>
                    </tr>
                </thead>

                <tbody>
                <?php while ($f = $res->fetch_assoc()) { ?>
                    <tr>
                        <td><?= $f["nombre"] ?></td>
                        <td><?= $f["programa"] ?></td>
                        <td><?= $f["aprobadas"] ?> / <?= $f["total_validaciones"] ?></td>
                        <td><?= $f["diploma_status"] ?? "Pendiente" ?></td>
                        <?php if ($f["aprobadas"] != $f["total_validaciones"]) { ?>
                                <td class="acciones">
                                    <a href="#" class="btn rojo error">
                                        Incompleto
                                    </a>
                                </td>
                        <?php } else if (!$f["diploma_id"]) { ?>
                            <td class="acciones">
                                <a class="btnGenerar btn azul" data-id="<?= $f['enrollment_id'] ?>">
                                    <i class='bx bx-download'></i> Generar Diploma
                                </a>
                            </td>
                         <?php } else { ?>
                            <td class="acciones">
                                <a class="btnImprimir btn amarillo" data-file="..<?= $f['certificate_file'] ?>">
                                    <i class='bx bx-printer'></i> Imprimir Diploma
                                </a>
                            </td>
                        <?php } ?>
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

<!-- =============== SCRIPTS =============== -->
<!-- Imprimir Diploma -->
<script>
$(".btnImprimir").click(function() {
    const file = $(this).data("file");

    let iframe = document.createElement('iframe');
    iframe.style.width = "0";
    iframe.style.height = "0";
    iframe.style.border = "0";
    iframe.style.position = "fixed";

    document.body.appendChild(iframe);
    const doc = iframe.contentWindow.document;

    doc.open();
    doc.write(`
        <html>
        <head>
            <title>Imprimir Diploma</title>
            <style>
                @page { size: landscape; margin: 0; }
                body { margin:0; padding:0; }
                img { width:100%; height:100%; object-fit:contain; }
            </style>
        </head>
        <body>
            <img src="${file}">
        </body>
        </html>
    `);
    doc.close();

    iframe.onload = function() {
        iframe.contentWindow.print();
        document.body.removeChild(iframe);
    };
});
</script>

<!-- Generar Diploma -->
<script>
$(".btnGenerar").click(function () {
    let id = $(this).data("id");
    window.open("Diploma_Generar.php?matricula=" + id, "_blank");
});
</script>

<script src="../JS/Tablas.js"></script>

</body>
</html>
