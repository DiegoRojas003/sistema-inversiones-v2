<?php
session_start();
include("conexionn.php");

$cedula_usuario = isset($_SESSION['cedula']) ? $_SESSION['cedula'] : '';
$password_input = $_POST['password'];

// Verificar la contraseña en la base de datos
$sql = "SELECT Contraseña FROM usuario2 WHERE ID_Usuario = '$cedula_usuario'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    if (password_verify($password_input, $row['Contraseña'])) {
        echo "correcta";
    } else {
        echo "incorrecta";
    }
} else {
    echo "incorrecta";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $password_input = $_POST['password'];  // Contraseña ingresada en el modal
    $cedula_usuario = $_SESSION['cedula'];  // Cedula del usuario autenticado

    // Verificar la contraseña del usuario
    $sql_verificar = "SELECT Contraseña FROM usuario2 WHERE ID_Usuario = '$cedula_usuario'";
    $result_verificar = $conn->query($sql_verificar);

    if ($result_verificar->num_rows > 0) {
        $row_verificar = $result_verificar->fetch_assoc();
        if (password_verify($password_input, $row_verificar['Contraseña'])) {
            // Contraseña correcta, procesar la subida del archivo
            if (isset($_FILES['documento_L']) && $_FILES['documento_L']['error'] === UPLOAD_ERR_OK) {
                $nombreArchivo = $_FILES['documento_L']['name'];
                $rutaDestino = 'uploads/' . $nombreArchivo;

                // Mover archivo al servidor
                if (move_uploaded_file($_FILES['documento_L']['tmp_name'], $rutaDestino)) {
                    // Actualizar la tabla proyecto
                    $sql_actualizar = "UPDATE proyecto SET Certificado_L = '$rutaDestino', liquidado = 1 WHERE ID_Proyecto = '$proyecto_id'";
                    if ($conn->query($sql_actualizar) === TRUE) {
                        echo "Liquidación registrada exitosamente.";
                    } else {
                        echo "Error al actualizar el proyecto: " . $conn->error;
                    }
                } else {
                    echo "Error al subir el archivo.";
                }
            } else {
                echo "No se seleccionó ningún archivo.";
            }
        } else {
            echo "Contraseña incorrecta.";
        }
    } else {
        echo "Error al verificar el usuario.";
    }
}
?>
