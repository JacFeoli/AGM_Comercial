<?php
    session_start();
    require_once('../Includes/Config.php');
    require_once('../Includes/Paginacion_Admin_Comercializador.php');
    if ($_SESSION['timeout'] + 60 * 60 < time()) {
        session_unset();
        session_destroy();
        $ruta = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
        header("Location:$ruta/Login.php");
    } else {
        $_SESSION['timeout'] = time();
        if ($_POST['sw'] == 1) {
            if (isset($_POST['busqueda_comercializador']) != "") {
                if ($_POST['busqueda_comercializador'] != "") {
                    $busqueda_comercializador = " WHERE NOMBRE LIKE '%" . $_POST['busqueda_comercializador'] . "%' ";
                } else {
                    $busqueda_comercializador = " WHERE NOMBRE <> ''";
                }
            } else {
                $busqueda_comercializador = " WHERE NOMBRE <> ''";
            }
        } else {
            $busqueda_comercializador = " WHERE NOMBRE <> ''";
        }
        $page = $_POST['page'];
        if ($page == 1) {
            $pageLimit = 0;
        } else {
            $pageLimit = PAGE_PER_COMERCIALIZADOR * ($page - 1);
        }
        $query_comercializador = mysqli_query($connection, "SELECT *
                                                      FROM comercializadores_2
                                                      $busqueda_comercializador
                                                     ORDER BY NOMBRE
                                                     LIMIT " . $pageLimit . ", " . PAGE_PER_COMERCIALIZADOR);
        $table = "";
        while ($row_comercializador = mysqli_fetch_assoc($query_comercializador)) {
            $table = $table . "<tr>";
                $table = $table . "<td style='vertical-align:middle;'>" . $row_comercializador['NIT_COMERCIALIZADOR'] . "</td>";
                $table = $table . "<td style='vertical-align:middle;'>" . $row_comercializador['NOMBRE'] . "</td>";
                $table = $table . "<td style='vertical-align:middle;'><a href='Admin_Comercializadores.php?id_comercializador_editar=" . $row_comercializador['ID_COMERCIALIZADOR'] . "'><button><img src='Images/pencil_edit.png' title='Editar' width='16' height='16' /></button></a></td>";
                $table = $table . "<td style='vertical-align:middle;'><a href='Admin_Comercializadores.php?id_comercializador_eliminar=" . $row_comercializador['ID_COMERCIALIZADOR'] . "'><button><img src='Images/gnome_edit_delete.png' title='Eliminar' width='16' height='16'/></button></a></td>";
            $table = $table . "</tr>";
        }
        $info_resultado = array();
        $info_resultado[0] = $table;
        echo json_encode($info_resultado);
        exit();
    }
?>