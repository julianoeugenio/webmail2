<?php

set_time_limit(120);



include DIR_classes . 'MailSo/MailSo.php';

if (!empty($_POST['codemailusuario']) && !empty($_POST['caixa'])) {

    $codemailusuario = $_POST['codemailusuario'];
    $caixa = $_POST['caixa'];
    $caixa_hash = md5($caixa);

    $caixa = \MailSo\Base\Utils::ConvertEncoding($caixa, \MailSo\Base\Enumerations\Charset::UTF_8, \MailSo\Base\Enumerations\Charset::UTF_7_IMAP);


    $bd = new BD_FB_EMAIL();
    $bd->open();

    $conta = getEmailSenha($codemailusuario);

    $email = $conta['EMAIL'];
    $senha = base64_decode($conta['SENHA']);
    $servidor = explode('@', $email);
    $servidor = 'pop.' . $servidor[1];

    /* echo $email."\n";
      echo $senha."\n";
      echo $servidor."\n"; */

    $oData = null;

    if (!empty($email) && !empty($senha) && !empty($servidor)) {

        try {
            $oMailClient = \MailSo\Mail\MailClient::NewInstance();

            $conn = $oMailClient
                    ->Connect($servidor, 143, \MailSo\Net\Enumerations\ConnectionSecurityType::NONE)
                    ->Login($email, $senha);
        } catch (Exception $e) {
            echo 'Erro: Conexão:';
            echo "\n";
            var_dump($e);
        }
        try {
            $res = $conn->FolderClear($caixa);
        } catch (Exception $e) {
            echo 'Erro: ao limpar pasta:';
            echo "\n";
            var_dump($e);
        }
        if (is_object($res) || is_array($res)) {
            $caixas_hash = carrega_caixas_hash($codemailusuario);
            $sql_update = "UPDATE baixar_email SET status = 'E' WHERE codcontasbaixaremail = " . $codemailusuario . " AND caixa = " . $caixas_hash[md5($caixa)];
            $query_update = ibase_query($sql_update);
            if (!$query_update) {
                echo 'Erro excluir mensagem: ' . $sql_update;
                return false;
            } else {

                try {
                    $res = $conn->FolderDelete($caixa);
                    if (is_object($res) || is_array($res)) {
                        $sql = "UPDATE caixas SET status = 'E' WHERE codcontasbaixaremail = " . $codemailusuario . " AND HASH = '" . $caixa_hash . "'";
                        $query = ibase_query($sql);
                        if ($query) {
                            echo 'ok';
                        } else {
                            echo 'Erro SQL: ' . $sql . "\n" . ibase_errmsg();
                        }
                    } else {
                        echo 'Erro ao excluir pasta.';
                    }
                } catch (Exception $e) {
                    echo 'Erro: ao excluir pasta:';
                    echo "\n";
                    var_dump($e);
                }
            }

            $oMailClient->LogoutAndDisconnect();
            $bd->close();
        } else {
            echo 'Erro ao limpar pasta';
        }
    } else {
        echo 'Erro: dados conta.';
    }
} else {
    echo 'Erro: POST.';
}

/*function limpa_pasta($conn, $caixa, $inicio, $fim, $id, $caixas_hash) {

   
    $oData = $conn->MessageList($caixa, $inicio, $fim);

    if (is_object($oData) || is_array($oData)) {
        if ($oData->Count() > 0) {
            for ($i = 0; $i < $oData->Count(); $i++) {
                $uid = $oData->GetByIndex($i)->Uid();
                $aux = array();
                $aux[0] = $uid;

                $sql_update = "UPDATE baixar_email SET status = 'E' WHERE codcontasbaixaremail = " . $id . " AND caixa = '" . $caixas_hash[md5($caixa)] . "' AND uid = " . $uid;
                $query_update = ibase_query($sql_update);
                if (!$query_update) {
                    echo 'Erro excluir mensagem: ' . $sql_update;
                    return false;
                } else {
                    $res = $conn->MessageDelete($caixa, $aux, $aux[0]);
                    if (is_object($res) || is_array($res)) {
                        
                    } else {
                        echo 'Erro excluir mensagem';
                        return false;
                    }
                }
            }
        }
    } else {
        echo 'Erro listar mensagens';
        return false;
    }

    if ($oData->Count() >= ($fim - $inicio) - 1) {
        return limpa_pasta($caixa, $fim, $fim * 2);
    } else {
        return true;
    }
}*/
