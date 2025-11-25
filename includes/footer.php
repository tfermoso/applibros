<?php
// in footer, show optional flash message and include common scripts
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// flash message support (optional)
$flash = $_SESSION['flash'] ?? null;
if (isset($_SESSION['flash'])) {
    unset($_SESSION['flash']);
}

$year = date('Y');
?>
<?php if (!empty($flash)): ?>
    <div class="container mt-3">
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            <?php echo htmlspecialchars($flash); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
        </div>
    </div>
<?php endif; ?>

<!-- footer con mt-auto para "empujar" abajo -->
<footer class="bg-light py-3 mt-auto">
    <div class="container d-flex justify-content-between align-items-center">
        <small class="text-muted">© <?php echo $year; ?> AppLibros. Todos los derechos reservados.</small>
        <small class="text-muted"><a href="/applibros/index.php" class="text-decoration-none">Inicio</a></small>
    </div>
</footer>

<!-- Scripts comunes de la app -->
<script src="/applibros/assets/js/app.js"></script>

</body>
</html>
