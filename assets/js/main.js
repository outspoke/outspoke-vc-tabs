jQuery(document).ready(function($){
  $('.outspoke-tabs-nav').slick({
    slidesToShow: $('.outspoke-tabs-nav').data('slides-to-show'),
    slidesToScroll: 1,
    asNavFor: '.outspoke-tabs',
    arrows: true,
    dots: false,
    focusOnSelect: true,
    swipeToSlide: true,
    centerMode: false,
    responsive: [
      {
        breakpoint: 1000,
        settings: {
          slidesToShow: 3,
          centerMode: true,
          centerPadding: 0,
        },
      },
      {
        breakpoint: 690,
        settings: {
          slidesToShow: 2,
        }
      },
      {
        breakpoint: 480,
        settings: {
          slidesToShow: 1,
        }
      }
    ],
  });

  $('.outspoke-tabs').slick({
    slidesToShow: 1,
    slidesToScroll: 1,
    asNavFor: '.outspoke-tabs-nav',
    arrows: false,
    dots: false,
    adaptiveHeight: true,
    draggable: false,
  });
});
