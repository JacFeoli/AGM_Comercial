<?php
    session_start();
    require_once('../Includes/Config.php');
    require_once('../Includes/Paginacion_Admin_Municipio.php');
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
                    $busqueda_municipio = " AND MUN.NOMBRE LIKE '%" . $_POST['busqueda_municipio'] . "%' ";
                } else {
                    $busqueda_municipio = " AND MUN.NOMBRE <> ''";
                }
            } else {
                $busqueda_municipio = " AND MUN.NOMBRE <> ''";
            }
        } else {
            $busqueda_municipio = " AND MUN.NOMBRE <> ''";
        }
        $page = $_POST['page'];
        if ($page == 1) {
            $pageLimit = 0;
        } else {
            $pageLimit = PAGE_PER_MUNICIPIO * ($page - 1);
        }
        $query_municipio = mysqli_query($connection, "SELECT MUN.NOMBRE AS NOMBRE_MUNICIPIO,
                                                             MUN.ID_MUNICIPIO AS ID_MUNICIPIO,
                                                             DEP.NOMBRE AS NOMBRE_DEPARTAMENTO,
                                                             DEP.ID_DEPARTAMENTO AS ID_DEPARTAMENTO,
                                                             MUN.ID_TABLA AS ID_TABLA, MUN.ID_COMERCIALIZADOR
                                                        FROM municipios_comercializadores_2 MUN,
                                                             departamentos_comercializadores_2 DEP,
                                                             comercializadores_2 COM
                                                       WHERE MUN.ID_DEPARTAMENTO = DEP.ID_DEPARTAMENTO
                                                         AND MUN.ID_COMERCIALIZADOR = COM.ID_COMERCIALIZADOR
                                                         AND DEP.ID_COMERCIALIZADOR = COM.ID_COMERCIALIZADOR
                                                        $busqueda_municipio
                                                       ORDER BY DEP.NOMBRE, MUN.NOMBRE
                                                       LIMIT " . $pageLimit . ", " . PAGE_PER_MUNICIPIO);
        $table = "";
        while ($row_municipio = mysqli_fetch_assoc($query_municipio)) {
            $table = $table . "<tr>";
                $table = $table . "<td style='vertical-align:middle;'>" . $row_municipio['ID_MUNICIPIO'] . "</td>";
                $table = $table . "<td style='vertical-align:middle;'>" . $row_municipio['NOMBRE_MUNICIPIO'] . "</td>";
                $table = $table . "<td style='vertical-align:middle;'>" . $row_municipio['ID_DEPARTAMENTO'] . "</td>";
                $table = $table . "<td style='vertical-align:middle;'>" . $row_municipio['NOMBRE_DEPARTAMENTO'] . "</td>";
                $query_select_comercializador = mysqli_query($connection, "SELECT * FROM comercializadores_2 WHERE ID_COMERCIALIZADOR = " . $row_municipio['ID_COMERCIALIZADOR']);
                $row_comercializador = mysqli_fetch_array($query_select_comercializador);
                $table = $table . "<td style='vertical-align:middle;'>" . $row_comercializador['NOMBRE'] . "</td>";
                $table = $table . "<td style='vertical-align:middle;'><a href='Admin_Municipios_Comercializadores.php?id_municipio_editar=" . $row_municipio['ID_TABLA'] . "'><button><img src='Images/pencil_edit.png' title='Editar' width='16' height='16' /></button></a></td>";
                $table = $table . "<td style='vertical-align:middle;'><a href='Admin_Municipios_Comercializadores.php?id_municipio_eliminar=" . $row_municipio['ID_TABLA'] . "'><button><img src='Images/gnome_edit_delete.png' title='Eliminar' width='16' height='16'/></button></a></td>";
            $table = $table . "</tr>";
        }
        $info_resultado = array();
        $info_resultado[0] = $table;
        echo json_encode($info_resultado);
        exit();
    }
?>