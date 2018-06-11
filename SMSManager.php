<?
header("Content-Type: text/html; charset=utf-8");
require_once("sms/Functions.php");

include("DBConnect.php");

session_start();
if(empty($_SESSION['password'])) $_SESSION['password'] = "empty";
?>

<html>
<head>
<link rel="stylesheet" type="text/css" href="css/bootstrap.css">
<link rel="stylesheet" type="text/css" href="css/style.css">
<link rel="stylesheet" type="text/css" href="css/theme.blue.css">
<link rel="stylesheet" type="text/css" href="css/jquery.formstyler.css">
<script src = "js/jquery-1.12.3.min.js"></script>
<script src = "js/jquery.form.min.js"></script>
<script src = "js/bootstrap.min.js"></script>
<script src = "js/date.js"></script>
<script src = "js/functions.js"></script>
<!--<script src = "js/functions.js"></script>!-->
<meta charset="utf-8">
<title>SMS Sending</title>
</head>
<body>
<?
if($_SESSION['password'] == "19733791Zx")
{
?>
	<div style="position:relative" id="zv-load">
	<div class="zv-load-css">
	<img src="img/loading.gif" alt="Загружаем страницу..." style="vertical-align: middle;" > Загружаем страницу...</div>
	</div>
	<div class="modal fade" id="main-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	  <div class="modal-dialog">
		<div class="modal-content">
		  <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			<h4 class="modal-title" id="myModalLabel">Изменение рассылки</h4>
		  </div>
		  <div id = "modal-text" class="modal-body">
			<form action = "manager.php" id = "newTaskDataEdit" role="form" method = "POST">
				<div class="form-group">
					<label>Выбор сервиса</label>
					<select name='Service'  id="cmbService" title="Please Select Service" onchange="CheckService();" class="form-control">
						<?
							$res = mysql_query("SELECT * FROM `channels2` WHERE `activate` = 1");
							while($res2 = mysql_fetch_array($res))
							{
								?>
									<option value="<?=$res2['id']?>" data-type="<?=$res2['type']?>"><?=$res2['name']?></option>
								<?
							}
						?>
					</select>
				</div>
				<div class="form-group">
					<label for="text        ">Имя отправителя</label>
					<div class="jq-selectbox jqselect">
					   <input id="selectInput1" name="selectInput1" class="form-control"><button type="button" id="addTplName" style="position: absolute; top:281px; right:20px; display:none;"><img src="img/+.png" width="20"></button>
						<div id="SelectDropdown1" class="jq-selectbox__dropdown" style="position: absolute; display:none">
						  <ul class="drop1" style="position: relative; list-style: none; overflow: auto; overflow-x: hidden;" tabindex="1">
							<?
								$res = mysql_query("SELECT * FROM `templates2` WHERE `type` = '1'");
								while($res2 = mysql_fetch_array($res))
								{
							?>
							<li id="<?=$res2['name']?>"><?=$res2['name']?><button type="button" id="<?=$res2['name']?>" style="position: absolute; right:5px;"><img src="img/mysor.png" width="10"></button></li>
							<?
								}
							?>
						  </ul>
						</div>
					</div>
				</div>
				<div class="form-group">
					<label for="pass">Номера получателей</label>
					<textarea class="form-control" id="numberArea" placeholder="Номера" rows = "20" name = "numbers" required></textarea>
					<div id="countNumber">К отправке номеров: 0</div>
				</div>
				<div class="form-group">
					<label>Тип сообщения</label>
					<select name='MessageType'  id="cmbMessageType" title="Please Select Message Type" onchange="SMSLength(this.value);" class="form-control">
					  <option value="0">Text</option>
					  <option value="1">Unicode</option>
					</select>
				</div>
				<div class="form-group">
					<label for="pass">Сообщение</label>
					<textarea class="form-control" id="smsarea" placeholder="Сообщение" name = "message" required></textarea>
					<div style="height:25px">
						<div id="barbox">
							<div id="bar"> </div>	
						</div>
						<div id="count"></div>
					</div>
					<label for="text" style="position:relative; top:-20px; font-size:11;">Шаблон:
						<div class="jq-selectbox2 jqselect2">
						   <input id="selectInput2" class="selectMess"><button type="button" id="addTplMess" style="position: absolute; top:0px; right:-148px; display:none;"><img src="img/+.png" width="10"></button>
							<div id="SelectDropdown2" class="jq-selectbox__dropdown2" style="position: absolute; display:none">
							  <ul class="drop2" style="position: relative; list-style: none; overflow: auto; overflow-x: hidden;" tabindex="1">
								<?
									$res = mysql_query("SELECT * FROM `templates2` WHERE `type` = '2'");
									while($res2 = mysql_fetch_array($res))
									{
										$arr[] = $res2;
								?>
								<li id="<?=$res2['name']?>"><?=$res2['name']?><button type="button" id="<?=$res2['name']?>" style="position: absolute; right:5px;"><img src="img/mysor.png" width="10"></button></li>
								<?
									}
								?>
							  </ul>
							</div>
						</div>
					</label>
				</div>
				<div class = "form-group">
					<div class = "checkbox">
						<label><input type = "checkbox" name = "PerSend"> Периодическая отправка</label>
					</div>
					<p class="help-block">Сообщения будут отправляться через заданный период времени</p>
					Каждые  <input type = "text" name = "periodicHours" value = "0"></input> часов<br>
					Каждые <input type = "text" name = "periodicMinutes" value="1"></input> минут<br>
					<p class="help-block">Период отправки сообщений</p>
					Кол-во сообщений <input type = "text" name = "amount" value="1"></input><br>
					<p class="help-block">Сколько номеров будет обрабатываться за период</p>

					<div class = "checkbox">
						<label><input type = "checkbox" name = "PlanSend"> Запланировать отправку</label>
					</div>
					<p class="help-block">Будет сделана одна рассылка в определенное время</p>
					Дата <input type = "date" name = "date" value = "<? echo $date; ?>"></input>
					<p class="help-block">DD.MM.YYYY</p>
					Час <input type = "text" name = "hour" value = "<? echo $timeH; ?>"></input>
					<p class="help-block">HH</p>
					Минута <input type = "text" name = "minute" value = "<? echo $timeM; ?>"></input>
					<p class="help-block">MM</p>
				</div>
				<input type = "text" id="key" name = "key" value = "<? echo $key; ?>" hidden></input>
				<input type="submit" id="beginSend" class = "btn btn-large btn-success" value="Сохранить изменения"></input>
				<button type="button" class="btn btn-default" id="Close" data-dismiss="modal">Закрыть</button>
			</form>
		  </div>
		</div>
	  </div>
	</div>
	<div class="modal fade" id="main-modal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel2" aria-hidden="true">
	  <div class="modal-dialog">
		<div class="modal-content">
		  <div class="modal-header">
			<button type="button" class="close" id="btnMClose" data-dismiss="modal" aria-hidden="true">&times</button>
			<h4 class="modal-title" id="myModalLabel">Смс в ожидании</h4>
		  </div>
		  <div id = "modal-text" class="modal-body">
			<div class="form-group">
				<label for="pass">Номера получателей</label>
				<textarea class="form-control" id="numberArea2" placeholder="Номера" rows = "20" name = "numbers" required></textarea>
			</div>
		  </div>
		</div>
	  </div>
	</div>

				<img src="img/back.png" id="btnBack" style="width: 25px" onclick="forTable = 'general'; updateTable();" hidden/>
                <button id = "SMSSender" class="myButton" onclick="location.href='<?=getUrl();?>/'">Расширение</button>
				<button onclick="location.href='<?=getUrl();?>/SMSReport.php'" id = "SMSReport" class="myButton" style="width: 70px">Отчёт</button>
				<div id="timeDisp"><script>timeDisp();</script></div>
<table id="myTable" class="tablesorter tablesorter-blue">
	<thead id="short">
		<tr class="tablesorter-headerRow">
			<td data-column="id" data-sortby="ASC" style="cursor: pointer;">№</td>
			<td data-column="messageType" style="cursor: pointer;">Тип</td>
			<td data-column="count_sms" style="cursor: pointer;">Cмс</td>
			<td data-column="count_numbers" style="cursor: pointer;">Номеров</td>
			<td data-column="createTime" style="cursor: pointer;">Время создания</td>
			<td style="cursor: default;">Периодичность</td>
			<td data-column="sender" style="cursor: pointer;">Имя отправителя</td>
			<td data-column="timeToSend" style="cursor: pointer;">Начало отправки</td>
			<td data-column="end" style="cursor: pointer;">Окончание отправки</td>
			<td style="cursor: default;">Действие</td>
		</tr>
	</thead>
	<thead id="full" hidden>
		<tr class="tablesorter-headerRow">
		   <td data-column="number" style="cursor: pointer;">Номер</td>
		   <td data-column="name" style="cursor: pointer;">Имя</td>
		   <td data-column="message" style="cursor: pointer;">Соощение</td>
		   <td data-column="status" style="cursor: pointer;">Действие</td>
		</tr>
	</thead>
	<tbody>

	</tbody>
</table>
<script>
var flag = true; var pendingFlag = 0; var keyEdit; var columnOLD; var sortHead = 0; var sortOrder = 1; var tableData = 0, countArr;
var forTable = 'general';
$('td').click(function(){
	var column = $(this).data('column');
	if(!!column)
	{
		var sortBY = $(this).attr('data-sortby');
		if(!!!sortBY || sortBY == 'DESC') { $('td[data-sortby]').removeAttr('data-sortby'); $(this).attr('data-sortby', 'ASC'); }
		else $(this).attr('data-sortby', 'DESC');
	}
});
function pendingSMS(key)
{
	forTable = key;
	updateTable();
}
function EditSend(key)
{
	forTable = key;
	console.log(key);
	$.ajax({
			type: "POST",
			url: "functions.php",
			data: {'function':'pause', 'key':key, 'status':2},
			success: function(e)
			{
				updateTable();
			}
		});
	updateTable();
}
function PauseSend(key, st)
{
	//$res3 = mysql_query("DELETE FROM `pending` WHERE `index` = "ind"");
	if(confirm("Вы действительно хотите приостановить рассылку?"))
	{
 		$.ajax({
			type: "POST",
			url: "functions.php",
			data: {'function':'pause', 'key':key, 'status':st},
			success: function(e)
			{
				updateTable();
			}
		});
	}
}
function PauseSendE(ind, st)
{
	$.ajax({
		type: "POST",
		url: "functions.php",
		data: {'function':'pause', 'index':ind, 'status':st},
		success: function(e)
		{
			updateTable();
		}
	});
}
function StopSend(key)
{
	if(confirm("Вы действительно хотите удалить рассылку?"))
	{
		$.ajax({
			type: "POST",
			url: "functions.php",
			data: {'function':'delete', 'key':key},
			success: function(e)
			{
				updateTable();
			}
		});
		return false;
	}
}
function updateTable()
{
	if(forTable == 'general' && $('thead#short').is(':hidden'))
	{
		pages = 1;
		$('thead#full').hide();
		$('#btnBack').hide();
		$('thead#short').show();
		$('td[data-column=id]').attr('data-sortby', 'ASC');
	}
	else if(forTable != 'general' && $('thead#full').is(':hidden'))
	{
		pages = 1;
		$('thead#short').hide();
		$('thead#full').show();
		$('#btnBack').show();
		$('td[data-column=timeToSend]').attr('data-sortby', 'ASC');
	}
	var td = $('td[data-sortby]');
	var column = td.attr('data-column');
	var sortBY = td.attr('data-sortby');
	console.log('column = '+column+' sort = '+sortBY);
	$.ajax({
		type: "POST",
		url: "functions.php",
		data: {'function':'updateTable', 'table':'manager', 'forTable':forTable, 'column':column, 'sortBY':sortBY},
		success: function(e)
		{
			$('tbody').html(e);
			console.log(e);
		}
	});
}
$(document).ready(function()
{
	updateTable();
	setInterval(function()
	{
		updateTable();
		pendingSMS(pendingFlag);
	}, 30000);
	// $('button').mouseover(function(){flag = false});
	// $('button').mouseout(function(){flag = true});
});
</script>
<?
}
else
{
?>
<div class = "row" style = "margin-top: 50px;">
        <div class = "col-md-8 col-md-offset-2">
                <h2>&nbsp;Session: <? echo $_SESSION['password']; ?>Ввод пароля</h2><br>
				<div id="timeDisp"><script>timeDisp();</script></div>
    <hr>
                <form action = "login.php" method = "POST">
                        <div class="form-group">
							<label for="text        ">Пароль:</label>
						    <input type="text" id="password" name="password" class="form-control"/>
                        </div>
                        <input type="submit" class = "btn btn-large btn-success" value="Вход"></input>
                </form>
        </div>
</div>
<?
}
?>
</body>
</html>