<?php
session_start();
include("conexionn.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_departamento = $_POST['id_departamento'];
    $nombre_departamento = $_POST['nombre_departamento'];
    $pais_departamento = $_POST['pais_departamento'];

    if (empty($nombre_departamento) || empty($pais_departamento)) {
        echo "<script>alert('Por favor, llena todos los campos.');</script>";
    } else {
        $consulta = "UPDATE departamento SET Nombre = ?, FK_ID_Pais = ? WHERE ID_Departamento = ?";
        $stmt = mysqli_prepare($conex, $consulta);
        mysqli_stmt_bind_param($stmt, "sii", $nombre_departamento, $pais_departamento, $id_departamento);

        if (mysqli_stmt_execute($stmt)) {
            echo "<script>alert('Departamento actualizado exitosamente.');</script>";
            echo "<script>window.location.replace('ubicacion.php');</script>";
        } else {
            echo "<script>alert('Error al actualizar el departamento: " . mysqli_error($conex) . "');</script>";
            echo "<script>window.location.replace('ubicacion.php');</script>";
        }
    }
}
?>
