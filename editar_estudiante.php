<?php
session_start();
include 'db_connection.php';

// Verificar si el usuario tiene acceso como administrador
if (!isset($_SESSION['id_usuario']) || $_SESSION['rol'] !== 'administrador') {
    header('Location: login.php');
    exit;
}

if (isset($_GET['id'])) {
    $id_estudiante = $_GET['id'];

    // Consultar los datos actuales del estudiante
    $sql = "SELECT id_estudiante, nombre FROM estudiantes WHERE id_estudiante = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $id_estudiante);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows == 1) {
        $estudiante = $result->fetch_assoc();
    } else {
        echo "Estudiante no encontrado.";
        exit;
    }

    // Consultar los grupos disponibles
    $sql_grupos = "SELECT id_grupo, nombre FROM grupos";
    $grupos_result = $conn->query($sql_grupos);

    // Procesar la actualización
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $nombre = $_POST['nombre'];
        $grupo = $_POST['grupo'];

        // Actualizar el nombre del estudiante
        $sql_update = "UPDATE estudiantes SET nombre = ? WHERE id_estudiante = ?";
        $stmt_update = $conn->prepare($sql_update);
        $stmt_update->bind_param('si', $nombre, $id_estudiante);
        $stmt_update->execute();

        // Eliminar inscripciones anteriores
        $sql_delete = "DELETE FROM inscripciones WHERE id_estudiante = ?";
        $stmt_delete = $conn->prepare($sql_delete);
        $stmt_delete->bind_param('i', $id_estudiante);
        $stmt_delete->execute();

        // Asignar al estudiante el nuevo grupo
        if ($grupo) {
            $sql_grupo = "INSERT INTO inscripciones (id_estudiante, id_grupo) VALUES (?, ?)";
            $stmt_grupo = $conn->prepare($sql_grupo);
            $stmt_grupo->bind_param('ii', $id_estudiante, $grupo);
            $stmt_grupo->execute();
        }

        // Redirigir a la lista de estudiantes
        header('Location: gestionar_estudiantes.php');
        exit;
    }
} else {
    echo "ID de estudiante no válido.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Estudiante</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container mt-5">
        <h1>Editar Estudiante</h1>
        <form action="editar_estudiante.php?id=<?= $id_estudiante ?>" method="POST">
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre Completo:</label>
                <input type="text" name="nombre" id="nombre" class="form-control" value="<?= $estudiante['nombre'] ?>" required>
            </div>
            <div class="mb-3">
                <label for="grupo" class="form-label">Grupo:</label>
                <select name="grupo" id="grupo" class="form-select" required>
                    <option value="" disabled>Selecciona un grupo</option>
                    <?php while ($grupo = $grupos_result->fetch_assoc()): ?>
                        <option value="<?= $grupo['id_grupo'] ?>" <?= ($grupo['id_grupo'] == $estudiante['grupo']) ? 'selected' : '' ?>>
                            <?= $grupo['nombre'] ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Actualizar Estudiante</button>
        </form>
    </div>
</body>
</html>
