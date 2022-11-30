<?php
    session_start();
    require_once('../Includes/Config.php');
    require_once('../Includes/Paginacion_Admin_Tipo_Cliente.php');
    if ($_SESSION['timeout'] + 60 * 60 < time()) {
        session_unset();
        session_destroy();
        $ruta = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
        header("Location:$ruta/Login.php");
    } else {
        $_SESSION['timeout'] = time();
        if ($_POST['sw'] == 1) {
            if (isset($_POST['busqueda_tipo_cliente']) != "") {
                if ($_POST['busqueda_tipo_cliente'] != "") {
                    $busqueda_tipo_cliente = " WHERE NOMBRE LIKE '%" . $_POST['busqueda_tipo_cliente'] . "%' ";
                } else {
                    $busqueda_tipo_cliente = " WHERE NOMBRE <> ''";
                }
            } else {
                $busqueda_tipo_cliente = " WHERE NOMBRE <> ''";
            }
        } else {
            $busqueda_tipo_cliente = " WHERE NOMBRE <> ''";
        }
        $page = $_POST['page'];
        if ($page == 1) {
            $pageLimit = 0;
        } else {
            $pageLimit = PAGE_PER_TIPO_CLIENTE * ($page - 1);
        }
        $query_tipo_cliente = mysqli_query($connection, "SELECT *
                                                           FROM tipo_clientes_2
                                                           $busqueda_tipo_cliente
                                                          ORDER BY NOMBRE
                                                          LIMIT " . $pageLimit . ", " . PAGE_PER_TIPO_CLIENTE);
        $table = "";
        while ($row_tipo_cliente = mysqli_fetch_assoc($query_tipo_cliente)) {
            $table = $table . "<tr>";
                $table = $table . "<td style='vertical-align:middle;'>" . $row_tipo_cliente['NOMBRE'] . "</td>";
                $table = $table . "<td style='vertical-align:middle;'><a href='Admin_Tipo_Clientes.php?id_tipo_cliente_editar=" . $row_tipo_cliente['ID_TIPO_CLIENTE'] . "'><button><img src='Images/pencil_edit.png' title='Editar' width='16' height='16' /></button></a></td>";
                $table = $table . "<td style='vertical-align:middle;'><a href='Admin_Tipo_Clientes.php?id_tipo_cliente_eliminar=" . $row_tipo_cliente['ID_TIPO_CLIENTE'] . "'><button><img src='Images/gnome_edit_delete.png' title='Eliminar' width='16' height='16'/></button></a></td>";
            $table = $table . "</tr>";
        }
        $info_resultado = array();
        $info_resultado[0] = $table;
        echo json_encode($info_resultado);
        exit();
    }
?>