<?php
    session_start();
    require_once('../Includes/Config.php');
    require_once('../Includes/Paginacion_Admin_Empresa.php');
    if ($_SESSION['timeout'] + 60 * 60 < time()) {
        session_unset();
        session_destroy();
        $ruta = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
        header("Location:$ruta/Login.php");
    } else {
        $_SESSION['timeout'] = time();
        if ($_POST['sw'] == 1) {
            if (isset($_POST['busqueda_empresa']) != "") {
                if ($_POST['busqueda_empresa'] != "") {
                    $busqueda_empresa = " WHERE NOMBRE LIKE '%" . $_POST['busqueda_empresa'] . "%' ";
                } else {
                    $busqueda_empresa = " WHERE NOMBRE <> ''";
                }
            } else {
                $busqueda_empresa = " WHERE NOMBRE <> ''";
            }
        } else {
            $busqueda_empresa = " WHERE NOMBRE <> ''";
        }
        $page = $_POST['page'];
        if ($page == 1) {
            $pageLimit = 0;
        } else {
            $pageLimit = PAGE_PER_EMPRESA * ($page - 1);
        }
        $query_empresa = mysqli_query($connection, "SELECT *
                                                      FROM empresas_2
                                                      $busqueda_empresa
                                                     ORDER BY NOMBRE
                                                     LIMIT " . $pageLimit . ", " . PAGE_PER_EMPRESA);
        $table = "";
        while ($row_empresa = mysqli_fetch_assoc($query_empresa)) {
            $table = $table . "<tr>";
                $table = $table . "<td style='vertical-align:middle;'>" . $row_empresa['NOMBRE'] . "</td>";
                $table = $table . "<td style='vertical-align:middle;'><a href='Admin_Empresas.php?id_empresa_editar=" . $row_empresa['ID_EMPRESA'] . "'><button><img src='Images/pencil_edit.png' title='Editar' width='16' height='16' /></button></a></td>";
                $table = $table . "<td style='vertical-align:middle;'><a href='Admin_Empresas.php?id_empresa_eliminar=" . $row_empresa['ID_EMPRESA'] . "'><button><img src='Images/gnome_edit_delete.png' title='Eliminar' width='16' height='16'/></button></a></td>";
            $table = $table . "</tr>";
        }
        $info_resultado = array();
        $info_resultado[0] = $table;
        echo json_encode($info_resultado);
        exit();
    }
?>