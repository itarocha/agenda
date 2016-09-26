$(function() {
    
    $('#des_imagem').change(function(){
        $('#incluir-logomarca').val($(this).val());
    });
    
    //Busca cep por ajax
    $('#num_cep').blur(function() {
        $.post(LINK_MODULE + "opcao/ajaxcepdados", {
            "numcep": this.value
        }, function(data) {
            if (data.sgl_estado) {
                $('#cod_pais').val(data.cod_pais);
                $('#des_endereco').val(data.logradouro);
                $('#nom_bairro').val(data.nom_bairro);
                selecionaEstado(data.cod_pais, data.cod_estado);
                $.post(LINK_MODULE + "opcao/ajaxbuscacidades", {
                    "cod_estado": data.cod_estado
                }, function(data2) {
                    $('#cod_cidade').find('option').remove().end().append(data2.retorno);
                    $('#cod_cidade').val(data.cod_cidade);
                }, "json");
                $('#cod_cidade').val(data.cod_cidade);
            }
        }, "json");
    });
    
    $('#cod_pais').change(function() {
        $('#cod_estado').html('<option>Aguarde...</option>');
        $('#cod_cidade').html('<option>Selecione uma opção ...</option>');
        $.post(LINK_DEFAULT + 'recrutador/opcao/ajaxbuscaestado/', {
            cod_pais: $('#cod_pais').val()
        }, function(data) {
            $('#cod_estado').html(data);
        });
    });
    $('#cod_estado').change(function() {
        $('#cod_cidade').html('<option>Aguarde...</option>');
        $.post(LINK_DEFAULT + 'recrutador/opcao/ajaxbuscacidade/', {
            cod_estado: $('#cod_estado').val()
        }, function(data) {
            $('#cod_cidade').html(data);
        });
    });
    
    $('#cod_cidade').click(function(){
        if($('#cod_estado').val() == ''){
            fancyAlert('Primeiro selecione o Estado onde está localizada a empresa onde você trabalha.', 'ale', '');
        }
    });
    
});

function selecionaEstado(cod_pais, estado_selected) {
    $('#cod_estado').html('<option>Aguarde...</option>');
    $.post(LINK_DEFAULT + 'recrutador/opcao/ajaxbuscaestado/', {
        cod_pais: cod_pais
    }, function(data) {
        $('#cod_estado').html(data);
        $('#cod_estado').val(estado_selected);
    });
}