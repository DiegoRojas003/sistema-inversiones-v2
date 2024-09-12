<?php
include("conexionn.php");

if (isset($_GET['id'])) {
    $id_tipo = $_GET['id'];

    // Verificar si el tipo de inversión está vinculado a otras tablas
    $consulta_vinculo = "SELECT COUNT(*) as count FROM inversion2 WHERE FK_ID_TIPO = '$id_tipo'";
    $resultado_vinculo = mysqli_query($conex, $consulta_vinculo);
    $vinculado = mysqli_fetch_assoc($resultado_vinculo);

    if ($vinculado['count'] > 0) {
        echo "<script>
                alert('No se puede eliminar, este tipo de inversión está vinculado a otros registros.');
                window.location.href = 'tiposInveriones.php';
              </script>";
    } else {
        // Eliminar el tipo de inversión si no está vinculado
        $consulta_eliminar = "DELETE FROM tipo WHERE ID_TIPO = '$id_tipo'";
        $resultado_eliminar = mysqli_query($conex, $consulta_eliminar);

        if ($resultado_eliminar) {
            echo "<script>
                    alert('Tipo de inversión eliminado correctamente.');
                    window.location.href = 'tiposInveriones.php';
                  </script>";
        } else {
            echo "<script>
                    alert('Error al eliminar el tipo de inversión.');
                    window.location.href = 'tiposInveriones.php';
                  </script>";
        }
    }
}
?>
