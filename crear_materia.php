<?php
session_start();
include 'db_connection.php';

// Verificar si el usuario tiene acceso
if (!isset($_SESSION['id_usuario']) || $_SESSION['rol'] !== 'administrador') {
    header('Location: login.html');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $id_maestro = $_POST['id_maestro'];

    $sql = "INSERT INTO materias (nombre, id_maestro) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('si', $nombre, $id_maestro);
    $stmt->execute();

    header('Location: gestionar_materias.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Materia</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container mt-5">
        <h1>Crear Materia</h1>
        <form action="crear_materia.php" method="POST">
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre de la Materia:</label>
                <input type="text" name="nombre" id="nombre" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="id_maestro" class="form-label">ID Maestro Asignado:</label>
                <input type="number" name="id_maestro" id="id_maestro" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Crear Materia</button>
        </form>
    </div>
    <div class="text-center mt-4">
                <a href="admin_dashboard.php" class="btn btn-danger btn-lg">Regresar</a>
            </div>
</body>
</html>

