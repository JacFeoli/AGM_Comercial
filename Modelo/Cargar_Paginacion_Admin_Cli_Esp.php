<?php
    session_start();
    require_once('../Includes/Config.php');
    require_once('../Includes/Paginacion_Admin_Cli_Esp.php');
    if ($_SESSION['timeout'] + 60 * 60 < time()) {
        session_unset();
        session_destroy();
        $ruta = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
        header("Location:$ruta/Login.php");
    } else {
        $_SESSION['timeout'] = time();
        if ($_POST['sw'] == 1) {
            if (isset($_POST['busqueda_cliente']) != "") {
                if ($_POST['busqueda_cliente'] != "") {
                    $busqueda_cliente = " WHERE CE.CLIENTE_ESPECIAL LIKE '%" . $_POST['busqueda_cliente'] . "%' ";
                } else {
                    $busqueda_cliente = " WHERE CE.CLIENTE_ESPECIAL <> ''";
                }
            } else {
                $busqueda_cliente = " WHERE CE.CLIENTE_ESPECIAL <> ''";
            }
        } else {
            $busqueda_cliente = " WHERE CE.CLIENTE_ESPECIAL <> ''";
        }
        $query_cliente = mysqli_query($connection, "SELECT *
                                                      FROM clientes_especiales_2 CE,
                                                           departamentos_visitas_2 DEPT,
                                                           municipios_visitas_2 MUN
                                                      $busqueda_cliente
                                                       AND CE.ID_COD_DPTO = DEPT.ID_DEPARTAMENTO
                                                       AND CE.ID_COD_MPIO = MUN.ID_MUNICIPIO
                                                       AND DEPT.ID_DEPARTAMENTO = MUN.ID_DEPARTAMENTO
                                                     ORDER BY DEPT.NOMBRE, MUN.NOMBRE");
        $count_cliente = mysqli_num_rows($query_cliente);
        $info_pagination = array();
        if ($count_cliente > 0) {
            $paginacion_count = getPagination($count_cliente);
            $info_pagination[0] = $paginacion_count;
        } else {
            $info_pagination[0] = 1;
        }
        if ($_POST['sw'] == 1) {
            $info_pagination[1] = $_POST['busqueda_cliente'];
        } else {
            $info_pagination[1] = "";
        }
        echo json_encode($info_pagination);
        exit();
    }
?>