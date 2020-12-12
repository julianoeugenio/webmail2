<?php
set_time_limit(120);



include DIR_classes . 'MailSo/MailSo.php';

if (!empty($_POST['codemailusuario']) && !empty($_POST['pasta']) && !empty($_POST['nome'])) {
    $codemailusuario = $_POST['codemailusuario'];
    $pasta_pai = $_POST['pasta'];
    $nome_pasta = $_POST['nome'];
    
    //$pasta_pai = mb_convert_encoding(utf8_decode(Utf8($pasta_pai)), "UTF7-IMAP", "ISO_8859-1");
   // $nome_pasta = mb_convert_encoding(utf8_decode(Utf8($nome_pasta)), "UTF7-IMAP", "ISO_8859-1");
    
    		$pasta_pai = \MailSo\Base\Utils::ConvertEncoding($pasta_pai,
			\MailSo\Base\Enumerations\Charset::UTF_8,
			\MailSo\Base\Enumerations\Charset::UTF_7_IMAP);
                
    		$nome_pasta = Utf8($nome_pasta);
    
    		//$nome_pasta = removeAcentos($nome_pasta);


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
        
           try {
              $res = $conn->FolderCreate($nome_pasta, $pasta_pai); 
              
               
        if(is_object($res) || is_array($res)){
            echo 'ok';
        }else{
            echo 'Erro ao criar pasta.';
        }
              
        } catch (Exception $e) {
            echo 'Erro: criar pasta:';
            echo "\n";
            var_dump($e);
        }
       
        
        $oMailClient->LogoutAndDisconnect();
    }else{
        echo 'Erro: dados conta.';
    }
}else{
    echo 'Erro: POST.';
}