<?php
session_start();
include 'db_connection.php';

// Verificar si el usuario tiene acceso
if (!isset($_SESSION['id_usuario']) || $_SESSION['rol'] !== 'administrador') {
    header('Location: index.php');
    exit;
}

// Consultar todos los estudiantes
$sql = "SELECT id_estudiante, nombre, grupo FROM estudiantes";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestionar Estudiantes</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container mt-5">
        <h1>Gestionar Estudiantes</h1>
        <a href="crear_estudiante.php" class="btn btn-success mb-3">Crear Estudiante</a>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Grupo</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $row['id_estudiante'] ?></td>
                        <td><?= $row['nombre'] ?></td>
                        <td><?= $row['grupo'] ?></td>
                        <td>
                            <a href="editar_estudiante.php?id=<?= $row['id_estudiante'] ?>" class="btn btn-warning">Editar</a>
                            <a href="eliminar_estudiante.php?id=<?= $row['id_estudiante'] ?>" class="btn btn-danger">Eliminar</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <div class="mt-3">
                <a href="admin_dashboard.php" class="btn btn-danger btn-lg">Regresar</a>
            </div>
    </div>

</body>
</html>
