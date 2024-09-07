-- phpMyAdmin SQL Dump
-- version 4.9.10
-- https://www.phpmyadmin.net/
--
-- Хост: localhost
-- Время создания: Апр 01 2024 г., 12:04
-- Версия сервера: 5.6.15
-- Версия PHP: 7.3.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `shop`
--

DELIMITER $$
--
-- Функции
--
CREATE DEFINER=`root`@`localhost` FUNCTION `check_product_count` (`id_product` INT, `id_pvz` INT, `id_group` INT) RETURNS INT(11) READS SQL DATA BEGIN
	select id into @res from stock inner join kolvo_product on kolvo_product.id_stock=stock.id where region like (SELECT region from stock where id=id_pvz) and pvz=0 and kolvo_product.id_group=id_group and kolvo_product.id_product=id_product limit 1;
    RETURN @res;
END$$

CREATE DEFINER=`root`@`localhost` FUNCTION `get_rating` (`id_product` INT, `id_group` INT) RETURNS INT(11) NO SQL BEGIN
select round(avg(rating),1) into @rate from feedback inner join purchase on purchase.id=feedback.id where purchase.id_group=id_group and purchase.product=id_product group by purchase.id_group;
return @rate;
end$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Структура таблицы `buyer`
--

CREATE TABLE `buyer` (
  `id` int(11) NOT NULL,
  `FIO` char(40) NOT NULL,
  `region` text,
  `phone` char(12) DEFAULT NULL,
  `email` char(30) DEFAULT NULL,
  `password` varchar(15) NOT NULL DEFAULT 'passwd'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `buyer`
--
--
-- Структура таблицы `feedback`
--

CREATE TABLE `feedback` (
  `id` int(11) NOT NULL,
  `rating` int(1) NOT NULL,
  `data` datetime(6) NOT NULL,
  `comment` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `group_product`
--

CREATE TABLE `group_product` (
  `id_group` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` text NOT NULL,
  `category` text NOT NULL,
  `price` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `group_product`
--
--
-- Структура таблицы `kolvo_product`
--

CREATE TABLE `kolvo_product` (
  `id_stock` int(11) NOT NULL,
  `id_group` int(11) NOT NULL,
  `id_product` int(11) NOT NULL,
  `quantity` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `kolvo_product`
--

INSERT INTO `kolvo_product` (`id_stock`, `id_group`, `id_product`, `quantity`) VALUES
(2, 1, 1, 40);

--
-- Триггеры `kolvo_product`
--
DELIMITER $$
CREATE TRIGGER `check_stock` BEFORE INSERT ON `kolvo_product` FOR EACH ROW BEGIN
if (new.id_stock not in (select id from view_stocks)) THEN
	set new.id_stock=NULL;
end if;
end
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Структура таблицы `news`
--

CREATE TABLE `news` (
  `id` int(11) NOT NULL,
  `name` text NOT NULL,
  `description` text,
  `data` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `news`
--
--
-- Триггеры `news`
--
DELIMITER $$
CREATE TRIGGER `set_news_data` BEFORE INSERT ON `news` FOR EACH ROW set new.data=CURRENT_DATE()
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Структура таблицы `product`
--

CREATE TABLE `product` (
  `id_group` int(11) NOT NULL,
  `id` int(11) NOT NULL,
  `color` varchar(50) NOT NULL,
  `size` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `product`
--
--
-- Структура таблицы `purchase`
--

CREATE TABLE `purchase` (
  `id` int(11) NOT NULL,
  `buyer` int(11) NOT NULL,
  `id_group` int(11) NOT NULL,
  `product` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `worker` int(11) DEFAULT NULL,
  `data` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `summa` float NOT NULL,
  `pvz` int(11) NOT NULL,
  `id_stock` int(11) NOT NULL,
  `status` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Триггеры `purchase`
--
DELIMITER $$
CREATE TRIGGER `_before_ins_tr1` BEFORE INSERT ON `purchase` FOR EACH ROW BEGIN
	select check_product_count(new.product, new.pvz, new.id_group) into @stock;
	if (@stock IS NOT NULL) THEN
    	set new.id_stock=@stock;
        select quantity into @v from kolvo_product where id_stock=@stock and id_group=new.id_group and id_product=new.product;
        if (@v>new.quantity) THEN
        	update kolvo_product set quantity=quantity-new.quantity where id_stock=@stock and id_group=new.id_group and id_product=new.product;
        ELSE
        	delete from kolvo_product where id_stock=@stock and id_group=new.id_group and id_product=new.product;
        end if;
    ELSE
    	set new.id=NULL;
    end if;
	select price into @v from group_product where id_group=new.id_group LIMIT 1;
    SET NEW.data=NOW();
	SET NEW.summa = @v * NEW.quantity;
    SET New.status = "Принято к обработке";
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Структура таблицы `stock`
--

CREATE TABLE `stock` (
  `id` int(11) NOT NULL,
  `region` varchar(50) NOT NULL,
  `addres` varchar(100) NOT NULL,
  `pvz` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `stock`
--
--
-- Структура таблицы `user_product`
--

CREATE TABLE `user_product` (
  `buyer` int(11) NOT NULL,
  `id_group` int(11) NOT NULL,
  `product` int(11) NOT NULL,
  `favourite` tinyint(1) NOT NULL,
  `basket` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `user_product`
--
--
-- Дублирующая структура для представления `view_basket`
-- (См. Ниже фактическое представление)
--
CREATE TABLE `view_basket` (
`buyer` int(11)
,`id_group` int(11)
,`product` int(11)
,`name` varchar(50)
,`price` double
,`category` text
,`description` text
,`color` varchar(50)
,`size` varchar(10)
,`favourite` tinyint(1)
,`basket` tinyint(1)
);

-- --------------------------------------------------------

--
-- Дублирующая структура для представления `view_favourite`
-- (См. Ниже фактическое представление)
--
CREATE TABLE `view_favourite` (
`buyer` int(11)
,`id_group` int(11)
,`product` int(11)
,`name` varchar(50)
,`price` double
,`category` text
,`description` text
,`color` varchar(50)
,`size` varchar(10)
,`favourite` tinyint(1)
,`basket` tinyint(1)
);

-- --------------------------------------------------------

--
-- Дублирующая структура для представления `view_orders`
-- (См. Ниже фактическое представление)
--
CREATE TABLE `view_orders` (
`buyer` int(11)
,`id_group` int(11)
,`id` int(11)
,`product` int(11)
,`quantity` int(11)
,`status` varchar(20)
,`summa` float
,`pvz` int(11)
,`name` varchar(50)
,`description` text
,`price` double
,`color` varchar(50)
,`size` varchar(10)
);

-- --------------------------------------------------------

--
-- Дублирующая структура для представления `view_product`
-- (См. Ниже фактическое представление)
--
CREATE TABLE `view_product` (
`id_group` int(11)
,`product` int(11)
,`name` varchar(50)
,`price` double
,`category` text
,`description` text
,`color` varchar(50)
,`size` varchar(10)
);

-- --------------------------------------------------------

--
-- Дублирующая структура для представления `view_pvzs`
-- (См. Ниже фактическое представление)
--
CREATE TABLE `view_pvzs` (
`id` int(11)
,`region` varchar(50)
,`addres` varchar(100)
);

-- --------------------------------------------------------

--
-- Дублирующая структура для представления `view_stocks`
-- (См. Ниже фактическое представление)
--
CREATE TABLE `view_stocks` (
`id` int(11)
,`region` varchar(50)
,`addres` varchar(100)
);

-- --------------------------------------------------------

--
-- Дублирующая структура для представления `view_worker_pvz`
-- (См. Ниже фактическое представление)
--
CREATE TABLE `view_worker_pvz` (
`worker_id` int(11)
,`FIO` char(40)
,`id_stock` int(11)
,`region` varchar(50)
,`addres` varchar(100)
);

-- --------------------------------------------------------

--
-- Дублирующая структура для представления `view_worker_stock`
-- (См. Ниже фактическое представление)
--
CREATE TABLE `view_worker_stock` (
`id_worker` int(11)
,`FIO` char(40)
,`id_stock` int(11)
,`region` varchar(50)
,`addres` varchar(100)
);

-- --------------------------------------------------------

--
-- Структура таблицы `worker`
--

CREATE TABLE `worker` (
  `id` int(11) NOT NULL,
  `FIO` char(40) NOT NULL,
  `stock` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `worker`
--
--
-- Структура для представления `view_basket`
--
DROP TABLE IF EXISTS `view_basket`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_basket`  AS SELECT `user_product`.`buyer` AS `buyer`, `user_product`.`id_group` AS `id_group`, `view_product`.`product` AS `product`, `view_product`.`name` AS `name`, `view_product`.`price` AS `price`, `view_product`.`category` AS `category`, `view_product`.`description` AS `description`, `view_product`.`color` AS `color`, `view_product`.`size` AS `size`, `user_product`.`favourite` AS `favourite`, `user_product`.`basket` AS `basket` FROM (`user_product` join `view_product` on(((`user_product`.`id_group` = `view_product`.`id_group`) and (`user_product`.`product` = `view_product`.`product`)))) WHERE (`user_product`.`basket` = 1) ;

-- --------------------------------------------------------

--
-- Структура для представления `view_favourite`
--
DROP TABLE IF EXISTS `view_favourite`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_favourite`  AS SELECT `user_product`.`buyer` AS `buyer`, `user_product`.`id_group` AS `id_group`, `view_product`.`product` AS `product`, `view_product`.`name` AS `name`, `view_product`.`price` AS `price`, `view_product`.`category` AS `category`, `view_product`.`description` AS `description`, `view_product`.`color` AS `color`, `view_product`.`size` AS `size`, `user_product`.`favourite` AS `favourite`, `user_product`.`basket` AS `basket` FROM (`user_product` join `view_product` on(((`user_product`.`id_group` = `view_product`.`id_group`) and (`user_product`.`product` = `view_product`.`product`)))) WHERE (`user_product`.`favourite` = 1) ;

-- --------------------------------------------------------

--
-- Структура для представления `view_orders`
--
DROP TABLE IF EXISTS `view_orders`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_orders`  AS SELECT `purchase`.`buyer` AS `buyer`, `purchase`.`id_group` AS `id_group`, `purchase`.`id` AS `id`, `purchase`.`product` AS `product`, `purchase`.`quantity` AS `quantity`, `purchase`.`status` AS `status`, `purchase`.`summa` AS `summa`, `purchase`.`pvz` AS `pvz`, `view_product`.`name` AS `name`, `view_product`.`description` AS `description`, `view_product`.`price` AS `price`, `view_product`.`color` AS `color`, `view_product`.`size` AS `size` FROM (`purchase` join `view_product` on(((`purchase`.`id_group` = `view_product`.`id_group`) and (`purchase`.`product` = `view_product`.`product`)))) ;

-- --------------------------------------------------------

--
-- Структура для представления `view_product`
--
DROP TABLE IF EXISTS `view_product`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_product`  AS SELECT `group_product`.`id_group` AS `id_group`, `product`.`id` AS `product`, `group_product`.`name` AS `name`, `group_product`.`price` AS `price`, `group_product`.`category` AS `category`, `group_product`.`description` AS `description`, `product`.`color` AS `color`, `product`.`size` AS `size` FROM (`group_product` join `product` on((`group_product`.`id_group` = `product`.`id_group`))) ;

-- --------------------------------------------------------

--
-- Структура для представления `view_pvzs`
--
DROP TABLE IF EXISTS `view_pvzs`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_pvzs`  AS SELECT `stock`.`id` AS `id`, `stock`.`region` AS `region`, `stock`.`addres` AS `addres` FROM `stock` WHERE (`stock`.`pvz` = 1) ;

-- --------------------------------------------------------

--
-- Структура для представления `view_stocks`
--
DROP TABLE IF EXISTS `view_stocks`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_stocks`  AS SELECT `stock`.`id` AS `id`, `stock`.`region` AS `region`, `stock`.`addres` AS `addres` FROM `stock` WHERE (`stock`.`pvz` = 0) ;

-- --------------------------------------------------------

--
-- Структура для представления `view_worker_pvz`
--
DROP TABLE IF EXISTS `view_worker_pvz`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_worker_pvz`  AS SELECT `worker`.`id` AS `worker_id`, `worker`.`FIO` AS `FIO`, `stock`.`id` AS `id_stock`, `stock`.`region` AS `region`, `stock`.`addres` AS `addres` FROM (`worker` join `stock` on((`stock`.`id` = `worker`.`stock`))) WHERE (`stock`.`pvz` = 1) ;

-- --------------------------------------------------------

--
-- Структура для представления `view_worker_stock`
--
DROP TABLE IF EXISTS `view_worker_stock`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_worker_stock`  AS SELECT `worker`.`id` AS `id_worker`, `worker`.`FIO` AS `FIO`, `stock`.`id` AS `id_stock`, `stock`.`region` AS `region`, `stock`.`addres` AS `addres` FROM (`worker` join `stock` on((`stock`.`id` = `worker`.`stock`))) WHERE (`stock`.`pvz` = 0) ;

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `buyer`
--
ALTER TABLE `buyer`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `id` (`id`) USING BTREE;

--
-- Индексы таблицы `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Индексы таблицы `group_product`
--
ALTER TABLE `group_product`
  ADD PRIMARY KEY (`id_group`);

--
-- Индексы таблицы `kolvo_product`
--
ALTER TABLE `kolvo_product`
  ADD PRIMARY KEY (`id_stock`,`id_group`,`id_product`) USING BTREE,
  ADD KEY `id_product` (`id_product`) USING BTREE,
  ADD KEY `kolvo_fk3` (`id_group`);

--
-- Индексы таблицы `news`
--
ALTER TABLE `news`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Индексы таблицы `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`id`,`id_group`) USING BTREE,
  ADD KEY `id` (`id`) USING BTREE,
  ADD KEY `fk1` (`id_group`);

--
-- Индексы таблицы `purchase`
--
ALTER TABLE `purchase`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `buyer` (`buyer`) USING BTREE,
  ADD KEY `product` (`product`) USING BTREE,
  ADD KEY `worker` (`worker`) USING BTREE,
  ADD KEY `order_fk4` (`pvz`) USING BTREE,
  ADD KEY `order_fk5` (`id_group`);

--
-- Индексы таблицы `stock`
--
ALTER TABLE `stock`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `id` (`id`) USING BTREE;

--
-- Индексы таблицы `user_product`
--
ALTER TABLE `user_product`
  ADD PRIMARY KEY (`buyer`,`id_group`,`product`) USING BTREE,
  ADD KEY `buyer` (`buyer`) USING BTREE,
  ADD KEY `product` (`product`) USING BTREE,
  ADD KEY `basket_fk3` (`id_group`);

--
-- Индексы таблицы `worker`
--
ALTER TABLE `worker`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `stock` (`stock`) USING BTREE;

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `buyer`
--
ALTER TABLE `buyer`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT для таблицы `group_product`
--
ALTER TABLE `group_product`
  MODIFY `id_group` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT для таблицы `news`
--
ALTER TABLE `news`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT для таблицы `product`
--
ALTER TABLE `product`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT для таблицы `purchase`
--
ALTER TABLE `purchase`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT для таблицы `stock`
--
ALTER TABLE `stock`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT для таблицы `worker`
--
ALTER TABLE `worker`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `feedback`
--
ALTER TABLE `feedback`
  ADD CONSTRAINT `feedback_fk1` FOREIGN KEY (`id`) REFERENCES `purchase` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `kolvo_product`
--
ALTER TABLE `kolvo_product`
  ADD CONSTRAINT `kolvo_fk1` FOREIGN KEY (`id_product`) REFERENCES `product` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `kolvo_fk2` FOREIGN KEY (`id_stock`) REFERENCES `stock` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `kolvo_fk3` FOREIGN KEY (`id_group`) REFERENCES `product` (`id_group`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `product`
--
ALTER TABLE `product`
  ADD CONSTRAINT `fk1` FOREIGN KEY (`id_group`) REFERENCES `group_product` (`id_group`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `purchase`
--
ALTER TABLE `purchase`
  ADD CONSTRAINT `order_fk1` FOREIGN KEY (`buyer`) REFERENCES `buyer` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `order_fk2` FOREIGN KEY (`product`) REFERENCES `product` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `order_fk3` FOREIGN KEY (`worker`) REFERENCES `worker` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `order_fk4` FOREIGN KEY (`pvz`) REFERENCES `stock` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `order_fk5` FOREIGN KEY (`id_group`) REFERENCES `product` (`id_group`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `user_product`
--
ALTER TABLE `user_product`
  ADD CONSTRAINT `basket_fk1` FOREIGN KEY (`buyer`) REFERENCES `buyer` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `basket_fk2` FOREIGN KEY (`product`) REFERENCES `product` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `basket_fk3` FOREIGN KEY (`id_group`) REFERENCES `product` (`id_group`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `worker`
--
ALTER TABLE `worker`
  ADD CONSTRAINT `worker_fk1` FOREIGN KEY (`stock`) REFERENCES `stock` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
