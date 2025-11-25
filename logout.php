<?php
session_start();

// Si solo queremos cerrar la sesión del usuario, quitamos la clave 'login'
if (isset($_SESSION['login'])) {
    unset($_SESSION['login']);
}


// Redirigir a la página de login (o a la que prefieras)
header('Location: login.php');
exit();