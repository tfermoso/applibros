<?php
session_start();

$error = '';
$usernameValue = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $usernameValue = $username;

    if ($username === '' || $password === '') {
        $error = 'Rellena usuario y contraseña.';
    } else {
        $miembros = $_SESSION['miembros'] ?? [];

        if (empty($miembros)) {
            $error = 'Necesitas crear un usuario para logearte';
        } else {
            if (isset($miembros[$username])) {
                $stored = $miembros[$username];

                // Soporta autenticación contra hash o texto plano por compatibilidad
                $ok = false;
                if (password_needs_rehash($stored, PASSWORD_DEFAULT) || strpos($stored, '$2y$') === 0) {
                    // $stored parece ser un hash bcrypt
                    $ok = password_verify($password, $stored);
                } else {
                    // formato antiguo: texto plano
                    $ok = ($password === $stored);
                }

                if ($ok) {
                    session_regenerate_id(true);
                    $_SESSION['login'] = $username;

                    $dest = $_SESSION['redirect_after_login'] ?? '/applibros/dashboard.php';
                    unset($_SESSION['redirect_after_login']);
                    header('Location: ' . $dest);
                    exit();
                } else {
                    $error = 'Usuario o contraseña incorrectos.';
                }
            } else {
                $error = 'Usuario o contraseña incorrectos.';
            }
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
                            <div class="invalid-feedback">Introduce tu usuario.</div>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Contraseña</label>
                            <input type="password" id="password" name="password" class="form-control" required>
                            <div class="invalid-feedback">Introduce tu contraseña.</div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <a href="register.php" class="link-secondary">¿No tienes cuenta?</a>
                            </div>
                            <div>
                                <button type="submit" class="btn btn-primary">Ingresar</button>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</main>
<?php
include_once __DIR__ . '/includes/footer.php';
?>