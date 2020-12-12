<?php

set_time_limit(120);


    $caixa = 'INBOX.lixo';

    $id = 1;
    $uid = 120220;

    echo Carrega_email_banco($id, $caixa, $uid);




/*

  Carrega mensagem formatada direto da web

 */

function Carrega_email_banco($id, $caixa, $uid) {
    set_time_limit(600);

    $bd = new BD_FB_EMAIL();
    $bd->open();

    $sql = "SELECT be.*, c.desccaixas FROM baixar_email be 
            INNER JOIN caixas c ON (be.caixa = c.codcaixas AND c.hash = '" . md5($caixa) . "')
            WHERE be.codcontasbaixaremail = " . $id . " AND be.status <> 'E' AND be.uid = " . $uid;

    $query = ibase_query($sql);

    if ($query) {
        if ($reg = ibase_fetch_assoc($query, IBASE_TEXT)) {
            $lida = false;
            if ($reg['LIDO'] == 'S') {
                $lida = true;
            }
            if (!$lida) {
                $sql_update = "UPDATE baixar_email SET lido='S' WHERE CODBAIXAREMAIL = " . $reg['CODBAIXAREMAIL'];
                $query_update = ibase_query($sql_update);
                if (!$query_update) {
                    echo 'erro: SQL UPDATE: ' . $sql_update;
                }
            }

            //$msg_email = linkify(Utf8(_decode_string_array($reg['MENSAGEM'])));
            $msg_email = (Utf8(($reg['MENSAGEM'])));
            $assunto = '(sem assunto)';
            if (!empty($reg['ASSUNTO'])) {
                $assunto = Utf8($reg['ASSUNTO']);
            }

            $de = Utf8($reg['DE']);
            $para = Utf8($reg['PARA']);

            $data = Data_Hora_BR($reg['DATA']);

            $ret_ec = '<div id="div_msg_header" '
                    . 'style="width: calc(96vw - 19px);min-width: fit-content;overflow-x: scroll;position: relative; height: auto; min-height: 52px; padding: 10px 0 0 20px;     background: -webkit-gradient(linear,left top,left bottom,color-stop(0%,#fff),color-stop(100%,#f0f0f0)); border: 1px solid #f0f0f0; border-radius: 8px;"> '
                    . '<div style="float: left;"><b style="color: #666;"> De: </b>' . $de . '<br /><b style="color: #666;"> Para: </b>' . $para . '<br /><b style="color: #666;"> Assunto: </b>' . $assunto . '<br /><b style="color: #666;"> Data: </b>' . $data . '</div>';


            $anexo_src = '';
            $anexo_sz = 0;

            $sql_anexo = "SELECT DESCANEXO, cid FROM emailanexo WHERE codbaixaremail = " . $reg['CODBAIXAREMAIL'];
            $query_anexo = ibase_query($sql_anexo);
            $count_anexo = 0;

            $cids = array();
            while ($reg_anexo = ibase_fetch_assoc($query_anexo)) {
                $pasta = DIR_anexos_email . $id . '/' . md5($caixa) . '/' . $uid . "/" . Utf8($reg_anexo['DESCANEXO']);
                if ($reg_anexo['CID'] == 'N') {
                    if ($count_anexo == 0) {
                        $anexo_src .= '<div style="float:left; margin-left:20px; margin-right:15px;max-width:80%;"> ANEXOS:';
                        $anexo_src .= '<a href="' . $pasta . '" download>' . Utf8($reg_anexo['DESCANEXO']) . '</a>';
                    } else {
                        $anexo_src .= '&nbsp&nbsp<a href="' . $pasta . '" download>' . Utf8($reg_anexo['DESCANEXO']) . '</a>';
                    }
                    $anexo_sz += strlen($reg_anexo['DESCANEXO']);
                    if ($anexo_sz > 80) {
                        $anexo_src .= '<br>';
                        $anexo_sz = 0;
                    }
                    $count_anexo++;

                    if ($count_anexo > 0) {
                        $anexo_src .= '</div>';
                    }
                } else {
                    $cids[$reg_anexo['CID']] = $pasta;
                }
            }

            preg_match_all('/src="cid:(.*)"/Uims', $msg_email, $matches);


            foreach ($cids as $cid => $pasta) {
                foreach ($matches[1] as $match) {
                    if ($cid == $match) {
                        $search[] = "src=\"cid:$match\"";
                        $replace[] = "src=\"$pasta\"";
                        $msg_email = str_replace($search, $replace, $msg_email);
                    }
                }
            }

            $ret_ec .= '</div><br><br>';

            return '<div id="tb_mensagem" style="height: 100%;min-width: 1000px; /*max-width: 1000px;*/ border: 1px solid #bbd3da; padding: 10px 10px 40px 10px;  background-color: #fff; border-radius: 8px;overflow-x: scroll;">' . $ret_ec . '<div style="clear:both;font-size:12px;">' . ($msg_email) . '</div>' . '<br><br><br>' . $anexo_src . '</div>';
        } else {
            return 'Erro ao carregar dados.';
        }
    } else {
        return 'Erro SQL: ' . $sql;
    }


    $bd->close();
}

?>