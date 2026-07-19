$(document).ready(function(){
    
   $(".open_mob_menu").click(function(){
       $("body").addClass("show_menu");
       return false;
   });
   $(".close_mob_menu").click(function(){
       $("body").removeClass("show_menu");
       return false;
   });

    //////////////////////////////////////////////////////////////////////////////////////////

    if ($("#main .wrap.slider").length > 0) {
        $("#main .wrap.slider").addClass("owl-carousel").owlCarousel({
            dots: true,
            autoplay: false,
            autoplayTimeout: 10000,
            autoplayHoverPause: true,
            nav: false,
            items: 1,
            loop: true,
            autoplaySpeed: 1000,
            dotsSpeed: 1000,
            margin: 0
        });
    }

    //////////////////////////////////////////////////////////////////////////////////////////

    // включаеться только на десктопе
    function CatalogCarousel() {
        var checkWidth = $(window).width();
        var owlPost = $("#catalog .slider_wrap .list");
        if (checkWidth <= 768) {
            owlPost.each(function () {
                if (typeof $(this).data('owl.carousel') != 'undefined') {
                    $(this).data('owl.carousel').destroy();
                }
                $(this).removeClass('owl-carousel');
            });
        } else {
            owlPost.each(function () {
                $(this).addClass('owl-carousel');
                $(this).owlCarousel({
                    dots: true,
                    autoplay: false,
                    autoplayTimeout: 10000,
                    autoplayHoverPause: true,
                    nav: false,
                    items: 2,
                    loop: true,
                    autoplaySpeed: 1000,
                    dotsSpeed: 1000,
                    margin: 10
                });
            });
        }
    }

    if ($("#catalog .slider_wrap").length > 0) {
        CatalogCarousel();
        $(window).resize(CatalogCarousel);
    }


    // включаеться только на мобильном
    function CatalogMobileCarousel() {
        var checkWidth = $(window).width();
        var owlPost = $("#catalog .mobile_slider_wrap .list");
        if (checkWidth > 768) {
            owlPost.each(function () {
                if (typeof $(this).data('owl.carousel') != 'undefined') {
                    $(this).data('owl.carousel').destroy();
                }
                $(this).removeClass('owl-carousel');
            });
        } else {
            owlPost.each(function () {
                $(this).addClass('owl-carousel');
                $(this).owlCarousel({
                    dots: true,
                    autoplay: false,
                    autoplayTimeout: 10000,
                    autoplayHoverPause: true,
                    nav: false,
                    items: 1,
                    loop: true,
                    autoplaySpeed: 1000,
                    dotsSpeed: 1000,
                    margin: 10,
                });
            });
        }
    }

    if ($("#catalog .mobile_slider_wrap").length > 0) {
        CatalogMobileCarousel();
        $(window).resize(CatalogMobileCarousel);
    }

    //////////////////////////////////////////////////////////////////////////////////////////

    if ($("#works .slider_wrap").length > 0) {
        $("#works .slider_wrap").addClass("owl-carousel").owlCarousel({
            dots: true,
            autoplay: false,
            autoplayTimeout: 10000,
            autoplayHoverPause: true,
            nav: false,
            items: 1,
            loop: true,
            autoplaySpeed: 1000,
            dotsSpeed: 1000,
            margin: 10,
            responsive:{
                0:{
                    items: 1,
                    center: false
                },
                768:{
                    items: 2,
                    center: false
                },
                1024:{
                    items: 3,
                    center: false
                },
                1280:{
                    items: 3,
                    center: true
                }
            }
        });
    }

    //////////////////////////////////////////////////////////////////////////////////////////

    $("#faq .item .tit").click(function(){
        $(this).parents(".item").toggleClass("open");
    });

    //////////////////////////////////////////////////////////////////////////////////////////

    $("#gallery .tabs_wrap a").click(function(){
        $(this).parents(".tabs_wrap").find("a").removeClass("_active");
        $(this).addClass("_active");

        var slug = $(this).data("slug");

        if(slug == "all"){
            $(this).parents("#gallery").find(".gallery_wrap .item").addClass("_active");
        }else{
            $(this).parents("#gallery").find(".gallery_wrap .item").removeClass("_active");
            $(this).parents("#gallery").find(".gallery_wrap .item.cat_"+slug).addClass("_active");
        }
        return false;
    });

    $("#gallery .gallery_wrap .item a.show_galley").click(function(){
        var galleryID = $(this).data("gallery");
        var list = catalog[galleryID];

        Fancybox.show(list, {
            Toolbar: {
                display: [
                    "close"
                ]
            }
        });

        return false;
    });

    $("#works .item a.show_galley").click(function(){
        var galleryID = $(this).data("gallery");
        var list = catalog[galleryID];

        Fancybox.show(list, {
            Toolbar: {
                display: [
                    "close"
                ]
            }
        });

        return false;
    });

    //////////////////////////////////////////////////////////////////////////////////////////

    $("#catalog .wrap a.show_galley").click(function(){
        var galleryID = $(this).data("gallery");
        var list = catalog[galleryID];

        var startIndex = $(this).data("index");
        console.log(startIndex);

        Fancybox.show(list, { 
            "startIndex": startIndex,
            Toolbar: {
                display: [
                    "close"
                ]
            }
        });

        return false;
    });


    //////////////////////////////////////////////////////////////////////////////////////////

    

    Fancybox.bind("[data-fancybox]", {
        Toolbar: {
            display: [
                "close",
            ],
        },
        Thumbs: {
            autoStart: false,
        },
    });

    // //////////////////////////////////////////////////////////////////////////////////////////

   $(document).mouseup(function (e) {
       var container = $("#call_form");
       var openLink = $(".open_callform");
       var closeLink = $(".close_call");

       openLink.click(function () {
           $("body").addClass("open_call_form");
           return false;
       });
       closeLink.click(function () {
           $("body").removeClass("open_call_form");
           return false;
       });

       if (container.find(".form_content").has(e.target).length === 0) {
           $("body").removeClass("open_call_form");
       }
   });


    //////////////////////////////////////////////////////////////////////////////////////////

});