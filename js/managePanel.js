'use strict';

function selectPanel(manPanel, arr, panelButton, arrButtons) {
    panelButton.addEventListener('click', () => {
        for(let panel of panelArray) {
            if(panel == manPanel) panel.style.display = 'block';
            else panel.style.display = 'none';
        }

        for(let button of arrButtons) {
            if(button == panelButton) {
                button.style.background = '#4E3822';
                button.style.color = '#FFF8E4';
            }
            else {
                button.style.background = '#FFF8E4';
                button.style.color = '#4E3822';
            }
        }
    })
}

let manageItems = document.querySelector('.manageItems');
let managePromotions = document.querySelector('.managePromotions');
let manageReviews = document.querySelector('.manageReviews');
let manageOrders = document.querySelector('.manageOrders');
let manageCategories = document.querySelector('.manageCategories');
let panelArray = [manageItems, managePromotions, manageReviews, manageOrders, manageCategories];

let manageItemsButton = document.getElementById('manageItems');
let managePromotionsButton = document.getElementById('managePromotions');
let manageReviewsButton = document.getElementById('manageReviews');
let manageOrdersButton = document.getElementById('manageOrders');
let manageCategoriesButton = document.getElementById('manageCategories');
let panelButtonsArray = [manageItemsButton, managePromotionsButton, manageReviewsButton, manageOrdersButton, manageCategoriesButton];

for(let i = 0; i < 5; i++) selectPanel(panelArray[i], panelArray, panelButtonsArray[i], panelButtonsArray);