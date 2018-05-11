jQuery(document).ready(function ($) {

    var $popupCity = $('.seoregions__hidden')

    $('.seoregions__active').on('click', function (event) {
        $(this).next('.seoregions__hidden').toggleClass('active')
        $(this).parent('.seoregions').toggleClass('active')
        event.stopPropagation()
    })

    $('body').on('click', function () {
        $popupCity.removeClass('active')
        $('.seoregions').removeClass('active')
    })

})
