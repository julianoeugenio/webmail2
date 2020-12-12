<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8" />
        <title>Email</title>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />


        <link href="<?php echo DIR_siga_css ?>global.css?<?php echo filemtime(DIR_siga_css_fisico . "global.css"); ?>" rel="stylesheet" type="text/css" />

        <script src="<?php echo DIR_siga_js ?>jquery-1.10.2.min.js?<?php echo filemtime(DIR_siga_js_fisico . "jquery-1.10.2.min.js"); ?>"></script>

        <script src="<?php echo DIR_siga_js ?>ui/jquery.ui.js?<?php echo filemtime(DIR_siga_js_fisico . "ui/jquery.ui.js"); ?>"></script>

        <script>var url = "<?php echo URL ?>";</script>

        <link rel="shortcut icon" href="<?php echo DIR_siga_img ?>email/favicon.ico?email" />

        <link href="<?php echo DIR_siga_css ?>uploadfile.css" rel="stylesheet">
        <script src="<?php echo DIR_siga_js ?>upload/jquery.uploadfile.min.js"></script>

        <script src="<?php echo DIR_siga_js ?>util.js"></script>
        <script src="<?php echo DIR_siga_js ?>email/md5.js"></script>
        <script src="<?php echo DIR_siga_js ?>email/ckeditor5-build-classic/ckeditor.js"></script>
        <style>
            /* NOTE: The styles were added inline because Prefixfree needs access to your styles and they must be inlined if they are on local disk! */
            @import url("<?php echo DIR_siga_css ?>email/Open+Sans+Condensed.css");


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
                overflow:hidden;
                max-height:20px;
                white-space: nowrap;
                /*border-left: 1px dotted #bbd3da;*/
                border-right: 1px dotted #bbd3da;
                font-size: 12px;
                text-overflow: ellipsis;
                /*max-height: 27px;*/
                /*max-width: 470px;*/
                /*overflow: no-display;*/
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
                /* min-height: 79%;
                 max-height: max-content;
                 height: min-content;*/
                position: fixed;
                margin: 10px;
                padding: 10px;
                border: 1px solid #999;
                box-shadow: 0 1px 8px #999;
                border-radius: 4px;
                background-color: #fff;
                overflow-y: scroll;
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
                padding: 5px 8px 2px 23px;
                height: 17px;

                box-shadow: 0 1px 4px #FF8040;
                border-radius: 2px;
                font-family: "Lucida Grande",Verdana,Arial,Helvetica,sans-serif;
                font-size: 10px;
                margin-bottom: 9px;
                width: max-content;
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
                /*min-height:900px;
                height:900px;
                max-height:900px;
                overflow:auto;*/
            }

            .msg_lida{
                background-color: #ccc;
            }
            .nova_pasta {
                display: block;
                color: #376572;
                text-shadow: 0 1px 1px #fff;
                text-decoration: none;
                cursor: default;
                padding: 1px 7px 6px 23px;
                height: 17px;
                box-shadow: 0 1px 4px #FF8040;
                border-radius: 2px;
                font-family: "Lucida Grande",Verdana,Arial,Helvetica,sans-serif;
                font-size: 10px;
                margin-bottom: 9px;
                width: max-content;
                background-color: #f9f9f9;
            }
            .modal_aviso{
                position: fixed;
                bottom: 20px;
                left: 50%;
                width: fit-content;
                height: 30px;
                background-color: rgba(60, 60, 60,0.9);
                border: 1px solid #ccc;
                text-align: center;
                color: #fff;
                border-radius: 10px;
                padding: 15px 10px 6px 10px;
                display: none;
            }
            .modal_envia_mensagem{
                position: fixed;
                left: 245px;
                top: 108px;
                width: calc(100% - 295px);
                height: 100%;
                z-index: 999999;
                background-color: #fff;
                display: none;
            }
            .div_envia_mensagem{
                position: fixed;
                top: 108px;
                left: 245px;
                width: calc(100% - 295px);
                height: calc(100% - 110px);
                border: 1px solid #999;
                box-shadow: 0 1px 8px #999;
                max-height: 100%;
                overflow: scroll;
            }
            .caixas_nova_mensagem_aberta{
                height: calc(100% - 36px)!important;
                z-index: 9999999;
                top: -4px;
                left: -2px;
            }

            .separador{
                display: inline-block;
                width: calc(100% - 20px);
                border-bottom: 1px solid rgba(188,188,188,0.8);
                height: 40px;
                margin-left: 8px;
                padding-left: 8px;
            }

            .separador input{
                width: 75%;
                height: 50%;
                margin-top: 12px;
            }
            .separador label{
                color: #9e9e9e;
                margin-top: 15px;
                display: inline-block;
            }

            .grid_bt_copia{
                width: auto;
                height: 15px;
                padding: 5px 12px 5px 15px;
                display: inline-block;
                border: 1px solid #598c52;
                color: #333;
                float: right;
                margin-top: 28px;
                margin-right: 10px;
                border-radius: 4px;
                margin: 8px 5px 0 0;
                font-weight: 700;
                cursor: pointer;
            }

            .grid_bt_copia:hover{
                color:#fff;
                border:1px solid #598c52;
                background-color:#98ce89
            }

            #separador_cc{
                display: none;
            }

            #separador_cco{
                display: none;
            }

            .btn_atualizar_pasta{
                float:right;
                clear: right;
                margin-right: 20px;
            }

            .auto_sugestao{
                clear: left;
                width: max-content;
                height: max-content;
                max-height: 500px;
                overflow-x: scroll;
                margin-left: 45px;
                border: 1px solid #ccc;
                z-index: 999;
                background-color: #fff;
                position: fixed;
                display: none;
            }

            .auto_sugestao .item{
                background-color: #EDEDED;
                width: max-content;
                height: max-content;
                margin: 10px;
                cursor: pointer;
                padding: 3px;
            }
            #caixa_pesquisa{
                float: left;
                margin-left: 10px;
                width: 400px;
                border: 1px solid #ccc;
            }
            ::-webkit-scrollbar { 
                display: none; 
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

            /* .cursor_carregando{
                 cursor: wait;
             }*/

            .over {
                border: 2px dashed #FF8040;
            }


        </style>

    </head>
    <body>

        <?php
        if (rodando_local) {
            $email = 'juliano.eugenio@site.com.br';
            $senha_email = '123456';
        }
        /* $bd = new BD_FB();
          $bd->open();
          $email = "";
          $sql_c_email = "SELECT email, senhaemail FROM usuario WHERE codusuario = " . $cod_usuario;
          $query_c_email = ibase_query($sql_c_email);
          if ($query_c_email) {
          $reg_c_email = ibase_fetch_assoc($query_c_email);
          $email = $reg_c_email['EMAIL'];
          $senha_email = $reg_c_email['SENHAEMAIL'];
          }
          $bd->close(); */
        ?>
        <div class="carregando_tela"><div class="carregando_tela_gif"><img src="<?php echo URL; ?>img/siga/carousel/AjaxLoader.gif"/></div></div>

        <div id="janelaModal" style="width: 400px;" class="reveal-modal"></div>
        <div id="janelaModal_novo_email" style="width: 500px; padding: 15px 20px 4px!important;" class="reveal-modal"></div>


        <div id="div_fundo_geral" style="display: block; position: fixed;top:0; left: 0; width: 100%; height: 100%; min-height: 100%; min-width: 100%; margin-bottom: 100px; overflow-x: hidden; overflow-y: hidden; background-color: #efefef;">



            <div id="div_form">
                <form style="margin-top: 200px;" action="" id="login_form">

                    <input type="text" name="" id="email" placeholder="Email" class="email" value="<?php echo $email; ?>">
                    <label style="margin-bottom: 10px" for=""></label>

                    <input type="password" name="" id="senha" placeholder="Senha" class="pass" value="<?php echo $senha_email; ?>">
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
                <a id="impressao" target="_blank" style="float:left; margin-left: 20px;" class="hide" href="#"><img title="Imprimir" src="<?php echo DIR_siga_img; ?>grid_bt_imprimir.png" style="width: 30px; height: 30px; padding-left: 10px;"/></a>   


                <a id="icone_sair" style="float:right; margin-right: 20px;" href="#"><img title="Sair" src="<?php echo DIR_siga_img; ?>email/sair.svg" style="width: 30px; height: 30px;"/><br/><label style="color:#000;">Sair</label></a>   

                <div style="float: right;margin-right: 30px;text-align: right;">
                    <a style="font-size: 12px;" href="#"><label style="color:#000;" id="nome_email"><?php echo $_SESSION['nome_conta_email']; ?></label></a>   
                    <div style="font-size: 12px;margin-top: 2px;">Forçar sincronização <input type="checkbox" id="forcar_atualizacao" /></div>   
                    <div style="font-size: 12px;margin-top: 2px;">Abrir email em tela cheia <input type="checkbox" id="abrir_tela_cheia" <?php
                        if ($_SESSION['maximizado_conta_email'] == 'S') {
                            echo 'checked="true"';
                        }
                        ?>/></div>   
                </div>

                <div style="float: right;margin-right: 30px;text-align: right;display:none;" id="select_transf">

                </div>

                <a id="voltar" style="float: right;margin-right: 30px;margin-top: 10px;font-size: 12px;display:none;" href="#"><img title="Voltar" src="<?php echo DIR_siga_img; ?>grid_bt_voltar.png" style="padding-left: 10px;"/><br/><label style="color:#000; margin-left: 5px;">Voltar</label></a> 

                <div style="float:right;margin: 15px 50px 0 0;" id="box_pesquisa">
                    <input type="text" id="caixa_pesquisa" /><a href="#" id="btn_pesquisar" style="margin-left: 5px;"><img title="Pesquisar" src="<?php echo DIR_siga_img; ?>grid_bt_exibir.png" style="width: 14px;"/></a>
                </div>
            </div>


            <div id="emails_x" style="float:left; display: block; overflow-x: hidden; overflow-y: auto; margin-bottom: 100px;">

                <div id="div_caixas" style="float: left; margin-left: 10px; background-color: #fff;" class="hide">

                </div>

                <div id="emails" style="float:left; display: block; margin-left:250px; margin-right: 50px; overflow: hidden; position: absolute; margin-bottom: 100px;">

                    <div class="modal_envia_mensagem">

                    </div>
                    <?php
                    $class_max = 'div_mensagem_minimizada';
                    if ($_SESSION['maximizado_conta_email'] == 'S') {
                        $class_max = 'div_mensagem_maximizada';
                    }
                    ?>

                    <div id="div_mensagens" class="hide" style="height:fit-content;margin-bottom:10px; /*min-height:450px;*/ overflow:auto; margin-top: 10px; min-width:1040px;">

                    </div>

                    <div id="div_mensagem" class="hide <?php echo $class_max; ?>">

                    </div>

                </div>
            </div>
        </div>
        <input type="hidden" id="pagina_atual"/>
        <input type="hidden" id="id"/>
        <input type="hidden" id="total_emails"/>
        <input type="hidden" id="carregar_caixa"/>
        <input type="hidden" id="cod_sessao"/>
        <input type="hidden" id="carregar_caixa_diff"/>
        <input type="hidden" id="nome_email_aux" value="<?php echo $_SESSION['nome_conta_email']; ?>"/>

        <div class="modal_aviso"></div>

        <?php
        /* require(DIR_siga_inc . "rodape.php");

          require(DIR_siga_inc . "pesquisa.php"); */
        ?>
        <link href="<?php echo DIR_siga_css ?>reveal.css" rel="stylesheet" type="text/css" />
        <script src="<?php echo DIR_siga_js ?>jquery16.js" type="text/javascript"></script>
        <script src="<?php echo DIR_siga_js ?>reveal/jquery.reveal.js"></script>
        <link href="<?php echo DIR_siga_css ?>showbox.css" rel="stylesheet" type="text/css" />


        <script type="text/javascript">
            var $a = jQuery.noConflict();
        </script>
        <script>

            function inicia_editor_texto_anexo() {
                /*ClassicEditor.create(document.querySelector('#editor')).catch(error => {
                 console.error(error);
                 });*/
                var extraObj = $("#fileuploader").uploadFile({
                    url: url + "ajax/ajax_email_anexo_send",
                    multiple: true,
                    dragDrop: true,
                    fileName: "myfile",
                    onSuccess: function (files, data, xhr, pd)
                    {
                        //alert(files);
                        anexos[anexos.length] = data;
                        enc[enc.length] = 'f';
                    }
                });
            }

            $(window).resize(function () {
                fun_ajusta_largura();
            });
            function fun_ajusta_largura() {
                var w = $(document).width() - 320;
                $('#tb_msgs').css('width', w);
                $('#div_mensagem').css('width', w);
                $('iframe').css('width', w);
                $('#tb_mensagens').css('width', w);
                //$('#tb_mensagem').css('width', w + 50);
                $('#div_msg_header').css('width', w - 10);
                $('#menu_superior').css('width', $(document).width() - 107);
                $('.tb_caixas').css('height', $(document).height() - 141);
                $('#div_mensagens').css('max-height', $(document).height() - 76);
                $('#div_fundo_geral').css('height', $(document).height());
                $('#div_fundo_geral').css('min-height', $(document).height());
                $('#div_fundo_geral').css('max-height', $(document).height());
            }
            $(window).bind('beforeunload', function () {
                $.ajax({
                    type: 'post',
                    data: {
                    },
                    url: url + 'ajax/ajax_email_transporta_sessao',
                    beforeSend: function () {

                    },
                    success: function () {

                    }
                });
            });
            $(document).ready(function (e) {

                fun_ajusta_largura();
<?php
if (isset($_SESSION['cod_conta_email']) && !empty($_SESSION['cod_conta_email']) && $_SESSION['cod_conta_email'] > 0) {
    ?>
                    var aux_conta = '<?php echo $_SESSION['cod_conta_email']; ?>';

                    $.ajax({
                        type: 'post',
                        data: {
                            'conta': aux_conta,
                            'sessao': '<?php echo $_SESSION['cod_sessao_tranporte']; ?>'
                        },
                        url: url + 'ajax/ajax_email_checa_sessao',
                        beforeSend: function () {

                        },
                        success: function (retorno) {
                            if (retorno == 'ok') {
                                $('#cod_sessao').val('<?php echo $_SESSION['cod_sessao_tranporte']; ?>');
                                carrega_conta(aux_conta, 'INBOX', 'N');
                                faz_backup(aux_conta, null, 'N');
                                id_atualizacao = setTimeout('atualiza_caixa_tempo("INBOX")', 300000);//5min
                            } else {
                                exibe_msg('Sessão finalizada.');
                                $('#icone_sair').trigger('click');
                            }
                        }
                    });
    <?php
} else {
    ?>
                    if ($('#email').val() != '' && $('#senha').val() != '') {
                        $('#login').trigger('click');
                    }
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
            var array_move_email = [];
            var total_mensagem_baixar = 0;
            var total_mensagem_baixadas = 0;
            // var array_geral = [[]];

            var controle_ajax_caixa = [[]];
            var ajax_rodando = 0;
            var id_nova_pasta = [];
            var novas_pastas = [];
            var offline = true;
            var global_array_caixas = null;
            var anexos = [];
            var enc = [];

            var id_atualizacao;

            var caixa_verificada = [];

            function atualiza_caixa_tempo(pasta) {
                var caixa = [];
                caixa[0] = pasta;
                //console.log('Atualizando caixa:' + caixa[0]);
                if (!executando_caixas[caixa[0]] && executando == false) {
                    var conta = $('#id').val();
                    $('#carregar_caixa_diff').val(pasta);
                    faz_backup(conta, caixa, 'N');
                }


                id_atualizacao = setTimeout('atualiza_caixa_tempo("' + pasta + '")', 300000);//5min
            }
            function pesquisa(texto) {
                var id = $('#id').val();
                var caixa = $('.tb_selectionada').attr('nome');
                carrega_mensagens(id, caixa, 1, texto);
            }
            $(document).on("keyup", "#caixa_pesquisa", function (e) {
                var texto = $(this).val();
                if (texto.length > 2) {
                    pesquisa(texto);
                }
            });
            $(document).on("click", "#btn_pesquisar", function (e) {
                pesquisa($("#caixa_pesquisa").val());
                return false;
            });
            $(document).on("click", ".btn_atualizar_pasta", function (e) {
                var caixa = [];
                caixa[0] = atob($(this).prop('id'));
                if (executando_caixas[caixa[0]]) {
                    exibe_msg('Atualização em andamento, aguarde...');
                } else {
                    var conta = $('#id').val();
                    $('#carregar_caixa').val(caixa[0]);
                    //carrega_conta(conta, caixa[0], 'N');

                    var forca_sinc = 'N';
                    if ($('#forcar_atualizacao').prop('checked')) {
                        forca_sinc = 'S';
                    }

                    faz_backup(conta, caixa, forca_sinc);
                }

                return false;
            });
            $(document).on("click", ".delete_anexo", function (e) {

                var id = (this.id) - 1;
                anexos[id] = '';
                $('#bd_anexo_' + this.id).html('');
                return false;
            });
            function getEmailSelecionados(uid) {
                var array_elementos = [];
                $('.email_selecionado').each(function () {
                    if ($(this).prop('checked')) {
                        var val = $(this).val();
                        if (val != uid) {
                            array_elementos[array_elementos.length] = val;
                        }
                    }
                });

                return array_elementos;
            }
            function limpa_string_email(string) {
                string = string.replaceAll('  ', ' ');
                string = string.replaceAll(', ', ',');
                string = string.replaceAll(' , ', ',');
                string = string.replaceAll(' ,', ',');
                string = string.replaceAll(',,', ',');
                string = string.replaceAll(',, ', ',');
                string = string.replaceAll(' ,, ', ',');
                string = string.replaceAll(' ,,', ',');
                string = string.replaceAll('; ', ',');
                string = string.replaceAll(' ; ', ',');
                string = string.replaceAll(' ;', ',');
                string = string.replaceAll(';;', ',');
                string = string.replaceAll(';; ', ',');
                string = string.replaceAll(' ;; ', ',');
                string = string.replaceAll(' ;;', ',');
                string = string.replaceAll(' ', ',');
                string = string.replaceAll(';', ',');
                string = string.replaceAll('  ', ' ');
                string = string.replaceAll(', ', ',');
                string = string.replaceAll(' , ', ',');
                string = string.replaceAll(' ,', ',');
                string = string.replaceAll(',,', ',');
                string = string.replaceAll(',, ', ',');
                string = string.replaceAll(' ,, ', ',');
                string = string.replaceAll(' ,,', ',');
                string = string.replaceAll('; ', ',');
                string = string.replaceAll(' ; ', ',');
                string = string.replaceAll(' ;', ',');
                string = string.replaceAll(';;', ',');
                string = string.replaceAll(';; ', ',');
                string = string.replaceAll(' ;; ', ',');
                string = string.replaceAll(' ;;', ',');
                return string;
            }
            $(document).on('click', 'body', function () {
                $('#auto_sugestao_para').html('');
                $('#auto_sugestao_para').hide();
            });
            $(document).on('focusin', '.auto_sugestao .item', function () {
                $(this).css('background-color', '#ccc');
            });
            $(document).on('focusout', '.auto_sugestao .item', function () {
                $(this).css('background-color', '#EDEDED');
            });
            $(document).on('keyup', '.auto_sugestao .item', function (e) {
                if (e.keyCode == 13) {
                    seleciona_email_sugestao($(this).attr('rel'), $(this).prop('id'));
                }
            });
            $(document).on('click', '.auto_sugestao .item', function () {
                seleciona_email_sugestao($(this).attr('rel'), $(this).prop('id'));
            });
            function seleciona_email_sugestao(rel, id) {
                $('#auto_sugestao_' + rel).html('');
                $('#auto_sugestao_' + rel).hide();

                var email = limpa_string_email($('#novo_email_' + rel).val());
                email = email.split(',');

                var aux_email = '';
                for (var i = 0; i < email.length - 1; i++) {

                    aux_email += email[i] + ',';


                }

                $('#novo_email_' + rel).val(aux_email + id + ',');
                $('#novo_email_' + rel).focus();
                $('.auto_sugestao').removeClass('selecao_ativa');
            }

            $(document).keydown(function (e) {
                if ($('.auto_sugestao').hasClass('selecao_ativa')) {
                    //console.log(e.keyCode);
                    if (e.keyCode == 38) { // UP
                        if ($('.focused').prev('.focusable').length)
                            $('.focused').removeClass('focused').prev('.focusable').focus().addClass('focused');
                    }
                    if (e.keyCode == 40) { // DOWN
                        if ($('.focused').hasClass('focused')) {
                            if ($('.focused').next('.focusable').length)
                                $('.focused').removeClass('focused').next('.focusable').focus().addClass('focused');
                        } else {
                            $('.focusable').first().focus().addClass('focused');
                        }
                    }
                }
            });
            $(document).on('keyup', '#novo_email_para, #novo_email_cc, #novo_email_cco', function () {
                var email = limpa_string_email($(this).val());
                email = email.split(',');
                email = email[email.length - 1];
                if (email.length >= 3) {
                    var id = $(this).prop('id').split('_');
                    id = id[id.length - 1];
                    $.ajax({
                        type: 'post',
                        data: {
                            'conta': $('#id').val(),
                            'email': email,
                            'elemento': id
                        },
                        url: url + 'ajax/ajax_email_auto_completa',
                        beforeSend: function () {

                        },
                        success: function (retorno) {


                            $('#auto_sugestao_' + id).html(retorno);
                            $('#auto_sugestao_' + id).show();
                            $('.auto_sugestao').addClass('selecao_ativa');

                            //$('.focused').focus();
                        }
                    });

                }
            });

            $(document).on('click', '#btn_enviar', function () {
                var iframe = document.getElementById("iframe_mensagem_tela_monta_email");


                var para = $('#novo_email_para').val();
                var copia = $('#novo_email_cc').val();
                var copia_oculta = $('#novo_email_cco').val();
                var assunto = $('#novo_email_assunto').val();
                var mensagem = iframe.contentWindow.document.getElementById("mensagem_tela_monta_email_edit").innerHTML;
                $('#novo_email_para').css('border', 'none');
                $('#novo_email_assunto').css('border', 'none');
                $('#mensagem_tela_monta_email_edit').css('border', 'none');
                var continua = true;

                if (para.substring(para.length - 1, para.length) == ',') {
                    para = para.substring(0, para.length - 1);
                    $('#novo_email_para').val(para);
                }

                if (copia.substring(copia.length - 1, copia.length) == ',') {
                    copia = copia.substring(0, copia.length - 1);
                    $('#novo_email_cc').val(copia);
                }

                if (copia_oculta.substring(copia_oculta.length - 1, copia_oculta.length) == ',') {
                    copia_oculta = copia_oculta.substring(0, copia_oculta.length - 1);
                    $('#novo_email_cco').val(copia_oculta);
                }


                if (para == "") {
                    $("#novo_email_para").focus();
                    $('#novo_email_para').css('border', '1px solid red');
                    continua = false;
                } else {

                    para = limpa_string_email(para);
                    if (para.search(',') > 0) {
                        var para_ex = para.split(',');
                        for (var i = 0; i < para_ex.length; i++) {
                            if (!validacaoEmail(para_ex[i]) || para_ex[i] == '')
                            {
                                $("#novo_email_para").focus();
                                $('#novo_email_para').css('border', '1px solid red');
                                continua = false;
                            }
                        }
                    } else {
                        if (!validacaoEmail(para))
                        {

                            $("#novo_email_para").focus();
                            $('#novo_email_para').css('border', '1px solid red');
                            continua = false;
                        }
                    }
                }

                if (copia != "") {
                    copia = limpa_string_email(copia);
                    if (copia.search(',') > 0) {
                        var copia_ex = copia.split(',');
                        for (var i = 0; i < copia_ex.length; i++) {
                            if (!validacaoEmail(copia_ex[i]) || copia_ex[i] == '')
                            {
                                $("#novo_email_cc").focus();
                                $('#novo_email_cc').css('border', '1px solid red');
                                continua = false;
                            }
                        }
                    } else {
                        if (!validacaoEmail(copia))
                        {

                            $("#novo_email_cc").focus();
                            $('#novo_email_cc').css('border', '1px solid red');
                            continua = false;
                        }
                    }
                }

                if (copia_oculta != "") {
                    copia_oculta = limpa_string_email(copia_oculta);
                    if (copia_oculta.search(',') > 0) {
                        var copia_oculta_ex = copia_oculta.split(',');
                        for (var i = 0; i < copia_oculta_ex.length; i++) {
                            if (!validacaoEmail(copia_oculta_ex[i]) || copia_oculta_ex[i] == '')
                            {
                                $("#novo_email_cco").focus();
                                $('#novo_email_cco').css('border', '1px solid red');
                                continua = false;
                            }
                        }
                    } else {
                        if (!validacaoEmail(copia_oculta))
                        {

                            $("#novo_email_cco").focus();
                            $('#novo_email_cco').css('border', '1px solid red');
                            continua = false;
                        }
                    }
                }

                if (assunto == "") {
                    if (continua) {
                        $("#novo_email_assunto").focus();
                    }
                    $('#novo_email_assunto').css('border', '1px solid red');
                    continua = false;
                }


                if (mensagem == "" || mensagem == '<p><br data-cke-filler="true"></p>') {
                    if (continua) {
                        $("#mensagem_tela_monta_email_edit").focus();
                    }
                    $('#mensagem_tela_monta_email_edit').css('border', '1px solid red');
                    continua = false;
                }

                if (continua == false) {
                    return false;
                }

                $.ajax({
                    type: 'post',
                    data: {
                        'para': para,
                        'copia': copia,
                        'copia_oculta': copia_oculta,
                        'assunto': assunto,
                        'mensagem': mensagem,
                        'codemailusuario': $('#id').val(),
                        'enc': enc,
                        'file': anexos
                    },
                    url: url + 'ajax/ajax_email_envia_email',
                    beforeSend: function () {
                        $("#carregando_envio_email").show();
                        $("#btn_enviar").hide();
                        exibe_msg('Enviando email...');
                    },
                    success: function (retorno) {
                        if (retorno == 'ok') {
                            exibe_msg('Email enviado com sucesso.');
                            $('#novo_email_para').val('');
                            $('#novo_email_cc').val('');
                            $('#novo_email_cco').val('');
                            $('#novo_email_assunto').val('');
                            $('.ck-editor__editable').html('');
                            enc = [];
                            anexos = [];
                            $('#anexo_div_ajax').html('');
                            $('.modal_envia_mensagem').hide();
                            $('.modal_envia_mensagem').html('');
                            $('.tb_caixas').removeClass('caixas_nova_mensagem_aberta');
                            var array_caixa_at = [];
                            array_caixa_at[0] = 'INBOX.enviadas';
                            faz_backup($('#id').val(), array_caixa_at, 'N');

                            $('.tb_selectionada').trigger('click');
                        } else {
                            $("#carregando_envio_email").hide();
                            $("#btn_enviar").show();
                            exibe_msg(retorno);
                        }
                    }
                });
                return false;
            });
            $(document).on('click', '#btn_add_cco', function () {
                $('#separador_cco').show();
                $(this).hide();
            });
            $(document).on('click', '#btn_add_cc', function () {
                $('#separador_cc').show();
                $(this).hide();
            });
            $(document).on('focusin', '.separador input', function () {
                $(this).parent('.separador').animate({
                    height: "50px"
                }, 400, function () {
                    // Animation complete.
                });
            });
            $(document).on('focusout', '.separador input', function () {
                $(this).parent('.separador').animate({
                    height: "40px"
                }, 300, function () {
                    // Animation complete.
                });
            });
            $(document).on('click', '#abrir_tela_cheia', function () {
                var maximizado = $(this).prop('checked');
                if (maximizado) {
                    maximizado = 'S';
                } else {
                    maximizado = 'N';
                }
                $.ajax({
                    type: 'post',
                    data: {
                        'codemailusuario': $('#id').val(),
                        'maximizado': maximizado
                    },
                    url: url + 'ajax/ajax_set_abre_maximizado',
                    beforeSend: function () {
                    },
                    success: function (retorno) {
                        if (retorno != 'ok') {
                            exibe_msg(retorno);
                        }
                    }
                });
            });
            $(document).on('click', '#box_bt_sim_aviso_excluir_caixa', function () {
                $a('.close-reveal-modal').trigger('click');
                var id = $(this).attr('rel');
                var caixa = $('#' + id).attr('nome');
                $.ajax({
                    type: 'post',
                    data: {
                        'codemailusuario': $('#id').val(),
                        'caixa': caixa
                    },
                    url: url + 'ajax/ajax_exclui_caixa',
                    beforeSend: function () {
                    },
                    success: function (retorno) {
                        if (retorno == 'ok') {
                            carrega_conta($('#id').val(), 'INBOX', 'N');
                        } else {
                            alert(retorno);
                        }
                    }
                });
                return false;
            });
            $(document).on('click', '.remove_caixa', function () {
                var id = $(this).prop('id').replaceAll('exclui_edicao_caixa_', '');
                $a("#janelaModal").html(msg_box_aviso("confirm", 'Deseja realmente excluir esta caixa e todos os emais dentro dela?', "aviso_excluir_caixa", id));
                $a("#janelaModal").reveal();
            });
            $(document).on('click', '.salva_caixa', function () {
                var id = $(this).prop('id').replaceAll('salva_edicao_caixa_', '');
                var nome_antigo = $('#' + id).attr('nome');
                var nome_novo = $('#input_edicao_caixa_' + id).val();
                var nome_novo_aux = '';
                var nome_antigo_aux = nome_antigo.split('.');
                for (var i = 0; i < nome_antigo_aux.length - 1; i++) {
                    nome_novo_aux += nome_antigo_aux[i] + '.';
                }
                nome_novo_aux += nome_novo;
                $.ajax({
                    type: 'post',
                    data: {
                        'codemailusuario': $('#id').val(),
                        'nome_antigo': nome_antigo,
                        'nome_novo': nome_novo
                    },
                    url: url + 'ajax/ajax_renomeia_caixa',
                    beforeSend: function () {
                    },
                    success: function (retorno) {
                        if (retorno == 'ok') {
                            carrega_conta($('#id').val(), nome_novo_aux, 'N');
                        } else {
                            alert(retorno);
                        }
                    }
                });
            });
            $(document).on('dblclick', '.modal_aviso', function () {
                fecha_msg();
            });
            $(document).on('click', '#gerencia_caixa_edicao', function () {
                if (!executando) {
                    var caixa = $('.tb_selectionada').attr('nome');
                    if (!$(this).hasClass('aberto')) {
                        carrega_conta($('#id').val(), caixa, 'S');
                    } else {
                        carrega_conta($('#id').val(), caixa, 'N');
                    }
                } else {
                    exibe_msg('Atualização em execução, aguarde a finalização.');
                }
            });
            function exibe_msg(msg) {
                $('.modal_aviso').hide();
                $('.modal_aviso').html(msg);
                $('.modal_aviso').show(100);
                setTimeout(fecha_msg, 10000);
            }
            function fecha_msg() {
                $('.modal_aviso').hide(300);
            }
            $(document).on('click', '.add_caixa', function () {
                var id_ar = $(this).prop('id').split('_');
                var id = '#input_caixa_' + id_ar[2] + '_' + id_ar[3];
                var nome_caixa = $(id).val();
                if (nome_caixa != '') {
                    var aux_nova_pasta = [];
                    var index = 0;
                    var achou = false;
                    for (var i = 0; i < novas_pastas.length; i++) {
                        aux_nova_pasta = novas_pastas[i];
                        if (aux_nova_pasta['caixa_md5'] == id_ar[2] && aux_nova_pasta['id'] == id_ar[3]) {
                            index = i;
                            achou = true;
                            break;
                        }
                    }
                    if (achou) {
                        aux_nova_pasta = [];
                        aux_nova_pasta = novas_pastas[index];
                        $.ajax({
                            type: 'post',
                            data: {
                                'codemailusuario': $('#id').val(),
                                'pasta': aux_nova_pasta['caixa'],
                                'nome': nome_caixa
                            },
                            url: url + 'ajax/ajax_adiciona_caixa',
                            beforeSend: function () {
                            },
                            success: function (retorno) {
                                if (retorno == 'ok') {
                                    novas_pastas.splice(index, 1);
                                    if (novas_pastas.length == 0) {
                                        carrega_conta($('#id').val(), 'INBOX', 'N');
                                        faz_backup($('#id').val(), '', 'N');
                                    } else {
                                        $(id).prop('disabled', true);
                                        $('#add_caixa_' + id_ar[2] + '_' + id_ar[3]).remove();
                                        $('#rem_caixa_' + id_ar[2] + '_' + id_ar[3]).remove();
                                    }
                                } else {
                                    alert(retorno);
                                }
                            }
                        });
                    } else {
                        alert('Erro, dados não encontrados.');
                    }

                } else {
                    alert('Digite o nome da nova caixa.');
                }
                return false;
            });
            $(document).on('click', '.remove_nova_caixa', function () {
                var id_ar = $(this).prop('id').split('_');
                var id = '#div_caixa_' + id_ar[2] + '_' + id_ar[3];
                $(id).remove();
                var aux_nova_pasta = [];
                for (var i = 0; i < novas_pastas.length; i++) {
                    aux_nova_pasta = novas_pastas[i];
                    if (aux_nova_pasta['caixa_md5'] == id_ar[2] && aux_nova_pasta['id'] == id_ar[3]) {
                        novas_pastas.splice(i, 1);
                    }
                }
                return false;
            });
            $(document).on('click', '#gerencia_caixa', function () {
                var caixa_pai = $('.tb_selectionada').attr('nome');
                var caixa_pai_md5 = $('.tb_selectionada').prop('id');
                var aux_nova_pasta = [];
                if (id_nova_pasta[caixa_pai_md5] == undefined) {
                    id_nova_pasta[caixa_pai_md5] = 0;
                }

                var html = "<li>";
                html += "<div id='div_caixa_" + caixa_pai_md5 + "_" + id_nova_pasta[caixa_pai_md5] + "' class='nova_pasta' style='background-image: url(" + url + "img/siga/email/pasta.svg); background-repeat: no-repeat; background-size: 20px;'>";
                html += "<input type='text' id='input_caixa_" + caixa_pai_md5 + "_" + id_nova_pasta[caixa_pai_md5] + "' style='height: 10px;margin: 0 0 0 0;padding: 5px 3px 4px 5px;border: 1px solid #ccc;font-size: 12px;color: #777;display: inline-block;width:125px;'/>";
                html += "<input type='hidden' id='input_caixa_nome_" + caixa_pai_md5 + "_" + id_nova_pasta[caixa_pai_md5] + "' value='" + caixa_pai + "'>";
                html += "<a href='#' id='add_caixa_" + caixa_pai_md5 + "_" + id_nova_pasta[caixa_pai_md5] + "' class='add_caixa'><img src='" + url + "img/siga/grid_bt_finalizar.png' /></a>";
                html += "<a href='#' id='rem_caixa_" + caixa_pai_md5 + "_" + id_nova_pasta[caixa_pai_md5] + "' class='remove_nova_caixa'><img style='margin-left:10px;' src='" + url + "img/siga/grid_bt_fechar_ver.png' /></a>";
                html += "</a>";
                html += "</div>";
                $('.tb_selectionada').parent('li').append(html);
                aux_nova_pasta['caixa'] = caixa_pai;
                aux_nova_pasta['caixa_md5'] = caixa_pai_md5;
                aux_nova_pasta['id'] = id_nova_pasta[caixa_pai_md5];
                novas_pastas[novas_pastas.length] = aux_nova_pasta;
                id_nova_pasta[caixa_pai_md5]++;
            });
            $(document).on('click', '.email_selecionado', function () {
                var selecionado = false;
                $('.email_selecionado').each(function () {
                    if ($(this).prop('checked')) {
                        selecionado = true;
                    }
                });
                if (selecionado) {
                    var link = document.getElementById("icone_lixo");
                    link.setAttribute("href", '#');
                    if ($('#icone_lixo').hasClass('hide')) {
                        $('#icone_lixo').removeClass('hide');
                    }

                    if (global_array_caixas == null) {
                        global_array_caixas = [];
                        $(".listing_link").each(function () {
                            global_array_caixas[global_array_caixas.length] = $(this).attr('nome');
                        });
                    }


                    var aux_cmb_tranf = '<select class="" style="border: 1px solid;" id="select_caixa_para">';
                    aux_cmb_tranf += '<option value="0" selected="selected">Transferir email</option>';

                    var aux_cmb_caixa_nome = '';
                    for (var i = 0; i < global_array_caixas.length; i++) {
                        aux_cmb_caixa_nome = global_array_caixas[i].split('.');
                        aux_cmb_tranf += '<option value="' + global_array_caixas[i] + '">' + aux_cmb_caixa_nome[aux_cmb_caixa_nome.length - 1] + '</option>';
                    }

                    aux_cmb_tranf += '</select>';
                    aux_cmb_tranf += '<br><a href="#" style="margin: 8px 2px 0px 0px;float: right;" id="tranferir_email_cmb"><img src="' + url + 'img/siga/grid_bt_transf_feed.png" /></a>';

                    $('#select_transf').html(aux_cmb_tranf);
                    $('#select_transf').show();

                    link = document.getElementById("tranferir_email_cmb");
                    link.setAttribute("href", $('#id').val() + '||' + $('.tb_selectionada').attr('nome'));



                } else {
                    if (!$('#icone_lixo').hasClass('hide')) {
                        $('#icone_lixo').addClass('hide');
                    }
                    $('#select_transf').html('');
                    $('#select_transf').show();
                }
            });
            $(document).on('click', '#seleciona_todos_emails', function () {
                var val = $(this).prop('checked');
                $('.email_selecionado').each(function () {
                    $(this).prop('checked', val);
                });
                if (val) {
                    var link = document.getElementById("icone_lixo");
                    link.setAttribute("href", '#');
                    if ($('#icone_lixo').hasClass('hide')) {
                        $('#icone_lixo').removeClass('hide');
                    }
                } else {
                    if (!$('#icone_lixo').hasClass('hide')) {
                        $('#icone_lixo').addClass('hide');
                    }
                }
            });
            $(document).on('click', '#para_execucao', function () {
                var forca_sinc = 'N';
                if ($('#forcar_atualizacao').prop('checked')) {
                    forca_sinc = 'S';
                }
                finaliza_execucao(forca_sinc);
                executando = false;
                array_move_email = [];
                //$('#porc_exec').html('');
                $('#btn_atualiza_conta_unica').html('<img src="<?php echo URL; ?>img/siga/email/update.svg" style="width: 30px; height: 30px; padding-left: 10px;"/><br/><label style="color:#000;">Atualizar</label>');
                $('.listing_link').each(function () {
                    var caixa = $(this).prop('id');
                    var caixa_nome_attr = $(this).attr('nome');
                    executando_caixas[caixa_nome_attr] = false;
                    var caixa_ex = caixa_nome_attr.split('.');
                    var caixa_nome = caixa_nome_attr;
                    if (caixa_ex.length > 1) {
                        caixa_nome = caixa_ex[caixa_ex.length - 1];
                    }
                    $('#forcar_atualizacao').prop('disabled', false);
                    $(this).html(caixa_nome + '<img class="carregando" id="carregando_' + caixa + '" style="display:none;" src="' + url + 'img/load_foto_tem.gif" />' + '<img style="display:none;" class="finalizado" id="finalizado_' + caixa + '" src="' + url + 'img/siga/bt_ativar.png" />');
                });
                return false;
            });
            function faz_backup(id, caixas, forcado) {
                executando = true;
                $('#forcar_atualizacao').prop('disabled', true);
                //$('#porc_exec').html('0%');
                $('#btn_atualiza_conta_unica').html('<a href="#" id="para_execucao"><img src="<?php echo URL; ?>img/load_foto_tem.gif" style="width: 30px; height: 30px; padding-left: 10px;"/><br/><label style="color:#000;">Atualizando</label></a>');
                $.ajax({
                    type: 'post',
                    data: {
                        'codemailusuario': id,
                        'sessao': $('#cod_sessao').val()
                    },
                    url: url + 'ajax/ajax_gera_lista_emails_baixar',
                    async: true,
                    beforeSend: function () {
                        ajax_rodando++;
                    },
                    success: function (retorno) {
                        if (retorno == 'sessao') {
                            exibe_msg('Sessão finalizada.');
                            $('#icone_sair').trigger('click');
                        } else {
                            var array_ret = retorno.split('|');
                            if (array_ret[0] == 'ok') {
                                var array_caixas = $.parseJSON(array_ret[1]);
                                // var array_caixas_total = $.parseJSON(array_ret[2]);
                                //total_tb = $.parseJSON(array_ret[3]);
                                total_mensagem_baixar = 0;
                                if (caixas != null && caixas.length > 0) {
                                    var aux_caixas_ex = [];
                                    for (var i = 0; i < array_caixas.length; i++) {
                                        for (var j = 0; j < caixas.length; j++) {
                                            if (array_caixas[i] == caixas[j]) {
                                                aux_caixas_ex[aux_caixas_ex.length] = array_caixas[i];
                                            }
                                        }
                                    }
                                    array_caixas = aux_caixas_ex;
                                }
                                for (var i = 0; i < array_caixas.length; i++) {
                                    total_emails_baixar_caixa[array_caixas[i]] = 0;
                                    emails_baixados_caixa[array_caixas[i]] = 0;
                                    emails_baixados_caixa_geral[array_caixas[i]] = 0;
                                    executando_caixas[array_caixas[i]] = true;
                                    caixa_verificada[array_caixas[i]] = false;
                                }
                                faz_backup_caixa_pagina(id, array_caixas, -1, 0, forcado);
                            } else {
                                //console.log(array_ret[1]);
                                exibe_msg('Erro ao gerar lista emails baixar:' + retorno);
                            }
                        }
                        ajax_rodando--;

                    }
                });
            }
            function finaliza_execucao(forcado) {
                executando = false;
                if (forcado == 'S') {
                    $.ajax({
                        type: 'post',
                        data: {
                            'codemailusuario': $('#id').val()
                        },
                        url: url + 'ajax/ajax_sincroniza_exclui_email',
                        async: true,
                        beforeSend: function () {
                        },
                        success: function (retorno) {
                            if (retorno != 'ok') {
                                exibe_msg(retorno);
                            }
                            //$('#porc_exec').html('');
                            $('#btn_atualiza_conta_unica').html('<img src="<?php echo URL; ?>img/siga/email/update.svg" style="width: 30px; height: 30px; padding-left: 10px;"/><br/><label style="color:#000;">Atualizar</label>');
                            for (var i = 0; i < array_move_email.length; i++) {
                                var aux_array_move_email = array_move_email[i];
                                move_email_array(aux_array_move_email['uid_array'], aux_array_move_email['de'], aux_array_move_email['para'], aux_array_move_email['conta'], aux_array_move_email['exclui'], false);
                            }
                            array_move_email = [];
                            $('#forcar_atualizacao').prop('checked', false);


                            $('#forcar_atualizacao').prop('disabled', false);
                            if ($('#carregar_caixa').val() != '') {
                                carrega_conta($('#id').val(), $('#carregar_caixa').val(), 'N');
                                $('#carregar_caixa').val('');
                            } else if ($('#carregar_caixa_diff').val() != '' && $('#carregar_caixa_diff').val() == $('.tb_selectionada').attr('nome') && total_emails_baixar_caixa[$('#carregar_caixa_diff').val()] > 0) {

                                add_mensagens($('#id').val(), $('#carregar_caixa_diff').val(), total_emails_baixar_caixa[$('#carregar_caixa_diff').val()], '0');
                                $('#carregar_caixa_diff').val('');
                            }
                        }
                    });
                } else {
                    //$('#porc_exec').html('');
                    $('#btn_atualiza_conta_unica').html('<img src="<?php echo URL; ?>img/siga/email/update.svg" style="width: 30px; height: 30px; padding-left: 10px;"/><br/><label style="color:#000;">Atualizar</label>');
                    for (var i = 0; i < array_move_email.length; i++) {
                        var aux_array_move_email = array_move_email[i];
                        move_email_array(aux_array_move_email['uid_array'], aux_array_move_email['de'], aux_array_move_email['para'], aux_array_move_email['conta'], aux_array_move_email['exclui'], false);
                    }
                    array_move_email = [];

                    $('#forcar_atualizacao').prop('disabled', false);
                    if ($('#carregar_caixa').val() != '') {
                        carrega_conta($('#id').val(), $('#carregar_caixa').val(), 'N');
                        $('#carregar_caixa').val('');
                    } else if ($('#carregar_caixa_diff').val() != '' && $('#carregar_caixa_diff').val() == $('.tb_selectionada').attr('nome') && total_emails_baixar_caixa[$('#carregar_caixa_diff').val()] > 0) {

                        add_mensagens($('#id').val(), $('#carregar_caixa_diff').val(), total_emails_baixar_caixa[$('#carregar_caixa_diff').val()], '0');
                        $('#carregar_caixa_diff').val('');
                    }
                }

            }
            function faz_backup_caixa_pagina(id, array_caixas, pagina, c, forcado) {

                if (c < array_caixas.length) {
                    var caixa = array_caixas[c];

                    if (forcado == 'S' && caixa_verificada[caixa] == false) {
                        caixa_verificada[caixa] = true;

                        $.ajax({
                            type: 'post',
                            async: false,
                            data: {
                                'codemailusuario': id,
                                'caixa': caixa
                            },
                            url: url + 'ajax/ajax_marca_emails_chk',
                            beforeSend: function () {
                                ajax_rodando++;
                            },
                            success: function (retorno) {
                                if (retorno != 'ok') {
                                    exibe_msg(retorno);
                                }
                                ajax_rodando--;
                            }
                        });

                    }

                    // if (caixa == 'INBOX') {
                    $.ajax({
                        type: 'post',
                        data: {
                            'codemailusuario': id,
                            'caixa': caixa,
                            'forcado': forcado
                        },
                        url: url + 'ajax/ajax_verifica_novos_emails',
                        async: true,
                        beforeSend: function () {
                            ajax_rodando++;
                            if (executando_caixas[caixa]) {
                                controla_pasta_status(caixa, true);
                            }
                        },
                        success: function (retorno) {
                            var array_ret = retorno.split('|');
                            if (array_ret[0] == 'ok') {
                                if (total_emails_baixar_caixa[caixa] == 0) {
                                    total_emails_baixar_caixa[caixa] = parseInt(array_ret[1]);
                                }
                                if (total_emails_baixar_caixa[caixa] > 0) {

                                    var total_paginas = Math.ceil(total_emails_baixar_caixa[caixa] / paginas_por_caixa);
                                    if (total_paginas < 1) {
                                        total_paginas = 1;
                                    }

                                    if (pagina == -1) {
                                        pagina = total_paginas;
                                    }

                                    $.ajax({
                                        type: 'post',
                                        data: {
                                            'codemailusuario': id,
                                            'caixa': caixa,
                                            'pagina': pagina,
                                            'qtn': paginas_por_caixa,
                                            'forcado': forcado,
                                            'sessao': $('#cod_sessao').val()
                                        },
                                        url: url + 'ajax/ajax_carrega_lista_emails_baixar',
                                        async: true,
                                        beforeSend: function () {
                                            ajax_rodando++;
                                            /*if (executando_caixas[caixa]) {
                                             controla_pasta_status(caixa, true);
                                             }*/
                                        },
                                        success: function (retorno) {
                                            if (retorno == 'sessao') {
                                                exibe_msg('Sessão finalizada.');
                                                $('#icone_sair').trigger('click');
                                            } else {
                                                var array_ret = retorno.split('|');
                                                if (array_ret[0] == 'ok') {
                                                    array_ret = $.parseJSON(array_ret[1]);
                                                    if (array_ret[caixa].length > 0 && executando) {
                                                        monta_pacote_ajax(array_ret[caixa], caixa, pagina, array_caixas, c, forcado);
                                                    } else if (forcado == 'S' && executando && pagina > 1) {
                                                        emails_baixados_caixa_geral[caixa] += paginas_por_caixa;
                                                        var caixa_md5 = CryptoJS.MD5(caixa).toString();
                                                        if ($('#' + caixa_md5).attr('nome') != undefined) {

                                                            var caixa_ex = $('#' + caixa_md5).attr('nome').split('.');
                                                            var caixa_nome = $('#' + caixa_md5).attr('nome');
                                                            if (caixa_ex.length > 1) {
                                                                caixa_nome = caixa_ex[caixa_ex.length - 1];
                                                            }
                                                            $('#' + caixa_md5).html(caixa_nome + ' ' + (emails_baixados_caixa_geral[caixa] + 1) + ' de ' + total_emails_baixar_caixa[caixa]);
                                                        }
                                                        pagina--;
                                                        faz_backup_caixa_pagina($('#id').val(), array_caixas, pagina, c, forcado);
                                                    } else {
                                                        if (forcado == 'S') {
                                                            var caixa_md5 = CryptoJS.MD5(caixa).toString();
                                                            if ($('#' + caixa_md5).attr('nome') != undefined) {

                                                                var caixa_ex = $('#' + caixa_md5).attr('nome').split('.');
                                                                var caixa_nome = $('#' + caixa_md5).attr('nome');
                                                                if (caixa_ex.length > 1) {
                                                                    caixa_nome = caixa_ex[caixa_ex.length - 1];
                                                                }

                                                                $('#' + caixa_md5).html(caixa_nome + '<img class="carregando" id="carregando_' + caixa_md5 + '" style="display:none;" src="' + url + 'img/load_foto_tem.gif" />' + '<img class="finalizado" id="finalizado_' + caixa_md5 + '" src="' + url + 'img/siga/bt_ativar.png" />');
                                                            }
                                                        }

                                                        if (executando) {
                                                            executando_caixas[caixa] = false;
                                                            c++;
                                                            if (c < array_caixas.length) {
                                                                //total_paginas = Math.ceil(total_emails_baixar_caixa[array_caixas[c]] / paginas_por_caixa);
                                                                faz_backup_caixa_pagina($('#id').val(), array_caixas, -1, c, forcado);
                                                            }


                                                            var finalizou_tudo = true;
                                                            controla_pasta_status(caixa, false);
                                                            for (property in executando_caixas) {
                                                                if (executando_caixas[property]) {
                                                                    finalizou_tudo = false;
                                                                }
                                                            }


                                                            if (finalizou_tudo) {
                                                                finaliza_execucao(forcado);
                                                            }
                                                        }
                                                    }
                                                } else {
                                                    exibe_msg('Erro ao carregar lista de emails a baixar Caixa:' + caixa + ' ' + retorno);
                                                    executando_caixas[caixa] = false;
                                                    controla_pasta_status(caixa, false);
                                                    controla_pasta_erro(caixa, true);
                                                    c++;
                                                    if (c < array_caixas.length && executando) {
                                                        faz_backup_caixa_pagina(id, array_caixas, -1, c, forcado);
                                                    }
                                                }
                                            }
                                            ajax_rodando--;
                                        }
                                    });
                                } else {
                                    if (executando) {
                                        executando_caixas[caixa] = false;
                                        c++;
                                        if (c < array_caixas.length) {
                                            //total_paginas = Math.ceil(total_emails_baixar_caixa[array_caixas[c]] / paginas_por_caixa);
                                            faz_backup_caixa_pagina($('#id').val(), array_caixas, -1, c, forcado);
                                        }


                                        var finalizou_tudo = true;
                                        controla_pasta_status(caixa, false);
                                        for (property in executando_caixas) {
                                            if (executando_caixas[property]) {
                                                finalizou_tudo = false;
                                            }
                                        }


                                        if (finalizou_tudo) {
                                            finaliza_execucao(forcado);
                                        }
                                    }
                                }
                            } else {
                                exibe_msg('Erro ao verificar novos emails Caixa:' + caixa + ' ' + retorno);
                                if (caixa != undefined && caixa != '') {
                                    executando_caixas[caixa] = false;
                                    controla_pasta_status(caixa, false);
                                    controla_pasta_erro(caixa, true);
                                    c++;
                                    if (c < array_caixas.length && executando) {
                                        faz_backup_caixa_pagina(id, array_caixas, -1, c, forcado);
                                    }
                                }
                            }
                            ajax_rodando--;
                        }
                    });
                    /*  } else {
                     c++;
                     console.log(c);
                     if (c < array_caixas.length) {
                     var total_paginas = Math.ceil(total_emails_baixar_caixa[array_caixas[c]] / paginas_por_caixa);
                     
                     faz_backup_caixa_pagina(id, array_caixas, total_paginas, c);
                     }
                     }*/
                } else {
                    if (pagina > 1) {
                        pagina--;
                        faz_backup_caixa_pagina($('#id').val(), array_caixas, pagina, 0, forcado);
                    } else {

                        executando_caixas[caixa] = false;
                        var finalizou_tudo = true;
                        controla_pasta_status(caixa, false);
                        for (property in executando_caixas) {
                            if (executando_caixas[property]) {
                                finalizou_tudo = false;
                            }
                        }


                        if (finalizou_tudo) {
                            finaliza_execucao(forcado);
                        }

                    }
                }
            }
            function controla_pasta_erro(caixa, status) {
                caixa = CryptoJS.MD5(caixa).toString();
                if (status) {
                    $('.carregando').each(function () {
                        if ($(this).prop('id') == 'carregando_' + caixa) {
                            $(this).hide();
                        }
                    });
                    $('.finalizado').each(function () {
                        if ($(this).prop('id') == 'finalizado_' + caixa) {
                            $(this).hide();
                        }
                    });
                    $('.listing_link').each(function () {
                        if ($(this).prop('id') == caixa) {
                            $(this).append('<img class="erro_pasta" src="' + url + 'img/siga/grid_bt_del.png"/>');
                        }
                    });
                } else {
                    $('.erro_pasta').remove();
                }
            }
            function controla_pasta_status(caixa, status) {
                caixa = CryptoJS.MD5(caixa).toString();
                $('.carregando').each(function () {
                    if ($(this).prop('id') == 'carregando_' + caixa) {
                        if (status) {
                            $(this).show();
                        } else {
                            $(this).hide();
                        }
                    }
                });
                $('.finalizado').each(function () {
                    if ($(this).prop('id') == 'finalizado_' + caixa) {
                        if (status) {
                            $(this).hide();
                        } else {
                            $(this).show();
                        }
                    }
                });
            }



            function monta_pacote_ajax(array, caixa, pagina, array_caixas, c, forcado) {
                var total_paginas = Math.ceil(total_emails_baixar_caixa[caixa] / paginas_por_caixa);
                if (total_paginas < 1) {
                    total_paginas = 1;
                }
                $.ajax({
                    type: 'post',
                    //async: false,
                    data: {
                        'conta': $('#id').val(),
                        'caixa': caixa,
                        'array': array[emails_baixados_caixa[caixa]],
                        'pag': pagina,
                        'forcado': forcado,
                        'sessao': $('#cod_sessao').val()
                    },
                    url: url + 'ajax/ajax_baixa_mensagem',
                    beforeSend: function () {
                        ajax_rodando++;
                    },
                    success: function (retorno) {
                        if (retorno != 'ok') {
                            if (retorno == 'sessao') {
                                exibe_msg('Sessão finalizada.');
                                $('#icone_sair').trigger('click');
                            } else {
                                exibe_msg('Erro ao baixar email Caixa:' + caixa + ' Pagina:' + pagina + ' UID:' + array[emails_baixados_caixa[caixa]] + ' ' + retorno);
                                console.log('Erro: Caixa:' + caixa + ' n: ' + emails_baixados_caixa[caixa] + ' UID:' + array[emails_baixados_caixa[caixa]] + ' ' + retorno);
                                executando_caixas[caixa] = false;
                                controla_pasta_status(caixa, false);
                                controla_pasta_erro(caixa, true);
                                c++;
                                if (c < array_caixas.length) {
                                    if (executando) {
                                        faz_backup_caixa_pagina($('#id').val(), array_caixas, -1, c, forcado);
                                    }
                                }
                            }
                        } else {
                            $('.listing_link').each(function () {
                                var caixa_md5 = CryptoJS.MD5(caixa).toString();
                                if (executando) {
                                    if ($(this).prop('id') == caixa_md5) {
                                        var caixa_ex = $(this).attr('nome').split('.');
                                        var caixa_nome = $(this).attr('nome');
                                        if (caixa_ex.length > 1) {
                                            caixa_nome = caixa_ex[caixa_ex.length - 1];
                                        }
                                        if ((emails_baixados_caixa_geral[caixa] + 1) < total_emails_baixar_caixa[caixa]) {
                                            $(this).html(caixa_nome + ' ' + (emails_baixados_caixa_geral[caixa] + 1) + ' de ' + total_emails_baixar_caixa[caixa]);
                                        } else {
                                            $(this).html(caixa_nome + '<img class="carregando" id="carregando_' + caixa_md5 + '" style="display:none;" src="' + url + 'img/load_foto_tem.gif" />' + '<img class="finalizado" id="finalizado_' + caixa_md5 + '" src="' + url + 'img/siga/bt_ativar.png" />');
                                            executando_caixas[caixa] = false;
                                            var finalizou_tudo = true;
                                            for (property in executando_caixas) {
                                                if (executando_caixas[property]) {
                                                    finalizou_tudo = false;
                                                }
                                            }
                                            if (finalizou_tudo) {
                                                finaliza_execucao(forcado);
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
                            if (emails_baixados_caixa[caixa] < array.length && executando) {
                                monta_pacote_ajax(array, caixa, pagina, array_caixas, c, forcado);
                            } else {
                                pagina--;
                                if (pagina > 0 && executando) {
                                    emails_baixados_caixa[caixa] = 0;
                                    faz_backup_caixa_pagina($('#id').val(), array_caixas, pagina, c, forcado);
                                } else if (executando) {
                                    $('.listing_link').each(function () {
                                        var caixa_md5 = CryptoJS.MD5(caixa).toString();
                                        if (executando) {
                                            if ($(this).prop('id') == caixa_md5) {
                                                var caixa_ex = $(this).attr('nome').split('.');
                                                var caixa_nome = $(this).attr('nome');
                                                if (caixa_ex.length > 1) {
                                                    caixa_nome = caixa_ex[caixa_ex.length - 1];
                                                }
                                                $(this).html(caixa_nome + '<img class="carregando" id="carregando_' + caixa_md5 + '" style="display:none;" src="' + url + 'img/load_foto_tem.gif" />' + '<img class="finalizado" id="finalizado_' + caixa_md5 + '" src="' + url + 'img/siga/bt_ativar.png" />');
                                                executando_caixas[caixa] = false;
                                                var finalizou_tudo = true;
                                                for (property in executando_caixas) {
                                                    if (executando_caixas[property]) {
                                                        finalizou_tudo = false;
                                                    }
                                                }
                                                if (finalizou_tudo) {
                                                    finaliza_execucao(forcado);
                                                }

                                            }
                                        }
                                    });
                                    c++;
                                    if (c < array_caixas.length) {
                                        faz_backup_caixa_pagina($('#id').val(), array_caixas, -1, c, forcado);
                                    }
                                }
                            }
                        }
                        ajax_rodando--;
                    }
                });
            }

            $(document).on("click", "#icone_sair", function () {
                executando = false;
                clearTimeout(id_atualizacao);
                //$('#porc_exec').html('');
                $.ajax({
                    type: 'post',
                    data: {
                    },
                    url: url + 'ajax/ajax_finaliza_sessao',
                    //async: false,
                    beforeSend: function () {

                    },
                    success: function () {
                        $('#novo_email_para').val('');
                        $('#novo_email_cc').val('');
                        $('#novo_email_cco').val('');
                        $('#novo_email_assunto').val('');
                        $('.ck-editor__editable').html('');
                        enc = [];
                        anexos = [];
                        $('#anexo_div_ajax').html('');
                        $('.modal_envia_mensagem').hide();
                        $('.modal_envia_mensagem').html('');


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
                if (ajax_rodando > 0) {
                    alert('Aguarde alguns segundos para executar novamente (' + ajax_rodando + ')');
                } else {
                    controla_pasta_erro('', false);
                    $('.finalizado').each(function () {
                        $(this).hide();
                    });
                    var forca_sinc = 'N';
                    if ($('#forcar_atualizacao').prop('checked')) {
                        forca_sinc = 'S';
                    }
                    faz_backup($('#id').val(), null, forca_sinc);
                }
                return false;
            });
            /*function atualiza() {
             var conta = $('#id').val();
             var caixa = $('.tb_selectionada').prop('id');
             carrega_mensagens(conta, caixa, 1);
             }*/
            $(document).on("click", "#btn_responder_todos", function () {

                var link = document.getElementById("btn_responder");
                link = link.getAttribute("href").split('|'); //id | uid | caixa

                var id = link[0];
                var uid = link[1];
                var caixa = link[2];
                tela_novo_email(id, uid, caixa, '1', '');
                return false;
            });
            function tela_novo_email(id, uid, caixa, todos, encaminhado) {
                anexos = [];
                enc = [];
                $.ajax({
                    type: 'post',
                    data: {
                        'codemailusuario': id,
                        'uid': uid,
                        'caixa': caixa,
                        'all': todos,
                        'encaminhar': encaminhado
                    },
                    url: url + 'tela_monta_email',
                    //async: false,
                    beforeSend: function () {

                    },
                    success: function (retorno) {
                        var aux_ret_msg = retorno.split('##MENSAGEM##');
                        $('.modal_envia_mensagem').html(aux_ret_msg[0]);



                        var el = document.querySelector('#mensagem_tela_monta_email');
                        var newiframe = document.createElement('iframe');
                        newiframe.setAttribute('id', 'iframe_mensagem_tela_monta_email');
                        'srcdoc' in newiframe ?
                                newiframe.srcdoc = aux_ret_msg[1] :
                                newiframe.src = 'data:text/html;charset=UTF-8,' + aux_ret_msg[1];
                        /* var parent = el.parentNode;
                         parent.replaceChild(newiframe, el);*/

                        el.appendChild(newiframe);
                        newiframe.style.width = '100%';
                        newiframe.style.height = '-webkit-fill-available';







                        inicia_editor_texto_anexo();
                        $('#div_fundo_geral').css('overflow', 'scroll');
                        $('.modal_envia_mensagem').show();
                        //$('.tb_caixas').addClass('caixas_nova_mensagem_aberta');

                        if (encaminhado == '1') {
                            $('#novo_email_para').focus();
                        }

                        if (id != '' && caixa != '' && uid != '') {
                            $.ajax({
                                type: 'post',
                                data: {
                                    'codemailusuario': id,
                                    'caixa': caixa,
                                    'uid': uid
                                },
                                url: url + 'ajax/ajax_email_get_anexos',
                                beforeSend: function () {

                                },
                                success: function (retorno) {
                                    var array_anexo = retorno.split('|');
                                    if (array_anexo[0] == 'ok') {
                                        $('#anexo_div_ajax').html(array_anexo[1]);
                                        anexos = JSON.parse(array_anexo[2]);
                                        if (anexos != null && anexos.length > 0) {
                                            for (var i = 0; i < anexos.length; i++) {
                                                enc[i] = 't';
                                            }
                                        }
                                    }

                                }
                            });
                        }
                    }
                });
            }

            $(document).on("click", "#btn_responder", function () {

                var link = document.getElementById("btn_responder");
                link = link.getAttribute("href").split('|'); //id | uid | caixa

                var id = link[0];
                var uid = link[1];
                var caixa = link[2];
                tela_novo_email(id, uid, caixa, '', '');
                return false;
            });
            $(document).on("click", "#btn_envia_email", function () {
                tela_novo_email($('#id').val(), '', '', '', '');
                /*  var link = document.getElementById("btn_envia_email");
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
                 });*/
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
                        url: url + 'ajax/ajax_email_checa_email_valido',
                        //async: false,
                        beforeSend: function () {

                        },
                        success: function (retorno) {
                            var array_ret = retorno.split('|');
                            $('#login').show();
                            $('#img_carregando').hide();
                            if (array_ret[0] == 'ok') {
                                if (array_ret[2] == 'S') {
                                    $('#abrir_tela_cheia').prop('checked', true);
                                }
                                $('#nome_email').html(email);
                                $('#nome_email_aux').val(email);
                                $('#cod_sessao').val(array_ret[3]);
                                carrega_conta(array_ret[1], 'INBOX', 'N');
                                faz_backup(array_ret[1], null, 'N');

                                id_atualizacao = setTimeout('atualiza_caixa_tempo("INBOX")', 300000);//5min
                            } else {
                                if (array_ret[1] == 'Too many login failures') {
                                    $a('#janelaModal').html(msg_box_aviso('erro', 'Login ou Senha incorretos', '', ''));
                                    $a('#janelaModal').reveal();
                                } else {
                                    if (array_ret[1] == undefined) {
                                        $a('#janelaModal').html(msg_box_aviso('erro', 'Erro: ' + retorno, '', ''));
                                        $a('#janelaModal').reveal();
                                    } else {
                                        $a('#janelaModal').html(msg_box_aviso('erro', 'Erro: ' + array_ret[1], '', ''));
                                        $a('#janelaModal').reveal();
                                    }
                                }
                            }
                        }
                    });
                }

                return false;
            });
            function monta_move_exclui_email(rel) {
                rel = rel.split('|');
                var conta = rel[0];
                var caixa = rel[1];
                var msg_selecionadas = rel[2];
                msg_selecionadas = msg_selecionadas.split(',');
                move_email_array(msg_selecionadas, caixa, "INBOX.lixo", conta, true, true);
            }
            $(document).on("click", "#box_bt_sim_aviso_excluir_email", function () {
                $a('.close-reveal-modal').trigger('click');
                var rel = $(this).prop('rel');
                monta_move_exclui_email(rel);
                return false;
            });
            $(document).on("click", "#icone_lixo", function () {

                var msg_selecionadas = '';
                var conta = $('#id').val();
                var caixa;
                var count = 0;
                $('.email_selecionado').each(function () {
                    if ($(this).prop('checked')) {
                        if (msg_selecionadas == '') {
                            msg_selecionadas = $(this).val();
                        } else {
                            msg_selecionadas += ',' + $(this).val();
                        }
                        count++;
                    }
                });
                if (msg_selecionadas == '') {
                    var aux_obj_tb;
                    $('.grid_tb_mensagens').each(function () {
                        if ($(this).hasClass('tb_selectionada')) {
                            aux_obj_tb = this;
                        }
                    });
                    //aux_obj_tb.style.opacity = '0.4';
                    var link = document.getElementById("icone_lixo").getAttribute("href");
                    link = link.split('|');
                    msg_selecionadas = link[0];
                    caixa = link[1];
                } else {
                    caixa = $('.tb_selectionada').attr('nome');
                }

                var msg = "Deseja relamente excluir";
                if (count > 1) {
                    msg += " " + count + " emails?";
                } else {
                    msg += " este email?";
                }

                var rel = conta + '|' + caixa + '|' + msg_selecionadas;
                if (caixa == 'INBOX.lixo') {
                    $a("#janelaModal").html(msg_box_aviso("confirm", msg, "aviso_excluir_email", rel));
                    $a("#janelaModal").reveal();
                } else {
                    monta_move_exclui_email(rel);
                }
                return false;
            });
            function move_email_array(uid_array, de, para, conta, exclui, add_array) {
                for (var i = 0; i < uid_array.length; i++) {
                    $('.exclui_' + uid_array[i]).hide();
                }

                if (executando_caixas[de] || executando_caixas[para]) {
                    if (add_array) {
                        var aux_array_move_email = [];
                        aux_array_move_email['de'] = de;
                        aux_array_move_email['para'] = para;
                        aux_array_move_email['exclui'] = exclui;
                        aux_array_move_email['uid_array'] = uid_array;
                        aux_array_move_email['conta'] = conta;
                        array_move_email[array_move_email.length] = aux_array_move_email;
                    }
                } else {
                    $.ajax({
                        type: 'post',
                        data: {
                            'codemailusuario': conta,
                            'caixa': de
                        },
                        url: url + 'ajax/ajax_verifica_novos_emails',
                        async: true,
                        success: function (retorno) {
                            var array_ret = retorno.split('|');
                            if (array_ret[0] == 'ok') {
                                var emails_caixa_a = parseInt(array_ret[1]);
                                $.ajax({
                                    type: 'post',
                                    data: {
                                        'codemailusuario': conta,
                                        'caixa': para
                                    },
                                    url: url + 'ajax/ajax_verifica_novos_emails',
                                    async: true,
                                    success: function (retorno) {
                                        var array_ret = retorno.split('|');
                                        if (array_ret[0] == 'ok') {
                                            var emails_caixa_b = parseInt(array_ret[1]);
                                            if (emails_caixa_a == 0 && emails_caixa_b == 0) {
                                                $("#voltar").trigger('click');
                                                for (var i = 0; i < uid_array.length; i++) {
                                                    $('.exclui_' + uid_array[i]).remove();
                                                }
                                                move_exclui_email_array(conta, uid_array, de, para, conta, exclui);
                                                array_move_email = [];
                                            } else {
                                                /*for (var i = 0; i < uid_array.length; i++) {
                                                 $('.exclui_' + uid_array[i]).show();
                                                 }*/

                                                var aux_array_move_email = [];
                                                aux_array_move_email['de'] = de;
                                                aux_array_move_email['para'] = para;
                                                aux_array_move_email['exclui'] = exclui;
                                                aux_array_move_email['uid_array'] = uid_array;
                                                aux_array_move_email['conta'] = conta;
                                                array_move_email[array_move_email.length] = aux_array_move_email;
                                                controla_pasta_erro('', false);
                                                $('.finalizado').each(function () {
                                                    $(this).hide();
                                                });
                                                var array_caixa_at = [];
                                                array_caixa_at[0] = de;
                                                array_caixa_at[1] = para;
                                                faz_backup(conta, array_caixa_at, 'N');
                                            }
                                        }
                                    }
                                });
                            }
                        }
                    });
                }
            }

            function add_mensagens(conta, caixa, qtn, offset) {
                var inicio = true;
                if (offset == '') {
                    inicio = false;
                    offset = parseInt($('#num_paginas').val()) - 1;
                }
                $.ajax({
                    type: 'post',
                    data: {
                        'id': conta,
                        'caixa': caixa,
                        'qtn': qtn,
                        'offset': offset
                    },
                    url: url + 'ajax/ajax_email_carrega_mensagens_especificas',
                    beforeSend: function () {
                        carregando("show");
                    },
                    success: function (retorno) {
                        carregando("hide");
                        var array_ret = retorno.split('|');
                        if (array_ret.length > 1) {
                            if (array_ret[0] == 'ok') {

                                if (inicio) {
                                    $($('.grid_tb_mensagens').first()).before(atob(array_ret[1]));
                                    $($('.grid_tb_mensagens').last()).remove();
                                } else {
                                    $($('.grid_tb_mensagens').last()).after(atob(array_ret[1]));
                                }
                                var cols = document.querySelectorAll('#columns .grid_tb_mensagens');
                                [].forEach.call(cols, function (col) {
                                    col.addEventListener('dragstart', handleDragStart, false);
                                });
                                $('#total_emails').val(parseInt($('#total_emails').val()) - qtn);

                                $('#total_emails_count').html($('#total_emails').val());
                                $('#total_paginas_count').html(Math.ceil($('#total_emails').val() / $('#num_paginas').val()));
                            } else {
                                exibe_msg(array_ret[1]);
                            }
                        } else {
                            exibe_msg(retorno);
                        }
                    }
                });
            }

            function move_exclui_email_array(conta, uid_array, de, para, conta, exclui) {

                executando_caixas[de] = true;
                executando_caixas[para] = true;
                $('#btn_atualiza_conta_unica').html('<a href="#" id="btn_atualiza_conta_unica"><img src="<?php echo URL; ?>img/load_foto_tem.gif" style="width: 30px; height: 30px; padding-left: 10px;"/><br/><label style="color:#000;">Atualizando</label></a>');
                if (de == "INBOX.lixo" && exclui) {
                    $.ajax({
                        type: 'post',
                        data: {
                            'id': conta,
                            'array_uid': uid_array,
                            'caixa': de
                        },
                        url: url + 'ajax/ajax_exclui_email_array',
                        async: true,
                        success: function (retorno) {
                            if (retorno == 'ok') {
                                executando_caixas[de] = false;
                                executando_caixas[para] = false;
                                $('#btn_atualiza_conta_unica').html('<img src="<?php echo URL; ?>img/siga/email/update.svg" style="width: 30px; height: 30px; padding-left: 10px;"/><br/><label style="color:#000;">Atualizar</label>');
                                //carrega_conta(conta, de, 'N');
                                $('#pagina_atual').val(parseInt($('#pagina_atual').val()) - 1);
                                add_mensagens(conta, de, uid_array.length, '');
                            } else {
                                alert(retorno);
                            }
                        }
                    });
                } else {
                    $.ajax({
                        type: 'post',
                        data: {
                            'array_uid': uid_array,
                            'de': de,
                            'para': para,
                            'id': conta
                        },
                        url: url + 'ajax/ajax_move_email_array',
                        //async: false,
                        beforeSend: function () {

                        },
                        success: function (retorno) {
                            if (retorno == 'ok') {
                                executando_caixas[de] = false;
                                executando_caixas[para] = false;
                                $('#btn_atualiza_conta_unica').html('<img src="<?php echo URL; ?>img/siga/email/update.svg" style="width: 30px; height: 30px; padding-left: 10px;"/><br/><label style="color:#000;">Atualizar</label>');
                                //carrega_conta(conta, de, 'N');      
                                $('#pagina_atual').val(parseInt($('#pagina_atual').val()) - 1);
                                add_mensagens(conta, de, uid_array.length, '');
                            } else {
                                alert('Erro: ' + retorno.split('|')[1]);
                            }
                        }
                    });
                }



            }

            $(document).on("click", "#btn_encaminhar", function () {

                var link = document.getElementById("btn_encaminhar");
                link = link.getAttribute("href").split('|'); //id | uid | caixa

                var id = link[0];
                var uid = link[1];
                var caixa = link[2];
                tela_novo_email(id, uid, caixa, '', '1');
                return false;
            });
            $(document).on("click", ".listing_link", function () {
                $('.modal_envia_mensagem').hide();
                $('.tb_caixas').removeClass('caixas_nova_mensagem_aberta');
                var id = $('#id').val();
                var caixa_m5 = $(this).prop('id');
                var caixa = $(this).attr('nome');
                $('#caixa_pesquisa').val('');
                carrega_mensagens(id, caixa, 1, '');
                $(this).each(function () {
                    $('.listing_link').removeClass('tb_selectionada');
                });
                $(this).addClass('tb_selectionada');
                // abre_fecha_caixas(this, caixa);
                if ($(this).hasClass('tem_sub')) {
                    if ($('.filha_' + caixa_m5).hasClass('hide')) {
                        $('.filha_' + caixa_m5).removeClass('hide');
                    } else {
                        $('.filha_' + caixa_m5).addClass('hide');
                    }
                }

                return false;
            });
            function abre_fecha_caixas(obj, caixa) {
                if ($(obj).hasClass('tem_sub')) {
                    if ($('.filha_' + caixa).hasClass('hide')) {
                        $('.filha_' + caixa).removeClass('hide');
                    } else {
                        $('.filha_' + caixa).addClass('hide');
                    }
                    if ($('.filha_' + caixa).hasClass('tem_sub')) {
                        abre_fecha_caixas($('.filha_' + caixa), 'filha_' + caixa);
                    }
                }
            }
            $(document).on("click", "#voltar", function () {
                $('#icone_lixo').addClass('hide');
                $('#btn_responder').addClass('hide');
                $('#btn_responder_todos').addClass('hide');
                $('#btn_encaminhar').addClass('hide');
                $('#impressao').addClass('hide');
                $('tr .tb_selectionada').removeClass('tb_selectionada');
                $('#div_mensagens').removeClass('hide');
                $('#div_mensagem').addClass('div_mensagem_minimizada');
                $('#voltar').hide();
                $('#impressao').addClass('hide');
                $('#div_mensagem').html('');
                $('.modal_envia_mensagem').hide();
                $('#box_pesquisa').show();
                $('#box_pesquisa').val('');
                $('#select_transf').html('');
                $('#select_transf').hide();
                $('#tranferir_email_cmb').attr('href', '#');

                return false;
            });
            $(document).on("click", ".tb_style_mensagens", function () {
                var id_msg = $(this).attr('id');
                var id_msg_or = id_msg;
                if (id_msg != '#') {
                    id_msg = id_msg.split('%*%');
                    var caixa = id_msg[0];
                    var uid = id_msg[1];
                    var id = id_msg[2];
                    $(".grid_tb_mensagens").each(function () {
                        if ($(this).prop('id') == id_msg_or) {
                            $(this).addClass('tb_selectionada');
                            $(this).removeClass('msg_lida');
                        } else {
                            $(this).removeClass('tb_selectionada');
                        }
                    });
                    carrega_mensagem(id, caixa, uid);
                }
            });
            function carrega_mensagem(conta, caixa, uid) {
                if (global_array_caixas == null) {
                    global_array_caixas = [];
                    $(".listing_link").each(function () {
                        global_array_caixas[global_array_caixas.length] = $(this).attr('nome');
                    });
                }
                $.ajax({
                    type: 'post',
                    data: {
                        'id': conta,
                        'caixa': caixa,
                        'uid': uid
                    },
                    url: url + 'ajax/ajax_email_carrega_mensagem',
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
                        link = document.getElementById("impressao");
                        link.setAttribute("href", url + 'imprime/' + uid + '/' + btoa(caixa));

                        $('#icone_lixo').removeClass('hide');
                        $('#btn_responder').removeClass('hide');
                        $('#btn_responder_todos').removeClass('hide');
                        $('#btn_encaminhar').removeClass('hide');
                        $('#impressao').removeClass('hide');

                        $('#box_pesquisa').hide();

                        if ($('#abrir_tela_cheia').prop('checked')) {
                            $('#div_mensagens').addClass('hide');
                            $('#div_mensagem').removeClass('div_mensagem_minimizada');
                            $('#voltar').show();
                            $('#div_fundo_geral').css('overflow', 'hidden');
                            $('#div_mensagem').css('height', $(document).height() - 107);
                            $('#div_mensagem').css('max-height', $(document).height() - 107);
                        } else {
                            $('#div_mensagem').addClass('div_mensagem_minimizada');
                            $('#voltar').hide();
                            $('#div_fundo_geral').css('overflow', 'scroll');
                            $('#div_mensagem').css('height', $(document).height() - $('#tb_msgs').height() + 214);
                            $('#div_mensagem').css('max-height', $(document).height() - $('#tb_msgs').height() + 214);
                        }
                        // $('#div_mensagem').html(retorno);

                        var el = document.querySelector('#div_mensagem');
                        var newiframe = document.createElement('iframe');
                        newiframe.setAttribute('id', 'iframe_mensagem');
                        'srcdoc' in newiframe ?
                                newiframe.srcdoc = retorno :
                                newiframe.src = 'data:text/html;charset=UTF-8,' + retorno;
                        /* var parent = el.parentNode;
                         parent.replaceChild(newiframe, el);*/

                        el.appendChild(newiframe);
                        newiframe.style.width = '100%';
                        newiframe.style.height = '-webkit-fill-available';
                        fun_ajusta_largura();

                        var aux_cmb_tranf = '<select class="" style="border: 1px solid;" id="select_caixa_para">';
                        aux_cmb_tranf += '<option value="0" selected="selected">Transferir email</option>';

                        var aux_cmb_caixa_nome = '';
                        for (var i = 0; i < global_array_caixas.length; i++) {
                            aux_cmb_caixa_nome = global_array_caixas[i].split('.');
                            aux_cmb_tranf += '<option value="' + global_array_caixas[i] + '">' + aux_cmb_caixa_nome[aux_cmb_caixa_nome.length - 1] + '</option>';
                        }

                        aux_cmb_tranf += '</select>';
                        aux_cmb_tranf += '<br><a href="#" style="margin: 8px 2px 0px 0px;float: right;" id="tranferir_email_cmb"><img src="' + url + 'img/siga/grid_bt_transf_feed.png" /></a>';

                        $('#select_transf').html(aux_cmb_tranf);
                        $('#select_transf').show();

                        link = document.getElementById("tranferir_email_cmb");
                        link.setAttribute("href", conta + '|' + uid + '|' + caixa);

                        carregando("hide");
                    }
                });
            }

            $(document).on('click', '#tranferir_email_cmb', function () {

                var href = $('#tranferir_email_cmb').attr('href').split('|');

                var caixa = href[2];
                var uid_array = [];

                var conta = href[0];
                var para_pasta = $('#select_caixa_para').val();

                uid_array = getEmailSelecionados(href[1]);

                if (href[1] != '') {
                    uid_array[uid_array.length] = href[1];
                }


                move_email_array(uid_array, caixa, para_pasta, conta, false, true);
                return false;
            });

            function carrega_conta(id, caixa, edicao) {
                $('#id').val(id);
                var aux_offline = 'N';
                if (offline) {
                    aux_offline = 'S';
                }
                $.ajax({
                    type: 'post',
                    data: {
                        'id': id,
                        'edicao': edicao,
                        'offline': aux_offline
                    },
                    url: url + 'ajax/ajax_email_carrega_caixas',
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

                            $('#select_transf').html('');
                            $('#select_transf').hide();
                            $('#tranferir_email_cmb').attr('href', '#');

                            $('#div_caixas').html(array_msg[1]);
                            var link = document.getElementById("btn_envia_email");
                            link.setAttribute("href", id);
                            carrega_mensagens(id, caixa, 1, '');
                            $(".listing_link").each(function () {
                                if ($(this).attr('nome') == caixa) {
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
                        } else {
                            exibe_msg('Erro ao carregar caixas:' + retorno);
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

                e.dataTransfer.dropEffect = 'move'; // See the section on the DataTransfer object.

                return false;
            }

            function handleDragEnter(e) {
                // this / e.target is the current hover target.

                this.style.opacity = '1';
                this.classList.add('over');
            }

            function handleDragLeave(e) {
                this.classList.remove('over'); // this / e.target is previous target element.

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

                var array_dados = $(dragSrcEl).prop('id').split('%*%');
                var caixa = array_dados[0];
                var uid = array_dados[1];
                var conta = array_dados[2];
                var para_pasta = $(this).attr('nome');
                $('.tb_caixas').addClass('cursor_carregando');
                $('#tb_msgs').addClass('cursor_carregando');
                var uid_array = [];
                var count = 0;
                $('.email_selecionado').each(function () {
                    if ($(this).prop('checked')) {
                        uid_array[count] = $(this).val();
                        count++;
                    }
                });
                if (count == 0) {
                    uid_array[0] = uid;
                }
                move_email_array(uid_array, caixa, para_pasta, conta, false, true);
                return false;
            }


            $(document).on("change", "#num_paginas", function () {
                var id = $('#id').val();
                var caixa = $('.tb_selectionada').attr('nome');
                var itens_pag = $(this).val();
                $.ajax({
                    type: 'post',
                    data: {
                        'id': id,
                        'itens_pagina': itens_pag
                    },
                    url: url + 'ajax/ajax_email_muda_itens_pag',
                    success: function (retorno) {
                        if (retorno != 'ok') {
                            console.log(retorno);
                        }
                    }
                });
                $('#caixa_pesquisa').val('');
                carrega_mensagens(id, caixa, 1, '');
            });
            $(document).on("click", ".proxima_pagina, .num_paginas, .ultima_pagina, .pagina_anterior, .primeira_pagina", function () {
                var id = $('#id').val();
                var caixa = $('.tb_selectionada').attr('nome');
                var pagina = $(this).prop('id');
                var pesquisa = $('#caixa_pesquisa').val();
                carrega_mensagens(id, caixa, pagina, pesquisa);
                return false;
            });
            function carrega_mensagens(id, caixa, pagina, pesquisa) {
                $('#pagina_atual').val(pagina);
                if (!$('#icone_lixo').hasClass('hide')) {
                    $('#icone_lixo').addClass('hide');
                }
                var itens_pag = $('#num_paginas').val();
                $.ajax({
                    type: 'post',
                    data: {
                        'id': id,
                        'caixa': caixa,
                        'pagina': pagina,
                        'pesquisa': pesquisa,
                        'itens_pag': itens_pag
                    },
                    url: url + 'ajax/ajax_email_carrega_mensagens',
                    beforeSend: function () {
                        carregando("show");
                        $('#icone_lixo').addClass('hide');
                        $('#btn_responder').addClass('hide');
                        $('#btn_responder_todos').addClass('hide');
                        $('#btn_encaminhar').addClass('hide');
                        $('#impressao').addClass('hide');
                        $('#div_mensagem').addClass('hide');
                        $('#div_mensagem').html('');
                        $('#div_mensagens').html('');
                        $('#voltar').hide();
                        $('#impressao').addClass('hide');
                        $('#box_pesquisa').show();
                        $('#box_pesquisa').val('');
                    },
                    success: function (retorno) {
                        var array_count = retorno.split('##CONTADOR##');
                        // var caixa_md5 = CryptoJS.MD5(caixa).toString();
                        $('#div_mensagens').removeClass('hide');
                        $('#menu_superior').removeClass('hide');
                        $('#div_mensagens').html(array_count[0]);
                        $('#total_emails').val(array_count[1]);
                        var cols = document.querySelectorAll('#columns .grid_tb_mensagens');
                        [].forEach.call(cols, function (col) {
                            col.addEventListener('dragstart', handleDragStart, false);
                        });
                        $('#select_transf').html('');
                        $('#select_transf').hide();
                        $('#tranferir_email_cmb').attr('href', '#');

                        fun_ajusta_largura();
                        carregando("hide");
                    }
                });
            }

        </script>
    </body>
</html>