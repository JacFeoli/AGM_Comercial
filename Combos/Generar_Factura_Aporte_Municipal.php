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
    }
    $query_select_info_fact_mun = mysqli_query($connection, "SELECT * "
                                                          . "  FROM facturacion_municipales_2 "
                                                          . " WHERE ID_FACTURACION = " . $_GET['id_fact_municipal']);
    $row_info_fact_mun = mysqli_fetch_array($query_select_info_fact_mun);
    $query_select_info_municipio = mysqli_query($connection, "SELECT * "
                                                           . "  FROM municipios_visitas_2 "
                                                           . " WHERE ID_DEPARTAMENTO = " . $row_info_fact_mun['ID_COD_DPTO'] . " "
                                                           . "   AND ID_MUNICIPIO = " . $row_info_fact_mun['ID_COD_MPIO']);
    $row_info_municipio = mysqli_fetch_array($query_select_info_municipio);
?>
<?php
    function numerosAletras($num, $fem = false, $dec = true) { 
        $matuni[2] = "dos"; 
        $matuni[3] = "tres"; 
        $matuni[4] = "cuatro"; 
        $matuni[5] = "cinco"; 
        $matuni[6] = "seis"; 
        $matuni[7] = "siete"; 
        $matuni[8] = "ocho"; 
        $matuni[9] = "nueve"; 
        $matuni[10] = "diez"; 
        $matuni[11] = "once"; 
        $matuni[12] = "doce"; 
        $matuni[13] = "trece"; 
        $matuni[14] = "catorce"; 
        $matuni[15] = "quince"; 
        $matuni[16] = "dieciseis"; 
        $matuni[17] = "diecisiete"; 
        $matuni[18] = "dieciocho"; 
        $matuni[19] = "diecinueve"; 
        $matuni[20] = "veinte"; 
        $matunisub[2] = "dos"; 
        $matunisub[3] = "tres"; 
        $matunisub[4] = "cuatro"; 
        $matunisub[5] = "quin"; 
        $matunisub[6] = "seis"; 
        $matunisub[7] = "sete"; 
        $matunisub[8] = "ocho"; 
        $matunisub[9] = "nove";
        
        $matdec[2] = "veint"; 
        $matdec[3] = "treinta"; 
        $matdec[4] = "cuarenta"; 
        $matdec[5] = "cincuenta"; 
        $matdec[6] = "sesenta"; 
        $matdec[7] = "setenta"; 
        $matdec[8] = "ochenta"; 
        $matdec[9] = "noventa"; 
        $matsub[3] = 'mill'; 
        $matsub[5] = 'bill'; 
        $matsub[7] = 'mill'; 
        $matsub[9] = 'trill'; 
        $matsub[11] = 'mill'; 
        $matsub[13] = 'bill'; 
        $matsub[15] = 'mill'; 
        $matmil[4] = 'millones'; 
        $matmil[6] = 'billones'; 
        $matmil[7] = 'de billones'; 
        $matmil[8] = 'millones de billones'; 
        $matmil[10] = 'trillones'; 
        $matmil[11] = 'de trillones'; 
        $matmil[12] = 'millones de trillones'; 
        $matmil[13] = 'de trillones'; 
        $matmil[14] = 'billones de trillones'; 
        $matmil[15] = 'de billones de trillones'; 
        $matmil[16] = 'millones de billones de trillones'; 
        
        //Zi hack
        $float=explode('.',$num);
        $num=$float[0];
        
        $num = trim((string)@$num); 
        if ($num[0] == '-') { 
        $neg = 'menos '; 
        $num = substr($num, 1); 
        }else 
        $neg = ''; 
        while ($num[0] == '0') $num = substr($num, 1); 
        if ($num[0] < '1' or $num[0] > 9) $num = '0' . $num; 
        $zeros = true; 
        $punt = false; 
        $ent = ''; 
        $fra = ''; 
        for ($c = 0; $c < strlen($num); $c++) { 
        $n = $num[$c]; 
        if (! (strpos(".,'''", $n) === false)) { 
        if ($punt) break; 
        else{ 
        $punt = true; 
        continue; 
        }
        
        }elseif (! (strpos('0123456789', $n) === false)) { 
        if ($punt) { 
        if ($n != '0') $zeros = false; 
        $fra .= $n; 
        }else
        
        $ent .= $n; 
        }else
        
        break;
        
        } 
        $ent = ' ' . $ent; 
        if ($dec and $fra and ! $zeros) { 
        $fin = ' coma'; 
        for ($n = 0; $n < strlen($fra); $n++) { 
        if (($s = $fra[$n]) == '0') 
        $fin .= ' cero'; 
        elseif ($s == '1') 
        $fin .= $fem ? ' una' : ' un'; 
        else 
        $fin .= ' ' . $matuni[$s]; 
        } 
        }else 
        $fin = ''; 
        if ((int)$ent === 0) return 'Cero ' . $fin; 
        $tex = ''; 
        $sub = 0; 
        $mils = 0; 
        $neutro = false; 
        while ( ($num = substr($ent, -3)) != ' ') { 
        $ent = substr($ent, 0, -3); 
        if (++$sub < 3 and $fem) { 
        $matuni[1] = 'una'; 
        $subcent = 'as'; 
        }else{ 
        $matuni[1] = $neutro ? 'un' : 'uno'; 
        $subcent = 'os'; 
        } 
        $t = ''; 
        $n2 = substr($num, 1); 
        if ($n2 == '00') { 
        }elseif ($n2 < 21) 
        $t = ' ' . $matuni[(int)$n2]; 
        elseif ($n2 < 30) { 
        $n3 = $num[2]; 
        if ($n3 != 0) $t = 'i' . $matuni[$n3]; 
        $n2 = $num[1]; 
        $t = ' ' . $matdec[$n2] . $t; 
        }else{ 
        $n3 = $num[2]; 
        if ($n3 != 0) $t = ' y ' . $matuni[$n3]; 
        $n2 = $num[1]; 
        $t = ' ' . $matdec[$n2] . $t; 
        } 
        $n = $num[0]; 
        if ($n == 1) { 
        $t = ' ciento' . $t; 
        }elseif ($n == 5){ 
        $t = ' ' . $matunisub[$n] . 'ient' . $subcent . $t; 
        }elseif ($n != 0){ 
        $t = ' ' . $matunisub[$n] . 'cient' . $subcent . $t; 
        } 
        if ($sub == 1) { 
        }elseif (! isset($matsub[$sub])) { 
        if ($num == 1) { 
        $t = ' mil'; 
        }elseif ($num > 1){ 
        $t .= ' mil'; 
        } 
        }elseif ($num == 1) { 
        $t .= ' ' . $matsub[$sub] . '?n'; 
        }elseif ($num > 1){ 
        $t .= ' ' . $matsub[$sub] . 'ones'; 
        } 
        if ($num == '000') $mils ++; 
        elseif ($mils != 0) { 
        if (isset($matmil[$sub])) $t .= ' ' . $matmil[$sub]; 
        $mils = 0; 
        } 
        $neutro = true; 
        $tex = $t . $tex; 
        } 
        $tex = $neg . substr($tex, 1) . $fin; 
        //Zi hack --> return ucfirst($tex);
        $end_num=ucfirst($tex).' Pesos M/CTE. *******';
        return $end_num; 
        }
    function valorEnLetras($x) {
        if ($x < 0) {
            $signo = "menos ";
        } else {
            $signo = "";
        }
        $x = abs ($x);
        $C1 = $x;
        $G6 = floor($x / (1000000)); //7 y mas.
        $E7 = floor($x / (100000));
        $G7 = $E7 - $G6 * 10; //6.
        $E8 = floor($x / 1000);
        $G8 = $E8 - $E7 * 100; //5 y 4.
        $E9 = floor($x / 100);
        $G9 = $E9 - $E8 * 10; //3.
        $E10 = floor($x);
        $G10 = $E10 - $E9 * 100; //2 y 1.
        $G11 = round(($x - $E10) * 100,0); //Decimales.
        $H6 = unidades($G6);
        if ($G7 == 1 && $G8 == 0) {
            $H7 = "Cien ";
        } else {
            $H7 = decenas($G7);
        }
        $H8 = unidades($G8);
        if ($G9 == 1 && $G10 == 0) {
            $H9 = "Cien ";
        } else {
            $H9 = decenas($G9);
        }
        $H10 = unidades($G10);
        if ($G11 < 10) {
            $H11 = "0" . $G11;
        } else {
            $H11 = $G11;
        }
        if($G6 == 0) {
            $I6 = " ";
        } elseif ($G6 == 1) {
            $I6 = "Millon ";
        } else {
            $I6 = "Millones ";
        }
        if ($G8 == 0 && $G7 == 0) {
            $I8 = " ";
        } else {
            $I8 = "Mil ";
        }
        $I10 = "Pesos ";
        $I11 = "M/CTE. ******* ";
        $C3 = $signo . $H6 . $I6 . $H7 . $H8 . $I8 . $H9 . $H10 . $I10 . $I11;
        return $C3; //Retornar el resultado
    }
    function unidades($u) {
        if ($u == 0)  {$ru = " ";}
            elseif ($u == 1)  {$ru = "Un ";}
            elseif ($u == 2)  {$ru = "Dos ";}
            elseif ($u == 3)  {$ru = "Tres ";}
            elseif ($u == 4)  {$ru = "Cuatro ";}
            elseif ($u == 5)  {$ru = "Cinco ";}
            elseif ($u == 6)  {$ru = "Seis ";}
            elseif ($u == 7)  {$ru = "Siete ";}
            elseif ($u == 8)  {$ru = "Ocho ";}
            elseif ($u == 9)  {$ru = "Nueve ";}
            elseif ($u == 10) {$ru = "Diez ";}

            elseif ($u == 11) {$ru = "Once ";}
            elseif ($u == 12) {$ru = "Doce ";}
            elseif ($u == 13) {$ru = "Trece ";}
            elseif ($u == 14) {$ru = "Catorce ";}
            elseif ($u == 15) {$ru = "Quince ";}
            elseif ($u == 16) {$ru = "Dieciseis ";}
            elseif ($u == 17) {$ru = "Decisiete ";}
            elseif ($u == 18) {$ru = "Dieciocho ";}
            elseif ($u == 19) {$ru = "Diecinueve ";}
            elseif ($u == 20) {$ru = "Veinte ";}

            elseif ($u == 21) {$ru = "Veintiun ";}
            elseif ($u == 22) {$ru = "Veintidos ";}
            elseif ($u == 23) {$ru = "Veintitres ";}
            elseif ($u == 24) {$ru = "Veinticuatro ";}
            elseif ($u == 25) {$ru = "Veinticinco ";}
            elseif ($u == 26) {$ru = "Veintiseis ";}
            elseif ($u == 27) {$ru = "Veintisiente ";}
            elseif ($u == 28) {$ru = "Veintiocho ";}
            elseif ($u == 29) {$ru = "Veintinueve ";}
            elseif ($u == 30) {$ru = "Treinta ";}

            elseif ($u == 31) {$ru = "Treinta y un ";}
            elseif ($u == 32) {$ru = "Treinta y dos ";}
            elseif ($u == 33) {$ru = "Treinta y tres ";}
            elseif ($u == 34) {$ru = "Treinta y cuatro ";}
            elseif ($u == 35) {$ru = "Treinta y cinco ";}
            elseif ($u == 36) {$ru = "Treinta y seis ";}
            elseif ($u == 37) {$ru = "Treinta y siete ";}
            elseif ($u == 38) {$ru = "Treinta y ocho ";}
            elseif ($u == 39) {$ru = "Treinta y nueve ";}
            elseif ($u == 40) {$ru = "Cuarenta ";}

            elseif ($u == 41) {$ru = "Cuarenta y un ";}
            elseif ($u == 42) {$ru = "Cuarenta y dos ";}
            elseif ($u == 43) {$ru = "Cuarenta y tres ";}
            elseif ($u == 44) {$ru = "Cuarenta y cuatro ";}
            elseif ($u == 45) {$ru = "Cuarenta y cinco ";}
            elseif ($u == 46) {$ru = "Cuarenta y seis ";}
            elseif ($u == 47) {$ru = "Cuarenta y siete ";}
            elseif ($u == 48) {$ru = "Cuarenta y ocho ";}
            elseif ($u == 49) {$ru = "Cuarenta y nueve ";}
            elseif ($u == 50) {$ru = "Cincuenta ";}

            elseif ($u == 51) {$ru = "Cincuenta y un ";}
            elseif ($u == 52) {$ru = "Cincuenta y dos ";}
            elseif ($u == 53) {$ru = "Cincuenta y tres ";}
            elseif ($u == 54) {$ru = "Cincuenta y cuatro ";}
            elseif ($u == 55) {$ru = "Cincuenta y cinco ";}
            elseif ($u == 56) {$ru = "Cincuenta y seis ";}
            elseif ($u == 57) {$ru = "Cincuenta y siete ";}
            elseif ($u == 58) {$ru = "Cincuenta y ocho ";}
            elseif ($u == 59) {$ru = "Cincuenta y nueve ";}
            elseif ($u == 60) {$ru = "Sesenta ";}

            elseif ($u == 61) {$ru = "Sesenta y un ";}
            elseif ($u == 62) {$ru = "Sesenta y dos ";}
            elseif ($u == 63) {$ru = "Sesenta y tres ";}
            elseif ($u == 64) {$ru = "Sesenta y cuatro ";}
            elseif ($u == 65) {$ru = "Sesenta y cinco ";}
            elseif ($u == 66) {$ru = "Sesenta y seis ";}
            elseif ($u == 67) {$ru = "Sesenta y siete ";}
            elseif ($u == 68) {$ru = "Sesenta y ocho ";}
            elseif ($u == 69) {$ru = "Sesenta y nueve ";}
            elseif ($u == 70) {$ru = "Setenta ";}

            elseif ($u == 71) {$ru = "Setenta y un ";}
            elseif ($u == 72) {$ru = "Setenta y dos ";}
            elseif ($u == 73) {$ru = "Setenta y tres ";}
            elseif ($u == 74) {$ru = "Setenta y cuatro ";}
            elseif ($u == 75) {$ru = "Setenta y cinco ";}
            elseif ($u == 76) {$ru = "Setenta y seis ";}
            elseif ($u == 77) {$ru = "Setenta y siete ";}
            elseif ($u == 78) {$ru = "Setenta y ocho ";}
            elseif ($u == 79) {$ru = "Setenta y nueve ";}
            elseif ($u == 80) {$ru = "Ochenta ";}

            elseif ($u == 81) {$ru = "Ochenta y un ";}
            elseif ($u == 82) {$ru = "Ochenta y dos ";}
            elseif ($u == 83) {$ru = "Ochenta y tres ";}
            elseif ($u == 84) {$ru = "Ochenta y cuatro ";}
            elseif ($u == 85) {$ru = "Ochenta y cinco ";}
            elseif ($u == 86) {$ru = "Ochenta y seis ";}
            elseif ($u == 87) {$ru = "Ochenta y siete ";}
            elseif ($u == 88) {$ru = "Ochenta y ocho ";}
            elseif ($u == 89) {$ru = "Ochenta y nueve ";}
            elseif ($u == 90) {$ru = "Noventa ";}

            elseif ($u == 91) {$ru = "Noventa y un ";}
            elseif ($u == 92) {$ru = "Noventa y dos ";}
            elseif ($u == 93) {$ru = "Noventa y tres ";}
            elseif ($u == 94) {$ru = "Noventa y cuatro ";}
            elseif ($u == 95) {$ru = "Noventa y cinco ";}
            elseif ($u == 96) {$ru = "Noventa y seis ";}
            elseif ($u == 97) {$ru = "Noventa y siete ";}
            elseif ($u == 98) {$ru = "Noventa y ocho ";}
        else            {$ru = "Noventaynueve ";}
        return $ru; //Retornar el resultado
    }
    function decenas($d) {
        if ($d == 0)  {$rd = "";}
            elseif ($d == 1)  {$rd = "Ciento ";}
            elseif ($d == 2)  {$rd = "Doscientos ";}
            elseif ($d == 3)  {$rd = "Trescientos ";}
            elseif ($d == 4)  {$rd = "Cuatrocientos ";}
            elseif ($d == 5)  {$rd = "Quinientos ";}
            elseif ($d == 6)  {$rd = "Seiscientos ";}
            elseif ($d == 7)  {$rd = "Setecientos ";}
            elseif ($d == 8)  {$rd = "Ochocientos ";}
        else            {$rd = "Novecientos ";}
        return $rd; //Retornar el resultado
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <title>AGM - Factura Aportes Municipales:  <?php echo $row_info_municipio['NOMBRE']; ?></title>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
        <link rel="icon" href="../Images/agm_desarrollos_2_icon" type="image/x-icon" />
    </head>
    <style>
        table {
            font-family: 'Cabin';
        }
        th {
            font-size: 12px;
            border: 1px solid #000000;
        }
        td {
            font-size: 11px;
            border: 1px solid #000000;
        }
        @media print {
            @page { margin: 0; }
            body { margin: 1.6cm; }
        }
        @font-face {
            font-family: 'Cabin';
            src: url('../Fonts/Cabin.ttf');
        }
    </style>
    <body onload="window.print()">
        <?php
            /*$query_select_alcaldia = mysqli_query($connection, "SELECT * FROM alcaldias_2 "
                                                             . " WHERE ID_COD_DPTO = " . $row_info_fact_mun['ID_COD_DPTO'] . " "
                                                             . "   AND ID_COD_MPIO = " . $row_info_fact_mun['ID_COD_MPIO'] . " "
                                                             . "   AND VALOR_CONCEPTO = " . $row_info_fact_mun['VALOR_FACTURA']);*/
            $query_select_alcaldia = mysqli_query($connection, "SELECT * FROM alcaldias_2 "
                                                             . " WHERE ID_COD_DPTO = " . $row_info_fact_mun['ID_COD_DPTO'] . " "
                                                             . "   AND ID_COD_MPIO = " . $row_info_fact_mun['ID_COD_MPIO'] . " ");
            $row_alcaldia = mysqli_fetch_array($query_select_alcaldia);
        ?>
        <table style="border: 0px;" width="100%">
            <tr>
                <td style="width: 30%; border-spacing: 0px; text-align: center; border: 0px;"><img style="max-width: 60%;" src="../Images/AGM Desarrollos.jpg" /></td>
                <td style="border: 0px; width: 40%;">
                    <table width="100%">
                        <tr>
                            <td style="font-size: 17px; font-weight: bold; border: 0px;"><center>AGM DESARROLLOS S.A.S.</center></td>
                        </tr>
                        <tr>
                            <td style="font-size: 13px; font-weight: bold; border: 0px;"><center>NIT: 800186313-0</center></td>
                        </tr>
                        <tr>
                            <td style="font-size: 13px; border: 0px;"><center>CENTRO, SECTOR MATUNA, AV. DANIEL LEMAITRE, EDF. BANCO DEL ESTADO PISO 5</center></td>
                        </tr>
                        <tr>
                            <td style="font-size: 13px; border: 0px;"><center>CARTAGENA - COLOMBIA</center></td>
                        </tr>
                        <!--<tr>
                            <td style="font-size: 13px; font-weight: bold; border: 0px;"><center>Tel: 6686995 Fax:</center></td>
                        </tr>
                        <tr>
                            <td style="font-size: 13px; font-weight: bold; border: 0px;"><center>contabilidad@agmdesarrollos.com</center></td>
                        </tr>-->
                    </table>
                </td>
                <td style="width: 30%; border-bottom-left-radius: 10px; border-bottom-right-radius: 10px; border-spacing: 0px; padding: 0px; border-top: 0px;">
                    <table style="border-collapse: collapse;" width="100%">
                        <tr>
                            <td style="font-size: 17px; font-weight: bold; border: 0px; background-color: #DCDCDC;"><center>CUENTA DE COBRO</center></td>
                        </tr>
                        <tr>
                            <td style="font-size: 17px; font-weight: bold; border: 0px; color: red; font-style: italic;"><center><?php echo $row_info_fact_mun['CONSECUTIVO_FACT']; ?></center></td>
                        </tr>
                        <!--<tr>
                            <td style="font-size: 14px; font-weight: bold; border: 0px;"><center>Resolución de Facturación</center></td>
                        </tr>
                        <tr>
                            <td style="font-size: 14px; border: 0px;"><center>18763005534778 de 22/04/2020</center></td>
                        </tr>
                        <tr>
                            <td style="font-size: 14px; border: 0px;"><center>Rango habilitado desde 1001 hasta 5000</center></td>
                        </tr>-->
                    </table>
                </td>
            </tr>
            <!--<tr>
                <td style="font-size: 13px; font-weight: bold; border: 0px;" colspan="2">Régimen Común - Somos grandes contribuyentes Resol. 012635 del 14 de Diciembre de 2018</td>
            </tr>-->
            <tr>
                <td style="width: 60%; border-bottom-left-radius: 10px; border-bottom-right-radius: 10px; border-top-left-radius: 10px; border-top-right-radius: 10px;" colspan="2">
                    <table width="100%">
                        <tr>
                            <td style="font-size: 17px; font-weight: bold; border: 0px;">Cliente: </td>
                            <td style="font-size: 17px; font-weight: bold; border: 0px;">MUNICIPIO DE <?php echo $row_info_municipio['NOMBRE']; ?></td>
                        </tr>
                        <tr>
                            <td style="font-size: 14px; font-weight: bold; border: 0px;">NIT: </td>
                            <td style="font-size: 14px; border: 0px;"><?php echo $row_alcaldia['NIT_ALCALDIA']; ?></td>
                        </tr>
                        <tr>
                            <td style="font-size: 14px; font-weight: bold; border: 0px;">Dirección: </td>
                            <td style="font-size: 14px; border: 0px;"><?php echo $row_alcaldia['DIRECCION_ALCALDIA']; ?></td>
                        </tr>
                        <tr>
                            <td style="font-size: 14px; font-weight: bold; border: 0px;">Ciudad: </td>
                            <td style="font-size: 14px; border: 0px;"><?php echo $row_info_municipio['NOMBRE']; ?></td>
                        </tr>
                        <tr>
                            <td style="font-size: 14px; font-weight: bold; border: 0px;">Teléfono: </td>
                            <td style="font-size: 14px; border: 0px;"><?php echo $row_alcaldia['TELEFONO_ALCALDIA']; ?></td>
                        </tr>
                    </table>
                </td>
                <td style="width: 40%; border-bottom-left-radius: 10px; border-bottom-right-radius: 10px; border-top-left-radius: 10px; border-top-right-radius: 10px;">
                    <table width="100%">
                        <tr>
                            <td style="font-size: 14px; font-weight: bold; border: 0px;">Fecha: </td>
                            <td style="font-size: 13px; border: 0px;"><?php echo $row_info_fact_mun['FECHA_FACTURA'] ?></td>
                        </tr>
                        <tr>
                            <td style="font-size: 14px; font-weight: bold; border: 0px;">Forma de Pago: </td>
                            <td style="font-size: 13px; border: 0px;">MENSUAL</td>
                        </tr>
                        <tr>
                            <td style="font-size: 14px; font-weight: bold; border: 0px;">Fecha de Vcto: </td>
                            <td style="font-size: 13px; border: 0px;"><?php echo $row_info_fact_mun['FECHA_VENCIMIENTO'] ?></td>
                        </tr>
                        <!--<tr>
                            <td style="font-size: 14px; font-weight: bold; border: 0px;">Acta/Orden: </td>
                        </tr>-->
                    </table>
                </td>
            </tr>
        </table>
        <br />
        <table style="border: 1px solid; text-align: center; border-collapse: collapse;" width="100%">
            <tr style="border: 1px solid;">
                <th style="border: 1px solid; background-color: #DCDCDC; width: 10%; font-size: 15px;"><b><center>Ítem:</center></b></th>
                <th style="border: 1px solid; background-color: #DCDCDC; width: 70%; font-size: 15px;"><b><center>Descripción</center></b></th>
                <th style="border: 1px solid; background-color: #DCDCDC; width: 20%; font-size: 15px;"><b><center>Valor Bruto</center></b></th>
            </tr>
            <tr>
                <td style="border: 1px solid; border-bottom: 0px; padding: 10px;">&nbsp;</td>
                <td style="border: 1px solid; border-bottom: 0px; font-size: 14px; text-align: left; padding: 10px; padding-top: 0px;">
                    <?php
                        $ano_factura = substr($row_info_fact_mun['PERIODO_FACTURA'], 0, 4);
                        switch (substr($row_info_fact_mun['PERIODO_FACTURA'], 4, 2)) {
                            case '01':
                                $mes_factura = "ENERO";
                                break;
                            case '02':
                                $mes_factura = "FEBRERO";
                                break;
                            case '03':
                                $mes_factura = "MARZO";
                                break;
                            case '04':
                                $mes_factura = "ABRIL";
                                break;
                            case '05':
                                $mes_factura = "MAYO";
                                break;
                            case '06':
                                $mes_factura = "JUNIO";
                                break;
                            case '07':
                                $mes_factura = "JULIO";
                                break;
                            case '08':
                                $mes_factura = "AGOSTO";
                                break;
                            case '09':
                                $mes_factura = "SEPTIEMBRE";
                                break;
                            case '10':
                                $mes_factura = "OCTUBRE";
                                break;
                            case '11':
                                $mes_factura = "NOVIEMBRE";
                                break;
                            case '12':
                                $mes_factura = "DICIEMBRE";
                                break;
                        }
                        echo $row_alcaldia['CONCEPTO_APORTE'] . " CORRESPONDIENTE AL MES DE " . $mes_factura . " DEL AÑO " . $ano_factura . ".";
                    ?>
                </td>
                <td style="border: 0px; border-bottom: 0px; padding: 10px;">&nbsp;</td>
            </tr>
            <tr>
                <td style="border: 1px solid; border-bottom: 0px; border-top: 0px; font-size: 14px; text-align: right; padding: 10px;">1</td>
                <td style="border: 1px solid; border-bottom: 0px; border-top: 0px; font-size: 14px; text-align: left; padding: 10px;">
                    APORTE AL MUNICIPIO
                </td>
                <td style="border: 1px solid; border-bottom: 0px; border-top: 0px; font-size: 14px; text-align: right;">
                    <b style='font-size: 14px;'>$ </b><?php echo number_format($row_info_fact_mun['VALOR_FACTURA'],0, ',', '.'); ?>
                </td>
            </tr>
            <tr>
                <td style="border: 1px solid; border-bottom: 0px; border-top: 0px;">&nbsp;</td>
                <td style="border: 1px solid; border-bottom: 0px; border-top: 0px; font-size: 14px; text-align: left; padding-left: 10px;">
                    <b>Total Valor Aporte</b>
                </td>
                <td style="border: 1px solid; border-bottom: 0px; border-top: 0px; font-size: 14px; text-align: right;">
                    <b style='font-size: 14px;'>$ </b><span style="font-size: 14px; font-weight: bold;"><?php echo number_format($row_info_fact_mun['VALOR_FACTURA'],0, ',', '.'); ?></span>
                </td>
            </tr>
            <!--<tr>
                <td style="border: 1px solid; border-bottom: 0px; border-top: 0px;">&nbsp;</td>
                <td style="border: 1px solid; border-bottom: 0px; border-top: 0px; font-size: 14px; text-align: left; padding-left: 10px;">
                    <b>Valor Impuesto</b>
                </td>
                <td style="border: 1px solid; border-bottom: 0px; border-top: 0px; font-size: 14px; text-align: right;">
                    <b style='font-size: 14px;'>$ </b><span style="font-size: 14px;">0</span>
                </td>
            </tr>
            <tr>
                <td style="border: 1px solid; border-bottom: 0px; border-top: 0px;">&nbsp;</td>
                <td style="border: 1px solid; border-bottom: 0px; border-top: 0px; font-size: 14px; text-align: left; padding-left: 10px;">
                    <b>Valor Retención</b>
                </td>
                <td style="border: 1px solid; border-bottom: 0px; border-top: 0px; font-size: 14px; text-align: right;">
                    <b style='font-size: 14px;'>$ </b><span style="font-size: 15px;">0</span>
                </td>
            </tr>
            <tr>
                <td style="border: 1px solid; border-bottom: 0px; border-top: 0px;">&nbsp;</td>
                <td style="border: 1px solid; border-bottom: 0px; border-top: 0px; font-size: 14px; text-align: left; padding-left: 10px;">
                    <b>Total Neto Factura</b>
                </td>
                <td style="border: 1px solid; border-bottom: 0px; border-top: 0px; font-size: 14px; text-align: right;">
                    <b style='font-size: 14px;'>$ </b><span style="font-size: 14px; font-weight: bold;"><?php echo number_format($row_info_fact_mun['VALOR_FACTURA'],0, ',', '.'); ?></span>
                </td>
            </tr>-->
            <tr>
                <td style="border-bottom: 0px; text-align: left; padding: 10px;" colspan="3">
                    <b style="font-size: 14px;">VALOR EN LETRAS</b>
                    <br />
                    <p style="font-size: 14px; margin-bottom: 0px;"><?php echo strtoupper(valorEnLetras($row_info_fact_mun['VALOR_FACTURA'])); ?></p>
                </td>
            </tr>
            <tr>
                <td style="border-bottom: 0px; text-align: left; padding: 10px;" colspan="3">
                    <?php
                        if ($row_alcaldia['VALOR_CARTERA'] > 0) {
                            $color = " color: red;";
                        } else {
                            $color = "";
                        }
                    ?>
                    <b style="font-size: 14px;">CUENTAS COBRO VENCIDAS: </b><span style="font-size: 14px; font-weight: bold; <?php echo $color; ?>"><?php echo number_format($row_info_fact_mun['NO_CC_VENCIDAS'],0, ',', '.'); ?></span>
                    <br />
                    <br />
                    <?php
                        $today = date('Y-m-d');
                        if ($row_info_fact_mun['FECHA_VENCIMIENTO'] > $today) { ?>
                            <b style="font-size: 14px;">CARTERA CORRIENTE: </b><b style='font-size: 14px; <?php echo $color; ?>'>$ </b><span style="font-size: 14px; font-weight: bold; <?php echo $color; ?>"><?php echo number_format($row_info_fact_mun['VALOR_FACTURA'],0, ',', '.'); ?></span>
                            <br />
                            <br />
                            <b style="font-size: 14px;">CARTERA VENCIDA: </b><b style='font-size: 14px; <?php echo $color; ?>'>$ </b><span style="font-size: 14px; font-weight: bold; <?php echo $color; ?>"><?php echo number_format($row_alcaldia['VALOR_CARTERA'] - $row_info_fact_mun['VALOR_FACTURA'],0, ',', '.'); ?></span>
                        <?php
                        } else { ?>
                            <b style="font-size: 14px;">CARTERA CORRIENTE: </b><b style='font-size: 14px; <?php echo $color; ?>'>$ </b><span style="font-size: 14px; font-weight: bold; <?php echo $color; ?>"><?php echo number_format(0,0, ',', '.'); ?></span>
                            <br />
                            <br />
                            <b style="font-size: 14px;">CARTERA VENCIDA: </b><b style='font-size: 14px; <?php echo $color; ?>'>$ </b><span style="font-size: 14px; font-weight: bold; <?php echo $color; ?>"><?php echo number_format($row_alcaldia['VALOR_CARTERA'],0, ',', '.'); ?></span>
                        <?php
                        }
                    ?>
                </td>
            </tr>
        </table>
        <table width='100%'>
            <tr>
                <td style="width: 60%; border-bottom-left-radius: 10px; border-bottom-right-radius: 10px; border-top-left-radius: 10px; border-top-right-radius: 10px;" colspan="2">
                    <table width="100%">
                        <tr>
                            <td style="font-size: 14px; font-weight: bold; border: 0px;">Observaciones </td>
                        </tr>
                        <tr>
                            <td style="font-size: 14px; font-weight: bold; border: 0px;">
                                <center>
                                    <?php
                                        switch ($row_info_municipio['NOMBRE']) {
                                            case 'MOMPOS':
                                            case 'ALTOS DEL ROSARIO':
                                            case 'BARRANCO DE LOBA':
                                            case 'CICUCO':
                                            case 'CLEMENCIA':
                                            case 'NUEVA GRANADA':
                                            case 'SAN ESTANISLAO':
                                            case 'SAN JACINTO':
                                            case 'SAN JUAN DEL CESAR':
                                            case 'SAN MARTIN DE LOBA':
                                            case 'SAN PEDRO':
                                            case 'TALAIGUA NUEVO':
                                            case 'URUMITA':
                                            case 'VILLANUEVA':
                                            case 'EL CARMEN DE BOLIVAR':
                                            case 'HATILLO DE LOBA':
                                            case 'SAN JUAN NEPOMUCENO':
                                            case 'TURBANA':
                                                echo "FAVOR CONSIGNAR EN LA CUENTA DE AHORROS No. " . $row_alcaldia['CUENTA_DESTINO'] . " DE " . $row_alcaldia['BANCO_DESTINO'] . " "
                                                    . "A NOMBRE DE FONDO ABIERTO ACCIÓN UNO NIT: 800.193.848-8";
                                                break;
                                            case 'SANTA ROSA':
                                                echo "FAVOR CONSIGNAR EN LA CUENTA DE AHORROS No. " . $row_alcaldia['CUENTA_DESTINO'] . " DE " . $row_alcaldia['BANCO_DESTINO'];
                                                break;
                                            case 'MARGARITA':
                                                echo "FAVOR CONSIGNAR EN LA CUENTA DE AHORROS No. " . $row_alcaldia['CUENTA_DESTINO'] . " DE " . $row_alcaldia['BANCO_DESTINO'] . " "
                                                    . "A NOMBRE DE PATRIMONIOS AUTONOMOS ACCIÓN FIDUCIARIA FA 4845 ALUMBRADO PÚBLICO MARGARITA NIT: 805.012.921-0";
                                                break;
                                        }
                                    ?>
                                </center>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        <br />
        <table style="border: 1px solid; text-align: center; border-collapse: collapse;" width="100%">
            <tr>
                <td style="font-size: 13px; border: 1px solid; text-align: justify;">
                    El concepto cobrado en este documento corresponde a la obligación pactada, con ocasión al Contrato de
                    Concesión celebrado entre la entidad territorial y la empresa que emite la presente cuenta de cobro,
                    correspondiente al aporte mensual que debe realizar el Municipio para la financiación de la prestación
                    del servicio de Alumbrado Público.
                </td>
                <!--<td style="font-size: 13px; border: 1px solid; text-align: justify;">
                    ESTA FACTURA SE ASIMILA A LA LETRA DE CAMBIO SEGUN ARTICULO 621, 773, 774 DEL CODIGO DE COMERCIO,
                    EL PAGO DEL PRESENTE DOCUMENTO ES EXIGIBLE EN NUESTROS LOCALES A LA FECHA DE SU VENCIMIENTO Y
                    PRESTA MERITO EJECUTIVO. La presente factura es un titulo valor en cuanto cumple con los requisitos exigidos por la ley
                    1231 de 2008 y causara intereses de mora vigentes a la fecha de su expedicion. Manifestamos que hemos recibido esta
                    factura en la forma y terminos de la ley antes mencionada. Si dentro de los (10 ) dias calendario siguientes de recibida la
                    factura no hemos sido notificado por escrito de alguna objecion se considerara IRREVOCABLEMENTE ACEPTADA.
                </td>-->
            </tr>
            <!--<tr>
                <td style="font-size: 13px; border: 1px solid; text-align: center;">
                    FACTURA IMPRESA POR COMPUTADOR POR AGM DESARROLLOS SAS 800.186.313-0
                </td>
            </tr>
            <tr>
                <td style="font-size: 13px; border: 1px solid; text-align: center; font-weight: bold;">
                    SE HACE CONSTAR QUE LA FIRMA DE UNA PERSONA DISTINTA AL COMPRADOR IMPLICA QUE DICHA PERSONA
                    ESTA AUTORIZADA EXPRESAMENTE POR EL COMPRADOR PARA FIRMAR, CONFESAR LA DEUDA Y OBLIGARLO
                </td>
            </tr>-->
        </table>
        <br />
        <br />
        <table style="text-align: center; border-collapse: collapse;" width="100%">
            <tr>
                <?php
                    $query_select_usuario = mysqli_query($connection, "SELECT NOMBRE FROM usuarios_2 WHERE ID_USUARIO = " . $row_info_fact_mun['ID_USUARIO']);
                    $row_usuario = mysqli_fetch_array($query_select_usuario);
                ?>
                <td style="width: 30%; border: 0px; font-size: 15px; border-bottom: 1px solid;"><img alt="" src="../Images/Firma Digital Vacia.png" /><?php echo $row_usuario['NOMBRE']; ?></td>
                <td style="width: 3%; border: 0px;">&nbsp;</td>
                <?php
                    $query_select_usuario_revisado = mysqli_query($connection, "SELECT NOMBRE FROM usuarios_2 WHERE ID_USUARIO = " . $row_info_fact_mun['ID_USUARIO_REVISADO']);
                    $row_usuario_revisado = mysqli_fetch_array($query_select_usuario_revisado);
                ?>
                <td style="width: 30%; border: 0px; font-size: 15px; border-bottom: 1px solid;"><img alt="" src="../Images/Firma Digital Vacia.png" /><?php echo $row_usuario_revisado['NOMBRE']; ?></td>
                <td style="width: 3%; border: 0px;">&nbsp;</td>
                <td style="width: 30%; border: 0px; font-size: 15px; border-bottom: 1px solid; position: relative;"><img alt="" src="../Images/Firma Digital 2.png" /> HAROLDO RIVERO</td>
            </tr>
            <tr>
                <td style="width: 30%; border: 0px; font-size: 15px;">Elaborado</td>
                <td style="width: 3%; border: 0px;">&nbsp;</td>
                <td style="width: 30%; border: 0px; font-size: 15px;">Revisado</td>
                <td style="width: 3%; border: 0px;">&nbsp;</td>
                <td style="width: 30%; border: 0px; font-size: 15px;">Aprobación de Gerencia</td>
            </tr>
            <tr><td style="border: 0px; font-size: 15px;">&nbsp;</td></tr>
            <tr>
                <td style="border: 0px; font-size: 14px;" colspan="5">ORIGINAL</td>
            </tr>
        </table>
    </body>
</html>
<?php