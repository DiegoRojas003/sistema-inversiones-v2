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
	<title>Parámetros-Empresas</title>

	<!-- Site favicon -->


	<!-- Mobile Specific Metas -->
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />

	<!-- Google Font -->
	<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
		rel="stylesheet" />
	<!-- CSS -->
	<link rel="stylesheet" type="text/css" href="../vendors/styles/core.css" />
	<link rel="stylesheet" type="text/css" href="../vendors/styles/icon-font.min.css" />
	<link rel="stylesheet" type="text/css" href="../src/plugins/datatables/css/dataTables.bootstrap4.min.css" />
	<link rel="stylesheet" type="text/css" href="../src/plugins/datatables/css/responsive.bootstrap4.min.css" />
	<link rel="stylesheet" type="text/css" href="../vendors/styles/style.css" />
	<link rel="stylesheet" href="styles/style.css">


</head>

<body>
	<?php include('template.php'); ?>
	<?php include('../modal.html'); ?>

	<?php
		include("conexionn.php");

		$consultop = "SELECT ID_Proyecto, Nombre, Fecha, Descripcion, Certificado FROM proyecto";
		$resultadop = mysqli_query($conex, $consultop);

		// Crear un array para almacenar los datos
		$datos_proyecto = array();
		while ($fila_proyecto = mysqli_fetch_assoc($resultadop)) {
			$datos_proyecto[] = $fila_proyecto;
		}

		// Consulta para obtener los datos de la tabla usuarios
		$consulta_usuarios = "SELECT ID_Usuario, Nombre, Apellido, Telefono, Correo, Contraseña, Fecha, FK_ID_Municipio, FK_ID_Rol FROM usuario2";
		$resultado_usuarios = mysqli_query($conex, $consulta_usuarios);

		// Crear un array para almacenar los datos
		$datos_usuarios = array();
		while ($fila_usuarios = mysqli_fetch_assoc($resultado_usuarios)) {
			$datos_usuarios[] = $fila_usuarios;
		}

		$consultopr = "SELECT ID_Proyecto , Nombre, Fecha, Descripcion, Certificado  FROM proyecto";
		$resultadopr = mysqli_query($conex, $consultopr);
		
	?>




	

	<div class="main-container">
		<div class="xs-pd-20-10 pd-ltr-20">
			<div id="contenedor-proyectos" class="card-box pb-10">
				<div class="h5 pd-20 mb-0">Empresas</div>
				<table class="data-table table nowrap">
					<thead>
						<tr>
							<th>Identificador</th>
							<th class="table-plus">Nombre</th>
							<th>Fecha</th>
							<th>Descripción</th>
							<th>Certificado</th>
							<th class="datatable-nosort">Acciones</th>
						</tr>
					</thead>
					<tbody>
						<?php
							while ($fila = mysqli_fetch_assoc($resultadopr)) {
								echo "<tr>";
								echo "<td>" . $fila['ID_Proyecto'] . "</td>";
								echo "<td>" . $fila['Nombre'] . "</td>";
								echo "<td>" . $fila['Fecha'] . "</td>";
								echo "<td>" . $fila['Descripcion'] . "</td>";
								echo "<td>";
								echo '<a href="descargar_p.php?id=' . $fila['ID_Proyecto'] . '">' . $fila['Certificado'] . '</a>';
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
			<div class="title pb-20 pt-20">
				<h2 class="h3 mb-0">Asignación de Usuarios</h2>
			</div>
			
			<div class="pd-20 card-box mb-30">
				<div class="clearfix">
					<div class="pull-left">
						<p class="mb-30">Seleccione la empresa y los usuarios que desea vincular a dicho empresa:</p>
					</div>
				</div>
				<form action="registrar.php" method="POST">
					<!-- Input hidden para el ID del proyecto -->
					
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label>Empresa:</label>
								<select class="custom-select2 form-control" name="proyecto" style="width: 100%; height: 38px">
									<option selected=""></option>
									<?php foreach ($datos_proyecto as $proyecto):?>
										<option value="<?php echo $proyecto['ID_Proyecto']; ?>">
											<?php echo $proyecto['Nombre']; ?>
										</option>
									<?php endforeach; ?>
								</select>
							</div>
						</div>

						<div class="col-md-6">
							<div class="form-group">
								<label>Usuarios:</label>
								<select  class="custom-select2 form-control" multiple="multiple" name="usuarios[]" style="width: 100%">
									<?php foreach ($datos_usuarios as $usuario): ?>
										<?php if ($usuario['FK_ID_Rol'] != 1): // Condición para excluir usuarios con FK_ID_Rol = 1 ?>
											<option name="id_usuario"  value="<?php  echo $usuario['ID_Usuario']; ?>">
												Cédula: <?php echo  $usuario['ID_Usuario']; ?> - <?php echo $usuario['Nombre'] . ' ' . $usuario['Apellido']; ?>
											</option>
										<?php endif; ?>
									<?php endforeach; ?>
								</select>
							</div>
						</div>
					</div>
					
					<!-- Botón para guardar -->
					<div class="contenido-boton">
						<input data-toggle="modal" data-target="#Medium-modal" class="btn btn-primary" type="submit" name="register_proyecto_usuario" value="Guardar">
					</div>
				</form>
			</div>

			<div class="title pb-20 pt-20">
				
				<h2 data-toggle="modal" data-target="#Medium-modal" class="h3 mb-0">Creación de Empresas</h2>
			</div>

			<div class="pd-20 card-box mb-30">
				<div class="clearfix"></div>
				<form action="registrar.php" method="post">
					<div class="form-group row">
						<label class="col-sm-12 col-md-2 col-form-label">Identificador</label>
						<div class="col-sm-12 col-md-10">
							<input name="id_proyecto" class="form-control" type="number" placeholder="01">
						</div>
					</div>
					<div class="form-group row">
						<label class="col-sm-12 col-md-2 col-form-label">Nombre</label>
						<div class="col-sm-12 col-md-10">
							<input name="nombre_proyecto" class="form-control" type="text"
								placeholder="Proyecto lavado de carros">
						</div>
					</div>
					<div class="form-group row">
						<label class="col-sm-12 col-md-2 col-form-label">Fecha de creación</label>
						<div class="col-sm-12 col-md-10">
							<input name="fecha_proyecto" class="form-control date-picker"
								placeholder="Seleccione la fecha" type="text">
						</div>
					</div>
					<div class="form-group row">
						<label class="col-sm-12 col-md-2 col-form-label">Descripcion</label>
						<div class="col-sm-12 col-md-10">
							<textarea name='descripcion_p'
								placeholder="Ingresa una breve Descripcion de la empresa a crear..." class="form-control"
								id="exampleFormControlTextarea1" rows="3"></textarea>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-sm-12 col-md-2 col-form-label">Documento</label>
						<div class="col-sm-12 col-md-10">
							<input name="documento_proyecto" type="file"
								class="form-control-file form-control height-auto" accept=".doc, .docx, .pdf" />
						</div>
					</div>

					<div class="contenido-boton">
						<input class="btn btn-primary" type="submit" name="register_proyecto" value="Guardar">
					</div>
				</form>
			</div>
		</div>
	</div>
	<!-- welcome modal start -->

	<script>
            $('#login-modal').modal('show');
        </script>
	<!-- welcome modal end -->
	<!-- js -->

	<script src="../vendors/scripts/core.js"></script>
	<script src="../vendors/scripts/script.min.js"></script>
	<script src="../vendors/scripts/process.js"></script>
	<script src="../vendors/scripts/layout-settings.js"></script>

	<script src="../src/plugins/datatables/js/jquery.dataTables.min.js"></script>
	<script src="../src/plugins/datatables/js/dataTables.bootstrap4.min.js"></script>
	<script src="../src/plugins/datatables/js/dataTables.responsive.min.js"></script>
	<script src="../src/plugins/datatables/js/responsive.bootstrap4.min.js"></script>
	<script src="../vendors/scripts/dashboard3.js"></script>

	<script src="vendors/scripts/core.js"></script>
		<script src="vendors/scripts/script.min.js"></script>
		<script src="vendors/scripts/process.js"></script>
		<script src="vendors/scripts/layout-settings.js"></script>

	<!-- Google Tag Manager (noscript) -->
	<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-NXZMQSS" height="0" width="0"
			style="display: none; visibility: hidden"></iframe></noscript>
	<!-- End Google Tag Manager (noscript) -->
</body>

</html>