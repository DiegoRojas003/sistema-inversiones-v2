<?php
// Iniciar la sesión
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['authenticated']) || $_SESSION['authenticated'] !== true) {
    header("Location: http://localhost/sistema-inversiones-v2/index.php");
    exit();
}


include("conexionn.php");
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

// Obtener la cédula de la sesión
$cedula = isset($_SESSION['cedula']) ? $_SESSION['cedula'] : '';

if (!empty($cedula)) {
    // Preparar la consulta
    $stmt = $conn->prepare("SELECT Contraseña FROM usuario2 WHERE ID_Usuario = ?");
    $stmt->bind_param("s", $cedula);

    // Ejecutar la consulta
    $stmt->execute();
    $stmt->bind_result($contraseña);
    $stmt->fetch();

    // Almacenar la contraseña en una variable
    $contraseñaUsuario = $contraseña;

    // Cerrar la declaración y la conexión
    $stmt->close();
}

// Inicializar variables
$proyecto_id = isset($_POST['proyecto']) ? $_POST['proyecto'] : null;
$tasa_ajustada = 0; // Tasa ajustada de 46.68%

// Obtener el valor de la tasa con el ID más alto
$sql_tasa_max = "SELECT Tasa FROM tasa ORDER BY Id DESC LIMIT 1";
$result_tasa_max = $conn->query($sql_tasa_max);

if ($result_tasa_max && $result_tasa_max->num_rows > 0) {
    $row_tasa_max = $result_tasa_max->fetch_assoc();
    $tasa_ajustada = $row_tasa_max['Tasa'];
} else {
    // Si no se encuentra ninguna tasa, asignar un valor predeterminado
    $tasa_ajustada = 0;
}

// Consulta para obtener los datos de la tabla proyecto donde liquidado = 0
$consulta_proyecto = "SELECT ID_Proyecto, Nombre, Fecha, Descripcion, Certificado, liquidado 
                      FROM proyecto 
                      WHERE liquidado = 1";
$resultado_proyecto = mysqli_query($conn, $consulta_proyecto);

// Crear un array para almacenar los datos del proyecto
$datos_proyecto = array();
while ($fila_proyecto = mysqli_fetch_assoc($resultado_proyecto)) {
    $datos_proyecto[] = $fila_proyecto;
}

// Consulta para obtener los datos de la tabla proyecto donde liquidado = 0
$consulta_proyecto1 = "SELECT ID_Proyecto, Nombre, Fecha, Descripcion, Certificado, liquidado 
                      FROM proyecto 
                      WHERE liquidado = 0";
$resultado_proyecto1 = mysqli_query($conn, $consulta_proyecto1);

// Crear un array para almacenar los datos del proyecto
$datos_proyecto1 = array();
while ($fila_proyecto1 = mysqli_fetch_assoc($resultado_proyecto1)) {
    $datos_proyecto1[] = $fila_proyecto1;
}

// Variables para la tabla resumen
$socios = [];
$valor_aportes_capital = 0;
$valor_aportes_industria = 0;
$total_aportes = 0;
$participacion_minima = 0; // Inicializar variable
$participacion_maxima = 0; // Inicializar variable
$promedio_participacion = 0; // Inicializar variable

// Si se ha seleccionado un proyecto, realizar las consultas necesarias
if ($proyecto_id) {
    // Consulta para obtener los datos de los inversionistas
    $consulta_inversionistas = "
        SELECT i.Nombre, i.Monto, i.FK_ID_Tipo, i.Fecha
        FROM inversion2 i
        JOIN proyecto p ON i.Proyecto = p.Nombre
        WHERE p.ID_Proyecto = '$proyecto_id'
    ";
    $resultado_inversionistas = mysqli_query($conex, $consulta_inversionistas);

    // Procesar los resultados y agrupar por usuario
    $inversiones_por_usuario = [];
    while ($fila_inversionista = mysqli_fetch_assoc($resultado_inversionistas)) {
        $nombre = $fila_inversionista['Nombre'];
        $monto = $fila_inversionista['Monto'];
        $fecha_inversion = new DateTime($fila_inversionista['Fecha']);
        $fecha_actual = new DateTime();
        $intervalo = $fecha_actual->diff($fecha_inversion);
        $anios = $intervalo->y + ($intervalo->m / 12) + ($intervalo->d / 365);

        // Calcular el valor futuro
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

    // Verificar si hay inversiones antes de calcular porcentajes
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
        // Asignar valores predeterminados si no hay aportes
        $participacion_minima = 0;
        $participacion_maxima = 0;
        $suma_participaciones = 0;
        $promedio_participacion = 0;
        $numero_socios = 0;
    }

	// Consulta para obtener los datos de las inversiones por mes y tipo
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
	$mes = $fila['Mes'] - 1; // Ajustar a índice base 0
	$tipo = $fila['FK_ID_Tipo'] == 1 || $fila['FK_ID_Tipo'] == 2 ? 'capital' : 'industria';
	$datos_mensuales[$tipo][$mes] += $fila['TotalMonto'];
	}

	// Convertir los datos a formato JSON para pasarlos a JavaScript
	$datos_mensuales_json = json_encode($datos_mensuales);

	
}



// Consulta para contar la cantidad de usuarios vinculados al proyecto seleccionado
$sql_cantidad_usuarios = "
    SELECT COUNT(DISTINCT pu.FK_ID_Usuario) AS cantidadUsuarios
    FROM proyecto_usuario pu
    WHERE pu.FK_ID_Proyecto = '$proyecto_id'
";

$result_cantidad_usuarios = $conn->query($sql_cantidad_usuarios);
$row_cantidad_usuarios = $result_cantidad_usuarios->fetch_assoc();
$cantidadUsuarios = $row_cantidad_usuarios['cantidadUsuarios'];


	// Valor total de inversiones de tipo 1 (Dinero) para el proyecto seleccionado
$sql_tipo1 = "SELECT SUM(Monto) AS montoTipo1 
FROM inversion2 
WHERE FK_ID_Tipo = 1 
AND Proyecto = (
	SELECT Nombre 
	FROM proyecto 
	WHERE ID_Proyecto = '$proyecto_id'
)";
$result_tipo1 = $conn->query($sql_tipo1);
$row_tipo1 = $result_tipo1->fetch_assoc();
$montoTipo1 = $row_tipo1['montoTipo1'] ? $row_tipo1['montoTipo1'] : 0;

// Valor total de inversiones de tipo 2 (Especie) para el proyecto seleccionado
$sql_tipo2 = "SELECT SUM(Monto) AS montoTipo2 
FROM inversion2 
WHERE FK_ID_Tipo = 2 
AND Proyecto = (
	SELECT Nombre 
	FROM proyecto 
	WHERE ID_Proyecto = '$proyecto_id'
)";
$result_tipo2 = $conn->query($sql_tipo2);
$row_tipo2 = $result_tipo2->fetch_assoc();
$montoTipo2 = $row_tipo2['montoTipo2'] ? $row_tipo2['montoTipo2'] : 0;

// Valor total de inversiones de tipo 3 (Industria) para el proyecto seleccionado
$sql_tipo3 = "SELECT SUM(Monto) AS montoTipo3 
FROM inversion2 
WHERE FK_ID_Tipo = 3 
AND Proyecto = (
	SELECT Nombre 
	FROM proyecto 
	WHERE ID_Proyecto = '$proyecto_id'
)";
$result_tipo3 = $conn->query($sql_tipo3);
$row_tipo3 = $result_tipo3->fetch_assoc();
$montoTipo3 = $row_tipo3['montoTipo3'] ? $row_tipo3['montoTipo3'] : 0;


// Consulta para obtener el nombre del proyecto seleccionado
$sql_nombre_proyecto = "
    SELECT Nombre 
    FROM proyecto 
    WHERE ID_Proyecto = '$proyecto_id'
";
$result_nombre_proyecto = $conn->query($sql_nombre_proyecto);

if ($result_nombre_proyecto && $result_nombre_proyecto->num_rows > 0) {
    $row_nombre_proyecto = $result_nombre_proyecto->fetch_assoc();
    $nombreProyecto = $row_nombre_proyecto['Nombre'];
} else {
    // Manejar el caso donde no se encontró el proyecto
    $nombreProyecto = null;
}
// Guardar el nombre del proyecto en la sesión
$_SESSION['nombreProyecto'] = $nombreProyecto;

// Consulta para obtener el enlace del documento (Certificado) asociado al proyecto seleccionado
$sql_documento = "
    SELECT Certificado_L 
    FROM proyecto 
    WHERE ID_Proyecto = ?
";
$stmt_documento = $conn->prepare($sql_documento);
$stmt_documento->bind_param("s", $proyecto_id);
$stmt_documento->execute();
$result_documento = $stmt_documento->get_result();

if ($result_documento && $result_documento->num_rows > 0) {
    $row_documento = $result_documento->fetch_assoc();
    $archivo_url = $row_documento['Certificado_L'];  // Almacenar en archivo_url
} else {
    // Manejar el caso donde no se encuentra el documento
    $archivo_url = null;
}


?>

<script>
	// Paso 1: Pasar la contraseña PHP a JavaScript
	var contraseñaUsuario = '<?php echo $contraseñaUsuario; ?>';
</script>

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
	<title>Simulación</title>

	<!-- Site favicon -->


	<!-- Mobile Specific Metas -->
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />

	<!-- Google Font -->
	<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
		rel="stylesheet" />
	<!-- CSS -->
	<link rel="stylesheet" type="text/css" href="../vendors/styles/core.css" />
	<link rel="stylesheet" type="text/css" href="../vendors/styles/icon-font.min.css" />
	<link rel="stylesheet" type="text/css" href="../vendors/styles/style.css" />

	<!-- Modal CSS -->
	

</head>

<body>


	<?php include('template.php'); ?>





	<div class="main-container">
		<div class="pd-ltr-20 xs-pd-20-10">
			<div class="min-height-200px">
				<div class="page-header">
					<form id="formLiquidar" method="post" action="liquidar_proyecto.php" enctype="multipart/form-data">
						<div class="row">
							<!-- Columna para el campo de selección de empresas -->
							<div class="col-md-6">
								<div class="form-group row">
									<label class="col-sm-12 col-md-2 col-form-label">Empresas</label>
									<div class="col-sm-12 col-md-10">
										<select name="proyecto" id="proyecto" class="custom-select col-12">
											<option value="" selected>Seleccione</option>
											<?php foreach ($datos_proyecto1 as $proyecto1): ?>
											<option value="<?php echo $proyecto1['ID_Proyecto']; ?>" <?php echo
												($proyecto1['ID_Proyecto']==$proyecto_id) ? 'selected' : '' ; ?>>
												<?php echo $proyecto1['Nombre']; ?>
											</option>
											<?php endforeach; ?>
										</select>
									</div>
								</div>
							</div>

							<!-- Columna para el campo de carga de documentos -->
							<div class="col-md-6">
								<div class="form-group row">
									<label class="col-sm-12 col-md-2 col-form-label">Documento</label>
									<div class="col-sm-12 col-md-10">
										<input name="documento_L" id="documento_L" type="file" class="form-control-file"
											accept=".pdf" />
									</div>
								</div>
							</div>

							<!-- Botón de liquidar -->
							<div class="col-md-12">
								<div class="form-group row">
									<div class="col-sm-12 col-md-12 text-center">
										<button style="padding: 1% 5% 1% 5%;" class="btn btn-primary" id="btnLiquidar">Liquidar</button>
									</div>
								</div>
							</div>
						</div>
					</form>
				</div>
				<div class="modal fade" id="Medium-modal" tabindex="-1" role="dialog"
					aria-labelledby="myLargeModalLabel" aria-hidden="true">
					<div class="modal-dialog modal-dialog-centered">
						<div class="modal-content">
							<div class="modal-header">
								<h4 class="modal-title" id="myLargeModalLabel">
									Confirmar liquidación
								</h4>
								<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
									×
								</button>
							</div>
							<div class="modal-body">
								<p>
									¿Estás seguro de querer liquidar?, este cambio es irreversible y
									ya no podrás agregar o modificar la información respecto al proyecto
								</p>
								<form id="passwordForm">
									<label for="password">Contraseña:</label>
									<input type="password" id="password" name="password" required />
									<button type="submit" class="btn btn-primary">Confirmar</button>
								</form>
							</div>
						</div>
					</div>
				</div>

				<script>
					document.getElementById('btnLiquidar').addEventListener('click', function (e) {
						var empresaSeleccionada = document.querySelector('select[name="proyecto"]').value;
						var actaDeLiquidacion = document.getElementById('documento_L').files.length;

						if (!empresaSeleccionada) {
							alert('Debe seleccionar una empresa para liquidar.');
							e.preventDefault();
						} else if (actaDeLiquidacion === 0) {
							alert('Debe subir un acta de liquidación.');
							e.preventDefault();
						} else {
							// Mostrar el modal para la verificación de contraseña
							$('#Medium-modal').modal('show');
							e.preventDefault();
						}
					});

					var modal = document.getElementById("passwordModal");
					var span = document.getElementsByClassName("close")[0];
					var form = document.getElementById("passwordForm");

					span.onclick = function () {
						modal.style.display = "none";
					}

					window.onclick = function (event) {
						if (event.target == modal) {
							modal.style.display = "none";
						}
					}

					form.onsubmit = function (event) {
						event.preventDefault(); // Evitar el envío del formulario

						var passwordInput = document.getElementById('password').value;

						if (passwordInput === contraseñaUsuario) {
							alert('Contraseña aceptada.');
							// Si la contraseña es correcta, enviar el formulario principal (de liquidación)
							document.getElementById('formLiquidar').submit();
						} else {
							alert('Contraseña incorrecta. Inténtelo de nuevo.');
							document.getElementById('password').value = '';
						}
					}
				</script>


				<div class="page-header">
					<div class="row">
						<div class="col-md-6">
							<div class="form-group row">
								<label class="col-sm-12 col-md-6 col-form-label">Consulta de empresas ya
									liquidadas</label>
								<div class="col-sm-12 col-md-10">
									<form method="post" action="">
										<select name="proyecto" class="custom-select col-12"
											onchange="this.form.submit()">
											<option value="">Seleccione</option>
											<?php foreach ($datos_proyecto as $proyecto): ?>
											<option value="<?php echo $proyecto['ID_Proyecto']; ?>" <?php echo
												($proyecto['ID_Proyecto']==$proyecto_id) ? 'selected' : '' ; ?>>
												<?php echo  $proyecto['Nombre']; ?>
											</option>
											<?php endforeach; ?>
										</select>
									</form>
								</div>
								<?php if ($archivo_url): ?>
									<a href="<?php echo $archivo_url; ?>" target="_blank" style="margin-left: 2rem;">
										<i class="icon-copy fa fa-file-pdf-o fa-2x" aria-hidden="true"></i>
									</a>
								<?php else: ?>
									<i class="icon-copy fa fa-file-pdf-o fa-2x" aria-hidden="true" style="display: none;"></i>
								<?php endif; ?>

							</div>
							
						</div>
					</div>
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
					<h5 class="h4 text-blue mb-20">Inversiones realizadas expresadas en millones de pesos</h5>
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
									$
									<?php echo number_format($valor_aportes_capital, 0, ',', '.'); ?>
								</p>
							</div>
						</div>
					</div>
					<div class="col-md-6 col-sm-12 mb-30">
						<div class="card text-white bg-primary card-box">
							<div class="card-body">
								<h5 class="card-title text-white">Valor de los aportes de industria</h5>
								<p class="card-text">
									$
									<?php echo number_format($valor_aportes_industria, 0, ',', '.'); ?>
									</th>
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
								<th style='text-align: right;' id="valor-capital">
									<?php echo "$ " . number_format($valor_aportes_capital, 0, ',', '.'); ?>
								</th>
								<th style='text-align: right;' id="valor-industria">
									<?php echo "$ " . number_format($valor_aportes_industria, 0, ',', '.'); ?>
								</th>
								<th style='text-align: right;' id="Valor-Total">
									<?php echo "$ " . number_format($total_aportes, 0, ',', '.'); ?>
								</th>
								<th style='text-align: center;'>100%</th>
							</tr>
						</tfoot>
					</table>

				</div>
				<?php endif; ?>
			</div>

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
	<script src="../vendors/scripts/dashboard3.js"></script>
	<script src="../src/plugins/highcharts-6.0.7/code/highcharts.js"></script>
	<script src="https://code.highcharts.com/highcharts-3d.js"></script>
	<script src="https://code.highcharts.com/10/highcharts.js"> </script>​​

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


	<link href="assets/vendor/aos/aos.css" rel="stylesheet">
	<link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
	<link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
	<link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
	<link href="assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
	<link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">
	<!-- Google Tag Manager (noscript) -->
	<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-NXZMQSS" height="0" width="0"
			style="display: none; visibility: hidden"></iframe></noscript>
	<!-- End Google Tag Manager (noscript) -->
</body>

</html>