<?php

ini_set('memory_limit', '512M');
set_time_limit(600);

include DIR_classes . 'MailSo/MailSo.php';

if (!empty($_POST['codemailusuario']) && !empty($_POST['sessao'])) {

    $codemailusuario = Anti_Injection($_POST['codemailusuario']);
    $sessao = $_POST['sessao'];

    $bd_email = new BD_FB_EMAIL();
    $bd_email->open();
    $cod_sessao = get_sessao($codemailusuario);
    if (!empty($cod_sessao) && $cod_sessao == $sessao) {
        $conta = getEmailSenha($codemailusuario);
        $caixas = carrega_caixas($codemailusuario);

        $sql_update = "UPDATE baixar_email SET STATUS_CONTROLE = null WHERE CODCONTASBAIXAREMAIL = " . $codemailusuario . " AND status <> 'E'";
        $query_update = ibase_query($sql_update);

        if (!$query_update) {
            echo 'Erro update:' . $sql_update . "\n" . ibase_errmsg();
        } else {


            $email = $conta['EMAIL'];
            $senha = base64_decode($conta['SENHA']);
            $servidor = explode('@', $email);
            $servidor = 'pop.' . $servidor[1];

            if (!empty($email) && !empty($senha) && !empty($servidor)) {

                try {
                    $oMailClient = \MailSo\Mail\MailClient::NewInstance();

                    $conn = $oMailClient
                            ->Connect($servidor, 143, \MailSo\Net\Enumerations\ConnectionSecurityType::NONE)
                            ->Login($email, $senha);
                    $oData = $conn->Folders();
                } catch (Exception $e) {
                    var_dump($e);
                }

                $total_tb = array();
                $array_caixas = array();
                $array_caixas_total = array();
                $min_uid_caixa = array();

                $array_caixas[] = 'INBOX';
                $array_caixas[] = 'INBOX.lixo';
                $array_caixas[] = 'INBOX.enviadas';
                $array_caixas = processa_caixas($array_caixas, $oData);

                $bd_email = new BD_FB_EMAIL();
                $bd_email->open();

                $caixas = carrega_caixas($codemailusuario);


                for ($i = 0; $i < count($array_caixas); $i++) {
                    if (!isset($caixas[$array_caixas[$i]]) || empty($caixas[$array_caixas[$i]])) {
                        $tr = ibase_trans();

                        if (insere_caixas_tr($array_caixas[$i], $codemailusuario, $tr)) {
                            ibase_commit($tr);
                        } else {
                            ibase_rollback($tr);
                        }
                    }
                }


                $bd_email->close();

                //$array_caixas[] = 'INBOX.enviadas';

                echo 'ok|' . json_encode($array_caixas) /* . '|' . json_encode($array_caixas_total) . '|' . json_encode($total_tb) */;
            }
        }
    } else {
        echo 'sessao';
    }
    $bd_email->close();
}

function processa_caixas($array, $obj) {
    if (is_object($obj) || is_array($obj)) {
        if ($obj->Count() > 0) {
            for ($i = 0; $i < $obj->Count(); $i++) {
                $sub_obj = $obj->GetByIndex($i)->SubFolders();
                if ($obj->GetByIndex($i)->FullName() != 'INBOX' && $obj->GetByIndex($i)->FullName() != 'INBOX.lixo' && $obj->GetByIndex($i)->FullName() != 'INBOX.enviadas') {
                    $array[] = $obj->GetByIndex($i)->FullName();
                }
                $array = processa_caixas($array, $sub_obj);
            }
            return $array;
        } else {
            return $array;
        }
    } else {
        return $array;
    }
}
