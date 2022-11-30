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
                $nombre_ano = $_POST['nombre_ano'];
                if ($_POST['sw'] == 1) {
                    if (isset($_POST['busqueda_archivo_facturacion']) != "") {
                        if ($_POST['busqueda_archivo_facturacion'] != "") {
                            if ($_POST['busqueda_archivo_facturacion'] != " ") {
                                $busqueda_archivo_facturacion = " AND RUTA LIKE '%" . $_POST['busqueda_archivo_facturacion'] . "%' ";
                            } else {
                                $busqueda_archivo_facturacion = " AND RUTA <> '' ";
                            }
                        } else {
                            $busqueda_archivo_facturacion = " AND RUTA <> '' ";
                        }
                    } else {
                        $busqueda_archivo_facturacion = " AND RUTA <> '' ";
                    }
                } else {
                    $busqueda_archivo_facturacion = " AND RUTA <> '' ";
                }
                $page = $_POST['page'];
                if ($page == 1) {
                    $pageLimit = 0;
                } else {
                    $pageLimit = PAGE_PER_RESULTADO_ARCHIVO_FACTURACION * ($page - 1);
                }
                $query_count_registros_archivos_facturacion = mysqli_query($connection, "SELECT * FROM archivos_cargados_facturacion_2 "
                                                                                      . " WHERE ANO_FACTURA = '" . $nombre_ano . "' "
                                                                                      . $busqueda_archivo_facturacion
                                                                                      . " ORDER BY MES_FACTURA, DEPARTAMENTO, MUNICIPIO, RUTA ");
                $count_registros_archivos_facturacion = mysqli_num_rows($query_count_registros_archivos_facturacion);
                $query_archivos_facturacion = mysqli_query($connection, "SELECT * FROM archivos_cargados_facturacion_2 "
                                                                      . " WHERE ANO_FACTURA = '" . $nombre_ano . "' "
                                                                      . $busqueda_archivo_facturacion
                                                                      . " ORDER BY MES_FACTURA, DEPARTAMENTO, MUNICIPIO, RUTA "
                                                                      . " LIMIT " . $pageLimit . ", " . PAGE_PER_RESULTADO_ARCHIVO_FACTURACION);
                $table = "";
                $info_resultado = array();
                while ($row_archivos_facturacion = mysqli_fetch_assoc($query_archivos_facturacion)) {
                    $table = $table . "<tr>";
                        $table = $table . "<td style='vertical-align: middle;'>" . $row_archivos_facturacion['MES_FACTURA'] . "</td>";
                        $table = $table . "<td style='vertical-align: middle;'>" . $row_archivos_facturacion['DEPARTAMENTO'] . "</td>";
                        $table = $table . "<td style='vertical-align: middle;'>" . $row_archivos_facturacion['MUNICIPIO'] . "</td>";
                        $table = $table . "<td style='vertical-align: middle;'>" . $row_archivos_facturacion['RUTA'] . "</td>";
                        $table = $table . "<td style='vertical-align: middle;'><button type='button' data-toggle='modal' id='" . $row_archivos_facturacion['ID_TABLA'] . "' data-target='#modalDetalleArchivoFacturacion'><img src='Images/search.png' title='Detalle' width='16' height='16' /></button></td>";
                    $table = $table . "</tr>";
                }
                $info_resultado[0] = $nombre_ano;
                $info_resultado[1] = $table;
                $info_resultado[2] = $id_ano;
                $info_resultado[3] = $count_registros_archivos_facturacion;
                break;
            case 'reca':
                require_once('../Includes/Paginacion_Resultado_Archivos_Recaudo.php');
                $id_ano = $_POST['id_ano'];
                $nombre_ano = $_POST['nombre_ano'];
                if ($_POST['sw'] == 1) {
                    if (isset($_POST['busqueda_archivo_recaudo']) != "") {
                        if ($_POST['busqueda_archivo_recaudo'] != "") {
                            if ($_POST['busqueda_archivo_recaudo'] != " ") {
                                $busqueda_archivo_recaudo = " AND RUTA LIKE '%" . $_POST['busqueda_archivo_recaudo'] . "%' ";
                            } else {
                                $busqueda_archivo_recaudo = " AND RUTA <> '' ";
                            }
                        } else {
                            $busqueda_archivo_recaudo = " AND RUTA <> '' ";
                        }
                    } else {
                        $busqueda_archivo_recaudo = " AND RUTA <> '' ";
                    }
                } else {
                    $busqueda_archivo_recaudo = " AND RUTA <> '' ";
                }
                $page = $_POST['page'];
                if ($page == 1) {
                    $pageLimit = 0;
                } else {
                    $pageLimit = PAGE_PER_RESULTADO_ARCHIVO_RECAUDO * ($page - 1);
                }
                $query_count_registros_archivos_recaudo = mysqli_query($connection, "SELECT * FROM archivos_cargados_recaudo_2 "
                                                                                  . " WHERE ANO_FACTURA = '" . $nombre_ano . "' "
                                                                                  . $busqueda_archivo_recaudo
                                                                                  . " ORDER BY MES_FACTURA, DEPARTAMENTO, MUNICIPIO, RUTA ");
                $count_registros_archivos_recaudo = mysqli_num_rows($query_count_registros_archivos_recaudo);
                $query_archivos_recaudo = mysqli_query($connection, "SELECT * FROM archivos_cargados_recaudo_2 "
                                                                  . " WHERE ANO_FACTURA = '" . $nombre_ano . "' "
                                                                  . $busqueda_archivo_recaudo
                                                                  . " ORDER BY MES_FACTURA, DEPARTAMENTO, MUNICIPIO, RUTA "
                                                                  . " LIMIT " . $pageLimit . ", " . PAGE_PER_RESULTADO_ARCHIVO_RECAUDO);
                $table = "";
                $info_resultado = array();
                while ($row_archivos_recaudo = mysqli_fetch_assoc($query_archivos_recaudo)) {
                    $table = $table . "<tr>";
                        $table = $table . "<td style='vertical-align: middle;'>" . $row_archivos_recaudo['MES_FACTURA'] . "</td>";
                        $table = $table . "<td style='vertical-align: middle;'>" . $row_archivos_recaudo['DEPARTAMENTO'] . "</td>";
                        $table = $table . "<td style='vertical-align: middle;'>" . $row_archivos_recaudo['MUNICIPIO'] . "</td>";
                        $table = $table . "<td style='vertical-align: middle;'>" . $row_archivos_recaudo['RUTA'] . "</td>";
                        $table = $table . "<td style='vertical-align: middle;'><button type='button' data-toggle='modal' id='" . $row_archivos_recaudo['ID_TABLA'] . "' data-target='#modalDetalleArchivoRecaudo'><img src='Images/search.png' title='Detalle' width='16' height='16' /></button></td>";
                    $table = $table . "</tr>";
                }
                $info_resultado[0] = $nombre_ano;
                $info_resultado[1] = $table;
                $info_resultado[2] = $id_ano;
                $info_resultado[3] = $count_registros_archivos_recaudo;
                break;
            case 'cata':
                require_once('../Includes/Paginacion_Resultado_Archivos_Catastro.php');
                $id_ano = $_POST['id_ano'];
                $nombre_ano = $_POST['nombre_ano'];
                if ($_POST['sw'] == 1) {
                    if (isset($_POST['busqueda_archivo_catastro']) != "") {
                        if ($_POST['busqueda_archivo_catastro'] != "") {
                            if ($_POST['busqueda_archivo_catastro'] != " ") {
                                $busqueda_archivo_catastro = " AND RUTA LIKE '%" . $_POST['busqueda_archivo_catastro'] . "%' ";
                            } else {
                                $busqueda_archivo_catastro = " AND RUTA <> '' ";
                            }
                        } else {
                            $busqueda_archivo_catastro = " AND RUTA <> '' ";
                        }
                    } else {
                        $busqueda_archivo_catastro = " AND RUTA <> '' ";
                    }
                } else {
                    $busqueda_archivo_catastro = " AND RUTA <> '' ";
                }
                $page = $_POST['page'];
                if ($page == 1) {
                    $pageLimit = 0;
                } else {
                    $pageLimit = PAGE_PER_RESULTADO_ARCHIVO_CATASTRO * ($page - 1);
                }
                $query_count_registros_archivos_catastro = mysqli_query($connection, "SELECT * FROM archivos_cargados_catastro_2 "
                                                                                   . " WHERE ANO_FACTURA = '" . $nombre_ano . "' "
                                                                                   . $busqueda_archivo_catastro
                                                                                   . " ORDER BY MES_FACTURA, DEPARTAMENTO, MUNICIPIO, RUTA ");
                $count_registros_archivos_catastro = mysqli_num_rows($query_count_registros_archivos_catastro);
                $query_archivos_catastro = mysqli_query($connection, "SELECT * FROM archivos_cargados_catastro_2 "
                                                                   . " WHERE ANO_FACTURA = '" . $nombre_ano . "' "
                                                                   . $busqueda_archivo_catastro
                                                                   . " ORDER BY MES_FACTURA, DEPARTAMENTO, MUNICIPIO, RUTA "
                                                                   . " LIMIT " . $pageLimit . ", " . PAGE_PER_RESULTADO_ARCHIVO_CATASTRO);
                $table = "";
                $info_resultado = array();
                while ($row_archivos_catastro = mysqli_fetch_assoc($query_archivos_catastro)) {
                    $table = $table . "<tr>";
                        $table = $table . "<td style='vertical-align: middle;'>" . $row_archivos_catastro['MES_FACTURA'] . "</td>";
                        $table = $table . "<td style='vertical-align: middle;'>" . $row_archivos_catastro['DEPARTAMENTO'] . "</td>";
                        $table = $table . "<td style='vertical-align: middle;'>" . $row_archivos_catastro['MUNICIPIO'] . "</td>";
                        $table = $table . "<td style='vertical-align: middle;'>" . $row_archivos_catastro['RUTA'] . "</td>";
                        $table = $table . "<td style='vertical-align: middle;'><button type='button' data-toggle='modal' id='" . $row_archivos_catastro['ID_TABLA'] . "' data-target='#modalDetalleArchivoCatastro'><img src='Images/search.png' title='Detalle' width='16' height='16' /></button></td>";
                    $table = $table . "</tr>";
                }
                $info_resultado[0] = $nombre_ano;
                $info_resultado[1] = $table;
                $info_resultado[2] = $id_ano;
                $info_resultado[3] = $count_registros_archivos_catastro;
                break;
            case 'nove':
                require_once('../Includes/Paginacion_Resultado_Archivos_Novedades.php');
                $id_ano = $_POST['id_ano'];
                $nombre_ano = $_POST['nombre_ano'];
                if ($_POST['sw'] == 1) {
                    if (isset($_POST['busqueda_archivo_novedades']) != "") {
                        if ($_POST['busqueda_archivo_novedades'] != "") {
                            if ($_POST['busqueda_archivo_novedades'] != " ") {
                                $busqueda_archivo_novedades = " AND RUTA LIKE '%" . $_POST['busqueda_archivo_novedades'] . "%' ";
                            } else {
                                $busqueda_archivo_novedades = " AND RUTA <> '' ";
                            }
                        } else {
                            $busqueda_archivo_novedades = " AND RUTA <> '' ";
                        }
                    } else {
                        $busqueda_archivo_novedades = " AND RUTA <> '' ";
                    }
                } else {
                    $busqueda_archivo_novedades = " AND RUTA <> '' ";
                }
                $page = $_POST['page'];
                if ($page == 1) {
                    $pageLimit = 0;
                } else {
                    $pageLimit = PAGE_PER_RESULTADO_ARCHIVO_NOVEDADES * ($page - 1);
                }
                $query_count_registros_archivos_novedades = mysqli_query($connection, "SELECT * FROM archivos_cargados_novedades_2 "
                                                                                   . " WHERE ANO_FACTURA = '" . $nombre_ano . "' "
                                                                                   . $busqueda_archivo_novedades
                                                                                   . " ORDER BY MES_FACTURA, DEPARTAMENTO, MUNICIPIO, RUTA ");
                $count_registros_archivos_novedades = mysqli_num_rows($query_count_registros_archivos_novedades);
                $query_archivos_novedades = mysqli_query($connection, "SELECT * FROM archivos_cargados_novedades_2 "
                                                                   . " WHERE ANO_FACTURA = '" . $nombre_ano . "' "
                                                                   . $busqueda_archivo_novedades
                                                                   . " ORDER BY MES_FACTURA, DEPARTAMENTO, MUNICIPIO, RUTA "
                                                                   . " LIMIT " . $pageLimit . ", " . PAGE_PER_RESULTADO_ARCHIVO_NOVEDADES);
                $table = "";
                $info_resultado = array();
                while ($row_archivos_novedades = mysqli_fetch_assoc($query_archivos_novedades)) {
                    $table = $table . "<tr>";
                        $table = $table . "<td style='vertical-align: middle;'>" . $row_archivos_novedades['MES_FACTURA'] . "</td>";
                        $table = $table . "<td style='vertical-align: middle;'>" . $row_archivos_novedades['DEPARTAMENTO'] . "</td>";
                        $table = $table . "<td style='vertical-align: middle;'>" . $row_archivos_novedades['MUNICIPIO'] . "</td>";
                        $table = $table . "<td style='vertical-align: middle;'>" . $row_archivos_novedades['RUTA'] . "</td>";
                        $table = $table . "<td style='vertical-align: middle;'><button type='button' data-toggle='modal' id='" . $row_archivos_novedades['ID_TABLA'] . "' data-target='#modalDetalleArchivoNovedades'><img src='Images/search.png' title='Detalle' width='16' height='16' /></button></td>";
                    $table = $table . "</tr>";
                }
                $info_resultado[0] = $nombre_ano;
                $info_resultado[1] = $table;
                $info_resultado[2] = $id_ano;
                $info_resultado[3] = $count_registros_archivos_novedades;
                break;
            case 'cartera':
                require_once('../Includes/Paginacion_Resultado_Archivos_Cartera_Fallida.php');
                $id_ano = $_POST['id_ano'];
                $nombre_ano = $_POST['nombre_ano'];
                if ($_POST['sw'] == 1) {
                    if (isset($_POST['busqueda_archivo_cartera_fallida']) != "") {
                        if ($_POST['busqueda_archivo_cartera_fallida'] != "") {
                            if ($_POST['busqueda_archivo_cartera_fallida'] != " ") {
                                $busqueda_archivo_cartera_fallida = " AND RUTA LIKE '%" . $_POST['busqueda_archivo_cartera_fallida'] . "%' ";
                            } else {
                                $busqueda_archivo_cartera_fallida = " AND RUTA <> '' ";
                            }
                        } else {
                            $busqueda_archivo_cartera_fallida = " AND RUTA <> '' ";
                        }
                    } else {
                        $busqueda_archivo_cartera_fallida = " AND RUTA <> '' ";
                    }
                } else {
                    $busqueda_archivo_cartera_fallida = " AND RUTA <> '' ";
                }
                $page = $_POST['page'];
                if ($page == 1) {
                    $pageLimit = 0;
                } else {
                    $pageLimit = PAGE_PER_RESULTADO_ARCHIVO_CARTERA_FALLIDA * ($page - 1);
                }
                $query_count_registros_archivos_cartera_fallida = mysqli_query($connection, "SELECT * FROM archivos_cargados_cartera_fallida_2 "
                                                                                          . " WHERE ANO_FACTURA = '" . $nombre_ano . "' "
                                                                                          . $busqueda_archivo_cartera_fallida
                                                                                          . " ORDER BY DEPARTAMENTO, MUNICIPIO, RUTA ");
                $count_registros_archivos_cartera_fallida = mysqli_num_rows($query_count_registros_archivos_cartera_fallida);
                $query_archivos_cartera_fallida = mysqli_query($connection, "SELECT * FROM archivos_cargados_cartera_fallida_2 "
                                                                          . " WHERE ANO_FACTURA = '" . $nombre_ano . "' "
                                                                          . $busqueda_archivo_cartera_fallida
                                                                          . " ORDER BY DEPARTAMENTO, MUNICIPIO, RUTA "
                                                                          . " LIMIT " . $pageLimit . ", " . PAGE_PER_RESULTADO_ARCHIVO_CARTERA_FALLIDA);
                $table = "";
                $info_resultado = array();
                while ($row_archivos_cartera_fallida = mysqli_fetch_assoc($query_archivos_cartera_fallida)) {
                    $table = $table . "<tr>";
                        $table = $table . "<td style='vertical-align: middle;'>" . $row_archivos_cartera_fallida['DEPARTAMENTO'] . "</td>";
                        $table = $table . "<td style='vertical-align: middle;'>" . $row_archivos_cartera_fallida['MUNICIPIO'] . "</td>";
                        $table = $table . "<td style='vertical-align: middle;'>" . $row_archivos_cartera_fallida['RUTA'] . "</td>";
                        $table = $table . "<td style='vertical-align: middle;'><button type='button' data-toggle='modal' id='" . $row_archivos_cartera_fallida['ID_TABLA'] . "' data-target='#modalDetalleArchivoCarteraFallida'><img src='Images/search.png' title='Detalle' width='16' height='16' /></button></td>";
                    $table = $table . "</tr>";
                }
                $info_resultado[0] = $nombre_ano;
                $info_resultado[1] = $table;
                $info_resultado[2] = $id_ano;
                $info_resultado[3] = $count_registros_archivos_cartera_fallida;
                break;
            case 'refact':
                require_once('../Includes/Paginacion_Resultado_Archivos_Refacturacion.php');
                $id_ano = $_POST['id_ano'];
                $nombre_ano = $_POST['nombre_ano'];
                if ($_POST['sw'] == 1) {
                    if (isset($_POST['busqueda_archivo_refacturacion']) != "") {
                        if ($_POST['busqueda_archivo_refacturacion'] != "") {
                            if ($_POST['busqueda_archivo_refacturacion'] != " ") {
                                $busqueda_archivo_refacturacion = " AND RUTA LIKE '%" . $_POST['busqueda_archivo_refacturacion'] . "%' ";
                            } else {
                                $busqueda_archivo_refacturacion = " AND RUTA <> '' ";
                            }
                        } else {
                            $busqueda_archivo_refacturacion = " AND RUTA <> '' ";
                        }
                    } else {
                        $busqueda_archivo_refacturacion = " AND RUTA <> '' ";
                    }
                } else {
                    $busqueda_archivo_refacturacion = " AND RUTA <> '' ";
                }
                $page = $_POST['page'];
                if ($page == 1) {
                    $pageLimit = 0;
                } else {
                    $pageLimit = PAGE_PER_RESULTADO_ARCHIVO_REFACTURACION * ($page - 1);
                }
                $query_count_registros_archivos_refacturacion = mysqli_query($connection, "SELECT * FROM archivos_cargados_refacturacion_2 "
                                                                                      . " WHERE ANO_FACTURA = '" . $nombre_ano . "' "
                                                                                      . $busqueda_archivo_refacturacion
                                                                                      . " ORDER BY MES_FACTURA, DEPARTAMENTO, MUNICIPIO, RUTA ");
                $count_registros_archivos_refacturacion = mysqli_num_rows($query_count_registros_archivos_refacturacion);
                $query_archivos_refacturacion = mysqli_query($connection, "SELECT * FROM archivos_cargados_refacturacion_2 "
                                                                      . " WHERE ANO_FACTURA = '" . $nombre_ano . "' "
                                                                      . $busqueda_archivo_refacturacion
                                                                      . " ORDER BY MES_FACTURA, DEPARTAMENTO, MUNICIPIO, RUTA "
                                                                      . " LIMIT " . $pageLimit . ", " . PAGE_PER_RESULTADO_ARCHIVO_REFACTURACION);
                $table = "";
                $info_resultado = array();
                while ($row_archivos_refacturacion = mysqli_fetch_assoc($query_archivos_refacturacion)) {
                    $table = $table . "<tr>";
                        $table = $table . "<td style='vertical-align: middle;'>" . $row_archivos_refacturacion['MES_FACTURA'] . "</td>";
                        $table = $table . "<td style='vertical-align: middle;'>" . $row_archivos_refacturacion['DEPARTAMENTO'] . "</td>";
                        $table = $table . "<td style='vertical-align: middle;'>" . $row_archivos_refacturacion['MUNICIPIO'] . "</td>";
                        $table = $table . "<td style='vertical-align: middle;'>" . $row_archivos_refacturacion['RUTA'] . "</td>";
                        $table = $table . "<td style='vertical-align: middle;'><button type='button' data-toggle='modal' id='" . $row_archivos_refacturacion['ID_TABLA'] . "' data-target='#modalDetalleArchivoRefacturacion'><img src='Images/search.png' title='Detalle' width='16' height='16' /></button></td>";
                    $table = $table . "</tr>";
                }
                $info_resultado[0] = $nombre_ano;
                $info_resultado[1] = $table;
                $info_resultado[2] = $id_ano;
                $info_resultado[3] = $count_registros_archivos_refacturacion;
                break;
            case 'oymri':
                require_once('../Includes/Paginacion_Resultado_Archivos_OYM_RI.php');
                $id_ano = $_POST['id_ano'];
                $nombre_ano = $_POST['nombre_ano'];
                if ($_POST['sw'] == 1) {
                    if (isset($_POST['busqueda_archivo_oymri']) != "") {
                        if ($_POST['busqueda_archivo_oymri'] != "") {
                            if ($_POST['busqueda_archivo_oymri'] != " ") {
                                $busqueda_archivo_oymri = " AND RUTA LIKE '%" . $_POST['busqueda_archivo_oymri'] . "%' ";
                            } else {
                                $busqueda_archivo_oymri = " AND RUTA <> '' ";
                            }
                        } else {
                            $busqueda_archivo_oymri = " AND RUTA <> '' ";
                        }
                    } else {
                        $busqueda_archivo_oymri = " AND RUTA <> '' ";
                    }
                } else {
                    $busqueda_archivo_oymri = " AND RUTA <> '' ";
                }
                $page = $_POST['page'];
                if ($page == 1) {
                    $pageLimit = 0;
                } else {
                    $pageLimit = PAGE_PER_RESULTADO_ARCHIVO_OYMRI * ($page - 1);
                }
                $query_count_registros_archivos_oymri = mysqli_query($connection, "SELECT * FROM archivos_cargados_oymri_2 "
                                                                                          . " WHERE ANO_FACTURA = '" . $nombre_ano . "' "
                                                                                          . $busqueda_archivo_oymri
                                                                                          . " ORDER BY PERIODO, RUTA ");
                $count_registros_archivos_oymri = mysqli_num_rows($query_count_registros_archivos_oymri);
                $query_archivos_oymri = mysqli_query($connection, "SELECT * FROM archivos_cargados_oymri_2 "
                                                                . " WHERE ANO_FACTURA = '" . $nombre_ano . "' "
                                                                . $busqueda_archivo_oymri
                                                                . " ORDER BY PERIODO, RUTA "
                                                                . " LIMIT " . $pageLimit . ", " . PAGE_PER_RESULTADO_ARCHIVO_OYMRI);
                $table = "";
                $info_resultado = array();
                while ($row_archivos_oymri = mysqli_fetch_assoc($query_archivos_oymri)) {
                    $table = $table . "<tr>";
                        $table = $table . "<td style='vertical-align: middle;'>" . $row_archivos_oymri['ANO_FACTURA'] . "</td>";
                        switch ($row_archivos_oymri['PERIODO']) {
                            case '01':
                                $table = $table . "<td style='vertical-align: middle;'>ENERO</td>";
                                break;
                            case '02':
                                $table = $table . "<td style='vertical-align: middle;'>FEBRERO</td>";
                                break;
                            case '03':
                                $table = $table . "<td style='vertical-align: middle;'>MARZO</td>";
                                break;
                            case '04':
                                $table = $table . "<td style='vertical-align: middle;'>ABRIL</td>";
                                break;
                            case '05':
                                $table = $table . "<td style='vertical-align: middle;'>MAYO</td>";
                                break;
                            case '06':
                                $table = $table . "<td style='vertical-align: middle;'>JUNIO</td>";
                                break;
                            case '07':
                                $table = $table . "<td style='vertical-align: middle;'>JULIO</td>";
                                break;
                            case '08':
                                $table = $table . "<td style='vertical-align: middle;'>AGOSTO</td>";
                                break;
                            case '09':
                                $table = $table . "<td style='vertical-align: middle;'>SEPTIEMBRE</td>";
                                break;
                            case '10':
                                $table = $table . "<td style='vertical-align: middle;'>OCTUBRE</td>";
                                break;
                            case '11':
                                $table = $table . "<td style='vertical-align: middle;'>NOVIEMBRE</td>";
                                break;
                            case '12':
                                $table = $table . "<td style='vertical-align: middle;'>DICIEMBRE</td>";
                                break;
                        }
                        $table = $table . "<td style='vertical-align: middle;'>" . $row_archivos_oymri['RUTA'] . "</td>";
                        $table = $table . "<td style='vertical-align: middle;'><button type='button' data-toggle='modal' id='" . $row_archivos_oymri['ID_TABLA'] . "' data-target='#modalDetalleArchivoOYMRI'><img src='Images/search.png' title='Detalle' width='16' height='16' /></button></td>";
                    $table = $table . "</tr>";
                }
                $info_resultado[0] = $nombre_ano;
                $info_resultado[1] = $table;
                $info_resultado[2] = $id_ano;
                $info_resultado[3] = $count_registros_archivos_oymri;
                break;
            case 'fact_comer':
                require_once('../Includes/Paginacion_Resultado_Archivos_Facturacion.php');
                $id_ano = $_POST['id_ano'];
                $nombre_ano = $_POST['nombre_ano'];
                if ($_POST['sw'] == 1) {
                    if (isset($_POST['busqueda_archivo_facturacion']) != "") {
                        if ($_POST['busqueda_archivo_facturacion'] != "") {
                            if ($_POST['busqueda_archivo_facturacion'] != " ") {
                                $busqueda_archivo_facturacion = " AND RUTA LIKE '%" . $_POST['busqueda_archivo_facturacion'] . "%' ";
                            } else {
                                $busqueda_archivo_facturacion = " AND RUTA <> '' ";
                            }
                        } else {
                            $busqueda_archivo_facturacion = " AND RUTA <> '' ";
                        }
                    } else {
                        $busqueda_archivo_facturacion = " AND RUTA <> '' ";
                    }
                } else {
                    $busqueda_archivo_facturacion = " AND RUTA <> '' ";
                }
                $page = $_POST['page'];
                if ($page == 1) {
                    $pageLimit = 0;
                } else {
                    $pageLimit = PAGE_PER_RESULTADO_ARCHIVO_FACTURACION * ($page - 1);
                }
                $query_count_registros_archivos_facturacion = mysqli_query($connection, "SELECT * FROM archivos_cargados_fact_comer_2 "
                                                                                      . " WHERE ANO_FACTURA = '" . $nombre_ano . "' "
                                                                                      . $busqueda_archivo_facturacion
                                                                                      . " ORDER BY PERIODO, ID_COMERCIALIZADOR, RUTA ");
                $count_registros_archivos_facturacion = mysqli_num_rows($query_count_registros_archivos_facturacion);
                $query_archivos_facturacion = mysqli_query($connection, "SELECT * FROM archivos_cargados_fact_comer_2 "
                                                                      . " WHERE ANO_FACTURA = '" . $nombre_ano . "' "
                                                                      . $busqueda_archivo_facturacion
                                                                      . " ORDER BY PERIODO, ID_COMERCIALIZADOR, RUTA "
                                                                      . " LIMIT " . $pageLimit . ", " . PAGE_PER_RESULTADO_ARCHIVO_FACTURACION);
                $table = "";
                $info_resultado = array();
                while ($row_archivos_facturacion = mysqli_fetch_assoc($query_archivos_facturacion)) {
                    $table = $table . "<tr>";
                        switch ($row_archivos_facturacion['PERIODO']) {
                            case '01':
                                $table = $table . "<td style='vertical-align: middle;'>ENERO</td>";
                                break;
                            case '02':
                                $table = $table . "<td style='vertical-align: middle;'>FEBRERO</td>";
                                break;
                            case '03':
                                $table = $table . "<td style='vertical-align: middle;'>MARZO</td>";
                                break;
                            case '04':
                                $table = $table . "<td style='vertical-align: middle;'>ABRIL</td>";
                                break;
                            case '05':
                                $table = $table . "<td style='vertical-align: middle;'>MAYO</td>";
                                break;
                            case '06':
                                $table = $table . "<td style='vertical-align: middle;'>JUNIO</td>";
                                break;
                            case '07':
                                $table = $table . "<td style='vertical-align: middle;'>JULIO</td>";
                                break;
                            case '08':
                                $table = $table . "<td style='vertical-align: middle;'>AGOSTO</td>";
                                break;
                            case '09':
                                $table = $table . "<td style='vertical-align: middle;'>SEPTIEMBRE</td>";
                                break;
                            case '10':
                                $table = $table . "<td style='vertical-align: middle;'>OCTUBRE</td>";
                                break;
                            case '11':
                                $table = $table . "<td style='vertical-align: middle;'>NOVIEMBRE</td>";
                                break;
                            case '12':
                                $table = $table . "<td style='vertical-align: middle;'>DICIEMBRE</td>";
                                break;
                        }
                        $query_select_comercializador = mysqli_query($connection, "SELECT * FROM comercializadores_2 WHERE ID_COMERCIALIZADOR = '" . $row_archivos_facturacion['ID_COMERCIALIZADOR'] . "'");
                        $row_comercializador = mysqli_fetch_array($query_select_comercializador);
                        $table = $table . "<td style='vertical-align: middle;'>" . $row_comercializador['NOMBRE'] . "</td>";
                        $table = $table . "<td style='vertical-align: middle;'>" . $row_archivos_facturacion['RUTA'] . "</td>";
                        $table = $table . "<td style='vertical-align: middle;'><button type='button' data-toggle='modal' id='" . $row_archivos_facturacion['ID_TABLA'] . "' data-target='#modalDetalleArchivoFacturacion'><img src='Images/search.png' title='Detalle' width='16' height='16' /></button></td>";
                    $table = $table . "</tr>";
                }
                $info_resultado[0] = $nombre_ano;
                $info_resultado[1] = $table;
                $info_resultado[2] = $id_ano;
                $info_resultado[3] = $count_registros_archivos_facturacion;
                break;
        }
        echo json_encode($info_resultado);
        exit();
    }
?>