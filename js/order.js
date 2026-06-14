'use strict';

let quanity = document.getElementById('quanity');
let text = document.querySelector('.orderPage .cartItemQuanity h3');
let decrease = document.getElementById('decrease');
let increase = document.getElementById('increase');
let itemPrice = document.querySelector('.orderPage .cartItemPrice p');
let totalCost = document.querySelector('.orderPage .confirmOrder p span');
let price = parseInt(itemPrice.textContent);

decrease.addEventListener('click', () => {
    if(quanity.value > 1) {
        text.textContent = +text.textContent - 1;
        quanity.value = +quanity.value - 1;
        itemPrice.textContent = (price * +quanity.value) + '₽';
        totalCost.textContent = (price * +quanity.value) + '₽';
    }
})

increase.addEventListener('click', () => {
    text.textContent = +text.textContent + 1;
    quanity.value = +quanity.value + 1;
    itemPrice.textContent = (price * +quanity.value) + '₽';
    totalCost.textContent = (price * +quanity.value) + '₽';
})