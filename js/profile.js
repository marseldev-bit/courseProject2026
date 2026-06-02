'use strict';

// Модальное окно выхода из аккаунта
let modalExit = document.querySelector('.modalExit');
let exitBtn = document.querySelector('.profileOptions button:last-child');
let closeModalExit = [document.querySelector('.modalExitWindow p'), document.querySelector('.modalExitOptions button:last-child')];

exitBtn.addEventListener('click', () => {
    modalExit.classList.add('active');
})

closeModalExit.forEach((close) => {
    close.addEventListener('click', () => {
        modalExit.classList.remove('active');
    })
})

// Модальное окно редактирования аватара
let avatarInput = document.querySelector('.avatarInput input');
let avatarName = document.querySelector('.avatarInput h2');

avatarInput.addEventListener('change', function() {
    if(this.files.length > 0) avatarName.textContent = this.files[0].name;
    else avatarName.textContent = 'Файл не выбран';
})
