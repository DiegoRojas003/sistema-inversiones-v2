<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["proyecto"])) {
    // Obtener el ID del proyecto seleccionado
    $id_proyecto_seleccionado = $_POST["proyecto"]; // Asegúrate de que este valor esté disponible en el formulario

    // Guardar el ID del proyecto seleccionado en la sesión
    $_SESSION['proyecto_seleccionado'] = $id_proyecto_seleccionado;
}
?>
