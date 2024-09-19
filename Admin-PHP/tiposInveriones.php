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
	
		$consultot = "SELECT ID_TIPO, Nombre, Descripcion  FROM tipo";
		$resultadot = mysqli_query($conex, $consultot);
		?>
		<?php include('template.php'); ?>
		<div class="main-container">
			<!-- Tabla de tipo -->
			<div class="pd-20 card-box mb-30">
				<div class="title pb-20 pt-20">
				<div class="h5 pd-20 mb-0">Tipos de inversiones</div>
					<table class="data-table table nowrap">
						<thead>
							<tr>
								<th class="table-plus">Nombre</th>
								<th>Descripcion</th>
								<th class="datatable-nosort">Acciones</th>
							</tr>
						</thead>
						<tbody>
						<?php
						while ($fila = mysqli_fetch_assoc($resultadot)) {
							echo "<tr>";
							echo "<td>" . $fila['Nombre'] . "</td>";
							echo "<td>" . $fila['Descripcion'] . "</td>";
							echo '<td>';
							echo '<div class="table-actions">';
							echo '<a href="#" data-toggle="modal" data-target="#editModal" onclick="cargarDatosTipo(\'' . $fila['ID_TIPO'] . '\', \'' . $fila['Nombre'] . '\', \'' . $fila['Descripcion'] . '\'); return false;" data-color="#265ed7"><i class="icon-copy dw dw-edit2"></i></a>';
							echo '<a href="eliminar.php?id=' . $fila['ID_TIPO'] . '" onclick="return confirm(\'¿Estás seguro de que deseas eliminar este tipo?\')" data-color="#e95959"><i class="icon-copy dw dw-delete-3"></i></a>';
							echo '</div>';
							echo '</td>';
							echo '</tr>';
						}
						?>
						</tbody>
					</table>
				</div>
			</div>
			<!-- Script para cargar los datos del tipo de inversión en el modal -->
			<script>
				// Función para cargar los datos en el modal
				function cargarDatosTipo(id, nombre, descripcion) {
					document.getElementById('id_tipo').value = id;
					document.getElementById('nombre_tipo').value = nombre;
					document.getElementById('descripcion_tipo').value = descripcion;
				}

				// Asignar el evento 'click' a los botones de edición cuando la página se haya cargado
				document.addEventListener('DOMContentLoaded', function () {
					document.querySelectorAll('.edit-btn').forEach(button => {
					button.addEventListener('click', function () {
						const id = this.getAttribute('data-id');
						const nombre = this.getAttribute('data-nombre');
						const descripcion = this.getAttribute('data-descripcion');
						
						// Llamar a la función para cargar los datos en el modal
						cargarDatosTipo(id, nombre, descripcion);
					});
					});
				});
				</script>

			<!-- Modal para editar tipo de inversión -->
				<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="editModalLabel">Editar Tipo de Inversión</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<form action="editar_tipo.php" method="POST">
						<input type="hidden" id="id_tipo" name="id_tipo">
						
						<div class="form-group">
							<label for="nombre_tipo">Nombre</label>
							<input type="text" class="form-control" id="nombre_tipo" name="nombre_tipo" placeholder="Nombre del tipo de inversión">
						</div>
						<div class="form-group">
							<label for="descripcion_tipo">Descripción</label>
							<textarea class="form-control" id="descripcion_tipo" name="descripcion_tipo" rows="3" placeholder="Descripción del tipo de inversión"></textarea>
						</div>
						
						<button type="submit" class="btn btn-primary">Guardar cambios</button>
						</form>
					</div>
					</div>
				</div>
				</div>


			
            <!-- Campos de registro -->
			<div class="xs-pd-20-10 pd-ltr-20">
	
				<div class="title pb-20 pt-20">
					<h2 class="h3 mb-0">Creación de tipos de inversiones</h2>
				</div>

				<div class="pd-20 card-box mb-30">
					<form action="registrar.php" method="post">
						<form>
							<div class="form-group row">
								<label class="col-sm-12 col-md-2 col-form-label">Nombre</label>
								<div class="col-sm-12 col-md-10">
									<input name= "nombre_tipo" class="form-control" type="text" placeholder="Vivienda">
								</div>
							</div>
							<div class="form-group row">
								<label class="col-sm-12 col-md-2 col-form-label">Descripcion</label>
								<div class="col-sm-12 col-md-10">
								<textarea name='descripcion_tipo' placeholder="Ingresa una breve Descripcion del proyecto a crear..." class="form-control" id="exampleFormControlTextarea1" rows="3"></textarea>
							</div>
							</div>
							<div class="contenido-boton">
								<input class="btn btn-primary" type="submit" name="register_tipos" value="Guardar">
							</div>
					</form>	
				</div>
					
			</div>
		</div>
		
		
		
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
