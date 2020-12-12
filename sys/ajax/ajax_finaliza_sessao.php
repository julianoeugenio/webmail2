<?php

if (!isset($_SESSION))
    session_start();



$bd = new BD_FB_EMAIL();
$bd->open();

remove_sessao($_SESSION['cod_conta_email']);

$bd->close();

unset($_SESSION['cod_conta_email']);
unset($_SESSION['cod_sessao']);
?>
