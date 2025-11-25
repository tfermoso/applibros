<?php
if (!isset($pageTitle)) {
    $pageTitle = 'AppLibros';
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title><?php echo htmlspecialchars($pageTitle); ?></title>

    <!-- Favicon -->
    <link rel="icon" href="/applibros/favicon.ico" type="image/x-icon">

    <!-- Google Fonts / Font Awesome (ejemplo) -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" integrity="..." crossorigin="anonymous">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- CSS principal -->
    <link rel="stylesheet" href="/applibros/assets/css/style.css">

    <!-- Meta tags SEO / OpenGraph opcionales -->
    <meta name="description" content="AppLibros - comparte tus libros favoritos">

    <!-- Bootstrap JS bundle (defer para ejecutar tras parseo) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" defer></script>
</head>
<!-- body en formato flex-column min-vh-100 -->
<body class="d-flex flex-column min-vh-100"></body>