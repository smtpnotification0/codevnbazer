const track = document.querySelector('.carousel__track');
const slides = Array.from(track.children);
const nextButton = document.querySelector('.carousel__next');
const prevButton = document.querySelector('.carousel__prev');
const dotsNav = document.querySelector('.carousel__pagination');
const dots = Array.from(dotsNav.children);
const autoSlideTime= 3000;

const slideWidth = slides[0].getBoundingClientRect().width;

const setSlidePosition = (slide, index) => {
  // slide.style.left = slideWidth * index + 'px';
};
slides.forEach(setSlidePosition);

const moveToSlide = (track, currentSlide, targetSlide, direction) => {
  const targetIndex = slides.findIndex(slide => slide === targetSlide);
  const amountToMove = slideWidth * targetIndex;

  track.style.transition = 'transform 0.5s ease-in-out';
  track.style.transform = 'translateX(-' + amountToMove + 'px)';

  currentSlide.classList.remove('current-slide');
  targetSlide.classList.add('current-slide');
};

const updateDots = (currentDot, targetDot) => {
  currentDot.classList.remove('carousel__pagination-button--active');
  targetDot.classList.add('carousel__pagination-button--active');
};

slides[0].classList.add('current-slide');
dots[0].querySelector('button').classList.add('carousel__pagination-button--active');

const moveToNextSlide = () => {
  const currentSlide = track.querySelector('.current-slide');
  let nextSlide = currentSlide.nextElementSibling;

  if (!nextSlide) {
    nextSlide = slides[0];
  }

  const currentDot = dotsNav.querySelector('.carousel__pagination-button--active');
  let nextDot = currentDot.parentElement.nextElementSibling?.querySelector('button');
  if (!nextDot) {
    nextDot = dots[0].querySelector('button');
  }

  moveToSlide(track, currentSlide, nextSlide, 'right');
  updateDots(currentDot, nextDot);
};

let autoSlideInterval = setInterval(moveToNextSlide, autoSlideTime);

const resetAutoSlide = () => {
  clearInterval(autoSlideInterval);
  autoSlideInterval = setInterval(moveToNextSlide, autoSlideTime);
};

// When I click left, move slides to the left
prevButton.addEventListener('click', e => {
  const currentSlide = track.querySelector('.current-slide');
  let prevSlide = currentSlide.previousElementSibling;

  if (!prevSlide) {
    prevSlide = slides[slides.length - 1]; 
  }

  const currentDot = dotsNav.querySelector('.carousel__pagination-button--active');
  let prevDot = currentDot.parentElement.previousElementSibling?.querySelector('button');
  if (!prevDot) {
    prevDot = dots[dots.length - 1].querySelector('button');
  }

  moveToSlide(track, currentSlide, prevSlide, 'left');
  updateDots(currentDot, prevDot);
  resetAutoSlide();
});

nextButton.addEventListener('click', e => {
  moveToNextSlide();
  resetAutoSlide();
});

dotsNav.addEventListener('click', e => {
  const targetDot = e.target.closest('button');

  if (!targetDot) return;

  const currentSlide = track.querySelector('.current-slide');
  const currentDot = dotsNav.querySelector('.carousel__pagination-button--active');
  const targetIndex = dots.findIndex(dot => dot === targetDot.parentElement);
  const targetSlide = slides[targetIndex];

  moveToSlide(track, currentSlide, targetSlide);
  updateDots(currentDot, targetDot);
  resetAutoSlide();
});