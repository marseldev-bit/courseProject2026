<?php 
    $errors = [];
    $sql = "SELECT * FROM categories";
    $stmt = $connect->prepare($sql);
    $stmt->execute();
    $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $count = 0;

    if(isset($_POST['searchCategory']) and !empty($_POST['categoryId']))
    if(isset($_POST['createCategory'])) {
        $sql = "SELECT id FROM categories WHERE name = ?";
        $stmt = $connect->prepare($sql);
        $stmt->execute([$_POST['category']]);
        $isCategoryName = $stmt->fetch();
        if(empty($_POST['category'])) $errors['createCategory'] = 'Введите название категории';
        elseif($isCategoryName) $errors['createCategory'] = 'Такая категория уже существует';
        elseif(empty($errors)) {
            $sql = 'INSERT INTO categories (name) VALUES (?)';
            $stmt = $connect->prepare($sql);
            $stmt->execute([$_POST['category']]);
            echo '<script>location.href="?page=managePanel&categories"</script>';
        }
    }
    if(isset($_POST['deleteConfirm'])) {
        $sql = "DELETE FROM categories WHERE id = ?";
        $stmt = $connect->prepare($sql);
        $stmt->execute([$_POST['deleteCategoryId']]);
        echo '<script>location.href="?page=managePanel&categories"</script>';
    }
?>

<div class="manageCategories cont manageSection">
    <div class="catalogHeader">
        <div class="filters">
            <form class="search" method="post">
                <input type="text" placeholder="ID категории..." name="categoryId">
                <button name="searchCategory"><img src="../assets/images/search.png"></button>
            </form>
            <a href="?page=managePanel&categories=create"><button name="createCategory">Добавить категорию</button></a>
        </div>
    </div>

    <div class="categories">
    <?php foreach($categories as $category) {
        if(isset($_POST['searchCategory']) and $_POST['categoryId'] != $category['id']) continue;
        else {
        if($count > 0) { ?>
        <div class="separateLine"></div>
        <?php } ?>
        <div class="category">
            <h1><span class="id"><?= $category['id'] ?></span><?= $category['name'] ?></h1>
            <p>Товаров: 12</p>
            <div class="categoryOptions">
                <a href="#">К товарам</a>
                <a href="?page=managePanel&categories&edit=<?= $category['id'] ?>">Редактировать</a>
                <a href="?page=managePanel&categories&deleteCategory=<?= $category['id'] ?>">Удалить</a>
            </div>
        </div>
        <?php $count += 1; } }
        
        if(!$count) { ?>
        <h1 class="categoriesEmpty">Категорий с таким ID нет</h1>
        <?php } ?>
    </div>
</div>

<?php if($_GET['categories'] == 'create') { ?>
<div class="createCategory">
        <form method="post" class="createCategoryWindow">
            <a href="?page=managePanel&categories"><p>⨉</p></a>
            <div class="createCategoryBody">
                <h1>Добавление категории</h1>
                <div class="categoryInput">
                    <label>Название категории:</label>
                    <input type="text" placeholder="Введите название..." name="category">
                    <?php if(isset($errors['createCategory'])) { ?>
                        <h2><?= $errors['createCategory'] ?></h2>
                    <?php } ?>
                </div>
                <div class="createCategoryOptions">
                    <a href="?page=managePanel&categories">Отмена</a>
                    <button name="createCategory">Добавить</button>
                </div>
            </div>
        </form>
    </div>
<?php }

if(isset($_GET['deleteCategory'])) {
    foreach($categories as $c) if($c['id'] == $_GET['deleteCategory']) {
        $category = $c;
        break;
    } ?>
<div class="modalExit active deleteCategory">
        <form method="post" class="modalExitWindow">
            <a href="?page=managePanel&categories"><p>⨉</p></a>
            <div class="modalExitBody">
                <h1>Удалить категорию «<?= $category['name'] ?>»?</h1>
                <div class="modalExitOptions">
                    <a href="?page=managePanel&categories">Отмена</a>
                    <input type="hidden" value="<?= $category['id'] ?>" name="deleteCategoryId">
                    <button name="deleteConfirm">Удалить</button>
                </div>
            </div>
        </form>
    </div>
<?php } ?>
<?php if(isset($_GET['edit'])) { 
    $count = 0;
    foreach($categories as $c) if($c['id'] == $_GET['edit']) {
        $category = $c;
        break;
    }
    $sql = "SELECT * FROM characteristics WHERE category_id = ?";
    $stmt = $connect->prepare($sql);
    $stmt->execute([$category['id']]);
    $chars = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if(isset($_POST['createNewChar'])) {
        $isCharIsset = false;
        foreach($chars as $char) if($char['name'] == $_POST['newChar']) {
            $isCharIsset = true;
            break;
        }
        if(isset($_POST['cancelNewChar'])) echo '<script>location.href="?page=managePanel&categories&edit='.$category['id'].'"</script>';
        if(empty($_POST['newChar'])) $errors['createChar'] = 'Введите имя характеристики';
        elseif($isCharIsset) $errors['createChar'] = 'Такая характеристика уже существует';
        else {
            $sql = "INSERT INTO characteristics (category_id, name) VALUES (?, ?)";
            $stmt = $connect->prepare($sql);
            $stmt->execute([$category['id'], $_POST['newChar']]);
            echo '<script>location.href="?page=managePanel&categories&edit='.$category['id'].'"</script>';
        }

    }
    if(isset($_POST['editCharConfirm'])) {
        $isCharIsset = false;
        foreach($chars as $char) if($char['name'] == $_POST['editCharName']) {
            $isCharIsset = true;
            break;
        }
        if(empty($_POST['editCharName'])) $errors['editChar'] = 'Введите имя характеристики';
        elseif($isCharIsset) $errors['editChar'] = 'Такая категория уже существует';
        else {
            $sql = "UPDATE characteristics SET name = ? WHERE id = ?";
            $stmt = $connect->prepare($sql);
            $stmt->execute([$_POST['editCharName'], $_POST['editCharId']]);
            echo '<script>location.href="?page=managePanel&categories&edit='.$category['id'].'"</script>';
        }
    }
    if(isset($_POST['deleteChar'])) {
        $sql = "DELETE FROM characteristics WHERE id = ?";
        $stmt = $connect->prepare($sql);
        $stmt->execute([$_POST['deleteCharId']]);
        echo '<script>location.href="?page=managePanel&categories&edit='.$category['id'].'"</script>';
    }

    if(isset($_POST['editCategory'])) {
        if(empty($_POST['categoryNameEdit'])) $errors['categoryNameEdit'] = 'Заполните имя категории';
        elseif(empty($errors)) {
            $sql = "UPDATE categories SET name = ? WHERE id = ?";
            $stmt = $connect->prepare($sql);
            $stmt->execute([$_POST['categoryNameEdit'], $category['id']]);
            echo '<script>location.href="?page=managePanel&categories"</script>';
        }
    }
    ?>
    
<div class="createCategory editCategory">
        <form method="post" class="createCategoryWindow">
            <a href="?page=managePanel&categories"><p>⨉</p></a>
            <div class="createCategoryBody">
                <h1>Редактирование категории</h1>
                <div class="categoryInput">
                    <label>Новое имя категории:</label>
                    <input type="text" placeholder="Введите имя..." name="categoryNameEdit" value="<?= $category['name'] ?>">
                    <?php if(isset($errors['categoryNameEdit'])) { ?>
                        <h2><?= $errors['categoryNameEdit'] ?></h2>
                    <?php } ?>
                </div>

                <div class="chars">
                    <div class="charsHead">
                        <h2>Характеристики</h2>
                        <a href="?page=managePanel&categories&edit=<?= $category['id'] ?>&createChar">Добавить</a>
                    </div>
                    <?php if(isset($_GET['createChar'])) { ?>
                    <div class="newChar">
                        <input type="text" name="newChar" placeholder="Характеристика...">
                        <div class="charOptions">
                            <button name="createNewChar">Добавить</button>
                            <a href="?page=managePanel&categories&edit=<?= $category['id'] ?>">Отмена</a>
                        </div>
                    </div>
                    <?php if(isset($errors['createChar'])) { ?>
                        <h2><?= $errors['createChar'] ?></h2>
                    <?php } ?>
                    <div class="separateLine"></div>
                    <?php } ?>
                    <?php if(empty($chars) and !isset($_GET['createChar'])) { ?>
                    <h3>Характеристик нет</h3>
                    <?php } else {
                        foreach($chars as $char) {
                            if($count > 0) { ?>
                            <div class="separateLine"></div>
                            <?php }
                            if(isset($_GET['editChar']) and $_GET['editChar'] == $char['id']) { ?>
                        <div class="char charEdit">
                            <input type="text" name="editCharName" value="<?= $char['name'] ?>" placeholder="Новое название...">
                            <div class="charOptions">
                                <input type="hidden" name="editCharId" value="<?= $char['id'] ?>">
                                <button name="editCharConfirm">Сохранить</button>
                                <a href="?page=managePanel&categories&edit=<?= $char['category_id'] ?>">Отмена</a>
                            </div>
                        </div>
                        <?php if(isset($errors['editChar'])) { ?>
                        <h2><?= $errors['editChar'] ?></h2>
                        <?php } ?>
                            <?php } else { ?>
                        <div class="char">
                            <h4><?= $char['name'] ?></h4>
                            <div class="charOptions">
                                <a href="?page=managePanel&categories&edit=<?= $char['category_id'] ?>&editChar=<?= $char['id'] ?>">Редактировать</a>
                                <form method="post">
                                    <input type="hidden" name="deleteCharId" value="<?= $char['id'] ?>">
                                    <button name="deleteChar">Удалить</button>
                                </form>
                            </div>
                        </div>
                    <?php } $count += 1; } } ?>
                </div>

                <div class="createCategoryOptions">
                    <a href="?page=managePanel&categories">Отмена</a>
                    <button name="editCategory">Сохранить</button>
                </div>
            </div>
        </form>
    </div>
<?php } ?>