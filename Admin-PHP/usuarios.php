<?php
// Iniciar la sesión
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['authenticated']) || $_SESSION['authenticated'] !== true) {
    // Si el usuario no está autenticado, redirigirlo a la página de inicio de sesión
    header("Location: http://localhost/sistema-inversiones-v2/index.php"); // Cambia 'inicio-de-sesion.php' por la ruta de tu página de inicio de sesión
    exit();
}
?>

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


	$consultou = "SELECT ID_Usuario , Nombre, Apellido, Telefono, Correo, Contraseña, Fecha, FK_ID_Municipio, FK_ID_Rol  FROM usuario2";
		$resultadou = mysqli_query($conex, $consultou);

	$consultop = "SELECT ID_Proyecto , Nombre, Fecha, Descripcion, Certificado  FROM proyecto";
		$resultadop = mysqli_query($conex, $consultop);

	$consultoi = "SELECT ID_Municipio, Nombre, FK_ID_Departamento  FROM municipio";
		$resultadoi = mysqli_query($conex, $consultoi);	

	$consultor = "SELECT ID_Rol, Nombre, Descripcion  FROM rol";
		$resultador = mysqli_query($conex, $consultor);

	?>
		<?php include('template.php'); ?>
		
		<div class="main-container">
			<div class="xs-pd-20-10 pd-ltr-20">
				<div class="title pb-20">
					<h2 class="h3 mb-0">Inversionistas y Moderadores</h2>
				</div>
				<div class="pd-20 card-box mb-30">
					<div class="h5 pd-20 mb-0">Usuarios</div>
					<table class="table hover multiple-select-row data-table-export nowrap">
						<thead>
							<tr>
								<th>Cédula</th>
								<th class="table-plus">Nombre</th>
								<th>Apellido</th>
								<th>Teléfono</th>
								<th>Correo</th>
								<th>Contraseña</th>
								<th>Fecha</th>
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
								echo '<a href="#" data-toggle="modal" data-target="#editUsuarioModal" onclick="cargarDatosUsuario(\'' . $fila['ID_Usuario'] . '\', \'' . $fila['Nombre'] . '\', \'' . $fila['Apellido'] . '\', \'' . $fila['Telefono'] . '\', \'' . $fila['Correo'] . '\', \'' . $fila['Contraseña'] . '\', \'' . $fila['Fecha'] . '\', \'' . $fila['FK_ID_Municipio'] . '\', \'' . $fila['FK_ID_Rol'] . '\'); return false;" data-color="#265ed7"><i class="icon-copy dw dw-edit2"></i></a>';
								echo '<a href="eliminar_usuario.php?id_usuario=' . $fila['ID_Usuario'] . '" data-color="#e95959"><i class="icon-copy dw dw-delete-3"></i></a>';
								echo '</div>';
								echo '</td>';
								echo '</tr>';
							}
							?>
						</tbody>
					</table>


				</div>
				<script>
					function cargarDatosUsuario(id, nombre, apellido, telefono, correo, contrasena, fecha, municipioId, rolId) {
						document.getElementById('id_usuario').value = id;
						document.getElementById('nombre_usuario').value = nombre;
						document.getElementById('apellido_usuario').value = apellido;
						document.getElementById('telefono_usuario').value = telefono;
						document.getElementById('correo_usuario').value = correo;
						document.getElementById('contrasena_usuario').value = contrasena;
						document.getElementById('fecha_usuario').value = fecha;
						document.getElementById('municipio_usuario').value = municipioId;
						document.getElementById('rol_usuario').value = rolId;
					}
				</script>


				

				<!-- Modal para editar Usuario -->
				<div class="modal fade" id="editUsuarioModal" tabindex="-1" role="dialog" aria-labelledby="editUsuarioModalLabel" aria-hidden="true">
					<div class="modal-dialog" role="document">
						<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title" id="editUsuarioModalLabel">Editar Usuario</h5>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>
							<div class="modal-body">
								<form id="formEditarUsuario" method="POST" action="editar_usuario.php">
									<input type="hidden" id="id_usuario" name="id_usuario">
									<div class="form-group">
										<label for="nombre_usuario">Nombre</label>
										<input type="text" class="form-control" id="nombre_usuario" name="nombre_usuario" required>
									</div>
									<div class="form-group">
										<label for="apellido_usuario">Apellido</label>
										<input type="text" class="form-control" id="apellido_usuario" name="apellido_usuario" required>
									</div>
									<div class="form-group">
										<label for="telefono_usuario">Teléfono</label>
										<input type="text" class="form-control" id="telefono_usuario" name="telefono_usuario" required>
									</div>
									<div class="form-group">
										<label for="correo_usuario">Correo</label>
										<input type="email" class="form-control" id="correo_usuario" name="correo_usuario" required>
									</div>
									<div class="form-group">
										<label for="contraseña_usuario">Contraseña</label>
										<input type="password" class="form-control" id="contraseña_usuario" name="contraseña_usuario" required>
									</div>
									<div class="form-group">
										<label for="municipio_usuario">Municipio</label>
										<select class="form-control" id="municipio_usuario" name="municipio_usuario" required>
											<?php
											foreach ($datos_Municipio as $municipio) {
												echo "<option value='{$municipio['ID_Municipio']}'>{$municipio['Nombre']}</option>";
											}
											?>
										</select>
									</div>
									
									<button type="submit" class="btn btn-primary">Guardar cambios</button>
								</form>
							</div>
						</div>
					</div>
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
											<?php if ($rol['ID_Rol']): // Excluir rol con ID 1 ?>
												<option value="<?php echo $rol['ID_Rol']; ?>">
													<?php echo $rol['Nombre']; ?>
												</option>
											<?php endif; ?>
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
