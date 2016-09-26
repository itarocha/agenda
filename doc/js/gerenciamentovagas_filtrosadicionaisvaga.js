$(document).ready(function() {

    $(".js-multselect").multiselect();

    $('.c-check').change(function() {
        var e = $(this);
        switch (e.val()) {
            case 'S':
                e.parents('li').find('.toggle').fadeIn();
                break;
            default:
                e.parents('li').find('.toggle').fadeOut();
                break;
        }
    });

    $('#ind_filtra_curriculo_localidade_n').click(function() {
        $('.bloco-sel-cidades').removeClass('d-block');
    });

    $('#opcao_filtra_por_idade').click(function() {
        if ($(this).is(':checked')) {
            $('.bloco-idade').addClass('d-block');
            $('.bloco-idade').fadeIn();
        } else {
            $('.bloco-idade').removeClass('d-block');
            $('.bloco-idade').fadeOut();
        }
    });

    $('#opcao_filtra_por_sexo').click(function() {
        if ($(this).is(':checked')) {
            $('.bloco-sexo').addClass('d-block');
            $('.bloco-sexo').fadeIn();
        } else {
            $('.bloco-sexo').removeClass('d-block');
            $('.bloco-sexo').fadeOut();
        }
    });

    $('.toggle').fadeOut('fast');

    $('#cod_estado').change(function() {
        $('#cod_regiao').html('<option>Aguarde...</option>');
        $.post(LINK_DEFAULT + 'recrutador/gerenciamentovagas/ajaxbuscaregiao/', {
            cod_estado: $('#cod_estado').val(),
            cidades_selecionadas: $('#cidades_selecionadas').val(),
            regioes_selecionadas: $('#regioes_selecionadas').val()
        }, function(data) {
            $('#multi_select_cod_regiao').html(data);
            $("#multi_select_cod_cidade").html('<select name="cod_cidade[]" id="cod_cidade" title="Cidades" multiple="multiple" size="5" class="js-multselect"></select>'); // Puro
            $(".js-multselect").multiselect();
            clickselectRegioes();
            clickselectCidades();
            setTimeout('atualizaSeleciondas()', 800);
        });
    });

    clickselectCidades();

    clickselectRegioes();

    if($('#opcao_filtra_por_idade').is(':checked')){
        $(".bloco-idade").show();
    }

    $('#opcao_filtra_por_idade').click(function() {
        if ($(this).is(':checked')) {
            $('#ind_filtra_idade_i').focus();
            $('#ind_filtra_idade_entre_menor').attr('disabled', true);
            $('#ind_filtra_idade_entre_maior').attr('disabled', true);
            $('#ind_filtra_idade_s').attr('disabled', true);
        }
    });

    $('#ind_filtra_idade_opcao_i').click(function() {
        $('#ind_filtra_idade_i').attr('disabled', false);
        ;
        $('#ind_filtra_idade_i').focus();
        $('#ind_filtra_idade_entre_menor').val('');
        $('#ind_filtra_idade_entre_menor').attr('disabled', true);
        $('#ind_filtra_idade_entre_maior').val('');
        $('#ind_filtra_idade_entre_maior').attr('disabled', true);
        $('#ind_filtra_idade_s').val('');
        $('#ind_filtra_idade_s').attr('disabled', true);
    });

    $('#ind_filtra_idade_opcao_e').click(function() {
        $('#ind_filtra_idade_entre_menor').attr('disabled', false);
        $('#ind_filtra_idade_entre_maior').attr('disabled', false);
        $('#ind_filtra_idade_entre_menor').focus();
        $('#ind_filtra_idade_i').val('');
        $('#ind_filtra_idade_i').attr('disabled', true);
        $('#ind_filtra_idade_s').val('');
        $('#ind_filtra_idade_s').attr('disabled', true);
    });

    $('#ind_filtra_idade_opcao_s').click(function() {
        $('#ind_filtra_idade_s').attr('disabled', false);
        ;
        $('#ind_filtra_idade_s').focus();
        $('#ind_filtra_idade_entre_menor').val('');
        $('#ind_filtra_idade_entre_menor').attr('disabled', true);
        $('#ind_filtra_idade_entre_maior').val('');
        $('#ind_filtra_idade_entre_maior').attr('disabled', true);
        $('#ind_filtra_idade_i').val('');
        $('#ind_filtra_idade_i').attr('disabled', true);
    });

});

function clickselectRegioes() {
    /* BOX SELECIONAR REGIOES */
    $('[name^="multiselect_cod_regiao"]').click(function() {
        var arrRegioes = $('#regioes_selecionadas').val().split(',');
        if ($(this).is(':checked')) {
            arrRegioes[arrRegioes.length] = $(this).val();
            $('#regioes_selecionadas').val(arrRegioes.join(','));
        } else {
            for (cc in arrRegioes) {
                var city = arrRegioes[cc];
                if (city == $(this).val()) {
                    arrRegioes.splice($.inArray(city, arrRegioes), 1);

                }
            }
            $('#regioes_selecionadas').val(arrRegioes.join(','));
        }
    });
    var arrRegioes = $('#regioes_selecionadas').val().split(',');
    var newArray = new Array();
    var i = 1;
    $('[name="multiselect_cod_regiao"]').each(function() {
        if ($(this).attr('aria-selected') == 'true') {
            if (($.inArray($(this).val(), arrRegioes) == -1) && ($(this).val() != '')) {
                newArray[i] = $(this).val();
            }
        }
        i++;
    });
    arrRegioes = arrRegioes.concat(newArray);
    arrRegioes = $.grep(arrRegioes, function(n) {
        return(n)
    });
    $('#regioes_selecionadas').val(arrRegioes.join(','));
    $('.ui-multiselect-none').click(function() {
        var arrRegioes = $('#regioes_selecionadas').val().split(',');
        $('[name="multiselect_cod_regiao"]').each(function() {
            if ($(this).not(':checked')) {
                if ($(this).val() != '') {
                    arrRegioes.splice(arrRegioes.indexOf($(this).val()), 1);
                }
            }
        });
        $('#regioes_selecionadas').val(arrRegioes.join(','));
    });
    selectCidades();
}
 
function clickselectCidades() {
    $('#cod_regiao').change(function() {
        $('#cod_cidade').html('<option>Aguarde...</option>');
        $.post(LINK_DEFAULT + 'recrutador/gerenciamentovagas/ajaxbuscacidade/', {
            cod_regiao: $('#cod_regiao').val(),
            cidades_selecionadas: $('#cidades_selecionadas').val()
        }, function(data) {
            $('#multi_select_cod_cidade').html(data);
            $(".js-multselect").multiselect(); // Sem cabeçalho
            fullselectCidades();
            fullnoneselectCidades();
            oneselectCidades();
            return false;
        });
    });
}

function selectCidades() {
    if ($('#cidades_selecionadas').val() != '') {
        $('#cod_cidade').html('<option>Aguarde...</option>');
        $.post(LINK_DEFAULT + 'recrutador/gerenciamentovagas/ajaxbuscacidade/', {
            cod_regiao: $('#cod_regiao').val(),
            cidades_selecionadas: $('#cidades_selecionadas').val()
        }, function(data) {
            $('#multi_select_cod_cidade').html(data);
            $(".js-multselect").multiselect();
            fullselectCidades();
            fullnoneselectCidades();
            oneselectCidades();
            return false;
        });
    }
}

function oneselectCidades() {
    $('[name^="multiselect_cod_cidade"]').click(function() {
        var arrCidades = $('#cidades_selecionadas').val().split(',');
        if ($(this).is(':checked')) {
            arrCidades[arrCidades.length] = $(this).val();
            $('#cidades_selecionadas').val(arrCidades.join(','));
        } else {
            for (cc in arrCidades) {
                var city = arrCidades[cc];
                if (city == $(this).val()) {
                    arrCidades.splice($.inArray(city, arrCidades), 1);

                }
            }
            $('#cidades_selecionadas').val(arrCidades.join(','));
        }
    });
}

function fullselectCidades() {
    $('.ui-multiselect-all').click(function() {
        setTimeout('clickfullselectCidades()', 800);
    });
}

function clickfullselectCidades() {
    var arrCidades = $('#cidades_selecionadas').val().split(',');
    var newArray = new Array();
    var i = 1;
    $('[name="multiselect_cod_cidade"]').each(function() {
        if ($(this).attr('aria-selected') == 'true') {
            if (($.inArray($(this).val(), arrCidades) == -1) && ($(this).val() != '')) {
                newArray[i] = $(this).val();
            }
        }
        i++;
    });
    arrCidades = arrCidades.concat(newArray);
    arrCidades = $.grep(arrCidades, function(n) {
        return(n)
    });
    $('#cidades_selecionadas').val(arrCidades.join(','));
}

function fullnoneselectCidades() {
    $('.ui-multiselect-none').click(function() {
        var arrCidades = $('#cidades_selecionadas').val().split(',');
        $('[name="multiselect_cod_cidade"]').each(function() {
            if ($(this).not(':checked')) {
                if ($(this).val() != '') {
                    arrCidades.splice(arrCidades.indexOf($(this).val()), 1);
                }
            }
        });
        $('#cidades_selecionadas').val(arrCidades.join(','));
    });
}

function atualizaSeleciondas() {
    var arrCidades = new Array();
    var i = 0;
    $('[name="multiselect_cod_cidade"]').each(function() {
        if ($(this).is(':checked')) {
            arrCidades[i] = $(this).val();
        }
        i++;
    });
    $('#cidades_selecionadas').val(arrCidades.join(','));
    var arrRegioes = new Array();
    var j = 0;
    $('[name="multiselect_cod_regiao"]').each(function() {
        if ($(this).is(':checked')) {
            arrRegioes[j] = $(this).val();
        }
        j++;
    });
    $('#regioes_selecionadas').val(arrRegioes.join(','));
}
