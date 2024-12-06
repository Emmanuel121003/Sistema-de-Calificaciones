<?php
session_start();
include 'db_connection.php';

// Verificar si el usuario tiene acceso como estudiante
if (!isset($_SESSION['id_usuario']) || $_SESSION['rol'] !== 'estudiante') {
    header('Location: login.php');
    exit;
}

$id_estudiante = $_SESSION['id_usuario'];

// Consultar materias inscritas y calificaciones
$sql_materias = "SELECT 
    estudiantes.nombre AS estudiante,
    estudiantes.grupo,
    materias.nombre AS materia,
    calificaciones.asistencia,
    calificaciones.participacion,
    calificaciones.trabajos,
    calificaciones.practicas,
    calificaciones.exposiciones,
    calificaciones.examen,
    calificaciones.proyecto,
    calificaciones.calificacion_final
FROM usuarios
JOIN estudiantes ON usuarios.id_usuario = estudiantes.id_usuario
JOIN calificaciones ON estudiantes.id_estudiante = calificaciones.id_estudiante
JOIN materias ON calificaciones.id_materia = materias.id_materia
WHERE usuarios.id_usuario = ?";
$stmt = $conn->prepare($sql_materias);
$stmt->bind_param('i', $id_estudiante);
$stmt->execute();
$result = $stmt->get_result();

// Consultar notificaciones
$sql_notificaciones = "SELECT mensaje, fecha FROM notificaciones WHERE id_estudiante = ? AND leido = FALSE";
$stmt_notificaciones = $conn->prepare($sql_notificaciones);
$nombre_estudiante = $_SESSION['nombre'] ?? 'Usuario';
$stmt_notificaciones->bind_param('i', $id_estudiante);
$stmt_notificaciones->execute();
$result_notificaciones = $stmt_notificaciones->get_result();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard del Estudiante</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container mt-5">
        <div class="card p-4 shadow-lg">
        <h1 class="text-center">Bienvenido, <?= htmlspecialchars($_SESSION['nombre'] ?? 'Usuario') ?></h1>

            <h4 class="text-center">Tus Materias y Calificaciones</h4>
            <?php if ($result->num_rows > 0): ?>
                <table class="table table-striped table-bordered mt-4">
                    <thead>
                        <tr>
                            <th>Materia</th>
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
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?= $row['materia'] ?></td>
                                <td><?= $row['asistencia'] ?></td>
                                <td><?= $row['participacion'] ?></td>
                                <td><?= $row['trabajos'] ?></td>
                                <td><?= $row['practicas'] ?></td>
                                <td><?= $row['exposiciones'] ?></td>
                                <td><?= $row['examen'] ?></td>
                                <td><?= $row['proyecto'] ?></td>
                                <td><?= number_format($row['calificacion_final'], 2) ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p class="text-center text-warning">No tienes calificaciones registradas.</p>
            <?php endif; ?>

            <div class="card mt-4 p-3">
                <h4>Notificaciones</h4>
                <?php if ($result_notificaciones->num_rows > 0): ?>
                    <ul>
                        <?php while ($notif = $result_notificaciones->fetch_assoc()): ?>
                            <li><?= $notif['mensaje'] ?> <small>(<?= $notif['fecha'] ?>)</small></li>
                        <?php endwhile; ?>
                    </ul>
                <?php else: ?>
                    <p>No tienes notificaciones nuevas.</p>
                <?php endif; ?>
            </div>

            <div class="text-center mt-4">
            <a href="exportar_reporte.php" class="btn btn-success">Descargar Reporte</a>
                <a href="logout.php" class="btn btn-danger">Cerrar Sesión</a>
            </div>
        </div>
    </div>
</body>
</html>
