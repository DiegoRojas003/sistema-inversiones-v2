<?php
session_start();
require 'conexionn.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener y sanitizar los datos enviados
    $username = mysqli_real_escape_string($conex, $_POST['username']);
    $password = mysqli_real_escape_string($conex, $_POST['password']);

    // Consulta a la base de datos
    $sql = "SELECT ID_Usuario, Contraseña, FK_ID_Rol FROM USUARIO2 WHERE ID_Usuario = ?";
    $stmt = mysqli_prepare($conex, $sql);
    mysqli_stmt_bind_param($stmt, 's', $username);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $userId, $storedPassword, $rolId);
    mysqli_stmt_fetch($stmt);

    // Verificar la contraseña (en producción, usa password_verify)
    if ($userId && $password === $storedPassword) {
        // Obtener el nombre del rol
        $sqlRol = "SELECT Rol_Nombre FROM ROL WHERE ID_Rol = ?";
        $stmtRol = mysqli_prepare($conex, $sqlRol);
        mysqli_stmt_bind_param($stmtRol, 's', $rolId);
        mysqli_stmt_execute($stmtRol);
        mysqli_stmt_bind_result($stmtRol, $rolNombre);
        mysqli_stmt_fetch($stmtRol);
        mysqli_stmt_close($stmtRol);

        // Iniciar sesión
        $_SESSION['user_id'] = $userId;
        $_SESSION['username'] = $username;
        $_SESSION['rol_nombre'] = $rolNombre;

        // Determinar la redirección según el rol
        switch ($rolNombre) {
            case 'Admin':
                header('Location: Admin-PHP/inicio.php');
                exit();
            case 'Inversionista':
                header('Location: Inversionista-PHP/inicioI.php');
                exit();
            case 'Moderador':
                header('Location: Moderador-PHP/inicioM.php');
                exit();
            default:
                echo "<script>alert('Rol desconocido.'); window.location.href = 'index.html';</script>";
                exit();
        }
    } else {
        // Credenciales incorrectas
        echo "<script>alert('Usuario o contraseña incorrectos.'); window.location.href = 'login.php';</script>";
        exit();
    }

    mysqli_stmt_close($stmt);
} else {
    echo "<script>alert('Método de solicitud no permitido.'); window.location.href = 'index.html';</script>";
    exit();
}

mysqli_close($conex);
?>
