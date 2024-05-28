<?php
// Iniciar la sesión
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['authenticated']) || $_SESSION['authenticated'] !== true) {
    // Si el usuario no está autenticado, redirigirlo a la página de inicio de sesión
    header("Location: http://localhost/sistema-inversiones-v2/inicio.php"); // Cambia 'inicio-de-sesion.php' por la ruta de tu página de inicio de sesión
    exit();
}
?>
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
		<link rel="stylesheet" href="styles/style.css">

		
	</head>
	<body>
			<!-- CONEXION PHP-->
	<?php
	include("conexionn.php");

	// Consulta para obtener los datos de la tabla municipio
	$consulta_departamento = "SELECT ID_Departamento, Nombre, FK_ID_Pais FROM departamento";
	$resultado_departamento = mysqli_query($conex, $consulta_departamento);

	// Crear un array para almacenar los datos
	$datos_departamento = array();
	while ($fila_departamento = mysqli_fetch_assoc($resultado_departamento)) {
		$datos_departamento[] = $fila_departamento;
	}

	// Consulta para obtener los datos de la tabla pais
	$consulta_pais = "SELECT ID_Pais, Nombre FROM pais";
	$resultado_pais = mysqli_query($conex, $consulta_pais);

	// Crear un array para almacenar los datos
	$datos_pais = array();
	while ($fila_pais = mysqli_fetch_assoc($resultado_pais)) {
		$datos_pais[] = $fila_pais;
	}
	
	$consulto = "SELECT ID_Pais, Nombre FROM pais";
	$resultado = mysqli_query($conex, $consulto);
	
	$consultoe = "SELECT ID_Departamento, Nombre, FK_ID_Pais  FROM departamento";
	$resultadoe = mysqli_query($conex, $consultoe);

	$consultoi = "SELECT ID_Municipio, Nombre, FK_ID_Departamento  FROM municipio";
	$resultadoi = mysqli_query($conex, $consultoi);
	
	?>

		<?php include('template.php'); ?>
		<div class="main-container">
			<!-- TABLAS -->
			<div class="pd-20 card-box mb-30">
				<div class="title pb-20 pt-20">
				<div class="h5 pd-20 mb-0">Paises</div>
				<table class="data-table table nowrap">
					<thead>
						<tr>
							<th>Identificador</th>
							<th class="table-plus">Nombre</th>
							<th class="datatable-nosort">Acciones</th>
						</tr>
					</thead>
					<tbody>
						<?php
						while ($fila = mysqli_fetch_assoc($resultado)) {
							echo "<tr>";
							echo "<td> +" . $fila['ID_Pais'] . "</td>";
							echo "<td>" . $fila['Nombre'] . "</td>";
							echo '<td>';
							echo '<div class="table-actions">';
							echo '<a href="#" data-color="#265ed7"><i class="icon-copy dw dw-edit2"></i></a>';
							echo '<a href="#" data-color="#e95959"><i class="icon-copy dw dw-delete-3"></i></a>';
							echo '</div>';
							echo '</td>';
							echo '</tr>';
						}
						?>
					</tbody>
				</table>
				</div>
			</div>

			<div class="pd-20 card-box mb-30">
				<div class="title pb-20 pt-20">
				<div class="h5 pd-20 mb-0">Departamentos o Provinicias</div>
					<table class="data-table table nowrap">
						<thead>
							<tr>
								<th class="table-plus">Nombre</th>
								<th class="table-plus">País</th>
								<th class="datatable-nosort">Acciones</th>
							</tr>
						</thead>
						<tbody>
							<?php
							while ($fila = mysqli_fetch_assoc($resultadoe)) {
								echo "<tr>";
								echo "<td>" . $fila['Nombre'] . "</td>";

								// Consulta para obtener el nombre del país
								$consulta_paiss = "SELECT Nombre FROM pais WHERE ID_Pais = " . $fila['FK_ID_Pais'];
								$resultado_paiss = mysqli_query($conex, $consulta_paiss);
								$fila_pais = mysqli_fetch_assoc($resultado_paiss);

								echo "<td>" . $fila_pais['Nombre'] . "</td>";
								
								echo '<td>';
								echo '<div class="table-actions">';
								echo '<a href="#" data-color="#265ed7"><i class="icon-copy dw dw-edit2"></i></a>';
								echo '<a href="#" data-color="#e95959"><i class="icon-copy dw dw-delete-3"></i></a>';
								echo '</div>';
								echo '</td>';
								echo '</tr>';
							}
							?>
						</tbody>
					</table>
				</div>
			</div>

			<div class="pd-20 card-box mb-30">
				<div class="title pb-20 pt-20">
				<div class="h5 pd-20 mb-0">Municipios o Ciudades</div>
				<table class="data-table table nowrap">
						<thead>
							<tr>
								<th class="table-plus">Nombre</th>
								<th class="table-plus">Departamento</th>
								<th class="datatable-nosort">Acciones</th>
							</tr>
						</thead>
						<tbody>
							<?php
							while ($fila = mysqli_fetch_assoc($resultadoi)) {
								echo "<tr>";
								echo "<td>" . $fila['Nombre'] . "</td>";

								// Consulta para obtener el nombre del departamento
								$consulta_departamentoo = "SELECT Nombre FROM departamento WHERE ID_Departamento = " . $fila['FK_ID_Departamento'];
								$resultado_departamentoo = mysqli_query($conex, $consulta_departamentoo);
								$fila_departamentoo = mysqli_fetch_assoc($resultado_departamentoo);

								echo "<td>" . $fila_departamentoo['Nombre'] . "</td>";
								
								echo '<td>';
								echo '<div class="table-actions">';
								echo '<a href="#" data-color="#265ed7"><i class="icon-copy dw dw-edit2"></i></a>';
								echo '<a href="#" data-color="#e95959"><i class="icon-copy dw dw-delete-3"></i></a>';
								echo '</div>';
								echo '</td>';
								echo '</tr>';
							}
							?>
						</tbody>
					</table>


				</div>
			</div>
			<!-- REGISTRO DE TABLAS-->
			<div class="xs-pd-20-10 pd-ltr-20">
				
				<div class="title pb-20 pt-20">
					<h2 class="h3 mb-0">Creación de Ubicaciones</h2>
				</div>
				
					<div class="pd-20 card-box mb-30">
						<form action="registrar.php" method="post">
							
							<div class="form-group row">
								<label class="col-sm-12 col-md-2 col-form-label">Identificador</label>
								<div class="col-sm-12 col-md-10">
									<input name="id_pais" class="form-control" type="number" placeholder="01,02,03,04,05">
								</div>
							</div>
							<div class="form-group row">
								<label class="col-sm-12 col-md-2 col-form-label">País</label>
								<div class="col-sm-12 col-md-10">
									<input name="pais" class="form-control" type="text" placeholder="Colombia">
								</div>
							</div>
							<div class="contenido-boton">
								<input class="btn btn-primary" type="submit" name="register_pais" value="Guardar">
							</div>
						</form>
					</div>
					<div class="pd-20 card-box mb-30">
						<form action="registrar.php" method="post">
							
							
							<div class="form-group row">
								<label class="col-sm-12 col-md-2 col-form-label">Departamento</label>
								<div class="col-sm-12 col-md-10">
									<input name="departamentoo" class="form-control" type="text" placeholder="Cundinamarca">
								</div>
							</div>
							<div class="form-group row">
								<label class="col-sm-12 col-md-2 col-form-label">Pais</label>
								<div class="col-sm-12 col-md-10">
									<select name="FK_Pais" class="custom-select col-12">
										<option selected="">Seleccione</option>
										<?php foreach ($datos_pais as $pais): ?>
											<option value="<?php echo $pais['ID_Pais']; ?>">
												<?php echo $pais['Nombre']; ?>
											</option>
										<?php endforeach; ?>
									</select>
								</div>
							</div>
							<div class="contenido-boton">
								<input class="btn btn-primary" type="submit" name="register_departamento" value="Guardar">
							</div>
						</form>
					</div>
					<div class="pd-20 card-box mb-30">
						<form action="registrar.php" method="post">
								<div class="form-group row">
									<label class="col-sm-12 col-md-2 col-form-label">municipio</label>
									<div class="col-sm-12 col-md-10">
										<input name="municipioo" class="form-control" type="text" placeholder="Fusagasuga">
									</div>
								</div>
								<div class="form-group row">
									<label class="col-sm-12 col-md-2 col-form-label">Departamento</label>
									<div class="col-sm-12 col-md-10">
										<select name="FK_Departamento" class="custom-select col-12">
											<option selected="">Seleccione</option>
											<?php foreach ($datos_departamento as $municipio): ?>
												<option value="<?php echo $municipio['ID_Departamento']; ?>">
													<?php echo  $municipio['Nombre']  ; ?>
												</option>
											<?php endforeach; ?>
										</select>
									</div>
								</div>

								<div class="contenido-boton">
									<input class="btn btn-primary" type="submit" name="register_municipio" value="Guardar">
								</div>
						</form>
					</div>
			</div>
		</div>
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
