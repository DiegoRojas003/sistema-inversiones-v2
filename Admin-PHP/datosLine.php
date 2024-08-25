<?php
header('Content-Type: application/json');

// Incluir el archivo de conexión
include("conexionn.php");

// Obtener el proyecto_id de la solicitud
$proyecto_id = isset($_GET['proyecto']) ? $conex->real_escape_string($_GET['proyecto']) : '';

// Consulta SQL para obtener el número de inversiones por año
$consulta_inversiones_anuales = "
   SELECT 
        MONTH(Fecha) AS Mes, 
        YEAR(Fecha) AS Anio, 
        FK_ID_Tipo, 
        SUM(Monto) AS TotalMonto
    FROM inversion2
    WHERE Proyecto = 'Proyecto prueba'
    GROUP BY Anio, Mes, FK_ID_Tipo
    ORDER BY Anio, Mes
";

$resultado_inversiones_anuales = mysqli_query($conex, $consulta_inversiones_anuales);

if (!$resultado_inversiones_anuales) {
    die(json_encode(['error' => 'Error en la consulta SQL: ' . mysqli_error($conex)]));
}

// Inicializar los datos para cada tipo (dinero, especie, industria)
$datos_anuales = [
    'dinero' => array_fill(0, 13, 0), // Suponiendo 13 años desde 2018 hasta 2030
    'especie' => array_fill(0, 13, 0),
    'industria' => array_fill(0, 13, 0)
];

// Rellenar los datos con la información de la consulta
while ($fila = mysqli_fetch_assoc($resultado_inversiones_anuales)) {
    $anio = $fila['Anio'];
    $tipo = $fila['FK_ID_Tipo'];

    // Mapear el tipo de inversión a su nombre
    switch ($tipo) {
        case 1:
            $tipo_nombre = 'dinero';
            break;
        case 2:
            $tipo_nombre = 'especie';
            break;
        case 3:
            $tipo_nombre = 'industria';
            break;
        default:
            continue 2; // Si el tipo no es reconocido, saltar esta fila
    }
    
    // Calcular el índice del año (2018 -> índice 0, 2019 -> índice 1, etc.)
    $indice_anio = $anio - 2018;
    
    // Verificar si el índice está dentro del rango
    if ($indice_anio >= 0 && $indice_anio < 13) {
        $datos_anuales[$tipo_nombre][$indice_anio] = $fila['TotalMonto'] / 1000000; // Usar 'TotalMonto' en lugar de 'TotalInversiones'
    }
}

// Convertir los datos a formato JSON
echo json_encode($datos_anuales);

// Cerrar la conexión
mysqli_close($conex);
?>
