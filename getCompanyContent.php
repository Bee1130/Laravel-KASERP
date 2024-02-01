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
 		
 		$buyer = "select * from companies_info where auto_id=".$auto_id;
		$resb = mysqli_query($con, $buyer) or die(mysqli_error($con) . "11");	
		if ($recb = mysqli_fetch_assoc($resb))
		{
			if (strcasecmp($type,'Staff')==0)
	 		{				
				$staff_titles = explode(';',$recb['staff_title']);
				$staff_names = explode(';',$recb['staff_name']);
				$staff_positions = explode(';',$recb['staff_position']);
				$staff_emails = explode(';',$recb['staff_email']);
				$staff_phones = explode(';',$recb['staff_phone']);
				$staff_exts = explode(';',$recb['staff_ext']);
				$staff_vm_greetings = explode(';',$recb['staff_vm_greeting']);
				$staff_email_signs = explode(';',$recb['staff_email_sign']);
				$staff_users = explode(';',$recb['staff_user']);
				
		    	foreach ($staff_titles as $i => $staff_title)
	            {	        
	            	                             	
	            	$staff_name = $staff_names[$i];
	            	$staff_position = $staff_positions[$i];
	            	$staff_email = $staff_emails[$i];
	            	$staff_phone = $staff_phones[$i];
	            	$staff_ext = $staff_exts[$i];
	            	$staff_vm_greeting = $staff_vm_greetings[$i];
	            	$staff_email_sign = $staff_email_signs[$i];
	            	$staff_user = $staff_users[$i];
		        	
		        	if ($i == $value_ind)
		        	{
		        		$response['staff_new_title'] = $staff_title;
						$response['staff_new_name'] = $staff_name;
						$response['staff_new_position'] = $staff_position;
						$response['staff_new_email'] = $staff_email;
						$response['staff_new_phone'] = my_phone_format3($staff_phone);
						$response['staff_new_ext'] = $staff_ext;
						$response['staff_new_vm_greeting'] = $staff_vm_greeting;
						$response['staff_new_email_sign'] = $staff_email_sign;
						$response['staff_new_user'] = $staff_user;
						break;
					}
		    	}
			}else if (strcasecmp($type,'Note')==0)
	 		{				
	 			$comments_ary = explode(';',$recb['note_comment']);
		    	foreach ($comments_ary as $i => $comment)
		        {	
		        	if ($i == $value_ind)
		        	{
						$response['note_new_comment'] = $comment;
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

function my_phone_format3($phone)
{
	if (isset($phone))
		$phone = preg_replace("/[^0-9]*/s", "",$phone);
	
	if (strlen($phone) == 10)
	{
		$areaCode = substr($phone, 0, 3);
	    $nextThree = substr($phone, 3, 3);
	    $lastFour = substr($phone, 6, 4);
		$res = '+1 ('.$areaCode.') '.$nextThree.'-'.$lastFour;
	}else if (strlen($phone) > 10)
	{
		$phone = substr($phone,-10);
		$areaCode = substr($phone, 0, 3);
	    $nextThree = substr($phone, 3, 3);
	    $lastFour = substr($phone, 6, 4);
		$res = '+1 ('.$areaCode.') '.$nextThree.'-'.$lastFour;
	}else
		$res = $phone;
	return $res;
}
?>