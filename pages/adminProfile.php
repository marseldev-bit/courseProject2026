<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../assets/images/favicon.png" type="image/x-icon">
    <link rel="stylesheet" href="style.css">
    <script src="../js/profile.js" defer></script>
    <title>Кинг</title>
</head>

<body class="profilePage adminPage">
    <!-- Шапка -->
    <?php include('header.php'); ?>
    <!-- Конец шапки -->

    <main>
        <!-- Профиль -->
        <h1 class="title cont">Профиль</h1>
        <div class="profile cont">
            <div class="profileAvatar">
                <div class="avatar">
                    <input type="file" id="avatar">
                    <label for="avatar"><img src="../assets/images/admin.png" alt=""></label>
                    <div class="avatarEdit"><p>Изменить фото</p></div>
                </div>
            </div>

            <div class="profileBlock">
                <div class="profileName">
                    <div class="profileNameBlock">
                        <h1>Евгений</h1>
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <g clip-path="url(#clip0_321_5)">
                        <path d="M0 15.8333L12.2917 3.54167L16.4583 7.70833L4.16667 20H0V15.8333ZM19.6875 4.47917L17.6562 6.51042L13.4896 2.34375L15.5208 0.3125C15.7292 0.104167 15.9896 0 16.3021 0C16.6146 0 16.875 0.104167 17.0833 0.3125L19.6875 2.91667C19.8958 3.125 20 3.38542 20 3.69792C20 4.01042 19.8958 4.27083 19.6875 4.47917Z" fill="#4E3822"/>
                        </g>
                        <defs>
                        <clipPath id="clip0_321_5">
                        <rect width="20" height="20" fill="white"/>
                        </clipPath>
                        </defs>
                        </svg>
                    </div>
                    <h2>Администратор</h2>
                </div>

                <div class="profileInfo">
                    <p><span>Email:</span> evgAdmin@gmail.com</p>
                    <p><span>Пол:</span> Мужской</p>
                    <p><span>Покупок:</span> 1</p>
                </div>
            </div>
        </div>

        <div class="profileOptions cont">
            <a href="managePanel.php">Панель управления</a>
            <a href="cart.php">Корзина</a>
            <button id="userOrdersHistory">История заказов</button>
            <button id="userReviews">Мои отзывы</button>
            <button>Выйти</button>
        </div>

        <?php
            include('userOrdersHistory.php');
            include('userReviews.php');
        ?>
        <!-- Конец профиля -->
    </main>

    <!-- Подвал -->
    <?php include('footerBlock.php'); ?>
    <!-- Конец подвала -->
</body>

</html>