jQuery(function ($) {
  const slidesN = $(".am-projects-gallery-swiper-wrapper").children().length;
  let currentSlide = 1;
  let isMoving = false;
  moveSlides();

  //Funzione che controlla se elemento è in viewport
  function isScrolledIntoView(el) {
    const rect = el.getBoundingClientRect();
    const elemTop = rect.top;
    const elemBottom = rect.bottom;

    //Quando elemento è completamente visibile
    const isVisible = (elemTop >= 0) && (elemBottom <= window.innerHeight);
    return isVisible;
  }
  
  //Su scroll
  $(window).scroll(function() {
    $(".am-projects-details-list-el-progress-bar-active").each(function(i) {
      //Controllo se barra progresso è in viewport
      if(isScrolledIntoView($(this)[0])) {
        //Attivo barra
        const perc = $(this).data("perc");
        $(this).css({'width' : perc});
      }
    });
  });

  //Hover tooltip
  $(".am-projects-icons-tooltip-el")
  .on('mouseenter', function() {
    $(this).addClass('hover');
  })
  .on('mouseleave', function() {
    $(this).removeClass('hover');
  });

  //Gallery 
  //Su click frecce
  $(".swipe-forward").click(function(e) {
    if(isMoving) return;
    
    currentSlide++
    isMoving = true;

    $(".am-projects-gallery-swiper-wrapper").css({"transition":"transform 0.8s ease"});
    moveSlides();
  });

  $(".swipe-back").click(function(e) {
    if(isMoving) return;

    isMoving = true;
    currentSlide--

    $(".am-projects-gallery-swiper-wrapper").css({"transition":"transform 0.8s ease"});
    moveSlides();
  });

  //Alla fine della trasizione
  $(".am-projects-gallery-swiper-wrapper").on('transitionend webkitTransitionEnd oTransitionEnd', function () {
    isMoving = false;
    
    //Vado ad ultima slide
    if(currentSlide == 0) {
      currentSlide = slidesN - 2;
      $(".am-projects-gallery-swiper-wrapper").css({"transition":"none"});
      moveSlides();
    }

    //Vado a prima slide
    if(currentSlide == slidesN - 1) {
      currentSlide = 1;
      $(".am-projects-gallery-swiper-wrapper").css({"transition":"none"});
      moveSlides();
    }
  });

  //Funzione per muovere slide
  function moveSlides() {
    $(".am-projects-gallery-swiper-wrapper").css("transform", "translateX(-" + currentSlide * 100 + "%)");
    $(".am-projects-gallery-el.active").removeClass("active");
    $(".am-projects-gallery-el:nth-child(" + (currentSlide + 1) + ")").addClass("active");
  }
});