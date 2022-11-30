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
        if (isset($_GET['editar'])) {
            $query_select_consecutivo = mysqli_query($connection, "SELECT * "
                                                                . "  FROM pqr_2 "
                                                                . " WHERE ID_PQR = " . $_GET['editar'] . " ");
            $row_consecutivo = mysqli_fetch_array($query_select_consecutivo);
            if ($row_consecutivo['FECHA_INGRESO'] == $_POST['fecha_ingreso_pqr']) {
                if ($row_consecutivo['ID_TIPO_ASUNTO'] == $_POST['id_tipo_asunto_pqr']) {
                    $radicado_pqr = $row_consecutivo['RADICADO'];
                } else {
                    $consecutivo_radicado = substr($row_consecutivo['RADICADO'], -3);
                    $radicado_pqr = $_POST['radicado_pqr'] . $consecutivo_radicado;
                }
            } else {
                $query_select_consecutivo = mysqli_query($connection, "SELECT * "
                                                                    . "  FROM pqr_2 "
                                                                    . " WHERE ID_COD_DPTO = " . $_POST['id_departamento'] . " "
                                                                    . "   AND ID_COD_MPIO = " . $_POST['id_municipio'] . " "
                                                                    . "   AND FECHA_INGRESO = '" . $_POST['fecha_ingreso_pqr'] . "' ");
                if (mysqli_num_rows($query_select_consecutivo) != 0) {
                    $consecutivo_radicado = mysqli_num_rows($query_select_consecutivo) + 1;
                } else {
                    $consecutivo_radicado = 1;
                }
                switch (mb_strlen($consecutivo_radicado)) {
                    case 1:
                        $radicado_pqr = $_POST['radicado_pqr'] . "00" . $consecutivo_radicado;
                        break;
                    case 2:
                        $radicado_pqr = $_POST['radicado_pqr'] . "0" . $consecutivo_radicado;
                        break;
                    case 3:
                        $radicado_pqr = $_POST['radicado_pqr'] . $consecutivo_radicado;
                        break;
                }
            }
            $fecha_ingreso_pqr = $_POST['fecha_ingreso_pqr'];
            $fecha_vencimiento_pqr = $_POST['fecha_vencimiento_pqr'];
            $fecha_respuesta_pqr = $_POST['fecha_respuesta_pqr'];
            $estado_pqr = $_POST['estado_pqr'];
            $departamento = $_POST['id_departamento'];
            $municipio = $_POST['id_municipio'];
            $barrio = strtoupper($_POST['barrio']);
            $tipo_persona = $_POST['tipo_persona'];
            $identificacion = $_POST['identificacion'];
            $nombres = strtoupper($_POST['nombres']);
            $apellidos = strtoupper($_POST['apellidos']);
            $cargo_usuario = strtoupper($_POST['cargo_usuario']);
            $direccion = strtoupper($_POST['direccion']);
            $telefono = $_POST['telefono'];
            $correo_electronico = strtolower($_POST['correo_electronico']);
            $id_tipo_asunto_pqr = $_POST['id_tipo_asunto_pqr'];
            $peticion = $_POST['peticion'];
            $requiere_visita = $_POST['requiere_visita'];
            $observaciones = strtoupper($_POST['observaciones']);
            $observaciones_respuesta = strtoupper($_POST['observaciones_respuesta']);
            $fecha_actualizacion = date('Y-m-d H:i:s');
            $id_usuario = $_SESSION['id_user'];
            if ($estado_pqr == '2') {
                mysqli_query($connection, "UPDATE pqr_2
                                              SET OBSERVACIONES_RESPUESTA = '" . $observaciones_respuesta . "', "
                                              . " FECHA_ACTUALIZACION = '" . $fecha_actualizacion . "', "
                                              . " FECHA_RESPUESTA = '" . $fecha_respuesta_pqr . "', "
                                              . " ESTADO_PQR = '" . $estado_pqr . "', "
                                              . " ID_USUARIO = '" . $id_usuario. "' "
                                        . " WHERE ID_PQR = " . $_GET['editar']);
            } else {
                mysqli_query($connection, "UPDATE pqr_2
                                            SET RADICADO = '" . $radicado_pqr . "', "
                                            . " ID_COD_DPTO = '" . $departamento . "', "
                                            . " ID_COD_MPIO = '" . $municipio . "', "
                                            . " BARRIO = '" . $barrio . "', "
                                            . " TIPO_PERSONA = '" . $tipo_persona . "', "
                                            . " IDENTIFICACION = '" . $identificacion . "', "
                                            . " NOMBRES = '" . $nombres . "', "
                                            . " APELLIDOS = '" . $apellidos . "', "
                                            . " CARGO_USUARIO = '" . $cargo_usuario . "', "
                                            . " DIRECCION = '" . $direccion . "', "
                                            . " TELEFONO = '" . $telefono . "', "
                                            . " CORREO_ELECTRONICO = '" . $correo_electronico . "', "
                                            . " ID_TIPO_ASUNTO = '" . $id_tipo_asunto_pqr . "', "
                                            . " PETICION = '" . $peticion . "', "
                                            . " REQUIERE_VISITA = '" . $requiere_visita . "', "
                                            . " OBSERVACIONES = '" . $observaciones . "', "
                                            . " OBSERVACIONES_RESPUESTA = '" . $observaciones_respuesta . "', "
                                            . " ESTADO_PQR = '" . $estado_pqr . "', "
                                            . " FECHA_INGRESO = '" . $fecha_ingreso_pqr . "', "
                                            . " FECHA_VENCIMIENTO = '" . $fecha_vencimiento_pqr . "', "
                                            . " FECHA_RESPUESTA = '" . $fecha_respuesta_pqr . "', "
                                            . " FECHA_ACTUALIZACION = '" . $fecha_actualizacion . "', "
                                            . " ID_USUARIO = '" . $id_usuario. "' "
                                        . " WHERE ID_PQR = " . $_GET['editar']);
            }
            echo "<script>";
                echo "document.location.href = '../P_Q_R.php'";
            echo "</script>";
        } else {
            if (isset($_GET['eliminar'])) {
                mysqli_query($connection, "DELETE FROM pqr_2 WHERE ID_PQR = " . $_GET['eliminar']);
                echo "<script>";
                    echo "document.location.href = '../P_Q_R.php'";
                echo "</script>";
            } else {
                $query_select_consecutivo = mysqli_query($connection, "SELECT * "
                                                                    . "  FROM pqr_2 "
                                                                    . " WHERE ID_COD_DPTO = " . $_POST['id_departamento'] . " "
                                                                    . "   AND ID_COD_MPIO = " . $_POST['id_municipio'] . " "
                                                                    . "   AND FECHA_INGRESO = '" . $_POST['fecha_ingreso_pqr'] . "' ");
                if (mysqli_num_rows($query_select_consecutivo) != 0) {
                    $consecutivo_radicado = mysqli_num_rows($query_select_consecutivo) + 1;
                } else {
                    $consecutivo_radicado = 1;
                }
                switch (mb_strlen($consecutivo_radicado)) {
                    case 1:
                        $radicado_pqr = $_POST['radicado_pqr'] . "00" . $consecutivo_radicado;
                        break;
                    case 2:
                        $radicado_pqr = $_POST['radicado_pqr'] . "0" . $consecutivo_radicado;
                        break;
                    case 3:
                        $radicado_pqr = $_POST['radicado_pqr'] . $consecutivo_radicado;
                        break;
                }
                $fecha_ingreso_pqr = $_POST['fecha_ingreso_pqr'];
                $fecha_vencimiento_pqr = $_POST['fecha_vencimiento_pqr'];
                $estado_pqr = $_POST['estado_pqr'];
                $departamento = $_POST['id_departamento'];
                $municipio = $_POST['id_municipio'];
                $barrio = strtoupper($_POST['barrio']);
                $tipo_persona = $_POST['tipo_persona'];
                $identificacion = $_POST['identificacion'];
                $nombres = strtoupper($_POST['nombres']);
                $apellidos = strtoupper($_POST['apellidos']);
                $cargo_usuario = strtoupper($_POST['cargo_usuario']);
                $direccion = strtoupper($_POST['direccion']);
                $telefono = $_POST['telefono'];
                $correo_electronico = strtolower($_POST['correo_electronico']);
                $id_tipo_asunto_pqr = $_POST['tipo_asunto_pqr'];
                $peticion = $_POST['peticion'];
                $requiere_visita = $_POST['requiere_visita'];
                $observaciones = strtoupper($_POST['observaciones']);
                $fecha_creacion = date('Y-m-d H:i:s');
                $fecha_actualizacion = '0000-00-00 00:00:00';
                $id_usuario = $_SESSION['id_user'];
                mysqli_query($connection, "INSERT INTO pqr_2 (RADICADO, ID_COD_DPTO, ID_COD_MPIO, BARRIO, TIPO_PERSONA, IDENTIFICACION, NOMBRES, APELLIDOS,
                                                              CARGO_USUARIO, DIRECCION, TELEFONO, CORREO_ELECTRONICO, ID_TIPO_ASUNTO, PETICION,
                                                              REQUIERE_VISITA,  OBSERVACIONES, ESTADO_PQR, FECHA_INGRESO, FECHA_VENCIMIENTO,
                                                              FECHA_ACTUALIZACION, FECHA_CREACION, ID_USUARIO)
                                                      VALUES ('" . $radicado_pqr . "', '" . $departamento .
                                                              "', '" . $municipio . "', '" . $barrio . "', '" . $tipo_persona .
                                                              "', '" . $identificacion . "', '" . $nombres . "', '" . $apellidos .
                                                              "', '" . $cargo_usuario . "', '" . $direccion .
                                                              "', '" . $telefono . "', '" . $correo_electronico .
                                                              "', '" . $id_tipo_asunto_pqr . "', '" . $peticion . "', '" . $requiere_visita .
                                                              "', '" . $observaciones . "', '" . $estado_pqr .
                                                              "', '" . $fecha_ingreso_pqr . "', '" . $fecha_vencimiento_pqr . "', '" . $fecha_actualizacion .
                                                              "', '" . $fecha_creacion . "', '" . $id_usuario . "')");
                $query_select_max_id_pqr = mysqli_query($connection, "SELECT MAX(ID_PQR) AS ID_PQR FROM pqr_2 ");
                $row_max_id_pqr = mysqli_fetch_array($query_select_max_id_pqr);
                echo $row_max_id_pqr['ID_PQR'];
            }
        }
    }
