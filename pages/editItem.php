<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../assets/images/favicon.png" type="image/x-icon">
    <link rel="stylesheet" href="style.css">
    <title>Кинг</title>
</head>

<body class="editItemPage">
    <!-- Шапка -->
    <?php include('header.php'); ?>
    <!-- Конец шапки -->

    <main>
        <!-- Редактирование товара -->
        <div class="editItemTitle cont">
            <h1 class="title">Редактирование товара</h1>
            <p>1</p>
        </div>

        <p class="mobileId cont">ID товара: 1</p>

        <form class="editItem cont">
            <div class="itemField">
                <label>Название</label>
                <input type="text" placeholder="Название товара..." value="Классические шахматы «Королевская партия»">
            </div>

            <div class="itemField">
                <label>Краткое описание</label>
                <input type="text" placeholder="Краткое описание..." value="Дерево, стандартные фигуры">
            </div>

            <div class="itemField">
                <label>Описание</label>
                <textarea
                    placeholder="Полное описание...">Элегантные классические шахматы из натурального дерева для дома и офиса. Фигуры стандартной формы, приятные на вес, не скользят по доске. Отличный выбор для начинающих и любителей, кто ценит традиционный стиль.</textarea>
            </div>

            <div class="itemField">
                <label>Цена ₽</label>
                <input type="number" placeholder="Введите цену..." value="3490">
            </div>

            <div class="itemField">
                <label>Категория</label>
                <select>
                    <option>Категория</option>
                    <option selected>Шахматы</option>
                    <option>Доски</option>
                    <option>Часы</option>
                    <option>Фигуры</option>
                </select>
            </div>

            <h2>Характеристики</h2>
            <div class="itemProperties">
                <div class="propertyBlock">
                    <div class="property">
                        <label>Материал фигур</label>
                        <input type="text" placeholder="Укажите материал фигур..." value="Бук">
                    </div>

                    <div class="property">
                        <label>Материал доски</label>
                        <input type="text" placeholder="Укажите материал доски..." value="Берёзовая фанера">
                    </div>
                </div>

                <div class="propertyBlock">
                    <div class="property">
                        <label>Высота короля</label>
                        <input type="text" placeholder="Укажите высоту короля..." value="7,5 см">
                    </div>

                    <div class="property">
                        <label>Размер доски</label>
                        <input type="text" placeholder="Укажите размер доски..." value="40 × 40 см ">
                    </div>
                </div>

                <div class="propertyBlock">
                    <div class="property">
                        <label>Вес комплекта</label>
                        <input type="text" placeholder="Укажите вес комплекта..." value="1,2 кг">
                    </div>

                    <div class="property">
                        <label>Страна производства</label>
                        <input type="text" placeholder="Укажите страну производства..." value="Россия">
                    </div>
                </div>
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
                        <img src="../assets/images/card1.png" alt="">
                        <a href="#">Удалить</a>
                    </div>

                    <div class="editItemImage">
                        <img src="../assets/images/item2.png" alt="">
                        <a href="#">Удалить</a>
                    </div>

                    <div class="editItemImage">
                        <img src="../assets/images/item3.png" alt="">
                        <a href="#">Удалить</a>
                    </div>

                    <div class="editItemImage">
                        <img src="../assets/images/item4.png" alt="">
                        <a href="#">Удалить</a>
                    </div>
                </div>
            </div>

            <div class="editItemOptions">
                <button type="reset">Сбросить изменения</button>
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