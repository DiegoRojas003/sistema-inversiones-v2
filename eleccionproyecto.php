<?php
// Iniciar la sesión
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['authenticated']) || $_SESSION['authenticated'] !== true) {
    // Si el usuario no está autenticado, redirigirlo a la página de inicio de sesión
    header("Location: http://localhost/sistema-inversiones-v2/inicio.php");
    exit();
}

include("conexionn.php");

// Obtener el ID del usuario desde la sesión
$id_usuario = $_SESSION['cedula'];

// Consulta para obtener los proyectos vinculados al usuario
$consulta_proyecto = "SELECT p.ID_Proyecto, p.Nombre, p.Fecha, p.Descripcion, p.Certificado 
                      FROM proyecto p
                      JOIN proyecto_usuario pu ON p.ID_Proyecto = pu.FK_ID_Proyecto
                      WHERE pu.FK_ID_Usuario = '$id_usuario'";
$resultado_proyecto = mysqli_query($conex, $consulta_proyecto);

// Crear un array para almacenar los datos
$datos_proyecto = array();
while ($fila_proyecto = mysqli_fetch_assoc($resultado_proyecto)) {
    $datos_proyecto[] = $fila_proyecto;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtén la ID del proyecto seleccionado
    $proyectoSeleccionado = $_POST["proyecto"];
    
    // Guarda la ID del proyecto en una variable de sesión
    $_SESSION["proyecto_seleccionado"] = $proyectoSeleccionado;
    
    // Redirige al usuario según su rol
    switch ($_SESSION['rol']) {
        case 2:
            header("Location: Moderador-PHP/inicioM.php");
            exit();
        case 3:
            header("Location: Inversionista-PHP/inicioI.php");
            exit();
        default:
            header("Location: index.html");
            exit();
    }
}
?>

<script>
    function seleccionarProyecto() {
        var proyectoSeleccionado = document.getElementById("proyecto").value;
        if (proyectoSeleccionado == "") {
            alert("Por favor, seleccione un proyecto.");
            return;
        }
        
        // Envía el formulario para guardar el proyecto seleccionado
        document.getElementById("formulario_seleccion_proyecto").submit();
    }
</script>



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
			href="vendors/images/apple-touch-icon.png"
		/>
		<link
			rel="icon"
			type="image/png"
			sizes="32x32"
			href="vendors/images/favicon-32x32.png"
		/>
		<link
			rel="icon"
			type="image/png"
			sizes="16x16"
			href="vendors/images/favicon-16x16.png"
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
		<link rel="stylesheet" type="text/css" href="vendors/styles/core.css" />
		<link
			rel="stylesheet"
			type="text/css"
			href="vendors/styles/icon-font.min.css"
		/>
		<link
			rel="stylesheet"
			type="text/css"
			href="src/plugins/jquery-steps/jquery.steps.css"
		/>
		<link rel="stylesheet" type="text/css" href="vendors/styles/style.css" />
        <link rel="stylesheet" href="src/styles/style.css">
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

	<body class="login-page">
		<div class="login-header box-shadow">
			<div
				class="container-fluid d-flex justify-content-between align-items-center"
			>
				<div class="brand-logo">
					<a href="login.html">
						<h3>Selección de proyecto</h3>
					</a>
				</div>
				
			</div>
		</div>
		<div
			class="register-page-wrap d-flex align-items-center flex-wrap justify-content-center"
		>
			<div class="container">
				<div class="row align-items-center">
					<div class="col-md ">
						<div class="login-box bg-white box-shadow border-radius-10">
                            <div class="login-title">
								<h2 class="text-center text-primary">Proyectos</h2>
							</div>
                            <h6 class="mb-20">
								Hemos encontrado más proyectos vinculados a tu cuenta
							</h6>
                            <form id="formulario_seleccion_proyecto" method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
								<div class="form-group row">
									<label class="col-sm-12 col-md-2 col-form-label">Proyectos</label>
									<div class="col-sm-12 col-md-10">
										<select name="proyecto" id="proyecto" class="custom-select col-12">
											<option selected=""></option>
											<?php foreach ($datos_proyecto as $proyecto): ?>
												<option value="<?php echo $proyecto['ID_Proyecto']; ?>">
													<?php echo $proyecto['Nombre']; ?>
												</option>
											<?php endforeach; ?>
										</select>
									</div>
								</div>
							</form>
                            <h6 class="mb-20">
								Seleccione un proyecto de elección
							</h6>
                            <div class="row align-items-center">
                                <div class="col-5">
                                    <div class="input-group mb-0">
                                        <!--
                                        use code for form submit
                                        <input class="btn btn-primary btn-lg btn-block" type="submit" value="Submit">
                                    -->
										<a class="btn btn-primary btn-lg btn-block" href="#" onclick="seleccionarProyecto()">Ingresar</a>

                                    </div>
                                </div>
                                <div class="col-2">
                                    <div
                                        class="font-16 weight-600 text-center"
                                        data-color="#707373"
                                    >
                                        Ó
                                    </div>
                                </div>
                                <div class="col-5">
                                    <div class="input-group mb-0">
                                        <a
                                            class="btn btn-outline-primary btn-lg btn-block"
                                            href="http://localhost/sistema-inversiones-v2/cerrar-sesion.php"
                                            >Volver</a
                                        >
                                    </div>
                                </div>
                            </div>
                        </div>
					</div>
					
				</div>
			</div>
		</div>
		<!-- success Popup html Start -->
		<button
			type="button"
			id="success-modal-btn"
			hidden
			data-toggle="modal"
			data-target="#success-modal"
			data-backdrop="static"
		>
			Launch modal
		</button>
		<div
			class="modal fade"
			id="success-modal"
			tabindex="-1"
			role="dialog"
			aria-labelledby="exampleModalCenterTitle"
			aria-hidden="true"
		>
			<div
				class="modal-dialog modal-dialog-centered max-width-400"
				role="document"
			>
				<div class="modal-content">
					<div class="modal-body text-center font-18">
						<h3 class="mb-20">Form Submitted!</h3>
						<div class="mb-30 text-center">
							<img src="vendors/images/success.png" />
						</div>
						Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do
						eiusmod
					</div>
					<div class="modal-footer justify-content-center">
						<a href="login.html" class="btn btn-primary">Done</a>
					</div>
				</div>
			</div>
		</div>
		<script src="vendors/scripts/core.js"></script>
		<script src="vendors/scripts/script.min.js"></script>
		<script src="vendors/scripts/process.js"></script>
		<script src="vendors/scripts/layout-settings.js"></script>
		<script src="src/plugins/jquery-steps/jquery.steps.js"></script>
		<script src="vendors/scripts/steps-setting.js"></script>
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