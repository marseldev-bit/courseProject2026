'use strict';

let status = document.querySelector('.editOrder .selectStatus');
if(status.value == 'Завершен') document.querySelector('.editOrder .deliveryDate').style.display = 'flex';
status.addEventListener('change', () => {
    if(status.value == 'Завершен') document.querySelector('.editOrder .deliveryDate').style.display = 'flex';
    else document.querySelector('.editOrder .deliveryDate').style.display = 'none';
})