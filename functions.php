<?php
	require_once("sms/Functions.php");
	require_once("sms/Logger.php");
	
	include("DBConnect.php");
	if($_POST['function'] == 'template')
	{
		$type = $_POST['type']; $text = $_POST['text']; $name = $_POST['name']; $action = $_POST['action'];
		if($action == 1)
		{
			$type = $_POST['type']; $text = $_POST['text']; $name = $_POST['name'];
			$insert_into = mysql_query("INSERT INTO `templates2`(`type`, `text`, `name`) VALUES('$type', '$text', '$name')");
			$err = mysql_error();
		}
		else if($action == 2)
		{
			$res = mysql_query("SELECT * FROM `templates2` WHERE `name` = '$name'");
			$err = mysql_error();
			while($row = mysql_fetch_array($res))
			{
				$type = $row['type']; $id = $row['id']; $text = $row['text'];
				print($row['text']);
			}
		}
		else if($action == 3)
		{
			$res = mysql_query("DELETE FROM `templates2` WHERE `name` = '$name'");
		}
	}
	else if($_POST['function'] == 'time')
	{
		echo (date("d.m.Y H:i:s")); 
	}
	else if($_POST['function'] == 'delete')
	{
		$key = $_POST['key'];
		if(isset($key)) $res = mysql_query("DELETE FROM `pending2` WHERE `key` = '$key'");
	}
	else if($_POST['function'] == 'deleteChannel')
	{
		$id = $_POST['id'];
		if(isset($id)) $res = mysql_query("DELETE FROM `channels2` WHERE `id` = $id");
	}
	else if($_POST['function'] == 'activateChannel')
	{
		$id = $_POST['id']; $value = $_POST['value'];
		if(isset($id) && isset($value)) $res = mysql_query("UPDATE `channels2` SET `activate` = $value WHERE `id` = $id");
	}
	else if($_POST['function'] == 'addToDirTemplate_iCloud')
	{
		$directoryID = $_POST['directoryID']; $templateID = $_POST['templateID'];
		if(isset($directoryID) && isset($templateID)) 
		{
			mysql_query("UPDATE `icloud_templates` SET `directory` = $directoryID WHERE `id` = $templateID");
			$count = mysql_query("SELECT `count` FROM `icloud_directory` WHERE `id` = '$directoryID'");
			$row = mysql_fetch_assoc($count);
			$count = $row['count'];
			$count = $count + 1;
			mysql_query("UPDATE `icloud_directory` SET `count` = $count WHERE `id` = '$directoryID'");
		}
	}
	else if($_POST['function'] == 'deleteTemplates_iCloud')
	{
		$ids = $_POST['templates'];
		if(isset($ids)) 
		{
			$ids = implode(",", $ids);
			$res = mysql_query("DELETE FROM `icloud_templates` WHERE `id` in($ids)") or die(mysql_error());
		}
	}
	else if($_POST['function'] == 'deleteDirectory_iCloud')
	{
		$ids = $_POST['directory'];
		if(isset($ids)) 
		{
			$ids = implode(",", $ids);
			$res = mysql_query("DELETE FROM `icloud_directory` WHERE `id` in($ids)") or die(mysql_error());
		}
	}
	else if($_POST['function'] == 'activateTemplate_iCloud')
	{
		$id = $_POST['id']; $value = $_POST['value'];
		if(isset($id) && isset($value)) $res = mysql_query("UPDATE `icloud_templates` SET `activate` = $value WHERE `id` = $id");
	}
	else if($_POST['function'] == 'deleteTemplate_iCloud')
	{
		$id = $_POST['id'];
		if(isset($id)) $res = mysql_query("DELETE FROM `icloud_templates` WHERE `id` = $id");
	}
	else if($_POST['function'] == 'editTemplate_iCloud')
	{
		$id = $_POST['id'];
		if(isset($id))
		{
			$row = mysql_query("SELECT * FROM `icloud_templates` WHERE `id` = '$id'") or die(mysql_error());
			$array = mysql_fetch_row($row); 
			echo json_encode($array);
		}
	}
	else if($_POST['function'] == 'addDirectory')
	{
		$name = $_POST['name'];
		if(isset($name))
		{
			$row = mysql_query("INSERT INTO `icloud_directory`(`name`, `count`, `custom`) VALUES('$name', '0', '0')") or die(mysql_error());
		}
	}
	else if($_POST['function'] == 'openDirectory')
	{
		$id = $_POST['id'];
		if(isset($id))
		{
			$html = "<div class='row text-center'>
				<div class='col-md-12'>
				  <div class='row text-left mt-5'>";
			if($id == 0)
			{
				$res = mysql_query("SELECT * FROM `icloud_directory`");
				while($res2 = mysql_fetch_array($res))
				{
					$directoryID = $res2['id'];
					$name = $res2['name'];
					$html .="
					<div class='col-md-2 my-3' id='directory_$directoryID' data-id='$directoryID' ondrop='drop(event, this)' ondragover='allowDrop(event)' ondblclick='openDirectory($directoryID)'>
					  <div class='row mb-4'>
						<div class='text-center col-1 col-md-12'><i class='d-block fa fa-lg fa-folder mx-auto'></i>
						  <p class='text-secondary text-lowercase'><small>$name</small></p>
						</div>
					  </div>
					</div>";
				}
				$res = mysql_query("SELECT * FROM `icloud_templates` WHERE `directory` = 0");
				while($res2 = mysql_fetch_array($res))
				{
					$templateID = $res2['id'];
					$name = $res2['name'];
					$html .= "
					<div class='col-md-2 my-3' id='$templateID' draggable='true' ondragstart='drag(event)' ondblclick='editTemplate(this)'>
					  <div class='row mb-4' id='$templateID' name='$name' onclick='selectTemplate(this)'>
						<div class='text-center col-1 col-md-12'><i class='d-block fa fa-lg mx-auto fa-file-text-o'></i>
						  <p class='text-secondary text-lowercase'><small>$name</small></p>
						</div>
					  </div>
					</div>";
				}
			}
			else
			{
				$html .= "<div class='col-md-12'> <i class='d-block fa mx-auto fa-arrow-left fa-lg' onclick='openDirectory(0)'></i> </div><br>";
				$res = mysql_query("SELECT * FROM `icloud_templates` WHERE `directory` = '$id'") or die(mysql_error());
				while($res2 = mysql_fetch_array($res))
				{
					$directoryID = $res2['id'];
					$name = $res2['name'];
					$html .= "<div class='col-md-2 my-3' id='$directoryID' draggable='true' ondragstart='drag(event)' ondblclick='editTemplate(this)'>
							  <div class='row mb-4' id='$directoryID' name='$name' onclick='selectTemplate(this)'>
								<div class='text-center col-1 col-md-12'><i class='d-block fa fa-lg mx-auto fa-file-text-o'></i>
								  <p class='text-secondary text-lowercase'><small>$name</small></p>
								</div>
							  </div>
							</div>";
				}
				$html .= "				  </div>
						</div>
					  </div>";
			}
			echo $html;
		}
	}
	else if($_POST['function'] == 'deleteSelected')
	{
		if(isset($_POST['array']))
		{
			$arr = $_POST['array'];
			$s = implode(',', $arr);
			$res = mysql_query("DELETE FROM `log2` WHERE `id` IN($s)");
			echo mysql_error();
			//if($_POST['type'] == 2) $res = mysql_query("DELETE FROM `pending` WHERE `id` = $index");
		}
	}
	else if($_POST['function'] == 'pause')
	{
		if(isset($_POST['key']) && isset($_POST['status']))
		{
			$st = $_POST['status']; $key = $_POST['key'];
			$pauseTime = 0; $i = 0;
			if($st == 1)
			{
				$result = mysql_query("SELECT `pauseTime`, `pending2_full`.`timeToSend`, `pending2_full`.`id` FROM `pending2` INNER JOIN `pending2_full` using(`key`) WHERE `key` = '$key'");
				while($row = mysql_fetch_array($result))
				{
					$id = $row['id'];
					$sleepTime = time() - $row['pauseTime']; $curTime = $row['timeToSend'] + $sleepTime;
					$i++;
					if($i == 1) $startTime = $curTime;
					mysql_query("UPDATE `pending2_full` SET `timeToSend` = $curTime, `status` = $st WHERE `key` = '$key' AND `id` = $id") or die(mysql_error());
				}
				mysql_query("UPDATE `pending2` SET `timeToSend` = $startTime, `end` = $curTime, `pauseTime` = 0, `status` = $st WHERE `key` = '$key'") or die(mysql_error());
			}
			else
			{			
				$pauseTime = time();
				mysql_query("UPDATE `pending2` p, `pending2_full` f SET 
								p.`status` = $st, p.`pauseTime` = $pauseTime, 
								f.`status` = $st WHERE p.`key` = '$key' AND f.`key` = '$key'") or die(mysql_error());
			}
		}
	}
/* 	else if($_POST['function'] == 'pending')
	{
		$key = $_POST['key'];
		if(isset($key))
		{
			mysql_query("SET SESSION group_concat_max_len=30720");
			$row = mysql_query("SELECT GROUP_CONCAT(`number` SEPARATOR '\n') AS numbers FROM `pending_full` WHERE `key` = '$key'") or die(mysql_error());
			$res = mysql_fetch_array($row);
			echo $res['numbers'];
		}
	} */
/* 	else if($_POST['function'] == 'edit')
	{
		$key = $_POST['key'];
		if(isset($key))
		{
			mysql_query("SET SESSION group_concat_max_len=30720");
			$row = mysql_query("SELECT type, 
									   sender, 
									   GROUP_CONCAT(`number` SEPARATOR '\n'),
									   message,
									   perHours,
									   perMinutes,
									   perAmount,
									   planDate,
									   planHours,
									   planMinutes,
									   (SELECT `messageType` FROM `channels` WHERE `id` = `pending`.`type`) AS messageType
									   FROM `pending` INNER JOIN `pending_full` USING(`key`) WHERE `key` = '$key' GROUP BY `key`;") or die(mysql_error());
			$array = mysql_fetch_row($row); 
			echo json_encode($array);
		}
	} */
	else if($_POST['function'] == 'clearDB')
	{
		$res = mysql_query("TRUNCATE TABLE `log2`");
	}
	else if($_POST['function'] == 'updateTable')
	{
		if($_POST['table'] == 'manager')
		{
			if($_POST['forTable'] == 'general')
			{
				$query = "SELECT * FROM `pending2` ORDER BY `".$_POST['column']."` ".$_POST['sortBY'];
				$res = mysql_query($query); 
				$i = 0;
				while($row = mysql_fetch_array($res))
				{
					$sender = $row['sender'];
					$message = $row['message']; 
					$createTime = $row['createTime'];
					$perAmount = $row['perAmount']; $perMinutes = $row['perMinutes'];
					$startSend = $row['timeToSend'];
					$endSend = $row['end'];
					$date = $row['planDate'];
					$status = $row['status'];
					$numCount = $row['count_numbers'];
					$key = $row['key'];
					$smsCount = $row['count_sms'];
					$type = $row['type'];
					$len = mb_strlen($message, 'utf-8');
					$row2 = mysql_query("SELECT * FROM `channels2` WHERE `id` = $type");
					$res2 = mysql_fetch_array($row2);
					$messageType = '['.$res2['name'].']';
					if($res2['type'] == 0) 
					{
						if($row['messageType'] == 0) $messageType .= "Text";
						else if ($row['messageType'] == 1) $messageType .= "Unicode";
					}
					if($perAmount == 0 && $date == "0000-00-00") $period = 'Моментальная отправка';
					else if($perAmount == 0 && $date != "0000-00-00") $period = 'Запланированная отправка';
					else $period = $perAmount.' смс в '.$perMinutes.' мин';
					if($status == 1)
					{
						$pauseBtn = '<button type="button" class="btn btn-warning btn-xs active" onclick="PauseSend(\''.$key.'\', 2); event.stopPropagation();">Pause</button></td>';
					}
					else if($status == 2)
					{
						$pauseBtn = '<button type="button" class="btn btn-success btn-xs active" onclick="PauseSend(\''.$key.'\', 1); event.stopPropagation();">Start</button></td>';
					}
					$bodyTable .= '<tr name="tables" id="'.$i.'" onclick="pendingSMS(\''.$key.'\');" style="cursor: pointer;">
							<td id="index">'.$i.'</td>
							<td id="messageType">'.$messageType.'</td>
							<td id="countSMS">'.$smsCount.'</td>
							<td id="countNum">'.$numCount.'</td>
							<td>'.date("d.m.Y H:i:s", $createTime).'</td>
							<td>'.$period.'</td>
							<td>'.$sender.'</td>
							<td id="startSend">'.date("d.m.Y H:i:s", $startSend).'</td>
							<td id="endSend">'.date("d.m.Y H:i:s", $endSend).'</td>
							<td><button type="button" class="btn btn-danger btn-xs active" onclick="StopSend(\''.$key.'\'); event.stopPropagation();">Stop</button>
							'.$pauseBtn.'
						</tr>';
						$i++;
				}
			}
			else
			{
				unset($bodyTable);
				$key = $_POST['forTable'];
				$countOnPage = 1000; $page = $_POST['pages']; $startPos = ($page - 1) * $countOnPage; $endPos = $page * $countOnPage;
				$res = mysql_query("SELECT COUNT(*) AS count FROM `pending2_full` WHERE `key` = '$key'");
				$count = mysql_fetch_array($res); $count = $count['count'];
				$pages = ceil($count / $countOnPage); if($pages == 1) $pages = '';
				if(!empty($_POST['column'])) $column = $_POST['column']; else $column = 'id';
				$query = "SELECT * FROM `pending2_full` WHERE `key` = '$key' ORDER BY `".$_POST['column']."` ".$_POST['sortBY'];
				$res = mysql_query($query);
				while($row = mysql_fetch_array($res))
				{
					$number = $row['number'];
					$timeToSend = $row['timeToSend'];
					$status = $row['status'];
					$dlr_status = $row['dlr_status'];
					$dlr_time = $row['dlr_time'];
					$message = $row['message'];
					$name = $row['name'];
					$bodyTable .= 
						'<tr>
							<td id="nums">'.$number.'</td>
							<td id="name">'.$name.'</td>
							<td id="message" style="white-space: normal;" width="900">'.$message.'</td>
							<td id="status">'.$errors[0][$status].'</td>
						</tr>';
				}
				$bodyTable .= '<tr><td class="tdPages" colspan="8">';
				for($i = 1; $i <= $pages; $i++) 
				{
					if($i != $page) $bodyTable .= '<a id="'.$i.'" onclick="pages = '.$i.';" style="cursor: pointer;">'.$i.'</a>&nbsp;&nbsp;&nbsp;';
					else $bodyTable .= '<b style="color: #000000;">'.$i.'</b>&nbsp;&nbsp;&nbsp;';
				}
				$bodyTable .= '</td></tr>';
			}
			echo $bodyTable;
		}
		else if($_POST['table'] == 'report')
		{
			$i = 0; $j = 0;
			$errors = array(array(
								"1701" => "Отправлено",
								"1702" => "Ошибка URL",
								"1703" => "Неверный логин или пароль",
								"1704" => "Неверный тип",
								"1705" => "Некорректно введено сообщение",
								"1706" => "Неверный номер получателя",
								"1707" => "Неверный номер отправителя",
								"1708" => "Ошибка dlr",
								"1709" => "Ошибка проверки пользователя",
								"1710" => "Внутренняя ошибка",
								"1025" => "Недостаточно средств",
								"1715" => "Ожидание ответа",
								"send" => "Отправлено"
							),
							array(
								"DELIVRD" => "Доставлено",
								"UNDELIV" => "Не доставлено",
								"PENDING" => "Ожидание ответа",
								"" => "Ожидание ответа"
							));
			if($_POST['forTable'] == 'general')
			{
				$query = "SELECT * FROM `log2` WHERE `count` <> 0 ORDER BY `".$_POST['column']."` ".$_POST['sortBY'];
				$res = mysql_query($query);
				unset($bodyTable);
				while($row = mysql_fetch_array($res))
				{
					$answer = "Unknown";
					$id = $row['id'];
					$key = $row['key'];
					$count = $row['count'];
					$sender = $row['sender'];
					$message = $row['message'];
					$type = $row['type'];
					$timeToSend = $row['timeToSend'];
					$status = $row['status'];
					$dlr_status = $row['dlr_status'];
					$messageType = $row['messageType'];
					$stat = $status;
					$row2 = mysql_query("SELECT * FROM `channels2` WHERE `id` = $type");
					$res2 = mysql_fetch_array($row2);
					$typeMess = '['.$res2['name'].']';
					if($messageType == 0) $typeMess .= "Text";
					else if($messageType == 1) $typeMess .= "Unicode";
					$stat = $errors[0][$status];
					/* if($res2['type'] == 0) 
					{
						if($row['messageType'] == 0) $typeMess .= "Text";
						else if ($row['messageType'] == 1) $typeMess .= "Unicode";
						$stat = $errors[0][$status];
					} */
					$strKey = "'".$key."'";
					if($tmpDate != date("d.m.Y", $timeToSend))
					{
						$tmpDate = date("d.m.Y", $timeToSend);
						$bodyTable .= '<tr><td class="tdDate" colspan="9">'.$tmpDate.'</td></tr>';
						$i = 0;
					}
					$bodyTable .= '<tr id="'.$i.'" name="1" ondblclick="tableSort = '.$strKey.'; updateTable();" style="cursor: pointer;">
									<td id="'.$id.'"><input id="checkbox'.$j.'"  data-id="'.$id.'" type="checkbox" onclick="CheckBoxes('.$id.'); updateTable(); event.stopPropagation();"'.$checked.'/></td>
									<td id="index">'.$i.'</td>
									<td id="type">'.$typeMess.'</td>
									<td id="countNum">'.$count.'</td>
									<td id="timeToSend">'.date("d.m.Y H:i:s", $timeToSend).'</td>
									<td id="sender">'.$sender.'</td>
									<td id="message" style="white-space: normal;" width="900">'.$message.'</td>
									<td id="status">'.$stat.'</td>
									<td id="dlr_status">'.$errors[1][$dlr_status].'</td>
								</tr>';
					$i++; 
					$j++;
				}
			}
			else
			{
				unset($bodyTable);
				$key = $_POST['forTable'];
				$countOnPage = 1000; $page = $_POST['pages']; $startPos = ($page - 1) * $countOnPage; $endPos = $page * $countOnPage;
				print_r($key);
				$res = mysql_query("SELECT COUNT(*) AS count FROM `log2_full` WHERE `key` = '$key'");
				$count = mysql_fetch_array($res); $count = $count['count'];
				$pages = ceil($count / $countOnPage); if($pages == 1) $pages = '';
				if(!empty($_POST['column'])) $column = $_POST['column']; else $column = 'id';
				$query = "SELECT * FROM `log2_full` WHERE `key` = '$key' ORDER BY `".$_POST['column']."` ".$_POST['sortBY']." LIMIT ".$startPos.",".$countOnPage;
				$res = mysql_query($query);
				while($row = mysql_fetch_array($res))
				{
					$number = $row['number'];
					$timeToSend = $row['timeToSend'];
					$status = $row['status'];
					$dlr_status = $row['dlr_status'];
					$dlr_time = (int)$row['dlr_time'];
					if($dlr_time > 0) $dlr_time = date("d.m.Y H:i:s", $dlr_time);
					else $dlr_time = '';
					$message = $row['message'];
					$name = $row['name'];
					$bodyTable .= 
						'<tr>
							<td id="nums">'.$number.'</td>
							<td id="name">'.$name.'</td>
							<td id="message" style="white-space: normal;" width="900">'.$message.'</td>
							<td id="status">'.$errors[0][$status].'</td>
							<td id="dlr_status">'.$errors[1][$dlr_status].'</td>
							<td id="timeToSend">'.date("d.m.Y H:i:s", $timeToSend).'</td>
							<td id="dlr_time">'.$dlr_time.'</td>
						</tr>';
				}
				$bodyTable .= '<tr><td class="tdPages" colspan="8">';
				for($i = 1; $i <= $pages; $i++) 
				{
					if($i != $page) $bodyTable .= '<a id="'.$i.'" onclick="pages = '.$i.';" style="cursor: pointer;">'.$i.'</a>&nbsp;&nbsp;&nbsp;';
					else $bodyTable .= '<b style="color: #000000;">'.$i.'</b>&nbsp;&nbsp;&nbsp;';
				}
				$bodyTable .= '</td></tr>';
			}
			echo $bodyTable;
		}
		if($_POST['table'] == 'channels')
		{
			$query = "SELECT * FROM `channels2` ORDER BY `".$_POST['column']."` ".$_POST['sortBY'];
			$res = mysql_query($query); 
			$i = 0;
			while($row = mysql_fetch_array($res))
			{
				$id = $row['id'];
				$name = $row['name']; 
				$type = $row['type'];
				$login = $row['login']; 
				$password = $row['password'];
				$activate = $row['activate'];
				$services = array(
								0 => "Routesms",
								1 => "Цифра"
								);
				if($activate == 1)
				{
					$pauseBtn = '<button type="button" class="btn btn-warning btn-xs active" onclick="ActivateChannel(\''.$id.'\', 0); event.stopPropagation();">Deactivate</button></td>';
				}
				else if($activate == 0)
				{
					$pauseBtn = '<button type="button" class="btn btn-success btn-xs active" onclick="ActivateChannel(\''.$id.'\', 1); event.stopPropagation();">Activate</button></td>';
				}
				$bodyTable .= '<tr name="tables" id="'.$i.'">
						<td id="id">'.$id.'</td>
						<td id="name">'.$name.'</td>
						<td id="type">'.$services[$type].'</td>
						<td id="login">'.$login.'</td>
						<td id="password">'.$password.'</td>
						<td><button type="button" class="btn btn-danger btn-xs active" onclick="DeleteChannel(\''.$id.'\'); event.stopPropagation();">Delete</button>
						'.$pauseBtn.'
					</tr>';
				$i++;
			}
			echo $bodyTable;
		}
		if($_POST['table'] == 'icloud_templates')
		{
			$query = "SELECT * FROM `icloud_templates` ORDER BY `".$_POST['column']."` ".$_POST['sortBY'];
			$res = mysql_query($query); 
			$i = 0;
			while($row = mysql_fetch_array($res))
			{
				$id = $row['id'];
				$name = $row['name']; 
				$time = $row['time_format'];
				$text = $row['text'];
				$activate = $row['activate'];
				
				$monthes = array(
					1 => 'Января', 2 => 'Февраля', 3 => 'Марта', 4 => 'Апреля',
					5 => 'Мая', 6 => 'Июня', 7 => 'Июля', 8 => 'Августа',
					9 => 'Сентября', 10 => 'Октября', 11 => 'Ноября', 12 => 'Декабря'
				);
				$timeFind = '';
				switch($time)
				{
					case '0':
						$timeFind .= date('H:i'); 
						$timeFind .= date(' j');
						$timeFind .= ' '.$monthes[date('n')];
						$timeFind .= date(' Y');
					break;
					case'1':
						$timeFind .= date('H:i');
						$timeFind .= ' on '.date('F j, Y');
					break;
				}
				
				if($activate == 1)
				{
					$pauseBtn = '<button type="button" class="btn btn-warning btn-xs active" onclick="ActivateTemplate_iCloud(\''.$id.'\', 0); event.stopPropagation();">Deactivate</button>';
				}
				else if($activate == 0)
				{
					$pauseBtn = '<button type="button" class="btn btn-success btn-xs active" onclick="ActivateTemplate_iCloud(\''.$id.'\', 1); event.stopPropagation();">Activate</button>';
				}
				$bodyTable .= '<tr name="tables" id="'.$i.'">
						<td id="id">'.$id.'</td>
						<td id="name">'.$name.'</td>
						<td id="text" style="white-space: normal;" width="900">'.$text.'</td>
						<td id="time_format">'.$timeFind.'</td>
						<td><button type="button" class="btn btn-danger btn-xs active" onclick="DeleteTemplate_iCloud(\''.$id.'\'); event.stopPropagation();">Delete</button>
						'.$pauseBtn.'
						<button type="button" class="btn btn-primary btn-xs active" onclick="event.stopPropagation(); EditTemplate_iCloud('.$id.');">Edit</button></td>
					</tr>';
				$i++;
			}
			echo $bodyTable;
		}
	}
?>