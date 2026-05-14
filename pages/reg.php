<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../assets/images/favicon.png" type="image/x-icon">
    <link rel="stylesheet" href="style.css">
    <title>Кинг</title>
</head>

<body class="regPage">
    <!-- Шапка -->
    <?php include('header.php'); ?>
    <!-- Конец шапки -->

    <main>
        <!-- Форма регистрации -->
        <h1 class="title">Регистрация</h1>
        <form class="reg">
            <div class="field">
                <label>Как Вас зовут?</label>
                <input type="text" placeholder="Введите имя...">
            </div>

            <div class="field">
                <label>Укажите электронную почту</label>
                <input type="text" placeholder="chess@gmail.com">
            </div>

            <div class="field">
                <label>Укажите Ваш пол</label>
                <select>
                    <option disabled selected>Не указан</option>
                    <option>Мужской</option>
                    <option>Женский</option>
                </select>
            </div>

            <div class="field">
                <label>Придумайте пароль</label>
                <input type="password" placeholder="Введите пароль...">
            </div>

            <div class="field">
                <label>Повторите пароль</label>
                <input type="password" placeholder="Введите пароль...">
            </div>

            <div class="regButtons">
                <button type="reset">Сбросить</button>
                <button type="submit">Подтвердить</button>
            </div>
        </form>
        <p>Есть аккаунт? <a href="login.php">Войти</a></p>
        <!-- Конец формы регистрации -->
    </main>

    <!-- Подвал -->
    <?php include('footerBlock.php'); ?>
    <!-- Конец подвала -->
</body>

</html>