<?php 
function notiDbConnect(){
	$DBServer = '150.107.31.15';
	$DBUser   = 'root';
	$DBPass   = 'Nchl33&rVx';
	$DBName   = 'vmmax_chat';
		//DB Connect
	$db = new mysqli($DBServer, $DBUser, $DBPass, $DBName);
	$db->set_charset("utf8");
	return $db;
}
if(isset($_REQUEST["a"])) {
	$action = $_REQUEST["a"];
	switch($action) {
		case "list":
			// http://armymax.com/api/noti/noti.php?a=list&user_id=22
		$userId = $_REQUEST["user_id"];
		if(isset($_REQUEST["page"]))
			$page = $_REQUEST["page"];
		else
			$page = 1; 
		echo getNotiList($userId,$page);
		break;
		case "badge":
		$userId = $_REQUEST["user_id"];
		$a = array("noti_unread"=>getCountNotiListInt($userId),"chat_unread"=>getCountChatListInt($userId),"follow_unread"=>getCountFollowListInt($userId));
		$b = array("user_id"=>$userId,"count"=>$a);
		echo json_encode($b);
		break;
		case "notiBadge":
			// http://armymax.com/api/noti/noti.php?a=list&user_id=22
		$userId = $_REQUEST["user_id"];
		echo getCountNotiList($userId);
		break;
		case "chatBadge":
			// http://armymax.com/api/noti/noti.php?a=list&user_id=22
		$userId = $_REQUEST["user_id"];
		echo getCountChatList($userId);
		break;

		case "insert":
			// http://armymax.com/api/noti/noti.php?a=insert&f=21&t=22&msg=asdf&type=100
			//$id = $_REQUEST["id"];
		$from = $_REQUEST["f"];

		if(isset($_REQUEST["n"]))
			$fname = $_REQUEST["n"];
		else
			$fname = "";

		$to = $_REQUEST["t"];

		$msg = $_REQUEST["msg"];
		$type = $_REQUEST["type"];
		if(isset($_REQUEST["post_id"]))
		$post_id = $_REQUEST["post_id"];
		else
			$post_id = 0;
		if(isset($_REQUEST["extra"]))
			$extra = $_REQUEST["extra"];
		else 
			$extra ="";
		echo insertNoti($from,$fname,$to,$msg,$type,$post_id,$extra);
		break;
		case "read":
			// http://armymax.com/api/noti/noti.php?a=list&user_id=22
		$notiId = $_REQUEST["noti_id"];
		echo read($notiId);
		break;
		case "readAll":
		$userId = $_REQUEST["user_id"];
		if(isset($_REQUEST["timestamp"]))
			$timestamp = $_REQUEST["timestamp"];
		else
			$timestamp = now();
		echo readAll($userId,$timestamp);
		break;
		case "delete":
			// http://armymax.com/api/noti/noti.php?a=list&user_id=22
		$notiId = $_REQUEST["noti_id"];
		echo deleteNoti($notiId);
		break;
	}
}

function read($notiId) {
	$db = notiDbConnect();
	$sql = "UPDATE message SET readed = 1 WHERE id = {$notiId}";
	$res = $db->query($sql);

	if($res)
		return json_encode(array('status' => "1"));
	else
		return json_encode(array('status' => "0"));
}

function readAll($userId,$timestamp) {
	$db = notiDbConnect();
	$sql = "UPDATE message SET readed = 1 WHERE time_received < {$timestamp} AND to_id = {$userId}";
	$res = $db->query($sql);

	if($res)
		return json_encode(array('status' => "1"));
	else
		return json_encode(array('status' => "0","sql" => $sql));
}

function deleteNoti($notiId) {
	$db = notiDbConnect();
	$sql = "DELETE FROM message WHERE id = {$notiId}";
	$res = $db->query($sql);

	if($res)
		return json_encode(array('status' => "1"));
	else
		return json_encode(array('status' => "0"));

}


function c($text) {
    $current_encoding = mb_detect_encoding($text, 'auto');
    $text = iconv($current_encoding, 'UTF-8', $text);
    return $text;
}


function getNotiList($id,$page = 1,$limit = 21) {
	$db = notiDbConnect();

	// $page = 1; $from = 0;
	// $page = 2; $from = 20;
	// $page = 3; $from = 40;
	// $page = 4; $from = 60;
	$from = ($page - 1) * ($limit);
	$sql = "SELECT * FROM message WHERE to_id = {$id} ORDER BY time_received DESC LIMIT {$from},{$limit}";
	$db->query("SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'");
	
	$sql_total_unread = "SELECT count(*) as total FROM message WHERE to_id = {$id} AND readed = 0 ORDER BY time_received DESC";
	$sql_total_read = "SELECT count(*) as total FROM message WHERE to_id = {$id} AND readed = 1 ORDER BY time_received DESC";
	
	$res2 = $db->query($sql_total_unread);
	$data2 = $res2->fetch_assoc();

	$res3 = $db->query($sql_total_read);
	$data3 = $res3->fetch_assoc();
	
	//echo $sql;
	$resp = $db->query($sql);
	$data = $resp->fetch_assoc();
	$i = 0;
	$d = array();

	while($data = $resp->fetch_assoc()){
		$d[$i] = $data;
		$d[$i]['ago'] = nicetime((int)$d[$i]['time_received']);
		$i++;
	}

	$a = array("sql"=>$sql,
		"status"=>"1",
		"page"=>$page,
		"count" =>sizeOf($d),
		"total_unread"=>(int)$data2['total'],
		//"total_read"=>(int)$data3['total'],
		"data" => $d);

	return json_encode($a);
}

function getCountNotiList($id) {
$db = notiDbConnect();
	$sql = "SELECT * FROM message WHERE to_id = {$id} AND readed = 0 ORDER BY time_received DESC";
	$db->query("SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'");
	
	//echo $sql;
	$resp = $db->query($sql);
	$data = $resp->fetch_assoc();
	$i = 0;
	$d = array();

	while($data = $resp->fetch_assoc()){
		$d[$i] = $data;
		$i++;
	}

	return json_encode(array("status"=>"1","count" =>sizeOf($d)));
}

function getCountNotiListInt($id) {
$db = notiDbConnect();
	$sql = "SELECT * FROM message WHERE to_id = {$id} AND readed = 0 ORDER BY time_received DESC";
	$db->query("SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'");
	
	//echo $sql;
	$resp = $db->query($sql);
	$data = $resp->fetch_assoc();
	$i = 0;
	$d = array();

	while($data = $resp->fetch_assoc()){
		$d[$i] = $data;
		$i++;
	}

	return sizeOf($d);
}

function getCountChatList($id) {
	
	$db = notiDbConnect();
	$sql = "SELECT * FROM message WHERE to_id = {$id} AND (type = 500 OR type = 501 OR type = 502 OR type = 503) AND readed = 0 ORDER BY time_received DESC";
	$db->query("SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'");
	
	//echo $sql;
	$resp = $db->query($sql);
	//$data = $resp->fetch_assoc();
	$i = 0;
	$d = array();

	while($data = $resp->fetch_assoc()){
		$d[$i] = $data;
		$i++;
	}



	return json_encode(array("status"=>"1","count" =>sizeOf($d)));
}

function getCountChatListInt($id) {
	
	$db = notiDbConnect();
	$sql = "SELECT * FROM message WHERE to_id = {$id} AND (type = 500 OR type = 501 OR type = 502 OR type = 503) AND readed = 0 ORDER BY time_received DESC";
	$db->query("SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'");
	
	//echo $sql;
	$resp = $db->query($sql);
	//$data = $resp->fetch_assoc();
	$i = 0;
	$d = array();

	while($data = $resp->fetch_assoc()){
		$d[$i] = $data;
		$i++;
	}

	return sizeOf($d);
}

function getCountFollowListInt($id) {
	
	$db = notiDbConnect();
	$sql = "SELECT * FROM message WHERE to_id = {$id} AND type = 300 AND readed = 0 ORDER BY time_received DESC";
	$db->query("SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'");
	
	//echo $sql;
	$resp = $db->query($sql);
	//$data = $resp->fetch_assoc();
	$i = 0;
	$d = array();

	while($data = $resp->fetch_assoc()){
		$d[$i] = $data;
		$i++;
	}

	return sizeOf($d);
}

function insertNoti($from,$fname,$to,$msg,$type,$post_id=0,$extra=""){
	$msg = c($msg);
	$db = notiDbConnect();
	$post_id = intval($post_id);
	$sql = "INSERT INTO message (id,from_id,from_name,to_id,msg,post_id,extra,type,time_received) VALUES (NULL,$from,'$fname',$to,'$msg',$post_id,'$extra',$type,'".time()."')";
	//echo $sql;
	$resp = $db->query($sql);
	if($resp) {
		$return["status"] = "1";
		$return["msg"] = "Success";

	} else {
		$return["status"] = "0";
		$return["msg"] = "Insert Fail";
	}
	return json_encode($return);
}

function getUnreadNoti($user_id) {
	$db = notiDbConnect();

	$sql = "SELECT COUNT(*) as count FROM message WHERE to_id = $user_id AND readed = 0";
	//echo $sql;
	$resp = $db->query($sql);
	$row = $resp->fetch_assoc();

	$count = $row['count'];

	return $count;
}

function getUnreadNotiSocial($user_id) {
	$db = notiDbConnect();

	$sql = "SELECT COUNT(*) as count FROM message WHERE to_id = $user_id AND readed = 0 AND (TYPE = 100 OR TYPE = 101 OR TYPE = 200)";
	//echo $sql;
	$resp = $db->query($sql);
	$row = $resp->fetch_assoc();

	$count = $row['count'];

	return $count;
}

function getUnreadNotiChat($user_id) {
	$db = notiDbConnect();

	$sql = "SELECT COUNT(*) as count FROM message WHERE to_id = $user_id AND readed = 0 AND (TYPE = 500 OR TYPE = 501 OR TYPE = 502 OR TYPE = 503 OR TYPE = 504 OR TYPE = 505 OR TYPE = 506 OR TYPE = 507 OR TYPE = 508 OR TYPE = 509 OR TYPE = 510 OR TYPE = 511)";
	//echo $sql;
	$resp = $db->query($sql);
	$row = $resp->fetch_assoc();

	$count = $row['count'];

	return $count;
}
function getUnreadNotiFollow($user_id) {
	$db = notiDbConnect();

	$sql = "SELECT COUNT(*) as count FROM message WHERE to_id = $user_id AND readed = 0 AND TYPE = 300";
	//echo $sql;
	$resp = $db->query($sql);
	$row = $resp->fetch_assoc();

	$count = $row['count'];

	return $count;
}

function nicetime($date)
{
    if(empty($date)) {
        return "No date provided";
    }
    
    $periods         = array("second", "minute", "hour", "day", "week", "month", "year", "decade");
    $lengths         = array("60","60","24","7","4.35","12","10");
    
    $now             = time();
    $unix_date         = $date;
    
       // check validity of date
    if(empty($unix_date)) {    
        return "Bad date";
    }

    // is it future date or past date
    if($now > $unix_date) {    
        $difference     = $now - $unix_date;
        $tense         = "ago";
        
    } else {
        $difference     = $unix_date - $now;
        $tense         = "from now";
    }
    
    for($j = 0; $difference >= $lengths[$j] && $j < count($lengths)-1; $j++) {
        $difference /= $lengths[$j];
    }
    
    $difference = round($difference);
    
    if($difference != 1) {
        $periods[$j].= "s";
    }
    
    return "$difference $periods[$j] {$tense}";
}





?>