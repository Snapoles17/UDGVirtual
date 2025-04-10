<?php
session_start();

function mostrarContenido() {
    if (isset($_SESSION['nombre'])) {
        return "<h3>Hola, " . htmlspecialchars($_SESSION['nombre']) . "!</h3> 
        <p><a href='upload.php'>Administrador de imagenes</a> | 
        <a href='logout.php'>Cerrar sesión</a></p>";
    } else {
        return "<h3><a href='login.php'>Iniciar sesión</a> | 
        <a href='registrar_usuario.php'>Registrarse</a></h3>";
    }
}
