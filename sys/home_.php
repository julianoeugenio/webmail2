<?php
require(DIR_siga . "verifica_sessao.php");

if (!isset($_SESSION))
    session_start();

/*
 * Verifica permissão
 * **************************************************************************
 */
$cod_usuario = Siga_Usuario_Show_Cod_Usuario();
//permissão 823
$ver_permissao823 = Verifica_Permissao_Contador($cod_usuario, $cod_usuario, 823, 0, "");
$ver_permissao823 = explode("|", $ver_permissao823);

if ($ver_permissao823[0] == "ok" || $cod_usuario == 1025 || true) {

    $permissao_acesso = "";
} else {

    $permissao_acesso = $ver_permissao823[1];
    $msg_erro = msg_Erro($permissao_acesso);
}
?>
<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8" />
        <title>Email</title>
        <?php
        require(DIR_siga_inc . "meta_tag.php");
        require(DIR_siga_inc . "header.php");
        ?>

        <script src="<?php echo DIR_siga_js ?>util/util.js"></script>
        <style>
            /* NOTE: The styles were added inline because Prefixfree needs access to your styles and they must be inlined if they are on local disk! */
            @import url("<?php echo DIR_siga_css ?>email/Open+Sans+Condensed.css");

            body {
                font-family: "Open Sans Condensed", sans-serif;
            }

            #bg {
                -webkit-filter: blur(5px);    
            }

            #div_form form {
                position: relative;
                width: 250px;
                margin: 0 auto;
                background: rgba(130,130,130,.3);
                padding: 20px 22px;
                border: 1px solid;
                border-top-color: rgba(255,255,255,.4);
                border-left-color: rgba(255,255,255,.4);
                border-bottom-color: rgba(60,60,60,.4);
                border-right-color: rgba(60,60,60,.4);
            }

            #div_form form input, #div_form form button {
                width: 212px;
                border: 1px solid;
                border-bottom-color: rgba(255,255,255,.5);
                border-right-color: rgba(60,60,60,.35);
                border-top-color: rgba(60,60,60,.35);
                border-left-color: rgba(80,80,80,.45);
                background-color: rgba(0,0,0,.2);
                background-repeat: no-repeat;
                padding: 8px 24px 8px 10px;
                font: bold .875em/1.25em "Open Sans Condensed", sans-serif;
                letter-spacing: .075em;
                color: #fff;
                text-shadow: 0 1px 0 rgba(0,0,0,.1);
                margin-bottom: 9px;
            }

            #div_form form input:focus { background-color: rgba(0,0,0,.4); }

            #div_form form input.email {
                background-image: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAA8AAAAMCAYAAAC9QufkAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAyJpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuMC1jMDYxIDY0LjE0MDk0OSwgMjAxMC8xMi8wNy0xMDo1NzowMSAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvIiB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIgeG1sbnM6c3RSZWY9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZVJlZiMiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENTNS4xIFdpbmRvd3MiIHhtcE1NOkluc3RhbmNlSUQ9InhtcC5paWQ6M0YwNDIzMTQ3QzIzMTFFMjg3Q0VFQzhDNTgxMTRCRTQiIHhtcE1NOkRvY3VtZW50SUQ9InhtcC5kaWQ6M0YwNDIzMTU3QzIzMTFFMjg3Q0VFQzhDNTgxMTRCRTQiPiA8eG1wTU06RGVyaXZlZEZyb20gc3RSZWY6aW5zdGFuY2VJRD0ieG1wLmlpZDozRjA0MjMxMjdDMjMxMUUyODdDRUVDOEM1ODExNEJFNCIgc3RSZWY6ZG9jdW1lbnRJRD0ieG1wLmRpZDozRjA0MjMxMzdDMjMxMUUyODdDRUVDOEM1ODExNEJFNCIvPiA8L3JkZjpEZXNjcmlwdGlvbj4gPC9yZGY6UkRGPiA8L3g6eG1wbWV0YT4gPD94cGFja2V0IGVuZD0iciI/PsOChsgAAADUSURBVHjaYvz///9JBgYGMwbSwSkGoOafQPwKiAOBmIEIHAXED0H6QJwPQGwAxE+AOJOAxnwgvgfEKiB9MM0gWg6IbwNxIw6NXUB8HogloHwUzSAsBAoDIJ4DxMxQMRA9H4gPADE/kloMzSCsBcR/gHgj1LAt0HBRR1P3gQktBA2AeBcQZwHxCyB+AsT3gTgFKq6FohrJZnssoW6AxPaDBqoZurP9oBrtCYS2ExA/h9JgzX+gAsZExrMZVP0fmGZ1IjWiBCoL0NsXgPgGGcnzLECAAQD5y8iZ2Z69IwAAAABJRU5ErkJggg==);
                background-position: 220px 10px;
            }

            #div_form form input.pass {
                background-image: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAA0AAAAQCAYAAADNo/U5AAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAyJpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuMC1jMDYxIDY0LjE0MDk0OSwgMjAxMC8xMi8wNy0xMDo1NzowMSAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvIiB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIgeG1sbnM6c3RSZWY9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZVJlZiMiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENTNS4xIFdpbmRvd3MiIHhtcE1NOkluc3RhbmNlSUQ9InhtcC5paWQ6NTVFMDg1QzU3QzIzMTFFMjgwQThGODZFM0EwQUZFQ0YiIHhtcE1NOkRvY3VtZW50SUQ9InhtcC5kaWQ6NTVFMDg1QzY3QzIzMTFFMjgwQThGODZFM0EwQUZFQ0YiPiA8eG1wTU06RGVyaXZlZEZyb20gc3RSZWY6aW5zdGFuY2VJRD0ieG1wLmlpZDo1NUUwODVDMzdDMjMxMUUyODBBOEY4NkUzQTBBRkVDRiIgc3RSZWY6ZG9jdW1lbnRJRD0ieG1wLmRpZDo1NUUwODVDNDdDMjMxMUUyODBBOEY4NkUzQTBBRkVDRiIvPiA8L3JkZjpEZXNjcmlwdGlvbj4gPC9yZGY6UkRGPiA8L3g6eG1wbWV0YT4gPD94cGFja2V0IGVuZD0iciI/Pv2NSIIAAADYSURBVHjanJAxCsJAEEXXaBMQtvIMqTxDKjtPELC1svMoOYM2WlqIhVcQFMVgG7ATAoIggfGPjrLIrBo/vCzZ+Z+dGUNExiECI7Clhw5gAtqur8YfUQxm4AzGIAMRSIAFXbC8OyUdghwsgH173cp9Lr5XqAeOSsANcj3h/8BpbQ4Ko6uQOvtMQy6noG4+iz3XZ4iHbIEQ9L8EeUlN3t5etvSrMg6RqajAc78BQ7BTq6QrllV3tKLvpZOclyrt/TWTlTP0zVQqba/BAKyUWsmh1BPUxL70JsAABHkyyK1uocIAAAAASUVORK5CYII=);
                background-position: 223px 8px
            }

            ::-webkit-input-placeholder { color: #ccc; text-transform: uppercase; }
            ::-moz-placeholder { color: #ccc; text-transform: uppercase; }
            :-ms-input-placeholder { color: #ccc; text-transform: uppercase; }

            #div_form form button[type=submit] {
                width: 248px;
                margin-bottom: 0;
                color: #3f898a;
                letter-spacing: .05em;
                text-shadow: 0 1px 0 #133d3e;
                text-transform: uppercase;
                background: #225556;
                border-top-color: #9fb5b5;
                border-left-color: #608586;
                border-bottom-color: #1b4849;
                border-right-color: #1e4d4e;
                cursor: pointer;
            }

            .campo_incorreto{
                background-color: #c60000!important;
            }

            .hide{
                display: none;
            }

            .label{
                height: 17px;
                margin: 0 10px 0 0;
                padding: 7px 5px 5px 0;
                font-size: 12px;
                font-weight: bold;
                text-align: right;
                display: inline-block;
                width: 45px;
            }
            .input{
                height: 17px;
                margin: 0 0 0 0;
                padding: 5px 5px 5px 5px;
                border: 1px solid #ccc;
                font-size: 12px;
                color: #777;
                display: inline-block;
            }

            #btn_add_email,#btn_edit_email{
                width: auto;
                height: 15px;
                margin: 0 0 10px 0;
                padding: 5px 12px 5px 12px;
                display: inline-block;
                border: 1px solid #598c52;
                color: #333;
                margin-top: 10px;
                margin-right: 30px;
                float: right;
            }

            #btn_cancela, #btn_cancela_edit{
                width: auto;
                height: 15px;
                margin: 0 0 10px 0;
                padding: 5px 12px 5px 12px;
                display: inline-block;
                border: 1px solid #ff3333;
                color: #333;
                margin-top: 10px;
                margin-left: 5px;
                float: left;
            }

            #btn_cancela:hover,#btn_cancela_edit:hover{
                color: #000;
                border: 1px solid #ff3333;
                background-color: #ff4d4d;
            }

            #btn_add_email:hover, #btn_edit_email:hover{
                color: #000;
                border: 1px solid #598c52;
                background-color: #98ce89;
            }


            #div_add_email, #div_edit_email{
                height: 125px;
                width: 400px;
                border: 1px solid #777;
                padding: 10px 10px 10px 10px;
                margin-left: 15px;
            }

            #btn_atualiza, #btn_atualiza_dados{
                height: 125px;
                width: 300px;
                border: 1px solid #777;
                padding: 5px 5px 5px 5px;
                margin-left: 15px;
                color: #333;
            }
            #btn_atualiza:hover, #btn_atualiza_dados:hover{
                color: #000;
                border: 1px solid #598c52;
                background-color: #98ce89;
            }

            .btn_voltar{
                height: 125px;
                width: 300px;
                border: 1px solid #777;
                padding: 5px 5px 5px 5px;
                margin-left: 15px;
                color: #333;
            }
            .btn_voltar:hover{
                color: #000;
                border: 1px solid #598c52;
                background-color: #98ce89;
            }

            #add_email_at{
                height: 125px;
                width: 300px;
                border: 1px solid #777;
                padding: 5px 5px 5px 5px;
                margin-left: 15px;
                color: #333;
            }
            #add_email_at:hover{
                color: #000;
                border: 1px solid #598c52;
                background-color: #98ce89;
            }

            .hide{
                display:none !important;
            }

            .grid_tb_contas{
                background-color: #fff;
                text-align: left;
                padding: 3px 5px 5px 5px;
                margin: 5px 5px 5px 5px;
                border: 1px solid #fff;
                background-color:#D7D7D2;
                border-top: 1px solid #333;
                border-bottom: 1px solid #333;
            }          


            .grid_tb_contas:hover{
                color:#000;
                border:1px solid #598c52;
                background-color:#98ce89;
            }

            .grid_tb_contas_c{

            }          


            .grid_tb_caixa{
                background-color: #fff;
                text-align: left;
                padding: 5px 5px 5px 5px;
                margin: 5px 5px 5px 5px;
                border: 1px solid #fff;
                background-color:#D7D7D2;
            }          


            .grid_tb_caixa:hover{
                color:#000;
                border:1px solid #598c52;
                background-color:#98ce89
            }

            .grid_tb_mensagens{
                background-color: #fff;
                text-align: left;
                padding: 5px 5px 5px 5px;
                margin: 5px 5px 5px 5px;
                //border: 1px solid #fff;
                //background-color:#D7D7D2;

                // border: 1px solid #999;
                box-shadow: 0 1px 8px #999;
                border-radius: 4px;
            }          


            .grid_tb_mensagens:hover{
                color:#000;
                // border:1px solid #598c52;
                background-color:#98ce89
            }

            .tb_style_geral{
                padding: 3px 5px 5px 5px;
                border-top: 1px solid #333;
                border-bottom: 1px solid #333;
                border-left: 1px solid #333;
                border-right: 1px solid #333;
                font-size: 12px;
            }

            .tb_style_mensagens{
                padding: 5px 7px 7px 7px;
                border-top: 1px solid #bbd3da;
                border-bottom: 1px solid #bbd3da;
                // border-left: 1px dotted #bbd3da;
                border-right: 1px dotted #bbd3da;
                font-size: 12px;
                max-height: 27px;
                max-width: 470px;
                overflow: no-display;
            }

            .cursor_pointer{
                cursor: pointer;
            }

            .tb_cor_ok{
                color:green;
            }

            .tb_cor_erro{
                color:red;
            }

            .tb_cor_exec{
                color:yellow;
            }

            .tb_selectionada{
                background-color: #ff9944!important;
            }

            .tb_caixas {
                width: 200px;
                min-height: 900px;
                position: absolute;
                margin: 10px;
                padding: 10px;
                border: 1px solid #999;
                box-shadow: 0 1px 8px #999;
                border-radius: 4px;
                background-color: #fff;
                /*margin-top:62px;*/
            }

            .listing {
                display: block;
                list-style: none;
                margin: 0;
                padding: 0;
                display: block;
                cursor: default;
                font-weight: normal;
            }

            .listing_link{
                display: block;
                color: #376572;
                text-shadow: 0 1px 1px #fff;
                text-decoration: none;
                cursor: default;
                padding: 6px 8px 2px 34px;
                height: 17px;

                box-shadow: 0 1px 4px #FF8040;
                border-radius: 2px;
                font-family: "Lucida Grande",Verdana,Arial,Helvetica,sans-serif;
                font-size: 11px;
                margin-bottom: 8px;
            }

            .cabecalho_tb {
                padding: 7px 7px;
                color: #69939e;
                text-decoration: none;
                overflow: hidden;
                text-overflow: ellipsis;
            }

            /**
  * Paginação
  ****************************************************************************************************
  */
            #paginacao_box{
                margin:10px 0 0 0;
                padding:15px 0;
                border:1px solid #828282;
                background-color:#e1e1e1;
            }

            .table_procurase{
                margin:0 auto;
            }

            .primeira_pagina{
                width:22px;
                height:22px;
                margin:0 0 0 0;
                background: url(<?php echo URL; ?>img/siga/paginacao.png) 0 0 no-repeat;
                display:inline-block;
            }
            .primeira_pagina:hover{
                background: url(<?php echo URL; ?>img/siga/paginacao.png) 0 -22px no-repeat;
            }
            .primeira_pagina_inativa{
                width:22px;
                height:22px;
                margin:0 0 0 0;
                background: url(<?php echo URL; ?>img/siga/paginacao.png) 0 0 no-repeat;
                display:inline-block;
            }

            .pagina_anterior{
                width:22px;
                height:22px;
                margin:0 0 0 0;
                background: url(<?php echo URL; ?>img/siga/paginacao.png) 0 -44px no-repeat;
                display:inline-block;
            }
            .pagina_anterior:hover{
                background: url(<?php echo URL; ?>img/siga/paginacao.png) 0 -66px no-repeat;
            }
            .pagina_anterior_inativa{
                width:22px;
                height:22px;
                margin:0 0 0 0;
                background: url(<?php echo URL; ?>img/siga/paginacao.png) 0 -44px no-repeat;
                display:inline-block;
            }

            .proxima_pagina{
                width:22px;
                height:22px;
                margin:0 0 0 0;
                background: url(<?php echo URL; ?>img/siga/paginacao.png) 0 -132px no-repeat;
                display:inline-block;
            }
            .proxima_pagina:hover{
                background: url(<?php echo URL; ?>img/siga/paginacao.png) 0 -154px no-repeat;
            }
            .proxima_pagina_inativa{
                width:22px;
                height:22px;
                margin:0 0 0 0;
                background: url(<?php echo URL; ?>img/siga/paginacao.png) 0 -132px no-repeat;
                display:inline-block;
            }

            .ultima_pagina{
                width:22px;
                height:22px;
                margin:0 0 0 0;
                background: url(<?php echo URL; ?>img/siga/paginacao.png) 0 -88px no-repeat;
                display:inline-block;
            }
            .ultima_pagina:hover{
                background: url(<?php echo URL; ?>img/siga/paginacao.png) 0 -110px no-repeat;
            }
            .ultima_pagina_inativa{
                width:22px;
                height:22px;
                margin:0 0 0 0;
                background: url(<?php echo URL; ?>img/siga/paginacao.png) 0 -88px no-repeat;
                display:inline-block;
            }



            .navegacao li {
                display:inline;
            }

            .navegacao li a {
                color:#333;
                margin:0 3px;
                text-decoration: none;
            }

            .navegacao li.active {
                min-width:14px;
                height:15px;
                display:inline-block;
                padding:3px 2px 2px 2px;
                border:1px solid #bbbbbb;
                text-align:center;
                background-color:#D7D7D2;
                font-size:11px;
            }
            .navegacao li.hover {
                background-color: #f5f5f5;
                color: #cdcdcd;
            }

            .num_paginas{
                min-width:14px;
                height:15px;
                display:inline-block;
                padding:3px 2px 2px 2px;
                border:1px solid #e1e1e1;
                text-align:center;
                font-size:11px;
            }
            .num_paginas:hover{
                border:1px solid #bbbbbb;
                background-color:#98ce89;
            }

            #num_paginas{
                margin:0 0 0 10px;
            }
            #texto_pag{
                padding:0 0 0 40px;
            }

            #paginacao_texto{
                margin:10px 0 0 0;
                text-align:center;
                font-size:12px;
                color:#333;
            }

            #paginacao_texto select{
                margin:-3px 0 0 0;
                padding:2px 0;
                border:1px solid #bbbbbb;
                position:relative;
            }

            .filtro_bg{
                background:none;
            }
            .filtro_up{
                padding:0 13px 0 0;
                background: url(<?php echo URL; ?>img/siga/filtro-setas.png) right 0 no-repeat;
            }
            .filtro_down{
                padding:0 13px 0 0;
                background: url(<?php echo URL; ?>img/siga/filtro-setas.png) right -16px no-repeat;
            }
            .carregando_tela{
                display: none;
                position: fixed;
                left: 0;
                width: 100%;
                height: 100%;
                min-height: 100%;
                min-width: 100%;
                margin-bottom: 100px;
                overflow-x: hidden;
                overflow-y: auto;
                z-index: 999999;
                background-color: rgba(10,10,10,0.6);
            }
            .carregando_tela_gif{
                display: block;
                position: fixed;
                left: 40%;
                top:40%;
            }

            .div_mensagem_minimizada{
                min-height:500px;
                height:500px;
                max-height:500px;
                overflow:auto;
            }

            .div_mensagem_maximizada{
                min-height:900px;
                height:900px;
                max-height:900px;
                overflow:auto;
            }

            /*.class_carregando_div{
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
                height: 100%;
                z-index: 9999;
                background-color: rgba(90, 90, 90, 0.4);
            }*/

            //----------------------------------------

            [draggable] {
                -moz-user-select: none;
                -khtml-user-select: none;
                -webkit-user-select: none;
                user-select: none;
                /* Required to make elements draggable in old WebKit */
                -khtml-user-drag: element;
                -webkit-user-drag: element;
            }

            .cursor_carregando{
                cursor: wait;
            }

            .over {
                border: 2px dashed #FF8040;
            }


        </style>
    </head>
    <body>

        <?php
        require(DIR_siga_inc . "topo.php");
        require(DIR_siga_inc . "status.php");
        if ($permissao_acesso == "") {

            $bd = new BD_FB();
            $bd->open();
            $email = "";
            $sql_c_email = "SELECT email FROM usuario WHERE codusuario = " . $cod_usuario;
            $query_c_email = ibase_query($sql_c_email);
            if ($query_c_email) {
                $reg_c_email = ibase_fetch_assoc($query_c_email);
                $email = $reg_c_email['EMAIL'];
            }
            $bd->close();
            ?>
            <div class="carregando_tela"><div class="carregando_tela_gif"><img src="<?php echo URL; ?>img/siga/carousel/AjaxLoader.gif"/></div></div>

            <div id="janelaModal" style="width: 400px;" class="reveal-modal"></div>
            <div id="janelaModal_novo_email" style="width: 500px; padding: 15px 20px 4px!important;" class="reveal-modal"></div>


            <div id="div_fundo_geral" style="display: block; position: fixed; left: 0; width: 100%; height: 100%; min-height: 100%; min-width: 100%; margin-bottom: 100px; overflow-x: hidden; overflow-y: auto; background-color: #efefef;">

                <div id="div_form">
                    <form style="margin-top: 200px;" action="" id="login_form">

                        <input type="text" name="" id="email" placeholder="Email" class="email" value="<?php echo $email; ?>">
                        <label style="margin-bottom: 10px" for=""></label>

                        <input type="password" name="" id="senha" placeholder="Senha" class="pass">
                        <label style="margin-bottom: 10px" for=""></label>

                        <button id="login" type="submit">login</button>
                        <img src="<?php echo URL; ?>img/carregando.gif" style="display: none; margin-left: 80px;" id="img_carregando"/>

                    </form>
                </div>

                <div id="menu_superior" class="hide" style="background-color: #fff; margin: 10px 50px 10px 20px; min-width: 1000px; border-radius: 8px; box-shadow: 0 1px 8px #999; overflow: auto; padding: 20px 0 20px 40px;">
                    <a id="btn_atualiza_conta_unica" style="float:left;" href="#"><img title="Atualizar" src="<?php echo DIR_siga_img; ?>email/update.svg" style="width: 30px; height: 30px; padding-left: 10px;"/><br/><label style="color:#000;">Atualizar</label></a>   
                    <a id="btn_envia_email" style="float:left; margin-left: 20px;" href="#"><img title="Novo email" src="<?php echo DIR_siga_img; ?>email/enviar.svg" style="width: 30px; height: 30px; padding-left: 10px;"/><br/><label style="color:#000; margin-left: 5px;">Novo</label></a>   
                    <a id="btn_responder" style="float:left; margin-left: 20px;" class="hide" href="#"><img title="Responder" src="<?php echo DIR_siga_img; ?>email/responder.svg" style="width: 30px; height: 30px; padding-left: 10px;"/></a>   
                    <a id="btn_responder_todos" style="float:left; margin-left: 20px;" class="hide" href="#"><img title="Responder a todos" src="<?php echo DIR_siga_img; ?>email/responder_todos.svg" style="width: 30px; height: 30px; padding-left: 10px;"/></a>   
                    <a id="btn_encaminhar" style="float:left; margin-left: 20px;" class="hide" href="#"><img title="Encaminhar" src="<?php echo DIR_siga_img; ?>email/encaminhar.svg" style="width: 30px; height: 30px; padding-left: 10px;"/></a>   
                    <a id="icone_lixo" style="float:left; margin-left: 20px;" class="hide" href="#"><img title="Excluir" src="<?php echo DIR_siga_img; ?>email/excluir.svg" style="width: 30px; height: 30px; padding-left: 10px;"/></a>   
                    <a id="icone_sair" style="float:right; margin-right: 20px;" href="#"><img title="Sair" src="<?php echo DIR_siga_img; ?>email/sair.svg" style="width: 30px; height: 30px;"/><br/><label style="color:#000;">Sair</label></a>   
                    <a style="float: right;margin-right: 30px;margin-top: 10px;font-size: 12px;" href="#"><label style="color:#000;" id="nome_email"><?php echo $_SESSION['nome_conta_email']; ?></label></a>   
                    <a style="float: right;margin-right: 30px;margin-top: 10px;font-size: 12px;" href="#"><label style="color:#000;" id="porc_exec"></label></a>   
                </div>


                <div id="emails_x" style="float:left; display: block; overflow-x: hidden; overflow-y: auto; margin-bottom: 100px;">

                    <div id="div_caixas" style="float: left; margin-left: 10px; background-color: #fff;" class="hide">

                    </div>

                    <div id="emails" style="float:left; display: block; margin-left:250px; margin-right: 50px; overflow: hidden; position: absolute; margin-bottom: 100px;">

                        <?php
                        $class_max = 'div_mensagem_minimizada';
                        if ($_SESSION['maximizado_conta_email'] == 'S') {
                            $class_max = 'div_mensagem_maximizada';
                        }
                        ?>

                        <div id="div_mensagens" class="hide" style=" margin-bottom:10px; min-height:450px; overflow:auto; margin-top: 10px; min-width:1040px;">

                        </div>

                        <div id="div_mensagem" class="hide <?php echo $class_max; ?>">

                        </div>

                    </div>
                </div>
            </div>
            <input type="hidden" id="id"/>
            <input type="hidden" id="nome_email_aux" value="<?php echo $_SESSION['nome_conta_email']; ?>"/>

            <?php
            require(DIR_siga_inc . "rodape.php");

            require(DIR_siga_inc . "pesquisa.php");
            ?>
            <script>
                $(window).resize(function () {
                    fun_ajusta_largura();
                });
                function fun_ajusta_largura() {
                    var w = $(document).width() - 320;

                    $('#tb_msgs').css('width', w);
                    $('#div_mensagem').css('width', w);
                    $('#tb_mensagens').css('width', w);
                    $('#tb_mensagem').css('width', w + 50);

                    $('#div_msg_header').css('width', $('#tb_mensagem').width() - 60);
                    $('#menu_superior').css('width', $(document).width() - 107);
                }
                $(document).ready(function (e) {
    <?php
    if (isset($_SESSION['cod_conta_email']) && !empty($_SESSION['cod_conta_email']) && $_SESSION['cod_conta_email'] > 0) {
        ?>
                        var aux_conta = '<?php echo $_SESSION['cod_conta_email']; ?>';
                        carrega_conta(aux_conta);
                        faz_backup(aux_conta, 1);


        <?php
    }
    ?>
                });
                var executando = false;
                var executando_caixas = [];
                // var emails_por_exec = 2;
                var paginas_por_caixa = 99;

                var total_emails_baixar_caixa = [];
                var emails_baixados_caixa = [];
                var emails_baixados_caixa_geral = [];
                var total_tb = [];

                var total_mensagem_baixar = 0;
                var total_mensagem_baixadas = 0;

                // var array_geral = [[]];

                var controle_ajax_caixa = [[]];

                $(document).on('click', '#para_execucao', function () {
                    executando = false;
                    $('#porc_exec').html('');
                    $('#btn_atualiza_conta_unica').html('<img src="<?php echo URL; ?>img/siga/email/update.svg" style="width: 30px; height: 30px; padding-left: 10px;"/><br/><label style="color:#000;">Atualizar</label>');

                    $('.listing_link').each(function () {
                        var caixa = $(this).prop('id');
                        executando_caixas[caixa] = false;
                        var caixa_ex = caixa.split('.');
                        var caixa_nome = caixa;
                        if (caixa_ex.length > 1) {
                            caixa_nome = caixa_ex[caixa_ex.length - 1];
                        }

                        $(this).html(caixa_nome.replaceAll('___', '*') + '<img class="carregando" id="carregando_' + caixa + '" style="display:none;" src="' + url + 'img/load_foto_tem.gif" />' + '<img style="display:none;" class="finalizado" id="finalizado_' + caixa + '" src="' + url + 'img/siga/bt_ativar.png" />');

                    });

                    return false;
                });

                function faz_backup(id) {
                    executando = true;
                    $('#porc_exec').html('0%');
                    $('#btn_atualiza_conta_unica').html('<a href="#" id="para_execucao"><img src="<?php echo URL; ?>img/load_foto_tem.gif" style="width: 30px; height: 30px; padding-left: 10px;"/><br/><label style="color:#000;">Atualizando</label></a>');
                    $.ajax({
                        type: 'post',
                        data: {
                            'codemailusuario': id,
                            'paginas_por_caixa': paginas_por_caixa
                        },
                        url: url + 'ajax/webmail/ajax_gera_lista_emails_baixar',
                        async: true,
                        beforeSend: function () {

                        },
                        success: function (retorno) {
                            var array_ret = retorno.split('|');
                            if (array_ret[0] == 'ok') {
                                var array_caixas = $.parseJSON(array_ret[1]);
                                var array_caixas_total = $.parseJSON(array_ret[2]);
                                total_tb = $.parseJSON(array_ret[3]);
                                
                                total_mensagem_baixar = 0;

                               for (var i = 0; i < array_caixas.length; i++) {
                                    total_emails_baixar_caixa[array_caixas[i]] = array_caixas_total[array_caixas[i]];
                                    emails_baixados_caixa[array_caixas[i]] = 0;
                                    emails_baixados_caixa_geral[array_caixas[i]] = 0;
                                    executando_caixas[array_caixas[i]] = true;

                                    if (parseInt(array_caixas_total[array_caixas[i]]) > parseInt(total_tb[array_caixas[i]])) {
                                        total_mensagem_baixar += (parseInt(array_caixas_total[array_caixas[i]]) - parseInt(total_tb[array_caixas[i]]));
                                    }
                                }

                                faz_backup_caixa_pagina(id, array_caixas, 1, 0);

                            } else {
                                console.log(array_ret[1]);
                            }


                        }
                    });
                }

                function faz_backup_caixa_pagina(id, array_caixas, pagina, c) {
                    var caixa = array_caixas[c];
                    var total_paginas = Math.ceil(total_emails_baixar_caixa[caixa] / paginas_por_caixa);
                    $.ajax({
                        type: 'post',
                        data: {
                            'codemailusuario': id,
                            'caixa': caixa,
                            'pagina': pagina
                        },
                        url: url + 'ajax/webmail/ajax_carrega_lista_emails_baixar',
                        async: true,
                        beforeSend: function () {
                            if (executando_caixas[caixa]) {
                                controla_pasta_status(caixa, true);
                            }
                        },
                        success: function (retorno) {
                            var array_ret = retorno.split('|');
                            if (array_ret[0] == 'ok') {
                                array_ret = $.parseJSON(array_ret[1]);
                                //array_geral[pagina] = [];
                                //array_geral[pagina][caixa] = array_ret;
                                if (array_ret[caixa].length > 0 && executando) {
                                    monta_pacote_ajax(array_ret[caixa], caixa, pagina, array_caixas, c);
                                } else {
                                    if (executando) {
                                        pagina++;

                                        if (pagina <= total_paginas) {
                                            emails_baixados_caixa[caixa] = 0;

                                            faz_backup_caixa_pagina($('#id').val(), array_caixas, pagina, c);

                                        } else {
                                            executando_caixas[caixa] = false;
                                            c++;
                                            if (c < array_caixas.length) {
                                                faz_backup_caixa_pagina($('#id').val(), array_caixas, 1, c);
                                            }


                                            var finalizou_tudo = true;
                                            controla_pasta_status(caixa, false);

                                            for (property in executando_caixas) {
                                                if (executando_caixas[property]) {
                                                    finalizou_tudo = false;
                                                }
                                            }


                                            if (finalizou_tudo) {
                                                executando = false;
                                                $('#porc_exec').html('');
                                                $('#btn_atualiza_conta_unica').html('<img src="<?php echo URL; ?>img/siga/email/update.svg" style="width: 30px; height: 30px; padding-left: 10px;"/><br/><label style="color:#000;">Atualizar</label>');
                                            }
                                        }
                                    }
                                }
                            } else {
                                executando_caixas[caixa] = false;
                                controla_pasta_status(caixa, false);
                                controla_pasta_erro(caixa, true);
                                c++;
                                if (c < array_caixas.length) {
                                    faz_backup_caixa_pagina(id, array_caixas, pagina, c);
                                }
                            }

                        }
                    });
                }
                function controla_pasta_erro(caixa, status) {
                    console.log(caixa);
                    if (status) {
                        $('.carregando').each(function () {
                            if ($(this).prop('id') == 'carregando_' + caixa.replaceAll('*', '___')) {
                                $(this).hide();
                            }
                        });
                        $('.finalizado').each(function () {
                            if ($(this).prop('id') == 'finalizado_' + caixa.replaceAll('*', '___')) {
                                $(this).hide();
                            }
                        });

                        $('.listing_link').each(function () {
                            if ($(this).prop('id') == caixa.replaceAll('*', '___')) {
                                $(this).append('<img class="erro_pasta" src="' + url + 'img/siga/grid_bt_del.png"/>');
                            }
                        });

                    } else {
                        $('.erro_pasta').remove();
                    }
                }
                function controla_pasta_status(caixa, status) {
                    $('.carregando').each(function () {
                        if ($(this).prop('id') == 'carregando_' + caixa.replaceAll('*', '___')) {
                            if (status) {
                                $(this).show();
                            } else {
                                $(this).hide();
                            }
                        }
                    });
                    $('.finalizado').each(function () {
                        if ($(this).prop('id') == 'finalizado_' + caixa.replaceAll('*', '___')) {
                            if (status) {
                                $(this).hide();
                            } else {
                                $(this).show();
                            }
                        }
                    });
                }



                function monta_pacote_ajax(array, caixa, pagina, array_caixas, c) {
                    var total_paginas = Math.ceil(total_emails_baixar_caixa[caixa] / paginas_por_caixa);

                    //console.log('exec:' + emails_baixados_caixa[caixa]);
                    $.ajax({
                        type: 'post',
                        //async: false,
                        data: {
                            'conta': $('#id').val(),
                            'caixa': caixa,
                            'array': array[emails_baixados_caixa[caixa]],
                            'pag': pagina
                        },
                        url: url + 'ajax/webmail/ajax_baixa_mensagem',
                        beforeSend: function () {

                        },
                        success: function (retorno) {
                            if (retorno != 'ok') {
                                console.log('Erro: Caixa:' + caixa + ' n: ' + emails_baixados_caixa[caixa] + ' ' + retorno);
                            } else {
                                //console.log('OK: Caixa:' + caixa + ' n: ' + emails_baixados_caixa[caixa] + ' ' + retorno);
                            }



                            $('.listing_link').each(function () {
                                if (executando) {
                                    if ($(this).prop('id') == caixa) {
                                        var caixa_ex = caixa.split('.');
                                        var caixa_nome = caixa;
                                        if (caixa_ex.length > 1) {
                                            caixa_nome = caixa_ex[caixa_ex.length - 1];
                                        }
                                        if ((emails_baixados_caixa_geral[caixa] + 1) < (parseInt(total_emails_baixar_caixa[caixa]) - parseInt(total_tb[caixa]))) {
                                            $(this).html(caixa_nome.replaceAll('___', '*') + ' ' + (emails_baixados_caixa_geral[caixa] + 1) + ' de ' + (parseInt(total_emails_baixar_caixa[caixa]) - parseInt(total_tb[caixa])));
                                        } else {
                                            $(this).html(caixa_nome + '<img class="carregando" id="carregando_' + caixa + '" style="display:none;" src="' + url + 'img/load_foto_tem.gif" />' + '<img class="finalizado" id="finalizado_' + caixa + '" src="' + url + 'img/siga/bt_ativar.png" />');
                                            executando_caixas[caixa] = false;
                                            var finalizou_tudo = true;
                                            for (property in executando_caixas) {
                                                if (executando_caixas[property]) {
                                                    finalizou_tudo = false;
                                                }
                                            }
                                            if (finalizou_tudo) {
                                                executando = false;
                                                $('#porc_exec').html('');
                                                $('#btn_atualiza_conta_unica').html('<img src="<?php echo URL; ?>img/siga/email/update.svg" style="width: 30px; height: 30px; padding-left: 10px;"/><br/><label style="color:#000;">Atualizar</label>');
                                            }
                                        }
                                    }
                                }
                            });

                            emails_baixados_caixa[caixa]++;
                            emails_baixados_caixa_geral[caixa]++;

                            total_mensagem_baixadas = 0;

                            for (obj in emails_baixados_caixa_geral) {
                                total_mensagem_baixadas += emails_baixados_caixa_geral[obj];
                            }

                            var porc_exec = Number(parseFloat((100.0 / total_mensagem_baixar) * total_mensagem_baixadas)).toFixed(2);

                            $('#porc_exec').html(porc_exec + '%');

                            if (emails_baixados_caixa[caixa] < array.length && executando) {

                                monta_pacote_ajax(array, caixa, pagina, array_caixas, c);
                            } else {
                                pagina++;

                                if (pagina <= total_paginas && executando) {
                                    emails_baixados_caixa[caixa] = 0;

                                    faz_backup_caixa_pagina($('#id').val(), array_caixas, pagina, c);

                                } else if (executando) {
                                    c++;
                                    faz_backup_caixa_pagina($('#id').val(), array_caixas, 1, c);
                                }
                            }
                        }
                    });

                }

                $(document).on("click", "#icone_sair", function () {
                    executando = false;
                    $('#porc_exec').html('');
                    $.ajax({
                        type: 'post',
                        data: {
                            'valor_session': '-1',
                            'nome_session': 'cod_conta_email'
                        },
                        url: url + 'ajax/global/ajax_session_criar',
                        //async: false,
                        beforeSend: function () {

                        },
                        success: function () {
                            $('#div_caixas').addClass('hide');
                            $('#menu_superior').addClass('hide');

                            $('#div_caixas').html('');

                            $('#login_form').removeClass('hide');
                            $('#div_mensagem').addClass('hide');
                            $('#div_mensagem').html('');

                            $('#div_mensagens').addClass('hide');
                            $('#div_mensagens').html('');
                        }
                    });
                    return false;
                });
                $(document).on('click', '#btn_atualiza_conta_unica', function () {
                    controla_pasta_erro('', false);
                    $('.finalizado').each(function () {
                        $(this).hide();
                    });
                    faz_backup($('#id').val(), 1);
                    return false;
                });
                /*function atualiza() {
                 var conta = $('#id').val();
                 var caixa = $('.tb_selectionada').prop('id');
                 carrega_mensagens(conta, caixa, 1);
                 }*/
                $(document).on("click", "#btn_responder_todos", function () {

                    var link = document.getElementById("btn_responder");
                    link = link.getAttribute("href").split('|');//id | uid | caixa

                    var id = link[0];
                    var uid = link[1];
                    var caixa = link[2];


                    $.ajax({
                        type: 'post',
                        data: {
                            'codemailusuario': id,
                            'uid': uid,
                            'caixa': caixa,
                            'all': 1
                        },
                        url: url + 'webmail/F_envia_email',
                        //async: false,
                        beforeSend: function () {

                        },
                        success: function (retorno) {
                            $a('#janelaModal_novo_email').html(retorno);
                            //$a('#janelaModal_novo_email').reveal({closeonbackgroundclick: false});
                            $a('#janelaModal_novo_email').reveal();
                        }
                    });
                    return false;
                });

                $(document).on("click", "#btn_responder", function () {

                    var link = document.getElementById("btn_responder");
                    link = link.getAttribute("href").split('|');//id | uid | caixa

                    var id = link[0];
                    var uid = link[1];
                    var caixa = link[2];


                    $.ajax({
                        type: 'post',
                        data: {
                            'codemailusuario': id,
                            'uid': uid,
                            'caixa': caixa
                        },
                        url: url + 'webmail/F_envia_email',
                        //async: false,
                        beforeSend: function () {

                        },
                        success: function (retorno) {
                            $a('#janelaModal_novo_email').html(retorno);
                            //$a('#janelaModal_novo_email').reveal({closeonbackgroundclick: false});
                            $a('#janelaModal_novo_email').reveal();
                        }
                    });
                    return false;
                });
                $(document).on("click", "#btn_envia_email", function () {
                    var link = document.getElementById("btn_envia_email");


                    $.ajax({
                        type: 'post',
                        data: {
                            'codemailusuario': link.getAttribute("href"),
                        },
                        url: url + 'webmail/F_envia_email',
                        //async: false,
                        beforeSend: function () {

                        },
                        success: function (retorno) {
                            $a('#janelaModal_novo_email').html(retorno);
                            //$a('#janelaModal_novo_email').reveal({closeonbackgroundclick: false});
                            $a('#janelaModal_novo_email').reveal();
                        }
                    });
                    return false;
                });
                $(document).on('click', '#login', function () {
                    $('#login').hide();
                    $('#img_carregando').show();

                    var email = $('#email').val();
                    var senha = $('#senha').val();

                    var continua = true;

                    $('#email').removeClass('campo_incorreto');
                    $('#senha').removeClass('campo_incorreto');

                    if (email == "" || !validacaoEmail(email)) {
                        $('#email').addClass('campo_incorreto');
                        continua = false;
                    }

                    if (senha == "") {
                        $('#senha').addClass('campo_incorreto');
                        continua = false;
                    }

                    if (continua) {
                        $.ajax({
                            type: 'post',
                            data: {
                                'email': email,
                                'senha': senha
                            },
                            url: url + 'ajax/webmail/ajax_email_checa_email_valido',
                            //async: false,
                            beforeSend: function () {

                            },
                            success: function (retorno) {
                                var array_ret = retorno.split('|');
                                $('#login').show();
                                $('#img_carregando').hide();
                                if (array_ret[0] == 'ok') {
                                    $('#nome_email').html(email);
                                    $('#nome_email_aux').val(email);
                                    carrega_conta(array_ret[1]);

                                    faz_backup(array_ret[1], 1);
                                } else {
                                    if (array_ret[1] == 'Too many login failures') {
                                        $a('#janelaModal').html(msg_box_aviso('erro', 'Login ou Senha incorretos', '', ''));
                                        $a('#janelaModal').reveal();
                                    } else {
                                        $a('#janelaModal').html(msg_box_aviso('erro', 'Erro: ' + array_ret[1], '', ''));
                                        $a('#janelaModal').reveal();
                                    }
                                }
                            }
                        });
                    }

                    return false;
                });

                $(document).on("click", "#icone_lixo", function () {
                    var aux_obj_tb;
                    $('.grid_tb_mensagens').each(function () {
                        if ($(this).hasClass('tb_selectionada')) {
                            aux_obj_tb = this;
                        }
                    });

                    var conta = $('#id').val();

                    aux_obj_tb.style.opacity = '0.4';
                    var link = document.getElementById("icone_lixo").getAttribute("href");
                    link = link.split('|');
                    var uid = link[0];
                    var caixa = link[1];
                    //$('#icone_lixo').html('<img src="<?php echo URL; ?>img/load_foto_tem.gif" style="width: 30px; height: 30px; padding-left: 10px;"/><br/><label style="color:#000;">Deletando</label>');

                    $.ajax({
                        type: 'post',
                        data: {
                            'uid': uid,
                            'de': caixa,
                            'para': "INBOX.lixo",
                            'id': conta
                        },
                        url: url + 'ajax/webmail/ajax_move_email',
                        //async: false,
                        beforeSend: function () {

                        },
                        success: function (retorno) {
                            //$('#icone_lixo').html('<img src="<?php echo DIR_siga_img; ?>email/excluir.svg" style="width: 30px; height: 30px; padding-left: 10px;"/>');
                            if (retorno == 'ok') {
                                atualiza();
                                // $(aux_obj_tb).next('.grid_tb_mensagens').trigger('click');
                                $(aux_obj_tb).html('');
                            } else {
                                alert('Erro: ' + retorno.split('|')[1]);
                            }
                        }
                    });

                    return false;
                });

                $(document).on("click", "#btn_encaminhar", function () {

                    var link = document.getElementById("btn_encaminhar");
                    link = link.getAttribute("href").split('|');//id | uid | caixa

                    var id = link[0];
                    var uid = link[1];
                    var caixa = link[2];


                    $.ajax({
                        type: 'post',
                        data: {
                            'codemailusuario': id,
                            'uid': uid,
                            'caixa': caixa,
                            'encaminhar': 1,
                            'all': 1
                        },
                        url: url + 'webmail/F_envia_email',
                        //async: false,
                        beforeSend: function () {

                        },
                        success: function (retorno) {
                            $a('#janelaModal_novo_email').html(retorno);
                            //$a('#janelaModal_novo_email').reveal({closeonbackgroundclick: false});
                            $a('#janelaModal_novo_email').reveal();
                            //document.getElementById("senha").focus();
                        }
                    });
                    return false;
                });

                $(document).on("click", ".listing_link", function () {
                    // var id = '<?php echo $_SESSION['cod_conta_email']; ?>';
                    var id = $('#id').val();
                    var caixa = $(this).prop('id');
                    carrega_mensagens(id, caixa, 1);

                    $(this).each(function () {
                        $('.listing_link').removeClass('tb_selectionada');
                    });

                    $(this).addClass('tb_selectionada');

                    if ($(this).hasClass('tem_sub')) {
                        caixa = caixa.replaceAll('.', '--');
                        caixa = caixa.replaceAll(' ', '_');
                        if ($('.filha_' + caixa).hasClass('hide')) {
                            $('.filha_' + caixa).removeClass('hide');
                        } else {
                            $('.filha_' + caixa).addClass('hide');
                        }
                    }

                    return false;
                });
                $(document).on("click", ".grid_tb_mensagens", function () {
                    var caixa = $(this).attr('id').split('%*%')[0];
                    var uid = $(this).attr('id').split('%*%')[1];
                    var id = $(this).attr('id').split('%*%')[2];

                    $(".grid_tb_mensagens").each(function () {
                        $(this).removeClass('tb_selectionada');
                    });
                    $(this).addClass('tb_selectionada');

                    carrega_mensagem(id, caixa, uid);
                });

                function carrega_mensagem(conta, caixa, uid) {
                    $.ajax({
                        type: 'post',
                        data: {
                            'id': conta,
                            'caixa': caixa,
                            'uid': uid
                        },
                        url: url + 'ajax/webmail/ajax_email_carrega_mensagem',
                        beforeSend: function () {
                            carregando("show");
                            $('#div_mensagem').html('');
                        },
                        success: function (retorno) {
                            $('#div_mensagem').removeClass('hide');
                            //$('#icone_lixo').removeClass('hide');

                            var link = document.getElementById("icone_lixo");
                            link.setAttribute("href", uid + '|' + caixa);

                            link = document.getElementById("btn_responder");
                            link.setAttribute("href", conta + '|' + uid + '|' + caixa);

                            link = document.getElementById("btn_encaminhar");
                            link.setAttribute("href", conta + '|' + uid + '|' + caixa);


                            $('#icone_lixo').removeClass('hide');
                            $('#btn_responder').removeClass('hide');
                            $('#btn_responder_todos').removeClass('hide');
                            $('#btn_encaminhar').removeClass('hide');
                            $('#div_mensagem').html(retorno);
                            fun_ajusta_largura();
                            carregando("hide");
                        }
                    });
                }

                function carrega_conta(id) {
                    $('#id').val(id);
                    $.ajax({
                        type: 'post',
                        data: {
                            'id': id
                        },
                        url: url + 'ajax/webmail/ajax_email_carrega_caixas',
                        beforeSend: function () {

                        },
                        success: function (retorno) {
                            var array_msg = retorno.split('|');
                            if (array_msg[0] == 'ok') {
                                $('#login_form').addClass('hide');
                                $('#div_mensagem').addClass('hide');
                                $('#div_mensagem').html('');

                                $('#div_mensagens').addClass('hide');
                                $('#div_mensagens').html('');

                                $('#div_caixas').removeClass('hide');
                                $('#menu_superior').removeClass('hide');
                                $('#div_caixas').html(array_msg[1]);

                                var link = document.getElementById("btn_envia_email");
                                link.setAttribute("href", id);

                                carrega_mensagens(id, 'INBOX', 1);

                                $(".listing_link").each(function () {
                                    if ($(this).prop('id') == 'INBOX') {
                                        $(this).addClass('tb_selectionada');
                                    } else {
                                        $(this).removeClass('tb_selectionada');
                                    }
                                });

                                var cols = document.querySelectorAll('.listing .drag_caixas');
                                [].forEach.call(cols, function (col) {
                                    col.addEventListener('dragenter', handleDragEnter, false);
                                    col.addEventListener('dragover', handleDragOver, false);
                                    col.addEventListener('dragleave', handleDragLeave, false);
                                    col.addEventListener('drop', handleDrop, false);
                                    col.addEventListener('dragend', handleDragEnd, false);
                                });

                            }
                        }
                    });
                }

                var dragSrcEl = null;

                function handleDragStart(e) {
                    this.style.opacity = '0.4';

                    dragSrcEl = this;
                }

                function handleDragOver(e) {
                    if (e.preventDefault) {
                        e.preventDefault(); // Necessary. Allows us to drop.
                    }

                    e.dataTransfer.dropEffect = 'move';  // See the section on the DataTransfer object.

                    return false;
                }

                function handleDragEnter(e) {
                    // this / e.target is the current hover target.

                    this.style.opacity = '1';
                    this.classList.add('over');

                }

                function handleDragLeave(e) {
                    this.classList.remove('over');  // this / e.target is previous target element.

                }

                function handleDragEnd(e) {
                    // this/e.target is the source node.

                    this.style.opacity = '1';
                    var cols = document.querySelectorAll('.listing .drag_caixas');
                    [].forEach.call(cols, function (col) {
                        col.classList.remove('over');
                    });
                    cols = document.querySelectorAll('#columns .grid_tb_mensagens');
                    [].forEach.call(cols, function (col) {
                        col.classList.remove('over');
                    });
                }

                function handleDrop(e) {
                    // this/e.target is current target element.

                    if (e.stopPropagation) {
                        e.stopPropagation(); // Stops some browsers from redirecting.
                    }
                    // alert($(dragSrcEl).prop('id'));
                    var array_dados = $(dragSrcEl).prop('id').split('%*%');

                    var caixa = array_dados[0];
                    var uid = array_dados[1];
                    var conta = array_dados[2];
                    var para_pasta = $(this).prop('id');


                    $('.tb_caixas').addClass('cursor_carregando');
                    $('#tb_msgs').addClass('cursor_carregando');

                    $.ajax({
                        type: 'post',
                        data: {
                            'uid': uid,
                            'de': caixa,
                            'para': para_pasta,
                            'id': conta
                        },
                        url: url + 'ajax/webmail/ajax_move_email',
                        //async: false,
                        beforeSend: function () {

                        },
                        success: function (retorno) {
                            //$('#icone_lixo').html('<img src="<?php echo DIR_siga_img; ?>email/excluir.svg" style="width: 30px; height: 30px; padding-left: 10px;"/>');
                            if (retorno == 'ok') {
                                atualiza();
                                $('.tb_caixas').removeClass('cursor_carregando');
                                $('#tb_msgs').removeClass('cursor_carregando');
                                dragSrcEl.innerHTML = '';
                                $('.over').removeClass('over');
                            } else {
                                alert('Erro: ' + retorno.split('|')[1]);
                            }
                        }
                    });

                    /*$.ajax({
                     type: 'post',
                     data: {
                     'uid': uid,
                     'caixa': caixa,
                     'para_pasta': para_pasta,
                     'conta': conta
                     },
                     url: url + 'ajax/email/ajax_email_move_email',
                     //async: false,
                     beforeSend: function () {
                     
                     },
                     success: function (retorno) {
                     
                     Atualiza_contas_f();
                     $('.tb_caixas').removeClass('cursor_carregando');
                     $('#tb_msgs').removeClass('cursor_carregando');
                     dragSrcEl.innerHTML = '';
                     }
                     });*/


                    return false;
                }


                $(document).on("change", "#num_paginas", function () {
                    var id = $('#id').val();
                    var caixa = $('.tb_selectionada').prop('id');
                    carrega_mensagens(id, caixa, 1);
                });
                $(document).on("click", ".proxima_pagina, .num_paginas, .ultima_pagina, .pagina_anterior, .primeira_pagina", function () {
                    var id = $('#id').val();
                    var caixa = $('.tb_selectionada').prop('id');
                    var pagina = $(this).prop('id');
                    carrega_mensagens(id, caixa, pagina);
                    return false;
                });

                function carrega_mensagens(id, caixa, pagina) {
                    var itens_pag = $('#num_paginas').val();
                    $.ajax({
                        type: 'post',
                        data: {
                            'id': id,
                            'caixa': caixa,
                            'pagina': pagina,
                            'itens_pag': itens_pag
                        },
                        url: url + 'ajax/webmail/ajax_email_carrega_mensagens',
                        beforeSend: function () {
    //                            $('#div_mensagens').html('<div class="class_carregando_div"></div>');
                            carregando("show");

                            $('#div_mensagem').addClass('hide');
                            $('#div_mensagem').html('');
                            $('#div_mensagens').html('');
                        },
                        success: function (retorno) {

                            $('#div_mensagens').removeClass('hide');

                            $('#menu_superior').removeClass('hide');

                            if ($('.filha_' + caixa).hasClass('hide')) {
                                $('.filha_' + caixa).removeClass('hide');
                            } else {
                                $('.filha_' + caixa).addClass('hide');
                            }
                            $('#div_mensagens').html(retorno);

                            var cols = document.querySelectorAll('#columns .grid_tb_mensagens');
                            [].forEach.call(cols, function (col) {
                                col.addEventListener('dragstart', handleDragStart, false);
                            });
                            fun_ajusta_largura();
                            carregando("hide");
                        }
                    });
                }
            </script>
            <?php
        }
        ?>
    </body>
</html>