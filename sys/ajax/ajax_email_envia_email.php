<?php

ini_set('memory_limit', '256M');
set_time_limit(240);


$anexos = $_POST['file'];

/*
  'para': para,
  'copia': copia,
  'copia_oculta': copia_oculta,
  'assunto': assunto,
  'mensagem': mensagem,
  'file': anexos
 */

if (!empty($_POST['para']) && !empty($_POST['codemailusuario'])) {

    $aux_anexo = "";
    $aux_body = "";
    $boundary = "------=" . md5(uniqid(rand()));

    $codemailusuario = Anti_Injection($_POST['codemailusuario']);
    $para = Anti_Injection($_POST['para']);
    $copia = Anti_Injection($_POST['copia']);
    $copia_oculta = Anti_Injection($_POST['copia_oculta']);
    $assunto = Utf8(Anti_Injection($_POST['assunto']));


    //$mensagem = Utf8(nl2br(Anti_Injection($_POST['mensagem'])));
    $mensagem = Utf8($_POST['mensagem']);

    $bd_email = new BD_FB_EMAIL();
    $bd_email->open();

    $email = getEmailSenha($codemailusuario);

    $senha = base64_decode($email['SENHA']);
    $email = $email['EMAIL'];

    $nome_dest = '';

    $email_ex = explode('@', $email);

    $email_ex_p = explode('.', $email_ex[0]);

    if (count($email_ex_p) > 1) {
        $primero_nome = str_split($email_ex_p[0]);
        $segundo_nome = str_split($email_ex_p[1]);

        $primero_nome[0] = strtoupper($primero_nome[0]);
        $segundo_nome[0] = strtoupper($segundo_nome[0]);

        $nome_dest = implode('', $primero_nome) . ' ' . implode('', $segundo_nome);
    } else {
        $primero_nome = str_split($email_ex[0]);
        $primero_nome[0] = strtoupper($primero_nome[0]);
        $nome_dest = implode('', $primero_nome);
    }



    require(DIR_classes . "PHPmailer.class.php");

    $mail = new PHPMailer(true); //Cria instancia  

    $mail->From = $email; //E-mail remetente
    $mail->FromName = $nome_dest; //Nome remetente
    $mail->Subject = $assunto; //Assunto do e-mail
    $mail->Host = "smtp.site.com.br"; //Host SMTP
    $mail->SMTPAuth = true; //Se o SMTP precisa de autenticaÃ§Ã£o
    $mail->Username = $email; //UsuÃ¡rio SMTP
    $mail->Password = $senha; //Senha SMTP
    $mail->Port = '587'; //Porta
    $mail->Body = $mensagem; //Mensagem a ser enviada
    $mail->IsHtml(true); //Mensagem no formato de texto
    $mail->IsSMTP(); //Configura mailer para entrega por SMTP
    $mail->SMTPDebug = 1; //Habilita debug do SMTP
//  $mail->SingleTo   = true;//Enviar e-mail individualmente
    $mail->AddReplyTo($email, $nome_dest); //Configura o endereÃ§o para receber resposta da msg
    $mail->CharSet = 'utf-8'; //codificaÃ§Ã£o

    if (strripos($para, ',')) {
        $ex_para = explode(',', $para);
        for ($i = 0; $i < count($ex_para); $i++) {
            $mail->AddAddress($ex_para[$i]); //Adiciona destinatÃ¡rio da mensagem   
        }
    } else {
        $mail->AddAddress($para); //Adiciona destinatÃ¡rio da mensagem     
    }

    if (!empty($copia)) {
        if (strripos($copia, ',')) {
            $ex_copia = explode(',', $copia);
            for ($i = 0; $i < count($ex_copia); $i++) {
                $mail->AddCC($ex_copia[$i]); //Adiciona destinatÃ¡rio da mensagem   
            }
        } else {
            $mail->AddCC($copia); //Adiciona destinatÃ¡rio da mensagem     
        }
    }

    if (!empty($copia_oculta)) {
        if (strripos($copia_oculta, ',')) {
            $ex_copia_oculta = explode(',', $copia_oculta);
            for ($i = 0; $i < count($ex_copia_oculta); $i++) {
                $mail->AddBCC($ex_copia_oculta[$i]); //Adiciona destinatÃ¡rio da mensagem   
            }
        } else {
            $mail->AddBCC($copia_oculta); //Adiciona destinatÃ¡rio da mensagem     
        }
    }

    /* $mail->ConfirmReadingTo(); */

    $aux_body = monta_envio_copia($email, $para, $assunto, $mensagem, $boundary);

    $enc = $_POST['enc'];

    for ($i = 0; $i < count($anexos); $i++) {


        if ($enc[$i] == 't') {
            $arquivo = $anexos[$i];
            if ($arquivo != '') {
                $nome = explode('/', $arquivo);
                $nome = $nome[count($nome) - 1];

                $aux_anexo .= montaEnvioAnexo(utf8_decode($arquivo), $nome, $boundary);
                $mail->AddAttachment(utf8_decode($arquivo), $nome);
                //echo $nome . '>true' . "\n";
            }
        } else if ($enc[$i] == 'f') {
            $arquivo = (json_decode($anexos[$i])[0]);
            if ($arquivo != '') {
                $nome_ex = explode('/', explode('.' . $extension = pathinfo($arquivo, PATHINFO_EXTENSION), $arquivo)[0]);
                $nome = $nome_ex[count($nome_ex) - 1];

                $aux_anexo .= montaEnvioAnexo($arquivo, $nome, $boundary);
                $mail->AddAttachment($arquivo, $nome);
                //echo $nome . '>false' . "\n";
            }
        }
    }


    if ($mail->Send()) {

        $servidor = "pop.site.com.br";


        $caixaDeCorreio = imap_open("{" . $servidor . ":143/novalidate-cert}", $email, $senha);

        if (!empty($aux_anexo)) {
            $aux_body .= $aux_anexo . "--$boundary--\r\n\r\n";
        }
        imap_append($caixaDeCorreio, "{" . $servidor . ":143/novalidate-cert}INBOX.enviadas", $aux_body);

        echo'ok' . imap_last_error();

        if ($_POST['enc'] != 'enc') {
            for ($i = 0; $i < count($anexos); $i++) {
                $arquivo = (json_decode($anexos[$i])[0]);
                unlink($arquivo);
            }
        }
    } else {
        echo'erro|Envio';
    }
} else {
    echo'erro|Post';
}

