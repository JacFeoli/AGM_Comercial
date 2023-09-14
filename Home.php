<?php
    session_start();
    require_once('Includes/Config.php');
    if ($_SESSION['timeout'] + 60 * 60 < time()) {
        session_unset();
        session_destroy();
        $ruta = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
        header("Location:$ruta/Login.php");
    } else {
        $_SESSION['timeout'] = time();
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <title>AGM - Home</title>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
        <link rel="icon" href="Images/agm_desarrollos_2_icon.png" type="image/x-icon" />
        <link rel="stylesheet" href="Css/AGM_Style.css" />
        <link rel="stylesheet" href="Css/bootstrap.min.css" />
        <link rel="stylesheet" href="Css/menu_style.css" />
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.2.0/css/all.css" />
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
                                                                <?php
                                                                    if ($_SESSION['rol'] != 'PQR') { ?>
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
                                                                                            <span>Fact. Municipio</span>
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
                                                                    <?php
                                                                    }
                                                                ?>
                                                                <?php
                                                                    if ($_SESSION['rol'] != 'PQR') { ?>
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
                                                                    }
                                                                ?>
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
                                                                    if ($_SESSION['id_user'] == '1' || $_SESSION['id_user'] == '47') { ?>
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
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
    <script src="Javascript/bootstrap.min.js"></script>
    <script src="Javascript/menu.js"></script>
    <!--<script src="Javascript/font-pro.js"></script>-->
    <script>
        $(document).ready(function() {
            $("#alert-login").fadeTo(3000, 500).fadeOut(500, function() {
                $("#alert-login").fadeOut();
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            $("#menu_home").tooltip({
                container : "body",
                placement : "top"
            });
        });
    </script>
</html>