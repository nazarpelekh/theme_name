$ = jQuery;
$(document).ready(function () {

    /* Swiper Slider */
    var swiper = new Swiper('.swiper-container', {
        pagination: '.swiper-pagination',
        nextButton: '.swiper-button-next',
        prevButton: '.swiper-button-prev',
        paginationClickable: true,
        spaceBetween: 10,
        loop: true
    });

    /* Respond view */
    $(function() {
        FastClick.attach(document.body);
    });

    /* Mob Menu */
    $("#menuOpen").click(function () {
        $(this).toggleClass('active');
        $('#menu-main-menu').stop().slideToggle(400);
        return false;
    });

    /* Resize Menu */
    $(window).bind('resize orientationchange', function () {
        if ($(window).width() >= 768 ) {
            $('#menu-main-menu').removeAttr('style');
        }
    });

});
