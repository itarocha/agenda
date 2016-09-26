// adicionar destinatarios
$(document).ready(function() {
    $("a[rel='addCampo']").click(function() {
        $("#campos").append("<div class='destinatario'><input type='text' class='input input-text' name='destinatarios[]' value='" + $('#email').val() + "'> <a href='javascript:void(0);' rel='delCampo' class='ic-ct-list bg-laranja ic-deletar f-left'></a></div>");
        $('#email').val('');
    });
    $(document).on('click', "a[rel='delCampo']", function() {
        $(this).parents('.destinatario').remove();
    });
    $('[name^="enviar-curriculo"]').click(function() {
        var erros = 0;
        $('#ditribuircurriculo').find('[type="text"]').each(function() {
            var e = $(this);
            if ((e.val() != '')) {
                if (!validateEmail(e.val())) {
                    if (e.parents('.destinatario').find('.email-invalid').length == 0) {
                        e.css('border', '1px solid red');
                        e.parents('.destinatario').append('<span class="input-stitle email-invalid"><p class="c-vermelho">E-mail é inválido.</p></span>');
                    }
                    erros++;
                } else {
                    e.parents('.destinatario').find('.email-invalid').remove();
                    e.css('border', '1px solid #E6E6E6');
                }
                e.parents('.destinatario').find('.email-empty').remove();
            } else {
                if (e.parents('.destinatario').find('.email-empty').length == 0) {
                    e.css('border', '1px solid red');
                    e.parents('.destinatario').find('.email-invalid').remove();
                    e.parents('.destinatario').append('<span class="input-stitle email-empty"><p class="c-vermelho">O campo é obrigatório e deve ser preenchido.</p></span>');
                }
                erros++;
            }
        });
        if (erros > 0) {
            //fancyAlert('Existem erros de preenchimento no seu cadastro! <br/>Gentileza conferir e corrigir todos os campos destacados em vermelho.', 'err', '');
            return false;
        } else {
            return true;
        }
    });
});

function validateEmail($email) {
    var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
    if (!emailReg.test($email)) {
        return false;
    } else {
        return true;
    }
}



