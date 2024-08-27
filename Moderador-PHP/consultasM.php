<?php
// Iniciar la sesión
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['authenticated']) || $_SESSION['authenticated'] !== true) {
    // Si el usuario no está autenticado, redirigirlo a la página de inicio de sesión
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

// Definir las variables para evitar advertencias de "Undefined variable"
$num_inversiones_realizadas = 0;
$diferencia_dias = 0;
$num_inversionistas = 0;
$tasa_ajustada = 0;
$total_aportes = 0;
$valor_aportes_capital = 0;
$valor_aportes_industria = 0;

// Consulta para obtener usuarios asociados al proyecto
$sql_usuarios = "SELECT DISTINCT u.ID_Usuario, u.Nombre, u.Apellido 
                  FROM usuario2 u
                  INNER JOIN proyecto_usuario pu ON u.ID_Usuario = pu.FK_ID_Usuario
                  WHERE pu.FK_ID_Proyecto = ?";
$stmt_usuarios = $conn->prepare($sql_usuarios);
$stmt_usuarios->bind_param("i", $proyectoID);
$stmt_usuarios->execute();
$result_usuarios = $stmt_usuarios->get_result();

// Verificar si la solicitud es AJAX para obtener los proyectos del usuario seleccionado
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['usuario_id'])) {
    $usuario_id = $_POST['usuario_id'];

    // Consulta para obtener proyectos relacionados con el usuario seleccionado
    $sql_proyectos = "SELECT p.ID_Proyecto, p.Nombre 
                      FROM proyecto p 
                      INNER JOIN proyecto_usuario pu ON p.ID_Proyecto = pu.FK_ID_Proyecto 
                      WHERE pu.FK_ID_Usuario = ?";
    $stmt = $conn->prepare($sql_proyectos);
    $stmt->bind_param("i", $usuario_id);
    $stmt->execute();
    $result_proyectos = $stmt->get_result();

    // Generar opciones de proyecto
	$options = '<option value="" selected>Seleccione un proyecto</option>';
	while ($row = $result_proyectos->fetch_assoc()) {
		// Comprobar si el ID del proyecto coincide con el valor de la variable $proyectoID
		$selected = ($row['ID_Proyecto'] == $proyectoID) ? ' selected' : '';
		$options .= '<option value="' . $row['ID_Proyecto'] . '"' . $selected . '>' . $row['Nombre'] . '</option>';
	}

	// Mostrar las opciones
	echo $options;

    // Cerrar conexión y salir para detener la ejecución del resto del script
    $conn->close();
    exit;
}

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

$resultadoI= null;
$resultadoII=null;
$resultadoIII=Null;
// Verificar si se envió el formulario de consulta
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['consultar'])) {
    
    // Obtener el usuario y proyecto seleccionados
    $usuario_id = $_POST['usuario'];
    $proyecto_id = $_POST['proyecto'];

    // Consulta para contar el número de inversiones realizadas
	$sql_num_inversiones = "SELECT COUNT(*) AS num_inversiones FROM inversion2 WHERE FK_ID_Usuario = ? AND proyecto = (SELECT Nombre FROM proyecto WHERE ID_Proyecto = ?)";
	$stmt = $conn->prepare($sql_num_inversiones);
	mysqli_stmt_bind_param($stmt, "ii", $usuario_id, $proyecto_id);
	$stmt->execute();
	$result_num_inversiones = $stmt->get_result();
	$row_num_inversiones = $result_num_inversiones->fetch_assoc();
	$num_inversiones_realizadas = $row_num_inversiones['num_inversiones'];



    $sql_fecha_inicio_proyecto = "SELECT Fecha FROM proyecto WHERE ID_Proyecto = ?";
    $stmt = $conn->prepare($sql_fecha_inicio_proyecto);	
    $stmt->bind_param("i", $proyecto_id);
    $stmt->execute();
    $result_fecha_inicio_proyecto = $stmt->get_result();

    // Verificar si hay resultados antes de intentar acceder a la fila
    if ($result_fecha_inicio_proyecto && $result_fecha_inicio_proyecto->num_rows > 0) {
        $row_fecha_inicio_proyecto = $result_fecha_inicio_proyecto->fetch_assoc();
        $fecha_inicio_proyecto = $row_fecha_inicio_proyecto['Fecha'];
    } else {
        // Si no hay resultados, asignar la fecha de inicio del proyecto como nula o algún valor predeterminado
        $fecha_inicio_proyecto = null; // o asigna algún valor predeterminado
    }

    // Calcular la diferencia de días entre la fecha actual y la fecha de inicio del proyecto
    $fecha_actual = date('Y-m-d');
    if ($fecha_inicio_proyecto) {
        $diferencia_dias = (strtotime($fecha_actual) - strtotime($fecha_inicio_proyecto)) / (60 * 60 * 24);
    }

    // Si el usuario no tiene proyectos, la diferencia de días es cero
    if (!$proyecto_id) {
        $diferencia_dias = 0;
    }

    // Consulta para contar el número de inversionistas en el proyecto
    $sql_num_inversionistas = "SELECT COUNT(*) AS num_inversionistas FROM proyecto_usuario WHERE FK_ID_Proyecto = ?";
    $stmt = $conn->prepare($sql_num_inversionistas);
    $stmt->bind_param("i", $proyecto_id);
    $stmt->execute();
    $result_num_inversionistas = $stmt->get_result();
    $row_num_inversionistas = $result_num_inversionistas->fetch_assoc();
    $num_inversionistas = $row_num_inversionistas['num_inversionistas'];
    
    include("conexionn.php");
    
    // Verifica que la conexión $conex esté definida en 'conexionn.php'
    if (!isset($conex)) {
        die("La conexión no está definida en 'conexionn.php'.");
    }

    // Corrección de las consultas
    $consultoI = "SELECT ID_Inversion, Nombre, Monto, Monto_Ajustado, proyecto, 
        Tipo, Fecha, Descripcion, CertificadoInversion, FK_ID_Usuario, FK_ID_Tipo  
        FROM inversion2
        WHERE FK_ID_Tipo = 1 AND FK_ID_Usuario = ? AND proyecto = (SELECT Nombre FROM proyecto WHERE ID_Proyecto = ?)";
    $stmtI = mysqli_prepare($conex, $consultoI);
    mysqli_stmt_bind_param($stmtI, "ii", $usuario_id, $proyecto_id);
    mysqli_stmt_execute($stmtI);
    $resultadoI = mysqli_stmt_get_result($stmtI);

    $consultoII = "SELECT ID_Inversion, Nombre, Monto, Monto_Ajustado, proyecto, 
        Tipo, Fecha, Descripcion, CertificadoInversion, FK_ID_Usuario, FK_ID_Tipo  
        FROM inversion2
        WHERE FK_ID_Tipo = 2 AND FK_ID_Usuario = ? AND proyecto = (SELECT Nombre FROM proyecto WHERE ID_Proyecto = ?)";
    $stmtII = mysqli_prepare($conex, $consultoII);
    mysqli_stmt_bind_param($stmtII, "ii", $usuario_id, $proyecto_id);
    mysqli_stmt_execute($stmtII);
    $resultadoII = mysqli_stmt_get_result($stmtII);

    $consultoIII = "SELECT ID_Inversion, Nombre, Monto, Monto_Ajustado, proyecto, 
        Tipo, Fecha, Descripcion, CertificadoInversion, FK_ID_Usuario, FK_ID_Tipo  
        FROM inversion2
        WHERE FK_ID_Tipo = 3 AND FK_ID_Usuario = ? AND proyecto = (SELECT Nombre FROM proyecto WHERE ID_Proyecto = ?)";
    $stmtIII = mysqli_prepare($conex, $consultoIII);
    mysqli_stmt_bind_param($stmtIII, "ii", $usuario_id, $proyecto_id);
    mysqli_stmt_execute($stmtIII);
    $resultadoIII = mysqli_stmt_get_result($stmtIII);
}


?>


<!DOCTYPE html>
<html>
	<head>
		<!-- Basic Page Info -->
		<meta charset="utf-8" />
		<title>Parametros-Usuarios</title>
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
		<link rel="stylesheet" href="src\styles\style.css">
	</head>
	<body>	
	<?php include('templateM.php'); ?>
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
                            <style>
								#proyecto {
									display: none;
								}
							</style>

							<div class="col-md-6">
								<div class="form-group row">
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
										<th id="valor-capital" scope="col"><?php echo $valor_aportes_capital; ?></th>
									</tr>
								</tbody>
								<tbody>
									<tr>
										<th scope="col">Valor de los aportes de industria</th>
										<th id="valor-industria" scope="col"><?php echo $valor_aportes_industria; ?></th>
									</tr>
								</tbody>
								<tbody>
									<tr>
										<th scope="col">Total Aportes:</th>
										<th id="suma-capital-industria" scope="col"><?php echo $total_aportes; ?></th>
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
							<table class="data-table table nowrap">
								<thead>
									<tr>
										<th>Fecha</th>
										<th>Monto</th>
										<th>Valor del Aporte ajustado</th>
										<th>Tiempo transcurrido desde el día del aporte</th>
										<th>Total aporte en dinero (ajustado)</th>
									</tr>
								</thead>
								<tbody>
									<?php
									$fecha_actual1 = date("Y-m-d");
									$valor_aportes_dinero = 0;
									if (is_object($resultadoI) && mysqli_num_rows($resultadoI) > 0) {
										while ($fila = mysqli_fetch_assoc($resultadoI)) {
											echo "<tr>";
											echo "<td>" . $fila['Fecha'] . "</td>";
											// Calcular la diferencia en días correctamente
											$diferencia_dias1 = (strtotime($fecha_actual1) - strtotime($fila['Fecha'])) / (60 * 60 * 24);
											// Calcular el valor futuro
											$valor_futuro = $fila['Monto'] * pow((1 + ($tasa_ajustada/100)), $diferencia_dias1 / 365);
											// Formatear y mostrar los valores
											echo "<td>" . number_format($fila['Monto'], 0, ',', '.') . "</td>";
											echo "<td>" . number_format($valor_futuro, 0, ',', '.') . "</td>";
											echo "<td>" . round($diferencia_dias1) . " días</td>";
											// Sumar el valor futuro a los aportes de industria
											$valor_aportes_dinero += $valor_futuro;
											echo '<td>' . number_format($valor_aportes_dinero, 0, ',', '.') . '</td>';
											echo "</tr>";
										}
									} else {
										echo "No se encontraron resultados.";
									}
									?>
									<thead>
									<tr>
										<th>Total aporte en dinero (ajustado)<?php echo '<td>' . number_format($valor_aportes_dinero, 0, ',', '.') . '</td>'; ?></th>
									</tr>
									</thead>
									
								</tbody>
							</table>
						</div>
					</div>
					<div class="pd-20 card-box mb-30">
						<div class="card-box pb-10">
							<div class="h5 pd-20 mb-0">Aportes en especie</div>
							<table class="data-table table nowrap">
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
											echo "<td>" . number_format($fila['Monto'], 0, ',', '.') . "</td>";
											echo "<td>" . number_format($valor_futuro, 0, ',', '.') . "</td>";
											echo "<td>" . round($diferencia_dias2) . " días</td>";
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
										<th>Total aporte en dinero (ajustado)<?php echo '<td>' . number_format($valor_aportes_especie, 0, ',', '.') . '</td>'; ?></th>
									</tr>
									</thead>
								</tbody>
							</table>
						</div>
					</div>
					<div class="pd-20 card-box mb-30">
						<div class="card-box pb-10">
							<div class="h5 pd-20 mb-0">Aportes en industria</div>
							<table class="data-table table nowrap">
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
												echo "<td>" . number_format($fila['Monto'], 0, ',', '.') . "</td>";
												echo "<td>" . number_format($valor_futuro, 0, ',', '.') . "</td>";
												echo "<td>" . round($diferencia_dias3) . " días</td>";
												// Sumar el valor futuro a los aportes de industria
												$valor_aportes_industria += $valor_futuro;
												echo "</tr>";
											}
										} else {
											echo "No se encontraron resultados.";
										}	
									?>
									<thead>
									<tr>
										<th>Total aporte en dinero (ajustado)<?php echo '<td>' . number_format($valor_aportes_industria, 0, ',', '.') . '</td>'; ?></th>
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
			$valor_aportes_capital = $valor_aportes_dinero + $valor_aportes_especie;

			// Calcular el total de aportes sumando aportes de capital e industria
			$total_aportes = $valor_aportes_capital + $valor_aportes_industria;
			?>
			document.addEventListener("DOMContentLoaded", function() {
				// Valores desde PHP
				var valorAportesIndustria = <?php echo $valor_aportes_industria; ?>;
				var valorAportesCapital = <?php echo $valor_aportes_capital; ?>;
				var totalAportes = <?php echo $total_aportes; ?>;

				/// Formateando los valores como moneda colombiana sin decimales (COP)
			document.getElementById('valor-capital').innerText = valorAportesCapital.toLocaleString( {  minimumFractionDigits: 0 });
			document.getElementById('valor-industria').innerText = valorAportesIndustria.toLocaleString( {  minimumFractionDigits: 0 });
			document.getElementById('suma-capital-industria').innerText = totalAportes.toLocaleString( {  minimumFractionDigits: 0 });
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