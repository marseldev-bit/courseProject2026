'use strict';

const orderOptions = document.querySelectorAll('.orderOptions button');
const orderForms = document.querySelectorAll('.orderForm');

orderOptions.forEach((option, index) => {
    option.addEventListener('click', () => {
        document.querySelector('.orderOptions button.active').classList.remove('active');
        option.classList.add('active');
        document.querySelector('.orderForm.active').classList.remove('active');
        orderForms[index].classList.add('active');
    })
})