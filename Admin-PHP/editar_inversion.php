<?php
include("conexionn.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_inversion = $_POST['id_inversion'];
    $usuario = trim($_POST['usuario']);

    // Validación del campo vacío
    if (empty($usuario)) {
        echo "El campo nombre no puede estar vacío.";
        exit();
    }

    // Actualización en la base de datos
    $query = "UPDATE inversion2 SET Nombre = ? WHERE ID_Inversion = ?";
    $stmt = $conex->prepare($query);
    $stmt->bind_param("si", $usuario, $id_inversion);
    
    if ($stmt->execute()) {
        echo "Registro actualizado exitosamente.";
        header("Location: inversiones.php"); // Redirigir a la página de inversiones
    } else {
        echo "Error al actualizar el registro.";
    }
}
?>
