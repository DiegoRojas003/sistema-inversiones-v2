<?php
// Iniciar la sesión
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['authenticated']) || $_SESSION['authenticated'] !== true) {
    header("Location: http://localhost/sistema-inversiones-v2/index.php");
    exit();
}


// Obtener el nombre y apellido del usuario de la sesión
	$nombre = isset($_SESSION['nombre']) ? $_SESSION['nombre'] : '';
	$apellido = isset($_SESSION['apellido']) ? $_SESSION['apellido'] : '';
	// Conexión a la base de datos (cambia los valores según tu configuración)
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "sistemainversiones";

// Crear la conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Consulta SQL para contar el número de proyectos
$sql = "SELECT COUNT(*) AS total_proyectos FROM proyecto";
$result = $conn->query($sql);

// Inicializar la variable con un valor predeterminado
$total_proyectos = 0;

// Verificar si hay resultados
if ($result->num_rows > 0) {
    // Obtener el valor de la consulta
    $row = $result->fetch_assoc();
    $total_proyectos = $row['total_proyectos'];
}

// Consulta SQL para obtener la fecha más nueva y más antigua
$sql = "SELECT MIN(Fecha) AS fecha_mas_antigua, MAX(Fecha) AS fecha_mas_nueva FROM proyecto";
$result = $conn->query($sql);

// Inicializar las variables con valores predeterminados
$fecha_mas_antigua = '';
$fecha_mas_nueva = '';

// Verificar si hay resultados
if ($result->num_rows > 0) {
    // Obtener los valores de la consulta
    $row = $result->fetch_assoc();
    $fecha_mas_antigua = $row['fecha_mas_antigua'];
    $fecha_mas_nueva = $row['fecha_mas_nueva'];
}

// Consulta SQL para contar el número total de usuarios
$sql = "SELECT COUNT(*) AS total_usuarios FROM usuario2";
$result = $conn->query($sql);

// Inicializar la variable con un valor predeterminado
$total_usuarios = 0;

// Verificar si hay resultados
if ($result->num_rows > 0) {
    // Obtener el valor de la consulta
    $row = $result->fetch_assoc();
    $total_usuarios = $row['total_usuarios'];
}


// Cerrar la conexión
$conn->close();
?>

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

	</head>
	<body>

		

		<?php include('template.php'); ?>
		<div class="mobile-menu-overlay"></div>

		<div class="main-container">
			<div class="pd-ltr-20">
				<div class="card-box pd-20 height-100-p mb-30">
					<div class="row align-items-center">
						<div class="col-md-4">
							<img src="../vendors/images/banner-img.png" alt="" />
						</div>
						<div class="col-md-4">
							<h4 class="font-20 weight-500 mb-10 text-capitalize">
								Bienvenido de nuevo
								<div class="weight-600 font-30 text-blue"><?php echo $nombre . ' ' . $apellido; ?></div>
							</h4>
							<p class="font-18 max-width-600">
								¡Bienvenido! Estamos encantados de tenerte aquí. 
								Queremos que sepas que estamos completamente disponibles
								 y comprometidos a atender tus necesidades financieras. 
								 En nuestro rol, tenemos la capacidad y disposición para 
								 gestionar y supervisar todos los aportes monetarios, 
								 así como cualquier contribución especial que desees 
								 hacer. Además, estamos aquí para facilitar cualquier 
								 tipo de registro que necesites realizar, ya sea para 
								 seguir de cerca tus inversiones, realizar seguimientos 
								 detallados de los fondos o simplemente mantener un registro
								organizado de tus transacciones financieras.
							</p>
						</div>
						<div class="col-md-4" style="text-align:center;padding:1em 0;">
							 <h3><a style="text-decoration:none;" >
								<span style="color:gray;">Hora actual en</span>
								<br />Colombia</a></h3> 
								<iframe src="https://www.zeitverschiebung.net/clock-widget-iframe-v2?language=es&size=medium&timezone=America%2FBogota" 
									width="100%" height="115" frameborder="0" seamless></iframe> </div>
						
						</div>
					</div>
				</div>
				<div class="row pb-10">
                    <div class="col-xl-3 col-lg-3 col-md-6 mb-20">
                        <div class="card-box height-100-p widget-style3">
                            <div class="d-flex flex-wrap">
                                <div class="widget-data">
                                    <div class="weight-700 font-24 text-dark"><?php echo $total_proyectos; ?></div>
                                    <div class="font-14 text-secondary weight-500">Empresas</div>
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
                                    <div class="weight-700 font-24 text-dark"><?php echo $fecha_mas_nueva; ?></div>
                                    <div class="font-14 text-secondary weight-500">Fecha de inicio primera empresa</div>
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
                                    <div class="weight-700 font-24 text-dark"><?php echo $fecha_mas_antigua; ?></div>
                                    <div class="font-14 text-secondary weight-500">Fecha de inicio ultima empresa</div>
                                </div>
                                <div class="widget-icon">
                                    <div class="icon">
                                        <i class="icon-copy bi bi-globe" aria-hidden="true"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-3 col-md-6 mb-20">
                        <div class="card-box height-100-p widget-style3">
                            <div class="d-flex flex-wrap">
                                <div class="widget-data">
                                    <div class="weight-700 font-24 text-dark"><?php echo $total_usuarios; ?></div>
                                    <div class="font-14 text-secondary weight-500">Usuarios</div>
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
				
			
			</div>
		</div>
		
		
		<script src="../vendors/scripts/core.js"></script>
		<script src="../vendors/scripts/script.min.js"></script>
		<script src="../vendors/scripts/process.js"></script>
		<script src="../vendors/scripts/layout-settings.js"></script>
		
		<script src="../src/plugins/datatables/js/jquery.dataTables.min.js"></script>
		<script src="../src/plugins/datatables/js/dataTables.bootstrap4.min.js"></script>
		<script src="../src/plugins/apexcharts/apexcharts.min.js"></script>
		<script src="../src/plugins/datatables/js/dataTables.responsive.min.js"></script>
		<script src="../src/plugins/datatables/js/responsive.bootstrap4.min.js"></script>
		<script src="../vendors/scripts/dashboard.js"></script>
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
