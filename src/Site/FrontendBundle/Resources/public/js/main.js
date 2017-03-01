// Handle facebook callback for oauth
if (window.location.hash && window.location.hash == '#_=_') {
    //window.location.hash = '';
    history.pushState("", document.title, window.location.pathname);
}
$(document).ready(function () {
    var appleTest = false;
    if (/iPhone|iPad|iPod/i.test(navigator.userAgent)) {
        appleTest = true;
    }
//------------------------------------------pre-loader-functions-------------------------------------//
    function showShadow() {
        if ($('.loader-background').length == 0)
            $('body').append('<div class="loader-background"></div>');
        $('.loader-background').fadeIn('200');
    }

    function hideShadow() {
        $('.loader-background').fadeOut('200');
    }

    function showLoader() {
        showShadow();
        $('.loader-gif').css({
            'position': 'fixed',
            'left': parseInt($(window).width() / 2),
            'top': parseInt($(window).height() / 2),
            'z-index': 99999999,
            'opacity': 0.5
        });
        $('.loader-gif').show();
    }

    function hideLoader() {
        $('.loader-gif').hide();
        hideShadow();
    }

    $(".show-loader").click(function () {
        showShadow();
        showLoader();
    });
//------------------------------------------end-pre-loader-functions-------------------------------------//

    function validateEmail(email) {
        var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        return re.test(email);
    }
    $('.int-only').on('input', function(){
        this.value = this.value.replace(/^\.|[^\d\.]|\.(?=.*\.)|^0+(?=\d)/g, '');
    });

    $(".only-int").keydown(function(event) {
        // Разрешаем: backspace, delete, tab и escape
        if ( event.keyCode == 46 || event.keyCode == 8 || event.keyCode == 9 || event.keyCode == 27 ||
                // Разрешаем: Ctrl+A
            (event.keyCode == 65 && event.ctrlKey === true) ||
                // Разрешаем: home, end, влево, вправо
            (event.keyCode >= 35 && event.keyCode <= 39)) {
            // Ничего не делаем
            return;
        }
        else {
            // Обеждаемся, что это цифра, и останавливаем событие keypress
            if ((event.keyCode < 48 || event.keyCode > 57) && (event.keyCode < 96 || event.keyCode > 105 )) {
                event.preventDefault();
            }
        }
    });
    if ($("#main-top-slider").is(":visible")) {
        var $mainTopSliderSelector = "#main-top-slider ul";
    }
    //else {
    //    var $newTopSliderSelector = "ul.new-mobile-top-slider";
    //}

//************************************************************************************************************//
    $("#main-top-slider ul").removeClass('show-preload-li');
    var $mainTopSlider = $($mainTopSliderSelector).bxSlider({
        speed: 1000,
        pause: 5000,
        mode: 'horizontal',
        auto: true,
        pager: true,
        infiniteLoop: true,
        controls: false,
        minSlides: 1,
        moveSlides: 1,
        slideMargin: 0,
        pagerCustom: '#main-top-slider-pager'
    });

    $(window).on('resize', function () {
        $mainTopSlider.reloadSlider();
    });
//    *****************************PLUS AND MINUS ********************************//
    $('.plus-minus-wrapper-item').on('click', function(){
        var shift = $(this).hasClass('plus') ? 1 : -1;
        var $elem = $(this).closest('.index-product-input').find('input');
        var $elemVal = parseFloat($elem.val() ? $elem.val() : 0);
        if (shift == -1 && (!$elemVal || $elemVal < 0)) {
            $elem.val(0);
            return false;
        }
        $elemVal += parseFloat(shift);
        $elem.val($elemVal);
        return false;
    });
//    *******************************************************************************//
    if($(window).width() < 768) {
        var lastScrollTop = 0;
        var $padding = 125;
        if($(window).width() < 321){

        }
        $(document).on('scroll', function () {
            var scroll = $(document).scrollTop();
            console.log(scroll);
            var direction = (scroll > lastScrollTop) ? 'down' : 'up';
            var topContainer = $('.fixed-menu-inner-wrapper');
            if (scroll >= $padding && direction == 'down') {
                topContainer.addClass('fixedMenu');
            }
            if (scroll < $padding && direction == 'up') {
                topContainer.removeClass('fixedMenu');
            }
            lastScrollTop = scroll;
        });
    }else{
        var lastScrollTop = 0;
        var $padding = 140;
        $(document).on('scroll', function () {
            var scroll = $(document).scrollTop();
            var direction = (scroll > lastScrollTop) ? 'down' : 'up';
            var topContainer = $('.fixed-content-wrapper');
            if (scroll >= $padding && direction == 'down') {
                topContainer.addClass('fixed-content-wrapper-active');
            }
            if (scroll < $padding && direction == 'up') {
                topContainer.removeClass('fixed-content-wrapper-active');
            }
            lastScrollTop = scroll;
        });
    }
    if($(window).width() < 768){
        $('.head-mobile-button').click(function(e){
            e.preventDefault();
            $('.head-mobile-button').toggleClass('open');
            $(".mobile-menu-content-wrapper").toggleClass('open');
            //$('.page-wrap').toggleClass('disabled');
            $('body').toggleClass('disabled');
        });
    }
    else{
        $('.scroll-mobile-button').click(function(e){
            e.preventDefault();
            $(".laptop-menu-content-wrapper").toggleClass('open');
        });
        $('.login-form-wrap input').focus(function(){
            $(".login-form-wrap").find('.error').html('');
        });
    }
});