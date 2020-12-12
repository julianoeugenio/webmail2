<?php



include DIR_classes . 'MailSo/MailSo.php';

if (!empty($_POST['codemailusuario']) && !empty($_POST['uid']) && !empty($_POST['caixa'])) {
    $codemailusuario = Anti_Injection($_POST['codemailusuario']);
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


        if (is_object($oData->Attachments()) || is_array($oData->Attachments())) {
            salva_anexo($servidor, $email, $senha, $caixa, $uid, $oData->Attachments());

            for ($i = 0; $i < $oData->Attachments()->Count(); $i++) {
                $uniqueFilename = (imap_utf8(($oData->Attachments()->GetByIndex($i)->Filename())));
                if (empty($uniqueFilename)) {
                    $uniqueFilename = $oData->Attachments()->GetByIndex($i)->GetBodyStructure()->Description();
                }


                $pasta = DIR_anexos_email . $codemailusuario . '/' . md5($caixa) . '/' . $uid . "/" . $uniqueFilename;
                // $pasta = DIR_anexos_email . removeAcentos(explode('@', $email)[0]) . '/' . $caixa . '/' . $uid . "/" . $uniqueFilename;
                $pasta_f = DIR_anexos_email_fisico . $codemailusuario . '/' . md5($caixa) . '/' . $uid . "/" . $uniqueFilename;
                // $pasta_f = DIR_anexos_email_fisico . removeAcentos(explode('@', $email)[0]) . '\\' . $caixa . '\\' . $uid . "\\" . $uniqueFilename;

                $array_anexo[] = $pasta_f;

                $anexo .= '<div id="bd_anexo_' . count($array_anexo) . '"><div class="ajax-file-upload-statusbar" style="margin-left: 130px; width: 335px;">
                                   <div class="ajax-file-upload-filename">' . $uniqueFilename . '</div>
                                   <div class="ajax-file-upload-green" style=""><a href="' . $pasta . '" style="color: #fff;" download>Baixar</a></div>
                                   <div id="' . count($array_anexo) . '" class="ajax-file-upload-red delete_anexo" style="">Apagar</div>
                                   </div></div>';
            }
        }
        echo'ok|' . $anexo . '|' . json_encode($array_anexo);

        $oMailClient->LogoutAndDisconnect();
    }
} else {
    echo'erro|post';
}
