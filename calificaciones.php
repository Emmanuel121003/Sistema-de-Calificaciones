<?php
session_start();
include 'db_connection.php';

// Verificar si el usuario es maestro
if (!isset($_SESSION['id_usuario']) || $_SESSION['rol'] !== 'maestro') {
    header('Location: index.php');
    exit;
}

// Consultar las materias asignadas al maestro
$id_maestro = $_SESSION['id_usuario'];
$sql_materias = "SELECT id_materia, nombre FROM materias WHERE id_maestro = ?";
$stmt = $conn->prepare($sql_materias);
$stmt->bind_param('i', $id_maestro);
$stmt->execute();
$materias_result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Subir Calificaciones</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script>
        function calcularFinal() {
            let asistencia = parseFloat(document.getElementById('asistencia').value) || 0;
            let participacion = parseFloat(document.getElementById('participacion').value) || 0;
            let trabajos = parseFloat(document.getElementById('trabajos').value) || 0;
            let practicas = parseFloat(document.getElementById('practicas').value) || 0;
            let examen = parseFloat(document.getElementById('examen').value) || 0;

            let ser = (asistencia * participacion * 0.20) / 100;
            let saberHacer = ((trabajos + practicas) / 2) * 0.50;
            let saber = examen * 0.30;

            let calificacionFinal = ser + saberHacer + saber;

            document.getElementById('resultado-final').innerText = `Calificación Final: ${calificacionFinal.toFixed(2)}%`;
        }
    </script>
</head>
<body>
    <div class="container mt-5">
        <h1>Subir Calificaciones</h1>
        <form action="exportar.php" method="POST">
            <label for="materia">Materia:</label>
            <select name="materia" id="materia" class="form-select" required>
                <option value="" disabled selected>Selecciona una materia</option>
                <?php while ($materia = $materias_result->fetch_assoc()): ?>
                    <option value="<?= $materia['id_materia'] ?>"><?= $materia['nombre'] ?></option>
                <?php endwhile; ?>
            </select>

            <h3 class="mt-4">Ser (20%)</h3>
            Asistencia (%): <input type="number" id="asistencia" min="0" max="100" class="form-control" required>
            Participación (%): <input type="number" id="participacion" min="0" max="100" class="form-control" required>

            <h3 class="mt-4">Saber Hacer (50%)</h3>
            Total de Trabajos: <input type="number" id="trabajos" min="0" class="form-control" required>
            Total de Prácticas: <input type="number" id="practicas" min="0" class="form-control" required>

            <h3 class="mt-4">Saber (30%)</h3>
            Examen: <input type="number" id="examen" min="0" max="100" class="form-control" required>

            <button type="button" class="btn btn-primary mt-3" onclick="calcularFinal()">Calcular Calificación Final</button>
            <p id="resultado-final" class="mt-3 fw-bold">Calificación Final: 0.00%</p>

            <input type="hidden" name="calificaciones" id="calificaciones" value="">
            <button type="submit" class="btn btn-success mt-3" onclick="prepararExportacion()">Exportar Calificaciones</button>
        </form>
    </div>

    <script>
        function prepararExportacion() {
            let asistencia = parseFloat(document.getElementById('asistencia').value) || 0;
            let participacion = parseFloat(document.getElementById('participacion').value) || 0;
            let trabajos = parseFloat(document.getElementById('trabajos').value) || 0;
            let practicas = parseFloat(document.getElementById('practicas').value) || 0;
            let examen = parseFloat(document.getElementById('examen').value) || 0;

            let ser = (asistencia * participacion * 0.20) / 100;
            let saberHacer = ((trabajos + practicas) / 2) * 0.50;
            let saber = examen * 0.30;
            let calificacionFinal = ser + saberHacer + saber;

            document.getElementById('calificaciones').value = JSON.stringify({
                asistencia,
                participacion,
                trabajos,
                practicas,
                examen,
                ser: ser.toFixed(2),
                saberHacer: saberHacer.toFixed(2),
                saber: saber.toFixed(2),
                calificacionFinal: calificacionFinal.toFixed(2)
            });
        }
    </script>
</body>
<div class="text-center mt-4">
                <a href="maestro_dashboard.php" class="btn btn-danger btn-lg">Regresar</a>
            </div>
</html>