<?php
session_start();
include("conexionn.php");

$id_departamento = $_GET['id'];

$consulta = "DELETE FROM departamento WHERE ID_Departamento = ?";
$stmt = mysqli_prepare($conex, $consulta);
mysqli_stmt_bind_param($stmt, "i", $id_departamento);

if (mysqli_stmt_execute($stmt)) {
    header("Location: ubicacion.php?msg=eliminado");
} else {
    echo "Error: " . mysqli_error($conex);
}
?>
