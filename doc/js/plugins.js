// Place any jQuery/helper plugins in here.
$(function() {
    $('input[type="text"]').setMask({
        attr: 'data-mask'
    });
    
    $('.telbr').on('keypress', function(e) {
        if ((e.which != '' && e.keyCode != 9) || (e.which != '' && e.which != 9)) {
            var target, phone, element;
            target = $(this).val();
            phone = target.replace(/\D/g, '');
            element = $(this);
            element.unsetMask();
            if (phone.length > 9) {
                element.setMask({mask:'(99)99999-9999', autoTab:false});
            } else {
                element.setMask({mask:'(99)9999-9999', autoTab:false});
            }
        }
    });
    
    $(".expand").on('click', function() {
        var e = $(this);
        var collapse = e.parents('.linha').find('.collapse');
        $(".expand").not(e).removeClass('selected');
        e.toggleClass('selected');
        // Fecha todos os outros itens abertos;
        $('.linha .collapse').not(collapse).slideUp();
        // Abre ou fecha o item clicado;
        collapse.slideToggle();
    });/**/
    /* preenche select */
    $('select[data-show]').on('change', function() {
        var e = $(this);
        var texto = e.find('option:selected').html();
        $('#' + e.attr('data-show')).html(texto);
    });
    /* recupera select selecionado */
    $('select[data-show]').each(function() {
        var dataShowValue = $(this).data('show');
        var elementValue = $(this).attr('id');
        if ($('#' + elementValue + ' option:selected').val()) {
            $('#' + dataShowValue).html($('#' + elementValue + ' option:selected').text());
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
        maxHeight: 492,
        width: 892,
        height: 492,
        padding: 0,
        autoSize: false,
        type: 'iframe'
    });
});

function fancyAlert(msg, tipo, redirect) {
    var classe;
    switch (tipo) {
        case 'ale' :
            classe = 'bg-amarelo';
            break;
        case 'err' :
            classe = 'bg-vermelho';
            break;
        case 'suc' :
            classe = 'bg-azul';
            break;
        default :
            classe = 'bg-azul';
            break;
    }
    if (!redirect) {
        redirect = '';
    }
    
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
        content: '<div class="w-100 ' + classe + ' fancy-header">'
                + '<div class="fancy-header-ic f-left"></div>'
                + '<div class="ff-opensans f-18 c-branco fancy-header-tt f-left">Atenção</div>'
                + '</div>'
                + '<div class="div_alert">'
                + '<p class="f-16 f-bold">' + msg + '</p>'
                + '</div>'
                + '<a href="javascript:;" class="fancybox-item fancybox-close" title="Close" onClick="closeFancyboxAndRedirectToUrl(\'' + redirect + '\');"></a>'
    });
}

function closeFancyboxAndRedirectToUrl(url) {
    if (url) {
        top.location.href = url;
    } else {
        parent.$.fancybox.close();
    }
}
