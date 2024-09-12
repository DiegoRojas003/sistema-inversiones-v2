<?php
// Incluir archivo de conexión
include("conexionn.php");

// Verificar si el parámetro 'id' ha sido enviado en la URL
if (isset($_GET['id'])) {
    $id_inversion = $_GET['id'];

    // Consulta SQL para eliminar el registro
    $query = "DELETE FROM inversion2 WHERE ID_Inversion = ?";
    $stmt = $conex->prepare($query);
    $stmt->bind_param("i", $id_inversion);

    // Ejecutar la consulta
    if ($stmt->execute()) {
        // Redirigir a la página de inversiones después de la eliminación
        header("Location: inversiones.php?message=deleted");
        exit();
    } else {
        echo "Error al eliminar el registro.";
    }
} else {
    echo "ID de inversión no proporcionado.";
}
?>
