'use strict';

// Слайдер
let sliderItem = document.querySelector('.itemPage .itemSlides');
let slidesBlock = document.querySelector('.itemPage .itemImagesBlock');
let slides = document.querySelectorAll('.itemPage .itemImages img');
let Back = document.querySelector('.itemPage .back');
let Forward = document.querySelector('.itemPage .forward');
let current = 0;
slides[0].classList.add('active');

function slide(index) {
    if (index >= slides.length) index = slides.length - 1;
    if (index < 0) index = 0;
    current = index;

    let percentage = 100 / slides.length;
    sliderItem.style.transform = `translateX(-${percentage * index}%)`;
    if(index > 3) slidesBlock.style.transform = `translateX(-${120 * (index - 3)}px)`;
    else slidesBlock.style.transform = `translateX(0)`;

    document.querySelector('.itemPage .itemImages img.active').classList.remove('active');
    slides[index].classList.add('active');
}

slides.forEach((s, i) => {
    s.addEventListener('click', () => {
        slide(i);
    })
})
Back.addEventListener('click', () => slide(current - 1))
Forward.addEventListener('click', () => slide(current + 1))