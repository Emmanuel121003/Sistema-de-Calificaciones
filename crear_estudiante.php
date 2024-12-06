<?php
session_start();
include 'db_connection.php';

// Verificar si el usuario tiene acceso como administrador
if (!isset($_SESSION['id_usuario']) || $_SESSION['rol'] !== 'administrador') {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = $_POST['nombre'];
    $grupo = $_POST['grupo'];

    // Insertar nuevo estudiante en la tabla 'estudiantes'
    $sql_estudiante = "INSERT INTO estudiantes (nombre) VALUES (?)";
    $stmt_estudiante = $conn->prepare($sql_estudiante);
    $stmt_estudiante->bind_param('s', $nombre);
    $stmt_estudiante->execute();

    // Obtener el ID del estudiante recién creado
    $id_estudiante = $conn->insert_id;

    // Asignar al estudiante un grupo (opcional, si lo hay)
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

// Consultar todos los grupos disponibles
$sql_grupos = "SELECT id_grupo, nombre FROM grupos";
$grupos_result = $conn->query($sql_grupos);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Estudiante</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container mt-5">
        <h1>Crear Estudiante</h1>
        <form action="crear_estudiante.php" method="POST">
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre Completo:</label>
                <input type="text" name="nombre" id="nombre" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="grupo" class="form-label">Grupo:</label>
                <select name="grupo" id="grupo" class="form-select">
                    <option value="" disabled selected>Selecciona un grupo</option>
                    <?php while ($grupo = $grupos_result->fetch_assoc()): ?>
                        <option value="<?= $grupo['id_grupo'] ?>"><?= $grupo['nombre'] ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Crear Estudiante</button>
        </form>
    </div>
</body>
</html>
