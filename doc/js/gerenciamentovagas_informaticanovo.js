$(document).ready(function() {

    $('#cod_cla_con_informatica').change(function() {
        $('#cod_con_informatica').html('<option>Aguarde...</option>');
        $.post(LINK_DEFAULT + 'recrutador/gerenciamentovagas/ajaxbuscaconinfo/', {
            cod_cla_con_informatica: $('#cod_cla_con_informatica').val()
        }, function(data) {
            $('#cod_con_informatica').html(data);
        });
    });
    

});