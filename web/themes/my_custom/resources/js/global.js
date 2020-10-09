(function ($) {

    'use strict';

    Drupal.behaviors.my_custom = {
        attach: function (context, settings) {


            $(window).scroll(function () {
                if ($(this).scrollTop() > 100) {
                    $('#scroller').fadeIn();
                } else {
                    $('#scroller').fadeOut();
                }
            });
            $('#scroller').click(function () {
                $('body,html').animate({
                    scrollTop: 0
                }, 400);
                return false;
            });


            $(".main_menu_share__dropdown").hide();
            $(".main_menu_share__btn_share").mouseover(function () {
                $(".main_menu_share__dropdown").slideDown("slow");
            });
            $(".main_menu_share").mouseleave(function () {
                $(".main_menu_share__dropdown").slideUp("slow");
            });


        }
    };

})(jQuery);
