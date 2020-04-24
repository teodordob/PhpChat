<?php

//creating the database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bunqchat";

$db = new mysqli($servername, $username, $password, $dbname);
if($db->connect_error){
	die("Connection lost: " . $db->connect_error);
}

$action = $_GET['action'] ?? '';
$result = array();

//sending the messages
if($action == 'sendMessage'){
	$message = isset($_POST['message']) ? $_POST['message'] : null;
	$from = isset($_POST['from']) ? $_POST['from'] : null;

	if(!empty($message) && !empty($from)){
		//sanitize input
		$Msg = filter_var($message, FILTER_SANITIZE_STRING);
		$Fr = filter_var($from, FILTER_SANITIZE_STRING);

		//prepare statements
		$stmt = $db->prepare("INSERT INTO `chat` (`message`, `from`) VALUES (?,?)");
		$stmt->bind_param("ss", $Msg, $Fr);
		
		
		$stmt->execute();
		$stmt->close();
		}
}

//getting the messages
else if($action == 'getMessage'){

	$start = isset($_GET['start']) ? intval($_GET['start']) : 0;
	$items = $db->query("SELECT * FROM `chat` WHERE `id` >" . $start);
	while ($row = $items->fetch_assoc()) {
		$result['items'][] = $row;

}

	header ('Access-Control-Allow-Origin: *');
	header('Content-Type: application/json');

	echo json_encode($result);
}
	
$db->close();




