<?php
session_start();
include("conexionn.php");

// Verificar si se recibió un proyecto y el archivo
if (isset($_POST['proyecto']) && isset($_FILES['documento_L'])) {
    $proyecto_id = $_POST['proyecto'];

    // Subida del archivo
    $archivo = $_FILES['documento_L']['name'];
    $archivo_tmp = $_FILES['documento_L']['tmp_name'];
    $carpeta_destino = "../files/";

    // Ruta final para guardar el archivo
    $ruta_archivo = $carpeta_destino . basename($archivo);

    // Verificar si el archivo ya existe
    if (file_exists($ruta_archivo)) {
        echo "<script>alert('El archivo ya existe en la carpeta. Por favor, sube un archivo con un nombre diferente.');</script>";
        echo "<script>window.location.replace('liquidacion.php');</script>";
    } else {
        // Si no existe, mover el archivo a la carpeta destino
        if (move_uploaded_file($archivo_tmp, $ruta_archivo)) {
            // Actualizar la tabla proyecto con el archivo subido y marcar como liquidado
            $sql_update_proyecto = "UPDATE proyecto 
                                    SET liquidado = 1, Certificado_L = '$ruta_archivo' 
                                    WHERE ID_Proyecto = '$proyecto_id'";

            if ($conex->query($sql_update_proyecto) === TRUE) {
                echo "<script>alert('Liquidación registrada exitosamente.');</script>";
                echo "<script>window.location.replace('liquidacion.php');</script>";
            } else {
                echo "<script>alert('Error al registrar la liquidación:');</script>" . $conex->error;
                echo "<script>window.location.replace('liquidacion.php');</script>";
            }
        } else {
            echo "<script>alert('Error al subir el archivo.');</script>";
            echo "<script>window.location.replace('liquidacion.php');</script>";
        }
    }
} else {
    echo "<script>alert('Debe seleccionar un proyecto y subir un acta de liquidación.');</script>";
    echo "<script>window.location.replace('liquidacion.php');</script>";
}
?>