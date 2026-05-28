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