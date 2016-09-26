$(document).ready(function() {
    $('[name="tipo_msg"]').click(function() {
        if ($(this).val() == 'E') {
            $('.bloco_msg_especifica').removeClass('d-none');
            $('.bloco_msg_especifica').show();
            $('.bloco_msg_padrao').hide();
        } else {
            $('.bloco_msg_padrao').removeClass('d-none');
            $('.bloco_msg_especifica').hide();
            $('.bloco_msg_padrao').show();
        }
    });
});