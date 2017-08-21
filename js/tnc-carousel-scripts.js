$ = jQuery.noConflict();
$(document).ready(adjustCarouselHeight);
$(window)
        .resize(adjustCarouselHeight)
        .load(adjustCarouselHeight);

function adjustCarouselHeight() {
    $navbar_height = 0;
    if ($('.navbar-fixed-top').length) {
        $navbar_height = $('.header-above .navbar, .header .navbar').outerHeight();
        if ($(window).width() < 768 && $('.in').length) {
            $navbar_height = $('.navbar-header').outerHeight();
        }
        $('body').css('padding-top', $navbar_height);
    }

    /*always make footer align below*/
    var $window_height = $(window).height();
    var total_height = 0;
    var $content_height = 0;

    total_height = $('.header-above').outerHeight();
    total_height = total_height + $('.header').outerHeight();
    total_height = total_height + $('.header-below').outerHeight();
//    total_height = total_height + $('.footer').outerHeight();
//    total_height = total_height + $('.footer-copyright').outerHeight();
    total_height = total_height + $navbar_height;


    $content_height = $window_height - total_height;
    if($(window).width()<990){
        $('.carousel .item').css('min-height', $content_height);
    }else{
        $('.carousel .item').css('min-height','');
    }
}