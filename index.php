<?
require_once("sms/Functions.php");
include("DBConnect.php");
session_start();
header("Content-Type: text/html; charset=utf-8");
if(empty($_SESSION['password'])) $_SESSION['password'] = "empty";
?>
<html>
<head>

<link rel="stylesheet" type="text/css" href="css/style.css">
<link rel="stylesheet" type="text/css" href="css/jquery.formstyler.css">
<script src = "js/jquery-1.12.3.min.js"></script>
<script src = "js/jquery.form.min.js"></script>
<script src = "js/bootstrap.min.js"></script>
<script src = "js/functions.js"></script>
<meta charset="utf-8">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" type="text/css">
<link rel="stylesheet" href="css/bootstrap-4.0.0-beta.1.css" type="text/css"> 
<style>
.mb-4:hover
{
	background: #b4b4b4;
}
.active
{
	background: rgba(66,66,66, 0.6);
}
</style>
<title>SMS Manager</title>
</head>
<body>
<?
if($_SESSION['password'] == '19733791Zx')
{
?>
<div style="position:relative" id="zv-load">
			<div class="zv-load-css">
			<img src="img/loading.gif" alt="Загружаем страницу..." style="vertical-align: middle;" > Загружаем страницу...</div>
			</div>

<div id = "notifications-top-left"></div>
<div class = "row" style = "margin-top: 50px;">
<div class="col-md-2"> </div>
        <div class = "col-md-8 col-md-offset-2">
                <h2>СМС рассылка</h2><br>
				<div id="timeDisp"><script>timeDisp();</script></div>
                <button id = "SMSSender" class="myButton" onclick="location.href='<?=getUrl();?>/SMSManager.php'">Менеджер</button>
				<button onclick="location.href='<?=getUrl();?>/SMSReport.php'" id = "SMSReport" class="myButton" style="width: 70px">Отчёт</button>
				<button onclick="location.href='<?=getUrl();?>/add.php'" class="myButton">Настройки каналов</button>
				<button onclick="location.href='<?=getUrl();?>/templates.php'" class="myButton">Шаблоны iCloud</button>
				<button onclick="location.href='<?=getUrl();?>/logout.php'" class="myButton">Выход</button>
    <hr>
                <form action = "manager.php" id = "newTaskData" role="form" method = "POST" enctype="multipart/form-data">
						<div class="form-group col-md-12">
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
                        <div class="form-group col-md-12" id="nameSender">
                                <label for="text        ">Имя отправителя</label>
								<div class="jq-selectbox jqselect">
								   <input id="selectInput1" name="selectInput1" class="form-control" required>
								   <button type="button" id="addTplName" style="position: absolute; top:281px; right:20px; display:none; z-index:10;"><img src="img/+.png" width="20"></button>
									<div id="SelectDropdown1" class="jq-selectbox__dropdown" style="position: absolute; display:none; z-index:10;">
									  <ul class="drop1" style="position: relative; list-style: none; overflow: auto; overflow-x: hidden; z-index:10;" tabindex="1">
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
                                <p class="help-block">Имя или номер</p>
                        </div>

                        <div class="form-group col-md-12">
								<div class = "checkbox">
									<label><input type = "checkbox" id="icloud" name = "icloud"> iCloud</label>
								</div>
                                <label for="user-file">Загрузить файл</label>
                                <input accept="text/plain" name="user-file" id="user-file" type="file" onchange='openFile(event)' required>
                        </div>
						<div id="templates" class="from-group" style="display: none;">
								<div id="templateContainer" class="container">
								  <div class="row text-center">
									<div class="col-md-12">
									  <div class="row text-left mt-5">
										<?
											$res = mysql_query("SELECT * FROM `icloud_directory`");
											while($res2 = mysql_fetch_array($res))
											{
												?>
												<div class="col-md-2 my-3" id="directory_<?=$res2['id']?>" data-id="<?=$res2['id']?>" ondrop="drop(event, this)" ondragover="allowDrop(event)" ondblclick="openDirectory('<?=$res2['id']?>')">
												  <div class="row mb-4">
													<div class="text-center col-1 col-md-12"><i class="d-block fa fa-lg fa-folder mx-auto"></i>
													  <p class="text-secondary text-lowercase"><small><?=$res2['name']?></small></p>
													</div>
												  </div>
												</div>
												<?
											}
											$res = mysql_query("SELECT * FROM `icloud_templates` WHERE `directory` = 0");
											while($res2 = mysql_fetch_array($res))
											{
												?>
												<div class="col-md-2 my-3" id="<?=$res2['id']?>" draggable="true" ondragstart="drag(event)" ondblclick="editTemplate(this)">
												  <div class="row mb-4" id="<?=$res2['id']?>" name="<?=$res2['name'];?>" onclick="selectTemplate(this)">
													<div class="text-center col-1 col-md-12"><i class="d-block fa fa-lg mx-auto fa-file-text-o"></i>
													  <p class="text-secondary text-lowercase"><small><?=$res2['name']?></small></p>
													</div>
												  </div>
												</div>
												<?
											}
										?>
									  </div>
									</div>
								  </div>
								</div>
								<input type="text" id="selectedTemplate" name="selectedTemplate" value="0" hidden>
							<div class="col-md-12">
								<label>Выбор часового пояса</label>
								<select name='timezone'  id="Timezone" title="Please Select Timezone" class="form-control">
									<option value="America/Los_Angeles">PST</option>
									<option value="Europe/Moscow">Москва(Россия)</option>
									<option value="America/New_York">Нью-Йорк(США)</option>
									<option value="Europe/Berlin">Берлин(Германия)</option>
									<option value="Europe/Kiev">Киев(Украина)</option>
									<option value="Asia/Almaty">Алмата(Казахстан)</option>
									<option value="Europe/Paris">Париж(Франция)</option>
									<option value="Europe/Madrid">Мадрид(Испания)</option>
									<option value="Africa/Malabo">Малабо(Африка)</option>
								</select>
							</div>
						</div>
						<div class="form-group col-md-12">
							<label>Тип сообщения</label>
							<select name='MessageType'  id="cmbMessageType" title="Please Select Message Type" onchange="SMSLength(this.value);" class="form-control" required>
							  <option value=""> -- Select -- </option>
							  <option value="0">Text</option>
							  <option value="1">Unicode</option>
							</select>
						</div>
						<br><br>
                        <div class = "form-group col-md-12">
							<div class = "checkbox">
								<label><input type = "checkbox" name = "PerSend"> Периодическая отправка</label>
							</div>
							<p class="help-block">Сообщения будут отправляться через заданный период времени</p>
							Каждые  <input type = "text" name = "periodicHours" value = "0"></input> часов<br>
							Каждые <input type = "text" name = "periodicMinutes" value="1"></input> минут<br>
							<p class="help-block">Период отправки сообщений</p>
							Кол-во сообщений <input type = "text" name = "amount" value="1" required></input><br>
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
							<input type="submit" id="beginSend" class = "btn btn-large btn-success" value="Начать рассылку"></input>
                        </div>

                        
                </form>
        </div>
</div>
<?
}
else
{
?>
<div class = "row" style = "margin-top: 50px;">
        <div class = "col-md-8 col-md-offset-2">
                <h2>&nbsp;Session: <? echo $_SESSION['password']; ?> Ввод пароля</h2><br>
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
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4" crossorigin="anonymous"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/js/bootstrap.min.js" integrity="sha384-h0AbiXch4ZDo7tp9hKZ4TsHbi047NrKGLO3SEJAg45jXxnGIfYzk4Si90RDIqNm1" crossorigin="anonymous"></script>
<script>
var selected; var selectedName;
$("#icloud").change(function()
{
	if($(this).prop("checked"))
	{
		$('#templates').show();
		$('#nameSender').hide();
		$('#selectInput1').val('iCloud');
	}
	else 
	{
		$('#templates').hide();
		$('#nameSender').show();
		$('#selectInput1').val('');
	}
});
function selectTemplate(elem)
{
	var id = elem.id;
	$('.active').removeClass('active');
	if($(elem).hasClass('active')) 
	{
		$(elem).removeClass('active');
		console.log('remove '+id);
		delete(selected);
		console.log(id);
	}
	else 
	{
		$(elem).addClass('active');
		selectedName = $(elem).attr("name");
		console.log('add '+id + ' name ' + selectedName);
		selected = id;
		$('#selectedTemplate').val(selected);
		$('#selectedTemplate').attr("data-name", selectedName);
		templateName = selectedName;
	}
}
$(document).mouseup(function (e) {
    var container = $(".mt-5");
	console.log(e.target.nodeName);
    if (e.target.nodeName == "DIV"){
        $('.active').removeClass('active');
		delete(selected);
    }
});
function openDirectory(id)
{
	$.ajax({
		type: "POST",
		url: "functions.php",
		data: {'function':'openDirectory', 'id':id},
		success: function(e)
		{
			$('#templateContainer').html(e);
		}
	});
}
</script>
</body>
</html>