window.roundDecimals = function (value, decimals) {
    decimals = decimals || 0;
    return Number(Math.round(value + 'e' + decimals) + 'e-' + decimals);
}

$(document).ready(function () {
    
    $(".IsNumberOnly").keypress(function (evt) {
        evt = (evt) ? evt : window.event;
        var charCode = (evt.which) ? evt.which : evt.keyCode;
        console.log(charCode, evt);
        if (charCode > 31 && (charCode < 48 || charCode > 57)) {
            return false;
        }
        return true;
    });

    $(".IsNumberDecimal").keypress(function (evt) {
        evt = (evt) ? evt : window.event;
        var charCode = (evt.which) ? evt.which : evt.keyCode;
        console.log(charCode, evt);
        if (charCode > 31 && (charCode < 48 || charCode > 57) && charCode != 46) {
            return false;
        }
        return true;
    });

    $('.checkbox_show_input, .checkbox_hide_input').click(function () {
        $(".hidden_input").slideToggle(this.checked);
        $(".visible_input").slideToggle(this.checked);

        $(".source_hidden_input").slideToggle(this.checked);
    });

    $('.checkbox_source_show_input').click(function () {
        $(".source_hidden_input").slideToggle(this.checked);
    });

    $('#can_not_use_program').click(function () {
        $(".hidden_input02").slideToggle(this.checked);
    });
    
    if ($('.checkbox_show_input').length) {
        if ($('.checkbox_show_input').is(':checked')) {
            $(".hidden_input").show();
        } else {
            $(".hidden_input").hide();
        }
    } else if ($('.checkbox_hide_input').length) {
        if ($('.checkbox_hide_input').is(':checked')) {
            $(".hidden_input").hide();
        } else {
            $(".hidden_input").show();
        }
    }

    if ($('.checkbox_source_show_input').length) {
        if ($('.checkbox_source_show_input').is(':checked')) {
            $(".source_hidden_input").show();
        } else {
            $(".source_hidden_input").hide();
        }
    }
    
});