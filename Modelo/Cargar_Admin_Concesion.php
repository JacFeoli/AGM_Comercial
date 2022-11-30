<?php
    session_start();
    require_once('../Includes/Config.php');
    require_once('../Includes/Paginacion_Admin_Concesion.php');
    if ($_SESSION['timeout'] + 60 * 60 < time()) {
        session_unset();
        session_destroy();
        $ruta = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
        header("Location:$ruta/Login.php");
    } else {
        $_SESSION['timeout'] = time();
        if ($_POST['sw'] == 1) {
            if (isset($_POST['busqueda_concesion']) != "") {
                if ($_POST['busqueda_concesion'] != "") {
                    $busqueda_concesion = " WHERE NOMBRE LIKE '%" . $_POST['busqueda_concesion'] . "%' ";
                } else {
                    $busqueda_concesion = " WHERE NOMBRE <> ''";
                }
            } else {
                $busqueda_concesion = " WHERE NOMBRE <> ''";
            }
        } else {
            $busqueda_concesion = " WHERE NOMBRE <> ''";
        }
        $page = $_POST['page'];
        if ($page == 1) {
            $pageLimit = 0;
        } else {
            $pageLimit = PAGE_PER_CONCESION * ($page - 1);
        }
        $query_concesion = mysqli_query($connection, "SELECT *
                                                        FROM concesiones_2
                                                        $busqueda_concesion
                                                       ORDER BY NOMBRE
                                                       LIMIT " . $pageLimit . ", " . PAGE_PER_CONCESION);
        $table = "";
        while ($row_concesion = mysqli_fetch_assoc($query_concesion)) {
            $table = $table . "<tr>";
                $table = $table . "<td style='vertical-align:middle;'>" . $row_concesion['NOMBRE'] . "</td>";
                $table = $table . "<td style='vertical-align:middle;'><a href='Admin_Concesiones.php?id_concesion_editar=" . $row_concesion['ID_CONCESION'] . "'><button><img src='Images/pencil_edit.png' title='Editar' width='16' height='16' /></button></a></td>";
                $table = $table . "<td style='vertical-align:middle;'><a href='Admin_Concesiones.php?id_concesion_eliminar=" . $row_concesion['ID_CONCESION'] . "'><button><img src='Images/gnome_edit_delete.png' title='Eliminar' width='16' height='16'/></button></a></td>";
            $table = $table . "</tr>";
        }
        $info_resultado = array();
        $info_resultado[0] = $table;
        echo json_encode($info_resultado);
        exit();
    }
?>