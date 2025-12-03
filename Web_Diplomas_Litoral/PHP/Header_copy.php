<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title></title>
        <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
        <link rel="stylesheet" href="../CSS/Haeder.CSS">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@800&family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    </head>
    <body>
        <!-- HEADER LATERAL DESPLEGABLE -->
        <aside id="sidebar" class="sidebar">
            <div class="sidebar-top">
                <div class="User">
                    <img src="../IMG/Starter pfp.jpeg" alt="" draggable="false">
                    <h4><a href="Usuario_Ver_copy.php" draggable="false"><?= $_SESSION["Nombre"]?></a></h4>
                </div>
                <ul>
                    <a href="../PHP/Validacion_Academica_Estudiante.php"><li><i class='bx bx-spreadsheet'></i><span>Validacion Academica</span></li></a>
                </ul>
            </div>
            <div class="sidebar-bottom">
                    <a href="../PHP/Cerrar_Secion.php"><i class='bx bx-spreadsheet'></i><span>Cerrar secion</span></a>
            </div>
        </aside>
        <script src="../JS/Header.js"></script>
    </body>
</html>
