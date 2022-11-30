<?php
    session_start();
?>
<?php
    switch ($_POST['sw']) {
        case 4: ?>
            <h1>Resultado de la Busqueda por Periodo</h1>
        <?php
            break;
        case 5: ?>
            <h1>Resultado de la Busqueda por Rango de Fechas</h1>
        <?php
            break;
    }
?>
<div style="display: none;" class="alert alert-success alert-dismissible text-center" role="alert" id="alert-consulta">
    Resultado Generado Satisfactoriamente. <i class="fas fa-check" aria-hidden="true"></i>
</div>
<h2></h2>
<?php
    require_once('../Includes/Config.php');
    $sw = $_POST['sw'];
    switch ($sw) {
        case '4':
            $id_ano_mensual = $_POST['id_ano_mensual'];
            $id_mes_mensual = $_POST['id_mes_mensual'];
            $periodo_mensual = strtolower($_POST['periodo_mensual']);
            echo "<p style='color: #003153; font-weight: bold; margin-bottom: 0px;'>PERIODO " . $id_ano_mensual . $id_mes_mensual . "&nbsp; <a onClick='generarExcelMensual(" . $sw . ", " . $id_ano_mensual . ", " . $id_mes_mensual . ")'><button><img src='Images/excel_2.png' title='Excel' width='16' height='16' /></button></a></p>";
            break;
        case '5':
            $fecha_inicio = $_POST['fecha_inicio'];
            $fecha_fin = $_POST['fecha_fin'];
            echo "<p style='color: #003153; font-weight: bold; margin-bottom: 0px;'>RANGO " . $fecha_inicio . " & " . $fecha_fin . "&nbsp; <a onClick='generarExcelRango(" . $sw . ", \"" . $fecha_inicio . "\", \"" . $fecha_fin . "\")'><button><img src='Images/excel_2.png' title='Excel' width='16' height='16' /></button></a></p>";
            break;
    }
?>
<script>
    //REPORTES EXCEL
    function generarExcelMensual(sw, id_ano, id_mes) {
        window.location.href = 'Combos/Generar_Excel_Informe_Reca.php?sw='+sw+'&id_ano='+id_ano+'&id_mes='+id_mes;
    }
    function generarExcelRango(sw, fecha_inicio, fecha_fin) {
        window.location.href = 'Combos/Generar_Excel_Informe_Reca.php?sw='+sw+'&fecha_inicio='+fecha_inicio+'&fecha_fin='+fecha_fin;
    }
    //END REPORTES EXCEL
</script>
<script>
    $(document).ready(function() {
        $("#alert-consulta").attr("display", "block");
        $("#alert-consulta").fadeTo(3000, 500).fadeOut(500, function() {
            $("#alert-consulta").fadeOut();
        });
    });
</script>