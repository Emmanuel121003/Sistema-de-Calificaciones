<?php
include 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_estudiante = $_POST['id_estudiante'];
    $id_materia = $_POST['id_materia'];
    $asistencia = $_POST['asistencia'];
    $participacion = $_POST['participacion'];
    $trabajos = $_POST['trabajos'];
    $practicas = $_POST['practicas'];
    $exposiciones = $_POST['exposiciones'];
    $examen = $_POST['examen'];
    $proyecto = $_POST['proyecto'];

    // Calcular la calificación final
    $ser = ($asistencia + $participacion) * 0.2;
    $saber_hacer = ($trabajos + $practicas + $exposiciones) * 0.5;
    $saber = ($examen + $proyecto) * 0.3;
    $calificacion_final = $ser + $saber_hacer + $saber;

    $sql = "INSERT INTO calificaciones (id_estudiante, id_materia, asistencia, participacion, trabajos, practicas, exposiciones, examen, proyecto, calificacion_final)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ON DUPLICATE KEY UPDATE
            asistencia = VALUES(asistencia),
            participacion = VALUES(participacion),
            trabajos = VALUES(trabajos),
            practicas = VALUES(practicas),
            exposiciones = VALUES(exposiciones),
            examen = VALUES(examen),
            proyecto = VALUES(proyecto),
            calificacion_final = VALUES(calificacion_final)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('iidddddddd', $id_estudiante, $id_materia, $asistencia, $participacion, $trabajos, $practicas, $exposiciones, $examen, $proyecto, $calificacion_final);
    $stmt->execute();

    echo "Calificaciones guardadas correctamente.";
    $mensaje = "Se han actualizado tus calificaciones en la materia $id_materia.";
$sql_notificacion = "INSERT INTO notificaciones (id_estudiante, mensaje) VALUES (?, ?)";
$stmt_notificacion = $conn->prepare($sql_notificacion);
$stmt_notificacion->bind_param('is', $id_estudiante, $mensaje);
$stmt_notificacion->execute();

}

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Subir Calificaciones</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="styles.css">
</head>
<script>
        function calcularCalificacion() {
            const asistencia = parseFloat(document.getElementById('asistencia').value) || 0;
            const participacion = parseFloat(document.getElementById('participacion').value) || 0;
            const trabajos = parseFloat(document.getElementById('trabajos').value) || 0;
            const practicas = parseFloat(document.getElementById('practicas').value) || 0;
            const exposiciones = parseFloat(document.getElementById('exposiciones').value) || 0;
            const examen = parseFloat(document.getElementById('examen').value) || 0;
            const proyecto = parseFloat(document.getElementById('proyecto').value) || 0;

            // Cálculo de las categorías
            const ser = (asistencia + participacion) * 0.2;
            const saberHacer = (trabajos + practicas + exposiciones) * 0.5;
            const saber = (examen + proyecto) * 0.3;

            // Calcular la calificación final
            const calificacionFinal = ser + saberHacer + saber;
            document.getElementById('calificacion_final').innerText = "Calificación Final: " + calificacionFinal.toFixed(2);
        }
    </script>
<body>
    <div class="container mt-5">
        <div class="card shadow-lg p-4">
            <h1 class="text-center">Subir Calificaciones</h1>
            <form action="subir_calificaciones.php" method="POST">
                <div class="mb-3">
                    <label for="id_estudiante" class="form-label">Estudiante:</label>
                    <select id="id_estudiante" name="id_estudiante" class="form-select" required>
                        <!-- Opciones dinámicas -->
                    </select>
                </div>
                <div class="mb-3">
                    <label for="id_materia" class="form-label">Materia:</label>
                    <select id="id_materia" name="id_materia" class="form-select" required>
                        <!-- Opciones dinámicas -->
                    </select>
                </div>
                <div class="mb-3">
                    <label for="asistencia" class="form-label">Asistencia:</label>
                    <input type="number" id="asistencia" name="asistencia" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="participacion" class="form-label">Participación:</label>
                    <input type="number" id="participacion" name="participacion" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="trabajos" class="form-label">Trabajos:</label>
                    <input type="number" id="trabajos" name="trabajos" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary w-100">Guardar</button>
            </form>
        </div>
    </div>
    <div class="text-center mt-4">
                <a href="maestro_dashboard.php" class="btn btn-danger btn-lg">Regresar</a>
            </div>
</body>
</html>