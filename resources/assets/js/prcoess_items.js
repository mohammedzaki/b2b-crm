window.processItemsCount = 0;
window.discount_value = 0;
window.discount_percentage = 0;
window.source_discount_percentage = 0.005;
window.source_discount_value = 0;
window.totxal_price = 0;
window.taxes = 0;
window.priceAfterTaxes = 0;

window.roundDecimals = function (value, decimals) {
    decimals = decimals || 0;
    return Number(Math.round(value + 'e' + decimals) + 'e-' + decimals);
}

window.add_new_process_item = function () {
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

window.calculate_process_price = function () {
    total_price = 0;
    $('.total_price').each(function () {
        total_price = parseFloat($(this).val()) + total_price;
        total_price = roundDecimals(total_price, decimalPointCount);
    });
    return total_price;
}

window.calculate_percentage = function () {
    discount_percentage = 0;
    if ($('input[name="has_discount"]').is(':checked')) {
        discount_percentage = (calculate_discount() / calculate_process_price()) * 100;
        discount_percentage = roundDecimals(discount_percentage, decimalPointCount);
    }
    return discount_percentage;
}

window.calculate_discount_from_percentage = function () {
    discount_value = 0;
    if ($('input[name="has_discount"]').is(':checked')) {
        discount_percentage = $("#discount_percentage").val();
        discount_value = (calculate_process_price() / 100) * discount_percentage;
        discount_value = roundDecimals(discount_value, decimalPointCount);
    }
    return discount_value;
}

window.calculate_discount = function () {
    discount_value = 0;
    if ($('input[name="has_discount"]').is(':checked')) {
        discount_value = roundDecimals($("#discount_value").val(), decimalPointCount);
    }
    return discount_value;
}

window.calculate_taxes = function () {
    taxes = 0;
    if ($('input[name="require_invoice"]').is(':checked')) {
        taxes = (calculate_process_price()) * parseFloat(TaxesRate);
        taxes = roundDecimals(taxes, decimalPointCount);
    }
    return taxes;
}

window.calculate_process_price_taxes = function () {
    priceAfterTaxes = 0;
    priceAfterTaxes = roundDecimals(((calculate_process_price() + calculate_taxes()) - (calculate_discount() + calculate_source_discount())), decimalPointCount);
    return priceAfterTaxes;
}

window.calculate_source_discount = function () {
    source_discount_value = 0;
    if ($('input[name="has_source_discount"]').is(':checked')) {
        source_discount_value = roundDecimals($("#source_discount_value").val(), decimalPointCount);
    }
    return source_discount_value;
}

window.calculate_source_percentage = function () {
    source_discount_percentage = 0;
    if ($('input[name="has_source_discount"]').is(':checked')) {
        source_discount_percentage = (calculate_source_discount() / calculate_process_price()) * 100;
        source_discount_percentage = roundDecimals(source_discount_percentage, decimalPointCount);
    }
    return source_discount_percentage;
}

window.calculate_source_discount_from_percentage = function () {
    source_discount_value = 0;
    if ($('input[name="has_source_discount"]').is(':checked')) {
        source_discount_percentage = $("#source_discount_percentage").val();
        source_discount_value = (calculate_process_price() / 100) * source_discount_percentage;
        source_discount_value = roundDecimals(source_discount_value, decimalPointCount);
    }
    return source_discount_value;
}

window.update_prices = function () {
    var process_price_html = $('.process_price');
    var discount_price_html = $('.discount_price');
    var taxes_price_html = $('.taxes_price');
    var final_price_html = $('.final_price');
    var source_discount_price = $('.source_discount_value');

    process_price_html.text(calculate_process_price());
    discount_price_html.text(calculate_discount());
    taxes_price_html.text(calculate_taxes());
    final_price_html.text(calculate_process_price_taxes());
    source_discount_price.text(calculate_source_discount());

    $('input[name="total_price"]').val(calculate_process_price());
    $('input[name="total_price_taxes"]').val(calculate_process_price_taxes());
    $('input[name="taxes_value"]').val(calculate_taxes());
    $('input[name="source_discount_value"]').val(calculate_source_discount());

}

window.changeTaxesRate = function() {
  TaxesRate = $('#taxesRate').val() / 100;
  update_prices();
}

window.setProcessItemsEvents = function () {
    $(document).delegate(".unit_price, .quantity, .total_price", "focus", function (e) {
        var parent = $(this).parent().closest('tr');
        if (!parent.hasClass('skip')) {
            /* add new row */
            add_new_process_item();
            parent.addClass('skip');
        }
    });

    $(document).delegate("#prcoess_items .delete", "click", function (e) {
        console.log('Working');
        var parent = $(this).parent().parent();
        console.log(processItemsCount);
        if (processItemsCount > 1) {
            if (parent.prev() && !parent.next().is('tr')) {
                parent.prev().removeClass('skip');
            }
            parent.remove();
            processItemsCount--;
            update_prices();
        }
        console.log(processItemsCount);
    });

    $(document).delegate('#prcoess_items input.quantity', 'change', function () {
        var quantity = $(this);
        var unit_price = $(this).parent().parent().next().find('input');
        var total_price = unit_price.parent().parent().next().find('input');

        if (quantity.val() == "") {
            quantity.val(0);
        }
        var pr = parseFloat(unit_price.val()) * parseFloat(quantity.val());
        pr = roundDecimals(pr, decimalPointCount);
        total_price.val(pr);

        update_prices();
    });

    $(document).delegate('#prcoess_items input.unit_price', 'change', function () {
        var unit_price = $(this);
        var quantity = $(this).parent().parent().prev().find('input');
        var total_price = unit_price.parent().parent().next().find('input');

        if (unit_price.val() == "") {
            unit_price.val(0);
        }
        var pr = parseFloat(unit_price.val()) * parseFloat(quantity.val());
        pr = roundDecimals(pr, decimalPointCount);
        total_price.val(pr);
        update_prices();
    });

    $("#require_invoice, #has_discount, #has_source_discount").change(function () {
        update_prices();
    });

    $("#discount_value").change(function () {
        $("#discount_percentage").val(calculate_percentage());
        update_prices();
    });

    $("#discount_percentage").change(function () {
        $("#discount_value").val(calculate_discount_from_percentage());
        update_prices();
    });

    $("#source_discount_value").change(function () {
        $("#source_discount_percentage").val(calculate_source_percentage());
        update_prices();
    });

    $("#source_discount_percentage").change(function () {
        $("#source_discount_value").val(calculate_source_discount_from_percentage());
        update_prices();
    });

    processItemsCount = $("#prcoess_items").children().length;
    if (processItemsCount == 0) {
        add_new_process_item();
    } else {
        for (var i = 0; i < processItemsCount; i++) {
            var qty = parseFloat($('input[name="items[' + i + '][quantity]"]').val());
            var unit = parseFloat($('input[name="items[' + i + '][unit_price]"]').val());
            $('input[name="items[' + i + '][total_price]"]').val(roundDecimals((qty * unit), decimalPointCount));
        }
    }
    update_prices();
}

$(document).ready(function () {
    setProcessItemsEvents();
});

