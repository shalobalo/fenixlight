var highlightLinkOnReview = function(el) {
    var pattern = /^(?:https?:\/\/)?(?:www\.)?(?:youtu\.be\/|youtube\.com\/)/;
    if (el.href.match(pattern)) {
        $(el).addClass('youtube').attr('target','_blank');;
    }
}

$(document).ready(function () {

    $(document).on('submit','form.addtocart',function(){
        var f = $(this);
        $.post(f.attr('action'), f.serialize(), function (response) {
            if (response.status == 'ok') {
                var cart_total = $(".cart-total");
				var cart_count = $("#cart_count");
                f.children('input[type=submit]').val('В корзине');
				f.children('input[type=submit]').css('background-color','#093');
				cart_total.html(response.data.total);	
                cart_count.html(response.data.count + ' товаров');
            }
        }, "json");
        return false;
    });

    $('.review p a').each(function (key, el) {
        highlightLinkOnReview(el);
    });

    $('.review_text a').each(function (key, el) {
        highlightLinkOnReview(el);
    });
});
