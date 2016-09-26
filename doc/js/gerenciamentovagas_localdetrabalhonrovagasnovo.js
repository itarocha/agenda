$(document).ready(function() {
    $('#cod_pais').change(function() {
        $('#cod_estado').html('<option>Aguarde...</option>');
        $('#cod_cidade').html('<option>Selecione uma opção ...</option>');
        $.post(LINK_DEFAULT + 'recrutador/gerenciamentovagas/ajaxbuscaestado/', {
            cod_pais: $('#cod_pais').val()
        }, function(data) {
            $('#cod_estado').html(data);
        });
    });
    $('#cod_estado').change(function() {
        $('#cod_cidade').html('<option>Aguarde...</option>');
        $.post(LINK_DEFAULT + 'recrutador/gerenciamentovagas/ajaxbuscacidadevaga/', {
            cod_estado: $('#cod_estado').val()
        }, function(data) {
            $('#cod_cidade').html(data);
        });
    });
    $('#cod_cidade').click(function() {
        if ($('#cod_estado').val() == '') {
            fancyAlert('Primeiro selecione o Estado.', 'ale', '');
        }
    });
});
