<?php
session_start();
include("conexionn.php");

$id_pais = $_GET['id'];

// Primero, verificar si hay departamentos asociados al país
$consulta_verificar = "SELECT COUNT(*) AS total FROM departamento WHERE FK_ID_Pais = ?";
$stmt_verificar = mysqli_prepare($conex, $consulta_verificar);
mysqli_stmt_bind_param($stmt_verificar, "i", $id_pais);
mysqli_stmt_execute($stmt_verificar);
mysqli_stmt_bind_result($stmt_verificar, $total);
mysqli_stmt_fetch($stmt_verificar);
mysqli_stmt_close($stmt_verificar);

if ($total > 0) {
    // Si hay departamentos asociados, mostrar un mensaje
    echo "<script>alert('No puedes eliminar este país porque tiene departamentos asociados.');</script>";
        // Actualizar la página después de mostrar el mensaje de error
    echo "<script>window.location.replace('ubicacion.php');</script>";
} else {
    // Si no hay departamentos asociados, proceder con la eliminación
    $consulta = "DELETE FROM pais WHERE ID_Pais = ?";
    $stmt = mysqli_prepare($conex, $consulta);
    mysqli_stmt_bind_param($stmt, "i", $id_pais);

    if (mysqli_stmt_execute($stmt)) {
        header("Location: ubicacion.php?msg=eliminado");
    } else {
        echo "Error: " . mysqli_error($conex);
    }
}
?>