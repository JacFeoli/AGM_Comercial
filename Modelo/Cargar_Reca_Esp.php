<?php
    session_start();
    require_once('../Includes/Config.php');
    require_once('../Includes/Paginacion_Reca_Esp.php');
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
                    $busqueda_recaudo = " WHERE C.NOMBRE LIKE '%" . $_POST['busqueda_recaudo'] . "%' ";
                } else {
                    $busqueda_recaudo = " WHERE C.NOMBRE <> ''";
                }
            } else {
                $busqueda_recaudo = " WHERE C.NOMBRE <> ''";
            }
        } else {
            $busqueda_recaudo = " WHERE C.NOMBRE <> ''";
        }
        $page = $_POST['page'];
        if ($page == 1) {
            $pageLimit = 0;
        } else {
            $pageLimit = PAGE_PER_RECA_ESP * ($page - 1);
        }
        $query_reca_esp = mysqli_query($connection, "SELECT DEPT.NOMBRE AS DEPARTAMENTO, MUN.NOMBRE AS MUNICIPIO,
                                                            RE.ID_RECAUDO, FE.ID_FACTURACION,
                                                            C.ID_CONTRIBUYENTE AS ID_CONTRIBUYENTE,
                                                            C.NOMBRE AS CONTRIBUYENTE, FE.CONSECUTIVO_FACT,
                                                            FE.VALOR_FACTURA, RE.VALOR_RECAUDO, RE.ESTADO_RECAUDO
                                                       FROM recaudo_especiales_2 RE,
                                                            facturacion_especiales_2 FE, departamentos_visitas_2 DEPT,
                                                            municipios_visitas_2 MUN, contribuyentes_2 C
                                                       $busqueda_recaudo
                                                        AND RE.ID_FACTURACION = FE.ID_FACTURACION
                                                        AND FE.ID_COD_DPTO = DEPT.ID_DEPARTAMENTO
                                                        AND FE.ID_COD_MPIO = MUN.ID_MUNICIPIO
                                                        AND DEPT.ID_DEPARTAMENTO = MUN.ID_DEPARTAMENTO
                                                        AND FE.ID_CONTRIBUYENTE = C.ID_CONTRIBUYENTE
                                                      ORDER BY DEPT.NOMBRE, MUN.NOMBRE
                                                      LIMIT " . $pageLimit . ", " . PAGE_PER_RECA_ESP);
        $table = "";
        while ($row_reca_esp = mysqli_fetch_assoc($query_reca_esp)) {
            $table = $table . "<tr>";
                switch ($row_reca_esp['ESTADO_RECAUDO']) {
                    case "1":
                        $table = $table . "<td style='vertical-align:middle;'><span style='font-size: 11px; background-color: #0676C0;' class='label label-success'><b>E</b></span></td>";
                        break;
                    case "2":
                        $table = $table . "<td style='vertical-align:middle;'><span style='font-size: 11px; background-color: #FFC107;' class='label label-success'><b>PE</b></span></td>";
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
                }
                $table = $table . "<td style='vertical-align:middle;'>" . $row_reca_esp['DEPARTAMENTO'] . "</td>";
                $table = $table . "<td style='vertical-align:middle;'>" . $row_reca_esp['MUNICIPIO'] . "</td>";
                $table = $table . "<td style='vertical-align:middle;'>" . $row_reca_esp['CONTRIBUYENTE'] . "</td>";
                $table = $table . "<td style='vertical-align:middle;'>" . $row_reca_esp['CONSECUTIVO_FACT'] . "</td>";
                //$table = $table . "<td style='vertical-align:middle;'>" . "<b style='font-size: 12px;'>$</b> " . number_format($row_reca_esp['VALOR_FACTURA'], 0, ',', '.') . "</td>";
                $table = $table . "<td style='vertical-align:middle;'>" . "<b style='font-size: 12px;'>$</b> " . number_format($row_reca_esp['VALOR_RECAUDO'], 0, ',', '.') . "</td>";
                $table = $table . "<td style='vertical-align:middle;'><a href='Recaudo_Especiales.php?id_reca_especial_editar=" . $row_reca_esp['ID_RECAUDO'] . "'><button><img src='Images/search.png' title='Detalle' width='16' height='16' /></button></a></td>";
                $table = $table . "<td style='vertical-align:middle;'><a href='Recaudo_Especiales.php?id_reca_especial_eliminar=" . $row_reca_esp['ID_RECAUDO'] . "'><button><img src='Images/gnome_edit_delete.png' title='Eliminar' width='16' height='16'/></button></a></td>";
            $table = $table . "</tr>";
        }
        $info_resultado = array();
        $info_resultado[0] = $table;
        echo json_encode($info_resultado);
        exit();
    }
?>