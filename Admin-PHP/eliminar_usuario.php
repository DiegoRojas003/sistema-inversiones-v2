<?php
session_start();
include("conexionn.php");

if (isset($_GET['id_usuario'])) {
    $id_usuario = $_GET['id_usuario'];

    // Verificar si el usuario está vinculado a otros registros
    $consulta_verificar = "SELECT * FROM proyecto_usuario WHERE FK_ID_Usuario = ?";
    $stmt_verificar = mysqli_prepare($conex, $consulta_verificar);
    mysqli_stmt_bind_param($stmt_verificar, "i", $id_usuario);
    mysqli_stmt_execute($stmt_verificar);
    mysqli_stmt_store_result($stmt_verificar);

    if (mysqli_stmt_num_rows($stmt_verificar) > 0) {
        echo "<script>alert('El usuario no puede ser eliminado porque ya está vinculado a una empresa.');</script>";
        echo "<script>window.location.replace('usuarios.php');</script>";
    } else {
        $consulta = "DELETE FROM usuario2 WHERE ID_Usuario = ?";
        $stmt = mysqli_prepare($conex, $consulta);
        mysqli_stmt_bind_param($stmt, "i", $id_usuario);

        if (mysqli_stmt_execute($stmt)) {
            echo "<script>alert('Usuario eliminado exitosamente.');</script>";
            echo "<script>window.location.replace('usuarios.php');</script>";
        } else {
            echo "<script>alert('Error al eliminar el usuario: " . mysqli_error($conex) . "');</script>";
            echo "<script>window.location.replace('usuarios.php');</script>";
        }
    }
} else {
    echo "<script>alert('No se ha especificado un usuario para eliminar.');</script>";
    echo "<script>window.location.replace('usuarios.php');</script>";
}
?>
