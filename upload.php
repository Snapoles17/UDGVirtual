<?php
ob_start(); // Iniciar el almacenamiento en búfer de salida
session_start();

// Comprobar si el usuario está logueado
if (!isset($_SESSION['nombre'])) {
    header('Location: login.php');
    exit;
}

$dir = 'uploads/';
if ($_SESSION['rol'] !== 'admin') {
    $dir .= $_SESSION['nombre'] . '/';
}
$json_file = $dir . 'images.json';

// Crear el directorio si no existe
if (!file_exists($dir)) {
    mkdir($dir, 0777, true);
}

// Leer el archivo JSON
if (file_exists($json_file)) {
    $json_data = json_decode(file_get_contents($json_file), true);
} else {
    $json_data = [];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($_FILES['files']['name'] as $i => $name) {
        if (move_uploaded_file($_FILES['files']['tmp_name'][$i], $dir . $name)) {
            echo "La imagen $name ha sido subida con éxito.<br>";
            // Agregar la imagen al archivo JSON
            $json_data[$name] = ['name' => $name, 'path' => $dir . $name];
        }
    }
    // Guardar el archivo JSON
    file_put_contents($json_file, json_encode($json_data));
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['delete'])) {
    $fileToDelete = $dir . $_GET['delete'];
    if ($_SESSION['rol'] === 'admin' && isset($_GET['user'])) {
        // Si es administrador y se proporciona un usuario, ajustar la ruta del archivo
        $fileToDelete = 'uploads/' . $_GET['user'] . '/' . $_GET['delete'];
        $json_file = 'uploads/' . $_GET['user'] . '/images.json';
        $json_data = json_decode(file_get_contents($json_file), true);
    }
    if (file_exists($fileToDelete)) {
        if (unlink($fileToDelete)) {
            echo "La imagen " . $_GET['delete'] . " ha sido eliminada.<br>";
            // Eliminar la imagen del archivo JSON
            unset($json_data[$_GET['delete']]);
            // Guardar el archivo JSON
            file_put_contents($json_file, json_encode($json_data));
        } else {
            echo "No se pudo eliminar la imagen " . $_GET['delete'] . ".<br>";
        }
    } else {
        echo "El archivo " . $_GET['delete'] . " no existe.<br>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrador de imagenes</title>
    <style>
        body {
            font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
        }

        form {
            width: 500px;
            height: auto;
            margin: 0 auto;
            border: 3px solid #B5CDFE;
            border-radius: 2rem;
            padding: 20px;
            box-shadow:0.3em 0.3em 0.3em rgba(0, 0, 0, 0.3)
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

        h2 {
            text-align: center;
            color: #0F1A34;
            font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
        }  
        .image-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
            gap: 10px;
        }

        .image-grid img {
            width: 100%;
            height: 100px; /* Ajusta este valor según tus necesidades */
            object-fit: cover;
        }
        .image-grid div {
            margin-bottom: 20px; 
        }
    </style>
</head>

<body>

    <form method="post" enctype="multipart/form-data">
        <h2>Subir Imágenes</h2>
        <?php if (isset($_SESSION['nombre'])) {
    echo "<p>¡Bienvenido, " . $_SESSION['nombre'] . "! Selecciona las imágenes para subir:</p>"; }?>
        
        <input type="file" name="files[]" multiple>
        <input type="submit" value="Subir imágenes" name="submit">
        <?php
        echo "<h2>Panel de Control</h2>";
        if ($_SESSION['rol'] === 'admin') {
            $users = scandir('uploads/');
            foreach ($users as $user) {
                if ($user === '.' || $user === '..') {
                    continue;
                }
                $user_dir = 'uploads/' . $user . '/';
                $user_json_file = $user_dir . 'images.json';
                if (file_exists($user_json_file)) {
                    $user_json_data = json_decode(file_get_contents($user_json_file), true);
                    echo "<div class='image-grid'>";
                    foreach ($user_json_data as $name => $image_data) {
                        echo "<div>
                                <img src='$user_dir$name'>
                                <p>$name
                                <a href='?delete=$name&user=$user'>Eliminar</a></p>
                              </div>";
                    }
                    echo "</div>";
                }
            }
        } else {
            $files = scandir($dir);
            echo "<div class='image-grid'>";
            foreach ($files as $file) {
                // Comprobar si el archivo es una imagen
                $file_info = pathinfo($dir . $file);
                if (is_file($dir . $file) && isset($file_info['extension']) && in_array(strtolower($file_info['extension']), ['jpg', 'jpeg', 'png', 'gif'])) {
                    echo "<div>
                            <img src='$dir$file'>
                            <p>$file
                            <a href='?delete=$file'>Eliminar</a></p>
                          </div>";
                }
            }
            echo "</div>";
        }
        // Botón para cerrar sesión
        echo "<a href='index.php'>Inicio</a> | <a href='logout.php'>Cerrar Sesión</a>";
ob_end_flush(); // Vaciar y desactivar el almacenamiento en búfer de salida
        ?>
    </form>
</body>

</html>

