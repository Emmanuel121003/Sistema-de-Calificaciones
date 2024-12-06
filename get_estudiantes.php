<?php
include 'db_connection.php';

if (isset($_GET['grupo_id']) && isset($_GET['materia_id'])) {
    $grupo_id = $_GET['grupo_id'];
    $materia_id = $_GET['materia_id'];

    // Consultar los estudiantes inscritos en el grupo y materia seleccionados
    $sql_estudiantes = "
        SELECT estudiantes.id_estudiante, estudiantes.nombre
        FROM estudiantes
        JOIN inscripciones ON estudiantes.id_estudiante = inscripciones.id_estudiante
        WHERE inscripciones.id_grupo = ? AND inscripciones.id_materia = ?";
    
    $stmt = $conn->prepare($sql_estudiantes);
    $stmt->bind_param('ii', $grupo_id, $materia_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $estudiantes = [];
    while ($row = $result->fetch_assoc()) {
        $estudiantes[] = $row;
    }

    echo json_encode(['estudiantes' => $estudiantes]);
} else {
    echo json_encode(['estudiantes' => []]);
}
?>
