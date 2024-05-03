let nextDom = document.getElementById('next');
let prevDom = document.getElementById('prev');
let carouselDom = document.querySelector('.carousel');
let sliderDom = carouselDom.querySelector('.list');
let thumbnailBorderDom = document.querySelector('.thumbnail');
let thumbnailItemsDom = thumbnailBorderDom.querySelectorAll('.item');

let currentSlide = 0;
const totalSlides = sliderDom.querySelectorAll('.item').length;
let autoSlideInterval;
const timeAutoNext = 7000; // Duration before auto slide

function showSlider(indexOrDirection) {
    stopAutoSlide(); // Stop the auto-slide before changing slides

    let index = typeof indexOrDirection === 'number' ? indexOrDirection : currentSlide;

    // Determine the new index if 'next' or 'prev' was passed
    if (indexOrDirection === 'next') {
        index = (currentSlide + 1) % totalSlides;
    } else if (indexOrDirection === 'prev') {
        index = (currentSlide - 1 + totalSlides) % totalSlides;
    }

    // Clear previous classes
    carouselDom.classList.remove('next', 'prev');

    // Add 'next' or 'prev' class based on the index
    if (index > currentSlide) {
        carouselDom.classList.add('next');
    } else if (index < currentSlide) {
        carouselDom.classList.add('prev');
    }

    currentSlide = index; // Set the currentSlide to the index calculated

    // Update active slide
    sliderDom.querySelectorAll('.item').forEach((item, idx) => {
        item.classList.toggle('active', idx === currentSlide);
    });
    thumbnailBorderDom.querySelectorAll('.item').forEach((item, idx) => {
        item.classList.toggle('active', idx === currentSlide);
    });

    startAutoSlide(); // Start the auto-slide again after changing slides
}

// Add event listeners to thumbnails
thumbnailItemsDom.forEach((thumbnail, index) => {
    thumbnail.addEventListener('click', () => {
        showSlider(index);
    });
});

// Event listeners for next and previous buttons
nextDom.addEventListener('click', () => {
    showSlider('next');
});

prevDom.addEventListener('click', () => {
    showSlider('prev');
});

function startAutoSlide() {
    autoSlideInterval = setInterval(() => {
        showSlider('next');
    }, timeAutoNext);
}

function stopAutoSlide() {
    clearInterval(autoSlideInterval);
}

// Initialize carousel with the first slide active
window.addEventListener('DOMContentLoaded', () => {
    sliderDom.children[currentSlide].classList.add('active');
    thumbnailBorderDom.children[currentSlide].classList.add('active');
    startAutoSlide();
});

// Ensure you clear the interval when the user navigates away from the page
window.addEventListener('unload', stopAutoSlide);
