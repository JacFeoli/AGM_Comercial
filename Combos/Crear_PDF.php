<?php
    include('../Combos/Pdf.php');
    if (isset($_POST["hidden_cliente_html"]) && $_POST["hidden_cliente_html"] != '') {
        $file_name = 'Total_Clientes_Tarifa.pdf';
        $html = '<link rel="stylesheet" href="bootstrap.min.css">';
        $html .= $_POST["hidden_cliente_html"];
        $pdf = new Pdf();
        $pdf->load_html($html);
        $pdf->render();
        $pdf->stream($file_name, array("Attachment" => false));
    }
?>