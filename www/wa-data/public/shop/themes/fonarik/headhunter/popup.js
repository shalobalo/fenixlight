var callBackHunter = function() {

    this.show_timeout = 5000 * 60;
    this.next_show_timeout = 3000 * 60;
    this.operators = 7;
    this.available_count_close = 2;
    this.office_timezone_offset = -180; // minutes
    this.office_start_day = 9; // hours
    this.office_end_day = 22; // hours
    this.callback_time = 5 // minutes

    this.first_visit_time = 'callBackHunter_first_visit_time';
    this.count_close_dialog = 'callBackHunter_count_close_dialog';

    var _this = this;

    this.init = function() {

        if( _this.canShowDialog() ) {
            var start_view_time = readCookie(this.first_visit_time);

            if( !start_view_time ) {
                start_view_time = jQuery.now();
                createCookie( this.first_visit_time, start_view_time , this.show_timeout / (1000 * 60 * 60 * 24) )
            }

            var show_popup_after = jQuery.now() - start_view_time + this.show_timeout;

            _this.showDialogAfter(show_popup_after);

            $('#clbh_button_1').on('click',this.showOkDialog);
            $('#clbh_button_2').on('click',this.showNoDialog);
            $('.clbh_send').on('click',this.showFinishDialog);

            $('#clbh_exit').on('click',this.closeDialog);
            $('.clbh_phone').val('');
        }
    }

    this.closeDialog = function() {
        $('#start_dialog').hide();
        $('.ok_dialog').hide();
        $('.no_dialog').hide();
        $('.finish_dialog').hide();
        $('.start_dialog').show();

        var count_close_dialog = readCookie(_this.count_close_dialog);
        var now = new Date();
        var hours = now.getHours();

        if( !count_close_dialog ) count_close_dialog = 0;

        count_close_dialog++;
        createCookie( _this.count_close_dialog, count_close_dialog , 24 - hours / 24 );

        if( _this.canShowDialog() ) {
            _this.showDialogAfter(_this.next_show_timeout)
        }
    };

    this.showDialogAfter = function(timeout) {
        setTimeout( function(){
            if( _this.checkDayOff() ) {
                _this.showStartDialog();
            }
        } , timeout );
    };

    this.canShowDialog = function() {
        var count_close_dialog = readCookie(_this.count_close_dialog);
        if( count_close_dialog < _this.available_count_close ) {
            return true;
        }
        return false;
    };

    this.checkDayOff = function() {
        var now = new Date();
        var hours = now.getHours(),
            minutes = now.getMinutes(),
            timezone_offset = now.getTimezoneOffset();
        var now_minutes = (hours*60 + minutes + timezone_offset - this.office_timezone_offset);
        if(  now_minutes > this.office_start_day * 60 && now_minutes < this.office_end_day * 60 ) {
            return true;
        }
        return false;

    };
    this.showStartDialog = function() {
        $('#start_dialog').show();
    };
    this.showOkDialog = function() {
        $('.clbh_phone').insertBefore( $('.ok_dialog .clbh_banner-button.clbh_send'));
        _this.createBlockOperators();
        $('.start_dialog').hide();
        $('.ok_dialog').show();
    };
    this.startTimer = function(){
        var totalSeconds = 60 * _this.callback_time;
        var timer = setInterval(setTime, 1000);
        function setTime() {
            --totalSeconds;
            time = pad(parseInt(totalSeconds/60)) + ':' + pad(totalSeconds%60);
            $('.hunter_timer').text(time);
            if( totalSeconds == 0 ) { clearInterval(timer); return; }
        };
        function pad(val) {
            var valString = val + "";
            if(valString.length < 2) { return "0" + valString; } else { return valString; }
        };
    };
    this.showNoDialog = function() {
        $('.clbh_phone').insertBefore( $('.no_dialog .clbh_banner-button.clbh_send'));

        _this.createBlockOperators();
        $('.start_dialog').hide();
        $('.no_dialog').show();
    };
    this.showFinishDialog = function() {

        if($('.clbh_phone').val().match(/[0-9]\d{10,11}$/)) {
            var now = new Date();
            var hours = now.getHours();
            createCookie( _this.count_close_dialog, _this.available_count_close , 24 - hours / 24 );
            _this.startTimer();
            $('.ok_dialog').hide();
            $('.no_dialog').hide();
            $('.finish_dialog').show();
            _this.sendEmail($('.clbh_phone').val());
        } else {
            $('.clbh_phone').css( "border-color", "red" );
        }
    };
    this.getDuringOperators = function() {
        return Math.floor(Math.random() * 3) + 1;
    };
    this.getAllCall = function() {

        var now = new Date();
        var hours = now.getHours(),
            minutes = now.getMinutes(),
            timezone_offset = now.getTimezoneOffset();

        return Math.floor(( hours*60 + minutes + timezone_offset - this.office_timezone_offset - this.office_start_day * 60 ) / 15,10);
    };
    this.createBlockOperators = function() {
        var duration = this.getDuringOperators();
        $('.operators_duration').text(duration);
        $('.operators_free').text(this.operators - duration);
        $('.operators_all_call').text(this.getAllCall());
    };
    this.sendEmail = function(phone) {
        $.ajax({
            type: "POST",
            url: "headhunter.php",
            data: { enter : true, phone : phone, title : "ПЕРЕЗВОНИТЬ в течении " + _this.callback_time + " минут", name : 'headhunter' }
        })
    };
};

jQuery(document).ready(function(){
    var call_back_hunter = new callBackHunter();
    call_back_hunter.init();
});

function createCookie(name, value, days) {
    var expires;

    if (days) {
        var date = new Date();
        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
        expires = "; expires=" + date.toGMTString();
    } else {
        expires = "";
    }
    document.cookie = escape(name) + "=" + escape(value) + expires + "; path=/";
}

function readCookie(name) {
    var nameEQ = escape(name) + "=";
    var ca = document.cookie.split(';');
    for (var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) === ' ') c = c.substring(1, c.length);
        if (c.indexOf(nameEQ) === 0) return unescape(c.substring(nameEQ.length, c.length));
    }
    return null;
}

function eraseCookie(name) {
    createCookie(name, "", -1);
}