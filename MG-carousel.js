  // Carousel navigation3 in Moods & Genre Page
  const next3 = document.querySelector('.carousel-container3 .btn-next');
  const previous3 = document.querySelector('.carousel-container3 .btn-prev');
  const carouselRow3 = document.querySelector('.carousel-container3 .carousel-row');
  const carouselWidth3 = document.querySelector('.carousel-container3').offsetWidth;

  next3.addEventListener('click', () => {
    carouselRow3.style.transform = `translateX(-${carouselWidth3}px)`;
  })
  previous3.addEventListener('click', () => {
    carouselRow3.style.transform = `translateX(0)`;
  })
