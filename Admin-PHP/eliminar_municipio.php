<?php
session_start();
include("conexionn.php");

$id_municipio = $_GET['id'];

$consulta = "DELETE FROM municipio WHERE ID_Municipio = ?";
$stmt = mysqli_prepare($conex, $consulta);
mysqli_stmt_bind_param($stmt, "i", $id_municipio);

if (mysqli_stmt_execute($stmt)) {
    header("Location: ubicacion.php?msg=eliminado");
} else {
    echo "Error: " . mysqli_error($conex);
}
?>
