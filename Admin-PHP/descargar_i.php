<?php
// Iniciar la sesión
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['authenticated']) || $_SESSION['authenticated'] !== true) {
    // Si el usuario no está autenticado, redirigirlo a la página de inicio de sesión
    header("Location: http://localhost/sistema-inversiones-v2/index.php"); // Cambia 'inicio-de-sesion.php' por la ruta de tu página de inicio de sesión
    exit();
}
?>
<?php
include("conexionn.php");

$id = $_GET['id']; // Obtén el ID de la inversión desde la URL
$consulta_certificado = "SELECT CertificadoInversion FROM inversion2 WHERE ID_Inversion = $id";
$resultado_certificado = mysqli_query($conex, $consulta_certificado);
$fila_certificado = mysqli_fetch_assoc($resultado_certificado);

$nombre_archivo = $fila_certificado['CertificadoInversion'];
$ruta_archivo = 'ruta/del/archivo/' . $nombre_archivo; // Ajusta la ruta según tu estructura de archivos

// Envía el archivo al usuario
header("Content-Disposition: attachment; filename=\"$nombre_archivo\"");
readfile($ruta_archivo);
exit;
