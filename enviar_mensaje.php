<?php
session_start();
include 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_remitente = $_POST['id_remitente'];
    $id_destinatario = $_POST['id_destinatario'];
    $mensaje = $_POST['mensaje'];

    // Insertar mensaje en la tabla `mensajes`
    $sql = "INSERT INTO mensajes (id_remitente, id_destinatario, mensaje) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('iis', $id_remitente, $id_destinatario, $mensaje);
    $stmt->execute();

    header('Location: mensajes_maestro.php');
    exit;
}
?>
