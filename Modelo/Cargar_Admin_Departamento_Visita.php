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
                    $busqueda_departamento = " WHERE NOMBRE LIKE '%" . $_POST['busqueda_departamento'] . "%' ";
                } else {
                    $busqueda_departamento = " WHERE NOMBRE <> ''";
                }
            } else {
                $busqueda_departamento = " WHERE NOMBRE <> ''";
            }
        } else {
            $busqueda_departamento = " WHERE NOMBRE <> ''";
        }
        $page = $_POST['page'];
        if ($page == 1) {
            $pageLimit = 0;
        } else {
            $pageLimit = PAGE_PER_DEPARTAMENTO * ($page - 1);
        }
        $query_departamento = mysqli_query($connection, "SELECT *
                                                           FROM departamentos_visitas_2
                                                           $busqueda_departamento
                                                          ORDER BY NOMBRE
                                                          LIMIT " . $pageLimit . ", " . PAGE_PER_DEPARTAMENTO);
        $table = "";
        while ($row_departamento = mysqli_fetch_assoc($query_departamento)) {
            $table = $table . "<tr>";
                $table = $table . "<td style='vertical-align:middle;'>" . $row_departamento['ID_DEPARTAMENTO'] . "</td>";
                $table = $table . "<td style='vertical-align:middle;'>" . $row_departamento['NOMBRE'] . "</td>";
                $table = $table . "<td style='vertical-align:middle;'><a href='Admin_Departamentos_Visitas.php?id_departamento_editar=" . $row_departamento['ID_TABLA'] . "'><button><img src='Images/pencil_edit.png' title='Editar' width='16' height='16' /></button></a></td>";
                $table = $table . "<td style='vertical-align:middle;'><a href='Admin_Departamentos_Visitas.php?id_departamento_eliminar=" . $row_departamento['ID_TABLA'] . "'><button><img src='Images/gnome_edit_delete.png' title='Eliminar' width='16' height='16'/></button></a></td>";
            $table = $table . "</tr>";
        }
        $info_resultado = array();
        $info_resultado[0] = $table;
        echo json_encode($info_resultado);
        exit();
    }
?>