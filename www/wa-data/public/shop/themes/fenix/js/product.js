$(document).ready(function() {

  $('.fast-purchase').on('click',function(){
    open_pop_up('#fast_purchase');
    return false;
  });

  $("#buy_one_click").submit(function() {
    var form = $("#buy_one_click");
    $.post('/custom/email/',
    $(this).serialize(), function (json) {
        if( json.data.send ) {
          $('#fast_purchase .block-form.box-border').html('<h5>Спасибо! Ваша заявка принята! Наши менеджеры свяжутся с Вами в ближайшее время!</h5><div class="row"><div class="col-md-12"><a onclick="close_pop_up(\'#fast_purchase\'); return false;" href="#" class="btn-default-1">закрыть</a></div></div>');
        } else {
          var errors = json.data.errors;
          for (var name in errors) {
            $('[name='+name+']', form).addClass('error');
            if( $('.errormsg', form).length ) {
              $('.errormsg', form).text(errors[name]);
            } else {
              $('[name='+name+']', form).after($('<em class="errormsg"></em>').text(errors[name]));
            }
          }
        }
    }, 'json');
    return false;
  });

  $(".fancybox-button").fancybox({
    prevEffect	: 'none',
    nextEffect	: 'none',
    beforeLoad: function () {
      var el, id = $(this.element).data('title-id');

      if (id) {
        el = $('#' + id);

        if (el.length) {
          this.title = el.html();
        }
      }
    },
    helpers	: {
      title	: {
        type: 'outside'
      },
      thumbs	: {
        width	: 50,
        height	: 50
      }
    }
  });

  // compare block
  $("a.compare-add").click(function () {
    var compare = $.cookie('shop_compare');
    if (compare) {
      compare += ',' + $(this).data('product');
    } else {
      compare = '' + $(this).data('product');
    }
    if (compare.split(',').length > 1) {
      var url = $("#compare-link").attr('href').replace(/compare\/.*$/, 'compare/' + compare + '/');
      $("#compare-link").attr('href', url).show().find('span.count').html(compare.split(',').length);
    }

    if (compare.split(',').length == 1) {
      $('#only_one_compare').show();
    } else {
      $('#only_one_compare').hide();
    }

    $.cookie('shop_compare', compare, { expires: 30, path: '/'});
    $(this).hide();
    $("a.compare-remove").show();
    return false;
  });

  $("a.compare-remove").click(function () {
    var compare = $.cookie('shop_compare');

    if (compare) {
      compare = compare.split(',');
    } else {
      compare = [];
    }
    var i = $.inArray($(this).data('product') + '', compare);
    if (i != -1) {
      compare.splice(i, 1)
    }
    if (compare.length < 2) {
      $("#compare-link").hide();
    }
    if (compare.length > 1) {
      var url = $("#compare-link").attr('href').replace(/compare\/.*$/, 'compare/' + compare + '/');
      $("#compare-link").attr('href', url).show().find('span.count').html(compare.length);
    }

    if (compare.length == 1) {
      $('#only_one_compare').show();
    } else {
      $('#only_one_compare').hide();
    }

    if (compare) {
      $.cookie('shop_compare', compare.join(','), { expires: 30, path: '/'});
    } else {
      $.cookie('shop_compare', null);
    }
    $(this).hide();
    $("a.compare-add").show();
    return false;
  });

});