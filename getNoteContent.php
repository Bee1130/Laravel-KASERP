<?php 
@session_start();
//ob_start();

if(!isset($_SESSION['user_login']) and !isset($_COOKIE['cookie_login']))//session store admin name
{
    header("Location: index.php");//login in AdminLogin.php
}
require_once("includes/dbconnect.php");



$cur_login_time = $_SESSION['last_login'];
$response = array();
$response['content'] = '';
$response['status'] = 'Success';
$arizona_off = -7;
if (is_ajax()) 
{
	$auto_id = trim($_POST["auto_id"]);
	$buyer = sprintf("select * from notes_info where auto_id='%d'",$auto_id);
	$resb = mysqli_query($con, $buyer) or die(mysqli_error($con) . "11");	
	if ($recb = mysqli_fetch_assoc($resb))
	{	
		$response['note_new_comment'] = $recb['comment'];
	}
	
}


echo json_encode($response);


//Function to check if the request is an AJAX request
function is_ajax() {
  return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
}
?>