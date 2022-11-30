<?php
    session_start();
    require_once('../Includes/Config.php');
    if ($_SESSION['timeout'] + 60 * 60 < time()) {
        session_unset();
        session_destroy();
        $ruta = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
        header("Location:$ruta/Login.php");
    } else {
        $_SESSION['timeout'] = time();
        $archivo = $_GET['archivo'];
        switch ($archivo) {
            case 'bitacora':
                $file = $_GET['file'];
                $id_tabla_archivo = $_GET['id_tabla_archivo'];
                $file_id = $_GET['file_id'];
                $query_select_info_tipo_visita = mysqli_query($connection, "SELECT * "
                                                                         . "  FROM bitacora_libretas_2 BT, tipo_visitas_2 TP "
                                                                         . " WHERE BT.ID_TIPO_VISITA = TP.ID_TIPO_VISITA "
                                                                         . "   AND BT.ID_TABLA = " . $id_tabla_archivo);
                $row_info_tipo_visita = mysqli_fetch_array($query_select_info_tipo_visita);
                $query_select_id_municipio = mysqli_query($connection, "SELECT * FROM municipios_libreta_2 "
                                                                     . " WHERE ID_MUNICIPIO_LIBRETA = " . $row_info_tipo_visita['ID_MUNICIPIO_LIBRETA']);
                $row_id_municipio = mysqli_fetch_array($query_select_id_municipio);
                $query_select_nombre_municipio = mysqli_query($connection, "SELECT NOMBRE FROM municipios_visitas_2 "
                                                                         . " WHERE ID_DEPARTAMENTO = " . $row_id_municipio['ID_DEPARTAMENTO'] . ""
                                                                         . "   AND ID_MUNICIPIO = " . $row_id_municipio['ID_MUNICIPIO']);
                $row_nombre_municipio = mysqli_fetch_array($query_select_nombre_municipio);
                if (file_exists("../Files/" . $row_nombre_municipio['NOMBRE'] . "/" . $file)) {
                    unlink("../Files/" . $row_nombre_municipio['NOMBRE'] . "/" . $file);
                    mysqli_query($connection, "DELETE FROM bitacora_libretas_archivos_2 WHERE ID_TABLA = " . $file_id);
                }
                echo "<script>";
                    echo "document.location.href = '../Bitacora_Acuerdos.php?id_bitacora_libreta_archivo=" . $id_tabla_archivo . "'";
                echo "</script>";
                break;
            case 'acuerdo':
                $file = $_GET['file'];
                $id_tabla_archivo = $_GET['id_tabla_archivo'];
                $file_id = $_GET['file_id'];
                $query_select_id_municipio = mysqli_query($connection, "SELECT * FROM municipios_libreta_2 "
                                                                     . " WHERE ID_MUNICIPIO_LIBRETA = " . $id_tabla_archivo);
                $row_id_municipio = mysqli_fetch_array($query_select_id_municipio);
                $query_select_nombre_municipio = mysqli_query($connection, "SELECT NOMBRE FROM municipios_visitas_2 "
                                                                         . " WHERE ID_DEPARTAMENTO = " . $row_id_municipio['ID_DEPARTAMENTO'] . ""
                                                                         . "   AND ID_MUNICIPIO = " . $row_id_municipio['ID_MUNICIPIO']);
                $row_nombre_municipio = mysqli_fetch_array($query_select_nombre_municipio);
                if (file_exists("../Files/" . $row_nombre_municipio['NOMBRE'] . "/" . $file)) {
                    unlink("../Files/" . $row_nombre_municipio['NOMBRE'] . "/" . $file);
                    mysqli_query($connection, "DELETE FROM municipios_libreta_archivos_2 WHERE ID_TABLA = " . $file_id);
                }
                echo "<script>";
                    echo "document.location.href = '../Municipios_Libretas.php?id_municipio_libreta_archivo=" . $id_tabla_archivo . "'";
                echo "</script>";
                break;
            case 'recaudo':
                $file = $_GET['file'];
                $id_tabla_archivo = $_GET['id_tabla_archivo'];
                $file_id = $_GET['file_id'];
                $query_select_id_municipio = mysqli_query($connection, "SELECT * FROM municipios_libreta_2 "
                                                                     . " WHERE ID_MUNICIPIO_LIBRETA = " . $id_tabla_archivo);
                $row_id_municipio = mysqli_fetch_array($query_select_id_municipio);
                $query_select_nombre_municipio = mysqli_query($connection, "SELECT NOMBRE FROM municipios_visitas_2 "
                                                                         . " WHERE ID_DEPARTAMENTO = " . $row_id_municipio['ID_DEPARTAMENTO'] . ""
                                                                         . "   AND ID_MUNICIPIO = " . $row_id_municipio['ID_MUNICIPIO']);
                $row_nombre_municipio = mysqli_fetch_array($query_select_nombre_municipio);
                if (file_exists("../Files/" . $row_nombre_municipio['NOMBRE'] . "/" . $file)) {
                    unlink("../Files/" . $row_nombre_municipio['NOMBRE'] . "/" . $file);
                    mysqli_query($connection, "DELETE FROM recaudo_especiales_archivos_2 WHERE ID_TABLA = " . $file_id);
                }
                echo "<script>";
                    echo "document.location.href = '../Recaudo_Especiales.php?id_reca_especial_editar=" . $id_tabla_archivo . "'";
                echo "</script>";
                break;
            case 'recaudo_municipal':
                $file = $_GET['file'];
                $id_tabla_archivo = $_GET['id_tabla_archivo'];
                $file_id = $_GET['file_id'];
                $query_select_id_facturacion = mysqli_query($connection, "SELECT * FROM recaudo_municipales_2 "
                                                                       . " WHERE ID_RECAUDO = " . $id_tabla_archivo);
                $row_id_facturacion = mysqli_fetch_array($query_select_id_facturacion);
                $query_select_info_facturacion = mysqli_query($connection, "SELECT * "
                                                                         . "  FROM facturacion_municipales_2 "
                                                                         . " WHERE ID_FACTURACION = " . $row_id_facturacion['ID_FACTURACION']);
                $row_info_facturacion = mysqli_fetch_array($query_select_info_facturacion);
                $query_select_nombre_municipio = mysqli_query($connection, "SELECT NOMBRE FROM municipios_visitas_2 "
                                                                         . " WHERE ID_DEPARTAMENTO = " . $row_info_facturacion['ID_COD_DPTO'] . ""
                                                                         . "   AND ID_MUNICIPIO = " . $row_info_facturacion['ID_COD_MPIO']);
                $row_nombre_municipio = mysqli_fetch_array($query_select_nombre_municipio);
                if (file_exists("../Files/" . $row_nombre_municipio['NOMBRE'] . "/" . $file)) {
                    unlink("../Files/" . $row_nombre_municipio['NOMBRE'] . "/" . $file);
                    mysqli_query($connection, "DELETE FROM recaudo_municipales_archivos_2 WHERE ID_TABLA = " . $file_id);
                }
                echo "<script>";
                    echo "document.location.href = '../Recaudo_Municipales.php?id_reca_municipal_editar=" . $id_tabla_archivo . "'";
                echo "</script>";
                break;
            case 'pqr':
                $file = $_GET['file'];
                $id_tabla_archivo = $_GET['id_tabla_archivo'];
                $file_id = $_GET['file_id'];
                $query_select_info_pqr = mysqli_query($connection, "SELECT * "
                                                                 . "  FROM pqr_2 "
                                                                 . " WHERE ID_PQR = " . $id_tabla_archivo);
                $row_info_pqr = mysqli_fetch_array($query_select_info_pqr);
                $query_select_nombre_municipio = mysqli_query($connection, "SELECT NOMBRE FROM municipios_visitas_2 "
                                                                         . " WHERE ID_DEPARTAMENTO = " . $row_info_pqr['ID_COD_DPTO'] . ""
                                                                         . "   AND ID_MUNICIPIO = " . $row_info_pqr['ID_COD_MPIO']);
                $row_nombre_municipio = mysqli_fetch_array($query_select_nombre_municipio);
                if (file_exists("../Files/" . $row_nombre_municipio['NOMBRE'] . "/" . $file)) {
                    unlink("../Files/" . $row_nombre_municipio['NOMBRE'] . "/" . $file);
                    mysqli_query($connection, "DELETE FROM pqr_archivos_2 WHERE ID_TABLA = " . $file_id);
                }
                echo "<script>";
                    echo "document.location.href = '../P_Q_R.php?id_pqr_editar=" . $id_tabla_archivo . "'";
                echo "</script>";
                break;
        }
    }
?>