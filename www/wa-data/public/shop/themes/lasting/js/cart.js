$(function () {

    function updateCart(data)
    {
        console.log('>>updateCart');
        $(".cart-total .price").html(data.total);
//		$("#cart_count").html(data.count + ' товаров');
//        $(".cart-discount").html('&minus; ' + data.discount);
    }

    $(".cart .delete").click(function () {
        var tr = $(this).closest('tr');
        $(this).addClass('fa-spinner fa-spin');
        $.post('delete/', {id: tr.data('id')}, function (response) {
            tr.remove();
            updateCart(response.data);
        }, "json");
    });

    $(".cart input.qty").change(function () {
        var that = $(this);
        if (that.val() > 0) {
            $( '<i class="fa fa-spinner fa-spin"></i>' ).insertAfter( that );
            that.prop('disabled', true);
            var tr = that.closest('tr');
            if (that.val()) {
                $.post('save/', {id: tr.data('id'), quantity: that.val()}, function (response) {
                    that.next().remove();
                    that.prop('disabled', false);
                    tr.find('.card_product_total').html(response.data.item_total);
                    if (response.data.q) {
                        that.val(response.data.q);
                    }
                    updateCart(response.data);
                }, "json");
            }
        } else {
            that.val(1);
        }
    });
});