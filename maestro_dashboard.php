<?php
session_start();
include 'db_connection.php';

// Verificar si el usuario tiene acceso
if (!isset($_SESSION['id_usuario']) || $_SESSION['rol'] !== 'maestro') {
    header('Location: index.php');
    exit;
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel del Maestro</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center">Panel del Maestro</h1>
        <p class="text-center">Bienvenido, maestro. ¿Qué deseas realizar?</p>
        <div class="d-flex justify-content-center flex-wrap gap-3">
            <a href="subir_calificaciones.php" class="btn btn-primary">Subir Calificaciones</a>
            <a href="ver_calificaciones.php" class="btn btn-secondary">Ver Calificaciones</a>
            <a href="reportes_estudiantes.php" class="btn btn-success">Consultar Reportes</a>
        </div>
        <div class="text-center mt-4">
            <a href="logout.php" class="btn btn-danger">Cerrar Sesión</a>
        </div>
    </div>
</body>
</html>
