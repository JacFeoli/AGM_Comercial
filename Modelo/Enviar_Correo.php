<?php
    session_start();
    require_once('../Includes/Config.php');
    $id_tabla_correo = $_POST['id_tabla_correo'];
    $email_usuario_remitente = $_POST['email_usuario_remitente'];
    $email_usuario_destinatario = $_POST['email_usuario_destinatario'];
    //$to = substr($id_usuario_destinatario, 0, -1);
    $to = "jcfeoli5@gmail.com";
    //$email_from = $email_usuario_remitente;
    $email_from = "jcastillo@agmdesarrollos.com";
    $nombre = $_POST['nombre'];
    $query_select_observaciones = mysqli_query($connection, "SELECT * FROM bitacora_libretas_2 WHERE ID_TABLA = '" . $id_tabla_correo . "'");
    $row_observaciones = mysqli_fetch_array($query_select_observaciones);
    $message = $_POST['mensaje'] . " \n" . "Observaciones:\n " . $row_observaciones['OBSERVACIONES'];
    $email_subject = $_POST['asunto_correo'];
    $email_body = "Has recibido un nuevo mensaje de $email_usuario_remitente. \n" . "Este es el Mensaje:\n $message";
    $headers = "From:" . $email_from;
    mail($to, $email_subject, $email_body, $headers);
?>