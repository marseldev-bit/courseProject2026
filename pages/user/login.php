<?php
    $errors = [];

    if(isset($_POST['login'])) {
        if(empty($_POST['email'])) $errors['email'] = "Почта не может быть пустой";
        else {
            $sql = "SELECT id, password FROM users WHERE email = ?";
            $stmt = $connect->prepare($sql);
            $stmt->execute([$_POST['email']]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            if(!$user['id']) $errors['email'] = "Неверная электронная почта";
        }

        if($user['id']) {
            if(empty($_POST['password'])) $errors['password'] = "Пароль не может быть пустым";
            elseif(!password_verify($_POST['password'], $user['password'])) $errors['password'] = "Неверный пароль";
        }

        if(empty($errors)) {
            $_SESSION['userID'] = $user['id'];
            echo '<script>location.href="?page=profile&success"</script>';
        }
    }
?>

<body class="regPage loginPage">
    <?php if(isset($_GET['success'])) include('pages/components/regSuccess.php'); ?>

    <!-- Шапка -->
    <?php include('pages/components/header.php'); ?>
    <!-- Конец шапки -->

    <main>
        <!-- Форма авторизации -->
        <h1 class="title">Авторизация</h1>
        <form class="reg" method="post">
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

            <div class="regButtons">
                <button type="reset">Сбросить</button>
                <button type="submit" name="login">Подтвердить</button>
            </div>
        </form>
        <p>Нет аккаунта? <a href="?page=reg">Регистрация</a></p>
        <!-- Конец формы авторизации -->
    </main>

    <!-- Подвал -->
    <?php include('pages/components/footerBlock.php'); ?>
    <!-- Конец подвала -->
</body>