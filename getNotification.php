<?php 
@session_start();
//ob_start();
if(!isset($_SESSION['user_login']) and !isset($_COOKIE['cookie_login']))//session store admin name
{
    header("Location: index.php");//login in AdminLogin.php
}
require_once("includes/dbconnect.php");
require_once("includes/Services/Twilio.php");	

$response = array();
$response['status']="";
$response['cnt']=0;
$response['res_data']=array();
$response['res_time']=array();

$cnt = 0;
if (is_ajax())
{
	
	// get log
	$sql_sel = "select * from log_info where is_viewed = 0 and agent != '".$_SESSION['user_login']."' and assigned='".$_SESSION['user_login']."' order by auto_id desc";
	//$sql_sel = sprintf("select * from log_info where is_viewed = '%d' order by auto_id desc",0);
	$resb = mysqli_query($con, $buyer) or die(mysqli_error($con) . "11");	
	if ($recb = mysqli_fetch_assoc($resb))
	{
		$response['res_data'][$cnt] = $res_rec['log_data'];
		$response['res_time'][$cnt] = date('M d, Y, h:i:s T O (e)');
		$response['res_url'][$cnt] = $res_rec['url'];
		if (isset($res_rec['fa_icon']) and strlen($res_rec['fa_icon'])>0)
			$response['res_fa_icon'][$cnt] = $res_rec['fa_icon'];
		else
			$response['res_fa_icon'][$cnt] = '';
		$cnt++;
	}
	
	
	$response['cnt']=$cnt;	
	
	$response['status'] = 'Success';		
}

echo json_encode($response);


//Function to check if the request is an AJAX request
function is_ajax() {
  return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
}

?>