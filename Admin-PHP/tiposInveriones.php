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
		<div id="template"></div>
		<div class="main-container">
			<!-- Tabla de tipo -->
			<div class="pd-20 card-box mb-30">
				<div class="title pb-20 pt-20">
				<div class="h5 pd-20 mb-0">Tipos de inversiones</div>
					<table class="data-table table nowrap">
						<thead>
							<tr>
								<th>Identificador</th>
								<th class="table-plus">Nombre</th>
								<th>Descripcion</th>
								<th class="datatable-nosort">Acciones</th>
							</tr>
						</thead>
						<tbody>
							<?php
							while ($fila = mysqli_fetch_assoc($resultadot)) {
								echo "<tr>";
								echo "<td>" . $fila['ID_TIPO'] . "</td>";
								echo "<td>" . $fila['Nombre'] . "</td>";
								echo "<td>" . $fila['Descripcion'] . "</td>";
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
			
            <!-- Campos de registro -->
			<div class="xs-pd-20-10 pd-ltr-20">
	
				<div class="title pb-20 pt-20">
					<h2 class="h3 mb-0">Creación de tipos de inversiones</h2>
				</div>

				<div class="pd-20 card-box mb-30">
					<form action="registrar.php" method="post">
						<form>
							<div class="form-group row">
								<label class="col-sm-12 col-md-2 col-form-label">Identificador</label>
								<div class="col-sm-12 col-md-10">
									<input name= "id_tipo" class="form-control" type="text" placeholder="01, 02 ,03">
								</div>
							</div>
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
		
		
		
	</body>
</html>
