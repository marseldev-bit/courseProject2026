<?php
    $errors = [];
    $charErrors = [];

    if(isset($_POST['changeCategory'])) {
        $sql = "SELECT id FROM categories WHERE name = ?";
        $stmt = $connect->prepare($sql);
        $stmt->execute([$_POST['category']]);
        $categoryId = $stmt->fetch(PDO::FETCH_ASSOC);

        $sql = "UPDATE items SET category_id = ? WHERE id = ?";
        $stmt = $connect->prepare($sql);
        $stmt->execute([$categoryId['id'], $_GET['id']]);
    }

    if(isset($_POST['addImages'])) {
        $formats = ['jpg','jpeg','png','webp'];
        foreach($_FILES['images']['tmp_name'] as $i => $tmp) {
            if(!in_array(pathinfo($_FILES['images']['name'][$i], PATHINFO_EXTENSION), $formats)) $errors['images'] = 'Неверный формат изображения';
            elseif($_FILES['images']['size'][$i] > 1024 * 1024 * 2) $errors['images'] = 'Недопустимый размер изображения';
            elseif(empty($errors)) {
                $path = $_SERVER['DOCUMENT_ROOT'].'/assets/itemGallery/'.$_GET['id'].'_'.time().'_'.$_FILES['images']['name'][$i];
                $shortPath = 'assets/itemGallery/'.$_GET['id'].'_'.time().'_'.$_FILES['images']['name'][$i];
                if(move_uploaded_file($_FILES['images']['tmp_name'][$i], $path)) {
                    $sql = "INSERT INTO itemGallery (item_id, imgPath) VALUES (?, ?)";
                    $stmt = $connect->prepare($sql);
                    $stmt->execute([$_GET['id'], $shortPath]);
                }
            }
        }
    }

    if(isset($_POST['deleteImage'])) {
        $sql = "SELECT imgPath FROM itemGallery WHERE id = ?";
        $stmt = $connect->prepare($sql);
        $stmt->execute([$_POST['deleteImageId']]);
        $img = $stmt->fetch(PDO::FETCH_ASSOC);

        $sql = "DELETE FROM itemGallery WHERE id = ?";
        $stmt = $connect->prepare($sql);
        $stmt->execute([$_POST['deleteImageId']]);
        $oldPath = $_SERVER['DOCUMENT_ROOT'].$img['imgPath'];
        if(file_exists($oldPath)) unlink($oldPath);
        echo '<script>location.href="?page=editItem&id='.$_GET['id'].'"</script>';
    }

    $sql = "SELECT * FROM items WHERE id = ?";
    $stmt = $connect->prepare($sql);
    $stmt->execute([$_GET['id']]);
    $item = $stmt->fetch(PDO::FETCH_ASSOC);

    $sql = "SELECT name FROM categories WHERE id = ?";
    $stmt = $connect->prepare($sql);
    $stmt->execute([$item['category_id']]);
    $category = $stmt->fetch(PDO::FETCH_ASSOC);

    $sql = "SELECT * FROM characteristics WHERE category_id = ?";
    $stmt = $connect->prepare($sql);
    $stmt->execute([$item['category_id']]);
    $chars = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $sql = "SELECT * FROM itemGallery WHERE item_id = ?";
    $stmt = $connect->prepare($sql);
    $stmt->execute([$item['id']]);
    $itemImages = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if(isset($_POST['editItem'])) {
        $sql = "SELECT name FROM items WHERE name = ?";
        $stmt = $connect->prepare($sql);
        $stmt->execute([$_POST['name']]);
        $isNameUsed = $stmt->fetch(PDO::FETCH_ASSOC);
        if(empty($_POST['name'])) $errors['name'] = 'Название товара не может быть пустым';
        elseif($isNameUsed and $isNameUsed['name'] != $item['name']) $errors['name'] = 'Такой товар уже существует';
        if(empty($_POST['shortDesc'])) $errors['shortDesc'] = 'Заполните краткое описание';
        if(empty($_POST['description'])) $errors['description'] = 'Заполните описание';
        if(empty($_POST['price'])) $errors['price'] = 'Укажите цену';
        foreach($chars as $char) {
            if(empty($_POST['char'][$char['id']])) $charErrors[$char['id']] = 'Значение не может быть пустым';
        }
        if(empty($errors) and empty($charErrors)) {
            if(empty($itemImages)) $errors['images'] = 'Добавьте минимум одно изображение';
            else {
                $sql = "SELECT id FROM categories WHERE name = ?";
                $stmt = $connect->prepare($sql);
                $stmt->execute([$_POST['category']]);
                $categoryId = $stmt->fetch(PDO::FETCH_ASSOC);

                $sql = "UPDATE items SET name = ?, shortDescription = ?, description = ?, price = ?, category_id = ? WHERE id = ?";
                $stmt = $connect->prepare($sql);
                $stmt->execute([$_POST['name'], $_POST['shortDesc'], $_POST['description'], $_POST['price'], $categoryId['id'], $item['id']]);

                $sql = "SELECT * FROM itemChars WHERE item_id = ?";
                $stmt = $connect->prepare($sql);
                $stmt->execute([$item['id']]);
                $itemChars = $stmt->fetchAll(PDO::FETCH_ASSOC);
                foreach($chars as $char) {
                    $sql = "SELECT id FROM itemChars WHERE item_id = ? AND char_id = ?";
                    $stmt = $connect->prepare($sql);
                    $stmt->execute([$item['id'], $char['id']]);
                    $exists = $stmt->fetch(PDO::FETCH_ASSOC);
                    if($exists) {
                        $sql = "UPDATE itemChars SET value = ? WHERE item_id = ? AND char_id = ?";
                        $stmt = $connect->prepare($sql);
                        $stmt->execute([$_POST['char'][$char['id']], $item['id'], $char['id']]);
                    }
                    else {
                        $sql = "INSERT INTO itemChars (item_id, char_id, value) VALUES (?, ?, ?)";
                        $stmt = $connect->prepare($sql);
                        $stmt->execute([$item['id'], $char['id'], $_POST['char'][$char['id']]]);
                    }
                }
                echo '<script>location.href="?page=managePanel"</script>';
            }  
        }
    }
?>

<body class="editItemPage">
    <!-- Шапка -->
    <?php include('pages/components/header.php'); ?>
    <!-- Конец шапки -->

    <main>
        <!-- Редактирование товара -->
        <div class="editItemTitle cont">
            <h1 class="title">Редактирование товара</h1>
            <p><?= $item['id'] ?></p>
        </div>

        <p class="mobileId cont">ID товара: <?= $item['id'] ?></p>

        <form class="editItem cont" method="post" enctype="multipart/form-data">
            <div class="itemField">
                <label>Название</label>
                <input type="text" placeholder="Название товара..." value="<?= $item['name'] ?>" name="name">
                <?php if(isset($errors['name'])) { ?>
                <h3><?= $errors['name'] ?></h3>
                <?php } ?>
            </div>

            <div class="itemField">
                <label>Краткое описание</label>
                <input type="text" placeholder="Краткое описание..." value="<?= $item['shortDescription'] ?>" name="shortDesc">
                <?php if(isset($errors['shortDesc'])) { ?>
                <h3><?= $errors['shortDesc'] ?></h3>
                <?php } ?>
            </div>

            <div class="itemField">
                <label>Описание</label>
                <textarea
                    placeholder="Полное описание..." name="description"><?= $item['description'] ?></textarea>
                <?php if(isset($errors['description'])) { ?>
                <h3><?= $errors['description'] ?></h3>
                <?php } ?>
            </div>

            <div class="itemField">
                <label>Цена ₽</label>
                <input type="number" placeholder="Введите цену..." value="<?= $item['price'] ?>" name="price">
                <?php if(isset($errors['price'])) { ?>
                <h3><?= $errors['price'] ?></h3>
                <?php } ?>
            </div>

            <div class="itemField">
                <label>Категория</label>
                <div class="selectCategory">
                    <select name="category">
                        <option selected><?= $category['name'] ?></option>
                        <?php 
                        $sql = "SELECT name FROM categories";
                        $stmt = $connect->prepare($sql);
                        $stmt->execute();
                        $categories = $stmt->fetchAll(PDO::FETCH_COLUMN);
                        foreach($categories as $cat) { 
                            if($cat == $category['name']) continue;
                            else { ?>
                            <option><?= $cat ?></option>
                        <?php } }  ?>
                    </select>
                    <button name="changeCategory">Выбрать категорию</button>
                </div>
                <?php if(isset($errors['category'])) { ?>
                <h3><?= $errors['category'] ?></h3>
                <?php } ?>
            </div>

            <h2>Характеристики</h2>
            <div class="itemProperties">
                <?php if(empty($chars)) { ?>
                <h1>У данной категории нет характеристик</h1>
                <?php } else {
                    foreach($chars as $char) {
                        $sql = "SELECT value FROM itemChars WHERE item_id = ? AND char_id = ?";
                        $stmt = $connect->prepare($sql);
                        $stmt->execute([$item['id'], $char['id']]);
                        $value = $stmt->fetch(PDO::FETCH_ASSOC); ?>
                    <div class="property">
                        <label><?= $char['name'] ?></label>
                        <?php if(!empty($value['value'])) { ?>
                        <input type="text" placeholder="Введите значение..." name="char[<?= $char['id'] ?>]" value="<?= $value['value'] ?>">
                        <?php } else { ?>
                        <input type="text" placeholder="Введите значение..." name="char[<?= $char['id'] ?>]">
                        <?php }
                        if(isset($charErrors[$char['id']])) { ?>
                        <h3><?= $charErrors[$char['id']] ?></h3>
                        <?php } ?>
                    </div>
                <?php } } ?>
            </div>

            <div class="itemField">
                <label>Добавьте изображения</label>
                <div class="editItemImages">
                    <input type="file" id="itemImg" multiple name="images[]">
                    <label for="itemImg">Выберите фото</label>
                    <p>Файлов не выбрано</p>
                    <button name="addImages">Добавить</button>
                </div>
                <?php if(!empty($itemImages)) { ?>
                <div class="editItemGallery">
                    <?php foreach($itemImages as $image) { ?>
                    <div class="editItemImage">
                        <img src="<?= $image['imgPath'] ?>" alt="">
                        <a href="?page=editItem&id=<?= $item['id'] ?>&deleteImage=<?= $image['id'] ?>">Удалить</a>
                    </div>
                    <?php } ?>
                </div>
                <?php } if(isset($errors['images'])) { ?>
                <h3><?= $errors['images'] ?></h3>
                <?php } ?>
            </div>

            <div class="editItemOptions">
                <button type="reset">Сбросить изменения</button>
                <button type="submit" name="editItem">Сохранить</button>
            </div>
        </form>
        <!-- Конец "Редактирование товара" -->
    </main>

    <!-- Подвал -->
    <?php include('pages/components/footerBlock.php'); ?>
    <!-- Конец подвала -->
</body>

<?php if(isset($_GET['deleteImage'])) { ?>
<div class="modalExit active deleteCategory">
        <form method="post" class="modalExitWindow">
            <a href="?page=editItem&id=<?= $_GET['id'] ?>"><p>⨉</p></a>
            <div class="modalExitBody">
                <h1>Удалить фотографию с ID: <?= $_GET['deleteImage'] ?>?</h1>
                <div class="modalExitOptions">
                    <a href="?page=editItem&id=<?= $_GET['id'] ?>">Отмена</a>
                    <input type="hidden" value="<?= $_GET['deleteImage'] ?>" name="deleteImageId">
                    <button name="deleteImage">Удалить</button>
                </div>
            </div>
        </form>
    </div>
<?php } ?>