$(document).ready(function() {
    
    $('#btEnviar').click(function(){
       
      var url = buildurl(false); 
        
       $('#formfiltro').attr('action',url);
       $('#formfiltro').submit();
    });
    
    $('#bt_imprimir').click(function() {
        
        // Montar url!!!!!!!!!!
        
        $('#bt_imprimir').fancybox({
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
        var url = buildurl(true);
        $('#bt_imprimir').attr('href', url);
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

function buildurl(imprimir) {
    var par = '';
    var cod_vaga_empresa = $('#cod_vaga_empresa').val();
    
    if (cod_vaga_empresa){
        par = par + '/empresa/'+cod_vaga_empresa;
    }
    
    if (imprimir === true) {
        return LINK_CONTROLLER+'/candidatosnaoaproveitadosrel'+par;
    } else {
        return LINK_ACTION+par;
    }
}