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

// Consulta para obtener los datos de la tabla proyecto
$consulta_proyecto = "SELECT ID_Proyecto, Nombre, Fecha, Descripcion, Certificado FROM proyecto";
$resultado_proyecto = mysqli_query($conex, $consulta_proyecto);

// Crear un array para almacenar los datos del proyecto
$datos_proyecto = array();
while ($fila_proyecto = mysqli_fetch_assoc($resultado_proyecto)) {
    $datos_proyecto[] = $fila_proyecto;
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


	// Cantidad de usuarios que han invertido en Capital (Tipo 1)
	$sql_usuarios_capital = "SELECT COUNT(DISTINCT FK_ID_Usuario) AS usuariosCapital FROM inversion2 WHERE FK_ID_Tipo = 1";
	$result_usuarios_capital = $conn->query($sql_usuarios_capital);
	$row_usuarios_capital = $result_usuarios_capital->fetch_assoc();
	$usuariosCapital = $row_usuarios_capital['usuariosCapital'];

	// Cantidad de usuarios que han invertido en Industria (Tipo 2)
	$sql_usuarios_industria = "SELECT COUNT(DISTINCT FK_ID_Usuario) AS usuariosIndustria FROM inversion2 WHERE FK_ID_Tipo = 2";
	$result_usuarios_industria = $conn->query($sql_usuarios_industria);
	$row_usuarios_industria = $result_usuarios_industria->fetch_assoc();
	$usuariosIndustria = $row_usuarios_industria['usuariosIndustria'];


	// Valor total de inversiones de tipo 1 (Dinero)
	$sql_tipo1 = "SELECT SUM(Monto) AS montoTipo1 FROM inversion2 WHERE FK_ID_Tipo = 1";
	$result_tipo1 = $conn->query($sql_tipo1);
	$row_tipo1 = $result_tipo1->fetch_assoc();
	$montoTipo1 = $row_tipo1['montoTipo1'];

	// Valor total de inversiones de tipo 2 (Especie)
	$sql_tipo2 = "SELECT SUM(Monto) AS montoTipo2 FROM inversion2 WHERE FK_ID_Tipo = 2";
	$result_tipo2 = $conn->query($sql_tipo2);
	$row_tipo2 = $result_tipo2->fetch_assoc();
	$montoTipo2 = $row_tipo2['montoTipo2'];

	// Valor total de inversiones de tipo 3 (Industria)
	$sql_tipo3 = "SELECT SUM(Monto) AS montoTipo3 FROM inversion2 WHERE FK_ID_Tipo = 3";
	$result_tipo3 = $conn->query($sql_tipo3);
	$row_tipo3 = $result_tipo3->fetch_assoc();
	$montoTipo3 = $row_tipo3['montoTipo3'];


?>

<script>
    // Paso 1: Guardar en el localStorage desde PHP
    localStorage.setItem('usuariosCapital', <?php echo $usuariosCapital; ?>);
    localStorage.setItem('usuariosIndustria', <?php echo $usuariosIndustria; ?>);
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
		<link rel="stylesheet" type="text/css" href="../vendors/styles/style.css" />

		<!-- Global site tag (gtag.js) - Google Analytics -->
		<script
			async
			src="https://www.googletagmanager.com/gtag/js?id=G-GBZ3SGGX85"
		></script>
		<script
			async
			src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-2973766580778258"
			crossorigin="anonymous"
		></script>
		<script>
			window.dataLayer = window.dataLayer || [];
			function gtag() {
				dataLayer.push(arguments);
			}
			gtag("js", new Date());

			gtag("config", "G-GBZ3SGGX85");
		</script>
		<!-- Google Tag Manager -->
		<script>
			(function (w, d, s, l, i) {
				w[l] = w[l] || [];
				w[l].push({ "gtm.start": new Date().getTime(), event: "gtm.js" });
				var f = d.getElementsByTagName(s)[0],
					j = d.createElement(s),
					dl = l != "dataLayer" ? "&l=" + l : "";
				j.async = true;
				j.src = "https://www.googletagmanager.com/gtm.js?id=" + i + dl;
				f.parentNode.insertBefore(j, f);
			})(window, document, "script", "dataLayer", "GTM-NXZMQSS");
		</script>
	
		<!-- End Google Tag Manager -->
	</head>
	<body>
		<?php
		include("conexionn.php");

		// Consulta para obtener los datos de la tabla municipio
		$consulta_proyecto = "SELECT ID_Proyecto, Nombre, Fecha, Descripcion, Certificado FROM proyecto";
		$resultado_proyecto = mysqli_query($conex, $consulta_proyecto);

		// Crear un array para almacenar los datos
		$datos_proyecto = array();
		while ($fila_proyecto = mysqli_fetch_assoc($resultado_proyecto)) {
			$datos_proyecto[] = $fila_proyecto;
		}
		?>
		<?php include('templateM.php'); ?>
		<div class="main-container">
			<div class="pd-ltr-20 xs-pd-20-10">
				<div class="min-height-200px">
					<div class="page-header">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group row">
                                <label class="col-sm-12 col-md-2 col-form-label">Proyecto</label>
                                <div class="col-sm-12 col-md-10">
                                    <form method="post" action="">
                                        <select name="proyecto" class="custom-select col-12" onchange="this.form.submit()">
                                            <option value="">Seleccione</option>
                                            <?php foreach ($datos_proyecto as $proyecto): ?>
                                                <option value="<?php echo $proyecto['ID_Proyecto']; ?>" <?php echo ($proyecto['ID_Proyecto'] == $proyecto_id) ? 'selected' : ''; ?>>
                                                    <?php echo  $proyecto['Nombre']; ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

				<div class="bg-white pd-20 card-box mb-30" style="min-height: 200px;">
					<div id="chart6"></div>
					
				</div>
				<div class="bg-white pd-20 card-box mb-30" style="min-height: 200px;">
					<div id="barras"></div>
					
				</div>
				<div class="bg-white pd-20 card-box mb-30" style="min-height: 200px;">
						<div id="TimeLine"></div>
					
					</div>
				<div class="row clearfix">
					<div class="col-lg-6 col-md-12 col-sm-12 mb-30">
						<div class="pd-20 card-box" style="height: 360px;">
							<h5 class="h4 text-blue mb-20">Valor tipo de aporte</h5>
							
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
							<h5 class="h4 text-blue mb-20">Aportes de la industria</h5>
							
								<div class="pd-20 card-box height-100-p" style="height: 255px, overflow: hidden">
									
									<div id="chart8Tipos"></div>
								</div>
							
						</div>
					</div>
					<div class="col-lg-6 col-md-12 col-sm-12 mb-30">
						<div class="pd-20 card-box" style="height: 360px;">
							<h5 class="h4 text-blue mb-20">Cantidad</h5>
							
								<div class="pd-20 card-box height-100-p" style="height: 255px">
									
									<div id="chart9" style="max-width: 500px;"></div>
								</div>
							
						</div>
					</div>
					<div class="col-md-4 col-sm-12 mb-30">
						<div class="card text-white bg-primary card-box">
							<div class="card-body">
								<h5 class="card-title text-white">Participación Minima</h5>
								<p class="card-text">
									<?php echo number_format($participacion_minima, 2, ',', '.'); ?>%
								</p>
							</div>
						</div>
					</div>
					<div class="col-md-4 col-sm-12 mb-30">
						<div class="card text-white bg-success card-box">
							<div class="card-body">
								<h5 class="card-title text-white">Participación Maxima</h5>
								<p class="card-text">
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
                        <table class="data-table table nowrap" >
                            <thead>
                                <tr>
                                    <th>Socios</th>
                                    <th>Valor de los aportes de capital</th>
                                    <th>Valor de los aportes de industria</th>
                                    <th>Valor total de los aportes</th>
                                    <th>Porcentaje participación accionaria</th>
                                </tr>
                            </thead>
                            <tbody id="data-table-body">
                                <?php foreach ($inversiones_por_usuario as $datos): ?>
                                    <tr>
                                        <td><?php echo $datos['Nombre']; ?></td>
                                        <td>$ <?php echo number_format($datos['Capital'], 0, ',', '.'); ?></td>
                                        <td>$ <?php echo number_format($datos['Industria'], 0, ',', '.'); ?></td>
                                        <td>$ <?php echo number_format($datos['Capital'] + $datos['Industria'], 0, ',', '.'); ?></td>
                                        <td><?php echo number_format($datos['Porcentaje'], 2, ',', '.'); ?>%</td>
                                        
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>TOTALES</th>
                                    <th id="valor-capital">$ <?php echo number_format($valor_aportes_capital, 0, ',', '.'); ?></th>
                                    <th id="valor-industria">$ <?php echo number_format($valor_aportes_industria, 0, ',', '.'); ?></th>
                                    <th id="Valor-Total">$ <?php echo number_format($total_aportes, 0, ',', '.'); ?></th>
                                    <th>100%</th>
                                    <th></th>
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
		<script src = "https://code.highcharts.com/10/highcharts.js" > </script>​​
		
		<script src="../vendors/scripts/highchart-setting-simulacion.js"></script>

		<script src="../src/plugins/apexcharts/apexcharts.min.js"></script>
		<script src="../vendors/scripts/apexcharts-setting-simulacion.js"></script>
		
		<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
		

		
		<!-- Google Tag Manager (noscript) -->
		<noscript
			><iframe
				src="https://www.googletagmanager.com/ns.html?id=GTM-NXZMQSS"
				height="0"
				width="0"
				style="display: none; visibility: hidden"
			></iframe
		></noscript>
		<!-- End Google Tag Manager (noscript) -->
	</body>
</html>
