<?php
include "access_control.php";
include "MySql_php.php";

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("No se recibió un ID válido.");
}

$enrollment_id = intval($_GET['id']);

validar_acceso('Student', __FILE__);

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
        req.name AS requerimiento
    FROM student_validations sv
    JOIN program_requirements prq ON prq.id = sv.program_requirement_id
    JOIN graduation_requirements req ON req.id = prq.requirement_id
    WHERE sv.enrollment_id = $enrollment_id
";
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
            <h3>Validación Académica</h3>
            <p>Revisa el estado de las validaciones académicas por programa y estudiante.</p>
        </div>
    </header>

<main class="contenido3">
    <div class="lista">
        <?php while($v = $result->fetch_assoc()): ?>
            <div>
                <b><?= $v['requerimiento'] ?></b>
                <p>Estado: <?= $v["status"] ?></p>
                
                    <?php if ($v["status"] === "Pending" || $v["status"] === "Pause"): ?>
                        <!-- Subir PDF -->
                        <form class="formArchivo" data-id="<?= $v['validacion_id'] ?>" enctype="multipart/form-data">
                            <input type="file" name="archivo" accept=".pdf" required class="inputArchivo">
                            <div class="contentbotones">
                                <button type="submit" class="btn verde subir">Subir PDF</button>
                                <a type="button" class="btn rojo btnCancelar">Cancelar</a>
                                <a type="button" class="btn azul btnVisualizar" style="display:none;"><i class='bx bx-show'></i>Visualizar</a>
                            </div>
                        </form>
                    <?php elseif (!empty($v['file_path'])): ?>
                        <!-- Campo bloqueado / archivo enviado -->
                        <input type="text" value="<?= basename($v['file_path']) ?>" readonly>
                    <?php endif; ?>

                <div class="contentbotones">
                    <!-- Ver PDF -->
                    <?php if ($v['file_path']): ?>
                        <a class="btn azul" onclick="mostrarDocumento('<?= $v['file_path'] ?>')"><i class='bx bx-show'></i>Ver PDF</a>
                    <?php endif; ?>

                    <!-- Enviar -->
                    <?php if ($v['file_path'] && $v["status"] != "Submitted"): ?>
                        <a onclick="enviarValidacion(<?= $v['validacion_id'] ?>)">Enviar</a>
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
document.querySelectorAll(".formArchivo").forEach(form => {
    const inputArchivo = form.querySelector(".inputArchivo");
    const btnVisualizar = form.querySelector(".btnVisualizar");
    const btnCancelar = form.querySelector(".btnCancelar");

    // Mostrar botón Visualizar solo si hay archivo seleccionado
    inputArchivo.addEventListener("change", () => {
        btnVisualizar.style.display = inputArchivo.files.length ? "inline-block" : "none";
    });

    // Acción de Cancelar: limpiar input y ocultar botón
    btnCancelar.addEventListener("click", () => {
        inputArchivo.value = "";
        btnVisualizar.style.display = "none";
    });

    // Evento Visualizar individual por formulario
    btnVisualizar.addEventListener("click", () => {
        if (inputArchivo.files.length > 0) {
            mostrarDocumentoTemporal(inputArchivo.files[0]);
        }
    });

    // Subir PDF
    form.addEventListener("submit", async e => {
        e.preventDefault();
        let id = form.dataset.id;
        let data = new FormData(form);

        let res = await fetch("subir_validacion.php?id=" + id, {
            method: "POST",
            body: data
        });

        alert(await res.text());
        location.reload();
    });
});

// VISOR TEMPORAL
function mostrarDocumentoTemporal(file) {
    if (!file) return;
    const fileURL = URL.createObjectURL(file);
    const visor = document.getElementById("visor");
    visor.src = "/Proyecto_Final/Web_Diplomas_Litoral/pdfjs/web/viewer.html?file=" 
                + encodeURIComponent(fileURL);
}

// VISOR DE DOCUMENTOS DEL SERVIDOR
function mostrarDocumento(rutaServidor) {
    const visor = document.getElementById("visor");
    visor.src = "/Proyecto_Final/Web_Diplomas_Litoral/pdfjs/web/viewer.html?file=" 
                + encodeURIComponent("/Proyecto_Final/Web_Diplomas_Litoral/" + rutaServidor);
}

// ENVIAR VALIDACIÓN
function enviarValidacion(id) {
    fetch("enviar_validacion.php?id=" + id)
    .then(r => r.text())
    .then(t => { 
        alert(t); 
        location.reload(); 
    });
}
</script>
</body>
</html>
