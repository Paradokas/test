-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Ноя 18 2022 г., 15:12
-- Версия сервера: 5.7.39
-- Версия PHP: 8.0.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `test_samson`
--

-- --------------------------------------------------------

--
-- Структура таблицы `a_category`
--

CREATE TABLE `a_category` (
                              `id` int(11) NOT NULL,
                              `code` varchar(255) DEFAULT NULL,
                              `name` varchar(255) NOT NULL,
                              `parent_id` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `a_category_product`
--

CREATE TABLE `a_category_product` (
                                      `id` int(11) NOT NULL,
                                      `id_category` int(11) DEFAULT NULL,
                                      `id_product` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `a_price`
--

CREATE TABLE `a_price` (
                           `id` int(11) NOT NULL,
                           `id_product` int(11) NOT NULL,
                           `price_type` varchar(255) NOT NULL,
                           `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `a_product`
--

CREATE TABLE `a_product` (
                             `id` int(11) NOT NULL,
                             `code` int(11) NOT NULL,
                             `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `a_property`
--

CREATE TABLE `a_property` (
                              `id` int(11) NOT NULL,
                              `id_product` int(11) NOT NULL,
                              `property` varchar(255) NOT NULL,
                              `value` varchar(255) NOT NULL,
                              `att_property` varchar(255) DEFAULT NULL,
                              `att_value` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `a_category`
--
ALTER TABLE `a_category`
    ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `a_category_product`
--
ALTER TABLE `a_category_product`
    ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `a_price`
--
ALTER TABLE `a_price`
    ADD PRIMARY KEY (`id`),
  ADD KEY `id_product` (`id_product`);

--
-- Индексы таблицы `a_product`
--
ALTER TABLE `a_product`
    ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `a_property`
--
ALTER TABLE `a_property`
    ADD PRIMARY KEY (`id`),
  ADD KEY `id_product` (`id_product`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `a_category`
--
ALTER TABLE `a_category`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `a_category_product`
--
ALTER TABLE `a_category_product`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `a_price`
--
ALTER TABLE `a_price`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `a_product`
--
ALTER TABLE `a_product`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `a_property`
--
ALTER TABLE `a_property`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `a_price`
--
ALTER TABLE `a_price`
    ADD CONSTRAINT `a_price_ibfk_1` FOREIGN KEY (`id_product`) REFERENCES `a_product` (`id`);

--
-- Ограничения внешнего ключа таблицы `a_property`
--
ALTER TABLE `a_property`
    ADD CONSTRAINT `a_property_ibfk_1` FOREIGN KEY (`id_product`) REFERENCES `a_product` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
