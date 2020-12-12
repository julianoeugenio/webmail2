<?php

set_time_limit(120);



include DIR_classes . 'MailSo/MailSo.php';

if (!empty($_POST['id']) && !empty($_POST['caixa']) && !empty($_POST['uid'])) {
    $id = Anti_Injection($_POST['id']);
    $caixa = str_replace('___', '*', $_POST['caixa']);
    $uid = intval(Anti_Injection($_POST['uid']));

    $caixa_cod = mb_convert_encoding(utf8_decode(Utf8($caixa)), "UTF7-IMAP", "ISO_8859-1");

    $bd = new BD_FB_EMAIL();
    $bd->open();

    $conta = getEmailSenha($id);
    $caixas_hash = carrega_caixas_hash($id);



    $email = $conta['EMAIL'];
    $senha = base64_decode($conta['SENHA']);
    $servidor = explode('@', $email);
    $servidor = 'pop.' . $servidor[1];

    $oData = null;

    if (!empty($email) && !empty($senha) && !empty($servidor)) {
        try {
            $oMailClient = \MailSo\Mail\MailClient::NewInstance();
            $conn = $oMailClient
                    ->Connect($servidor, 143, \MailSo\Net\Enumerations\ConnectionSecurityType::NONE)
                    ->Login($email, $senha);

            $aux = array();
            $aux[0] = $uid;

            $res = $conn->MessageDelete($caixa_cod, $aux, $aux[0]);

            if (is_object($res) || is_array($res)) {

                $sql_update = "UPDATE baixar_email SET status = 'E' WHERE codcontasbaixaremail = " . $id . " AND caixa = " . $caixas_hash[md5($caixa_cod)] . " AND uid = " . $uid . " AND status = 'L'";
                $query_update = ibase_query($sql_update);
                if (!$query_update) {
                    echo 'erro: SQL UPDATE';
                } else {
                    echo 'ok';
                }
            } else {
                echo 'erro|';
                print_r($res);
            }
        } catch (Exception $e) {
            echo 'erro|';
            var_dump($e);
        }
    } else {
        echo 'erro|Dados';
    }
    $bd->close();
} else {
    echo 'erro|POST';
}