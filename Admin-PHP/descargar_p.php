<?php
// Iniciar la sesión
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['authenticated']) || $_SESSION['authenticated'] !== true) {
    // Si el usuario no está autenticado, redirigirlo a la página de inicio de sesión
    header("Location: http://localhost/sistema-inversiones-v2/inicio.php"); // Cambia 'inicio-de-sesion.php' por la ruta de tu página de inicio de sesión
    exit();
}
?>
<?php
include("conexionn.php");

$id = $_GET['id']; // Obtén el ID del proyecto desde la URL
$consulta_certificado = "SELECT Certificado FROM proyecto WHERE ID_Proyecto = $id";
$resultado_certificado = mysqli_query($conex, $consulta_certificado);
$fila_certificado = mysqli_fetch_assoc($resultado_certificado);

$nombre_archivo = $fila_certificado['Certificado'];
$ruta_archivo = 'C:\Users\jhonm\Documents\documentos\htdocs\sistema-inversiones-v2\Documento_P' . $nombre_archivo; // Ajusta la ruta según tu estructura de archivos

header("Content-Disposition: attachment; filename=\"$nombre_archivo\"");
readfile($ruta_archivo);
exit;

