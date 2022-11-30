<section>
    <div class="container">
        <div class="row">
            <div class="col-lg-4">
                <div class="juridica-logo">
                    <?php
                    $id_departamento = $_SESSION['id_departamento'];
                    $id_municipio = $_SESSION['id_municipio'];
                    switch ($id_departamento) {
                        case '41':
                            switch ($id_municipio) {
                                case '551': ?>
                                    <img style="margin-top: 9px;" src="Images/Logos/Logo SEMAPP.jpg" width="180" height="76" />
                                <?php
                                    break;
                                case '1': ?>
                                    <img style="margin-top: 9px;" src="Images/Logos/Logo ESIP.png" width="180" height="76" />
                                <?php
                                    break;
                            }
                            break;
                        case '54':
                            switch ($id_municipio) {
                                case '874': ?>
                                    <img style="margin-top: 9px;" src="Images/Logos/Logo American Lighting.png" width="101" height="76" />
                                <?php
                                    break;
                                case '498': ?>
                                    <img style="margin-top: 9px;" src="Images/AGM Desarrollos.jpg" width="101" height="76" />
                                <?php
                                    break;
                                case '1': ?>
                                    <img style="margin-top: 9px;" src="Images/Logos/Logo SJC.png" width="70" height="76" />
                                <?php
                                    break;
                                case '518': ?>
                                    <img style="margin-top: 9px;" src="Images/AGM Desarrollos.jpg" width="70" height="76" />
                                <?php
                                    break;
                                case '553': ?>
                                    <img style="margin-top: 9px;" src="Images/AGM Desarrollos.jpg" width="70" height="76" />
                            <?php
                                    break;
                            }
                            break;
                        case '8': ?>
                            <img style="margin-top: 9px;" src="Images/Logos/Logo American Lighting.png" width="101" height="76" />
                        <?php
                            break;
                        default: ?>
                            <img style="margin-top: 9px;" src="Images/AGM Desarrollos.jpg" width="91" height="76" />
                            <!--<img style="margin-top: 9px;" src="Images/logo-nombre-1.png" />-->
                    <?php
                            break;
                    }
                    ?>
                </div>
            </div>
            <div class="col-lg-4">
                <?php
                if ($_SESSION['login'] != 0) { ?>
                    <div style="font-family: 'Cabin'; font-size: 14px; margin-top: 13px; border-color: #3C763D;" id="alert-login" class="alert alert-success alert-dismissible text-center" role="alert">
                        Inicio de Sesión Correctamente. <i class="fas fa-check" aria-hidden="true"></i>
                    </div>
                <?php
                    $_SESSION['login'] = 0;
                }
                ?>
            </div>
            <div class="col-lg-4">
                <div class="header">
                    <div class="header-img">
                        <img style="border: none;" width="151" height="65" />
                    </div>
                    <div class="header-user">
                        Bienvenido<span><?php echo $_SESSION['fullname']; ?></span><a class="logout" href="Logout.php">[Salir]</a>
                    </div>
                    <div class="clear"></div>
                </div>
            </div>
        </div>
    </div>
</section>
<section>
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <nav class="navbar navbar-static-top" style="border-top-left-radius: 5px; border-top-right-radius: 5px; margin-bottom: 0px;">
                    <div class="container-fluid">
                        <div class="navbar-header">
                            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
                                <span style="background-color: #FFFFFF;" class="icon-bar"></span>
                                <span style="background-color: #FFFFFF;" class="icon-bar"></span>
                                <span style="background-color: #FFFFFF;" class="icon-bar"></span>
                            </button>
                        </div>
                        <div class="collapse navbar-collapse" id="myNavbar">
                            <ul class="nav navbar-nav">
                                <li id="menu_home" data-toogle="tooltip" title="HOME"><a href="Home.php"><i style="font-size: 33px; padding: 5px 8px;" class="fas fa-home"></i></a></li>
                            </ul>
                        </div>
                    </div>
                </nav>
            </div>
        </div>
    </div>
</section>