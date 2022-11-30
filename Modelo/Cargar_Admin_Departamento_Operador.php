<?php
    session_start();
    require_once('../Includes/Config.php');
    require_once('../Includes/Paginacion_Admin_Departamento.php');
    if ($_SESSION['timeout'] + 60 * 60 < time()) {
        session_unset();
        session_destroy();
        $ruta = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
        header("Location:$ruta/Login.php");
    } else {
        $_SESSION['timeout'] = time();
        if ($_POST['sw'] == 1) {
            if (isset($_POST['busqueda_departamento']) != "") {
                if ($_POST['busqueda_departamento'] != "") {
                    $busqueda_departamento = " WHERE O.NOMBRE LIKE '%" . $_POST['busqueda_departamento'] . "%' ";
                } else {
                    $busqueda_departamento = " WHERE O.NOMBRE <> ''";
                }
            } else {
                $busqueda_departamento = " WHERE O.NOMBRE <> ''";
            }
        } else {
            $busqueda_departamento = " WHERE O.NOMBRE <> ''";
        }
        $page = $_POST['page'];
        if ($page == 1) {
            $pageLimit = 0;
        } else {
            $pageLimit = PAGE_PER_DEPARTAMENTO * ($page - 1);
        }
        $query_departamento = mysqli_query($connection, "SELECT DOP.ID_DEPARTAMENTO, DOP.NOMBRE AS NOMBRE_DEPARTAMENTO,
                                                                O.NOMBRE AS NOMBRE_OPERADOR, DOP.ID_TABLA AS ID_TABLA
                                                           FROM departamentos_operadores_2 DOP,
                                                                operadores_2 O
                                                           $busqueda_departamento
                                                            AND DOP.ID_OPERADOR = O.ID_OPERADOR
                                                          ORDER BY DOP.NOMBRE, O.NOMBRE
                                                          LIMIT " . $pageLimit . ", " . PAGE_PER_DEPARTAMENTO);
        $table = "";
        while ($row_departamento = mysqli_fetch_assoc($query_departamento)) {
            $table = $table . "<tr>";
                $table = $table . "<td style='vertical-align:middle;'>" . $row_departamento['ID_DEPARTAMENTO'] . "</td>";
                $table = $table . "<td style='vertical-align:middle;'>" . $row_departamento['NOMBRE_DEPARTAMENTO'] . "</td>";
                //$query_select_operador = mysqli_query($connection, "SELECT * FROM operadores_2 WHERE ID_OPERADOR = " . $row_departamento['ID_OPERADOR']);
                //$row_operador = mysqli_fetch_array($query_select_operador);
                $table = $table . "<td style='vertical-align:middle;'>" . $row_departamento['NOMBRE_OPERADOR'] . "</td>";
                $table = $table . "<td style='vertical-align:middle;'><a href='Admin_Departamentos_Operadores.php?id_departamento_editar=" . $row_departamento['ID_TABLA'] . "'><button><img src='Images/pencil_edit.png' title='Editar' width='16' height='16' /></button></a></td>";
                $table = $table . "<td style='vertical-align:middle;'><a href='Admin_Departamentos_Operadores.php?id_departamento_eliminar=" . $row_departamento['ID_TABLA'] . "'><button><img src='Images/gnome_edit_delete.png' title='Eliminar' width='16' height='16'/></button></a></td>";
            $table = $table . "</tr>";
        }
        $info_resultado = array();
        $info_resultado[0] = $table;
        echo json_encode($info_resultado);
        exit();
    }
?>