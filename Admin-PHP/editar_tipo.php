<?php
session_start();
include("conexionn.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_tipo = $_POST['id_tipo'];
    $nombre_tipo = $_POST['nombre_tipo'];
    $descripcion_tipo = $_POST['descripcion_tipo'];

    // Mostrar los valores enviados para verificar
    echo "<script>console.log('ID: $id_tipo, Nombre: $nombre_tipo, Descripción: $descripcion_tipo');</script>";

    if (empty($nombre_tipo) || empty($descripcion_tipo)) {
        echo "<script>alert('Por favor, llena todos los campos.');</script>";
        echo "<script>window.location.replace('tiposInveriones.php');</script>";
    } else {
        // Preparar la consulta de actualización
        $consulta = "UPDATE tipo SET Nombre = ?, Descripcion = ? WHERE ID_TIPO = ?";
        $stmt = mysqli_prepare($conex, $consulta);
        mysqli_stmt_bind_param($stmt, "ssi", $nombre_tipo, $descripcion_tipo, $id_tipo);

        // Ejecutar la consulta y verificar si fue exitosa
        if (mysqli_stmt_execute($stmt)) {
            echo "<script>alert('Tipo de inversión actualizado exitosamente.');</script>";
            echo "<script>window.location.replace('tiposInveriones.php');</script>";
        } else {
            // Mostrar error si la consulta falla
            echo "<script>alert('Error al actualizar el tipo de inversión: " . mysqli_error($conex) . "');</script>";
            echo "<script>window.location.replace('tiposInveriones.php');</script>";
        }
    }
}

?>
