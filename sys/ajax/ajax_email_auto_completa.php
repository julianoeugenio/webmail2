<?php

if (!empty($_POST['conta']) && !empty($_POST['email']) && !empty($_POST['elemento'])) {

    $conta = Anti_Injection($_POST['conta']);
    $email = Anti_Injection($_POST['email']);
    $elemento = Anti_Injection($_POST['elemento']);

    $bd_email = new BD_FB_EMAIL();
    $bd_email->open();

    $sql = "SELECT nome, email FROM contato WHERE codcontasbaixaremail = " . $conta . " and email like '%" . $email . "%'";
    $query = ibase_query($sql);
    echo '<ul>';

    while ($reg = ibase_fetch_assoc($query)) {
        //echo '<li class="item" id="'.$reg['EMAIL'].'" rel="'.$elemento.'" aria-selected="true" role="option" tabindex="0">'.  Utf8($reg['NOME']).'	&#60;'.$reg['EMAIL'].'&#62;'.'</li>';
        echo '<li class="item focusable" id="' . $reg['EMAIL'] . '" rel="' . $elemento . '" aria-selected="true" role="option" tabindex="0">' . Utf8($reg['NOME']) . '	&#60;' . $reg['EMAIL'] . '&#62;' . '</li>';
    }


    $bd_email->close();


    /* $bd = new BD_FB();
      $bd->open();

      $sql = "SELECT username, email FROM usuario WHERE status = 'A' and email like '%".$email."%'";
      $query = ibase_query($sql);

      while($reg = ibase_fetch_assoc($query)){
      echo '<li class="item" id="'.$reg['EMAIL'].'" rel="'.$elemento.'" aria-selected="true" role="option" tabindex="0">'.  Utf8($reg['USERNAME']).'	&#60;'.$reg['EMAIL'].'&#62;'.'</li>';
      }
      echo '</ul>';

      $bd->close(); */
}

