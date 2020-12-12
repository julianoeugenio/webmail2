<?php

if (!isset($_SESSION))
    session_start();
/*

  Arquivo js/imovel_adicionar_foto.js não pega da config
  Arquivo ajax/imovel/ajax_imovel_excluir_foto_all.php não pega da config

 */

//Definindo data e hora corretamente
date_default_timezone_set('America/Sao_Paulo');

//Diret�rio principal
define('DIR', dirname(__FILE__)."/");

define('DIR_principal', DIR . "sys/");

//Pastas operacionais
define('DIR_classes', DIR . "classes/");
define('DIR_funcoes', DIR . "funcoes/");
define('DIR_log', "log/");


define('DIR_siga_js_fisico', DIR . "js/");
define('DIR_siga_css_fisico', DIR . "css/");

//Define a URL base principal
$start_Url = strlen($_SERVER["DOCUMENT_ROOT"]);
$exclude_Url = substr($_SERVER["SCRIPT_FILENAME"], $start_Url, -9);
if (!empty($exclude_Url) == "/") {
    $base_Url = $exclude_Url;
} else {
    $base_Url = "/" . $exclude_Url;
}
define('URL', $base_Url);
define('DIR_siga_js', URL . "js/");
define('DIR_siga_css', URL . "css/");
define('DIR_siga_img', URL . "img/siga/");

define('DIR_anexos_email', URL . "siga_anexos_email/");
define('DIR_anexos_email_fisico', DIR . "siga_anexos_email/");
define('DIR_email_envia_anexo_fisico', DIR . "anexo_email_envia/");

define('rodando_local', true);

$ip = $_SERVER["REMOTE_ADDR"];


/**
 * Logs de erros
 * 
 */
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', DIR_log . 'error_log.txt');
error_reporting(E_ERROR);

function my_error_handler($cod, $msg, $arq, $lin, $ctx) {

    $erro = "Erro PHP\r\n";
    $erro .= "Código: $cod\r\n";
    $erro .= "Mensagem: $msg\r\n";
    $erro .= "Arquivo: $arq\r\n";
    $erro .= "Linha: $lin\r\n";
    $erro .= "Data: " . date("d/m/y") . " - Hora: " . date("h:i:s");
    $erro .= "Contexto:\r\n " . print_r($ctx, 1);
    $erro .= "\r\n\r\n********************\r\n\r\n";

    error_log($erro, 3, DIR_log . 'error_log.txt');
}

set_error_handler("my_error_handler");




