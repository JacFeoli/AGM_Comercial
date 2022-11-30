<?php
	session_start();
	require_once('Includes/Config_2.php');
	if ($_SESSION['timeout'] + 60 * 60 < time()) {
		session_unset();
		session_destroy();
		// *** ESTA CONEXIÓN ES PARA LOCAL (PC) *** \\.
		//header("Location:/Juridica/Login_2.php");
		// *** ESTA CONEXIÓN ES PARA EL SERVIDOR *** \\.
		$ruta = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
		header("Location:$ruta/Login_2.php");
	} else {
		$_SESSION['timeout'] = time();
	}
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Juridica - Estadísticas Procesos</title>
		<meta charset="UTF-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
		<link rel="icon" href="Images/juridica-icon-1.png" type="image/x-icon" />
		<link rel="stylesheet" href="Css/Juridica_Style_2.css" />
		<link rel="stylesheet" href="Css/bootstrap.min.css" />
		<link rel="stylesheet" href="Css/font-awesome.css">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
		<script type="text/javascript" src="https://www.google.com/jsapi"></script>
		<script src="Javascript/bootstrap.min.js"></script>
	</head>
	<body>
		<div class="wrapper">
			<?php include("Top Pages/Top_Page_Home_2.php"); ?>
			<section>
				<div class="container">
					<div class="row">
						<div class="col-lg-12">
							<div class="content">
								<div class="row">
									<div class="col-md-2">
										<div style="width: 170px;" class="leftcol">
											<h3 style="text-align: center;">OPCIONES DISPONIBLES</h3>
											<nav id="nav" class="accordion">
												<ul class="nav nav-pills nav-stacked">
													<li class="active">
														<a href="Estadisticas_Procesos_2.php">
															<table>
																<tr>
																	<td style="padding-right: 13px;">
																		<span><i class="fa fa-bar-chart fa-fw"></i></span>
																	</td>
																	<td>
																		<span>Estadísticas</span>
																	</td>
																</tr>
															</table>
														</a>
													</li>
													<li>
														<a href="Consultar_Procesos_2.php">
															<table>
																<tr>
																	<td style="padding-right: 13px;">
																		<span><i class="fa fa-search fa-fw"></i></span>
																	</td>
																	<td>
																		<span>Consultas</span>
																	</td>
																</tr>
															</table>
														</a>
													</li>
												</ul>
											</nav>
										</div>
									</div>
									<div class="col-md-10">
										<div class="rightcol">
											<h1>Estadísticas de Procesos</h1>
											<h2></h2>
											<ul class="nav nav-pills" role="tablist">
												<li role="presentation" class="active">
													<a href="#estadistica_distritos_tab" id="tab_info_estadistica_distritos" aria-controls="estadistica_distritos_tab" role="tab" data-toggle="tab">Por Distrito</a>
												</li>
												<!--<li role="presentation">
													<a href="#estadistica_abogados_tab" id="tab_info_estadistica_abogados" aria-controls="estadistica_abogados_tab" role="tab" data-toggle="tab">Por Abogados Externos</a>
												</li>-->
												<li role="presentation">
                                                    <a href="#estadistica_contingencias_tab" id="tab_info_estadistica_contingencias" aria-controls="estadistica_contingencias_tab" role="tab" data-toggle="tab">Por Contingencias</a>
                                                </li>
                                                <li role="presentation">
                                                    <a href="#estadistica_pretensiones_tab" id="tab_info_estadistica_pretensiones" aria-controls="estadistica_pretensiones_tab" role="tab" data-toggle="tab">Por Pretensiones</a>
                                                </li>
                                                <li role="presentation">
                                                    <a href="#estadistica_terminaciones_tab" id="tab_info_estadistica_terminaciones" aria-controls="estadistica_terminaciones_tab" role="tab" data-toggle="tab">Por Tipo Terminación</a>
                                                </li>
                                                <li role="presentation">
                                                    <a href="#estadistica_primeras_instancias_tab" id="tab_info_estadistica_primeras_instancias" aria-controls="estadistica_primeras_instancias_tab" role="tab" data-toggle="tab">Por Primeras Instancias</a>
                                                </li>
                                                <li role="presentation">
                                                    <a href="#estadistica_segundas_instancias_tab" id="tab_info_estadistica_segundas_instancias" aria-controls="estadistica_segundas_instancias_tab" role="tab" data-toggle="tab">Por Segundas Instancias</a>
                                                </li>
											</ul>
											<h2></h2>
											<div class="tab-content">
												<div role="tabpanel" class="tab-pane fade in active" id="estadistica_distritos_tab">
													<?php
														$rows = array();
														$query_select_distritos = mysqli_query($connection, "SELECT D.NOMBRE, COUNT(*)
																								 FROM procesos_2 P, departamentos_2 D
																								WHERE P.ID_DEPARTAMENTO = D.ID_DEPARTAMENTO
																								GROUP BY D.NOMBRE
																							   HAVING COUNT(*) >= 1
																							    ORDER BY COUNT(*) DESC, NOMBRE ASC");
														$table = array();
														$table['cols'] = array(
																			array('label' => 'Distritos', 'type' => 'string'),
																			array('label' => 'Total', 'type' => 'number')
																		 );
														while ($row_distritos = mysqli_fetch_assoc($query_select_distritos)) {
															$temp = array();
															$temp[] = array('v' => (string) $row_distritos['NOMBRE']);
															$temp[] = array('v' => (int) $row_distritos['COUNT(*)']);
															$rows[] = array('c' => $temp);
														}
														$table['rows'] = $rows;
														$jsonTableDistritos = json_encode($table);
														//echo $jsonTable;
													?>
													<form class="form-horizontal row-bottom-buffer row-top-buffer">
														<div class="form-group">
															<div class="col-xs-12">
																<div style="text-align: center;" id="piechartDistritos"></div>
															</div>
														</div>
													</form>
												</div>
												<div role="tabpanel" class="tab-pane fade" id="estadistica_abogados_tab">
													<?php
														$rows = array();
														$query_select_abogados = mysqli_query($connection, "SELECT A.NOMBRE, COUNT(*)
																								 FROM procesos_2 P, abogados_2 A
																								WHERE P.ID_ABOGADO_EXTERNO = A.ID_ABOGADO
																								GROUP BY A.NOMBRE
																							   HAVING COUNT(*) >= 1
																							    ORDER BY COUNT(*) DESC, NOMBRE ASC");
														$table = array();
														$table['cols'] = array(
																			array('label' => 'Abogados', 'type' => 'string'),
																			array('label' => 'Total', 'type' => 'number')
																		 );
														while ($row_abogados = mysqli_fetch_assoc($query_select_abogados)) {
															$temp = array();
															$temp[] = array('v' => (string) $row_abogados['NOMBRE']);
															$temp[] = array('v' => (int) $row_abogados['COUNT(*)']);
															$rows[] = array('c' => $temp);
														}
														$table['rows'] = $rows;
														$jsonTableAbogados = json_encode($table);
														//echo $jsonTable;
													?>
													<form class="form-horizontal row-bottom-buffer row-top-buffer">
														<div class="form-group">
															<div class="col-xs-12">
																<div style="text-align: center;" id="piechartAbogados"></div>
															</div>
														</div>
													</form>
												</div>
												<div role="tabpanel" class="tab-pane fade" id="estadistica_contingencias_tab">
												    <?php
                                                        $rows = array();
                                                        $query_select_contingencias = mysqli_query($connection, "SELECT D.NOMBRE, SUM(P.VALOR_PROVISION) AS TOTAL
                                                                                                     FROM procesos_2 P, departamentos_2 D
                                                                                                    WHERE P.ID_DEPARTAMENTO = D.ID_DEPARTAMENTO
                                                                                                    GROUP BY D.NOMBRE
                                                                                                   HAVING COUNT(*) >= 1
                                                                                                    ORDER BY TOTAL DESC, NOMBRE ASC");
                                                        $table = array();
                                                        $table['cols'] = array(
                                                                            array('label' => 'Contingencias', 'type' => 'string'),
                                                                            array('label' => 'Total', 'type' => 'number')
                                                                         );
                                                        while ($row_contingencias = mysqli_fetch_assoc($query_select_contingencias)) {
                                                            $temp = array();
                                                            $temp[] = array('v' => (string) $row_contingencias['NOMBRE']);
                                                            $temp[] = array('v' => (int) $row_contingencias['TOTAL']);
                                                            $rows[] = array('c' => $temp);
                                                        }
                                                        $table['rows'] = $rows;
                                                        $jsonTableContingencias = json_encode($table);
                                                        //echo $jsonTable;
                                                    ?>
												    <form class="form-horizontal row-bottom-buffer row-top-buffer">
                                                        <div class="form-group">
                                                            <div class="col-xs-12">
                                                                <div style="text-align: center;" id="piechartContingencias"></div>
                                                            </div>
                                                        </div>
                                                    </form>
												</div>
												<div role="tabpanel" class="tab-pane fade" id="estadistica_pretensiones_tab">
                                                    <ul class="nav nav-pills" role="tablist">
                                                        <?php
                                                            $sw = 0;
                                                            $array_id_distritos = array();
                                                            $array_distritos = array();
                                                            $query_select_distritos = mysqli_query($connection, "SELECT D.ID_DEPARTAMENTO, D.NOMBRE, COUNT(*) AS TOTAL
                                                                                                      FROM procesos_2 P, departamentos_2 D
                                                                                                     WHERE P.ID_DEPARTAMENTO = D.ID_DEPARTAMENTO
                                                                                                     GROUP BY D.ID_DEPARTAMENTO, D.NOMBRE
                                                                                                    HAVING COUNT(*) >= 1
                                                                                                     ORDER BY D.NOMBRE ASC");
                                                            while ($row_pretensiones_distritos = mysqli_fetch_assoc($query_select_distritos)) {
                                                                if ($sw == 0) {
                                                                    $sw = 1;
                                                                    $array_id_distritos[] = $row_pretensiones_distritos['ID_DEPARTAMENTO'];
                                                                    $nombre_distrito = strtolower($row_pretensiones_distritos['NOMBRE']);
                                                                    $array_distritos[] = $nombre_distrito;
                                                                    $count_pretensiones = $row_pretensiones_distritos['TOTAL'];
                                                                ?>
                                                                <li role="presentation" class="active">
                                                                    <a href="#informacion_<?php echo $nombre_distrito; ?>_tab" id="tab_info_<?php echo $nombre_distrito; ?>" aria-controls="informacion_<?php echo $nombre_distrito; ?>_tab" role="tab" data-toggle="tab"><?php echo ucwords(strtolower($row_pretensiones_distritos['NOMBRE'])); ?> <span class="badge"><?php echo $count_pretensiones; ?></span></a>
                                                                </li>
                                                                <?php
                                                                } else {
                                                                    $array_id_distritos[] = $row_pretensiones_distritos['ID_DEPARTAMENTO'];
                                                                    $nombre_distrito = strtolower($row_pretensiones_distritos['NOMBRE']);
                                                                    $array_distritos[] = $nombre_distrito;
                                                                    $count_pretensiones = $row_pretensiones_distritos['TOTAL'];
                                                                ?>
                                                                    <li role="presentation">
                                                                        <a href="#informacion_<?php echo $nombre_distrito; ?>_tab" id="tab_info_<?php echo $nombre_distrito; ?>" aria-controls="informacion_<?php echo $nombre_distrito; ?>_tab" role="tab" data-toggle="tab"><?php echo ucwords(strtolower($row_pretensiones_distritos['NOMBRE'])); ?> <span class="badge"><?php echo $count_pretensiones; ?></span></a>
                                                                    </li>
                                                                <?php
                                                                }
                                                            }
                                                        ?>
                                                    </ul>
                                                    <h2></h2>
                                                    <div class="tab-content">
                                                        <?php
                                                            $sw = 0;
                                                            $array_table = array();
                                                            $query_select_distritos = mysqli_query($connection, "SELECT D.ID_DEPARTAMENTO, D.NOMBRE, COUNT(*) AS TOTAL
                                                                                                      FROM procesos_2 P, departamentos_2 D
                                                                                                     WHERE P.ID_DEPARTAMENTO = D.ID_DEPARTAMENTO
                                                                                                     GROUP BY D.ID_DEPARTAMENTO, D.NOMBRE
                                                                                                    HAVING COUNT(*) >= 1
                                                                                                     ORDER BY D.NOMBRE ASC");
                                                            while ($row_pretensiones_distritos = mysqli_fetch_assoc($query_select_distritos)) {
                                                                $nombre_distrito = strtolower($row_pretensiones_distritos['NOMBRE']);
                                                                if ($sw == 0) {
                                                                    $sw = 1;
                                                                    $id_departamento = $row_pretensiones_distritos['ID_DEPARTAMENTO'];
                                                                    ?>
                                                                    <div role="tabpanel" class="tab-pane fade in active" id="informacion_<?php echo $nombre_distrito; ?>_tab">
                                                                        <?php
                                                                            $rows = array();
                                                                            $query_select_pretensiones = mysqli_query($connection, "SELECT PT.NOMBRE, COUNT(*) AS TOTAL
                                                                                                                        FROM procesos_2 PR, departamentos_2 D, tipo_pretensiones_2 PT
                                                                                                                       WHERE PR.ID_DEPARTAMENTO = D.ID_DEPARTAMENTO
                                                                                                                          AND PR.ID_TIPO_PRETENSION = PT.ID_TIPO_PRETENSION
                                                                                                                          AND PR.ID_CLASE_PROCESO = PT.ID_CLASE_PROCESO
                                                                                                                          AND PR.ID_DEPARTAMENTO = " . $id_departamento . "
                                                                                                                       GROUP BY PT.NOMBRE
                                                                                                                       ORDER BY TOTAL DESC, NOMBRE ASC");
                                                                            $table = array();
                                                                            $table['cols'] = array(
                                                                                                array('label' => 'Pretensiones', 'type' => 'string'),
                                                                                                array('label' => 'Total', 'type' => 'number')
                                                                                             );
                                                                            while ($row_pretensiones = mysqli_fetch_assoc($query_select_pretensiones)) {
                                                                                $temp = array();
                                                                                $temp[] = array('v' => (string) $row_pretensiones['NOMBRE']);
                                                                                $temp[] = array('v' => (int) $row_pretensiones['TOTAL']);
                                                                                $rows[] = array('c' => $temp);
                                                                            }
                                                                            $table['rows'] = $rows;
                                                                            $array_table[] = json_encode($table);
                                                                            //echo $array_table[$i];
                                                                            //$jsonTablePretensiones = json_encode($table);
                                                                            //echo $jsonTablePretensiones;
                                                                        ?>
                                                                        <form class="form-horizontal row-bottom-buffer row-top-buffer">
                                                                            <div class="form-group">
                                                                                <div class="col-xs-12">
                                                                                    <div style="text-align: center;" id="piechartPretensiones-<?php echo $nombre_distrito; ?>"></div>
                                                                                </div>
                                                                            </div>
                                                                        </form>
                                                                    </div>
                                                                    <?php
                                                                } else {
                                                                    $id_departamento = $row_pretensiones_distritos['ID_DEPARTAMENTO'];
                                                                    ?>
                                                                    <div role="tabpanel" class="tab-pane fade" id="informacion_<?php echo $nombre_distrito; ?>_tab">
                                                                        <?php
                                                                            $rows = array();
                                                                            $query_select_pretensiones = mysqli_query($connection, "SELECT PT.NOMBRE, COUNT(*) AS TOTAL
                                                                                                                        FROM procesos_2 PR, departamentos_2 D, tipo_pretensiones_2 PT
                                                                                                                       WHERE PR.ID_DEPARTAMENTO = D.ID_DEPARTAMENTO
                                                                                                                          AND PR.ID_TIPO_PRETENSION = PT.ID_TIPO_PRETENSION
                                                                                                                          AND PR.ID_CLASE_PROCESO = PT.ID_CLASE_PROCESO
                                                                                                                          AND PR.ID_DEPARTAMENTO = " . $id_departamento . "
                                                                                                                       GROUP BY PT.NOMBRE
                                                                                                                       ORDER BY TOTAL DESC, NOMBRE ASC");
                                                                            $table = array();
                                                                            $table['cols'] = array(
                                                                                                array('label' => 'Pretensiones', 'type' => 'string'),
                                                                                                array('label' => 'Total', 'type' => 'number')
                                                                                             );
                                                                            while ($row_pretensiones = mysqli_fetch_assoc($query_select_pretensiones)) {
                                                                                $temp = array();
                                                                                $temp[] = array('v' => (string) $row_pretensiones['NOMBRE']);
                                                                                $temp[] = array('v' => (int) $row_pretensiones['TOTAL']);
                                                                                $rows[] = array('c' => $temp);
                                                                            }
                                                                            $table['rows'] = $rows;
                                                                            $array_table[] = json_encode($table);
                                                                            //echo $array_table[$i];
                                                                            //$jsonTablePretensiones = json_encode($table);
                                                                            //echo $jsonTablePretensiones;
                                                                        ?>
                                                                        <form class="form-horizontal row-bottom-buffer row-top-buffer">
                                                                            <div class="form-group">
                                                                                <div class="col-xs-12">
                                                                                    <div style="text-align: center;" id="piechartPretensiones-<?php echo $nombre_distrito; ?>"></div>
                                                                                </div>
                                                                            </div>
                                                                        </form>
                                                                    </div>
                                                                    <?php
                                                                }
                                                            }
                                                        ?>
                                                    </div>
                                                </div>
                                                <div role="tabpanel" class="tab-pane fade" id="estadistica_terminaciones_tab">
                                                    <?php
                                                        $rows = array();
                                                        $query_select_terminaciones = mysqli_query($connection, "SELECT TT.NOMBRE, COUNT(*) AS TOTAL
                                                                                                      FROM procesos_2 P, tipo_terminacion TT
                                                                                                     WHERE P.ID_TIPO_TERMINACION = TT.ID_TIPO_TERMINACION
                                                                                                     GROUP BY TT.NOMBRE
                                                                                                    HAVING COUNT(*) >= 1
                                                                                                     ORDER BY TOTAL DESC, NOMBRE ASC");
                                                        $table = array();
                                                        $table['cols'] = array(
                                                                            array('label' => 'Tipo Terminacion', 'type' => 'string'),
                                                                            array('label' => 'Total', 'type' => 'number')
                                                                         );
                                                        while ($row_terminaciones = mysqli_fetch_assoc($query_select_terminaciones)) {
                                                            $temp = array();
                                                            $temp[] = array('v' => (string) $row_terminaciones['NOMBRE']);
                                                            $temp[] = array('v' => (int) $row_terminaciones['TOTAL']);
                                                            $rows[] = array('c' => $temp);
                                                        }
                                                        $table['rows'] = $rows;
                                                        $jsonTableTerminaciones = json_encode($table);
                                                        //echo $jsonTable;
                                                    ?>
                                                    <form class="form-horizontal row-bottom-buffer row-top-buffer">
                                                        <div class="form-group">
                                                            <div class="col-xs-12">
                                                                <div style="text-align: center;" id="piechartTerminaciones"></div>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                                <div role="tabpanel" class="tab-pane fade" id="estadistica_primeras_instancias_tab">
                                                    <?php
                                                        //$i = 0;
                                                        $rows = array();
                                                        $query_select_distritos = mysqli_query($connection, "SELECT D.ID_DEPARTAMENTO, D.NOMBRE
                                                                                                               FROM procesos_2 P, departamentos_2 D, actuaciones_judiciales_2 AJ
                                                                                                              WHERE P.ID_DEPARTAMENTO = D.ID_DEPARTAMENTO
                                                                                                                AND P.ID_PROCESO = AJ.ID_PROCESO
                                                                                                                AND P.ID_CLASE_PROCESO = AJ.ID_CLASE_PROCESO
                                                                                                                AND AJ.ID_TIPO_ETAPA = 9
                                                                                                              GROUP BY D.NOMBRE
                                                                                                              ORDER BY D.NOMBRE ASC");
                                                        $table_primeras_instancias = array();
                                                        $table_primeras_instancias['cols'] = array(
                                                                            array('label' => 'Distrito', 'type' => 'string'),
                                                                            array('label' => 'A Favor', 'type' => 'number'),
                                                                            array('label' => 'En Contra', 'type' => 'number')
                                                                         );
                                                        while ($row_pretensiones_distritos = mysqli_fetch_assoc($query_select_distritos)) {
                                                            $id_departamento = $row_pretensiones_distritos['ID_DEPARTAMENTO'];
                                                            $query_select_primeras_instancias_a_favor = mysqli_query($connection, "SELECT IFNULL(COUNT(*), 0) AS TOTAL
                                                                                                                            FROM procesos_2 P, actuaciones_judiciales_2 AJ
                                                                                                                           WHERE P.ID_PROCESO = AJ.ID_PROCESO
                                                                                                                             AND P.ID_CLASE_PROCESO = AJ.ID_CLASE_PROCESO
                                                                                                                             AND P.ID_DEPARTAMENTO = " . $id_departamento . "
                                                                                                                             AND AJ.ID_TIPO_ETAPA = 9
                                                                                                                             AND AJ.ID_TIPO_ACTUACION = 2");
                                                            $row_primeras_instancias_a_favor = mysqli_fetch_array($query_select_primeras_instancias_a_favor);
                                                            $query_select_primeras_instancias_en_contra = mysqli_query($connection, "SELECT IFNULL(COUNT(*), 0) AS TOTAL
                                                                                                                            FROM procesos_2 P, actuaciones_judiciales_2 AJ
                                                                                                                           WHERE P.ID_PROCESO = AJ.ID_PROCESO
                                                                                                                             AND P.ID_CLASE_PROCESO = AJ.ID_CLASE_PROCESO
                                                                                                                             AND P.ID_DEPARTAMENTO = " . $id_departamento . "
                                                                                                                             AND AJ.ID_TIPO_ETAPA = 9
                                                                                                                             AND AJ.ID_TIPO_ACTUACION = 1");
                                                            $row_primeras_instancias_en_contra = mysqli_fetch_array($query_select_primeras_instancias_en_contra);
                                                            $temp = array();
                                                            $temp[] = array('v' => (string) $row_pretensiones_distritos['NOMBRE']);
                                                            $temp[] = array('v' => (int) $row_primeras_instancias_a_favor['TOTAL']);
                                                            $temp[] = array('v' => (int) $row_primeras_instancias_en_contra['TOTAL']);
                                                            $rows[] = array('c' => $temp);
                                                        }
                                                        $table_primeras_instancias['rows'] = $rows;
                                                        $jsonTablePrimerasInstancias = json_encode($table_primeras_instancias);
                                                        //echo $jsonTablePrimerasInstancias;
                                                    ?>
                                                    <form class="form-horizontal row-bottom-buffer row-top-buffer">
                                                        <div class="form-group">
                                                            <div class="col-xs-12">
                                                                <div style="text-align: center;" id="columnchartPrimerasInstancias"></div>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                                <div role="tabpanel" class="tab-pane fade" id="estadistica_segundas_instancias_tab">
                                                    <?php
                                                        //$i = 0;
                                                        $rows = array();
                                                        $query_select_distritos = mysqli_query($connection, "SELECT D.ID_DEPARTAMENTO, D.NOMBRE
                                                                                                               FROM procesos_2 P, departamentos_2 D, actuaciones_judiciales_2 AJ
                                                                                                              WHERE P.ID_DEPARTAMENTO = D.ID_DEPARTAMENTO
                                                                                                                AND P.ID_PROCESO = AJ.ID_PROCESO
                                                                                                                AND P.ID_CLASE_PROCESO = AJ.ID_CLASE_PROCESO
                                                                                                                AND AJ.ID_TIPO_ETAPA = 45
                                                                                                              GROUP BY D.NOMBRE
                                                                                                              ORDER BY D.NOMBRE ASC");
                                                        $table_segundas_instancias = array();
                                                        $table_segundas_instancias['cols'] = array(
                                                                            array('label' => 'Distrito', 'type' => 'string'),
                                                                            array('label' => 'A Favor', 'type' => 'number'),
                                                                            array('label' => 'En Contra', 'type' => 'number')
                                                                         );
                                                        while ($row_pretensiones_distritos = mysqli_fetch_assoc($query_select_distritos)) {
                                                            $id_departamento = $row_pretensiones_distritos['ID_DEPARTAMENTO'];
                                                            $query_select_segundas_instancias_a_favor = mysqli_query($connection, "SELECT IFNULL(COUNT(*), 0) AS TOTAL
                                                                                                                            FROM procesos_2 P, actuaciones_judiciales_2 AJ
                                                                                                                           WHERE P.ID_PROCESO = AJ.ID_PROCESO
                                                                                                                             AND P.ID_CLASE_PROCESO = AJ.ID_CLASE_PROCESO
                                                                                                                             AND P.ID_DEPARTAMENTO = " . $id_departamento . "
                                                                                                                             AND AJ.ID_TIPO_ETAPA = 45
                                                                                                                             AND AJ.ID_TIPO_ACTUACION = 16");
                                                            $row_segundas_instancias_a_favor = mysqli_fetch_array($query_select_segundas_instancias_a_favor);
                                                            $query_select_segundas_instancias_en_contra = mysqli_query($connection, "SELECT IFNULL(COUNT(*), 0) AS TOTAL
                                                                                                                            FROM procesos_2 P, actuaciones_judiciales_2 AJ
                                                                                                                           WHERE P.ID_PROCESO = AJ.ID_PROCESO
                                                                                                                             AND P.ID_CLASE_PROCESO = AJ.ID_CLASE_PROCESO
                                                                                                                             AND P.ID_DEPARTAMENTO = " . $id_departamento . "
                                                                                                                             AND AJ.ID_TIPO_ETAPA = 45
                                                                                                                             AND AJ.ID_TIPO_ACTUACION = 17");
                                                            $row_segundas_instancias_en_contra = mysqli_fetch_array($query_select_segundas_instancias_en_contra);
                                                            $temp = array();
                                                            $temp[] = array('v' => (string) $row_pretensiones_distritos['NOMBRE']);
                                                            $temp[] = array('v' => (int) $row_segundas_instancias_a_favor['TOTAL']);
                                                            $temp[] = array('v' => (int) $row_segundas_instancias_en_contra['TOTAL']);
                                                            $rows[] = array('c' => $temp);
                                                        }
                                                        $table_segundas_instancias['rows'] = $rows;
                                                        $jsonTableSegundasInstancias = json_encode($table_segundas_instancias);
                                                        //echo $jsonTablePrimerasInstancias;
                                                    ?>
                                                    <form class="form-horizontal row-bottom-buffer row-top-buffer">
                                                        <div class="form-group">
                                                            <div class="col-xs-12">
                                                                <div style="text-align: center;" id="columnchartSegundasInstancias"></div>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
											</div>
										</div>
									</div>
								</div>
								<div class="clear"></div>
							</div>
						</div>
					</div>
				</div>
			</section>
		</div>
	</body>
	<script>
    	google.load("visualization", "1", {packages:["corechart"]});
    	google.setOnLoadCallback(drawChartDistritos);
    	google.setOnLoadCallback(drawChartAbogados);
    	google.setOnLoadCallback(drawChartContingencias);
    	google.setOnLoadCallback(drawChartPretensiones);
    	google.setOnLoadCallback(drawChartTerminaciones);
    	function drawChartDistritos() {
    		var data = new google.visualization.DataTable(<?php echo $jsonTableDistritos; ?>);
    		var options = {
    						backgroundColor: 'none',
    						fontName: 'Cabin',
    						fontSize: 14,
    						is3D: true,
    						legend: {alignment: 'center'},
    						/*slices: {3: {offset: 0.2},
    								 7: {offset: 0.2},
    								 9: {offset: 0.2}},*/
    						chartArea: {left: "5%", width: 500, height: 300},
    						width: 600,
    						height: 350};
    		var chart = new google.visualization.PieChart(document.getElementById("piechartDistritos"));
    		chart.draw(data, options);
    	}
    	function drawChartAbogados() {
    		var data = new google.visualization.DataTable(<?php echo $jsonTableAbogados; ?>);
    		var options = {
    						backgroundColor: 'none',
    						fontName: 'Cabin',
    						fontSize: 14,
    						is3D: true,
    						legend: {alignment: 'center'},
    						/*slices: {3: {offset: 0.2},
    								 7: {offset: 0.2},
    								 9: {offset: 0.2}},*/
    						chartArea: {left: "5%", width: 500, height: 300},
    						width: 600,
    						height: 350};
    		var chart = new google.visualization.PieChart(document.getElementById("piechartAbogados"));
    		chart.draw(data, options);
    	}
    	function drawChartContingencias() {
            var data = new google.visualization.DataTable(<?php echo $jsonTableContingencias; ?>);
            var options = {
                            backgroundColor: 'none',
                            fontName: 'Cabin',
                            fontSize: 14,
                            is3D: true,
                            legend: {alignment: 'center'},
                            /*slices: {3: {offset: 0.2},
                                     7: {offset: 0.2},
                                     9: {offset: 0.2}},*/
                            chartArea: {left: "5%", width: 500, height: 300},
                            width: 600,
                            height: 350};
            var formatter = new google.visualization.NumberFormat({negativeColor: 'red', negativeParens: true, pattern: '$###,###'});
            formatter.format(data, 1);
            var chart = new google.visualization.PieChart(document.getElementById("piechartContingencias"));
            chart.draw(data, options);
        }
        function drawChartPretensiones() {
            //alert("Entra");
            var array_distritos = <?php echo json_encode($array_distritos); ?>;
            var array_table = <?php echo json_encode($array_table); ?>;
            for (var i=0; i<array_distritos.length; i++) {
                //alert(array_table[i]);
                //alert(i);
                var data = new google.visualization.DataTable(array_table[i]);
                var options = {
                                backgroundColor: 'none',
                                fontName: 'Cabin',
                                fontSize: 14,
                                is3D: true,
                                legend: {alignment: 'center',
                                         textStyle: {fontSize: 12}},
                                /*slices: {3: {offset: 0.2},
                                         7: {offset: 0.2},
                                         9: {offset: 0.2}},*/
                                chartArea: {left: "5%", width: 500, height: 300},
                                width: 600,
                                height: 350};
                var chart = new google.visualization.PieChart(document.getElementById("piechartPretensiones-" + array_distritos[i]));
                chart.draw(data, options);
            }
        }
        function drawChartTerminaciones() {
            var data = new google.visualization.DataTable(<?php echo $jsonTableTerminaciones; ?>);
            var options = {
                            backgroundColor: 'none',
                            fontName: 'Cabin',
                            fontSize: 14,
                            is3D: true,
                            legend: {alignment: 'center',
                                     textStyle: {fontSize: 12}},
                            /*slices: {3: {offset: 0.2},
                                     7: {offset: 0.2},
                                     9: {offset: 0.2}},*/
                            chartArea: {left: "5%", width: 500, height: 300},
                            width: 600,
                            height: 350};
            var chart = new google.visualization.PieChart(document.getElementById("piechartTerminaciones"));
            chart.draw(data, options);
        }
	</script>
	<script>
	   //google.load('current', {'packages':['corechart']});
       google.setOnLoadCallback(drawPrimerasInstancias);
       google.setOnLoadCallback(drawSegundasInstancias);
       function drawPrimerasInstancias() {
           var data = new google.visualization.DataTable(<?php echo $jsonTablePrimerasInstancias; ?>);
           var options = {
                            backgroundColor: 'none',
                            fontName: 'Cabin',
                            fontSize: 14,
                            is3D: true,
                            legend: {alignment: 'center'},
                            bar: {groupWidth: "30%"},
                            /*slices: {3: {offset: 0.2},
                                     7: {offset: 0.2},
                                     9: {offset: 0.2}},*/
                            chartArea: {left: "5%", width: 500, height: 300},
                            width: 800,
                            height: 350};
           //var materialChart = new google.charts.Bar(document.getElementById("columnchartPrimerasInstancias"));
           //materialChart.draw(data, google.charts.Bar.convertOptions(options));
           var chart = new google.visualization.ColumnChart(document.getElementById("columnchartPrimerasInstancias"));
           chart.draw(data, options);
       }
       function drawSegundasInstancias() {
           var data = new google.visualization.DataTable(<?php echo $jsonTableSegundasInstancias; ?>);
           var options = {
                            backgroundColor: 'none',
                            fontName: 'Cabin',
                            fontSize: 14,
                            is3D: true,
                            legend: {alignment: 'center'},
                            bar: {groupWidth: "30%"},
                            /*slices: {3: {offset: 0.2},
                                     7: {offset: 0.2},
                                     9: {offset: 0.2}},*/
                            chartArea: {left: "5%", width: 500, height: 300},
                            width: 800,
                            height: 350};
           //var materialChart = new google.charts.Bar(document.getElementById("columnchartPrimerasInstancias"));
           //materialChart.draw(data, google.charts.Bar.convertOptions(options));
           var chart = new google.visualization.ColumnChart(document.getElementById("columnchartSegundasInstancias"));
           chart.draw(data, options);
       }
	</script>
	<script>
		$(document).ready(function() {
			$("#menu_home").tooltip({
				container : "body",
				placement : "top"
			});
		});
	</script>
</html>