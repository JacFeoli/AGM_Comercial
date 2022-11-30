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
        $archivo = $_POST['archivo'];
        switch ($archivo) {
            case 'fact':
                require_once('../Includes/Paginacion_Resultado_Archivos_Facturacion.php');
                $id_ano = $_POST['id_ano'];
                $query_select_nombre_ano = mysqli_query($connection, "SELECT NOMBRE FROM anos_2 WHERE ID_ANO = " . $id_ano);
                $row_nombre_ano = mysqli_fetch_array($query_select_nombre_ano);
                $nombre_ano = $row_nombre_ano['NOMBRE'];
                if ($_POST['sw'] == 1) {
                    if (isset($_POST['busqueda_archivo_facturacion']) != "") {
                        if ($_POST['busqueda_archivo_facturacion'] != "") {
                            if ($_POST['busqueda_archivo_facturacion'] != " ") {
                                $busqueda_archivo_facturacion = " AND RUTA LIKE '%" . $_POST['busqueda_archivo_facturacion'] . "%' ";
                            } else {
                                $busqueda_archivo_facturacion = " AND RUTA <> '' ";
                            }
                            $pagination = "pagination-" . $nombre_ano;
                        } else {
                            $busqueda_archivo_facturacion = " AND RUTA <> '' ";
                        }
                    } else {
                        $busqueda_archivo_facturacion = " AND RUTA <> '' ";
                    }
                } else {
                    $busqueda_archivo_facturacion = " AND RUTA <> '' ";
                    $pagination = $_POST['pagination'];
                }
                $query_archivos_facturacion = mysqli_query($connection, "SELECT * FROM archivos_cargados_facturacion_2 "
                                                                      . " WHERE ANO_FACTURA = '" . $nombre_ano . "' "
                                                                      . $busqueda_archivo_facturacion
                                                                      . " ORDER BY MES_FACTURA, DEPARTAMENTO, MUNICIPIO, RUTA");
                $count_archivos_facturacion = mysqli_num_rows($query_archivos_facturacion);
                $info_pagination = array();
                $info_pagination[0] = $pagination;
                if ($count_archivos_facturacion > 0) {
                    $paginacion_count = getPagination($count_archivos_facturacion);
                    $info_pagination[1] = $paginacion_count;
                } else {
                    $info_pagination[1] = 1;
                }
                $info_pagination[2] = $nombre_ano;
                $info_pagination[3] = $id_ano;
                if ($_POST['sw'] == 1) {
                    $info_pagination[4] = $_POST['busqueda_archivo_facturacion'];
                } else {
                    $info_pagination[4] = "";
                }
                break;
            case 'reca':
                require_once('../Includes/Paginacion_Resultado_Archivos_Recaudo.php');
                $id_ano = $_POST['id_ano'];
                $query_select_nombre_ano = mysqli_query($connection, "SELECT NOMBRE FROM anos_2 WHERE ID_ANO = " . $id_ano);
                $row_nombre_ano = mysqli_fetch_array($query_select_nombre_ano);
                $nombre_ano = $row_nombre_ano['NOMBRE'];
                if ($_POST['sw'] == 1) {
                    if (isset($_POST['busqueda_archivo_recaudo']) != "") {
                        if ($_POST['busqueda_archivo_recaudo'] != "") {
                            if ($_POST['busqueda_archivo_recaudo'] != " ") {
                                $busqueda_archivo_recaudo = " AND RUTA LIKE '%" . $_POST['busqueda_archivo_recaudo'] . "%' ";
                            } else {
                                $busqueda_archivo_recaudo = " AND RUTA <> '' ";
                            }
                            $pagination = "pagination-" . $nombre_ano;
                        } else {
                            $busqueda_archivo_recaudo = " AND RUTA <> '' ";
                        }
                    } else {
                        $busqueda_archivo_recaudo = " AND RUTA <> '' ";
                    }
                } else {
                    $busqueda_archivo_recaudo = " AND RUTA <> '' ";
                    $pagination = $_POST['pagination'];
                }
                $query_archivos_recaudo = mysqli_query($connection, "SELECT * FROM archivos_cargados_recaudo_2 "
                                                                  . " WHERE ANO_FACTURA = '" . $nombre_ano . "' "
                                                                  . $busqueda_archivo_recaudo
                                                                  . " ORDER BY MES_FACTURA, DEPARTAMENTO, MUNICIPIO, RUTA");
                $count_archivos_recaudo = mysqli_num_rows($query_archivos_recaudo);
                $info_pagination = array();
                $info_pagination[0] = $pagination;
                if ($count_archivos_recaudo > 0) {
                    $paginacion_count = getPagination($count_archivos_recaudo);
                    $info_pagination[1] = $paginacion_count;
                } else {
                    $info_pagination[1] = 1;
                }
                $info_pagination[2] = $nombre_ano;
                $info_pagination[3] = $id_ano;
                if ($_POST['sw'] == 1) {
                    $info_pagination[4] = $_POST['busqueda_archivo_recaudo'];
                } else {
                    $info_pagination[4] = "";
                }
                break;
            case 'cata':
                require_once('../Includes/Paginacion_Resultado_Archivos_Catastro.php');
                $id_ano = $_POST['id_ano'];
                $query_select_nombre_ano = mysqli_query($connection, "SELECT NOMBRE FROM anos_2 WHERE ID_ANO = " . $id_ano);
                $row_nombre_ano = mysqli_fetch_array($query_select_nombre_ano);
                $nombre_ano = $row_nombre_ano['NOMBRE'];
                if ($_POST['sw'] == 1) {
                    if (isset($_POST['busqueda_archivo_catastro']) != "") {
                        if ($_POST['busqueda_archivo_catastro'] != "") {
                            if ($_POST['busqueda_archivo_catastro'] != " ") {
                                $busqueda_archivo_catastro = " AND RUTA LIKE '%" . $_POST['busqueda_archivo_catastro'] . "%' ";
                            } else {
                                $busqueda_archivo_catastro = " AND RUTA <> '' ";
                            }
                            $pagination = "pagination-" . $nombre_ano;
                        } else {
                            $busqueda_archivo_catastro = " AND RUTA <> '' ";
                        }
                    } else {
                        $busqueda_archivo_catastro = " AND RUTA <> '' ";
                    }
                } else {
                    $busqueda_archivo_catastro = " AND RUTA <> '' ";
                    $pagination = $_POST['pagination'];
                }
                $query_archivos_catastro = mysqli_query($connection, "SELECT * FROM archivos_cargados_catastro_2 "
                                                                   . " WHERE ANO_FACTURA = '" . $nombre_ano . "' "
                                                                   . $busqueda_archivo_catastro
                                                                   . " ORDER BY MES_FACTURA, DEPARTAMENTO, MUNICIPIO, RUTA");
                $count_archivos_catastro = mysqli_num_rows($query_archivos_catastro);
                $info_pagination = array();
                $info_pagination[0] = $pagination;
                if ($count_archivos_catastro > 0) {
                    $paginacion_count = getPagination($count_archivos_catastro);
                    $info_pagination[1] = $paginacion_count;
                } else {
                    $info_pagination[1] = 1;
                }
                $info_pagination[2] = $nombre_ano;
                $info_pagination[3] = $id_ano;
                if ($_POST['sw'] == 1) {
                    $info_pagination[4] = $_POST['busqueda_archivo_catastro'];
                } else {
                    $info_pagination[4] = "";
                }
                break;
            case 'nove':
                require_once('../Includes/Paginacion_Resultado_Archivos_Novedades.php');
                $id_ano = $_POST['id_ano'];
                $query_select_nombre_ano = mysqli_query($connection, "SELECT NOMBRE FROM anos_2 WHERE ID_ANO = " . $id_ano);
                $row_nombre_ano = mysqli_fetch_array($query_select_nombre_ano);
                $nombre_ano = $row_nombre_ano['NOMBRE'];
                if ($_POST['sw'] == 1) {
                    if (isset($_POST['busqueda_archivo_novedades']) != "") {
                        if ($_POST['busqueda_archivo_novedades'] != "") {
                            if ($_POST['busqueda_archivo_novedades'] != " ") {
                                $busqueda_archivo_novedades = " AND RUTA LIKE '%" . $_POST['busqueda_archivo_novedades'] . "%' ";
                            } else {
                                $busqueda_archivo_novedades = " AND RUTA <> '' ";
                            }
                            $pagination = "pagination-" . $nombre_ano;
                        } else {
                            $busqueda_archivo_novedades = " AND RUTA <> '' ";
                        }
                    } else {
                        $busqueda_archivo_novedades = " AND RUTA <> '' ";
                    }
                } else {
                    $busqueda_archivo_novedades = " AND RUTA <> '' ";
                    $pagination = $_POST['pagination'];
                }
                $query_archivos_novedades = mysqli_query($connection, "SELECT * FROM archivos_cargados_novedades_2 "
                                                                   . " WHERE ANO_FACTURA = '" . $nombre_ano . "' "
                                                                   . $busqueda_archivo_novedades
                                                                   . " ORDER BY MES_FACTURA, DEPARTAMENTO, MUNICIPIO, RUTA");
                $count_archivos_novedades = mysqli_num_rows($query_archivos_novedades);
                $info_pagination = array();
                $info_pagination[0] = $pagination;
                if ($count_archivos_novedades > 0) {
                    $paginacion_count = getPagination($count_archivos_novedades);
                    $info_pagination[1] = $paginacion_count;
                } else {
                    $info_pagination[1] = 1;
                }
                $info_pagination[2] = $nombre_ano;
                $info_pagination[3] = $id_ano;
                if ($_POST['sw'] == 1) {
                    $info_pagination[4] = $_POST['busqueda_archivo_novedades'];
                } else {
                    $info_pagination[4] = "";
                }
                break;
            case 'cartera':
                require_once('../Includes/Paginacion_Resultado_Archivos_Cartera_Fallida.php');
                $id_ano = $_POST['id_ano'];
                $query_select_nombre_ano = mysqli_query($connection, "SELECT NOMBRE FROM anos_2 WHERE ID_ANO = " . $id_ano);
                $row_nombre_ano = mysqli_fetch_array($query_select_nombre_ano);
                $nombre_ano = $row_nombre_ano['NOMBRE'];
                if ($_POST['sw'] == 1) {
                    if (isset($_POST['busqueda_archivo_cartera_fallida']) != "") {
                        if ($_POST['busqueda_archivo_cartera_fallida'] != "") {
                            if ($_POST['busqueda_archivo_cartera_fallida'] != " ") {
                                $busqueda_archivo_cartera_fallida = " AND RUTA LIKE '%" . $_POST['busqueda_archivo_cartera_fallida'] . "%' ";
                            } else {
                                $busqueda_archivo_cartera_fallida = " AND RUTA <> '' ";
                            }
                            $pagination = "pagination-" . $nombre_ano;
                        } else {
                            $busqueda_archivo_cartera_fallida = " AND RUTA <> '' ";
                        }
                    } else {
                        $busqueda_archivo_cartera_fallida = " AND RUTA <> '' ";
                    }
                } else {
                    $busqueda_archivo_cartera_fallida = " AND RUTA <> '' ";
                    $pagination = $_POST['pagination'];
                }
                $query_archivos_cartera_fallida = mysqli_query($connection, "SELECT * FROM archivos_cargados_cartera_fallida_2 "
                                                                          . " WHERE ANO_FACTURA = '" . $nombre_ano . "' "
                                                                          . $busqueda_archivo_cartera_fallida
                                                                          . " ORDER BY DEPARTAMENTO, MUNICIPIO, RUTA");
                $count_archivos_cartera_fallida = mysqli_num_rows($query_archivos_cartera_fallida);
                $info_pagination = array();
                $info_pagination[0] = $pagination;
                if ($count_archivos_cartera_fallida > 0) {
                    $paginacion_count = getPagination($count_archivos_cartera_fallida);
                    $info_pagination[1] = $paginacion_count;
                } else {
                    $info_pagination[1] = 1;
                }
                $info_pagination[2] = $nombre_ano;
                $info_pagination[3] = $id_ano;
                if ($_POST['sw'] == 1) {
                    $info_pagination[4] = $_POST['busqueda_archivo_cartera_fallida'];
                } else {
                    $info_pagination[4] = "";
                }
                break;
            case 'refact':
                require_once('../Includes/Paginacion_Resultado_Archivos_Refacturacion.php');
                $id_ano = $_POST['id_ano'];
                $query_select_nombre_ano = mysqli_query($connection, "SELECT NOMBRE FROM anos_2 WHERE ID_ANO = " . $id_ano);
                $row_nombre_ano = mysqli_fetch_array($query_select_nombre_ano);
                $nombre_ano = $row_nombre_ano['NOMBRE'];
                if ($_POST['sw'] == 1) {
                    if (isset($_POST['busqueda_archivo_refacturacion']) != "") {
                        if ($_POST['busqueda_archivo_refacturacion'] != "") {
                            if ($_POST['busqueda_archivo_refacturacion'] != " ") {
                                $busqueda_archivo_refacturacion = " AND RUTA LIKE '%" . $_POST['busqueda_archivo_refacturacion'] . "%' ";
                            } else {
                                $busqueda_archivo_refacturacion = " AND RUTA <> '' ";
                            }
                            $pagination = "pagination-" . $nombre_ano;
                        } else {
                            $busqueda_archivo_refacturacion = " AND RUTA <> '' ";
                        }
                    } else {
                        $busqueda_archivo_refacturacion = " AND RUTA <> '' ";
                    }
                } else {
                    $busqueda_archivo_refacturacion = " AND RUTA <> '' ";
                    $pagination = $_POST['pagination'];
                }
                $query_archivos_refacturacion = mysqli_query($connection, "SELECT * FROM archivos_cargados_refacturacion_2 "
                                                                      . " WHERE ANO_FACTURA = '" . $nombre_ano . "' "
                                                                      . $busqueda_archivo_refacturacion
                                                                      . " ORDER BY MES_FACTURA, DEPARTAMENTO, MUNICIPIO, RUTA");
                $count_archivos_refacturacion = mysqli_num_rows($query_archivos_refacturacion);
                $info_pagination = array();
                $info_pagination[0] = $pagination;
                if ($count_archivos_refacturacion > 0) {
                    $paginacion_count = getPagination($count_archivos_refacturacion);
                    $info_pagination[1] = $paginacion_count;
                } else {
                    $info_pagination[1] = 1;
                }
                $info_pagination[2] = $nombre_ano;
                $info_pagination[3] = $id_ano;
                if ($_POST['sw'] == 1) {
                    $info_pagination[4] = $_POST['busqueda_archivo_refacturacion'];
                } else {
                    $info_pagination[4] = "";
                }
                break;
            case 'oymri':
                require_once('../Includes/Paginacion_Resultado_Archivos_OYM_RI.php');
                $id_ano = $_POST['id_ano'];
                $query_select_nombre_ano = mysqli_query($connection, "SELECT NOMBRE FROM anos_2 WHERE ID_ANO = " . $id_ano);
                $row_nombre_ano = mysqli_fetch_array($query_select_nombre_ano);
                $nombre_ano = $row_nombre_ano['NOMBRE'];
                if ($_POST['sw'] == 1) {
                    if (isset($_POST['busqueda_archivo_oymri']) != "") {
                        if ($_POST['busqueda_archivo_oymri'] != "") {
                            if ($_POST['busqueda_archivo_oymri'] != " ") {
                                $busqueda_archivo_oymri = " AND RUTA LIKE '%" . $_POST['busqueda_archivo_oymri'] . "%' ";
                            } else {
                                $busqueda_archivo_oymri = " AND RUTA <> '' ";
                            }
                            $pagination = "pagination-" . $nombre_ano;
                        } else {
                            $busqueda_archivo_oymri = " AND RUTA <> '' ";
                        }
                    } else {
                        $busqueda_archivo_oymri = " AND RUTA <> '' ";
                    }
                } else {
                    $busqueda_archivo_oymri = " AND RUTA <> '' ";
                    $pagination = $_POST['pagination'];
                }
                $query_archivos_oym_ri = mysqli_query($connection, "SELECT * FROM archivos_cargados_oymri_2 "
                                                                          . " WHERE ANO_FACTURA = '" . $nombre_ano . "' "
                                                                          . $busqueda_archivo_oymri
                                                                          . " ORDER BY PERIODO, RUTA");
                $count_archivos_oym_ri = mysqli_num_rows($query_archivos_oym_ri);
                $info_pagination = array();
                $info_pagination[0] = $pagination;
                if ($count_archivos_oym_ri > 0) {
                    $paginacion_count = getPagination($count_archivos_oym_ri);
                    $info_pagination[1] = $paginacion_count;
                } else {
                    $info_pagination[1] = 1;
                }
                $info_pagination[2] = $nombre_ano;
                $info_pagination[3] = $id_ano;
                if ($_POST['sw'] == 1) {
                    $info_pagination[4] = $_POST['busqueda_archivo_oymri'];
                } else {
                    $info_pagination[4] = "";
                }
                break;
            case 'fact_comer':
                require_once('../Includes/Paginacion_Resultado_Archivos_Facturacion.php');
                $id_ano = $_POST['id_ano'];
                $query_select_nombre_ano = mysqli_query($connection, "SELECT NOMBRE FROM anos_2 WHERE ID_ANO = " . $id_ano);
                $row_nombre_ano = mysqli_fetch_array($query_select_nombre_ano);
                $nombre_ano = $row_nombre_ano['NOMBRE'];
                if ($_POST['sw'] == 1) {
                    if (isset($_POST['busqueda_archivo_facturacion']) != "") {
                        if ($_POST['busqueda_archivo_facturacion'] != "") {
                            if ($_POST['busqueda_archivo_facturacion'] != " ") {
                                $busqueda_archivo_facturacion = " AND RUTA LIKE '%" . $_POST['busqueda_archivo_facturacion'] . "%' ";
                            } else {
                                $busqueda_archivo_facturacion = " AND RUTA <> '' ";
                            }
                            $pagination = "pagination-" . $nombre_ano;
                        } else {
                            $busqueda_archivo_facturacion = " AND RUTA <> '' ";
                        }
                    } else {
                        $busqueda_archivo_facturacion = " AND RUTA <> '' ";
                    }
                } else {
                    $busqueda_archivo_facturacion = " AND RUTA <> '' ";
                    $pagination = $_POST['pagination'];
                }
                $query_archivos_facturacion = mysqli_query($connection, "SELECT * FROM archivos_cargados_fact_comer_2 "
                                                                      . " WHERE ANO_FACTURA = '" . $nombre_ano . "' "
                                                                      . $busqueda_archivo_facturacion
                                                                      . " ORDER BY PERIODO, ID_COMERCIALIZADOR, RUTA");
                $count_archivos_facturacion = mysqli_num_rows($query_archivos_facturacion);
                $info_pagination = array();
                $info_pagination[0] = $pagination;
                if ($count_archivos_facturacion > 0) {
                    $paginacion_count = getPagination($count_archivos_facturacion);
                    $info_pagination[1] = $paginacion_count;
                } else {
                    $info_pagination[1] = 1;
                }
                $info_pagination[2] = $nombre_ano;
                $info_pagination[3] = $id_ano;
                if ($_POST['sw'] == 1) {
                    $info_pagination[4] = $_POST['busqueda_archivo_facturacion'];
                } else {
                    $info_pagination[4] = "";
                }
                break;
        }
        echo json_encode($info_pagination);
        exit();
    }
?>