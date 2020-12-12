/*Validação de email */
function validacaoEmail(email)
{
    usuario = email.substring(0, email.indexOf("@"));
    dominio = email.substring(email.indexOf("@") + 1, email.length);
    if ((usuario.length >= 1) &&
            (dominio.length >= 3) &&
            (usuario.search("@") == -1) &&
            (dominio.search("@") == -1) &&
            (usuario.search(" ") == -1) &&
            (dominio.search(" ") == -1) &&
            (dominio.search(".") != -1) &&
            (dominio.indexOf(".") >= 1) &&
            (dominio.lastIndexOf(".") < dominio.length - 1))
    {
        return true;
    } else
    {
        return false;
    }
}

function msg_box_aviso(msg_tipo, msg_text, bt_id, cod_rel) {

//msg_tipo  -confirm > confirmacao
//          -erro > erro
//          -info > aviso

//msg_texto - texto do corpo da mensagem


    var retorno_ = "";
    var msg_nome = "";
    var css_class = "";

    var bt_box = "";

    if (msg_tipo == "confirm_auditoria") {
        retorno_ += "<a class='close-reveal-modal'></a><a class='fecha-modal-auditoria'>×</a><div class='clear'></div>";
    } else if (msg_tipo == "login_perm") {
        retorno_ += "<a class='close-reveal-modal'></a><div class='clear'></div>";
    } else {
        retorno_ += "<a class='close-reveal-modal'>×</a><div class='clear'></div>";
    }

    if (msg_tipo == "confirm") {
        msg_tipo = "Confirmação"
        msg_nome = "msgbox_sucesso.png";
        css_class = "showbox_txtcorpo_confirm"
        bt_box = "<br><br><div style='text-align: center;' class='div_showbox_btn'><a href='' class='grid_bt_atv bt_showbox_confirm_sim' rel='" + cod_rel + "' id='box_bt_sim_" + bt_id + "' title='Sim' alt='Sim'>Sim</a><a href='' class='grid_bt_dsv bt_showbox_confirm_nao bt_showbox_close' rel='" + cod_rel + "' id='box_bt_nao_" + bt_id + "' title='Não' alt='Não'>Não</a></div>";

    } else if (msg_tipo == "confirm_auditoria") {
        msg_tipo = "Confirmação"
        msg_nome = "msgbox_sucesso.png";
        css_class = "showbox_txtcorpo_confirm"
        bt_box = "<br><br><div style='text-align: center;' class='div_showbox_btn'><a href='' class='grid_bt_atv bt_showbox_confirm_sim' rel='" + cod_rel + "' id='box_bt_sim_" + bt_id + "' title='Sim' alt='Sim'>Sim</a><a href='' class='grid_bt_dsv bt_showbox_confirm_nao' rel='" + cod_rel + "' id='box_bt_nao_auditoria_" + bt_id + "' title='Não' alt='Não'>Não</a></div>";

    } else if (msg_tipo == "erro") {
        msg_nome = "msgbox_erro.png";
        msg_tipo = "Erro"
        css_class = "showbox_txtcorpo_erro"
        bt_box = "<br><br><div style='text-align: center;' class='div_showbox_btn'><a href='' class='grid_bt_dsv bt_showbox_confirm_nao bt_showbox_close' rel='" + cod_rel + "' id='box_bt_erro_" + bt_id + "' title='Voltar' alt='Voltar'>Voltar</a></div>";

    } else if (msg_tipo == "info") {
        msg_nome = "msgbox_info.png";
        msg_tipo = "Aviso"
        css_class = "showbox_txtcorpo_info"
        bt_box = "<br><br><div style='text-align: center;' class='div_showbox_btn'><a href='' class='grid_bt_atv bt_showbox_confirm_sim bt_showbox_close' rel='" + cod_rel + "' id='box_bt_info_" + bt_id + "' title='Cancelar Reserva' alt='Cancelar Reserva'>Confirmar</a></div>";
    } else if (msg_tipo == "info_id") {
        msg_nome = "msgbox_info.png";
        msg_tipo = "Aviso"
        css_class = "showbox_txtcorpo_info"
        bt_box = "<br><br><div style='text-align: center;' class='div_showbox_btn' id='copo_modal'><a href='' class='grid_bt_atv bt_showbox_confirm_sim' rel='" + cod_rel + "' id='box_bt_info_" + bt_id + "' title='Cancelar Reserva' alt='Cancelar Reserva'>Confirmar</a></div>";
    } else if (msg_tipo == "login_perm") {
        msg_nome = "msgbox_info.png";
        msg_tipo = "Login Permissão"
        css_class = "showbox_txtcorpo_confirm"

        bt_box = "<form id='login' autocomplete='off'>\n\
                    <fieldset id='inputs'>\n\
                        <input id='username_perm' type='text' placeholder='' required>\n\
                        <input id='password_perm' type='password' onkeypress='logar_enter(event)' placeholder='Senha' autofocus required>\n\
                    </fieldset>\n\
                        <a href='' id='box_bt_sim_" + bt_id + "' class='grid_bt_atv bt_showbox_login' title='Permitir' alt='Permitir'>Permitir</a>\n\
                        <a href='' id='box_bt_nao_" + bt_id + "' class='grid_bt_dsv bt_showbox_login' title='Cancelar' alt='Cancelar'>Cancelar</a>\n\
                        <div id='div_erro_login_perm' style='color:red;font-weight: bold;'></div>\n\
                   </form><script>$('#username').val(''); </script>";


        //bt_box = "<br><br><div style='text-align: center;' class='div_showbox_btn' id='copo_modal'><a href='' class='grid_bt_atv bt_showbox_confirm_sim' rel='" + cod_rel + "' id='box_bt_info_" + bt_id + "' title='Cancelar Reserva' alt='Cancelar Reserva'>Confirmar</a></div>";
    } else {
        msg_tipo = "selecione um tipo";
    }

    retorno_ += "<div class='showbox_txttitulo_info'>" + msg_tipo + " <img src='" + url + "img/" + msg_nome + "'></div>";
    retorno_ += "<br><div class='" + css_class + " div_showbox_txt'>" + msg_text + "</div>";
    retorno_ += bt_box;

    return retorno_;
}
/*********************************************************************************
 * Função carregando...
 */
function carregando(acao) {

    if (acao == 'show') {
        $("#box_carregando").fadeIn("fast");
    }

    if (acao == 'hide') {
        $("#box_carregando").fadeOut("fast");
    }

}

$(document).on("click", ".bt_showbox_close", function () {
    $a(".close-reveal-modal").trigger("click");
    return false;
});
//Replace em vários caracteres
String.prototype.replaceAll = function (match, replace) {
    return this.split(match).join(replace);
};