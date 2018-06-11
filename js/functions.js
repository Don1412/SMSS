$(window).load(function () {
$("#zv-load").fadeOut("slow");
});
function notify(typeAlert, textAlert){
  var html = '<div id = "alertBlock" class="alert alert-'+typeAlert+' fade show"><button type="button" class="close" data-dismiss="alert" aria-label="Close">×</button><strong>Результат: </strong>' + textAlert + '</div>';
  $('#notifications-top-left').html(html);
  $('#notifications-top-left').attr("x", screen.width);
  $('#notifications-top-left').attr("y", screen.height);
}

function CheckRequired(event) {
    var $form = $(this);

    if ($form.find('.required').filter(function(){ return this.value === '' }).length > 0) {
        event.preventDefault();
        alert("Одно из полей не заполненно");
        return false;
    }
	return true;
}

function setNewModalText(text){
  $('.modal-text').html(text);
}

function showResponse(responseText, statusText, xhr, $form){
  var notificationClass = "";
  if (responseText.search(/К отправке/i) != -1) notificationClass = "success";
  else if (responseText.search(/ошибка/i) != -1) notificationClass = "danger";
  else notificationClass = "warning";

  notify(notificationClass, responseText);
}

function timeDisp()
{
	$.ajax(
	{
		type: "POST",
		url: "functions.php",
		data: {'function':'time'},
		success: function(e)
		{
			$('#timeDisp').html(e);
		}
	});
	t=setTimeout('timeDisp()', 1000);
}

/* window.onload = function(){
    (function(){
        var date = new Date();
        //var time = date.getHours()+':'+date.getMinutes()+':'+date.getSeconds();
        document.getElementsById('timeDisp').html = "loool";
        window.setTimeout(arguments.callee, 1000);
    })();
}; */
var maxCount = 0;
function SMSLength(value)
{
	if(value == 0) { maxCount = 160; $('#count').show(); }
	else if(value == 1) { maxCount = 70; $('#count').show(); }
	else if(value == 255) $('#count').hide(); 
	$('#count').html(maxCount);
}

var countNumbers = 0; var nameSender = ''; var fileName = '';
var openFile = function(event) 
{
	var input = event.target;
	var file = input.files[0];
	var reader = new FileReader;
	var row;

	reader.onloadend = function(evt) 
	{
		//console.log(reader.result);
		var txt = reader.result.split("\n");
		countNumbers = txt.length;
		var row = txt[0].split('|');
		nameSender = row[4];
		fileName = $('#user-file').val();
/* 		if(reader.result.match(/\n/g))
		{
			var data = reader.result.match(/\n/g);
			countNumbers = data.length;
			row = data[0].match(/|/g);
		}
		else
		{
			row = reader.result.split('|');
		} */
		//nameSender = row[4];
		console.log("data 0: "+txt[0]+"\ncount: "+countNumbers+"\nnameSender: "+nameSender+"\nFileName: "+fileName);
	};

	reader.readAsText(file);
};

var submitFlag = false;
$( function() {
            $('#newTaskData').submit(function() {
				var formData = new FormData(this);
				var perSend = false; var planSend = false;
				var typeSend = ""; var sendHoursPer, sendMinutesPer, sendAmount; var dateSend, sendHours, sendMinutes;
				var txtName = $('#selectInput1').val(); var txtMessageType = $('#cmbMessageType').val();
				if(txtName == '' || txtMessageType == -1) return false;
				if($("[name=PerSend]").prop("checked"))
				{
					perSend = true;
					sendHoursPer = $('[name=periodicHours]').val(); sendMinutesPer = $('[name=periodicMinutes]').val(); sendAmount = $('[name=amount]').val();
					typeSend += " Периодическая, "+sendAmount+" смс";
					if(sendHoursPer != 0) typeSend += ", каждые "+sendHoursPer+" часов";
					if(sendMinutesPer != 0) typeSend += ", каждые "+sendMinutesPer+" минут";
					if(sendHoursPer == '' || sendMinutesPer == '')
					{
						alert("Не заполненно одно из полей периодической отправки!");
						return false;
					}
				}
				if($("[name=PlanSend]").prop("checked")) 
				{
					planSend = true;
					dateSend = $('[name=date]').val(); sendHours = $('[name=hour]').val(); sendMinutes = $('[name=minute]').val();
					typeSend += " Запланированная, "+dateSend+" "+sendHours+":"+sendMinutes;
					if(dateSend == '' || sendHours == '' || sendMinutes == '')
					{
						alert("Не заполненно одно из полей запланированной отправки!");
						return false;
					}
				}
				if(!$("[name=PerSend]").prop("checked") && !$("[name=PlanSend]").prop("checked")) typeSend = "Моментальная";
				var channel = $("#cmbService option:selected").text();
				var channelNum = $("#cmbService option:selected").val(); var templateName = $('#selectedTemplate').attr("data-name");
				if(templateName == "selectedTemplate") templateName = "Не выбран";
				if(confirm(
					"Имя отправителя: "+nameSender+
					"\nШаблон: "+templateName+
					"\nФайл: "+fileName+
					"\nТип отправки: "+typeSend+
					"\nКодировка: "+$('#cmbMessageType option:selected').text()+
					"\nЧасовой пояс: "+$('#Timezone option:selected').text()+
					"\nКол-во номеров: "+countNumbers+
					"\nКанал: "+channel))
				{
					$.ajax({
						type: "POST",
						url: "manager.php",
						data: formData,
						async: false,
						success: function(e)
						{
							var notificationClass = "";
							console.log(e);
							if (e.search(/К отправке/i) != -1) notificationClass = "success";
							else if (e.search(/ошибка/i) != -1) notificationClass = "danger";
							else notificationClass = "warning";
							notify(notificationClass, e);
							$('#newTaskData')[0].reset();
							setTimeout(function(){$("#alertBlock").hide();}, 800);
						},
						cache: false,
						contentType: false,
						processData: false
					});
				}
				return false;
            });
			$('#newTaskDataEdit').submit(function() {
				var key = $('[name=key]').val();
				alert(key);
				clicked(key);
				return false;
            });
        });

var countSMS;
function clicked(key) 
{
    var perSend = false; var planSend = false;
	var typeSend = ""; var sendHoursPer, sendMinutesPer, sendAmount; var dateSend, sendHours, sendMinutes;
	var txtName = $('#selectInput1').val();
	if(txtName == '') return false;
	if($("[name=PerSend]").prop("checked"))
	{
		perSend = true;
		sendHoursPer = $('[name=periodicHours]').val(); sendMinutesPer = $('[name=periodicMinutes]').val(); sendAmount = $('[name=amount]').val();
		typeSend += " Периодическая, "+sendAmount+" смс";
		if(sendHoursPer != 0) typeSend += ", каждые "+sendHoursPer+" часов";
		if(sendMinutesPer != 0) typeSend += ", каждые "+sendMinutesPer+" минут";
		if(sendHoursPer == '' || sendMinutesPer == '')
		{
			alert("Не заполненно одно из полей периодической отправки!");
			return false;
		}
	}
	if($("[name=PlanSend]").prop("checked")) 
	{
		planSend = true;
		dateSend = $('[name=date]').val(); sendHours = $('[name=hour]').val(); sendMinutes = $('[name=minute]').val();
		typeSend += " Запланированная, "+dateSend+" "+sendHours+":"+sendMinutes;
		if(dateSend == '' || sendHours == '' || sendMinutes == '')
		{
			alert("Не заполненно одно из полей запланированной отправки!");
			return false;
		}
	}
	if(!$("[name=PerSend]").prop("checked") && !$("[name=PlanSend]").prop("checked")) typeSend = "Моментальная";
	var channel = $("#cmbService option:selected").text();
	var channelNum = $("#cmbService option:selected").val();
	if(confirm("Имя отправителя: "+$('#selectInput1').val()+"\nКанал: "+channel))
	{
		if(key != 0)
		{
			$.ajax({
				type: "POST",
				url: "functions.php",
				data: {'function':'delete', 'key':key},
				success: function(e)
				{
				}
			});
		}
		$.ajax({
			type: "POST",
			url: "manager.php",
			data: formData,
			success: function(e)
			{
				var notificationClass = "";
				if (e.search(/К отправке/i) != -1) notificationClass = "success";
				else if (e.search(/ошибка/i) != -1) notificationClass = "danger";
				else notificationClass = "warning";
				notify(notificationClass, e);
				setTimeout(function(){location.reload();}, 800);
			}
		});
	}
    else
	{
		return false;
	}
}

var flag, flag2;
$(document).ready(function()
{
	$('#SelectDropdown1').hide();
	$('#selectInput1').click(function() 
	{
		$('#SelectDropdown1').show();
		$('#selectInput1').focus();
	});
	$('ul.drop1 li').mouseover(function(){flag = true});
	$('ul.drop1 li').mouseout(function(){flag = false});

	$("#selectInput1").focusout(function()
	{
		if(flag) 
		{
			$('ul.drop1 li').click(function() 
			{
				$('#selectInput1').val($(this).attr("id"));
				$('#SelectDropdown1').hide();
			});
		}			
		else $('#SelectDropdown1').hide();
		
	});
	$('ul.drop1 li button').click(function()
	{
		$('#selectInput1').focus();
		if(confirm("Вы действительно хотите удалить шаблон " + $(this).attr('id') + "?"))
		{
			$('#selectInput1').val('');
			$('li#'+$(this).attr('id')).remove();
			$.ajax(
			{
				type: "POST",
				url: "functions.php",
				data: {'function':'template', 'name':$(this).attr('id'), 'action':3},
				success: function(e)
				{
				}
			});
			return false;
		}
	});
	$('#selectInput1').keyup(function()
	{
		$('li').each(function(i,elem) 
		{
			if ($(this).text() == $('#selectInput1').val() || $('#selectInput1').val() == "")
			{
				$('#addTplName').hide();
				return false;
			} 
			else 
			{
				$('#addTplName').show();
			}
		});
	});
	$('#addTplName').click(function()
	{
		$('#addTplName').hide();
		var name = $('#selectInput1').val();
		$.ajax({
			type: "POST",
			url: "functions.php",
			data: {'function':'template', 'text':0, 'type':1, 'name':name, 'action':1},
			success: function(e)
			{
			}
		});
		var addOption = $('<li id="'+name+'">'+name+'<button type="button" id="'+name+'" style="position: absolute; right:5px;"><img src="img/mysor.png" width="10"></button></li>');
		$('ul.drop1').append(addOption);
	});
	
	$('#SelectDropdown2').hide();
	$('#selectInput2').click(function() 
	{
		$('#SelectDropdown2').show();
		$('#selectInput2').focus();
	});
	$('ul.drop2 li').mouseover(function(){flag2 = true});
	$('ul.drop2 li').mouseout(function(){flag2 = false});

	$("#selectInput2").focusout(function()
	{
		if(flag2) 
		{
			$('ul.drop2 li').click(function() 
			{
				$('#selectInput2').val($(this).attr("id"));
				var name = $(this).attr("id");
				$.ajax(
				{
					type: "POST",
					url: "functions.php",
					data: {'function':'template', 'name':name, 'action':2},
					success: function(e)
					{
						$('#smsarea').val(e);
					}
				});
				$('#SelectDropdown2').hide();
			});
		}			
		else $('#SelectDropdown2').hide();
		
	});
	$('ul.drop2 li button').click(function()
	{
		$('#selectInput2').focus();
		if(confirm("Вы действительно хотите удалить шаблон " + $(this).attr('id') + "?"))
		{
			$('#selectInput2').val('');
			$('li#'+$(this).attr('id')).remove();
			$.ajax(
			{
				type: "POST",
				url: "functions.php",
				data: {'function':'template', 'name':$(this).attr('id'), 'action':3},
				success: function(e)
				{
				}
			});
			return false;
		}
	});
	$('#selectInput2').keyup(function()
	{
		$('li').each(function(i,elem) 
		{
			if ($(this).text() == $('#selectInput2').val() || $('#selectInput2').val() == "")
			{
				$('#addTplMess').hide();
				return false;
			} 
			else 
			{
				$('#addTplMess').show();
			}
		});
	});
	$('#addTplMess').click(function()
	{
		$('#addTplMess').hide();
		var txt = $('#smsarea').val();
		var name = $('#selectInput2').val();
		$.ajax({
			type: "POST",
			url: "functions.php",
			data: {'function':'template', 'text':txt, 'type':2, 'name':name, 'action':1},
			success: function(e)
			{
			}
		});
		var addOption = $('<li id="'+name+'">'+name+'<button type="button" id="'+name+'" style="position: absolute; right:5px;"><img src="img/mysor.png" width="10"></button></li>');
		$('ul.drop2').append(addOption);
	});
		
	$("#tplMess").change(function(){

	alert('Selected value: ' + $(this).val());
	});

	$('#logsButton').click(setNewModalText("Text"));
  
	var countNumber = 0;
	//$("#numberArea").onchange(function()
	//var inputFile = $('#user-file');
});