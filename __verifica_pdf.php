<?php

/*
 * Verificação de arquivos PDFs
 * ***************************************************************************************************
 */


function show_PDF($path) {
    // Abrimos o arquivo com permissão de leitura
    $fp = fopen($path, "r");

    // Armazenamos o conteúdo do arquivo na variável buffer
    $buffer = fread($fp, filesize($path));

    // Fechamos o arquivo
    fclose($fp);

    // Enviamos para o cabeçalho o tipo de arquivo
    header("Content-type: application/pdf");

    // Fazemos a liberação do arquivo no navegador
    print $buffer;
}

$path = utf8_decode(urldecode($_SERVER["DOCUMENT_ROOT"] . $_SERVER["REQUEST_URI"]));
// 1) Verifica se arquivo existe
if (file_exists($path)) {

    if (!isset($_SESSION))
        session_start();

    // 3) Verifica se usuário está logado
    if (!isset($_SESSION['cod_conta_email'])) {

        // Destrói a sessão por segurança
        session_destroy();

        echo "Arquivo n&atilde;o encontrado!";
        exit;
    } else {//Se estiver logado,abre o pdf
        header('Content-Type: text/html; charset=utf-8');
        show_PDF($path);   
    }
} else {//Se arquivo não existe
    echo "Arquivo não encontrado!";
    exit;
}
?>