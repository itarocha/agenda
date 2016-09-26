$(document).ready(function() {
    $('#btEnviar').click(function(){
        var par = '';
        var cod_vaga_empresa = $('#cod_vaga_empresa').val();
        var cod_vaga = $('#cod_vaga').val();
        var ind_status_agenda_entrevista = $('#ind_status_agenda_entrevista').val();
        var cod_pessoa_fisica = $('#cod_pessoa_fisica').val();
        var dta_inicial = $('#dta_inicial').val();
        var dta_final = $('#dta_final').val();

        if (cod_vaga_empresa){
            par = par + '/empresa/'+cod_vaga_empresa;
        }
        if (cod_vaga){
            par = par + '/vaga/'+cod_vaga;
        }
        if (ind_status_agenda_entrevista){
            par = par + '/status/'+ind_status_agenda_entrevista;
        }
        if (cod_pessoa_fisica){
            par = par + '/candidato/'+cod_pessoa_fisica;
        }
        if (dta_inicial){
            dta_inicial = dta_inicial.replace(/\//g, "-");
            par = par + '/datini/'+dta_inicial;
        }
        if (dta_final){
            dta_final = dta_final.replace(/\//g, "-");
            par = par + '/datfim/'+dta_final;
        }
       $('#formfiltro').attr('action',LINK_ACTION+par);
       $('#formfiltro').submit();
    });


/*
        $script = "$('.btnBuscaCandidato').click(function() {
            var form = $(this).closest('form');
            var metode = form.find('#ind_metodo').val();
            if(metode){
                form.attr('action', '" . $this->_url . $this->_module . '/' . $this->_controller . '/' . $this->_action . "/id/" . $this->_getParam('id') . "/mb/'+metode);
            } else {
                form.attr('action', '" . $this->_url . $this->_module . '/' . $this->_controller . '/' . $this->_action . "/id/" . $this->_getParam('id') . "');
            }
            form.submit();
        });";
    */



    $('#cod_vaga_empresa').change(function() {
        carregarVagas();
        carregarCandidatos();
    });

    $('#cod_vaga').change(function() {
        carregarCandidatos();
    });

    $('#dta_final').focusout(function() {
        var dta_inicial = $("#dta_inicial").val();
        var dta_final = $("#dta_final").val();

        if (dta_inicial && dta_final) {
            var compara1 = parseInt(dta_inicial.split("/")[2].toString() + dta_inicial.split("/")[1].toString() + dta_inicial.split("/")[0].toString());
            var compara2 = parseInt(dta_final.split("/")[2].toString() + dta_final.split("/")[1].toString() + dta_final.split("/")[0].toString());
            if (compara1 > compara2)
            {
                $("#dta_final").val('');
                $("#dta_final").focus();
                fancyAlert("Data final não pode ser anterior à data inicial", 'ale', '');
            }
        }
    });

});

// http://fancybox.net/howto
function confirmCancelarEntrevista(id) {
    jQuery.fancybox({
        closeBtn: false,
        closeClick: false,
        modal: true,
        fitToView: true,
        width: '526px',
        height: '152px',
        padding: '0px',
        border: '0px',
        autoSize: false,
        openEffect: 'none',
        closeEffect: 'none',
        scrolling: false,
        content: '<div class="w-100 bg-amarelo fancy-header">'
                + '<div class="fancy-header-ic f-left"></div>'
                + '<div class="ff-opensans f-18 c-branco fancy-header-tt f-left">Atenção</div>'
                + '</div>'
                + '<div class="fancy-main">'
                + '<p class="f-16 f-bold">Realmente deseja cancelar esta Entrevista ?</p>'
                + '<div class="bts_deletar_modal">'
                + '<span class="f-left b-submit-maior c-branco bg-verde margin-auto b-ic-adicionar" onclick="cancelarEntrevista(\'' + id + '\');">OK</span>'
                + '<span class="b-submit-maior-ic b-ic-deletar c-branco bg-laranja f-left w-margin-left20" onclick="closeFancyboxAndRedirectToUrl();">Não</span>'
                + '</div>'
                + '<a href="javascript:;" class="fancybox-item fancybox-close" title="Cancelar" onClick="closeFancyboxAndRedirectToUrl();"></a>'
    });
}

function cancelarEntrevista(id) {

    var url = LINK_MODULE + "agendaentrevista/ajaxcancelarentrevista";

    //alert(url);

    $.post(url, {
        "id": id
    }, function(data) {
        if (data.msg == 'ok') {
            closeFancyboxAndRedirectToUrl(LINK_MODULE + "agendaentrevista/index");
            //closeFancyboxAndRedirectToUrl('');
        } else {
            fancyAlert('Erro ao cancelar entrevista. <br/>Entre em contato com o suporte técnico', 'err', LINK_MODULE + '/index');
        }
    }, "json");
}

function carregarVagas(){
    //fancyAlert("MUDOU empresa", 'ale', '');
    $('#cod_vaga').html('<option value="">Aguarde...</option>');
    $.post(LINK_DEFAULT + 'recrutador/agendaentrevista/ajaxbuscavagas/', {
        cod_vaga_empresa: $('#cod_vaga_empresa').val()
    }, function(data) {
        $('#cod_vaga').html(data);
    });
}

function carregarCandidatos(){
    $('#cod_pessoa_fisica').html('<option value="">Aguarde...</option>');
    $.post(LINK_DEFAULT + 'recrutador/agendaentrevista/ajaxbuscacandidatos/',
    {
        cod_vaga_empresa: $('#cod_vaga_empresa').val(),
        cod_vaga: $('#cod_vaga').val()
    }, function(data) {
        $('#cod_pessoa_fisica').html(data);
    });
}
