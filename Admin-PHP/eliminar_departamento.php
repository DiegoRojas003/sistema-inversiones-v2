<?php
session_start();
include("conexionn.php");

$id_departamento = $_GET['id'];

// Verificar si hay municipios asociados al departamento
$consulta_verificar = "SELECT COUNT(*) AS total FROM municipio WHERE FK_ID_Departamento = ?";
$stmt_verificar = mysqli_prepare($conex, $consulta_verificar);
mysqli_stmt_bind_param($stmt_verificar, "i", $id_departamento);
mysqli_stmt_execute($stmt_verificar);
mysqli_stmt_bind_result($stmt_verificar, $total);
mysqli_stmt_fetch($stmt_verificar);
mysqli_stmt_close($stmt_verificar);

if ($total > 0) {
    // Si hay departamentos asociados, mostrar un mensaje
    echo "<script>alert('No puedes eliminar este país porque tiene Municipios asociados.');</script>";
        // Actualizar la página después de mostrar el mensaje de error
    echo "<script>window.location.replace('ubicacion.php');</script>";
} else {
    // Si no hay municipios asociados, proceder con la eliminación
    $consulta = "DELETE FROM departamento WHERE ID_Departamento = ?";
    $stmt = mysqli_prepare($conex, $consulta);
    mysqli_stmt_bind_param($stmt, "i", $id_departamento);

    if (mysqli_stmt_execute($stmt)) {
        header("Location: ubicacion.php?msg=eliminado");
    } else {
        echo "Error: " . mysqli_error($conex);
    }
}
?>