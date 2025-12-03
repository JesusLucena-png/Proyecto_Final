<?php
include "access_control.php";
include "MySql_php.php";

validar_acceso('Admin', __FILE__);

if (!isset($_GET["matricula"])) die("Matrícula no válida");
$matricula_id = intval($_GET["matricula"]);

// Obtener info del estudiante y programa, incluyendo documento
$sql = "
SELECT 
    el.id AS enrollment_id,
    CONCAT_WS(' ',p.first_name,p.second_name,p.last_name,p.second_last_name) AS nombre,
    pg.name AS programa,
    CONCAT(it.code,'-',p.document_id) AS documento_completo
FROM enrollments el
JOIN people p ON el.people_id = p.id
JOIN academic_group ag ON el.academic_group_id = ag.id
JOIN programs pg ON ag.program_id = pg.id
JOIN identity_types it ON p.document_type_id = it.id
WHERE el.id = $matricula_id
";
$f = $conn->query($sql)->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Generar Diploma</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Diplomas Litoral</title>
    <link rel="icon" type="image/svg+xml" href="../ICONS/Union (2).svg" sizes="12x12">

    <link rel="stylesheet" href="../CSS/Formularios.CSS">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@800&family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">


    <!-- ICONOS -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <!-- intl-tel-input -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/css/intlTelInput.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/intlTelInput.min.js"></script>
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

<main class="contenido">
    <div class="content_canvas">
        <h3 class="h3">Generando Diploma de:  <?= htmlspecialchars($f['nombre']) ?></h3>
        <h3 class="Diploma">
            <canvas id="miCanvas" width="3000" height="2000"></canvas>
        </h3>
        <br>
        <a class="btn amarillo" onclick="guardarDiploma()"><i class='bx bx-download'></i> Generar Diploma</a>
    </div>
</main>
<script>
const canvas = document.getElementById("miCanvas");
const ctx = canvas.getContext("2d");
<?php
$meses = [
    "enero", "febrero", "marzo", "abril", "mayo", "junio",
    "julio", "agosto", "septiembre", "octubre", "noviembre", "diciembre"
];

$dia  = date("d");
$mes  = $meses[date("n") - 1];
$anio = date("Y");
?>
// Fondo del diploma
const img = new Image();
img.src = "../uploads/diplomas/Group 3.svg"; 
img.onload = () => {
    ctx.drawImage(img, 0, 0, canvas.width, canvas.height);

    ctx.fillStyle = "black";
    ctx.textAlign = "center";

    // Nombre de la institución
    ctx.font = "80px 'Playfair Display'";
    ctx.fillText("La Corporación de Educación Superior del Litoral", canvas.width/2, 550);

    // Introducción
    ctx.font = "40px Arial";
    ctx.fillText("Otorga el presente diploma a:", canvas.width/2, 610);

    // Nombre estudiante
    ctx.font = "110px 'Pinyon Script'";
    ctx.fillText("<?= addslashes($f['nombre']) ?>", canvas.width/2, 750);

    // Documento
    ctx.font = "50px 'Pinyon Script'";
    ctx.fillText("Con N° Documento: <?= addslashes($f['documento_completo']) ?>", canvas.width/2, 820);

    // Texto principal
    ctx.font = "50px Arial";
    ctx.fillText("Por haber cumplido satisfactoriamente con todos los requisitos académicos,", canvas.width/2, 920);
    ctx.fillText("administrativos y reglamentarios establecidos para obtener el título de:", canvas.width/2, 975);

    // Nombre del programa
    ctx.font = "80px 'Playfair Display'";
    ctx.fillText("<?= addslashes($f['programa']) ?>", canvas.width/2, 1150);

    // Leyes y normas
    ctx.font = "40px Arial";
    ctx.fillText("De conformidad con lo dispuesto por la Ley 30 de 1992, el registro calificado vigente y las normas", canvas.width/2, 1300);
    ctx.fillText("institucionales que rigen la formación técnica profesional en Colombia.", canvas.width/2, 1350);

    // Fecha fija
    ctx.fillText("Se expide en la ciudad de Barranquilla, el dia <?= $dia ?> del mes de <?= $mes ?> del año <?= $anio ?>.", canvas.width/2, 1400);

    // Firmas
    ctx.font = "35px Arial";
    ctx.fillText("Dr. Juan Carlos Robledo Fernández", 770, 1800);
    ctx.fillText("Fernando Ruiz Ohlsen", 2250, 1800);
    ctx.font = "30px Arial";
    ctx.fillText("Rector", 770, 1850);
    ctx.fillText("Vicerrector Académico", 2250, 1850);

    // Información adicional
    ctx.fillText("Código del Diploma: [  ]", 1500, 1750);
    ctx.fillText("Acta de Grado Nº: [  ]", 1500, 1800);
    ctx.fillText("Folio: [  ]", 1500, 1850);
};

function guardarDiploma() {
    const imagen = canvas.toDataURL("image/png");

    fetch("guardar_diploma_final.php", {
        method:"POST",
        headers: {"Content-Type": "application/json"},
        body: JSON.stringify({
            matricula: <?= $matricula_id ?>,
            imagen: imagen
        })
    })
    .then(r => r.text())
    .then(url => {
        if(url.includes("uploads/diplomas")){
            alert("Diploma generado correctamente!");
            window.location.href = "Modulo_Diplomas_Admin.php";
        } else {
            alert("Error al generar diploma: " + url);
        }
    });
}
</script>

</body>
</html>
