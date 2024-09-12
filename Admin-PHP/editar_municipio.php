<?php
session_start();
include("conexionn.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_municipio = $_POST['id_municipio'];
    $nombre_municipio = $_POST['nombre_municipio'];
    $departamento_municipio = $_POST['departamento_municipio'];

    if (empty($nombre_municipio) || empty($departamento_municipio)) {
        echo "<script>alert('Por favor, llena todos los campos.');</script>";
    } else {
        $consulta = "UPDATE municipio SET Nombre = ?, FK_ID_Departamento = ? WHERE ID_Municipio = ?";
        $stmt = mysqli_prepare($conex, $consulta);
        mysqli_stmt_bind_param($stmt, "sii", $nombre_municipio, $departamento_municipio, $id_municipio);

        if (mysqli_stmt_execute($stmt)) {
            echo "<script>alert('Municipio actualizado exitosamente.');</script>";
            echo "<script>window.location.replace('ubicacion.php');</script>";
        } else {
            echo "<script>alert('Error al actualizar el municipio: " . mysqli_error($conex) . "');</script>";
            echo "<script>window.location.replace('ubicacion.php');</script>";
        }
    }
}
?>
