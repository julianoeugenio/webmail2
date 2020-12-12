<?php

if (!empty($_POST['codemailusuario']) && !empty($_POST['caixa'])) {

    $codemailusuario = Anti_Injection($_POST['codemailusuario']);
    $caixa = $_POST['caixa'];
    
    $forcado = 'N';    
    if (isset($_POST['forcado'])) {
        $forcado = $_POST['forcado'];
    }

    $caixa_or = $caixa;

    $caixa = (mb_convert_encoding(utf8_decode($caixa), "UTF7-IMAP", "ISO_8859-1"));

    $bd_email = new BD_FB_EMAIL();
    $bd_email->open();

    $conta = getEmailSenha($codemailusuario);

    $email = $conta['EMAIL'];
    $senha = base64_decode($conta['SENHA']);
    $servidor = explode('@', $email);
    $servidor = 'pop.' . $servidor[1];

    if (!empty($email) && !empty($senha) && !empty($servidor)) {

        $caixas = carrega_caixas($codemailusuario);
        $uid = 0;
        if (isset($caixas[$caixa_or]) && $forcado != 'S') {
            $cod_caixa = $caixas[$caixa_or];
            $sql = "SELECT MAX(uid) as min_uid FROM baixar_email WHERE caixa = " . $cod_caixa . " AND CODCONTASBAIXAREMAIL = " . $codemailusuario . " AND status <> 'E'";

            $query = ibase_query($sql);

            if ($query) {
                $reg = ibase_fetch_assoc($query);
                if ($reg['MIN_UID'] != null) {
                    $uid = $reg['MIN_UID'];
                }
            } else {
                echo 'erro|SQL';
                exit();
            }
        }
        $conn_num_msg = imap_open("{" . $servidor . ":143/novalidate-cert}" . $caixa, $email, $senha);

        if ($conn_num_msg) {
            $qtn = imap_num_msg($conn_num_msg);
            $id_uid = 0;
            if ($uid > 0 && $forcado != 'S') {
                $id_uid = imap_msgno($conn_num_msg, $uid);
            } else if ($forcado == 'S') {
                /* $caixas = carrega_caixas($codemailusuario);
                  $cod_caixas = '';
                  if (isset($caixas[$caixa]) && !empty($caixas[$caixa])) {
                  $cod_caixas = $caixas[$caixa];
                  }
                  if (!empty($cod_caixas)) {
                  $sql = "SELECT count(codbaixaremail) as TOTAL FROM baixar_email  WHERE CODCONTASBAIXAREMAIL = " . $codemailusuario . " AND status <> 'E' AND caixa = " . $cod_caixas;
                  $query = ibase_query($sql);

                  if (!$query) {
                  echo 'Erro SQL:' . $sql . "\n" . ibase_errmsg();
                  ibase_rollback($tr);
                  return -1;
                  }

                  if ($reg = ibase_fetch_assoc($query)) {
                  $id_uid = intval($reg['TOTAL']);
                  }
                  } */
            }
            imap_close($conn_num_msg);
            echo 'ok|' . ($qtn - $id_uid);
        } else {
            echo 'erro|abir conexão';
        }
    } else {
        echo 'erro|dados';
    }
    $bd_email->close();
} else {
    echo 'erro|POST';
}