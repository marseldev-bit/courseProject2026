<?php
    $errors = [];

    if(isset($_POST['reg'])) {
        if(empty($_POST['name'])) $errors['name'] = "Имя не может быть пустым";
        elseif(mb_strlen($_POST['name']) > 20) $errors['name'] = "Имя должно быть короче 20 символов";

        if(empty($_POST['email'])) $errors['email'] = "Почта не может быть пустой";
        elseif(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) $errors['email'] = "Неверный формат почты";
        else {
            $sql = "SELECT id FROM users WHERE email = ?";
            $stmt = $connect->prepare($sql);
            $stmt->execute([$_POST['email']]);
            $isEmailUsed = $stmt->fetch();
            if($isEmailUsed) $errors['email'] = "Пользователь с такой почтой уже зарегистрирован";
        }
        if(!isset($_POST['sex'])) $errors['sex'] = "Укажите Ваш пол";
        if(empty($_POST['password'])) $errors['password'] = "Пароль не может быть пустым";
        elseif(mb_strlen($_POST['password']) < 6) $errors['password'] = "Пароль должен содержать не менее 6 символов";
        if(empty($_POST['passwordConfirm'])) $errors['passwordConfirm'] = "Повторите пароль";
        elseif($_POST['password'] != $_POST['passwordConfirm']) $errors['passwordConfirm'] = "Пароли не совпадают";
        elseif(empty($errors)) {
            $hash = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $sql = "INSERT INTO users (name, email, password, sex) VALUES (?, ?, ?, ?)";
            $stmt = $connect->prepare($sql);
            $stmt->execute([$_POST['name'], $_POST['email'], $hash, $_POST['sex']]);
            echo '<script>location.href="?page=login&success"</script>';
        }
    }
?>

<body class="regPage">
    <!-- Шапка -->
    <?php include('pages/components/header.php'); ?>
    <!-- Конец шапки -->

    <main>
        <!-- Форма регистрации -->
        <h1 class="title">Регистрация</h1>
        <form class="reg" method="post">
            <?php if(isset($errors['name'])) { ?>
            <div class="field errorField">
                <label>Как Вас зовут?</label>
                <div class="error">
                    <input type="text" placeholder="Введите имя..." name="name">
                    <p><?= $errors['name'] ?></p>
                </div>
            </div>
            <?php } else { ?>
            <div class="field">
                <label>Как Вас зовут?</label>
                <input type="text" placeholder="Введите имя..." name="name">
            </div>
            <?php } ?>

            <?php if(isset($errors['email'])) { ?>
            <div class="field errorField">
                <label>Укажите электронную почту</label>
                <div class="error">
                    <input type="text" placeholder="chess@gmail.com" name="email">
                    <p><?= $errors['email'] ?></p>
                </div>
            </div>
            <?php } else { ?>
            <div class="field">
                <label>Укажите электронную почту</label>
                <input type="text" placeholder="chess@gmail.com" name="email">
            </div>
            <?php } ?>

            <?php if(isset($errors['sex'])) { ?>
            <div class="field errorField">
                <label>Укажите Ваш пол</label>
                <div class="error">
                    <select name="sex">
                    <option disabled selected>Не указан</option>
                    <option>Мужской</option>
                    <option>Женский</option>
                </select>
                    <p><?= $errors['sex'] ?></p>
                </div>
            </div>
            <?php } else { ?>
            <div class="field">
                <label>Укажите Ваш пол</label>
                <select name="sex">
                    <option disabled selected>Не указан</option>
                    <option>Мужской</option>
                    <option>Женский</option>
                </select>
            </div>
            <?php } ?>

            <?php if(isset($errors['password'])) { ?>
            <div class="field errorField">
                <label>Придумайте пароль</label>
                <div class="error">
                    <input type="password" placeholder="Введите пароль..." name="password">
                    <p><?= $errors['password'] ?></p>
                </div>
            </div>
            <?php } else { ?>
            <div class="field">
                <label>Придумайте пароль</label>
                <input type="password" placeholder="Введите пароль..." name="password">
            </div>
            <?php } ?>

            <?php if(isset($errors['passwordConfirm'])) { ?>
            <div class="field errorField">
                <label>Повторите пароль</label>
                <div class="error">
                    <input type="password" placeholder="Введите пароль..." name="passwordConfirm">
                    <p><?= $errors['passwordConfirm'] ?></p>
                </div>
            </div>
            <?php } else { ?>
            <div class="field">
                <label>Повторите пароль</label>
                <input type="password" placeholder="Введите пароль..." name="passwordConfirm">
            </div>
            <?php } ?>

            <div class="regButtons">
                <button type="reset">Сбросить</button>
                <button type="submit" name="reg">Подтвердить</button>
            </div>
        </form>
        <p>Есть аккаунт? <a href="?page=login">Войти</a></p>
        <!-- Конец формы регистрации -->
    </main>

    <!-- Подвал -->
    <?php include('pages/components/footerBlock.php'); ?>
    <!-- Конец подвала -->
</body>