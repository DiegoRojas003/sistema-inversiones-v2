<!DOCTYPE html>
<html>
	<head>
		<script>
			function mostrarProyecto() {
				var rol = document.getElementById("rol").value;
				var proyectoField = document.getElementById("proyectoField");
				var proyectoInput = document.getElementById("proyecto");

				// Si el rol seleccionado es Administrador (valor 1), ocultar el campo de proyecto y establecer su valor en "todos"
				if (rol == 1) {
					proyectoField.style.display = "none";
					proyectoInput.value = "Todos"; // Establecer el valor en "todos"
				} else {
					proyectoField.style.display = "block";
				}
			}
		</script>
		<!-- Basic Page Info -->
		<meta charset="utf-8" />
		<title>Parámetros-Usuarios</title>

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
		<link rel="stylesheet" href="styles/style.css">

		
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

	// Consulta para obtener los datos de la tabla pais
	$consulta_pais = "SELECT ID_Pais, Nombre FROM pais";
	$resultado_pais = mysqli_query($conex, $consulta_pais);

	// Crear un array para almacenar los datos
	$datos_pais = array();
	while ($fila_pais = mysqli_fetch_assoc($resultado_pais)) {
		$datos_pais[] = $fila_pais;
	}

	// Consulta para obtener los datos de la tabla municipio
	$consulta_departamento = "SELECT ID_Departamento, Nombre, FK_ID_Pais FROM departamento";
	$resultado_departamento = mysqli_query($conex, $consulta_departamento);

	// Crear un array para almacenar los datos
	$datos_departamento = array();
	while ($fila_departamento = mysqli_fetch_assoc($resultado_departamento)) {
		$datos_departamento[] = $fila_departamento;
	}

	// Consulta para obtener los datos de la tabla municipio
	$consulta_Municipio = "SELECT ID_Municipio, Nombre, FK_ID_Departamento FROM municipio";
	$resultado_Municipio = mysqli_query($conex, $consulta_Municipio);

	// Crear un array para almacenar los datos
	$datos_Municipio = array();
	while ($fila_Municipio = mysqli_fetch_assoc($resultado_Municipio)) {
		$datos_Municipio[] = $fila_Municipio;
	}

	// Consulta para obtener los datos de la tabla municipio
	$consulta_rol = "SELECT ID_Rol, Nombre, Descripcion FROM rol ";
	$resultado_rol = mysqli_query($conex, $consulta_rol);

	// Crear un array para almacenar los datos
	$datos_rol = array();
	while ($fila_rol= mysqli_fetch_assoc($resultado_rol)) {
		$datos_rol[] = $fila_rol;
	}


	$consultou = "SELECT ID_Usuario , Nombre, Apellido, Telefono, Correo, Contraseña, Fecha, Proyecto, FK_ID_Municipio, FK_ID_Rol  FROM usuario2";
		$resultadou = mysqli_query($conex, $consultou);

	$consultop = "SELECT ID_Proyecto , Nombre, Fecha, Descripcion, Certificado  FROM proyecto";
		$resultadop = mysqli_query($conex, $consultop);

	$consultoi = "SELECT ID_Municipio, Nombre, FK_ID_Departamento  FROM municipio";
		$resultadoi = mysqli_query($conex, $consultoi);	

	$consultor = "SELECT ID_Rol, Nombre, Descripcion  FROM rol";
		$resultador = mysqli_query($conex, $consultor);

	?>
		<div id="template"></div>
		
		<div class="main-container">
			<div class="xs-pd-20-10 pd-ltr-20">
				<div class="title pb-20">
					<h2 class="h3 mb-0">Inversionistas y Moderadores</h2>
				</div>
				<div class="pd-20 card-box mb-30">
					<div class="h5 pd-20 mb-0">Usuarios</div>
					<table class="data-table table nowrap">
						<thead>
							<tr>
								<th>Cédula</th>
								<th class="table-plus">Nombre</th>
								<th>Apellido</th>
								<th>Teléfono</th>
								<th>Correo</th>
								<th>Contraseña</th>
								<th>Fecha</th>
								<th>Proyecto</th>
								<th>Municipio</th>
								<th>Rol</th>
								<th class="datatable-nosort">Acciones</th>
							</tr>
						</thead>
						<tbody>
							<?php
							while ($fila = mysqli_fetch_assoc($resultadou)) {
								echo "<tr>";
								echo "<td>" . $fila['ID_Usuario'] . "</td>";
								echo "<td>" . $fila['Nombre'] . "</td>";
								echo "<td>" . $fila['Apellido'] . "</td>";
								echo "<td>" . $fila['Telefono'] . "</td>";
								echo "<td>" . $fila['Correo'] . "</td>";
								echo "<td>" . $fila['Contraseña'] . "</td>";
								echo "<td>" . $fila['Fecha'] . "</td>";
								
								// Buscar el nombre del proyecto
								$proyecto_nombre = "";
								foreach ($datos_proyecto as $proyecto) {
									if ($proyecto['ID_Proyecto'] == $fila['Proyecto']) {
										$proyecto_nombre = $proyecto['Nombre'];
										break;
									}
								}
								// Si el nombre del proyecto está vacío, establecerlo en "TODOS"
								if (empty($proyecto_nombre)) {
									$proyecto_nombre = "TODOS";
								}
								echo "<td>" . $proyecto_nombre . "</td>";

								// Buscar el nombre del municipio
								$municipio_nombre = "";
								foreach ($datos_Municipio as $municipio) {
									if ($municipio['ID_Municipio'] == $fila['FK_ID_Municipio']) {
										$municipio_nombre = $municipio['Nombre'];
										break;
									}
								}
								echo "<td>" . $municipio_nombre . "</td>";

								// Buscar el nombre del rol
								$rol_nombre = "";
								foreach ($datos_rol as $rol) {
									if ($rol['ID_Rol'] == $fila['FK_ID_Rol']) {
										$rol_nombre = $rol['Nombre'];
										break;
									}
								}
								echo "<td>" . $rol_nombre . "</td>";

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

				<div class="title pb-20">
					<h2 class="h3 mb-0">Resgistros de Proyectos, Municipios y Roles</h2>
				</div>

				<div class="pd-20 card-box mb-30">
					<div class="h5 pd-20 mb-0">Proyectos</div>
					<table class="data-table table nowrap">
						<thead>
							<tr>
								<th>Identificador</th>
								<th class="table-plus">Nombre</th>
								<th>Fecha</th>
								<th>Descripción</th>
							</tr>
						</thead>	
						<tbody>
							<?php
							while ($fila = mysqli_fetch_assoc($resultadop)) {
								echo "<tr>";
								echo "<td>" . $fila['ID_Proyecto'] . "</td>";
								echo "<td>" . $fila['Nombre'] . "</td>";
								echo "<td>" . $fila['Fecha'] . "</td>";
								echo "<td>" . $fila['Descripcion'] . "</td>";
							}
							?>
						</tbody>
					</table>

				</div>
				<div class="pd-20 card-box mb-30">
					<div class="h5 pd-20 mb-0">Municipios</div>
					<table class="data-table table nowrap">
						<thead>
							<tr>
								<th class="table-plus">Nombre</th>
							</tr>
						</thead>
						<tbody>
							<?php
							while ($fila = mysqli_fetch_assoc($resultadoi)) {
								echo "<tr>";
								echo "<td>" . $fila['Nombre'] . "</td>";
							}
							?>
						</tbody>
					</table>
				</div>

				<div class="pd-20 card-box mb-30">
					<div class="h5 pd-20 mb-0">Roles</div>
					<table class="data-table table nowrap">
						<thead>
							<tr>
								<th class="table-plus">Nombre</th>
								<th class="table-plus">Descripcion</th>
							</tr>
						</thead>
						<tbody>
							<?php
							while ($fila = mysqli_fetch_assoc($resultador)) {
								echo "<tr>";
								echo "<td>" . $fila['Nombre'] . "</td>";
								echo "<td>" . $fila['Descripcion'] . "</td>";
							}
							?>
						</tbody>
					</table>
				</div>

				<div class="title pb-20 pt-20">
					<h2 class="h3 mb-0">Creación de Usuarios</h2>
				</div>

				
					<div class="pd-20 card-box mb-30">
						<form action="registrar.php" method="post">
							<div class="form-group row">
								<label class="col-sm-12 col-md-2 col-form-label">Cédula</label>
								<div class="col-sm-12 col-md-10">
									<input name= "cedula" class="form-control" type="number" placeholder="33457">
								</div>
							</div>
							<div class="form-group row">
								<label class="col-sm-12 col-md-2 col-form-label">Nombre</label>
								<div class="col-sm-12 col-md-10">
									<input name= "nombre" class="form-control" type="text" placeholder="Johnny">
								</div>
							</div>
							<div class="form-group row">
								<label class="col-sm-12 col-md-2 col-form-label">Apellido</label>
								<div class="col-sm-12 col-md-10">
									<input name= "apellido" class="form-control" placeholder="Garzón" type="text">
								</div>
							</div>
							<div class="form-group row">
								<label class="col-sm-12 col-md-2 col-form-label">Teléfono</label>
								<div class="col-sm-12 col-md-10">
									<input name= "telefono"  class="form-control" type="number" placeholder="322 2535668" >
								</div>
							</div>
							<div class="form-group row">
								<label class="col-sm-12 col-md-2 col-form-label">Correo</label>
								<div class="col-sm-12 col-md-10">
									<input name= "correo" class="form-control" placeholder="user@example.com" type="email">
								</div>
							</div>
							
							
							<div class="form-group row">
								<label class="col-sm-12 col-md-2 col-form-label">Contraseña</label>
								<div class="col-sm-12 col-md-10">
									<input name= "contrasena" class="form-control" placeholder="password" type="password">
								</div>
							</div>
							<div class="form-group row">
								<label class="col-sm-12 col-md-2 col-form-label">Confirmacion de Contraseña</label>
								<div class="col-sm-12 col-md-10">
									<input name= "contrasena2" class="form-control" placeholder="password" type="password">
								</div>
							</div>
							
							<div class="form-group row">
								<label class="col-sm-12 col-md-2 col-form-label">Fecha de nacimiento</label>
								<div class="col-sm-12 col-md-10">
									<input name= "fecha" class="form-control date-picker" placeholder="Select Date" type="text">
								</div>
							</div>
							<div class="form-group row">
								<label class="col-sm-12 col-md-2 col-form-label">Rol</label>
								<div class="col-sm-12 col-md-10">
									<select name="rol" id="rol" class="custom-select col-12" onchange="mostrarProyecto()">
										<option selected="">Seleccione</option>
										<?php foreach ($datos_rol as $rol): ?>
											<option value="<?php echo $rol['ID_Rol']; ?>">
												<?php echo $rol['Nombre']; ?>
											</option>
										<?php endforeach; ?>
									</select>
								</div>
							</div>

							<div class="form-group row" id="proyectoField">
								<label class="col-sm-12 col-md-2 col-form-label">Proyecto</label>
								<div class="col-sm-12 col-md-10"> <!-- Dividimos el espacio con el campo de selección de proyecto -->
									<select name="proyecto" id="proyecto" class="custom-select col-12">
										<option value="Todos" selected="">Todos</option>
										<?php foreach ($datos_proyecto as $proyecto): ?>
											<option value="<?php echo $proyecto['ID_Proyecto']; ?>">
												<?php echo $proyecto['Nombre']; ?>
											</option>
										<?php endforeach; ?>
									</select>
								</div>
							</div>
							
							<div class="form-group row">
								<label class="col-sm-12 col-md-2 col-form-label">Ciudad</label>
								<div class="col-sm-12 col-md-10">
									<select name="ciudad" class="custom-select col-12">
										<option selected="">Seleccione</option>
										<?php foreach ($datos_Municipio as $municipio): ?>
											<option value="<?php echo $municipio['ID_Municipio']; ?>">
												<?php echo  $municipio['Nombre']  ; ?>
											</option>
										<?php endforeach; ?>
									</select>
								</div>
							</div>
							
							<div class="contenido-boton">
								<input class="btn btn-primary" type="submit" name="register_usuarios" value="Guardar">
							</div>
							
						</form>	
					</div>
				
						
			</div>
		</div>
	
		<!-- welcome modal start -->
		
		
		<!-- welcome modal end -->
		<!-- js -->
		<script>
			// Cargar el contenido del header utilizando fetch
			fetch('../Admin-PHP/template.php')
				.then(response => response.text())
				.then(data => {
					// Insertar el contenido del header en el contenedor
					document.getElementById('template').innerHTML = data;
				});
		</script>
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
		
		<!-- End Google Tag Manager (noscript) -->
	</body>
</html>
