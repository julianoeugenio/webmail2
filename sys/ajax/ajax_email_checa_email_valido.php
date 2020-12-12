<?php

if (!isset($_SESSION))
    session_start();

//error_reporting(0);
if (!empty($_POST['email']) && !empty($_POST['senha']) && !empty($_POST['conta_antiga'])) {

    $email = Anti_Injection($_POST['email']);
    $senha = Anti_Injection($_POST['senha']);
    $conta_antiga = Anti_Injection($_POST['conta_antiga']);

    if ($conta_antiga == 'N') {
        imap_timeout(IMAP_OPENTIMEOUT, 1);
        $servidor = "pop." . explode("@", $email)[1];
        $caixaDeCorreio = imap_open("{" . $servidor . ":143/novalidate-cert}", $email, $senha);
    } else {
        $caixaDeCorreio = true;
    }

    if ($caixaDeCorreio) {

        $bd = new BD_FB_EMAIL();
        $bd->open();

        $conta_antiga_inv = 'S';

        if ($conta_antiga == 'S') {
            $conta_antiga_inv = 'N';
        }

        $sql = "SELECT codcontasbaixaremail, maximizado, itens_pag FROM contas_baixar_email WHERE email = '" . $email . "' AND senha = '" . base64_encode($senha) . "' AND flag_baixar = '" . $conta_antiga_inv . "'";
        $query = ibase_query($sql);

        if ($query) {

            $reg = ibase_fetch_assoc($query);

            if (!empty($reg['CODCONTASBAIXAREMAIL'])) {
                $_SESSION['cod_conta_email'] = $reg['CODCONTASBAIXAREMAIL'];
                $_SESSION['nome_conta_email'] = $email;
                $_SESSION['maximizado_conta_email'] = $reg['MAXIMIZADO'];
                $_SESSION['itens_pag'] = $reg['ITENS_PAG'];
                $cod_sessao = gera_sessao($reg['CODCONTASBAIXAREMAIL']);
                if ($cod_sessao) {
                    $_SESSION['cod_sessao'] = $cod_sessao;
                    echo'ok|' . $reg['CODCONTASBAIXAREMAIL'] . '|' . $reg['MAXIMIZADO'] . '|' . $cod_sessao;
                } else {
                    session_destroy();
                    echo'erro|Criar sessão';
                }
            } else if ($conta_antiga == 'N') {

                $GEN_CONTAS_BAIXAR_EMAIL_ID = get_ultimo_gen_email("CONTAS_BAIXAR_EMAIL_ID");

                $sql_insert = "INSERT INTO contas_baixar_email (codcontasbaixaremail, email, senha, flag_baixar, maximizado, itens_pag)"
                        . "VALUES(" . $GEN_CONTAS_BAIXAR_EMAIL_ID . ", '" . $email . "', '" . base64_encode($senha) . "', 'S', 'S', 10)";

                $query_insert = ibase_query($sql_insert);

                if ($query_insert) {


                    $_SESSION['cod_conta_email'] = $GEN_CONTAS_BAIXAR_EMAIL_ID;
                    $_SESSION['nome_conta_email'] = $email;
                    $_SESSION['maximizado_conta_email'] = 'S';
                    $_SESSION['itens_pag'] = '10';
                    $cod_sessao = gera_sessao($GEN_CONTAS_BAIXAR_EMAIL_ID);
                    if ($cod_sessao) {
                        $_SESSION['cod_sessao'] = $cod_sessao;
                        echo'ok|' . $GEN_CONTAS_BAIXAR_EMAIL_ID . '|' . 'S' . '|' . $cod_sessao;
                    } else {
                        session_destroy();
                        echo'erro|Criar sessão';
                    }
                } else {
                    session_destroy();
                    echo'erro|' . $sql_insert;
                }
            } else {
                echo'erro|Email ou senha incorretos.';
            }
        } else {
            session_destroy();
            echo'erro|' . $sql;
        }

        $bd->close();
    } else {
        session_destroy();
        echo 'erro|AQUI' . imap_last_error();
    }
    if ($conta_antiga == 'N') {
        imap_close($caixaDeCorreio);
    }
} else {
    session_destroy();
    echo'erro|POST';
}