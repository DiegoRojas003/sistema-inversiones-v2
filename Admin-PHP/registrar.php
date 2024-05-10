<?php
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
    if (
        isset($_POST['id_departamento']) &&
        isset($_POST['departamentoo']) &&
        isset($_POST['FK_Pais'])
    ) {
        $id_departamento = trim($_POST['id_departamento']);
        $departamento = trim($_POST['departamentoo']);
        $FK_Pais = trim($_POST['FK_Pais']);

        // Verificar si los campos están en blanco
        if (empty($id_departamento) || empty($departamento) || empty($FK_Pais)) {
            echo "<script>alert('Por favor, llena todos los campos.');</script>";
            // Actualizar la página después de mostrar el mensaje de error
            echo "<script>window.location.replace('ubicacion.php');</script>";
        } else {
            // Consulta para verificar si el ID_Departamento ya existe
            $consulta_existencia = "SELECT ID_Departamento FROM departamento WHERE ID_Departamento = ?";
            $stmt_existencia = mysqli_prepare($conex, $consulta_existencia);
            mysqli_stmt_bind_param($stmt_existencia, "s", $id_departamento);
            mysqli_stmt_execute($stmt_existencia);
            mysqli_stmt_store_result($stmt_existencia);

            // Verificar si el ID_Departamento ya existe
            if (mysqli_stmt_num_rows($stmt_existencia) > 0) {
                echo "<script>alert('El Identificador del Departamento ya está registrado en la base de datos. Ingresa un valor diferente.');</script>";
                // Actualizar la página después de mostrar el mensaje de error
                echo "<script>window.location.replace('ubicacion.php');</script>";
            } else {
                // Insertar el nuevo registro
                $consulta = "INSERT INTO departamento(ID_Departamento, Nombre, FK_ID_Pais) VALUES(?, ?, ?)";
                $stmt = mysqli_prepare($conex, $consulta);
                mysqli_stmt_bind_param($stmt, "sss", $id_departamento, $departamento, $FK_Pais);
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

if (isset($_POST['register_municipio'])) {
    if (
        isset($_POST['id_municipio']) &&
        isset($_POST['municipioo']) &&
        isset($_POST['FK_Departamento'])
    ) {
        $id_municipio = trim($_POST['id_municipio']);
        $municipioo = trim($_POST['municipioo']);
        $FK_Departamento = trim($_POST['FK_Departamento']);

        // Verificar si los campos están en blanco
        if (empty($id_municipio) || empty($municipioo) || empty($FK_Departamento)) {
            echo "<script>alert('Por favor, llena todos los campos.');</script>";
            // Actualizar la página después de mostrar el mensaje de error
            echo "<script>window.location.replace('ubicacion.php');</script>";
        } else {
            // Consulta para verificar si el ID_Municipio ya existe
            $consulta_existencia = "SELECT ID_Municipio FROM municipio WHERE ID_Municipio = ?";
            $stmt_existencia = mysqli_prepare($conex, $consulta_existencia);
            mysqli_stmt_bind_param($stmt_existencia, "s", $id_municipio);
            mysqli_stmt_execute($stmt_existencia);
            mysqli_stmt_store_result($stmt_existencia);

            // Verificar si el ID_Municipio ya existe
            if (mysqli_stmt_num_rows($stmt_existencia) > 0) {
                echo "<script>alert('El Identificador del Municipio ya está registrado en la base de datos. Ingresa un valor diferente.');</script>";
                // Actualizar la página después de mostrar el mensaje de error
                echo "<script>window.location.replace('ubicacion.php');</script>";
            } else {
                // Insertar el nuevo registro
                $consulta = "INSERT INTO municipio(ID_Municipio, Nombre, FK_ID_Departamento) VALUES(?, ?, ?)";
                $stmt = mysqli_prepare($conex, $consulta);
                mysqli_stmt_bind_param($stmt, "sss", $id_municipio, $municipioo, $FK_Departamento);
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
        if (empty($id_proyecto) || empty($nombre_proyecto) || empty($fecha_proyecto) || empty($descripcion_p) || empty($documento_proyecto)) {
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
    if (
        isset($_POST['cedula']) &&
        isset($_POST['nombre']) &&
        isset($_POST['apellido']) &&
        isset($_POST['telefono']) &&
        isset($_POST['correo']) &&
        isset($_POST['contrasena']) &&
        isset($_POST['fecha']) &&
        isset($_POST['proyecto']) &&
        isset($_POST['ciudad']) &&
        isset($_POST['rol'])
    ) {
        $cedula = trim($_POST['cedula']);
        $nombre = trim($_POST['nombre']);
        $apellido = trim($_POST['apellido']);
        $telefono = trim($_POST['telefono']);
        $correo = trim($_POST['correo']);
        $contrasena = trim($_POST['contrasena']);
        $fecha = $_POST['fecha'];
        $proyecto = trim($_POST['proyecto']);
        $ciudad = trim($_POST['ciudad']);
        $rol = trim($_POST['rol']);

        // Verificar si algún campo está en blanco
        if (empty($cedula) || empty($nombre) || empty($apellido) || empty($telefono) || empty($correo) || empty($contrasena) || empty($fecha) || empty($proyecto) || empty($ciudad) || empty($rol)) {
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
                    $consulta = "INSERT INTO usuario2(ID_Usuario, Nombre, Apellido, Telefono, Correo, Contraseña, Fecha, Proyecto, FK_ID_Municipio, FK_ID_Rol)
                    VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                    $stmt = mysqli_prepare($conex, $consulta);
                    mysqli_stmt_bind_param($stmt, "ssssssssss", $cedula, $nombre, $apellido, $telefono, $correo, $contrasena, $fecha, $proyecto, $ciudad, $rol);
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

if (isset($_POST['register_tipos'])) {

    if (isset($_POST['id_tipo']) && isset($_POST['nombre_tipo']) && isset($_POST['descripcion_tipo'])) {
        $id_tipo = trim($_POST['id_tipo']);
        $nombre_tipo = trim($_POST['nombre_tipo']);
        $descripcion_tipo = trim($_POST['descripcion_tipo']);

        // Verificar si los campos están en blanco
        if (empty($id_tipo) || empty($nombre_tipo) || empty($descripcion_tipo)) {
            echo "<script>alert('Por favor, llena todos los campos.');</script>";
            // Actualizar la página después de mostrar el mensaje de error
            echo "<script>window.location.replace('tiposInveriones.php');</script>";
        } else {
            $consulta_existencia = "SELECT ID_TIPO FROM tipo WHERE ID_TIPO = ?";
            $stmt_existencia = mysqli_prepare($conex, $consulta_existencia);
            mysqli_stmt_bind_param($stmt_existencia, "s", $id_tipo);
            mysqli_stmt_execute($stmt_existencia);
            mysqli_stmt_store_result($stmt_existencia);

            // Verificar si el ID_Pais ya existe
            if (mysqli_stmt_num_rows($stmt_existencia) > 0) {
                echo "<script>alert('El Identificador del Tipo ya está registrado en la base de datos. Ingresa un valor diferente.');</script>";
                // Actualizar la página después de mostrar el mensaje de error
                echo "<script>window.location.replace('tiposInveriones.php');</script>";
            } else {
                // Insertar el nuevo registro
                $consultat = "INSERT INTO tipo(ID_TIPO, Nombre, Descripcion) VALUES(?, ?, ?)";
                $stmt = mysqli_prepare($conex, $consultat);
                mysqli_stmt_bind_param($stmt, "sss", $id_tipo, $nombre_tipo, $descripcion_tipo);
                $resultado = mysqli_stmt_execute($stmt);

                if ($resultado) {
                    echo "<script>alert('¡Tu registro se ha completado!');</script>";
                    // Actualizar la página después de la inserción
                    echo "<script>window.location.replace('tiposInveriones.php');</script>";
                } else {
                    echo "<script>alert('Error al insertar en la base de datos: " . mysqli_error($conex) . "');</script>";
                }
            }
        }
    } else {
        echo "<script>alert('Por favor, llena todos los campos.');</script>";
    }
}

if (isset($_POST['registar_inversion'])) {
    if (
        isset($_POST['id_inversion']) &&
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
        $id_inversion = trim($_POST['id_inversion']);
        $usuario = trim($_POST['usuario']);
        $monto = trim($_POST['monto']);
        $montoA = isset($_POST['null']) ? trim($_POST['null']) : '';
        $proyecto = trim($_POST['proyecto']);
        $tipo = trim($_POST['tipo']);
        $fecha_inversion = $_POST['fecha_inversion'];
        $descripcion_inversion = trim($_POST['descripcion_inversion']);
        $documento_inversion = trim($_POST['documento_inversion']);
        $id_usuario = trim($_POST['id_usuario']);
        $id_tipo = trim($_POST['id_tipo']);

        // Verificar si algún campo está en blanco
        if (empty($id_inversion) || empty($usuario) || empty($monto) || empty($proyecto) || 
        empty($tipo) || empty($fecha_inversion) || empty($descripcion_inversion) || 
        empty($documento_inversion) || empty($id_usuario) || empty($id_tipo)) {
            echo "<script>alert('Por favor, llena todos los campos.');</script>";
            // Actualizar la página después de mostrar el mensaje de error
            echo "<script>window.location.replace('inversiones.php');</script>";
        } else {
            // Verificar si la fecha es válida
            if (strtotime($fecha_inversion) !== false) {
                // Convertir la fecha al formato correcto para MySQL
                $fecha_inversion = date('Y-m-d', strtotime($fecha_inversion));

                // Consulta para verificar si el ID_Proyecto ya existe
                $consulta_existencia = "SELECT ID_Inversion FROM inversion2 WHERE ID_Inversion = ?";
                $stmt_existencia = mysqli_prepare($conex, $consulta_existencia);
                mysqli_stmt_bind_param($stmt_existencia, "s", $id_inversion);
                mysqli_stmt_execute($stmt_existencia);
                mysqli_stmt_store_result($stmt_existencia);

                // Verificar si el ID_inversion ya existe
                if (mysqli_stmt_num_rows($stmt_existencia) > 0) {
                    echo "<script>alert('El Identificador de Invercion ya está registrado en la base de datos. Ingresa un valor diferente.');</script>";
                    // Actualizar la página después de mostrar el mensaje de error
                    echo "<script>window.location.replace('inversiones.php');</script>";
                } else {
                    // Insertar el nuevo registro
                    $consulta = "INSERT INTO inversion2(ID_Inversion, Nombre, Monto, Monto_Ajustado, 
                    proyecto,Tipo,Fecha, Descripcion, CertificadoInversion, FK_ID_Usuario, FK_ID_Tipo) 
                    VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                    $stmt = mysqli_prepare($conex, $consulta);
                    mysqli_stmt_bind_param($stmt, "sssssssssss", $id_inversion, $usuario,$monto,$montoA,$proyecto, 
                    $tipo,$fecha_inversion, $descripcion_inversion, $documento_inversion,$id_usuario,$id_tipo);
                    $resultado = mysqli_stmt_execute($stmt);

                    if ($resultado) {
                        echo "<script>alert('¡Tu registro se ha completado!');</script>";
                        // Actualizar la página después de la inserción
                        echo "<script>window.location.replace('inversiones.php');</script>";
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

?>
