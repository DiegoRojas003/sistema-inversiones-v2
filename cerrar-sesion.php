<?php
// Iniciar la sesión
session_start();

// Limpiar todas las variables de sesión
session_unset();

// Destruir la sesión
session_destroy();

// Redirigir al usuario a la página de inicio de sesión u otra página deseada
header("Location: index.php"); // Cambia 'inicio-de-sesion.php' por la ruta de tu página de inicio de sesión
exit();

