<body class="orderSuccessPage">
    <!-- Шапка -->
    <?php include('pages/components/header.php'); ?>
    <!-- Конец шапки -->

    <main class="cont">
        <h2>Оплата прошла успешно</h2>
        <div class="thanksgiving">
            <h1>Спасибо за то, что выбрали нас</h1>
            <svg width="60" height="60" viewBox="0 0 60 60" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M45 7.5C45 7.5 33.75 7.5 30 18.75C26.25 7.5 15 7.5 15 7.5C6.75 7.5 0 14.25 0 22.5C0 37.875 30 56.25 30 56.25C30 56.25 60 37.5 60 22.5C60 14.25 53.25 7.5 45 7.5Z" fill="#4E3822"/>
            </svg>
        </div>

        <div class="orderSuccessOptions">
            <a href="?page=profile&orders">К моим заказам</a>
            <a href="?">На главную</a>
        </div>
    </main>

    <!-- Подвал -->
    <?php include('pages/components/footerBlock.php'); ?>
    <!-- Конец подвала -->
</body>