// Carousel
const carousel = document.querySelector('.carousel');
const carouselItems = document.querySelectorAll('.carousel-item');
const carouselIndicators = document.querySelector('.carousel-indicators');
const prevButton = document.querySelector('.prev-button');
const nextButton = document.querySelector('.next-button');

let currentIndex = 0;

function updateCarousel() {
    carouselItems.forEach((item, index) => {
        item.style.display = index === currentIndex ? 'block' : 'none';
    });

    carouselIndicators.querySelectorAll('li').forEach((indicator, index) => {
        indicator.classList.toggle('active', index === currentIndex);
    });
}

prevButton.addEventListener('click', () => {
    currentIndex = (currentIndex - 1 + carouselItems.length) % carouselItems.length;
    updateCarousel();
});

nextButton.addEventListener('click', () => {
    currentIndex = (currentIndex + 1) % carouselItems.length;
    updateCarousel();
});

updateCarousel();