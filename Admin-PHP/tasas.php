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

		
	</head>
	<body>
		<script>
			function validarCampos() {
				const form = document.getElementById('myForm');
				const inputs = form.querySelectorAll('input');

				let camposVacios = false;
				inputs.forEach(input => {
					const name = input.getAttribute('name');
					if (name && !name.endsWith('2') && input.value.trim() === '') {
						camposVacios = true;
					}
				});

				if (camposVacios) {
					alert('Por favor completa todos los campos antes de guardar.');
				} else {
					copiarValores();
				}
			}

			// Función para guardar los valores en localStorage
			function saveToLocalStorage(name, value) {
				localStorage.setItem(name, value);
			}

			// Función para cargar los valores desde localStorage
			function loadFromLocalStorage(name) {
				return localStorage.getItem(name) || '';
			}

			// Función para convertir porcentaje a decimal
			function porcentajeADecimal(value) {
				return parseFloat(value) / 100;
			}

			// Función para realizar los cálculos y actualizar los campos
			function calcularValores() {
				const form = document.getElementById('myForm');

				const tlr2 = porcentajeADecimal(loadFromLocalStorage('tlr2'));
				const tlrc2 = porcentajeADecimal(loadFromLocalStorage('tlrc2'));
				const rsyp2 = porcentajeADecimal(loadFromLocalStorage('rsyp2'));
				const rlr2 = porcentajeADecimal(loadFromLocalStorage('rlr2'));
				const bd2 = parseFloat(loadFromLocalStorage('bd2'));
				const tdi2 = porcentajeADecimal(loadFromLocalStorage('tdi2'));
				const da2 = porcentajeADecimal(loadFromLocalStorage('da2'));
				const a2 = porcentajeADecimal(loadFromLocalStorage('a2'));
				const tldrct2 = porcentajeADecimal(loadFromLocalStorage('tldrct2'));
				const ppt2 = porcentajeADecimal(loadFromLocalStorage('ppt2'));
				const cdadi2 = porcentajeADecimal(loadFromLocalStorage('cdadi2'));
				const sda2 = porcentajeADecimal(loadFromLocalStorage('sda2'));

				// Cálculo de rp2
				const rp2 = tlrc2 - tlr2;

				// Cálculo de pdm2
				const pdm2 = rsyp2 - rlr2;

				// Cálculo de d2 (convertir a2 de porcentaje a decimal)
				const d2 = da2 * a2;

				// Cálculo de p2
				const p2 = a2 - d2;

				// Cálculo de dp2
				const dp2 = d2 / p2;

				// Cálculo de pa2
				const pa2 = p2 / a2;

				// Cálculo de ba2
				const ba2 = bd2 * (1 + (dp2 * (1 - tdi2)));

				// Cálculo de cepi2
				const cepi2 = tlr2 + ba2 * pdm2 + rp2;

				// Cálculo de tldrcy2 (igual a tlrc2)
				const tldrcy2 = tlrc2;

				// Cálculo de de2
				const de2 = (1 + tldrct2) / (1 + tldrcy2) - 1;

				// Cálculo de cepiep2
				const cepiep2 = (1 + cepi2) * (1 + de2) - 1;

				// Cálculo de cepeiep2
				const cepeiep2 = cepiep2 + ppt2;

				// Cálculo de cdddi2
				const cdddi2 = cdadi2 * (1 - tdi2);

				// Cálculo de tdd2
				const tdd2 = pa2 * cepeiep2 + da2 * cdddi2;

				// Cálculo de tddaar2
				const tddaar2 = tdd2 + sda2;

				// Actualizar los valores en el formulario
				form.querySelector('input[name="rp2"]').value = (rp2 * 100).toFixed(2) + '%';
				form.querySelector('input[name="pdm2"]').value = (pdm2 * 100).toFixed(2) + '%';
				form.querySelector('input[name="d2"]').value = d2.toFixed(2);
				form.querySelector('input[name="p2"]').value = p2.toFixed(2);
				form.querySelector('input[name="dp2"]').value = dp2.toFixed(2);
				form.querySelector('input[name="pa2"]').value = pa2.toFixed(2);
				form.querySelector('input[name="ba2"]').value = ba2.toFixed(2);
				form.querySelector('input[name="cepi2"]').value = (cepi2 * 100).toFixed(2) + '%';
				form.querySelector('input[name="tldrcy2"]').value = (tldrcy2 * 100).toFixed(2) + '%';
				form.querySelector('input[name="de2"]').value = (de2 * 100).toFixed(2) + '%';
				form.querySelector('input[name="cepiep2"]').value = (cepiep2 * 100).toFixed(2) + '%';
				form.querySelector('input[name="cepeiep2"]').value = (cepeiep2 * 100).toFixed(2) + '%';
				form.querySelector('input[name="cdddi2"]').value = (cdddi2 * 100).toFixed(2) + '%';
				form.querySelector('input[name="tdd2"]').value = (tdd2 * 100).toFixed(2) + '%';
				form.querySelector('input[name="tddaar2"]').value = (tddaar2 * 100).toFixed(2) + '%';
			}

			// Función para copiar los valores y guardarlos en localStorage
			function copiarValores() {
				const form = document.getElementById('myForm');
				const inputs = form.querySelectorAll('input');

				inputs.forEach(input => {
					const name = input.getAttribute('name');
					if (name && !name.endsWith('2')) {
						const value = input.value;
						saveToLocalStorage(name, value);
						const readOnlyInput = form.querySelector(`input[name="${name}2"]`);
						if (readOnlyInput) {
							readOnlyInput.value = value;
							saveToLocalStorage(`${name}2`, value);
						}
						// Vaciar los campos de entrada después de guardar
						input.value = '';
					}
				});

				calcularValores();
			}

			// Cargar los valores al cargar la página
			window.onload = function() {
				const form = document.getElementById('myForm');
				const inputs = form.querySelectorAll('input');

				inputs.forEach(input => {
					const name = input.getAttribute('name');
					if (name) {
						const value = loadFromLocalStorage(name);
						if (value) {
							input.value = value;
						}
					}
				});

				calcularValores();
			};

		</script>
	


		<div id="template"></div>
		<div class="main-container">
			<div class="pd-ltr-20 xs-pd-20-10">
				<div class="title pb-20 pt-20">
					<h2 class="h3 mb-0">Tasas</h2>
				</div>
				<div class="pd-20 card-box mb-30">
					<form id="myForm">
						<div class="form-group row">
							<label class="col-sm-12 col-md-2 col-form-label">Tasa Libre de Riesgo:</label>
							<div class="col-sm-12 col-md-10">
								<input name="tlr" class="form-control" type="number" placeholder="%">
								<input name="tlr2" class="form-control mt-2" type="text" readonly>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-sm-12 col-md-2 col-form-label">Tasa libre de riesgo Colombia (Yankee):</label>
							<div class="col-sm-12 col-md-10">
								<input name="tlrc" class="form-control" type="number" placeholder="%">
								<input name="tlrc2" class="form-control mt-2" type="text" readonly>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-sm-12 col-md-2 col-form-label">Riesgo país:</label>
							<div class="col-sm-12 col-md-10">
								<input name="rp2" class="form-control mt-2" type="text" readonly>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-sm-12 col-md-2 col-form-label">Retorno S&P 500 (1928-2015):</label>
							<div class="col-sm-12 col-md-10">
								<input name="rsyp" class="form-control" type="number" placeholder="%">
								<input name="rsyp2" class="form-control mt-2" type="text" readonly>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-sm-12 col-md-2 col-form-label">Retorno Libre de Riesgo (1928-2015):</label>
							<div class="col-sm-12 col-md-10">
								<input name="rlr" class="form-control" type="number" placeholder="%">
								<input name="rlr2" class="form-control mt-2" type="text" readonly>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-sm-12 col-md-2 col-form-label">Prima de Mercado:</label>
							<div class="col-sm-12 col-md-10">
								<input name="pdm2" class="form-control mt-2" type="text" readonly>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-sm-12 col-md-2 col-form-label">Beta Desapalancado:</label>
							<div class="col-sm-12 col-md-10">
								<input name="bd" class="form-control" type="number" placeholder="%">
								<input name="bd2" class="form-control mt-2" type="text" readonly>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-sm-12 col-md-2 col-form-label">Tasa de impuestos:</label>
							<div class="col-sm-12 col-md-10">
								<input name="tdi" class="form-control" type="number" placeholder="%">
								<input name="tdi2" class="form-control mt-2" type="text" readonly>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-sm-12 col-md-2 col-form-label">Deuda/Activo:</label>
							<div class="col-sm-12 col-md-10">
								<input name="da" class="form-control" type="number" placeholder="%">
								<input name="da2" class="form-control mt-2" type="text" readonly>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-sm-12 col-md-2 col-form-label">Activo:</label>
							<div class="col-sm-12 col-md-10">
								<input name="a" class="form-control" type="number" placeholder="">
								<input name="a2" class="form-control mt-2" type="text" readonly>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-sm-12 col-md-2 col-form-label">Deuda:</label>
							<div class="col-sm-12 col-md-10">
								<input name="d2" class="form-control mt-2" type="text" readonly>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-sm-12 col-md-2 col-form-label">Patrimonio:</label>
							<div class="col-sm-12 col-md-10">
								<input name="p2" class="form-control mt-2" type="text" readonly>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-sm-12 col-md-2 col-form-label">Deuda/Patrimonio:</label>
							<div class="col-sm-12 col-md-10">
								<input name="dp2" class="form-control mt-2" type="text" readonly>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-sm-12 col-md-2 col-form-label">Patrimonio/Activo:</label>
							<div class="col-sm-12 col-md-10">
								<input name="pa2" class="form-control mt-2" type="text" readonly>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-sm-12 col-md-2 col-form-label">Beta Apalancado:</label>
							<div class="col-sm-12 col-md-10">
								<input name="ba2" class="form-control mt-2" type="text" readonly>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-sm-12 col-md-2 col-form-label">Costo exigido por el inversionista en dólares:</label>
							<div class="col-sm-12 col-md-10">
								<input name="cepi2" class="form-control mt-2" type="text" readonly>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-sm-12 col-md-2 col-form-label">Tasa libre de riesgo Colombia (Yankee):</label>
							<div class="col-sm-12 col-md-10">
								<input name="tldrcy2" class="form-control mt-2" type="text" readonly>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-sm-12 col-md-2 col-form-label">Tasa libre de riesgo Colombia (TES):</label>
							<div class="col-sm-12 col-md-10">
								<input name="tldrct" class="form-control" type="number" placeholder="%">
								<input name="tldrct2" class="form-control mt-2" type="text" readonly>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-sm-12 col-md-2 col-form-label">Devaluación esperada:</label>
							<div class="col-sm-12 col-md-10">
								<input name="de2" class="form-control mt-2" type="text" readonly>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-sm-12 col-md-2 col-form-label">Costo exigido por el inversionista en pesos Ke:</label>
							<div class="col-sm-12 col-md-10">
								<input name="cepiep2" class="form-control mt-2" type="text" readonly>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-sm-12 col-md-2 col-form-label">Prima por tamaño:</label>
							<div class="col-sm-12 col-md-10">
								<input name="ppt" class="form-control" type="number" placeholder="%">
								<input name="ppt2" class="form-control mt-2" type="text" readonly>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-sm-12 col-md-2 col-form-label">Costo exigido por el inversionista en pesos Ke:</label>
							<div class="col-sm-12 col-md-10">
								<input name="cepeiep2" class="form-control mt-2" type="text" readonly>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-sm-12 col-md-2 col-form-label">Costo deuda antes de impuestos:</label>
							<div class="col-sm-12 col-md-10">
								<input name="cdadi" class="form-control" type="number" placeholder="%">
								<input name="cdadi2" class="form-control mt-2" type="text" readonly>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-sm-12 col-md-2 col-form-label">Costo deuda después de impuestos:</label>
							<div class="col-sm-12 col-md-10">
								<input name="cdddi2" class="form-control mt-2" type="text" readonly>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-sm-12 col-md-2 col-form-label">Tasa de descuento:</label>
							<div class="col-sm-12 col-md-10">
								<input name="tdd2" class="form-control mt-2" type="text" readonly>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-sm-12 col-md-2 col-form-label">Spreed de ajuste:</label>
							<div class="col-sm-12 col-md-10">
								<input name="sda" class="form-control" type="number" placeholder="%">
								<input name="sda2" class="form-control mt-2" type="text" readonly>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-sm-12 col-md-2 col-form-label">Tasa de descuento ajustada al riesgo:</label>
							<div class="col-sm-12 col-md-10">
								<input name="tddaar2" class="form-control mt-2" type="text" readonly>
							</div>
						</div>
					</form>
					<div class="contenido-boton">
						<input name="submitButton" class="btn btn-primary" type="button" value="Guardar" onclick="validarCampos()">

					</div>

				</div>

				
			</div>
		</div>
		<!-- welcome modal start -->
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
