<?php
    session_start();
    require_once('Includes/Config.php');
    if ($_SESSION['rol'] == 'A') {
        if ($_SESSION['timeout'] + 60 * 60 < time()) {
            session_unset();
            session_destroy();
            $ruta = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
            header("Location:$ruta/Login.php");
        } else {
            $_SESSION['timeout'] = time();
        }
    } else {
        $ruta = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
        header("Location:$ruta/Acceso_Restringido.php");
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <title>AGM - Administrador de Contenido</title>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
        <link rel="icon" href="Images/agm_desarrollos_2_icon.png" type="image/x-icon" />
        <link rel="stylesheet" href="Css/AGM_Style.css" />
        <link rel="stylesheet" href="Css/bootstrap.min.css" />
        <link rel="stylesheet" href="Css/menu_style.css" />
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.2.0/css/all.css" />
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
        <script src="Javascript/bootstrap.min.js"></script>
    </head>
    <body>
        <div class="wrapper">
            <?php include("Top Pages/Top_Page_Seguridad.php"); ?>
            <section>
                <div class="container">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="content">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="leftcol">
                                            <div class="page-wrapper chiller-theme toggled">
                                                <nav id="sidebar" class="sidebar-wrapper">
                                                    <div class="sidebar-content">
                                                        <div class="sidebar-menu">
                                                            <ul>
                                                                <li class="header-menu">
                                                                    <h3 style="text-align: center;">OPCIONES DISPONIBLES</h3>
                                                                </li>
                                                                <li class="sidebar-dropdown active">
                                                                    <a href="#">
                                                                        <i style="background-color: #003153; color: #FFFFFF;" class="fas fa-cogs"></i>
                                                                        <span>Admin. Contenido</span>
                                                                    </a>
                                                                    <div style="display: block;" class="sidebar-submenu">
                                                                        <ul class="nav nav-pills nav-stacked">
                                                                            <li>
                                                                                <a href='Admin_Tipo_Clientes.php'>
                                                                                    <i style="background-color: #003153; color: #FFFFFF;" class="fas fa-tags"></i>
                                                                                    <span>Tipo Clientes</span>
                                                                                </a>
                                                                            </li>
                                                                            <li>
                                                                                <a href='Admin_Tarifas.php'>
                                                                                    <i style="background-color: #003153; color: #FFFFFF;" class="fas fa-tags"></i>
                                                                                    <span>Tarifas</span>
                                                                                </a>
                                                                            </li>
                                                                            <li>
                                                                                <a href='Admin_Alcaldias.php'>
                                                                                    <i style="background-color: #003153; color: #FFFFFF;" class="fas fa-tags"></i>
                                                                                    <span>Alcaldías</span>
                                                                                </a>
                                                                            </li>
                                                                            <li>
                                                                                <a href='Admin_Departamentos.php'>
                                                                                    <i style="background-color: #003153; color: #FFFFFF;" class="fas fa-tags"></i>
                                                                                    <span>Deptos. Cargue</span>
                                                                                </a>
                                                                            </li>
                                                                            <li>
                                                                                <a href='Admin_Departamentos_Visitas.php'>
                                                                                    <i style="background-color: #003153; color: #FFFFFF;" class="fas fa-tags"></i>
                                                                                    <span>Deptos. Visita</span>
                                                                                </a>
                                                                            </li>
                                                                            <li>
                                                                                <a href='Admin_Departamentos_Operadores.php'>
                                                                                    <i style="background-color: #003153; color: #FFFFFF;" class="fas fa-tags"></i>
                                                                                    <span>Deptos. Operadores</span>
                                                                                </a>
                                                                            </li>
                                                                            <li>
                                                                                <a href='Admin_Departamentos_Comercializadores.php'>
                                                                                    <i style="background-color: #003153; color: #FFFFFF;" class="fas fa-tags"></i>
                                                                                    <span>Deptos. Comercializ.</span>
                                                                                </a>
                                                                            </li>
                                                                            <li>
                                                                                <a href='Admin_Municipios.php'>
                                                                                    <i style="background-color: #003153; color: #FFFFFF;" class="fas fa-tags"></i>
                                                                                    <span>Mpios. Cargue</span>
                                                                                </a>
                                                                            </li>
                                                                            <li>
                                                                                <a href='Admin_Municipios_Visitas.php'>
                                                                                    <i style="background-color: #003153; color: #FFFFFF;" class="fas fa-tags"></i>
                                                                                    <span>Mpios. Visita</span>
                                                                                </a>
                                                                            </li>
                                                                            <li>
                                                                                <a href='Admin_Municipios_Operadores.php'>
                                                                                    <i style="background-color: #003153; color: #FFFFFF;" class="fas fa-tags"></i>
                                                                                    <span>Mpios. Operadores</span>
                                                                                </a>
                                                                            </li>
                                                                            <li>
                                                                                <a href='Admin_Municipios_Comercializadores.php'>
                                                                                    <i style="background-color: #003153; color: #FFFFFF;" class="fas fa-tags"></i>
                                                                                    <span>Mpios. Comercializ.</span>
                                                                                </a>
                                                                            </li>
                                                                            <li>
                                                                                <a href='Admin_Concesiones.php'>
                                                                                    <i style="background-color: #003153; color: #FFFFFF;" class="fas fa-tags"></i>
                                                                                    <span>Concesiones</span>
                                                                                </a>
                                                                            </li>
                                                                            <li>
                                                                                <a href='Admin_Empresas.php'>
                                                                                    <i style="background-color: #003153; color: #FFFFFF;" class="fas fa-tags"></i>
                                                                                    <span>Empresas</span>
                                                                                </a>
                                                                            </li>
                                                                            <li>
                                                                                <a href='Admin_Operadores.php'>
                                                                                    <i style="background-color: #003153; color: #FFFFFF;" class="fas fa-tags"></i>
                                                                                    <span>Operadores</span>
                                                                                </a>
                                                                            </li>
                                                                            <li>
                                                                                <a href='Admin_Contribuyentes.php'>
                                                                                    <i style="background-color: #003153; color: #FFFFFF;" class="fas fa-tags"></i>
                                                                                    <span>Contribuyentes</span>
                                                                                </a>
                                                                            </li>
                                                                            <li>
                                                                                <a href='Admin_Comercializadores.php'>
                                                                                    <i style="background-color: #003153; color: #FFFFFF;" class="fas fa-tags"></i>
                                                                                    <span>Comercializadores</span>
                                                                                </a>
                                                                            </li>
                                                                            <li>
                                                                                <a href='Admin_Tipo_Contratos.php'>
                                                                                    <i style="background-color: #003153; color: #FFFFFF;" class="fas fa-tags"></i>
                                                                                    <span>Tipo Contratos</span>
                                                                                </a>
                                                                            </li>
                                                                            <li>
                                                                                <a href='Admin_Tipo_Visitas.php'>
                                                                                    <i style="background-color: #003153; color: #FFFFFF;" class="fas fa-tags"></i>
                                                                                    <span>Tipo Visitas</span>
                                                                                </a>
                                                                            </li>
                                                                            <li>
                                                                                <a href='Admin_Tipo_Novedades.php'>
                                                                                    <i style="background-color: #003153; color: #FFFFFF;" class="fas fa-tags"></i>
                                                                                    <span>Tipo Novedades</span>
                                                                                </a>
                                                                            </li>
                                                                            <li>
                                                                                <a href='Admin_Estados_Suministro.php'>
                                                                                    <i style="background-color: #003153; color: #FFFFFF;" class="fas fa-tags"></i>
                                                                                    <span>Estados Suministro</span>
                                                                                </a>
                                                                            </li>
                                                                            
                                                                        </ul>
                                                                    </div>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </nav>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-9">
                                        <div class="rightcol">
                                                
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </body>
    <script src="Javascript/menu.js"></script>
    <script>
        $(document).ready(function() {
            $("#menu_home").tooltip({
                container : "body",
                placement : "top"
            });
            $("#menu_seguridad").tooltip({
                container : "body",
                placement : "top"
            });
        });
    </script>
</html>