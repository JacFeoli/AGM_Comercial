<?php
    session_start();
    require_once('../Includes/Config.php');
    require_once('../Includes/Paginacion_Admin_Operador.php');
    if ($_SESSION['timeout'] + 60 * 60 < time()) {
        session_unset();
        session_destroy();
        $ruta = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
        header("Location:$ruta/Login.php");
    } else {
        $_SESSION['timeout'] = time();
        if ($_POST['sw'] == 1) {
            if (isset($_POST['busqueda_operador']) != "") {
                if ($_POST['busqueda_operador'] != "") {
                    $busqueda_operador = " WHERE NOMBRE LIKE '%" . $_POST['busqueda_operador'] . "%' ";
                } else {
                    $busqueda_operador = " WHERE NOMBRE <> ''";
                }
            } else {
                $busqueda_operador = " WHERE NOMBRE <> ''";
            }
        } else {
            $busqueda_operador = " WHERE NOMBRE <> ''";
        }
        $page = $_POST['page'];
        if ($page == 1) {
            $pageLimit = 0;
        } else {
            $pageLimit = PAGE_PER_OPERADOR * ($page - 1);
        }
        $query_operador = mysqli_query($connection, "SELECT *
                                                      FROM operadores_2
                                                      $busqueda_operador
                                                     ORDER BY NOMBRE
                                                     LIMIT " . $pageLimit . ", " . PAGE_PER_OPERADOR);
        $table = "";
        while ($row_operador = mysqli_fetch_assoc($query_operador)) {
            $table = $table . "<tr>";
                $table = $table . "<td style='vertical-align:middle;'>" . $row_operador['NIT_OPERADOR'] . "</td>";
                $table = $table . "<td style='vertical-align:middle;'>" . $row_operador['NOMBRE'] . "</td>";
                $table = $table . "<td style='vertical-align:middle;'><a href='Admin_Operadores.php?id_operador_editar=" . $row_operador['ID_OPERADOR'] . "'><button><img src='Images/pencil_edit.png' title='Editar' width='16' height='16' /></button></a></td>";
                $table = $table . "<td style='vertical-align:middle;'><a href='Admin_Operadores.php?id_operador_eliminar=" . $row_operador['ID_OPERADOR'] . "'><button><img src='Images/gnome_edit_delete.png' title='Eliminar' width='16' height='16'/></button></a></td>";
            $table = $table . "</tr>";
        }
        $info_resultado = array();
        $info_resultado[0] = $table;
        echo json_encode($info_resultado);
        exit();
    }
?>