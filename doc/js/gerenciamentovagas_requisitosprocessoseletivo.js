$(document).ready(function() {
    $('.c-check').click(function() {
        $(this).find('[type="radio"]').each(function() {
            var e = $(this);
            switch (e.val()) {
                case 'S':
                    $('.' + e.attr('name')).fadeIn();
                    break;
                default:
                    $('.' + e.attr('name')).fadeOut();
                    break;
            }
        });
    });
    $('#dta_abertura_vaga').focusout(function() {
        var data_inicio = $("#dta_abertura_vaga").val();
        var data_fim = $("#dta_prevista_admissao").val();
        if (data_inicio.length != 0 && data_fim.length != 0) {
            var compara1 = parseInt(data_inicio.split("/")[2].toString() + data_inicio.split("/")[1].toString() + data_inicio.split("/")[0].toString());
            var compara2 = parseInt(data_fim.split("/")[2].toString() + data_fim.split("/")[1].toString() + data_fim.split("/")[0].toString());
            if (compara1 > compara2)
            {
                $("#dta_abertura_vaga").val('');
                $("#dta_abertura_vaga").focus();
                fancyAlert("A data de abertura da vaga não pode ser posterior à data prevista para admissão", 'err', '');
            }
        }

    });
    $('#dta_fechamento').focusout(function() {
        var data_inicio = $("#dta_abertura_vaga").val();
        var data_fim = $("#dta_fechamento").val();
        if (data_inicio.length != 0 && data_fim.length != 0) {
            var compara1 = parseInt(data_inicio.split("/")[2].toString() + data_inicio.split("/")[1].toString() + data_inicio.split("/")[0].toString());
            var compara2 = parseInt(data_fim.split("/")[2].toString() + data_fim.split("/")[1].toString() + data_fim.split("/")[0].toString());
            if (compara1 > compara2)
            {
                $("#dta_fechamento").val('');
                $("#dta_fechamento").focus();
                fancyAlert("Data de fechamento não pode ser anterior à data de abertura", 'err', '');
            }
        }
    });
    
    $('#dta_prevista_admissao').focusout(function() {
        var data_inicio = $("#dta_fechamento").val();
        var data_fim = $("#dta_prevista_admissao").val();
        if (data_inicio.length != 0 && data_fim.length != 0) {
            var compara1 = parseInt(data_inicio.split("/")[2].toString() + data_inicio.split("/")[1].toString() + data_inicio.split("/")[0].toString());
            var compara2 = parseInt(data_fim.split("/")[2].toString() + data_fim.split("/")[1].toString() + data_fim.split("/")[0].toString());
            if (compara1 > compara2)
            {
                $("#dta_prevista_admissao").val('');
                $("#dta_prevista_admissao").focus();
                fancyAlert("Data de prevista para admissão não pode ser anterior à data de fechamento", 'err', '');
            }
        }
    });
});