var shift_timer = null;
var shift_time = 2000;	// ����� ����� ���������
var show_time = 5500;	// ����� ������ ���������
function shift_left ()
{
		var size = parseInt($('#lenta').css('width'));
		var position = parseInt ($('#lenta-inside').css('left')) - size;
		var number = Math.abs(Math.ceil(position/size));
        if (!number)
    {
     number=1;
    }
		if (number > 7)
		{
			$('#lenta-inside').css('left', '0');
			number = 1;
		}
		var next = number;
		next = next > 6? 0: next;
		position = -number*size;
		$('#lenta-inside').animate({left: position+'px'}, shift_time);	
		$('.curent').removeClass('curent');
		$('#navigation a:eq('+next+')').addClass('curent');
}

$(function (){
	// ���������� ������ ����� ��������
	var w = $('#lenta img').width();
	$('#lenta').width(w);
	// ������������� ������� ��������� ��������
	$('#item2').css('left', w+'px');
	$('#item3').css('left', 2*w+'px');
	$('#item4').css('left', 3*w+'px');
	$('#item5').css('left', 4*w+'px');
 	$('#item6').css('left', 5*w+'px');
 	$('#item7').css('left', 6*w+'px');
	$('#item1a').css('left', 7*w+'px');
	// ���������� ������ ����� ��������
	$('#lenta').height ($('#item1').height ());
	// ������������� ����������� ������ ���������
	$('#navigation a').click(function(){
			clearInterval(shift_timer);
			var size = parseInt($('#lenta').css('width'));
			var number = parseInt (this.toString().substr(-1,1)) - 1;
			var next = number;
			next = next > 6? 0: next;
			position = -number*size;
			$('#lenta-inside').animate({left: position+'px'}, shift_time);	
			$('.curent').removeClass('curent');
			$('#navigation a:eq('+next+')').addClass('curent');
			shift_timer = setInterval("shift_left()", show_time+shift_time);
			return false;
		});
	// ��������� ����� ����� �����
	shift_timer = setInterval("shift_left()", show_time+shift_time);
});
