<?php
    session_start();
    require_once('../Includes/Config.php');
?>
<div class="modal fade" id="modalDetalleFactura" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">
                    &times;
                </button>
                <h4 class="modal-title" id="modal-title"></h4>
            </div>
            <div class="modal-body" id="modal-body">
                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default btn-sm font background-default cursor" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="modalHistorialFactura" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">
                    &times;
                </button>
                <h4 class="modal-title" id="modal-title-hist"></h4>
            </div>
            <div class="modal-body" id="modal-body-hist">
                <form style="background-color: #D0DEE7; border: 1px solid #A9BDC8;" class="form-horizontal row-bottom-buffer row-top-buffer" id="seleccionar_periodo" name="seleccionar_periodo">
                    <div class="form-group">
                        <div class="col-xs-8">
                            <input type="hidden" name="id_detalle" id="id_detalle" />
                            <select class="form-control input-text input-sm" id="periodo_factura" name="periodo_factura" data-toggle="tooltip" title="PERIODO FACTURADO" required>
                                <option value="" selected="selected">SELECCIONE EL PERIODO</option>
                                <?php
                                    $query_select_periodos = mysqli_query($connection, "SELECT DISTINCT(ANO_FACTURA), ID_MES_FACTURA, MES_FACTURA "
                                                                                     . "  FROM archivos_cargados_facturacion_2 "
                                                                                     . " ORDER BY ANO_FACTURA DESC, ID_MES_FACTURA DESC");
                                    while ($row_periodos = mysqli_fetch_assoc($query_select_periodos)) {
                                        if (strlen($row_periodos['ID_MES_FACTURA']) == 1) {
                                            echo "<option value='" . $row_periodos['ANO_FACTURA'] . "0" . $row_periodos['ID_MES_FACTURA'] . "'>" . $row_periodos['MES_FACTURA'] . " - " . $row_periodos['ANO_FACTURA'] . "</option>";
                                        } else {
                                            echo "<option value='" . $row_periodos['ANO_FACTURA'] . $row_periodos['ID_MES_FACTURA'] . "'>" . $row_periodos['MES_FACTURA'] . " - " . $row_periodos['ANO_FACTURA'] . "</option>";
                                        }
                                    }
                                ?>
                            </select>
                        </div>
                        <div class="col-xs-2">
                            <button class="btn btn-primary btn-sm font background cursor" id="btn_filtrar_periodo_factura" type="button"><i style="font-size: 14px;" class="fas fa-filter"></i>&nbsp;&nbsp;Filtrar Periodo</button>
                        </div>
                        <div class="col-xs-2">
                            <div style='margin-bottom: 30px; margin-top: 0px;'>
                                <span id='loading-spinner-historial' style='display: none; float: left;'><img src='Images/squares.gif' /></span>
                            </div>
                        </div>
                    </div>
                </form>
                <br />
                <div class='table-responsive'>
                    <table class='table table-condensed table-hover'>
                        <thead>
                            <tr>
                                <th width=10%>AÑO FACT.</th>
                                <th width=10%>MES FACT.</th>
                                <th width=10%>VALOR FACTURA</th>
                                <th width=10%>FECHA FACTURA</th>
                                <th width=10%>VALOR RECAUDO</th>
                                <th width=10%>FECHA RECAUDO</th>
                                <th width=10%>DEUDA MES</th>
                                <th width=10%>DETALLE</th>
                            </tr>
                        </thead>
                        <tbody id='resultado_nic_periodo'>
                            
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default btn-sm font background-default cursor" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="modalDetalleHistorialFactura" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">
                    &times;
                </button>
                <h4 id="modal-title-historial" class="modal-title"></h4>
            </div>
            <div id="modal-body-historial" class="modal-body">
                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default btn-sm font background-default cursor" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
<?php
    switch ($_POST['sw']) {
        case 0: ?>
            <h1>Resultado de la Busqueda por NIC</h1>
        <?php
            break;
        case 1: ?>
            <h1>Resultado de la Busqueda por Propietario</h1>
        <?php
            break;
        case 2: ?>
            <h1>Resultado de la Busqueda por Departamento</h1>
        <?php
            break;
    }
?>
<div style="display: none;" class="alert alert-success alert-dismissible text-center" role="alert" id="alert-consulta">
    Resultado Generado Satisfactoriamente. <i class="fas fa-check" aria-hidden="true"></i>
</div>
<h2></h2>
<?php
    $sw = $_POST['sw'];
    switch ($sw) {
        case 0:
            $nic = $_POST['nic'];
            echo "<input type='hidden' id='sw' name='sw' value='" . $sw . "' />";
            $query_select_ult_ano_fact = mysqli_query($connection, "SELECT MAX(ANO_FACTURA) AS ULT_ANO "
                                                                . "   FROM archivos_cargados_cartera_fallida_2");
            $row_ult_ano_fact = mysqli_fetch_array($query_select_ult_ano_fact);
            $bd_tabla_cartera_fallida = "cartera_fallida_" . $row_ult_ano_fact['ULT_ANO'] . "_2";
            $query_select_info_nic = mysqli_query($connection, "SELECT CAT.NIC AS NIC, CAT.NOMBRE_CLIENTE AS PROPIETARIO, "
                                                                    . "CAT.DIR_SUMINISTRO AS DIRECCION, "
                                                                    . "CAT.IMPORTE_TOTAL AS IMPORTE_TOTAL, "
                                                                    . "CAT.IMPORTE_CTA AS IMPORTE_CTA, "
                                                                    . "CAT.ANO_CARTERA_FALLIDA AS ANO, "
                                                                    . "DEP.NOMBRE AS DEPARTAMENTO, MUN.NOMBRE AS MUNICIPIO, "
                                                                    . "CORR.NOMBRE AS CORREGIMIENTO, LDAD.NOMBRE AS LOCALIDAD, "
                                                                    . "TAR.NOMBRE AS TARIFA, CAT.FECHA_FACT_ANT, CAT.FECHA_LECTURA, "
                                                                    . "CAT.FECHA_FACT, CAT.FECHA_PUESTA_COBRO, CAT.FECHA_VENC "
                                                               . "FROM $bd_tabla_cartera_fallida CAT, departamentos_2 DEP, "
                                                                    . "municipios_2 MUN, corregimientos_2 CORR, localidades_2 LDAD, "
                                                                    . "tarifas_2 TAR "
                                                              . "WHERE CAT.ID_COD_DPTO = DEP.ID_DEPARTAMENTO "
                                                                . "AND CAT.ID_COD_MPIO = MUN.ID_MUNICIPIO "
                                                                . "AND CAT.ID_COD_CORREG = CORR.ID_TABLA "
                                                                . "AND CAT.ID_COD_LDAD = LDAD.ID_TABLA "
                                                                . "AND DEP.ID_DEPARTAMENTO = MUN.ID_DEPARTAMENTO "
                                                                . "AND DEP.ID_DEPARTAMENTO = CORR.ID_DEPARTAMENTO "
                                                                . "AND DEP.ID_DEPARTAMENTO = LDAD.ID_DEPARTAMENTO "
                                                                . "AND MUN.ID_DEPARTAMENTO = CORR.ID_DEPARTAMENTO "
                                                                . "AND MUN.ID_MUNICIPIO = CORR.ID_MUNICIPIO "
                                                                . "AND MUN.ID_MUNICIPIO = LDAD.ID_MUNICIPIO "
                                                                . "AND CAT.ID_TARIFA = TAR.ID_TARIFA "
                                                                . "AND CAT.NIC = " . $nic . " "
                                                              . "ORDER BY CAT.FECHA_FACT_ANT DESC");
            if (mysqli_num_rows($query_select_info_nic) != 0) {
                $row_info_nic = mysqli_fetch_array($query_select_info_nic);
                $query_select_ult_ano_fact = mysqli_query($connection, "SELECT MAX(ANO_FACTURA) AS ULT_ANO "
                                                                    . "   FROM archivos_cargados_cartera_fallida_2");
                $row_ult_ano_fact = mysqli_fetch_array($query_select_ult_ano_fact);
                $ult_ano_fact = $row_ult_ano_fact['ULT_ANO'];
            } else {
                $query_select_max_periodo = mysqli_query($connection, "SELECT DISTINCT(ANO_FACTURA) "
                                                                    . "  FROM archivos_cargados_cartera_fallida_2 "
                                                                    . " ORDER BY ANO_FACTURA DESC");
                while ($row_max_periodo = mysqli_fetch_assoc($query_select_max_periodo)) {
                    $bd_tabla_cartera_fallida = "cartera_fallida_" . $row_max_periodo['ANO_FACTURA'] . "_2";
                    $query_select_info_nic = mysqli_query($connection, "SELECT CAT.NIC AS NIC, CAT.NOMBRE_CLIENTE AS PROPIETARIO, "
                                                                            . "CAT.DIR_SUMINISTRO AS DIRECCION, "
                                                                            . "CAT.IMPORTE_TOTAL AS IMPORTE_TOTAL, "
                                                                            . "CAT.IMPORTE_CTA AS IMPORTE_CTA, "
                                                                            . "CAT.ANO_CARTERA_FALLIDA AS ANO, "
                                                                            . "DEP.NOMBRE AS DEPARTAMENTO, MUN.NOMBRE AS MUNICIPIO, "
                                                                            . "CORR.NOMBRE AS CORREGIMIENTO, LDAD.NOMBRE AS LOCALIDAD, "
                                                                            . "TAR.NOMBRE AS TARIFA, CAT.FECHA_FACT_ANT, CAT.FECHA_LECTURA, "
                                                                            . "CAT.FECHA_FACT, CAT.FECHA_PUESTA_COBRO, CAT.FECHA_VENC "
                                                                       . "FROM $bd_tabla_cartera_fallida CAT, departamentos_2 DEP, "
                                                                            . "municipios_2 MUN, corregimientos_2 CORR, localidades_2 LDAD, "
                                                                            . "tarifas_2 TAR "
                                                                      . "WHERE CAT.ID_COD_DPTO = DEP.ID_DEPARTAMENTO "
                                                                        . "AND CAT.ID_COD_MPIO = MUN.ID_MUNICIPIO "
                                                                        . "AND CAT.ID_COD_CORREG = CORR.ID_TABLA "
                                                                        . "AND CAT.ID_COD_LDAD = LDAD.ID_TABLA "
                                                                        . "AND DEP.ID_DEPARTAMENTO = MUN.ID_DEPARTAMENTO "
                                                                        . "AND DEP.ID_DEPARTAMENTO = CORR.ID_DEPARTAMENTO "
                                                                        . "AND DEP.ID_DEPARTAMENTO = LDAD.ID_DEPARTAMENTO "
                                                                        . "AND MUN.ID_DEPARTAMENTO = CORR.ID_DEPARTAMENTO "
                                                                        . "AND MUN.ID_MUNICIPIO = CORR.ID_MUNICIPIO "
                                                                        . "AND MUN.ID_MUNICIPIO = LDAD.ID_MUNICIPIO "
                                                                        . "AND CAT.ID_TARIFA = TAR.ID_TARIFA "
                                                                        . "AND CAT.NIC = " . $nic . " "
                                                                      . "ORDER BY CAT.FECHA_FACT_ANT DESC");
                    if (mysqli_num_rows($query_select_info_nic) != 0) {
                        $row_info_nic = mysqli_fetch_array($query_select_info_nic);
                        break;
                    }
                }
            }
            echo "<form class='form-horizontal row-bottom-buffer row-top-buffer'>";
                echo "<div class='form-group'>";
                    echo "<div class='col-xs-4'>";
                        echo "<input type='hidden' id='bd_tabla_cartera_fallida' name='bd_tabla_cartera_fallida' value='" . $bd_tabla_cartera_fallida . "' />";
                        echo "<input type='text' class='form-control input-text input-sm' value='" . $row_info_nic['NIC'] . "' id='nic' name='nic' placeholder='NIC' data-toogle='tooltip' title='NIC' readonly='readonly' />";
                    echo "</div>";
                    echo "<div class='col-xs-4'>";
                        echo "<input type='text' class='form-control input-text input-sm' value='" . $row_info_nic['TARIFA'] . "' id='tarifa' name='tarifa' placeholder='Tarifa' data-toogle='tooltip' title='TARIFA' readonly='readonly' />";
                    echo "</div>";
                    echo "<div class='col-xs-3'></div>";
                    echo "<div class='col-xs-1'>";
                        echo "<button class='btn_historial' type='button' data-toggle='modal' id='" . $nic . "' data-target='#modalHistorialFactura' data-tooltip='tooltip' title='PERIODOS ANTERIORES'><img id='historial_factura' src='Images/search_history.png' width='16' height='16' /></button>";
                    echo "</div>";
                echo "</div>";
                echo "<div class='form-group'>";
                    echo "<div class='col-xs-6'>";
                        echo "<input type='text' class='form-control input-text input-sm' value='" . $row_info_nic['PROPIETARIO'] . "' id='propietario' name='propietario' placeholder='Propietario' data-toogle='tooltip' title='PROPIETARIO' readonly='readonly' />";
                    echo "</div>";
                    echo "<div class='col-xs-6'>";
                        echo "<input type='text' class='form-control input-text input-sm' value='" . $row_info_nic['DIRECCION'] . "' id='direccion' name='direccion' placeholder='Dirección' data-toogle='tooltip' title='DIRECCIÓN' readonly='readonly' />";
                    echo "</div>";
                echo "</div>";
                echo "<div class='form-group'>";
                    echo "<div class='col-xs-4'>";
                        echo "<input type='text' class='form-control input-text input-sm' value='" . $row_info_nic['DEPARTAMENTO'] . "' id='departamento' name='departamento' placeholder='Departamento' data-toogle='tooltip' title='DEPARTAMENTO' readonly='readonly' />";
                    echo "</div>";
                    echo "<div class='col-xs-4'>";
                        echo "<input type='text' class='form-control input-text input-sm' value='" . $row_info_nic['MUNICIPIO'] . "' id='municipio' name='municipio' placeholder='Municipio' data-toogle='tooltip' title='MUNICIPIO' readonly='readonly' />";
                    echo "</div>";
                echo "</div>";
                echo "<div class='form-group'>";
                    echo "<div class='col-xs-4'>";
                        echo "<input type='text' class='form-control input-text input-sm' value='" . $row_info_nic['CORREGIMIENTO'] . "' id='corregimiento' name='corregimiento' placeholder='Corregimiento' data-toogle='tooltip' title='CORREGIMIENTO' readonly='readonly' />";
                    echo "</div>";
                    echo "<div class='col-xs-4'>";
                        echo "<input type='text' class='form-control input-text input-sm' value='" . $row_info_nic['LOCALIDAD'] . "' id='localidad' name='localidad' placeholder='Localidad' data-toogle='tooltip' title='LOCALIDAD' readonly='readonly' />";
                    echo "</div>";
                echo "</div>";
                echo "<div class='form-group'>";
                    echo "<div class='col-xs-4'>";
                        echo "<input type='text' class='form-control input-text input-sm' value='" . $ult_ano_fact . "' id='periodo' name='periodo' placeholder='Periodo' data-toogle='tooltip' title='ULT. PERIODO CARGADO' readonly='readonly' />";
                    echo "</div>";
                    echo "<div class='col-xs-4'>";
                        echo "<div class='input-group'>";
                            echo "<span class='input-group-addon'>";
                                echo "<span class='fas fa-dollar-sign'></span>";
                            echo "</span>";
                            echo "<input type='text' class='form-control input-text input-sm' value='" . $row_info_nic['IMPORTE_TOTAL'] . "' id='importe_total' name='importe_total' placeholder='Importe Total' data-toogle='tooltip' title='IMPORTE TOTAL' readonly='readonly' />";
                        echo "</div>";
                    echo "</div>";
                    echo "<div class='col-xs-4'>";
                        echo "<div class='input-group'>";
                            echo "<span class='input-group-addon'>";
                                echo "<span class='fas fa-dollar-sign'></span>";
                            echo "</span>";
                            echo "<input type='text' class='form-control input-text input-sm' value='" . $row_info_nic['IMPORTE_CTA'] . "' id='importe_cta' name='importe_cta' placeholder='Importe CTA.' data-toogle='tooltip' title='IMPORTE CTA.' readonly='readonly' />";
                        echo "</div>";
                    echo "</div>";
                echo "</div>";
            echo "</form>";
            echo "<br />";
            //echo "<input type='text' class='form-control input-text input-sm' id='buscar_periodo' name='buscar_periodo' placeholder='BUSCAR PERIODO' onkeypress='return isNumeric(event)' />";
            //echo "<br />";
            $query_select_cartera_fallida_nic = mysqli_query($connection, "SELECT * "
                                                                        . "  FROM $bd_tabla_cartera_fallida CART "
                                                                        . " WHERE CART.NIC = " . $nic . " ");
            if (mysqli_num_rows($query_select_cartera_fallida_nic) != 0) {
                echo "<div class='table-responsive'>";
                    echo "<table class='table table-condensed table-hover'>";
                        echo "<thead>";
                            echo "<tr>";
                                echo "<th width=10%>AÑO CART.</th>";
                                echo "<th width=10%>IMPORTE TOTAL</th>";
                                echo "<th width=10%>IMPORTE CTA.</th>";
                                echo "<th width=10%>FECHA FACT. ANT.</th>";
                                echo "<th width=10%>FECHA LECTURA</th>";
                                echo "<th width=10%>FECHA FACT.</th>";
                                echo "<th width=10%>FECHA COBRO</th>";
                                echo "<th width=10%>FECHA VENC.</th>";
                            echo "</tr>";
                        echo "</thead>";
                        echo "<tbody id='resultado_nic'>";
                            
                        echo "</tbody>";
                    echo "</table>";
                echo "</div>";
            } else {
                echo "<br />";
		echo "<p class='message'>La Consulta no generó Resultados.</p>";
            }
            echo "<div id='div-pagination'>";
                echo "<ul id='pagination-nic'></ul>";
                echo "<span id='loading-spinner' style='display: none; float: right;'><img src='Images/squares.gif' /></span>";
            echo "</div>";
            break;
        case 1:
            $propietario = $_POST['propietario'];
            echo "<input type='hidden' id='sw' name='sw' value='" . $sw . "' />";
            echo "<input type='hidden' id='propietario' name='propietario' value='" . $propietario . "' />";
            $query_select_ult_ano_fact = mysqli_query($connection, "SELECT MAX(ANO_FACTURA) AS ULT_ANO "
                                                                . "   FROM archivos_cargados_facturacion_2");
            $row_ult_ano_fact = mysqli_fetch_array($query_select_ult_ano_fact);
            $query_select_ult_mes_fact = mysqli_query($connection, "SELECT MAX(ID_MES_FACTURA) AS ULT_MES "
                                                                . "   FROM archivos_cargados_facturacion_2 "
                                                                . "  WHERE ANO_FACTURA = " . $row_ult_ano_fact['ULT_ANO']);
            $row_ult_mes_fact = mysqli_fetch_array($query_select_ult_mes_fact);
            $query_select_mes_factura = mysqli_query($connection, "SELECT DISTINCT(MES_FACTURA) "
                                                                . "  FROM archivos_cargados_facturacion_2 "
                                                                . " WHERE ANO_FACTURA = " . $row_ult_ano_fact['ULT_ANO'] . " "
                                                                . "   AND ID_MES_FACTURA = " . $row_ult_mes_fact['ULT_MES']);
            $row_mes_factura = mysqli_fetch_array($query_select_mes_factura);
            $bd_tabla_catastro = "catastro_" . strtolower($row_mes_factura['MES_FACTURA']) . $row_ult_ano_fact['ULT_ANO'] . "_2";
            echo "<input type='hidden' id='bd_tabla_catastro' name='bd_tabla_catastro' value='" . $bd_tabla_catastro . "' />";
            $query_select_info_propietario = mysqli_query($connection, "SELECT CAT.NIC AS NIC, CAT.NOMBRE_PROPIETARIO AS PROPIETARIO, "
                                                                            . "CAT.DIRECCION_VIVIENDA AS DIRECCION, "
                                                                            . "CAT.DEUDA_CORRIENTE AS DEUDA_CORRIENTE, "
                                                                            . "CAT.DEUDA_CUOTA AS DEUDA_CUOTA, "
                                                                            . "CAT.ANO_CATASTRO AS ANO, CAT.MES_CATASTRO AS MES, "
                                                                            . "DEP.NOMBRE AS DEPARTAMENTO, MUN.NOMBRE AS MUNICIPIO, "
                                                                            . "CORR.NOMBRE AS CORREGIMIENTO, "
                                                                            . "EST.NOMBRE AS ESTADO_SUMINISTRO, TAR.NOMBRE AS TARIFA "
                                                                       . "FROM $bd_tabla_catastro CAT, departamentos_2 DEP, "
                                                                            . "municipios_2 MUN, corregimientos_2 CORR, "
                                                                            . "estados_suministro_2 EST, tarifas_2 TAR "
                                                                      . "WHERE CAT.ID_COD_DPTO = DEP.ID_DEPARTAMENTO "
                                                                        . "AND CAT.ID_COD_MPIO = MUN.ID_MUNICIPIO "
                                                                        . "AND CAT.ID_COD_CORREG = CORR.ID_TABLA "
                                                                        . "AND DEP.ID_DEPARTAMENTO = MUN.ID_DEPARTAMENTO "
                                                                        . "AND DEP.ID_DEPARTAMENTO = CORR.ID_DEPARTAMENTO "
                                                                        . "AND MUN.ID_DEPARTAMENTO = CORR.ID_DEPARTAMENTO "
                                                                        . "AND MUN.ID_MUNICIPIO = CORR.ID_MUNICIPIO "
                                                                        . "AND CAT.ID_TARIFA = TAR.ID_TARIFA "
                                                                        . "AND CAT.ID_ESTADO_SUMINISTRO = EST.ID_ESTADO_SUMINISTRO "
                                                                        . "AND CAT.NOMBRE_PROPIETARIO LIKE '%" . $propietario . "%' "
                                                                      . "ORDER BY CAT.NOMBRE_PROPIETARIO");
            if (mysqli_num_rows($query_select_info_propietario) != 0) {
                echo "<div class='table-responsive'>";
                    echo "<table class='table table-condensed table-hover'>";
                        echo "<thead>";
                            echo "<tr>";
                                echo "<th width=10%>NIC</th>";
                                echo "<th width=35%>PROPIETARIO</th>";
                                echo "<th width=10%>DEPARTAMENTO</th>";
                                echo "<th width=15%>MUNICIPIO</th>";
                                echo "<th width=25%>CORREGIMIENTO</th>";
                            echo "</tr>";
                        echo "</thead>";
                        echo "<tbody id='resultado_propietario'>";
                            
                        echo "</tbody>";
                    echo "</table>";
                echo "</div>";
            } else {
                echo "<br />";
		echo "<p class='message'>La Consulta no generó Resultados.</p>";
            }
            echo "<div id='div-pagination'>";
                echo "<ul id='pagination-propietario'></ul>";
                echo "<span id='loading-spinner' style='display: none; float: right;'><img src='Images/squares.gif' /></span>";
            echo "</div>";
            break;
        case '2':
            $departamento = $_POST['departamento'];
            $municipio = $_POST['municipio'];
            $id_ano_factura = $_POST['id_ano_factura'];
            if ($departamento == "") {
                $query_departamento = " ";
            } else {
                $query_departamento = " AND MUN.ID_DEPARTAMENTO = " . $departamento . " ";
            }
            if ($municipio == "") {
                $query_municipio = "";
            } else {
                $query_municipio = " AND MUN.ID_MUNICIPIO = " . $municipio . " ";
            }
            if ($id_ano_factura == "") {
                $query_select_ult_ano_fact = mysqli_query($connection, "SELECT MAX(ANO_FACTURA) AS ULT_ANO "
                                                                    . "   FROM archivos_cargados_facturacion_2");
                $row_ult_ano_fact = mysqli_fetch_array($query_select_ult_ano_fact);
                $query_select_ult_mes_fact = mysqli_query($connection, "SELECT MAX(ID_MES_FACTURA) AS ULT_MES "
                                                                    . "   FROM archivos_cargados_facturacion_2 "
                                                                    . "  WHERE ANO_FACTURA = " . $row_ult_ano_fact['ULT_ANO']);
                $row_ult_mes_fact = mysqli_fetch_array($query_select_ult_mes_fact);
                $query_select_mes_factura = mysqli_query($connection, "SELECT DISTINCT(MES_FACTURA) "
                                                                    . "  FROM archivos_cargados_facturacion_2 "
                                                                    . " WHERE ANO_FACTURA = " . $row_ult_ano_fact['ULT_ANO'] . " "
                                                                    . "   AND ID_MES_FACTURA = " . $row_ult_mes_fact['ULT_MES']);
                $row_mes_factura = mysqli_fetch_array($query_select_mes_factura);
                $id_ano_factura = $row_ult_ano_fact['ULT_ANO'];
                $id_mes_factura = $row_ult_mes_fact['ULT_MES'];
                $mes_factura = strtolower($row_mes_factura['MES_FACTURA']);
                $bd_tabla_facturacion = "facturacion_" .  strtolower($row_mes_factura['MES_FACTURA']) . $row_ult_ano_fact['ULT_ANO'] . "_2";
                $bd_tabla_recaudo = "recaudo_" . strtolower($row_mes_factura['MES_FACTURA']) . $row_ult_ano_fact['ULT_ANO'] . "_2";
                $bd_tabla_catastro = "catastro_" . strtolower($row_mes_factura['MES_FACTURA']) . $row_ult_ano_fact['ULT_ANO'] . "_2";
            } else {
                $id_mes_factura = $_POST['id_mes_factura'];
                $mes_factura = strtolower($_POST['mes_factura']);
                $bd_tabla_facturacion = "facturacion_" . $mes_factura . $id_ano_factura . "_2";
                $bd_tabla_recaudo = "recaudo_" . $mes_factura . $id_ano_factura . "_2";
                $bd_tabla_catastro = "catastro_" . $mes_factura . $id_ano_factura . "_2";
            }
            echo "<ul class='nav nav-pills' role='tablist'>";
                echo "<li role='presentation' class='active'>";
                    echo "<a href='#municipio_consolidado_tab' id='tab_municipio_consolidado' aria-controls='municipio_consolidado_tab' role='tab' data-toggle='tab'>Resumen - Consolidado</a>";
                echo "</li>";
            echo "</ul>";
            echo "<h2></h2>";
            echo "<div class='tab-content'>";
                echo "<div role='tabpanel' class='tab-pane fade in active' id='municipio_consolidado_tab'>";
                    echo "<input type='hidden' id='sw' name='sw' value='" . $sw . "' />";
                    echo "<input type='hidden' id='departamento' name='departamento' value='" . $departamento . "' />";
                    echo "<input type='hidden' id='municipio' name='municipio' value='" . $municipio . "' />";
                    echo "<input type='hidden' id='id_ano_factura' name='id_ano_factura' value='" . $id_ano_factura . "' />";
                    echo "<input type='hidden' id='id_mes_factura' name='id_mes_factura' value='" . $id_mes_factura . "' />";
                    echo "<input type='hidden' id='mes_factura' name='mes_factura' value='" . $mes_factura . "' />";
                    $query_select_info_departamento = mysqli_query($connection, "SELECT MUN.ID_MUNICIPIO, MUN.NOMBRE, COUNT(DISTINCT(NIC)) AS TOTAL_CLIENTES "
                                                                              . "  FROM municipios_2 MUN, $bd_tabla_facturacion FACT, tarifas_2 TAR "
                                                                              . " WHERE MUN.ID_DEPARTAMENTO = FACT.ID_COD_DPTO "
                                                                              . "   AND MUN.ID_MUNICIPIO = FACT.ID_COD_MPIO "
                                                                              . "   AND TAR.ID_TARIFA = FACT.ID_TARIFA "
                                                                              . $query_departamento . " "
                                                                              . $query_municipio . " "
                                                                              . " GROUP BY MUN.NOMBRE "
                                                                              . "HAVING COUNT(1) >= 1 "
                                                                              . " ORDER BY MUN.NOMBRE");
                    if (mysqli_num_rows($query_select_info_departamento) != 0) {
                        echo "<div id='resultado_departamento' class='table-scroll'>";
                            echo "<table class='table table-condensed table-hover'>";
                                echo "<thead>";
                                    echo "<tr>";
                                        echo "<th width=9%></th>";
                                        echo "<th width=5%>R1</th>";
                                        echo "<th width=5%>R2</th>";
                                        echo "<th width=5%>R3</th>";
                                        echo "<th width=5%>R4</th>";
                                        echo "<th width=5%>R5</th>";
                                        echo "<th width=5%>R6</th>";
                                        echo "<th width=5%>COM.</th>";
                                        echo "<th width=5%>OFC.</th>";
                                        echo "<th width=5%>IND.</th>";
                                        echo "<th width=10%>TOTALES</th>";
                                    echo "</tr>";
                                echo "</thead>";
                                echo "<tbody>";

                                echo "</tbody>";
                            echo "</table>";
                        echo "</div>";
                    } else {
                        echo "<br />";
                        echo "<p class='message'>La Consulta no generó Resultados.</p>";
                    }
                    echo "<div id='div-pagination'>";
                        echo "<ul id='pagination-departamento'></ul>";
                        echo "<span id='loading-spinner' style='display: none; float: right;'><img src='Images/squares.gif' /></span>";
                    echo "</div>";
                echo "</div>";
                echo "<div role='tabpanel' class='tab-pane fade' id='grafica_clientes_tab'>";
                    echo "<form class='form-horizontal row-bottom-buffer row-top-buffer' method='post' id='generar_cliente_pdf' action='Combos/Crear_PDF.php?pdf=cliente'>";
                        echo "<div class='form-group'>";
                            echo "<div class='col-xs-12'>";
                                echo "<div style='text-align: center; font-size: 16px;'>";
                                    echo "<b>TOTAL CLIENTES POR TARIFA</b>&nbsp;&nbsp;&nbsp;";
                                    //echo "<input type='hidden' name='hidden_cliente_html' id='hidden_cliente_html' />";
                                    //echo "<button class='btn_print' name='crear_pdf' id='crear_pdf' type='button' title='Generar PDF'><img src='Images/print_2.png' width='16' height='16' /></button>";
                                echo "</div>";
                            echo "</div>";
                        echo "</div>";
                        echo "<div class='form-group'>";
                            echo "<div class='col-xs-12'>";
                                echo "<div style='text-align: center; font-size: 12px;' id='piechartClientes'></div>";
                            echo "</div>";
                        echo "</div>";
                    echo "</form>";
                echo "</div>";
                echo "<div role='tabpanel' class='tab-pane fade' id='grafica_facturado_tab'>";
                    echo "<form class='form-horizontal row-bottom-buffer row-top-buffer' method='post' id='generar_factura_pdf' action='Combos/Crear_PDF.php?pdf=factura'>";
                        echo "<div class='form-group'>";
                            echo "<div class='col-xs-12'>";
                                echo "<div style='text-align: center; font-size: 16px;'>";
                                    echo "<b>TOTAL FACTURADO POR TARIFA</b>&nbsp;&nbsp;&nbsp;";
                                    //echo "<input type='hidden' name='hidden_factura_html' id='hidden_factura_html' />";
                                    //echo "<button class='btn_print' name='crear_pdf' id='crear_pdf' type='button' title='Generar PDF'><img src='Images/print_2.png' width='16' height='16' /></button>";
                                echo "</div>";
                            echo "</div>";
                        echo "</div>";
                        echo "<div class='form-group'>";
                            echo "<div class='col-xs-12'>";
                                echo "<div style='text-align: center; font-size: 12px;' id='piechartFactura'></div>";
                            echo "</div>";
                        echo "</div>";
                    echo "</form>";
                echo "</div>";
                echo "<div role='tabpanel' class='tab-pane fade' id='grafica_recaudo_tab'>";
                    echo "<form class='form-horizontal row-bottom-buffer row-top-buffer' method='post' id='generar_recaudo_pdf' action='Combos/Crear_PDF.php?pdf=recaudo'>";
                        echo "<div class='form-group'>";
                            echo "<div class='col-xs-12'>";
                                echo "<div style='text-align: center; font-size: 16px;'>";
                                    echo "<b>TOTAL RECAUDO POR TARIFA</b>&nbsp;&nbsp;&nbsp;";
                                    //echo "<input type='hidden' name='hidden_recaudo_html' id='hidden_recaudo_html' />";
                                    //echo "<button class='btn_print' name='crear_pdf' id='crear_pdf' type='button' title='Generar PDF'><img src='Images/print_2.png' width='16' height='16' /></button>";
                                echo "</div>";
                            echo "</div>";
                        echo "</div>";
                        echo "<div class='form-group'>";
                            echo "<div class='col-xs-12'>";
                                echo "<div style='text-align: center; font-size: 12px;' id='piechartRecaudo'></div>";
                            echo "</div>";
                        echo "</div>";
                    echo "</form>";
                echo "</div>";
            echo "</div>";
            break;
    }
?>
    <script>
        function isNumeric(evt) {
            var charCode = (evt.which) ? evt.which : event.keyCode
            if (charCode > 31 && (charCode < 48 || charCode > 57)) {
                return false;
            }
            return true;
        }
        function convertValorImporteTotal() {
            var importe_total = $("#importe_total").val();
            var replaceImporteTotal = importe_total.replace(/,/g, '');
            var new_ImporteTotal = replaceImporteTotal.toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,");
            $("#importe_total").val(new_ImporteTotal);
        }
        function convertValorImporteCta() {
            var importe_cta = $("#importe_cta").val();
            var replaceImporteCta = importe_cta.replace(/,/g, '');
            var new_ImporteCta = replaceImporteCta.toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,");
            $("#importe_cta").val(new_ImporteCta);
        }
    </script>
    <script>
        $(document).ready(function() {
            $("#alert-consulta").attr("display", "block");
            $("#alert-consulta").fadeTo(3000, 500).fadeOut(500, function() {
                $("#alert-consulta").fadeOut();
            });
            var sw = $("#sw").val();
            switch (sw) {
                case '0':
                    //$("#buscar_periodo").focus();
                    var nic = $("#nic").val();
                    var bd_tabla_cartera_fallida = $("#bd_tabla_cartera_fallida").val();
                    convertValorImporteTotal();
                    convertValorImporteCta();
                    $.ajax({
                        type: "POST",
                        url: "Modelo/Cargar_Paginacion_Resultados_Cartera.php",
                        dataType: "json",
                        data: "nic="+nic+"&sw="+sw+
                              "&bd_tabla_cartera_fallida="+bd_tabla_cartera_fallida,
                        success: function(data) {
                            $("#pagination-nic").twbsPagination('destroy');
                            $("#pagination-nic").twbsPagination({
                                totalPages: data[0],
                                visiblePages: 15,
                                first: '<i class="fas fa-angle-double-left" aria-hidden="true"></i>',
                                prev: '<i class="fas fa-angle-left" aria-hidden="true"></i>',
                                next: '<i class="fas fa-angle-right" aria-hidden="true"></i>',
                                last: '<i class="fas fa-angle-double-right" aria-hidden="true"></i>',
                                onPageClick: function (event, page) {
                                    $("#loading-spinner").css('display', 'block');
                                    $.ajax({
                                        type: "POST",
                                        url: "Modelo/Cargar_Resultados_Cartera.php",
                                        data: "nic="+nic+"&sw="+sw+"&page="+page+
                                              "&bd_tabla_cartera_fallida="+bd_tabla_cartera_fallida,
                                        success: function(data) {
                                            //alert(data);
                                            $("#loading-spinner").css('display', 'none');
                                            $("#resultado_nic").html(data);
                                        }
                                    });
                                }
                            });
                        }
                    });
                    /*$("#modalHistorialFactura").on('show.bs.modal', function(e) {
                        $(".btn_historial").tooltip("hide");
                        var detalle_id = e.relatedTarget.id;
                        $("#id_detalle").val(detalle_id);
                        $("#modal-title-hist").html("");
                        $("#modal-body-hist").html("<div style='margin-bottom: 30px; margin-top: 0px;'><span id='loading-spinner-historial' style='display: block; float: left;'><img src='Images/squares.gif' /></span></div>");
                        $.ajax ({
                            type: "POST",
                            url: "Modelo/Cargar_Historial_Factura_Recaudo.php",
                            dataType: "json",
                            data: "detalle_id="+detalle_id,
                            success: function(data) {
                                $("#modal-title-hist").html(data[0]);
                                $("#modal-body-hist").html(data[1]);
                            }
                        });
                    });*/
                    $("#modalHistorialFactura").on('show.bs.modal', function(e) {
                        $(".btn_historial").tooltip("hide");
                        $("#modal-title-hist").html("");
                        $("#resultado_nic_periodo").html("");
                        var detalle_id = e.relatedTarget.id;
                        $("#id_detalle").val(detalle_id);
                        $("#periodo_factura").val($("#periodo_factura option:first").val());
                    });
                    $("#btn_filtrar_periodo_factura").click(function() {
                        var detalle_id = $("#id_detalle").val();
                        var periodo_factura = $("#periodo_factura").val();
                        if (periodo_factura.length == 0) {
                            $("#periodo_factura").focus();
                            return false;
                        }
                        $("#loading-spinner-historial").css('display', 'block');
                        $.ajax ({
                            type: "POST",
                            url: "Modelo/Cargar_Historial_Factura_Recaudo.php",
                            dataType: "json",
                            data: "detalle_id="+detalle_id+"&periodo_factura="+periodo_factura,
                            success: function(data) {
                                $("#loading-spinner-historial").css('display', 'none');
                                $("#modal-title-hist").html(data[0]);
                                $("#resultado_nic_periodo").html(data[1]);
                            }
                        });
                    });
                    $("#modalDetalleHistorialFactura").on('show.bs.modal', function(e) {
                        var detalle_id = e.relatedTarget.id;
                        $("#modal-title-historial").html("");
                        $("#modal-body-historial").html("<div style='margin-bottom: 30px; margin-top: 0px;'><span id='loading-spinner-detalle-historial' style='display: block; float: left;'><img src='Images/squares.gif' /></span></div>");
                        $.ajax ({
                            type: "POST",
                            url: "Modelo/Cargar_Detalle_Factura_Recaudo.php",
                            dataType: "json",
                            data: "detalle_id="+detalle_id,
                            success: function(data) {
                                $("#modal-title-historial").html(data[0]);
                                $("#modal-body-historial").html(data[1]);
                            }
                        });
                    });
                    break;
                case '1':
                    var propietario = $("#propietario").val();
                    var bd_tabla_catastro = $("#bd_tabla_catastro").val();
                    $.ajax({
                        type: "POST",
                        url: "Modelo/Cargar_Paginacion_Resultados.php",
                        dataType: "json",
                        data: "propietario="+propietario+"&sw="+sw+"&bd_tabla_catastro="+bd_tabla_catastro,
                        success: function(data) {
                            $("#pagination-propietario").twbsPagination('destroy');
                            $("#pagination-propietario").twbsPagination({
                                totalPages: data[0],
                                visiblePages: 15,
                                first: '<i class="fas fa-angle-double-left" aria-hidden="true"></i>',
                                prev: '<i class="fas fa-angle-left" aria-hidden="true"></i>',
                                next: '<i class="fas fa-angle-right" aria-hidden="true"></i>',
                                last: '<i class="fas fa-angle-double-right" aria-hidden="true"></i>',
                                onPageClick: function (event, page) {
                                    $("#loading-spinner").css('display', 'block');
                                    $.ajax({
                                        type: "POST",
                                        url: "Modelo/Cargar_Resultados.php",
                                        data: "propietario="+propietario+"&sw="+sw+"&page="+page+"&bd_tabla_catastro="+bd_tabla_catastro,
                                        success: function(data) {
                                            $("#loading-spinner").css('display', 'none');
                                            $("#resultado_propietario").html(data);
                                        }
                                    });
                                }
                            });
                        }
                    });
                    break;
                case '2':
                    var departamento = $("#departamento").val();
                    var municipio = $("#municipio").val();
                    var id_ano_factura = $("#id_ano_factura").val();
                    var id_mes_factura = $("#id_mes_factura").val();
                    var mes_factura = $("#mes_factura").val();
                    $.ajax({
                        type: "POST",
                        url: "Modelo/Cargar_Paginacion_Resultados.php",
                        dataType: "json",
                        data: "departamento="+departamento+"&municipio="+municipio+"&sw="+sw+
                              "&id_ano_factura="+id_ano_factura+"&id_mes_factura="+id_mes_factura+
                              "&mes_factura="+mes_factura,
                        success: function(data) {
                            $("#pagination-departamento").twbsPagination('destroy');
                            $("#pagination-departamento").twbsPagination({
                                totalPages: data[0],
                                visiblePages: 15,
                                first: '<i class="fas fa-angle-double-left" aria-hidden="true"></i>',
                                prev: '<i class="fas fa-angle-left" aria-hidden="true"></i>',
                                next: '<i class="fas fa-angle-right" aria-hidden="true"></i>',
                                last: '<i class="fas fa-angle-double-right" aria-hidden="true"></i>',
                                onPageClick: function (event, page) {
                                    $("#loading-spinner").css('display', 'block');
                                    $.ajax({
                                        type: "POST",
                                        url: "Modelo/Cargar_Resultados.php",
                                        data: "departamento="+departamento+"&municipio="+municipio+"&sw="+sw+"&page="+page+
                                              "&id_ano_factura="+id_ano_factura+"&id_mes_factura="+id_mes_factura+
                                              "&mes_factura="+mes_factura,
                                        success: function(data) {
                                            $("#loading-spinner").css('display', 'none');
                                            $("#resultado_departamento").html(data);
                                        }
                                    });
                                }
                            });
                        }
                    });
                    break;
            }
        });
    </script>
    <script>
        $(document).ready(function() {
            $('input[type=text][name=nic]').tooltip({
                container: "body",
                placement: "top"
            });
            $('.btn_historial').tooltip({
                container: "body",
                placement: "top"
            });
            $('input[type=text][name=tarifa]').tooltip({
                container: "body",
                placement: "right"
            });
            $('input[type=text][name=estado_suministro]').tooltip({
                container: "body",
                placement: "top"
            });
            $('input[type=text][name=propietario]').tooltip({
                container: "body",
                placement: "top"
            });
            $('input[type=text][name=direccion]').tooltip({
                container: "body",
                placement: "top"
            });
            $('input[type=text][name=departamento]').tooltip({
                container: "body",
                placement: "top"
            });
            $('input[type=text][name=municipio]').tooltip({
                container: "body",
                placement: "right"
            });
            $('input[type=text][name=corregimiento]').tooltip({
                container: "body",
                placement: "top"
            });
            $('input[type=text][name=localidad]').tooltip({
                container: "body",
                placement: "right"
            });
            $('input[type=text][name=periodo]').tooltip({
                container: "body",
                placement: "top"
            });
            $('input[type=text][name=importe_total]').tooltip({
                container: "body",
                placement: "top"
            });
            $('input[type=text][name=importe_cta]').tooltip({
                container: "body",
                placement: "top"
            });
            $('select[name=periodo_factura]').tooltip({
                container : "body",
                placement : "top"
            });
        });
    </script>