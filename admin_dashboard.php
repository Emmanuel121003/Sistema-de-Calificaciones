<?php
session_start();
include 'db_connection.php';

// Verificar si el usuario tiene acceso
if (!isset($_SESSION['id_usuario']) || $_SESSION['rol'] !== 'administrador') {
    header('Location: index.php');
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
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center">Panel del Administrador</h1>
        <p class="text-center">Bienvenido, administrador. ¿Qué deseas gestionar?</p>
        <div class="d-flex justify-content-center flex-wrap gap-3">
            <a href="gestionar_usuarios.php" class="btn btn-primary">Gestionar Usuarios</a>
            <a href="gestionar_materias.php" class="btn btn-secondary">Gestionar Materias</a>
            <a href="gestionar_estudiantes.php" class="btn btn-success">Gestionar Estudiantes</a>
            <a href="exportar_calificaciones.php" class="btn btn-warning">Exportar Calificaciones</a>
        </div>
        <div class="text-center mt-4">
            <a href="logout.php" class="btn btn-danger">Cerrar Sesión</a>
        </div>
    </div>
</body>
</html>
