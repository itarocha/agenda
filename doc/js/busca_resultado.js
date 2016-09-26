$(document).ready(function() {
    $('[name="ordenacao"]').click(function() {
        $('form').submit();
    });
    $('.bt-add-to-grupo').click(function() {
        var arrCurr = new Array();
        var idSelecionados;
        $("input[type=checkbox]:checked").each(function() {
            var e = $(this);
            arrCurr[arrCurr.length] = e.val();
        });
        if (arrCurr.length == 0) {
            fancyAlert('Você precisa selecionar ao menos um registro!', 'err');
            return false;
        } else {
            $('.bt-add-to-grupo').fancybox({
                closeBtn: false,
                closeClick: false,
                modal: true,
                fitToView: true,
                openEffect: 'none',
                closeEffect: 'none',
                maxWidth: 892,
                maxHeight: 436,
                width: 892,
                height: 436,
                padding: 0,
                autoSize: false,
                type: 'iframe'
            });
            idSelecionados = arrCurr.join(',');
            $('.bt-add-to-grupo').attr('href', LINK_DEFAULT + 'recrutador/processoseletivo/adicionarparagrupocomprocesso/selecionados/' + idSelecionados);
        }
    });
    $('.envia_msg_all').click(function() {
        var i = 0
        var arrCurr = new Array();
        var idSelecionados;
        $('.rbv-li-btcontainer-bt-check').find('[type="checkbox"]').each(function() {
            var e = $(this);
            if (e.is(':checked')) {
                arrCurr[arrCurr.length] = e.val();
                i++;
            }
        });
        if (i == 0) {
            fancyAlert('Você precisa selecionar ao menos um registro!', 'err');
            return false;
        } else {
            $('.envia_msg_all').fancybox({
                closeBtn: false,
                closeClick: false,
                modal: true,
                fitToView: true,
                openEffect: 'none',
                closeEffect: 'none',
                maxWidth: 892,
                maxHeight: 436,
                width: 892,
                height: 436,
                padding: 0,
                autoSize: false,
                type: 'iframe'
            });
            idSelecionados = arrCurr.join(',');
            $('.envia_msg_all').attr('href', LINK_DEFAULT + 'recrutador/busca/enviamsg/selecionados/' + idSelecionados);
        }
    });
    $('.distribuir_curriculo_all').click(function() {
        var i = 0
        var arrCurr = new Array();
        var idSelecionados;
        $('.rbv-li-btcontainer-bt-check').find('[type="checkbox"]').each(function() {
            var e = $(this);
            if (e.is(':checked')) {
                arrCurr[arrCurr.length] = e.val();
                i++;
            }
        });
        if (i == 0) {
            fancyAlert('Você precisa selecionar ao menos um registro!', 'err');
            return false;
        } else {
            $('.distribuir_curriculo_all').fancybox({
                closeBtn: false,
                closeClick: false,
                modal: true,
                fitToView: true,
                openEffect: 'none',
                closeEffect: 'none',
                maxWidth: 892,
                maxHeight: 436,
                width: 892,
                height: 436,
                padding: 0,
                autoSize: false,
                type: 'iframe'
            });
            idSelecionados = arrCurr.join(',');
            $('.distribuir_curriculo_all').attr('href', LINK_DEFAULT + 'recrutador/opcao/distribuircurriculo/selecionados/' + idSelecionados);
        }
    });
    $('#exibircompleto').click(function() {
        if ($('.check-all-marc').length > 0) {
            $('.rbv-li-btcontainer-bt-check').find('[type="checkbox"]').each(function() {
                var e = $(this);
                e.prop('checked', true);
            });
            $(this).html('Desmarcar todos');
            $(this).addClass('check-all-desmarc');
            $(this).removeClass('check-all-marc');
        } else {
            $('.rbv-li-btcontainer-bt-check').find('[type="checkbox"]').each(function() {
                var e = $(this);
                e.prop('checked', false);
            });
            $(this).html('Marcar todos');
            $(this).addClass('check-all-marc');
            $(this).removeClass('check-all-desmarc');
        }
    });
    $('.fancyboxcall').fancybox({
        closeBtn: false,
        closeClick: false,
        modal: true,
        fitToView: true,
        openEffect: 'none',
        closeEffect: 'none',
        maxWidth: 892,
        maxHeight: 480,
        width: 892,
        height: 480,
        padding: 0,
        autoSize: false,
        type: 'iframe'
    });
});