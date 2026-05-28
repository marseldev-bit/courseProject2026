<?php
    $errors = [];

    if(isset($_POST['createPromotion'])) {
        $sql = "SELECT * FROM promotions WHERE title = ?";
        $stmt = $connect->prepare($sql);
        $stmt->execute([$_POST['title']]);
        $isTitleUsed = $stmt->fetch(PDO::FETCH_ASSOC);

        if(empty($_POST['title'])) $errors['title'] = 'Заголовок не может быть пустым';
        elseif(!empty($isTitleUsed['title'])) $errors['title'] = 'Такая акция уже существует';
        if(empty($_POST['date'])) $errors['date'] = 'Укажите дату';
        if(empty($_POST['description'])) $errors['description'] = 'Описание не может быть пустым';
        if(empty($errors)) {
            $sql = "INSERT INTO promotions (title, date, description) VALUES (?, ?, ?)";
            $stmt = $connect->prepare($sql);
            $stmt->execute([$_POST['title'], $_POST['date'], $_POST['description']]);

            $sql = "SELECT id FROM promotions WHERE title = ?";
            $stmt = $connect->prepare($sql);
            $stmt->execute([$_POST['title']]);
            $id = $stmt->fetch(PDO::FETCH_ASSOC);

            echo '<script>location.href="?page=editPromotion&id='.$id['id'].'"</script>';
        }
    }
?>

<body class="editItemPage createItemPage">
    <!-- Шапка -->
    <?php include('pages/components/header.php'); ?>
    <!-- Конец шапки -->

    <main>
        <!-- Редактирование товара -->
        <h1 class="title cont">Создание акции</h1>

        <form class="editItem cont" method="post" enctype="multipart/form-data">
            <div class="createPromotionBlock">
                <div class="itemField">
                    <label>Заголовок</label>
                    <input type="text" placeholder="Заголовок акции..." name="title">
                    <?php if(isset($errors['title'])) { ?>
                    <h3><?= $errors['title'] ?></h3>
                    <?php } ?>
                </div>

                <div class="itemField">
                    <label>Срок действия</label>
                    <div class="promotionDate">
                        <p>До</p>
                        <input type="date" placeholder="Введите цену..." name="date">
                    </div>
                    <?php if(isset($errors['date'])) { ?>
                    <h3><?= $errors['date'] ?></h3>
                    <?php } ?>
                </div>
            </div>

            <div class="itemField">
                <label>Описание</label>
                <textarea placeholder="Полное описание..." name="description"></textarea>
                <?php if(isset($errors['description'])) { ?>
                <h3><?= $errors['description'] ?></h3>
                <?php } ?>
            </div>

            <h2>* Изображение акции можно будет добавить после ее создания</h2>

            <div class="editItemOptions">
                <button type="reset">Сбросить</button>
                <button type="submit" name="createPromotion">Сохранить</button>
            </div>
        </form>
        <!-- Конец "Редактирование товара" -->
    </main>

    <!-- Подвал -->
    <?php include('pages/components/footerBlock.php'); ?>
    <!-- Конец подвала -->
</body>