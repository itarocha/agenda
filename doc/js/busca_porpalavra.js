$(document).ready(function() {

    $(".js-multselect-filter").multiselect().multiselectfilter(); // Com filtro
    $(".js-multselect-header").multiselect({header: false}); // Sem cabeçalho
    $(".js-multselect").multiselect(); // Puro


    $('.btnAddIdioma').click(function() {
        var htmlMatrizIdioma = $(this).parents('.matriz').clone();
        htmlMatrizIdioma.removeClass('matriz').addClass('ul-list');

        var findMatriz = htmlMatrizIdioma.find('.btnAddIdioma');
        findMatriz.removeClass('b-ic-adicionar');
        findMatriz.removeClass('bg-amarelo');
        findMatriz.addClass('b-ic-deletar');
        findMatriz.addClass('bg-cinza');
        findMatriz.addClass('btnRmIdioma');
        findMatriz.html('Remover');
        findMatriz.removeClass('btnAddIdioma');

        $(this).parents('.matriz').after(htmlMatrizIdioma);
    });

    $(document).on('click', '.btnRmIdioma', function() {
        $(this).parents('.ul-list').remove();
    });
    
    
    $('.btnAddInfo').click(function() {
        var htmlMatrizIdioma = $(this).parents('.matriz').clone();
        htmlMatrizIdioma.removeClass('matriz').addClass('ul-list');

        var findMatriz = htmlMatrizIdioma.find('.btnAddInfo');
        findMatriz.removeClass('b-ic-adicionar');
        findMatriz.removeClass('bg-amarelo');
        findMatriz.addClass('b-ic-deletar');
        findMatriz.addClass('bg-cinza');
        findMatriz.addClass('btnRmInfo');
        findMatriz.html('Remover');
        findMatriz.removeClass('btnAddInfo');

        $(this).parents('.matriz').after(htmlMatrizIdioma);
    });

    $(document).on('click', '.btnRmInfo', function() {
        $(this).parents('.ul-list').remove();
    });


    $('[name="cod_perfil_profissional"]').click(function() {
        var arrAreasProfSelecionadas = new Array();
        arrAreasProfSelecionadas = $('#cod_area_profissional_selecionadas').val().split(',');
        $.post(LINK_DEFAULT + 'recrutador/busca/ajaxbuscaareaprofissional/', {
            cod_perfil_profissional: $(this).val(),
            cod_area_profissional_selecionadas: arrAreasProfSelecionadas,
            tipoBusca: 'P'
        }, function(data) {
            $('#bloco_area_profissional').html(data);
            $('#bloco_area_especializacao').html('<select name="cod_area_especializacao[]" id="cod_area_especializacao"  title="Área profissional desejada" multiple="multiple" size="5" class="js-multselect-header"></select>');
            $(".js-multselect-header").multiselect({header: false}); // Puro
            selecionaAreas();
        });
    });

    $('[name="cod_perfil_profissional"]').click(function() {
        $.post(LINK_DEFAULT + 'recrutador/busca/ajaxbuscanivelhierarquico/', {
            cod_perfil_profissional: $(this).val(),
        }, function(data) {
            $('#bloco_nivel_hierarquico').html(data);
            $(".js-multselect-header").multiselect({header: false}); // Puro
        });
    });
    $('#cod_estado').change(function() {
        $('#cod_regiao').html('<option>Aguarde...</option>');
        $.post(LINK_DEFAULT + 'recrutador/busca/ajaxbuscaregiao/', {
            cod_estado: $('#cod_estado').val(),
            cidades_selecionadas: $('#cidades_selecionadas').val(),
            regioes_selecionadas: $('#regioes_selecionadas').val()
        }, function(data) {
            $('#multi_select_cod_regiao').html(data);
            $("#multi_select_cod_cidade").html('<select name="cod_cidade[]" id="cod_cidade" title="Cidades Trabalhar" multiple="multiple" size="5" class="js-multselect"></select>'); // Puro
            $(".js-multselect-filter").multiselect().multiselectfilter(); // Com filtro
            $(".js-multselect-header").multiselect({
                header: false
            }); // Sem cabeçalho
            $(".js-multselect").multiselect(); // Puro
            clickselectRegioes();
            clickselectCidades();
            setTimeout('atualizaSeleciondas()', 800);
        });
    });
    $('.recimputs').click(function() {
        $('#cod_perfil_profissional_1').attr('checked', true);
    });
    $('#cod_perfil_profissional_1').trigger('click');
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
    if ($("#tit_vaga").size()) {
        $("#tit_vaga").autocomplete({
            minLength: 3,
            delay: 0,
            //define callback to format results  
            source: function(req, add) {
                var idChecked;
                $('[name="cod_perfil_profissional"]').each(function() {
                    if ($(this).is(':checked')) {
                        idChecked = $(this).val();
                    }
                });
                
                var $this = $(this);
                var $element = $(this.element);
                var jqXHR = $element.data('jqXHR');
                if (jqXHR){
                    jqXHR.abort();
                }
                
                //pass request to server
                var baseUrl = LINK_MODULE + "busca/ajaxbuscacargo/perfil/" + idChecked;
                
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
                $(this).val(ui.item.value);
                return false;
            }
        });
    }
    clickselectCidades();
    clickselectRegioes();
});

function selecionaAreas() {
    $('[name="multiselect_cod_area_profissional"]').click(function() {
        var i = 0;
        var arrAreasEspecSelecionadas = new Array();
        arrAreasEspecSelecionadas = $('#cod_area_especializacao_selecionadas').val().split(',');
        var arrAreas = new Array();
        arrAreas = $('#cod_area_profissional_selecionadas').val().split(',');
        if ($(this).is(':checked')) {
            arrAreas[arrAreas.length] = $(this).val();
            arrAreas = arrAreas.filter(function(n) {
                return n
            });
            $('#cod_area_profissional_selecionadas').val(arrAreas.join(','));
        } else {
            for (cc in arrAreas) {
                var area = arrAreas[cc];
                if (area == $(this).val() && $(this).val() != '') {
                    arrAreas.splice($.inArray(area, arrAreas), 1);
                }
            }
            $('#cod_area_profissional_selecionadas').val(arrAreas.join(','));
        }
        $.post(LINK_DEFAULT + 'recrutador/busca/ajaxbuscaareaespecializacao/', {
            cod_area_profissional: arrAreas,
            cod_perfil_profissional: $('[name^="cod_perfil_profissional"]').val(),
            cod_area_especializacao_selecionadas: arrAreasEspecSelecionadas,
            tipoBusca: 'P'
        }, function(data) {
            $('#bloco_area_especializacao').html(data);
            $(".js-multselect-header").multiselect({header: false}); // Puro
            selecionaEspecializacao();
        }, "json");
    });
}

function selecionaEspecializacao() {
    $('[name="multiselect_cod_area_especializacao"]').click(function() {
        var arrAreas = new Array();
        arrAreas = $('#cod_area_especializacao_selecionadas').val().split(',');
        if ($(this).is(':checked')) {
            arrAreas[arrAreas.length] = $(this).val();
            arrAreas = arrAreas.filter(function(n) {
                return n
            });
            $('#cod_area_especializacao_selecionadas').val(arrAreas.join(','));
        } else {
            for (cc in arrAreas) {
                var area = arrAreas[cc];
                if (area == $(this).val() && $(this).val() != '') {
                    arrAreas.splice($.inArray(area, arrAreas), 1);
                }
            }
            $('#cod_area_especializacao_selecionadas').val(arrAreas.join(','));
        }
    });
}

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
        $.post(LINK_DEFAULT + 'recrutador/busca/ajaxbuscacidade/', {
            cod_regiao: $('#cod_regiao').val(),
            cidades_selecionadas: $('#cidades_selecionadas').val()
        }, function(data) {
            $('#multi_select_cod_cidade').html(data);
            $(".js-multselect-filter").multiselect().multiselectfilter(); // Com filtro
            $(".js-multselect-header").multiselect({
                header: false
            }); // Sem cabeçalho
            $(".js-multselect").multiselect(); // Puro
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
        $.post(LINK_DEFAULT + 'recrutador/busca/ajaxbuscacidade/', {
            cod_regiao: $('#cod_regiao').val(),
            cidades_selecionadas: $('#cidades_selecionadas').val()
        }, function(data) {
            $('#multi_select_cod_cidade').html(data);
            $(".js-multselect-filter").multiselect().multiselectfilter(); // Com filtro
            $(".js-multselect-header").multiselect({
                header: false
            }); // Sem cabeçalho
            $(".js-multselect").multiselect(); // Puro
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