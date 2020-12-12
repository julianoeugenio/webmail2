<?php

set_time_limit(120);



include DIR_classes . 'MailSo/MailSo.php';

if (!empty($_POST['id']) && !empty($_POST['de']) && !empty($_POST['para']) && !empty($_POST['uid'])) {
    $id = Anti_Injection($_POST['id']);

    $de = $_POST['de'];
    $para = $_POST['para'];
    
    $de_cod = mb_convert_encoding(utf8_decode(Utf8($de)), "UTF7-IMAP", "ISO_8859-1");
    $para_cod = mb_convert_encoding(utf8_decode(Utf8($para)), "UTF7-IMAP", "ISO_8859-1");

    $uid = intval(Anti_Injection($_POST['uid']));

    $bd = new BD_FB_EMAIL();
    $bd->open();

    $conta = getEmailSenha($id);

    $bd->close();



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
            
            $caixa_hash_de = md5($de);
            $caixa_hash_para = md5($para);
            
            $oData = $conn->FolderInformation($para_cod);

            $novo_uid = $oData['UidNext'];
        } catch (Exception $e) {
            echo 'erro|';
            var_dump($e);
        }

        if (!empty($novo_uid) && !empty($caixa_hash_de) && !empty($caixa_hash_para)) {
            $aux = array();
            $aux[0] = $uid;


            $bd = new BD_FB_EMAIL();
            $bd->open();
            $caixas_hash = carrega_caixas_hash($id);
            $bd->close();


            if (!isset($caixas_hash[$caixa_hash_de])) {
                $caixas_hash[$caixa_hash_de] = cria_pasta_local($id, $de, $caixa_hash_de);
            }

            if (!isset($caixas_hash[$caixa_hash_para])) {
                $caixas_hash[$caixa_hash_para] = cria_pasta_local($id, $para, $caixa_hash_para);
            }

            if (!empty($caixas_hash[$caixa_hash_de]) && !empty($caixas_hash[$caixa_hash_para])) {
                $res = $conn->MessageMove($de_cod, $para_cod, $aux, $aux[0]);

                if (is_object($res) || is_array($res)) {
                    
                    $aux_update = " , status = 'N' ";
                    
                    if($para == 'INBOX.lixo'){
                        $aux_update = " , status = 'L' ";
                    }

                    $sql_update = "UPDATE baixar_email SET caixa=" . $caixas_hash[$caixa_hash_para] . ", uid=" . $novo_uid .$aux_update. " WHERE codcontasbaixaremail = " . $id . " AND caixa = " . $caixas_hash[$caixa_hash_de] . " AND uid = " . $uid;
                    $query_update = ibase_query($sql_update);
                    if (!$query_update) {
                        echo 'erro: SQL UPDATE';
                    } else {
                        $pasta_origem = DIR_anexos_email_fisico . $id . '/' . $caixa_hash_de . '/' . $uid;
                        $pasta_destino = DIR_anexos_email_fisico . $id . '/' . $caixa_hash_para;
                        if (!is_dir($pasta_destino)) {
                            mkdir($pasta_destino);
                        }
                        $pasta_destino .= '/' . $novo_uid;
                        if (is_dir($pasta_origem)) {
                            if (move_arquivos($pasta_origem, $pasta_destino)) {
                                echo 'ok';
                            } else {
                                echo 'erro|mover anexo';
                            }
                        } else {
                            echo 'ok';
                        }
                    }
                } else {
                    echo 'erro|';
                    print_r($res);
                }
            } else {
                echo 'erro|Dados hash';
            }
        } else {
            echo 'erro|Dados UID, hash';
        }
    } else {
        echo 'erro|Dados';
    }
} else {
    echo 'erro|POST';
}

function cria_pasta_local($cod_conta, $caixa, $hash) {

    $GEN_CAIXAS = get_ultimo_gen_email("CAIXAS");
    $sql_insert_caixa = "INSERT INTO caixas (codcaixas,codcontasbaixaremail,desccaixas, hash, status) VALUES(" . $GEN_CAIXAS . "," . $cod_conta . ", '" . utf8_decode($caixa) . "', '" . $hash . "', 'A')";
    $query_insert_caixa = ibase_query($sql_insert_caixa);
    if ($query_insert_caixa) {
        return $GEN_CAIXAS;
    } else {
        return -1;
    }
}
