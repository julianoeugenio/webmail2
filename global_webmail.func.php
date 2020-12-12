<?php

function monta_mensagem_off($reg, $ac) {
    if (!empty($reg['ASSUNTO'])) {
        $assunto = Utf8($reg['ASSUNTO']);
    } else {
        $assunto = '(sem assunto)';
    }


    $de_tr = Utf8($reg[$ac]);

    $anexos = '';
    if (!empty($reg['ANEXO'])) {
        $anexos = '<img src="' . DIR_siga_img . 'email/anexo.svg" style="height:22px; width:22px;">';
    }
    $size = $reg['SIZE'];
    $unidade = "Bytes";

    if ($size > 1024) {
        $size /= 1024;
        $unidade = "KB";
    }

    if ($size > 1024) {
        $size /= 1024;
        $unidade = "MB";
    }

    if ($size > 1024) {
        $size /= 1024;
        $unidade = "GB";
    }

    $uid = $reg['UID'];
    $cc = Utf8($reg['COPIA']);

    $data = Data_Hora_BR($reg['DATA']);


    $flag = '';
    $marcado = '';
    $lida = " msg_lida ";

    if ($reg['LIDO'] == 'S') {
        $lida = "";
    }

    if (!empty($reg['MARCADO']) && $reg['MARCADO'] == 'F') {
        $flag = '<img src="' . DIR_siga_img . 'email/flag.svg" style="height:22px; width:22px;">';
        $marcado = " color:red; ";
    }
    $id_email = Utf8($reg['DESCCAIXAS']) . '%*%' . $uid . '%*%' . $reg['CODCONTASBAIXAREMAIL'];

    $tb = "<tr id='" . $id_email . "' class='grid_tb_mensagens cursor_pointer " . $lida . " exclui_" . $uid . "' style='" . $marcado . "'  draggable='true'>";
    $tb .= "<td id='#' class='tb_style_mensagens' style='".gera_style('chk')."'><input type='checkbox' class='email_selecionado' value='" . $uid . "'></td>";
    $tb .= "<td id='" . $id_email . "' class='tb_style_mensagens' style='".gera_style('de')."'><strong>" . $de_tr . "</strong></td>";
    $tb .= "<td id='" . $id_email . "' class='tb_style_mensagens' style='".gera_style('assunto')."'><strong>" . $assunto . "</strong></td>";
    $tb .= "<td id='" . $id_email . "' class='tb_style_mensagens' style='padding: 4px 5px 4px 5px!important;".gera_style('anexo')."'>" . $anexos . "</td>";
    $tb .= "<td id='" . $id_email . "' class='tb_style_mensagens' style='max-width:125px!important;".gera_style('data')."'><strong>" . $data . "</strong></td>";
    $tb .= "<td id='" . $id_email . "' class='tb_style_mensagens' style='padding: 4px 5px 4px 5px!important;".gera_style('tamanho')."'>" . round($size, 1) . ' ' . $unidade . "</td>";
    $tb .= "<td id='" . $id_email . "' class='tb_style_mensagens' style='padding: 4px 5px 4px 5px!important;".gera_style('flag')."'>" . $flag . "</td>";
    $tb .= "<td id='" . $id_email . "' class='tb_style_mensagens' style='border-right: none!important;".gera_style('copia')."'><strong>" . $cc . "</strong></td>";
    $tb .= "</tr></div>";

    return $tb;
}

function gera_style($index) {
    $size_tb = array();

    $size_tb['chk'] = '30px';
    $size_tb['de'] = 'calc(20% - 260px)';
    $size_tb['assunto'] = 'calc(70% - 260px)';
    $size_tb['anexo'] = '30px';
    $size_tb['data'] = '110px';
    $size_tb['tamanho'] = '60px';
    $size_tb['flag'] = '30px';
    $size_tb['copia'] = 'calc(10% - 260px)';

    if (isset($size_tb[$index])) {
        return 'width:' . $size_tb[$index] . '!important;min-width:' . $size_tb[$index] . '!important;max-width:' . $size_tb[$index] . '!important;';
    } else {
        return '';
    }
}

/*
  Obtem sessão
 */

function get_sessao($cod_conta) {
    $sql = "SELECT first 1 codsessao FROM sessao WHERE codcontasbaixaremail = " . $cod_conta . " ORDER BY data DESC";
    $query = ibase_query($sql);
    if ($query) {
        if ($reg = ibase_fetch_assoc($query)) {
            if (!empty($reg['CODSESSAO'])) {
                return $reg['CODSESSAO'];
            } else {
                return false;
            }
        } else {
            return false;
        }
    } else {
        return false;
    }
}

/*
  Gera sessão
 */

function gera_sessao($cod_conta) {

    if (remove_sessao($cod_conta)) {
        $cod_sessao = get_ultimo_gen_email("SESSAO");
        $sql_insert = "INSERT INTO sessao (codsessao, data, codcontasbaixaremail) VALUES (" . $cod_sessao . ", '" . Data_Firebird() . "', " . $cod_conta . ")";
        $query_insert = ibase_query($sql_insert);
        if ($query_insert) {
            return $cod_sessao;
        } else {
            return false;
        }
    } else {
        return false;
    }
}

/*
  Remove sessão
 */

function remove_sessao($cod_conta) {
    $sql_del = "DELETE FROM sessao WHERE codcontasbaixaremail = " . $cod_conta;
    $query_del = ibase_query($sql_del);

    if ($query_del) {
        return true;
    } else {
        return false;
    }
}

/*

  Monta Linha mensagem formatada direto da web

 */

function monta_mensagem($msg, $email, $id) {

    $assunto = Utf8($msg->Subject());

    if (empty($assunto)) {
        $assunto = '(Sem Assunto)';
    }

    $from = $msg->From();
    $to = $msg->To();
    $dest_tr = '';

    if (is_object($from) || is_array($from)) {
        if ($from->Count() > 0 && $from->GetByIndex(0)->GetEmail() != $email) {
            for ($x = 0; $x < $from->Count(); $x++) {
                if (empty($dest_tr)) {
                    if (!empty($from->GetByIndex($x)->GetDisplayName())) {
                        $dest_tr = Utf8(rtrim(ltrim($from->GetByIndex($x)->GetDisplayName())));
                    } else {
                        $dest_tr = rtrim(ltrim($from->GetByIndex($x)->GetEmail()));
                    }
                } else {
                    if (!empty($from->GetByIndex($x)->GetDisplayName())) {
                        $dest_tr .= ',' . Utf8(rtrim(ltrim($from->GetByIndex($x)->GetDisplayName())));
                    } else {
                        $dest_tr .= ',' . rtrim(ltrim($from->GetByIndex($x)->GetEmail()));
                    }
                }
            }
        }
    }

    if (is_object($to) || is_array($to)) {
        if ($to->Count() > 0 && $to->GetByIndex(0)->GetEmail() != $email && empty($dest_tr)) {
            for ($x = 0; $x < $to->Count(); $x++) {
                if (empty($dest_tr)) {
                    if (!empty($to->GetByIndex($x)->GetDisplayName())) {
                        $dest_tr = Utf8(rtrim(ltrim($to->GetByIndex($x)->GetDisplayName())));
                    } else {
                        $dest_tr = rtrim(ltrim($to->GetByIndex($x)->GetEmail()));
                    }
                } else {
                    if (!empty($to->GetByIndex($x)->GetDisplayName())) {
                        $dest_tr .= ',' . Utf8(rtrim(ltrim($to->GetByIndex($x)->GetDisplayName())));
                    } else {
                        $dest_tr .= ',' . rtrim(ltrim($to->GetByIndex($x)->GetEmail()));
                    }
                }
            }
        }
    }

    if (empty($dest_tr)) {
        for ($x = 0; $x < $from->Count(); $x++) {
            if (empty($dest_tr)) {
                if (!empty($from->GetByIndex($x)->GetDisplayName())) {
                    $dest_tr = Utf8(rtrim(ltrim($from->GetByIndex($x)->GetDisplayName())));
                } else {
                    $dest_tr = rtrim(ltrim($from->GetByIndex($x)->GetEmail()));
                }
            } else {
                if (!empty($from->GetByIndex($x)->GetDisplayName())) {
                    $dest_tr .= ',' . Utf8(rtrim(ltrim($from->GetByIndex($x)->GetDisplayName())));
                } else {
                    $dest_tr .= ',' . rtrim(ltrim($from->GetByIndex($x)->GetEmail()));
                }
            }
        }
    }
    $anexos = "";
    if (is_object($msg->Attachments()) || is_array($msg->Attachments())) {
        if ($msg->Attachments()->Count() > 0) {
            $anexos = '<img src="' . DIR_siga_img . 'email/anexo.svg" style="height:22px; width:22px;">';
        }
    }

    $data = Data_Hora_BR($msg->HeaderDate());
    //$data .= ' (' . $msg->HeaderDate() . ')';

    $size = $msg->Size();
    $unidade = "Bytes";

    if ($size > 1024) {
        $size /= 1024;
        $unidade = "KB";
    }

    if ($size > 1024) {
        $size /= 1024;
        $unidade = "MB";
    }

    if ($size > 1024) {
        $size /= 1024;
        $unidade = "GB";
    }

    $uid = $msg->Uid();

    $cc = $msg->Cc();

    $copia = '';

    if (is_object($cc) || is_array($cc)) {
        if ($cc->Count() > 0) {
            for ($x = 0; $x < $cc->Count(); $x++) {
                if (empty($copia)) {
                    $copia = rtrim(ltrim($cc->GetByIndex($x)->GetDisplayName())) . '(' . rtrim(ltrim($cc->GetByIndex($x)->GetEmail())) . ')';
                } else {
                    $copia .= ',' . rtrim(ltrim($cc->GetByIndex($x)->GetDisplayName())) . '(' . rtrim(ltrim($cc->GetByIndex($x)->GetEmail())) . ')';
                }
            }
        }
    }

    $flag = '';
    $marcado = '';
    $lida = " msg_lida ";

    if (sizeof($msg->Flags()) > 0) {
        for ($i = 0; $i < sizeof($msg->Flags()); $i++) {
            if ($msg->Flags()[$i] == '\Seen') {
                $lida = "";
            }
            if ($msg->Flags()[$i] == '\Flagged') {
                $flag = '<img src="' . DIR_siga_img . 'email/flag.svg" style="height:22px; width:22px;">';
                $marcado = " color:red; ";
            }
        }
    }


    $caixa = $msg->Folder();

    $id_email = Utf8(mb_convert_encoding(Utf8($caixa), "ISO_8859-1", "UTF7-IMAP")) . '%*%' . $uid . '%*%' . $id;

    $tb = "<tr id='" . $id_email . "' class='grid_tb_mensagens cursor_pointer " . $lida . " exclui_" . $uid . "' style='" . $marcado . "'  draggable='true'>";
    $tb .= "<td id='#' class='tb_style_mensagens'><input type='checkbox' class='email_selecionado' value='" . $uid . "'></td>";
    $tb .= "<td id='" . $id_email . "' class='tb_style_mensagens'><strong>" . $dest_tr . "</strong></td>";
    $tb .= "<td id='" . $id_email . "' class='tb_style_mensagens'><strong>" . $assunto . "</strong></td>";
    $tb .= "<td id='" . $id_email . "' class='tb_style_mensagens' style='padding: 4px 5px 4px 5px!important;'>" . $anexos . "</td>";
    $tb .= "<td id='" . $id_email . "' class='tb_style_mensagens'><strong>" . $data . "</strong></td>";
    $tb .= "<td id='" . $id_email . "' class='tb_style_mensagens' style='padding: 4px 5px 4px 5px!important;'>" . round($size, 1) . ' ' . $unidade . "</td>";
    $tb .= "<td id='" . $id_email . "' class='tb_style_mensagens' style='padding: 4px 5px 4px 5px!important;'>" . $flag . "</td>";
    $tb .= "<td id='" . $id_email . "' class='tb_style_mensagens' style='border-right: none!important;'><strong>" . $copia . "</strong></td>";
    $tb .= "</tr></div>";

    return $tb;
}

/*

  Carrega mensagem formatada direto da web

 */

function Carrega_email_web($id, $caixa, $uid, $cmb_caixas = array()) {
    set_time_limit(600);
    $caixa_cod = mb_convert_encoding(utf8_decode(Utf8($caixa)), "UTF7-IMAP", "ISO_8859-1");

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
            $oData = $conn->FolderInformation($caixa_cod);

            $caixa_hash = md5($oData['Folder']);


            $oData = $conn->Message($caixa_cod, $uid);
        } catch (Exception $e) {
            var_dump($e);
        }
        if (is_object($oData) || is_array($oData)) {
            $lida = false;
            if (is_object($oData->Flags()) || is_array($oData->Flags())) {
                if (sizeof($oData->Flags()) > 0) {
                    for ($i = 0; $i < sizeof($oData->Flags()); $i++) {
                        if ($oData->Flags()[$i] == '\Seen') {
                            $lida = true;
                        }
                    }
                }
            }

            if (!$lida) {
                $array_uid = array();
                $array_uid[0] = $uid;


                $bd = new BD_FB_EMAIL();
                $bd->open();
                $caixas = carrega_caixas($id);
                if (!empty($id) && !empty($caixas[$caixa]) && !empty($uid)) {

                    try {
                        $conn->MessageSetFlag($caixa_cod, $array_uid, true, '\Seen');
                    } catch (Exception $e) {
                        var_dump($e);
                    }

                    $sql_update = "UPDATE baixar_email SET lido='S' WHERE codcontasbaixaremail = " . $id . " AND caixa = " . $caixas[$caixa] . " AND uid = " . $uid;
                    $query_update = ibase_query($sql_update);
                    if (!$query_update) {
                        echo 'erro: SQL UPDATE';
                    }
                } else {
                    echo 'erro: itens faltando';
                }
                $bd->close();
            }



            if (!empty($oData->Html())) {
                $msg_email = $oData->Html();
            } else if (!empty($oData->Plain())) {
                $msg_email = nl2br($oData->Plain());
            }


            $assunto = $oData->Subject();

            if (empty($assunto)) {
                $assunto = '(Sem Assunto)';
            }

            $from = $oData->From();
            $to = $oData->To();

            $de = '';
            $para = '';


            if (is_object($from) || is_array($from)) {
                if ($from->Count() > 0) {
                    for ($x = 0; $x < $from->Count(); $x++) {
                        if (empty($de)) {
                            $de = $from->GetByIndex($x)->GetDisplayName() . ' (' . $from->GetByIndex($x)->GetEmail() . ')';
                        } else {
                            $de .= ',' . $from->GetByIndex($x)->GetDisplayName() . ' (' . $from->GetByIndex($x)->GetEmail() . ')';
                        }
                    }
                }
            }

            if (is_object($to) || is_array($to)) {
                if ($to->Count() > 0) {
                    for ($x = 0; $x < $to->Count(); $x++) {
                        if (empty($para)) {
                            $para = $to->GetByIndex($x)->GetDisplayName() . ' (' . $to->GetByIndex($x)->GetEmail() . ')';
                        } else {
                            $para .= ',' . $to->GetByIndex($x)->GetDisplayName() . ' (' . $to->GetByIndex($x)->GetEmail() . ')';
                        }
                    }
                }
            }

            /* if (date('I') == 1) {
              $fuso_horario = -2;
              }else{
              $fuso_horario = -3;
              } */


            $data = Data_Hora_BR(str_replace('(', '', str_replace(')', '', $oData->HeaderDate())));
            //$data = $oData->HeaderDate();


            $ret_ec = '<div id="div_msg_header" '
                    . 'style="width: calc(96vw - 19px);min-width: fit-content;overflow-x: scroll;position: relative; height: auto; min-height: 52px; padding: 10px 0 0 20px;     background: -webkit-gradient(linear,left top,left bottom,color-stop(0%,#fff),color-stop(100%,#f0f0f0)); border: 1px solid #f0f0f0; border-radius: 8px;"> '
                    . '<div style="float: left;"><b style="color: #666;"> De: </b>' . $de . '<br /><b style="color: #666;"> Para: </b>' . $para . '<br /><b style="color: #666;"> Assunto: </b>' . $assunto . '<br /><b style="color: #666;"> Data: </b>' . $data . '</div>';


            if (count($cmb_caixas) > 0) {
                $ret_ec .= '<div style="float: right;margin-right: 12px;"><select class="" id="select_caixa_para">';
                $ret_ec .= '<option value="0" selected="selected">Transferir email</option>';
                for ($i = 0; $i < count($cmb_caixas); $i++) {
                    $aux_array_caixas = $cmb_caixas[$i];
                    $aux_caixa_nome = $aux_array_caixas[1];
                    $aux_caixa_nome_ex = explode('.', $aux_array_caixas[1]);
                    if (count($aux_caixa_nome_ex) > 1) {
                        $aux_caixa_nome = $aux_caixa_nome_ex[count($aux_caixa_nome_ex) - 1];
                    }

                    $ret_ec .= '<option value="' . $aux_array_caixas[1] . '">' . $aux_caixa_nome . '</option>';
                }
                $ret_ec .= '</select><br><a href="#" style="margin: 8px 2px 0px 0px;float: right;" id="tranferir_email_cmb"><img src="' . DIR_siga_img . 'grid_bt_transf_feed.png" /></a></div>';
            }
            $ret_ec .= '<div style="clear:both;"></div>';
            $anexo_src = '';
            if (is_object($oData->Attachments()) || is_array($oData->Attachments())) {
                salva_anexo($servidor, $email, $senha, $caixa_cod, $uid, $oData->Attachments(), $caixa_hash, '', null, $id);
                preg_match_all('/src="cid:(.*)"/Uims', $msg_email, $matches);
                $pasta_link = DIR_anexos_email . $id . '/' . $caixa_hash . '/' . $uid . "/";

                for ($i = 0; $i < $oData->Attachments()->Count(); $i++) {
                    $cid = str_replace('<', '', $oData->Attachments()->GetByIndex($i)->GetBodyStructure()->ContentID());
                    $cid = str_replace('>', '', $cid);
                    $e_cid = false;
                    foreach ($matches[1] as $match) {
                        if ($cid == $match) {
                            $e_cid = true;
                            $uniqueFilename = Utf8(utf8_decode(imap_utf8($oData->Attachments()->GetByIndex($i)->Filename())));
                            if (empty($uniqueFilename)) {
                                $uniqueFilename = Utf8(utf8_decode(imap_utf8($oData->Attachments()->GetByIndex($i)->GetBodyStructure()->Description())));
                            }
                            $ret_ec .= '<div style="float:left; margin-left:20px; margin-right:15px;"> ANEXOS:<a href="' . $pasta_link . $uniqueFilename . '" download>' . $uniqueFilename . '</a></div>';
                            if (empty($oData->Attachments()->GetByIndex($i)->CID())) {
                                //$aux_msg_email .= '<img src="'.$pasta_link.$uniqueFilename.'"/>';

                                $annex_ext = strtolower(get_Extensao_Arquivo($pasta_link . $uniqueFilename));

                                if ($annex_ext == '.jpg' || $annex_ext == '.png' || $annex_ext == '.gif' || $annex_ext == '.bmp') {
                                    $anexo_src .= "<img src='" . $pasta_link . $uniqueFilename . "' style='max-width:640px; max-height: 480px;' />";
                                }
                            } else {
                                $search[] = "src=\"cid:$match\"";
                                $pasta_link_f = $pasta_link . $uniqueFilename;
                                $replace[] = "src=\"$pasta_link_f\"";
                                $msg_email = str_replace($search, $replace, $msg_email);
                            }
                        }
                    }
                    if (!$e_cid) {
                        $uniqueFilename = Utf8(utf8_decode(imap_utf8($oData->Attachments()->GetByIndex($i)->Filename())));
                        if (empty($uniqueFilename)) {
                            $uniqueFilename = Utf8(utf8_decode(imap_utf8($oData->Attachments()->GetByIndex($i)->GetBodyStructure()->Description())));
                        }

                        $ret_ec .= '<div style="float:left; margin-left:20px; margin-right:15px;"> ANEXOS:<a href="' . $pasta_link . $uniqueFilename . '" download>' . $uniqueFilename . '</a></div>';
                    }
                }
            }





            $ret_ec .= '</div><br><br>';


            $oMailClient->LogoutAndDisconnect();

            return '<div id="tb_mensagem" style="height: 100%;min-width: 1000px; /*max-width: 1000px;*/ border: 1px solid #bbd3da; padding: 10px 10px 40px 10px;  background-color: #fff; border-radius: 8px;overflow-x: scroll;">' . $ret_ec . '<div style="clear:both;font-size:12px;">' . $msg_email . '</div><br /><br />' . $anexo_src . '</div>';
        } else {
            return 'Erro ao carregar dados.';
        }
    }
}

/*

  Obtem login e senha do email apartir do código da conta

 */

function getEmailSenha($id) {
    $sql = "SELECT email, senha FROM contas_baixar_email WHERE CODCONTASBAIXAREMAIL = " . $id;
    $query = ibase_query($sql);


    if ($query) {
        return ibase_fetch_assoc($query);
    } else {
        return false;
    }
}

/*

  Formata data e hora de email para exibir na tela e gravar no banco.

 */

function Data_Hora_BR($data, $firebird = false) {

    $data_aux = $data;
    if (date('I', strtotime($data)) == 1) {
        $fuso = -2;
    } else {
        $fuso = -3;
    }

    $aux_fuso_ = $fuso;

    $data = str_replace('(-0', '', $data);
    $data_ex = explode('-', $data);

    if (count($data_ex) == 2) {
        $data = $data_ex[0];
        $aux = str_split(trim(str_replace('0', '', $data_ex[1])));
        $aux_fuso = '';

        for ($i = 0; $i < count($aux); $i++) {
            if (is_numeric($aux[$i])) {
                $aux_fuso .= $aux[$i];
            } else {
                break;
            }
        }

        $fuso = intval($aux_fuso) * -1;
    } else {
        $data_ex = explode('+', $data);
        if (count($data_ex) == 2) {
            $data = $data_ex[0];
            $aux = str_split(trim(str_replace('0', '', $data_ex[1])));
            $aux_fuso = 'b';
            for ($i = 0; $i < count($aux); $i++) {
                if (is_numeric($aux[$i])) {
                    $aux_fuso .= $aux[$i];
                } else {
                    break;
                }
            }

            $fuso = intval($aux_fuso);
        }
    }

    if (strripos($data_aux, '(UTC)')) {
        $fuso = intval($fuso);
        if ($fuso == 0) {
            $fuso = 1;
        }
        $fuso = $fuso * (intval($aux_fuso_) * 2);
    }
    $data = gmdate('Y-m-d|H:i:s', strtotime($data));
    $data = explode("|", $data);
    $hora = explode(":", $data[1]);
    $data = Data_BR($data[0]);

    $hr = (intval($hora[0]) + $fuso);
    //$hr = ($hora[0]);

    if ($hr <= 0) {
        $data_ex = explode('/', $data);

        $data_dia = intval($data_ex[0]);
        $data_dia -= ceil(($hr * -1) / 24);

        $data_mes = intval($data_ex[1]);
        $data_ano = intval($data_ex[2]);

        if ($data_dia < 1) {
            $data_mes--;
            $ultimo_dia_mes = date("t", mktime(0, 0, 0, $data_mes, 1, $data_ano));
            $data_dia += $ultimo_dia_mes;
        }

        if ($data_mes < 1) {
            $data_ano--;
            $data_mes += 12;
        }

        if ($data_mes < 10) {
            $data_mes = '0' . $data_mes;
        }

        if ($data_dia < 10) {
            $data_dia = '0' . $data_dia;
        }
        $data = $data_dia . '/' . $data_mes . '/' . $data_ano;

        $hr += 24;
    }

    if ($hr >= 24) {
        $hr -= 24;
    }

    if ($hr < 10) {
        $hr = '0' . $hr;
    }
    if ($firebird) {
        $data = str_replace('/', '.', $data);
        $hora = $hr . ':' . $hora[1] . ':' . $hora[2];
    } else {
        $hora = $hr . ':' . $hora[1];
    }


    return $data . ' ' . $hora;
}

function salva_anexo($pop, $usuario, $senha, $caixa, $uid, $obj_aux, $caixa_hash, $cod_email = '', $tr = null, $conta) {
    $pasta = DIR_anexos_email_fisico . $conta;
    if (!is_dir($pasta)) {
        mkdir($pasta);
    }

    imap_timeout(10);

    $caixaDeCorreio = imap_open("{" . $pop . ":143/novalidate-cert}" . $caixa, $usuario, $senha);

    if (!$caixaDeCorreio) {
        $caixaDeCorreio = imap_open("{" . $pop . ":143/novalidate-cert}" . $caixa, $usuario, $senha);
    }

    if ($caixaDeCorreio) {

        if (empty($cod_email)) {
            require(DIR_classes . 'EmailMessage.php');
        }
        $matches = array();
        $codigo = imap_msgno($caixaDeCorreio, $uid);
        if ($codigo > 0) {
            $pasta_f = $pasta . '/' . $caixa_hash . '/' . $uid;

            $emailMessage = new EmailMessage($caixaDeCorreio, $codigo);
            $emailMessage->fetch();

            /* if (count($emailMessage->attachments) == 0) {
              $emailMessage = new EmailMessage($caixaDeCorreio, $codigo);
              $emailMessage->fetch();
              echo ' Re fetch ';

              echo nl2br(print_r($emailMessage, true));
              } */

            preg_match_all('/src="cid:(.*)"/Uims', $emailMessage->bodyHTML, $matches);


            $array_anexos = array();

            for ($i = 0; $i < $obj_aux->Count(); $i++) {
                $array_anexos_aux = array();

                $cid = str_replace('<', '', $obj_aux->GetByIndex($i)->GetBodyStructure()->ContentID());
                $cid = str_replace('>', '', $cid);

                $uniqueFilename = $obj_aux->GetByIndex($i)->Filename();
                if (empty($uniqueFilename)) {
                    $uniqueFilename = $obj_aux->GetByIndex($i)->GetBodyStructure()->Description();
                }


                if (!empty(trim($uniqueFilename))) {
                    $array_anexos_aux['nome'] = $uniqueFilename;
                    if (!empty($obj_aux->GetByIndex($i)->CID())) {
                        $array_anexos_aux['cid'] = $cid;
                    } else {
                        $array_anexos_aux['cid'] = 'N';
                    }
                    $array_anexos_aux['similaridade'] = array();

                    $array_anexos[] = $array_anexos_aux;

                    /* $salva_arq = grava_arquivo($pasta, $pasta_f, $caixa_hash, $uid, $emailMessage->attachments, $uniqueFilename, $obj_aux->GetByIndex($i));

                      if ($salva_arq != false) {
                      if (!empty($cod_email)) {
                      if (!insere_anexo($cod_email, $salva_arq, $cid, $tr)) {
                      echo 'ERRO 1';
                      return false;
                      }
                      }
                      } else {
                      echo 'ERRO 2 (' . $salva_arq . ') ';
                      return false;
                      } */
                }
            }
            //echo nl2br(print_r($array_anexos, true));
            $array_anexos = relaciona_anexos($pasta, $pasta_f, $caixa_hash, $uid, $array_anexos, $emailMessage->attachments);
            //echo nl2br(print_r($array_anexos, true));
            foreach ($array_anexos as $key => $anexo) {
                if (empty($anexo['cid'])) {
                    $cid = 'N';
                } else {
                    $cid = $anexo['cid'];
                }

                $nome_aux = $anexo['similaridade'][2];

                //echo $cod_email . ', ' . $anexo['nome'] . ', ' . $cid . '<br>';
                if (!insere_anexo($cod_email, str_replace('/', '_', str_replace('?', '', utf8_decode(Utf8($anexo['nome'])))), $cid, $tr, str_replace('/', '_', str_replace('?', '', utf8_decode(Utf8($nome_aux)))))) {
                    echo 'ERRO 1';
                    return false;
                }
            }

            return true;
        } else {
            echo 'ERRO ao obter codigo';
            return false;
        }
        imap_close($caixaDeCorreio);
    } else {
        echo 'ERRO ao abrir conexão';
        return false;
    }
}

function relaciona_anexos($pasta, $pasta_f, $caixa_hash, $uid, $array_anexo, $attachments) {
    $array_anexo_ = $array_anexo;
    if (!is_dir($pasta_f)) {
        if (!is_dir($pasta)) {
            mkdir($pasta);
        }

        $pasta .= '/' . $caixa_hash;
        if (!is_dir($pasta)) {
            mkdir($pasta);
        }

        $pasta .= '/' . $uid;
        if (!is_dir($pasta)) {
            mkdir($pasta);
        }
    }


    foreach ($attachments as $key_obj => $object) {
        $temp_arq = $object['filename'];

        foreach ($array_anexo as $key => $anexo) {
            $break = false;


            $array_string = normaliza_string($anexo['nome'], $temp_arq);

            /*  $uniqueFilename = $array_string[0];
              $temp_arq = $array_string[1]; */

            $similaridade = $array_string[0];
            $by_pass = $array_string[1];

            if ($by_pass < 0) {
                array_multisort($similaridade);
                $aux_similaridade = $similaridade[0];
            } else {
                $aux_similaridade = $similaridade[$by_pass - 1];
                unset($array_anexo[$key]);
                $break = true;
            }

            if (!isset($anexo['similaridade'][0]) || $anexo['similaridade'][0] > $aux_similaridade[0]) {
                $anexo['similaridade'] = array($aux_similaridade[0], $key_obj, $aux_similaridade[2], $object);
            }

            $array_anexo[$key] = $anexo;
            $array_anexo_[$key] = $anexo;
            /* $uniqueFilename = $aux_similaridade[1];
              $temp_arq = $aux_similaridade[2];

              echo '<br>similaridade: ' . $aux_similaridade[0];
              echo ' $uniqueFilename: ' . $uniqueFilename;
              echo ' $temp_arq: ' . $temp_arq . '<br>'; */

            if ($break) {
                break;
            }
        }
    }

    //array_multisort($array_anexo);

    foreach ($array_anexo_ as $key => $anexo) {
        $temp_arq = str_replace('/', '_', str_replace('?', '', utf8_decode(Utf8($anexo['nome']))));
        $object = $anexo['similaridade'][3];
//echo $temp_arq.'<br>';

        if ($object['type'] == '2') {
            // echo print_r($object, true).'<br>';
            if (!file_exists($pasta_f . '/' . $temp_arq)) {
                /* echo $a . ' == ' . $b .'('.$temp_arq.')<br>';
                  print_r($object['data']);
                  echo '<br>'; */
                if (!empty($object['data']->bodyHTML)) {

                    $myfile = fopen($pasta_f . '/' . ($temp_arq), "w");
                    fwrite($myfile, $object['data']->bodyHTML);
                    fclose($myfile);

                    if (!file_exists($pasta_f . '/' . ($temp_arq))) {
                        echo $pasta_f . '/' . ($temp_arq) . ' não existe. ';
                        return false;
                    }

                    // echo 'Gravado (2):' . $pasta_f . '/' . $temp_arq . '<br>';
                    //break;
                }
            }
        } else if ($object['type'] == '0' || $object['type'] == '3' || $object['type'] == '4' || $object['type'] == '5' || $object['type'] == '6' || $object['type'] == '7' || $object['type'] == '8') {
            if (!file_exists($pasta_f . '/' . $temp_arq)) {
                if (file_put_contents($pasta_f . '/' . ($temp_arq), $object['data'])) {
                    // echo 'Gravado:' . $pasta_f . '/' . $temp_arq . '<br>';
                    //break;
                } else {
                    if ($object['data'] != null && !empty($object['data'])) {
                        echo 'Erro ao gravar :' . $pasta_f . '/' . $temp_arq;
                        return false;
                    } else if (!file_exists($pasta_f . '/' . ($temp_arq))) {
                        echo $pasta_f . '/' . ($temp_arq) . ' não existe. ';
                        return false;
                    }
                }
            }
        }
    }

    return $array_anexo_;
}

function imap_utf8_fix($string) {
    return iconv_mime_decode($string);
}

function flatMimeDecode($string) {
    $array = imap_mime_header_decode($string);
    $str = "";
    foreach ($array as $key => $part) {
        if ($part->charset == "UTF-8") {
            $str .= utf8_decode($part->text);
        } else {
            $str .= $part->text;
        }
    }
    return $str;
}

function normaliza_string_testes($string, $tipo) {
    $string = trim($string);
    if ($tipo == 1) {
        $string = mb_decode_mimeheader($string);
        $string = \MailSo\Base\Utils::ConvertEncoding($string, mb_detect_encoding($string), \MailSo\Base\Enumerations\Charset::WIN_1252);
    }
    if ($tipo == 2) {
        $string = \MailSo\Base\Utils::ConvertEncoding($string, mb_detect_encoding($string), \MailSo\Base\Enumerations\Charset::WIN_1252);
        $string = mb_decode_mimeheader($string);
    }

    if ($tipo == 3) {
        $string = mb_decode_mimeheader($string);
    }

    if ($tipo == 4) {
        $string = \MailSo\Base\Utils::ConvertEncoding($string, mb_detect_encoding($string), \MailSo\Base\Enumerations\Charset::WIN_1252);
    }
    if ($tipo == 5) {
        $string = iconv_mime_decode($string);
        $string = mb_decode_mimeheader($string);
        $string = \MailSo\Base\Utils::ConvertEncoding($string, mb_detect_encoding($string), \MailSo\Base\Enumerations\Charset::WIN_1252);
    }
    if ($tipo == 6) {
        $string = iconv_mime_decode($string);
        $string = \MailSo\Base\Utils::ConvertEncoding($string, mb_detect_encoding($string), \MailSo\Base\Enumerations\Charset::WIN_1252);
        $string = mb_decode_mimeheader($string);
    }

    if ($tipo == 7) {
        $string = iconv_mime_decode($string);
        $string = mb_decode_mimeheader($string);
    }

    if ($tipo == 8) {
        $string = iconv_mime_decode($string);
        $string = \MailSo\Base\Utils::ConvertEncoding($string, mb_detect_encoding($string), \MailSo\Base\Enumerations\Charset::WIN_1252);
    }

    if ($tipo == 9) {
        $string = iconv_mime_decode($string);
    }





    if ($tipo == 10) {
        $string = urldecode($string);
        $string = iconv_mime_decode($string);
        $string = mb_decode_mimeheader($string);
        $string = \MailSo\Base\Utils::ConvertEncoding($string, mb_detect_encoding($string), \MailSo\Base\Enumerations\Charset::WIN_1252);
    }
    if ($tipo == 11) {
        $string = urldecode($string);
        $string = iconv_mime_decode($string);
        $string = \MailSo\Base\Utils::ConvertEncoding($string, mb_detect_encoding($string), \MailSo\Base\Enumerations\Charset::WIN_1252);
        $string = mb_decode_mimeheader($string);
    }

    if ($tipo == 12) {
        $string = urldecode($string);
        $string = iconv_mime_decode($string);
        $string = mb_decode_mimeheader($string);
    }

    if ($tipo == 13) {
        $string = urldecode($string);
        $string = iconv_mime_decode($string);
        $string = \MailSo\Base\Utils::ConvertEncoding($string, mb_detect_encoding($string), \MailSo\Base\Enumerations\Charset::WIN_1252);
    }

    if ($tipo == 14) {
        $string = urldecode($string);
        $string = iconv_mime_decode($string);
    }

    return $string;
}

function normaliza_string($a, $b) {
    $similaridade = array();
    $by_pass = -1;
    $testes = array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 0);
    $string_a = $a;
    $string_b = $b;
    // echo '<br><br>$a:' . $a . '$b:' . $b . 'f:' . iconv_mime_decode($b) . ':<br>';
    for ($x = 0; $x < 3; $x++) {
        $para = false;

        for ($y = 0; $y < 3; $y++) {

            for ($i = 0; $i < count($testes); $i++) {
                for ($j = 0; $j < count($testes); $j++) {
                    $aux_similaridade = array();
                    $string_a = normaliza_string_testes($a, $testes[$i]);
                    $string_b = normaliza_string_testes($b, $testes[$j]);

                    if ($x == 1) {
                        $string_a = tirarAcentos($string_a);
                    }
                    if ($x == 2) {
                        $string_a = tirarAcentos_c($string_a);
                    }

                    if ($y == 1) {
                        $string_b = tirarAcentos($string_b);
                    }
                    if ($y == 2) {
                        $string_b = tirarAcentos_c($string_b);
                    }


                    // echo '(' . $x . ':' . $y . ':' . $i . ':' . $j . ')' . str_replace('_', ' ', $string_a) . '(' . $testes[$i] . ')' . '<br>' . str_replace('?', '', str_replace('_', ' ', $string_b)) . '(' . $testes[$j] . ')' . ':' . strcmp(str_replace('_', ' ', $string_a), str_replace('?', '', str_replace('_', ' ', $string_b))) . '<br><br>';
                    $comp = strcmp(str_replace('_', ' ', $string_a), str_replace('?', '', str_replace('_', ' ', $string_b)));

                    $aux_similaridade[0] = $comp;
                    $aux_similaridade[1] = str_replace('/', '_', $string_a);
                    $aux_similaridade[2] = str_replace('/', '_', $string_b);

                    //echo $aux_similaridade[0].'|'.str_replace('_', ' ', $string_a).'|'.str_replace('?', '', str_replace('_', ' ', $string_b)).'<br>';

                    if ($aux_similaridade[0] < 0) {
                        $aux_similaridade[0]*=-1;
                    }

                    $similaridade[] = $aux_similaridade;

                    if ($comp == 0) {
                        $by_pass = count($similaridade);
                        // echo 'FIM';
                        $para = true;
                        break;
                    }
                }
                if ($para) {
                    break;
                }
            }
            if ($para) {
                break;
            }
        }
        if ($para) {
            break;
        }
    }
    //echo '<br><br><br>';

    return array($similaridade, $by_pass);
}

function tirarAcentos_c($string) {
    return preg_replace(array("/(ç)/", "/(Ç)/", "/(á|à|ã|â|ä)/", "/(Á|À|Ã|Â|Ä)/", "/(é|è|ê|ë)/", "/(É|È|Ê|Ë)/", "/(í|ì|î|ï)/", "/(Í|Ì|Î|Ï)/", "/(ó|ò|õ|ô|ö)/", "/(Ó|Ò|Õ|Ô|Ö)/", "/(ú|ù|û|ü)/", "/(Ú|Ù|Û|Ü)/", "/(ñ)/", "/(Ñ)/"), explode(" ", "c C a A e E i I o O u U n N"), $string);
}

function tirarAcentos($string) {
    return preg_replace(array("/(á|à|ã|â|ä)/", "/(Á|À|Ã|Â|Ä)/", "/(é|è|ê|ë)/", "/(É|È|Ê|Ë)/", "/(í|ì|î|ï)/", "/(Í|Ì|Î|Ï)/", "/(ó|ò|õ|ô|ö)/", "/(Ó|Ò|Õ|Ô|Ö)/", "/(ú|ù|û|ü)/", "/(Ú|Ù|Û|Ü)/", "/(ñ)/", "/(Ñ)/"), explode(" ", "a A e E i I o O u U n N"), $string);
}

function grava_arquivo($pasta, $pasta_f, $caixa_hash, $uid, $attachments, $uniqueFilename, $obj_aux) {
    if (!is_dir($pasta_f)) {
        if (!is_dir($pasta)) {
            mkdir($pasta);
        }

        $pasta .= '/' . $caixa_hash;
        if (!is_dir($pasta)) {
            mkdir($pasta);
        }

        $pasta .= '/' . $uid;
        if (!is_dir($pasta)) {
            mkdir($pasta);
        }
    }

    $encontrou = false;
    $debug = '';


    foreach ($attachments as $object) {


        $temp_arq = ($object['filename']);

        $testes = array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 0);

        for ($x = 0; $x < 3; $x++) {
            $para = false;

            for ($i = 0; $i < count($testes); $i++) {
                for ($j = 0; $j < count($testes); $j++) {
                    $string_a = normaliza_string_testes($temp_arq, $testes[$i]);

                    if ($x == 1) {
                        $string_a = tirarAcentos($string_a);
                    }
                    if ($x == 2) {
                        $string_a = tirarAcentos_c($string_a);
                    }


                    //echo $string_a.'<br>------------------------------------------<br>';
                }
            }
        }


        //echo 'normaliza_string('.$uniqueFilename.', '.$temp_arq.');<br>'; 

        $array_string = normaliza_string($uniqueFilename, $temp_arq);
        $uniqueFilename = $array_string[0];
        $temp_arq = $array_string[1];
        $decode = $array_string[2];

        /* if (mb_detect_encoding($temp_arq) != 'UTF-8') {
          $temp_arq = \MailSo\Base\Utils::ConvertEncoding($temp_arq, \MailSo\Base\Enumerations\Charset::UTF_8, \MailSo\Base\Enumerations\Charset::WIN_1252);
          }
          if (strcmp(str_replace('_', ' ', $uniqueFilename), str_replace('?', '', str_replace('_', ' ', $temp_arq))) != 0) {
          $temp_arq = \MailSo\Base\Utils::ConvertEncoding($temp_arq, \MailSo\Base\Enumerations\Charset::UTF_8, \MailSo\Base\Enumerations\Charset::WIN_1252);
          } */

        $a = '';
        if (is_object($obj_aux) || is_array($obj_aux)) {
            $a = strtoupper(str_replace(array('message/', 'image/'), '', $obj_aux->GetBodyStructure()->ContentType()));
        }
        $b = strtoupper($object['subtype']);
        //$debug .= '<br>' . str_replace('_', ' ', $uniqueFilename) . ',' . str_replace('?', '', str_replace('_', ' ', $temp_arq));
        if ((strcmp(str_replace('_', ' ', $uniqueFilename), str_replace('?', '', str_replace('_', ' ', $temp_arq))) == 0 || empty($temp_arq))) {
            //if (empty($temp_arq) && !empty($uniqueFilename) && strcmp($a, $b) == 0) {
            $uniqueFilename = Utf8($uniqueFilename);
            //if ($decode) {
            $uniqueFilename = utf8_decode($uniqueFilename);
            // }
            $temp_arq = str_replace('?', '', $uniqueFilename);
            //}
            if (!empty($temp_arq)) {
                $encontrou = true;
                if ($object['type'] == '2') {
                    if (!file_exists($pasta_f . '/' . $temp_arq)) {
                        /* echo $a . ' == ' . $b .'('.$temp_arq.')<br>';
                          print_r($object['data']);
                          echo '<br>'; */
                        if (!empty($object['data']->bodyHTML)) {

                            $myfile = fopen($pasta_f . '/' . ($temp_arq), "w");
                            fwrite($myfile, $object['data']->bodyHTML);
                            fclose($myfile);

                            if (!file_exists($pasta_f . '/' . ($temp_arq))) {
                                echo $pasta_f . '/' . ($temp_arq) . ' não existe. ';
                                return false;
                            }

                            // echo 'Gravado (2):' . $pasta_f . '/' . $temp_arq . '<br>';
                            //break;
                        }
                    }
                } else if ($object['type'] == '0' || $object['type'] == '3' || $object['type'] == '4' || $object['type'] == '5' || $object['type'] == '6' || $object['type'] == '7' || $object['type'] == '8') {
                    if (!file_exists($pasta_f . '/' . $temp_arq)) {
                        if (file_put_contents($pasta_f . '/' . ($temp_arq), $object['data'])) {
                            // echo 'Gravado:' . $pasta_f . '/' . $temp_arq . '<br>';
                            break;
                        } else {
                            if ($object['data'] != null && !empty($object['data'])) {
                                echo 'Erro ao gravar :' . $pasta_f . '/' . $temp_arq;
                                return false;
                            } else if (!file_exists($pasta_f . '/' . ($temp_arq))) {
                                echo $pasta_f . '/' . ($temp_arq) . ' não existe. ';
                                return false;
                            }
                        }
                    }
                }
            } else {
                $debug = 'SAIU $temp_arq: ' . $temp_arq;
            }
        } else {
            $debug = 'SAIU STRCOMP: ' . str_replace('_', ' ', $uniqueFilename) . ',' . str_replace('?', '', str_replace('_', ' ', $temp_arq));
        }
    }

    if (empty($debug)) {
        $debug = 'Erro OBJ: ' . nl2br(print_r($attachments, true));
    }

    if ($encontrou) {
        return $uniqueFilename;
    } else {
        echo $uniqueFilename . ' Não encontrado! ' . $debug;
        return false;
    }
}

function insere_anexo($gen_BAIXAR_EMAIL_ID, $desc, $cid, $tr, $nome_aux) {
    //echo 'insert Anexo';
    $GEN_EMAILANEXO_ID = get_ultimo_gen_email_tr($tr, "EMAILANEXO_ID");
    $sql_anexo = "INSERT INTO emailanexo (codemailanexo, codbaixaremail, descanexo,cid)"
            . "VALUES(" . $GEN_EMAILANEXO_ID . "," . $gen_BAIXAR_EMAIL_ID . ",'" . str_replace("'", "''", $desc) . "','" . $cid . "')";
    $query_anexo = ibase_query($tr, $sql_anexo);
    if ($query_anexo) {
        return true;
    } else {
        echo $sql_anexo;
        return false;
    }
}

/*

  Monta extrutura do anexo de email para copiar para caixa de saida.

 */

function montaEnvioAnexo($arquivo, $nome, $boundary) {
    if (file_exists($arquivo)) {
        $myfile = fopen($arquivo, "r") or die("Unable to open file!");
        $attachment = chunk_split(base64_encode(fread($myfile, filesize($arquivo))));
        fclose($myfile);

        $ret = "--$boundary\r\n"
                . "Content-Type: application/octet-stream; name=\"" . $nome . "\"\r\n"
                . "Content-Transfer-Encoding: base64\r\n"
                . "Content-Disposition: attachment; filename=\"$nome\"\r\n"
                . "\r\n" . $attachment . "\r\n"
                . "\r\n\r\n\r\n";

        return $ret;
    } else {
        return "";
    }
}

/*

  Monta extrutura do email para copiar para caixa de saida.

 */

function monta_envio_copia($de, $para, $assunto, $msg, $boundary) {
    $dmy = date("d-M-Y H:i:s");
    $tmp = "From: " . $de . "\r\n"
            . "To: " . $para . "\r\n"
            . "Date: $dmy\r\n"
            . "Subject: " . utf8_decode($assunto) . "\r\n"
            . "MIME-Version: 1.0\r\n"
            . "Content-Type: multipart/mixed; boundary=\"$boundary\"\r\n"
            . "\r\n\r\n"
            . "--$boundary\r\n"
            . "Content-Type: text/html; charset=\"utf-8\"\r\n"
            . "Content-Transfer-Encoding: 8bit \r\n"
            . "\r\n\r\n"
            . $msg . "\r\n"
            . "\r\n\r\n";

    return $tmp;
}

function get_ultimo_gen_email_tr($tr, $tabela) {


    //SELECT GEN_ID(Gen_solicitacao, 0) FROM RDB$DATABASE;

    if ($tabela == "FOTOEDIFICIO") {
        $tabela = "FOTOEDFICIO";
    }

    $sql = "SELECT GEN_ID(Gen_" . $tabela . ", 1)"
            . " FROM RDB\$DATABASE";


    //echo $sql;

    $query = ibase_query($tr, $sql);

    $cod_gen = "";

    if ($query) {

        $reg = ibase_fetch_assoc($query);

        if ($reg) {

            $cod_gen = $reg['GEN_ID'];
            return $cod_gen;
        } else {

            return false;
        }
    } else {

        return false;
    }
}

/*

  Codifica e decodifica uma string para salvar no banco de forma compactada em sem caracteres especiais

 */

function _encode_string_array($stringArray) {
    $s = strtr(base64_encode(addslashes(gzcompress(serialize($stringArray), 1))), '+/=', '-_,');
    return $s;
}

function _decode_string_array($stringArray) {
    $s = unserialize(gzuncompress(stripslashes(base64_decode(strtr($stringArray, '-_,', '+/=')))));
    return $s;
}

/*

  Carrega caixas de todos os emails

 */

function carrega_caixas($cod_conta) {
    $sql = "SELECT * FROM  caixas WHERE codcontasbaixaremail = " . $cod_conta . " AND STATUS = 'A'";
    $query = ibase_query($sql);

    $caixas = array();


    if ($query) {
        while ($reg = ibase_fetch_assoc($query)) {
            $caixas[Utf8($reg['DESCCAIXAS'])] = $reg['CODCAIXAS'];
        }
        return $caixas;
    } else {
        return false;
    }
}

function carrega_caixas_hash($cod_conta) {
    $sql = "SELECT * FROM  caixas WHERE codcontasbaixaremail = " . $cod_conta . " AND STATUS = 'A'";
    $query = ibase_query($sql);

    $caixas = array();


    if ($query) {
        while ($reg = ibase_fetch_assoc($query)) {
            $caixas[$reg['HASH']] = $reg['CODCAIXAS'];
        }
        return $caixas;
    } else {
        return false;
    }
}

function carrega_caixas_cod($cod_conta) {
    $sql = "SELECT * FROM  caixas WHERE codcontasbaixaremail = " . $cod_conta . " AND STATUS = 'A'";
    $query = ibase_query($sql);

    $caixas = array();


    if ($query) {
        while ($reg = ibase_fetch_assoc($query)) {
            $caixas[$reg['CODCAIXAS']] = Utf8($reg['DESCCAIXAS']);
        }
        return $caixas;
    } else {
        return false;
    }
}

/*

  Insere nova caixa na tabela

 */

function insere_caixas_tr($caixa, $cod_conta, $tr) {
    $GEN_CAIXAS = get_ultimo_gen_email_tr($tr, "CAIXAS");
    $sql_insert_caixa = "INSERT INTO caixas (codcaixas,codcontasbaixaremail,desccaixas, hash, status) VALUES(" . $GEN_CAIXAS . "," . $cod_conta . ", '" . utf8_decode($caixa) . "', '" . md5($caixa) . "', 'A')";
    $query_insert_caixa = ibase_query($tr, $sql_insert_caixa);
    if ($query_insert_caixa) {
        return $GEN_CAIXAS;
    } else {
        return false;
    }
}

function insere_caixas($caixa, $cod_conta) {
    $GEN_CAIXAS = get_ultimo_gen_email("CAIXAS");
    $sql_insert_caixa = "INSERT INTO caixas (codcaixas,codcontasbaixaremail,desccaixas, hash, status) VALUES(" . $GEN_CAIXAS . "," . $cod_conta . ", '" . utf8_decode($caixa) . "', '" . md5($caixa) . "', 'A')";
    $query_insert_caixa = ibase_query($sql_insert_caixa);
    if ($query_insert_caixa) {
        return $GEN_CAIXAS;
    } else {
        return false;
    }
}

function move_arquivos($pasta_origem, $pasta_destino) {
    /* $pasta_origem = Utf8($pasta_origem);
      $pasta_destino = Utf8($pasta_destino); */
    copia_arquivos($pasta_origem, $pasta_destino);

    $array_pastas = array();

    $array_pastas = apaga_pasta($array_pastas, $pasta_origem);

    for ($i = (count($array_pastas) - 1); $i >= 0; $i--) {
        rmdir($array_pastas[$i]);
    }

    rmdir($pasta_origem);

    return true;
}

function copia_arquivos($pasta_origem, $pasta_destino) {

    if (!is_dir($pasta_destino)) {
        mkdir($pasta_destino);
    }

    $array_arquivos = array();

    $array_arquivos = le_pasta($array_arquivos, $pasta_origem, 0);
    rsort($array_arquivos);

    for ($i = 0; $i < count($array_arquivos); $i++) {
        $array_arquivos_nivel = $array_arquivos[$i];
        for ($j = 0; $j < count($array_arquivos_nivel); $j++) {
            $pasta_ex = explode('/', str_replace($pasta_origem . '/', '', $array_arquivos_nivel[$j]));
            $pasta_aux = '';
            for ($k = 0; $k < count($pasta_ex) - 1; $k++) {
                $pasta_aux .= '/' . $pasta_ex[$k];
                if (!is_dir($pasta_destino . $pasta_aux)) {
                    mkdir($pasta_destino . $pasta_aux);
                }
            }

            $pasta_aux = str_replace($pasta_origem, $pasta_destino, $array_arquivos_nivel[$j]);

            copy($array_arquivos_nivel[$j], $pasta_aux);
        }
    }

    array_reverse($array_arquivos);

    for ($i = 0; $i < count($array_arquivos); $i++) {
        $array_arquivos_nivel = $array_arquivos[$i];
        for ($j = 0; $j < count($array_arquivos_nivel); $j++) {
            unlink($array_arquivos_nivel[$j]);
        }
    }
}

function le_pasta($array_arquivos, $pasta, $nivel) {
    if ($handle = opendir($pasta)) {

        while (false !== ($file = readdir($handle))) {
            if ($file != '.' && $file != '..') {
                if (is_dir($pasta . '/' . $file)) {
                    $array_arquivos = le_pasta($array_arquivos, $pasta . '/' . $file, $nivel + 1);
                } else {
                    $array_arquivos[$nivel][] = $pasta . '/' . $file;
                }
            }
        }


        closedir($handle);
    } else {
        echo 'erro:' . $pasta;
    }

    return $array_arquivos;
}

function apaga_pasta($array, $pasta) {
    if ($handle = opendir($pasta)) {

        while (false !== ($file = readdir($handle))) {
            if ($file != '.' && $file != '..') {
                if (is_dir($pasta . '/' . $file)) {
                    $array[] = $pasta . '/' . $file;
                    $array = apaga_pasta($array, $pasta . '/' . $file);
                }
            }
        }


        closedir($handle);
    } else {
        echo 'E:' . $pasta;
        return false;
    }

    return $array;
}

function Anti_Injection($campo, $adicionaBarras = false) {
    // remove palavras que contenham sintaxe sql
    $campo = preg_replace("/(from|alter table|select|insert|delete|update|
								where|drop table|show tables|\|--|\\\\)/i", "", $campo);
    $campo = trim($campo); //limpa espaços vazio
    $campo = strip_tags($campo); //tira tags html e php
    if ($adicionaBarras || !get_magic_quotes_gpc())
        $campo = addslashes($campo); //Adiciona barras invertidas a uma string
    return $campo;
}

function get_ultimo_gen_email($tabela) {


    //SELECT GEN_ID(Gen_solicitacao, 0) FROM RDB$DATABASE;

    $bd_email = new BD_FB_EMAIL();
    $bd_email->open();

    if ($tabela == "FOTOEDIFICIO") {
        $tabela = "FOTOEDFICIO";
    }

    $sql = "SELECT GEN_ID(Gen_" . $tabela . ", 1)"
            . " FROM RDB\$DATABASE";


    //echo $sql;

    $query = ibase_query($sql);

    $cod_gen = "";

    if ($query) {

        $reg = ibase_fetch_assoc($query);

        if ($reg) {

            $cod_gen = $reg['GEN_ID'];
            return $cod_gen;
        } else {

            return false;
        }
    } else {

        return false;
    }

    $bd_email->close();
}

function Data_Firebird() {

    $timestamp = mktime(get_hora_fuso_horario(), date("i"), date("s"), date("m"), date("d"), date("Y"));
    $show = gmdate("d.m.Y", $timestamp);

    return $show;
}

function get_hora_fuso_horario() {
    if (date('I') == 1) {
        return (date("H") - 2);
    } else {
        return (date("H") - 3);
    }
}

function Utf8($texto) {
    $saida = '';

    $i = 0;
    $len = strlen($texto);
    while ($i < $len) {
        $char = $texto[$i++];
        $ord = ord($char);

        // Primeiro byte 0xxxxxxx: simbolo ascii possui 1 byte
        if (($ord & 0x80) == 0x00) {

            // Se e' um caractere de controle
            if (($ord >= 0 && $ord <= 31) || $ord == 127) {

                // Incluir se for: tab, retorno de carro ou quebra de linha
                if ($ord == 9 || $ord == 10 || $ord == 13) {
                    $saida .= $char;
                }

                // Simbolo ASCII
            } else {
                $saida .= $char;
            }

            // Primeiro byte 110xxxxx ou 1110xxxx ou 11110xxx: simbolo possui 2, 3 ou 4 bytes
        } else {

            // Determinar quantidade de bytes analisando os bits da esquerda para direita
            $bytes = 0;
            for ($b = 7; $b >= 0; $b--) {
                $bit = $ord & (1 << $b);
                if ($bit) {
                    $bytes += 1;
                } else {
                    break;
                }
            }

            switch ($bytes) {
                case 2: // 110xxxxx 10xxxxxx
                case 3: // 1110xxxx 10xxxxxx 10xxxxxx
                case 4: // 11110xxx 10xxxxxx 10xxxxxx 10xxxxxx
                    $valido = true;
                    $saida_padrao = $char;
                    $i_inicial = $i;
                    for ($b = 1; $b < $bytes; $b++) {
                        if (!isset($texto[$i])) {
                            $valido = false;
                            break;
                        }
                        $char_extra = $texto[$i++];
                        $ord_extra = ord($char_extra);

                        if (($ord_extra & 0xC0) == 0x80) {
                            $saida_padrao .= $char_extra;
                        } else {
                            $valido = false;
                            break;
                        }
                    }
                    if ($valido) {
                        $saida .= $saida_padrao;
                    } else {
                        $saida .= ($ord < 0x7F || $ord > 0x9F) ? utf8_encode($char) : '';
                        $i = $i_inicial;
                    }
                    break;
                case 1:  // 10xxxxxx: ISO-8859-1
                default: // 11111xxx: ISO-8859-1
                    $saida .= ($ord < 0x7F || $ord > 0x9F) ? utf8_encode($char) : '';
                    break;
            }
        }
    }
    return $saida;
}

function Data_BR($data) {

    $d = explode("-", $data);
    $data = $d[2] . "/" . $d[1] . "/" . $d[0];

    return $data;
}

//Com pontos
function Data_Hora_Firebird() {

    $timestamp = mktime(get_hora_fuso_horario(), date("i"), date("s"), date("m"), date("d"), date("Y"));
    $show = gmdate("d.m.Y H:i:s", $timestamp);

    return $show;
}

/*

  Gera uma seguencia de caracteres aleatórios

 */

function geraSenha($tamanho = 8, $maiusculas = true, $numeros = true, $simbolos = false) {
    $lmin = 'abcdefghijklmnopqrstuvwxyz';
    $lmai = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $num = '1234567890';
    $simb = '!@#$%*-';
    $retorno = '';
    $caracteres = '';
    $caracteres .= $lmin;
    if ($maiusculas)
        $caracteres .= $lmai;
    if ($numeros)
        $caracteres .= $num;
    if ($simbolos)
        $caracteres .= $simb;
    $len = strlen($caracteres);
    for ($n = 1; $n <= $tamanho; $n++) {
        $rand = mt_rand(1, $len);
        $retorno .= $caracteres[$rand - 1];
    }
    return $retorno;
}

/**
 * Turn all URLs in clickable links.
 * 
 * @param string $value
 * @param array  $protocols  http/https, ftp, mail, twitter
 * @param array  $attributes
 * @param string $mode       normal or all
 * @return string
 */
function linkify($value, $protocols = array('http', 'mail'), array $attributes = array(), $mode = 'normal') {
    // Link attributes
    $attr = '';
    foreach ($attributes as $key => $val) {
        $attr = ' ' . $key . '="' . htmlentities($val) . '"';
    }

    $links = array();

    // Extract existing links and tags
    $value = preg_replace_callback('~(<a .*?>.*?</a>|<.*?>)~i', function ($match) use (&$links) {
        return '<' . array_push($links, $match[1]) . '>';
    }, $value);

    // Extract text links for each protocol
    foreach ((array) $protocols as $protocol) {
        switch ($protocol) {
            case 'http':
            case 'https': $value = preg_replace_callback($mode != 'all' ? '~(?:(https?)://([^\s<]+)|(www\.[^\s<]+?\.[^\s<]+))(?<![\.,:])~i' : '~([^\s<]+\.[^\s<]+)(?<![\.,:])~i', function ($match) use ($protocol, &$links, $attr) {
                    if ($match[1])
                        $protocol = $match[1];
                    $link = $match[2] ? : $match[3];
                    return '<' . array_push($links, '<a' . $attr . ' target="_blank" href="' . $protocol . '://' . $link . '">' . $link . '</a>') . '>';
                }, $value);
                break;
            case 'mail': $value = preg_replace_callback('~([^\s<]+?@[^\s<]+?\.[^\s<]+)(?<![\.,:])~', function ($match) use (&$links, $attr) {
                    return '<' . array_push($links, '<a' . $attr . ' target="_blank" href="mailto:' . $match[1] . '">' . $match[1] . '</a>') . '>';
                }, $value);
                break;
            case 'twitter': $value = preg_replace_callback('~(?<!\w)[@#](\w++)~', function ($match) use (&$links, $attr) {
                    return '<' . array_push($links, '<a' . $attr . ' target="_blank" href="https://twitter.com/' . ($match[0][0] == '@' ? '' : 'search/%23') . $match[1] . '">' . $match[0] . '</a>') . '>';
                }, $value);
                break;
            default: $value = preg_replace_callback($mode != 'all' ? '~' . preg_quote($protocol, '~') . '://([^\s<]+?)(?<![\.,:])~i' : '~([^\s<]+)(?<![\.,:])~i', function ($match) use ($protocol, &$links, $attr) {
                    return '<' . array_push($links, '<a' . $attr . ' target="_blank" href="' . $protocol . '://' . $match[1] . '">' . $match[1] . '</a>') . '>';
                }, $value);
                break;
        }
    }

    // Insert all link
    return preg_replace_callback('/<(\d+)>/', function ($match) use (&$links) {
        return $links[$match[1] - 1];
    }, $value);
}
