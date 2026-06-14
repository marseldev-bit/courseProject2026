-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Июн 05 2026 г., 13:12
-- Версия сервера: 8.0.30
-- Версия PHP: 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `King`
--

-- --------------------------------------------------------

--
-- Структура таблицы `cart`
--

CREATE TABLE `cart` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `item_id` int NOT NULL,
  `quanity` int NOT NULL,
  `price` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `categories`
--

CREATE TABLE `categories` (
  `id` int NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `categories`
--

INSERT INTO `categories` (`id`, `name`) VALUES
(1, 'Шахматы'),
(2, 'Доски'),
(3, 'Часы'),
(5, 'Фигуры');

-- --------------------------------------------------------

--
-- Структура таблицы `characteristics`
--

CREATE TABLE `characteristics` (
  `id` int NOT NULL,
  `category_id` int NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `characteristics`
--

INSERT INTO `characteristics` (`id`, `category_id`, `name`) VALUES
(17, 1, 'Материал доски'),
(18, 1, 'Размер доски'),
(19, 1, 'Вес комплекта'),
(20, 2, 'Материал доски'),
(21, 2, 'Размер клетки (мм)'),
(22, 2, 'Размер доски (см)'),
(23, 2, 'Толщина доски (мм)'),
(24, 3, 'Тип часов'),
(25, 3, 'Функция задержки'),
(26, 3, 'Питание'),
(27, 3, 'Точность хода'),
(28, 5, 'Материал фигур'),
(29, 5, 'Высота короля (см)'),
(30, 5, 'Вес комплекта (кг)'),
(31, 5, 'Стиль'),
(32, 1, 'Материал фигур');

-- --------------------------------------------------------

--
-- Структура таблицы `itemChars`
--

CREATE TABLE `itemChars` (
  `id` int NOT NULL,
  `item_id` int NOT NULL,
  `char_id` int NOT NULL,
  `value` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `itemChars`
--

INSERT INTO `itemChars` (`id`, `item_id`, `char_id`, `value`) VALUES
(74, 22, 17, 'Берёза'),
(75, 22, 18, '40 × 40 см'),
(76, 22, 19, '1,2 кг'),
(89, 26, 24, 'Цифровые'),
(90, 26, 25, 'Есть'),
(91, 26, 26, 'Батарейки'),
(92, 26, 27, '±1 сек/сутки'),
(97, 22, 32, 'Береза'),
(100, 28, 17, 'Дуб'),
(101, 28, 18, '40 × 40 см'),
(102, 28, 19, '3,2 кг'),
(103, 28, 32, 'Дуб'),
(104, 29, 17, 'Морёный дуб'),
(105, 29, 18, '42 × 42 см'),
(106, 29, 19, '4,1 кг'),
(107, 29, 32, 'Морёный дуб'),
(108, 30, 17, 'Дерево'),
(109, 30, 18, '15 × 13 см'),
(110, 30, 19, '0,6 кг'),
(111, 30, 32, 'Пластик'),
(112, 31, 20, 'Морёный дуб'),
(113, 31, 21, '32 × 32 мм'),
(114, 31, 22, '50 × 50 см'),
(115, 31, 23, '15 мм'),
(116, 32, 20, 'Дуб'),
(117, 32, 21, '25 × 25 мм'),
(118, 32, 22, '40 × 40 см'),
(119, 32, 23, '12 мм'),
(120, 33, 20, 'Морёный дуб'),
(121, 33, 21, '28 × 28 мм'),
(122, 33, 22, '45 × 45 см'),
(123, 33, 23, '18 мм'),
(124, 34, 20, 'Дерево'),
(125, 34, 21, '30 × 30 мм'),
(126, 34, 22, '43 × 43 см'),
(127, 34, 23, '14 мм'),
(128, 35, 28, 'Дерево'),
(129, 35, 29, '10,5 см'),
(130, 35, 30, '1,8 кг'),
(131, 35, 31, 'Классический'),
(132, 36, 28, 'Дуб'),
(133, 36, 29, '10 см'),
(134, 36, 30, '1,9 кг'),
(135, 36, 31, 'Стаунтон'),
(136, 37, 28, 'Граб'),
(137, 37, 29, '11 см'),
(138, 37, 30, '1,85 кг'),
(139, 37, 31, 'Классический'),
(140, 38, 28, 'Дерево'),
(141, 38, 29, '7 см'),
(142, 38, 30, '0,7 кг'),
(143, 38, 31, 'Стаунтон'),
(144, 39, 24, 'Цифровые'),
(145, 39, 25, 'Есть'),
(146, 39, 26, 'Батарейки'),
(147, 39, 27, '±1 секунда в день');

-- --------------------------------------------------------

--
-- Структура таблицы `itemGallery`
--

CREATE TABLE `itemGallery` (
  `id` int NOT NULL,
  `item_id` int NOT NULL,
  `imgPath` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `itemGallery`
--

INSERT INTO `itemGallery` (`id`, `item_id`, `imgPath`) VALUES
(51, 22, 'assets/itemGallery/22_1779978261_card1.png'),
(52, 22, 'assets/itemGallery/22_1779978261_item2.png'),
(53, 22, 'assets/itemGallery/22_1779978261_item3.png'),
(54, 22, 'assets/itemGallery/22_1779978261_item4.png'),
(58, 26, 'assets/itemGallery/26_1779978657_card5.png'),
(66, 28, 'assets/itemGallery/28_1780642852_n1.png'),
(67, 28, 'assets/itemGallery/28_1780642856_n2.png'),
(68, 28, 'assets/itemGallery/28_1780642860_n3.png'),
(69, 28, 'assets/itemGallery/28_1780642863_n4.png'),
(70, 29, 'assets/itemGallery/29_1780643481_n1.png'),
(71, 29, 'assets/itemGallery/29_1780643481_n2.png'),
(72, 29, 'assets/itemGallery/29_1780643481_n3.png'),
(73, 29, 'assets/itemGallery/29_1780643481_n4.png'),
(75, 30, 'assets/itemGallery/30_1780644747_n1.png'),
(76, 30, 'assets/itemGallery/30_1780644747_n2.png'),
(77, 30, 'assets/itemGallery/30_1780644747_n3.png'),
(78, 31, 'assets/itemGallery/31_1780650213_n1.png'),
(79, 31, 'assets/itemGallery/31_1780650213_n2.png'),
(80, 31, 'assets/itemGallery/31_1780650213_n3.png'),
(81, 32, 'assets/itemGallery/32_1780650617_n1.png'),
(82, 32, 'assets/itemGallery/32_1780650617_n2.png'),
(83, 32, 'assets/itemGallery/32_1780650617_n3.png'),
(84, 32, 'assets/itemGallery/32_1780650617_n4.png'),
(85, 33, 'assets/itemGallery/33_1780651042_n1.png'),
(86, 33, 'assets/itemGallery/33_1780651042_n2.png'),
(87, 33, 'assets/itemGallery/33_1780651042_n3.png'),
(88, 33, 'assets/itemGallery/33_1780651042_n4.png'),
(89, 34, 'assets/itemGallery/34_1780651533_n1.png'),
(90, 34, 'assets/itemGallery/34_1780651533_n2.png'),
(91, 34, 'assets/itemGallery/34_1780651533_n3.png'),
(92, 34, 'assets/itemGallery/34_1780651533_n4.png'),
(93, 35, 'assets/itemGallery/35_1780651917_т1.png'),
(94, 35, 'assets/itemGallery/35_1780651917_т2.png'),
(95, 35, 'assets/itemGallery/35_1780651917_т3.png'),
(96, 35, 'assets/itemGallery/35_1780651917_т4.png'),
(97, 36, 'assets/itemGallery/36_1780652232_n1.png'),
(98, 36, 'assets/itemGallery/36_1780652232_n2.png'),
(99, 36, 'assets/itemGallery/36_1780652232_n3.png'),
(100, 36, 'assets/itemGallery/36_1780652232_n4.png'),
(101, 37, 'assets/itemGallery/37_1780652632_n1.png'),
(102, 37, 'assets/itemGallery/37_1780652632_n2.png'),
(103, 37, 'assets/itemGallery/37_1780652632_n3.png'),
(104, 37, 'assets/itemGallery/37_1780652632_n4.png'),
(105, 38, 'assets/itemGallery/38_1780653032_n1.png'),
(106, 38, 'assets/itemGallery/38_1780653032_n2.png'),
(107, 38, 'assets/itemGallery/38_1780653032_n3.png'),
(108, 38, 'assets/itemGallery/38_1780653032_n4.png'),
(109, 39, 'assets/itemGallery/39_1780654115_n1.png'),
(110, 39, 'assets/itemGallery/39_1780654115_n2.png'),
(111, 39, 'assets/itemGallery/39_1780654115_n3.png');

-- --------------------------------------------------------

--
-- Структура таблицы `items`
--

CREATE TABLE `items` (
  `id` int NOT NULL,
  `name` varchar(255) NOT NULL,
  `shortDescription` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `price` int NOT NULL,
  `category_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `items`
--

INSERT INTO `items` (`id`, `name`, `shortDescription`, `description`, `price`, `category_id`) VALUES
(22, 'Классические шахматы «Королевская партия»', 'Дерево, стандартные фигуры', 'Элегантные классические шахматы из натурального дерева для дома и офиса. Фигуры стандартной формы, приятные на вес, не скользят по доске. Отличный выбор для начинающих и любителей, кто ценит традиционный стиль.', 3490, 1),
(26, 'Шахматные часы «Тайм-контрол»', 'Электронные, премиум-класс', 'Профессиональные цифровые часы для турниров и серьёзных партий. Поддерживают основные режимы контроля времени, включая классику, блиц и рапид. Имеют функцию задержки Фишера и программы для разных регламентов. Большой контрастный дисплей виден под любым углом, удобное управление кнопками позволяет быстро переключать настройки.', 4490, 3),
(28, 'Шахматы подарочные «Дубовый престиж»', 'Дуб, подарочный комплект', 'Подарочные шахматы из массива дуба с классическими резными фигурами. Древесина благородного тёмного оттенка, приятная на вес, фигуры устойчиво стоят на доске и не скользят. Идеальный выбор для статусного подарка руководителю, коллеге или ценителю интеллектуальных игр. В комплекте — складная доска с мягким покрытием внутри для безопасного хранения.', 14900, 1),
(29, 'Шахматы «Итальянское наследие»', 'Морёный дуб, итальянский дизайн', 'Эксклюзивные подарочные шахматы в редком исполнении из морёного дуба — дерева, которое добывали со дна рек и выдерживали десятилетиями. Итальянский дизайн: изящные, чуть вытянутые фигуры с тонкой резьбой, напоминающие шахматы из дворцов эпохи Ренессанса. Комплект поставляется в деревянном ларце с индивидуальными ячейками для каждой фигуры — ничего не болтается, всё на своих местах. Идеальный подарок для коллекционера, руководителя или человека, у которого уже есть всё.', 37500, 1),
(30, 'Шахматы дорожные «Компаньон»', 'Компактные, чехол, дерево', 'Дорожные шахматы в прочном чехле — удобно брать в поездку, на дачу или в офис. Доска раскладная, фигуры стандартной формы, устойчивые, приятные на вес. Всё помещается в компактный чехол на молнии: фигуры не теряются, ничего не гремит. Отличный подарок на День Рождения другу, брату, коллеге или сыну — практичный, серьёзный и без лишнего пафоса.', 890, 1),
(31, 'Шахматная доска «Классика»', 'Складная, ручная работа', 'Турнирная складная доска из эксклюзивного морёного дуба — ручная работа, каждая доска уникальна. Крупный формат 50 см подходит для серьёзных партий и стандартных турнирных фигур. Складная конструкция удобна для хранения и транспортировки. Благородный тёмный цвет морёного дерева и безупречная геометрия клеток делают эту доску украшением любого дома или шахматного клуба.', 8990, 2),
(32, 'Шахматная доска «Бишоп»', 'Дуб, складная', 'Складная турнирная доска из натурального дуба. Компактный формат 40 см удобен для дома, поездок или клуба. Доска раскладывается пополам, внутри — мягкое покрытие для безопасного хранения фигур (фигуры в комплект не входят). Классическое светлое дерево, чёткая разметка, ровные клетки. Надёжный выбор для тех, кому нужна качественная доска без переплаты за фигуры.', 3990, 2),
(33, 'Доска-ларец «Дубовая сокровищница»', 'Ларец, ручная работа', 'Эксклюзивная шахматная доска в форме ларца из морёного дуба. В раскрытом виде — полноценное игровое поле 45 см. В закрытом — изящный деревянный ларец, внутри которого есть ячейки и отделения для хранения фигур (фигуры приобретаются отдельно). Ручная работа: каждая доска обработана вручную, с вниманием к стыкам, углам и текстуре дерева. Большой формат, благородный тёмный цвет морёного дуба и статусный внешний вид. Идеальный выбор в качестве премиальной основы для подарочных шахмат.', 12990, 2),
(34, 'Шахматная доска «Гроссмейстер»', 'Турнирная, крупная клетка', 'Большая складная турнирная доска гроссмейстерского формата 43 см. Специально разработана для серьёзных партий — клетка увеличенного размера обеспечивает комфортную игру даже с крупными фигурами. Складная конструкция удобна для хранения и транспортировки. Подходит для домашних турниров, шахматных клубов и в качестве замены стандартной доски для опытных игроков.', 1290, 2),
(35, 'Фигуры шахматные «Премиум»', 'Утяжелённые, премиум-класс', 'Эксклюзивный комплект шахматных фигур премиум-класса. Выполнены из натурального дерева с утяжелителем внутри — фигуры устойчиво стоят на доске, приятно тяжелеют в руке, не скользят и не падают при резких движениях. Большой размер подходит для турнирных и подарочных досок. Ручная обработка, идеальная геометрия и благородное покрытие. Без доски — можно купить отдельно под любой стиль и размер.', 4990, 5),
(36, 'Фигуры шахматные «Королевский Стаунтон»', 'Утяжелённые, большие', 'Классические фигуры в легендарном стиле Стаунтон — эталонная форма для турнирных и подарочных шахмат. Утяжелённая конструкция делает фигуры устойчивыми и приятно весомыми в руке. Большой размер (король 10,5 см) идеально подходит для досок с клеткой 45–55 мм. Ручная доработка, чёткие силуэты, благородная отделка. Фигуры приобретаются без доски — вы можете выбрать игровое поле под свой вкус и размер.', 2990, 5),
(37, 'Фигуры шахматные «Грабовый престиж»', 'Граб, премиум', 'Премиальные шахматные фигуры из граба — одной из самых плотных и благородных пород дерева. Граб даёт однородную текстуру, приятный кремово-бежевый оттенок и высокую износостойкость. Внутреннее утяжеление обеспечивает идеальную устойчивость на доске и уверенный вес в руке. Большой размер (король 10,5 см) подходит для турнирных и подарочных досок с клеткой 50–55 мм.', 7990, 5),
(38, 'Фигуры шахматные «Гамбит»', 'Стаунтон, средний размер', 'Классические фигуры в эталонном стиле Стаунтон с утяжелением. Компактная высота короля 7 см делает этот комплект идеальным для дорожных досок, домашних игр и стандартных полей с клеткой 30–40 мм. Утяжеление обеспечивает устойчивость и приятную тяжесть в руке. Фигуры приобретаются без доски — можно подобрать игровое поле под свой размер и стиль.', 1990, 5),
(39, 'Часы шахматные \"Chess2000\"', 'Турнирные, с задержкой', 'Надёжные шахматные часы S200 для турнирной и домашней игры. Два отдельных циферблата, механическое управление кнопками. Поддерживают классический контроль времени и функцию задержки — актуально для современных турниров по блицу, рапиду и классике. Компактные, лёгкие, работают от стандартных батареек.', 1290, 3);

-- --------------------------------------------------------

--
-- Структура таблицы `ordersDelivery`
--

CREATE TABLE `ordersDelivery` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `item_id` int NOT NULL,
  `address` varchar(100) NOT NULL,
  `date` date NOT NULL,
  `picked_date` date NOT NULL,
  `time` text NOT NULL,
  `phone` text NOT NULL,
  `comment` text,
  `quanity` int NOT NULL,
  `delivery_date` date DEFAULT NULL,
  `status` varchar(100) NOT NULL,
  `cancelReason` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `ordersDelivery`
--

INSERT INTO `ordersDelivery` (`id`, `user_id`, `item_id`, `address`, `date`, `picked_date`, `time`, `phone`, `comment`, `quanity`, `delivery_date`, `status`, `cancelReason`) VALUES
(8, 2, 22, 'Казань, Галеева 3', '2026-05-31', '2026-06-07', '8:00-12:00', '89991158888', 'Это общежитие', 2, NULL, 'Отменен', 'Извините, товар закончился на складе и пока мы не можем его Вам доставить. Можете подобрать что-нибудь похожее.'),
(9, 2, 26, 'Казань, Галеева 3', '2026-05-31', '2026-06-05', '12:00-16:00', '89991158888', 'Это общежитие', 1, '2026-06-01', 'В пути', NULL),
(10, 2, 25, 'Казань, Галеева 3', '2026-06-02', '2026-06-05', '16:00-20:00', '89124743333', NULL, 1, '2026-06-15', 'Завершен', NULL),
(11, 4, 25, 'Казань, Проспект Победы 78', '2026-06-03', '2026-06-12', '12:00-16:00', '89991158888', NULL, 1, NULL, 'В пути', NULL);

-- --------------------------------------------------------

--
-- Структура таблицы `ordersPickup`
--

CREATE TABLE `ordersPickup` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `item_id` int NOT NULL,
  `date` date NOT NULL,
  `storeAddress` varchar(255) NOT NULL,
  `phone` varchar(100) NOT NULL,
  `comment` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `delivery_date` date DEFAULT NULL,
  `predict_date` date NOT NULL,
  `quanity` int NOT NULL,
  `status` varchar(100) NOT NULL,
  `cancelReason` text,
  `code` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `ordersPickup`
--

INSERT INTO `ordersPickup` (`id`, `user_id`, `item_id`, `date`, `storeAddress`, `phone`, `comment`, `delivery_date`, `predict_date`, `quanity`, `status`, `cancelReason`, `code`) VALUES
(7, 4, 23, '2026-06-01', 'Москва, Моховая 12', '89124743333', NULL, '2026-06-02', '2026-06-11', 1, 'Завершен', NULL, 6282),
(8, 4, 27, '2026-06-01', 'Москва, Моховая 12', '89124743333', NULL, NULL, '2026-06-11', 2, 'Можно забирать', NULL, 6282),
(9, 3, 25, '2026-06-01', 'СПБ, Большая Пушкарская 9', '89991112222', NULL, '2026-06-01', '2026-06-11', 1, 'Завершен', NULL, 2517),
(10, 3, 27, '2026-06-01', 'СПБ, Большая Пушкарская 9', '89991112222', NULL, '2026-06-01', '2026-06-11', 1, 'Завершен', NULL, 2517),
(11, 4, 25, '2026-06-02', 'СПБ, Большая Пушкарская 9', '89027352441', NULL, '2026-06-20', '2026-06-12', 1, 'Завершен', NULL, 7537),
(12, 5, 25, '2026-06-02', 'Казань, Баумана 36', '89991158888', NULL, '2026-06-13', '2026-06-12', 1, 'Завершен', NULL, 8252),
(18, 3, 23, '2026-06-03', 'Москва, Моховая 12', '89991158888', NULL, NULL, '2026-06-13', 2, 'В пути', NULL, 7985),
(19, 6, 22, '2026-06-04', 'Казань, Баумана 36', '89027352441', NULL, NULL, '2026-06-14', 1, 'В пути', NULL, 7651),
(20, 6, 23, '2026-06-04', 'Казань, Баумана 36', '89027352441', NULL, NULL, '2026-06-14', 1, 'В пути', NULL, 7651);

-- --------------------------------------------------------

--
-- Структура таблицы `promotions`
--

CREATE TABLE `promotions` (
  `id` int NOT NULL,
  `title` varchar(255) NOT NULL,
  `date` date NOT NULL,
  `description` text NOT NULL,
  `imgPath` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `promotions`
--

INSERT INTO `promotions` (`id`, `title`, `date`, `description`, `imgPath`) VALUES
(6, 'Скидка 20% на первый заказ', '2026-06-19', 'При регистрации на сайте вы получаете промокод на скидку 20% на любой товар из каталога. Акция действует для новых покупателей.', 'assets/promGallery/6_1779977219_prom1.png'),
(7, 'Шахматы + часы = выгода', '2026-06-07', 'При покупке любых шахмат и шахматных часов вместе — скидка 15% на весь комплект. Идеальный старт для турнирного игрока.', 'assets/promGallery/7_1779977295_prom2.png'),
(8, 'Подарок к каждому заказу', '2026-06-22', 'При покупке любого товара вы получаете в подарок блокнот для записи партий с ручкой.', 'assets/promGallery/8_1779977330_prom3.png');

-- --------------------------------------------------------

--
-- Структура таблицы `reviews`
--

CREATE TABLE `reviews` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `item_id` int NOT NULL,
  `rate` int NOT NULL,
  `text` text NOT NULL,
  `status` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `reviews`
--

INSERT INTO `reviews` (`id`, `user_id`, `item_id`, `rate`, `text`, `status`) VALUES
(3, 3, 25, 5, 'Долго выбирал турнирные шахматы и не прогадал. \"Грандмастер\" — это уровень! Фигуры тяжёлые, приятные на ощупь, доска ровная, без люфта. Поле отлично контрастирует — глаза не устают даже после 3 часов партии. В комплекте идёт запасная ферзь и удобный чехол. Ощущение, что играешь в серьёзном турнире. Рекомендую всем, кто ценит качество!', 'Принят'),
(5, 2, 25, 5, 'В целом отличные турнирные шахматы. Доска гладкая, фигуры ровные, видно, что делали с умом. Но минус за размер: для моего стола чуть великоваты (поле 40х40 см). И если честно, хотелось бы чуть более плотное прилегание фигур к доске — иногда съезжают при резком движении. Но за эти деньги — твёрдая четвёрка. Для домашней лиги самое то!', 'Принят'),
(6, 5, 25, 5, 'Брал для шахматного кружка. Уже третьи \"Грандмастер\" — дети играют активно, но фигуры не сколоты, покрытие не стёрлось. Доска магнитная? Нет, классика, но фигуры устойчивые, не падают от случайного дыхания. Удобно, что клетки пронумерованы — новичкам легче учить нотацию. Лучший вариант для клубов и домашних турниров!', 'Принят');

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `name` varchar(20) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(1000) NOT NULL,
  `sex` varchar(10) NOT NULL,
  `isAdmin` tinyint(1) NOT NULL DEFAULT '0',
  `imgPath` varchar(100) DEFAULT NULL,
  `orders` int DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `sex`, `isAdmin`, `imgPath`, `orders`) VALUES
(2, 'Катерина', 'kata@domen.php', '$2y$10$3rlAO22.0xbrTu6EPOe81.RzRy9pmeqxUHS8Imom03Kv1.lVm3W16', 'Женский', 0, '/assets/images/2_marla.webp_1779742228.webp', 0),
(3, 'Tyler Durden', 'tylerKepka@gmail.com', '$2y$10$gi/ToCRo5Vtjwhdtc3U9m..mTo42HczlUDdWoOyc9SGBzErQ9u.H.', 'Мужской', 0, '/assets/images/3_tylerr.jpg_1779742522.jpg', 0),
(4, 'Евгений', 'evgAdmin@gmail.com', '$2y$10$CFVMKaS/bA3Kiqs.rCniluKX3tYKKJzv/uVnPSe9q488cEqBV4582', 'Мужской', 1, '/assets/images/4_admin.png_1780599291.png', 0),
(5, 'Марсель', 'marseck.mt@gmail.com', '$2y$10$coozMTfhT8a5bqzXNVcx5uRCqe07WenzqUk2Tt17SAt7kyV46q.dq', 'Мужской', 0, '/assets/images/5_noize.jpg_1780418467.jpg', 0),
(6, 'Bishop', 'dsd@mail.ru', '$2y$10$RrLoGf7xQz37K1iuyfcZqOsCjOGVYoYr3qDl4YPiqx5ruWTB677vi', 'Женский', 0, '/assets/images/6_Chess.jpg_1780582698.jpg', 0);

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `characteristics`
--
ALTER TABLE `characteristics`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`);

--
-- Индексы таблицы `itemChars`
--
ALTER TABLE `itemChars`
  ADD PRIMARY KEY (`id`),
  ADD KEY `item_id` (`item_id`),
  ADD KEY `char_id` (`char_id`);

--
-- Индексы таблицы `itemGallery`
--
ALTER TABLE `itemGallery`
  ADD PRIMARY KEY (`id`),
  ADD KEY `item_id` (`item_id`);

--
-- Индексы таблицы `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`);

--
-- Индексы таблицы `ordersDelivery`
--
ALTER TABLE `ordersDelivery`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `ordersPickup`
--
ALTER TABLE `ordersPickup`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `promotions`
--
ALTER TABLE `promotions`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=84;

--
-- AUTO_INCREMENT для таблицы `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT для таблицы `characteristics`
--
ALTER TABLE `characteristics`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT для таблицы `itemChars`
--
ALTER TABLE `itemChars`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=148;

--
-- AUTO_INCREMENT для таблицы `itemGallery`
--
ALTER TABLE `itemGallery`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=112;

--
-- AUTO_INCREMENT для таблицы `items`
--
ALTER TABLE `items`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT для таблицы `ordersDelivery`
--
ALTER TABLE `ordersDelivery`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT для таблицы `ordersPickup`
--
ALTER TABLE `ordersPickup`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT для таблицы `promotions`
--
ALTER TABLE `promotions`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT для таблицы `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `characteristics`
--
ALTER TABLE `characteristics`
  ADD CONSTRAINT `characteristics_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `itemChars`
--
ALTER TABLE `itemChars`
  ADD CONSTRAINT `itemchars_ibfk_1` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `itemchars_ibfk_2` FOREIGN KEY (`char_id`) REFERENCES `characteristics` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `itemGallery`
--
ALTER TABLE `itemGallery`
  ADD CONSTRAINT `itemgallery_ibfk_1` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `items`
--
ALTER TABLE `items`
  ADD CONSTRAINT `items_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
