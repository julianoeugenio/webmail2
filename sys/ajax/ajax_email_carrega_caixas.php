<?php

set_time_limit(240);
ini_set('max_execution_time', 240);



include DIR_classes . 'MailSo/MailSo.php';

set_time_limit(240);
ini_set('max_execution_time', 240);
if (!empty($_POST['id']) && !empty($_POST['offline'])) {
    set_time_limit(240);
    ini_set('max_execution_time', 240);
    $id = Anti_Injection($_POST['id']);
    $edicao = Anti_Injection($_POST['edicao']);
    $offline = Anti_Injection($_POST['offline']);
    set_time_limit(240);
    ini_set('max_execution_time', 240);
    monta_caixas($id, $edicao, $offline);
}

function monta_caixas($id, $edicao, $offline) {
    set_time_limit(240);
    ini_set('max_execution_time', 240);
    $bd = new BD_FB_EMAIL();
    $bd->open();

    $conta = getEmailSenha($id);

    $bd->close();

    $email = $conta['EMAIL'];
    $senha = base64_decode($conta['SENHA']);
    $servidor = explode('@', $email);
    $servidor = 'pop.' . $servidor[1];

    /* echo $email."\n";
      echo $senha."\n";
      echo $servidor."\n"; */

    $oData = null;

    if (!empty($email) && !empty($senha) && !empty($servidor)) {

        if ($offline == 'N') {
            try {
                $oMailClient = \MailSo\Mail\MailClient::NewInstance();

                $conn = $oMailClient
                        ->Connect($servidor, 143, \MailSo\Net\Enumerations\ConnectionSecurityType::NONE)
                        ->Login($email, $senha);
                $oData = $conn->Folders();
            } catch (Exception $e) {
                var_dump($e);
            }
        }
        set_time_limit(240);
        ini_set('max_execution_time', 240);
        //$tb_caixa = '<!--<a href="#" id="voltar_inicio" class="btn_voltar"><strong>Voltar</a><br />--><table style=""><div class="tb_caixas">';
        $tb_caixa = '<table style=""><div class="tb_caixas">';
        $tb_caixa .= "<ul class='listing'>";

        $tb_caixa_comp = $tb_caixa;

        if ($offline == 'S') {

            $tb_caixa = carrega_dados($id, $tb_caixa, $edicao);
            if (empty($tb_caixa) || $tb_caixa == $tb_caixa_comp) {
                monta_caixas($id, $edicao, 'N');
                return false;
            }
        } else {
            $tb_caixa = processa_sub_pastas($oData, $tb_caixa, '', 1, $edicao);
            $oMailClient->LogoutAndDisconnect();
        }
        $tb_caixa .= '</ul></div>';

        echo 'ok|' . $tb_caixa;
        if ($edicao == 'S') {
            echo '<div style="position:fixed;bottom:10px;left: 175px;cursor:pointer;" id="gerencia_caixa_edicao" class="aberto"><img src="' . URL . 'img/siga/icon_menos.png" /></div>';
        } else {
            echo '<div style="position:fixed;bottom:16px;left: 175px;cursor:pointer;" id="gerencia_caixa_edicao"><img src="' . URL . 'img/siga/grid_bt_engrenagem.png" /></div>';
        }

        echo '<div style="position:fixed;bottom:10px;left: 200px;cursor:pointer;" id="gerencia_caixa"><img src="' . URL . 'img/siga/icon_mais.png" /></div>';
    }
}

function carrega_dados($id, $tb_caixa, $edicao) {
    set_time_limit(240);
    ini_set('max_execution_time', 240);
    $bd = new BD_FB_EMAIL();
    $bd->open();
    $sql = 'SELECT * FROM caixas WHERE CODCONTASBAIXAREMAIL = ' . $id . " AND status <> 'E' ORDER BY codcaixas ASC";
    $query = ibase_query($sql);
    if ($query) {
        $obj = array();
        while ($reg = ibase_fetch_assoc($query)) {
            $reg = processa_pai($reg);
            $obj[count($obj)] = $reg;
        }

        $obj = processa_se_tem_filho($obj);
        // echo nl2br(print_r($obj[7], 1));
        $tb_caixa = processa_sub_pastas_offline($obj, $tb_caixa, $edicao);
    } else {
        echo 'erro SQL: ' . $sql;
        $bd->close();
        exit();
    }
    $bd->close();

    return $tb_caixa;
}

function processa_sub_pastas_offline($obj, $tb_caixa, $edicao) {
    set_time_limit(240);
    ini_set('max_execution_time', 240);
    for ($i = 0; $i < count($obj); $i++) {
        $reg = $obj[$i];
        $pai = $reg['PAI'];
        if ($pai == 'INBOX') {
            $pai = '';
        }
        $tb_caixa .= processa_caixa(utf8_encode($reg['DESCCAIXAS']), $reg['TEM_FILHO'], $pai, $reg['NIVEL'], $edicao);
    }

    return $tb_caixa;
}

function processa_sub_pastas($obj, $tb_caixa, $pai, $nivel, $edicao) {
    set_time_limit(240);
    ini_set('max_execution_time', 240);
    if ($pai == 'INBOX') {
        $pai = '';
    }
    if (is_object($obj) || is_array($obj)) {
        if ($obj->Count() > 0) {
            for ($i = 0; $i < $obj->Count(); $i++) {
                $sub_obj = $obj->GetByIndex($i)->SubFolders();
                $tem_filho = false;
                if (is_object($sub_obj) || is_array($sub_obj)) {
                    if ($sub_obj->Count() > 0) {
                        $tem_filho = true;
                    }
                }
                $nivel_aux = $nivel;
                if ($nivel <= 2) {
                    $nivel_aux = 1;
                }
                $tb_caixa .= processa_caixa($obj->GetByIndex($i)->FullName(), $tem_filho, $pai, $nivel_aux, $edicao);
                $tb_caixa = processa_sub_pastas($sub_obj, $tb_caixa, $obj->GetByIndex($i)->FullName(), $nivel + 1, $edicao);
            }

            return $tb_caixa;
        } else {
            return $tb_caixa;
        }
    } else {
        return $tb_caixa;
    }
}

function processa_caixa($caixa, $tem_sub, $pai, $nivel, $edicao) {
    set_time_limit(240);
    ini_set('max_execution_time', 240);
    $caixa_nome = Utf8($caixa);
    $caixa_md5 = (md5($caixa));
    $pai_md5 = (md5($pai));
    $caixa = strtolower($caixa_nome);


    if ($caixa != 'inbox') {
        $caixa_ex = explode('inbox.', $caixa);
        if (count($caixa_ex) > 1) {
            $caixa = $caixa_ex[1];
        }
    }

    $img = "";
    if ($caixa == 'inbox') {
        $img = DIR_siga_img . 'email/inbox.svg';
    } else if ($caixa == 'enviadas') {
        $img = DIR_siga_img . 'email/enviadas.svg';
    } else if ($caixa == 'lixo') {
        $img = DIR_siga_img . 'email/lixo.svg';
    } else if ($caixa == 'rascunho') {
        $img = DIR_siga_img . 'email/rascunho.svg';
    } else {
        if ($tem_sub) {
            $img = DIR_siga_img . 'email/pastaadd.svg';
        } else {
            $img = DIR_siga_img . 'email/pasta.svg';
        }
    }

    $caixa_ex = explode('.', $caixa);
    if (count($caixa_ex) > 0) {
        $caixa = $caixa_ex[count($caixa_ex) - 1];
    }
    $class_sub = '';
    if ($tem_sub) {
        $class_sub = 'tem_sub';
    }
    if (!empty($pai)) {
        $html_caixa = "<li><a href='#' id='" . $caixa_md5 . "' nome='" . $caixa_nome . "' class='listing_link drag_caixas hide filha_" . $pai_md5 . " " . $class_sub . "' style='background-image: url(" . $img . "); background-repeat: no-repeat; background-size: 20px; margin-left:" . $nivel * 6 . "px'>";
    } else {
        $html_caixa = "<li><a href='#' id='" . $caixa_md5 . "' nome='" . $caixa_nome . "' class='listing_link drag_caixas " . $class_sub . "' style='background-image: url(" . $img . "); background-repeat: no-repeat; background-size: 20px;'>";
    }
    $edicao_bloqueada = array('INBOX', 'INBOX.Mala_Direta', 'INBOX.enviadas', 'INBOX.lixo', 'INBOX.rascunho');
    if ($edicao == 'S' && !in_array($caixa_nome, $edicao_bloqueada)) {
        $html_caixa .= "<input id='input_edicao_caixa_" . $caixa_md5 . "' type='text' style='height: 10px;margin: 0 0 0 0;padding: 1px 3px 4px 5px;border: 1px solid #ccc;font-size: 12px;color: #777;display: inline-block;width:125px;float:left;' value='" . $caixa . "'/>";
        $html_caixa .= "<div style='float:left;cursor:pointer;' id='salva_edicao_caixa_" . $caixa_md5 . "' class='salva_caixa'><img src='" . URL . "img/siga/grid_bt_finalizar.png' /></div>";

        $html_caixa .= "<div style='float:left;cursor:pointer;' id='exclui_edicao_caixa_" . $caixa_md5 . "' class='remove_caixa'><img style='margin-left:10px;' src='" . URL . "img/siga/grid_bt_del.png' /></div>";
    } else {
        $html_caixa .= $caixa;
    }

    $html_caixa .= '<img class="carregando" id="carregando_' . $caixa_md5 . '" style="display:none;" src="' . URL . 'img/load_foto_tem.gif" />' . '<img class="finalizado" id="finalizado_' . $caixa_md5 . '" style="display:none;"  src="' . URL . 'img/siga/bt_ativar.png" /></a></li>';

    return $html_caixa;
}

function processa_se_tem_filho($obj) {
    set_time_limit(240);
    ini_set('max_execution_time', 240);
    for ($i = 0; $i < count($obj); $i++) {
        $reg = $obj[$i];
        $reg['TEM_FILHO'] = false;

        for ($j = 0; $j < count($obj); $j++) {
            $reg_filho = $obj[$j];
            if (strcmp($reg_filho['PAI'], Utf8($reg['DESCCAIXAS'])) == 0) {
                $reg['TEM_FILHO'] = true;
                $j = $j < count($obj);
            }
        }
        $obj[$i] = $reg;
    }
    return $obj;
}

function processa_pai($reg) {
    set_time_limit(240);
    ini_set('max_execution_time', 240);
    $nome = Utf8($reg['DESCCAIXAS']);
    $nome_ex = explode('.', $nome);
    $pai = '';
    $reg['NIVEL'] = 1;
    if (count($nome_ex) > 1) {
        for ($i = 0; $i < count($nome_ex) - 1; $i++) {
            if (empty($pai)) {
                $pai .= $nome_ex[$i];
            } else {
                $pai .= '.' . $nome_ex[$i];
            }
        }
        $reg['NIVEL'] = count($nome_ex) - 1;
    }
    //$reg['DESCCAIXAS'] = $nome;
    $reg['PAI'] = $pai;

    return $reg;
}
