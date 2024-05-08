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
		<div id="template"></div>
		<div class="main-container">
			<div class="pd-ltr-20 xs-pd-20-10">
				<div class="title pb-20 pt-20">
					<h2 class="h3 mb-0">Tasas</h2>
				</div>
				<div class="pd-20 card-box mb-30">
					<form>
						<div class="form-group row">
							<label class="col-sm-12 col-md-2 col-form-label">Tasa Libre de Riesgo:</label>
							<div class="col-sm-12 col-md-10">
								<input class="form-control" type="number" placeholder="%">
							</div>
						</div>
						<div class="form-group row">
							<label class="col-sm-12 col-md-2 col-form-label">Tasa libre de riesgo Colombia (Yankee):</label>
							<div class="col-sm-12 col-md-10">
								<input class="form-control" type="number" placeholder="%">
							</div>
						</div>
						<div class="form-group row">
							<label class="col-sm-12 col-md-2 col-form-label">Riesgo país:</label>
							<div class="col-sm-12 col-md-10">
								<input class="form-control" type="number" placeholder="%">
							</div>
						</div>
						<div class="form-group row">
							<label class="col-sm-12 col-md-2 col-form-label">Retorno S&P 500 (1928-2015):</label>
							<div class="col-sm-12 col-md-10">
								<input class="form-control" type="number" placeholder="%">
							</div>
						</div>
						<div class="form-group row">
							<label class="col-sm-12 col-md-2 col-form-label">Prima de Mercado:</label>
							<div class="col-sm-12 col-md-10">
								<input class="form-control" type="number" placeholder="%">
							</div>
						</div>
						<div class="form-group row">
							<label class="col-sm-12 col-md-2 col-form-label">Beta Desapalancado:</label>
							<div class="col-sm-12 col-md-10">
								<input class="form-control" type="number" placeholder="%">
							</div>
						</div>
						<div class="form-group row">
							<label class="col-sm-12 col-md-2 col-form-label">Tasa de impuestos:</label>
							<div class="col-sm-12 col-md-10">
								<input class="form-control" type="number" placeholder="%">
							</div>
						</div>
						<div class="form-group row">
							<label class="col-sm-12 col-md-2 col-form-label">Deuda/Activo:</label>
							<div class="col-sm-12 col-md-10">
								<input class="form-control" type="number" placeholder="%">
							</div>
						</div>
						<div class="form-group row">
							<label class="col-sm-12 col-md-2 col-form-label">Activo:</label>
							<div class="col-sm-12 col-md-10">
								<input class="form-control" type="number" placeholder="%">
							</div>
						</div>
						<div class="form-group row">
							<label class="col-sm-12 col-md-2 col-form-label">Deuda:</label>
							<div class="col-sm-12 col-md-10">
								<input class="form-control" type="number" placeholder="%">
							</div>
						</div>
						<div class="form-group row">
							<label class="col-sm-12 col-md-2 col-form-label">Patrimonio:</label>
							<div class="col-sm-12 col-md-10">
								<input class="form-control" type="number" placeholder="%">
							</div>
						</div>
						<div class="form-group row">
							<label class="col-sm-12 col-md-2 col-form-label">Deuda/Patrimonio:</label>
							<div class="col-sm-12 col-md-10">
								<input class="form-control" type="number" placeholder="%">
							</div>
						</div>
						<div class="form-group row">
							<label class="col-sm-12 col-md-2 col-form-label">Patrimonio/Activo:</label>
							<div class="col-sm-12 col-md-10">
								<input class="form-control" type="number" placeholder="%">
							</div>
						</div>
						<div class="form-group row">
							<label class="col-sm-12 col-md-2 col-form-label">Beta Apalancado:</label>
							<div class="col-sm-12 col-md-10">
								<input class="form-control" type="number" placeholder="%">
							</div>
						</div>
						<div class="form-group row">
							<label class="col-sm-12 col-md-2 col-form-label">Costo exigido por el inversionista en dólares:</label>
							<div class="col-sm-12 col-md-10">
								<input class="form-control" type="number" placeholder="%">
							</div>
						</div>
						<div class="form-group row">
							<label class="col-sm-12 col-md-2 col-form-label">Tasa libre de riesgo Colombia (Yankee):</label>
							<div class="col-sm-12 col-md-10">
								<input class="form-control" type="number" placeholder="%">
							</div>
						</div>
						<div class="form-group row">
							<label class="col-sm-12 col-md-2 col-form-label">Tasa libre de riesgo Colombia (TES):</label>
							<div class="col-sm-12 col-md-10">
								<input class="form-control" type="number" placeholder="%">
							</div>
						</div>
						<div class="form-group row">
							<label class="col-sm-12 col-md-2 col-form-label">Devaluación esperada:</label>
							<div class="col-sm-12 col-md-10">
								<input class="form-control" type="number" placeholder="%">
							</div>
						</div>
						<div class="form-group row">
							<label class="col-sm-12 col-md-2 col-form-label">Costo exigido por el inversionista en pesos:</label>
							<div class="col-sm-12 col-md-10">
								<input class="form-control" type="number" placeholder="%">
							</div>
						</div>
						<div class="form-group row">
							<label class="col-sm-12 col-md-2 col-form-label">Prima por tamaño:</label>
							<div class="col-sm-12 col-md-10">
								<input class="form-control" type="number" placeholder="%">
							</div>
						</div>
						<div class="form-group row">
							<label class="col-sm-12 col-md-2 col-form-label">Costo exigido por el inversionista en pesos:</label>
							<div class="col-sm-12 col-md-10">
								<input class="form-control" type="number" placeholder="%">
							</div>
						</div>
						<div class="form-group row">
							<label class="col-sm-12 col-md-2 col-form-label">Costo deuda antes de impuestos:</label>
							<div class="col-sm-12 col-md-10">
								<input class="form-control" type="number" placeholder="%">
							</div>
						</div>
						<div class="form-group row">
							<label class="col-sm-12 col-md-2 col-form-label">Costo deuda después de impuestos:</label>
							<div class="col-sm-12 col-md-10">
								<input class="form-control" type="number" placeholder="%">
							</div>
						</div>
						<div class="form-group row">
							<label class="col-sm-12 col-md-2 col-form-label">Tasa de descuento:</label>
							<div class="col-sm-12 col-md-10">
								<input class="form-control" type="number" placeholder="%">
							</div>
						</div>
						<div class="form-group row">
							<label class="col-sm-12 col-md-2 col-form-label">Spreed de ajuste:</label>
							<div class="col-sm-12 col-md-10">
								<input class="form-control" type="number" placeholder="%">
							</div>
						</div>
					</form>
					<div class="contenido-boton">
						<input class="btn btn-primary" type="submit" value="Guardar" />
					</div>
				</div>
				
			</div>
		</div>
		<!-- welcome modal start -->
		<script>
			// Cargar el contenido del header utilizando fetch
			fetch('template.html')
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
