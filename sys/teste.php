<?php

$time_ret = '';
$time = microtime(true);
set_time_limit(120);

header('Content-Type: text/html; charset=utf-8');


mb_internal_encoding('UTF-8');
mb_http_output('UTF-8');
mb_http_input('UTF-8');
mb_regex_encoding('UTF-8');

if (!isset($_SESSION))
    session_start();



    $caixa = 'INBOX';

    $id = 1;
    $pagina = 1;
    if (empty($pagina)) {
        $pagina = 1;
    } else {
        $pagina = intval($pagina);
    }

    $pesquisa = '';
    if (isset($_POST['pesquisa'])) {
        if (!empty($_POST['pesquisa'])) {
            $pesquisa = utf8_decode(str_replace("'", "''", Anti_Injection($_POST['pesquisa'])));
        }
    }
    if (isset($_POST['itens_pag'])) {
        $itens_pag = Anti_Injection($_POST['itens_pag']);
        if (empty($itens_pag)) {
            $itens_pag = intval($_SESSION['itens_pag']);
        } else {
            $itens_pag = intval($itens_pag);
            $_SESSION['itens_pag'] = $itens_pag;
        }
    } else {
        $itens_pag = 10;
    }

    $skip = "";
    if ($pagina > 1) {
        $skip = "SKIP " . (($pagina - 1) * $itens_pag);
    }

    $where = '';

    if (!empty($pesquisa)) {
        $where = " AND (de like '%" . $pesquisa . "%' OR denome like '%" . $pesquisa . "%' OR para like '%" . $pesquisa . "%' OR paranome like '%" . $pesquisa . "%' OR copia like '%" . $pesquisa . "%' OR copianome like '%" . $pesquisa . "%' OR assunto like '%" . $pesquisa . "%' OR mensagem like '%" . $pesquisa . "%' )";
    }

    $sql = "SELECT FIRST " . $itens_pag . " " . $skip . " be.*, c.desccaixas, (SELECT first 1 ea.codemailanexo FROM emailanexo ea WHERE cid = 'N' AND ea.codbaixaremail = be.codbaixaremail) as anexo FROM baixar_email be 
        INNER JOIN caixas c ON (be.caixa = c.codcaixas AND c.hash = '" . md5($caixa) . "')
            WHERE be.codcontasbaixaremail = " . $id . " AND be.status <> 'E' " . $where . " ORDER BY be.uid desc, be.data_formatada DESC ";
    $sql_count = "SELECT COUNT(be.codbaixaremail) AS total FROM baixar_email be 
                  INNER JOIN caixas c ON (be.caixa = c.codcaixas AND c.hash = '" . md5($caixa) . "')
                  WHERE be.codcontasbaixaremail = " . $id . " AND be.status <> 'E' " . $where;

    $ac = "DE";

    if ($caixa == "INBOX.enviadas") {
        $ac = "PARA";
    }

    $bd = new BD_FB_EMAIL();
    $bd->open();
    $query = ibase_query($sql);
    $query_count = ibase_query($sql_count);


    if ($query && $query_count) {
        $tb = '
           <div id="tb_msgs" style="min-width: 1000px; /*max-width: 1000px;*/ border: 1px solid #bbd3da; padding: 10px 10px 40px 10px;  background-color: #fff; border-radius: 8px;overflow-y: scroll;max-height: 89%;">
            <table id="tb_mensagens" style="min-width: 1000px;table-layout: fixed; /*max-width: 1000px;*/">
                <thead class="cabecalho_tb">
                    <tr>
                        <th style="border: 1px solid #333; padding: 3px 1px 1px 1px;'.gera_style("chk").'"><input type="checkbox" name="seleciona_todos_emails" id="seleciona_todos_emails"></th>
                        <th style="border-top: 1px solid #333; border-right: 1px solid #333; border-bottom: 1px solid #333; padding: 5px 5px 5px 5px;'.gera_style("de").'">' . $ac . '</th>
                        <th style="border-top: 1px solid #333; border-right: 1px solid #333; border-bottom: 1px solid #333; padding: 5px 5px 5px 5px;'.gera_style("assunto").'">Assunto</th>
                        <th style="border-top: 1px solid #333; border-right: 1px solid #333; border-bottom: 1px solid #333; padding: 4px 5px 4px 5px;'.gera_style("anexo").'"><img src="' . DIR_siga_img . 'email/anexo.svg" style="height:22px; width:22px;"></th>
                        <th style="border-top: 1px solid #333; border-right: 1px solid #333; border-bottom: 1px solid #333; padding: 5px 5px 5px 5px;'.gera_style("data").'">Data</th>                            
                        <th style="border-top: 1px solid #333; border-right: 1px solid #333; border-bottom: 1px solid #333; padding: 4px 5px 4px 5px;'.gera_style("tamanho").'">Tamanho</th>
                        <th style="border-top: 1px solid #333; border-right: 1px solid #333; border-bottom: 1px solid #333; padding: 4px 5px 4px 5px;'.gera_style("flag").'"><img src="' . DIR_siga_img . 'email/flag_header.svg" style="height:22px; width:22px;"></th>                        
                        <th style="border-top: 1px solid #333; border-right: 1px solid #333; border-bottom: 1px solid #333; padding: 5px 5px 5px 5px;'.gera_style("copia").'">Cópia</th>
                    </tr>
                <tbody id="columns">';


        $grid = '';

        $reg_count = ibase_fetch_assoc($query_count);

        while ($reg = ibase_fetch_assoc($query, IBASE_TEXT)) {
            $grid .= monta_mensagem_off($reg, $ac);
        }

        $tb .= $grid . '</tbody></table>';


        $total_emails = intval($reg_count['TOTAL']);

        $tb .= '<table class="table_procurase" style="margin-top:10px;"><tbody><tr><td><ul class="navegacao">';
        if ($pagina > 1) {
            $tb .= '<li><a href="#" id="1" class="primeira_pagina" title="Primeira página"></a></li>';
            $tb .= '<li><a href="#" id="' . ($pagina - 1) . '" class="pagina_anterior" title="Página anterior"></a></li>';
        } else {
            $tb .= '<li><a class="primeira_pagina_inativa" title="Primeira página"></a></li>';
            $tb .= '<li><a class="pagina_anterior_inativa" title="Página anterior"></a></li>';
        }

        $tb .= monta_link_pag($itens_pag, $total_emails, $pagina);
        $v_pag = ceil($total_emails / $itens_pag);
        if (($pagina * $itens_pag) < $total_emails) {
            $tb .= ' <li><a href="#" id="' . ($pagina + 1) . '" class="proxima_pagina" title="Próxima página"></a></li>
                 <li><a href="#" id="' . $v_pag . '" class="ultima_pagina" title="Última página"></a></li>';
        } else {
            $tb .= ' <li><a class="proxima_pagina_inativa" title="Próxima página"></a></li>
                 <li><a class="ultima_pagina_inativa" title="Última página"></a></li>';
        }

        $select_10 = '';
        $select_20 = '';
        $select_50 = '';

        if ($itens_pag == 10) {
            $select_10 = 'selected="selected"';
        } else if ($itens_pag == 20) {
            $select_20 = 'selected="selected"';
        } else if ($itens_pag == 50) {
            $select_50 = 'selected="selected"';
        }


        $tb .= '<div id="paginacao_texto" style="margin-top:10px;">Resultados por página
                                            <select id="num_paginas">
                                                <option value="10" ' . $select_10 . '>10</option>
                                                <option value="20" ' . $select_20 . '>20</option>
                                                <option value="50" ' . $select_50 . '>50</option>
                                            </select>
                                            <span id="texto_pag">
                                                <label id="total_emails_count">' . $total_emails . '</label> Emails&nbsp;&nbsp;&nbsp;-&nbsp;&nbsp;&nbsp;<label id="total_paginas_count">' . $v_pag . '</label> páginas</span>
                                        <a href="#" id="' . base64_encode(utf8_decode($caixa)) . '" class="btn_atualizar_pasta"><img title="Atualizar" src="' . DIR_siga_img . 'email/update.svg" style="width: 20px;"/></a></div></table>';

        $tb .= '</div>';

        /* $time_ret .= '(F) ' . round((microtime(true) - $time), 2) . 's' . "\n";
          if ($id == '2') {
          $tb .= $time_ret;
          } */

        echo $tb . '##CONTADOR##' . $total_emails;
    } else {
        if (!$query) {
            echo'erro|' . $sql;
        }
        if (!$query_count) {
            echo'erro|' . $sql_count;
        }
    }

    $bd->close();


function monta_link_pag($link_pag, $total, $pag) {

    $max = ceil($total / $link_pag);

    if ($max < 24) {
        $qtn = $max;
    } else {
        $qtn = 24;
    }

    $limite = $qtn + $pag;
    $link = "";

    if ($max > $limite) {
        $max_m = $limite;
    } else {
        $max_m = $max;

        if ((intval($max - ($qtn + 1)) > 1 || ($max < 24 && $pag > 1)) && (intval($pag) > intval($max - ($qtn + 1)))) {

            for ($i = (($max - $qtn) + 1); $i < $pag; $i++) {
                $link .= '<li><a href="#" id="' . $i . '" class="num_paginas">' . $i . '</a></li>';
            }
        }
    }
    $link .= '<li class="active">' . $pag . '</li>';
    for ($i = $pag + 1; $i <= $max_m; $i++) {
        $link .= '<li><a href="#" id="' . $i . '" class="num_paginas">' . $i . '</a></li>';
    }

    if ($max - 1 > $limite) {
        $link .= '<li><a href="#" id="' . $i . '" class="num_paginas_desat">...</a></li>';
    }

    return $link;
}
