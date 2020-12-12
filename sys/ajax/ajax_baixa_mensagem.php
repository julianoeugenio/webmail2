<?php

set_time_limit(240);
ini_set('max_execution_time', 240);


header('Content-Type: text/html; charset=utf-8');


mb_internal_encoding('UTF-8');
mb_http_output('UTF-8');
mb_http_input('UTF-8');
mb_regex_encoding('UTF-8');

include DIR_classes . 'MailSo/MailSo.php';
require(DIR_classes . 'EmailMessage.php');

if (!empty($_POST['conta']) && !empty($_POST['caixa']) && !empty($_POST['array']) && !empty($_POST['sessao'])) {

    $conta = $_POST['conta'];
    $caixa = $_POST['caixa'];
    $array = $_POST['array'];
    $forcado = $_POST['forcado'];
    $sessao = $_POST['sessao'];


    $bd_email = new BD_FB_EMAIL();
    $bd_email->open();

    $cod_sessao = get_sessao($conta);
    if (!empty($cod_sessao) && $cod_sessao == $sessao) {

        $tr = ibase_trans();
        $erros = 0;
        $erro = '';

        $conta_ar = getEmailSenha($conta);

        $email = $conta_ar['EMAIL'];
        $senha = base64_decode($conta_ar['SENHA']);
        $servidor = explode('@', $email);
        $servidor = 'pop.' . $servidor[1];

        if (!empty($email) && !empty($senha) && !empty($servidor)) {

            $caixas = carrega_caixas($conta);

            try {
                $oMailClient = \MailSo\Mail\MailClient::NewInstance();
                $conn = $oMailClient
                        ->Connect($servidor, 143, \MailSo\Net\Enumerations\ConnectionSecurityType::NONE)
                        ->Login($email, $senha);
            } catch (Exception $e) {
                var_dump($e);
            }
            // foreach ($array_ as $i => $array) {
            $gen_BAIXAR_EMAIL_ID = get_ultimo_gen_email_tr($tr, "BAIXAR_EMAIL_ID");
            //echo $caixa.' UID:'.$array[$i]."\n";
            $uid = intval($array);

            try {
                //$oData = $conn->FolderInformation(mb_convert_encoding(utf8_decode($caixa), "UTF7-IMAP", "ISO_8859-1"));
                $caixa_hash = md5($caixa);

                $oData = $conn->Message(mb_convert_encoding(utf8_decode($caixa), "UTF7-IMAP", "ISO_8859-1"), intval($uid));
            } catch (Exception $e) {
                var_dump($e);
            }

            $msg_email = '';
            if (!empty($oData->Html())) {
                $msg_email = $oData->Html();
            } else if (!empty($oData->Plain())) {
                $msg_email = nl2br($oData->Plain());
            }


            $assunto = $oData->Subject();

            if (empty($assunto)) {
                $assunto = '(Sem Assunto)';
            }

            $data = $oData->HeaderDate();

            $data_formatada = Data_Hora_BR($data, true);

            $from = $oData->From();
            $to = $oData->To();

            $de = '';
            $para = '';

            if (is_object($from) || is_array($from)) {
                if ($from->Count() > 0) {
                    for ($x = 0; $x < $from->Count(); $x++) {
                        if (empty($de)) {
                            $de = $from->GetByIndex($x)->GetEmail();
                            $de_nome = $from->GetByIndex($x)->GetDisplayName();
                        } else {
                            $de .= ',' . $from->GetByIndex($x)->GetEmail();
                            $de_nome .= ',' . $from->GetByIndex($x)->GetDisplayName();
                        }
                    }
                }
            }

            if (is_object($to) || is_array($to)) {
                if ($to->Count() > 0) {
                    for ($x = 0; $x < $to->Count(); $x++) {
                        if (empty($para)) {
                            $para = $to->GetByIndex($x)->GetEmail();
                            $para_nome = $to->GetByIndex($x)->GetDisplayName();
                        } else {
                            $para .= ',' . $to->GetByIndex($x)->GetEmail();
                            $para_nome .= ',' . $to->GetByIndex($x)->GetDisplayName();
                        }
                    }
                }
            }
            //$body = _encode_string_array($msg_email);
            $body = utf8_decode($msg_email);

            $lido = 'N';
            $marcado = 'N';

            if (sizeof($oData->Flags()) > 0) {
                for ($i = 0; $i < sizeof($oData->Flags()); $i++) {
                    if ($oData->Flags()[$i] == '\Seen') {
                        $lido = 'S';
                    }
                    if ($oData->Flags()[$i] == '\Flagged') {
                        $marcado = 'S';
                    }
                }
            }

            $size = $oData->Size();

            if ($caixa == 'INBOX.lixo') {
                $status = 'L';
            } else {
                $status = 'N';
            }

            $cc = $oData->Cc();

            $cc_e = '';
            $cc_nome = '';

            if (is_object($cc) || is_array($cc)) {
                if ($cc->Count() > 0) {
                    for ($x = 0; $x < $cc->Count(); $x++) {
                        if (empty($cc_e)) {
                            $cc_e = $cc->GetByIndex($x)->GetEmail();
                            $cc_nome = $cc->GetByIndex($x)->GetDisplayName();
                        } else {
                            $cc_e .= ',' . $cc->GetByIndex($x)->GetEmail();
                            $cc_nome .= ',' . $cc->GetByIndex($x)->GetDisplayName();
                        }
                    }
                }
            }

            $sql = "INSERT INTO baixar_email (codbaixaremail, uid, de, deNome, para,paranome, assunto,data, data_formatada, mensagem, caixa, codcontasbaixaremail,lido,size,copia, copianome,marcado,status, data_download) "
                    . "VALUES(" . $gen_BAIXAR_EMAIL_ID . ", " . $uid . ", '" . str_replace("'", "''", $de) . "',"
                    . "'" . utf8_decode(str_replace("'", "''", $de_nome)) . "', '" . str_replace("'", "''", $para) . "','" . utf8_decode(str_replace("'", "''", $para_nome)) . "','" . utf8_decode(str_replace("'", "''", $assunto)) . "',"
                    . "'" . $data . "','" . $data_formatada . "',?,'" . $caixas[$caixa] . "'," . $conta . ",'" . $lido . "'," . $size . ",'" . utf8_decode(str_replace("'", "''", $cc_e)) . "', '" . utf8_decode(str_replace("'", "''", $cc_nome)) . "','" . $marcado . "','" . $status . "', '" . Data_Hora_Firebird() . "')";

            $prepared = ibase_prepare($tr, $sql);
            $exec = ibase_execute($prepared, $body);


            if (!$exec) {
                $erros++;
                if (empty($erro)) {
                    $erro = $sql;
                }
            }


            if (is_object($oData->Attachments()) || is_array($oData->Attachments())) {
                if (!salva_anexo($servidor, $email, $senha, mb_convert_encoding(utf8_decode(Utf8($caixa)), "UTF7-IMAP", "ISO_8859-1"), $uid, $oData->Attachments(), $caixa_hash, $gen_BAIXAR_EMAIL_ID, $tr, $conta)) {
                    $erros++;
                    if (empty($erro)) {
                        $erro = 'Salvar Anexo';
                    }
                    //break;
                }
                /*preg_match_all('/src="cid:(.*)"/Uims', $msg_email, $matches);
                $pasta_link = DIR_anexos_email . $conta . '/' . $caixa_hash . '/' . $uid . "/";


                for ($i = 0; $i < $oData->Attachments()->Count(); $i++) {
                    if (is_array($oData->Attachments()->GetByIndex($i)->GetBodyStructure()) || is_object($oData->Attachments()->GetByIndex($i)->GetBodyStructure())) {
                        $cid = str_replace('<', '', $oData->Attachments()->GetByIndex($i)->GetBodyStructure()->ContentID());
                        $cid = str_replace('>', '', $cid);
                        foreach ($matches[1] as $match) {
                            if ($cid == $match) {

                                $uniqueFilename = \MailSo\Base\Utils::ConvertEncoding($oData->Attachments()->GetByIndex($i)->Filename(), \MailSo\Base\Enumerations\Charset::UTF_8, \MailSo\Base\Enumerations\Charset::WIN_1252);

                                if (empty($uniqueFilename)) {
                                    $uniqueFilename = \MailSo\Base\Utils::ConvertEncoding($oData->Attachments()->GetByIndex($i)->GetBodyStructure()->Description(), \MailSo\Base\Enumerations\Charset::UTF_8, \MailSo\Base\Enumerations\Charset::WIN_1252);
                                }
                                if (!empty($oData->Attachments()->GetByIndex($i)->CID())) {
                                    $search[] = "src=\"cid:$match\"";
                                    $pasta_link_f = $pasta_link . $uniqueFilename;
                                    $replace[] = "src=\"$pasta_link_f\"";
                                    $msg_email = str_replace($search, $replace, $msg_email);
                                }
                            }
                        }
                    }
                }*/
            }


//SQL

            $array_contato = explode(',', $de . ',' . $para);
            $array_contato_nome = explode(',', $de_nome . ',' . $para_nome);


            $array_contato_aux = array();
            $array_contato_nome_aux = array();

            $array_contato_aux_sql = array();
            $array_contato_nome_aux_sql = array();


            for ($i = 0; $i < count($array_contato); $i++) {
                $contato = str_replace("'", "''", trim($array_contato[$i]));
                $contato_nome = trim($array_contato_nome[$i]);

                if (!empty($contato) && $contato != $email) {
                    if (empty($contato_nome)) {
                        $contato_nome = explode('@', $contato);
                        $contato_nome = $contato_nome[0];
                    }

                    $array_contato_aux[] = $contato;
                    $array_contato_nome_aux[] = $contato_nome;

                    $array_contato_aux_sql[] = "'" . $contato . "'";
                    $array_contato_nome_aux_sql[] = "'" . $contato_nome . "'";
                }
            }

            if (count($array_contato_aux_sql) > 0) {

                $array_contato_repetido = array();
                $array_contato_nome_repetido = array();

                $sql_chk = "SELECT email, nome FROM contato WHERE codcontasbaixaremail = " . $conta . " AND email IN (" . implode(',', $array_contato_aux_sql) . ")";
                $query_chk = ibase_query($tr, $sql_chk);

                if ($query_chk) {
                    while ($reg_chk = ibase_fetch_assoc($query_chk)) {
                        $array_contato_repetido[] = $reg_chk['EMAIL'];
                        $array_contato_nome_repetido[] = $reg_chk['NOME'];
                    }
                } else {
                    $erros++;
                    if (empty($erro)) {
                        $erro = $sql_chk;
                    }
                }

                $array_contato = array_diff($array_contato_aux, $array_contato_repetido);
                $array_contato_nome = array_diff($array_contato_nome_aux, $array_contato_nome_repetido);



                for ($i = 0; $i < count($array_contato); $i++) {
                    $contato = trim($array_contato[$i]);
                    $contato_nome = str_replace("'", "''", trim($array_contato_nome[$i]));

                    if (!empty($contato) && !empty($contato_nome)) {

                        $gen_contato = get_ultimo_gen_email_tr($tr, "CONTATO");
                        $sql_contato = "INSERT INTO contato (codcontato, email, nome, codcontasbaixaremail) VALUES(" . $gen_contato . ", '" . $contato . "', '" . utf8_decode($contato_nome) . "', " . $conta . ")";
                        $query_contato = ibase_query($tr, $sql_contato);
                        if (!$query_contato) {
                            $erros++;
                            if (empty($erro)) {
                                $erro = $sql_contato;
                            }
                            break;
                        }
                    }
                }
            }






            if ($erros == 0) {
                ibase_commit($tr);
                echo 'ok';
            } else {
                ibase_rollback($tr);
                echo 'erro|' . $erro;
            }

            $oMailClient->LogoutAndDisconnect();
        } else {
            echo 'erro|Dados incompletos.';
        }
    } else {
        echo 'sessao';
    }
    $bd_email->close();
} else {
    echo 'erro|POST';
}


    