function confirmdelVaga(id) {
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
                + '<input type="submit" class="f-left b-submit-maior c-branco bg-verde margin-auto b-ic-adicionar" value="OK" onclick="deletaVaga(\'' + id + '\');" />'
                + '<input type="submit" class="b-submit-maior-ic b-ic-deletar c-branco bg-laranja f-left w-margin-left20" value="Não" onclick="closeFancyboxAndRedirectToUrl();" />'
                + '</div>'
                + '<a href="javascript:;" class="fancybox-item fancybox-close" title="Close" onClick="closeFancyboxAndRedirectToUrl();"></a>'
    });
}

function deletaVaga(id) {
    $.post(LINK_MODULE + "gerenciamentovagas/ajaxdelvaga", {
        "id": id
    }, function(data) {
        if (data.msg == 'ok') {
            closeFancyboxAndRedirectToUrl(LINK_CONTROLLER + '/index');
        } else {
            fancyAlert('Erro de execução do processo. <br/>Gentileza tentar o procedimento novamente, caso ocorra novamente o problema contate a Ser Humano informando o ocorrido, obrigado!', 'err', LINK_CONTROLLER + '/index');
        }
    }, "json");
}
