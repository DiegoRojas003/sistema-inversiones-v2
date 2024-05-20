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
