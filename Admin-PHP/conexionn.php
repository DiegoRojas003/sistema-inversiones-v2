
<?php

$conex = mysqli_connect("localhost","root","","sistemaInversiones");

if (!$conex) {
    die("Conexión fallida: " . mysqli_connect_error());
}

?>