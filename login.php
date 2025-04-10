<?php
ob_start(); // Iniciar el almacenamiento en búfer de salida
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar sesión</title>
    <style>
        body {
            font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
        }

        form {
            width: 500px;
            height: auto;
            margin: 0 auto;
            border: 3px solid #B5CDFE;
            border-radius: 0.5rem;
            padding: 20px;
            box-shadow:0.2em 0.2em 0.2em rgba(0, 0, 0, 0.3)
        }

        label {
            display: inline;
        }

        input {
            width: 100%;
            margin-bottom: 10px;
            border: 1px solid #B5CDFE;
            border-radius: 0.2rem;
        }

        input[type="submit"] {
            background-color: #608BE6;
            border: none;
            padding: 0.5em 1em;
            border-radius: 0.5em;
        }

        input[type="submit"]:hover {
            background-color: #5E7BB9;
        }

        p {
            color: red;
            font-weight: bold;

        }

        h1,
        h2 {
            text-align: center;
            color: #0F1A34;
            font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
        }
    </style>
</head>

<body>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <h2>Iniciar sesión</h2>
        Correo: <input type="text" name="correo"><br>
        Contraseña: <input type="password" name="contraseña"><br>
        <?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $correo = $_POST['correo'];
    $contraseña = $_POST['contraseña'];

    // Leer el archivo data.json
    $data = file_get_contents('data.json');
    $usuarios = json_decode($data, true);

    // Buscar el usuario
    foreach ($usuarios as $usuario) {
        if ($usuario['correo'] == $correo && $usuario['contraseña'] == $contraseña) {
            // Iniciar la sesión
            $_SESSION['nombre'] = $usuario['nombre'];
            $_SESSION['correo'] = $usuario['correo'];
            $_SESSION['rol'] = $usuario['rol']; // Establecer el rol en la sesión
            header("Location: upload.php");
            exit();
        }
    }

    echo "<p>Correo o contraseña incorrectos</p>";
    header("Refresh: 2; URL=index.php");
}
ob_end_flush(); // Vaciar y desactivar el almacenamiento en búfer de salida
?>
        <input type="submit" value="Iniciar sesión">
        <br><a href='registrar_usuario.php'>Registrar Usuario</a>
    </form>
</body>

</html>