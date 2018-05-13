jQuery(document).ready(function ($) {

    $('.seoregions__active').on('click', function (event) {
        $(this).next('.seoregions__hidden').toggleClass('seoregions__hidden-active')
        $(this).parent('.seoregions').toggleClass('seoregions__hidden-active')
        event.stopPropagation()
    })

    $('body').on('click', function () {
        $('.seoregions__hidden').removeClass('seoregions__hidden-active')
        $('.seoregions').removeClass('seoregions__hidden-active')
    })

})
