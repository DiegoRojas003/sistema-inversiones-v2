<?php
session_start();
include("conexionn.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_departamento = $_POST['id_departamento'];
    $nombre_departamento = $_POST['nombre_departamento'];
    $pais_departamento = $_POST['pais_departamento'];

    $consulta = "UPDATE departamento SET Nombre = ?, FK_ID_Pais = ? WHERE ID_Departamento = ?";
    $stmt = mysqli_prepare($conex, $consulta);
    mysqli_stmt_bind_param($stmt, "sii", $nombre_departamento, $pais_departamento, $id_departamento);
    
    if (mysqli_stmt_execute($stmt)) {
        header("Location: ubicacion.php?msg=actualizado");
    } else {
        echo "Error: " . mysqli_error($conex);
    }
}
?>
