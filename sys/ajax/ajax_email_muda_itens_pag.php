<?php

if (!isset($_SESSION))
    session_start();

if (!empty($_POST['id']) && !empty($_POST['itens_pagina'])) {

    $id = Anti_Injection($_POST['id']);
    $itens_pag = Anti_Injection($_POST['itens_pagina']);

    $bd = new BD_FB_EMAIL();
    $bd->open();

    $sql = "UPDATE contas_baixar_email SET itens_pag = " . $itens_pag . " WHERE CODCONTASBAIXAREMAIL = " . $id;
    $query = ibase_query($sql);

    if ($query) {
        $_SESSION['itens_pag'] = $itens_pag;
        echo 'ok';
    } else {
        echo 'erro: ' . $sql . "\n" . ibase_errmsg();
    }

    $bd->close();
} else {
    echo 'erro: POST';
}