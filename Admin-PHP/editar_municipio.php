<?php
session_start();
include("conexionn.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_municipio = $_POST['id_municipio'];
    $nombre_municipio = $_POST['nombre_municipio'];
    $departamento_municipio = $_POST['departamento_municipio'];

    $consulta = "UPDATE municipio SET Nombre = ?, FK_ID_Departamento = ? WHERE ID_Municipio = ?";
    $stmt = mysqli_prepare($conex, $consulta);
    mysqli_stmt_bind_param($stmt, "sii", $nombre_municipio, $departamento_municipio, $id_municipio);
    
    if (mysqli_stmt_execute($stmt)) {
        header("Location: ubicacion.php?msg=actualizado");
    } else {
        echo "Error: " . mysqli_error($conex);
    }
}
?>
