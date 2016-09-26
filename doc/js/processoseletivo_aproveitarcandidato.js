$(document).ready(function() {
    $('#cod_processo_seletivo').change(function() {
        carregarListas();
    });
});

function carregarListas(){
    //fancyAlert("MUDOU processo seletivo "+$('#cod_processo_seletivo').val(), 'ale', '');

    
    
    $('#cod_proc_seletivo_lista').html('<option value="">Aguarde...</option>');
    $.post(LINK_DEFAULT + 'recrutador/processoseletivo/ajaxbuscalistas/', {
        cod_processo_seletivo: $('#cod_processo_seletivo').val()
    }, function(data) {
        $('#cod_proc_seletivo_lista').html(data);
    });    
}
