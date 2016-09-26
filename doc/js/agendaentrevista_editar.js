// foi injetado um array json no formato
// array['m/d/yyyy',{nome:''}]

$(function(){
    $('#dates').datepicker({
        altField: 'dta_entrevista_date',
        dateFormat: 'dd/mm/yy',
        inline: true,
        showOtherMonths: true,
        dayNamesMin: ['DOM', 'SEG', 'TER', 'QUA', 'QUI', 'SEX', 'SAB'],
        monthNamesShort: ['Janeiro','Fevereiro','Março','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro'],
        changeMonth: true,
        changeYear: true,

        beforeShowDay: function(date) {
            var evento = getEvento(date);
            if (evento) {
                if (evento.candidato){
                    return [false, 'entrevistacandidato', evento.nome];
                } else {
                    return [true, 'entrevistaagendadas', evento.nome];
                }
            } else {
                return [true, '', ''];
            }
        }
    });
    //alert('Recarregou...');
    
    $('#dta_entrevista_date').change(function(){
        //$('#dates').datepicker( 'setDate', $(this).val() );
    });
    
    $("#dates" ).datepicker( "option", "altField", "#dta_entrevista_date" );
    /*
    if (dta_entrevista_date) {
        $('#dates').datepicker( 'setDate', dta_entrevista_date );
    }
    */
 });
 
function getEvento(date){
    var d = date.getMonth()+1 + '/' + date.getDate() + '/' + date.getFullYear();
    var obj = null;
    var retorno = evento[d];
    if (retorno){
        obj = {nome: retorno.nome, candidato : true};
    } else {
        retorno = evento_outros[d];
        if (retorno){
            obj = {nome: retorno.nome, candidato : false};
        }
    }
    return obj;
}