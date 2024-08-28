<?php
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['authenticated']) || $_SESSION['authenticated'] !== true) {
    header("Location: http://localhost/sistema-inversiones-v2/index.php");
    exit();
}

include("conexionn.php");

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
// Conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "sistemainversiones";

$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Inicializar variables
$proyecto_id = $proyectoID;  // Usar directamente el ID del proyecto seleccionado
$tasa_ajustada = 0; // Tasa ajustada de 46.68%

// Obtener el valor de la tasa con el ID más alto
$sql_tasa_max = "SELECT Tasa FROM tasa ORDER BY Id DESC LIMIT 1";
$result_tasa_max = $conn->query($sql_tasa_max);

if ($result_tasa_max && $result_tasa_max->num_rows > 0) {
    $row_tasa_max = $result_tasa_max->fetch_assoc();
    $tasa_ajustada = $row_tasa_max['Tasa'];
} else {
    $tasa_ajustada = 0;
}

// Consulta para obtener los datos de la tabla proyecto
$consulta_proyecto = "SELECT ID_Proyecto, Nombre, Fecha, Descripcion, Certificado FROM proyecto";
$resultado_proyecto = mysqli_query($conex, $consulta_proyecto);

$datos_proyecto = array();
while ($fila_proyecto = mysqli_fetch_assoc($resultado_proyecto)) {
    $datos_proyecto[] = $fila_proyecto;
}

// Variables para la tabla resumen
$socios = [];
$valor_aportes_capital = 0;
$valor_aportes_industria = 0;
$total_aportes = 0;
$participacion_minima = 0;
$participacion_maxima = 0;
$promedio_participacion = 0;

if ($proyecto_id) {
    $consulta_inversionistas = "
        SELECT i.Nombre, i.Monto, i.FK_ID_Tipo, i.Fecha
        FROM inversion2 i
        JOIN proyecto p ON i.Proyecto = p.Nombre
        WHERE p.ID_Proyecto = '$proyecto_id'
    ";
    $resultado_inversionistas = mysqli_query($conex, $consulta_inversionistas);

    $inversiones_por_usuario = [];
    while ($fila_inversionista = mysqli_fetch_assoc($resultado_inversionistas)) {
        $nombre = $fila_inversionista['Nombre'];
        $monto = $fila_inversionista['Monto'];
        $fecha_inversion = new DateTime($fila_inversionista['Fecha']);
        $fecha_actual = new DateTime();
        $intervalo = $fecha_actual->diff($fecha_inversion);
        $anios = $intervalo->y + ($intervalo->m / 12) + ($intervalo->d / 365);

        $valor_futuro = $monto * pow(1 + $tasa_ajustada/100, $anios);

        if (!isset($inversiones_por_usuario[$nombre])) {
            $inversiones_por_usuario[$nombre] = [
                'Nombre' => $nombre,
                'Capital' => 0,
                'Industria' => 0
            ];
        }

        if ($fila_inversionista['FK_ID_Tipo'] == 1 || $fila_inversionista['FK_ID_Tipo'] == 2) {
            $inversiones_por_usuario[$nombre]['Capital'] += $valor_futuro;
            $valor_aportes_capital += $valor_futuro;
        } elseif ($fila_inversionista['FK_ID_Tipo'] == 3) {
            $inversiones_por_usuario[$nombre]['Industria'] += $valor_futuro;
            $valor_aportes_industria += $valor_futuro;
        }
    }

    $total_aportes = $valor_aportes_capital + $valor_aportes_industria;

    if ($total_aportes > 0) {
        foreach ($inversiones_por_usuario as $nombre => $datos) {
            $total_inversion_usuario = $datos['Capital'] + $datos['Industria'];
            $inversiones_por_usuario[$nombre]['Porcentaje'] = ($total_inversion_usuario / $total_aportes) * 100;
        }

        $participaciones_accionarias = array_column($inversiones_por_usuario, 'Porcentaje');
        $participacion_minima = min($participaciones_accionarias);
        $participacion_maxima = max($participaciones_accionarias);
        $suma_participaciones = array_sum($participaciones_accionarias);
        $numero_socios = count($inversiones_por_usuario);
        $promedio_participacion = $numero_socios > 0 ? $suma_participaciones / $numero_socios : 0;
    } else {
        $participacion_minima = 0;
        $participacion_maxima = 0;
        $suma_participaciones = 0;
        $promedio_participacion = 0;
        $numero_socios = 0;
    }

    $consulta_inversiones_mensuales = "
    SELECT 
        MONTH(Fecha) AS Mes, 
        YEAR(Fecha) AS Anio, 
        FK_ID_Tipo, 
        SUM(Monto) AS TotalMonto
    FROM inversion2
    WHERE Proyecto = '$proyecto_id'
    GROUP BY Anio, Mes, FK_ID_Tipo
    ORDER BY Anio, Mes
    ";
    $resultado_inversiones_mensuales = mysqli_query($conex, $consulta_inversiones_mensuales);

    $datos_mensuales = [
        'capital' => array_fill(0, 12, 0),
        'industria' => array_fill(0, 12, 0)
    ];

    while ($fila = mysqli_fetch_assoc($resultado_inversiones_mensuales)) {
        $mes = $fila['Mes'] - 1;
        $tipo = $fila['FK_ID_Tipo'] == 1 || $fila['FK_ID_Tipo'] == 2 ? 'capital' : 'industria';
        $datos_mensuales[$tipo][$mes] += $fila['TotalMonto'];
    }

    $datos_mensuales_json = json_encode($datos_mensuales);
}

// Contar la cantidad de usuarios vinculados al proyecto específico
$sql_usuarios_proyecto = "SELECT COUNT(FK_ID_Usuario) AS cantidadUsuarios 
                          FROM proyecto_usuario 
                          WHERE FK_ID_Proyecto = $proyectoID";
$result_usuarios_proyecto = $conn->query($sql_usuarios_proyecto);

// Verificar si la consulta se realizó correctamente
if ($result_usuarios_proyecto) {
    $row_usuarios_proyecto = $result_usuarios_proyecto->fetch_assoc();
    // Asegurarse de que 'cantidadUsuarios' tenga un valor numérico
    $cantidadUsuarios = isset($row_usuarios_proyecto['cantidadUsuarios']) ? (int)$row_usuarios_proyecto['cantidadUsuarios'] : 0;
} else {
    // En caso de error en la consulta
    $cantidadUsuarios = 0;
}


// Valor total de inversiones de tipo 1 (Dinero) en el proyecto específico
$sql_tipo1 = "SELECT SUM(Monto) AS montoTipo1 
              FROM inversion2 
              WHERE FK_ID_Tipo = 1 
              AND Proyecto = '$proyectoNombre'";
$result_tipo1 = $conn->query($sql_tipo1);
$row_tipo1 = $result_tipo1->fetch_assoc();
$montoTipo1 = $row_tipo1['montoTipo1'] ? $row_tipo1['montoTipo1'] : 0;

// Valor total de inversiones de tipo 2 (Especie) en el proyecto específico
$sql_tipo2 = "SELECT SUM(Monto) AS montoTipo2 
              FROM inversion2 
              WHERE FK_ID_Tipo = 2 
              AND Proyecto = '$proyectoNombre'";
$result_tipo2 = $conn->query($sql_tipo2);
$row_tipo2 = $result_tipo2->fetch_assoc();
$montoTipo2 = $row_tipo2['montoTipo2'] ? $row_tipo2['montoTipo2'] : 0;

// Valor total de inversiones de tipo 3 (Industria) en el proyecto específico
$sql_tipo3 = "SELECT SUM(Monto) AS montoTipo3 
              FROM inversion2 
              WHERE FK_ID_Tipo = 3 
              AND Proyecto = '$proyectoNombre'";
$result_tipo3 = $conn->query($sql_tipo3);
$row_tipo3 = $result_tipo3->fetch_assoc();
$montoTipo3 = $row_tipo3['montoTipo3'] ? $row_tipo3['montoTipo3'] : 0;

?>

<script>
    // Paso 1: Guardar en el localStorage desde PHP
    localStorage.setItem('cantidadUsuarios', <?php echo $cantidadUsuarios; ?>);

    localStorage.setItem('inversionTipo1', <?php echo $montoTipo1; ?>);
    localStorage.setItem('inversionTipo2', <?php echo $montoTipo2; ?>);
    localStorage.setItem('inversionTipo3', <?php echo $montoTipo3; ?>);
</script>


<script>
    // Pasar los datos PHP a JavaScript
    var datosMensuales = <?php echo $datos_mensuales_json; ?>;
</script>

<!DOCTYPE html>
<html>
	<head>
		<!-- Basic Page Info -->
		<meta charset="utf-8" />
		<title>DeskApp - Bootstrap Admin Dashboard HTML Template</title>

		<!-- Site favicon -->
		<link
			rel="apple-touch-icon"
			sizes="180x180"
			href="../vendors/images/apple-touch-icon.png"
		/>
		<link
			rel="icon"
			type="image/png"
			sizes="32x32"
			href="../vendors/images/favicon-32x32.png"
		/>
		<link
			rel="icon"
			type="image/png"
			sizes="16x16"
			href="../vendors/images/favicon-16x16.png"
		/>

		<!-- Mobile Specific Metas -->
		<meta
			name="viewport"
			content="width=device-width, initial-scale=1, maximum-scale=1"
		/>

		<!-- Google Font -->
		<link
			href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
			rel="stylesheet"
		/>
		<!-- CSS -->
		<link rel="stylesheet" type="text/css" href="../vendors/styles/core.css" />
		<link
			rel="stylesheet"
			type="text/css"
			href="../vendors/styles/icon-font.min.css"
		/>
		<link
			rel="stylesheet"
			type="text/css"
			href="../src/plugins/datatables/css/dataTables.bootstrap4.min.css"
		/>
		<link
			rel="stylesheet"
			type="text/css"
			href="../src/plugins/datatables/css/responsive.bootstrap4.min.css"
		/>
		<link rel="stylesheet" type="text/css" href="../vendors/styles/style.css" />

		
		
	</head>
	<body>

		

		<?php include('templateI.php'); ?>
		<div class="mobile-menu-overlay"></div>

		<div class="main-container">
			<div class="pd-ltr-20">
				<div class="card-box pd-20 height-100-p mb-30">
					<div class="row align-items-center">
						<div class="col-md-4">
							<img src="../vendors/images/banner-img.png" alt="" />
						</div>
						<div class="col-md-4">
							<h4 class="font-20 weight-500 mb-10 text-capitalize">
								¡Bienvenido a la empresa: <?php echo htmlspecialchars($proyectoNombre); ?>!
								<div class="weight-600 font-30 text-blue"><?php echo $nombre . ' ' . $apellido; ?></div>
							</h4>
							<p class="font-18 max-width-600">
							¡Hola y bienvenido a nuestro servicio de gestión de 
							inversiones personalizadas! Estamos aquí para cuidar 
							y hacer crecer tus inversiones. Nos ocupamos de todo, 
							desde supervisar tus aportes financieros hasta proporcionarte 
							actualizaciones regulares sobre el rendimiento de tus fondos. 
							¡Confía en nosotros para alcanzar tus objetivos financieros!
							</p>
						</div>
						<div class="col-md-4" style="text-align:center;padding:1em 0;">
							 <h3><a style="text-decoration:none;" >
								<span style="color:gray;">Hora actual en</span>
								<br />Colombia</a></h3> 
								<iframe src="https://www.zeitverschiebung.net/clock-widget-iframe-v2?language=es&size=medium&timezone=America%2FBogota" 
									width="100%" height="115" frameborder="0" seamless></iframe> </div>
						
						</div>
					</div>
				</div>
				
				<div class="min-height-200px">	
						
				<div class="col-sm-12 col-md-10">
					<form method="post" action="">
						<select name="proyecto" class="custom-select col-12" onchange="this.form.submit()" style="display: none;">
							<option value="">Seleccione</option>
							<?php foreach ($datos_proyecto as $proyecto): ?>
								<option value="<?php echo $proyecto['ID_Proyecto']; ?>" <?php echo ($proyecto['ID_Proyecto'] == $proyecto_id) ? 'selected' : ''; ?>>
									<?php echo  $proyecto['Nombre']; ?>
								</option>
							<?php endforeach; ?>
						</select>
					</form>
				</div>

				<div class="bg-white pd-20 card-box mb-30" style="min-height: 200px;">
				<h5 class="h4 text-blue mb-20">Gráfica circular %</h5>
					<div id="chart6"></div>
					
				</div>
				<div class="bg-white pd-20 card-box mb-30" style="min-height: 200px;">
				<h5 class="h4 text-blue mb-20">Gráfica de barras %</h5>
					<div id="barras"></div>
				</div>
				
				<div class="bg-white pd-20 card-box mb-30" style="min-height: 200px;">
				<h5 class="h4 text-blue mb-20">Inversiones realizadas expresadas en millones</h5>
						<div id="TimeLine"></div>
					
					</div>
				<div class="row clearfix">
					<div class="col-lg-6 col-md-12 col-sm-12 mb-30">
						<div class="pd-20 card-box" style="height: 360px;">
							<h5 class="h4 text-blue mb-20">% Aportes</h5>
							
								<div class="pd-20 card-box height-100-p" style="height: 255px">
									
									<div id="chart8Valor" style="max-width: 500px;"></div>
								</div>
							
						</div>
					</div>
					<div class="col-lg-6 col-md-12 col-sm-12 mb-30">
						<div class="pd-20 card-box" style="height: 360px; overflow: hidden;">
							<h5 class="h4 text-blue mb-20">Usuarios</h5>
							
								<div class="pd-20 card-box height-100-p" style="height: 255px">
									
									<div id="velocimetro"></div>
								</div>
							
						</div>
					</div>
					
					<div class="col-lg-6 col-md-12 col-sm-12 mb-30">
						<div class="pd-20 card-box" style="height: 360px; overflow: hidden;">
							<h5 class="h4 text-blue mb-20">Gráfica de radar % tipos de inversiones</h5>
							
								<div class="pd-20 card-box height-100-p" style="height: 255px, overflow: hidden">
									
									<div id="chart8Tipos"></div>
								</div>
							
						</div>
					</div>
					<div class="col-lg-6 col-md-12 col-sm-12 mb-30">
						<div class="pd-20 card-box" style="height: 360px;">
							<h5 class="h4 text-blue mb-20">Participación máxima vs mínima</h5>
							
								<div class="pd-20 card-box height-100-p" style="height: 255px">
									
									<div id="chart9" style="max-width: 500px;"></div>
								</div>
							
						</div>
					</div>
					<div class="col-md-4 col-sm-12 mb-30">
						<div class="card text-white bg-primary card-box">
							<div class="card-body">
								<h5 class="card-title text-white">Participación Minima</h5>
								<p id="min" class="card-text">
									<?php echo number_format($participacion_minima, 2, ',', '.'); ?>%
								</p>
							</div>
						</div>
					</div>
					<div class="col-md-4 col-sm-12 mb-30">
						<div class="card text-white bg-success card-box">
							<div class="card-body">
								<h5 class="card-title text-white">Participación Maxima</h5>
								<p id="max" class="card-text">
									<?php echo number_format($participacion_maxima, 2, ',', '.'); ?>%
								</p>
							</div>
						</div>
					</div>
					<div class="col-md-4 col-sm-12 mb-30">
						<div class="card text-white bg-primary card-box">
							<div class="card-body">
								<h5 class="card-title text-white">Promedio Participación</h5>
								<p class="card-text">
									<?php echo number_format($promedio_participacion, 2, ',', '.'); ?>%
								</p>
							</div>
						</div>
					</div>
					<div class="col-md-6 col-sm-12 mb-30">
						<div class="card text-white bg-primary card-box">
							<div class="card-body">
								<h5 class="card-title text-white">Valor de los aportes de capital</h5>
								<p class="card-text">
									$ <?php echo number_format($valor_aportes_capital, 0, ',', '.'); ?>
								</p>
							</div>
						</div>
					</div>
					<div class="col-md-6 col-sm-12 mb-30">
						<div class="card text-white bg-primary card-box">
							<div class="card-body">
								<h5 class="card-title text-white">Valor de los aportes de industria</h5>
								<p class="card-text">
									$ <?php echo number_format($valor_aportes_industria, 0, ',', '.'); ?></th>
								</p>
							</div>
						</div>
					</div>
				</div>
				<?php if ($proyecto_id): ?>
                    <div class="card-box pb-10">
                        <div class="h5 pd-20 mb-0">Resumen</div>
                        <table class="table hover multiple-select-row data-table-export nowrap">
							<thead>
								<tr>
									<th>Socios</th>
									<th>Aportes de capital</th>
									<th>Aportes de industria</th>
									<th>Total aportes</th>
									<th>Porcentaje participación</th>
								</tr>
							</thead>
							<tbody id="data-table-body">
								<?php
									$valor_aportes_capital = 0;
									$valor_aportes_industria = 0;
									$total_aportes = 0;
									foreach ($inversiones_por_usuario as $datos) {
										
										// Mostrar datos
										echo "<tr>";
										echo "<td>" . $datos['Nombre'] . "</td>";
										echo "<td style='text-align: right;'>$ " . number_format($datos['Capital'], 0, ',', '.') . "</td>";
										echo "<td style='text-align: right;'>$ " . number_format($datos['Industria'], 0, ',', '.') . "</td>";
										echo "<td style='text-align: right;'>$ " . number_format($datos['Capital'] + $datos['Industria'], 0, ',', '.') . "</td>";
										echo "<td style='text-align: center;'>" . number_format($datos['Porcentaje'], 2, ',', '.') . "%</td>";
										echo "</tr>";

										
										$valor_aportes_capital += $datos['Capital'];
										$valor_aportes_industria += $datos['Industria'];
										$total_aportes += $datos['Capital'] + $datos['Industria'];
									}
								?>
							</tbody>
							<tfoot>
								<tr>
									<th>TOTALES</th>
									<th style='text-align: right;' id="valor-capital"><?php echo "$ " . number_format($valor_aportes_capital, 0, ',', '.'); ?></th>
									<th style='text-align: right;' id="valor-industria"><?php echo "$ " . number_format($valor_aportes_industria, 0, ',', '.'); ?></th>
									<th style='text-align: right;' id="Valor-Total"><?php echo "$ " . number_format($total_aportes, 0, ',', '.'); ?></th>
									<th style='text-align: center;'>100%</th>
								</tr>
							</tfoot>
						</table>

                    </div>
                <?php endif; ?>
            </div>
			</div>
		</div>
		<!-- welcome modal start -->
				
		<!-- welcome modal end -->
		<!-- js -->
		
		<script src="../vendors/scripts/core.js"></script>
		<script src="../vendors/scripts/script.min.js"></script>
		<script src="../vendors/scripts/process.js"></script>
		<script src="../vendors/scripts/layout-settings.js"></script>
		<script src="../src/plugins/apexcharts/apexcharts.min.js"></script>
		<script src="../src/plugins/datatables/js/jquery.dataTables.min.js"></script>
		<script src="../src/plugins/datatables/js/dataTables.bootstrap4.min.js"></script>
		<script src="../src/plugins/datatables/js/dataTables.responsive.min.js"></script>
		<script src="../src/plugins/datatables/js/responsive.bootstrap4.min.js"></script>
		<script src="../vendors/scripts/dashboard.js"></script>
		<script src="../src/plugins/highcharts-6.0.7/code/highcharts.js"></script>
		<script src="https://code.highcharts.com/highcharts-3d.js"></script>
		<script src = "https://code.highcharts.com/10/highcharts.js" > </script>​​
		
		<script src="../vendors/scripts/highchart-setting-simulacion.js"></script>

		<script src="../src/plugins/apexcharts/apexcharts.min.js"></script>
		<script src="../vendors/scripts/apexcharts-setting-simulacion.js"></script>
		
		<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
		
		<!-- DataTables -->
		<!-- buttons for Export datatable -->
		<script src="../src/plugins/datatables/js/dataTables.buttons.min.js"></script>
		<script src="../src/plugins/datatables/js/buttons.bootstrap4.min.js"></script>
		<script src="../src/plugins/datatables/js/buttons.print.min.js"></script>
		<script src="../src/plugins/datatables/js/buttons.html5.min.js"></script>
		<script src="../src/plugins/datatables/js/buttons.flash.min.js"></script>
		<script src="../src/plugins/datatables/js/pdfmake.min.js"></script>
		<script src="../src/plugins/datatables/js/vfs_fonts.js"></script>
		<!-- Datatable Setting js -->
		<script src="../vendors/scripts/datatable-setting.js"></script>
	</body>
</html>
