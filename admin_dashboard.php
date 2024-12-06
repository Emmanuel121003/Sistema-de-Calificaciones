<?php
session_start();
include 'db_connection.php';

// Verificar si el usuario tiene acceso
if (!isset($_SESSION['id_usuario']) || $_SESSION['rol'] !== 'administrador') {
    header('Location: login.html');
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel del Administrador</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container mt-5">
        <div class="card p-4 shadow-lg">
            <h1 class="text-center">Panel del Administrador</h1>
            <p class="text-center">Selecciona una opción:</p>
            <div class="d-flex justify-content-around flex-wrap gap-3">
                <a href="gestionar_usuarios.php" class="btn btn-primary btn-lg">Gestionar Usuarios</a>
                <a href="gestionar_materias.php" class="btn btn-secondary btn-lg">Gestionar Materias</a>
                <a href="logout.php" class="btn btn-danger btn-lg">Cerrar Sesión</a>
            </div>
        </div>
    </div>
</body>
</html>
