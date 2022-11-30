<?php
    session_start();
    require_once('../Includes/Config.php');
    require_once('../Includes/Paginacion_Admin_Alcaldia.php');
    if ($_SESSION['timeout'] + 60 * 60 < time()) {
        session_unset();
        session_destroy();
        $ruta = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
        header("Location:$ruta/Login.php");
    } else {
        $_SESSION['timeout'] = time();
        if ($_POST['sw'] == 1) {
            if (isset($_POST['busqueda_alcaldia']) != "") {
                if ($_POST['busqueda_alcaldia'] != "") {
                    $busqueda_alcaldia = " WHERE NOMBRE LIKE '%" . $_POST['busqueda_alcaldia'] . "%' ";
                } else {
                    $busqueda_alcaldia = " WHERE NOMBRE <> ''";
                }
            } else {
                $busqueda_alcaldia = " WHERE NOMBRE <> ''";
            }
        } else {
            $busqueda_alcaldia = " WHERE NOMBRE <> ''";
        }
        $page = $_POST['page'];
        if ($page == 1) {
            $pageLimit = 0;
        } else {
            $pageLimit = PAGE_PER_ALCALDIA * ($page - 1);
        }
        $query_alcaldia = mysqli_query($connection, "SELECT *
                                                       FROM alcaldias_2
                                                       $busqueda_alcaldia
                                                      ORDER BY NOMBRE
                                                      LIMIT " . $pageLimit . ", " . PAGE_PER_ALCALDIA);
        $table = "";
        while ($row_alcaldia = mysqli_fetch_assoc($query_alcaldia)) {
            $table = $table . "<tr>";
                $query_select_nombre_departamento = mysqli_query($connection, "SELECT NOMBRE "
                                                                            . "  FROM departamentos_visitas_2 "
                                                                            . " WHERE ID_DEPARTAMENTO = " . $row_alcaldia['ID_COD_DPTO']);
                $row_nombre_departamento = mysqli_fetch_array($query_select_nombre_departamento);
                $table = $table . "<td style='vertical-align:middle;'>" . $row_nombre_departamento['NOMBRE'] . "</td>";
                $query_select_nombre_municipio = mysqli_query($connection, "SELECT NOMBRE "
                                                                            . "  FROM municipios_visitas_2 "
                                                                            . " WHERE ID_DEPARTAMENTO = " . $row_alcaldia['ID_COD_DPTO'] . " "
                                                                            . "   AND ID_MUNICIPIO = " . $row_alcaldia['ID_COD_MPIO']);
                $row_nombre_municipio = mysqli_fetch_array($query_select_nombre_municipio);
                $table = $table . "<td style='vertical-align:middle;'>" . $row_nombre_municipio['NOMBRE'] . "</td>";
                $table = $table . "<td style='vertical-align:middle;'>" . $row_alcaldia['NIT_ALCALDIA'] . "</td>";
                $table = $table . "<td style='vertical-align:middle;'>" . $row_alcaldia['NOMBRE'] . "</td>";
                $table = $table . "<td style='vertical-align:middle;'><a href='Admin_Alcaldias.php?id_alcaldia_editar=" . $row_alcaldia['ID_ALCALDIA'] . "'><button><img src='Images/pencil_edit.png' title='Editar' width='16' height='16' /></button></a></td>";
                $table = $table . "<td style='vertical-align:middle;'><a href='Admin_Alcaldias.php?id_alcaldia_eliminar=" . $row_alcaldia['ID_ALCALDIA'] . "'><button><img src='Images/gnome_edit_delete.png' title='Eliminar' width='16' height='16'/></button></a></td>";
            $table = $table . "</tr>";
        }
        $info_resultado = array();
        $info_resultado[0] = $table;
        echo json_encode($info_resultado);
        exit();
    }
?>