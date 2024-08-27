<?php
// Iniciar la sesión
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['authenticated']) || $_SESSION['authenticated'] !== true) {
    // Si el usuario no está autenticado, redirigirlo a la página de inicio de sesión
    header("Location: http://localhost/sistema-inversiones-v2/index.php"); // Cambia 'inicio-de-sesion.php' por la ruta de tu página de inicio de sesión
    exit();
}

// Procesar el formulario si se ha enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Conectar a la base de datos
    $servername = "localhost"; // Cambia esto según tu configuración
    $username = "root"; // Cambia esto según tu configuración
    $password = ""; // Cambia esto según tu configuración
    $dbname = "sistemainversiones"; // Cambia esto según tu configuración

    // Crear conexión
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Verificar conexión
    if ($conn->connect_error) {
        die("Conexión fallida: " . $conn->connect_error);
    }

    // Obtener el valor de la variable tddaar2
    $tddaar2 = $_POST['tddaar2'];
    $fecha = date('Y-m-d H:i:s'); // Fecha actual

    // Preparar y ejecutar la consulta SQL
    $stmt = $conn->prepare("INSERT INTO tasa (Tasa, Fecha) VALUES (?, ?)");
    $stmt->bind_param("ss", $tddaar2, $fecha);

    if ($stmt->execute()) {
        echo "Datos guardados correctamente.";
    } else {
        echo "Error al guardar los datos: " . $stmt->error;
    }

    // Cerrar la conexión
    $stmt->close();
    $conn->close();
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

		
	</head>
	<body>
	<script>
		document.addEventListener('DOMContentLoaded', () => {
			cargarDatos();
		});

		function validarCampos() {
			const campos = [
				'tlr', 'tlrc', 'rsyp', 'bd', 'tdi', 'a', 'd', 'p', 'de','ppt', 'cdadi', 'sda'
			];

			let hayCamposVacios = false;
			let alertaMostrada = false;

			campos.forEach(campo => {
				const valor = document.getElementById(campo).value;
				if (valor === "") {
					hayCamposVacios = true;
				}
			});

			if (hayCamposVacios && !alertaMostrada) {
				alertaMostrada = true;
				alert(`Hay campos en blanco. Por favor, completa todos los campos antes de guardar.`);
			}

			if (!hayCamposVacios) {
				calcularVariables();
				guardarDatos();
				vaciarCampos(campos);
			}
		}

		function porcentajeADecimal(value) {
			return parseFloat(value) / 100;
		}

		function calcularVariables() {
			const tlr2 = porcentajeADecimal(document.getElementById('tlr').value) || 0;
			const tlrc2 = porcentajeADecimal(document.getElementById('tlrc').value) || 0;
			const rsyp2 = porcentajeADecimal(document.getElementById('rsyp').value) || 0;
			const bd2 = parseFloat(document.getElementById('bd').value) || 0;
			const tdi2 = porcentajeADecimal(document.getElementById('tdi').value) || 0;
			const a2 = parseFloat(document.getElementById('a').value) || 0;
			const d2 = parseFloat(document.getElementById('d').value) || 0;
			const p2 = parseFloat(document.getElementById('p').value) || 0;
			const de2 = parseFloat(document.getElementById('de').value) || 0;
			const ppt2 = porcentajeADecimal(document.getElementById('ppt').value) || 0;
			const cdadi2 = porcentajeADecimal(document.getElementById('cdadi').value) || 0;
			const sda2 = porcentajeADecimal(document.getElementById('sda').value) || 0;

			const rp2 = tlrc2 - tlr2;
			const rlr2 = tlrc2;
			const pdm2 = rsyp2 - tlrc2;
			const da2 = d2 / a2;
			const pa2 = p2 / a2;
			const dp2 = d2 / p2;
			const ba2 = bd2 * (1 + (dp2 * (1 - tdi2)));
			const cepi2 = tlr2 + ba2 * pdm2 + rp2;
			const cepiep2 = (1 + cepi2) * (1 + porcentajeADecimal(de2)) - 1;
			const cepeiep2 = cepiep2 + ppt2;
			const cdddi2 = cdadi2 * (1 - tdi2);
			const tdd2 = pa2 * cepeiep2 + da2 * cdddi2;
			const tddaar2 = tdd2 + sda2;

			document.getElementById('tlr2').value = (tlr2 * 100).toFixed(2) + '%';
			document.getElementById('tlrc2').value = (tlrc2 * 100).toFixed(2) + '%';
			document.getElementById('rp2').value = (rp2 * 100).toFixed(2) + '%';
			document.getElementById('rsyp2').value = (rsyp2 * 100).toFixed(2) + '%';
			document.getElementById('rlr2').value = (rlr2 * 100).toFixed(2) + '%';
			document.getElementById('pdm2').value = (pdm2 * 100).toFixed(2) + '%';
			document.getElementById('bd2').value = bd2.toFixed(2);
			document.getElementById('tdi2').value = (tdi2 * 100).toFixed(2) + '%';
			document.getElementById('da2').value = (da2 * 100).toFixed(2) + '%';
			document.getElementById('pa2').value = (pa2 * 100).toFixed(2) + '%';
			document.getElementById('a2').value = a2.toFixed(0);
			document.getElementById('d2').value = d2.toFixed(0);
			document.getElementById('p2').value = p2.toFixed(0);
			document.getElementById('dp2').value = dp2.toFixed(2);
			document.getElementById('ba2').value = ba2.toFixed(2);
			document.getElementById('cepi2').value = (cepi2 * 100).toFixed(2) + '%';
			document.getElementById('cepiep2').value = (cepiep2 * 100).toFixed(2) + '%';
			document.getElementById('cepeiep2').value = (cepeiep2 * 100).toFixed(2) + '%';
			document.getElementById('cdadi2').value = (cdadi2 * 100).toFixed(2) + '%';
			document.getElementById('cdddi2').value = (cdddi2 * 100).toFixed(2) + '%';
			document.getElementById('tdd2').value = (tdd2 * 100).toFixed(2) + '%';
			document.getElementById('sda2').value = (sda2 * 100).toFixed(2) + '%';
			document.getElementById('tddaar2').value = (tddaar2 * 100).toFixed(2) + '%';
			document.getElementById('de2').value = (de2).toFixed(2) + '%';
			document.getElementById('ppt2').value = (ppt2 * 100).toFixed(2) + '%';
		}

		function guardarDatos() {
            const variablesConPorcentaje = [
                'tlr2', 'tlrc2', 'rp2', 'rsyp2', 'rlr2', 'pdm2', 'tdi2', 'da2', 'pa2', 'cepi2',
                'cepiep2', 'cepeiep2', 'cdadi2', 'cdddi2', 'tdd2', 'sda2', 'tddaar2', 'de2', 'ppt2'
            ];

            const variables = [
                'tlr2', 'tlrc2', 'rp2', 'rsyp2', 'rlr2', 'pdm2', 'bd2', 'tdi2', 'da2', 'a2', 'd2',
                'p2', 'dp2', 'pa2', 'ba2', 'cepi2', 'cepiep2', 'cepeiep2', 'cdadi2', 'cdddi2',
                'tdd2', 'sda2', 'tddaar2', 'de2', 'ppt2'
            ];

            variables.forEach(variable => {
                let valor = document.getElementById(variable).value;
                if (variablesConPorcentaje.includes(variable)) {
                    if (!valor.endsWith("%")) {
                        valor += "%";
                    }
                } else {
                    if (valor.endsWith("%")) {
                        valor = valor.slice(0, -1);
                    }
                }
                localStorage.setItem(variable, valor);
            });

            // Enviar el valor de tddaar2 al mismo archivo PHP
            const tddaar2 = document.getElementById('tddaar2').value;
            
            const formData = new FormData();
            formData.append('tddaar2', tddaar2);

            fetch('<?php echo $_SERVER['PHP_SELF']; ?>', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                console.log(data); // Mostrar el mensaje del servidor en la consola
                alert('Datos guardados exitosamente');
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Ocurrió un error al guardar los datos.');
            });
        }


		function cargarDatos() {
			const variables = [
				'tlr2', 'tlrc2', 'rp2', 'rsyp2', 'rlr2', 'pdm2', 'bd2', 'tdi2', 'da2', 'a2', 'd2',
				'p2', 'dp2', 'pa2', 'ba2', 'cepi2', 'cepiep2', 'cepeiep2', 'cdadi2', 'cdddi2',
				'tdd2', 'sda2', 'tddaar2','de2', 'ppt2'
			];

			variables.forEach(variable => {
				const valorGuardado = localStorage.getItem(variable);
				if (valorGuardado !== null) {
					const hasPercentage = valorGuardado.endsWith("%");
					if (!hasPercentage && variableNeedsPercentage(variable)) {
						document.getElementById(variable).value = `${valorGuardado}%`;
					} else {
						document.getElementById(variable).value = valorGuardado;
					}
				}
			});
		}

		function variableNeedsPercentage(variable) {
			const variablesConPorcentaje = [
				'tlr2', 'tlrc2', 'rp2', 'rsyp2', 'rlr2', 'pdm2', 'tdi2', 'da2', 'pa2', 'cepi2',
				'cepiep2', 'cepeiep2', 'cdadi2', 'cdddi2', 'tdd2', 'sda2', 'tddaar2','de2', 'ppt2'
			];
			return variablesConPorcentaje.includes(variable);
		}

		function vaciarCampos(campos) {
			campos.forEach(campo => {
				document.getElementById(campo).value = "";
			});
		}
	</script>


<?php include('template.php'); ?>
		<div class="main-container">
			<div class="pd-ltr-20 xs-pd-20-10">
				<div class="title pb-20 pt-20">
					<h2 class="h3 mb-0">Tasas</h2>
				</div>
				<div class="pd-20 card-box mb-30">
					<form id="myForm">
						<div class="form-group row">
							<label class="col-sm-12 col-md-2 col-form-label">Tasa Libre de Riesgo EEUU:</label>
							<div class="col-sm-12 col-md-10">
								<input name="tlr" id="tlr" class="form-control" type="number" placeholder="%">
								<input name="tlr2" id="tlr2" class="form-control mt-2" type="text" readonly placeholder="%">
							</div>
						</div>
						<div class="form-group row">
							<label class="col-sm-12 col-md-2 col-form-label">Tasa libre de riesgo Colombia :</label>
							<div class="col-sm-12 col-md-10">
								<input name="tlrc" id="tlrc" class="form-control" type="number" placeholder="%">
								<input name="tlrc2" id="tlrc2" class="form-control mt-2" type="text" readonly placeholder="%">
							</div>
						</div>
						<div class="form-group row">
							<label class="col-sm-12 col-md-2 col-form-label">Riesgo país: </label>
							<div class="col-sm-12 col-md-10">
								<input name="rp2" id="rp2" class="form-control mt-2" type="text" readonly placeholder="%">
							</div>
						</div>
						<div class="form-group row">
							<label class="col-sm-12 col-md-2 col-form-label">Retorno S&P 500: </label>
							<div class="col-sm-12 col-md-10">
								<input name="rsyp" id="rsyp" class="form-control" type="number" placeholder="%">
								<input name="rsyp2" id="rsyp2" class="form-control mt-2" type="text" readonly placeholder="%">
							</div>
						</div>
						<div class="form-group row">
							<label class="col-sm-12 col-md-2 col-form-label">Retorno Libre de Riesgo: </label>
							<div class="col-sm-12 col-md-10">
								<input name="rlr2" id="rlr2" class="form-control mt-2" type="text" readonly placeholder="%">
							</div>
						</div>
						<div class="form-group row">
							<label class="col-sm-12 col-md-2 col-form-label">Prima de Mercado:</label>
							<div class="col-sm-12 col-md-10">
								<input name="pdm2" id="pdm2" class="form-control mt-2" type="text" readonly placeholder="%">
							</div>
						</div>
						<div class="form-group row">
							<label class="col-sm-12 col-md-2 col-form-label">Beta Desapalancado: </label>
							<div class="col-sm-12 col-md-10">
								<input name="bd" id="bd" class="form-control" type="number" placeholder="">
								<input name="bd2" id="bd2" class="form-control mt-2" type="text" readonly>
							</div>
						</div>


						<div class="form-group row">
							<label class="col-sm-12 col-md-2 col-form-label">Tasa de impuestos:</label>
							<div class="col-sm-12 col-md-10">
								<input name="tdi" id="tdi" class="form-control" type="number" placeholder="%">
								<input name="tdi2" id="tdi2" class="form-control mt-2" type="text" readonly placeholder="%">
							</div>
						</div>
						<div class="form-group row">
							<label class="col-sm-12 col-md-2 col-form-label">Deuda/Activo:</label>
							<div class="col-sm-12 col-md-10">
								<input name="da2" id="da2" class="form-control mt-2" type="text" readonly placeholder="%">
							</div>
						</div>
						<div class="form-group row">
							<label class="col-sm-12 col-md-2 col-form-label">Patrimonio/Activo:</label>
							<div class="col-sm-12 col-md-10">
								<input name="pa2" id="pa2" class="form-control mt-2" type="text" readonly placeholder="%">
							</div>
						</div>
						<div class="form-group row">
							<label class="col-sm-12 col-md-2 col-form-label">Activo:</label>
							<div class="col-sm-12 col-md-10">
								<input name="a" id="a" class="form-control" type="number" placeholder="">
								<input name="a2" id="a2" class="form-control mt-2" type="text" readonly>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-sm-12 col-md-2 col-form-label">Deuda:</label>
							<div class="col-sm-12 col-md-10">
								<input name="d" id="d" class="form-control" type="number" placeholder="">
								<input name="d2" id="d2" class="form-control mt-2" type="text" readonly>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-sm-12 col-md-2 col-form-label">Patrimonio:</label>
							<div class="col-sm-12 col-md-10">
							    <input name="p" id="p" class="form-control" type="number" placeholder="">
								<input name="p2" id="p2" class="form-control mt-2" type="text" readonly>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-sm-12 col-md-2 col-form-label">Deuda/Patrimonio:</label>
							<div class="col-sm-12 col-md-10">
								<input name="dp2" id="dp2" class="form-control mt-2" type="text" readonly>
							</div>
						</div>
						

						<div class="form-group row">
							<label class="col-sm-12 col-md-2 col-form-label">Beta Apalancado:</label>
							<div class="col-sm-12 col-md-10">
								<input name="ba2" id="ba2" class="form-control mt-2" type="text" readonly>
							</div>
						</div>


						<div class="form-group row">
							<label class="col-sm-12 col-md-2 col-form-label">Costo exigido por el inversionista en dólares:</label>
							<div class="col-sm-12 col-md-10">
								<input name="cepi2" id="cepi2" class="form-control mt-2" type="text" readonly placeholder="%">
							</div>
						</div>
						

						<div class="form-group row">
							<label class="col-sm-12 col-md-2 col-form-label">Devaluación esperada:</label>
							<div class="col-sm-12 col-md-10">
								<input name="de" id="de" class="form-control" type="number" placeholder="%">
								<input name="de2" id="de2" class="form-control mt-2" type="text" readonly placeholder="%">
							</div>
						</div>

				
						<div class="form-group row">
							<label class="col-sm-12 col-md-2 col-form-label">Costo exigido por el inversionista en pesos Ke:</label>
							<div class="col-sm-12 col-md-10">
								<input name="cepiep2" id="cepiep2" class="form-control mt-2" type="text" readonly placeholder="%">
							</div>
						</div>
						<div class="form-group row">
							<label class="col-sm-12 col-md-2 col-form-label">Prima por tamaño:</label>
							<div class="col-sm-12 col-md-10">
								<input name="ppt" id="ppt" class="form-control" type="number" placeholder="%">
								<input name="ppt2" id="ppt2" class="form-control mt-2" type="text" readonly placeholder="%">
							</div>
						</div>
						<div class="form-group row">
							<label class="col-sm-12 col-md-2 col-form-label">Costo exigido por el inversionista en pesos Ke:</label>
							<div class="col-sm-12 col-md-10">
								<input name="cepeiep2" id="cepeiep2" class="form-control mt-2" type="text" readonly placeholder="%">
							</div>
						</div>


						<div class="form-group row">
							<label class="col-sm-12 col-md-2 col-form-label">Costo deuda antes de impuestos:</label>
							<div class="col-sm-12 col-md-10">
								<input name="cdadi" id="cdadi" class="form-control" type="number" placeholder="%">
								<input name="cdadi2" id="cdadi2" class="form-control mt-2" type="text" readonly placeholder="%">
							</div>
						</div>
						<div class="form-group row">
							<label class="col-sm-12 col-md-2 col-form-label">Costo deuda después de impuestos:</label>
							<div class="col-sm-12 col-md-10">
								<input name="cdddi2" id="cdddi2" class="form-control mt-2" type="text" readonly placeholder="%">
							</div>
						</div>


						<div class="form-group row">
							<label class="col-sm-12 col-md-2 col-form-label">Tasa de descuento:</label>
							<div class="col-sm-12 col-md-10">
								<input name="tdd2" id="tdd2" class="form-control mt-2" type="text" readonly placeholder="%">
							</div>
						</div>


						<div class="form-group row">
							<label class="col-sm-12 col-md-2 col-form-label">Spreed de ajuste:</label>
							<div class="col-sm-12 col-md-10">
								<input name="sda" id="sda" class="form-control" type="number" placeholder="%">
								<input name="sda2" id="sda2" class="form-control mt-2" type="text" readonly placeholder="%">
							</div>
						</div>


						<div class="form-group row">
							<label class="col-sm-12 col-md-2 col-form-label">Tasa de descuento ajustada al riesgo:</label>
							<div class="col-sm-12 col-md-10">
								<input name="tddaar2" id="tddaar2" class="form-control mt-2" type="text" readonly placeholder="%">
							</div>
						</div>
					</form>
					<div class="contenido-boton">
						<input name="submitButton" class="btn btn-primary" type="button" value="Guardar" onclick="validarCampos()">
					</div>
				</div>
				<script src="script.js"></script>
				</div>
			</div>
		</div>
		<!-- welcome modal start -->
		
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
