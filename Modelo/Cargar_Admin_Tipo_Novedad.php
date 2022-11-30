<?php
    session_start();
    require_once('../Includes/Config.php');
    require_once('../Includes/Paginacion_Admin_Tipo_Novedad.php');
    if ($_SESSION['timeout'] + 60 * 60 < time()) {
        session_unset();
        session_destroy();
        $ruta = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
        header("Location:$ruta/Login.php");
    } else {
        $_SESSION['timeout'] = time();
        if ($_POST['sw'] == 1) {
            if (isset($_POST['busqueda_tipo_novedad']) != "") {
                if ($_POST['busqueda_tipo_novedad'] != "") {
                    $busqueda_tipo_novedad = " WHERE NOMBRE LIKE '%" . $_POST['busqueda_tipo_novedad'] . "%' ";
                } else {
                    $busqueda_tipo_novedad = " WHERE NOMBRE <> ''";
                }
            } else {
                $busqueda_tipo_novedad = " WHERE NOMBRE <> ''";
            }
        } else {
            $busqueda_tipo_novedad = " WHERE NOMBRE <> ''";
        }
        $page = $_POST['page'];
        if ($page == 1) {
            $pageLimit = 0;
        } else {
            $pageLimit = PAGE_PER_TIPO_NOVEDAD * ($page - 1);
        }
        $query_tipo_novedad = mysqli_query($connection, "SELECT *
                                                      FROM tipo_novedades_2
                                                      $busqueda_tipo_novedad
                                                     ORDER BY NOMBRE
                                                     LIMIT " . $pageLimit . ", " . PAGE_PER_TIPO_NOVEDAD);
        $table = "";
        while ($row_tipo_novedad = mysqli_fetch_assoc($query_tipo_novedad)) {
            $table = $table . "<tr>";
                $table = $table . "<td style='vertical-align:middle;'>" . $row_tipo_novedad['NOMBRE'] . "</td>";
                $table = $table . "<td style='vertical-align:middle;'><a href='Admin_Tipo_Novedades.php?id_tipo_novedad_editar=" . $row_tipo_novedad['ID_TIPO_NOVEDAD'] . "'><button><img src='Images/pencil_edit.png' title='Editar' width='16' height='16' /></button></a></td>";
                $table = $table . "<td style='vertical-align:middle;'><a href='Admin_Tipo_Novedades.php?id_tipo_novedad_eliminar=" . $row_tipo_novedad['ID_TIPO_NOVEDAD'] . "'><button><img src='Images/gnome_edit_delete.png' title='Eliminar' width='16' height='16'/></button></a></td>";
            $table = $table . "</tr>";
        }
        $info_resultado = array();
        $info_resultado[0] = $table;
        echo json_encode($info_resultado);
        exit();
    }
?>