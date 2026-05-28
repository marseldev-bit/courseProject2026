<div class="regSuccess">
    <button>⨉</button>
    <h1>Успешная регистрация!</h1>
    <p>Теперь авторизуйтесь</p>
</div>

<script>
    let close = document.querySelector('.regSuccess button');
    let regSuccess = document.querySelector('.regSuccess');

    close.addEventListener('click', () => {
        regSuccess.classList.add('hide');
    })
</script>