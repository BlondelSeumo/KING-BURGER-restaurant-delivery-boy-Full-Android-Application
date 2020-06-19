$(function() {
  $('.carousel').slick({
    infinite: false,
    variableWidth: true,
    slidesToShow: 1,
  });
  
$('.carousel').on('afterChange', function(event, slick, currentSlide, nextSlide){
  console.log(currentSlide);
});

});