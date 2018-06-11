<?
header("Content-Type: text/html; charset=utf-8");
require_once("sms/Functions.php");
include("DBConnect.php");
session_start();
if(empty($_GET['dir'])) $directoryID = 0;
else $directoryID = $_GET['dir'];
if(empty($_POST))
{
?>

<html>
<head>
<link rel="stylesheet" type="text/css" href="css/style.css">
<link rel="stylesheet" type="text/css" href="css/theme.blue.css">
<link rel="stylesheet" type="text/css" href="css/jquery.formstyler.css">
<script src = "js/jquery-1.12.3.min.js"></script>
<script src = "js/jquery.form.min.js"></script>
<script src = "js/date.js"></script>

<meta charset="utf-8">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" type="text/css">
  <link rel="stylesheet" href="css/bootstrap-4.0.0-beta.1.css" type="text/css"> 
<style>
.mb-3:hover
{
	background: #b4b4b4;
}
.active
{
	background: rgba(66,66,66, 0.6);
}
</style>
<title>Добавление Шаблона iCloud</title>
</head>
<body>
<?
if($_SESSION['password'] == "19733791Zx")
{
?>
	<button id = "SMSSender" class="myButton" onclick="location.href='<?=getUrl();?>/'">Расширение</button>
	<button id = "manager" class="myButton" onclick="location.href='<?=getUrl();?>/SMSManager.php'">Менеджер</button>
	<button onclick="location.href='<?=getUrl();?>/SMSReport.php'" id = "SMSReport" class="myButton" style="width: 70px">Отчёт</button><br><br>
	<div class="py-5 bg-light">
		<div class="container">
		<button class="btn btn-dark btn-sm" data-toggle="modal" data-target="#exampleModal2">Создать папку</button>
		<button class="btn btn-success btn-sm" data-toggle="modal" data-target="#exampleModal" onclick="addTemplate()">Создать шаблон</button>
		<button class="btn btn-danger btn-sm" onclick="deleteTemplates()">Удалить выбранные</button>
		  <div class="row text-center">
			<div class="col-md-12">
			  <div class="row text-left mt-5">
			  
			  
				<?
					if($directoryID == 0)
					{
						$res = mysql_query("SELECT * FROM `icloud_directory`");
						while($res2 = mysql_fetch_array($res))
						{
							?>
							<div class="col-md-1 my-3" id="directory_<?=$res2['id']?>" data-id="<?=$res2['id']?>" ondrop="drop(event, this)" ondragover="allowDrop(event)" onclick="selectDirectory(this)" ondblclick="location.href='<?=getUrl();?>/templates.php?dir=<?=$res2['id']?>'">
							  <div class="row mb-3">
								<div class="text-center col-1 col-md-12"><i class="d-block fa fa-3x fa-folder mx-auto"></i>
								  <p class="text-secondary"><?=$res2['name']?></p>
								</div>
							  </div>
							</div>
							<?
						}
					}
					else
					{
						
				?>
				<div class="col-md-12"> <i class="d-block fa mx-auto fa-arrow-left fa-lg" onclick="location.href='<?=getUrl();?>/templates.php'"></i> </div><br>
				<?
					}
					$res = mysql_query("SELECT * FROM `icloud_templates` WHERE `directory` = $directoryID");
					while($res2 = mysql_fetch_array($res))
					{
						?>
						<div class="col-md-1 my-3" id="<?=$res2['id']?>" draggable="true" ondragstart="drag(event)" ondblclick="editTemplate(this)">
						  <div class="row mb-3" id="<?=$res2['id']?>" onclick="selectTemplate(this)">
							<div class="text-center col-1 col-md-12"><i class="d-block fa fa-3x mx-auto fa-file-text-o"></i>
							  <p class="text-secondary"><small><?=$res2['name']?></small></p>
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
	</div>
	
	<div class="modal fade" aria-hidden="true" aria-labelledby="exampleModalLabel" id="exampleModal" role="dialog" tabindex="-1">
		<div class="modal-dialog" role="document">
			<form action = "ww" id = "addForm" role="form" method = "POST">
			  <div class="modal-content">
				<div class="modal-header">
				  <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
				</div>
				<div class="modal-body">
				  <div class="row" style="margin-top: 50px;">
					<div class="col-md-offset-2 col-md-12">
					  <h2>Добавление шаблона iCloud</h2>
					  <br>
					  <div class="form-group">
						<div class="form-group"> <label for="text">Текст</label> <textarea class="form-control" id="mText" placeholder="Текст шаблона" rows="20" name="text" required=""></textarea>
						  <p class="help-block">{imei} - IMEI номер устройства; {iphone} - модель устройства; {timeFind} - время и дата; {link} - ссылка; {mask} - маска; {custom1},{custom2},{custom3} - дополнительные параметры</p>
						</div>
						<input type="hidden" id="action">
						<input type="text" name="directory" value="<?=$directoryID?>" hidden>
						<label for="text">Название</label>
						<input id="mName" name="name" class="form-control"> </div>
					</div>
				  </div>
				</div>
				<div class="modal-footer">
				  <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
				  <input type="submit" id="save" class="btn btn-large btn-success" value="Добавить"> 
				</div>
			  </div>
			</form>
		</div>
  </div>
<div class="modal fade" aria-hidden="true" aria-labelledby="exampleModalLabel2" id="exampleModal2" role="dialog" tabindex="-1">
		<div class="modal-dialog" role="document">
			<form action = "ww" id = "addDir" role="form" method = "POST">
			  <div class="modal-content">
				<div class="modal-header">
				  <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
				</div>
				<div class="modal-body">
				  <div class="row" style="margin-top: 50px;">
					<div class="col-md-offset-2 col-md-12">
					  <h2>Добавление директории</h2>
					  <br>
					  <div class="form-group">
						<label for="text">Название</label>
						<input id="name" name="name" class="form-control"> 
						<input type="hidden" name="function" value="addDirectory" id="function">
					  </div>
					</div>
				  </div>
				</div>
				<div class="modal-footer">
				  <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
				  <input type="submit" id="save" class="btn btn-large btn-success" value="Добавить"> 
				</div>
			  </div>
			</form>
		</div>
  </div>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4" crossorigin="anonymous"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/js/bootstrap.min.js" integrity="sha384-h0AbiXch4ZDo7tp9hKZ4TsHbi047NrKGLO3SEJAg45jXxnGIfYzk4Si90RDIqNm1" crossorigin="anonymous"></script>
<script>
var arrTemplates = [];
var arrDirectory = [];
function allowDrop(ev) {
    ev.preventDefault();
}

function drag(ev) {
    ev.dataTransfer.setData("id", ev.target.id);
}

function drop(ev, currElem) {
    ev.preventDefault();
    var templateID = ev.dataTransfer.getData("id");
	var directoryID = $(currElem).data('id');
	var elem = document.getElementById(templateID);
	console.log("dir="+directoryID+" template="+templateID);
	elem.remove();
	addToDirectory(directoryID, templateID);
}

function addToDirectory(directoryID, templateID) {
	$.ajax({
		type: "POST",
		url: "functions.php",
		data: {'function':'addToDirTemplate_iCloud', 'directoryID':directoryID, 'templateID':templateID},
		success: function(e)
		{
			console.log(e);
		}
	});
}

function selectTemplate(elem)
{
	var id = elem.id;
	if($(elem).hasClass('active')) 
	{
		$(elem).removeClass('active');
		console.log('remove '+id);
		arrTemplates.splice(arrTemplates.indexOf(id), 1);
		console.log(arrTemplates);
	}
	else 
	{
		$(elem).addClass('active');
		console.log('add '+id);
		arrTemplates.push(id);
		console.log(arrTemplates);
	}
}

function selectDirectory(elem)
{
	var id = $(elem).data("id");
	if($(elem).hasClass('active')) 
	{
		$(elem).removeClass('active');
		console.log('remove '+id);
		arrDirectory.splice(arrDirectory.indexOf(id), 1);
		console.log(arrDirectory);
	}
	else 
	{
		$(elem).addClass('active');
		console.log('add '+id);
		arrDirectory.push(id);
		console.log(arrDirectory);
	}
}

function deleteTemplates()
{
	if(arrTemplates.length != 0)
	{
		$.ajax({
			type: "POST",
			url: "functions.php",
			data: {'function':'deleteTemplates_iCloud', 'templates':arrTemplates},
			success: function(e)
			{
				$('.active').remove();
			}
		});
	}
	if(arrDirectory.length != 0)
	{
		$.ajax({
			type: "POST",
			url: "functions.php",
			data: {'function':'deleteDirectory_iCloud', 'directory':arrDirectory},
			success: function(e)
			{
				$('.active').remove();
			}
		});
	}
}

$(document).mouseup(function (e) {
    var container = $(".mt-5");
    if (e.target.nodeName == "DIV"){
        $('.active').removeClass('active');
		arrTemplates = [];
		arrDirectory = [];
    }
});

function addTemplate()
{
	$('h2').text('Добавление шаблона');
	$('#action').val('add');
	$('#addForm')[0].reset();
	$('#save').val('Добавить');
}

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
		url: "templates.php",
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

$('#addDir').submit(function() {
	var formData = new FormData(this);
	$.ajax({
		type: "POST",
		url: "functions.php",
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

function editTemplate(elem)
{
	$('#exampleModal').modal('show');
	$('#save').val('Сохранить');
	$('h2').text('Редактирование шаблона');
	var id = elem.id;
	$('#action').val(id);
	$.ajax({
		type: "POST",
		url: "functions.php",
		data: {'function':'editTemplate_iCloud', 'id':id},
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
	$directoryID = $_POST['directory'];
	$text = mysql_real_escape_string($text);
	$query = "INSERT INTO `icloud_templates` (`name`, `text`, `time_format`, `directory`) VALUES ('$name', '$text', '$time', '$directoryID');"; mysql_query($query);
	echo "<script>location.href=\"/templates.php\";</script>";
}
?>