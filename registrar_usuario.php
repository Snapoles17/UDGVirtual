<?php
ob_start(); // Iniciar el almacenamiento en búfer de salida
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de usuario</title>
</head>
<body>
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
            color: blue;
            font-weight: bold;
        }
        h2 {
            text-align: center;
            color: #0F1A34;
            font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
        }
    </style>
</head>
<body>
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
        <h2>Registrar Usuario</h2>
    Nombre: <input type="text" name="nombre"><br>
    Correo: <input type="text" name="correo"><br>
    Contraseña: <input type="password" name="contraseña"><br>
    Rol: <select name="rol">
        <option value="usuario">Usuario</option>
        <option value="admin">Administrador</option>
    </select></br></br>
    <input type="submit" value="Registrar">
    <br><a href='login.php'>Iniciar sesión</a>
    <?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $correo = $_POST['correo'];
    $contraseña = $_POST['contraseña'];
    $rol = $_POST['rol'];

    $nuevo_usuario = array("nombre" => $nombre, "correo" => $correo, "contraseña" => $contraseña, "rol" => $rol);

    // Leer el archivo JSON existente
    $json_data = file_get_contents('data.json');

    // Convertir los datos JSON a un array de PHP
    $usuarios = json_decode($json_data, true);

    // Agregar el nuevo usuario al array de usuarios
    $usuarios[] = $nuevo_usuario;

    // Convertir el array de usuarios a JSON
    $json_data = json_encode($usuarios);

    // Guardar los datos en el archivo JSON
    file_put_contents('data.json', $json_data);

    echo "<p>¡Registro exitoso! Ahora sera redirigido</p>";
    header("Refresh: 2; URL=login.php");
}
ob_end_flush(); // Vaciar y desactivar el almacenamiento en búfer de salida
?>
</form>
</body>
</html>