<?php

set_time_limit(120);

if (!isset($_SESSION))
    session_start();



include DIR_classes . 'MailSo/MailSo.php';

if (!empty($_POST['caixa']) && !empty($_POST['id']) && !empty($_POST['qtn']) && (!empty($_POST['offset']) || $_POST['offset'] == '0')) {
    $caixa = $_POST['caixa'];
    //$caixa = (mb_convert_encoding(utf8_decode($caixa), "UTF7-IMAP", "ISO_8859-1"));

    $id = Anti_Injection($_POST['id']);
    $qtn = intval(Anti_Injection($_POST['qtn']));
    $offset = intval(Anti_Injection($_POST['offset']));

    $bd = new BD_FB_EMAIL();
    $bd->open();


    $ac = "DE";

    if ($caixa == "INBOX.enviadas") {
        $ac = "PARA";
    }

    $sql = "SELECT FIRST " . $qtn . " skip " . $offset . " be.*, c.desccaixas, (SELECT first 1 ea.codemailanexo FROM emailanexo ea WHERE cid = 'N' AND ea.codbaixaremail = be.codbaixaremail) as anexo FROM baixar_email be 
        INNER JOIN caixas c ON (be.caixa = c.codcaixas AND c.hash = '" . md5($caixa) . "')
            WHERE be.codcontasbaixaremail = " . $id . " AND be.status <> 'E' ORDER BY be.uid desc, be.data_formatada DESC ";

    $query = ibase_query($sql);
    $grid = '';
    while ($reg = ibase_fetch_assoc($query, IBASE_TEXT)) {
        $grid .= monta_mensagem_off($reg, $ac);
    }

    echo 'ok|' . base64_encode($grid);

    $bd->close();
} else {
    echo 'erro|Erro POST';
}