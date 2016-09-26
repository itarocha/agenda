$(document).ready(function() {
    $('#ind_opcao').change(function() {
        opcaoChange();
    });
    $('#ind_opcao').keydown(function() {
        opcaoChange();
    });
    opcaoChange();
});

// A = Adicionar
// S = Selecionar
function opcaoChange(){
    if ($('#ind_opcao').val() == "A") { 
        $('#div_adicionar').show();
        $('#div_selecionar').hide();
    } else {
        $('#div_adicionar').hide();
        $('#div_selecionar').show();
    }
}
