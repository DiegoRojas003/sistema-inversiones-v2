<?php
// Iniciar la sesión
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['authenticated']) || $_SESSION['authenticated'] !== true) {
    // Si el usuario no está autenticado, redirigirlo a la página de inicio de sesión
    header("Location: http://localhost/sistema-inversiones-v2/index.php"); // Cambia 'inicio-de-sesion.php' por la ruta de tu página de inicio de sesión
    exit();
}

include("conexionn.php");


if (isset($_POST['register_usuarios'])) {
    // Verificar si las contraseñas son iguales
    if ($_POST['contrasena'] !== $_POST['contrasena2']) {
        echo "<script>alert('Las contraseñas no son iguales. Por favor, inténtalo de nuevo.');</script>";
        // Actualizar la página después de mostrar el mensaje de error
        echo "<script>window.location.replace('usuariosM.php');</script>";
    } else {
        // Continuar con el procesamiento del formulario
        if (
            isset($_POST['cedula']) &&
            isset($_POST['nombre']) &&
            isset($_POST['apellido']) &&
            isset($_POST['telefono']) &&
            isset($_POST['correo']) &&
            isset($_POST['contrasena']) &&
            isset($_POST['contrasena2']) &&
            isset($_POST['fecha']) &&
            isset($_POST['ciudad']) &&
            isset($_POST['rol'])
        ) {
            $cedula = trim($_POST['cedula']);
            $nombre = trim($_POST['nombre']);
            $apellido = trim($_POST['apellido']);
            $telefono = trim($_POST['telefono']);
            $correo = trim($_POST['correo']);
            $contrasena = trim($_POST['contrasena']);
            $contrasena2 = trim($_POST['contrasena2']);
            $fecha = $_POST['fecha'];
            $ciudad = trim($_POST['ciudad']);
            $rol = trim($_POST['rol']);

            // Verificar si algún campo está en blanco
            if (empty($cedula) || empty($nombre) || empty($apellido) || empty($telefono) || empty($correo) || empty($contrasena) || empty($fecha) || empty($ciudad) || empty($rol)) {
                echo "<script>alert('Por favor, llena todos los campos.');</script>";
                // Actualizar la página después de mostrar el mensaje de error
                echo "<script>window.location.replace('usuariosM.php');</script>";
            } else {
                // Verificar si la fecha es válida
                if (strtotime($fecha) !== false) {
                    // Convertir la fecha al formato correcto para MySQL
                    $fecha = date('Y-m-d', strtotime($fecha));

                    // Consulta para verificar si el ID_Usuario ya existe
                    $consulta_existencia = "SELECT ID_Usuario FROM usuario2 WHERE ID_Usuario = ?";
                    $stmt_existencia = mysqli_prepare($conex, $consulta_existencia);
                    mysqli_stmt_bind_param($stmt_existencia, "s", $cedula);
                    mysqli_stmt_execute($stmt_existencia);
                    mysqli_stmt_store_result($stmt_existencia);

                    // Verificar si el ID_Usuario ya existe
                    if (mysqli_stmt_num_rows($stmt_existencia) > 0) {
                        echo "<script>alert('La cédula ya está registrada en la base de datos. Ingresa un valor diferente.');</script>";
                        // Actualizar la página después de mostrar el mensaje de error
                        echo "<script>window.location.replace('usuariosM.php');</script>";
                    } else {
                        // Insertar el nuevo registro
                        $consulta = "INSERT INTO usuario2(ID_Usuario, Nombre, Apellido, Telefono, Correo, Contraseña, Fecha, FK_ID_Municipio, FK_ID_Rol)
                        VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?)";
                        $stmt = mysqli_prepare($conex, $consulta);
                        mysqli_stmt_bind_param($stmt, "sssssssss", $cedula, $nombre, $apellido, $telefono, $correo, $contrasena, $fecha, $ciudad, $rol);
                        $resultado = mysqli_stmt_execute($stmt);


                        if ($resultado) {
                            $proyectoID = $_SESSION['proyecto_seleccionado'];
                            $insertar_proyecto_usuario = "INSERT INTO proyecto_usuario (FK_ID_Usuario, FK_ID_Proyecto) VALUES ('$cedula', '$proyectoID')";
                            mysqli_query($conex, $insertar_proyecto_usuario);
                            echo "<script>alert('Usuario registrado con éxito y asociado al proyecto.');</script>";
                            echo "<script>window.location.replace('usuariosM.php');</script>";
                        } else {
                            echo "Error al registrar usuario.";
                            echo "<script>alert('Error al insertar en la base de datos: " . mysqli_error($conex) . "');</script>";
                        }

                    }
                } else {
                    echo "<script>alert('La fecha no es válida. Por favor, ingresa una fecha válida.');</script>";
                }
            }
        } else {
            echo "<script>alert('Por favor, llena todos los campos.');</script>";
        }
    }
}


if (isset($_POST['registar_inversion'])) {
    if (
        isset($_POST['usuario']) &&
        isset($_POST['monto']) &&
        isset($_POST['proyecto']) &&
        isset($_POST['tipo']) &&
        isset($_POST['fecha_inversion']) &&
        isset($_POST['descripcion_inversion']) &&
        isset($_POST['documento_inversion']) &&
        isset($_POST['id_usuario']) &&
        isset($_POST['id_tipo'])
    ) {
        $usuario = trim($_POST['usuario']);
        $monto = trim($_POST['monto']);
        $montoA = isset($_POST['null']) ? trim($_POST['null']) : '';
        $proyecto_nombre = trim($_POST['proyecto']); // Nombre del proyecto
        $tipo = trim($_POST['tipo']);
        $fecha_inversion = $_POST['fecha_inversion'];
        $descripcion_inversion = trim($_POST['descripcion_inversion']);
        $documento_inversion = trim($_POST['documento_inversion']);
        $id_usuario = trim($_POST['id_usuario']);
        $id_tipo = trim($_POST['id_tipo']);

        // Verificar si algún campo está en blanco
        if (empty($usuario) || empty($monto) || empty($proyecto_nombre) || empty($tipo) || 
            empty($fecha_inversion) || empty($descripcion_inversion) || 
            empty($documento_inversion) || empty($id_usuario) || empty($id_tipo)) {
            echo "<script>alert('Por favor, llena todos los campos.');</script>";
            // Actualizar la página después de mostrar el mensaje de error
            echo "<script>window.location.replace('inversionesM.php');</script>";
        } else {
            // Verificar si la fecha es válida
            if (strtotime($fecha_inversion) !== false) {
                // Convertir la fecha al formato correcto para MySQL
                $fecha_inversion = date('Y-m-d', strtotime($fecha_inversion));

                // Obtener la ID del proyecto basado en el nombre proporcionado
                $consulta_proyecto = "SELECT ID_Proyecto FROM proyecto WHERE Nombre = ?";
                $stmt_proyecto = mysqli_prepare($conex, $consulta_proyecto);
                mysqli_stmt_bind_param($stmt_proyecto, "s", $proyecto_nombre);
                mysqli_stmt_execute($stmt_proyecto);
                mysqli_stmt_store_result($stmt_proyecto);

                // Si el proyecto existe, obtener su ID
                if (mysqli_stmt_num_rows($stmt_proyecto) > 0) {
                    mysqli_stmt_bind_result($stmt_proyecto, $id_proyecto);
                    mysqli_stmt_fetch($stmt_proyecto);
                } else {
                    echo "<script>alert('El proyecto no existe. Por favor, ingresa un proyecto válido.');</script>";
                    exit; // Detener la ejecución si el proyecto no existe
                }

                // Insertar el nuevo registro en la tabla inversion2
                $consulta = "INSERT INTO inversion2(Nombre, Monto, Monto_Ajustado, proyecto, Tipo, Fecha, 
                Descripcion, CertificadoInversion, FK_ID_Usuario, FK_ID_Tipo) 
                VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                $stmt = mysqli_prepare($conex, $consulta);
                mysqli_stmt_bind_param($stmt, "ssssssssss", $usuario, $monto, $montoA, $proyecto_nombre, $tipo, 
                $fecha_inversion, $descripcion_inversion, $documento_inversion, $id_usuario, $id_tipo);
                $resultado = mysqli_stmt_execute($stmt);

                if ($resultado) {
                    echo "<script>alert('¡Tu registro se ha completado!');</script>";
                    // Actualizar la página después de la inserción
                    echo "<script>window.location.replace('inversionesM.php');</script>";
                } else {
                    echo "<script>alert('Error al insertar en la base de datos: " . mysqli_error($conex) . "');</script>";
                }
            } else {
                echo "<script>alert('La fecha no es válida. Por favor, ingresa una fecha válida.');</script>";
            }
        }
    } else {
        echo "<script>alert('Por favor, llena todos los campos.');</script>";
    }
}




