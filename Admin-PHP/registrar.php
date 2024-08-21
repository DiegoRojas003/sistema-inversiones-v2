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

// header('Location: ubicacion.php');
if (isset($_POST['register_pais'])) {
    if (isset($_POST['id_pais']) && isset($_POST['pais'])) {
        $id = trim($_POST['id_pais']);
        $pais = trim($_POST['pais']);

        // Verificar si los campos están en blanco
        if (empty($id) || empty($pais)) {
            echo "<script>alert('Por favor, llena todos los campos.');</script>";
            // Actualizar la página después de mostrar el mensaje de error
            echo "<script>window.location.replace('ubicacion.php');</script>";
        } else {
            // Consulta para verificar si el ID_Pais ya existe
            $consulta_existencia = "SELECT ID_Pais FROM pais WHERE ID_Pais = ?";
            $stmt_existencia = mysqli_prepare($conex, $consulta_existencia);
            mysqli_stmt_bind_param($stmt_existencia, "s", $id);
            mysqli_stmt_execute($stmt_existencia);
            mysqli_stmt_store_result($stmt_existencia);

            // Verificar si el ID_Pais ya existe
            if (mysqli_stmt_num_rows($stmt_existencia) > 0) {
                echo "<script>alert('El Identificador del Pais ya está registrado en la base de datos. Ingresa un valor diferente.');</script>";
                // Actualizar la página después de mostrar el mensaje de error
                echo "<script>window.location.replace('ubicacion.php');</script>";
            } else {
                // Insertar el nuevo registro
                $consulta = "INSERT INTO pais(ID_Pais, Nombre) VALUES(?, ?)";
                $stmt = mysqli_prepare($conex, $consulta);
                mysqli_stmt_bind_param($stmt, "ss", $id, $pais);
                $resultado = mysqli_stmt_execute($stmt);

                if ($resultado) {
                    echo "<script>alert('¡Tu registro se ha completado!');</script>";
                    // Actualizar la página después de la inserción
                    echo "<script>window.location.replace('ubicacion.php');</script>";
                } else {
                    echo "<script>alert('Error al insertar en la base de datos: " . mysqli_error($conex) . "');</script>";
                }
            }
        }
    } else {
        echo "<script>alert('Por favor, llena todos los campos.');</script>";
    }
}

if (isset($_POST['register_departamento'])) {
    if (isset($_POST['departamentoo']) &&
        isset($_POST['FK_Pais'])
    ) {$departamento = trim($_POST['departamentoo']);
        $FK_Pais = trim($_POST['FK_Pais']);

        // Verificar si los campos están en blanco
        if (empty($departamento) || empty($FK_Pais)) {
            echo "<script>alert('Por favor, llena todos los campos.');</script>";
            // Actualizar la página después de mostrar el mensaje de error
            echo "<script>window.location.replace('ubicacion.php');</script>";
        } else {
            // Insertar el nuevo registro
            $consulta = "INSERT INTO departamento(Nombre, FK_ID_Pais) VALUES(?, ?)";
            $stmt = mysqli_prepare($conex, $consulta);
            mysqli_stmt_bind_param($stmt, "ss", $departamento, $FK_Pais);
            $resultado = mysqli_stmt_execute($stmt);

            if ($resultado) {
                echo "<script>alert('¡Tu registro se ha completado!');</script>";
                // Actualizar la página después de la inserción
                echo "<script>window.location.replace('ubicacion.php');</script>";
            } else {
                echo "<script>alert('Error al insertar en la base de datos: " . mysqli_error($conex) . "');</script>";
            }
        }
    } else {
        echo "<script>alert('Por favor, llena todos los campos.');</script>";
    }
}

if (isset($_POST['register_municipio'])) {
    if (
        isset($_POST['municipioo']) &&
        isset($_POST['FK_Departamento'])
    ) {
        $municipioo = trim($_POST['municipioo']);
        $FK_Departamento = trim($_POST['FK_Departamento']);

        // Verificar si los campos están en blanco
        if (empty($municipioo) || empty($FK_Departamento)) {
            echo "<script>alert('Por favor, llena todos los campos.');</script>";
            // Actualizar la página después de mostrar el mensaje de error
            echo "<script>window.location.replace('ubicacion.php');</script>";
        } else {
            // Insertar el nuevo registro
            $consulta = "INSERT INTO municipio(Nombre, FK_ID_Departamento) VALUES(?, ?)";
            $stmt = mysqli_prepare($conex, $consulta);
            mysqli_stmt_bind_param($stmt, "ss", $municipioo, $FK_Departamento);
            $resultado = mysqli_stmt_execute($stmt);

            if ($resultado) {
                echo "<script>alert('¡Tu registro se ha completado!');</script>";
                // Actualizar la página después de la inserción
                echo "<script>window.location.replace('ubicacion.php');</script>";
            } else {
                echo "<script>alert('Error al insertar en la base de datos: " . mysqli_error($conex) . "');</script>";
            }
        }
    } else {
        echo "<script>alert('Por favor, llena todos los campos.');</script>";
    }
}

if (isset($_POST['register_proyecto'])) {
    if (
        isset($_POST['id_proyecto']) &&
        isset($_POST['nombre_proyecto']) &&
        isset($_POST['fecha_proyecto']) &&
        isset($_POST['descripcion_p']) &&
        isset($_POST['documento_proyecto'])
    ) {
        $id_proyecto = trim($_POST['id_proyecto']);
        $nombre_proyecto = trim($_POST['nombre_proyecto']);
        $fecha_proyecto = $_POST['fecha_proyecto'];
        $descripcion_p = trim($_POST['descripcion_p']);
        $documento_proyecto = trim($_POST['documento_proyecto']);

        // Verificar si algún campo está en blanco
        if (empty($id_proyecto) || empty($nombre_proyecto) || empty($fecha_proyecto) || empty($descripcion_p) ) {
            echo "<script>alert('Por favor, llena todos los campos.');</script>";
            // Actualizar la página después de mostrar el mensaje de error
            echo "<script>window.location.replace('proyectos.php');</script>";
        } else {
            // Verificar si la fecha es válida
            if (strtotime($fecha_proyecto) !== false) {
                // Convertir la fecha al formato correcto para MySQL
                $fecha_proyecto = date('Y-m-d', strtotime($fecha_proyecto));

                // Consulta para verificar si el ID_Proyecto ya existe
                $consulta_existencia = "SELECT ID_Proyecto FROM proyecto WHERE ID_Proyecto = ?";
                $stmt_existencia = mysqli_prepare($conex, $consulta_existencia);
                mysqli_stmt_bind_param($stmt_existencia, "s", $id_proyecto);
                mysqli_stmt_execute($stmt_existencia);
                mysqli_stmt_store_result($stmt_existencia);

                // Verificar si el ID_Proyecto ya existe
                if (mysqli_stmt_num_rows($stmt_existencia) > 0) {
                    echo "<script>alert('El Identificador de Proyecto ya está registrado en la base de datos. Ingresa un valor diferente.');</script>";
                    // Actualizar la página después de mostrar el mensaje de error
                    echo "<script>window.location.replace('proyectos.php');</script>";
                } else {
                    // Insertar el nuevo registro
                    $consulta = "INSERT INTO proyecto(ID_Proyecto, Nombre, Fecha, Descripcion, Certificado) VALUES(?, ?, ?, ?, ?)";
                    $stmt = mysqli_prepare($conex, $consulta);
                    mysqli_stmt_bind_param($stmt, "sssss", $id_proyecto, $nombre_proyecto, $fecha_proyecto, $descripcion_p, $documento_proyecto);
                    $resultado = mysqli_stmt_execute($stmt);

                    if ($resultado) {
                        echo "<script>alert('¡Tu registro se ha completado!');</script>";
                        // Actualizar la página después de la inserción
                        echo "<script>window.location.replace('proyectos.php');</script>";
                    } else {
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

if (isset($_POST['register_usuarios'])) {
    // Verificar si las contraseñas son iguales
    if ($_POST['contrasena'] !== $_POST['contrasena2']) {
        echo "<script>alert('Las contraseñas no son iguales. Por favor, inténtalo de nuevo.');</script>";
        // Actualizar la página después de mostrar el mensaje de error
        echo "<script>window.location.replace('usuarios.php');</script>";
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
                echo "<script>window.location.replace('usuarios.php');</script>";
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
                        echo "<script>window.location.replace('usuarios.php');</script>";
                    } else {
                        // Insertar el nuevo registro
                        $consulta = "INSERT INTO usuario2(ID_Usuario, Nombre, Apellido, Telefono, Correo, Contraseña, Fecha, FK_ID_Municipio, FK_ID_Rol)
                        VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?)";
                        $stmt = mysqli_prepare($conex, $consulta);
                        mysqli_stmt_bind_param($stmt, "sssssssss", $cedula, $nombre, $apellido, $telefono, $correo, $contrasena, $fecha, $ciudad, $rol);
                        $resultado = mysqli_stmt_execute($stmt);

                        if ($resultado) {
                            echo "<script>alert('¡Tu registro se ha completado!');</script>";
                            // Actualizar la página después de la inserción
                            echo "<script>window.location.replace('usuarios.php');</script>";
                        } else {
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

if (isset($_POST['register_tipos'])) {
    if (isset($_POST['nombre_tipo']) && isset($_POST['descripcion_tipo'])) {
        $nombre_tipo = trim($_POST['nombre_tipo']);
        $descripcion_tipo = trim($_POST['descripcion_tipo']);

        // Verificar si los campos están en blanco
        if (empty($nombre_tipo) || empty($descripcion_tipo)) {
            echo "<script>alert('Por favor, llena todos los campos.');</script>";
            // Actualizar la página después de mostrar el mensaje de error
            echo "<script>window.location.replace('tiposInveriones.php');</script>";
        } else {
            // Insertar el nuevo registro
            $consulta = "INSERT INTO tipo(Nombre, Descripcion) VALUES(?, ?)";
            $stmt = mysqli_prepare($conex, $consulta);
            mysqli_stmt_bind_param($stmt, "ss", $nombre_tipo, $descripcion_tipo);
            $resultado = mysqli_stmt_execute($stmt);

            if ($resultado) {
                echo "<script>alert('¡Tu registro se ha completado!');</script>";
                // Actualizar la página después de la inserción
                echo "<script>window.location.replace('tiposInveriones.php');</script>";
            } else {
                echo "<script>alert('Error al insertar en la base de datos: " . mysqli_error($conex) . "');</script>";
            }
        }
    } else {
        echo "<script>alert('Por favor, llena todos los campos.');</script>";
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
            echo "<script>window.location.replace('inversiones.php');</script>";
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
                    echo "<script>window.location.replace('inversiones.php');</script>";
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


if (isset($_POST['register_proyecto_usuario'])) {
    if (empty($_POST['proyecto']) || empty($_POST['usuarios']) ) {
            echo "<script>alert('Por favor, llena todos los campos.');</script>";
            // Actualizar la página después de mostrar el mensaje de error
            echo "<script>window.location.replace('proyectos.php');</script>";
    } else {
        // Verificar si se seleccionó un proyecto y al menos un usuario
        if (isset($_POST['proyecto']) && isset($_POST['usuarios'])) {
            // Obtener el ID del proyecto
            $id_proyecto = $_POST['proyecto'];
            
            // Recorrer los usuarios seleccionados y registrarlos en proyecto_usuario
            foreach ($_POST['usuarios'] as $usuario) {
                try {
                    // Realizar la inserción en la tabla proyecto_usuario
                    $consulta_insertar = "INSERT INTO proyecto_usuario (FK_ID_Usuario, FK_ID_Proyecto) VALUES (?, ?)";
                    $stmt_insertar = mysqli_prepare($conex, $consulta_insertar);
                    mysqli_stmt_bind_param($stmt_insertar, "ss", $usuario, $id_proyecto);
                    mysqli_stmt_execute($stmt_insertar);
                } catch (mysqli_sql_exception $e) {
                    // Mostrar un mensaje de error si ocurre una excepción
                    echo "<script>alert('El usuario ya está vinculado al proyecto');</script>";
                    echo "<script>window.location.replace('proyectos.php');</script>";
                }
            }
            
            // Mostrar un mensaje de éxito
            echo "<script>alert('Usuarios vinculados al proyecto correctamente');</script>";
            echo "<script>window.location.replace('proyectos.php');</script>";
            
        }
    }
    
}



