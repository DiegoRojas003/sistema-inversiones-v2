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
		<title>Liquidación</title>

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
		<link rel="stylesheet" type="text/css" href="../vendors/styles/style.css" />

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
		?>
		
		<?php include('templateI.php'); ?>
		<div class="mobile-menu-overlay"></div>

		<div class="main-container">
			<div class="pd-ltr-20 xs-pd-20-10">
				<div class="min-height-200px">
				<div class="bg-white pd-20 card-box mb-30">
					<div id="chart6"></div>
					<div><h5>Total: $56.000.000</h5></div>
				</div>
				
				<div class="row clearfix">
					<div class="col-lg-6 col-md-12 col-sm-12 mb-30">
						<div class="pd-20 card-box" style="height: 360px;">
							<h5 class="h4 text-blue mb-20">Valor tipo de aporte</h5>
							
								<div class="pd-20 card-box height-100-p" style="height: 255px">
									
									<div id="chart8Valor" style="max-width: 500px;"></div>
								</div>
							
						</div>
					</div>
					<div class="col-lg-6 col-md-12 col-sm-12 mb-30">
						<div class="pd-20 card-box" style="height: 360px;">
							<h5 class="h4 text-blue mb-20">Cantidad tipo de aporte</h5>
							
								<div class="pd-20 card-box height-100-p" style="height: 255px">
									
									<div id="chart8Cantidad" style="max-width: 500px;"></div>
								</div>
							
						</div>
					</div>
					<div class="col-lg-6 col-md-12 col-sm-12 mb-30">
						<div class="pd-20 card-box" style="height: 360px;">
							<h5 class="h4 text-blue mb-20">Aportes de la industria</h5>
							
								<div class="pd-20 card-box height-100-p" style="height: 255px">
									
									<div id="chart8Industria" style="max-width: 500px;"></div>
								</div>
							
						</div>
					</div>
					<div class="col-lg-6 col-md-12 col-sm-12 mb-30">
						<div class="pd-20 card-box" style="height: 360px;">
							<h5 class="h4 text-blue mb-20">Cantidad</h5>
							
								<div class="pd-20 card-box height-100-p" style="height: 255px">
									
									<div id="chart9" style="max-width: 500px;"></div>
								</div>
							
						</div>
					</div>
					<div class="col-md-4 col-sm-12 mb-30">
						<div class="card text-white bg-primary card-box">
							<div class="card-body">
								<h5 class="card-title text-white">Participación Minima</h5>
								<p class="card-text">
									5,5%
								</p>
							</div>
						</div>
					</div>
					<div class="col-md-4 col-sm-12 mb-30">
						<div class="card text-white bg-success card-box">
							
							<div class="card-body">
								<h5 class="card-title text-white">Participación Maxima</h5>
								<p class="card-text">
									10,5%
								</p>
							</div>
						</div>
					</div>
					<div class="col-md-4 col-sm-12 mb-30">
						<div class="card text-white bg-primary card-box">
							<div class="card-body">
								<h5 class="card-title text-white">Promedio Participación</h5>
								<p class="card-text">
									10,0%
								</p>
							</div>
						</div>
					</div>
					<div class="col-md-6 col-sm-12 mb-30">
						<div class="card text-white bg-primary card-box">
							<div class="card-body">
								<h5 class="card-title text-white">Valor de los aportes de capital</h5>
								<p class="card-text">
									$5.466.766.901
								</p>
							</div>
						</div>
					</div>
					<div class="col-md-6 col-sm-12 mb-30">
						<div class="card text-white bg-primary card-box">
							<div class="card-body">
								<h5 class="card-title text-white">Valor de los aportes de industria</h5>
								<p class="card-text">
									$770.434.969
								</p>
							</div>
						</div>
					</div>
				</div>
				
				<div class="card-box pb-10">
					<div class="h5 pd-20 mb-0">Resumen</div>
					<table class="data-table table nowrap">
						<thead>
							<tr>
								<th>Socios</th>
								<th class="table-plus">Valor de los aportes de capital</th>
								<th>Valor de los aportes de industria</th>
								<th>Valor de los aportes</th>
								<th>Porcentaje participación accionaria</th>
								<th class="datatable-nosort">Actions</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>Socio 1</td>
								<td>$ 263.016.989</td>
								<td>$ 77.043.497</td>
								<td>$ 340.060.486</td>
								<td>$ 5,45</td>
								
								
								<td>
									<div class="table-actions">
										<a href="#" data-color="#265ed7"
											><i class="icon-copy dw dw-edit2"></i
										></a>
										<a href="#" data-color="#e95959"
											><i class="icon-copy dw dw-delete-3"></i
										></a>
									</div>
								</td>
							</tr>
							<tr>
								<td>Socio 1</td>
								<td>$ 263.016.989</td>
								<td>$ 77.043.497</td>
								<td>$ 340.060.486</td>
								<td>$ 5,45</td>
								<td>
									<div class="table-actions">
										<a href="#" data-color="#265ed7"
											><i class="icon-copy dw dw-edit2"></i
										></a>
										<a href="#" data-color="#e95959"
											><i class="icon-copy dw dw-delete-3"></i
										></a>
									</div>
								</td>
							</tr>
							<tr>
								<td>Socio 1</td>
								<td>$ 263.016.989</td>
								<td>$ 77.043.497</td>
								<td>$ 340.060.486</td>
								<td>$ 5,45</td>
								<td>
									<div class="table-actions">
										<a href="#" data-color="#265ed7"
											><i class="icon-copy dw dw-edit2"></i
										></a>
										<a href="#" data-color="#e95959"
											><i class="icon-copy dw dw-delete-3"></i
										></a>
									</div>
								</td>
							</tr>
							<tr>
								<td>Socio 1</td>
								<td>$ 263.016.989</td>
								<td>$ 77.043.497</td>
								<td>$ 340.060.486</td>
								<td>$ 5,45</td>
								<td>
									<div class="table-actions">
										<a href="#" data-color="#265ed7"
											><i class="icon-copy dw dw-edit2"></i
										></a>
										<a href="#" data-color="#e95959"
											><i class="icon-copy dw dw-delete-3"></i
										></a>
									</div>
								</td>
							</tr>
							<tr>
								<td>Socio 1</td>
								<td>$ 263.016.989</td>
								<td>$ 77.043.497</td>
								<td>$ 340.060.486</td>
								<td>$ 5,45</td>
								<td>
									<div class="table-actions">
										<a href="#" data-color="#265ed7"
											><i class="icon-copy dw dw-edit2"></i
										></a>
										<a href="#" data-color="#e95959"
											><i class="icon-copy dw dw-delete-3"></i
										></a>
									</div>
								</td>
							</tr>
							<tr>
								<td>Socio 1</td>
								<td>$ 263.016.989</td>
								<td>$ 77.043.497</td>
								<td>$ 340.060.486</td>
								<td>$ 5,45</td>
								<td>
									<div class="table-actions">
										<a href="#" data-color="#265ed7"
											><i class="icon-copy dw dw-edit2"></i
										></a>
										<a href="#" data-color="#e95959"
											><i class="icon-copy dw dw-delete-3"></i
										></a>
									</div>
								</td>
							</tr>
							<tr>
								<td>Socio 1</td>
								<td>$ 263.016.989</td>
								<td>$ 77.043.497</td>
								<td>$ 340.060.486</td>
								<td>$ 5,45</td>
								<td>
									<div class="table-actions">
										<a href="#" data-color="#265ed7"
											><i class="icon-copy dw dw-edit2"></i
										></a>
										<a href="#" data-color="#e95959"
											><i class="icon-copy dw dw-delete-3"></i
										></a>
									</div>
								</td>
							</tr>
						</tbody>
						<tfoot>
							<tr>
								<th>TOTALES</th>
								<th class="table-plus">$ 45.265.267</th>
								<th>$ 45.265.267</th>
								<th>$ 45.265.267</th>
								<th>100%</th>
								<th></th>
							</tr>
						</tfoot>
					</table>
				</div>

				</div>
			</div>	
			
			<div
									class="modal fade"
									id="confirmation-modal"
									tabindex="-1"
									role="dialog"
									aria-hidden="true"
								>
									<div
										class="modal-dialog modal-dialog-centered"
										role="document"
									>
										<div class="modal-content">
											<div class="modal-body text-center font-18">
												<h4 class="padding-top-30 mb-30 weight-500">
													¿Estas seguro de liquidar?
													Recuerda que no hay vuelta atrás y no se podrá realizar más inversiones.
												</h4>
												<div
													class="padding-bottom-30 row"
													style="max-width: 170px; margin: 0 auto"
												>
													<div class="col-6">
														<button
															type="button"
															class="btn btn-secondary border-radius-100 btn-block confirmation-btn"
															data-dismiss="modal"
														>
															<i class="fa fa-times"></i>
														</button>
														NO
													</div>
													<div class="col-6">
														<button
															type="button"
															class="btn btn-primary border-radius-100 btn-block confirmation-btn"
															data-dismiss="modal"
														>
															<i class="fa fa-check"></i>
														</button>
														SI
													</div>
												</div>
											</div>
										</div>
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
		<script src="../src/plugins/highcharts-6.0.7/code/highcharts.js"></script>
		<script src="https://code.highcharts.com/highcharts-3d.js"></script>
		<script src="../src/plugins/highcharts-6.0.7/code/highcharts-more.js"></script>
		<script src="../vendors/scripts/highchart-setting.js"></script>

		<script src="../src/plugins/apexcharts/apexcharts.min.js"></script>
		<script src="../vendors/scripts/apexcharts-setting.js"></script>
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
