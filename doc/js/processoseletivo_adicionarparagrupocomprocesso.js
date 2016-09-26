$(document).ready(function() {
    $('#ind_opcao').change(function() {
        opcaoChange();
    });
    $('#ind_opcao').keydown(function() {
        opcaoChange();
    });
    opcaoChange();
    $('#cod_processo_seletivo').change(function() {
        carregarListas();
    });
});

// A = Adicionar
// S = Selecionar
function opcaoChange(){
    if ($('#ind_opcao').val() == "A") { 
        $('#div_adicionar').show();
        $('#div_selecionar').hide();
    } else {
        $('#div_adicionar').hide();
        $('#div_selecionar').show();
    }
}

function carregarListas(){
    $('#cod_proc_seletivo_lista').html('<option value="">Aguarde...</option>');
    $.post(LINK_DEFAULT + 'recrutador/processoseletivo/ajaxbuscalistas/', {
        cod_processo_seletivo: $('#cod_processo_seletivo').val()
    }, function(data) {
        $('#cod_proc_seletivo_lista').html(data);
    });    
}
