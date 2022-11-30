<?php
    session_start();
    require_once('../Includes/Config.php');
    if ($_SESSION['timeout'] + 60 * 60 < time()) {
        session_unset();
        session_destroy();
        $ruta = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
        header("Location:$ruta/Login.php");
    } else {
        $_SESSION['timeout'] = time();
        if (isset($_GET['editar'])) {
            $departamento = $_POST['id_departamento'];
            $municipio = $_POST['id_municipio'];
            $nombre_alcaldia = strtoupper(trim($_POST['nombre_alcaldia']));
            $nit_alcaldia = trim($_POST['nit_alcaldia']);
            $cuenta_fiducia = $_POST['cuenta_fiducia'];
            $banco_fiducia = strtoupper(trim($_POST['banco_fiducia']));
            $nombre_fiducia = strtoupper(trim($_POST['nombre_fiducia']));
            $nit_fiducia = trim($_POST['nit_fiducia']);
            $direccion_alcaldia = strtoupper(trim($_POST['direccion_alcaldia']));
            $telefono_alcaldia = trim($_POST['telefono_alcaldia']);
            $correo_electronico = strtolower(trim($_POST['correo_electronico']));
            $nombre_sec_hacienda = strtoupper(trim($_POST['nombre_sec_hacienda']));
            $cuenta_destino = $_POST['cuenta_destino'];
            $banco_destino = strtoupper(trim($_POST['banco_destino']));
            $concepto_aporte = strtoupper(trim($_POST['concepto_aporte']));
            $valor_concepto = str_replace(',', '', $_POST['valor_concepto']);
            $valor_cartera = str_replace(',', '', $_POST['valor_cartera']);
            mysqli_query($connection, "UPDATE alcaldias_2
                                          SET ID_COD_DPTO = '" . $departamento . "', "
                                          . " ID_COD_MPIO = '" . $municipio . "', "
                                          . " NOMBRE = '" . $nombre_alcaldia . "', "
                                          . " NIT_ALCALDIA = '" . $nit_alcaldia . "', "
                                          . " CUENTA_FIDUCIA = '" . $cuenta_fiducia . "', "
                                          . " BANCO_FIDUCIA = '" . $banco_fiducia . "', "
                                          . " NOMBRE_FIDUCIA = '" . $nombre_fiducia . "', "
                                          . " NIT_FIDUCIA = '" . $nit_fiducia . "', "
                                          . " DIRECCION_ALCALDIA = '" . $direccion_alcaldia . "', "
                                          . " TELEFONO_ALCALDIA = '" . $telefono_alcaldia . "', "
                                          . " CORREO_ELECTRONICO_ALCALDIA = '" . $correo_electronico . "', "
                                          . " NOMBRE_SEC_HACIENDA = '" . $nombre_sec_hacienda . "', "
                                          . " CUENTA_DESTINO = '" . $cuenta_destino . "', "
                                          . " BANCO_DESTINO = '" . $banco_destino . "', "
                                          . " CONCEPTO_APORTE = '" . $concepto_aporte . "', "
                                          . " VALOR_CONCEPTO = '" . $valor_concepto . "', "
                                          . " VALOR_CARTERA = '" . $valor_cartera . "' "
                                    . " WHERE ID_ALCALDIA = " . $_GET['editar']);
        } else {
            if (isset($_GET['eliminar'])) {
                mysqli_query($connection, "DELETE FROM alcaldias_2 WHERE ID_ALCALDIA = " . $_GET['eliminar']);
            } else {
                $departamento = $_POST['departamento'];
                $municipio = $_POST['municipio'];
                $nombre_alcaldia = strtoupper(trim($_POST['nombre_alcaldia']));
                $nit_alcaldia = trim($_POST['nit_alcaldia']);
                $cuenta_fiducia = $_POST['cuenta_fiducia'];
                $banco_fiducia = strtoupper(trim($_POST['banco_fiducia']));
                $nombre_fiducia = strtoupper(trim($_POST['nombre_fiducia']));
                $nit_fiducia = trim($_POST['nit_fiducia']);
                $direccion_alcaldia = strtoupper(trim($_POST['direccion_alcaldia']));
                $telefono_alcaldia = trim($_POST['telefono_alcaldia']);
                $correo_electronico = strtolower(trim($_POST['correo_electronico']));
                $nombre_sec_hacienda = strtoupper(trim($_POST['nombre_sec_hacienda']));
                $cuenta_destino = $_POST['cuenta_destino'];
                $banco_destino = strtoupper(trim($_POST['banco_destino']));
                $concepto_aporte = strtoupper(trim($_POST['concepto_aporte']));
                $valor_concepto = $_POST['valor_concepto'];
                $valor_cartera = $_POST['valor_cartera'];
                mysqli_query($connection, "INSERT INTO alcaldias_2 (ID_COD_DPTO, ID_COD_MPIO, NOMBRE, NIT_ALCALDIA, CUENTA_FIDUCIA,
                                                                    BANCO_FIDUCIA, NOMBRE_FIDUCIA, NIT_FIDUCIA, DIRECCION_ALCALDIA,
                                                                    TELEFONO_ALCALDIA, CORREO_ELECTRONICO_ALCALDIA, NOMBRE_SEC_HACIENDA,
                                                                    CUENTA_DESTINO, BANCO_DESTINO, CONCEPTO_APORTE, VALOR_CONCEPTO, VALOR_CARTERA)
                                                            VALUES ('" . $departamento . "', '" . $municipio .
                                                                    "', '" . $nombre_alcaldia . "', '" . $nit_alcaldia .
                                                                    "', '" . $cuenta_fiducia . "', '" . $banco_fiducia .
                                                                    "', '" . $nombre_fiducia . "', '" . $nit_fiducia .
                                                                    "', '" . $direccion_alcaldia . "', '" . $telefono_alcaldia .
                                                                    "', '" . $correo_electronico . "', '" . $nombre_sec_hacienda .
                                                                    "', '" . $cuenta_destino . "', '" . $banco_destino .
                                                                    "', '" . $concepto_aporte . "', '" . $valor_concepto .
                                                                    "', '" . $valor_cartera . "')");
            }
        }
        echo "<script>";
            echo "document.location.href = '../Admin_Alcaldias.php'";
        echo "</script>";
    }
?>