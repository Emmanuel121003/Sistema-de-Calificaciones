<?php
include 'db_connection.php';

if (isset($_GET['materia_id'])) {
    $materia_id = $_GET['materia_id'];

    // Consultar los grupos para la materia seleccionada
    $sql_grupos = "SELECT id_grupo, nombre FROM grupos WHERE id_materia = ?";
    $stmt = $conn->prepare($sql_grupos);
    $stmt->bind_param('i', $materia_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $grupos = [];
    while ($row = $result->fetch_assoc()) {
        $grupos[] = $row;
    }

    echo json_encode(['grupos' => $grupos]);
} else {
    echo json_encode(['grupos' => []]);
}
?>