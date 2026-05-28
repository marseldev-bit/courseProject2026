<?php
    $errors = [];

    if(isset($_POST['createItem'])) {
        $sql = "SELECT id FROM items WHERE name = ?";
        $stmt = $connect->prepare($sql);
        $stmt->execute([$_POST['name']]);
        $isNameUsed = $stmt->fetch();
        if(empty($_POST['name'])) $errors['name'] = 'Название товара не может быть пустым';
        elseif($isNameUsed) $errors['name'] = 'Такой товар уже существует';
        if(empty($_POST['shortDesc'])) $errors['shortDesc'] = 'Заполните краткое описание';
        if(empty($_POST['description'])) $errors['description'] = 'Заполните описание';
        if(empty($_POST['price'])) $errors['price'] = 'Укажите цену';
        if(empty($_POST['category'])) $errors['category'] = 'Выберите категорию';
        if(empty($errors)) {
            $sql = "SELECT id FROM categories WHERE name = ?";
            $stmt = $connect->prepare($sql);
            $stmt->execute([$_POST['category']]);
            $categoryId = $stmt->fetch(PDO::FETCH_ASSOC);

            $sql = "INSERT INTO items (name, shortDescription, description, price, category_id) VALUES (?, ?, ?, ?, ?)";
            $stmt = $connect->prepare($sql);
            $stmt->execute([$_POST['name'], $_POST['shortDesc'], $_POST['description'], $_POST['price'], $categoryId['id']]);

            $sql = "SELECT id FROM items WHERE name = ?";
            $stmt = $connect->prepare($sql);
            $stmt->execute([$_POST['name']]);
            $directId = $stmt->fetch(PDO::FETCH_ASSOC);
            echo '<script>location.href="?page=editItem&id='.$directId['id'].'"</script>';
        }
    }
?>

<body class="editItemPage createItemPage">
    <!-- Шапка -->
    <?php include('pages/components/header.php'); ?>
    <!-- Конец шапки -->

    <main>
        <!-- Редактирование товара -->
        <h1 class="title cont">Создание товара</h1>

        <form class="editItem cont" method="post">
            <div class="itemField">
                <label>Название</label>
                <input type="text" placeholder="Название товара..." name="name">
                <?php if(isset($errors['name'])) { ?>
                <h3><?= $errors['name'] ?></h3>
                <?php } ?>
            </div>

            <div class="itemField">
                <label>Краткое описание</label>
                <input type="text" placeholder="Краткое описание..." name="shortDesc">
                <?php if(isset($errors['shortDesc'])) { ?>
                <h3><?= $errors['shortDesc'] ?></h3>
                <?php } ?>
            </div>

            <div class="itemField">
                <label>Описание</label>
                <textarea placeholder="Полное описание..." name="description"></textarea>
                <?php if(isset($errors['description'])) { ?>
                <h3><?= $errors['description'] ?></h3>
                <?php } ?>
            </div>

            <div class="itemField">
                <label>Цена ₽</label>
                <input type="number" placeholder="Введите цену..." name="price">
                <?php if(isset($errors['price'])) { ?>
                <h3><?= $errors['price'] ?></h3>
                <?php } ?>
            </div>

            <div class="itemField">
                <label>Категория</label>
                    <select name="category">
                    <option selected disabled>Категория</option>
                    <?php 
                    $sql = "SELECT name FROM categories";
                    $stmt = $connect->prepare($sql);
                    $stmt->execute();
                    $categories = $stmt->fetchAll(PDO::FETCH_COLUMN);
                    foreach($categories as $cat) { ?>
                        <option><?= $cat ?></option>
                    <?php }  ?>
                </select>
                <?php if(isset($errors['category'])) { ?>
                <h3><?= $errors['category'] ?></h3>
                <?php } ?>
            </div>

            <h2>* Характеристики и фото товара можно будет добавить после его создания</h2>

            <div class="editItemOptions">
                <button type="reset">Сбросить</button>
                <button type="submit" name="createItem">Сохранить</button>
            </div>
        </form>
        <!-- Конец "Редактирование товара" -->
    </main>

    <!-- Подвал -->
    <?php include('pages/components/footerBlock.php'); ?>
    <!-- Конец подвала -->
</body>