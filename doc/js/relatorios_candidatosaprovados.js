$(document).ready(function() {

    $('#cod_vaga_empresa').change(function() {
        carregarProcessos();
    });
    
    $('#btEnviar').click(function(){
       
      var url = buildurl(false); 
        
       $('#formfiltro').attr('action',url);
       $('#formfiltro').submit();
    });
    
    $('#bt_imprimir').click(function() {
        
        // Montar url!!!!!!!!!!
        
        $('#bt_imprimir').fancybox({
            closeBtn: false,
            closeClick: false,
            modal: true,
            fitToView: true,
            openEffect: 'none',
            closeEffect: 'none',
            maxWidth: 892,
            maxHeight: 436,
            width: 892,
            height: 436,
            padding: 0,
            autoSize: false,
            type: 'iframe'
        });
        var url = buildurl(true);
        $('#bt_imprimir').attr('href', url);
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
    
    $('.fancyboxcall').fancybox({
        closeBtn: false,
        closeClick: false,
        modal: true,
        fitToView: true,
        openEffect: 'none',
        closeEffect: 'none',
        maxWidth: 892,
        maxHeight: 480,
        width: 892,
        height: 480,
        padding: 0,
        autoSize: false,
        type: 'iframe'
    });
});

function carregarProcessos(){
    $('#cod_processo_seletivo').html('<option value="">Aguarde...</option>');
    $.post(LINK_DEFAULT + 'recrutador/relatorios/ajaxbuscaprocessos/',
    {
        cod_vaga_empresa: $('#cod_vaga_empresa').val(),
        //cod_vaga: $('#cod_vaga').val()
    }, function(data) {
        $('#cod_processo_seletivo').html(data);
    });
}

function buildurl(imprimir) {
    var par = '';
    var cod_pessoa_juridica = $('#cod_pessoa_juridica').val();
    var cod_vaga_empresa = $('#cod_vaga_empresa').val();
    var cod_processo_seletivo = $('#cod_processo_seletivo').val();
    var ind_status_processo = $('#ind_status_processo').val();
    var ind_tipo_vaga = $('#ind_tipo_vaga').val();
    var dta_inicial = $('#dta_inicial').val();
    var dta_final = $('#dta_final').val();

    if (cod_pessoa_juridica){
        par = par + '/pessoajuridica/'+cod_pessoa_juridica;
    }
    if (cod_vaga_empresa){
        par = par + '/empresa/'+cod_vaga_empresa;
    }
    if (cod_processo_seletivo){
        par = par + '/processo/'+cod_processo_seletivo;
    }
    if (ind_status_processo){
        par = par + '/statusprocesso/'+ind_status_processo;
    }
    if (dta_inicial){
        dta_inicial = dta_inicial.replace(/\//g, "-");
        par = par + '/datini/'+dta_inicial;
    }
    if (dta_final){
        dta_final = dta_final.replace(/\//g, "-");
        par = par + '/datfim/'+dta_final;
    }
    if (imprimir === true) {
        return LINK_CONTROLLER+'/candidatosaprovadosrel'+par;
    } else {
        return LINK_ACTION+par;
    }
}