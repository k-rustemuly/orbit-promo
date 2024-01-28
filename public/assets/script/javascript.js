/* TABS CODE SETTINGS START: */

// jQuery(".table-block .table-tabs-content > div").hide();
// jQuery(".table-block .table-tabs-buttons a:first").toggleClass("active");
// jQuery(".table-block .table-tabs-content div:first").fadeIn();

// jQuery('.table-block .table-tabs-buttons a').click(function(e) {
//     e.preventDefault();        
//     jQuery(".table-block .table-tabs-content > div").hide();
//     jQuery(".table-block .table-tabs-buttons a").removeClass("active");
//     jQuery(this).toggleClass("active");
//     jQuery('#' + jQuery(this).attr('name')).fadeIn();
// });

/* TABS CODE SETTINGS END. */


/* QUESTIONS CODE SETTINGS START: */

jQuery('.question-item').click(function() {
    jQuery(this).find('img').toggleClass('active-image');
    jQuery(this).find('p').slideToggle();
})

// jQuery('.card .head').click(function(){
//     jQuery(this).siblings('.body').find('.block').toggleClass('hidden');
//     jQuery(this).siblings('.body').find(' > p').toggleClass('hidden');
// })


jQuery('.present-slider').owlCarousel({
    loop: true,
    touchDrag: true,
    mouseDrag: true,
    nav: false,
    dots: false,
    autoWidth: true,
    autoHeight: false,
    autoplay: false,
    smartSpeed: 2000,
    margin: 40,
    items: 3,
})

let owlPresentSlider = jQuery('.present-slider');
owlPresentSlider.owlCarousel();

jQuery('.logotype-slider').owlCarousel({
    loop: false,
    touchDrag: true,
    mouseDrag: true,
    nav: false,
    dots: false,
    autoWidth: false,
    autoHeight: false,
    autoplay: false,
    smartSpeed: 2000,
    margin: 40,
    responsive : {
    	0 : {
	        items : 2,
	    },
	    800 : {
	        items : 4,
	    }
    }
})

let owlLogotypeSlider = jQuery('.logotype-slider');
owlLogotypeSlider.owlCarousel();
