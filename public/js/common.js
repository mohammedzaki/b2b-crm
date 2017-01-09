
var processItemsCount = 0;

$(document).ready(function() {
    
    var roundDecimals = function (value, decimals) {
        decimals = decimals || 0;
        return Number(Math.round(value + 'e' + decimals) + 'e-' + decimals);
    };
    
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

    $(document).delegate(".authorized_person .delete", "click", function(e) {
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
    $(document).delegate(".unit_price, .quantity, .total_price", "focus", function(e) {
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
    $(document).delegate("#prcoess_items .delete", "click", function(e) {
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

    $(document).delegate('#prcoess_items input.quantity', 'change', function() {
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

    $(document).delegate('#prcoess_items input.unit_price', 'change', function() {
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
    
    
    var discount_value = 0;
    $("#require_bill, #has_discount").change(function() {
        update_prices();
    });
    
    $("#discount_value").change(function() {
        var process_price = $('.process_price');
        var price = calculate_process_price();
        discount_value = ($("#discount_value").val() / price) * 100;
        discount_value = roundDecimals(discount_value, 3);
        $("#discount_percentage").val(discount_value);
        update_prices();
    });
    $("#discount_percentage").change(function() {
        /*var price = calculate_process_price();
        discount_value = (price / 100) * $("#discount_percentage").val();
        $("#discount_value").val(discount_value);
        update_prices();*/
    });
    function add_new_process_item() {
        var html = '<tr>';
        html += '<td><div class="form-group"><input class="form-control" name="items[' + processItemsCount + '][description]" placeholder="ادخل تفاصيل البيان" /></div></td>';
        html += '<td><div class="form-group"><input class="form-control quantity" name="items[' + processItemsCount + '][quantity]" value="0" placeholder="ادخل الكمية" /></div></td>';
        html += '<td><div class="form-group"><input class="form-control unit_price" name="items[' + processItemsCount + '][unit_price]" value="0" placeholder="ادخل سعر الوحدة" /></div></td>';
        html += '<td><div class="form-group"><input class="form-control total_price" name="items[' + processItemsCount + '][total_price]" value="0" /></div></td>';
        html += '<td><div class="btn btn-danger btn-sm pull-left delete"><i class="fa fa-times"></i> حذف</div></td>';
        html += '</tr>';
        $('#prcoess_items').append(html);
        processItemsCount++;
    }

    function calculate_process_price() {
        var sum = 0;
        $('.total_price').each(function() {
            sum = parseFloat($(this).val()) + sum;
        });
        return sum;
    }
    
    /*
    function calculate_discount(price) {
        var discount_percentage = $('#discount_percentage').val();
        if ($('input[name="has_discount"]').is(':checked') && discount_percentage != "") {
            // $('.discount_price').text("-" + discount_percentage + "%");
            return parseFloat(price * parseFloat(discount_percentage) / 100);
        } else {
            $('.discount_price').text("0");
            return 0;
        }
    }*/
    
    function update_prices() {
        var process_price_html = $('.process_price');
        var discount_price_html = $('.discount_price');
        var taxes_price_html = $('.taxes_price');
        var final_price_html = $('.final_price');

        var price = calculate_process_price();
        var priceAfterTaxes = price;
        
        if ($('input[name="require_bill"]').is(':checked')) {
            var taxes = (price - discount_value) * parseFloat(TaxesRate);
            taxes = roundDecimals(taxes, 3);
        } else {
            var taxes = 0;
        }
        discount_value = roundDecimals(discount_value, 3);
        
        process_price_html.text(price);
        discount_price_html.text(discount_value);
        taxes_price_html.text(taxes);

        priceAfterTaxes -= discount_value;
        priceAfterTaxes += taxes;
        
        //$("#discount_value").val(discount);

        //price = Math.ceil(price);
        //priceAfterTaxes = Math.ceil(priceAfterTaxes);
        final_price_html.text(priceAfterTaxes);
        $('input[name="total_price"]').val(price);
        $('input[name="total_priceAfterTaxes"]').val(price);
        
    }

    if (processItemsCount == 0) {
        add_new_process_item();
    } else {
        for (var i = 0; i < processItemsCount; i++) {
            var qty = parseFloat($('input[name="items[' + i + '][quantity]"]').val());
            var unit = parseFloat($('input[name="items[' + i + '][unit_price]"]').val());
            $('input[name="items[' + i + '][total_price]"]').val(
                qty * unit
            );
        }
    }
    update_prices();

    /******************
        Process
    *******************/
});


