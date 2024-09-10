<?php
session_start();
include("conexionn.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_pais = $_POST['id_pais'];
    $nombre_pais = $_POST['nombre_pais'];

    $consulta = "UPDATE pais SET Nombre = ? WHERE ID_Pais = ?";
    $stmt = mysqli_prepare($conex, $consulta);
    mysqli_stmt_bind_param($stmt, "si", $nombre_pais, $id_pais);
    
    if (mysqli_stmt_execute($stmt)) {
        header("Location: ubicacion.php?msg=actualizado");
    } else {
        echo "Error: " . mysqli_error($conex);
    }
}
?>
