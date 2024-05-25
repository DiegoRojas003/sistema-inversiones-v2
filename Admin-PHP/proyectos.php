<!DOCTYPE html>
<html>
	<head>
		<!-- Basic Page Info -->
		<meta charset="utf-8" />
		<title>Parámetros-Proyectos</title>

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

		$consultop = "SELECT ID_Proyecto , Nombre, Fecha, Descripcion, Certificado  FROM proyecto";
		$resultadop = mysqli_query($conex, $consultop);

		?>

		<div id="template"></div>
		<div class="main-container">
			<div class="xs-pd-20-10 pd-ltr-20">
				<div class="card-box pb-10">
					<div class="h5 pd-20 mb-0">Proyectos</div>
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
							while ($fila = mysqli_fetch_assoc($resultadop)) {
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
					<h2 class="h3 mb-0">Creación de Proyectos</h2>
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
								<input name="nombre_proyecto" class="form-control" type="text" placeholder="Proyecto lavado de carros">
							</div>
						</div>
						<div class="form-group row">
							<label class="col-sm-12 col-md-2 col-form-label">Fecha de creación</label>
							<div class="col-sm-12 col-md-10">
								<input name="fecha_proyecto" class="form-control date-picker" placeholder="Seleccione la fecha" type="text">
							</div>
						</div>
						<div class="form-group row">
							<label class="col-sm-12 col-md-2 col-form-label">Descripcion</label>
							<div class="col-sm-12 col-md-10">
							<textarea name='descripcion_p' placeholder="Ingresa una breve Descripcion del proyecto a crear..." class="form-control" id="exampleFormControlTextarea1" rows="3"></textarea>
						</div>
						</div>
						<div class="form-group row">
							<label class="col-sm-12 col-md-2 col-form-label">Documento</label>
							<div class="col-sm-12 col-md-10">
								<input name="documento_proyecto"
									type="file"
									class="form-control-file form-control height-auto"
									accept=".doc, .docx, .pdf" />
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
		<script src="../"></script>
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
