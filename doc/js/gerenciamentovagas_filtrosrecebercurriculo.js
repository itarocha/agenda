$(document).ready(function() {
    $('.c-check').change(function() {
        var e = $(this);
        switch (e.val()) {
            case 'E':
                e.parents('div').find('.bloco-email').addClass('d-block');
                e.parents('div').find('.toggle').fadeIn();
                break;
            default:
                e.parents('div').find('.bloco-email').removeClass('d-block');
                e.parents('div').find('.toggle').fadeOut();
                break;
        }
    });
    $('.toggle').fadeOut('fast'); // Obs.: Os campos que possuiem o plugin multselect precisam aparecer como display none, é obrigado a dar o display none pelo javascript para não bugar a estrutura.
});