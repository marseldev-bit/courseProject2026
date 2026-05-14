<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../assets/images/favicon.png" type="image/x-icon">
    <link rel="stylesheet" href="style.css">
    <script src="../js/managePanel.js" defer></script>
    <title>Кинг</title>
</head>

<body class="managePage">
    <!-- Шапка -->
    <?php include('header.php'); ?>
    <!-- Конец шапки -->

    <main>
        <!-- Панель управления -->
        <h1 class="title cont">Панель управления</h1>
        <div class="manageOptions cont">
            <button id="manageItems">Товары</button>
            <button id="managePromotions">Акции</button>
            <button id="manageReviews">Отзывы</button>
            <button id="manageOrders">Заказы</button>
            <button id="manageCategories">Категории</button>
        </div>

        <?php 
            include('manageItems.php');
            include('managePromotions.php');
            include('manageReviews.php');
            include('manageOrders.php');
            include('manageCategories.php');
        ?>
        <!-- Конец "Панель управления" -->
    </main>

    <!-- Подвал -->
    <?php include('footerBlock.php'); ?>
    <!-- Конец подвала -->
</body>

</html>