<?php
// Iniciar la sesión
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['authenticated']) || $_SESSION['authenticated'] !== true) {
    header("Location: http://localhost/sistema-inversiones-v2/index.php");
    exit();
}

// Conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "sistemainversiones";

function conectarDB($servername, $username, $password, $dbname) {
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Conexión fallida: " . $conn->connect_error);
    }
    return $conn;
}

function ejecutarConsulta($conn, $sql, $params = [], $types = '') {
    $stmt = $conn->prepare($sql);
    if ($params) {
        $stmt->bind_param($types, ...$params);
    }
    $stmt->execute();
    return $stmt->get_result();
}

$conn = conectarDB($servername, $username, $password, $dbname);

// Definir variables para evitar advertencias de "Undefined variable"
$num_inversiones_realizadas = 0;
$diferencia_dias = 0;
$num_inversionistas = 0;
$tasa_ajustada = 0;
$total_aportes = 0;
$valor_aportes_capital = 0;
$valor_aportes_industria = 0;
$resultadoI = $resultadoII = $resultadoIII = null;

// Consultar usuarios
$sql_usuarios = "SELECT ID_Usuario, Nombre, Apellido FROM usuario2 WHERE FK_ID_Rol != 1";
$result_usuarios = ejecutarConsulta($conn, $sql_usuarios);

// Manejar solicitud AJAX
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['usuario_id'])) {
    $usuario_id = $_POST['usuario_id'];
    $sql_proyectos = "SELECT p.ID_Proyecto, p.Nombre 
                      FROM proyecto p 
                      INNER JOIN proyecto_usuario pu ON p.ID_Proyecto = pu.FK_ID_Proyecto 
                      WHERE pu.FK_ID_Usuario = ?";
    $result_proyectos = ejecutarConsulta($conn, $sql_proyectos, [$usuario_id], 'i');

    $options = '<option value="" selected>Seleccione un proyecto</option>';
    while ($row = $result_proyectos->fetch_assoc()) {
        $options .= '<option value="' . $row['ID_Proyecto'] . '">' . $row['Nombre'] . '</option>';
    }

    echo $options;
    $conn->close();
    exit;
}

// Obtener tasa ajustada
$sql_tasa_max = "SELECT Tasa FROM tasa ORDER BY Id DESC LIMIT 1";
$result_tasa_max = ejecutarConsulta($conn, $sql_tasa_max);
if ($result_tasa_max && $result_tasa_max->num_rows > 0) {
    $row_tasa_max = $result_tasa_max->fetch_assoc();
    $tasa_ajustada = $row_tasa_max['Tasa'];
} else {
    $tasa_ajustada = 0;
}
$resultadoI= null;
$resultadoII=null;
$resultadoIII=Null;

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['consultar'])) {
    $usuario_id = $_POST['usuario'];
    $proyecto_id = $_POST['proyecto'];

    // Consultar número de inversiones
    $sql_num_inversiones = "SELECT COUNT(*) AS num_inversiones FROM inversion2 WHERE FK_ID_Usuario = ?";
    $result_num_inversiones = ejecutarConsulta($conn, $sql_num_inversiones, [$usuario_id], 'i');
    $row_num_inversiones = $result_num_inversiones->fetch_assoc();
    $num_inversiones_realizadas = $row_num_inversiones['num_inversiones'];

    // Obtener fecha de inicio del proyecto
    $sql_fecha_inicio_proyecto = "SELECT Fecha FROM proyecto WHERE ID_Proyecto = ?";
    $result_fecha_inicio_proyecto = ejecutarConsulta($conn, $sql_fecha_inicio_proyecto, [$proyecto_id], 'i');
    if ($result_fecha_inicio_proyecto && $result_fecha_inicio_proyecto->num_rows > 0) {
        $row_fecha_inicio_proyecto = $result_fecha_inicio_proyecto->fetch_assoc();
        $fecha_inicio_proyecto = $row_fecha_inicio_proyecto['Fecha'];
    } else {
        $fecha_inicio_proyecto = null;
    }

    // Calcular diferencia de días
    $fecha_actual = date('Y-m-d');
    if ($fecha_inicio_proyecto) {
        $diferencia_dias = (strtotime($fecha_actual) - strtotime($fecha_inicio_proyecto)) / (60 * 60 * 24);
    }

    if (!$proyecto_id) {
        $diferencia_dias = 0;
    }

    // Consultar número de inversionistas
    $sql_num_inversionistas = "SELECT COUNT(*) AS num_inversionistas FROM proyecto_usuario WHERE FK_ID_Proyecto = ?";
    $result_num_inversionistas = ejecutarConsulta($conn, $sql_num_inversionistas, [$proyecto_id], 'i');
    $row_num_inversionistas = $result_num_inversionistas->fetch_assoc();
    $num_inversionistas = $row_num_inversionistas['num_inversionistas'];

    // Conexión adicional
    include("conexionn.php");

    if (!isset($conex)) {
        die("La conexión no está definida en 'conexionn.php'.");
    }

    // Consultas corregidas
    $consultoI = "SELECT ID_Inversion, Nombre, Monto, Monto_Ajustado, proyecto, Tipo, Fecha, Descripcion, CertificadoInversion, FK_ID_Usuario, FK_ID_Tipo FROM inversion2 WHERE FK_ID_Tipo = 1 AND FK_ID_Usuario = ?";
    $resultadoI = ejecutarConsulta($conex, $consultoI, [$usuario_id], 'i');

    $consultoII = "SELECT ID_Inversion, Nombre, Monto, Monto_Ajustado, proyecto, Tipo, Fecha, Descripcion, CertificadoInversion, FK_ID_Usuario, FK_ID_Tipo FROM inversion2 WHERE FK_ID_Tipo = 2 AND FK_ID_Usuario = ?";
    $resultadoII = ejecutarConsulta($conex, $consultoII, [$usuario_id], 'i');

    $consultoIII = "SELECT ID_Inversion, Nombre, Monto, Monto_Ajustado, proyecto, Tipo, Fecha, Descripcion, CertificadoInversion, FK_ID_Usuario, FK_ID_Tipo FROM inversion2 WHERE FK_ID_Tipo = 3 AND FK_ID_Usuario = ?";
    $resultadoIII = ejecutarConsulta($conex, $consultoIII, [$usuario_id], 'i');
}

$conn->close(); // Cerrar conexión principal
?>




<!DOCTYPE html>
<html>
	<head>
		<!-- Basic Page Info -->
		<meta charset="utf-8" />
		<title>Consultas</title>

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
		<link rel="stylesheet" href="../src/styles/style.css">
	</head>
	<body>
	
	<?php include('template.php'); ?>
		<div class="main-container">
			<div class="pd-ltr-20 xs-pd-20-10">
				<div class="min-height-200px">
				<div class="page-header">
					<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                    	<div class="row">
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-sm-12 col-md-2 col-form-label">Usuarios</label>
                                    <div class="col-sm-12 col-md-10">
                                        <select name="usuario" id="usuario" class="custom-select col-12" onchange="mostrarProyecto()">
                                            <option value="" selected>Seleccione</option>
                                            <?php while ($row = $result_usuarios->fetch_assoc()): ?>
                                                <option value="<?php echo $row['ID_Usuario']; ?>">
                                                    Cédula: <?php echo $row['ID_Usuario']; ?> - <?php echo $row['Nombre'] . ' ' . $row['Apellido']; ?>
                                                </option>
                                            <?php endwhile; ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-sm-12 col-md-2 col-form-label">Proyecto</label>
                                    <div class="col-sm-12 col-md-10">
                                        <select name="proyecto" id="proyecto" class="custom-select col-12">
                                            <option value="" selected>Seleccione un usuario</option>
                                            <!-- Opciones de proyecto generadas dinámicamente -->
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="contenido-boton">
                                <input class="btn btn-primary" type="submit" value="Consultar" name="consultar" />

                            </div>
                    	</div>
					</form>
                </div>

                <script>
                    function mostrarProyecto() {
                        var usuarioId = $('#usuario').val();
                        if (usuarioId) {
                            $.ajax({
                                url: '',
                                type: 'POST',
                                data: {usuario_id: usuarioId},
                                success: function(response) {
                                    $('#proyecto').html(response);
                                }
                            });
                        } else {
                            $('#proyecto').html('<option value="">Seleccione un usuario</option>');
                        }
                    }
                </script>
                <div class="row pb-10">
                    <div class="col-xl-3 col-lg-3 col-md-6 mb-20">
                        <div class="card-box height-100-p widget-style3">
                            <div class="d-flex flex-wrap">
                                <div class="widget-data">
                                    <div class="weight-700 font-24 text-dark"><?php echo $num_inversiones_realizadas; ?></div>
                                    <div class="font-14 text-secondary weight-500">Inversiones Realizadas</div>
                                </div>
                                <div class="widget-icon">
                                    <div class="icon" data-color="#00eccf">
                                        <i class="icon-copy dw dw-calendar1"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-3 col-md-6 mb-20">
                        <div class="card-box height-100-p widget-style3">
                            <div class="d-flex flex-wrap">
                                <div class="widget-data">
                                    <div class="weight-700 font-24 text-dark"><?php echo $diferencia_dias; ?> días</div>
                                    <div class="font-14 text-secondary weight-500">Vida del proyecto</div>
                                </div>
                                <div class="widget-icon">
                                    <div class="icon" data-color="#ff5b5b">
                                        <span class="icon-copy ti-heart"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-3 col-md-6 mb-20">
                        <div class="card-box height-100-p widget-style3">
                            <div class="d-flex flex-wrap">
                                <div class="widget-data">
                                    <div class="weight-700 font-24 text-dark"><?php echo $num_inversionistas; ?></div>
                                    <div class="font-14 text-secondary weight-500">Inversionistas</div>
                                </div>
                                <div class="widget-icon">
                                    <div class="icon">
                                        <i class="icon-copy bi bi-globe" aria-hidden="true"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-3 col-md-6 mb-20">
                        <div class="card-box height-100-p widget-style3">
                            <div class="d-flex flex-wrap">
                                <div class="widget-data">
                                    <div class="weight-700 font-24 text-dark"><?php echo $tasa_ajustada; ?>%</div>
                                    <div class="font-14 text-secondary weight-500">Tasa ajustada</div>
                                </div>
                                <div class="widget-icon">
                                    <div class="icon" data-color="#09cc06">
                                        <i class="icon-copy fa fa-money" aria-hidden="true"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row clearfix">
                    <div class="col-lg-6 col-md-12 col-sm-12 mb-30">
                        <div class="pd-20 card-box">
                            <h5 class="h4 text-blue mb-20">Resumen Inversiones</h5>
                            <div class="card-box pd-20 mb-20" style="width: 500px; height: 250px;">
							<table class="table">
								<tbody>
									<tr>
										<th scope="col">Valor de los aportes de capital</th>
										<th id="valor-capital" scope="col" style='text-align: right;'><?php echo number_format($valor_aportes_capital, 0, ','); ?></th>

									</tr>
								</tbody>
								<tbody>
									<tr>
										<th scope="col">Valor de los aportes de industria</th>
										<th id="valor-industria" scope="col" style='text-align: right;'><?php echo $valor_aportes_industria; ?></th>
									</tr>
								</tbody>
								<tbody>
									<tr>
										<th scope="col">Total Aportes:</th>
										<th id="suma-capital-industria" scope="col" style='text-align: right;'><?php echo $total_aportes; ?></th>
									</tr>
								</tbody>
							</table>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-12 col-sm-12 mb-30">
                        <div class="pd-20 card-box" style="height: 360px;">
                            <h5 class="h4 text-blue mb-20">Gráfico tipo de aporte</h5>
                            <div class="pd-20 card-box height-100-p" style="height: 255px">
                                <div id="chart8Valor" style="max-width: 500px;"></div>
                            </div>
                        </div>
                    </div>
                </div>
           
            
					<div col-md-4 mb-20>
						
						
						
						
					</div>
					<div class="pd-20 card-box mb-30">
						<div class="card-box pb-10">
							<div class="h5 pd-20 mb-0">Aporte en Dinero</div>
							<table class="table hover multiple-select-row data-table-export nowrap">
								<thead>
									<tr>
										<th>Fecha</th>
										<th>Monto</th>
										<th>Valor del aporte ajustado</th>
										<th>Tiempo transcurrido desde el día del aporte</th>
									</tr>
								</thead>
								<tbody>
									<?php
									
									$fecha_actual1 = date("Y-m-d");
									echo '<script>console.log("Fecha Actual en PHP: ' . $fecha_actual1 . '");</script>';


									
									$valor_aportes_dinero = 0;
									if (is_object($resultadoI) && mysqli_num_rows($resultadoI) > 0) {
										while ($fila = mysqli_fetch_assoc($resultadoI)) {
											echo "<tr>";
											echo "<td>" . $fila['Fecha'] . "</td>";
										
											// Calcular la diferencia en días correctamente
											$diferencia_dias1 = round((strtotime($fecha_actual1) - strtotime(date("Y-m-d", strtotime($fila['Fecha'])))) / (60 * 60 * 24), 0);
										
											// Convertir la tasa a formato decimal
											$tasa_decimal = $tasa_ajustada / 100;
										
											// Calcular el valor futuro
											$valor_futuro = round($fila['Monto'] * pow((1 + ($tasa_ajustada / 100)), $diferencia_dias1 / 365), 0);

										
											// Formatear y mostrar los valores
											echo "<td style='text-align: right;'>$ " . number_format($fila['Monto'], 0, ',', '.') . "</td>";
											echo "<td style='text-align: right;'>$ " . number_format($valor_futuro, 0, ',', '.') . "</td>";
											echo "<td style='text-align: center;'>" . $diferencia_dias1 . " días</td>";
										
											// Sumar el valor futuro a los aportes de industria
											$valor_aportes_dinero += $valor_futuro;
										
											echo "</tr>";
										}
									} else {
										echo "No se encontraron resultados.";
									}	
									?>
									<thead>
									<tr>
										<th>Total aporte en dinero (ajustado)<?php echo '<td> $ ' . number_format($valor_aportes_dinero, 0, ',', '.') . '</td>'; ?></th>
									</tr>
									</thead>
									
								</tbody>
							</table>
						</div>
					</div>
					<div class="pd-20 card-box mb-30">
						<div class="card-box pb-10">
							<div class="h5 pd-20 mb-0">Aportes en especie</div>
							<table class="table hover multiple-select-row data-table-export nowrap">
								<thead>
									<tr>
										<th>Fecha</th>
										<th>Monto</th>
										<th>Valor del Aporte ajustado</th>
										<th>Tiempo transcurrido desde el día del aporte</th>
									</tr>
								</thead>
								<tbody>
								<?php
									$fecha_actual2 = date("Y-m-d");
									$valor_aportes_especie = 0;
									if (is_object($resultadoII) && mysqli_num_rows($resultadoII) > 0) {
										while ($fila = mysqli_fetch_assoc($resultadoII)) {
											echo "<tr>";
											echo "<td>" . $fila['Fecha'] . "</td>";
											// Calcular la diferencia en días correctamente
											$diferencia_dias2 = (strtotime($fecha_actual2) - strtotime($fila['Fecha'])) / (60 * 60 * 24);
											// Calcular el valor futuro
											$valor_futuro = $fila['Monto'] * pow((1 + ($tasa_ajustada/100)), $diferencia_dias2 / 365);
											// Formatear y mostrar los valores
											echo "<td style='text-align: right;'>$ " . number_format($fila['Monto'], 0, ',', '.') . "</td>";
											echo "<td style='text-align: right;'>$ " . number_format($valor_futuro, 0, ',', '.') . "</td>";
											echo "<td style='text-align: center;'>" . round($diferencia_dias2) . " días</td>";
											// Sumar el valor futuro a los aportes de industria
											$valor_aportes_especie += $valor_futuro;
											
											echo "</tr>";
										}
									} else {
										echo "No se encontraron resultados.";
									}	
									?>
									<thead>
									<tr>
										<th>Total aporte en dinero (ajustado)<?php echo '<td>$' . number_format($valor_aportes_especie, 0, ',', '.') . '</td>'; ?></th>
									</tr>
									</thead>
								</tbody>
							</table>
						</div>
					</div>
					<div class="pd-20 card-box mb-30">
						<div class="card-box pb-10">
							<div class="h5 pd-20 mb-0">Aportes en industria</div>
							<table class="table hover multiple-select-row data-table-export nowrap">
								<thead>
									<tr>
										<th>Fecha</th>
										<th>Monto</th>
										<th>Valor del Aporte ajustado</th>
										<th>Tiempo transcurrido desde el día del aporte</th>
									</tr>
								</thead>
								<tbody>
									<?php
										$fecha_actual3 = date("Y-m-d");
										$valor_aportes_industria = 0;
										if (is_object($resultadoIII) && mysqli_num_rows($resultadoIII) > 0) {
											while ($fila = mysqli_fetch_assoc($resultadoIII)) {
												echo "<tr>";
												echo "<td>" . $fila['Fecha'] . "</td>";
												// Calcular la diferencia en días correctamente
												$diferencia_dias3 = (strtotime($fecha_actual3) - strtotime($fila['Fecha'])) / (60 * 60 * 24);
												// Calcular el valor futuro
												$valor_futuro = $fila['Monto'] * pow((1 + ($tasa_ajustada/100)), $diferencia_dias3 / 365);
												// Formatear y mostrar los valores
												echo "<td style='text-align: right;'>$ " . number_format($fila['Monto'], 0, ',', '.') . "</td>";
												echo "<td style='text-align: right;'>$ " . number_format($valor_futuro, 0, ',', '.') . "</td>";
												echo "<td style='text-align: center;'>" . round($diferencia_dias3) . " días</td>";
												// Sumar el valor futuro a los aportes de industria
												$valor_aportes_industria += round($valor_futuro, 0);

												// Destruir la instancia anterior antes de re-inicializar


												echo "</tr>";
											}
										} else {
											echo "No se encontraron resultados.";
										}	
									?>
									<thead>
									<tr>
										<th>Total aporte en dinero (ajustado)<?php echo '<td>$' . number_format($valor_aportes_industria, 0, ',', '.') . '</td>'; ?></th>
									</tr>
									</thead>
								</tbody>
							</table>
						</div>
					</div>
			</div>
		</div>
		<script>
			<?php
			// Calcular valor de los aportes de capital sumando aportes en dinero y especie
			$valor_aportes_capital =round($valor_aportes_dinero + $valor_aportes_especie,0);

			// Calcular el total de aportes sumando aportes de capital e industria
			$total_aportes =round($valor_aportes_capital + $valor_aportes_industria,0) ;
			?>
			document.addEventListener("DOMContentLoaded", function() {
				// Valores desde PHP
				var valorAportesIndustria = <?php echo $valor_aportes_industria; ?>;
				var valorAportesCapital = <?php echo $valor_aportes_capital; ?>;
				var totalAportes = <?php echo $total_aportes; ?>;

				/// Formateando los valores como moneda colombiana sin decimales (COP)
			document.getElementById('valor-capital').innerText ="$ "+ valorAportesCapital.toLocaleString( {  minimumFractionDigits: 0 });
			document.getElementById('valor-industria').innerText ="$ "+ valorAportesIndustria.toLocaleString( {  minimumFractionDigits: 0 });
			document.getElementById('suma-capital-industria').innerText ="$ "+ totalAportes.toLocaleString( {  minimumFractionDigits: 0 });
			});
			
		</script>
		
		
		<script src="../vendors/scripts/core.js"></script>
<script src="../vendors/scripts/script.min.js"></script>
<script src="../vendors/scripts/process.js"></script>
<script src="../vendors/scripts/layout-settings.js"></script>
<script src="../src/plugins/apexcharts/apexcharts.min.js"></script>
<script src="../vendors/scripts/apexcharts-setting.js"></script>
<script src="../src/plugins/datatables/js/jquery.dataTables.min.js"></script>
<script src="../src/plugins/datatables/js/dataTables.bootstrap4.min.js"></script>
<script src="../src/plugins/datatables/js/dataTables.responsive.min.js"></script>
<script src="../src/plugins/datatables/js/responsive.bootstrap4.min.js"></script>
<script src="../vendors/scripts/dashboard3.js"></script>

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
