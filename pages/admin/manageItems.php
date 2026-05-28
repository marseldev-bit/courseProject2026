<?php
    $sql = "SELECT * FROM items";
    $stmt = $connect->prepare($sql);
    $stmt->execute();
    $items = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if(isset($_POST['deleteItem'])) {
        $sql = "DELETE FROM items WHERE id = ?";
        $stmt = $connect->prepare($sql);
        $stmt->execute([$_POST['deleteItemId']]);

        $sql = "SELECT imgPath FROM itemGallery WHERE item_id = ?";
        $stmt = $connect->prepare($sql);
        $stmt->execute([$_POST['deleteItemId']]);
        $imgPath = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach($imgPath as $img) {
            $path = $_SERVER['DOCUMENT_ROOT'].'/'.$img['imgPath'];
            if(file_exists($path)) unlink($path);
        }

        $sql = "DELETE FROM itemGallery WHERE item_id = ?";
        $stmt = $connect->prepare($sql);
        $stmt->execute([$_POST['deleteItemId']]);

        $sql = "DELETE FROM itemChars WHERE item_id = ?";
        $stmt = $connect->prepare($sql);
        $stmt->execute([$_POST['deleteItemId']]);
        echo '<script>location.href="?page=managePanel"</script>';
    }
?>

<div class="manageItems cont manageSection active">
    <div class="catalog">
        <div class="catalogHeader">
            <form class="filters" method="post">
                <div class="search">
                    <input type="text" placeholder="Название товара..." name="itemName">
                    <button type="submit" name="searchItem"><img src="../assets/images/search.png"></button>
                </div>

                <div class="search">
                    <input type="number" placeholder="ID товара..." name="itemId">
                    <button type="submit" name="searchItem"><img src="../assets/images/search.png"></button>
                </div>

                <a href="?page=createItem">Добавить товар</a>
            </form>
        </div>

        <div class="catalogBlock">
            <?php
            if(isset($_POST['searchItem'])) {
                if(!empty($_POST['itemName'])) $filterName = mb_strtolower(trim($_POST['itemName']));
                if(!empty($_POST['itemId'])) $filterId = $_POST['itemId']; }
            foreach($items as $item) { 
                $sql = "SELECT imgPath FROM itemGallery WHERE item_id = ?";
                $stmt = $connect->prepare($sql);
                $stmt->execute([$item['id']]);
                $img = $stmt->fetch(PDO::FETCH_ASSOC);

                $sql = "SELECT name FROM categories WHERE id = ?"; 
                $stmt = $connect->prepare($sql);
                $stmt->execute([$item['category_id']]);
                $category = $stmt->fetch(PDO::FETCH_ASSOC);
                if(isset($filterName) and mb_strpos(mb_strtolower(trim($item['name'])), $filterName) === false) continue;
                elseif(isset($filterId) and $filterId != $item['id']) continue;
                ?>
            <div class="card">
                <div class="item">
                    <a href="?page=item&id=<?= $item['id'] ?>"><img src="<?= $img['imgPath'] ?>" alt="Товар">
                        <h1><?= $item['name'] ?></h1>
                    </a>
                    <p><?= $item['shortDescription'] ?></p>
                    <div class="price">
                        <h2><?= $item['price'] ?>₽</h2>
                        <a href="category.php">
                            <p><?= $category['name'] ?></p>
                        </a>
                    </div>
                </div>
                <div class="buy">
                    <a href="?page=editItem&id=<?= $item['id'] ?>">Редактировать</a>
                    <a href="?page=managePanel&deleteItem=<?= $item['id'] ?>"><svg width="30" height="30" viewBox="0 0 30 30" fill="#FF0000"
                            xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M28.75 4.28571H21.25V2.67857C21.25 1.96817 20.9208 1.28686 20.3347 0.784535C19.7487 0.282206 18.9538 0 18.125 0H11.875C11.0462 0 10.2513 0.282206 9.66529 0.784535C9.07924 1.28686 8.75 1.96817 8.75 2.67857V4.28571H1.25C0.918479 4.28571 0.600537 4.3986 0.366117 4.59953C0.131696 4.80046 0 5.07298 0 5.35714C0 5.6413 0.131696 5.91383 0.366117 6.11476C0.600537 6.31569 0.918479 6.42857 1.25 6.42857H2.57812L4.0625 26.8473C4.17344 28.6453 5.78125 30 7.8125 30H22.1875C24.2289 30 25.8047 28.6754 25.9375 26.8527L27.4219 6.42857H28.75C29.0815 6.42857 29.3995 6.31569 29.6339 6.11476C29.8683 5.91383 30 5.6413 30 5.35714C30 5.07298 29.8683 4.80046 29.6339 4.59953C29.3995 4.3986 29.0815 4.28571 28.75 4.28571ZM10.0445 25.7143H10C9.67605 25.7145 9.36468 25.6068 9.1315 25.4141C8.89831 25.2213 8.76154 24.9585 8.75 24.681L8.125 9.68103C8.11319 9.39687 8.23356 9.12032 8.45963 8.91223C8.6857 8.70414 8.99895 8.58155 9.33047 8.57143C9.66199 8.56131 9.98462 8.66448 10.2274 8.85825C10.4702 9.05203 10.6132 9.32053 10.625 9.60469L11.25 24.6047C11.256 24.7454 11.2295 24.8858 11.1721 25.0177C11.1147 25.1497 11.0276 25.2706 10.9156 25.3737C10.8037 25.4767 10.6691 25.5599 10.5196 25.6183C10.3702 25.6767 10.2087 25.7094 10.0445 25.7143ZM16.25 24.6429C16.25 24.927 16.1183 25.1995 15.8839 25.4005C15.6495 25.6014 15.3315 25.7143 15 25.7143C14.6685 25.7143 14.3505 25.6014 14.1161 25.4005C13.8817 25.1995 13.75 24.927 13.75 24.6429V9.64286C13.75 9.3587 13.8817 9.08617 14.1161 8.88524C14.3505 8.68431 14.6685 8.57143 15 8.57143C15.3315 8.57143 15.6495 8.68431 15.8839 8.88524C16.1183 9.08617 16.25 9.3587 16.25 9.64286V24.6429ZM18.75 4.28571H11.25V2.67857C11.2491 2.608 11.2646 2.53797 11.2957 2.47261C11.3267 2.40725 11.3727 2.34787 11.431 2.29796C11.4892 2.24806 11.5585 2.20863 11.6347 2.18199C11.711 2.15535 11.7927 2.14205 11.875 2.14286H18.125C18.2073 2.14205 18.289 2.15535 18.3653 2.18199C18.4415 2.20863 18.5108 2.24806 18.569 2.29796C18.6273 2.34787 18.6733 2.40725 18.7043 2.47261C18.7354 2.53797 18.7509 2.608 18.75 2.67857V4.28571ZM21.25 24.681C21.2385 24.9585 21.1017 25.2213 20.8685 25.4141C20.6353 25.6068 20.3239 25.7145 20 25.7143H19.9547C19.7906 25.7093 19.6292 25.6766 19.4798 25.6181C19.3305 25.5596 19.196 25.4765 19.0841 25.3734C18.9722 25.2704 18.8851 25.1495 18.8278 25.0176C18.7705 24.8857 18.744 24.7454 18.75 24.6047L19.375 9.60469C19.3808 9.46398 19.419 9.32565 19.4872 9.19757C19.5554 9.0695 19.6524 8.9542 19.7726 8.85825C19.8928 8.76231 20.0339 8.68759 20.1878 8.63838C20.3417 8.58917 20.5054 8.56642 20.6695 8.57143C20.8337 8.57644 20.9951 8.60912 21.1445 8.66759C21.2939 8.72607 21.4284 8.8092 21.5404 8.91223C21.6523 9.01527 21.7395 9.13619 21.7969 9.2681C21.8543 9.40001 21.8808 9.54033 21.875 9.68103L21.25 24.681Z" />
                        </svg></a>
                </div>
            </div>
            <?php } ?>
        </div>
    </div>
</div>

<?php if(isset($_GET['deleteItem'])) { 
    $sql = "SELECT name FROM items WHERE id = ?";
    $stmt = $connect->prepare($sql);
    $stmt->execute([$_GET['deleteItem']]);
    $itemName = $stmt->fetch(PDO::FETCH_ASSOC); ?>
<div class="modalExit active deleteCategory">
        <form method="post" class="modalExitWindow">
            <a href="?page=managePanel"><p>⨉</p></a>
            <div class="modalExitBody">
                <h1>Удалить товар <?= $itemName['name'] ?>?</h1>
                <div class="modalExitOptions">
                    <a href="?page=managePanel">Отмена</a>
                    <input type="hidden" value="<?= $_GET['deleteItem'] ?>" name="deleteItemId">
                    <button name="deleteItem">Удалить</button>
                </div>
            </div>
        </form>
    </div>
<?php } ?>