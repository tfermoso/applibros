<?php
session_start();

$error = '';
if (!isset($_SESSION["login"]) || $_SESSION["login"] === "") {
    header("Location: login.php");
    exit();
}
$usuario = $_SESSION["login"];

// título original a buscar (puede venir en GET o en POST como original_titulo)
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

// encontrar índice del libro por título
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
$titulo = $book['titulo'] ?? '';
$sinopsis = $book['sinopsis'] ?? '';
$imagenActual = $book['imagen'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = trim($_POST['titulo'] ?? '');
    $sinopsis = trim($_POST['sinopsis'] ?? '');

    if ($titulo === '' || $sinopsis === '') {
        $error = 'Completa título y sinopsis.';
    } else {
        $newImagePath = $imagenActual;

        // manejar subida de imagen opcional
        if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] !== UPLOAD_ERR_NO_FILE) {
            if ($_FILES['imagen']['error'] !== UPLOAD_ERR_OK) {
                $error = 'Error en la subida de imagen.';
            } else {
                $file = $_FILES['imagen'];
                $maxSize = 2 * 1024 * 1024; // 2 MB
                if ($file['size'] > $maxSize) {
                    $error = 'La imagen excede el tamaño máximo (2 MB).';
                } else {
                    $finfo = new finfo(FILEINFO_MIME_TYPE);
                    $mime = $finfo->file($file['tmp_name']);
                    $allowed = [
                        'image/jpeg' => 'jpg',
                        'image/png' => 'png',
                        'image/gif' => 'gif'
                    ];

                    if (!array_key_exists($mime, $allowed)) {
                        $error = 'Tipo de imagen no permitido. Usa JPG, PNG o GIF.';
                    } else {
                        $uploadDir = __DIR__ . DIRECTORY_SEPARATOR . 'uploads';
                        if (!is_dir($uploadDir)) {
                            mkdir($uploadDir, 0755, true);
                        }

                        $ext = $allowed[$mime];
                        $newName = uniqid('img_', true) . '.' . $ext;
                        $targetPath = $uploadDir . DIRECTORY_SEPARATOR . $newName;

                        if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
                            $error = 'Error al guardar la imagen en el servidor.';
                        } else {
                            // prefiero usar rutas relativas para mostrar en HTML
                            $newImagePath = 'uploads/' . $newName;

                            // borrar imagen anterior si existe y está en uploads/
                            if (!empty($imagenActual)) {
                                $oldFile = __DIR__ . DIRECTORY_SEPARATOR . basename($imagenActual);
                                $realOld = realpath($oldFile);
                                $realUploadDir = realpath($uploadDir);
                                if ($realOld !== false && $realUploadDir !== false && strpos($realOld, $realUploadDir) === 0 && file_exists($oldFile)) {
                                    @unlink($oldFile);
                                }
                            }
                        }
                    }
                }
            }
        }

        if ($error === '') {
            // guardar cambios en sesión
            $_SESSION['libros'][$usuario][$index] = [
                'titulo' => $titulo,
                'sinopsis' => $sinopsis,
                'imagen' => $newImagePath
            ];

            header('Location: dashboard.php');
            exit();
        }
    }
}
include_once 'includes/head.php';
include_once 'includes/header.php';
?>

<main class="container my-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h1 class="h4 mb-3">Editar libro</h1>

                    <?php if ($error): ?>
                        <div class="alert alert-danger" role="alert">
                            <?php echo htmlspecialchars($error); ?>
                        </div>
                    <?php endif; ?>

                    <form action="?titulo=<?php echo urlencode($originalTitle); ?>" method="post" enctype="multipart/form-data" class="needs-validation" novalidate>
                        <input type="hidden" name="original_titulo" value="<?php echo htmlspecialchars($originalTitle); ?>">

                        <div class="mb-3">
                            <label for="titulo" class="form-label">Título</label>
                            <input type="text" id="titulo" name="titulo" value="<?php echo htmlspecialchars($titulo); ?>" class="form-control" required>
                            <div class="invalid-feedback">Introduce el título del libro.</div>
                        </div>

                        <div class="mb-3">
                            <label for="sinopsis" class="form-label">Sinopsis</label>
                            <textarea id="sinopsis" name="sinopsis" rows="6" class="form-control" required><?php echo htmlspecialchars($sinopsis); ?></textarea>
                            <div class="invalid-feedback">Introduce la sinopsis del libro.</div>
                        </div>

                        <?php if (!empty($imagenActual)): ?>
                            <div class="mb-3">
                                <label class="form-label d-block">Imagen actual</label>
                                <img src="<?php echo htmlspecialchars($imagenActual); ?>" alt="Imagen del libro" class="img-fluid img-thumbnail" style="max-width: 260px;">
                            </div>
                        <?php endif; ?>

                        <div class="mb-3">
                            <label for="imagen" class="form-label">Sustituir imagen (opcional, JPG/PNG/GIF, máx 2MB)</label>
                            <input type="file" id="imagen" name="imagen" class="form-control" accept="image/*">
                            <div class="form-text">Dejar vacío para mantener la imagen actual.</div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <a href="dashboard.php" class="btn btn-outline-secondary">Cancelar</a>
                            </div>
                            <div>
                                <button type="submit" class="btn btn-success">Guardar cambios</button>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</main>

<?php
include_once "includes/footer.php"; 
?>