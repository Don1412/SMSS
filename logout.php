<?php
/* $dom = new domDocument;
$dom->loadXML('<?xml version="1.0" encoding="utf-8"?><request><state id_sms="812719879" time="2017-04-09 04:23:14">EXPIRED</state></request>
			   <?xml version="1.0" encoding="utf-8"?><request><state id_sms="812723800" time="2017-04-09 04:23:14">EXPIRED</state></request>');
if (!$dom) {
   echo 'Error while parsing the document';
   exit;
}

$s = simplexml_import_dom($dom);

echo $s->state; // Great American Novel */
session_start();
$_SESSION['password'] = "empty";
echo "<script>location.href=\"/\";</script>";
?>