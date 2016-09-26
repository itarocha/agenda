$(document).ready(function() {
    $('[name="cod_perfil_profissional"]').click(function() {
        $.post(LINK_DEFAULT + 'recrutador/busca/ajaxbuscaareaprofissional/', {
            cod_perfil_profissional: $(this).val(),
            tipoBusca: 'C'
        }, function(data) {
            $('#cod_area_profissional').html(data);
            $('.campo-obrigatorio-prof').removeClass('form-invalido');
            $('span.span-aprof').addClass('d-none');
            $('.campo-obrigatorio-espec').removeClass('form-invalido');
            $('span.span-aespc').addClass('d-none');
            setTimeout(function() {
                $('#cod_area_profissional').trigger('change');
            }, 2000);
        });
    });
    $('[name="cod_area_profissional"]').change(function() {
        $.post(LINK_DEFAULT + 'recrutador/busca/ajaxbuscaareaespecializacao/', {
            cod_area_profissional: $(this).val(),
            cod_perfil_profissional: $('[name^="cod_perfil_profissional"]:checked').val(),
            tipoBusca: 'C'
        }, function(data) {
            $('#cod_area_especializacao').html(data);
        });
    });
    $('ul.bv-ordem-alfa').mouseover(function() {
        $('li.bv-ordem-alfa-check').removeClass('bv-ordem-alfa-check');
        var letra_atual = $('#letraSelecionada').val();
        $('#' + letra_atual).addClass('bv-ordem-alfa-check');
    });
    $('.link_letra').mouseout(function() {
        $('li.bv-ordem-alfa-check').removeClass('bv-ordem-alfa-check');
        $(this).addClass('bv-ordem-alfa-check');
    });
    $('.link_letra').click(function() {
        var alert = true;
        if ($('[name^="cod_area_profissional"]').val() == '') {
            $('.campo-obrigatorio-prof').addClass('form-invalido');
            $('span.span-aprof').removeClass('d-none');
            alert = false;
        } else {
            $('.campo-obrigatorio-prof').removeClass('form-invalido');
            $('span.span-aprof').addClass('d-none');
            alert = true;
        }
        if ($('[name^="cod_area_especializacao"]').val() == '') {
            $('.campo-obrigatorio-espec').addClass('form-invalido');
            $('span.span-aespc').removeClass('d-none');
            alert = false;
        } else {
            $('.campo-obrigatorio-espec').removeClass('form-invalido');
            $('span.span-aespc').addClass('d-none');
            alert = true;
        }
        if (!alert) {
            fancyAlert('<span class="div_alert">Existem erros de preenchimento na sua busca!<br />Gentileza conferir e corrigir todos os campos destacados em vermelho</span>', 'err', '');
            return false;
        }
    });
    $('[name^="cod_perfil_profissional"]').click(function() {
        var letras = new Array('a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z');
        var link = '';
        var i = 0;
        $('#cod_area_especializacao').html('<option value="">Selecione uma opção ...</option>');
        $('.bv-ordem-alfa-li a:first-child').each(function() {
            link = LINK_CONTROLLER + '/porcargo/lt/' + letras[i] + '/pf/' + $('[name^="cod_perfil_profissional"]:checked').val();
            $(this).attr('href', link);
            i++;
        });
    });
    $('[name^="cod_area_profissional"]').change(function() {
        var letras = new Array('a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z');
        var id = $(this).val();
        var link = '';
        var i = 0;
        $('.bv-ordem-alfa-li a:first-child').each(function() {
            link = LINK_CONTROLLER + '/porcargo/lt/' + letras[i] + '/pf/' + $('[name^="cod_perfil_profissional"]:checked').val() + '/ap/' + id;
            $(this).attr('href', link);
            i++;
        });
    });
    $('#cod_area_especializacao').change(function() {
        var letras = new Array('a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z');
        var i = 0;
        var link;
        $('.bv-ordem-alfa-li a:first-child').each(function() {
            link = LINK_CONTROLLER + '/porcargo/lt/' + letras[i] + '/pf/' + $('[name^="cod_perfil_profissional"]:checked').val() + '/ap/' + $('[name^="cod_area_profissional"]').val() + '/ae/' + $('#cod_area_especializacao').val();
            $(this).attr('href', link);
            i++;
        });
    });
    $('.fancyboxcall').fancybox({
        closeBtn: false,
        closeClick: false,
        modal: true,
        fitToView: true,
        openEffect: 'none',
        closeEffect: 'none',
        maxWidth: 872,
        maxHeight: 436,
        width: 872,
        height: 436,
        padding: 0,
        autoSize: false,
        type: 'iframe'
    });
});