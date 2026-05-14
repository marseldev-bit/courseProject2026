'use strict';

let delivery = document.querySelector('.orderDelivery');
let pickup = document.querySelector('.orderPickup');
let deliveryButton = document.getElementById('delivery');
let pickupButton = document.getElementById('pickup');
let isPickup = false;

pickupButton.addEventListener('click', () => {
    if(!isPickup) {
        delivery.style.display = 'none';
        pickup.style.display = 'block';

        pickupButton.style.background = '#4E3822';
        pickupButton.style.color = '#FFF8E4';
        deliveryButton.style.background = '#FFF8E4';
        deliveryButton.style.color = '#4E3822';
        isPickup = true;
    }
})

deliveryButton.addEventListener('click', () => {
    if(isPickup) {
        pickup.style.display = 'none';
        delivery.style.display = 'block';

        deliveryButton.style.background = '#4E3822';
        deliveryButton.style.color = '#FFF8E4';
        pickupButton.style.background = '#FFF8E4';
        pickupButton.style.color = '#4E3822';
        isPickup = false;
    }
})