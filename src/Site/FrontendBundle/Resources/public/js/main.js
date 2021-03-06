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
            // Убеждаемся, что это цифра, и останавливаем событие keypress
            if ((event.keyCode < 48 || event.keyCode > 57) && (event.keyCode < 96 || event.keyCode > 105 )) {
                event.preventDefault();
            }
        }
    });
    function numOnly(selector) {
        selector.keypress(function (b) {
            var C = /[0-9\x25\x24\x23]/;
            var a = b.which;
            var c = String.fromCharCode(a);
            return !!(a == 0 || a == 8 || a == 9 || a == 13 || c.match(C));
        });
    }

//************************************************************************************************************//
//    index-page-slider
    if($("#main-top-slider ul").length>0){
        $("#main-top-slider ul").removeClass('show-preload-li');
        var $mainTopSlider = $("#main-top-slider ul").bxSlider({
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
    }
    //if($('.default-popular-container').length){
    //    function productDefaultSliderParameters(obj,slide){
    //        var $maxSlides = null;
    //        var $width = $(".default-popular-container").width();
    //        var $slideWidth = $(".default-popular-item").width();
    //        var $type=obj.attr('data-type');
    //        $maxSlides = Math.floor($width/($slideWidth));
    //        var $next = "#default-"+$type+"-right";
    //        var $prev = "#default-"+$type+"-left";
    //        return {
    //            startSlide: slide,
    //            auto: false,
    //            mode: 'horizontal',
    //            pager: false,
    //            infiniteLoop: false,
    //            controls: true,
    //            nextText: '<i class="fa  fa-caret-right"></i>',
    //            prevText: '<i class="fa  fa-caret-left"></i>',
    //            prevSelector: $prev,
    //            nextSelector: $next,
    //            minSlides: 1,
    //            maxSlides: $maxSlides,
    //            moveSlides: 1,
    //            slideWidth: $slideWidth,
    //            slideMargin: 5,
    //            responsive: true,
    //            onSlideNext: function($slideElement, oldIndex, newIndex){
    //                $slideElement.trigger('new-slide-load');
    //            }
    //        };
    //    }
    //    if($("#default-slider-novels")){
    //        var $params = productDefaultSliderParameters($("#default-slider-novels"),0);
    //        var $novelsSlider = $("#default-slider-novels").bxSlider($params);
    //        $novelsSlider.on('new-slide-load',function(){
    //            var $number = $novelsSlider.getSlideCount();
    //            var $current = $novelsSlider.getCurrentSlide();
    //            var $width = $(".default-popular-container").width();
    //            var $slideWidth = $(".default-popular-item").width();
    //            $maxSlides = Math.floor($width/($slideWidth));
    //            if($current+$maxSlides+2>$number){
    //                var $page = parseInt($("#default-slider-novels").attr('page'));
    //                var $url = $("#default-slider-novels").attr('data-url')+"?page="+$page;
    //                $.get($url,function(data){
    //                    if(data){
    //                        $("#default-slider-novels").append(data);
    //                        $params = productDefaultSliderParameters($("#default-slider-novels"),$current);
    //                        $novelsSlider.reloadSlider($params);
    //                        $("#default-slider-novels").attr('page',$page+1)
    //                    }
    //                });
    //            }
    //        });
    //    }
    //    if($("#default-slider-hits")){
    //        var $params = productDefaultSliderParameters($("#default-slider-hits"),0);
    //        var $hitsSlider = $("#default-slider-hits").bxSlider($params);
    //        $hitsSlider.on('new-slide-load',function(){
    //            var $number = $hitsSlider.getSlideCount();
    //            var $current = $hitsSlider.getCurrentSlide();
    //            var $width = $(".default-popular-container").width();
    //            var $slideWidth = $(".default-popular-item").width();
    //            $maxSlides = Math.floor($width/($slideWidth));
    //            if($current+$maxSlides+2>$number){
    //                var $page = parseInt($("#default-slider-hits").attr('page'));
    //                var $url = $("#default-slider-hits").attr('data-url')+"?page="+$page;
    //                $.get($url,function(data){
    //                    if(data){
    //                        $("#default-slider-hits").append(data);
    //                        $params = productDefaultSliderParameters($("#default-slider-hits"),$current);
    //                        $hitsSlider.reloadSlider($params);
    //                        $("#default-slider-hits").attr('page',$page+1)
    //                    }
    //                });
    //            }
    //        });
    //    }
    //    $(window).on('resize', function () {
    //        clearTimeout(window.resizedFinished);
    //        window.resizedFinished = setTimeout(function () {
    //            if(typeof $novelsSlider !== 'undefined' ){
    //                var $params = productDefaultSliderParameters($("#default-slider-novels"),0);
    //                $novelsSlider.reloadSlider($params);
    //            }
    //            if(typeof $novelsSlider !== 'undefined' ){
    //                var $params = productDefaultSliderParameters($("#default-slider-novels"),0);
    //                $novelsSlider.reloadSlider($params);
    //            }
    //        }, 100);
    //    });
    //}
//    product-show-slider
    if($('.product-show-slider').length>0&&$('.product-thumbnail-image-container ul').length>0){
        $(".product-show-slider").removeClass('show-preload-li');
        $('.product-show-slider').bxSlider({
            speed: 1000,
            mode: 'horizontal',
            pager: true,
            auto: false,
            infiniteLoop: false,
            controls: false,
            minSlides: 1,
            moveSlides: 1,
            slideMargin: 0,
            pagerCustom: '.product-thumbnail-image-container'
        });
        var $parameters =  productThumbnailsSliderParameters();
        var $thumbnailSlider =  $('.product-thumbnail-image-container ul').bxSlider($parameters);
        $(window).on('resize', function () {
            clearTimeout(window.resizedFinished);
            window.resizedFinished = setTimeout(function () {
                $parameters =  productThumbnailsSliderParameters();
                $thumbnailSlider.reloadSlider($parameters);
            }, 100);
        });
        function productThumbnailsSliderParameters(){
            var $maxSlides = null;
            var $width = $(".product-thumbnail-image-container").width();
            var $slideWidth = $(".product-thumbnail-image-wrapper").width();
            $maxSlides = Math.floor($width/($slideWidth+9));
            return {
                auto: false,
                mode: 'horizontal',
                pager: false,
                infiniteLoop: true,
                controls: true,
                nextText: '<i class="fa  fa-caret-right"></i>',
                prevText: '<i class="fa  fa-caret-left"></i>',
                prevSelector: '.product-show-slider-prev',
                nextSelector: '.product-show-slider-next',
                minSlides: 1,
                maxSlides: $maxSlides,
                moveSlides: 1,
                slideWidth: 90,
                slideMargin: 9,
                responsive: true
            };
        }
    }
//    *****************************PLUS AND MINUS ********************************//
    $(document).on('click','.plus-minus-wrapper-item',function(){
        var shift = $(this).hasClass('plus') ? 1 : -1;
        var $elem = $(this).closest('.index-product-input').find('input');
        var $elemVal = parseFloat($elem.val() ? $elem.val() : 0);
        if (shift == -1 && (!$elemVal || $elemVal < 0)) {
            $elem.val(0);
            $elem.trigger('countinputchanged');
            return false;
        }
        $elemVal += parseFloat(shift);
        $elem.val($elemVal);
        $elem.trigger('countinputchanged');
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
    if($(window).width() < 767){
        $('.head-mobile-button,.scroll-mobile-button').click(function(e){
            e.preventDefault();
            $('.head-mobile-button,.scroll-mobile-button').toggleClass('open');
            $(".laptop-menu-content-wrapper").toggleClass('open');
            //$(".mobile-menu-content-wrapper").toggleClass('open');
            $('body').toggleClass('body-no-scroll');
        });
    }
    else{
        $('.scroll-mobile-button,.head-mobile-button').click(function(e){
            e.preventDefault();
            $(".laptop-menu-content-wrapper").toggleClass('open');
        });
        $('.login-form-wrap input').focus(function(){
            $(".login-form-wrap").find('.error').html('');
        });
    }
    $('.menu-global-wrapper').perfectScrollbar();
    $('.menu-catalog-title').click(function(){
        $(this).closest('.menu-catalog-wrapper').toggleClass('active');
    });
//    filter - sort buttons
    if($('.product-list-title-buttons-wrapper').length>0){
        function productSortFiltersBind(){
            var $sort = $(".product-filter-sort-type input:checked").val();
            $('.product-list-title-button[data-value='+$sort+']').addClass('active');
            $('.product-list-title-button').click(function(){
                var $val = $(this).attr('data-value');
                var $obj = $(this);
                $('.product-list-title-button').removeClass('active');
                $obj.addClass('active');
                $(".product-filter-sort-type input[value="+$val+"]").attr('checked',true);
                $(".product-filter-sort-type input[value="+$val+"]").closest("form").submit();
                return false;
            });
            if($(window).width()<992){
                $(".product-list-filter-title-mobile-header").click(function(){
                    $(this).parent().toggleClass('open');
                });
            }
        }
        function  productSortFiltersUnBind(){
            $('.product-list-title-button').off('click');
            $(".product-list-filter-title-mobile-header").off('click');
        }
        productSortFiltersBind();
        $(window).on('resize', function () {
            clearTimeout(window.sortButtonsResizedFinished);
            window.sortButtonsResizedFinished = setTimeout(function () {
                productSortFiltersUnBind();
                productSortFiltersBind();
            }, 100);
        });
    }
//    filter number on page
    if($('.product-list-number-select').length>0){
        $('.product-list-number-select').selectpicker({
            dropupAuto: false
        });
        $('.product-list-number-select').on('change',function(){
            $('input.product-filter-number').val($(this).val());
            $('input.product-filter-number').closest('form').submit();
            return false;
        });
    }
    if($('#product-filter-form').length>0){
        $('#product-filter-form input[type="checkbox"]').on('change',function(){
            $(this).closest('form').submit();
            return false;
        });
    }
//  filter titles
    if($('.product-filter-item-title').length>0){
        $('.product-filter-item-title').click(function(){
            $(this).toggleClass('active');
            $(this).parent().find('.product-filter-item-body').slideToggle();
        });
    }
//  product description tabs
    if($(".product-show-tabs-navbar-item").length>0){
        $(".product-show-tabs-navbar-item").click(function(){
            if($(this).hasClass('active')){
                return false;
            }
            var $selector = $(this).attr('data-item');
            $(".product-show-tabs-navbar-item , .product-show-tabs-item").removeClass('active');
            $selector = '[data-item = "'+$selector+'"]';
            $($selector).addClass('active');
        });
    }
//    default selectpicker
    if($(".select-default-view").length){
        $('.select-default-view').selectpicker({
            dropupAuto: false,
            size:15
        });
    }
//    count set sum
    if($('.set-show-form-items-wrapper').length>0){
        $('.set-show-checkbox').click(function(e){
            var $sum=0;
            $('.set-show-form-items-wrapper').find('[data-price]').each(function(){
                var $number = $(this).attr('data-price');
                var $checkbox= $(this).find('.squared-one input');
                if($checkbox.prop('checked')){
                    $sum+=parseInt($number);
                }
            });
            $('.set-show-item-final-price').html($sum);
        });
    }

//**********cart***************
    $('body').on('click','.product-show-cart-add-button',function(){
        var $url = $(this).attr('data-url');
        var $object = {};
        if($('#set-show-form').length > 0){
            $object = $('#set-show-form').serialize();
        }
        if($('#product-show-form').length > 0){
            $object = $('#product-show-form').serialize();
        }

        $.post($url,$object,function(data){
            getNumberOfItemsInCart();
            $(".global-cart-items-container").html(data);
            $('.global-cart-items-container').trigger('contentchanged');
            countGlobalCartSum();
            var $obj = $(".global-cart-wrapper");
            $('.modal-shadow').removeClass("show-image");
            $(".global-cart-wrapper").addClass('open');
            $(".global-cart-modal-body-container").one('transitionend',
                function(e) {
                    var $elm = $(".global-cart-modal-body-container")[0];
                    if($elm.clientHeight < $elm.scrollHeight){
                        $(".global-cart-modal-body-container").addClass('scroll');
                    }
                });
            return false;
        });
        return false;
    });
//**********global cart***************
//    #################################################
    if($(".global-cart-wrapper").length>0){
//******************modal-cart-positioning*****
        function calculateCartTop() {
            var $h = $(".global-cart-wrapper").height();
            var $mH = $('.global-cart-modal-body-wrapper').height();
            if ((($h - $mH) < 20)&&($(window).width()>991)) {
                $('.global-cart-modal-body-wrapper').css({
                    "top": 30 + "px",
                    'margin-top': 0
                });
                //$('.global-cart-modal-body-container').addClass('scroll');
            }
            else{
                $('.global-cart-modal-body-wrapper').css({
                    "top": "",
                    'margin-top': ""
                });
                //$('.global-cart-modal-body-container').removeClass('scroll');
            }
        }
        calculateCartTop();
        $(window).on('resize', function () {
            calculateCartTop();
        });
//*******************update perfectScrollbar on change content
        $(".global-cart-items-container").on('contentchanged',function(){
            $('.global-cart-items-container').perfectScrollbar("destroy");
            if($(window).width()>991) {
                $(".global-cart-items-container").perfectScrollbar({
                    suppressScrollX: true,
                    useBothWheelAxes: false,
                    maxScrollbarLength: 100,
                    wheelSpeed: 0.3,
                });
            }
            $('.global-cart-items-container .select-default-view').selectpicker('destroy');
            $('.global-cart-items-container .select-default-view').selectpicker({
                dropupAuto: false
            });
        });
        $(".global-cart-items-container").on('click','.cart-remove-item',function(){
            var $object =  $(this).closest(".global-cart-item-wrapper");
            var $url = $(this).attr('data-url');
            $object.slideUp(400,function(){
                if($(window).width()>991){
                    $(".global-cart-items-container").perfectScrollbar('update');
                }
                $object.remove();
                countGlobalCartSum();
                return false;
            });
            $.get($url,[],function(data){
                getNumberOfItemsInCart();
            });
        });
        $(document).on('click','.index-product-buy,.basket-image,.menu-basket-wrapper',function(){
            $('.modal-shadow').addClass("open show-image");
            $("body").addClass('body-no-scroll');
            var $url = $(this).attr('data-url');
            var $object = $(this);
            $.get($url,'',function(data){
                if(!$object.hasClass('basket-image')){
                    getNumberOfItemsInCart();
                }
                $(".global-cart-items-container").html(data);
                $('.global-cart-items-container').trigger('contentchanged');
                countGlobalCartSum();
                var $obj = $(".global-cart-wrapper");
                $('.modal-shadow').removeClass("show-image");
                $(".global-cart-wrapper").addClass('open');
                $(".global-cart-modal-body-container").one('transitionend',
                    function(e) {
                        var $elm = $(".global-cart-modal-body-container")[0];
                        if($elm.clientHeight < $elm.scrollHeight){
                            $(".global-cart-modal-body-container").addClass('scroll');
                        }
                    });
                return false;
            });
        });
        $(".close-button, .keep-shopping").click(function(){
            $(".global-cart-wrapper").removeClass('open');
            $(".modal-shadow").removeClass('open');
            $("body").removeClass('body-no-scroll');
            $(".global-cart-modal-body-container").removeClass('scroll');
        });
        $('.global-cart-modal-button-item.make-order').click(function(){
            if($(".global-cart-items-container form .global-cart-item-wrapper").length>0){
                $(".global-cart-wrapper").removeClass('open');
                $('.modal-shadow').addClass("show-image");
                $(".global-cart-items-container form").submit();
                return false;
            }
        });
        //$(".global-cart-wrapper").on('input','.global-cart-set-item-title-container input',function(){
        //    countGlobalCartSum();
        //    return false;
        //});
        $(".global-cart-wrapper").on('change','.global-cart-set-item-title-container input[type="checkbox"]',function(){
            countGlobalCartSum();
            return false;
        });
        $(".global-cart-wrapper").on('countinputchanged','input',function(){
            saveTempForm();
            countGlobalCartSum();
            return false;
        });
    }
//    **************get number of items in cart****
    function getNumberOfItemsInCart(){
        var $url = $('.basket-number-wrapper').attr('data-url');
        if(!$url.length>1){
            return;
        }

        $.get($url,[],function(data){
            var $result = $.parseJSON(data);
            if($result.success == true){
                $('.basket-number-wrapper').html($result.number);
                $('.basket-number-wrapper').addClass('basket-number-bigger');
                $('.basket-number-wrapper.basket-number-bigger').one('transitionend',function(){
                    $('.basket-number-wrapper').removeClass('basket-number-bigger');
                });
            }
        });
    }
//    **************cart count sum
    function countGlobalCartSum(){
        var $sum=0;
        if(!$('.global-cart-item-wrapper').length){
            $(".global-cart-item-final-price").html(0);
            return;
        }
        $(".global-cart-item-wrapper").each(function(){
            $sum+=countItemCartSum($(this));
            $(".global-cart-item-final-price").html($sum);
        });
        return false;
    }
    function countItemCartSum(obj){
        var $obj = obj;
        var $sum = 0;
        var $priceContainer = $obj.find('.global-cart-item-price-value');
        var $numberItem = $obj.find('input.number-count');
        if(!$obj.find('[data-price]').length||!$numberItem.length){
            if($priceContainer.length>0){
                $priceContainer.html(0);
                return 0;
            }
        }
        $obj.find('[data-price]').each(function(){
            var $val = $(this).attr('data-price');
            var $checkbox= $(this).parent().parent().find('.squared-one input');
            if((!$checkbox.length)||($checkbox.prop('checked'))){
                $sum+=parseInt($val);
            }
        });
        $sum = $sum*parseInt($numberItem.val());
        $priceContainer.html($sum);
        return $sum;
    }
//    **************end cart count sum
//    ************************order-create-page********
//    #################################################
    if($(".order-create-items-wrapper").length>0){
        countGlobalCartSum();
        $(".order-create-items-container").on('click','.cart-remove-item',function(){
            var $object =  $(this).closest(".global-cart-item-wrapper");
            var $reindexObject =  $(this).closest(".order-create-global-items-wrapper");
            var $url = $(this).attr('data-url');
            $object.slideUp(400,function(){
                if($(window).width()>991){
                    $(".global-cart-items-container").perfectScrollbar('update');
                }
                $object.remove();
                countGlobalCartSum();
                reindexItems($reindexObject);
            });
            $.get($url,[],function(data){
                getNumberOfItemsInCart();
            });
        });
        $(".order-create-items-wrapper").on('change','.global-cart-set-item-title-container input',function(){
            countGlobalCartSum();
            return false;
        });
        $(".order-create-items-wrapper").on('countinputchanged','input',function(){
            countGlobalCartSum();
            return false;
        });
    }
// ***************set-list**************************
// #################################################
    if($('.set-list-properties-components').length>0){
        $(".set-list-properties-components").perfectScrollbar({
            suppressScrollX: true,
            useBothWheelAxes: false,
            maxScrollbarLength: 25,
            wheelSpeed: 0.1,
        });
    }
// ***************order create**************************
// #################################################
    if($('.order-create-content').length){
        function setPaymentDescription(){
            var $value = $('.select-default-view.payment-description-select select').val();
            if($value.length<1){
                $value = $('.select-default-view.payment-description-select select').find('option:first').val();
                $('.select-default-view.payment-description-select').val($value);
                $('.select-default-view.payment-description-select').selectpicker('refresh');
            }
            var $selector = "[data-value='"+$value+"']";
            $(".order-payment-description-item").removeClass('active');
            $($selector).addClass('active');
        }
        setPaymentDescription();
        $('.payment-description-select').on('change',function(){
            setPaymentDescription();
        });
    }
// ***************news list**************************
// #################################################
    if($('.article-list-content').length>0){
        $('.news-list-content-wrapper').masonry({
            itemSelector: '.list-article-item',
            //columnWidth: 270
        });
        $('.news-list-content-wrapper').infinitescroll({
                //// Pagination element that will be hidden
                navSelector: '#pagination',

                // Next page link
                nextSelector: '#pagination p a',

                // Selector of items to retrieve
                itemSelector: '.list-article-item',

                // Loading message
                loadingText: 'Loading new items…',
                img: ''
            },

            // Function called once the elements are retrieved
            function(new_elts) {
                //var elts = $(new_elts).css('opacity', 0);
                //elts.animate({opacity: 1},300);
                $('.news-list-content-wrapper').masonry('appended', new_elts);
            }
        );
    }
//    ******************comparison and selected
    function updateSelectedNumber(data){
        try
        {
            var response = JSON.parse(data);
        }
        catch(e)
        {
            return;
        }
        if(typeof response.number !== 'undefined'&&typeof response.type !== 'undefined'){
            var $selector = ".count-holder[data-type='"+response.type+"']";
            var $element = $($selector);
            $element.text(response.number).addClass('active').one('transitionend',function(){
                $element.removeClass('active');
            });
        }
    }
    if($('.comparison-item').length||$('.selected-item').length){
        $(document).on('click','.comparison-item ,.selected-item',function(){
            if($(this).hasClass('comparison-item-disabled')){
                return false;
            }
            var $obj = $(this);
            var $url = $obj.attr('data-url');
            $(this).addClass('comparison-item-disabled');
            $('.modal-no-background').addClass('active');
            $.post($url,[],function(data){
                $('.modal-no-background').removeClass('active');
                updateSelectedNumber(data);
            });
        });
    }
    if($('.comparison-list-content-wrapper').length){
        $('.comparison-remove-item').click(function(){
            $('.modal-no-background').addClass('active');
            var $obj = $(this);
            var $url = $obj.attr('data-url');
            $.post($url,[],function(){
                $('.modal-no-background').removeClass('active');
                $obj= $obj.closest('.comparison-item-content-wrapper');
                $obj.animate({
                    'width': 'toggle'
                },300,function(){
                    $obj.remove();
                });
            });
        });
    }
//    ***************comparison show******************
    if($('.comparison-show-content').length){
        $('.comparison-cell-item-remove').click(function(){
            var $attr = $(this).closest('.comparison-fixed-item').attr('data-item');
            var $selector = ".comparison-table [data-item='"+$attr+"']";
            $($selector).animate({
                'width': 'toggle'
            },500,function(){
                $($selector).remove();
            });
            var $url = $(this).attr('data-url');
            $.post($url,[],function(data){
                updateSelectedNumber(data);
            });
        });
    }
//    **************selected-list***********************
    if($('.selected-product-list-content').length){
        $('.selected-remove-item').click(function(){
            var $obj = $(this).closest('.col-lg-15');
            var $url = $(this).attr('data-url');
            $obj.animate({
                'width': 'toggle'
            },500,function(){
                $obj.remove();
            });
            $.post($url,[],function(data){
                updateSelectedNumber(data);
            });
        });
    }
//    **********validation rules************
    $.validator.addMethod("equalToPassword", function (check) {
            var $flag = true;
            $(".equalToPassword").each(function () {
                if (check != $(this).val()) {
                    $flag = false;
                }
            });
            return $flag;
        },
        "Fields are not equal");
    $.validator.addMethod("emailValidation", function (email, element) {
            var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
            return this.optional(element) || regex.test(email);
        },
        "Please specify a valid email");
    $.validator.addMethod("phoneValidation", function (phone, element) {
            var regex = /^\d{10}$/;
            return this.optional(element) || regex.test(phone);
        },
        "Please specify a valid phone number");
    $.validator.addClassRules({
        "name-validation": {
            required: true,
            minlength: 2
        }
    });
    $.validator.addClassRules({
        "email-validation": {
            required: false,
            emailValidation: true
        }
    });
    $.validator.addClassRules({
        "text-validation": {
            required: true,
            minlength: 10
        }
    });
    $.validator.addClassRules({
        "phone-validation": {
            required: true,
            phoneValidation: true
        }
    });
//    *********callback *******************
    $(".close-modal-button").click(function(){
        $(this).closest('.active').removeClass('active');
        $('.modal-with-background').removeClass('active');
        $('.modal-with-background').removeClass('no-image');
        $("body").removeClass('body-no-scroll');
    });
    function clearInputs(){
        $('.clear-input').val('');
    }
    if($('#callback-form').length){
        numOnly($('.phone-validation'));
        $('#callback-form .regular-button').on('click',function(){
            $("#callback-form").validate({
                errorPlacement: function () {
                    //$('html, body').animate({
                    //    scrollTop: $(".error").first().offset().top - 100
                    //}, 200);
                    return false;  // suppresses error message text
                },
                errorClass: "error",
                submitHandler: function () {
                    var $url = $('#callback-form').attr('action');
                    var $obj = $('#callback-form').serialize();
                    $('.modal-callback-wrapper').removeClass('active');
                    $('.modal-with-background').removeClass('no-image');
                    $.post($url,$obj,function(data){
                        clearInputs();
                        var $res = $.parseJSON(data);
                        var $message = 'Во время обработки запроса произошли некоторые ошибки';
                        if($res.success == true){
                            $message = $res.message;
                        }
                        $('.modal-with-background').addClass('no-image');
                        $('.modal-message-wrapper .modal-body').html($message);
                        $('.modal-message-wrapper').addClass('active');
                        setTimeout(function(){
                            $('.modal-message-wrapper').removeClass('active');
                            $('.modal-message-wrapper .modal-body').html('');
                            $('.modal-with-background').removeClass('active');
                            $("body").removeClass('body-no-scroll');
                            $('.modal-with-background').removeClass('no-image');
                        },4000);
                    });
                    return false;
                }
            });
        });
        $('.nav-callback-link a, .footer-callback-link a, .static-content-callback').click(function(){
            $('.modal-with-background').addClass('active');
            $('body').addClass('body-no-scroll');
            $('.modal-with-background').addClass('no-image');
            $('.modal-callback-wrapper').addClass('active');

        });
    }
//    order create
    if($('#order-create-form').length){
        numOnly($('.phone-validation'));
        $('#order-create-form .order-create-form-submit').on('click',function(){
            $("#order-create-form").validate({
                errorPlacement: function () {
                    //$('html, body').animate({
                    //    scrollTop: $(".error").first().offset().top - 100
                    //}, 200);
                    return false;  // suppresses error message text
                },
                errorClass: "error",
                submitHandler: function () {
                    $('.modal-no-background').addClass('active');
                    return true;
                }
            });
        });
    }
//    *******contacts-form******
    if($('#contacts-form').length){
        numOnly($('.phone-validation'));
        $('#contacts-form .contacts-form-submit').on('click',function(){
            $("#contacts-form").validate({
                errorPlacement: function () {
                    //$('html, body').animate({
                    //    scrollTop: $(".error").first().offset().top - 100
                    //}, 200);
                    return false;  // suppresses error message text
                },
                errorClass: "error",
                submitHandler: function () {
                    return true;
                }
            });
        });
    }
    if($('#search-form').length){
        $('.search-input-item').keypress(function (e) {
            if (e.which == 13) {
                $('form#search-form').submit();
                return false;    //<---- Add this line
            }
        });
    }
    function reindexItems($reindexObject){
        var $proto  = $reindexObject.attr('data-name');
        var $number = $reindexObject.children('.reindex-cart-item-wrapper').length;
        if($number){
            for (var $i = 0; $i < $number; $i++){
                var $item =  $proto.split(':');
                var $itemNameProto = $item[0]+'['+$item[1]+']'+'['+$i+']';
                var $regexItem =   $item[0]+'\\['+$item[1]+'\\]\\[\\d\\]';
                var $object = $reindexObject.children('.reindex-cart-item-wrapper').get($i);
                var $nameRegexp = new RegExp($regexItem,'g');
                $reindexObject.find($object).find('input,select').each(function(){
                    var $itemName = $(this).attr('name');
                    $itemName = $itemName.replace($nameRegexp,$itemNameProto);
                    $(this).attr('name',$itemName);
                });
            }
        }
    }
//    ********************************new-post-actions****************************************************************//
    //-------------------------------set delivery select to default on reload-------------------------------///
    var $deliveryType = $('.order-delivery-type-select');
    var sVal = $deliveryType.find('select').find('option:first').val();
    $deliveryType.val(sVal);
    $deliveryType.selectpicker('refresh');
    //------------------------------------------------------------------------------------------------------///
    //---------------------------get delivery provider form via ajax----------------------------------------///
    $('.order-delivery-type-select').on('change', function (e) {
        $('.modal-no-background').addClass('active');
        var str = $(this).val();
        if (str == 'new-post') {
            // send ajax and get propper form
            var url = $(".order-payment-and-delivery-item-wrapper").attr('data-url');
            $.post(url, {}, function (data) {
                $(".order-delivery-item-ukrposhta-wrapper").html('');
                $(".order-payment-and-delivery-item-wrapper").html(data);
                $(".order-payment-and-delivery-item-wrapper .select-default-view").selectpicker({
                    'size': 15,
                    'refresh': true,
                    'dropupAuto': false
                });
                $('.modal-no-background').removeClass('active');
            });
        }
        else if(str == 'ukr-post'){
            // send ajax and get propper form
            var url = $(".order-delivery-item-ukrposhta-wrapper").attr('data-url');
            $.post(url, {}, function (data) {
                $(".order-payment-and-delivery-item-wrapper").html('');
                $(".order-delivery-item-ukrposhta-wrapper").html(data);
                $('.modal-no-background').removeClass('active');
            });
        }
        else if (str == 'self-pickup') {
            $(".order-delivery-item-ukrposhta-wrapper").html('');
            $(".order-payment-and-delivery-item-wrapper").html('');
            $('.modal-no-background').removeClass('active');
        }
        return false;
    });
    ///*******************************new-post form on region change************************************************////
    if($('.order-payment-and-delivery-item-wrapper').length){
        $('.order-payment-and-delivery-item-wrapper').on('change', ".new-post-select-region", function (e) {
            e.preventDefault();
            var url = $('.order-payment-and-delivery-item-wrapper').attr('data-url');
            var dataObj = {};
            dataObj.novaPoshtaData = {};
            dataObj.novaPoshtaData.regionHref  = $(this).val();
            if (dataObj.novaPoshtaData.regionHref != 0) {
                $('.modal-no-background').addClass('active');
                $.post(url, dataObj, function (data) {
                    $(".order-payment-and-delivery-item-wrapper").html(data);
                    $(".order-payment-and-delivery-item-wrapper .select-default-view").selectpicker({
                            'dropupAuto': false,
                            'size': 15,
                            'refresh': true
                        }
                    );
                    $('.modal-no-background').removeClass('active');
                });
            }
            else {
                $('.modal-no-background').addClass('active');
                $.post(url, {}, function (data) {
                    $(".order-payment-and-delivery-item-wrapper").html(data);
                    $(".order-payment-and-delivery-item-wrapper .select-default-view").selectpicker({
                            'size': 15,
                            'dropupAuto': false,
                            'refresh': true
                        }
                    );
                    $('.modal-no-background').removeClass('active');
                });
            }
            return false;
        });
        ///*******************************new-post form on city change************************************************////
        $('.order-payment-and-delivery-item-wrapper').on('change', ".new-post-select-city", function (e) {
            e.preventDefault();
            var url = $('.order-payment-and-delivery-item-wrapper').attr('data-url');
            var dataObj = {};
            dataObj.novaPoshtaData = {};
            dataObj.novaPoshtaData.regionHref = $('.new-post-select-region').find('select').val();
            dataObj.novaPoshtaData.cityHref = $(this).val();
            if (dataObj.novaPoshtaData.cityHref != 0) {
                $('.modal-no-background').addClass('active');
                $.post(url, dataObj, function (data) {
                    $(".order-payment-and-delivery-item-wrapper").html(data);
                    $(".order-payment-and-delivery-item-wrapper .select-default-view").selectpicker({
                            'size': 15,
                            'refresh': true,
                            'dropupAuto': false,
                        }
                    );
                    $('.modal-no-background').removeClass('active');
                });
            }
            else {
                delete dataObj.novaPoshtaData.cityHref;
                $('.modal-no-background').addClass('active');
                $.post(url, dataObj, function (data) {
                    $(".order-payment-and-delivery-item-wrapper").html(data);
                    $(".order-payment-and-delivery-item-wrapper .select-default-view").selectpicker({
                            'size': 15,
                            'refresh': true,
                            'dropupAuto': false
                        }
                    );
                    $('.modal-no-background').removeClass('active');
                });
            }
            return false;
        });
    }
//    ****************************end-new-post-actions****************************************************************//
    $('body').on('change','.global-cart-element-feature select',function(){
        saveTempForm();
        // $.post(url, dataObj, function (data) {
        // });
    });
    $('body').on('click','.global-cart-checkbox-wrapper input',function(){
        saveTempForm();
    });
    function saveTempForm() {
        var form = $('.global-cart-items-container form');
        var data_href_url = form.attr('data-href-save-order');
        var dataObj = form.serialize();
        $.ajax({
            type: 'POST',
            url: data_href_url,
            data: dataObj,
            dataType: 'JSON',
            success: function (response) {
            }
        });
    }
//    ***************************************************************************************************************//
    $(document).on('blur','.error',function(){
        $(this).removeClass('error');
    });
});