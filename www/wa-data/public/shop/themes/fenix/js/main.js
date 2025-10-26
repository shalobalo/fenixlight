jQuery(document).ready(function(){
    var close_address_popup = function() {
        $('.address-popup').hide();
        $('.address-popup-content').html('');
    };
    $('.address-popup-close').on('click',close_address_popup);
    $('.address-list li .more').on('click',function(){
        close_address_popup();
        if( $(this).children('.address-extend').html() ) {
            $('.address-popup').css({ top : ( $(this).offset().top - ($('#navbar-collapse-1').length ? $('#navbar-collapse-1').height() : 0)) /*, left : $(this).offset().left*/ });
            $('.address-popup-content').html($(this).children('.address-extend').html());
            $('.address-popup').show();
        }
    });
});

function moveContentForWindowResize(){
  if( $(window).width() > 975 ) {
    $( ".sidebar" ).insertBefore( $( ".main-content" ) );
  } else {
    $( ".sidebar" ).insertAfter( $( ".main-content" ) );
  }
  if( $(window).width() > 975 || $('body').hasClass('is_desctop') ) {
    $( ".nav.navbar-nav.navbar-right" ).insertBefore( $( ".nav.navbar-nav:not(.navbar-right)" ) );
  } else {
    $( ".nav.navbar-nav.navbar-right" ).insertAfter( $( ".nav.navbar-nav:not(.navbar-right)" ) );
  }
}
var highlightLinkOnReview = function(el) {
  var pattern = /^(?:https?:\/\/)?(?:www\.)?(?:youtu\.be\/|youtube\.com\/)/;
  if (el.href.match(pattern)) {
    $(el).addClass('youtube').attr('target','_blank');;
  }
}

function open_pop_up(box) {
  $("#fon_box").show();
  $(box).fadeIn(500).css({margin:'-'+ ($(box).height()/2) +'px 0 0 -'+ ($(box).width()/2) + 'px'});
}

function close_pop_up(box) {
  $(box).fadeOut(500);
  $("#fon_box").delay(550).hide(1);
}

jQuery(document).ready(function($) {
    "use strict";

  var is_mobile = (/android|webos|iphone|ipad|ipod|blackberry|iemobile|opera mini/i.test(navigator.userAgent.toLowerCase()));

  if( !is_mobile ) {
    $('body').addClass('is_desctop');
  }

  //Load footer information block after page loaded (noibdex for google and yandex)
  $.post('/custom/block/',
  {block_id : 'footer_information_block'}, function (json) {
     if( typeof json.data.block_content != 'undefined' ) {
        jQuery('#footer-block .footer-information .row').append(json.data.block_content)
    }
  }, 'json');

  //replace images for different screen dimensions
  $('.block-chess-banners img').each(function(key,el){ //chess block
    el = $(el);
    var src = el.attr('src');
    if( $(window).width() < 1000 ) {
      if (el.attr('data-src-screen-md')) {
        src = el.attr('data-src-screen-md');
      } else if (el.attr('data-src-screen-lg')) {
        src = el.attr('data-src-screen-lg');
      }
    } else {
      if (el.attr('data-src-screen-lg')) {
        src = el.attr('data-src-screen-lg');
      } else if (el.attr('data-src-screen-md')) {
        src = el.attr('data-src-screen-md');
      }
    }
    if( src != el.attr('src') ) {
      el.attr('src', src);
    }
  });

  $('.home-category img').each(function(key,el){
    el = $(el);
    var src = el.attr('src');
    if( $(window).width() >= 768 ) {
      if (el.attr('data-src-screen-lg')) {
        src = el.attr('data-src-screen-lg');
      } else if (el.attr('data-src-screen-sm')) {
        src = el.attr('data-src-screen-sm');
      }
    } else {
      if (el.attr('data-src-screen-sm')) {
        src = el.attr('data-src-screen-sm');
      } else if (el.attr('data-src-screen-lg')) {
        src = el.attr('data-src-screen-lg');
      }
    }
    if( src != el.attr('src') ) {
      el.attr('src', src);
    }
  });

  if( $(window).width() <= 975 && $('#product-zoom').length ) {
    var size = 720;
    $('#product-zoom').attr('src', $('#product-zoom').attr('src').replace(/([^\.]+)\.([0-9]+)x([0-9]+)\.jpg/,'$1.' + (size ? size : '$2') + 'x0.jpg') );
  }




  // Find all YouTube videos
  var $allVideos = $('.last-posts .description iframe, .tab-content iframe, .blog iframe, .block-blog iframe');
  // Figure out and save aspect ratio for each video
  $allVideos.each(function() {
    $(this)
      .data('aspectRatio', this.height / this.width)
      // and remove the hard coded width/height
      .removeAttr('height')
      .removeAttr('width');
  });

  // Resize all videos according to their own aspect ratio
  $allVideos.each(function() {
    var $el = $(this);
    var newWidth = $el.parent().width();
    $el
      .width(newWidth)
      .height(newWidth * $el.data('aspectRatio'));
  });

    $(function() {
        //Keep track of last scroll
        var lastScroll = 0;
        var header = $("#header");
        var headerfixed = $("#header-main-fixed");
        var headerfixedbg = $(".header-bg");
        var headerfixedtopbg = $(".top-header-bg");
        $(window).scroll(function() {
            //Sets the current scroll position
            var st = $(this).scrollTop();
            //Determines up-or-down scrolling
            if (st > lastScroll) {

                //Replace this with your function call for downward-scrolling
                if (st > 50) {
                    header.addClass("header-top-fixed");
                    header.find(".header-top-row").addClass("dis-n");
                    headerfixedbg.addClass("header-bg-fixed");
                    headerfixed.addClass("header-main-fixed");
                    headerfixedtopbg.addClass("top-header-bg-fix");
                }
            }
            else {
                //Replace this with your function call for upward-scrolling
                if (st < 50) {
                    header.removeClass("header-top-fixed");
                    header.find(".header-top-row").removeClass("dis-n");
                    headerfixed.removeClass("header-main-fixed");
                    headerfixedbg.removeClass("header-bg-fixed");
                    headerfixedtopbg.removeClass("top-header-bg-fix");
                    //headerfixed.addClass("header-main-fixed")
                }
            }
            //Updates scroll position
            lastScroll = st;
        });
    });

    // Bestseller owl slider script
    $("#nav-bestseller .next").click(function() {
        $("#owl-bestseller").trigger('owl.next');
    });
    $("#nav-bestseller .prev").click(function() {
        $("#owl-bestseller").trigger('owl.prev');
    });

    $("#owl-bestseller").owlCarousel({
        // Most important owl features
        items: 4,
        itemsCustom: false,
        itemsDesktop: [1199, 3],
        itemsDesktopSmall: [980, 2],
        itemsTablet: [630, 1],
        itemsTabletSmall: false,
        itemsMobile: [479, 1],
        singleItem: false,
        itemsScaleUp: false,
        responsive: true,
        responsiveRefreshRate: 200,
        responsiveBaseWidth: window,
        autoPlay: false,
        stopOnHover: false,
        navigation: false
    });

    // owl slider script

    $(function() {
      if( $(window).width() > 975 && $('body').hasClass('is_desctop')) {
        var open_timeout = false;
        $('.dropdown').on('mouseenter', function () {
          var _this = this;
          open_timeout = setInterval(function () {
            $(_this).addClass('open');
            clearInterval(open_timeout);
          }, 500);
        });

        $('.dropdown').on('mouseleave', function () {
          var _this = this;
          clearInterval(open_timeout);
          $(_this).removeClass('open');
        });
      } else {
        $('a.dropdown-toggle').on('click',function(){
          var $parent = $(this).parent('li');
          var $menu = $parent.parent('ul');

          if( $parent.find('ul li .header-menu > a').length == 0 ) return;

          if( $parent.hasClass('open') ) {
            $menu.children('li').removeClass('open')
          } else {
            $menu.children('li').removeClass('open')
            $parent.addClass('open');
          }
          return false;
        })
      }
//alert('1');
//        $('.dropdown').hover(function() {
//            $(this).addClass('open');
//          return false;
//        }, function() {
//            $(this).removeClass('open');
//          return false;
//        });
//      $('a.dropdown-toggle').on('click',function(){
//        if( $(window).width() < 975) return false;
//      })
    });

    if( $('.revolution').length ) {

        var w_width = $(window).width();
        var size = false;
        if( w_width >= 1920) {
          size = 1920;
        } else if( w_width >= 1366 ) {
          size = 1920;
        } else if( w_width >= 1280 ) {
          size = 1366;
        } else if( w_width >= 1024 ) {
          size = 1280;
        } else if( w_width >= 800 ) {
          size = 1024;
        } else {
          size = 800;
        }

        $('.revolution li img').each(function(key,el){
          el.src = $(el).attr('data-src').replace(/([^\.]+)\.([0-9]+)x([0-9]+)\.jpg/,'$1.' + (size ? size : '$2') + 'x0.jpg');
        })

      var revapi = $('.revolution').revolution(
          {
            delay:9000,
	          startwidth:1904,
            startheight:932,
            hideThumbs:10,
            fullWidth:"on",
            forceFullWidth:"on",
            soloArrowLeftVOffset: 50,
            soloArrowRightVOffset: 50
          });
    }

    //var gallery = $('#gal1');
    //gallery.find('a').hover(function() {
    //
    //    var smallImage = $(this).attr("data-image");
    //    var largeImage = $(this).attr("data-zoom-image");
    //    var ez = $('#product-zoom').data('elevateZoom');
    //
    //    ez.swaptheimage(smallImage, largeImage);
    //});

    // Categories Menu Manipulations
    $(".ul-side-category li i").click(function() {
        var el = $(this).parent();
        var sm = el.next();
        if (sm.hasClass("sub-category")) {
            if (sm.css("display") === "none") {
                el.next().slideDown();
            } else {
                el.next().slideUp();
                el.next().find(".sub-category").slideUp();
            }
            return false;
        }
        else {
            return true;
        }
    });

    moveContentForWindowResize();

    $( window ).resize(function() {
      moveContentForWindowResize();
    });

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

  //compare START
  function compare_init() {
    var compare = $.cookie('shop_compare')
    if (compare) {

      var compare_array = compare.split(',')
      for (var i = 0; i < compare_array.length; i++) {

        var prod = $('.product_' + compare_array[i]);
        if (prod) {
          var compare_add = prod.find('.compare-grid-add');
          var compare_link = prod.find('.compare-grid-link');
          var compare_checkbox = prod.find('.compare-grid-checkbox');
          compare_add.hide();
          compare_link.show();
          //if (compare_array.length > 1) {
          compare_link.attr('href', '/compare/' + compare );
          compare_checkbox.prop("checked", true);
          //}
        }
      }
    }
  }
  compare_init();

  $('.compare-grid-checkbox').on('change',function(){
    if( $(this).is(":checked") ) {
      var compare = $.cookie('shop_compare');
      if (compare) {
        compare += ',' + $(this).data('product');
      } else {
        compare = '' + $(this).data('product');
      }
      $.cookie('shop_compare', compare, { expires: 30, path: '/'});
    } else {
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
        $(".compare-grid-link").hide();
        $(".compare-grid-add").show();
      } else {
        $(this).parent().find('.compare-grid-link').hide();
        $(this).parent().find('.compare-grid-add').show();
      }

      if (compare) {
        $.cookie('shop_compare', compare.join(','), { expires: 30, path: '/'});
      } else {
        $.cookie('shop_compare', null);
      }
    }
    compare_init();
  });
  $('.compare-grid-add').on('click',function(){
    var compare = $.cookie('shop_compare');
    if (compare) {
      compare += ',' + $(this).data('product');
    } else {
      compare = '' + $(this).data('product');
    }
    $.cookie('shop_compare', compare, { expires: 30, path: '/'});
    compare_init();
    return false;
  });

  $('.page-compare-remove').on('click',function(){
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
    if (compare) {
      $.cookie('shop_compare', compare.join(','), { expires: 30, path: '/'});
    } else {
      $.cookie('shop_compare', null);
    }
  });
  //compare END

  // Review cont
  $('a.review').each(function(key, el){
    var reviews = parseInt($(el).html(),10) + "";
    var last_numb = parseInt(reviews.substring( reviews.length -1 ),10);
    var text = reviews + ' отзыв';
    if( last_numb == 0 || last_numb > 5 || ( parseInt(reviews,10) > 5 && parseInt(reviews,10) < 20 )) {
      text += "ов";
    } else if( last_numb > 1 && last_numb < 5 ) {
      text += "а";
    }
    $(el).html(text)
  })
  // Review count END
});
new WOW().init();