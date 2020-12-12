<?php

set_time_limit(120);



include DIR_classes . 'MailSo/MailSo.php';

if (!empty($_POST['codemailusuario']) && !empty($_POST['nome_antigo']) && !empty($_POST['nome_novo'])) {

    $codemailusuario = $_POST['codemailusuario'];
    $nome_antigo = $_POST['nome_antigo'];
    $nome_novo = $_POST['nome_novo'];
    $caixa_hash = md5($nome_antigo);
    $nome_antigo = \MailSo\Base\Utils::ConvertEncoding($nome_antigo, \MailSo\Base\Enumerations\Charset::UTF_8, \MailSo\Base\Enumerations\Charset::UTF_7_IMAP);

    $nome_novo = Utf8($nome_novo);

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
      echo $servidor."\n"; */

    $oData = null;

    if (!empty($email) && !empty($senha) && !empty($servidor)) {

        try {
            $oMailClient = \MailSo\Mail\MailClient::NewInstance();

            $conn = $oMailClient
                    ->Connect($servidor, 143, \MailSo\Net\Enumerations\ConnectionSecurityType::NONE)
                    ->Login($email, $senha);
        } catch (Exception $e) {
            echo 'Erro: Conexão:';
            echo "\n";
            var_dump($e);
        }
        $bd = new BD_FB_EMAIL();
        $bd->open();
        try {

            $sql = "UPDATE caixas SET DESCCAIXAS = '" . utf8_decode($nome_novo) . "', HASH='" . md5($nome_novo) . "' WHERE codcontasbaixaremail = " . $codemailusuario . " AND HASH = '" . $caixa_hash . "'";
            $query_update = ibase_query($sql);
            if (!$query_update) {
                echo 'erro: SQL UPDATE' . $sql . "\n" . ibase_errmsg();
            } else {
                $res = $conn->FolderRename($nome_antigo, $nome_novo);
                if (is_object($res) || is_array($res)) {
                    echo 'ok';
                } else {
                    echo 'Erro ao criar pasta.';
                }
            }
        } catch (Exception $e) {
            echo 'Erro: ao renomear pasta:';
            echo "\n";
            var_dump($e);
        }

        $bd->close();
        $oMailClient->LogoutAndDisconnect();
    } else {
        echo 'Erro: dados conta.';
    }
} else {
    echo 'Erro: POST.';
}