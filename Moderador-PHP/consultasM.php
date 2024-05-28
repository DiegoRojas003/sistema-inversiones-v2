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
		<title>Parametros-Usuarios</title>

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
		<?php include('templateM.php'); ?>
		<div class="main-container">
			<div class="pd-ltr-20 xs-pd-20-10">
				<div class="min-height-200px">
					<div class="page-header">
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label>Usuario:</label>
									<select
										class="custom-select2 form-control"
										multiple="multiple"
										style="width: 100%"
									>
									<!--Ingresa aqui las opciones de usuario -->	
									</select>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label>Proyecto</label>
									<select
										class="custom-select2 form-control"
										multiple="multiple"
										style="width: 100%"
									>
										<!--Ingresa aqui las opciones de Proyectos que tenga el usuario -->
									</select>
								</div>
							</div>
							
						</div>
						<div class="contenido-boton">
							<input class="btn btn-primary" type="submit" value="Consultar" />
						</div>
					</div>
					<div class="row pb-10">
						<div class="col-xl-3 col-lg-3 col-md-6 mb-20">
							<div class="card-box height-100-p widget-style3">
								<div class="d-flex flex-wrap">
									<div class="widget-data">
										<div class="weight-700 font-24 text-dark">10</div>
										<div class="font-14 text-secondary weight-500">
											Inversiones Realizadas
										</div>
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
										<div class="weight-700 font-24 text-dark">200 días</div>
										<div class="font-14 text-secondary weight-500">
											Vida del proyecto
										</div>
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
										<div class="weight-700 font-24 text-dark">400+</div>
										<div class="font-14 text-secondary weight-500">
											Inversionistas
										</div>
									</div>
									<div class="widget-icon">
										<div class="icon">
											<i
												class="icon-copy bi bi-globe"
												aria-hidden="true"
											></i>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="col-xl-3 col-lg-3 col-md-6 mb-20">
							<div class="card-box height-100-p widget-style3">
								<div class="d-flex flex-wrap">
									<div class="widget-data">
										<div class="weight-700 font-24 text-dark">3,5</div>
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
								<div
							class="card-box  pd-20 mb-20 "
							style="width: 500px; height: 250px;"
						>
							
							<table class="table" >
								<tbody>
								<tr>
									<th scope="col">Valor de los aportes de capital</th>
									<th scope="col">$ 2.500.000</th>
								</tr>
								</tbody>
								<tbody>
								<tr>
									<th scope="col">Valor de los aportes de industria</th>
									<th scope="col">$ 2.500.000</th>
								</tr>
								</tbody>
								<tbody>
									<tr>
										<th scope="col">Total Aportes:</th>
										<th scope="col">$ 5.000.000</th>
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
						<h4 class="text-blue h4">Aportes en dinero</h4>
						<table class="table table-striped">
							<thead>
							<tr>
								<th scope="col">Fecha del Aporte</th>
								<th scope="col">Valor del Aporte</th>
								<th scope="col">Valor del Aporte ajustado</th>
								<th scope="col">Tiempo transcurrido desde el día del aporte</th>
							</tr>
							</thead>
							<tbody>
								<tr>
									<th scope="row">10/03/2024</th>
									<th scope="row">2500000</th>
									<th scope="row">$ 30000000</th>
									<th scope="row">158 días</th>
								</tr>
								<tr>
									<th scope="row">10/03/2024</th>
									<th scope="row">2500000</th>
									<th scope="row">$ 30000000</th>
									<th scope="row">158 días</th>
								</tr>
								<tr>
									<th scope="row">10/03/2024</th>
									<th scope="row">2500000</th>
									<th scope="row">$ 30000000</th>
									<th scope="row">158 días</th>
								</tr>
								<tr>
									<th scope="row">10/03/2024</th>
									<th scope="row">2500000</th>
									<th scope="row">$ 30000000</th>
									<th scope="row">158 días</th>
								</tr>
								<tr>
									<th scope="row">10/03/2024</th>
									<th scope="row">2500000</th>
									<th scope="row">$ 30000000</th>
									<th scope="row">158 días</th>
								</tr><tr style="background-color: blue; color: white;">
									<th scope="row">Total</th>
									<th scope="row"></th>
									<th scope="row">$ 12.000.000</th>
									<th scope="row"></th>
								</tr>
							</tbody>
						</table>
							  
					</div>
					<div class="pd-20 card-box mb-30">
						<h4 class="text-blue h4">Aportes en especie</h4>
						<table class="table table-striped" >
							<thead >
							<tr >
								<th scope="col" style="font-size:10px ;" >Fecha del Aporte</th>
								<th scope="col" style="font-size:10px ;">Aporte de genero y especie</th>
								<th scope="col" style="font-size:10px ;">Aportes de usufructo</th>
								<th scope="col" style="font-size:10px ;">Aportes de crédito</th>
								<th scope="col" style="font-size:10px ;">Aportes en sesión de contratos</th>
								<th scope="col"style="font-size:10px ;">Aportes de establecimientos de comercio</th>
								<th scope="col"style="font-size:10px ;">Aportes de derecho sobre propiedad industrial</th>
								<th scope="col"style="font-size:10px ;">Aporte de partes de interés, cuotas o acciones</th>
								<th scope="col"style="font-size:10px ;">Total aporte en especie</th>
								<th scope="col"style="font-size:10px ;">Total de aporte en especie ajustado</th>
								<th scope="col"style="font-size:10px ;">Tiempo transcurrido desde el día del aporte</th>
							</tr>
							</thead>
							<tbody>
								<tr>
									<th scope="row"style="font-size:10px ;">10/03/2024</th>
									<th scope="row"style="font-size:10px ;">$2500000</th>
									<th scope="row"style="font-size:10px ;"></th>
									<th scope="row"style="font-size:10px ;"></th>
									<th scope="row"style="font-size:10px ;"></th>
									<th scope="row"style="font-size:10px ;"></th>
									<th scope="row"style="font-size:10px ;"></th>
									<th scope="row"style="font-size:10px ;"></th>
									<th scope="row"style="font-size:10px ;"></th>
									<th scope="row"style="font-size:10px ;">2500000</th>
									<th scope="row"style="font-size:10px ;">4 días</th>
									
								</tr>
								<tr>
									<th scope="row"style="font-size:10px ;">10/03/2024</th>
									<th scope="row"style="font-size:10px ;"></th>
									<th scope="row"style="font-size:10px ;">$ 30000000</th>
									<th scope="row"style="font-size:10px ;"></th>
									<th scope="row"style="font-size:10px ;"></th>
									<th scope="row"style="font-size:10px ;"></th>
									<th scope="row"style="font-size:10px ;"></th>
									<th scope="row"style="font-size:10px ;"></th>
									<th scope="row"style="font-size:10px ;"></th>
									<th scope="row"style="font-size:10px ;">2500000</th>
									<th scope="row"style="font-size:10px ;">4 días</th>
								</tr>
								<tr>
									<th scope="row"style="font-size:10px ;">10/03/2024</th>
									<th scope="row"style="font-size:10px ;"></th>
									<th scope="row"style="font-size:10px ;"></th>
									<th scope="row"style="font-size:10px ;">2500000</th>
									<th scope="row"style="font-size:10px ;"></th>
									<th scope="row"style="font-size:10px ;"></th>
									<th scope="row"style="font-size:10px ;"></th>
									<th scope="row"style="font-size:10px ;"></th>
									<th scope="row"style="font-size:10px ;"></th>
									<th scope="row"style="font-size:10px ;">2500000</th>
									<th scope="row"style="font-size:10px ;">4 días</th>
								</tr>
								<tr>
									<th scope="row"style="font-size:10px ;">10/03/2024</th>
									<th scope="row"style="font-size:10px ;"></th>
									<th scope="row"style="font-size:10px ;"></th>
									<th scope="row"style="font-size:10px ;"></th>
									<th scope="row"style="font-size:10px ;">2500000</th>
									<th scope="row"style="font-size:10px ;"></th>
									<th scope="row"style="font-size:10px ;"></th>
									<th scope="row"style="font-size:10px ;"></th>
									<th scope="row"style="font-size:10px ;"></th>
									<th scope="row"style="font-size:10px ;">2500000</th>
									<th scope="row"style="font-size:10px ;">4 días</th>
								</tr>
								<tr>
									<th scope="row"style="font-size:10px ;">10/03/2024</th>
									<th scope="row"style="font-size:10px ;"></th>
									<th scope="row"style="font-size:10px ;"></th>
									<th scope="row"style="font-size:10px ;"></th>
									<th scope="row"style="font-size:10px ;"></th>
									<th scope="row"style="font-size:10px ;"></th>
									<th scope="row"style="font-size:10px ;"></th>
									<th scope="row"style="font-size:10px ;"></th>
									<th scope="row"style="font-size:10px ;">2500000</th>
									<th scope="row"style="font-size:10px ;">2500000</th>
									<th scope="row"style="font-size:10px ;">4 días</th>
								</tr>
								<tr style="background-color: blue; color: white;">
									<th scope="row"style="font-size:15px ;">Total:</th>
									<th scope="row"style="font-size:10px ;"></th>
									<th scope="row"style="font-size:10px ;"></th>
									<th scope="row"style="font-size:10px ;"></th>
									<th scope="row"style="font-size:10px ;"></th>
									<th scope="row"style="font-size:10px ;"></th>
									<th scope="row"style="font-size:10px ;"></th>
									<th scope="row"style="font-size:10px ;"></th>
									<th scope="row"style="font-size:10px ;"></th>
									<th scope="row"style="font-size:15px ;">$2500000</th>
									<th scope="row"style="font-size:10px;"></th>
								</tr>
							</tbody>
						</table>
							  
					</div>
				</div>
				
			</div>
		</div>
		
		
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
