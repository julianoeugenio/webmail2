<?php
if (!isset($_SESSION))
    session_start();

if (!empty($_POST['codemailusuario']) && !empty($_POST['maximizado'])) {
    $codemailusuario = $_POST['codemailusuario'];
    $maximizado = $_POST['maximizado'];

    $bd = new BD_FB_EMAIL();
    $bd->open();

    $sql = "UPDATE contas_baixar_email SET maximizado = '" . $maximizado . "' WHERE CODCONTASBAIXAREMAIL = " . $codemailusuario;
    $query = ibase_query($sql);

    if ($query) {
        $_SESSION['maximizado_conta_email'] = $maximizado;
        echo 'ok';
    } else {
        echo 'Erro: ' . $sql . "\n" . ibase_errmsg();
    }

    $bd->close();
}
