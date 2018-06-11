<?
	// require_once("sms/Logger.php");
	include("DBConnect.php");

	// $logger = new Logger("routeReport.txt");

	$status = $_POST['sStatus'];
	$id = $_POST['sMessageId'];
	$time = strtotime($_POST['dtDone']);
	$sender = $_POST['sSender'];
	$number = $_POST['sMobileNo'];
	mysql_query("UPDATE `log_full` SET `dlr_status` = '$status', `dlr_time` = '$time' WHERE `dlr_id` = '$id'");
	mysql_query("UPDATE `log` SET `dlr_status` = '$status' WHERE `dlr_id` = '$id'");
/* 	$str=@file_get_contents('php://input');
	$logger->log($str);

	$logger->dispose(); */
?>