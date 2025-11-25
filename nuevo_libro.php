<?php
session_start();

$error = '';
$titulo = '';
$sinopsis = '';

if (!isset($_SESSION["login"]) || $_SESSION["login"] === "") {
    header("Location: login.php");
    exit();
}
$usuario = $_SESSION["login"];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = trim($_POST['titulo'] ?? '');
    $sinopsis = trim($_POST['sinopsis'] ?? '');

    if ($titulo === '' || $sinopsis === '') {
        $error = 'Completa título y sinopsis.';
    } else {
        // validación del archivo
        if (!isset($_FILES['imagen']) || $_FILES['imagen']['error'] !== UPLOAD_ERR_OK) {
            $error = 'Debes subir una imagen del libro.';
        } else {
            $file = $_FILES['imagen'];
            $maxSize = 2 * 1024 * 1024; // 2 MB

            if ($file['size'] > $maxSize) {
                $error = 'La imagen excede el tamaño máximo (2 MB).';
            } else {
                // comprobar tipo MIME real
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
                    // preparar carpeta de destino
                    $uploadDir = __DIR__ . DIRECTORY_SEPARATOR . 'uploads';
                    if (!is_dir($uploadDir)) {
                        mkdir($uploadDir, 0755, true);
                    }

                    // generar nombre único y mover archivo
                    $ext = $allowed[$mime];
                    $newName = uniqid('img_', true) . '.' . $ext;
                    $targetPath = $uploadDir . DIRECTORY_SEPARATOR . $newName;

                    if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
                        $error = 'Error al guardar la imagen en el servidor.';
                    } else {
                        // ruta relativa para guardar en sesión / mostrar
                        $imagenPath = 'uploads/' . $newName;

                        // guardar libro en sesión (nota: en producción usar BD)
                        if (!isset($_SESSION['libros'])) {
                            $_SESSION['libros'] = [];
                        }
                        if (!isset($_SESSION['libros'][$usuario])) {
                            $_SESSION['libros'][$usuario] = [];
                        }
                        $_SESSION['libros'][$usuario][] = [
                            'titulo' => $titulo,
                            'sinopsis' => $sinopsis,
                            'imagen' => $imagenPath
                        ];

                        header('Location: dashboard.php');
                        exit();
                    }
                }
            }
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
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h1 class="h4 mb-0">Nuevo libro</h1>
                        <a href="dashboard.php" class="btn btn-outline-secondary btn-sm">Volver</a>
                    </div>

                    <?php if ($error): ?>
                        <div class="alert alert-danger" role="alert">
                            <?php echo htmlspecialchars($error); ?>
                        </div>
                    <?php endif; ?>

                    <form action="" method="post" enctype="multipart/form-data" class="needs-validation" novalidate>
                        <div class="mb-3">
                            <label for="titulo" class="form-label">Título</label>
                            <input type="text" id="titulo" name="titulo" value="<?php echo htmlspecialchars($titulo); ?>" class="form-control" required>
                            <div class="invalid-feedback">Introduce el título del libro.</div>
                        </div>

                        <div class="mb-3">
                            <label for="sinopsis" class="form-label">Sinopsis</label>
                            <textarea id="sinopsis" name="sinopsis" rows="5" class="form-control" required><?php echo htmlspecialchars($sinopsis); ?></textarea>
                            <div class="invalid-feedback">Introduce la sinopsis del libro.</div>
                        </div>

                        <div class="mb-3">
                            <label for="imagen" class="form-label">Portada (JPG/PNG/GIF, máx 2MB)</label>
                            <input type="file" id="imagen" name="imagen" class="form-control" accept="image/*" required>
                            <div class="form-text">Se recomienda 800x1200px o similar. Dejar vacío para no subir imagen (si fuera opcional).</div>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="dashboard.php" class="btn btn-outline-secondary">Cancelar</a>
                            <button type="submit" class="btn btn-primary">Agregar libro</button>
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