<?php
session_start();

$error = '';
$usernameValue = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $email = trim($_POST['email'] ?? '');
    $usernameValue = $username;

    if ($username === '' || $password === '' || $email === '') {
        $error = 'Rellena todos los campos.';
    } elseif (strlen($username) < 3) {
        $error = 'El usuario debe tener al menos 3 caracteres.';
    } elseif (strlen($password) < 6) {
        $error = 'La contraseña debe tener al menos 6 caracteres.';
    } else {
        require_once "config.php";
        require_once __DIR__ . '/includes/functions.php';

        $conn = conectarBaseDatos();

        $stmt = $conn->prepare("SELECT usuario_id FROM usuario WHERE nombre = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $error = 'El usuario ya existe.';
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $conn->prepare("INSERT INTO usuario (nombre, email, password) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $username, $email, $hash);

            if ($stmt->execute()) {
                $_SESSION['flash'] = 'Usuario registrado correctamente. Ya puedes iniciar sesión.';
                header('Location: login.php');
                exit();
            } else {
                $error = 'Error al registrar el usuario. Inténtalo de nuevo.';
            }
        }
        $stmt->close();
        $conn->close();
    }
}

$pageTitle = 'Registrar - AppLibros';
include_once __DIR__ . '/includes/head.php';
?>
<main class="container my-5">
    <div class="row justify-content-center">
        <div class="col-12 col-sm-10 col-md-8 col-lg-6">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h2 class="card-title mb-4 text-center">Registrar usuario</h2>

                    <?php if ($error): ?>
                        <div class="alert alert-danger" role="alert">
                            <?php echo htmlspecialchars($error); ?>
                        </div>
                    <?php endif; ?>

                    <form action="" method="post" class="needs-validation" novalidate>
                        <div class="mb-3">
                            <label for="username" class="form-label">Usuario</label>
                            <input type="text" id="username" name="username" class="form-control"
                                value="<?php echo htmlspecialchars($usernameValue); ?>" required autofocus>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" id="email" name="email" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Contraseña</label>
                            <input type="password" id="password" name="password" class="form-control" required>
                        </div>

                        <div class="d-flex justify-content-between align-items-center">
                            <a href="login.php" class="link-secondary">¿Ya tienes cuenta?</a>
                            <button type="submit" class="btn btn-primary">Registrar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include_once __DIR__ . '/includes/footer.php'; ?>
