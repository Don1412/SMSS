<?
header("Content-Type: text/html; charset=utf-8");
require_once("sms/Functions.php");
include("DBConnect.php");
session_start();
if(empty($_POST))
{
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

<meta charset="utf-8">
<title>Добавление канала</title>
</head>
<body>
<?
if($_SESSION['password'] == "19733791Zx")
{
?>
	<button id = "SMSSender" class="myButton" onclick="location.href='<?=getUrl();?>/'">Расширение</button>
	<button id = "manager" class="myButton" onclick="location.href='<?=getUrl();?>/SMSManager.php'">Менеджер</button>
	<button onclick="location.href='<?=getUrl();?>/SMSReport.php'" id = "SMSReport" class="myButton" style="width: 70px">Отчёт</button>
	<table id="myTable" class="tablesorter tablesorter-blue">
		<thead>
			<tr class="tablesorter-headerRow">
				<td data-column="id" data-sortby="ASC" style="cursor: pointer;">№</td>
				<td data-column="name" style="cursor: pointer;">Название</td>
				<td data-column="type" style="cursor: pointer;">Тип</td>
				<td data-column="login" style="cursor: pointer;">Логин</td>
				<td data-column="password" style="cursor: pointer;">Пароль</td>
				<td style="cursor: default;">Действие</td>
			</tr>
		</thead>
		<tbody>

		</tbody>
	</table>
	<button type="button" class="btn btn-success active" data-toggle="modal" data-target="#exampleModal">Добавить канал</button>
	<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<form action = "add.php" id = "add" role="form" method = "POST">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<div class = "row" style = "margin-top: 50px;">
							<div class = "col-md-8 col-md-offset-2">
								<h2>Добавление канала</h2><br>
								<div class="form-group">
									<label>Выбор сервиса</label>
									<select name='cmbService'  id="cmbService" title="Please Select Service" class="form-control">
									  <option value="0">Routesms</option>
									  <option value="1">Цифра</option>
									</select>
									<label for="text">Название</label><input id="name" name="name" class="form-control">
									<label for="text">Логин</label><input id="login" name="login" class="form-control">
									<label for="text">Пароль</label><input id="password" name="password" class="form-control">
								</div>
							</div>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
						<input type="submit" id="save" class = "btn btn-large btn-success" value="Добавить"></input>
					</div>
				</div>
			</form>
		</div>
	</div>
<script>
$('td').click(function(){
	var column = $(this).data('column');
	if(!!column)
	{
		var sortBY = $(this).attr('data-sortby');
		if(!!!sortBY || sortBY == 'DESC') { $('td[data-sortby]').removeAttr('data-sortby'); $(this).attr('data-sortby', 'ASC'); }
		else $(this).attr('data-sortby', 'DESC');
	}
});
function DeleteChannel(id)
{
	if(confirm("Вы действительно хотите удалить канал?"))
	{
		$.ajax({
			type: "POST",
			url: "functions.php",
			data: {'function':'deleteChannel', 'id':id},
			success: function(e)
			{
			}
		});
		return false;
	}
}
function ActivateChannel(id, value)
{
	$.ajax({
		type: "POST",
		url: "functions.php",
		data: {'function':'activateChannel', 'id':id, 'value':value},
		success: function(e)
		{
		}
	});
	return false;
}
function updateTable()
{
	var td = $('td[data-sortby]');
	var column = td.attr('data-column');
	var sortBY = td.attr('data-sortby');
	console.log('column = '+column+' sort = '+sortBY);
	$.ajax({
		type: "POST",
		url: "functions.php",
		data: {'function':'updateTable', 'table':'channels', 'column':column, 'sortBY':sortBY},
		success: function(e)
		{
			$('tbody').html(e);
		}
	});
}
$(document).ready(function()
{
	updateTable();
	setInterval(function()
	{
		updateTable();
	}, 300);
	// $('button').mouseover(function(){flag = false});
	// $('button').mouseout(function(){flag = true});
});
</script>
</body>
</html>
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
<?
}
else
{
	$name = $_POST['name'];
	$type = $_POST['cmbService'];
	$login = $_POST['login'];
	$password = $_POST['password'];
	
	$query = "INSERT INTO `channels2` (`name`, `type`, `login`, `password`) VALUES ('$name', '$type', '$login', '$password');"; mysql_query($query) or die(mysql_error());
	echo "<script>location.href=\"/add.php\";</script>";
}
?>