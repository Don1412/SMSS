<?
require_once("sms/SendSMSRoute.php");
require_once("sms/Functions.php");
require_once("sms/Logger.php");


//Connection
global $sms_username;

global $sms_password;


$message = "";

include("DBConnect.php");

$timeLimit = time(); 
/* $res = mysql_query("SELECT b.*, GROUP_CONCAT(a.`number` ORDER BY a.`id` SEPARATOR ',') AS numbers,
					GROUP_CONCAT(a.`message` ORDER BY a.`id` SEPARATOR '|') AS messages FROM `pending2_full` a INNER JOIN `pending2` b using(`key`) 
					WHERE a.`timeToSend` < '$timeLimit' AND b.`status` = 1 GROUP BY `key`");*/
$res = mysql_query("SELECT b.*, a.`number`, a.`message`, a.`name` FROM `pending2_full` a 
					INNER JOIN `pending2` b using(`key`) WHERE a.`timeToSend` < '$timeLimit' AND b.`status` = 1");
if (mysql_num_rows($res) < 1){
        echo "There's no work for me!<br>";
        exit;
}

function sms__unicode($message)
{
	$hex1='';
	if (function_exists('iconv')) 
	{
		$latin = @iconv('UTF-8', 'ISO-8859-1', $message);
		if (strcmp($latin, $message)) 
		{
			$arr = unpack('H*hex', @iconv('UTF-8', 'UCS-2BE', $message));
			$hex1 = strtoupper($arr['hex']);
		}
		if($hex1 =='')
		{
			$hex2='';
			$hex='';
			for ($i=0; $i < strlen($message); $i++)
			{
				$hex = dechex(ord($message[$i]));
				$len =strlen($hex);
				$add = 4 - $len;
				if($len < 4)
				{
					for($j=0;$j<$add;$j++)
					{
						$hex="0".$hex;
					}
				}
				$hex2.=$hex;
			}
			return $hex2;
		}
		else
		{
			return $hex1;
		}
	}
	else
	{
		print 'iconv Function Not Exists !';
	}
}

while($row = mysql_fetch_assoc($res))
{
	$timeToSend = time();
	$key = $row['key'];
	$sender = $row['sender'];
	$nameSender = $row['name'];
	if(!empty($nameSender)) 
	{
		$sender = $nameSender;
	}
/* 	$numbers = $row['numbers'];
	$messages = $row['messages']; */
	$type = $row['type'];
	$messageType = $row['messageType'];
/* 	$numbersArr = explode(',', $numbers);
	$messagesArr = explode('|', $messages); */
	if($messageType == 0) { $messageType = 0; }
	else if($messageType == 1) { $msg = sms__unicode($message); $messageType = 2; }
	$res2 = mysql_query("SELECT * FROM `channels2` WHERE `id` = $type");
	$res3 = mysql_fetch_array($res2);
	$sms_username = $res3['login']; $sms_password = $res3['password'];
	if($res3['type'] == 0)
	{
		//$number = $numbersArr[$j];
		$number = $row['number'];
		//$msg = sms__unicode($messagesArr[$j]);
		if($messageType == 2)
		{
			$msg = sms__unicode($row['message']);
		}
		else
		{
			$msg = $row['message'];
		}
		//$message = $messagesArr[$j];
		$message = $row['message'];
		$message = mysql_real_escape_string($message);
		$data = array('username'=>$sms_username,
								'password'=>$sms_password,
								'type'=>$messageType,
								'dlr'=>1,
								'destination'=>$number,
								'source'=>$sender,
								'message'=>$msg);
		$request = "http://api.rmlconnect.net/bulksms/bulksms?".http_build_query($data)."";
		$request = str_replace("%0D", "", $request);
		/* echo $request; */
		$ch = curl_init($request);
		if (!$ch) 
		{
			$errstr = "Could not connect to server.";
			echo $errstr;
		}
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		$serverresponse = curl_exec($ch);

		if (!$serverresponse) 
		{
			$code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
			$errstr = "HTTP error: $code ".curl_error($ch)."\n";
			echo $errstr;
		}
		$code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);
		$part = explode(",", $serverresponse);
		for($i = 0; $i < count($part); $i++)
		{
			$numStat = explode("|", $part[$i]);
			$nums .= $numStat[1].'\n';
			$num = $numStat[1];
			$stat .= $numStat[0].'\n';
			$status = $numStat[0];
			$id = $numStat[2];
			$timeToSends .= $timeToSend.'\n';
			echo $serverresponse."||||||||".$num;
			$c = mysql_query("SELECT COUNT(*) FROM `log2_full` WHERE `key` = '$key' AND `number` = '$num'");
			mysql_query("UPDATE `log2` SET `dlr_id` = '$id' WHERE `key` = '$key'");
			mysql_query("INSERT INTO `log2_full` (`key`, `number`, `timeToSend`, `status`, `dlr_id`, `message`, `name`) VALUES ('$key', '$num', '$timeToSend', '$status', '$id', '$message', '$sender')") or die(mysql_error());
			mysql_query("INSERT INTO `log2_full_tmp` (`key`, `number`, `timeToSend`, `status`, `dlr_id`, `message`, `name`) VALUES ('$key', '$num', '$timeToSend', '$status', '$id', '$message', '$sender')") or die(mysql_error());
		}
		$str .= "key - ".$key." sender - ".$sender." toSendNumbers - ".$nums." SendedNumbers - ".$numbers." message - ".$message." status - ".$stat." type - ".$type."\n";
		//mysql_query("INSERT INTO `log3` (`key`, `sender`, `message`, `type`) VALUES ('$key', '$sender', '$message', '$type')") or die(mysql_error());
	}
	/* else
	{
		$soap = curl_init("https://my.smpp.com.ua/xml/");
		curl_setopt($soap, CURLOPT_POST, 1);
		curl_setopt($soap, CURLOPT_RETURNTRANSFER, 1);

		for($i = 0; $i < count($numbersArr); $i++)
		{
			$nums .= '<abonent phone="'.$numbersArr[$i].'" number_sms="'.$i.'"/>';
		}
		
		$request = '
		<?xml version="1.0" encoding="utf-8" ?>
		<request>
		<message>
		 <sender>'.$sender.'</sender>
		 <text>'.$message.'</text>
		 '.$nums.'
		</message>
	   <security>
		 <login value="'.$sms_username.'"/>
		 <password value="'.$sms_password.'"/>
	   </security>
	   </request>';

		curl_setopt($soap, CURLOPT_HTTPHEADER, 
				array('Content-Type: text/xml; charset=utf-8', 
					  'Content-Length: '.strlen($request)));

		curl_setopt($soap, CURLOPT_POSTFIELDS, $request);
		$serverresponse = curl_exec($soap);

		curl_close($soap);
		
		echo $serverresponse."||||||||";
		var_dump($request);
		
		$stat = explode(' ', $serverresponse);
		$dom = new domDocument;
		$dom->loadXML($serverresponse);
		$s = simplexml_import_dom($dom);
		$status = $s->information;
		for($i = 0; $i < count($numbersArr); $i++)
		{
			$num = $numbersArr[$i];
			//$status = $stat[$i];
			mysql_query("INSERT INTO `log2_full` (`key`, `number`, `timeToSend`, `status`, `message`) VALUES ('$key', '$num', '$timeToSend', '$status', ' ')") or die(mysql_error());
		}
		$str .= "key - ".$key." sender - ".$sender." toSendNumbers - ".$nums." SendedNumbers - ".$numbers." message - ".$message." status - ".print_r($stat)." type - ".$type."\n";
		//mysql_query("INSERT INTO `log3` (`key`, `sender`, `message`, `type`) VALUES ('$key', '$sender', '$message', '$type')") or die(mysql_error());
	} */
	echo $str;
}
?>