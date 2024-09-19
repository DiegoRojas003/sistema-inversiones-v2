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
		<!-- Basic Page Info -->
		<meta charset="utf-8" />
		<title>Inversiones</title>

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
		<?php

		include("conexionn.php");

		// Consulta para obtener los datos de la tabla usuarios
		$consulta_usuarios = "SELECT ID_Usuario, Nombre, Apellido, Telefono, Correo, Contraseña, Fecha, FK_ID_Municipio, FK_ID_Rol FROM usuario2";
		$resultado_usuarios = mysqli_query($conex, $consulta_usuarios);

		// Crear un array para almacenar los datos
		$datos_usuarios = array();
		while ($fila_usuarios = mysqli_fetch_assoc($resultado_usuarios)) {
			$datos_usuarios[] = $fila_usuarios;
		}

		// Consulta para obtener los datos de la tabla proyecto
		$consulta_proyecto = "SELECT ID_Proyecto, Nombre, Fecha, Descripcion, Certificado FROM proyecto WHERE liquidado <> 1";
		$resultado_proyecto = mysqli_query($conex, $consulta_proyecto);

		// Crear un array para almacenar los datos
		$datos_proyecto = array();
		while ($fila_proyecto = mysqli_fetch_assoc($resultado_proyecto)) {
			$datos_proyecto[] = $fila_proyecto;
		}

		// Consulta para obtener los datos de la tabla proyecto
		$consulta_tipo = "SELECT ID_TIPO, Nombre, Descripcion FROM tipo";
		$resultado_tipo = mysqli_query($conex, $consulta_tipo);

		// Crear un array para almacenar los datos
		$datos_tipo = array();
		while ($fila_tipo = mysqli_fetch_assoc($resultado_tipo)) {
			$datos_tipo[] = $fila_tipo;
		}

		$consultoIN = "SELECT ID_Inversion, Nombre, Monto, Monto_Ajustado, proyecto, 
		Tipo, Fecha, Descripcion, CertificadoInversion, FK_ID_Usuario, FK_ID_Tipo  FROM inversion2";
		$resultadoIN = mysqli_query($conex, $consultoIN);

		?>

		<script>
			document.addEventListener("DOMContentLoaded", function() {
				// Obtener los elementos select
				var tipoSelect = document.querySelector('select[name="tipo"]');
				var idTipoHidden = document.querySelector('input[name="id_tipo"]');
				
				// Asignar un evento de cambio al select de tipo de inversión
				tipoSelect.addEventListener('change', function() {
					// Obtener la opción seleccionada
					var selectedOption = tipoSelect.options[tipoSelect.selectedIndex];
					
					// Establecer el valor del campo oculto con la ID de la opción seleccionada
					idTipoHidden.value = selectedOption.getAttribute('data-id');
				});
			});
			document.addEventListener("DOMContentLoaded", function() {
				// Obtener los elementos select
				var tipoSelect = document.querySelector('select[name="usuario"]');
				var idTipoHidden = document.querySelector('input[name="id_usuario"]');
				
				// Asignar un evento de cambio al select de tipo de inversión
				tipoSelect.addEventListener('change', function() {
					// Obtener la opción seleccionada
					var selectedOption = tipoSelect.options[tipoSelect.selectedIndex];
					
					// Establecer el valor del campo oculto con la ID de la opción seleccionada
					idTipoHidden.value = selectedOption.getAttribute('data-cedula');
				});
			});
		</script>
		

		<?php include('template.php'); ?>
		<div class="main-container">
			<div class="xs-pd-20-10 pd-ltr-20">
				<div class="card-box pb-10">
					<div class="h5 pd-20 mb-0">Registo de Inversiones</div>
					<table class="table hover multiple-select-row data-table-export nowrap">
						<thead>
							<tr>
								<th class="table-plus">Nombre</th>
								<th>Monto</th>
								<th>proyecto</th>
								<th>Tipo</th>
								<th>Fecha</th>
								<th>Descripcion</th>
								<th>CertificadoInversion</th>
								<th class="datatable-nosort">Acciones</th>
							</tr>
						</thead>
						<tbody>
							<?php
							while ($fila = mysqli_fetch_assoc($resultadoIN)) {
								echo "<tr>";
								echo "<td>" . $fila['Nombre'] . "</td>";
								echo "<td style='text-align: right;'>$" . number_format($fila['Monto'], 0, ',', '.') . "</td>";
								echo "<td>" . $fila['proyecto'] . "</td>";
								echo "<td>" . $fila['Tipo'] . "</td>";
								echo "<td>" . $fila['Fecha'] . "</td>";
								echo "<td>" . $fila['Descripcion'] . "</td>";
								echo '<td><a href="../files/' . $fila['CertificadoInversion'] . '" target="_blank">' . $fila['CertificadoInversion'] . '</a></td>';
								echo '<td>';
								echo '<div class="table-actions">';
								echo '<a href="#" data-toggle="modal" data-target="#editModal" onclick="cargarDatosInversion(\'' . $fila['ID_Inversion'] . '\', \'' . $fila['Nombre'] . '\', \'' . $fila['Monto'] . '\', \'' . $fila['proyecto'] . '\', \'' . $fila['Tipo'] . '\'); return false;" data-color="#265ed7"><i class="icon-copy dw dw-edit2"></i></a>';
								echo '<a href="eliminar_inversiones.php?id=' . $fila['ID_Inversion'] . '" data-color="#e95959"><i class="icon-copy dw dw-delete-3"></i></a>';
								echo '</div>';
								echo '</td>';
								echo '</tr>';
							}
							?>
						</tbody>
					</table>

				</div>

				<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
					<div class="modal-dialog" role="document">
						<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title" id="editModalLabel">Editar Inversión</h5>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
							</button>
						</div>
						<form id="editForm" method="post" action="editar_inversion.php">
							<div class="modal-body">
							<input type="hidden" name="id_inversion" id="id_inversion">

							<!-- Campo editable de Nombre -->
							<div class="form-group">
								<label for="nombre">Nombre</label>
								<input type="text" class="form-control" id="usuario" name="usuario" required>
							</div>

							<!-- Campo Monto (solo lectura) -->
							<div class="form-group">
								<label for="monto">Monto</label>
								<input type="text" class="form-control" id="monto" name="monto" disabled>
							</div>

							<!-- Campo Proyecto (solo lectura) -->
							<div class="form-group">
								<label for="proyecto">Proyecto</label>
								<input type="text" class="form-control" id="proyecto" name="proyecto" disabled>
							</div>

							<!-- Campo Tipo (solo lectura) -->
							<div class="form-group">
								<label for="tipo">Tipo</label>
								<input type="text" class="form-control" id="tipo" name="tipo" disabled>
							</div>
							</div>
							<div class="modal-footer">
							<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
							<button type="submit" class="btn btn-primary">Guardar cambios</button>
							</div>
						</form>
						</div>
					</div>
				</div>

				<script>
					function cargarDatosInversion(id, usuarioId, monto, proyecto, tipo) {
						document.getElementById('id_inversion').value = id;
						document.getElementById('usuario').value = usuarioId; // Selecciona el usuario en el menú desplegable
						document.getElementById('monto').value = monto;
						document.getElementById('proyecto').value = proyecto;
						document.getElementById('tipo').value = tipo;

						$('#editModal').modal('show');
					}

					document.addEventListener("DOMContentLoaded", function() {
						var editButtons = document.querySelectorAll('.edit-btn');
						var idInversionInput = document.getElementById('id_inversion');
						var usuarioSelect = document.getElementById('usuario');
						var montoInput = document.getElementById('monto');
						var proyectoInput = document.getElementById('proyecto');
						var tipoInput = document.getElementById('tipo');

						editButtons.forEach(function(button) {
							button.addEventListener('click', function() {
								var id = this.getAttribute('data-id');
								var usuarioId = this.getAttribute('data-usuario'); // Asegúrate de tener este atributo en los botones de editar
								var monto = this.parentNode.parentNode.previousElementSibling.previousElementSibling.innerHTML;
								var proyecto = this.parentNode.parentNode.previousElementSibling.innerHTML;
								var tipo = this.parentNode.parentNode.previousElementSibling.previousElementSibling.previousElementSibling.innerHTML;

								idInversionInput.value = id;
								usuarioSelect.value = usuarioId;
								montoInput.value = monto;
								proyectoInput.value = proyecto;
								tipoInput.value = tipo;

								$('#editModal').modal('show');
							});
						});
					});
				</script>


				<div class="title pb-20 pt-20">
					<h2 class="h3 mb-0">Ingreso de inversiones</h2>
				</div>

				<div class="pd-20 card-box mb-30">
					<div class="clearfix"></div>
					<form action="registrar.php" method="post" enctype="multipart/form-data">

					<div class="form-group row">
						<label class="col-sm-12 col-md-2 col-form-label">Usuario</label>
						<div class="col-sm-12 col-md-10">
						<select name="usuario" class="custom-select col-12">
							<option selected="">Seleccione</option>
							<?php foreach ($datos_usuarios as $usuario): ?>
										<?php if ($usuario['FK_ID_Rol'] != 1): // Condición para excluir usuarios con FK_ID_Rol = 1 ?>
											<option value="<?php echo $usuario['Nombre'] . ' ' . $usuario['Apellido']; ?>" data-cedula="<?php echo $usuario['ID_Usuario']; ?>">
												Cédula: <?php echo  $usuario['ID_Usuario']; ?> - <?php echo $usuario['Nombre'] . ' ' . $usuario['Apellido']; ?>
											</option>
										<?php endif; ?>
									<?php endforeach; ?>
						</select>
						<input type="hidden" name="id_usuario" value="<?php echo isset($datos_usuarios[0]['ID_Usuario']) ? $datos_usuarios[0]['ID_Usuario'] : ''; ?>">
						</div>
					</div>


						<div class="form-group row">
							<label class="col-sm-12 col-md-2 col-form-label">Monto en dinero</label>
							<div class="col-sm-12 col-md-10">
								<input name="monto" class="form-control" type="number" placeholder="2500000">
							</div>
						</div>
						<div class="form-group row">
							<label class="col-sm-12 col-md-2 col-form-label">Empresa</label>
							<div class="col-sm-12 col-md-10">
								<select name="proyecto" class="custom-select col-12">
									<option selected="">Seleccione</option>
									<?php foreach ($datos_proyecto as $proyecto): ?>
										<option value="<?php echo $proyecto['Nombre']; ?>">
											<?php echo $proyecto['Nombre']; ?>
										</option>
									<?php endforeach; ?>
								</select>
							</div>
						</div>

						<div class="form-group row">
							<label class="col-sm-12 col-md-2 col-form-label">Tipo de Inversión</label>
							<div class="col-sm-12 col-md-10">
								<select name="tipo" class="custom-select col-12">
									<option selected="">Seleccione</option>
									<?php foreach ($datos_tipo as $tipo): ?>
										<option value="<?php echo $tipo['Nombre']; ?>"data-id="<?php echo $tipo['ID_TIPO']; ?>">
											<?php echo $tipo['Nombre']; ?>
										</option>
									<?php endforeach; ?>
									<!-- Campo oculto para enviar la ID seleccionada -->
									<input type="hidden" name="id_tipo" value="<?php echo $tipo['ID_TIPO']; ?>">
								</select>
								
							</div>
						</div>
						<div class="form-group row">
							<label class="col-sm-12 col-md-2 col-form-label">Fecha de Inversión</label>
							<div class="col-sm-12 col-md-10">
								<input name="fecha_inversion" class="form-control date-picker" placeholder="Seleccione la fecha" type="text">
							</div>
						</div>
						<div class="form-group row">
							<label class="col-sm-12 col-md-2 col-form-label">Descripción de Inversión</label>
							<div class="col-sm-12 col-md-10">
							<textarea name='descripcion_inversion' placeholder="Ingresa una breve descripción de la empresa a crear..." class="form-control" id="exampleFormControlTextarea1" rows="3"></textarea>
						</div>
						</div>
						
						<div class="form-group row">
							<label class="col-sm-12 col-md-2 col-form-label">Documento</label>
							<div class="col-sm-12 col-md-10">
							<input name="archivo" id="archivo" type="file" class="form-control-file form-control height-auto" accept=".pdf">
							</div>
						</div>

						
						<div class="contenido-boton">
							<input name="registar_inversion" class="btn btn-primary" type="submit" value="Guardar" />
						</div>
					</form>
				</div>
			</div>
		</div>
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
