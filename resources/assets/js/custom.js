var processItemsCount = 0;

$(document).ready(function() {
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

        total_price.val(parseFloat(unit_price.val()) * parseInt(quantity.val()));
        update_prices();
    });

    $(document).delegate('#prcoess_items input.unit_price', 'change', function() {
        var unit_price = $(this);
        var quantity = $(this).parent().parent().prev().find('input');
        var total_price = unit_price.parent().parent().next().find('input');

        if (unit_price.val() == "") {
            unit_price.val(0);
        }

        total_price.val(parseFloat(unit_price.val()) * parseInt(quantity.val()));
        update_prices();
    });

    $("#require_bill, #has_discount, #discount_percentage").change(function() {
        update_prices();
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

    function calculate_discount(price) {
        var discount_percentage = $('#discount_percentage').val();
        if ($('input[name="has_discount"]').is(':checked') && discount_percentage != "") {
            // $('.discount_price').text("-" + discount_percentage + "%");
            return parseFloat(price * parseFloat(discount_percentage) / 100);
        } else {
            $('.discount_price').text("0");
            return 0;
        }
    }

    function calculate_taxes(price) {
        if ($('input[name="require_bill"]').is(':checked')) {
            // $('.taxes_price').text("10%");
            // return parseFloat((price * 0.1) + price);
            return parseFloat(price * 0.1);
        } else {
            $('.taxes_price').text("0");
            return 0;
        }
    }

    // function calculate_total() {
    //     var price = calculate_process_price();
    //     price -= calculate_discount(price);
    //     price += calculate_taxes(price);
    //     return Math.ceil(price);
    // }

    function update_prices() {
        var process_price_html = $('.process_price');
        var discount_price_html = $('.discount_price');
        var taxes_price_html = $('.taxes_price');
        var final_price_html = $('.final_price');

        var price = calculate_process_price();
        var discount = calculate_discount(price);
        var taxes = calculate_taxes(price);

        process_price_html.text(price);
        discount_price_html.text(discount);
        taxes_price_html.text(taxes);

        price -= discount;
        price += taxes;

        price = Math.ceil(price);
        final_price_html.text(price);
        $('input[name="total_price"]').val(price);
    }

    if (processItemsCount == 0) {
        add_new_process_item();
    } else {
        for (var i = 0; i < processItemsCount; i++) {
            var qty = parseInt($('input[name="items[' + i + '][quantity]"]').val());
            var unit = parseFloat($('input[name="items[' + i + '][unit_price]"]').val());
            $('input[name="items[' + i + '][total_price]"]').val(
                qty * unit
            );
        }
    }
    update_prices();

    /******************
        Employee  -  Borrow
    ****************************************************************/



});
