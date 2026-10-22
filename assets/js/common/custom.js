//---------Menu Toggle-----------//

jQuery(document).ready(function(){
  jQuery('.slide-in-open, .slide-in-close').click(function(){
      jQuery(this).toggleClass('open');
      jQuery('.slide-in-menu-container').toggleClass('slide-in-menu');
      jQuery('body').toggleClass('menu-open');
  });
});


/* slick*/
jQuery(document).ready(function(){//.ct-testimonial-block .ct-content
    jQuery('.testimonial-slider .ct-content').slick({
     	slidesToShow: 1,
        slidesToScroll: 1,
        centerMode: true,
        centerPadding: '0px',
        speed: 1000,              // Transition speed (0.7 sec)
        autoplay: false,
        autoplaySpeed: 4000,     // Time between slides (4 sec)
        arrows: false,
        dots: true,
        pauseOnHover: true,
    });
});


jQuery(document).ready(function() {
  jQuery('.popup-gallery-list').magnificPopup({
    delegate: 'a',
    type: 'image',
    tLoading: 'Loading image #%curr%...',
    mainClass: 'mfp-img-mobile',
    closeOnBgClick:true, 
    closeMarkup: '<button title="%title%" type="button" class="mfp-close custom-close-svg" aria-label="Close"><svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" viewBox="0 0 256 256"><path d="M205.66,194.34a8,8,0,0,1-11.32,11.32L128,139.31,61.66,205.66a8,8,0,0,1-11.32-11.32L116.69,128,50.34,61.66A8,8,0,0,1,61.66,50.34L128,116.69l66.34-66.35a8,8,0,0,1,11.32,11.32L139.31,128Z"/></svg></button>',
    gallery: {
      enabled: true,
      navigateByImgClick: false,
      preload: [0,1] ,// Will preload 0 - before current, and 1 after the current image
      arrowMarkup: '<button title="%title%" type="button" class="mfp-arrow mfp-arrow-%dir%"><svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" viewBox="0 0 256 256"><path d="M221.66,133.66l-72,72a8,8,0,0,1-11.32-11.32L196.69,136H40a8,8,0,0,1,0-16H196.69L138.34,61.66a8,8,0,0,1,11.32-11.32l72,72A8,8,0,0,1,221.66,133.66Z"></path></svg></button>',
    },
    image: {
      tError: '<a href="%url%">The image #%curr%</a> could not be loaded.',
      titleSrc: function(item) {
        //return item.el.attr('title');
      }
    }
  });
});

