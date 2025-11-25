<?php
// iniciar sesión solo si no está iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// redirigir si el usuario no está autenticado
if (!isset($_SESSION['login']) || $_SESSION['login'] === '') {
    header('Location: /applibros/login.php');
    exit();
}

$username = htmlspecialchars($_SESSION['login'] ?? '');
?>
<nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom">
    <div class="container-fluid">
        <a class="navbar-brand fw-bold" href="/applibros/index.php">AppLibros</a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#applibrosNavbar" aria-controls="applibrosNavbar" aria-expanded="false" aria-label="Mostrar navegación">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="applibrosNavbar">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link" href="/applibros/dashboard.php">Mi dashboard</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/applibros/nuevo_libro.php">Agregar libro</a>
                </li>
            </ul>

            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="usuarioDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fa fa-user me-1" aria-hidden="true"></i><?php echo $username; ?>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="usuarioDropdown">
                        <li><a class="dropdown-item" href="/applibros/dashboard.php"><i class="fa fa-tachometer-alt me-1"></i> Dashboard</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-danger" href="/applibros/logout.php"><i class="fa fa-sign-out-alt me-1"></i> Cerrar sesión</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>