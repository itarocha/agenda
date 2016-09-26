$(function() {
    $('.ajuda-li').click(function() {
        var e = $(this);
        $('.pajuda').hide();
        e.find('.pajuda').show();
    });
});