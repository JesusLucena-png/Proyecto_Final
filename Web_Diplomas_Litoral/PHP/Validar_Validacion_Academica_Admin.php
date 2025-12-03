<?php
include "access_control.php";
include "MySql_php.php";

validar_acceso('Admin', __FILE__);

// Validar matrícula
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("No se recibió un ID válido.");
}

$enrollment_id = intval($_GET['id']);

$sql = "
    SELECT 
        sv.id AS validacion_id,
        sv.status,
        sv.documen AS file_path,
        req.name AS requerimiento,
        CONCAT_WS(' ', p.first_name, p.second_name, p.last_name, p.second_last_name) AS estudiante
    FROM student_validations sv
    JOIN program_requirements prq ON prq.id = sv.program_requirement_id
    JOIN graduation_requirements req ON req.id = prq.requirement_id
    JOIN enrollments e ON e.id = sv.enrollment_id
    JOIN people p ON p.id = e.people_id
    WHERE sv.enrollment_id = $enrollment_id
";

// Ejecutar consulta
$result = $conn->query($sql);

// Obtener nombre del estudiante antes de que se consuma el fetch
if ($result->num_rows > 0) {
    $primeraFila = $result->fetch_assoc();
    $nombreEstudiante = $primeraFila['estudiante'];
} else {
    die("No hay validaciones para este estudiante.");
}

// Volver a ejecutar la consulta para el while
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Validación de Documentos</title>
    <link rel="icon" type="image/svg+xml" href="../ICONS/Union (2).svg" sizes="12x12">

    <link rel="stylesheet" href="../CSS/Formularios.CSS">
    <link rel="stylesheet" href="../CSS/Haeder.CSS">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@800&family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">

       <!-- ICONOS -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    <link rel="stylesheet" href="../pdfjs/web/viewer.css" media="screen">
</head>

<body style="height: auto;background: one">
    <header class="top-header">
        <div class="Titulo-Haeder">
            <a href="Validacion_Academica.php" class="return"> <i class='bx bx-redo bx-flip-horizontal' ></i> </a>
            <img src="../IMG/Vector_Logo (1).svg" alt="">
        </div>

        <div class="bottom-header">
            <h3>Validación Académica - <?= htmlspecialchars($nombreEstudiante) ?></h3>
            <p>Revisa el estado de las validaciones académicas por programa y estudiante.</p>
        </div>
    </header>

<main class="contenido3">
    <div class="lista">
        <?php while($v = $result->fetch_assoc()): ?>
            <div>
                <b><?= htmlspecialchars($v['requerimiento']) ?></b>
                <p>Estado: <?= htmlspecialchars($v["status"]) ?></p>
                <div class="contentbotones">
                    <?php if ($v['file_path']): ?>
                        <a class="btn azul" onclick="mostrarDocumento('<?= htmlspecialchars($v['file_path']) ?>')"><i class='bx bx-show'></i>Ver PDF</a>
                    <?php endif; ?>

                    <?php if ($v['file_path'] && $v["status"] != "Submitted"): ?>
                        <a class="btn amarillo" onclick="enviarValidacion(<?= $v['validacion_id'] ?>)"><i class='bx bx-edit-alt'></i>Editar</a>
                    <?php endif; ?>

                    <?php if ($v['file_path'] && in_array($v["status"], ['Pending', 'Submitted'])): ?>
                        <a class="btn verde" onclick="actualizarValidacion(<?= $v['validacion_id'] ?>, 'Approved')"><i class='bx bx-check'></i>Aceptar</a>
                        <a class="btn rojo" onclick="actualizarValidacion(<?= $v['validacion_id'] ?>, 'Rejected')"><i class='bx bx-x'></i>Rechazar</a>
                    <?php endif; ?>
                </div>
            </div>
        <?php endwhile; ?>
    </div>

    <div class="visor-container" style="flex-grow:1; height:85vh; border:1px solid #ccc; border-radius:5px;">
        <iframe id="visor" style="width:100%; height:100%; border:none;"></iframe>
    </div>
</main>

<script>
function mostrarDocumento(rutaServidor) {
    const visor = document.getElementById("visor");
    // No necesitamos viewer.css aquí, el iframe lo carga internamente
    visor.src = "/Proyecto_Final/Web_Diplomas_Litoral/pdfjs/web/viewer.html?file=" 
                + encodeURIComponent("/Proyecto_Final/Web_Diplomas_Litoral/" + rutaServidor);
}

function mostrarDocumento(rutaServidor) {
    const visor = document.getElementById("visor");
    visor.src = "/Proyecto_Final/Web_Diplomas_Litoral/pdfjs/web/viewer.html?file=" 
                + encodeURIComponent("/Proyecto_Final/Web_Diplomas_Litoral/" + rutaServidor);
}

function enviarValidacion(id) {
    fetch("enviar_validacion.php?id=" + id)
    .then(r => r.text())
    .then(t => { alert(t); location.reload(); });
}

function actualizarValidacion(id, accion) {
    if (!confirm(`¿Seguro que deseas ${accion === 'Approved' ? 'aceptar' : 'rechazar'} este documento?`)) return;

    fetch(`actualizar_validacion.php?id=${id}&accion=${accion}`)
        .then(r => r.json())
        .then(res => {
            alert(res.success || res.error || "Ocurrió un error.");
            location.reload();
        });
}
</script>

</body>
</html>
