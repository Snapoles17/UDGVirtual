<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Inicio</title>
    <style>
        body {
            font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
        }

        div {
            width: 500px;
            height: auto;
            margin: 0 auto;
            border: 3px solid #B5CDFE;
            border-radius: 0.5rem;
            padding: 20px;
            box-shadow:0.2em 0.2em 0.2em rgba(0, 0, 0, 0.3)
        }


        p,h1,h3 {
            text-align: center;
            color: #0F1A34;
            font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
        }
    </style>
</head>
<body>
    <h1>Bienvenido a mi producto integrador. Gestión de imagenes</h1>
    <h3>Lenguajes de programación Back End | Sergio Iván Nápoles Chávez</h3>
    <div>
        <?php
    if (isset($_SESSION['nombre'])) {
        echo "<h3>Hola, " . $_SESSION['nombre'] . "!</h3> <p><a href='upload.php'>Administrador de imagenes</a> | <a href='logout.php'>Cerrar sesión</a></p>";
    } else {
        echo "<h3><a href='login.php'>Iniciar sesión</a> | <a href='registrar_usuario.php'>Registrarse</a></h3>";
    }
    ?>
    </div>
</body>
</html>
