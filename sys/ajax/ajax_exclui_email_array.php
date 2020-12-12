<?php

set_time_limit(120);



include DIR_classes . 'MailSo/MailSo.php';

//$cod_usuario = Siga_Usuario_Show_Cod_Usuario();

if (!empty($_POST['id']) && !empty($_POST['caixa']) && !empty($_POST['array_uid']) /*&& !empty($cod_usuario)*/) {
    $id = Anti_Injection($_POST['id']);
    $caixa = $_POST['caixa'];
    $array_uid = $_POST['array_uid'];

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

            $tr = ibase_trans();
            $erros = 0;

            for ($i = 0; $i < count($array_uid); $i++) {

                $uid = intval($array_uid[$i]);
                $aux = array();
                $aux[0] = $uid;



                $sql_update = "UPDATE baixar_email SET status = 'E', excluidopor = null,
                               excluidoem = '" . Data_Hora_Firebird() . "' WHERE codcontasbaixaremail = " . $id . " AND caixa = " . $caixas_hash[md5($caixa_cod)] . " AND uid = " . $uid . " AND status = 'L'";
                $query_update = ibase_query($sql_update);
                if (!$query_update) {
                    echo 'erro: SQL UPDATE';
                    $erros++;
                    break;
                } else {
                    $res = $conn->MessageDelete($caixa_cod, $aux, $aux[0]);

                    if (is_object($res) || is_array($res)) {
                        ibase_commit($tr);
                    } else {
                        ibase_rollback($tr);
                        echo 'erro|';
                        print_r($res);
                        $erros++;
                        break;
                    }
                }
            }

            if ($erros == 0) {
                echo 'ok';
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