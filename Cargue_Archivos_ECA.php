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
        <title>AGM - Cargue Archivos</title>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
        <link rel="icon" href="Images/agm_desarrollos_2_icon.png" type="image/x-icon" />
        <link rel="stylesheet" href="Css/AGM_Style.css" />
        <link rel="stylesheet" href="Css/bootstrap.min.css" />
        <link rel="stylesheet" href="Css/menu_style.css" />
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.2.0/css/all.css" />
        <link rel="stylesheet" href="Css/bootstrap-datetimepicker.css">
    </head>
    <body>
        <div class="wrapper">
            <?php include("Top Pages/Top_Page_Electricaribe.php");?>
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
                                                                        <i style="background-color: #003153; color: #FFFFFF;" class="fas fa-file-upload"></i>
                                                                        <span>Cargue Archivos</span>
                                                                    </a>
                                                                    <div style="display: block;" class="sidebar-submenu">
                                                                        <ul class="nav nav-pills nav-stacked">
                                                                            <!--<li>
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
                                                                                <a href='Cargue_Refacturacion.php'>
                                                                                    <i style="background-color: #003153; color: #FFFFFF;" class="fas fa-file-signature"></i>
                                                                                    <span>Refacturación</span>
                                                                                </a>
                                                                            </li>
                                                                            <li>
                                                                                <a href='Cargue_Cartera_Fallida.php'>
                                                                                    <i style="background-color: #003153; color: #FFFFFF;" class="fas fa-receipt"></i>
                                                                                    <span>Cartera Fallida</span>
                                                                                </a>
                                                                            </li>
                                                                            <li>
                                                                                <a href='Cargue_Novedades.php'>
                                                                                    <i style="background-color: #003153; color: #FFFFFF;" class="fas fa-file-alt"></i>
                                                                                    <span>Novedades</span>
                                                                                </a>
                                                                            </li>-->
                                                                            <li>
                                                                                <a href='Cargue_Liquidaciones.php'>
                                                                                    <i style="background-color: #003153; color: #FFFFFF;" class="fas fa-file-upload"></i>
                                                                                    <span>Liquidaciones</span>
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
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="Javascript/bootstrap.min.js"></script>
    <script src="Javascript/moment-with-locales.js"></script>
    <script src="Javascript/bootstrap-datetimepicker.js"></script>
    <script src="Javascript/jquery.twbsPagination.js"></script>
    <script src="Javascript/menu.js"></script>
    <script>
        $(document).ready(function() {
            $("#menu_home").tooltip({
                container : "body",
                placement : "top"
            });
            $("#menu_electricaribe").tooltip({
                container : "body",
                placement : "top"
            });
        });
    </script>
</html>