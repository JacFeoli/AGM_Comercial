<?php
    session_start();
    require_once('../Includes/Config.php');
    require_once('../Includes/Paginacion_Admin_Tarifa.php');
    if ($_SESSION['timeout'] + 60 * 60 < time()) {
        session_unset();
        session_destroy();
        $ruta = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
        header("Location:$ruta/Login.php");
    } else {
        $_SESSION['timeout'] = time();
        if ($_POST['sw'] == 1) {
            if (isset($_POST['busqueda_tarifa']) != "") {
                if ($_POST['busqueda_tarifa'] != "") {
                    $busqueda_tarifa = " WHERE NOMBRE LIKE '%" . $_POST['busqueda_tarifa'] . "%' ";
                } else {
                    $busqueda_tarifa = " WHERE NOMBRE <> ''";
                }
            } else {
                $busqueda_tarifa = " WHERE NOMBRE <> ''";
            }
        } else {
            $busqueda_tarifa = " WHERE NOMBRE <> ''";
        }
        $page = $_POST['page'];
        if ($page == 1) {
            $pageLimit = 0;
        } else {
            $pageLimit = PAGE_PER_TARIFA * ($page - 1);
        }
        $query_tarifa = mysqli_query($connection, "SELECT *
                                                     FROM tarifas_2
                                                     $busqueda_tarifa
                                                    ORDER BY NOMBRE
                                                    LIMIT " . $pageLimit . ", " . PAGE_PER_TARIFA);
        $table = "";
        while ($row_tarifa = mysqli_fetch_assoc($query_tarifa)) {
            $table = $table . "<tr>";
                $table = $table . "<td style='vertical-align:middle;'>" . $row_tarifa['COD_TARIFA'] . "</td>";
                $table = $table . "<td style='vertical-align:middle;'>" . $row_tarifa['NOMBRE'] . "</td>";
                $table = $table . "<td style='vertical-align:middle;'><a href='Admin_Tarifas.php?id_tarifa_editar=" . $row_tarifa['ID_TARIFA'] . "'><button><img src='Images/pencil_edit.png' title='Editar' width='16' height='16' /></button></a></td>";
                $table = $table . "<td style='vertical-align:middle;'><a href='Admin_Tarifas.php?id_tarifa_eliminar=" . $row_tarifa['ID_TARIFA'] . "'><button><img src='Images/gnome_edit_delete.png' title='Eliminar' width='16' height='16'/></button></a></td>";
            $table = $table . "</tr>";
        }
        $info_resultado = array();
        $info_resultado[0] = $table;
        echo json_encode($info_resultado);
        exit();
    }
?>