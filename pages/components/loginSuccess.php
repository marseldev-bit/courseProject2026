<div class="loginSuccess regSuccess">
    <button>⨉</button>
    <h1>Добро пожаловать, <?= $USER['name'] ?>!</h1>
    <p>Удачных покупок</p>
</div>

<script>
    let close = document.querySelector('.regSuccess button');
    let regSuccess = document.querySelector('.regSuccess');

    close.addEventListener('click', () => {
        regSuccess.classList.add('hide');
    })
</script>