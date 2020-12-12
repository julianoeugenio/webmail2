<?php

if (substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip')) {
    ob_start("ob_gzhandler");
} else {
    ob_start();
}

require "config.php";

//Carrega a classe de conexao
require("BD_FB_EMAIL.class.php");

require("global_webmail.func.php");

/*
 * Verifica navegador
 */
//require("_verifica_navegador.php");
//Carrega a classe de URL
require DIR_classes . "URL.class.php";

$pdf = URL::getPDF();

$area = Url::getURL(0);
$sub_area_arquivo = Url::getURL(1);

if ($area == '' || $area == 'home') {
    $area = "home";
    $sub_area_arquivo = "home";
}

$local = DIR_principal;
$local_area = DIR_principal . $area;

if ($pdf == '.pdf') {
    require "__verifica_pdf.php";
} else {

    if ($area == "ajax") {
        $local_area = DIR_principal . 'ajax/' . $sub_area_arquivo;
    }

    if (file_exists($local_area . ".php")) {
        require($local_area . ".php");
    } else {
        header("Location: " . URL . "home");
        exit;
    }
}
?>
