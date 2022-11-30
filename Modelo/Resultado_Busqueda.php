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
                <ul class="nav nav-pills" role="tablist">
                    <li role="presentation" class="active">
                        <a href="#historial_periodo_tab" id="tab_historial_periodo" aria-controls="historial_periodo_tab" role="tab" data-toggle="tab">Por Periodo</a>
                    </li>
                    <li role="presentation">
                        <a href="#historial_anual_tab" id="tab_historial_anual" aria-controls="historial_anual_tab" role="tab" data-toggle="tab">Por Año</a>
                    </li>
                </ul>
                <h2></h2>
                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane fade in active" id="historial_periodo_tab">
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
                                        <span id='loading-spinner-historial-periodo' style='display: none; float: left;'><img src='Images/squares.gif' /></span>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div role="tabpanel" class="tab-pane fade" id="historial_anual_tab">
                        <form style="background-color: #D0DEE7; border: 1px solid #A9BDC8;" class="form-horizontal row-bottom-buffer row-top-buffer" id="seleccionar_ano" name="seleccionar_ano">
                            <div class="form-group">
                                <div class="col-xs-8">
                                    <input type="hidden" name="id_detalle" id="id_detalle" />
                                    <select class="form-control input-text input-sm" id="ano_factura" name="ano_factura" data-toggle="tooltip" title="AÑO FACTURADO" required>
                                        <option value="" selected="selected">SELECCIONE EL AÑO</option>
                                        <?php
                                            $query_select_anos = mysqli_query($connection, "SELECT DISTINCT(ANO_FACTURA) "
                                                                                         . "  FROM archivos_cargados_facturacion_2 "
                                                                                         . " ORDER BY ANO_FACTURA DESC");
                                            while ($row_anos = mysqli_fetch_assoc($query_select_anos)) {
                                                echo "<option value='" . $row_anos['ANO_FACTURA'] . "'>" . $row_anos['ANO_FACTURA'] . "</option>";
                                            }
                                        ?>
                                    </select>
                                </div>
                                <div class="col-xs-2">
                                    <button class="btn btn-primary btn-sm font background cursor" id="btn_filtrar_ano_factura" type="button"><i style="font-size: 14px;" class="fas fa-filter"></i>&nbsp;&nbsp;Filtrar Periodo</button>
                                </div>
                                <div class="col-xs-2">
                                    <div style='margin-bottom: 30px; margin-top: 0px;'>
                                        <span id='loading-spinner-historial-ano' style='display: none; float: left;'><img src='Images/squares.gif' /></span>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <br />
                <div class='table-responsive'>
                    <table class='table table-condensed table-hover'>
                        <thead>
                            <tr>
                                <th width=10%>PERIODO</th>
                                <th width=10%>SV</th>
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
        case 3: ?>
            <h1>Resultado de la Busqueda por Tarifas</h1>
        <?php
            break;
        case 4: ?>
            <h1>Resultado de la Busqueda por Cambio de Tarifa</h1>
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
            $bd_tabla_facturacion = "facturacion_" .  strtolower($row_mes_factura['MES_FACTURA']) . $row_ult_ano_fact['ULT_ANO'] . "_2";
            $bd_tabla_recaudo = "recaudo_" . strtolower($row_mes_factura['MES_FACTURA']) . $row_ult_ano_fact['ULT_ANO'] . "_2";
            $bd_tabla_catastro = "catastro_" . strtolower($row_mes_factura['MES_FACTURA']) . $row_ult_ano_fact['ULT_ANO'] . "_2";
            $query_select_info_nic = mysqli_query($connection, "SELECT CAT.NIC AS NIC, CAT.NOMBRE_PROPIETARIO AS PROPIETARIO, "
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
                                                                . "AND CAT.NIC = " . $nic);
            if (mysqli_num_rows($query_select_info_nic) != 0) {
                $row_info_nic = mysqli_fetch_array($query_select_info_nic);
                $query_select_ult_ano_fact = mysqli_query($connection, "SELECT MAX(ANO_FACTURA) AS ULT_ANO "
                                                                    . "   FROM archivos_cargados_facturacion_2");
                $row_ult_ano_fact = mysqli_fetch_array($query_select_ult_ano_fact);
                $ult_ano_fact = $row_ult_ano_fact['ULT_ANO'];
                $query_select_ult_mes_fact = mysqli_query($connection, "SELECT MAX(ID_MES_FACTURA) AS ULT_MES "
                                                                    . "   FROM archivos_cargados_facturacion_2 "
                                                                    . "  WHERE ANO_FACTURA = " . $row_ult_ano_fact['ULT_ANO']);
                $row_ult_mes_fact = mysqli_fetch_array($query_select_ult_mes_fact);
                if (strlen($row_ult_mes_fact['ULT_MES']) == 1) {
                    $ult_mes_fact = "0" . $row_ult_mes_fact['ULT_MES'];
                } else {
                    $ult_mes_fact = $row_ult_mes_fact['ULT_MES'];
                }
            } else {
                $query_select_max_periodo = mysqli_query($connection, "SELECT DISTINCT(ANO_FACTURA), ID_MES_FACTURA, MES_FACTURA "
                                                                    . "  FROM archivos_cargados_facturacion_2 "
                                                                    . " ORDER BY ANO_FACTURA DESC, ID_MES_FACTURA DESC");
                while ($row_max_periodo = mysqli_fetch_assoc($query_select_max_periodo)) {
                    $bd_tabla_catastro = "catastro_" . strtolower($row_max_periodo['MES_FACTURA']) . $row_max_periodo['ANO_FACTURA'] . "_2";
                    $query_select_info_nic = mysqli_query($connection, "SELECT CAT.NIC AS NIC, CAT.NOMBRE_PROPIETARIO AS PROPIETARIO, "
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
                                                                        . "AND CAT.NIC = " . $nic);
                    if (mysqli_num_rows($query_select_info_nic) != 0) {
                        $row_info_nic = mysqli_fetch_array($query_select_info_nic);
                        $bd_tabla_facturacion = "facturacion_" .  strtolower($row_max_periodo['MES_FACTURA']) . $row_max_periodo['ANO_FACTURA'] . "_2";
                        $bd_tabla_recaudo = "recaudo_" . strtolower($row_max_periodo['MES_FACTURA']) . $row_max_periodo['ANO_FACTURA'] . "_2";
                        $ult_ano_fact = $row_info_nic['ANO'];
                        if (strlen($row_info_nic['MES']) == 1) {
                            $ult_mes_fact = "0" . $row_info_nic['MES'];
                        } else {
                            $ult_mes_fact = $row_info_nic['MES'];
                        }
                        break;
                    }
                }
            }
            echo "<form class='form-horizontal row-bottom-buffer row-top-buffer'>";
                echo "<div class='form-group'>";
                    echo "<div class='col-xs-4'>";
                        echo "<input type='hidden' id='bd_tabla_facturacion' name='bd_tabla_facturacion' value='" . $bd_tabla_facturacion . "' />";
                        echo "<input type='hidden' id='bd_tabla_recaudo' name='bd_tabla_recaudo' value='" . $bd_tabla_recaudo . "' />";
                        echo "<input type='text' class='form-control input-text input-sm' value='" . $row_info_nic['NIC'] . "' id='nic' name='nic' placeholder='NIC' data-toogle='tooltip' title='NIC' readonly='readonly' />";
                    echo "</div>";
                    echo "<div class='col-xs-4'>";
                        echo "<input type='text' class='form-control input-text input-sm' value='" . $row_info_nic['TARIFA'] . "' id='tarifa' name='tarifa' placeholder='Tarifa' data-toogle='tooltip' title='TARIFA' readonly='readonly' />";
                    echo "</div>";
                    echo "<div class='col-xs-3'>";
                        echo "<input type='text' class='form-control input-text input-sm' value='" . $row_info_nic['ESTADO_SUMINISTRO'] . "' id='estado_suministro' name='estado_suministro' placeholder='Estado Suministro' data-toogle='tooltip' title='ESTADO SUMINISTRO' readonly='readonly' />";
                    echo "</div>";
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
                    echo "<div class='col-xs-4'>";
                        echo "<input type='text' class='form-control input-text input-sm' value='" . $row_info_nic['CORREGIMIENTO'] . "' id='corregimiento' name='corregimiento' placeholder='Corregimiento' data-toogle='tooltip' title='CORREGIMIENTO' readonly='readonly' />";
                    echo "</div>";
                echo "</div>";
                echo "<div class='form-group'>";
                    echo "<div class='col-xs-4'>";
                        echo "<input type='text' class='form-control input-text input-sm' value='" . $ult_ano_fact . $ult_mes_fact . "' id='periodo' name='periodo' placeholder='Periodo' data-toogle='tooltip' title='ULT. PERIODO CARGADO' readonly='readonly' />";
                    echo "</div>";
                    echo "<div class='col-xs-4'>";
                        echo "<div class='input-group'>";
                            echo "<span class='input-group-addon'>";
                                echo "<span class='fas fa-dollar-sign'></span>";
                            echo "</span>";
                            echo "<input type='text' class='form-control input-text input-sm' value='" . $row_info_nic['DEUDA_CORRIENTE'] . "' id='deuda_corriente' name='deuda_corriente' placeholder='Deuda Corriente' data-toogle='tooltip' title='DEUDA CORRIENTE' readonly='readonly' />";
                        echo "</div>";
                    echo "</div>";
                    echo "<div class='col-xs-4'>";
                        echo "<div class='input-group'>";
                            echo "<span class='input-group-addon'>";
                                echo "<span class='fas fa-dollar-sign'></span>";
                            echo "</span>";
                            echo "<input type='text' class='form-control input-text input-sm' value='" . $row_info_nic['DEUDA_CUOTA'] . "' id='deuda_cuota' name='deuda_cuota' placeholder='Deuda Cuota' data-toogle='tooltip' title='DEUDA CUOTA' readonly='readonly' />";
                        echo "</div>";
                    echo "</div>";
                echo "</div>";
            echo "</form>";
            echo "<br />";
            //echo "<input type='text' class='form-control input-text input-sm' id='buscar_periodo' name='buscar_periodo' placeholder='BUSCAR PERIODO' onkeypress='return isNumeric(event)' />";
            //echo "<br />";
            $query_select_facturacion_nic = mysqli_query($connection, "SELECT FACT.VALOR_RECIBO AS VALOR_FACTURA, FACT.FECHA_TRANS AS FECHA_FACTURA, "
                                                                           . "RECA.VALOR_RECIBO AS VALOR_RECAUDO, RECA.FECHA_TRANS AS FECHA_RECAUDO, "
                                                                           . "FACT.ANO_FACTURA, FACT.MES_FACTURA "
                                                                    . "  FROM $bd_tabla_facturacion FACT "
                                                                    . "  LEFT JOIN $bd_tabla_recaudo RECA "
                                                                    . "    ON FACT.NIC = RECA.NIC "
                                                                    . "   AND FACT.FECHA_FACT_LECT = RECA.FECHA_FACT_LECT "
                                                                    . " WHERE FACT.NIC = " . $nic . " "
                                                                    . " GROUP BY FACT.COD_OPER_CONT");
            if (mysqli_num_rows($query_select_facturacion_nic) != 0) {
                echo "<div class='table-responsive'>";
                    echo "<table class='table table-condensed table-hover'>";
                        echo "<thead>";
                            echo "<tr>";
                                echo "<th width=10%>AÑO FACT.</th>";
                                echo "<th width=10%>MES FACT.</th>";
                                echo "<th width=10%>VALOR FACTURA</th>";
                                echo "<th width=10%>FECHA FACTURA</th>";
                                echo "<th width=10%>VALOR RECAUDO</th>";
                                echo "<th width=10%>FECHA RECAUDO</th>";
                                echo "<th width=10%>DEUDA MES</th>";
                                echo "<th width=10%>DETALLE</th>";
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
                $bd_tabla_refacturacion = "refacturacion_" . strtolower($row_mes_factura['MES_FACTURA']) . $row_ult_ano_fact['ULT_ANO'] . "_2";
            } else {
                $id_mes_factura = $_POST['id_mes_factura'];
                $mes_factura = strtolower($_POST['mes_factura']);
                $bd_tabla_facturacion = "facturacion_" . $mes_factura . $id_ano_factura . "_2";
                $bd_tabla_recaudo = "recaudo_" . $mes_factura . $id_ano_factura . "_2";
                $bd_tabla_catastro = "catastro_" . $mes_factura . $id_ano_factura . "_2";
                $bd_tabla_refacturacion = "refacturacion_" . $mes_factura . $id_ano_factura . "_2";
            }
            echo "<ul class='nav nav-pills' role='tablist'>";
                echo "<li role='presentation' class='active'>";
                    echo "<a href='#municipio_consolidado_tab' id='tab_municipio_consolidado' aria-controls='municipio_consolidado_tab' role='tab' data-toggle='tab'>Resumen - Consolidado</a>";
                echo "</li>";
                /*echo "<li role='presentation'>";
                    echo "<a href='#grafica_clientes_tab' id='tab_grafica_cliente' aria-controls='grafica_clientes_tab' role='tab' data-toggle='tab'>Gráfica - Clientes</a>";
                echo "</li>";
                echo "<li role='presentation'>";
                    echo "<a href='#grafica_facturado_tab' id='tab_grafica_facturado' aria-controls='grafica_facturado_tab' role='tab' data-toggle='tab'>Gráfica - Facturado</a>";
                echo "</li>";
                echo "<li role='presentation'>";
                    echo "<a href='#grafica_recaudo_tab' id='tab_grafica_recaudo' aria-controls='grafica_recaudo_tab' role='tab' data-toggle='tab'>Gráfica - Recaudo</a>";
                echo "</li>";*/
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
                    /*$rows = array();
                    $query_select_clientes = mysqli_query($connection, "SELECT T.NOMBRE, COUNT(*)
                                                                          FROM $bd_tabla_catastro C, tarifas_2 T
                                                                         WHERE C.ID_TARIFA = T.ID_TARIFA
                                                                           AND C.ID_COD_DPTO = $departamento
                                                                           AND C.ID_COD_MPIO = $municipio
                                                                         GROUP BY C.ID_TARIFA
                                                                         ORDER BY COUNT(*) DESC, T.NOMBRE ASC");
                    $table = array();
                    $table['cols'] = array(
                        array('label' => 'Tarifas', 'type' => 'string'),
                        array('label' => 'Total', 'type' => 'number')
                    );
                    while ($row_clientes = mysqli_fetch_assoc($query_select_clientes)) {
                        $temp = array();
                        $temp[] = array('v' => (string) $row_clientes['NOMBRE']);
                        $temp[] = array('v' => (int) $row_clientes['COUNT(*)']);
                        $rows[] = array('c' => $temp);
                    }
                    $table['rows'] = $rows;
                    $jsonTableClientes = json_encode($table);*/
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
                    /*$rows = array();
                    $query_select_factura = mysqli_query($connection, "SELECT T.NOMBRE, SUM(F.IMPORTE_TRANS)
                                                                          FROM $bd_tabla_facturacion F, tarifas_2 T
                                                                         WHERE F.ID_TARIFA = T.ID_TARIFA
                                                                           AND F.ID_COD_DPTO = $departamento
                                                                           AND F.ID_COD_MPIO = $municipio
                                                                           AND F.CONCEPTO <> 'CI306'
                                                                         GROUP BY F.ID_TARIFA
                                                                         ORDER BY COUNT(*) DESC, T.NOMBRE ASC");
                    $table = array();
                    $table['cols'] = array(
                        array('label' => 'Tarifas', 'type' => 'string'),
                        array('label' => 'Total', 'type' => 'number')
                    );
                    while ($row_factura = mysqli_fetch_assoc($query_select_factura)) {
                        $temp = array();
                        $temp[] = array('v' => (string) $row_factura['NOMBRE']);
                        $temp[] = array('v' => (int) max($row_factura['SUM(F.IMPORTE_TRANS)'], 0));
                        $rows[] = array('c' => $temp);
                    }
                    $table['rows'] = $rows;
                    $jsonTableFactura = json_encode($table);*/
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
                    /*$rows = array();
                    $query_select_recaudo = mysqli_query($connection, "SELECT T.NOMBRE, SUM(R.IMPORTE_TRANS)
                                                                          FROM $bd_tabla_recaudo R, tarifas_2 T
                                                                         WHERE R.ID_TARIFA = T.ID_TARIFA
                                                                           AND R.ID_COD_DPTO = $departamento
                                                                           AND R.ID_COD_MPIO = $municipio
                                                                         GROUP BY R.ID_TARIFA
                                                                         ORDER BY COUNT(*) DESC, T.NOMBRE ASC");
                    $table = array();
                    $table['cols'] = array(
                        array('label' => 'Tarifas', 'type' => 'string'),
                        array('label' => 'Total', 'type' => 'number')
                    );
                    while ($row_recaudo = mysqli_fetch_assoc($query_select_recaudo)) {
                        $temp = array();
                        $temp[] = array('v' => (string) $row_recaudo['NOMBRE']);
                        $temp[] = array('v' => (int) max($row_recaudo['SUM(R.IMPORTE_TRANS)'], 0));
                        $rows[] = array('c' => $temp);
                    }
                    $table['rows'] = $rows;
                    $jsonTableRecaudo = json_encode($table);*/
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
        case '3':
            $tarifa = $_POST['tarifa'];
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
                $bd_tabla_catastro = "catastro_" .  strtolower($row_mes_factura['MES_FACTURA']) . $row_ult_ano_fact['ULT_ANO'] . "_2";
            } else {
                $id_mes_factura = $_POST['id_mes_factura'];
                $mes_factura = strtolower($_POST['mes_factura']);
                $bd_tabla_catastro = "catastro_" . $mes_factura . $id_ano_factura . "_2";
            }
            echo "<input type='hidden' id='sw' name='sw' value='" . $sw . "' />";
            echo "<input type='hidden' id='tarifa' name='tarifa' value='" . $tarifa . "' />";
            echo "<input type='hidden' id='departamento' name='departamento' value='" . $departamento . "' />";
            echo "<input type='hidden' id='municipio' name='municipio' value='" . $municipio . "' />";
            echo "<input type='hidden' id='id_ano_factura' name='id_ano_factura' value='" . $id_ano_factura . "' />";
            echo "<input type='hidden' id='id_mes_factura' name='id_mes_factura' value='" . $id_mes_factura . "' />";
            echo "<input type='hidden' id='mes_factura' name='mes_factura' value='" . $mes_factura . "' />";
            $query_select_info_departamento = mysqli_query($connection, "SELECT MUN.ID_MUNICIPIO, MUN.NOMBRE, COUNT(DISTINCT(NIC)) AS TOTAL_CLIENTES "
                                                                      . "  FROM municipios_2 MUN, $bd_tabla_catastro CATA, tarifas_2 TAR "
                                                                      . " WHERE MUN.ID_DEPARTAMENTO = CATA.ID_COD_DPTO "
                                                                      . "   AND MUN.ID_MUNICIPIO = CATA.ID_COD_MPIO "
                                                                      . "   AND TAR.ID_TARIFA = CATA.ID_TARIFA "
                                                                      . $query_departamento . " "
                                                                      . $query_municipio . " "
                                                                      . " GROUP BY MUN.NOMBRE "
                                                                      . "HAVING COUNT(1) >= 1 "
                                                                      . " ORDER BY MUN.NOMBRE");
            if (mysqli_num_rows($query_select_info_departamento) != 0) {
                echo "<div id='resultado_tarifa' class='table-scroll'>";
                    echo "<table class='table table-condensed table-hover'>";
                        echo "<thead>";
                            echo "<tr>";
                                echo "<th width=9%></th>";
                                $query_select_cod_tarifa = mysqli_query($connection, "SELECT COD_TARIFA FROM tarifas_2 WHERE ID_TARIFA = " . $tarifa);
                                $row_cod_tarifa = mysqli_fetch_array($query_select_cod_tarifa);
                                echo "<th width=19%>SITUACIÓN CORRECTA</th>";
                                echo "<th width=19%>ALTA SIN FACTURAR</th>";
                                echo "<th width=19%>SIN CONTRATO</th>";
                                echo "<th width=19%>BAJA FORZADA</th>";
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
                echo "<ul id='pagination-tarifa'></ul>";
                echo "<span id='loading-spinner' style='display: none; float: right;'><img src='Images/squares.gif' /></span>";
            echo "</div>";
            break;
        case '4':
            $id_ano_cambio_tarifa = $_POST['id_ano_cambio_tarifa'];
            if ($id_ano_cambio_tarifa == "") {
                $query_select_ult_ano_cambio_tar = mysqli_query($connection, "SELECT MAX(ANO_FACTURA) AS ULT_ANO "
                                                                    . "   FROM archivos_cargados_novedades_2");
                $row_ult_ano_cambio_tar = mysqli_fetch_array($query_select_ult_ano_cambio_tar);
                $query_select_ult_mes_cambio_tar = mysqli_query($connection, "SELECT MAX(ID_MES_FACTURA) AS ULT_MES "
                                                                    . "   FROM archivos_cargados_novedades_2 "
                                                                    . "  WHERE ANO_FACTURA = " . $row_ult_ano_cambio_tar['ULT_ANO']);
                $row_ult_mes_cambio_tar = mysqli_fetch_array($query_select_ult_mes_cambio_tar);
                $query_select_mes_cambio_tarifa = mysqli_query($connection, "SELECT DISTINCT(MES_FACTURA) "
                                                                    . "  FROM archivos_cargados_novedades_2 "
                                                                    . " WHERE ANO_FACTURA = " . $row_ult_ano_cambio_tar['ULT_ANO'] . " "
                                                                    . "   AND ID_MES_FACTURA = " . $row_ult_mes_cambio_tar['ULT_MES']);
                $row_mes_cambio_tarifa = mysqli_fetch_array($query_select_mes_cambio_tarifa);
                $id_ano_cambio_tarifa = $row_ult_ano_cambio_tar['ULT_ANO'];
                $id_mes_cambio_tarifa = $row_ult_mes_cambio_tar['ULT_MES'];
                $mes_cambio_tarifa = strtolower($row_mes_cambio_tarifa['MES_FACTURA']);
                $bd_tabla_novedades = "novedades_" .  strtolower($row_mes_cambio_tarifa['MES_FACTURA']) . $row_ult_ano_cambio_tar['ULT_ANO'] . "_2";
            } else {
                $id_mes_cambio_tarifa = $_POST['id_mes_cambio_tarifa'];
                $mes_cambio_tarifa = strtolower($_POST['mes_cambio_tarifa']);
                $bd_tabla_novedades = "novedades_" . $mes_cambio_tarifa . $id_ano_cambio_tarifa . "_2";
            }
            echo "<input type='hidden' id='sw' name='sw' value='" . $sw . "' />";
            echo "<input type='hidden' id='id_ano_cambio_tarifa' name='id_ano_cambio_tarifa' value='" . $id_ano_cambio_tarifa . "' />";
            echo "<input type='hidden' id='id_mes_cambio_tarifa' name='id_mes_cambio_tarifa' value='" . $id_mes_cambio_tarifa . "' />";
            echo "<input type='hidden' id='mes_cambio_tarifa' name='mes_cambio_tarifa' value='" . $mes_cambio_tarifa . "' />";
            $query_select_info_novedades = mysqli_query($connection, " SELECT * "
                                                                   . "   FROM $bd_tabla_novedades "
                                                                   . "  WHERE COD_TARIFA_ACTUAL <> COD_TARIFA_ANTERIOR "
                                                                   . "  ORDER BY NIC");
            if (mysqli_num_rows($query_select_info_novedades)) {
                echo "<div id='resultado_cambio_tarifa' class='table-scroll'>";
                    echo "<table class='table table-condensed table-hover'>";
                        echo "<thead>";
                            echo "<tr>";
                                echo "<th width=10%>NIC</th>";
                                echo "<th width=8%>TAR. ACTUAL</th>";
                                echo "<th width=8%>TAR. ANTER.</th>";
                                echo "<th width=10%>FECHA CAMBIO</th>";
                                echo "<th width=24%>TIPO NOVEDAD</th>";
                                echo "<th width=20%>ESTADO ACTUAL</th>";
                                echo "<th width=20%>ESTADO ANTERIOR</th>";
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
                echo "<ul id='pagination-cambio_tarifa'></ul>";
                echo "<span id='loading-spinner' style='display: none; float: right;'><img src='Images/squares.gif' /></span>";
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
        function convertValorDeudaCorriente() {
            var deuda_corriente = $("#deuda_corriente").val();
            var replaceDeudaCorriente = deuda_corriente.replace(/,/g, '');
            var new_DeudaCorriente = replaceDeudaCorriente.toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,");
            $("#deuda_corriente").val(new_DeudaCorriente);
        }
        function convertValorDeudaCuota() {
            var deuda_cuota = $("#deuda_cuota").val();
            var replaceDeudaCuota = deuda_cuota.replace(/,/g, '');
            var new_DeudaCuota = replaceDeudaCuota.toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,");
            $("#deuda_cuota").val(new_DeudaCuota);
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
                    var bd_tabla_facturacion = $("#bd_tabla_facturacion").val();
                    var bd_tabla_recaudo = $("#bd_tabla_recaudo").val();
                    convertValorDeudaCorriente();
                    convertValorDeudaCuota();
                    $.ajax({
                        type: "POST",
                        url: "Modelo/Cargar_Paginacion_Resultados.php",
                        dataType: "json",
                        data: "nic="+nic+"&sw="+sw+
                              "&bd_tabla_facturacion="+bd_tabla_facturacion+"&bd_tabla_recaudo="+bd_tabla_recaudo,
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
                                        url: "Modelo/Cargar_Resultados.php",
                                        data: "nic="+nic+"&sw="+sw+"&page="+page+
                                              "&bd_tabla_facturacion="+bd_tabla_facturacion+"&bd_tabla_recaudo="+bd_tabla_recaudo,
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
                    $("#modalDetalleFactura").on('show.bs.modal', function(e) {
                        var detalle_id = e.relatedTarget.id;
                        $("#modal-title").html("");
                        $("#modal-body").html("<div style='margin-bottom: 30px; margin-top: 0px;'><span id='loading-spinner-detalle' style='display: block; float: left;'><img src='Images/squares.gif' /></span></div>");
                        $.ajax ({
                            type: "POST",
                            url: "Modelo/Cargar_Detalle_Factura_Recaudo.php",
                            dataType: "json",
                            data: "detalle_id="+detalle_id,
                            success: function(data) {
                                $("#modal-title").html(data[0]);
                                $("#modal-body").html(data[1]);
                            }
                        });
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
                        $("#ano_factura").val($("#ano_factura option:first").val());
                        $(".nav-pills a[href='#historial_periodo_tab']").tab("show");
                    });
                    $("#btn_filtrar_periodo_factura").click(function() {
                        var detalle_id = $("#id_detalle").val();
                        var periodo_factura = $("#periodo_factura").val();
                        if (periodo_factura.length == 0) {
                            $("#periodo_factura").focus();
                            return false;
                        }
                        $("#loading-spinner-historial-periodo").css('display', 'block');
                        $("#modal-title-hist").html("");
                        $("#resultado_nic_periodo").html("");
                        $.ajax ({
                            type: "POST",
                            url: "Modelo/Cargar_Historial_Factura_Recaudo.php",
                            dataType: "json",
                            data: "detalle_id="+detalle_id+"&periodo_factura="+periodo_factura+"&hist=periodo",
                            success: function(data) {
                                $("#loading-spinner-historial-periodo").css('display', 'none');
                                $("#modal-title-hist").html(data[0]);
                                $("#resultado_nic_periodo").html(data[1]);
                            }
                        });
                    });
                    $("#btn_filtrar_ano_factura").click(function() {
                        var detalle_id = $("#id_detalle").val();
                        var ano_factura = $("#ano_factura").val();
                        if (ano_factura.length == 0) {
                            $("#ano_factura").focus();
                            return false;
                        }
                        $("#loading-spinner-historial-ano").css('display', 'block');
                        $("#modal-title-hist").html("");
                        $("#resultado_nic_periodo").html("");
                        $.ajax ({
                            type: "POST",
                            url: "Modelo/Cargar_Historial_Factura_Recaudo.php",
                            dataType: "json",
                            data: "detalle_id="+detalle_id+"&ano_factura="+ano_factura+"&hist=ano",
                            success: function(data) {
                                $("#loading-spinner-historial-ano").css('display', 'none');
                                $("#modal-title-hist").html(data[0]);
                                $("#resultado_nic_periodo").html(data[1]);
                            }
                        });
                    });
                    $("#tab_historial_periodo").on("click", function() {
                        $("#modal-title-hist").html("");
                        $("#resultado_nic_periodo").html("");
                        $("#periodo_factura").val($("#periodo_factura option:first").val());
                    });
                    $("#tab_historial_anual").on("click", function() {
                        $("#modal-title-hist").html("");
                        $("#resultado_nic_periodo").html("");
                        $("#ano_factura").val($("#ano_factura option:first").val());
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
                case '3':
                    var tarifa = $("#tarifa").val();
                    var departamento = $("#departamento").val();
                    var municipio = $("#municipio").val();
                    var id_ano_factura = $("#id_ano_factura").val();
                    var id_mes_factura = $("#id_mes_factura").val();
                    var mes_factura = $("#mes_factura").val();
                    $.ajax({
                        type: "POST",
                        url: "Modelo/Cargar_Paginacion_Resultados.php",
                        dataType: "json",
                        data: "tarifa="+tarifa+"&departamento="+departamento+"&municipio="+municipio+"&sw="+sw+
                              "&id_ano_factura="+id_ano_factura+"&id_mes_factura="+id_mes_factura+
                              "&mes_factura="+mes_factura,
                        success: function(data) {
                            $("#pagination-tarifa").twbsPagination('destroy');
                            $("#pagination-tarifa").twbsPagination({
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
                                        data: "tarifa="+tarifa+"&departamento="+departamento+"&municipio="+municipio+"&sw="+sw+"&page="+page+
                                              "&id_ano_factura="+id_ano_factura+"&id_mes_factura="+id_mes_factura+
                                              "&mes_factura="+mes_factura,
                                        success: function(data) {
                                            $("#loading-spinner").css('display', 'none');
                                            $("#resultado_tarifa").html(data);
                                        }
                                    });
                                }
                            });
                        }
                    });
                    break;
                case '4':
                    var id_ano_cambio_tarifa = $("#id_ano_cambio_tarifa").val();
                    var id_mes_cambio_tarifa = $("#id_mes_cambio_tarifa").val();
                    var mes_cambio_tarifa = $("#mes_cambio_tarifa").val();
                    $.ajax({
                        type: "POST",
                        url: "Modelo/Cargar_Paginacion_Resultados.php",
                        dataType: "json",
                        data: "sw="+sw+
                              "&id_ano_cambio_tarifa="+id_ano_cambio_tarifa+
                              "&id_mes_cambio_tarifa="+id_mes_cambio_tarifa+
                              "&mes_cambio_tarifa="+mes_cambio_tarifa,
                        success: function(data) {
                            $("#pagination-cambio_tarifa").twbsPagination('destroy');
                            $("#pagination-cambio_tarifa").twbsPagination({
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
                                        data: "sw="+sw+"&page="+page+
                                              "&id_ano_cambio_tarifa="+id_ano_cambio_tarifa+
                                              "&id_mes_cambio_tarifa="+id_mes_cambio_tarifa+
                                              "&mes_cambio_tarifa="+mes_cambio_tarifa,
                                        success: function(data) {
                                            $("#loading-spinner").css('display', 'none');
                                            $("#resultado_cambio_tarifa").html(data);
                                        }
                                    });
                                }
                            });
                        }
                    });
                    break;
            }
            $('#crear_pdf').click(function(){
                $('#hidden_html').val($('#grafica_clientes_tab').html());
                $('#generar_pdf').submit();
            });
            $("#tab_grafica_cliente").on("click", function() {
                drawChartClientes();
            });
            $("#tab_grafica_facturado").on("click", function() {
                drawChartFactura();
            });
            $("#tab_grafica_recaudo").on("click", function() {
                drawChartRecaudo();
            });
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
                placement: "top"
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
                placement: "top"
            });
            $('input[type=text][name=corregimiento]').tooltip({
                container: "body",
                placement: "top"
            });
            $('input[type=text][name=periodo]').tooltip({
                container: "body",
                placement: "top"
            });
            $('input[type=text][name=deuda_corriente]').tooltip({
                container: "body",
                placement: "top"
            });
            $('input[type=text][name=deuda_cuota]').tooltip({
                container: "body",
                placement: "top"
            });
            $('select[name=periodo_factura]').tooltip({
                container : "body",
                placement : "top"
            });
            $('select[name=ano_factura]').tooltip({
                container : "body",
                placement : "top"
            });
        });
    </script>