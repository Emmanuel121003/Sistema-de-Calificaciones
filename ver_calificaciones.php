<?php
session_start();
include 'db_connection.php';

// Verificar si el usuario tiene acceso
if (!isset($_SESSION['id_usuario']) || $_SESSION['rol'] !== 'maestro') {
    header('Location: login.html');
    exit;
}

$id_usuario = $_SESSION['id_usuario'];

// Consultar materias asignadas al maestro
$sql_materias = "SELECT id_materia, nombre FROM materias WHERE id_maestro = ?";
$stmt_materias = $conn->prepare($sql_materias);
$stmt_materias->bind_param('i', $id_usuario);
$stmt_materias->execute();
$materias_result = $stmt_materias->get_result();

// Consultar calificaciones si se seleccionó una materia
$calificaciones = [];
if (isset($_GET['id_materia'])) {
    $id_materia = $_GET['id_materia'];

    $sql_calificaciones = "SELECT 
                            estudiantes.nombre AS estudiante,
                            calificaciones.asistencia,
                            calificaciones.participacion,
                            calificaciones.trabajos,
                            calificaciones.practicas,
                            calificaciones.exposiciones,
                            calificaciones.examen,  -- Asegúrate de que esta columna exista
                            calificaciones.proyecto,  -- Asegúrate de que esta columna exista
                            calificaciones.calificacion_final
                        FROM calificaciones
                        JOIN estudiantes ON calificaciones.id_estudiante = estudiantes.id_estudiante
                        WHERE calificaciones.id_materia = ?";
    $stmt_calificaciones = $conn->prepare($sql_calificaciones);
    $stmt_calificaciones->bind_param('i', $id_materia);
    $stmt_calificaciones->execute();
    $result_calificaciones = $stmt_calificaciones->get_result();

    while ($row = $result_calificaciones->fetch_assoc()) {
        $calificaciones[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ver Calificaciones</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container mt-5">
        <div class="card shadow-lg p-4">
            <h1 class="text-center">Ver Calificaciones</h1>
            <form method="GET" class="mb-4">
                <div class="mb-3">
                    <label for="id_materia" class="form-label">Selecciona una Materia:</label>
                    <select name="id_materia" id="id_materia" class="form-select" required>
                        <option value="" disabled selected>Selecciona una Materia</option>
                        <?php while ($materia = $materias_result->fetch_assoc()): ?>
                            <option value="<?= $materia['id_materia'] ?>" <?= (isset($_GET['id_materia']) && $_GET['id_materia'] == $materia['id_materia']) ? 'selected' : '' ?>>
                                <?= $materia['nombre'] ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary w-100">Ver Calificaciones</button>
            </form>

            <?php if (!empty($calificaciones)): ?>
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>Estudiante</th>
                            <th>Asistencia</th>
                            <th>Participación</th>
                            <th>Trabajos</th>
                            <th>Prácticas</th>
                            <th>Exposiciones</th>
                            <th>Examen</th>
                            <th>Proyecto</th>
                            <th>Calificación Final</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($calificaciones as $calificacion): ?>
                            <tr>
                                <td><?= $calificacion['estudiante'] ?></td>
                                <td><?= $calificacion['asistencia'] ?></td>
                                <td><?= $calificacion['participacion'] ?></td>
                                <td><?= $calificacion['trabajos'] ?></td>
                                <td><?= $calificacion['practicas'] ?></td>
                                <td><?= $calificacion['exposiciones'] ?></td>
                                <td><?= $calificacion['examen'] ?></td>
                                <td><?= $calificacion['proyecto'] ?></td>
                                <td><?= number_format($calificacion['calificacion_final'], 2) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php elseif (isset($_GET['id_materia'])): ?>
                <p class="text-center text-warning">No hay calificaciones registradas para esta materia.</p>
            <?php endif; ?>
        </div>
    </div>
    <div class="text-center mt-4">
                <a href="maestro_dashboard.php" class="btn btn-danger btn-lg">Regresar</a>
            </div>
</body>
</html>
