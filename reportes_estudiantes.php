<?php
session_start();
include 'db_connection.php';

// Verificar si el usuario tiene acceso
if (!isset($_SESSION['id_usuario']) || $_SESSION['rol'] !== 'maestro') {
    header('Location: index.php');
    exit;
}

$id_usuario = $_SESSION['id_usuario'];

// Consultar materias asignadas al maestro
$sql_materias = "SELECT id_materia, nombre FROM materias WHERE id_maestro = ?";
$stmt_materias = $conn->prepare($sql_materias);
$stmt_materias->bind_param('i', $id_usuario);
$stmt_materias->execute();
$materias_result = $stmt_materias->get_result();

// Consultar los estudiantes y sus calificaciones
$reportes = [];
if (isset($_GET['id_materia'])) {
    $id_materia = $_GET['id_materia'];
    
    $sql_reportes = "SELECT estudiantes.nombre, calificaciones.ser, calificaciones.saber_hacer, calificaciones.saber,
                            (calificaciones.ser * 0.2 + calificaciones.saber_hacer * 0.5 + calificaciones.saber * 0.3) AS calificacion_final
                     FROM calificaciones
                     JOIN estudiantes ON calificaciones.id_estudiante = estudiantes.id_estudiante
                     WHERE calificaciones.id_materia = ?";
    $stmt_reportes = $conn->prepare($sql_reportes);
    $stmt_reportes->bind_param('i', $id_materia);
    $stmt_reportes->execute();
    $reportes_result = $stmt_reportes->get_result();

    while ($row = $reportes_result->fetch_assoc()) {
        $reportes[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reportes de Estudiantes</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1>Reportes de Estudiantes</h1>
        
        <form action="reportes_estudiantes.php" method="GET">
            <label for="id_materia" class="form-label">Selecciona una Materia:</label>
            <select name="id_materia" id="id_materia" class="form-select" required>
                <option value="" disabled selected>Selecciona una materia</option>
                <?php while ($materia = $materias_result->fetch_assoc()): ?>
                    <option value="<?= $materia['id_materia'] ?>"><?= $materia['nombre'] ?></option>
                <?php endwhile; ?>
            </select>
            <button type="submit" class="btn btn-primary mt-3">Ver Reportes</button>
        </form>

        <?php if (!empty($reportes)): ?>
            <table class="table table-bordered mt-4">
                <thead>
                    <tr>
                        <th>Estudiante</th>
                        <th>Ser</th>
                        <th>Saber Hacer</th>
                        <th>Saber</th>
                        <th>Calificación Final</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($reportes as $reporte): ?>
                        <tr>
                            <td><?= $reporte['nombre'] ?></td>
                            <td><?= $reporte['ser'] ?></td>
                            <td><?= $reporte['saber_hacer'] ?></td>
                            <td><?= $reporte['saber'] ?></td>
                            <td><?= number_format($reporte['calificacion_final'], 2) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
    <div class="text-center mt-4">
                <a href="maestro_dashboard.php" class="btn btn-danger btn-lg">Regresar</a>
            </div>
</body>
</html>
