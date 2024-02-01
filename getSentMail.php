<?php 
@session_start();
//ob_start();

if(!isset($_SESSION['user_login']) and !isset($_COOKIE['cookie_login']))//session store admin name
{
    header("Location: index.php");//login in AdminLogin.php
}
require_once("includes/dbconnect.php");
	
$response = array();
$response['status'] = "fail";
if (is_ajax())
{
	if (isset($_POST['auto_no']))
	{
		$sql_sel = sprintf("select mail_subject,mail_body from mail_log_info where auto_no ='%d'",$_POST['auto_no']);
		$resb = mysqli_query($con, $buyer) or die(mysqli_error($con) . "11");	
	if ($recb = mysqli_fetch_assoc($resb))
		{
			
			$response['eml_subject'] = $sql_rec['mail_subject'];
			$response['eml_content'] = $sql_rec['mail_body'];
			$response['status'] = "success";
		}			
	}
}
echo json_encode($response);

//Function to check if the request is an AJAX request
function is_ajax() {
  return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
}
?>