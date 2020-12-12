<?php
//error_reporting(0);


include DIR_classes . 'MailSo/MailSo.php';

if (!empty($_POST['codemailusuario'])) {

    $codemailusuario = Anti_Injection($_POST['codemailusuario']);
    if (!empty($_POST['uid']) && !empty($_POST['caixa'])) {
        $uid = Anti_Injection($_POST['uid']);
        $caixa = Anti_Injection($_POST['caixa']);


        $bd = new BD_FB_EMAIL();
        $bd->open();



        $sql = "SELECT be.*, c.desccaixas FROM baixar_email be 
            INNER JOIN caixas c ON (be.caixa = c.codcaixas AND c.hash = '" . md5($caixa) . "')
            WHERE be.codcontasbaixaremail = " . $codemailusuario . " AND be.status <> 'E' AND be.uid = " . $uid;

        $query = ibase_query($sql);

        if ($query) {
            if ($reg = ibase_fetch_assoc($query, IBASE_TEXT)) {
                $msg_email = $reg['MENSAGEM'];
                $assunto = '(sem assunto)';
                if (!empty($reg['ASSUNTO'])) {
                    $assunto = Utf8($reg['ASSUNTO']);
                }

                $de = Utf8($reg['DE']);
                $de_nome = Utf8($reg['DENOME']);
                $para = Utf8($reg['PARA']);

                $data = Data_Hora_BR($reg['DATA']);

                if (!empty($_POST['encaminhar'])) {


                    $mensagem = Utf8('<br><br>---------- Mensagem encaminhada ----------<br><br>
De: ' . $de_nome . ' &lt;' . $para . '&gt; <br/>
Data: ' . $data . '<br/>
Assunto: ' . $assunto . '<br/>
Para: ' . $para . '<br/><br/><br/><br/>' . $msg_email);

                    $assunto = 'Fwd: ' . $assunto;
                    $de = '';
                } else {

                    $mensagem = '<br><br>Em '.$data.', '.$de_nome.' &lt;'.$de.'&gt; escreveu:<br><br>'.$msg_email;
                    $assunto = 'Re: ' . $assunto;
                }
            }
        }



        $bd->close();
    }
    ?>

    <div class="div_envia_mensagem">


        <div class="separador">
            <a style="margin-top: 6px;border-radius: 4px;cursor:pointer;" class="grid_bt_enviar" id="btn_enviar" title="Enviar" alt="Enviar">Enviar</a>
            <img src="<?php echo URL; ?>img/load_foto_tem.gif" style="margin-top: 12px;display: none;" id="carregando_envio_email"/>
        </div>


        <div class="separador">
            <label>Para:</label>
            <input type="text" name="novo_email_para" id="novo_email_para" value="<?php
            if (!empty($de)) {
                echo $de;
            }
            ?>" autocomplete="off"/>     
            <a class="grid_bt_copia" id="btn_add_cco" title="Cco" alt="Cco">Cco</a>
            <a class="grid_bt_copia" id="btn_add_cc" title="Cc" alt="Cc">Cc</a>
            <div class="auto_sugestao" id="auto_sugestao_para"></div>
        </div>

        <div class="separador" id="separador_cc">
            <label>Cc:</label>
            <input type="text" name="novo_email_cc" id="novo_email_cc" style="width: 90%;" autocomplete="off"/>   
            <div class="auto_sugestao" id="auto_sugestao_cc"></div>
        </div>

        <div class="separador" id="separador_cco">
            <label>Cco:</label>
            <input type="text" name="novo_email_cco" id="novo_email_cco" style="width: 90%;" autocomplete="off"/>  
            <div class="auto_sugestao" id="auto_sugestao_cco"></div>
        </div>

        <div class="separador">
            <label>Assunto:</label>
            <input type="text" name="novo_email_assunto" id="novo_email_assunto" style="width: 90%;" value="<?php
            if (!empty($assunto)) {
                echo $assunto;
            }
            ?>"/>
        </div>

        <div id="mensagem_tela_monta_email" style="height: calc(90% - 250px);"></div>

        <div style="clear: both;"></div>

        <div class="separador">
        </div>

        <div style="margin-top: 10px;margin-left: 10px;">
            <div id="fileuploader">Anexo</div>
            <div id="anexo_div_ajax" class="ajax-file-upload-container">
            </div>
        </div>
    </div>
##MENSAGEM##
<div contenteditable="true" id="mensagem_tela_monta_email_edit">
<?php
        if (!empty($mensagem)) {
            echo $mensagem;
        }
        ?>
    </div>
    <?php
}
?>