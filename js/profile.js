'use strict';

let userOrders = document.querySelector('.userOrdersBlock');
let userReviews = document.querySelector('.userReviews');
let userReviewsTitle = document.querySelector('.userReviewsTitle');
let userOrdersButton = document.getElementById('userOrdersHistory');
let userReviewsButton = document.getElementById('userReviews');
let profileOption = '';

userOrdersButton.addEventListener('click', () => {
    if(profileOption != 'userOrders') {
        userReviewsTitle.style.display = 'none';
        userReviews.style.display = 'none';
        userOrders.style.display = 'block';
        profileOption = 'userOrders';
    }
})

userReviewsButton.addEventListener('click', () => {
    if(profileOption != 'userReviews') {
        userReviewsTitle.style.display = 'block';
        userReviews.style.display = 'flex';
        userOrders.style.display = 'none';
        profileOption = 'userReviews';
    }
})
