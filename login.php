<?php
session_start();
require_once "config.php";
require_once 'includes/functions.php';

$error = '';
$usernameValue = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $usernameValue = $username;

    if ($username === '' || $password === '') {
        $error = 'Rellena usuario y contraseña.';
    } else {
        try {
            $conn = conectarBaseDatos();

            // Coincidir con la tabla y columnas usadas en registro
            $stmt = $conn->prepare('SELECT usuario_id, nombre, password FROM usuario WHERE nombre = ?');
            $stmt->bind_param('s', $username);
            $stmt->execute();
            $result = $stmt->get_result();
            $user = $result->fetch_assoc();
            $stmt->close();

            if ($user && password_verify($password, $user['password'])) {
                session_regenerate_id(true);
                $_SESSION['login'] = $user['nombre'];
                $_SESSION['user_id'] = $user['id'];

                $dest = $_SESSION['redirect_after_login'] ?? '/applibros/dashboard.php';
                unset($_SESSION['redirect_after_login']);
                header('Location: ' . $dest);
                exit();
            } else {
                $error = 'Usuario o contraseña incorrectos.';
            }

            $conn->close();
        } catch (Exception $e) {
            $error = 'Error en la autenticación. Intenta más tarde.';
        }
    }
}

$pageTitle = 'Login - AppLibros';
include_once __DIR__ . '/includes/head.php';
?>
<main class="container my-5">
    <div class="row justify-content-center">
        <div class="col-12 col-sm-10 col-md-8 col-lg-6">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h2 class="card-title mb-4 text-center">Iniciar sesión</h2>

                    <?php if ($error): ?>
                        <div class="alert alert-danger" role="alert">
                            <?php echo htmlspecialchars($error); ?>
                        </div>
                    <?php endif; ?>

                    <form action="" method="post" class="needs-validation" novalidate>
                        <div class="mb-3">
                            <label for="username" class="form-label">Usuario</label>
                            <input type="text" id="username" name="username" class="form-control" value="<?php echo htmlspecialchars($usernameValue); ?>" required autofocus>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Contraseña</label>
                            <input type="password" id="password" name="password" class="form-control" required>
                        </div>

                        <div class="d-flex justify-content-between align-items-center">
                            <a href="register.php" class="link-secondary">¿No tienes cuenta?</a>
                            <button type="submit" class="btn btn-primary">Ingresar</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</main>

<?php include_once __DIR__ . '/includes/footer.php'; ?>
