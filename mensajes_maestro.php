<?php
session_start();
include 'db_connection.php';

// Verificar si el usuario tiene acceso como maestro
if (!isset($_SESSION['id_usuario']) || $_SESSION['rol'] !== 'maestro') {
    header('Location: login.php');
    exit;
}

$id_maestro = $_SESSION['id_usuario'];

// Consultar estudiantes asociados a las materias del maestro
$sql_estudiantes = "
    SELECT DISTINCT estudiantes.id_estudiante, estudiantes.nombre
    FROM estudiantes
    JOIN calificaciones ON estudiantes.id_estudiante = calificaciones.id_estudiante
    JOIN materias ON calificaciones.id_materia = materias.id_materia
    WHERE materias.id_maestro = ?";
$stmt = $conn->prepare($sql_estudiantes);
$stmt->bind_param('i', $id_maestro);
$stmt->execute();
$estudiantes_result = $stmt->get_result();

// Consultar mensajes recibidos
$sql_mensajes = "
    SELECT mensajes.id_mensaje, mensajes.mensaje, mensajes.fecha, usuarios.nombre AS remitente
    FROM mensajes
    JOIN usuarios ON mensajes.id_remitente = usuarios.id_usuario
    WHERE mensajes.id_destinatario = ?
    ORDER BY mensajes.fecha DESC";
$stmt_mensajes = $conn->prepare($sql_mensajes);
$stmt_mensajes->bind_param('i', $id_maestro);
$stmt_mensajes->execute();
$mensajes_result = $stmt_mensajes->get_result();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mensajes</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container mt-5">
        <div class="card p-4 shadow-lg">
            <h1 class="text-center">Mensajes</h1>

            <!-- Formulario para enviar mensajes -->
            <div class="card mt-4 p-3">
                <h4>Enviar Mensaje</h4>
                <form action="enviar_mensaje.php" method="POST">
                    <input type="hidden" name="id_remitente" value="<?= $id_maestro ?>">
                    <div class="mb-3">
                        <label for="id_destinatario" class="form-label">Estudiante:</label>
                        <select name="id_destinatario" id="id_destinatario" class="form-select" required>
                            <option value="" disabled selected>Selecciona un estudiante</option>
                            <?php while ($estudiante = $estudiantes_result->fetch_assoc()): ?>
                                <option value="<?= $estudiante['id_estudiante'] ?>"><?= $estudiante['nombre'] ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="mensaje" class="form-label">Mensaje:</label>
                        <textarea name="mensaje" id="mensaje" class="form-control" rows="4" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Enviar</button>
                </form>
            </div>

            <!-- Mensajes recibidos -->
            <div class="card mt-4 p-3">
                <h4>Mensajes Recibidos</h4>
                <?php if ($mensajes_result->num_rows > 0): ?>
                    <ul class="list-group">
                        <?php while ($mensaje = $mensajes_result->fetch_assoc()): ?>
                            <li class="list-group-item">
                                <strong><?= $mensaje['remitente'] ?></strong>: <?= htmlspecialchars($mensaje['mensaje']) ?>
                                <br>
                                <small class="text-muted"><?= $mensaje['fecha'] ?></small>
                            </li>
                        <?php endwhile; ?>
                    </ul>
                <?php else: ?>
                    <p class="text-center text-warning">No tienes mensajes nuevos.</p>
                <?php endif; ?>
            </div>
        </div>
        <div class="mt-3">
                <a href="maestro_dashboard.php" class="btn btn-danger btn-lg">Regresar</a>
            </div>
    </div>
    
</body>
</html>
