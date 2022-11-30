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
            $nic_cliente = $_POST['nic_cliente'];
            $nombre_cliente = strtoupper($_POST['nombre_cliente']);
            $departamento = $_POST['id_departamento'];
            $municipio = $_POST['id_municipio'];
            $valor_importe = str_replace(',', '', $_POST['valor_importe']);
            $concepto = $_POST['concepto'];
            $tipo_servicio = $_POST['tipo_servicio'];
            $fecha_actualizacion = date('Y-m-d H:i:s');
            $id_usuario = $_SESSION['id_user'];
            mysqli_query($connection, "UPDATE clientes_especiales_2
                                          SET NIC = '" . $nic_cliente . "', "
                                          . " VALOR_IMPORTE = '" . $valor_importe . "', "
                                          . " CONCEPTO = '" . $concepto . "', "
                                          . " TIPO_SERVICIO = '" . $tipo_servicio . "', "
                                          . " FECHA_ANULACION = '2999-12-31', "
                                          . " CLIENTE_ESPECIAL = '" . $nombre_cliente . "', "
                                          . " ID_COD_DPTO = '" . $departamento . "', "
                                          . " ID_COD_MPIO = '" . $municipio . "', "
                                          . " FECHA_ACTUALIZACION = '" . $fecha_actualizacion . "', "
                                          . " ID_USUARIO = '" . $id_usuario. "' "
                                    . " WHERE ID_TABLA = " . $_GET['editar']);
            echo "<script>";
                echo "document.location.href = '../Admin_Especiales.php'";
            echo "</script>";
        } else {
            if (isset($_GET['eliminar'])) {
                mysqli_query($connection, "DELETE FROM clientes_especiales_2 WHERE ID_TABLA = " . $_GET['eliminar']);
                echo "<script>";
                    echo "document.location.href = '../Admin_Especiales.php'";
                echo "</script>";
            } else {
                $nic_cliente = $_POST['nic_cliente'];
                $nombre_cliente = strtoupper($_POST['nombre_cliente']);
                $departamento = $_POST['departamento'];
                $municipio = $_POST['municipio'];
                $valor_importe = str_replace(',', '', $_POST['valor_importe']);
                $concepto = $_POST['concepto'];
                $tipo_servicio = $_POST['tipo_servicio'];
                $fecha_creacion = date('Y-m-d H:i:s');
                $fecha_actualizacion = '0000-00-00 00:00:00';
                $id_usuario = $_SESSION['id_user'];
                mysqli_query($connection, "INSERT INTO clientes_especiales_2 (NIC, VALOR_IMPORTE, CONCEPTO, TIPO_SERVICIO,
                                                                              FECHA_ANULACION, CLIENTE_ESPECIAL, ID_COD_DPTO, ID_COD_MPIO,
                                                                              FECHA_ACTUALIZACION, FECHA_CREACION, ID_USUARIO)
                                                                      VALUES ('" . $nic_cliente . "', '" . $valor_importe .
                                                                              "', '" . $concepto . "', '" . $tipo_servicio .
                                                                              "', '2999-12-31', '" . $nombre_cliente .
                                                                              "', '" . $departamento . "', '" . $municipio .
                                                                              "', '" . $fecha_actualizacion . "', '" . $fecha_creacion .
                                                                              "', '" . $id_usuario . "')");
                $query_select_max_id_tabla = mysqli_query($connection, "SELECT MAX(ID_TABLA) AS ID_TABLA "
                                                                           . "  FROM clientes_especiales_2 ");
                $row_max_id_tabla = mysqli_fetch_array($query_select_max_id_tabla);
                echo $row_max_id_tabla['ID_TABLA'];
            }
        }
    }
?>