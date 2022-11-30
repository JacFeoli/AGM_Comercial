<section>
    <div class="container">
        <div class="row">
            <div class="col-lg-4">
                <div class="juridica-logo">
                    <?php
                        $id_departamento = $_SESSION['id_departamento'];
                        switch ($id_departamento) {
                            case '41': ?>
                                <img style="margin-top: 9px;" src="Images/Logos/Logo ESIP.png" width="180" height="76" />
                            <?php
                                break;
                            case '54': ?>
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

            </div>
            <div class="col-lg-4">
                <div class="header">
                    <div class="header-img">
                        <img width="151" height="65" />
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
                                <li id='menu_electricaribe' data-toogle='tooltip' title='AFINIA / AIR-E'><a href="Afinia-Aire.php"><i style="font-size: 33px; padding: 5px 15px;" class="fas fa-bolt"></i></a></li>
                            </ul>
                        </div>
                    </div>
                </nav>
            </div>
        </div>
    </div>
</section>