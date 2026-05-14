<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../assets/images/favicon.png" type="image/x-icon">
    <link rel="stylesheet" href="style.css">
    <title>Кинг</title>
</head>

<body class="regPage loginPage">
    <!-- Шапка -->
    <?php include('header.php'); ?>
    <!-- Конец шапки -->

    <main>
        <!-- Форма авторизации -->
        <h1 class="title">Авторизация</h1>
        <form class="reg">
            <div class="field">
                <label>Электронная почта</label>
                <input type="text" placeholder="chess@gmail.com">
            </div>

            <div class="field">
                <label>Пароль</label>
                <input type="password" placeholder="Введите пароль...">
            </div>

            <div class="regButtons">
                <button type="reset">Сбросить</button>
                <button type="submit">Подтвердить</button>
            </div>
        </form>
        <p>Нет аккаунта? <a href="reg.php">Регистрация</a></p>
        <!-- Конец формы авторизации -->
    </main>

    <!-- Подвал -->
    <?php include('footerBlock.php'); ?>
    <!-- Конец подвала -->
</body>

</html>