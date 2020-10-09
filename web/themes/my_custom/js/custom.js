(function ($, Drupal) {

    // 'use strict';

    Drupal.behaviors.my_custom = {
        attach: function (context, settings) {
            $('.block-views-blockfrontpage-block-7 .view-content').slick();
        }
    };

})(jQuery, Drupal);
// (function ($) {
//     $('.block-views-blockfrontpage-block-7 .view-content').slick();
// })(jQuery);