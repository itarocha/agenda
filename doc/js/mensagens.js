$(document).ready(function() {
    $("#minhaconta-aba1").click(function() {
        $("#minhaconta-pedidospagos, #minhaconta-pedidosaguardo, #minhaconta-comprasefetuadas").hide()
        $("#minhaconta-aba2, #minhaconta-aba3, #minhaconta-aba4").find('.aba-ic').css('background-color', '#9F9F9F');
        $("#minhaconta-aba2, #minhaconta-aba3, #minhaconta-aba4").removeClass('aba-check');
        $(this).addClass('aba-check');
        $(this).find('.aba-ic').css('background-color', '#005FB0');
        $("#minhaconta-planoasinado").show();
    });
    $("#minhaconta-aba2").click(function() {
        $("#minhaconta-pedidospagos, #minhaconta-pedidosaguardo, #minhaconta-planoasinado").hide()
        $("#minhaconta-aba1, #minhaconta-aba3, #minhaconta-aba4").find('.aba-ic').css('background-color', '#9F9F9F');
        $("#minhaconta-aba1, #minhaconta-aba3, #minhaconta-aba4").removeClass('aba-check');
        $(this).addClass('aba-check');
        $(this).find('.aba-ic').css('background-color', '#005FB0');
        $("#minhaconta-comprasefetuadas").show();
    });
    $("#minhaconta-aba3").click(function() {
        $("#minhaconta-comprasefetuadas, #minhaconta-pedidosaguardo, #minhaconta-planoasinado").hide()
        $("#minhaconta-aba1, #minhaconta-aba2, #minhaconta-aba4").find('.aba-ic').css('background-color', '#9F9F9F');
        $("#minhaconta-aba1, #minhaconta-aba2, #minhaconta-aba4").removeClass('aba-check');
        $(this).addClass('aba-check');
        $(this).find('.aba-ic').css('background-color', '#005FB0');
        $("#minhaconta-pedidospagos").show();
    });
    $("#minhaconta-aba4").click(function() {
        $("#minhaconta-pedidospagos, #minhaconta-comprasefetuadas, #minhaconta-planoasinado").hide()
        $("#minhaconta-aba1, #minhaconta-aba2, #minhaconta-aba3").find('.aba-ic').css('background-color', '#9F9F9F');
        $("#minhaconta-aba1, #minhaconta-aba2, #minhaconta-aba3").removeClass('aba-check');
        $(this).addClass('aba-check');
        $(this).find('.aba-ic').css('background-color', '#005FB0');
        $("#minhaconta-pedidosaguardo").show();
    });
    $('.boxmsg').fancybox({
        closeBtn: false,
        closeClick: false,
        modal: true,
        fitToView: true,
        openEffect: 'none',
        closeEffect: 'none',
        maxWidth: 650,
        maxHeight: 490,
        width: 640,
        height: 490,
        padding: 10,
        autoSize: false,
        type: 'iframe'
    });
    $('.boxmsg').click(function() {
        var id = $(this).parents('tr').attr('id');
        var myArray = id.split('_');
        var mySt = myArray[0];
        var myId = myArray[1];
        $.post(LINK_DEFAULT + "recrutador/mensagens/ajaxmarcamsglida", {
            "cod_mensagem": myId
        }, function(data) {
            if (data == 'ok') {
                $('#' + mySt + '_' + myId).find('.msg-ver-bold').removeClass('msg-ver-bold');
            }
        }, "json").error(function() {
        });
    });
    $('.bt-favoritos').click(function() {
        var id = $(this).parents('tr').attr('id');
        var myArray = id.split('_');
        var mySt = myArray[0];
        var myId = myArray[1];
        $.post(LINK_DEFAULT + "recrutador/mensagens/ajaxmarcamsgfav", {
            "cod_mensagem": myId
        }, function(data) {
            if (data.fav == 'sim') {
                if (data.tipo == 'empresa') {
                    $('#empresas_' + myId).find('.caixa-ic-star2').addClass('caixa-ic-star3');
                    $('#empresas_' + myId).find('.caixa-ic-star3').removeClass('caixa-ic-star2');
                } else {
                    $('#serhumano_' + myId).find('.caixa-ic-star2').addClass('caixa-ic-star3');
                    $('#serhumano_' + myId).find('.caixa-ic-star3').removeClass('caixa-ic-star2');
                }
                $('#todas_' + myId).find('.caixa-ic-star2').addClass('caixa-ic-star3');
                $('#todas_' + myId).find('.caixa-ic-star3').removeClass('caixa-ic-star2');
            }
            if (data.fav == 'nao') {
                if (data.tipo == 'empresa') {
                    $('#empresas_' + myId).find('.caixa-ic-star3').addClass('caixa-ic-star2');
                    $('#empresas_' + myId).find('.caixa-ic-star2').removeClass('caixa-ic-star3');
                } else {
                    $('#serhumano_' + myId).find('.caixa-ic-star3').addClass('caixa-ic-star2');
                    $('#serhumano_' + myId).find('.caixa-ic-star2').removeClass('caixa-ic-star3');
                }
                $('#todas_' + myId).find('.caixa-ic-star3').addClass('caixa-ic-star2');
                $('#todas_' + myId).find('.caixa-ic-star2').removeClass('caixa-ic-star3');
            }
        }, "json").error(function() {
        });
    });
    $('.b-ic-check').click(function() {
        var id = $(this).attr('class');
        var myArray = id.split(' ');
        var nomeClass = myArray[6];
        var classNome = nomeClass.split('-');
        if ($(this).html() == 'Desmarcar todas') {
            $('input[type=checkbox]').each(function() {
                this.checked = false;
            });
            $('.b-ic-check').html('Selecionar todas');
        } else {
            $('.check-' + classNome[2]).each(function() {
                this.checked = true;
            });
            $('.b-ic-check').html('Desmarcar todas');
        }
    });
    $('.bt-selecionar-apagar').click(function() {
        var id = $('.aba-check').attr('id');
        var aba;
        switch (true) {
            case (id == 'minhaconta-aba1') : aba = 'todas'; break;
            case (id == 'minhaconta-aba2') : aba = 'empresas'; break;
            case (id == 'minhaconta-aba3') : aba = 'shumano'; break;
            case (id == 'minhaconta-aba4') : aba = 'lixeira'; break;
        }
        var check = '';
        $('input[type=checkbox].cod_mensagem_check').each(function(i, val) {
            if (this.checked == true) {
                check += $(this).val() + ',';
            }
        });
        if (check != '') {
            check = check.substr(0, check.length - 1);
            jQuery.fancybox({
                closeBtn: false,
                closeClick: false,
                modal: true,
                fitToView: true,
                width: '526px',
                height: '152px',
                padding: '0px',
                border: '0px',
                autoSize: false,
                openEffect: 'none',
                closeEffect: 'none',
                scrolling: false,
                content: '<div class="w-100 bg-amarelo fancy-header">'
                        + '<div class="fancy-header-ic f-left"></div>'
                        + '<div class="ff-opensans f-18 c-branco fancy-header-tt f-left">Atenção</div>'
                        + '</div>'
                        + '<div class="fancy-main">'
                        + '<p class="f-16 f-bold">Realmente deseja excluir este registro?</p>'
                        + '<div class="bts_deletar_modal">'
                        + '<input type="submit" class="f-left b-submit-maior c-branco bg-verde margin-auto b-ic-adicionar" value="OK" onclick="deletamsgs(\'' + check + '\', \'' + aba + '\');" />'
                        + '<input type="submit" class="b-submit-maior-ic b-ic-deletar c-branco bg-laranja f-left w-margin-left20" value="Não" onclick="closeFancyboxAndRedirectToUrl();" />'
                        + '</div>'
                        + '<a href="javascript:;" class="fancybox-item fancybox-close" title="Close" onClick="closeFancyboxAndRedirectToUrl();"></a>'
            });
        } else {
            return false;
        }
    });
});

function submitForm() {
    jQuery.ajax({
        cache: false,
        type: "POST",
        url: "process.asp", // your controller
        data: jQuery("#formMsg").serialize(), // serialize form with id="myForm"
        success: function(data) {
        }
    });
}

function confirmdelMsg(id, aba) {
    jQuery.fancybox({
        closeBtn: false,
        closeClick: false,
        modal: true,
        fitToView: true,
        width: '526px',
        height: '152px',
        padding: '0px',
        border: '0px',
        autoSize: false,
        openEffect: 'none',
        closeEffect: 'none',
        scrolling: false,
        content: '<div class="w-100 bg-amarelo fancy-header">'
                + '<div class="fancy-header-ic f-left"></div>'
                + '<div class="ff-opensans f-18 c-branco fancy-header-tt f-left">Atenção</div>'
                + '</div>'
                + '<div class="fancy-main">'
                + '<p class="f-16 f-bold">Realmente deseja excluir este registro?</p>'
                + '<div class="bts_deletar_modal">'
                + '<input type="submit" class="f-left b-submit-maior c-branco bg-verde margin-auto b-ic-adicionar" value="OK" onclick="deletamsg(\'' + id + '\', \'' + aba + '\');" />'
                + '<input type="submit" class="b-submit-maior-ic b-ic-deletar c-branco bg-laranja f-left w-margin-left20" value="Não" onclick="closeFancyboxAndRedirectToUrl();" />'
                + '</div>'
                + '<a href="javascript:;" class="fancybox-item fancybox-close" title="Close" onClick="closeFancyboxAndRedirectToUrl();"></a>'
    });
}

function deletamsg(id, aba) {
    $.post(LINK_DEFAULT + "recrutador/mensagens/ajaxdelmg", {
        "cod_mensagem": id
    }, function(data) {
        if (data.msg == 'ok') {
            window.location = LINK_DEFAULT + 'recrutador/mensagens/index/aba/' + aba;
        }
    }, "json");
    $.fancybox.close();
}

function deletamsgs(id, aba) {
    $.post(LINK_DEFAULT + "recrutador/mensagens/ajaxdelmsgs", {
        "codigos": id
    }, function(data) {
        if (data.msg == 'ok') {
            window.location = LINK_DEFAULT + 'recrutador/mensagens/index/aba/' + aba;
        }
    }, "json");
    $.fancybox.close();
}

function confirmrestMsg(id) {
    jQuery.fancybox({
        closeBtn: false,
        closeClick: false,
        modal: true,
        fitToView: true,
        width: '526px',
        height: '152px',
        padding: '0px',
        border: '0px',
        autoSize: false,
        openEffect: 'none',
        closeEffect: 'none',
        scrolling: false,
        content: '<div class="w-100 bg-amarelo fancy-header">'
                + '<div class="fancy-header-ic f-left"></div>'
                + '<div class="ff-opensans f-18 c-branco fancy-header-tt f-left">Atenção</div>'
                + '</div>'
                + '<div class="fancy-main">'
                + '<p class="f-16 f-bold">Realmente deseja restaurar este registro?</p>'
                + '<div class="bts_deletar_modal">'
                + '<input type="submit" class="f-left b-submit-maior c-branco bg-verde margin-auto b-ic-adicionar" value="OK" onclick="restaurarmsg(\'' + id + '\');" />'
                + '<input type="submit" class="b-submit-maior-ic b-ic-deletar c-branco bg-laranja f-left w-margin-left20" value="Não" onclick="closeFancyboxAndRedirectToUrl();" />'
                + '</div>'
                + '<a href="javascript:;" class="fancybox-item fancybox-close" title="Close" onClick="closeFancyboxAndRedirectToUrl();"></a>'
    });
}

function restaurarmsg(id) {
    $.post(LINK_DEFAULT + "recrutador/mensagens/ajaxrestmgs", {
        "cod_mensagem": id
    }, function(data) {
        if (data.msg == 'ok') {
            window.location = LINK_DEFAULT + 'recrutador/mensagens/index/aba/lixeira';
        }
    }, "json");
    $.fancybox.close();
}