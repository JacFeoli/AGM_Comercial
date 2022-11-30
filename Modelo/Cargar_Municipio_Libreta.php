<?php
    session_start();
    require_once('../Includes/Config.php');
    require_once('../Includes/Paginacion_Libreta_Municipio.php');
    if ($_SESSION['timeout'] + 60 * 60 < time()) {
        session_unset();
        session_destroy();
        $ruta = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
        header("Location:$ruta/Login.php");
    } else {
        $_SESSION['timeout'] = time();
        if ($_POST['sw'] == 1) {
            if (isset($_POST['busqueda_municipio']) != "") {
                if ($_POST['busqueda_municipio'] != "") {
                    $busqueda_municipio = " WHERE MUN.NOMBRE LIKE '%" . $_POST['busqueda_municipio'] . "%' ";
                } else {
                    $busqueda_municipio = " WHERE MUN.NOMBRE <> ''";
                }
            } else {
                $busqueda_municipio = " WHERE MUN.NOMBRE <> ''";
            }
        } else {
            $busqueda_municipio = " WHERE MUN.NOMBRE <> ''";
        }
        $page = $_POST['page'];
        if ($page == 1) {
            $pageLimit = 0;
        } else {
            $pageLimit = PAGE_PER_LIBRETA_MUNICIPIO * ($page - 1);
        }
        $query_libreta_municipio = mysqli_query($connection, "SELECT DEPT.NOMBRE AS DEPARTAMENTO, MUN.NOMBRE AS MUNICIPIO,
                                                                     ML.ID_CONCESION AS ID_CONCESION, ML.ID_EMPRESA AS ID_EMPRESA,
                                                                     ML.NO_CONTRATO_CONCESION, ML.ID_MUNICIPIO_LIBRETA
                                                                FROM municipios_libreta_2 ML, departamentos_visitas_2 DEPT,
                                                                municipios_visitas_2 MUN
                                                                $busqueda_municipio
                                                                 AND ML.ID_DEPARTAMENTO = DEPT.ID_DEPARTAMENTO
                                                                 AND ML.ID_MUNICIPIO = MUN.ID_MUNICIPIO
                                                                 AND DEPT.ID_DEPARTAMENTO = MUN.ID_DEPARTAMENTO
                                                               ORDER BY DEPT.NOMBRE, MUN.NOMBRE
                                                               LIMIT " . $pageLimit . ", " . PAGE_PER_LIBRETA_MUNICIPIO);
        $table = "";
        while ($row_libreta_municipio = mysqli_fetch_assoc($query_libreta_municipio)) {
            $table = $table . "<tr>";
                $table = $table . "<td style='vertical-align:middle;'>" . $row_libreta_municipio['DEPARTAMENTO'] . "</td>";
                $table = $table . "<td style='vertical-align:middle;'>" . $row_libreta_municipio['MUNICIPIO'] . "</td>";
                $query_select_concesion = mysqli_query($connection, "SELECT NOMBRE "
                                                                  . " FROM concesiones_2 "
                                                                  . " WHERE ID_CONCESION = '" . $row_libreta_municipio['ID_CONCESION'] . "'");
                $row_concesion = mysqli_fetch_array($query_select_concesion);
                $table = $table . "<td style='vertical-align:middle;'>" . $row_concesion['NOMBRE'] . "</td>";
                $query_select_empresa = mysqli_query($connection, "SELECT NOMBRE "
                                                                 . " FROM empresas_2 "
                                                                 . " WHERE ID_EMPRESA = '" . $row_libreta_municipio['ID_EMPRESA'] . "'");
                $row_empresa = mysqli_fetch_array($query_select_empresa);
                $table = $table . "<td style='vertical-align:middle;'>" . $row_empresa['NOMBRE'] . "</td>";
                $table = $table . "<td style='vertical-align:middle;'>" . $row_libreta_municipio['NO_CONTRATO_CONCESION'] . "</td>";
                $table = $table . "<td style='vertical-align:middle;'><a href='Municipios_Libretas.php?id_municipio_libreta_historico=" . $row_libreta_municipio['ID_MUNICIPIO_LIBRETA'] . "'><button><img src='Images/search_history.png' title='Historico' width='16' height='16' /></button></a></td>";
                $table = $table . "<td style='vertical-align:middle;'><a href='Municipios_Libretas.php?id_municipio_libreta_editar=" . $row_libreta_municipio['ID_MUNICIPIO_LIBRETA'] . "'><button><img src='Images/pencil_edit.png' title='Editar' width='16' height='16' /></button></a></td>";
                $table = $table . "<td style='vertical-align:middle;'><a href='Municipios_Libretas.php?id_municipio_libreta_eliminar=" . $row_libreta_municipio['ID_MUNICIPIO_LIBRETA'] . "'><button><img src='Images/gnome_edit_delete.png' title='Eliminar' width='16' height='16'/></button></a></td>";
            $table = $table . "</tr>";
        }
        $info_resultado = array();
        $info_resultado[0] = $table;
        echo json_encode($info_resultado);
        exit();
    }
?>