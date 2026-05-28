<?php
    $errors = [];

    $sql = "SELECT * FROM promotions WHERE id = ?";
    $stmt = $connect->prepare($sql);
    $stmt->execute([$_GET['id']]);
    $promotion = $stmt->fetch(PDO::FETCH_ASSOC);

    if(isset($_POST['addImage'])) {
        $formats = ['jpg','jpeg','png','webp'];
        if(empty($_FILES['image']['name']) and empty($promotion['imgPath'])) $errors['image'] = 'Добавьте изображение';
        elseif(!in_array(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION), $formats)) $errors['image'] = 'Неверный формат изображения';
        elseif($_FILES['image']['size'] > 1024 * 1024 * 2) $errors['image'] = 'Недопустимый размер изображения';
        elseif(empty($errors)) {
            $path = $_SERVER['DOCUMENT_ROOT'].'/assets/promGallery/'.$promotion['id'].'_'.time().'_'.$_FILES['image']['name'];
            $shortPath = 'assets/promGallery/'.$promotion['id'].'_'.time().'_'.$_FILES['image']['name'];
            if(move_uploaded_file($_FILES['image']['tmp_name'], $path)) {
                $sql = "UPDATE promotions SET imgPath = ? WHERE id = ?";
                $stmt = $connect->prepare($sql);
                $stmt->execute([$shortPath, $promotion['id']]);
                echo '<script>location.href="?page=editPromotion&id='.$promotion['id'].'"</script>';
            }
        }
    }

    if(isset($_POST['delete'])) {
        $sql = "UPDATE promotions SET imgPath = ? WHERE id = ?";
        $stmt = $connect->prepare($sql);
        $stmt->execute([NULL, $promotion['id']]);

        $oldPath = $_SERVER['DOCUMENT_ROOT'].$promotion['imgPath'];
        if(file_exists($oldPath)) unlink($oldPath);
        echo '<script>location.href="?page=editPromotion&id='.$_GET['id'].'"</script>';
    }

    if(isset($_POST['editPromotion'])) {
        $sql = "SELECT * FROM promotions WHERE title = ?";
        $stmt = $connect->prepare($sql);
        $stmt->execute([$_POST['title']]);
        $isTitleUsed = $stmt->fetch(PDO::FETCH_ASSOC);

        if(empty($_POST['title'])) $errors['title'] = 'Заголовок не может быть пустым';
        elseif(!empty($isTitleUsed['title']) and $isTitleUsed['title'] != $_POST['title']) $errors['title'] = 'Такая акция уже существует';
        if(empty($_POST['date'])) $errors['date'] = 'Укажите дату';
        if(empty($_POST['description'])) $errors['description'] = 'Описание не может быть пустым';
        if($promotion['imgPath'] == NULL) $errors['image'] = 'Добавьте изображение';
        if(empty($errors)) {
            $sql = "UPDATE promotions SET title = ?, date = ?, description = ? WHERE id = ?";
            $stmt = $connect->prepare($sql);
            $stmt->execute([$_POST['title'], $_POST['date'], $_POST['description'], $promotion['id']]);
            echo '<script>location.href="?page=managePanel&promotions"</script>';
        }
    }
?>

<body class="editItemPage .editPromotionPage">
    <!-- Шапка -->
    <?php include('pages/components/header.php'); ?>
    <!-- Конец шапки -->

    <main>
        <!-- Редактирование товара -->
        <div class="editItemTitle cont">
            <h1 class="title">Редактирование акции</h1>
            <p><?= $promotion['id'] ?></p>
        </div>

        <form class="editItem cont" method="post" enctype="multipart/form-data">
            <div class="createPromotionBlock">
                <div class="itemField">
                    <label>Заголовок</label>
                    <input type="text" placeholder="Заголовок акции..." value="<?= $promotion['title'] ?>" name="title">
                    <?php if(isset($errors['title'])) { ?>
                    <h3><?= $errors['title'] ?></h3>
                    <?php } ?>
                </div>

                <div class="itemField">
                    <label>Срок действия</label>
                    <div class="promotionDate">
                        <p>До</p>
                        <input type="date" placeholder="Введите цену..." value="<?= $promotion['date'] ?>" name="date">
                    </div>
                    <?php if(isset($errors['date'])) { ?>
                    <h3><?= $errors['date'] ?></h3>
                    <?php } ?>
                </div>
            </div>

            <div class="itemField">
                <label>Описание</label>
                <textarea placeholder="Полное описание..." name="description"><?= $promotion['description'] ?></textarea>
                <?php if(isset($errors['description'])) { ?>
                <h3><?= $errors['description'] ?></h3>
                <?php } ?>
            </div>

            <div class="itemField">
                <label>Добавьте изображения</label>
                <div class="editItemImages">
                    <input type="file" id="itemImg" name="image">
                    <label for="itemImg">Выберите фото</label>
                    <p>Файлов не выбрано</p>
                    <button name="addImage">Добавить</button>
                </div>
                <?php if(!empty($promotion['imgPath'])) { ?>
                <div class="editItemGallery">
                    <div class="editItemImage">
                        <img src="<?= $promotion['imgPath'] ?>" alt="">
                        <a href="?page=editPromotion&id=<?= $promotion['id'] ?>&delete=<?= $promotion['id'] ?>">Удалить</a>
                    </div>
                </div>
                <?php } ?>
                <?php if(isset($errors['image'])) { ?>
                <h3><?= $errors['image'] ?></h3>
                <?php } ?>
            </div>

            <div class="editItemOptions">
                <button type="reset">Сбросить</button>
                <button type="submit" name="editPromotion">Сохранить</button>
            </div>
        </form>
        <!-- Конец "Редактирование товара" -->
    </main>

    <!-- Подвал -->
    <?php include('pages/components/footerBlock.php'); ?>
    <!-- Конец подвала -->
</body>

<?php if(isset($_GET['delete'])) { ?>
<div class="modalExit active deleteCategory">
        <form method="post" class="modalExitWindow">
            <a href="?page=editPromotion&id=<?= $_GET['id'] ?>"><p>⨉</p></a>
            <div class="modalExitBody">
                <h1>Удалить фотографию?</h1>
                <div class="modalExitOptions">
                    <a href="?page=editPromotion&id=<?= $_GET['id'] ?>">Отмена</a>
                    <button name="delete">Удалить</button>
                </div>
            </div>
        </form>
    </div>
<?php } ?>