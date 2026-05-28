<?php
    $errors = [];

    if(isset($_POST['editName'])) {
        if(empty($_POST['newName'])) $errors['editName'] = 'Имя не может быть пустым';
        elseif(mb_strlen($_POST['newName']) > 20) $errors['editName'] = "Имя не должно быть длиннее 20 символов";
        elseif($_POST['newName'] == $USER['name']) $errors['editName'] = 'Имя осталось прежним';
        elseif(empty($errors)) {
            $sql = 'UPDATE users SET name = ? WHERE id = ?';
            $stmt = $connect->prepare($sql);
            $stmt->execute([$_POST['newName'], $USER['id']]);
            echo '<script>location.href="?page=profile"</script>';
        }
    }

    if(isset($_POST['setAvatar'])) {
            if(empty($_FILES['avatar']['name'])) $errors['editAvatar'] = 'Добавьте изображение';
            else {
                $formats = ['jpg', 'jpeg', 'png', 'webp'];
                if(!in_array(pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION), $formats)) $errors['editAvatar'] = 'Файл должен быть формата jpg/jpeg, png или webp';
                elseif($_FILES['avatar']['size'] > 1024 * 1024 * 2) $errors['editAvatar'] = 'Размер изображения не должен превышать 50КБ';
                elseif(empty($errors['editAvatar'])) {
                    $extension = strtolower(pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION));
                    $path = 'C:/OSPanel/domains/King/assets/images/'.$USER['id'].'_'.$_FILES['avatar']['name'].'_'.time().'.'.$extension;
                    $shortPath = '/assets/images/'.$USER['id'].'_'.$_FILES['avatar']['name'].'_'.time().'.'.$extension;
                    if(isset($USER['imgPath'])) {
                        $oldPath = $_SERVER['DOCUMENT_ROOT'].$USER['imgPath'];
                        if(file_exists($oldPath)) unlink($oldPath);
                    }
                    if(move_uploaded_file($_FILES['avatar']['tmp_name'], $path)) {
                        $sql = "UPDATE users SET imgPath = ? WHERE id = ?";
                        $stmt = $connect->prepare($sql);
                        $stmt->execute([$shortPath, $USER['id']]);

                    }
                    else echo 'ошибка загрузки';
                    echo '<script>location.href="?page=profile"</script>';
                }
            }
        }
?>

<body class="profilePage">
    <?php if(isset($_GET['success'])) include('pages/components/loginSuccess.php'); ?>

    <!-- Шапка -->
    <?php include('pages/components/header.php'); ?>
    <!-- Конец шапки -->

    <main>
        <!-- Профиль -->
        <h1 class="title cont">Профиль</h1>
        <div class="profile cont">
            <div class="profileAvatar">
                    <?php if(isset($USER['imgPath'])) { ?>
                    <div class="avatar">
                        <a href="?page=profile&avatar"><label for="avatar"><img src="<?= $USER['imgPath'] ?>" alt="gtht"></label></a>
                        <p>Изменить<br>фото</p>
                    </div>
                    <?php } else { ?>
                    <a href="?page=profile&avatar"><label>
                        <p>Добавить фото</p>
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <line x1="10.166" y1="1.5" x2="10.166" y2="18.5" stroke="white" stroke-width="3" stroke-linecap="round"/>
                        <line x1="1.5" y1="9.83334" x2="18.5" y2="9.83334" stroke="white" stroke-width="3"    stroke-linecap="round"/>
                        </svg>
                    </label></a>
                    <?php } ?>
            </div>

            <div class="profileBlock">
                <div class="profileName">
                    <h1><?= $USER['name'] ?></h1>
                    <a href="?page=profile&editName"><svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <g clip-path="url(#clip0_321_5)">
                    <path d="M0 15.8333L12.2917 3.54167L16.4583 7.70833L4.16667 20H0V15.8333ZM19.6875 4.47917L17.6562 6.51042L13.4896 2.34375L15.5208 0.3125C15.7292 0.104167 15.9896 0 16.3021 0C16.6146 0 16.875 0.104167 17.0833 0.3125L19.6875 2.91667C19.8958 3.125 20 3.38542 20 3.69792C20 4.01042 19.8958 4.27083 19.6875 4.47917Z" fill="#4E3822"/>
                    </g>
                    <defs>
                    <clipPath id="clip0_321_5">
                    <rect width="20" height="20" fill="white"/>
                    </clipPath>
                    </defs>
                    </svg></a>
                    <?php if($USER['isAdmin']) { ?>
                    <h2>Администратор</h2>
                    <?php } ?>
                </div>

                <div class="profileInfo">
                    <p><span>Email:</span> <?= $USER['email'] ?></p>
                    <p><span>Пол:</span> <?= $USER['sex'] ?></p>
                    <p><span>Покупок:</span> <?= $USER['orders'] ?></p>
                </div>
            </div>
        </div>

        <div class="profileOptions cont">
            <?php if($USER['isAdmin']) { ?>
            <a href="?page=managePanel">Панель управления</a>
            <?php } ?>
            <a href="?page=cart">Корзина</a>
            <button id="userOrdersHistory" class="profileOption">История заказов</button>
            <button id="userReviews" class="profileOption">Мои отзывы</button>
            <button>Выйти</button>
        </div>

        <?php 
            include('userOrdersHistory.php');
            include('userReviews.php'); 
        ?>
        <!-- Конец профиля -->
    </main>

    <!-- Модальное окно для подтверждения выхода из аккаунта -->
    <div class="modalExit">
        <form method="post" class="modalExitWindow">
            <p>⨉</p>
            <div class="modalExitBody">
                <h1>Выйти из учетной записи?</h1>
                <div class="modalExitOptions">
                    <button>Отмена</button>
                    <button name="exit">Выйти</button>
                </div>
            </div>
        </form>
    </div>

    <!-- Модальное окно смены имени -->
    <?php if(isset($_GET['editName'])) { ?>
    <div class="editName">
        <form method="post" class="editNameWindow">
            <a href="?page=profile"><p>⨉</p></a>
            <div class="editNameBody">
                <h1>Редактирование имени</h1>
                <div class="nameInput">
                    <label>Новое имя:</label>
                    <input type="text" name="newName" value="<?= $USER['name'] ?>" placeholder="Введите имя...">
                    <?php if(isset($errors['editName'])) { ?>
                    <h2><?= $errors['editName'] ?></h2>
                    <?php } ?>
                </div>
                <div class="editNameOptions">
                    <a href="?page=profile">Отмена</a>
                    <button name="editName">Сохранить</button>
                </div>
            </div>
        </form>
    </div>
    <?php } ?>

    <!-- Модальное окно редактирования аватара -->
    <?php if(isset($_GET['avatar'])) { ?>
    <div class="editAvatar">
        <div class="editAvatarWindow">
            <a href="?page=profile"><p>⨉</p></a>
            <form class="editAvatarBody" method="post" enctype="multipart/form-data">
                <h1>Редактирование аватара</h1>
                <div class="editAvatarInput">
                    <div class="avatarInput">
                        <input type="file" id="avatar" name="avatar">
                        <label for="avatar">Выберите изображение</label>
                        <h2>Файл не выбран</h2>
                    </div>
                    <?php if(isset($errors['editAvatar'])) { ?>
                        <h2><?= $errors['editAvatar'] ?></h2>
                    <?php } ?>
                </div>
                <div class="editAvatarOptions">
                    <a href="?page=profile">Отмена</a>
                    <button type="submit" name="setAvatar">Сохранить</button>
                </div>
            </form>
        </div>
    </div>
    <?php } ?>

    <!-- Подвал -->
    <?php include('pages/components/footerBlock.php'); ?>
    <!-- Конец подвала -->
</body>