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
        $page = $_POST['page'];
        if ($page == 1) {
            $pageLimit = 0;
        } else {
            $pageLimit = PAGE_PER_CLI_ESP * ($page - 1);
        }
        $query_cli_esp = mysqli_query($connection, "SELECT DEPT.NOMBRE AS DEPARTAMENTO, MUN.NOMBRE AS MUNICIPIO,
                                                           CE.CLIENTE_ESPECIAL AS CLIENTE_ESPECIAL, CE.VALOR_IMPORTE,
                                                           CE.NIC, CE.ID_TABLA
                                                      FROM clientes_especiales_2 CE, departamentos_visitas_2 DEPT,
                                                           municipios_visitas_2 MUN
                                                      $busqueda_cliente
                                                       AND CE.ID_COD_DPTO = DEPT.ID_DEPARTAMENTO
                                                       AND CE.ID_COD_MPIO = MUN.ID_MUNICIPIO
                                                       AND DEPT.ID_DEPARTAMENTO = MUN.ID_DEPARTAMENTO
                                                     ORDER BY DEPT.NOMBRE, MUN.NOMBRE
                                                     LIMIT " . $pageLimit . ", " . PAGE_PER_CLI_ESP);
        $table = "";
        while ($row_cli_esp = mysqli_fetch_assoc($query_cli_esp)) {
            $table = $table . "<tr>";
                $table = $table . "<td style='vertical-align:middle;'>" . $row_cli_esp['NIC'] . "</td>";
                $table = $table . "<td style='vertical-align:middle;'>" . $row_cli_esp['CLIENTE_ESPECIAL'] . "</td>";
                $table = $table . "<td style='vertical-align:middle;'>" . $row_cli_esp['DEPARTAMENTO'] . "</td>";
                $table = $table . "<td style='vertical-align:middle;'>" . $row_cli_esp['MUNICIPIO'] . "</td>";
                $table = $table . "<td style='vertical-align:middle;'>" . "<b style='font-size: 12px;'>$</b> " . number_format($row_cli_esp['VALOR_IMPORTE'], 0, ',', '.') . "</td>";
                $table = $table . "<td style='vertical-align:middle;'><a href='Admin_Especiales.php?id_cli_especial_editar=" . $row_cli_esp['ID_TABLA'] . "'><button><img src='Images/search.png' title='Detalle' width='16' height='16' /></button></a></td>";
                $table = $table . "<td style='vertical-align:middle;'><a href='Admin_Especiales.php?id_cli_especial_eliminar=" . $row_cli_esp['ID_TABLA'] . "'><button><img src='Images/gnome_edit_delete.png' title='Eliminar' width='16' height='16'/></button></a></td>";
            $table = $table . "</tr>";
        }
        $info_resultado = array();
        $info_resultado[0] = $table;
        echo json_encode($info_resultado);
        exit();
    }
?>