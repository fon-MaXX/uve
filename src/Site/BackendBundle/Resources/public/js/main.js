/**
 * Created with JetBrains PhpStorm.
 * User: dismaster
 * Date: 30.09.13
 * Time: 17:38
 * To change this template use File | Settings | File Templates.
 */

$(document).ready(function () {

    function showShadow() {
        if ($('.loader-background').length == 0)
            $('body').append('<div class="loader-background"></div>');
        $('.loader-background').fadeIn('200');
    }

    function hideShadow() {
        $('.loader-background').fadeOut('200');
    }

    function showLoader() {
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
    }

    $(".show-loader").click(function () {
        showShadow();
        showLoader();
    });
    $(".box-header").click(function () {
        $(this).parent().find(".box-body").toggleClass('displayNone');
    });
    $("button[name='btn_create_and_edit']," +
        "button[name='btn_create_and_list']," +
        "button[name='btn_create_and_create']").click(function () {
        $('.image-require').each(function () {
            var input = $(this).parent().find('input[type=hidden]');
            if (input.val().length == 0) {
                $(".message-modal-dialog").modal();
                return false;
            }
        });
    });
//--------------------------  time-picker ---------------------------------------------------//
    $('.filter-datetimepicker').datetimepicker({
        locale: 'ru',
        format: 'DD-MM-YYYY',
        showTodayButton: true,
        showClose: true,
        disabledHours: true,
        showClear: true,
        useCurrent: true,
        defaultDate: moment,
        useStrict: true,
        pickTime: false
    });
    $('.time-filter-datetimepicker').datetimepicker({
        locale: 'ru',
        format: 'DD-MM-YYYY HH:mm',
        showTodayButton: true,
        showClose: true,
        disabledHours: true,
        showClear: true,
        useCurrent: true,
        defaultDate: moment,
        useStrict: true,
    });
//******************************add-product
    $('.order-add-product-category').select2('destroy');
    $("body").on('change','.order-add-product-category',function(){
        var $url = $(this).closest('form').attr('data-url');
        var $object = {'catid':$(this).val()};
        $('.modal-body-wrapper').removeClass('active');
        $('.modal-loader').addClass('active');
        $.get($url,$object,function(data){
            $('.modal-content').html(data);
            $('.modal-loader').removeClass('active');
            $('.modal-body-wrapper').addClass('active');
        });
    });
    $('.modal-close').click(function(){
        $(this).closest('.modal-body-wrapper').removeClass('active');
        $('.modal-shaddow').removeClass('active');
        $('body').removeClass('body-no-scroll');
        $('.modal-content').html('');
    });
    if($('.add-product-to-order').length||$('.add-set-to-order').length){
        $('.add-product-to-order , .add-set-to-order').click(function(){
            var $url =$(this).attr('data-url');
            $('body').addClass('body-no-scroll');
            $('.modal-shaddow').addClass('active');
            $('.modal-loader').addClass('active');
            $.get($url,{},function(data){
                $('.modal-content').html(data);
                $('.modal-loader').removeClass('active');
                $('.modal-body-wrapper').addClass('active');
            });
        });
    }
    $('body').on('click','.squared-one label',function(){
        $(this).closest('.squared-one').find('input[type="checkbox"]').prop('checked', function( i, val ) {
            return !val;
        });
    });
    $('.phone-mask').inputmask({'mask': '+9(9999)999-99-99', 'placeholder': '_'});
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
    function numOnly(selector) {
        selector.keypress(function (b) {
            var C = /[0-9\x25\x24\x23]/;
            var a = b.which;
            var c = String.fromCharCode(a);
            return !!(a == 0 || a == 8 || a == 9 || a == 13 || c.match(C));
        });
    }
    numOnly($('.number-input-wrapper input'));
//    *******************************************************************************//
//    **************count-sum***************
    function countSum(){
        var $sum=0;
        $('.form-item-collection-element').each(function(){
            if($(this).find('[data-price]').length){
                var $elementSum = countElementSum($(this));
                if($(this).find('.delete-checkbox input').prop('checked')==false){
                    $sum+=$elementSum;
                }
            }
        });
        $('#order_price').val($sum);
    }
    function countElementSum(object){
        var $sum=0;
        if(object.find('.set-components').length){
            object.find('.collection-set').children('.set-components').each(function(){
              if($(this).find('.component-presence input').prop('checked')==true){
                  $sum+=parseInt($(this).find('[data-price]').attr('data-price'));
                }
            });
        }
        else{
            $sum = parseInt(object.find('[data-price]').attr('data-price'));
        }
        if(object.find('.final-price').length){
            object.find('.final-price').html($sum);
        }
        var $num = parseInt(object.find('.number-count').val());
        return $num*$sum;
    }
    if($('.form-item-collection-element').length){
        countSum();
        $("body").on('countinputchanged','input',function(){
            countSum();
        });
        $('body').on('change','.delete-checkbox input,.component-presence input',function(){
            countSum();
        });
        $(".squared-one-input input").on('ifChanged', function (e) {
            $(this).trigger("change", e);
        });
    }
});