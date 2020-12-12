<?php

ini_set('memory_limit', '512M');
set_time_limit(600);

include DIR_classes . 'MailSo/MailSo.php';

if (!empty($_POST['codemailusuario']) && !empty($_POST['caixa']) && !empty($_POST['qtn']) && !empty($_POST['sessao'])) {

    $codemailusuario = Anti_Injection($_POST['codemailusuario']);
    $caixa = $_POST['caixa'];
    $pagina = Anti_Injection($_POST['pagina']);
    $forcado = Anti_Injection($_POST['forcado']);
    $sessao = $_POST['sessao'];

    if (empty($pagina)) {
        $pagina = 1;
    } else {
        $pagina = intval($pagina);
    }

    $qtn = intval(Anti_Injection($_POST['qtn']));

    if ($pagina > 1) {
        $offset = $qtn * ($pagina - 1);
    } else {
        $offset = 0;
    }

    $bd_email = new BD_FB_EMAIL();
    $bd_email->open();

    $cod_sessao = get_sessao($codemailusuario);
    if (!empty($cod_sessao) && $cod_sessao == $sessao) {
        $conta = getEmailSenha($codemailusuario);
        $caixas = carrega_caixas($codemailusuario);

        $bd_email->close();

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
            } catch (Exception $e) {
                var_dump($e);
            }


            $continua = true;
            $array_uid_caixas = array();



            $array_uid_caixas[$caixa] = get_lista_emails_caixa($conn, $offset, $caixa, $qtn, $caixas[$caixa], $codemailusuario, $caixas, $forcado);
            if ($array_uid_caixas[$caixa] == -1) {
                echo 'erro|Erro ao processar caixas e emails';
                $continua = false;
            }


            if ($continua) {

                //print_r($array_uid_caixas);
                echo 'ok|' . json_encode($array_uid_caixas);
            }



            $oMailClient->LogoutAndDisconnect();
        }
    } else {
        echo 'sessao';
    }
}

function procura_remove_repetido($array, $val) {
    $array_rem = array();
    for ($i = 0; $i < count($array); $i++) {
        if ($array[$i] == $val) {
            $array_rem[] = $i;
        }
    }

    for ($i = 0; $i < count($array_rem); $i++) {
        array_splice($array, $array_rem[$i], 1);
    }

    return $array;
}

function get_lista_emails_caixa($conn, $offset, $caixa, $qtn, $cod_caixas, $cod_conta, $caixas, $forcado) {
    /* echo 'caixa:'.$caixa."\n";
      echo 'offset:'.$offset."\n";
      echo 'qtn:'.$qtn."\n"; */
    $array_uid = array();
    try {
        // $oData_total = $conn->FolderInformation($caixa);
        $oData = $conn->MessageList((mb_convert_encoding(utf8_decode($caixa), "UTF7-IMAP", "ISO_8859-1")), $offset, $qtn);
    } catch (Exception $e) {
        var_dump($e);
    }

    $bd_email = new BD_FB_EMAIL();
    $bd_email->open();
    $tr = ibase_trans();

    $cod_caixas = '';
    if (isset($caixas[$caixa]) && !empty($caixas[$caixa])) {
        $cod_caixas = $caixas[$caixa];
    }

    if (empty($cod_caixas)) {
        $cod_caixas = insere_caixas_tr($caixa, $cod_conta, $tr);
        if (!$cod_caixas) {
            echo '1:' . $sql_insert_caixa . "\n" . ibase_errmsg();
            ibase_rollback($tr);
            return -1;
        }
    }




    $sql = "SELECT MAX(uid) as min_uid FROM baixar_email WHERE caixa = " . $cod_caixas . " AND CODCONTASBAIXAREMAIL = " . $cod_conta . " AND status <> 'E'";

    $query = ibase_query($tr, $sql);
    $min_uid_caixa = 0;
    if ($query) {
        $reg = ibase_fetch_assoc($query);
        if ($reg['MIN_UID'] != null) {
            $min_uid_caixa = $reg['MIN_UID'];
        }
    } else {
        echo 'erro: ' . $sql;
    }

    if ($forcado == 'S') {
        $aux_uid = array();
        $aux_uid_sql = array();

        for ($i = 0; $i < $oData->Count(); $i++) {
            $uid = $oData->GetByIndex($i)->Uid();
            $aux_uid[] = $uid;
        }

        if (count($aux_uid) > 0) {
          /*  $sql_update = "UPDATE baixar_email SET STATUS_CONTROLE = 'V' WHERE CODCONTASBAIXAREMAIL = " . $cod_conta . " AND status <> 'E' AND STATUS_CONTROLE IS null AND uid NOT IN (" . implode(',', $aux_uid) . ") AND caixa = " . $cod_caixas;
            $query_update = ibase_query($tr, $sql_update);

            if (!$query_update) {
                echo 'Erro update:' . $sql_update . "\n" . ibase_errmsg();
                ibase_rollback($tr);
                return -1;
            }*/
            
            $sql_update_2 = "UPDATE baixar_email SET STATUS_CONTROLE = 'O' WHERE CODCONTASBAIXAREMAIL = " . $cod_conta . " AND status <> 'E' AND STATUS_CONTROLE = 'V' AND uid IN (" . implode(',', $aux_uid) . ") AND caixa = " . $cod_caixas;
            $query_update_2 = ibase_query($tr, $sql_update_2);

            if (!$query_update_2) {
                echo 'Erro update:' . $sql_update_2 . "\n" . ibase_errmsg();
                ibase_rollback($tr);
                return -1;
            }

            $sql_select = "SELECT uid FROM baixar_email  WHERE CODCONTASBAIXAREMAIL = " . $cod_conta . " AND status <> 'E' AND uid IN (" . implode(',', $aux_uid) . ") AND caixa = " . $cod_caixas;
            $query_select = ibase_query($tr, $sql_select);

            if (!$query_select) {
                echo 'Erro select:' . $sql_select . "\n" . ibase_errmsg();
                ibase_rollback($tr);
                return -1;
            }

            while ($reg_uid = ibase_fetch_assoc($query_select)) {
                $aux_uid_sql[] = $reg_uid['UID'];
            }
        }

        for ($i = 0; $i < $oData->Count(); $i++) {
            $uid = $oData->GetByIndex($i)->Uid();
            if (!in_array($uid, $aux_uid_sql)) {
                $array_uid[$i] = $uid;
            }
        }
    } else {
        for ($i = 0; $i < $oData->Count(); $i++) {
            $uid = $oData->GetByIndex($i)->Uid();
            if ($min_uid_caixa < $uid) {
                $array_uid[$i] = $uid;
            } else {
                break;
            }
        }
    }


    ibase_commit($tr);
    $bd_email->close();


    return array_reverse($array_uid);
}

function processa_caixas($array, $obj) {
    if (is_object($obj) || is_array($obj)) {
        if ($obj->Count() > 0) {
            for ($i = 0; $i < $obj->Count(); $i++) {
                $sub_obj = $obj->GetByIndex($i)->SubFolders();
                $array[] = $obj->GetByIndex($i)->FullName();
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
