<?
	require_once("sms/Functions.php");
	include("DBConnect.php");
	require_once("sms/Logger.php");

    $res = mysql_query("SELECT id FROM `pending2` ORDER BY `id` DESC LIMIT 1");
	$res2 = mysql_fetch_array($res);
	if ($res2['id'] < 1) $id = 1;
	else $id = $res2['id'] + 1;
	$logger = new Logger("manager.txt");
 
	$name = $_POST['selectInput1'];//Имя отправителя
	$date = $_POST['date'];//Дата запланированной отправки
	$hour = $_POST['hour'];//Время запланированной отправки(часы)
	$minute = $_POST['minute'];//Время запланированной отправки(минуты)
	$periodicHours = $_POST['periodicHours'];//Время периодической отправки(часы)
	$periodicMinutes = $_POST['periodicMinutes'];//Время периодической отправки(минуты)
	$type = $_POST['Service'];//Выбор канала
	$amount = $_POST['amount'];//Период отправки
	$perSend = $_POST['PerSend'];//Периодическая отправка
	$planSend = $_POST['PlanSend'];//Запланированная отправка
	$fileTempName = $_FILES['user-file']['tmp_name'];//Файл
	$icloud = $_POST['icloud'];
	$template = $_POST['selectedTemplate'];
	$timezone = $_POST['timezone'];
	$messageType = $_POST['MessageType'];
	//$messageType = 1;

	$timeString = $hour.':'.$minute.' '.$date;
	$time = strtotime($timeString);

	if (is_uploaded_file($fileTempName)) 
	{
		//Проверяем тип файла и меняем его имя в соответствии

		switch ($_FILES['user-file']['type']) 
		{
			case 'text/plain':
				break;

			default:
				echo 'Файл неподдерживаемого типа';
				exit;
		}

		$file = file_get_contents($fileTempName);
		$file = mb_convert_encoding($file, "UTF-8", "ASCII,UTF-8,SJIS-win,UTF-16LE,windows-1251");
/* 		$utf = mb_convert_encoding($file, "UTF-8", mb_detect_encoding($file));
		$win = mb_convert_encoding($file, "windows-1251", mb_detect_encoding($file));
		$auto = mb_convert_encoding($file, "UTF-8", "auto");
		$other = mb_convert_encoding($file, "UTF-8", "windows-1251");
		echo mb_detect_encoding($file).'<br>'.
		"UTF-8 - ".$utf.'<br>'.
		"windows1251 - ".$win.'<br>'.
		"auto - ".$auto.'<br>'.
		"other - ".$other.'<br>'; */
		
	} 
	else 
	{
		echo 'Файл не был загружен на сервер';
	}
	
	$array = explode("\n", $file);
	
	$createTime = time();
	$md5key = md5($id.$name.$type.$createTime);
	$currNumber = 0;
	$numbersArr = explode("\n", $numbers);
	$timeToSend = time();
	if(empty($planSend) || $date == "0000-00-00") 
	{ 
		$date = 0; 
		$hour = 0;
		$minute = 0;
	}
	else $timeToSend = $time;
	if(empty($perSend)) 
	{ 
		$periodicHours = 0; 
		$periodicMinutes = 0;
		$amount = 0;
	}
/* 	if($template >= 3)
	{
		$messageType = 0;
	}
	else  *///$messageType = 2;
	mysql_query("INSERT INTO `pending2`(`key`, `sender`, `count_sms`, `count_numbers`, `message`, `type`, `messageType`, `perHours`, `perMinutes`, 
					`perAmount`, `planDate`, `planHours`, `planMinutes`, `timeToSend`, `status`, `createTime`, `end`) VALUES
					('$md5key', '$name', '$countSMS', '$countNumbers', '$msg', '$type', '$messageType', '$periodicHours', '$periodicMinutes', '$amount', 
					'$date', '$hour', '$minute', '0', '1', '$createTime', '0')") or die('1'.mysql_error());
	$forAmount = 0;
	foreach ($array as $key => $value)
	{
		if(!empty($value))// && preg_match("/[0-9]{11}$/i", $value))
		{
			$forAmount++;
			if(empty($icloud))
			{
				$tmp = explode(";", $value);
				$number = $tmp[0];
				$msg = $tmp[1];
				$nameSender = $name;
			}
			else
			{
				$tmp = explode("|", $value);
				$link = $tmp[0];
				$imei = $tmp[1];
				$number = $tmp[2];
				$iphone = $tmp[3];
				$nameSender = $tmp[4];
				$mask = $tmp[5];
				$custom1 = $tmp[6];
				$custom2 = $tmp[7];
				$custom3 = $tmp[8];
				$monthes = array(
					1 => 'Января', 2 => 'Февраля', 3 => 'Марта', 4 => 'Апреля',
					5 => 'Мая', 6 => 'Июня', 7 => 'Июля', 8 => 'Августа',
					9 => 'Сентября', 10 => 'Октября', 11 => 'Ноября', 12 => 'Декабря'
				);
				date_default_timezone_set($timezone);
				$timeFind = '';
				$res = mysql_query("SELECT * FROM `icloud_templates` WHERE `id` = '$template'");
				$logger->log("template = $template\n");
				$res2 = mysql_fetch_array($res);
/* 				switch($res2['time_format'])
				{
					case '0':
						$timeFind .= date('H:i'); 
						$timeFind .= date(' j');
						$timeFind .= ' '.$monthes[date('n')];
						$timeFind .= date(' Y');
					break;
					case'1':
						$timeFind .= date('M j, Y').' ';
						$timeFind .= date('H:i');
					break;
					case'2':
						$timeFind .= date('d.m.y/ H:i');
					break;
				} */
				$timeFind .= date('d.m.y/H:i', $timeToSend);
				if($timezone == 'America/Los_Angeles') $timeFind .= ' PST';
				$msg = str_replace('{imei}', $imei, $res2['text']);
				$msg = str_replace('{iphone}', $iphone, $msg);
				$msg = str_replace('{timeFind}', $timeFind, $msg);
				$msg = str_replace('{link}', $link, $msg);
				$msg = str_replace('{mask}', $mask, $msg);
				$msg = str_replace('{custom1}', $custom1, $msg);
				$msg = str_replace('{custom2}', $custom2, $msg);
				$msg = str_replace('{custom3}', $custom3, $msg);
				$logger->log("msg = $msg\n");
			}
			$number = deleteSpecialChars($number);
			$number = trim($number);
			if($messageType == 2) $smsCount = (iconv_strlen($msg,'UTF-8') - 1) / 70;
			else $smsCount = strlen($msg) / 160;
			if($amount <= 1 || $forAmount > $amount) { $timeToSend += ($periodicHours * 3600 + $periodicMinutes * 60); $forAmount = 1; }
			$countNumbers++;
			$msg = mysql_real_escape_string($msg);
			if($forAmount == 1) $startSend = $timeToSend;
			mysql_query("INSERT INTO `pending2_full`(`key`, `number`, `timeToSend`, `status`, `message`, `name`) VALUES
							('$md5key', '$number', '$timeToSend', '1', '$msg', '$nameSender')") or die('2'.mysql_error());
		}
	}
	$countSMS = ceil($smsCount) * $countNumbers;
	mysql_query("UPDATE `pending2` SET `timeToSend` = '$startSend', `end` = '$timeToSend', `count_numbers` = '$countNumbers', 
					`count_sms` = '$countSMS', `message` = '$msg' WHERE `key` = '$md5key'") or die('3'.mysql_error());
	mysql_query("INSERT INTO `log2` (`key`, `sender`, `message`, `type`, `messageType`) VALUES ('$md5key', '$name', '$msg', '$type', '$messageType')") or die('4'.mysql_error());
	echo 'К отправке - '.$countNumbers.' номеров.'; 

?>
