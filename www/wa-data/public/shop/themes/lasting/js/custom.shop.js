var highlightLinkOnReview = function(el) {
  var pattern = /^(?:https?:\/\/)?(?:www\.)?(?:youtu\.be\/|youtube\.com\/)/;
  if (el.href.match(pattern)) {
    $(el).addClass('youtube').attr('target','_blank');;
  }
}

$(document).ready(function () {

  $('.review p a').each(function (key, el) {
    highlightLinkOnReview(el);
  });

  $('.review_text a').each(function (key, el) {
    highlightLinkOnReview(el);
  });

    $(document).on('submit','form.addtocart',function(){

        open_pop_up('#onclick_buy');

        var f = $(this);
        $.post(f.attr('action'), f.serialize(), function (response) {
            if (response.status == 'ok') {
                var header_mini_cart = $('.header-mini-cart span');
                f.children('input[type=submit]').val('В КОРЗИНЕ');
                f.children('input[type=submit]').addClass('added');
                header_mini_cart.html(response.data.count + ' товаров - ' + response.data.total);
            }
        }, "json");
        return false;
    });

});

function open_pop_up(box) {
    $("#fon_box").show();
    $(box).show(500);
}

function close_pop_up(box) {
    $(box).hide(500);
    $("#fon_box").delay(550).hide(1);
}
