<?php
session_start();

if (!isset($_SESSION["login"]) || $_SESSION["login"] === "") {
    header("Location: login.php");
    exit();
}
$usuario = $_SESSION["login"];

$originalTitle = null;
if (isset($_GET['titulo'])) {
    $originalTitle = trim($_GET['titulo']);
} elseif (isset($_POST['original_titulo'])) {
    $originalTitle = trim($_POST['original_titulo']);
}

if ($originalTitle === null || $originalTitle === '') {
    header("Location: dashboard.php");
    exit();
}

// Buscar libro por título
$index = null;
if (isset($_SESSION['libros'][$usuario]) && is_array($_SESSION['libros'][$usuario])) {
    foreach ($_SESSION['libros'][$usuario] as $i => $b) {
        if (isset($b['titulo']) && $b['titulo'] === $originalTitle) {
            $index = $i;
            break;
        }
    }
}

if ($index === null || !isset($_SESSION['libros'][$usuario][$index])) {
    header("Location: dashboard.php");
    exit();
}

$book = $_SESSION['libros'][$usuario][$index];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Si el libro tiene imagen, intentamos borrarla (solo si está en uploads/)
    $imagen = $book['imagen'] ?? '';
    if (!empty($imagen)) {
        $uploadDir = __DIR__ . DIRECTORY_SEPARATOR . 'uploads';
        $oldFile = $uploadDir . DIRECTORY_SEPARATOR . basename($imagen);
        $realOld = realpath($oldFile);
        $realUploadDir = realpath($uploadDir);
        if ($realOld !== false && $realUploadDir !== false && strpos($realOld, $realUploadDir) === 0 && file_exists($oldFile)) {
            @unlink($oldFile);
        }
    }

    // Borrar libro de la sesión
    unset($_SESSION['libros'][$usuario][$index]);

    // Reindexar el array para evitar huecos en los índices
    $_SESSION['libros'][$usuario] = array_values($_SESSION['libros'][$usuario]);

    header("Location: dashboard.php");
    exit();
}
include_once 'includes/head.php';
include_once 'includes/header.php'; 
?>

    <h1>Confirmar borrado</h1>
    <p>¿Seguro que quieres borrar este libro?</p>

    <h2><?php echo htmlspecialchars($book['titulo'] ?? ''); ?></h2>
    <?php if (!empty($book['imagen'])): ?>
        <div>
            <img src="<?php echo htmlspecialchars($book['imagen']); ?>" alt="Imagen del libro" style="max-width:200px;">
        </div>
    <?php endif; ?>
    <p><?php echo nl2br(htmlspecialchars($book['sinopsis'] ?? '')); ?></p>

    <form action="?titulo=<?php echo urlencode($originalTitle); ?>" method="post">
        <input type="hidden" name="original_titulo" value="<?php echo htmlspecialchars($originalTitle); ?>">
        <button type="submit">Sí, borrar libro</button>
        <a href="dashboard.php">Cancelar</a>
    </form>
<?php
include_once "includes/footer.php";     
?>
