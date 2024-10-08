<?php


// Obtener el nombre y apellido del usuario de la sesión
$nombre = isset($_SESSION['nombre']) ? $_SESSION['nombre'] : '';
$apellido = isset($_SESSION['apellido']) ? $_SESSION['apellido'] : '';
?>
<div id="template">
    <style>
        .user-icon {
            display: inline-block;
            width: 40px; /* Tamaño del logo circular */
            height: 40px; /* Tamaño del logo circular */
            border-radius: 50%; /* Hacer el contenedor circular */
            background-color: #28a745; /* Color de fondo del logo (puedes cambiarlo) */
            color: white; /* Color del texto (letra) */
            text-align: center;
            line-height: 40px; /* Centrar verticalmente el texto */
            font-size: 18px; /* Tamaño de la letra */
            font-weight: bold;
        }
    </style>
<div class="header">
    <div class="header-left">
        <div class="menu-icon bi bi-list"></div>
        
        <div class="header-search">
				<h2>Inversionista</h1>	
        </div>
    </div>
    <div class="header-right">
        <div class="dashboard-setting user-notification">
            <div class="dropdown">
                <a
                    class="dropdown-toggle no-arrow"
                    href="javascript:;"
                    data-toggle="right-sidebar"
                >
                    <i class="icon-copy fa fa-adjust"></i>
                </a>
            </div>
        </div>

        <div class="user-info-dropdown">
            <div class="dropdown">
                <a
                    class="dropdown-toggle"
                    href="#"
                    role="button"
                    data-toggle="dropdown"
                >
                    <span class="user-icon">
                        <!-- Reemplazar la imagen con el logo circular -->
                        <?php
                        // Obtener la primera letra del nombre
                        $letra = strtoupper(substr($nombre, 0, 1));
                        echo $letra;
                        ?>
                    </span>
                    <span class="user-name"><?php echo $nombre . ' ' . $apellido; ?></span>
                </a>
                <div
                    class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list"
                >
                    <a class="dropdown-item" href="http://localhost/sistema-inversiones-v2/cerrar-sesion.php"><i class="dw dw-logout"></i>Cerrar sesión</a>
                </div>
            </div>
        </div>
        
    </div>
</div>

<div class="right-sidebar">
    <div class="sidebar-title">
        <h3 class="weight-600 font-16 text-blue">
            Ajustes Visuales
            <span class="btn-block font-weight-400 font-12"
                >Interfaz Moderador</span
            >
        </h3>
        <div class="close-sidebar" data-toggle="right-sidebar-close">
            <i class="icon-copy ion-close-round"></i>
        </div>
    </div>
    <div class="right-sidebar-body customscroll">
        <div class="right-sidebar-body-content">
            <h4 class="weight-600 font-18 pb-10">Color-Encabezado</h4>
            <div class="sidebar-btn-group pb-30 mb-10">
                <a
                    href="javascript:void(0);"
                    class="btn btn-outline-primary header-white active"
                    >Blanco</a
                >
                <a
                    href="javascript:void(0);"
                    class="btn btn-outline-primary header-dark"
                    >Oscuro</a
                >
            </div>

            <h4 class="weight-600 font-18 pb-10">Color-barra de navegación</h4>
            <div class="sidebar-btn-group pb-30 mb-10">
                <a
                    href="javascript:void(0);"
                    class="btn btn-outline-primary sidebar-light"
                    >Blanco</a
                >
                <a
                    href="javascript:void(0);"
                    class="btn btn-outline-primary sidebar-dark active"
                    >Oscuro</a
                >
            </div>

            <h4 class="weight-600 font-18 pb-10">Ícono del menú</h4>
            <div class="sidebar-radio-group pb-10 mb-10">
                <div class="custom-control custom-radio custom-control-inline">
                    <input
                        type="radio"
                        id="sidebaricon-1"
                        name="menu-dropdown-icon"
                        class="custom-control-input"
                        value="icon-style-1"
                        checked=""
                    />
                    <label class="custom-control-label" for="sidebaricon-1"
                        ><i class="fa fa-angle-down"></i
                    ></label>
                </div>
                <div class="custom-control custom-radio custom-control-inline">
                    <input
                        type="radio"
                        id="sidebaricon-2"
                        name="menu-dropdown-icon"
                        class="custom-control-input"
                        value="icon-style-2"
                    />
                    <label class="custom-control-label" for="sidebaricon-2"
                        ><i class="ion-plus-round"></i
                    ></label>
                </div>
                <div class="custom-control custom-radio custom-control-inline">
                    <input
                        type="radio"
                        id="sidebaricon-3"
                        name="menu-dropdown-icon"
                        class="custom-control-input"
                        value="icon-style-3"
                    />
                    <label class="custom-control-label" for="sidebaricon-3"
                        ><i class="fa fa-angle-double-right"></i
                    ></label>
                </div>
            </div>

            <h4 class="weight-600 font-18 pb-10">Ícono de la lista</h4>
            <div class="sidebar-radio-group pb-30 mb-10">
                <div class="custom-control custom-radio custom-control-inline">
                    <input
                        type="radio"
                        id="sidebariconlist-1"
                        name="menu-list-icon"
                        class="custom-control-input"
                        value="icon-list-style-1"
                        checked=""
                    />
                    <label class="custom-control-label" for="sidebariconlist-1"
                        ><i class="ion-minus-round"></i
                    ></label>
                </div>
                <div class="custom-control custom-radio custom-control-inline">
                    <input
                        type="radio"
                        id="sidebariconlist-2"
                        name="menu-list-icon"
                        class="custom-control-input"
                        value="icon-list-style-2"
                    />
                    <label class="custom-control-label" for="sidebariconlist-2"
                        ><i class="fa fa-circle-o" aria-hidden="true"></i
                    ></label>
                </div>
                <div class="custom-control custom-radio custom-control-inline">
                    <input
                        type="radio"
                        id="sidebariconlist-3"
                        name="menu-list-icon"
                        class="custom-control-input"
                        value="icon-list-style-3"
                    />
                    <label class="custom-control-label" for="sidebariconlist-3"
                        ><i class="dw dw-check"></i
                    ></label>
                </div>
                <div class="custom-control custom-radio custom-control-inline">
                    <input
                        type="radio"
                        id="sidebariconlist-4"
                        name="menu-list-icon"
                        class="custom-control-input"
                        value="icon-list-style-4"
                        checked=""
                    />
                    <label class="custom-control-label" for="sidebariconlist-4"
                        ><i class="icon-copy dw dw-next-2"></i
                    ></label>
                </div>
                <div class="custom-control custom-radio custom-control-inline">
                    <input
                        type="radio"
                        id="sidebariconlist-5"
                        name="menu-list-icon"
                        class="custom-control-input"
                        value="icon-list-style-5"
                    />
                    <label class="custom-control-label" for="sidebariconlist-5"
                        ><i class="dw dw-fast-forward-1"></i
                    ></label>
                </div>
                <div class="custom-control custom-radio custom-control-inline">
                    <input
                        type="radio"
                        id="sidebariconlist-6"
                        name="menu-list-icon"
                        class="custom-control-input"
                        value="icon-list-style-6"
                    />
                    <label class="custom-control-label" for="sidebariconlist-6"
                        ><i class="dw dw-next"></i
                    ></label>
                </div>
            </div>

            <div class="reset-options pt-30 text-center">
                <button class="btn btn-danger" id="reset-settings">
                    Restablecer Ajustes
                </button>
            </div>
        </div>
    </div>
</div>

<div class="left-side-bar">
    <div class="brand-logo">
        <a href="index.html">
            <img src="" alt="" class="dark-logo" />
            
        </a>
        <div class="close-sidebar" data-toggle="left-sidebar-close">
            <i class="ion-close-round"></i>
        </div>
    </div>
    <div class="menu-block customscroll">
        <div class="sidebar-menu">
            <ul id="accordion-menu">
                <li>
                    <a href="inicioI.php" class="dropdown-toggle no-arrow">
                        <span class="micon fa fa-home"></span
                        ><span class="mtext">Inicio</span>
                    </a>
                </li>
                
                <li>
                    <a href="consultaI.php" class="dropdown-toggle no-arrow">
                        <span class="micon fa fa-table"></span
                        ><span class="mtext">Resumen</span>
                    </a>
                </li>

                <li>
                    <div class="sidebar-small-cap">Extra</div>
                </li>
            </ul>
        </div>
    </div>
</div>
<div class="mobile-menu-overlay"></div>
</div>