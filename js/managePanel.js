'use strict';

if(document.querySelector('.managePromotions')) {
    document.querySelector('.manageOptions a.active').classList.remove('active');
    document.getElementById('managePromotions').classList.add('active');
}
else if(document.querySelector('.manageReviews')) {
    document.querySelector('.manageOptions a.active').classList.remove('active');
    document.getElementById('manageReviews').classList.add('active');
}
else if(document.querySelector('.manageOrders')) {
    document.querySelector('.manageOptions a.active').classList.remove('active');
    document.getElementById('manageOrders').classList.add('active');
}
else if(document.querySelector('.manageCategories')) {
    document.querySelector('.manageOptions a.active').classList.remove('active');
    document.getElementById('manageCategories').classList.add('active');
}
else if(document.querySelector('.manageitems')) {
    document.querySelector('.manageOptions a.active').classList.remove('active');
    document.getElementById('manageitems').classList.add('active');
}


// Добавление фотографий товару и акции
let imagesInput = document.querySelector('.itemField .editItemImages input');
let imagesQuanity = document.querySelector('.itemField .editItemImages p');

imagesInput.addEventListener('change', function() {
    if(this.files.length == 1) imagesQuanity.textContent = this.files[0].name;
    else if(this.files.length > 1) imagesQuanity.textContent = "Файлов выбрано: " + this.files.length;
})
