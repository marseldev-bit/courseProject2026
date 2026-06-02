'use strict';

const questions = document.querySelectorAll('.faq .questionBlock');
questions.forEach((question) => {
    question.addEventListener('click', (event) => {
        if(event.target.closest('.question')) {
            question.classList.toggle('active');
        }
    })
})

const headerModalBtn = document.querySelector('header .options a:last-child');
const headerModal = document.querySelector('.headerModal');

headerModalBtn.addEventListener('click', () => {
    headerModal.classList.toggle('active');
    headerModalBtn.classList.toggle('active');
}) 


// Фильтрация каталога
let cards = document.querySelectorAll('.mainPage .catalogBlock .card');
let category = document.querySelector('.mainPage .catalogHeader select');

category.addEventListener('change', function() {
    cards.forEach((c) => { c.classList.remove('disable'); })
    if(category.value != 'Все') {
        cards.forEach((card) => { 
            if(card.querySelector('.item .price p').textContent != category.value) card.classList.add('disable');
        })
    }
})


// Слайдер акций
let slider = document.querySelector('.mainPage .promotions');
let forward = document.querySelector('.mainPage .forward');
let back = document.querySelector('.mainPage .back');
let currentIndex = 0;

function slide(index) {
    if(index < 0) index = 0;
    else if(index >= slider.children.length) index = slider.children.length - 1;
    currentIndex = index;

    let translate = document.querySelector('.mainPage .prom').clientWidth + 40;
    slider.style.transform = `translateX(-${translate * index}px)`;
}

back.addEventListener('click', () => {
    slide(currentIndex - 1);
})

forward.addEventListener('click', () => {
    slide(currentIndex + 1);
})