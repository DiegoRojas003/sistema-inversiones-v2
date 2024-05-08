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

		<!-- Global site tag (gtag.js) - Google Analytics -->
		<script
			async
			src="https://www.googletagmanager.com/gtag/js?id=G-GBZ3SGGX85"
		></script>
		<script
			async
			src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-2973766580778258"
			crossorigin="anonymous"
		></script>
		<script>
			window.dataLayer = window.dataLayer || [];
			function gtag() {
				dataLayer.push(arguments);
			}
			gtag("js", new Date());

			gtag("config", "G-GBZ3SGGX85");
		</script>
		<!-- Google Tag Manager -->
		<script>
			(function (w, d, s, l, i) {
				w[l] = w[l] || [];
				w[l].push({ "gtm.start": new Date().getTime(), event: "gtm.js" });
				var f = d.getElementsByTagName(s)[0],
					j = d.createElement(s),
					dl = l != "dataLayer" ? "&l=" + l : "";
				j.async = true;
				j.src = "https://www.googletagmanager.com/gtm.js?id=" + i + dl;
				f.parentNode.insertBefore(j, f);
			})(window, document, "script", "dataLayer", "GTM-NXZMQSS");
		</script>
		<!-- End Google Tag Manager -->
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

		<div id="template"></div>
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
							echo "<td>" . $fila['ID_Pais'] . "</td>";
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
								<th>Identificador</th>
								<th class="table-plus">Nombre</th>
								<th class="table-plus">Identificador del Pais</th>
								<th class="datatable-nosort">Acciones</th>
							</tr>
						</thead>
						<tbody>
							<?php
							while ($fila = mysqli_fetch_assoc($resultadoe)) {
								echo "<tr>";
								echo "<td>" . $fila['ID_Departamento'] . "</td>";
								echo "<td>" . $fila['Nombre'] . "</td>";
								echo "<td>" . $fila['FK_ID_Pais'] . "</td>";
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
							<th>Identificador</th>
							<th class="table-plus">Nombre</th>
							<th class="table-plus">Identificador del Departamento</th>
							<th class="datatable-nosort">Acciones</th>
						</tr>
					</thead>
					<tbody>
						<?php
						while ($fila = mysqli_fetch_assoc($resultadoi)) {
							echo "<tr>";
							echo "<td>" . $fila['ID_Municipio'] . "</td>";
							echo "<td>" . $fila['Nombre'] . "</td>";
							echo "<td>" . $fila['FK_ID_Departamento'] . "</td>";
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
									<input name="id_pais" class="form-control" type="text" placeholder="01,02,03,04,05">
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
								<label class="col-sm-12 col-md-2 col-form-label">Identificador</label>
								<div class="col-sm-12 col-md-10">
									<input name="id_departamento" class="form-control" type="text" placeholder="01,02,03,04,05">
								</div>
							</div>
							<div class="form-group row">
								<label class="col-sm-12 col-md-2 col-form-label">departamento</label>
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
									<label class="col-sm-12 col-md-2 col-form-label">Identificador</label>
									<div class="col-sm-12 col-md-10">
										<input name="id_municipio" class="form-control" type="text" placeholder="01,02,03,04,05">
									</div>
								</div>
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
		<script>
			// Cargar el contenido del header utilizando fetch
			fetch('../Admin-HTML/template.html')
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
