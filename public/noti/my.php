<?php 
	header('Content-type:text/html; charset=utf-8');
	ini_set('display_errors', 'Off');

	$title=$_GET["title"];
	$alert = $_GET["alert"];
	$type =$_GET["type"];
	
	$where = array(
			"deviceType" => "android"
			);
	$data = array(
			"title" => $title,
			"alert" => $alert,
			"type" => $type
			);

	$cURLHandler = curl_init();
	$url = "https://api.parse.com/1/push";
	$send_json = array(
		"where" => $where,
		"data" => $data
		);
	$a = json_encode($send_json);
	echo $a;
	if($cURLHandler) {
		curl_setopt($cURLHandler, CURLOPT_HTTPHEADER, array("X-Parse-Application-Id: j6DTfeUL6JvI9PunllRInpQbUg3dJLCVNTvaAOfY","X-Parse-REST-API-Key: scpgvOkSsPgF9cEHVH2U8IFYkF3maZV0cOfQmsu0","Content-Type: application/json")); 
		curl_setopt($cURLHandler, CURLOPT_BINARYTRANSFER, 1);
		curl_setopt($cURLHandler, CURLOPT_POST, 1);
		curl_setopt($cURLHandler, CURLOPT_PORT,443);
		curl_setopt($cURLHandler, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($cURLHandler, CURLOPT_POSTFIELDS, $a);
		curl_setopt($cURLHandler, CURLOPT_URL, $url);
		curl_exec($cURLHandler);
		curl_close($cURLHandler);
	}
	else {
		throw new RuntimeException("CURL Exception");
	}


?>