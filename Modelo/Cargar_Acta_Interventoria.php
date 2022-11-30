<?php
    session_start();
    require_once('../Includes/Config.php');
    require_once('../Includes/Paginacion_Fact_Esp.php');
    if ($_SESSION['timeout'] + 60 * 60 < time()) {
        session_unset();
        session_destroy();
        $ruta = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
        header("Location:$ruta/Login.php");
    } else {
        $_SESSION['timeout'] = time();
        if ($_POST['sw'] == 1) {
            if (isset($_POST['busqueda_acta']) != "") {
                if ($_POST['busqueda_acta'] != "") {
                    $busqueda_acta = " WHERE MUN.NOMBRE LIKE '%" . $_POST['busqueda_acta'] . "%' ";
                } else {
                    $busqueda_acta = " WHERE MUN.NOMBRE <> ''";
                }
            } else {
                $busqueda_acta = " WHERE MUN.NOMBRE <> ''";
            }
        } else {
            $busqueda_acta = " WHERE MUN.NOMBRE <> ''";
        }
        $page = $_POST['page'];
        if ($page == 1) {
            $pageLimit = 0;
        } else {
            $pageLimit = PAGE_PER_FACT_ESP * ($page - 1);
        }
        $query_acta_interventoria = mysqli_query($connection, "SELECT DISTINCT(DEPT.ID_DEPARTAMENTO) AS ID_DEPARTAMENTO,
                                                                      AC.ID_ACTA_INTERVENTORIA, MUN.ID_MUNICIPIO, AC.PERIODO_ACTA,
                                                                      AC.ID_ACTA_INTERVENTORIA, AC.ID_COD_DPTO, AC.ID_COD_MPIO,
                                                                      DEPT.NOMBRE AS DEPARTAMENTO, MUN.NOMBRE AS MUNICIPIO
                                                                 FROM actas_interventoria_2 AC,
                                                                      departamentos_visitas_2 DEPT,
                                                                      municipios_visitas_2 MUN
                                                                 $busqueda_acta
                                                                  AND AC.ID_COD_DPTO = DEPT.ID_DEPARTAMENTO
                                                                  AND AC.ID_COD_MPIO = MUN.ID_MUNICIPIO
                                                                  AND DEPT.ID_DEPARTAMENTO = MUN.ID_DEPARTAMENTO
                                                                ORDER BY MUN.NOMBRE, PERIODO_ACTA DESC
                                                                LIMIT " . $pageLimit . ", " . PAGE_PER_FACT_ESP);
        $table = "";
        while ($row_acta_interventoria = mysqli_fetch_assoc($query_acta_interventoria)) {
            $table = $table . "<tr>";
                $table = $table . "<td style='vertical-align:middle;'>" . $row_acta_interventoria['DEPARTAMENTO'] . "</td>";
                $table = $table . "<td style='vertical-align:middle;'>" . $row_acta_interventoria['MUNICIPIO'] . "</td>";
                $table = $table . "<td style='vertical-align:middle;'>" . $row_acta_interventoria['PERIODO_ACTA'] . "</td>";
                $table = $table . "<td style='vertical-align:middle;'><a href='Actas_Interventoria.php?id_acta_interventoria_editar=" . $row_acta_interventoria['ID_ACTA_INTERVENTORIA'] . "'><button><img src='Images/search.png' title='Detalle' width='16' height='16' /></button></a></td>";
                $table = $table . "<td style='vertical-align:middle;'><a href='Actas_Interventoria.php?id_acta_interventoria_eliminar=" . $row_acta_interventoria['ID_ACTA_INTERVENTORIA'] . "'><button><img src='Images/gnome_edit_delete.png' title='Eliminar' width='16' height='16'/></button></a></td>";
                $table = $table . "<td style='vertical-align:middle;'><a onClick='generarActaInterventoria(" . $row_acta_interventoria['ID_ACTA_INTERVENTORIA'] . ")'><button style='border: 1px solid;'><img src='Images/print_2.png' title='Imprimir' width='16' height='16' /></button></a></td>";
                $table = $table . "<td style='vertical-align:middle;'><a onClick='generarWordActaInterventoria(" . $row_acta_interventoria['ID_ACTA_INTERVENTORIA'] . ")'><button style='border: 1px solid;'><img src='Images/word_2.png' title='Imprimir' width='16' height='16' /></button></a></td>";
            $table = $table . "</tr>";
        }
        $info_resultado = array();
        $info_resultado[0] = $table;
        echo json_encode($info_resultado);
        exit();
    }
?>