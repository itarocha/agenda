$(document).ready(function() {
    $(".js-multselect").multiselect({
        header: false
    });
    if ($("#nom_cargo").length > 0) {
        $("#nom_cargo").autocomplete({
            minLength: 3,
            delay: 0,
            //define callback to format results  
            source: function(req, add) {
                var $this = $(this);
                var $element = $(this.element);
                var jqXHR = $element.data('jqXHR');
                if (jqXHR) {
                    jqXHR.abort();
                }
                //pass request to server
                var baseUrl = LINK_DEFAULT + "recrutador/gerenciamentovagas/ajaxbuscacargo/perfil/" + $('[name="cod_perfil_profissional"]').val()
                $element.data('jqXHR', $.getJSON(baseUrl, req, function(data) {
                    $this.removeData('jqXHR');
                    //create array for response objects
                    var suggestions = [];
                    //process response
                    $.each(data, function(i, val) {
                        var entry = new Object();
                        entry.index = val.value;
                        entry.value = val.label;
                        suggestions.push(entry);
                    });
                    //pass array to callback
                    add(suggestions);
                }));
            },
            //define select handler  
            select: function(e, ui) {
                $("#nom_cargo").val(ui.item.value);
                $("#nom_cargo").attr('readonly', true);
                $('#nom_cargo').addClass('bg-cinzaclaro');
                $('#cod_cargo_vaga').val(ui.item.index);
                $('.bt-autocomplete').show();
            }
        });
    }
    $('.bt-autocomplete').click(function() {
        var e = $(this);
        e.parents('li').find('input').attr('readonly', false).val('').removeClass('bg-cinzaclaro');
        $(this).hide();
    });
    $('#ind_tipo_salario').change(function() {
        if ($(this).val() == 'V') {
            $('.div-valor-salario').removeClass('d-none');
            $('.div-faixa-salario').addClass('d-none');
        } else {
            $('.div-valor-salario').addClass('d-none');
            $('.div-faixa-salario').removeClass('d-none');
        }
    });
    $('#nom_cargo').parents('li').find('span.ui-helper-hidden-accessible').each(function() {
        var e = $(this).html();
        $(this).remove();
        $(this).html(e);
    });
    $('.ui-corner-all [name^="multiselect_cod_area_profissional"]').click(function() {
        var element = $(this);
        var i = 0;
        $('.ui-corner-all [name^="multiselect_cod_area_profissional"]').each(function() {
            if ($(this).is(':checked')) {
                i++;
            }
        });
        if (i > 5) {
            element.attr('checked', false);
            $('.ui-corner-all [name^="multiselect_cod_area_profissional"]').not(':checked').attr('disabled', true);
            fancyAlert('Selecione no máximo 5 áreas. Caso queira selecionar outra opção, desmarque uma das já selecionados.', 'err', '');
            return false;
        } else {
            $('.ui-corner-all [name^="multiselect_cod_area_profissional"]').not(':checked').attr('disabled', false);
        }
    });
    $('.ui-corner-all [name^="multiselect_cod_nivel_hierarquico"]').click(function() {
        var element = $(this);
        var i = 0;
        $('.ui-corner-all [name^="multiselect_cod_nivel_hierarquico"]').each(function() {
            if ($(this).is(':checked')) {
                i++;
            }
        });
        if (i > 3) {
            element.attr('checked', false);
            $('.ui-corner-all [name^="multiselect_cod_nivel_hierarquico"]').not(':checked').attr('disabled', true);
            fancyAlert('Você pode selecionar no máximo 3 Níveis. Para cada novo nível que quiser selecionar, deve primeiro desmarcar um dos já selecionados.', 'err', '');
            return false;
        } else {
            $('.ui-corner-all [name^="multiselect_cod_nivel_hierarquico"]').not(':checked').attr('disabled', false);
        }
    });
});