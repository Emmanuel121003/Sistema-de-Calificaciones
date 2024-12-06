<?php
session_start();
include 'db_connection.php';

// Verificar si el usuario tiene acceso
if (!isset($_SESSION['id_usuario']) || $_SESSION['rol'] !== 'administrador') {
    header('Location: login.php');
    exit;
}

// Consultar todos los usuarios
$sql = "SELECT usuarios.id_usuario, usuarios.nombre, usuarios.usuario, usuarios.rol, estudiantes.grupo 
        FROM usuarios 
        LEFT JOIN estudiantes ON usuarios.id_usuario = estudiantes.id_usuario";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestionar Usuarios</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container mt-5">
        <h1>Gestionar Usuarios</h1>
        <a href="crear_usuario.php" class="btn btn-success mb-3">Crear Usuario</a>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Usuario</th>
                    <th>Rol</th>
                    <th>Grupo (Estudiantes)</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $row['id_usuario'] ?></td>
                        <td><?= $row['nombre'] ?></td>
                        <td><?= $row['usuario'] ?></td>
                        <td><?= ucfirst($row['rol']) ?></td>
                        <td><?= $row['grupo'] ?? 'N/A' ?></td>
                        <td>
                            <a href="editar_usuario.php?id=<?= $row['id_usuario'] ?>" class="btn btn-warning">Editar</a>
                            <a href="eliminar_usuario.php?id=<?= $row['id_usuario'] ?>" class="btn btn-danger">Eliminar</a>
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
