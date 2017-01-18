
var processItemsCount = 0;

$(document).ready(function() {
    
    var roundDecimals = function(value, decimals) {
        decimals = decimals || 0;
        return Number(Math.round(value + 'e' + decimals) + 'e-' + decimals);
    };
    var add_new_process_item = function() {
        var html = '<tr>';
        html += '<td><div class="form-group"><input class="form-control" name="items[' + processItemsCount + '][description]" placeholder="ادخل تفاصيل البيان" /></div></td>';
        html += '<td><div class="form-group"><input class="form-control quantity" name="items[' + processItemsCount + '][quantity]" value="0" placeholder="ادخل الكمية" /></div></td>';
        html += '<td><div class="form-group"><input class="form-control unit_price" name="items[' + processItemsCount + '][unit_price]" value="0" placeholder="ادخل سعر الوحدة" /></div></td>';
        html += '<td><div class="form-group"><input class="form-control total_price" name="items[' + processItemsCount + '][total_price]" value="0" /></div></td>';
        html += '<td><div class="btn btn-danger btn-sm pull-left delete"><i class="fa fa-times"></i> حذف</div></td>';
        html += '</tr>';
        $('#prcoess_items').append(html);
        processItemsCount++;
    };
    var calculate_process_price = function() {
        var sum = 0;
        $('.total_price').each(function() {
            sum = parseFloat($(this).val()) + sum;
        });
        return roundDecimals(sum, 3);
    };
    var update_prices = function() {
        var process_price_html = $('.process_price');
        var discount_price_html = $('.discount_price');
        var taxes_price_html = $('.taxes_price');
        var final_price_html = $('.final_price');

        var price = calculate_process_price();
        price = roundDecimals(price, 3);
        var priceAfterTaxes = price;
        
        if ($('input[name="has_discount"]').is(':checked')) {
            discount_value = $("#discount_value").val();
        } else {
            discount_value = 0;
        }
        discount_value = roundDecimals(discount_value, 3);
        if ($('input[name="require_bill"]').is(':checked')) {
            var taxes = (price - discount_value) * parseFloat(TaxesRate);
            taxes = roundDecimals(taxes, 3);
        } else {
            var taxes = 0;
        }
        
        process_price_html.text(price);
        discount_price_html.text(discount_value);
        taxes_price_html.text(taxes);

        priceAfterTaxes -= discount_value;
        priceAfterTaxes += taxes;
        
        priceAfterTaxes = roundDecimals(priceAfterTaxes, 3);
        price = roundDecimals(price, 3);

        //price = Math.ceil(price);
        //priceAfterTaxes = Math.ceil(priceAfterTaxes);
        final_price_html.text(priceAfterTaxes);
        $('input[name="total_price"]').val(price);
        $('input[name="total_priceAfterTaxes"]').val(price);
    };
    var discount_value = 0;
    var discount_percentage = 0;
    
    /******************
    General
    ****************************************************************/
    $('.checkbox_show_input, .checkbox_hide_input').click(function() {
        $(".hidden_input").slideToggle(this.checked);
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
    /******************
    Client
    ****************************************************************/
    $('#add_authorized_people').click(function(e) {
        e.preventDefault();
        var html = '<div class="authorized_person">';

        html += '<div class="btn btn-danger btn-sm pull-left delete"><i class="fa fa-times"></i> حذف</div>';

        html += '<div class="form-group">';
        html += '<label>اسم الشخص المفوض</label>';
        html += '<input class="form-control" name="authorized[' + authorizedPeopleCount + '][name]" placeholder="ادخل الشخص المفوض" />';
        html += '</div>';

        html += '<div class="form-group">';
        html += '<label>المسمى الوظيفي</label>';
        html += '<input class="form-control" name="authorized[' + authorizedPeopleCount + '][jobtitle]" placeholder="ادخل المسمى الوظيفي" />';
        html += '</div>';

        html += '<div class="form-group">';
        html += '<label>التليفون</label>';
        html += '<input class="form-control" name="authorized[' + authorizedPeopleCount + '][telephone]" placeholder="ادخل التليفون" />';
        html += '</div>';

        html += '<div class="form-group">';
        html += '<label>البريد اﻻلكتروني</label>';
        html += '<input class="form-control" name="authorized[' + authorizedPeopleCount + '][email]" placeholder="ادخل البريد اﻻلكتروني" />';
        html += '</div></div>';

        $('.authorized_people').append(html);
        authorizedPeopleCount++;
    });

    $(".authorized_person .delete").click(function() {
        $(this).parent().remove();
        authorizedPeopleCount--;
    });
    /******************
        Process - Client
    ****************************************************************/
    $('.selectpicker').selectpicker({
        size: 4,
        liveSearch: true
    });

    /*
     * Onfoucs event for items fields
     */
    $(".unit_price, .quantity, .total_price").focus(function() {
        var parent = $(this).parent().closest('tr');
        if (!parent.hasClass('skip')) {
            /* add new row */
            add_new_process_item();
            parent.addClass('skip');
        }
    });

    /*
     * Click event for delete item button.
     */
    $("#prcoess_items .delete").click(function() {
        var parent = $(this).parent().parent();
        if (processItemsCount > 1) {
            if (parent.prev() && !parent.next().is('tr')) {
                parent.prev().removeClass('skip');
            }
            parent.remove();
            processItemsCount--;
            update_prices();
        }
    });

    $('#prcoess_items input.quantity').change(function() {
        var quantity = $(this);
        var unit_price = $(this).parent().parent().next().find('input');
        var total_price = unit_price.parent().parent().next().find('input');

        if (quantity.val() == "") {
            quantity.val(0);
        }
        var pr = parseFloat(unit_price.val()) * parseFloat(quantity.val());
        pr = roundDecimals(pr, 3);
        total_price.val(pr);
        
        update_prices();
    });

    $('#prcoess_items input.unit_price').change(function() {
        var unit_price = $(this);
        var quantity = $(this).parent().parent().prev().find('input');
        var total_price = unit_price.parent().parent().next().find('input');

        if (unit_price.val() == "") {
            unit_price.val(0);
        }
        var pr = parseFloat(unit_price.val()) * parseFloat(quantity.val());
        pr = roundDecimals(pr, 3);
        total_price.val(pr);
        update_prices();
    });
    
    
    $("#require_bill, #has_discount").change(function() {
        update_prices();
    });
    
    $("#discount_value").change(function() {
        //console.log('in discount_value');
        //var process_price = $('.process_price');
        var price = calculate_process_price();
        discount_percentage = ($("#discount_value").val() / price) * 100;
        discount_percentage = roundDecimals(discount_value, 3);
        $("#discount_percentage").val(discount_percentage);
        update_prices();
    });
    
    if (processItemsCount == 0) {
        add_new_process_item();
    } else {
        for (var i = 0; i < processItemsCount; i++) {
            var qty = parseFloat($('input[name="items[' + i + '][quantity]"]').val());
            var unit = parseFloat($('input[name="items[' + i + '][unit_price]"]').val());
            $('input[name="items[' + i + '][total_price]"]').val(roundDecimals(qty * unit, 3));
        }
    }
    update_prices();

    /******************
        Process
    *******************/
});


