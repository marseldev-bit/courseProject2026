'use strict';

let burgerLabel = document.querySelector('.headerBlock label');
let burgerMenu = document.querySelector('.burgerMenu');
let isBurgerOpen = false;

burgerLabel.addEventListener('click', () => {
    if(!isBurgerOpen) {
        burgerLabel.style.content = "url('../assets/images/burgerCloseTablet.png')";
        burgerMenu.style.display = 'flex';
        isBurgerOpen = true;
    }
    else {
        burgerLabel.style.content = "url('../assets/images/burgerTablet.png')";
        burgerMenu.style.display = 'none';
        isBurgerOpen = false;
    }
})