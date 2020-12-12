<?php

if (!isset($_SESSION))
    session_start();


if (!empty($_POST['conta'])) {

    $conta = Anti_Injection($_POST['conta']);
    $sessao = Anti_Injection($_POST['sessao']);

    $sessao_ = $_SESSION['cod_sessao'];


    $bd = new BD_FB_EMAIL();
    $bd->open();

    $sql = "SELECT count(codsessao) as total, codsessao FROM sessao WHERE codcontasbaixaremail = " . $conta . " GROUP BY codsessao";
    $query = ibase_query($sql);
    if ($query) {
        if ($reg = ibase_fetch_assoc($query)) {
            if (!empty($reg['CODSESSAO'])) {
                if ($reg['TOTAL'] == '1') {
                    if ($reg['CODSESSAO'] == $sessao && $sessao == $sessao_) {
                        unset($_SESSION['cod_sessao_tranporte']);
                        echo 'ok';
                    } else {
                        echo '-1';
                    }
                } else {
                    echo '-2';
                }
            } else {
                echo '-3';
            }
        } else {
            echo '-4';
        }
    } else {
        echo '-5';
    }

    $bd->close();
} else {
    echo '-6';
}
    