<?php

require_once 'includes/mail_cron_config.inc.php';

$sql_sel_user = "select * from admin_user limit 1";
$res_user=mysqli_query($con, $sql_sel_user) or die(mysqli_error($con)."11");
if($rec_user=mysqli_fetch_assoc($res_user))
{
	$accountSid = $rec_user['tw_account_sid'];
	$authToken  = $rec_user['tw_auth_token'];
	$from_phone = $rec_user['tw_number'];	
	$client = new Services_Twilio($accountSid, $authToken,'2010-04-01');
}
	
// get Redminder
$reminder1_time=3;
$reminder2_time=$reminder1_time+3;
  
$sql_sel = sprintf("select * from reminders_info where is_viewed = '%d' and (TIMESTAMPDIFF(MINUTE,NOW(),start) >= '%d' AND TIMESTAMPDIFF(MINUTE,NOW(),start) <= '%d') order by start ASC",0,$reminder1_time,$reminder2_time);
$res=mysqli_query($con, $sql_sel) or die(mysqli_error($con)."11");
while($res_rec=mysqli_fetch_assoc($res))
{
	$auto_id = $res_rec['auto_id'];
	$customer_id = $res_rec['client_id'];
	$title = 'I am reminding you about '.$res_rec['title'];
	$time = date('M d, Y, h:i:s (e)',strtotime($res_rec['start']));
	
	$sms_body = $title.' at '.$time;
	$email_body = $sms_body;
	$email_subj = "Reminder about ".$res_rec['title'];
	$from_name = 'CRM';
	
	$sql_sel_user = "select * from admin_user where user_id = '".$res_rec['user']."'";
	$res_user=mysqli_query($con, $sql_sel_user) or die(mysqli_error($con)."11");
	if($rec_user=mysqli_fetch_assoc($res_user))
	{
		$to_email = $rec_user['e_mail'];
		$from_email = $rec_user['marketing_email'];
		$to_name = $rec_user['user_id'];
		$to_phone = preg_replace("/[^0-9]*/s", "",trim($rec_user['phone']));
	}
	
	// Send Text
	if (isset($to_phone) and strlen($to_phone)>9)
	{
		
		/*$sms = $client->account->messages->sendMessage(
			$from_phone,
			$to_phone,
			$sms_body
		);				*/
		// insert action logs into system_log_info
		$log_str = "to_addr : ".$to_phone." => sms_body : ".$sms_body;
		$sql_log = sprintf("insert into system_log_info (action,agent,query,log_time) values ('%s','%s','%s',sysdate())","send sms in reminder_cron.php ",'Cron',mysqli_real_escape_string($con,$log_str));
		mysqli_query($con, $sql_log) or die(mysqli_error($con));	
	}
	    	

	// Send Email
	if ((isset($from_email) and strlen($from_email)>3) and (isset($to_email) and strlen($to_email)>3))
	{
		
		/*$sent = sendMail($to_email,$email_subj,$email_body,$from_email);*/
		
		$sql_mail = sprintf("insert into mail_log_info (mail_rcvr,mail_subject,mail_body,from_nm,from_address,start_time,log_time,customer_name,customer_id) values ('%s','%s','%s','%s','%s',sysdate(),sysdate(),'%s','%s')",
			$to_email,mysqli_real_escape_string($con,$email_subj),
			mysqli_real_escape_string($con,$email_body),mysqli_real_escape_string($con,$from_name),
			mysqli_real_escape_string($con,$from_email),mysqli_real_escape_string($con,$to_name),$customer_id);

		mysqli_query($con, $sql_mail) or die(mysqli_error($con));	    		
	}
	
	// Set notification as viewed for reminders info
	$sql_upd = sprintf("update reminders_info set is_viewed='%d' where auto_id='%d'",1,$auto_id);
	mysqli_query($con, $sql_upd) or die(mysqli_error($con)."11");
	 
	$sql_log = sprintf("insert into system_log_info (action,agent,query,log_time) values ('%s','%s','%s',sysdate())","Update reminder info for viewed notification : reminder_cron.php ",'Cron',mysqli_real_escape_string($con,$sql_upd));
	mysqli_query($con, $sql_log) or die(mysqli_error($con));	
}
?>