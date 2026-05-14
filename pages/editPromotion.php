<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../assets/images/favicon.png" type="image/x-icon">
    <link rel="stylesheet" href="style.css">
    <title>Кинг</title>
</head>

<body class="editItemPage .editPromotionPage">
    <!-- Шапка -->
    <?php include('header.php'); ?>
    <!-- Конец шапки -->

    <main>
        <!-- Редактирование товара -->
        <div class="editItemTitle cont">
            <h1 class="title">Редактирование акции</h1>
            <p>3</p>
        </div>

        <form class="editItem cont">
            <div class="createPromotionBlock">
                <div class="itemField">
                    <label>Заголовок</label>
                    <input type="text" placeholder="Заголовок акции..." value="Скидка 20% на первый заказ">
                </div>

                <div class="itemField">
                    <label>Срок действия</label>
                    <div class="promotionDate">
                        <p>До</p>
                        <input type="date" placeholder="Введите цену..." value="2026-05-31">
                    </div>
                </div>
            </div>

            <div class="itemField">
                <label>Описание</label>
                <textarea placeholder="Полное описание...">При регистрации на сайте вы получаете промокод на скидку 20% на любой товар из каталога. Акция действует для новых покупателей.</textarea>
            </div>

            <div class="itemField">
                <label>Добавьте изображения</label>
                <div class="editItemImages">
                    <input type="file" id="itemImg">
                    <label for="itemImg">Выберите фото</label>
                    <a href="#">Добавить</a>
                </div>
                <div class="editItemGallery">
                    <div class="editItemImage">
                        <img src="../assets/images/prom1.png" alt="">
                        <a href="#">Удалить</a>
                    </div>
                </div>
            </div>

            <div class="editItemOptions">
                <button type="reset">Сбросить </button>
                <button type="submit">Сохранить</button>
            </div>
        </form>
        <!-- Конец "Редактирование товара" -->
    </main>

    <!-- Подвал -->
    <?php include('footerBlock.php'); ?>
    <!-- Конец подвала -->
</body>

</html>