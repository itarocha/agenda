$(function() {

//------------------------------------------------------------------------------
//---------- Chama o plugin Multselect.	

    $(".js-multselect-filter").multiselect().multiselectfilter(); // Com filtro
    $(".js-multselect-header").multiselect({header: false}); // Sem cabeçalho
    $(".js-multselect").multiselect(); // Puro


//------------------------------------------------------------------------------
//------------- Chama o plugin Autocomplete.	


    /* Ação auto complete disable ---------- */
    $(".autocomplete2-js").autocomplete({
        source: [
            "ActionScript",
            "AppleScript",
            "Asp",
            "BASIC",
            "C",
            "C++",
            "Clojure",
            "COBOL",
            "ColdFusion",
            "Erlang",
            "Fortran",
            "Groovy",
            "Haskell",
            "Java",
            "JavaScript",
            "Lisp",
            "Perl",
            "PHP",
            "Python",
            "Ruby",
            "Scala",
            "Scheme"
        ],
        select: function(event, ui) {
            var e = $(this);
            e.parents('li').find('.bt-autocomplete').show();
            $(this).attr('disabled', true);
            $(this).trigger('keypress'); //needed to fix bug with enter on chrome and IE
            $(this).blur();
        }
    });

    $('.bt-autocomplete').click(function() {
        var e = $(this);
        e.parents('li').find('input').attr('disabled', false).val('');
        $(this).hide();
    });



    /* Ação auto complete Básica ---------- */
    $(".autocomplete-js").autocomplete({
        source: [
            "ActionScript",
            "AppleScript",
            "Asp",
            "BASIC",
            "C",
            "C++",
            "Clojure",
            "COBOL",
            "ColdFusion",
            "Erlang",
            "Fortran",
            "Groovy",
            "Haskell",
            "Java",
            "JavaScript",
            "Lisp",
            "Perl",
            "PHP",
            "Python",
            "Ruby",
            "Scala",
            "Scheme"
        ],
    });

    $(".autocomplete-js").autocomplete({
        source: availableTags
    });

});