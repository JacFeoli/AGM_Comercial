<?php
    session_start();
    require_once('../Includes/Config.php');
    require_once('../Includes/Paginacion_Reca_Mun.php');
    if ($_SESSION['timeout'] + 60 * 60 < time()) {
        session_unset();
        session_destroy();
        $ruta = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
        header("Location:$ruta/Login.php");
    } else {
        $_SESSION['timeout'] = time();
        if ($_POST['sw'] == 1) {
            if (isset($_POST['busqueda_recaudo']) != "") {
                if ($_POST['busqueda_recaudo'] != "") {
                    $busqueda_recaudo = " WHERE MUN.NOMBRE LIKE '%" . $_POST['busqueda_recaudo'] . "%' ";
                } else {
                    $busqueda_recaudo = " WHERE MUN.NOMBRE <> ''";
                }
            } else {
                $busqueda_recaudo = " WHERE MUN.NOMBRE <> ''";
            }
        } else {
            $busqueda_recaudo = " WHERE MUN.NOMBRE <> ''";
        }
        $page = $_POST['page'];
        if ($page == 1) {
            $pageLimit = 0;
        } else {
            $pageLimit = PAGE_PER_RECA_MUN * ($page - 1);
        }
        $query_reca_mun = mysqli_query($connection, "SELECT DEPT.NOMBRE AS DEPARTAMENTO, MUN.NOMBRE AS MUNICIPIO,
                                                            RM.ID_RECAUDO, FM.ID_FACTURACION,
                                                            FM.CONSECUTIVO_FACT,
                                                            FM.VALOR_FACTURA, RM.VALOR_RECAUDO, RM.ESTADO_RECAUDO
                                                       FROM recaudo_municipales_2 RM,
                                                            facturacion_municipales_2 FM, departamentos_visitas_2 DEPT,
                                                            municipios_visitas_2 MUN
                                                       $busqueda_recaudo
                                                        AND RM.ID_FACTURACION = FM.ID_FACTURACION
                                                        AND FM.ID_COD_DPTO = DEPT.ID_DEPARTAMENTO
                                                        AND FM.ID_COD_MPIO = MUN.ID_MUNICIPIO
                                                        AND DEPT.ID_DEPARTAMENTO = MUN.ID_DEPARTAMENTO
                                                      ORDER BY DEPT.NOMBRE, MUN.NOMBRE
                                                      LIMIT " . $pageLimit . ", " . PAGE_PER_RECA_MUN);
        $table = "";
        while ($row_reca_mun = mysqli_fetch_assoc($query_reca_mun)) {
            $table = $table . "<tr>";
                switch ($row_reca_mun['ESTADO_RECAUDO']) {
                    case "1":
                        $table = $table . "<td style='vertical-align:middle;'><span style='font-size: 11px; background-color: #0676C0;' class='label label-success'><b>E</b></span></td>";
                        break;
                    case "2":
                        $table = $table . "<td style='vertical-align:middle;'><span style='font-size: 11px; background-color: #CC3300;' class='label label-success'><b>PE</b></span></td>";
                        break;
                    case "3":
                        $table = $table . "<td style='vertical-align:middle;'><span style='font-size: 11px; background-color: #4D7B52;' class='label label-success'><b>R</b></span></td>";
                        break;
                    case "4":
                        $table = $table . "<td style='vertical-align:middle;'><span style='font-size: 11px; background-color: #00A328;' class='label label-success'><b>P</b></span></td>";
                        break;
                    case "5":
                        $table = $table . "<td style='vertical-align:middle;'><span style='font-size: 11px; background-color: #66C77E;' class='label label-success'><b>PP</b></span></td>";
                        break;
                    case "6":
                        $table = $table . "<td style='vertical-align:middle;'><span style='font-size: 11px; background-color: #F6ED0E; color: #323232;' class='label label-success'><b>PA</b></span></td>";
                        break;
                }
                $table = $table . "<td style='vertical-align:middle;'>" . $row_reca_mun['DEPARTAMENTO'] . "</td>";
                $table = $table . "<td style='vertical-align:middle;'>" . $row_reca_mun['MUNICIPIO'] . "</td>";
                $table = $table . "<td style='vertical-align:middle;'>" . $row_reca_mun['CONSECUTIVO_FACT'] . "</td>";
                //$table = $table . "<td style='vertical-align:middle;'>" . "<b style='font-size: 12px;'>$</b> " . number_format($row_reca_esp['VALOR_FACTURA'], 0, ',', '.') . "</td>";
                $table = $table . "<td style='vertical-align:middle;'>" . "<b style='font-size: 12px;'>$</b> " . number_format($row_reca_mun['VALOR_RECAUDO'], 0, ',', '.') . "</td>";
                $table = $table . "<td style='vertical-align:middle;'><a href='Recaudo_Municipales.php?id_reca_municipal_editar=" . $row_reca_mun['ID_RECAUDO'] . "'><button><img src='Images/search.png' title='Detalle' width='16' height='16' /></button></a></td>";
                $table = $table . "<td style='vertical-align:middle;'><a href='Recaudo_Municipales.php?id_reca_municipal_eliminar=" . $row_reca_mun['ID_RECAUDO'] . "'><button><img src='Images/gnome_edit_delete.png' title='Eliminar' width='16' height='16'/></button></a></td>";
            $table = $table . "</tr>";
        }
        $info_resultado = array();
        $info_resultado[0] = $table;
        echo json_encode($info_resultado);
        exit();
    }
?>