$(document).ready(function() {
  $('.comment-reply').on('click', function () {
    var id = $(this).attr('data-id').replace(/^[\D]+/, '');
    $('.comment-form').find('input[name=parent]').val( id );
    $('.comment-form').show().find('[name=text]').val('').focus();
    return false;
  });

  var form = $('.comment-form');

  form.submit(function() {
    addBlogReview();
    return false;
  });

  function clear(form, clear_inputs) {
    clear_inputs = typeof clear_inputs === 'undefined' ? true : clear_inputs;
    $('.errormsg', form).remove();
    $('.error',    form).removeClass('error');
    $('.wa-captcha-refresh', form).click();
    if (clear_inputs) {
      $('input[name=captcha], textarea', form).val('');
      $('input[name=title]', form).val('');
    }
  };

  function addBlogReview(){
form.json = true;
    $.ajax({
      url : form[0].action,
      type: 'POST',
      data: form.serialize(),
      success : function(response) {
        if( response.status == 'ok') {
          clear(form,true);
          location.reload();
        } else {
          clear(form,false);
          for(var i =0; i < response.errors.length; i++ ) {
            var errors = response.errors[i];
            for (var name in errors) {
              $('[name=' + name + ']', form).after($('<em class="errormsg"></em>').text(errors[name])).addClass('error');
            }
          }
        }
      },
      error: function(jqXHR, textStatus, errorThrown) {

      },
      dataType : 'json'
    });





    //$.post(
    //  form[0].action,
    //  form.serialize(),
    //  function (r) {
    //    //location.reload();
    //  },
    //  'json')
    //  .error(function(r) {
    //    if( r.responseText ) {
    //      //for( var i=0; i < r.responseText.length; i++){
    //        console.log('>>>r.responseText[i]',r.responseText[1])
    //      //  if( r.responseText[i] ) {
    //          var errors = r.responseText;
    //          //for (var name in errors) {
    //          //  console.log('>>>name',name)
    //          //  $('[name='+name+']', form).after($('<em class="errormsg"></em>').text(errors[name])).addClass('error');
    //          //}
    //    //    }
    //    //  }
    //    }
    //
    //
    //
    //
    //    console.log('>>>>r1',r)
    //
    //    if (console) {
    //      console.error(r.responseText ? 'Error occured: ' + r.responseText : 'Error occured.');
    //    }
    //  });
  }

});