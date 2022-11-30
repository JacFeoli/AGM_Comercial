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
            $nombre_contribuyente = strtoupper(trim($_POST['nombre_contribuyente']));
            $nit_contribuyente = trim($_POST['nit_contribuyente']);
            $departamento = $_POST['id_departamento'];
            $municipio = $_POST['id_municipio'];
            $direccion_contribuyente = strtoupper($_POST['direccion_contribuyente']);
            $correo_electronico = strtolower(trim($_POST['correo_electronico']));
            $sector_contribuyente = strtoupper(trim($_POST['sector_contribuyente']));
            $responsable_pago = strtoupper(trim($_POST['responsable_pago']));
            $telefono_resp_pago = $_POST['telefono_resp_pago'];
            $tipo_facturacion = $_POST['tipo_facturacion'];
            $tarifa = $_POST['tarifa'];
            $acuerdo_mcpal = $_POST['acuerdo_mcpal'];
            mysqli_query($connection, "UPDATE contribuyentes_2
                                          SET NOMBRE = '" . $nombre_contribuyente . "', "
                                          . " NIT_CONTRIBUYENTE = '" . $nit_contribuyente . "', "
                                          . " ID_DEPARTAMENTO = '" . $departamento . "', "
                                          . " ID_MUNICIPIO = '" . $municipio . "', "
                                          . " DIRECCION_CONTRIBUYENTE = '" . $direccion_contribuyente . "', "
                                          . " CORREO_ELECTRONICO = '" . $correo_electronico . "', "
                                          . " SECTOR_CONTRIBUYENTE = '" . $sector_contribuyente . "', "
                                          . " RESPONSABLE_PAGO = '" . $responsable_pago . "', "
                                          . " TELEFONO_RESP_PAGO = '" . $telefono_resp_pago . "', "
                                          . " ID_TIPO_FACTURACION = '" . $tipo_facturacion . "', "
                                          . " TARIFA = '" . $tarifa . "', "
                                          . " ACUERDO_MCPAL = '" . $acuerdo_mcpal . "' "
                                    . " WHERE ID_CONTRIBUYENTE = " . $_GET['editar']);
        } else {
            if (isset($_GET['eliminar'])) {
                mysqli_query($connection, "DELETE FROM contribuyentes_2 WHERE ID_CONTRIBUYENTE = " . $_GET['eliminar']);
            } else {
                $nombre_contribuyente = strtoupper(trim($_POST['nombre_contribuyente']));
                $nit_contribuyente = trim($_POST['nit_contribuyente']);
                $departamento = $_POST['departamento'];
                $municipio = $_POST['municipio'];
                $direccion_contribuyente = $_POST['direccion_contribuyente'];
                $correo_electronico = $_POST['correo_electronico'];
                $sector_contribuyente = strtoupper(trim($_POST['sector_contribuyente']));
                $responsable_pago = $_POST['responsable_pago'];
                $telefono_resp_pago = $_POST['telefono_resp_pago'];
                $tipo_facturacion = $_POST['tipo_facturacion'];
                $tarifa = $_POST['tarifa'];
                $acuerdo_mcpal = $_POST['acuerdo_mcpal'];
                mysqli_query($connection, "INSERT INTO contribuyentes_2 (NOMBRE, NIT_CONTRIBUYENTE, ID_DEPARTAMENTO, ID_MUNICIPIO,
                                                                         DIRECCION_CONTRIBUYENTE, CORREO_ELECTRONICO, SECTOR_CONTRIBUYENTE,
                                                                         RESPONSABLE_PAGO, TELEFONO_RESP_PAGO, ID_TIPO_FACTURACION,
                                                                         TARIFA, ACUERDO_MCPAL)
                                                VALUES ('" . $nombre_contribuyente . "', '" . $nit_contribuyente .
                                                        "', '" . $departamento . "', '" . $municipio .
                                                        "', '" . $direccion_contribuyente . "', '" . $correo_electronico .
                                                        "', '" . $sector_contribuyente . "', '" . $responsable_pago .
                                                        "', '" . $telefono_resp_pago . "', '" . $tipo_facturacion .
                                                        "', '" . $tarifa . "', '" . $acuerdo_mcpal . "')");
            }
        }
        echo "<script>";
            echo "document.location.href = '../Admin_Contribuyentes.php'";
        echo "</script>";
    }
?>