<?
header("Content-Type: text/html; charset=utf-8");
require_once("sms/Functions.php");
session_start();
if(empty($_SESSION['password'])) $_SESSION['password'] = "empty";
?>

<html>
<head>
<link rel="stylesheet" type="text/css" href="css/bootstrap.css">
<link rel="stylesheet" type="text/css" href="css/style.css">
<link rel="stylesheet" type="text/css" href="css/theme.blue.css">
<script src = "js/jquery-1.12.3.min.js"></script>
<script src = "js/jquery.form.min.js"></script>
<script src = "js/bootstrap.min.js"></script>
<script src = "js/date.js"></script>
<script src = "js/md5.js"></script>
<meta charset="utf-8">
<title>SMS Report</title>
</head>
<body>
<?
if($_SESSION['password'] == "19733791Zx")
{
?>
	<img src="img/back.png" id="btnBack" style="width: 25px" onclick="tableSort = 'general'; updateTable();" hidden/>
	<button id = "SMSSender" class="myButton" data-toggle="modal" data-target="#main-modal1" onclick="location.href='<?=getUrl();?>/'">Расширение</button>
	<button onclick="location.href='<?=getUrl();?>/SMSManager.php'" id = "SMSReport" class="myButton" data-toggle="modal" data-target="#main-modal2">Менеджер</button>
	<button onclick="truncate();" id = "Truncate" class="myButton">Очистить таблицу</button>
	<button onclick="CheckBoxes(0);" id = "deleteSelected" class="myButton">Удалить выбранные(0)</button>&nbsp;Session: <? echo $_SESSION['password']; ?>
<table id="myTable" class="tablesorter tablesorter-blue">
	<thead id="short">
		<tr class="tablesorter-headerRow">
		   <td>&nbsp;&nbsp;<input type = "checkbox" name = "id" onclick="selectAll(); event.stopPropagation();"/></td>
		   <td style="cursor: default;">№</td>
		   <td data-column="id" data-sortby="ASC" hidden></td>
		   <td data-column="type" style="cursor: pointer;">Тип</td>
		   <td data-column="count" style="cursor: pointer;">Номеров</td>
		   <td data-column="timeToSend" style="cursor: pointer;">Время отправления</td>
		   <td data-column="sender" style="cursor: pointer;">Имя отправителя</td>
		   <td data-column="message" style="cursor: pointer;">Сообщение</td>
		   <td data-column="status" style="cursor: pointer;">На сервис</td>
		   <td data-column="dlr_status" style="cursor: pointer;">На номер</td>
		</tr>
	</thead>
	<thead id="full" hidden>
		<tr class="tablesorter-headerRow">
		   <td data-column="number" style="cursor: pointer;">Номер</td>
		   <td data-column="name" style="cursor: pointer;">Имя</td>
		   <td data-column="message" style="cursor: pointer;">Соощение</td>
		   <td data-column="status" style="cursor: pointer;">На сервис</td>
		   <td data-column="dlr_status" style="cursor: pointer;">На номер</td>
		   <td data-column="timeToSend" style="cursor: pointer;">Время отправления</td>
		   <td data-column="dlr_time" style="cursor: pointer;">Время получения ответа</td>
		</tr>
	</thead>
    <tbody>
   
	</tbody>
</table>
<script>
var flag = 1, sortHead = 0, sortOrder = 0, tableSort = 'general', pages = 1, countArr, checkboxFlag = false; 
function selectAll()
{
	var length = $(':checkbox').length-1;
	if(checkboxFlag == false) { $("input[type=checkbox]").prop('checked', true); checkboxFlag = true; }
	else { $("input[type=checkbox]").prop('checked', false); checkboxFlag = false; }
	for(var i = 0; i < length; i++)
	{
		CheckBoxes($('#checkbox'+i).data('id'));
	}
}
var checkboxes = new Array();
function CheckBoxes(id)
{
	if(id == 0)
	{
		if(confirm("Вы действительно хотите удалить выбранные элементы?"))
		{
			$.ajax({
				type: "POST",
				url: "functions.php",
				data: {'function':'deleteSelected', 'array':checkboxes},
				success: function(e)
				{
					checkboxes = [];
					updateTable();
					$('#deleteSelected').text('Удалить выбранные('+checkboxes.length+')');
				}
			});
			return false;
		}
	}
	else
	{
		var n = checkboxes.indexOf(id);
		if(n != -1) checkboxes.splice(n, 1);
		else checkboxes.push(id);
		$('#deleteSelected').text('Удалить выбранные('+checkboxes.length+')');
		//var tmp = "Arr: "+checkboxes+" id: "+n;
		//alert(tmp);
	}
}
$('td').click(function(){
	var column = $(this).data('column');
	if(!!column)
	{
		var sortBY = $(this).attr('data-sortby');
		if(!!!sortBY || sortBY == 'DESC') { $('td[data-sortby]').removeAttr('data-sortby'); $(this).attr('data-sortby', 'ASC'); }
		else $(this).attr('data-sortby', 'DESC');
	}
});
function truncate()
{
	if(confirm("Вы действительно хотите очистить логи?"))
	{
 		$.ajax({
			type: "POST",
			url: "truncate.php",
			success: function(e)
			{
			   location.reload();
			}
		});
		return false;
	}
}
function updateTable()
{
	if(tableSort == 'general' && $('thead#short').is(':hidden'))
	{
		pages = 1;
		$('thead#full').hide();
		$('#btnBack').hide();
		$('thead#short').show();
		$('td[data-column=id]').attr('data-sortby', 'ASC');
	}
	else if(tableSort != 'general' && $('thead#full').is(':hidden'))
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
	console.log('column = '+column+' sort = '+sortBY+' forTable = '+tableSort);
	$.ajax({
		type: "POST",
		url: "functions.php",
		data: {'function':'updateTable', 'table':'report', 'forTable':tableSort, 'checkboxes':checkboxes, 'column':column, 'sortBY':sortBY, 'pages':pages},
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
		// pendingSMS(pendingFlag);
		// $('button').mouseenter(function(){flag = false;});
		// $('button').mouseleave(function(){flag = true});
	}, 90000);
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