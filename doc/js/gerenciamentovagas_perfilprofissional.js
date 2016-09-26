$(document).ready(function() {
    $(".PerfilSelect").click(function() {
        $('.defp-status').css('background-color', '#FAFAFA');
        $('.PerfilSelect').parents('li').css('border-color', '#EEEEEE');
        $('.change').css('color', '#BCBCBC');
        $(this).find('.defp-status').css('background-color', '#0C7846');
        $(this).parents('li').css('border-color', '#0C7846');
        $(this).find('.change').css('color', '#FFF');
        $('[name="cod_perfil_profissional"]').each(function() {
        if ($(this).is(':checked')) {
            $(this).parents('li').find('.defp-txt').removeClass('c-cinzaclaro');
            $(this).parents('li').find('.defp-txt').addClass('c-cinza');
        } else {
            $(this).parents('li').find('.defp-txt').addClass('c-cinzaclaro');
            $(this).parents('li').find('.defp-txt').removeClass('c-cinza');
        }
    });
        
    });
    $('[name="cod_perfil_profissional"]').each(function() {
        if ($(this).is(':checked')) {
            $('.PerfilSelect').parents('li').css('border-color', '#EEEEEE');
            $('.change').css('color', '#BCBCBC');
            $(this).parents('li').find('.defp-status').css('background-color', '#0C7846');
            $(this).parents('li').find('.defp-txt').removeClass('c-cinzaclaro');
            $(this).parents('li').find('.defp-txt').addClass('c-cinza');
            $(this).parents('li').css('border-color', '#0C7846');
            $(this).parents('li').find('.change').css('color', '#FFF');
        } else {
            $(this).parents('li').find('.defp-txt').addClass('c-cinzaclaro');
            $(this).parents('li').find('.defp-txt').removeClass('c-cinza');
        }
    });
    $('[name="cod_perfil_profissional"]').each(function() {
        $(this).not(':checked').parents('li').find('.defp-txt').removeClass('c-cinza');
        $(this).not(':checked').parents('li').find('.defp-txt').addClass('c-cinzaclaro');
    });
});
