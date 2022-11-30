<?php
    session_start();
    require_once('../Includes/Config.php');
    require_once('../Includes/Paginacion_Admin_Contribuyente.php');
    if ($_SESSION['timeout'] + 60 * 60 < time()) {
        session_unset();
        session_destroy();
        $ruta = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
        header("Location:$ruta/Login.php");
    } else {
        $_SESSION['timeout'] = time();
        if ($_POST['sw'] == 1) {
            if (isset($_POST['busqueda_contribuyente']) != "") {
                if ($_POST['busqueda_contribuyente'] != "") {
                    $busqueda_contribuyente = " WHERE NOMBRE LIKE '%" . $_POST['busqueda_contribuyente'] . "%' ";
                } else {
                    $busqueda_contribuyente = " WHERE NOMBRE <> ''";
                }
            } else {
                $busqueda_contribuyente = " WHERE NOMBRE <> ''";
            }
        } else {
            $busqueda_contribuyente = " WHERE NOMBRE <> ''";
        }
        $page = $_POST['page'];
        if ($page == 1) {
            $pageLimit = 0;
        } else {
            $pageLimit = PAGE_PER_CONTRIBUYENTE * ($page - 1);
        }
        $query_contribuyente = mysqli_query($connection, "SELECT *
                                                      FROM contribuyentes_2
                                                      $busqueda_contribuyente
                                                     ORDER BY NOMBRE
                                                     LIMIT " . $pageLimit . ", " . PAGE_PER_CONTRIBUYENTE);
        $table = "";
        while ($row_contribuyente = mysqli_fetch_assoc($query_contribuyente)) {
            $table = $table . "<tr>";
                $table = $table . "<td style='vertical-align:middle;'>" . $row_contribuyente['NIT_CONTRIBUYENTE'] . "</td>";
                $table = $table . "<td style='vertical-align:middle;'>" . $row_contribuyente['NOMBRE'] . "</td>";
                $table = $table . "<td style='vertical-align:middle;'><a href='Admin_Contribuyentes.php?id_contribuyente_editar=" . $row_contribuyente['ID_CONTRIBUYENTE'] . "'><button><img src='Images/pencil_edit.png' title='Editar' width='16' height='16' /></button></a></td>";
                $table = $table . "<td style='vertical-align:middle;'><a href='Admin_Contribuyentes.php?id_contribuyente_eliminar=" . $row_contribuyente['ID_CONTRIBUYENTE'] . "'><button><img src='Images/gnome_edit_delete.png' title='Eliminar' width='16' height='16'/></button></a></td>";
            $table = $table . "</tr>";
        }
        $info_resultado = array();
        $info_resultado[0] = $table;
        echo json_encode($info_resultado);
        exit();
    }
?>