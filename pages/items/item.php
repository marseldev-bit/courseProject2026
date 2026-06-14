<?php
    $errors = [];
    $allReviews = false;

    $sql = "SELECT * FROM items WHERE id = ?";
    $stmt = $connect->prepare($sql);
    $stmt->execute([$_GET['id']]);
    $item = $stmt->fetch(PDO::FETCH_ASSOC);

    $sql = "SELECT imgPath FROM itemGallery WHERE item_id = ?";
    $stmt = $connect->prepare($sql);
    $stmt->execute([$item['id']]);
    $imgs = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $sql = "SELECT c.name, ic.value FROM itemChars ic JOIN characteristics c ON ic.char_id = c.id WHERE ic.item_id = ?";
    $stmt = $connect->prepare($sql);
    $stmt->execute([$item['id']]);
    $chars = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if(isset($_POST['addToCart']) and empty($USER['id'])) echo '<script>location.href="?page=item&id='.$item['id'].'&auth"</script>';
    elseif(isset($_POST['addToCart']) and !empty($USER['id'])) {
        $sql = "SELECT price FROM items WHERE id = ?";
        $stmt = $connect->prepare($sql);
        $stmt->execute([$_POST['cartItemId']]);
        $price = $stmt->fetch(PDO::FETCH_ASSOC);

        $sql = "INSERT INTO cart (user_id, item_id, quanity, price) VALUES (?, ?, ?, ?)";
        $stmt = $connect->prepare($sql);
        $stmt->execute([$USER['id'], $_POST['cartItemId'], 1, $price['price']]);
    }

    if(isset($_POST['removeFromCart'])) {
        $sql = "DELETE FROM cart WHERE user_id = ? AND item_id = ?";
        $stmt = $connect->prepare($sql);
        $stmt->execute([$USER['id'], $_POST['cartItemId']]);
    }

    $writeReview = false;
    $sql = "SELECT * FROM ordersDelivery WHERE item_id = ? AND user_id = ? AND status = ?";
    $stmt = $connect->prepare($sql);
    $stmt->execute([$item['id'], $USER['id'], 'Завершен']);
    $ordersDelivery = $stmt->fetch(PDO::FETCH_ASSOC);

    $sql = "SELECT * FROM ordersPickup WHERE item_id = ? AND user_id = ? AND status = ?";
    $stmt = $connect->prepare($sql);
    $stmt->execute([$item['id'], $USER['id'], 'Завершен']);
    $ordersPickup = $stmt->fetch(PDO::FETCH_ASSOC);

    $sql = "SELECT * FROM reviews WHERE item_id = ? AND user_id = ?";
    $stmt = $connect->prepare($sql);
    $stmt->execute([$item['id'], $USER['id']]);
    $issetReview = $stmt->fetch(PDO::FETCH_ASSOC);

    if(empty($issetReview) and (!empty($ordersDelivery) or !empty($ordersPickup))) $writeReview = true;

    if(isset($_POST['createReview'])) {
        if(empty($_POST['rate'])) $errors['rate'] = 'Укажите оценку товара';
        if(empty($_POST['text'])) $errors['text'] = 'Заполните текст отзыва';
        if(empty($errors)) {
            $sql = "INSERT INTO reviews (user_id, item_id, rate, text, status) VALUES (?, ?, ?, ?, ?)";
            $stmt = $connect->prepare($sql);
            $stmt->execute([$USER['id'], $item['id'], $_POST['rate'], $_POST['text'], 'На модерации']);
            echo '<script>location.href="?page=item&id='.$item['id'].'"</script>';
        }
    }

    if(isset($_POST['allReviews'])) {
        $allReviews = true;
        echo '<script>location.href="?page=item&id='.$item['id'].'#reviews"</script>';
    }
    elseif(isset($_POST['collapseReviews'])) {
        $allReviews = false;
        echo '<script>location.href="?page=item&id='.$item['id'].'#reviews"</script>';
    }
?>

<body class="itemPage">
    <script src="js/item.js" defer></script>
    <!-- Шапка -->
    <?php include('pages/components/header.php'); ?>
    <!-- Конец шапки -->

    <main>
        <!-- Карточка товара -->
        <h1 class="itemTitle cont"><?= $item['name'] ?></h1>
        <div class="itemBlock cont">
            <div class="itemMain">
                <div class="itemGallery">
                    <div class="itemImage">
                        <div class="itemSlides">
                        <?php foreach($imgs as $img) { ?>
                            <img src="<?= $img['imgPath'] ?>" alt="">
                        <?php } ?>
                        </div>
                        <div class="slider">
                            <svg class="back" width="39" height="14" viewBox="0 0 39 14" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M38.03 7.75813C38.5423 7.75813 38.9576 7.34285 38.9576 6.83057C38.9576 6.31829 38.5423 5.903 38.03 5.903V6.83057V7.75813ZM0.27158 6.17468C-0.0906563 6.53692 -0.0906563 7.12422 0.27158 7.48645L6.17456 13.3894C6.5368 13.7517 7.1241 13.7517 7.48634 13.3894C7.84857 13.0272 7.84857 12.4399 7.48634 12.0777L2.23924 6.83057L7.48634 1.58347C7.84857 1.22123 7.84857 0.633934 7.48634 0.271698C7.1241 -0.090539 6.5368 -0.090539 6.17456 0.271698L0.27158 6.17468ZM38.03 6.83057V5.903L0.927467 5.903V6.83057V7.75813L38.03 7.75813V6.83057Z"
                                    fill="#4E3822" />
                            </svg>
                            <svg class="forward" width="39" height="14" viewBox="0 0 39 14" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M38.03 7.75813C38.5423 7.75813 38.9576 7.34285 38.9576 6.83057C38.9576 6.31829 38.5423 5.903 38.03 5.903V6.83057V7.75813ZM0.27158 6.17468C-0.0906563 6.53692 -0.0906563 7.12422 0.27158 7.48645L6.17456 13.3894C6.5368 13.7517 7.1241 13.7517 7.48634 13.3894C7.84857 13.0272 7.84857 12.4399 7.48634 12.0777L2.23924 6.83057L7.48634 1.58347C7.84857 1.22123 7.84857 0.633934 7.48634 0.271698C7.1241 -0.090539 6.5368 -0.090539 6.17456 0.271698L0.27158 6.17468ZM38.03 6.83057V5.903L0.927467 5.903V6.83057V7.75813L38.03 7.75813V6.83057Z"
                                    fill="#4E3822" />
                            </svg>
                        </div>
                    </div>

                    <div class="itemImages">
                        <div class="itemImagesBlock">
                        <?php foreach ($imgs as $image) { ?>
                            <img src="<?= $image['imgPath'] ?>" alt="">
                        <?php } ?>
                        </div>
                    </div>
                </div>

                <form class="itemBuy" method="post">
                    <a
                    <?php if(empty($USER['id'])) { ?>href="?page=item&id=<?= $item['id'] ?>&auth"
                    <?php } else { ?>
                    href="?page=order&buy=<?= $item['id'] ?>"
                    <?php } ?>
                    >Купить • <?= $item['price'] ?> ₽</a>
                    <input type="hidden" name="cartItemId" value="<?= $item['id'] ?>">
                    <button 
                        <?php 
                        $sql = "SELECT id FROM cart WHERE user_id = ? AND item_id = ?";
                        $stmt = $connect->prepare($sql);
                        $stmt->execute([$USER['id'], $item['id']]); 
                        $inCart = $stmt->fetch(PDO::FETCH_ASSOC);
                        if($inCart) { ?> 
                            <button class="inCart" name="removeFromCart"><svg width="60" height="60" viewBox="0 0 60 60" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M49.1196 5.24316L21.3667 32.9959L10.8804 22.5096L0 33.3899L10.4864 43.8764L21.3667 54.7564L32.2468 43.8764L60 16.1233L49.1196 5.24316Z" fill="#4E3822"/>
                            </svg></button>
                            <?php } else { ?>
                            <button name="addToCart"><svg width="38" height="38" viewBox="0 0 38 38" fill="#FFF8E4"
                            xmlns="http://www.w3.org/2000/svg">
                            <path
                                    d="M35.5107 29.0172L32.0014 6.78654C31.8445 5.80023 31.3412 4.90212 30.5819 4.25333C29.8226 3.60454 28.857 3.24753 27.8583 3.24634H9.24519C8.24647 3.24753 7.28085 3.60454 6.52157 4.25333C5.76228 4.90212 5.25902 5.80023 5.10206 6.78654L1.59278 29.0172C1.5003 29.6151 1.53818 30.2259 1.70382 30.8078C1.86947 31.3898 2.15897 31.929 2.55247 32.3886C2.94598 32.8482 3.4342 33.2172 3.98367 33.4705C4.53314 33.7238 5.13087 33.8553 5.7359 33.856H31.3676C31.9726 33.8553 32.5704 33.7238 33.1198 33.4705C33.6693 33.2172 34.1575 32.8482 34.551 32.3886C34.9445 31.929 35.234 31.3898 35.3997 30.8078C35.5653 30.2259 35.6032 29.6151 35.5107 29.0172ZM26.2505 12.3055C25.3259 13.4587 24.1541 14.3895 22.8216 15.0291C21.489 15.6686 20.0298 16.0007 18.5517 16.0007C17.0737 16.0007 15.6145 15.6686 14.2819 15.0291C12.9494 14.3895 11.7776 13.4587 10.853 12.3055C10.722 12.1401 10.6249 11.9505 10.5673 11.7475C10.5096 11.5445 10.4925 11.3322 10.5169 11.1226C10.5413 10.913 10.6068 10.7103 10.7095 10.526C10.8123 10.3417 10.9503 10.1794 11.1158 10.0485C11.2812 9.91753 11.4709 9.82046 11.6738 9.7628C11.8768 9.70514 12.0892 9.68802 12.2987 9.71242C12.5083 9.73683 12.7111 9.80228 12.8954 9.90503C13.0796 10.0078 13.2419 10.1458 13.3728 10.3113C13.997 11.0837 14.786 11.7067 15.6821 12.1347C16.5782 12.5627 17.5587 12.7848 18.5517 12.7848C19.5448 12.7848 20.5253 12.5627 21.4214 12.1347C22.3175 11.7067 23.1065 11.0837 23.7306 10.3113C23.8616 10.1458 24.0238 10.0078 24.2081 9.90503C24.3924 9.80228 24.5952 9.73683 24.8048 9.71242C25.0143 9.68802 25.2267 9.70514 25.4297 9.7628C25.6326 9.82046 25.8223 9.91753 25.9877 10.0485C26.1532 10.1794 26.2912 10.3417 26.394 10.526C26.4967 10.7103 26.5622 10.913 26.5866 11.1226C26.611 11.3322 26.5939 11.5445 26.5362 11.7475C26.4785 11.9505 26.3815 12.1401 26.2505 12.3055Z" />
                            </svg></button>
                            <?php } ?>
                </form>
            </div>

            <div class="itemInfo">
                <p><?= $item['description'] ?></p>

                <div class="characteristics">
                    <?php foreach($chars as $char) { ?>
                    <div class="char">
                        <h2><?= $char['name'] ?></h2>
                        <div class="charLine"></div>
                        <h2><?= $char['value'] ?></h2>
                    </div>
                    <?php } ?>
                </div>

                <?php if($writeReview) { ?>
                <a href="?page=item&id=<?= $item['id'] ?>&review">Написать отзыв</a>
                <?php } ?>
            </div>
        </div>
        <!-- Конец карточки товара  -->

        <!-- Отзывы -->
        <?php
        $sql = "SELECT * FROM reviews WHERE item_id = ?";
        $stmt = $connect->prepare($sql);
        $stmt->execute([$item['id']]);
        $reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if(!empty($reviews)) { $count = 0;
        ?>
        <h1 class="title cont" id="reviews">Отзывы</h1>
        <div class="reviewsBlock cont">
            <div class="reviews">
                <?php foreach($reviews as $review) {
                $sql = "SELECT * FROM users WHERE id = ?";
                $stmt = $connect->prepare($sql);
                $stmt->execute([$review['user_id']]);
                $user = $stmt->fetch(PDO::FETCH_ASSOC);
                if(!$allReviews and $count > 2) break; ?>
                <div class="review">
                <div class="name">
                    <img src="<?= $user['imgPath'] ?>" alt="">
                    <h1><?= $user['name'] ?></h1>
                    <div class="rate">
                        <p><?= $review['rate'] ?></p>
                        <svg width="17" height="17" viewBox="0 0 17 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M15.282 7.01553C15.3514 6.66822 15.0736 6.25146 14.7263 6.25146L10.767 5.69576L8.96096 2.08376C8.8915 1.94484 8.82203 1.87538 8.68311 1.80592C8.3358 1.59753 7.91903 1.73646 7.71065 2.08376L5.97411 5.69576L2.0148 6.25146C1.80642 6.25146 1.66749 6.32092 1.59803 6.45984C1.32019 6.73769 1.32019 7.15446 1.59803 7.4323L4.44596 10.2108L3.75134 14.1701C3.75134 14.309 3.75134 14.4479 3.8208 14.5868C4.02919 14.9341 4.44596 15.0731 4.79326 14.8647L8.3358 12.9892L11.8783 14.8647C11.9478 14.9341 12.0867 14.9341 12.2257 14.9341H12.3646C12.7119 14.8647 12.9897 14.5174 12.9203 14.1006L12.2257 10.1413L15.0736 7.36284C15.2125 7.29338 15.282 7.15446 15.282 7.01553Z"
                                fill="#FFF8E4" />
                        </svg>
                    </div>
                </div>
                <p><?= $review['text'] ?></p>
                </div>
                <?php $count += 1; } ?>
            </div>

            <?php if(!$allReviews and count($reviews) > 3) { ?>
            <form method="post"><button class="allItems" name="allReviews">Все отзывы ↓</button></form>
            <?php } elseif($allReviews and count($reviews) > 3) { ?>
            <form method="post"><button class="allReviews" name="collapseReviews">Свернуть отзывы ↑</button></form>
            <?php } ?>
        </div>
        <?php } ?>
        <!-- Конец "Отзывы" -->

        <!-- Похожие товары -->
        <?php 
            $sql = "SELECT * FROM items WHERE category_id = ?";
            $stmt = $connect->prepare($sql);
            $stmt->execute([$item['category_id']]);
            $similarItems = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if(count($similarItems) > 1) {
        ?>
        <h1 class="title cont similarItemsTitle">Похожие товары</h1>
                <div class="catalogCards cont">
                    <?php
                    $count = 0;
                    $countMax = 3;
                    foreach($similarItems as $similarItem) {
                        if($count == $countMax) break;
                        $sql = "SELECT imgPath FROM itemGallery WHERE item_id = ?";
                        $stmt = $connect->prepare($sql);
                        $stmt->execute([$similarItem['id']]);
                        $img = $stmt->fetch(PDO::FETCH_ASSOC);

                        $sql = "SELECT * FROM categories WHERE id = ?"; 
                        $stmt = $connect->prepare($sql);
                        $stmt->execute([$similarItem['category_id']]);
                        $categoryName = $stmt->fetch(PDO::FETCH_ASSOC);

                        if($similarItem['id'] == $item['id']) continue;
                    ?>
                    <div class="card">
                        <div class="item">
                            <a href="?page=item&id=<?= $similarItem['id'] ?>"><img src="<?= $img['imgPath'] ?>" alt="Товар">
                            <h1><?= $similarItem['name'] ?></h1></a>
                            <p><?= $similarItem['shortDescription'] ?></p>
                            <div class="price">
                                <h2><?= $similarItem['price'] ?>₽</h2>
                                <a href="?page=catalog&category=<?= $categoryName['id'] ?>"><p><?= $categoryName['name'] ?></p></a>
                            </div>
                        </div>
                        <form class="buy" method="post">
                            <a
                            <?php if(empty($USER['id'])) { ?>href="?page=item&id=<?= $item['id'] ?>&auth"
                            <?php } else { ?>
                            href="?page=order&buy=<?= $similarItem['id'] ?>"
                            <?php } ?>
                            >Купить</a>
                            <input type="hidden" name="cartItemId" value="<?= $similarItem['id'] ?>">
                            <button 
                            <?php 
                            $sql = "SELECT id FROM cart WHERE user_id = ? AND item_id = ?";
                            $stmt = $connect->prepare($sql);
                            $stmt->execute([$USER['id'], $similarItem['id']]); 
                            $inCart = $stmt->fetch(PDO::FETCH_ASSOC);
                            if($inCart) { ?> 
                            <button class="inCart" name="removeFromCart">
                                <svg width="60" height="60" viewBox="0 0 60 60" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M49.1196 5.24316L21.3667 32.9959L10.8804 22.5096L0 33.3899L10.4864 43.8764L21.3667 54.7564L32.2468 43.8764L60 16.1233L49.1196 5.24316Z" fill="#4E3822"/>
                            </svg></button>
                            <?php } else { ?>
                            <button name="addToCart">
                                <svg width="38" height="38" viewBox="0 0 38 38" fill="#FFF8E4"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M35.5107 29.0172L32.0014 6.78654C31.8445 5.80023 31.3412 4.90212 30.5819 4.25333C29.8226 3.60454 28.857 3.24753 27.8583 3.24634H9.24519C8.24647 3.24753 7.28085 3.60454 6.52157 4.25333C5.76228 4.90212 5.25902 5.80023 5.10206 6.78654L1.59278 29.0172C1.5003 29.6151 1.53818 30.2259 1.70382 30.8078C1.86947 31.3898 2.15897 31.929 2.55247 32.3886C2.94598 32.8482 3.4342 33.2172 3.98367 33.4705C4.53314 33.7238 5.13087 33.8553 5.7359 33.856H31.3676C31.9726 33.8553 32.5704 33.7238 33.1198 33.4705C33.6693 33.2172 34.1575 32.8482 34.551 32.3886C34.9445 31.929 35.234 31.3898 35.3997 30.8078C35.5653 30.2259 35.6032 29.6151 35.5107 29.0172ZM26.2505 12.3055C25.3259 13.4587 24.1541 14.3895 22.8216 15.0291C21.489 15.6686 20.0298 16.0007 18.5517 16.0007C17.0737 16.0007 15.6145 15.6686 14.2819 15.0291C12.9494 14.3895 11.7776 13.4587 10.853 12.3055C10.722 12.1401 10.6249 11.9505 10.5673 11.7475C10.5096 11.5445 10.4925 11.3322 10.5169 11.1226C10.5413 10.913 10.6068 10.7103 10.7095 10.526C10.8123 10.3417 10.9503 10.1794 11.1158 10.0485C11.2812 9.91753 11.4709 9.82046 11.6738 9.7628C11.8768 9.70514 12.0892 9.68802 12.2987 9.71242C12.5083 9.73683 12.7111 9.80228 12.8954 9.90503C13.0796 10.0078 13.2419 10.1458 13.3728 10.3113C13.997 11.0837 14.786 11.7067 15.6821 12.1347C16.5782 12.5627 17.5587 12.7848 18.5517 12.7848C19.5448 12.7848 20.5253 12.5627 21.4214 12.1347C22.3175 11.7067 23.1065 11.0837 23.7306 10.3113C23.8616 10.1458 24.0238 10.0078 24.2081 9.90503C24.3924 9.80228 24.5952 9.73683 24.8048 9.71242C25.0143 9.68802 25.2267 9.70514 25.4297 9.7628C25.6326 9.82046 25.8223 9.91753 25.9877 10.0485C26.1532 10.1794 26.2912 10.3417 26.394 10.526C26.4967 10.7103 26.5622 10.913 26.5866 11.1226C26.611 11.3322 26.5939 11.5445 26.5362 11.7475C26.4785 11.9505 26.3815 12.1401 26.2505 12.3055Z" />
                                </svg>
                            </button>
                            <?php } ?>
                        </form>
                    </div>
                    <?php $count += 1; } ?>
                </div>
            <?php } ?>
        <!-- Конец "Похожие товары" -->
    </main>

    <!-- Подвал -->
    <?php include('pages/components/footerBlock.php'); ?>
    <!-- Конец подвала -->
</body>

<?php if(isset($_GET['auth'])) { ?>
<div class="modalExit active auth">
        <form method="post" class="modalExitWindow">
            <a href="?page=item&id=<?= $item['id'] ?>"><p>⨉</p></a>
            <div class="modalExitBody">
                <h1>Удобнее с аккаунтом</h1>
                <h2>Чтобы добавлять товары в корзину войдите или создайте профиль. Это займёт меньше минуты.</h2>
                <div class="modalExitOptions">
                    <a href="?page=login">Войти</a>
                    <a href="?page=reg">Регистрация</a>
                </div>
            </div>
        </form>
    </div>
<?php } ?>

<?php if(isset($_GET['review'])) { ?>
<div class="modalExit active writeReview">
        <form method="post" class="modalExitWindow">
            <a href="?page=item&id=<?= $item['id'] ?>"><p>⨉</p></a>
            <div class="modalExitBody">
                <h1>Ваш отзыв</h1>
                <div class="myReview">
                    <div class="rating">
                        <h2>Оценка: </h2>
                        <div class="stars">
                            <div class="star">
                                <svg width="27" height="26" viewBox="0 0 27 26" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M13.0254 0.94043C13.1116 0.914564 13.2021 0.920888 13.2998 0.979492L13.3301 0.99707L13.3623 1.01367C13.4375 1.05129 13.4441 1.06324 13.4336 1.05273C13.4231 1.0422 13.435 1.04875 13.4727 1.12402L16.8848 7.94629L17.1016 8.38184L17.584 8.44922L25.0625 9.49902L25.127 9.50781H25.1914C25.1344 9.50781 25.1767 9.48102 25.2549 9.5918C25.2882 9.63896 25.3124 9.69346 25.3252 9.74512C25.338 9.79682 25.3357 9.83028 25.333 9.84375L25.3184 9.90918L25.3037 9.91797L25.2002 10.0186L19.8213 15.2666L19.4717 15.6084L19.5557 16.0898L20.8662 23.5596V23.5605C20.9073 23.8072 20.7684 23.9933 20.6074 24.0566H20.4678C20.3994 24.0566 20.3487 24.0562 20.3027 24.0547C20.2957 24.0545 20.2893 24.053 20.2832 24.0527L20.2451 24.0332L13.5537 20.4902L13.1201 20.2607L12.6875 20.4902L5.99609 24.0332L5.97363 24.0449L5.95215 24.0576C5.85477 24.1159 5.7647 24.1214 5.67871 24.0957C5.59138 24.0695 5.48966 24.0016 5.40723 23.8789C5.39621 23.8405 5.39019 23.7734 5.38867 23.6074L6.68555 16.2207L6.76953 15.7393L6.41992 15.3975L1.04785 10.1572H1.04883C0.88575 9.9941 0.885712 9.79398 1.04883 9.63086L1.1543 9.52441L1.16113 9.50879C1.16723 9.50864 1.17373 9.50781 1.18066 9.50781H1.24512L1.30957 9.49902L8.78809 8.44922L9.2793 8.38086L9.49414 7.93359L12.7471 1.16602C12.8304 1.03745 12.9359 0.967357 13.0254 0.94043Z" stroke="#4E3822" stroke-width="1.85231"/>
                                </svg>
                            </div>

                            <div class="star">
                                <svg width="27" height="26" viewBox="0 0 27 26" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M13.0254 0.94043C13.1116 0.914564 13.2021 0.920888 13.2998 0.979492L13.3301 0.99707L13.3623 1.01367C13.4375 1.05129 13.4441 1.06324 13.4336 1.05273C13.4231 1.0422 13.435 1.04875 13.4727 1.12402L16.8848 7.94629L17.1016 8.38184L17.584 8.44922L25.0625 9.49902L25.127 9.50781H25.1914C25.1344 9.50781 25.1767 9.48102 25.2549 9.5918C25.2882 9.63896 25.3124 9.69346 25.3252 9.74512C25.338 9.79682 25.3357 9.83028 25.333 9.84375L25.3184 9.90918L25.3037 9.91797L25.2002 10.0186L19.8213 15.2666L19.4717 15.6084L19.5557 16.0898L20.8662 23.5596V23.5605C20.9073 23.8072 20.7684 23.9933 20.6074 24.0566H20.4678C20.3994 24.0566 20.3487 24.0562 20.3027 24.0547C20.2957 24.0545 20.2893 24.053 20.2832 24.0527L20.2451 24.0332L13.5537 20.4902L13.1201 20.2607L12.6875 20.4902L5.99609 24.0332L5.97363 24.0449L5.95215 24.0576C5.85477 24.1159 5.7647 24.1214 5.67871 24.0957C5.59138 24.0695 5.48966 24.0016 5.40723 23.8789C5.39621 23.8405 5.39019 23.7734 5.38867 23.6074L6.68555 16.2207L6.76953 15.7393L6.41992 15.3975L1.04785 10.1572H1.04883C0.88575 9.9941 0.885712 9.79398 1.04883 9.63086L1.1543 9.52441L1.16113 9.50879C1.16723 9.50864 1.17373 9.50781 1.18066 9.50781H1.24512L1.30957 9.49902L8.78809 8.44922L9.2793 8.38086L9.49414 7.93359L12.7471 1.16602C12.8304 1.03745 12.9359 0.967357 13.0254 0.94043Z" stroke="#4E3822" stroke-width="1.85231"/>
                                </svg>
                            </div>

                            <div class="star">
                                <svg width="27" height="26" viewBox="0 0 27 26" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M13.0254 0.94043C13.1116 0.914564 13.2021 0.920888 13.2998 0.979492L13.3301 0.99707L13.3623 1.01367C13.4375 1.05129 13.4441 1.06324 13.4336 1.05273C13.4231 1.0422 13.435 1.04875 13.4727 1.12402L16.8848 7.94629L17.1016 8.38184L17.584 8.44922L25.0625 9.49902L25.127 9.50781H25.1914C25.1344 9.50781 25.1767 9.48102 25.2549 9.5918C25.2882 9.63896 25.3124 9.69346 25.3252 9.74512C25.338 9.79682 25.3357 9.83028 25.333 9.84375L25.3184 9.90918L25.3037 9.91797L25.2002 10.0186L19.8213 15.2666L19.4717 15.6084L19.5557 16.0898L20.8662 23.5596V23.5605C20.9073 23.8072 20.7684 23.9933 20.6074 24.0566H20.4678C20.3994 24.0566 20.3487 24.0562 20.3027 24.0547C20.2957 24.0545 20.2893 24.053 20.2832 24.0527L20.2451 24.0332L13.5537 20.4902L13.1201 20.2607L12.6875 20.4902L5.99609 24.0332L5.97363 24.0449L5.95215 24.0576C5.85477 24.1159 5.7647 24.1214 5.67871 24.0957C5.59138 24.0695 5.48966 24.0016 5.40723 23.8789C5.39621 23.8405 5.39019 23.7734 5.38867 23.6074L6.68555 16.2207L6.76953 15.7393L6.41992 15.3975L1.04785 10.1572H1.04883C0.88575 9.9941 0.885712 9.79398 1.04883 9.63086L1.1543 9.52441L1.16113 9.50879C1.16723 9.50864 1.17373 9.50781 1.18066 9.50781H1.24512L1.30957 9.49902L8.78809 8.44922L9.2793 8.38086L9.49414 7.93359L12.7471 1.16602C12.8304 1.03745 12.9359 0.967357 13.0254 0.94043Z" stroke="#4E3822" stroke-width="1.85231"/>
                                </svg>
                            </div>

                            <div class="star">
                                <svg width="27" height="26" viewBox="0 0 27 26" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M13.0254 0.94043C13.1116 0.914564 13.2021 0.920888 13.2998 0.979492L13.3301 0.99707L13.3623 1.01367C13.4375 1.05129 13.4441 1.06324 13.4336 1.05273C13.4231 1.0422 13.435 1.04875 13.4727 1.12402L16.8848 7.94629L17.1016 8.38184L17.584 8.44922L25.0625 9.49902L25.127 9.50781H25.1914C25.1344 9.50781 25.1767 9.48102 25.2549 9.5918C25.2882 9.63896 25.3124 9.69346 25.3252 9.74512C25.338 9.79682 25.3357 9.83028 25.333 9.84375L25.3184 9.90918L25.3037 9.91797L25.2002 10.0186L19.8213 15.2666L19.4717 15.6084L19.5557 16.0898L20.8662 23.5596V23.5605C20.9073 23.8072 20.7684 23.9933 20.6074 24.0566H20.4678C20.3994 24.0566 20.3487 24.0562 20.3027 24.0547C20.2957 24.0545 20.2893 24.053 20.2832 24.0527L20.2451 24.0332L13.5537 20.4902L13.1201 20.2607L12.6875 20.4902L5.99609 24.0332L5.97363 24.0449L5.95215 24.0576C5.85477 24.1159 5.7647 24.1214 5.67871 24.0957C5.59138 24.0695 5.48966 24.0016 5.40723 23.8789C5.39621 23.8405 5.39019 23.7734 5.38867 23.6074L6.68555 16.2207L6.76953 15.7393L6.41992 15.3975L1.04785 10.1572H1.04883C0.88575 9.9941 0.885712 9.79398 1.04883 9.63086L1.1543 9.52441L1.16113 9.50879C1.16723 9.50864 1.17373 9.50781 1.18066 9.50781H1.24512L1.30957 9.49902L8.78809 8.44922L9.2793 8.38086L9.49414 7.93359L12.7471 1.16602C12.8304 1.03745 12.9359 0.967357 13.0254 0.94043Z" stroke="#4E3822" stroke-width="1.85231"/>
                                </svg>
                            </div>

                            <div class="star">
                                <svg width="27" height="26" viewBox="0 0 27 26" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M13.0254 0.94043C13.1116 0.914564 13.2021 0.920888 13.2998 0.979492L13.3301 0.99707L13.3623 1.01367C13.4375 1.05129 13.4441 1.06324 13.4336 1.05273C13.4231 1.0422 13.435 1.04875 13.4727 1.12402L16.8848 7.94629L17.1016 8.38184L17.584 8.44922L25.0625 9.49902L25.127 9.50781H25.1914C25.1344 9.50781 25.1767 9.48102 25.2549 9.5918C25.2882 9.63896 25.3124 9.69346 25.3252 9.74512C25.338 9.79682 25.3357 9.83028 25.333 9.84375L25.3184 9.90918L25.3037 9.91797L25.2002 10.0186L19.8213 15.2666L19.4717 15.6084L19.5557 16.0898L20.8662 23.5596V23.5605C20.9073 23.8072 20.7684 23.9933 20.6074 24.0566H20.4678C20.3994 24.0566 20.3487 24.0562 20.3027 24.0547C20.2957 24.0545 20.2893 24.053 20.2832 24.0527L20.2451 24.0332L13.5537 20.4902L13.1201 20.2607L12.6875 20.4902L5.99609 24.0332L5.97363 24.0449L5.95215 24.0576C5.85477 24.1159 5.7647 24.1214 5.67871 24.0957C5.59138 24.0695 5.48966 24.0016 5.40723 23.8789C5.39621 23.8405 5.39019 23.7734 5.38867 23.6074L6.68555 16.2207L6.76953 15.7393L6.41992 15.3975L1.04785 10.1572H1.04883C0.88575 9.9941 0.885712 9.79398 1.04883 9.63086L1.1543 9.52441L1.16113 9.50879C1.16723 9.50864 1.17373 9.50781 1.18066 9.50781H1.24512L1.30957 9.49902L8.78809 8.44922L9.2793 8.38086L9.49414 7.93359L12.7471 1.16602C12.8304 1.03745 12.9359 0.967357 13.0254 0.94043Z" stroke="#4E3822" stroke-width="1.85231"/>
                                </svg>
                            </div>
                        </div>
                        <?php if(isset($errors['rate'])) { ?><h3><?= $errors['rate'] ?></h3><?php } ?>
                        <input type="hidden" name="rate" 
                        <?php if(isset($_POST['rate'])) { ?>value="<?= $_POST['rate'] ?>"<?php } ?>>
                    </div>

                    <h2>Текст отзыва:</h2>
                    <textarea placeholder="Опишите впечатления..." name="text"><?php if(isset($_POST['text'])) { ?><?= $_POST['text'] ?><?php } ?></textarea>
                    <?php if(isset($errors['text'])) { ?><h3><?= $errors['text'] ?></h3><?php } ?>
                </div>
                <div class="modalExitOptions">
                    <a href="?page=item&id=<?= $item['id'] ?>">Отмена</a>
                    <button name="createReview">Подтвердить</button>
                </div>
            </div>
        </form>
    </div>
<?php } ?>