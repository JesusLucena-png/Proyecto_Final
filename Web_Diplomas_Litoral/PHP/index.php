<?php 
session_start();

// Recibir errores enviados desde validar_login.php
$errores = $_SESSION["errores"] ?? [];
unset($_SESSION["errores"]);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Diplomas Litoral</title>
    <link rel="icon" type="image/x-icon" href="../ICONS/Union (2).svg">
    <!-- ========================================= -->
    <!-- ENLACES CSS PRINCIPALES / MAIN STYLES -->
    <!-- ========================================= -->
    <link rel="stylesheet" href="../CSS/index.CSS">    
    <!-- ========================================= -->
    <!-- ENLACES DE FUENTES / FONT LINKS -->
    <!-- ========================================= -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@800&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

    <!-- ========================================= -->
    <!-- ENLACES DE ÍCONOS / ICON LINKS -->
    <!-- ========================================= -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>
<body>
    <section class="login-section">
        <div class="login-section__image-container">
            <div class="login-section__overlay"></div>
            <img src="../IMG/home_img_2_2-2048x1152 1.svg" alt="Imagen corporativa" class="login-section__image">
        </div>
        
        <div class="login-section__form-container">
            <div class="login-form">
                <img src="../IMG/Group 118 (1).svg" class="vector" alt="Logo Litoral">
            </div>

            <form class="login-form" method="post" id="form" action="sign_in.php">
                <div class="login-form__group Titulo">  
                    <h2>SIGN IN</h2>
                </div>
                <?php if (!empty($errores["login"])): ?>
                    <div class="error-msg"><?= $errores["login"] ?></div>
                <?php endif; ?>
                <div class="login-form__group">  
                    <label for="Roles" class="login-form__label">User Rol</label>
                    <select name="roles" class="login-form__input-select" required>
                        <option value="">Seleccione...</option>
                        <option value="Student">Student</option>
                        <option value="Admin">Admin</option>
                    </select>
                </div>
                <div class="login-form__group">  
                    <label for="usuario" class="login-form__label">User Name</label>
                    <input type="text" id="usuario" name="usuario"  placeholder="User Name" minlength="5" maxlength="50"  class="login-form__input-text" required>
                </div>

                <div class="login-form__group"> 
                    <label for="contraseña" class="login-form__label">Password</label>
                    <div class="login-form__password-group">
                        <input type="password" id="contraseña" name="contraseña" placeholder="Password" minlength="5" maxlength="60" pattern="(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#$%^&*()_+\-=?\[\]{};:,.<>]).+" title="Debe contener al menos una letra mayúscula, un número y un carácter especial." class="login-form__input-Password" required>
                        <div class="login-form__icon">
                            <i class="login-form__icon login-form__icon--show bx bx-show"></i>
                            <i class="login-form__icon login-form__icon--hide bx bx-hide"></i>
                        </div>
                        
                    </div>
                    <div class="login-form__recovery-link">
                        <a href="#" class="recovery-link">Forgot Password?</a>
                    </div>
                </div>

                <div class="login-form__group"> 
                    <button type="submit" class="login-form__submit">INICIO</button>
                </div>
            </form>  
        </div>
    </section>
    <script>
document.addEventListener("click", () => {
    const error = document.querySelector(".error-msg");
    if (error) {
        error.remove(); // elimina completamente el div
    }
}, { once: true }); // solo se ejecuta una vez
</script>

    <script src="../JS/Input_Password.JS"></script>
</body>
</html>