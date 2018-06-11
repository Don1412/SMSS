<?
header("Content-Type: text/html; charset=utf-8");
require_once("sms/Functions.php");
if(empty($_POST))
{
?>

<html>
<head>
<link rel="stylesheet" type="text/css" href="css/bootstrap.css">
<link rel="stylesheet" type="text/css" href="css/style.css">
<link rel="stylesheet" type="text/css" href="css/jquery.formstyler.css">
<script src = "js/jquery-1.12.3.min.js"></script>
<script src = "js/jquery.form.min.js"></script>
<script src = "js/bootstrap.min.js"></script>
<script src = "js/functions.js"></script>
<meta charset="utf-8">
<title>Установка SMS Manager</title>
</head>
<body>
	<div class = "row" style = "margin-top: 50px;">
        <div class = "col-md-8 col-md-offset-2">
            <h2>Установка SMS Manager</h2><br>
				<hr>
                <form action = "install.php" id = "install" role="form" method = "POST">
					<div class="form-group">
						<label for="text">Имя базы данных</label><input id="bdName" name="bdName" class="form-control">
						<label for="text">Логин базы данных</label><input id="bdLogin" name="bdLogin" class="form-control">
						<label for="text">Пароль базы данных</label><input id="bdPassword" type="password" name="bdPassword" class="form-control">
					</div>
                    <input type="submit" id="bdSave" class = "btn btn-large btn-success" value="Установить"></input>
                </form>
        </div>
</div>
</body>
</html>
<?
}
else
{
	$bdName = $_POST['bdName'];
	$bdLogin = $_POST['bdLogin'];
	$bdPassword = $_POST['bdPassword'];
	
	$dbconnect = mysql_connect ('localhost', $bdLogin, $bdPassword);
	if (!$dbconnect) { echo "Не могу подключиться к серверу базы данных! Проверьте правильно ли введены данные либо обратитесь в техподдержку хостинга."; exit; }
	if(@mysql_select_db($bdName)) { /*echo "Подключение к базе $dbname установлено!";*/}
		else die("Не могу подключиться к базе данных $bdName! Проверьте правильно ли введены данные либо обратитесь в техподдержку хостинга.");
	
	$query = "CREATE TABLE IF NOT EXISTS `log` (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `key` varchar(100) NOT NULL,
			  `count` int(11) NOT NULL,
			  `sender` varchar(30) CHARACTER SET utf32 NOT NULL,
			  `message` varchar(500) NOT NULL,
			  `type` int(11) NOT NULL,
			  `timeToSend` int(11) NOT NULL,
			  `status` int(11) NOT NULL,
			  `dlr_status` varchar(20) NOT NULL,
			  `dlr_errors` int(11) NOT NULL DEFAULT '0',
			  PRIMARY KEY (`id`),
			  UNIQUE KEY `key` (`key`)
			) ENGINE=InnoDB AUTO_INCREMENT=92 DEFAULT CHARSET=utf8;"; mysql_query($query);
	$query = "CREATE TABLE IF NOT EXISTS `log_full` (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `key` varchar(100) NOT NULL,
			  `number` varchar(20) NOT NULL,
			  `timeToSend` int(11) NOT NULL,
			  `status` int(11) NOT NULL,
			  `dlr_id` varchar(50) NOT NULL,
			  `dlr_status` varchar(20) NOT NULL,
			  `dlr_time` varchar(100) NOT NULL,
			  PRIMARY KEY (`id`),
			  KEY `key` (`key`),
			  KEY `dlr_id` (`dlr_id`)
			) ENGINE=InnoDB AUTO_INCREMENT=5960 DEFAULT CHARSET=utf8;"; mysql_query($query);
	$query = "DELIMITER $$
				CREATE TRIGGER `update log` AFTER UPDATE ON `log_full` FOR EACH ROW BEGIN
				UPDATE `log` SET `dlr_status` = NEW.`dlr_status` WHERE `key` = NEW.`key`;
				IF (NEW.`dlr_status` = 'UNDELIV') THEN 
					SET @errs = (SELECT `dlr_errors` FROM `log` WHERE `key` = NEW.`key`);
					IF (@errs = 50) THEN 
						UPDATE `pending` SET `status` = 2 WHERE `key` = NEW.`key`;
					END IF;
					UPDATE `log` SET `dlr_errors` = `dlr_errors` + 1 WHERE `key` = NEW.`key`;
				ELSE
					UPDATE `log` SET `dlr_errors` = 0 WHERE `key` = NEW.`key`;
				END IF;
				END
				$$
				DELIMITER ;
				DELIMITER $$
				CREATE TRIGGER `update log and delete form pending_full` AFTER INSERT ON `log_full` FOR EACH ROW BEGIN
					UPDATE `log` SET `count` = `count` + 1, `timeToSend` = NEW.`timeToSend`, `status` = NEW.`status` WHERE `key` = NEW.`key`;
					DELETE FROM `pending_full` WHERE `key` = NEW.`key` AND `number` = NEW.`number` LIMIT 1;
					SET @countNumbers = (SELECT `count_numbers` FROM `pending` WHERE `key` = NEW.`key`);
					IF (@countNumbers = 0) THEN 
						DELETE FROM `pending` WHERE `key` = NEW.`key`; 
					END IF;
				END
				$$
				DELIMITER ;"; mysql_query($query);
	$query = "CREATE TABLE IF NOT EXISTS `pending` (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `key` varchar(100) NOT NULL,
			  `sender` varchar(30) NOT NULL,
			  `count_sms` int(11) NOT NULL,
			  `count_numbers` int(11) NOT NULL,
			  `message` varchar(500) NOT NULL,
			  `type` int(11) NOT NULL,
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
			  `end` int(11) NOT NULL,
			  PRIMARY KEY (`id`),
			  UNIQUE KEY `key` (`key`),
			  KEY `key_2` (`key`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8;"; mysql_query($query);
	$query = "DELIMITER $$
				CREATE TRIGGER `insert` BEFORE UPDATE ON `pending` FOR EACH ROW BEGIN
				SET @timeToSend=(SELECT `timeToSend` FROM `pending_full` WHERE `key`=NEW.`key` ORDER BY `id` ASC LIMIT 1);
				SET @endSend=(SELECT `timeToSend` FROM `pending_full` WHERE `key`=NEW.`key` ORDER BY `id` DESC LIMIT 1);
				SET NEW.`timeToSend`=@timeToSend;
				SET NEW.`end`=@endSend;
				END
				$$
				DELIMITER ;"; mysql_query($query);
	$query = "CREATE TABLE IF NOT EXISTS `pending_full` (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `key` varchar(100) NOT NULL,
			  `number` varchar(20) NOT NULL,
			  `timeToSend` int(11) NOT NULL,
			  `status` int(11) NOT NULL,
			  PRIMARY KEY (`id`),
			  KEY `key` (`key`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8;"; mysql_query($query);
	$query = "DELIMITER $$
				CREATE TRIGGER `updatePending` AFTER DELETE ON `pending_full` FOR EACH ROW BEGIN
				SET @timeToSend=(SELECT `timeToSend` FROM `pending_full` WHERE `key`=OLD.`key` ORDER BY `id` ASC LIMIT 1);
				SET @endSend=(SELECT `timeToSend` FROM `pending_full` WHERE `key`=OLD.`key` ORDER BY `id` DESC LIMIT 1);
				SET @countNumbers = (SELECT COUNT(*) FROM `pending_full` WHERE `key`=OLD.`key`);
				SET @countNumbersOLD = (SELECT `count_numbers` FROM `pending` WHERE `key`=OLD.`key`);
				SET @countSMSOLD = (SELECT `count_sms` FROM `pending` WHERE `key`=OLD.`key`);
				SET @multipler = @countSMSOLD / @countNumbersOLD;
				SET @countSMS = @multipler * @countNumbers;
				IF (@countNumbers <> 0) THEN
				UPDATE `pending` SET `timeToSend`=@timeToSend, `end`=@endSend, `count_numbers`=@countNumbers, `count_sms`=@countSMS WHERE `key`=OLD.`key`;
				ELSE 
				DELETE FROM `pending` WHERE `key`=OLD.`key`;
				END IF;
				END
				$$
				DELIMITER ;"; mysql_query($query);
	$query = "CREATE TABLE IF NOT EXISTS `templates` (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `type` int(11) NOT NULL,
			  `text` varchar(500) NOT NULL,
			  `name` varchar(500) NOT NULL,
			  PRIMARY KEY (`id`)
			) ENGINE=MyISAM AUTO_INCREMENT=45 DEFAULT CHARSET=cp1251;"; mysql_query($query);
	$query = "INSERT INTO `templates` (`id`, `type`, `text`, `name`) VALUES
				(1, 1, '0', 'onlineSB'),
				(2, 2, 'Операция на сумму 16237RUB прошла успешно.Инфо:  8-800-555-8724', 'вас иван операция'),
				(29, 1, '0', 'verify '),
				(5, 1, '0', 'FindMyPhone'),
				(21, 1, '0', 'rapida'),
				(23, 2, 'Спасибо за регистрацию rapida-online.www.rapida.ru', 'рапида регистрация'),
				(28, 1, '0', 'info'),
				(38, 2, 'Ваш персональный логин для пополнения 410014391002876. Провайдер Яндекс. Никому его не сообщайте!', 'рапида логин'),
				(26, 2, 'ваша заявка на регистрацию принята \nhttps://m.sendspace.com/file/3kxuz1', 'для цифры'),
				(27, 2, 'прроооо', 'ап'),
				(33, 1, '0', 'FMI '),
				(34, 2, 'Уведомление \"Найти FindMyPhone\"\n\nBаш iPhone 6 Gold 64GB был обнаружен сегодня в 13:41, 22  Июня 2016.\n\nИнформация о последнем местоположении Вашего iPhone 6 будет доступна в течение 24 часов по адресу applecloud.us.com \n\nЭто автоматическое сообщение от Apple?', 'айфон'),
				(32, 2, 'Списание на сумму 16237RUB прошло успешно.Справки:88005554809', 'ант списание'),
				(37, 1, '0', 'MSG'),
				(39, 1, '0', 'online'),
				(36, 2, 'перезвони мне на этот номер 89081267315', 'тест '),
				(40, 1, '0', 'fssp'),
				(42, 2, 'Списание средств отказано! Срочно свяжитесь!88005556453', 'от тарух списание '),
				(43, 1, '0', 'uBank'),
				(44, 1, '0', 'pay');"; mysql_query($query);
	$query = "ALTER TABLE `log_full`
				ADD CONSTRAINT `log_full_ibfk_1` FOREIGN KEY (`key`) REFERENCES `log` (`key`) ON DELETE CASCADE;"; mysql_query($query);
	$query = "ALTER TABLE `pending_full`
				ADD CONSTRAINT `pending_full_ibfk_1` FOREIGN KEY (`key`) REFERENCES `pending` (`key`) ON DELETE CASCADE;"; mysql_query($query);
	$str = '
	[database]
		host = "localhost";
		username = "'.$bdLogin.'";
		pass = "'.$bdPassword.'";
		name = "'.$bdName.'";
	
	[install]
		status = "success";';
	file_put_contents('config.ini', $str);
}
?>