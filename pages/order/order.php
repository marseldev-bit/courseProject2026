<?php
    $errors = [];

    $predictDate = date('Y-m-d', strtotime('+10 days'));
    $sql = "SELECT * FROM cart WHERE user_id = ?";
    $stmt = $connect->prepare($sql);
    $stmt->execute([$USER['id']]);
    $cartItems = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $itemsCount = 0;
    $totalCost = 0;
    foreach($cartItems as $itemPrice) {
        $totalCost += $itemPrice['price'] * $itemPrice['quanity'];
    }

    if(isset($_POST['createOrder'])) {
        if(!isset($_GET['pickup'])) {
            if(empty($_POST['address'])) $errors['address'] = 'Укажите адрес доставки';
            if(empty($_POST['date'])) $errors['date'] = 'Укажите дату доставки';
            if(empty($_POST['phone'])) $errors['phone'] = 'Укажите свой номер телефона';
            elseif(strlen($_POST['phone']) > 11 or strlen($_POST['phone']) < 11 or $_POST['phone'][0] != 8 or $_POST['phone'][1] != 9) $errors['phone'] = 'Неверный формат'; 
            elseif(empty($errors)) {
                if(empty($_POST['comment'])) {
                    foreach($cartItems as $cartItem) {
                        $sql = "INSERT INTO ordersDelivery (user_id, item_id, address, date, picked_date, time, phone, quanity, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
                        $stmt = $connect->prepare($sql);
                        $stmt->execute([$USER['id'], $cartItem['item_id'], $_POST['address'], date('Y-m-d'), $_POST['date'], $_POST['time'], $_POST['phone'], $cartItem['quanity'], 'В пути']);
                    }
                }
                else {
                    foreach($cartItems as $cartItem) {
                        $sql = "INSERT INTO ordersDelivery (user_id, item_id, address, date, picked_date, time, phone, comment, quanity, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                        $stmt = $connect->prepare($sql);
                        $stmt->execute([$USER['id'], $cartItem['item_id'], $_POST['address'], date('Y-m-d'), $_POST['date'], $_POST['time'], $_POST['phone'], $_POST['comment'], $cartItem['quanity'], 'В пути']);
                    }
                }

                $sql = "DELETE FROM cart WHERE user_id = ?";
                $stmt = $connect->prepare($sql);
                $stmt->execute([$USER['id']]);

                echo '<script>location.href="?page=orderSuccess"</script>';
            }
        }
        else {
            if(empty($_POST['location'])) $errors['location'] = 'Укажите магазин';
            if(empty($_POST['phone'])) $errors['phone'] = 'Укажите свой номер телефона';
            elseif(strlen($_POST['phone']) > 11 or strlen($_POST['phone']) < 11 or $_POST['phone'][0] != 8 or $_POST['phone'][1] != 9) $errors['phone'] = 'Неверный формат'; 
            elseif(empty($errors)) {
                $code = rand(1000, 9999);
                if(empty($_POST['comment'])) {
                    foreach($cartItems as $cartItem) {
                        $sql = "INSERT INTO ordersPickup (user_id, item_id, date, storeAddress, phone, predict_date, quanity, status, code) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
                        $stmt = $connect->prepare($sql);
                        $stmt->execute([$USER['id'], $cartItem['item_id'], date('Y-m-d'), $_POST['location'], $_POST['phone'], $predictDate, $cartItem['quanity'], 'В пути', $code]);
                    }
                }
                else {
                    foreach($cartItems as $cartItem) {
                        $sql = "INSERT INTO ordersPickup (user_id, item_id, date, storeAddress, phone, comment, predict_date, quanity, status, code) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                        $stmt = $connect->prepare($sql);
                        $stmt->execute([$USER['id'], $cartItem['item_id'], date('Y-m-d'), $_POST['location'], $_POST['phone'], $_POST['comment'], $predictDate, $cartItem['quanity'], 'В пути', $code]);
                    }
                }

                $sql = "DELETE FROM cart WHERE user_id = ?";
                $stmt = $connect->prepare($sql);
                $stmt->execute([$USER['id']]);

                echo '<script>location.href="?page=orderSuccess"</script>';
            }
        }
    }
?>

<body class="orderPage">
    <!-- Шапка -->
    <?php include('pages/components/header.php'); ?>
    <!-- Конец шапки -->

    <main>
        <!-- Оформление заказа -->
        <h1 class="title cont">Оформление заказа</h1>
        <div class="cart cont">
            <?php foreach($cartItems as $cartItem) {
                $sql = "SELECT * FROM items WHERE id = ?";
                $stmt = $connect->prepare($sql);
                $stmt->execute([$cartItem['item_id']]);
                $item = $stmt->fetch(PDO::FETCH_ASSOC);

                $sql = "SELECT imgPath FROM itemGallery WHERE item_id = ?";
                $stmt = $connect->prepare($sql);
                $stmt->execute([$item['id']]);
                $img = $stmt->fetch(PDO::FETCH_ASSOC);
                if($itemsCount > 0) { ?>
                <div class="separateLine"></div>
                <?php } ?>
            <div class="cartItem">
                <div class="cartItemBlock">
                    <img src="<?= $img['imgPath'] ?>" alt="">
                    <div class="cartItemInfo">
                        <h1><?= $item['name'] ?></h1>
                        <div class="cartItemInfoBlock">
                            <p><?= $cartItem['price'] * $cartItem['quanity'] ?>₽</p>
                            <h3><?= $item['quanity'] ?> шт</h3>
                        </div>
                    </div>
                </div>

                <h2><?= $cartItem['quanity'] ?> шт</h2>
            </div>
            <?php $itemsCount += 1; } ?>
        </div>

        <h1 class="title cont" id="orderData">Данные заказа</h1>
        <div class="orderOptions cont">
            <a href="?page=order#orderData" id="delivery" <?php if(!isset($_GET['pickup'])) { ?>class="active"<?php } ?>>Доставка</a>
            <a href="?page=order&pickup#orderData" id="pickup" <?php if(isset($_GET['pickup'])) { ?>class="active"<?php } ?>>Самовывоз</a>
        </div>
        
        <form method="post" class="cont">
            <?php
            if(isset($_GET['pickup'])) include('orderPickup.php');
            else include('orderDelivery.php');
            ?>

            <div class="confirmOrder cont">
                <p>К оплате: <span><?= $totalCost ?>₽</span></p>
                <button name="createOrder">Оформить заказ</button>
            </div>
        </form>
        <!-- Конец "Оформление заказа" -->
    </main>

    <!-- Подвал -->
    <?php include('pages/components/footerBlock.php'); ?>
    <!-- Конец подвала -->
</body>