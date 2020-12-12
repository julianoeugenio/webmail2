<?php
//error_reporting(0);


include DIR_classes . 'MailSo/MailSo.php';

if (!empty($_POST['codemailusuario'])) {

    $codemailusuario = Anti_Injection($_POST['codemailusuario']);

// = 20;

    $mensagem = "";
    $para = "";
    $de = "";
    $assunto = "";
    $anexo = "";

    $array_anexo = array();

    if (!empty($_POST['uid']) && !empty($_POST['caixa'])) {

        $uid = Anti_Injection($_POST['uid']);
        $caixa = Anti_Injection($_POST['caixa']);


        $bd = new BD_FB_EMAIL();
        $bd->open();

        $conta = getEmailSenha($codemailusuario);

        $bd->close();

        $email = $conta['EMAIL'];
        $senha = base64_decode($conta['SENHA']);
        $servidor = explode('@', $email);
        $servidor = 'pop.' . $servidor[1];

        /* echo $email."\n";
          echo $senha."\n";
          echo $servidor."\n";

          exit(); */

        $oData = null;

        if (!empty($email) && !empty($senha) && !empty($servidor)) {

            try {
                $oMailClient = \MailSo\Mail\MailClient::NewInstance();
                $conn = $oMailClient
                        ->Connect($servidor, 143, \MailSo\Net\Enumerations\ConnectionSecurityType::NONE)
                        ->Login($email, $senha);
                $oData = $conn->Message($caixa, intval($uid));
            } catch (Exception $e) {
                var_dump($e);
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

            $de = $email;
            $para = '';

            $de_nome = '';
            if ($from->Count() > 0) {
                $para = $from->GetByIndex(0)->GetEmail();
                $de_nome = $from->GetByIndex(0)->GetDisplayName();
            }


            if (!empty($_POST['all'])) {
                if ($from->Count() > 0) {
                    $de_str = '';
                    for ($x = 0; $x < $from->Count(); $x++) {
                        if (empty($de_str)) {
                            $de_str = $from->GetByIndex($x)->GetEmail();
                        } else {
                            $de_str .= ',' . $from->GetByIndex($x)->GetEmail();
                        }
                    }
                }
            }


            if (!empty($_POST['encaminhar'])) {
                $data = Data_Hora_BR(gmdate('Y-m-d H:i:s', strtotime($oData->HeaderDate())));

//$anexo = $reg['CODBAIXAREMAIL'];

                $mensagem = Utf8('---------- Mensagem encaminhada ----------<br/>
De: ' . $de_nome . ' &lt;' . $para . '&gt; <br/>
Data: ' . $data . '<br/>
Assunto: ' . $assunto . '<br/>
Para: ' . $de_str . '<br/><br/><br/><br/>' . nl2br($msg_email));

                $assunto = 'Fwd: ' . $assunto;
                $para = '';
                $de_str = '';
            } else {

                $mensagem = Utf8(nl2br($msg_email));
                $assunto = 'Re: ' . $assunto;
            }
        }
    }
    ?>
    <!DOCTYPE html>
    <html lang="pt-br">
        <head>

            <title>Email</title>

            <?php
            require(DIR_siga_inc . "meta_tag.php");
            require(DIR_siga_inc . "header.php");
            ?>

            <script src="<?php echo DIR_siga_js ?>jssor/jssor.utils.js"></script>

            <link href="<?php echo DIR_siga_css ?>grid.css" rel="stylesheet" type="text/css" />
            <link href="<?php echo DIR_siga_css ?>imovel.css" rel="stylesheet" type="text/css" />
            <link href="<?php echo DIR_siga_css ?>email.css" rel="stylesheet" type="text/css" />
            <link href="<?php echo DIR_siga_css ?>uploadfile.css" rel="stylesheet">

            <script src="<?php echo DIR_siga_js ?>upload/jquery.uploadfile.min.js"></script>
            <style>

                .grid_form_titulos, .grid_form_titulos_first, .grid_form_divisoria{

                    border-color:#778899;
                }
                .grid_form_titulos, .grid_form_titulos_first, .grid_form_divisoria{

                    border-color:#87CEEB;
                }

                .grid_bt_enviar{

                    border:1px solid #87CEEB;

                }
                .grid_bt_enviar:hover{
                    color:#fff;
                    border:1px solid #87CEEB;
                    background-color:#87CEEB;
                }

            </style>

        </head>

        <body>
            <a class="close-reveal-modal">x</a>
            <form class="grid_form" method="post" name="form_email" id="form_email" action="#" enctype="multipart/form-data">

                <div class="fundo_focus">
                    <label for="txt_para" style="width:110px">Para</label>
                    <input name="txt_para" type="text" id="txt_para" style="width:350px" maxlength="100" class="focus txt_para" value="<?php
                    if (!empty($de_str)) {
                        echo $de_str;
                    } else if (!empty($para)) {
                        echo $para;
                    }
                    ?>" />
                </div>
                <div class="msg_aviso" style="padding:0 0 0 100px"></div>

                <div class="fundo_focus">
                    <label for="txt_copia_para" style="width:110px">Cópia Para</label>
                    <input name="txt_copia_para" type="text" id="txt_copia_para" style="width:350px" maxlength="100" class="focus txt_copia_para" value="" />
                </div>
                <div class="msg_aviso" style="padding:0 0 0 100px"></div>

                <div class="fundo_focus">
                    <label for="txt_copia_oculta_para" style="width:110px">Cópia Oculta Para</label>
                    <input name="txt_copia_oculta_para" type="text" id="txt_copia_oculta_para" style="width:350px" maxlength="100" class="focus txt_copia_oculta_para" value="" />
                </div>
                <div class="msg_aviso" style="padding:0 0 0 100px"></div>

                <div class="fundo_focus">
                    <label for="txt_anexo" style="width:110px;">Incluir Anexo</label>
                    <div style="margin-left: 130px;" id="fileuploader">Anexo</div>

                    <div id="anexo_div_ajax" class="ajax-file-upload-container">
                    </div>
                </div>

                <div class ="msg_aviso" style="padding:0 0 0 100px"></div>

                <div class="fundo_focus">
                    <label for="txt_aasunto" style="width:110px">Assunto</label>
                    <input name="txt_assunto" type="text" id="txt_assunto" style="width:350px" maxlength="100" class="focus txt_assunto" value="<?php
                    if (!empty($assunto)) {
                        echo $assunto;
                    }
                    ?>" />
                </div>
                <div class="msg_aviso" style="padding:0 0 0 100px"></div>

                <div class="fundo_focus">
                    <label for="txt_mensagem" style="width:110px; clear:both; float:left;">Mensagem</label>
                    <div contenteditable="true" name="txt_mensagem" id="txt_mensagem" style="width:350px; height:100px; background-color: #fff; float:left; border: 1px solid #ccc; overflow-x: hidden; overflow-y: auto; font-family: Arial, Helvetica, sans-serif;
                         margin: 0 0 0 4px;
                         padding: 5px 5px 5px 5px;
                         display: inline-block;
                         border: 1px solid #ccc;
                         font-size: 12px;
                         color: #777;
                         display: inline-block;" maxlength="500" class="focus txt_mensagem">
                         <?php
                         if (!empty($mensagem)) {
                             echo $mensagem;
                         }
                         ?>
                    </div>
                </div>
                <br>
                <div id="div_msg_carregando" style="float: right; font-size: 20px; color: #87CEEB; display: none; margin-top: 10px;">Enviando...</div>
                <a style="float: right; margin-top: 10px;" class="grid_bt_enviar botao" id="btn_enviar" title="Enviar" alt="Enviar">Enviar</a>
            </form>

            <script>




                $(document).ready(function () {

                    var anexos = [];
                    var enc = [];


                    $(document).on("click", ".delete_anexo", function (e) {

                        var id = (this.id) - 1;

                        anexos[id] = '';
                        $('#bd_anexo_' + this.id).html('');


                        /* anexos.splice(id, 1);
                         enc.splice(id, 1);
                         
                         $('#bd_anexo_' + this.id).html('');
                         $('#bd_anexo_' + this.id).prop('id','');
                         
                         for (var i = id; i < anexos.length; i++) {
                         $('#bd_anexo_' + (i+2)).prop('id', 'bd_anexo_'+(i+1));                            
                         $('.delete_anexo').prop('id',(i+1));
                         }
                         //alert(anexos[(this.id)-1]);*/

                        return false;
                    });


    <?php
    if (!empty($caixa) && !empty($uid)) {
        ?>

                        $.ajax({
                            type: 'post',
                            data: {
                                'codemailusuario': <?php echo $codemailusuario; ?>,
                                'caixa': '<?php echo $caixa; ?>',
                                'uid': <?php echo $uid; ?>
                            },
                            url: url + 'ajax/webmail/ajax_email_get_anexos',
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
        <?php
    }
    ?>



                    $("#btn_enviar").on("click", function () {
                        // $("#form_email").submit();
                        //extraObj.startUpload();

                        var para = $('#txt_para').val();
                        var copia = $('#txt_copia_para').val();
                        var copia_oculta = $('#txt_copia_oculta_para').val();
                        var assunto = $('#txt_assunto').val();
                        var mensagem = $('#txt_mensagem').html();
                        $.focus_out_borda("txt_para");
                        $.focus_out_borda("txt_assunto");
                        $.focus_out_borda("txt_mensagem");
                        var continua = true;
                        if (para == "") {
                            $("#txt_para").focus();
                            $.focus_borda("txt_para");
                            continua = false;
                        } else {
                            if (para.search(',') > 0) {
                                var para_ex = para.split(',');

                                for (var i = 0; i < para_ex.length; i++) {
                                    if (!validacaoEmail(para_ex[i]) || para_ex[i] == '')
                                    {
                                        $("#txt_para").focus();
                                        $.focus_borda("txt_para");
                                        continua = false;
                                    }
                                }
                            } else {
                                if (!validacaoEmail(para))
                                {
                                    $("#txt_para").focus();
                                    $.focus_borda("txt_para");
                                    continua = false;
                                }
                            }
                        }

                        if (assunto == "") {
                            if (continua) {
                                $("#txt_assunto").focus();
                            }
                            $.focus_borda("txt_assunto");
                            continua = false;
                        }


                        if (mensagem == "") {
                            if (continua) {
                                $("#txt_mensagem").focus();
                            }
                            $.focus_borda("txt_mensagem");
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
                                'codemailusuario': <?php echo $codemailusuario; ?>,
                                'enc': enc,
                                'file': anexos
                            },
                            url: url + 'ajax/webmail/ajax_email_envia_email',
                            beforeSend: function () {
                                $("#btn_enviar").hide();
                                $("#div_msg_carregando").show();
                            },
                            success: function (retorno) {
                                if (retorno == 'ok') {
                                    //Atualiza_contas_f();
                                    $a('.close-reveal-modal').trigger('click');
                                } else {
                                    $("#btn_enviar").show();
                                    $("#div_msg_carregando").hide();
                                    alert('Erro: ' + retorno);
                                }
                            }
                        });
                    });
                    var extraObj = $("#fileuploader").uploadFile({
                        url: url + "ajax/email/ajax_email_anexo_send",
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
                });

            </script>

        </body>
    </html>

    <?php
} else {
    echo'erro|POST';
}
?>