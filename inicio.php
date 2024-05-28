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
    
        // Redireccionar según el rol
        switch ($rol) {
            case 1:
                header("Location: Admin-PHP/inicio.php");
                exit();
            case 2:
            case 3:
                // Verificar proyectos del usuario
                $proyectos = obtener_proyectos($conn, $id_usuario);
                if (count($proyectos) == 1) {
                    $redirect_page = ($rol == 2) ? "Moderador-PHP/inicioM.php" : "Inversionista-PHP/inicioI.php";
                    header("Location: $redirect_page");
                    exit();
                } else {
                    // Si tiene más de un proyecto, dirigir a la página de selección de proyecto
                    header("Location: eleccionproyecto.php");
                    exit();
                }
            default:
                // Rol no válido
                echo "Rol no válido";
                exit();
        }
    } else {
        // No se encontraron coincidencias para el usuario y la contraseña
        echo "<script>alert('Credenciales Incorrectas');</script>";
        echo "<script>window.location.replace('inicio.php');</script>";
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
<html>

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

    <!-- Basic Page Info -->
    <meta charset="utf-8" />
    <title>DeskApp - Distribución de Participación Monetaria</title>

    <!-- Site favicon -->
    <link rel="apple-touch-icon" sizes="180x180" href="vendors/images/apple-touch-icon.png" />
    <link rel="icon" type="image/png" sizes="32x32" href="vendors/images/favicon-32x32.png" />
    <link rel="icon" type="image/png" sizes="16x16" href="vendors/images/favicon-16x16.png" />

    <!-- Mobile Specific Metas -->
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet" />

    <!-- CSS -->
    <link rel="stylesheet" type="text/css" href="vendors/styles/core.css" />
    <link rel="stylesheet" type="text/css" href="vendors/styles/icon-font.min.css" />
    <link rel="stylesheet" type="text/css" href="src/plugins/jquery-steps/jquery.steps.css" />
    <link rel="stylesheet" type="text/css" href="vendors/styles/style.css" />
    <link rel="stylesheet" href="src/styles/style-inicio.css">
    <link rel="stylesheet" href="src/styles/style.css">

    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-GBZ3SGGX85"></script>
    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-2973766580778258"
        crossorigin="anonymous"></script>
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
                    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" onsubmit="return validarFormulario();">
                        <div class="input-group custom">
                            <input type="number" class="form-control form-control-lg" placeholder="Cédula" id="cedula" name="cedula" />
                            <div class="input-group-append custom">
                                <span class="input-group-text"><i class="icon-copy dw dw-user1"></i></span>
                            </div>
                        </div>
                        <div class="input-group custom">
                            <input type="password" class="form-control form-control-lg" placeholder="Contraseña" id="contrasena" name="contrasena" />
                            <div class="input-group-append custom">
                                <span class="input-group-text"><i class="dw dw-padlock1"></i></span>
                            </div>
                        </div>
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
                                    <a class="btn btn-outline-primary btn-lg btn-block" onclick="$('#login-modal').modal('hide')">Salir</a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Header -->
    <div class="login-header box-shadow">
        <div class="container-fluid d-flex justify-content-between align-items-center">
            <div class="brand-logo">
                <a href="index.html">
                    <img src="vendors/images/deskapp-logo.svg" alt="" />
                </a>
            </div>
            <div class="login-menu">
                <ul>
                    <li><a href="#"
                        class="btn-block"
                        data-backdrop="static"
                        data-toggle="modal"
                        data-target="#login-modal"
                        type="button">Iniciar sesión</a></li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container mt-5">
        <div class="row align-items-center">
            <!-- Left Side: Text Content -->
            <div class="col-md-6">
                <div class="bg-white box-shadow border-radius-10 p-4">
                    <h1 class="titulo">Bienvenido a la Plataforma de Distribución de Capital</h1>
                    <h2 class="subtitulo">Distribuye tu capital de manera eficiente</h2>
                    <p class="cuerpo-texto">En esta plataforma podrás gestionar y visualizar cómo se distribuye la
                        participación monetaria entre los diferentes participantes de tu proyecto. Ofrecemos una forma
                        fácil y transparente de asegurar que cada miembro reciba su parte justa.</p>
                </div>
            </div>
            <!-- Right Side: Image -->
            <div class="col-md-6 text-center">
                <img src="src/images/imagen-inicio.jpg" class="img-fluid" alt="Distribución de Capital" />
            </div>
        </div>
    </div>


    <script src="vendors/scripts/core.js"></script>
    <script src="vendors/scripts/script.min.js"></script>
    <script src="vendors/scripts/process.js"></script>
    <script src="vendors/scripts/layout-settings.js"></script>
    <script src="src/plugins/jquery-steps/jquery.steps.js"></script>
    <script src="vendors/scripts/steps-setting.js"></script>
</body>

</html>