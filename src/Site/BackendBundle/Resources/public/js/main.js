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
});