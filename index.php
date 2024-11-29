<?php
session_start();
include 'db_connection.php';

// Verificar si el usuario ya inició sesión
if (isset($_SESSION['id_usuario'])) {
    // Redirigir según el rol del usuario
    if ($_SESSION['rol'] === 'administrador') {
        header('Location: admin_dashboard.php');
        exit;
    } elseif ($_SESSION['rol'] === 'maestro') {
        header('Location: maestro_dashboard.php');
        exit;
    }
}

// Si no hay sesión iniciada, mostrar el formulario de inicio de sesión
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Calificaciones</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center">Sistema de Calificaciones</h1>
        <p class="text-center">Por favor, inicia sesión para continuar.</p>
        <form action="login.php" method="POST" class="mx-auto" style="max-width: 400px;">
            <div class="mb-3">
                <label for="usuario" class="form-label">Usuario:</label>
                <input type="text" name="usuario" id="usuario" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="contraseña" class="form-label">Contraseña:</label>
                <input type="password" name="contraseña" id="contraseña" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Iniciar Sesión</button>
        </form>
    </div>
</body>
</html>
