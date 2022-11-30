<?php
    session_start();
    require_once('Includes/Config.php');
    if($_SESSION['timeout'] + 60 * 60 < time()){
        session_unset();
        session_destroy();
        $ruta = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
        header("Location:$ruta/Login.php");
    }
    else{
        $_SESSION['timeout'] = time();
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <title>AGM - Acceso Restringido</title>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
        <link rel="icon" href="Images/agm_desarrollos_2_icon.png" type="image/x-icon" />
        <link rel="stylesheet" href="Css/AGM_Style.css" />
        <link rel="stylesheet" href="Css/bootstrap.min.css" />
        <link rel="stylesheet" href="Css/menu_style.css" />
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.2.0/css/all.css" integrity="sha384-hWVjflwFxL6sNzntih27bfxkr27PmbbK/iSvJ+a4+0owXq79v+lsFkW54bOGbiDQ" crossorigin="anonymous">
    </head>
    <body>
        <div class="wrapper">
            <?php include("Top Pages/Top_Page_Home.php"); ?>
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
                                                                <li class="sidebar-dropdown">
                                                                    <a href="#">
                                                                        <i style="background-color: #003153; color: #FFFFFF;" class="fas fa-money-check-alt"></i>
                                                                        <span>Facturación y Recaudo</span>
                                                                    </a>
                                                                    <div class="sidebar-submenu">
                                                                        <ul class="nav nav-pills nav-stacked">
                                                                            <?php
                                                                                if ($_SESSION['rol'] == 'A') { ?>
                                                                                    <li>
                                                                                        <a href='Afinia-Aire.php'>
                                                                                            <i style="background-color: #003153; color: #FFFFFF;" class="fas fa-bolt"></i>
                                                                                            <span>Afinia / Air-e</span>
                                                                                        </a>
                                                                                    </li>
                                                                                <?php
                                                                                }
                                                                            ?>
                                                                            <ul>
                                                                                <li class="sidebar-dropdown-2">
                                                                                    <a href="#">
                                                                                        <i style="background-color: #003153; color: #FFFFFF;" class="fas fa-layer-group"></i>
                                                                                        <span>Liquidaciones</span>
                                                                                    </a>
                                                                                    <div class="sidebar-submenu-2">
                                                                                        <ul class="nav nav-pills nav-stacked">
                                                                                            <li>
                                                                                                <a href='Otros_Operadores.php'>
                                                                                                    <i style="background-color: #003153; color: #FFFFFF;" class="fas fa-lightbulb"></i>
                                                                                                    <span>Operadores</span>
                                                                                                </a>
                                                                                            </li>
                                                                                            <li>
                                                                                                <a href='Otros_Comercializadores.php'>
                                                                                                    <i style="background-color: #003153; color: #FFFFFF;" class="fas fa-industry"></i>
                                                                                                    <span>Comercializ.</span>
                                                                                                </a>
                                                                                            </li>
                                                                                        </ul>
                                                                                    </div>
                                                                                </li>
                                                                            </ul>
                                                                            <li>
                                                                                <a href='Clientes_Especiales.php'>
                                                                                    <i style="background-color: #003153; color: #FFFFFF;" class="fas fa-user-tie"></i>
                                                                                    <span>Clientes Especiales</span>
                                                                                </a>
                                                                            </li>
                                                                            <li>
                                                                                <a href='Aportes_Municipales.php'>
                                                                                    <i style="background-color: #003153; color: #FFFFFF;" class="fas fa-coins"></i>
                                                                                    <span>Aportes Municipales</span>
                                                                                </a>
                                                                            </li>
                                                                            <?php
                                                                                if ($_SESSION['rol'] == 'A') { ?>
                                                                                    <li>
                                                                                        <a href='OYM_RI.php'>
                                                                                            <i style="background-color: #003153; color: #FFFFFF;" class="fas fa-hand-holding-usd"></i>
                                                                                            <span>OYM - RI</span>
                                                                                        </a>
                                                                                    </li>
                                                                                    <li>
                                                                                        <a href='Actas_Interventoria.php'>
                                                                                            <i style="background-color: #003153; color: #FFFFFF;" class="fas fa-handshake"></i>
                                                                                            <span>Actas Interventoría</span>
                                                                                        </a>
                                                                                    </li>
                                                                                <?php
                                                                                }
                                                                            ?>
                                                                            <li>
                                                                                <a href='Consultas_Generales_FR.php'>
                                                                                    <i style="background-color: #003153; color: #FFFFFF;" class="fas fa-tasks"></i>
                                                                                    <span>Consultas Generales</span>
                                                                                </a>
                                                                            </li>
                                                                        </ul>
                                                                    </div>
                                                                </li>
                                                                <!--<?php
                                                                    if ($_SESSION['rol'] == 'A') { ?>
                                                                        <li class="sidebar-dropdown">
                                                                            <a href="#">
                                                                                <i style="background-color: #003153; color: #FFFFFF;" class="fas fa-file-upload"></i>
                                                                                <span>Cargue Archivos</span>
                                                                            </a>
                                                                            <div class="sidebar-submenu">
                                                                                <ul class="nav nav-pills nav-stacked">
                                                                                    <li>
                                                                                        <a href='Cargue_Facturacion.php'>
                                                                                            <i style="background-color: #003153; color: #FFFFFF;" class="fas fa-file-invoice"></i>
                                                                                            <span>Facturación</span>
                                                                                        </a>
                                                                                    </li>
                                                                                    <li>
                                                                                        <a href='Cargue_Recaudo.php'>
                                                                                            <i style="background-color: #003153; color: #FFFFFF;" class="fas fa-file-invoice-dollar"></i>
                                                                                            <span>Recaudo</span>
                                                                                        </a>
                                                                                    </li>
                                                                                    <li>
                                                                                        <a href='Cargue_Catastro.php'>
                                                                                            <i style="background-color: #003153; color: #FFFFFF;" class="fas fa-file-contract"></i>
                                                                                            <span>Catastro</span>
                                                                                        </a>
                                                                                    </li>
                                                                                    <li>
                                                                                        <a href='Cargue_Novedades.php'>
                                                                                            <i style="background-color: #003153; color: #FFFFFF;" class="fas fa-file-alt"></i>
                                                                                            <span>Novedades</span>
                                                                                        </a>
                                                                                    </li>
                                                                                </ul>
                                                                            </div>
                                                                        </li>
                                                                    <?php
                                                                    }
                                                                ?>
                                                                <?php
                                                                    if ($_SESSION['rol'] == 'A') { ?>
                                                                        <li style="margin-top: 2px;" class="sidebar-dropdown">
                                                                            <a href="#">
                                                                                <i style="background-color: #003153; color: #FFFFFF;" class="fas fa-file-import"></i>
                                                                                <span>Archivos Cargados</span>
                                                                            </a>
                                                                            <div class="sidebar-submenu">
                                                                                <ul class="nav nav-pills nav-stacked">
                                                                                    <li>
                                                                                        <a href='Archivos_Facturacion.php'>
                                                                                            <i style="background-color: #003153; color: #FFFFFF;" class="fas fa-copy"></i>
                                                                                            <span>Facturación</span>
                                                                                        </a>
                                                                                    </li>
                                                                                    <li>
                                                                                        <a href='Archivos_Recaudo.php'>
                                                                                            <i style="background-color: #003153; color: #FFFFFF;" class="fas fa-copy"></i>
                                                                                            <span>Recaudo</span>
                                                                                        </a>
                                                                                    </li>
                                                                                    <li>
                                                                                        <a href='Archivos_Catastro.php'>
                                                                                            <i style="background-color: #003153; color: #FFFFFF;" class="fas fa-copy"></i>
                                                                                            <span>Catastro</span>
                                                                                        </a>
                                                                                    </li>
                                                                                    <li>
                                                                                        <a href='Archivos_Novedades.php'>
                                                                                            <i style="background-color: #003153; color: #FFFFFF;" class="fas fa-copy"></i>
                                                                                            <span>Novedades</span>
                                                                                        </a>
                                                                                    </li>
                                                                                </ul>
                                                                            </div>
                                                                        </li>
                                                                    <?php
                                                                    }
                                                                ?>
                                                                <?php
                                                                    if ($_SESSION['rol'] == 'A') { ?>
                                                                        <li style="margin-top: 2px;" class="sidebar-dropdown">
                                                                            <a href="#">
                                                                                <i style="background-color: #003153; color: #FFFFFF;" class="fas fa-search"></i>
                                                                                <span>Consultas</span>
                                                                            </a>
                                                                            <div class="sidebar-submenu">
                                                                                <ul class="nav nav-pills nav-stacked">
                                                                                    <li>
                                                                                        <a href='Consultas.php'>
                                                                                            <i style="background-color: #003153; color: #FFFFFF;" class='fas fa-filter'></i>
                                                                                            <span>Consultas</span>
                                                                                        </a>
                                                                                    </li>
                                                                                    <li>
                                                                                        <a href='Historiales.php'>
                                                                                            <i style="background-color: #003153; color: #FFFFFF;" class='fas fa-history'></i>
                                                                                            <span>Historiales</span>
                                                                                        </a>
                                                                                    </li>
                                                                                </ul>
                                                                            </div>
                                                                        </li>
                                                                    <?php
                                                                    }
                                                                ?>-->
                                                                <li style="margin-top: 2px;" class="sidebar-dropdown">
                                                                    <a href="#">
                                                                        <i style="background-color: #003153; color: #FFFFFF;" class="fas fa-book-open"></i>
                                                                        <span>Bitacoras</span>
                                                                    </a>
                                                                    <div class="sidebar-submenu">
                                                                        <ul class="nav nav-pills nav-stacked">
                                                                            <li>
                                                                                <a href='Bitacora_Visitas.php'>
                                                                                    <i style="background-color: #003153; color: #FFFFFF;" class='fas fa-globe-americas'></i>
                                                                                    <span>Visitas</span>
                                                                                </a>
                                                                            </li>
                                                                        </ul>
                                                                    </div>
                                                                </li>
                                                                <?php
                                                                    if ($_SESSION['rol'] == 'A' || $_SESSION['rol'] == 'PQR') { ?>
                                                                        <li style="margin-top: 2px;" class="sidebar-dropdown">
                                                                            <a href="#">
                                                                                <i style="background-color: #003153; color: #FFFFFF;" class="fas fa-headset"></i>
                                                                                <span>P.Q.R.</span>
                                                                            </a>
                                                                            <div class="sidebar-submenu">
                                                                                <ul class="nav nav-pills nav-stacked">
                                                                                    <li>
                                                                                        <a href='P_Q_R.php'>
                                                                                            <i style="background-color: #003153; color: #FFFFFF;" class='fas fa-headset'></i>
                                                                                            <span>P.Q.R.</span>
                                                                                        </a>
                                                                                    </li>
                                                                                </ul>
                                                                            </div>
                                                                        </li>
                                                                <?php
                                                                    }
                                                                ?>
                                                                <!--<?php
                                                                    if ($_SESSION['rol'] == 'A') { ?>
                                                                        <li style="margin-top: 2px;" class="sidebar-dropdown">
                                                                            <a href="#">
                                                                                <i style="background-color: #003153; color: #FFFFFF;" class="fas fa-tachometer-alt"></i>
                                                                                <span>Simuladores</span>
                                                                            </a>
                                                                            <div class="sidebar-submenu">
                                                                                <ul class="nav nav-pills nav-stacked">
                                                                                    <li>
                                                                                        <a href='Simulador_ICA.php'>
                                                                                            <i style="background-color: #003153; color: #FFFFFF;" class='fas fa-calculator'></i>
                                                                                            <span>I.C.A.</span>
                                                                                        </a>
                                                                                    </li>
                                                                                </ul>
                                                                            </div>
                                                                        </li>
                                                                    <?php
                                                                    }
                                                                ?>-->
                                                                <?php
                                                                    if ($_SESSION['rol'] == 'A') { ?>
                                                                        <li style="margin-top: 2px;" class="sidebar-dropdown">
                                                                            <a href="#">
                                                                                <i style="background-color: #003153; color: #FFFFFF;" class="fas fa-cog"></i>
                                                                                <span>Configuración</span>
                                                                            </a>
                                                                            <div class="sidebar-submenu">
                                                                                <ul class="nav nav-pills nav-stacked">
                                                                                    <li>
                                                                                        <a href='Seguridad.php'>
                                                                                            <i style="background-color: #003153; color: #FFFFFF;" class='fas fa-shield-alt'></i>
                                                                                            <span>Seguridad</span>
                                                                                        </a>
                                                                                    </li>
                                                                                </ul>
                                                                            </div>
                                                                        </li>
                                                                    <?php
                                                                    }
                                                                ?>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </nav>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-9">
                                        <div class="rightcol">
                                            <h1>Acceso Restringido</h1>
                                            <h2></h2>
                                            <div class="alert alert-danger" role="alert">
                                                <i class="fas fa-hand-paper fa-lg"></i><strong> &nbsp;&nbsp;La pagina que has solicitado no puede mostrarse.</strong>
                                            </div>
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
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="Javascript/bootstrap.min.js"></script>
    <script src="Javascript/menu.js"></script>
    <script>
        $(document).ready(function() {
            $("#menu_home").tooltip({
                container : "body",
                placement : "top"
            });
        });
    </script>
</html>