'use strict';

let stars = document.querySelectorAll('.stars .star');
let rate = document.querySelector('.rating input');

stars.forEach((star, i) => {
    star.addEventListener('mouseenter', () => {
        if(!rate.value) {
            stars.forEach((s, j) => {
                if(j <= i) s.classList.add('active');
            })
        }
    })
})

stars.forEach((star, i) => {
    star.addEventListener('mouseleave', () => {
        if(!rate.value) {
            stars.forEach((s, j) => {
                s.classList.remove('active');
            })
        }
    })
})

if(rate.value) {
    stars.forEach((star) => {
        star.classList.remove('active');
    }) 
    stars.forEach((star, i) => {
        if(i < +rate.value) star.classList.add('active');
    }) 
}

stars.forEach((star, i) => {
    star.addEventListener('click', () => {
        rate.value = i + 1;
        stars.forEach((s, j) => {
            s.classList.remove('active');
        })
        stars.forEach((s, j) => {
            if(j <= i) s.classList.add('active');
        })
    })
})
