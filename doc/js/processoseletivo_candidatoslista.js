$(document).ready(function() {
    
    $('#btEnviar').click(function(){
        var par = '';
        var id = $('#id').val();
        var percepcao = $('#ind_percepcao').val();
        var ordenacao = $('#ordenacao').val();
        var situacao = $('#ind_situacao_candidato').val();
        
        if (id){
            par = par + '/id/'+id;
        }
        if (percepcao){
            par = par + '/percepcao/'+percepcao;
        }
        if (ordenacao){
            par = par + '/ordenacao/'+ordenacao;
        }
        if (situacao){
            par = par + '/situacao/'+situacao;
        }
       $('#formfiltro').attr('action',LINK_ACTION+par);
       $('#formfiltro').submit();
    });
    
    $('#marcadesmarcatodos').click(function() {
        if ($('.check-all-marc').length > 0) {
            $('input[type=checkbox].rbv-li-btcontainer-check').each(function() {
                var e = $(this);
                e.prop('checked', true);
            });
            $('label[for=marcadesmarcatodos]').text(' Desmarcar todos');
            //$(this).html('Desmarcar todos');
            $(this).addClass('check-all-desmarc');
            $(this).removeClass('check-all-marc');
        } else {
            $('input[type=checkbox].rbv-li-btcontainer-check').each(function() {
                var e = $(this);
                e.prop('checked', false);
            });
            $('label[for=marcadesmarcatodos]').text(' Marcar todos');
            //$(this).html('Marcar todos');
            $(this).addClass('check-all-marc');
            $(this).removeClass('check-all-desmarc');
        }
    });
    
    // Distribuir por e-mail
    $('#bt-distribuir-email').click(function() {
        var arrCurr = new Array();
        var idSelecionados;

        $("input[type=checkbox].rbv-li-btcontainer-check:checked").each(function(){
            var e = $(this);
            arrCurr[arrCurr.length] = e.val();
        });
       
        if (arrCurr.length == 0) {
            fancyAlert('Você precisa selecionar no mínimo um candidato!', 'err');
            return false;
        } else {
            $('#bt-distribuir-email').fancybox({
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
            $('#bt-distribuir-email').attr('href', LINK_DEFAULT + 'recrutador/opcao/distribuircurriculo/selecionados/' + idSelecionados);
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