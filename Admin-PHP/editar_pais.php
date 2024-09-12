<?php
session_start();
include("conexionn.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_pais = $_POST['id_pais'];
    $nombre_pais = $_POST['nombre_pais'];

    if (empty($nombre_pais)) {
        echo "<script>alert('Por favor, llena todos los campos.');</script>";
    } else {
        $consulta = "UPDATE pais SET Nombre = ? WHERE ID_Pais = ?";
        $stmt = mysqli_prepare($conex, $consulta);
        mysqli_stmt_bind_param($stmt, "si", $nombre_pais, $id_pais);

        if (mysqli_stmt_execute($stmt)) {
            echo "<script>alert('País actualizado exitosamente.');</script>";
            echo "<script>window.location.replace('ubicacion.php');</script>";
        } else {
            echo "<script>alert('Error al actualizar el país: " . mysqli_error($conex) . "');</script>";
            echo "<script>window.location.replace('ubicacion.php');</script>";
        }
    }
}
?>
