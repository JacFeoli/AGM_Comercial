<?php
    session_start();
    require_once('../Includes/Config.php');
    require_once('../Includes/Paginacion_Admin_Estado_Suministro.php');
    if ($_SESSION['timeout'] + 60 * 60 < time()) {
        session_unset();
        session_destroy();
        $ruta = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
        header("Location:$ruta/Login.php");
    } else {
        $_SESSION['timeout'] = time();
        if ($_POST['sw'] == 1) {
            if (isset($_POST['busqueda_estado_suministro']) != "") {
                if ($_POST['busqueda_estado_suministro'] != "") {
                    $busqueda_estado_suministro = " WHERE NOMBRE LIKE '%" . $_POST['busqueda_estado_suministro'] . "%' ";
                } else {
                    $busqueda_estado_suministro = " WHERE NOMBRE <> ''";
                }
            } else {
                $busqueda_estado_suministro = " WHERE NOMBRE <> ''";
            }
        } else {
            $busqueda_estado_suministro = " WHERE NOMBRE <> ''";
        }
        $page = $_POST['page'];
        if ($page == 1) {
            $pageLimit = 0;
        } else {
            $pageLimit = PAGE_PER_ESTADO_SUMINISTRO * ($page - 1);
        }
        $query_estado_suministro = mysqli_query($connection, "SELECT *
                                                      FROM estados_suministro_2
                                                      $busqueda_estado_suministro
                                                     ORDER BY NOMBRE
                                                     LIMIT " . $pageLimit . ", " . PAGE_PER_ESTADO_SUMINISTRO);
        $table = "";
        while ($row_estado_suministro = mysqli_fetch_assoc($query_estado_suministro)) {
            $table = $table . "<tr>";
                $table = $table . "<td style='vertical-align:middle;'>" . $row_estado_suministro['NOMBRE'] . "</td>";
                $table = $table . "<td style='vertical-align:middle;'><a href='Admin_Estados_Suministro.php?id_estado_suministro_editar=" . $row_estado_suministro['ID_ESTADO_SUMINISTRO'] . "'><button><img src='Images/pencil_edit.png' title='Editar' width='16' height='16' /></button></a></td>";
                $table = $table . "<td style='vertical-align:middle;'><a href='Admin_Estados_Suministro.php?id_estado_suministro_eliminar=" . $row_estado_suministro['ID_ESTADO_SUMINISTRO'] . "'><button><img src='Images/gnome_edit_delete.png' title='Eliminar' width='16' height='16'/></button></a></td>";
            $table = $table . "</tr>";
        }
        $info_resultado = array();
        $info_resultado[0] = $table;
        echo json_encode($info_resultado);
        exit();
    }
?>