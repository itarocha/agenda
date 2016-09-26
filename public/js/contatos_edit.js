$(document).ready(function() {
    $('#id_cidade').change(function() {
        carregarBairros();
    });
    carregarBairros();
});

function carregarBairros(){
    $('#id_bairro').html('<option value="">Aguarde...</option>');

    $.get('/api/ajax/bairrosporcidade/',
    {
      id_cidade: $('#id_cidade').val(),
      id_bairro: $('#old_id_bairro').val()
    }, function(data) {
        $('#id_bairro').html(data);
    });
}
