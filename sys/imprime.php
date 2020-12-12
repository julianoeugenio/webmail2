<?php

set_time_limit(120);



include DIR_classes . 'MailSo/MailSo.php';
define("MPDF_PATH", DIR_funcoes . "include_boleto/MPDF/");
include(MPDF_PATH . "mpdf.php");

if (!isset($_SESSION))
    session_start();

if (isset($_SESSION['cod_conta_email']) && !empty($_SESSION['cod_conta_email'])) {
    $id = $_SESSION['cod_conta_email'];

    $uid = intval(Url::getURL(1));
    $caixa = base64_decode(Url::getURL(2));

    if (is_int($uid) && $uid > 0 && !empty($caixa)) {

        $html = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//E">
<HTML>
    <HEAD>
        <TITLE>Email</TITLE>
        <style type=text/css>

            @import "reset.css";

            <!--.cp {  font: bold 10px Arial; color: black}
            <!--.ti {  font: 9px Arial, Helvetica, sans-serif}
            <!--.ld { font: bold 15px Arial; color: #000000}
            <!--.ct { FONT: 9px "Arial Narrow"; COLOR: #000033}
            <!--.cn { FONT: 9px Arial; COLOR: black }
            <!--.bc { font: bold 20px Arial; color: #000000 }
            <!--.ld2 { font: bold 12px Arial; color: #000000 }
            -->

            page[size="A4"] {
                background: white;
                width: 18cm;
                height: 30cm;
                display: block;
                margin: 0 auto;
            }

            @media print {
                page[size="A4"] {
                    margin: 0;
                    box-shadow: 0;
                    max-height: 100%;
                    /*height: 14.85cm;*/
                    height: 30cm;
                }
          </style>
        </head>

        <BODY text=#000000 bgColor=#ffffff topMargin=0 rightMargin=0>
        <page size="A4" >
        ' . Carrega_email_web($id, $caixa, $uid) . '
        </page>

        <!--Final terceira parte-->
    </BODY>
</HTML>';

        $mpdf = new mPDF('utf-8', 'A4', 0, '', 0, 0, 0, 0, 0, 0);


        $mpdf->WriteHTML($html);
        $mpdf->Output();
        exit();
    } else {
        echo 'Erro Dados inválidos';
        exit();
    }
} else {
    echo 'Erro usário não conectado!';
    exit();
}
?>

