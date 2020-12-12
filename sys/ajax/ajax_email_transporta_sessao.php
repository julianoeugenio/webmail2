<?php

if (!isset($_SESSION))
    session_start();

$_SESSION['cod_sessao_tranporte'] = $_SESSION['cod_sessao'];
?>