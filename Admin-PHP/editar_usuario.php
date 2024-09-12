<?php
session_start();
include("conexionn.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_usuario = $_POST['id_usuario'];
    $nombre_usuario = $_POST['nombre_usuario'];
    $apellido_usuario = $_POST['apellido_usuario'];
    $telefono_usuario = $_POST['telefono_usuario'];
    $correo_usuario = $_POST['correo_usuario'];
    $contraseña_usuario = $_POST['contraseña_usuario'];
    $municipio_usuario = $_POST['municipio_usuario'];

    if (empty($nombre_usuario) || empty($apellido_usuario) || empty($telefono_usuario) || empty($correo_usuario) || empty($contraseña_usuario) || empty($municipio_usuario)) {
        echo "<script>alert('Por favor, llena todos los campos.');</script>";
    } else {
        $consulta = "UPDATE usuario2 SET Nombre = ?, Apellido = ?, Telefono = ?, Correo = ?, Contraseña = ?, FK_ID_Municipio = ? WHERE ID_Usuario = ?";
        $stmt = mysqli_prepare($conex, $consulta);
        mysqli_stmt_bind_param($stmt, "ssssiii", $nombre_usuario, $apellido_usuario, $telefono_usuario, $correo_usuario, $contraseña_usuario, $municipio_usuario, $id_usuario);

        if (mysqli_stmt_execute($stmt)) {
            echo "<script>alert('Usuario actualizado exitosamente.');</script>";
            echo "<script>window.location.replace('usuarios.php');</script>";
        } else {
            echo "<script>alert('Error al actualizar el usuario: " . mysqli_error($conex) . "');</script>";
            echo "<script>window.location.replace('usuarios.php');</script>";
        }
    }
}
?>
