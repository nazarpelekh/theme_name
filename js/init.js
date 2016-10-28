$ = jQuery;
$(document).ready(function () {
    
    /* Contact Form 7 */
    $(this).on('click', '.wpcf7-not-valid-tip', function(){
        $(this).prev().trigger('focus');
        $(this).fadeOut(500,function(){
            $(this).remove();
        });
    });

    /* Swiper Slider */
    var swiper = new Swiper('.swiper-container', {
        pagination: '.swiper-pagination',
        nextButton: '.swiper-button-next',
        prevButton: '.swiper-button-prev',
        paginationClickable: true,
        spaceBetween: 10,
        loop: true
    });

    /* Resize Menu */
    $(window).bind('resize orientationchange', function () {
        if ($(window).width() >= 768 ) {
            $('#menu-main-menu').removeAttr('style');
        }
    });

    /* Respond Menu */
    $('#menuOpen').click(function() {
        $(this).toggleClass('active');
        $('#mainMenu').toggleClass('active');
        $('body').css('overflow','hidden');
    });
    
    /* Respond Menu */
    if ($("body").width() < 1024) {
    $("#mainMenu .menu-item-has-children > a").append("<span></span>");
    $("#mainMenu .menu-item-has-children span").click(function() {
        $(this).parent().next().slideToggle(300);
        $(this).toggleClass("active");
        return false;
    });
}

});
