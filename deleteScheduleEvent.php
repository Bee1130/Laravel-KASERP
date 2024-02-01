<?php 
@session_start();	
if (! isset($_COOKIE['cookie_login']) and !isset($_SESSION['user_login'])) {//session store admin name
    header("Location: index.php"); //login in AdminLogin.php
}
require_once("includes/dbconnect.php");

$response = array();
$response['content'] = '';
$response['status'] = 'Success';

if (is_ajax()) 
{
	$event_id = 0;
	if (isset($_POST['event_id']))
	{
		$event_id = trim($_POST['event_id']);	
		$sql_del= sprintf("delete from reminders_info where auto_id=%d",$event_id);
		$sql_del_res=mysqli_query($con, $sql_del) or die(mysqli_error($con)."11");	
	}
	
	saveLog('','Event id',$event_id,'removed');
		
}


//echo $res;
echo json_encode($response);

//Function to check if the request is an AJAX request
function is_ajax() {
  return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
}
?>
