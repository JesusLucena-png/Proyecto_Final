<?php
if (!isset($_GET["file"])) die("Archivo inválido");
$file = $_GET["file"];
?>

<!DOCTYPE html>
<html>
<head>
<title>Diploma Generado</title>
<style>
body { text-align: center; background: #f4f4f4; }
img { max-width: 90%; border: 3px solid #000; }
</style>
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

<?php include "Header.php"; ?>

<main class="contenido">
    <h2>Diploma Generado</h2>

    <img src="<?= $file ?>" alt="Diploma">

    <br><br>

    <button onclick="window.print()">Imprimir</button>
</main>
</body>
</html>
