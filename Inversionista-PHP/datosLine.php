<?php
header('Content-Type: application/json');

// Incluir el archivo de conexión
include("conexionn.php");

session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['authenticated']) || $_SESSION['authenticated'] !== true) {
    header("Location: http://localhost/sistema-inversiones-v2/index.php");
    exit();
}


// Recuperar la información del proyecto seleccionado
$proyectoID = isset($_SESSION['proyecto_seleccionado']) ? $_SESSION['proyecto_seleccionado'] : null;
$proyectoNombre = isset($_SESSION['nombre_proyecto']) ? $_SESSION['nombre_proyecto'] : null;
$id_usuario = isset($_SESSION['cedula']) ? $_SESSION['cedula'] : null;

if (empty($proyectoID) && !empty($id_usuario)) {
	// Buscar en la tabla proyecto_usuario el FK_ID_Proyecto según el id_usuario
	$consulta_proyecto_usuario = "SELECT FK_ID_Proyecto 
								FROM proyecto_usuario 
								WHERE FK_ID_Usuario = ?";

	$stmt = mysqli_prepare($conex, $consulta_proyecto_usuario);
	mysqli_stmt_bind_param($stmt, "i", $id_usuario);
	mysqli_stmt_execute($stmt);
	$resultado_proyecto_usuario = mysqli_stmt_get_result($stmt);

	if ($fila = mysqli_fetch_assoc($resultado_proyecto_usuario)) {
		$proyectoID = $fila['FK_ID_Proyecto'];

		// Ahora que tenemos el $proyectoID, buscar el nombre del proyecto en la tabla proyecto
		$consulta_proyecto = "SELECT Nombre 
							FROM proyecto 
							WHERE ID_Proyecto = ?";

		$stmt_proyecto = mysqli_prepare($conex, $consulta_proyecto);
		mysqli_stmt_bind_param($stmt_proyecto, "i", $proyectoID);
		mysqli_stmt_execute($stmt_proyecto);
		$resultado_proyecto = mysqli_stmt_get_result($stmt_proyecto);

		if ($fila_proyecto = mysqli_fetch_assoc($resultado_proyecto)) {
			$proyectoNombre = $fila_proyecto['Nombre'];

			// Guardar el nombre del proyecto en la sesión
			$_SESSION['nombre_proyecto'] = $proyectoNombre;
		}
	}
}

// Consulta SQL para obtener el número de inversiones por año
$consulta_inversiones_anuales = "
   SELECT 
        MONTH(Fecha) AS Mes, 
        YEAR(Fecha) AS Anio, 
        FK_ID_Tipo, 
        SUM(Monto) AS TotalMonto
    FROM inversion2
    WHERE Proyecto = '$proyectoNombre'
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
