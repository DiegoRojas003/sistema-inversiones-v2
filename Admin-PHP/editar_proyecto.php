<?php
session_start();
include("conexionn.php");

if (!isset($_SESSION['authenticated']) || $_SESSION['authenticated'] !== true) {
    header("Location: index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_proyecto = $_POST['id_proyecto'];
    $nombre_proyecto = $_POST['nombre_proyecto'];
    $fecha_proyecto = $_POST['fecha_proyecto'];
    $descripcion_proyecto = $_POST['descripcion_proyecto'];

    if (empty($nombre_proyecto) || empty($fecha_proyecto) || empty($descripcion_proyecto)) {
        echo "<script>alert('Por favor, llena todos los campos.');</script>";
    } else {
        $fecha_proyecto = date('Y-m-d', strtotime($fecha_proyecto));
        $sql_update = "UPDATE proyecto SET Nombre=?, Fecha=?, Descripcion=? WHERE ID_Proyecto=?";
        $stmt = mysqli_prepare($conex, $sql_update);
        mysqli_stmt_bind_param($stmt, "ssss", $nombre_proyecto, $fecha_proyecto, $descripcion_proyecto, $id_proyecto);
        $resultado = mysqli_stmt_execute($stmt);

        if ($resultado) {
            echo "<script>alert('Proyecto actualizado exitosamente.');</script>";
            echo "<script>window.location.replace('empresas.php');</script>";
        } else {
            echo "<script>alert('Error al actualizar el proyecto: " . mysqli_error($conex) . "');</script>";
            echo "<script>window.location.replace('empresas.php');</script>";
        }
    }
}
?>