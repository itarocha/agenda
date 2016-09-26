function confirmdelRecrutador(id) {
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
                + '<span class="f-left b-submit-maior c-branco bg-verde margin-auto b-ic-adicionar" onclick="deletaRecrutador(\'' + id + '\');">OK</span>'
                + '<span class="b-submit-maior-ic b-ic-deletar c-branco bg-laranja f-left w-margin-left20" onclick="closeFancyboxAndRedirectToUrl();">Não</span>'
                + '</div>'
                + '<a href="javascript:;" class="fancybox-item fancybox-close" title="Close" onClick="closeFancyboxAndRedirectToUrl();"></a>'
    });
}

function deletaRecrutador(id) {
    $.post(LINK_MODULE + "gerenciamentorecrutadores/ajaxdelrecrutador", {
        "id": id
    }, function(data) {
        if (data.msg == 'ok') {
            closeFancyboxAndRedirectToUrl(LINK_CONTROLLER + '/index');
        } else if (data.msg == 'vagas') {
            fancyAlert('Existem vagas ativas relacionadas a essa empresa. <br/>Gentileza excluir as vagas correspondentes para poder excluir esta emrpresa.', 'err', LINK_CONTROLLER + '/index');
        } else {
            fancyAlert('Existem erros de preenchimento no seu cadastro. <br/>Gentileza conferir e corrigir todos os campos destacados em vermelho', 'err', LINK_CONTROLLER + '/index');
        }
        
    }, "json");
}
