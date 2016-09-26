$(document).ready(function() {
    $('#cod_area_atuacao_empresa').change(function() {
        $('#cod_area_atuacao_cargo').html('<option>Aguarde...</option>');
        $.post(LINK_DEFAULT + 'recrutador/gerenciamentorecrutadores/ajaxbuscacargo/', {
            cod_area_atuacao_empresa: $('#cod_area_atuacao_empresa').val()
        }, function(data) {
            $('#cod_area_atuacao_cargo').html(data);
        });
    });
    $(".js-multselect-header").multiselect({
        header: false
    });
});