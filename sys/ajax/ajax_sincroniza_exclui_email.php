<?php

set_time_limit(240);
ini_set('max_execution_time', 240);
if (!empty($_POST['codemailusuario'])) {

    $cod_conta = Anti_Injection($_POST['codemailusuario']);
    $bd_email = new BD_FB_EMAIL();
    $bd_email->open();

    $tr = ibase_trans();
    $erros = 0;
    $erro = '';

    $sql_update = "UPDATE baixar_email SET status = 'E' WHERE CODCONTASBAIXAREMAIL = " . $cod_conta . " AND status <> 'E' AND STATUS_CONTROLE = 'V'";
    $query_update = ibase_query($tr, $sql_update);

    if (!$query_update) {
        $erros++;
        if (empty($erro)) {
            $erro = 'Erro update:' . $sql_update . "\n" . ibase_errmsg();
        }
    }


    $sql_update2 = "UPDATE baixar_email SET STATUS_CONTROLE = null WHERE CODCONTASBAIXAREMAIL = " . $cod_conta . " AND status <> 'E' AND STATUS_CONTROLE = 'O'";
    $query_update2 = ibase_query($tr, $sql_update2);

    if (!$query_update2) {
        $erros++;
        if (empty($erro)) {
            $erro = 'Erro update2:' . $sql_update2 . "\n" . ibase_errmsg();
        }
    }








    $sql_pre_select = "SELECT be1.uid, be1.caixa
FROM baixar_email be1
where be1.codcontasbaixaremail = " . $cod_conta . " AND status <> 'E' 
group by be1.uid, be1.caixa
having count(be1.codbaixaremail) > 1";

    $query_pre_select = ibase_query($tr, $sql_pre_select);
    if (!$query_pre_select) {
        $erros++;
        if (empty($erro)) {
            $erro = 'Erro update2:' . $sql_pre_select . "\n" . ibase_errmsg();
        }
    }

    $where = '';

    while ($reg_pre = ibase_fetch_assoc($query_pre_select)) {
        if (empty($where)) {
            $where .= " AND (";
            $where .= " (be.uid = " . $reg_pre['UID'] . " AND be.caixa = " . $reg_pre['CAIXA'] . ")";
        } else {
            $where .= " OR (be.uid = " . $reg_pre['UID'] . " AND be.caixa = " . $reg_pre['CAIXA'] . ")";
        }
    }
    if (!empty($where)) {
        $where .= " )";
        $sql_select = "SELECT be.codbaixaremail, be.uid FROM baixar_email be
WHERE be.codcontasbaixaremail = " . $cod_conta . " AND status <> 'E' " . $where . "
ORDER BY be.uid";

        $query_select = ibase_query($tr, $sql_select);

        if (!$query_select) {
            $erros++;
            if (empty($erro)) {
                $erro = 'Erro update2:' . $sql_select . "\n" . ibase_errmsg();
            }
        }

        $array = array();
        $array_l = array();

        $ultimo_uid = '';
        while ($reg = ibase_fetch_assoc($query_select)) {

            if ($ultimo_uid != $reg['UID'] && !empty($ultimo_uid)) {
                $array_l = array();
            }

            $array_l[] = $reg['CODBAIXAREMAIL'];
            $array[$reg['UID']] = $array_l;


            $ultimo_uid = $reg['UID'];
        }

        foreach ($array as $key => $value) {
            unset($value[0]);
            $array[$key] = $value;
        }

        $aux_ids = array();
        foreach ($array as $value) {
            foreach ($value as $dados) {
                $aux_ids[] = $dados;
            }
        }

        if (count($aux_ids) > 0) {

            $aux_ids_ = array_chunk($aux_ids, 1500);

            foreach ($aux_ids_ as $array_aux) {
                $sql_update_3 = "UPDATE baixar_email SET status = 'E' WHERE CODCONTASBAIXAREMAIL = " . $cod_conta . " AND status <> 'E' AND codbaixaremail IN (" . implode(',', $array_aux) . ")";
                $query_update_3 = ibase_query($tr, $sql_update_3);
                if (!$query_update_3) {
                    $erros++;
                    if (empty($erro)) {
                        $erro = 'Erro update:' . $sql_update_3 . "\n" . ibase_errmsg();
                    }
                }
            }
        }
    }



    if ($erros == 0) {
        //ibase_rollback($tr);
        ibase_commit($tr);
        echo 'ok';
    } else {
        ibase_rollback($tr);
        echo $erro;
    }

    $bd_email->close();
} else {
    echo 'Erro: Post';
}