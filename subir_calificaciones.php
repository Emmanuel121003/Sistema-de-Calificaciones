<?php
session_start();
include 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!isset($_SESSION['id_usuario']) || $_SESSION['rol'] !== 'maestro') {
        die("Acceso no autorizado.");
    }

    $id_usuario = $_SESSION['id_usuario'];
    $id_estudiante = $_POST['id_estudiante'];
    $id_materia = $_POST['id_materia'];
    $ser = $_POST['ser'] ?? 0;
    $saber_hacer = $_POST['saber_hacer'] ?? 0;
    $saber = $_POST['saber'] ?? 0;

    $sql_check = "SELECT id_materia FROM materias WHERE id_materia = ? AND id_maestro = ?";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bind_param('ii', $id_materia, $id_usuario);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();

    if ($result_check->num_rows === 0) {
        die("No tienes permiso para modificar las calificaciones de esta materia.");
    }

    $sql = "INSERT INTO calificaciones (id_estudiante, id_materia, ser, saber_hacer, saber)
            VALUES (?, ?, ?, ?, ?)
            ON DUPLICATE KEY UPDATE
            ser = VALUES(ser), saber_hacer = VALUES(saber_hacer), saber = VALUES(saber)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('iiddd', $id_estudiante, $id_materia, $ser, $saber_hacer, $saber);
    $stmt->execute();

    echo "Calificaciones actualizadas correctamente.";
}
?>
