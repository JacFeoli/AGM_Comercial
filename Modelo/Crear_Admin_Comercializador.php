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
            $nombre_comercializador = strtoupper(trim($_POST['nombre_comercializador']));
            $nit_comercializador = trim($_POST['nit_comercializador']);
            $representante_legal = strtoupper(trim($_POST['representante_legal']));
            $direccion_comercializador = strtoupper(trim($_POST['direccion_comercializador']));
            $correo_electronico = strtolower(trim($_POST['correo_electronico']));
            $responsable_acargo = strtoupper(trim($_POST['responsable_acargo']));
            $cargo_responsable = strtoupper(trim($_POST['cargo_responsable']));
            $telefono_responsable = trim($_POST['telefono_responsable']);
            $correo_electronico_responsable = strtolower(trim($_POST['correo_electronico_responsable']));
            mysqli_query($connection, "UPDATE comercializadores_2
                                          SET NOMBRE = '" . $nombre_comercializador . "', "
                                          . " NIT_COMERCIALIZADOR = '" . $nit_comercializador . "', "
                                          . " REPR_LEGAL = '" . $representante_legal . "', "
                                          . " DIRECCION_COMERCIALIZADOR = '" . $direccion_comercializador . "', "
                                          . " CORREO_ELECTRONICO = '" . $correo_electronico . "', "
                                          . " RESPONSABLE_ACARGO = '" . $responsable_acargo . "', "
                                          . " CARGO_RESPONSABLE = '" . $cargo_responsable . "', "
                                          . " TELEFONO_RESPONSABLE = '" . $telefono_responsable . "', "
                                          . " CORREO_RESPONSABLE = '" . $correo_electronico_responsable . "' "
                                    . " WHERE ID_COMERCIALIZADOR = " . $_GET['editar']);
        } else {
            if (isset($_GET['eliminar'])) {
                mysqli_query($connection, "DELETE FROM comercializadores_2 WHERE ID_COMERCIALIZADOR = " . $_GET['eliminar']);
            } else {
                $nombre_comercializador = strtoupper(trim($_POST['nombre_comercializador']));
                $nit_comercializador = trim($_POST['nit_comercializador']);
                $representante_legal = strtoupper(trim($_POST['representante_legal']));
                $direccion_comercializador = strtoupper(trim($_POST['direccion_comercializador']));
                $correo_electronico = strtolower(trim($_POST['correo_electronico']));
                $responsable_acargo = strtoupper(trim($_POST['responsable_acargo']));
                $cargo_responsable = strtoupper(trim($_POST['cargo_responsable']));
                $telefono_responsable = trim($_POST['telefono_responsable']);
                $correo_electronico_responsable = strtolower(trim($_POST['correo_electronico_responsable']));
                mysqli_query($connection, "INSERT INTO comercializadores_2 (NOMBRE, NIT_COMERCIALIZADOR, REPR_LEGAL, DIRECCION_COMERCIALIZADOR, 
                                                                            CORREO_ELECTRONICO, RESPONSABLE_ACARGO, CARGO_RESPONSABLE, 
                                                                            TELEFONO_RESPONSABLE, CORREO_RESPONSABLE)
                                                                    VALUES ('" . $nombre_comercializador . "', '" .
                                                                                 $nit_comercializador . "', '" .
                                                                                 $representante_legal . "', '" .
                                                                                 $direccion_comercializador . "', '" .
                                                                                 $correo_electronico . "', '" .
                                                                                 $responsable_acargo . "', '" .
                                                                                 $cargo_responsable . "', '" .
                                                                                 $telefono_responsable . "', '" .
                                                                                 $correo_electronico_responsable . "')");
            }
        }
        echo "<script>";
            echo "document.location.href = '../Admin_Comercializadores.php'";
        echo "</script>";
    }
?>