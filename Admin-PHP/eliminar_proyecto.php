<?php
session_start();
include("conexionn.php");

// Verificar si el usuario está autenticado
if (!isset($_SESSION['authenticated']) || $_SESSION['authenticated'] !== true) {
    header("Location: index.php");
    exit();
}

$id_proyecto = $_GET['id'] ?? '';

if ($id_proyecto) {
    // Verificar si el proyecto tiene datos asociados en otras tablas
    $consulta_asociaciones = "SELECT COUNT(*) as total FROM proyecto_usuario WHERE FK_ID_Proyecto = ?";
    $stmt_asociaciones = mysqli_prepare($conex, $consulta_asociaciones);
    mysqli_stmt_bind_param($stmt_asociaciones, "s", $id_proyecto);
    mysqli_stmt_execute($stmt_asociaciones);
    mysqli_stmt_bind_result($stmt_asociaciones, $total_asociaciones);
    mysqli_stmt_fetch($stmt_asociaciones);
    mysqli_stmt_close($stmt_asociaciones);

    if ($total_asociaciones > 0) {
        // Si existen datos asociados, mostrar mensaje amigable
        echo "<script>alert('No se puede eliminar el proyecto porque tiene datos asociados.');</script>";
        echo "<script>window.location.replace('empresas.php');</script>";
    } else {
        // Si no hay datos asociados, proceder a eliminar el proyecto
        $sql_delete = "DELETE FROM proyecto WHERE ID_Proyecto=?";
        $stmt = mysqli_prepare($conex, $sql_delete);
        mysqli_stmt_bind_param($stmt, "s", $id_proyecto);
        $resultado = mysqli_stmt_execute($stmt);

        if ($resultado) {
            echo "<script>alert('Proyecto eliminado exitosamente.');</script>";
            echo "<script>window.location.replace('empresas.php');</script>";
        } else {
            echo "<script>alert('Error al eliminar el proyecto: " . mysqli_error($conex) . "');</script>";
            echo "<script>window.location.replace('empresas.php');</script>";
        }
        mysqli_stmt_close($stmt);
    }

    // Redirigir a la página de proyectos
    echo "<script>window.location.replace('empresas.php');</script>";
} else {
    echo "<script>alert('No se proporcionó ID de proyecto.');</script>";
    echo "<script>window.location.replace('empresas.php');</script>";
}
?>