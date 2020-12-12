<?php

set_time_limit(240);
ini_set('max_execution_time', 240);
if (!empty($_POST['codemailusuario']) && !empty($_POST['caixa'])) {

    $cod_conta = Anti_Injection($_POST['codemailusuario']);
    $caixa = Anti_Injection($_POST['caixa']);

    $bd_email = new BD_FB_EMAIL();
    $bd_email->open();

    $caixas = carrega_caixas($cod_conta);


    $tr = ibase_trans();

    $erros = 0;



    $cod_caixas = '';
    if (isset($caixas[$caixa]) && !empty($caixas[$caixa])) {
        $cod_caixas = $caixas[$caixa];
    }

    if (empty($cod_caixas)) {
        $cod_caixas = insere_caixas_tr($caixa, $cod_conta, $tr);
        if (!$cod_caixas) {
            echo 'Erro ao inserir caixas';
            $erros++;
        }
    }


    $sql_update = "UPDATE baixar_email SET STATUS_CONTROLE = 'V' WHERE CODCONTASBAIXAREMAIL = " . $cod_conta . " AND status <> 'E' AND caixa = " . $cod_caixas;
    $query_update = ibase_query($tr, $sql_update);

    if (!$query_update) {
        echo 'Erro: ' . $sql_update . "\n" . ibase_errmsg();
        $erros++;
    }
    
    if($erros == 0){
        ibase_commit($tr);
        echo 'ok';
    }else{
        ibase_rollback($tr);        
    }
} else {
    echo 'Erro: POST';
}