<?php
session_start();
include 'db_connection.php';

// Verificar si el usuario tiene acceso
if (!isset($_SESSION['id_usuario']) || $_SESSION['rol'] !== 'administrador') {
    header('Location: index.php');
    exit;
}

if (isset($_GET['id'])) {
    $id_estudiante = $_GET['id'];

    // Eliminar el estudiante
    $sql = "DELETE FROM estudiantes WHERE id_estudiante = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $id_estudiante);
    $stmt->execute();

    header('Location: gestionar_estudiantes.php');
    exit;
} else {
    echo "ID de estudiante no válido.";
}
?>
