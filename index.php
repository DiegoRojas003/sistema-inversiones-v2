<?php
// Deshabilitar el almacenamiento en caché
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

// Iniciar la sesión
session_start();

// Verificar si el usuario ya está autenticado
if (isset($_SESSION['authenticated']) && $_SESSION['authenticated'] === true) {
    // Si el usuario ya está autenticado, redirigirlo a la página de inicio adecuada según su rol
    switch ($_SESSION['rol']) {
        case 1:
            header("Location: Admin-PHP/inicio.php");
            exit();
        case 2:
            header("Location: Moderador-PHP/inicioM.php");
            exit();
        case 3:
            header("Location: Inversionista-PHP/inicioI.php");
            exit();
        default:
            // Rol no válido, redirigir a la página de inicio de sesión
            header("Location: inicio.php");
            exit();
    }
}

// Verificar si se ha enviado el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Conectar a la base de datos
    $conn = new mysqli("localhost", "root", "", "sistemaInversiones");

    // Verificar conexión
    if ($conn->connect_error) {
        die("Error de conexión: " . $conn->connect_error);
    }

    // Limpiar y obtener los datos del formulario
    $cedula = clean_input($_POST["cedula"]);
    $contrasena = clean_input($_POST["contrasena"]);

    // Consulta para obtener el rol, nombre y apellido del usuario
    $sql = "SELECT ID_Usuario, FK_ID_Rol, Nombre, Apellido FROM usuario2 WHERE ID_Usuario = '$cedula' AND Contraseña = '$contrasena'";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Si se encontró al menos un usuario con las credenciales proporcionadas
        $row = $result->fetch_assoc();
        $id_usuario = $row["ID_Usuario"];
        $rol = $row["FK_ID_Rol"];
        $nombre = $row["Nombre"];
        $apellido = $row["Apellido"];
    
        // Establecer variables de sesión
        $_SESSION['authenticated'] = true;
        $_SESSION['cedula'] = $id_usuario; // Almacenar el ID del usuario
        $_SESSION['rol'] = $rol;
        $_SESSION['nombre'] = $nombre;
        $_SESSION['apellido'] = $apellido;
    
        // Verificar proyectos del usuario
        $proyectos = obtener_proyectos($conn, $id_usuario);

        if (count($proyectos) == 0) {
          switch ($rol) {
              case 1:
                header("Location: Admin-PHP/inicio.php");
                exit();
              default:
                // Si el usuario no tiene proyectos
                echo "<script>alert('El usuario no tiene proyectos asignados. Por favor, comuníquese con el administrador.');</script>";
                echo "<script>window.location.replace('http://localhost/sistema-inversiones-v2/cerrar-sesion.php');</script>";
                exit();
            }
        } elseif (count($proyectos) == 1) {
            // Si el usuario tiene solo un proyecto, redirigir según el rol
            switch ($rol) {
                case 2:
                    echo "<script>window.location.replace('Moderador-PHP/inicioM.php');</script>"; 
                    exit();
                case 3:
                  echo "<script>window.location.replace('Inversionista-PHP/inicioI.php');</script>"; 
                    exit();
            }
        } else {
            // Si tiene más de un proyecto, dirigir a la página de selección de proyecto
            echo "<script>window.location.replace('eleccionproyecto.php');</script>";
            exit();
        }
    } else {
        // No se encontraron coincidencias para el usuario y la contraseña
        echo "<script>alert('Credenciales Incorrectas');</script>";
        echo "<script>window.location.replace('index.php');</script>";
    }

    // Cerrar conexión
    $conn->close();
}

// Función para limpiar entrada de usuario
function clean_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Función para obtener proyectos de un usuario
function obtener_proyectos($conn, $id_usuario) {
    $proyectos = array();
    $sql = "SELECT FK_ID_Proyecto FROM proyecto_usuario WHERE FK_ID_Usuario = '$id_usuario'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $proyectos[] = $row["FK_ID_Proyecto"];
        }
    }
    return $proyectos;
}
?>



<!DOCTYPE html>
<html lang="es">

<head>
  <script>
    function validarFormulario() {
      var cedula = document.getElementById("cedula").value;
      var contrasena = document.getElementById("contrasena").value;

      if (cedula.trim() == "" || contrasena.trim() == "") {
        alert("Por favor, complete todos los campos.");
        return false; // Evitar que el formulario se envíe si hay campos en blanco
      }
      return true; // Permitir el envío del formulario si todos los campos están llenos
    }
  </script>
  <script>
    // Función para recordar el estado del checkbox "Recuérdeme"
    function recordarCheckbox() {
      var recordar = localStorage.getItem("recuerdame");
      if (recordar === "true") {
        document.getElementById("customCheck1").checked = true;
      }
    }

    // Función para guardar el estado del checkbox en localStorage
    function guardarCheckbox() {
      var recordar = document.getElementById("customCheck1").checked;
      localStorage.setItem("recuerdame", recordar);
    }

    // Llamar a la función para recordar el estado del checkbox al cargar la página
    recordarCheckbox();
  </script>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>sipmainputvalue-inicio</title>
  <meta content="" name="description">
  <meta content="" name="keywords">


  <link href="assets/img/favicon.png" rel="icon">
  <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">


  <link
    href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Raleway:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i"
    rel="stylesheet">


  <link href="assets/vendor/aos/aos.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
  <link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">

  <link rel="stylesheet" type="text/css" href="vendors/styles/core.css" />
  <link rel="stylesheet" type="text/css" href="vendors/styles/icon-font.min.css" />
  <link rel="stylesheet" type="text/css" href="src/plugins/jquery-steps/jquery.steps.css" />
  <link rel="stylesheet" type="text/css" href="vendors/styles/style.css" />
  <link rel="stylesheet" href="src/styles/style-inicio.css">
  <link rel="stylesheet" href="src/styles/style.css">
  <link href="assets\css\style-index.css" rel="stylesheet">


</head>

<body>
  <!-- Contenido del modal Login -->
  <div class="modal fade" id="login-modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="login-box bg-white box-shadow border-radius-10">
          <div class="login-title">
            <h2 class="text-center text-primary">
              Iniciar Sesión
            </h2>
          </div>
          <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" onsubmit="return validarFormulario();">
            <div class="input-group custom">
              <input type="number" class="form-control form-control-lg" placeholder="Cédula" id="cedula"
                name="cedula" />
              <div class="input-group-append custom">
                <span class="input-group-text"><i class="icon-copy dw dw-user1"></i></span>
              </div>
            </div>
            <div class="input-group custom">
              <input type="password" class="form-control form-control-lg" placeholder="Contraseña" id="contrasena"
                name="contrasena" />
              <div class="input-group-append custom">
                <span class="input-group-text"><i class="dw dw-padlock1"></i></span>
              </div>
            </div>
            <!--
            <div class="row pb-30">
              <div class="col-6">
                <div class="custom-control custom-checkbox">
                  <input type="checkbox" class="custom-control-input" id="customCheck1" onchange="guardarCheckbox();" />
                  <label class="custom-control-label" for="customCheck1">Recuérdame</label>
                </div>
              </div>
              <div class="col-6">
                <div class="forgot-password">
                  <a href="forgot-password.html">¿Olvidó su contraseña?</a>
                </div>
              </div>
            </div>
            -->

            <div class="row align-items-center">
              <div class="col-5">
                <div class="input-group mb-0">
                  <button class="btn btn-primary btn-lg btn-block" type="submit">Iniciar sesión</button>
                </div>
              </div>
              <div class="col-2">
                <div class="font-16 weight-600 text-center" data-color="#707373">Ó</div>
              </div>
              <div class="col-5">
              
                <div class="input-group mb-0">
                <button
													type="button"
													class="btn btn-outline-primary btn-lg btn-block"
													data-dismiss="modal"
												>
													Salir
												</button>

                </div>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
  <!-- ======= Header ======= -->
  <header id="header" class="fixed-top d-flex align-items-center">
    <div class="container d-flex align-items-center">

      <div class="logo me-auto">
        <h1><a href="index.html">Sipmainputvalue</a></h1>
        <!-- Uncomment below if you prefer to use an image logo -->
        <!-- <a href="index.html"><img src="assets/img/logo.png" alt="" class="img-fluid"></a>-->
      </div>

      <nav id="navbar" class="navbar order-last order-lg-0">
        <ul>
          <li><a class="nav-link scrollto active" href="#hero">Inicio</a></li>
          <li class="dropdown"><a href="#"><span>Acerca de</span> <i class="bi bi-chevron-down"></i></a>
            <ul>
              <li><a class="nav-link scrollto" href="#about">Acerca de</a></li>
              <li><a class="nav-link scrollto" href="#team">Nuestro equipo</a></li>
            </ul>
          </li>
          <li><a class="nav-link scrollto" href="#services">Servicios</a></li>
          <li><a class="nav-link scrollto" href="#contact">Contacto</a></li>
          <li><a href="#" class="btn-block" data-backdrop="static" data-toggle="modal" data-target="#login-modal"
              type="button">Iniciar sesión</a></li>
        </ul>
        <i class="bi bi-list mobile-nav-toggle"></i>
      </nav><!-- .navbar -->



    </div>
  </header><!-- End Header -->

  <!-- ======= Hero Section ======= -->
  <section id="hero">

    <div class="container">
      <div class="row">
        <div class="col-lg-6 pt-5 pt-lg-0 order-2 order-lg-1 d-flex flex-column justify-content-center"
          data-aos="fade-up">
          <div>
            <h1>Bienvenido a la Plataforma de distribución de participación</h1>
            <h2>Distribuye tu capital de manera eficiente, con el software de justicia accionaria mayor actualizado del
              momento</h2>
            <a href="#about" class="btn-get-started scrollto">Enseñame más</a>
          </div>
        </div>
        <div class="col-lg-6 order-1 order-lg-2 hero-img" data-aos="fade-left">
          <img src="assets/img/hero-img.png" class="img-fluid" alt="">
        </div>
      </div>
    </div>

  </section><!-- End Hero -->

  <main id="main">

    <!-- ======= About Section ======= -->
    <section id="about" class="about">
      <div class="container">

        <div class="row">
          <div class="col-lg-6" data-aos="zoom-in">
            <img src="assets/img/about.jpg" class="img-fluid" alt="">
          </div>
          <div class="col-lg-6 d-flex flex-column justify-contents-center" data-aos="fade-left">
            <div class="content pt-4 pt-lg-0">
              <h3>Un poco más sobre nuestro proyecto</h3>
              <p class="fst-italic">
                En esta plataforma podrás gestionar y visualizar cómo se distribuye la participación monetaria entre los
                diferentes participantes de tu proyecto. Ofrecemos una forma fácil y transparente de asegurar que cada
                miembro reciba su parte justa.
              </p>
              <ul>
                <li><i class="bi bi-check-circle"></i> Encuentre la información o certificados de su empresa.</li>
                <li><i class="bi bi-check-circle"></i> Ingrese todo tipo de inversión tanto de capital como de
                  industria.</li>
                <li><i class="bi bi-check-circle"></i> Controle las inversiones y asigne un ciclo de vida al proyecto,
                  cuando se liquide las inveriones podrás denegar el ingreso de nuevas inversiones.</li>
              </ul>
              <p>
                Animate y descubre más funcionalidades y permítenos enseñarte más de nuestro software.
              </p>
            </div>
          </div>
        </div>

      </div>
    </section><!-- End About Section -->

    <!-- ======= Features Section ======= -->
    <section id="features" class="features">
      <div class="container">

        <div class="row">
          <div class="col-lg-6 mt-2 mb-tg-0 order-2 order-lg-1">
            <ul class="nav nav-tabs flex-column">
              <li class="nav-item" data-aos="fade-up">
                <a class="nav-link active show" data-bs-toggle="tab" href="#tab-1">
                  <h4>Estadísticas</h4>
                  <p>Cuente con un resumen de las inversiones y de la relevancia en el proyecto de cada una.</p>
                </a>
              </li>
              <li class="nav-item mt-2" data-aos="fade-up" data-aos-delay="100">
                <a class="nav-link" data-bs-toggle="tab" href="#tab-2">
                  <h4>Conectividad</h4>
                  <p>Este al tanto de la liquidación, por medio de notificaciones de alerta.</p>
                </a>
              </li>
              <li class="nav-item mt-2" data-aos="fade-up" data-aos-delay="200">
                <a class="nav-link" data-bs-toggle="tab" href="#tab-3">
                  <h4>Resumen contable</h4>
                  <p>Independiente del resultado encuentre los valores invertidos con su información legal.</p>
                </a>
              </li>
              <li class="nav-item mt-2" data-aos="fade-up" data-aos-delay="300">
                <a class="nav-link" data-bs-toggle="tab" href="#tab-4">
                  <h4>Seguridad</h4>
                  <p>Su información personal y monetaria, se encontrara presente y segura.</p>
                </a>
              </li>
            </ul>
          </div>
          <div class="col-lg-6 order-1 order-lg-2" data-aos="zoom-in">
            <div class="tab-content">
              <div class="tab-pane active show" id="tab-1">
                <figure>
                  <img src="assets/img/features-1.png" alt="" class="img-fluid">
                </figure>
              </div>
              <div class="tab-pane" id="tab-2">
                <figure>
                  <img src="assets/img/features-2.png" alt="" class="img-fluid">
                </figure>
              </div>
              <div class="tab-pane" id="tab-3">
                <figure>
                  <img src="assets/img/features-3.png" alt="" class="img-fluid">
                </figure>
              </div>
              <div class="tab-pane" id="tab-4">
                <figure>
                  <img src="assets/img/features-4.png" alt="" class="img-fluid">
                </figure>
              </div>
            </div>
          </div>
        </div>

      </div>
    </section><!-- End Features Section -->

    <!-- ======= Services Section ======= -->
    <section id="services" class="services section-bg">
      <div class="container">

        <div class="section-title" data-aos="fade-up">
          <h2>Servicios</h2>
          <p>Presentamos más funcionalidades del proyecto.</p>
        </div>

        <div class="row">
          <div class="col-md-6 col-lg-3 d-flex align-items-stretch mb-5 mb-lg-0" data-aos="zoom-in">
            <div class="icon-box icon-box-pink">
              <div class="icon"><i class="bx bxl-dribbble"></i></div>
              <h4 class="title"><a href="">Roles</a></h4>
              <p class="description">No se preocupe por el ingreso en apartados de administración, cada persona contara
                con su propia visualización y control de información</p>
            </div>
          </div>

          <div class="col-md-6 col-lg-3 d-flex align-items-stretch mb-5 mb-lg-0" data-aos="zoom-in"
            data-aos-delay="100">
            <div class="icon-box icon-box-cyan">
              <div class="icon"><i class="bx bx-file"></i></div>
              <h4 class="title"><a href="">Documentación</a></h4>
              <p class="description">Descargue y cargue los certificados y documentos legales de sus inversiones</p>
            </div>
          </div>

          <div class="col-md-6 col-lg-3 d-flex align-items-stretch mb-5 mb-lg-0" data-aos="zoom-in"
            data-aos-delay="200">
            <div class="icon-box icon-box-green">
              <div class="icon"><i class="bx bx-tachometer"></i></div>
              <h4 class="title"><a href="">Rendimiento</a></h4>
              <p class="description">Visualice y logre procesar la información en tiempo real y sin contratiempos</p>
            </div>
          </div>

          <div class="col-md-6 col-lg-3 d-flex align-items-stretch mb-5 mb-lg-0" data-aos="zoom-in"
            data-aos-delay="300">
            <div class="icon-box icon-box-blue">
              <div class="icon"><i class="bx bx-world"></i></div>
              <h4 class="title"><a href="">Globalización</a></h4>
              <p class="description">Realice su distribución desde cualquier parte del mundo</p>
            </div>
          </div>

        </div>

      </div>
    </section><!-- End Services Section -->







    <!-- ======= Team Section ======= -->
    <section id="team" class="team">
      <div class="container">

        <div class="section-title" data-aos="fade-up">
          <h2>Nuestro equipo</h2>
          <p>Nuestro equipo está compuesto por profesionales altamente calificados en desarrollo de software y gestión
            de inversiones,
            dedicados a ofrecer soluciones innovadoras y eficaces para nuestros clientes.</p>
        </div>

        <div class="row">

          <div class="col-lg-4 col-md-6">
            <div class="member" data-aos="zoom-in">
              <div class="pic"><img src="assets\img\team\team-1.jpg" class="img-fluids" alt=""></div>
              <div class="member-info">
                <h4>Micher Alexander Gonzalez Monroy</h4>
                <span>Docente</span>
                <div class="social">
                  <a href=""><i class="bi bi-twitter"></i></a>
                  <a href=""><i class="bi bi-facebook"></i></a>
                  <a href=""><i class="bi bi-instagram"></i></a>
                  <a href=""><i class="bi bi-linkedin"></i></a>
                </div>
              </div>
            </div>
          </div>

          <div class="col-lg-4 col-md-6">
            <div class="member" data-aos="zoom-in" data-aos-delay="100">
              <div class="pic"><img src="assets\img\team\team-1.jpg" class="img-fluids" alt=""></div>
              <div class="member-info">
                <h4>Campo Eli Castillo Eraso</h4>
                <span>Docente</span>
                <div class="social">
                  <a href=""><i class="bi bi-twitter"></i></a>
                  <a href=""><i class="bi bi-facebook"></i></a>
                  <a href=""><i class="bi bi-instagram"></i></a>
                  <a href=""><i class="bi bi-linkedin"></i></a>
                </div>
              </div>
            </div>
          </div>

          <div class="col-lg-4 col-md-6">
            <div class="member" data-aos="zoom-in" data-aos-delay="200">
              <div class="pic"><img src="assets\img\team\Diana Karina Lopez Carreño.jpg" class="img-fluids" alt="">
              </div>
              <div class="member-info">
                <h4>Diana Karina Lopez Carreño</h4>
                <span>Docente</span>
                <div class="social">
                  <a href=""><i class="bi bi-twitter"></i></a>
                  <a href=""><i class="bi bi-facebook"></i></a>
                  <a href=""><i class="bi bi-instagram"></i></a>
                  <a href=""><i class="bi bi-linkedin"></i></a>
                </div>
              </div>
            </div>
          </div>
          <div class="col-lg-4 col-md-6">
            <div class="member" data-aos="zoom-in" data-aos-delay="200">
              <div class="pic"><img src="assets\img\team\Jonatan Riveros.jpg" class="img-fluids" alt=""></div>
              <div class="member-info">
                <h4>Jonatan Mateo Riveros Mendez</h4>
                <span>Desarrollador</span>
                <div class="social">
                  <a href=""><i class="bi bi-twitter"></i></a>
                  <a href=""><i class="bi bi-facebook"></i></a>
                  <a href=""><i class="bi bi-instagram"></i></a>
                  <a href=""><i class="bi bi-linkedin"></i></a>
                </div>
              </div>
            </div>
          </div>
          <div class="col-lg-4 col-md-6">
            <div class="member" data-aos="zoom-in" data-aos-delay="200">
              <div class="pic"><img src="assets\img\team\Kevin Alexander Pena Conejo.jpg" class="img-fluids" alt="">
              </div>
              <div class="member-info">
                <h4>Kevin Alexander Pena Conejo</h4>
                <span>CTO</span>
                <div class="social">
                  <a href=""><i class="bi bi-twitter"></i></a>
                  <a href=""><i class="bi bi-facebook"></i></a>
                  <a href=""><i class="bi bi-instagram"></i></a>
                  <a href=""><i class="bi bi-linkedin"></i></a>
                </div>
              </div>
            </div>
          </div>
          <div class="col-lg-4 col-md-6">
            <div class="member" data-aos="zoom-in" data-aos-delay="200">
              <div class="pic"><img src="assets\img\team\Diego Alejandro Penagos Rojas.jpg" class="img-fluids" alt="">
              </div>
              <div class="member-info">
                <h4>Diego Alejandro Penagos Rojas</h4>
                <span>Desarrollador</span>
                <div class="social">
                  <a href=""><i class="bi bi-twitter"></i></a>
                  <a href=""><i class="bi bi-facebook"></i></a>
                  <a href=""><i class="bi bi-instagram"></i></a>
                  <a href=""><i class="bi bi-linkedin"></i></a>
                </div>
              </div>
            </div>
          </div>
          <div class="col-lg-4 col-md-6">
            <div class="member" data-aos="zoom-in" data-aos-delay="200">
              <div class="pic"><img src="assets\img\team\Paula Cantor Caballero.jpg" class="img-fluids" alt=""></div>
              <div class="member-info">
                <h4>Paula Andrea Cantor Caballero</h4>
                <span></span>
                <div class="social">
                  <a href=""><i class="bi bi-twitter"></i></a>
                  <a href=""><i class="bi bi-facebook"></i></a>
                  <a href=""><i class="bi bi-instagram"></i></a>
                  <a href=""><i class="bi bi-linkedin"></i></a>
                </div>
              </div>
            </div>
          </div>
          <div class="col-lg-4 col-md-6">
            <div class="member" data-aos="zoom-in" data-aos-delay="200">
              <div class="pic"><img src="assets\img\team\Cristhian Raul.jpg" class="img-fluids" alt=""></div>
              <div class="member-info">
                <h4>Cristhian Raul Mora Angulo</h4>
                <span></span>
                <div class="social">
                  <a href=""><i class="bi bi-twitter"></i></a>
                  <a href=""><i class="bi bi-facebook"></i></a>
                  <a href=""><i class="bi bi-instagram"></i></a>
                  <a href=""><i class="bi bi-linkedin"></i></a>
                </div>
              </div>
            </div>
          </div>
        </div>

      </div>
    </section><!-- End Team Section -->




    <!-- ======= F.A.Q Section ======= -->
    <section id="faq" class="faq">
      <div class="container">

        <div class="section-title" data-aos="fade-up">
          <h2>Preguntas frecuentes</h2>
        </div>

        <ul class="faq-list">

          <li>
            <div data-bs-toggle="collapse" class="collapsed question" href="#faq1">¿Qué es la tasa libre de riesgo? <i
                class="bi bi-chevron-down icon-show"></i><i class="bi bi-chevron-up icon-close"></i></div>
            <div id="faq1" class="collapse" data-bs-parent=".faq-list">
              <p>
                La tasa libre de riesgo es el rendimiento esperado de una inversión que se considera sin riesgo de
                pérdida, lo que generalmente se representa con los bonos del Tesoro de Estados Unidos.
                <br><em>Fuente: Brealey, R.A., Myers, S.C., & Allen, F. (2019). Principles of Corporate Finance (13th
                  ed.). McGraw-Hill Education.
                  </br></em>
              </p>
            </div>
          </li>



          <li>
            <div data-bs-toggle="collapse" href="#faq3" class="collapsed question">¿Qué es la prima de mercado? <i
                class="bi bi-chevron-down icon-show"></i><i class="bi bi-chevron-up icon-close"></i></div>
            <div id="faq3" class="collapse" data-bs-parent=".faq-list">
              <p>
                La prima de mercado es la diferencia entre el rendimiento esperado del mercado de acciones y la tasa
                libre de riesgo. Refleja el retorno adicional que los inversionistas requieren por asumir el riesgo
                adicional asociado con las acciones en comparación con los activos sin riesgo. Se usa en el modelo CAPM
                para estimar el rendimiento esperado de una acción.
                <br><em>Fuente: Sharpe, W.F. (1964). "Capital Asset Prices: A Theory of Market Equilibrium under
                  Conditions of Risk". Journal of Finance, 19(3), 425-442.</br></em>
              </p>
            </div>
          </li>

          <li>
            <div data-bs-toggle="collapse" href="#faq5" class="collapsed question">¿Qué es la beta apalancada? <i
                class="bi bi-chevron-down icon-show"></i><i class="bi bi-chevron-up icon-close"></i></div>
            <div id="faq5" class="collapse" data-bs-parent=".faq-list">
              <p>
                La beta apalancada mide el riesgo de una acción teniendo en cuenta la estructura de capital de la
                empresa, es decir, su mezcla de deuda y capital propio. Refleja cómo los cambios en el mercado afectan
                el rendimiento de la acción, considerando el efecto del apalancamiento financiero.
                Apalancar la beta es reconocer a través del riesgo que la empresa esta endeudada y, por lo tanto, tiene
                mayor riesgo.
              </p>
            </div>
          </li>

          <li>
            <div data-bs-toggle="collapse" href="#faq6" class="collapsed question">¿Qué es la tasa de impuestos?<i
                class="bi bi-chevron-down icon-show"></i><i class="bi bi-chevron-up icon-close"></i></div>
            <div id="faq6" class="collapse" data-bs-parent=".faq-list">
              <p>
                La tasa de impuestos es el porcentaje de ingresos o ganancias que una empresa o individuo debe pagar al
                gobierno (Impuesto de renta). En el contexto financiero, es importante porque los intereses de la deuda
                suelen ser deducibles de impuestos, lo que afecta el cálculo del costo de la deuda y, por ende, el WACC.
                <br><em>Fuente: Graham, J.R. (2000). "How Big Are the Tax Benefits of Debt?". Journal of Finance, 55(5),
                  1901-1941. </em></br>
              </p>
            </div>
          </li>


          <li>
            <div data-bs-toggle="collapse" href="#faq2" class="collapsed question">¿Qué es la estructura de capital?<i
                class="bi bi-chevron-down icon-show"></i><i class="bi bi-chevron-up icon-close"></i></div>
            <div id="faq2" class="collapse" data-bs-parent=".faq-list">
              <p>
                La estructura de capital es la proporción de deuda y capital propio que una empresa utiliza para
                financiar sus activos y operaciones. Esta mezcla impacta el costo de capital, el riesgo financiero y la
                rentabilidad general de la empresa.
                <br><em>Fuente: Myers, S.C. (1984). "The Capital Structure Puzzle". Journal of Finance, 39(3),
                  575-592.</em></br>
              </p>
            </div>
          </li>

          <li>
            <div data-bs-toggle="collapse" href="#faq7" class="collapsed question">¿Qué es el WACC (Costo Promedio
              Ponderado de Capital)?<i class="bi bi-chevron-down icon-show"></i><i
                class="bi bi-chevron-up icon-close"></i></div>
            <div id="faq7" class="collapse" data-bs-parent=".faq-list">
              <p>
                El WACC es el costo promedio ponderado del capital que calcula el costo de todas las fuentes de
                financiación utilizadas por una empresa, ponderadas según su proporción en la estructura de capital de
                la empresa. Incluye el costo de la deuda y el costo del capital propio, y se utiliza para valorar
                proyectos y empresas.
                <br><em>Fuente: Brealey, R.A., Myers, S.C., & Allen, F. (2019). Principles of Corporate Finance (13th
                  ed.). McGraw-Hill Education.</em></br>
              </p>
            </div>
          </li>


        </ul>

      </div>
    </section><!-- End Frequently Asked Questions Section -->

    <!-- ======= Contact Section ======= -->
    <section id="contact" class="contact section-bg">
      <div class="container">

        <div class="section-title" data-aos="fade-up">
          <h2>Contáctenos</h2>
        </div>

        <div class="row">

          <div class="col-lg-5 d-flex align-items-stretch" data-aos="fade-right">
            <div class="info">
              <div class="address">
                <i class="bi bi-geo-alt"></i>
                <h4>Ubicación:</h4>
                <p>Diagonal 18 No. 20-29 Fusagasugá barrio Manila</p>
              </div>

              <div class="email">
                <i class="bi bi-envelope"></i>
                <h4>Correo:</h4>
                <p>unicundi@ucundinamarca.edu.co</p>
              </div>

              <div class="phone">
                <i class="bi bi-phone"></i>
                <h4>Celular:</h4>
                <p>+57 3228237214</p>
              </div>

              <iframe
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d1311.9270222192467!2d-74.36964982384944!3d4.333868218831487!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x8e3f04f314d97f1b%3A0x669cea7143a50cb0!2sUNIVERSIDAD%20DE%20CUNDINAMARCA%20-%20SEDE%20FUSAGASUG%C3%81!5e0!3m2!1ses-419!2sco!4v1716957351722!5m2!1ses-419!2sco"
                frameborder="0" style="border:0; width: 100%; height: 290px;" allowfullscreen></iframe>
            </div>

          </div>

          <div class="col-lg-7 mt-5 mt-lg-0 d-flex align-items-stretch" data-aos="fade-left">
            <form action="forms/contact.php" method="post" role="form" class="php-email-form">
              <div class="row">
                <div class="form-group col-md-6">
                  <label for="name">Nombre y apellido</label>
                  <input type="text" name="name" class="form-control" id="name" required>
                </div>
                <div class="form-group col-md-6 mt-3 mt-md-0">
                  <label for="name">Su correo</label>
                  <input type="email" class="form-control" name="email" id="email" required>
                </div>
              </div>
              <div class="form-group mt-3">
                <label for="name">Asunto</label>
                <input type="text" class="form-control" name="subject" id="subject" required>
              </div>
              <div class="form-group mt-3">
                <label for="name">Mensaje</label>
                <textarea class="form-control" name="message" rows="10" required></textarea>
              </div>
              <div class="my-3">
                <div class="loading">Cargando...</div>
                <div class="error-message"></div>
                <div class="sent-message">Su mensaje ha sido enviado, gracias por comunicarse con nosotros</div>
              </div>
              <div class="text-center"><button type="submit">Enviar correo</button></div>
            </form>
          </div>

        </div>

      </div>
    </section><!-- End Contact Section -->

  </main><!-- End #main -->

  <!-- ======= Footer ======= -->
  <footer id="footer">
    <div class="footer-top">
      <div class="container">
        <div class="row">

          <div class="col-lg-3 col-md-6">
            <div class="footer-info">
              <h3>Colombia</h3>
              <p>
                Fusagasugá Cundinamarca<br>
                Diagonal 18 No. 20-29 Fusagasugá barrio Manila<br><br>
                <strong>Celular:</strong> +57 3228237214<br>
                <strong>Correo:</strong> unicundi@ucundinamarca.edu.co<br>
              </p>

            </div>
          </div>

          <div class="col-lg-2 col-md-6 footer-links">
            <h4>Navegación</h4>
            <ul>
              <li><i class="bx bx-chevron-right"></i> <a href="#">Inicio</a></li>
              <li><i class="bx bx-chevron-right"></i> <a href="#">Acerca de</a></li>
              <li><i class="bx bx-chevron-right"></i> <a href="#">Servicios</a></li>
            </ul>
          </div>

          <div class="col-lg-3 col-md-6 footer-links">
            <h4>Nuestros servicios</h4>
            <ul>
              <li><i class="bx bx-chevron-right"></i> <a href="#">Documentación inversiones</a></li>
              <li><i class="bx bx-chevron-right"></i> <a href="#">Distribución accionaria</a></li>
              <li><i class="bx bx-chevron-right"></i> <a href="#">Soporte del software</a></li>
              <li><i class="bx bx-chevron-right"></i> <a href="#">Soporte tecnico</a></li>
            </ul>
          </div>

          <div class="col-lg-4 col-md-6 footer-newsletter">
            <img src="assets/img/LogoU.png" style="width: 100px; height: auto;">

          </div>

        </div>
      </div>
    </div>

    <div class="container">
      <div class="copyright">
        &copy; Copyright <strong><span>Sipmainputvalue</span></strong>. Todos los derechos reservados.
      </div>

    </div>
  </footer><!-- End Footer -->

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i
      class="bi bi-arrow-up-short"></i></a>

  <!-- Vendor JS Files -->
  <script src="assets/vendor/aos/aos.js"></script>
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
  <script src="assets/vendor/isotope-layout/isotope.pkgd.min.js"></script>
  <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>
  <script src="assets/vendor/php-email-form/validate.js"></script>

  <script src="vendors/scripts/core.js"></script>
  <script src="vendors/scripts/script.min.js"></script>
  <script src="vendors/scripts/process.js"></script>
  <script src="vendors/scripts/layout-settings.js"></script>
  <script src="src/plugins/jquery-steps/jquery.steps.js"></script>
  <script src="vendors/scripts/steps-setting.js"></script>
  <!-- Template Main JS File -->
  <script src="assets/js/main.js"></script>

</body>

</html>