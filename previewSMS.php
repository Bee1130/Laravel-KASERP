<?php 
@session_start();
//ob_start();

if(!isset($_SESSION['user_login']) and !isset($_COOKIE['cookie_login']))//session store admin name
{
    header("Location: index.php");//login in AdminLogin.php
}
require_once("includes/dbconnect.php");

$phone = $_SESSION['tw_number'];


$cur_login_time = $_SESSION['last_login'];
$response = array();
$response['status'] = 'Error';
$arizona_off = -7;
if (is_ajax()) 
{

	if (isset($_POST["sms_to"]) && !empty($_POST["sms_to"])) 
 	{
 		// send sms
 		$sms_sal = trim($_REQUEST["sms_sal"]);	 //salutations
    	$sms_to = explode(';',trim($_REQUEST["sms_to"])); //to people
    	$sms_to_cnt = count($sms_to);
    	
    	$sms_body = $_REQUEST["sms_body"];
    							
    	foreach($sms_to as $number => $to)
    	{
    		
			$to_phone = preg_replace("/[^0-9]*/s", "",trim($to));
			$to_phone1 = "+1".$to_phone;			
			
			/* check if this number is already saved in undeliverable table */
			$sql_check = sprintf("select count(*) as cnt from undeliverable_info where address='%s' and type='sms'",$to_phone);
			$res_check = mysqli_query($con, $sql_check) or die(mysqli_error($con));
			$check_cnt = 0;
			if ($rec_check = mysqli_fetch_assoc($res_check))
			{
				$check_cnt = $rec_check['cnt'];
				if ($check_cnt == 0) // not saved in undeliverable table
				{
					
					$sql_sel_cus = sprintf("select p_fl_nm,customer_id,agent  from customer_info where ((p_ph1 like '%s') or (p_ph2 like '%s') or (p2_ph1 like '%s') or (p3_ph1 like '%s')) limit 1",$to_phone,$to_phone,$to_phone,$to_phone);
					$resb_sel_cus = mysqli_query($con, $sql_sel_cus) or die(mysqli_error($con));	
					if ($recb_cus = mysqli_fetch_assoc($resb_sel_cus))
					{				
						$customer_id = $recb_cus['customer_id'];
						$customer_name = $recb_cus['p_fl_nm'];
						$agent = $recb_cus['agent'];				
					}
					
					if (isset($customer_name) and ($customer_name != ""))
					{
						$last_nm_pos = strpos($customer_name,' ');
						if ($last_nm_pos === false)
						{
							$sms_sal_nm = $customer_name;
						}else
							$sms_sal_nm = substr($customer_name,0,$last_nm_pos);
					}
					
					if ($_REQUEST['single_sms_salution_included'] == "true")
					{
						$sms_sal = $sms_sal.' '.$sms_sal_nm.','."\r\n";	
					}else
						$sms_sal = "";	
					
					$sms_body = $sms_sal.$sms_body;
				
					$response['sms_body']	= $sms_body;	
					$response['status'] = 'Success';				
				}else
				{
					$response['status'] = 'This phone number is wrong!';					
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