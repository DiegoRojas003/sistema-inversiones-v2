<?php
session_start();
include("conexionn.php");

$id_pais = $_GET['id'];

$consulta = "DELETE FROM pais WHERE ID_Pais = ?";
$stmt = mysqli_prepare($conex, $consulta);
mysqli_stmt_bind_param($stmt, "i", $id_pais);

if (mysqli_stmt_execute($stmt)) {
    header("Location: ubicacion.php?msg=eliminado");
} else {
    echo "Error: " . mysqli_error($conex);
}
?>
