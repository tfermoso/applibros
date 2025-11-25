<?php
session_start();

if (isset($_SESSION["login"]) && $_SESSION["login"]!=="") {
    $usuario=$_SESSION["login"];
    $libros=$_SESSION["libros"][$usuario] ?? [];   
}else {
    header("Location: login.php");
    exit();
}
include_once "includes/head.php";
include_once "includes/header.php";
?>

<main class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 mb-0">Mis Libros</h1>
        <a href="nuevo_libro.php" class="btn btn-primary btn-sm">Agregar nuevo libro</a>
    </div>

    <div class="table-responsive">
        <table class="table table-striped table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th scope="col">Título</th>
                    <th scope="col">Sinopsis</th>
                    <th scope="col">Portada</th>
                    <th scope="col" class="text-end">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($libros)): ?>
                    <?php foreach ($libros as $libro): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($libro['titulo']); ?></td>
                            <td class="text-truncate" style="max-width: 40ch;"><?php echo htmlspecialchars($libro['sinopsis']); ?></td>
                            <td>
                                <?php if (!empty($libro['imagen']) && file_exists(__DIR__ . '/' . ltrim($libro['imagen'], '/\\'))): ?>
                                    <img src="<?php echo htmlspecialchars($libro['imagen']); ?>" alt="Portada de <?php echo htmlspecialchars($libro['titulo']); ?>" class="img-thumbnail" style="max-width:120px;">
                                <?php else: ?>
                                    <div class="text-muted small">Sin imagen</div>
                                <?php endif; ?>
                            </td>
                            <td class="text-end">
                                <a href="editar_libro.php?titulo=<?php echo urlencode($libro['titulo']); ?>" class="btn btn-sm btn-outline-secondary me-1">Editar</a>
                                <a href="borrar_libro.php?titulo=<?php echo urlencode($libro['titulo']); ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('¿Estás seguro de que deseas eliminar este libro?');">Eliminar</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" class="text-center">No tienes libros añadidos.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</main>

<?php
include_once "includes/footer.php"; 
?>