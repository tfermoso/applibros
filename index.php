<?php
session_start();

$pageTitle = 'AppLibros - Inicio';
include_once __DIR__ . '/includes/head.php';

// Recopilar todos los libros de la sesión (de todos los usuarios) sin mostrar usuarios
$allBooks = [];
if (isset($_SESSION['libros']) && is_array($_SESSION['libros'])) {
    foreach ($_SESSION['libros'] as $userBooks) {
        if (!is_array($userBooks)) continue;
        foreach ($userBooks as $book) {
            // normalizar estructura mínima
            $allBooks[] = [
                'titulo' => $book['titulo'] ?? '',
                'sinopsis' => $book['sinopsis'] ?? '',
                'imagen' => $book['imagen'] ?? ''
            ];
        }
    }
}
?>
<nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom">
    <div class="container">
        <a class="navbar-brand fw-bold" href="/applibros/index.php">AppLibros</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#applibrosNav" aria-controls="applibrosNav" aria-expanded="false" aria-label="Mostrar navegación">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="applibrosNav">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item"><a class="nav-link" href="/applibros/index.php">Inicio</a></li>
            </ul>

            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                <?php if (isset($_SESSION['login']) && $_SESSION['login'] !== ''): ?>
                    <li class="nav-item"><a class="nav-link" href="/applibros/dashboard.php">Mi dashboard</a></li>
                    <li class="nav-item"><a class="nav-link" href="/applibros/nuevo_libro.php">Agregar libro</a></li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fa fa-user me-1" aria-hidden="true"></i><?php echo htmlspecialchars($_SESSION['login']); ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                            <li><a class="dropdown-item" href="/applibros/dashboard.php">Dashboard</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-danger" href="/applibros/logout.php">Cerrar sesión</a></li>
                        </ul>
                    </li>
                <?php else: ?>
                    <li class="nav-item"><a class="nav-link" href="/applibros/login.php">Identificarse</a></li>
                    <li class="nav-item"><a class="nav-link" href="/applibros/register.php">Registrar</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<main class="container my-4 flex-grow-1">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h4 mb-0">Todos los libros</h1>
    </div>

    <?php if (empty($allBooks)): ?>
        <div class="alert alert-secondary">No hay libros publicados todavía.</div>
    <?php else: ?>
        <div class="row gx-3 gy-4">
            <?php foreach ($allBooks as $book): ?>
            <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                <article class="card h-100 shadow-sm">
                    <?php
                        $imgHtml = '<div class="bg-light d-flex align-items-center justify-content-center" style="height:200px;color:#888;">Sin imagen</div>';
                        if (!empty($book['imagen'])) {
                            $imgPath = __DIR__ . '/' . ltrim($book['imagen'], '/\\');
                            if (file_exists($imgPath)) {
                                $imgHtml = '<img src="'.htmlspecialchars($book['imagen']).'" class="card-img-top" alt="'.htmlspecialchars($book['titulo']).'" style="height:200px;object-fit:cover;">';
                            }
                        }
                        echo $imgHtml;
                    ?>
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title mb-2" style="min-height:48px"><?php echo htmlspecialchars($book['titulo']); ?></h5>
                        <p class="card-text text-muted small" style="max-height:5.5rem;overflow:hidden;"><?php echo nl2br(htmlspecialchars($book['sinopsis'])); ?></p>
                        <div class="mt-auto pt-2">
                            <a href="index.php" class="btn btn-outline-primary btn-sm disabled">Ver</a>
                        </div>
                    </div>
                </article>
            </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <hr class="mt-5">
    
</main>



<?php include_once __DIR__ . '/includes/footer.php'; ?>