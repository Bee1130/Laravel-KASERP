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
	
	if (isset($_POST["type"]) && !empty($_POST["type"])) 
 	{
 		// send sms
 		$type = trim($_POST["type"]);
 		$auto_id = trim($_POST["auto_id"]);
 		$value_ind = trim($_POST["value_ind"]);
 		
 		$buyer = "select * from contacts_info where auto_id=".$auto_id;
		$resb = mysqli_query($con, $buyer) or die(mysqli_error($con) . "11");	
		if ($recb = mysqli_fetch_assoc($resb))
		{
			if (strcasecmp($type,'File')==0)
	 		{				
		 		$filenames_ary = explode(';',$recb['fil_filename']);
				$filepaths_ary = explode(';',$recb['fil_filepath']);
				$dates_ary = explode(';',$recb['fil_date']);
				
		    	foreach ($filenames_ary as $i => $filename)
		        {	
		        	$filepath = $filepaths_ary[$i];  
		        	$date = $dates_ary[$i];  
		        	if ($i == $value_ind)
		        	{
						$response['file_new_filepath'] = $filepath;						
						$response['file_new_filename'] = $filename;																		
						break;
					}
	        	}
			}
		}
	}
}


echo json_encode($response);


//Function to check if the request is an AJAX request
function is_ajax() {
  return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
}
?>