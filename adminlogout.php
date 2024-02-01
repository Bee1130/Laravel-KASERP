<?php
//phpinfo();
/****-------------------------------------------------------------------**************************	
		Purpose 	: 	This page will destroy the session and log off the user from the system
		Project 	:	Smart Travel Project	
	 	Developer 	: 	Wilson Tan
		Version		:	1.0
	 	Create Date : 	15/02/2012     
****-------------------------------------------------------------------************************/
session_start();
require_once("includes/dbconnect.php");


/*--- login, logout, duration time log ---*/

if(!isset($_SESSION['user_login']) ||$_SESSION['user_login']=="")//session store admin name
{
	header("Location: adminlogin.php");//login in AdminLogin.php
}

//header("Location: getNotification.php");//login in AdminLogin.php
$_SESSION['admin_logout_time']=time();

	
$sql="select sysdate() as cur_logout_time;";  
$res=mysqli_query($con, $sql) or die(mysqli_error($con)."11");
$res_rec=mysqli_fetch_assoc($res);
$_SESSION['cur_logout_time']=$res_rec['cur_logout_time'];				



$sql=sprintf("select TIME_TO_SEC(TIMEDIFF('%s','%s')) as secs_diff;",$_SESSION['cur_logout_time'],$_SESSION['last_login']);  
$res=mysqli_query($con, $sql) or die(mysqli_error($con)."11");
$res_rec=mysqli_fetch_assoc($res);
$duration=$res_rec['secs_diff'];	


$seconds = $duration % 60;
$minutes = (int)(($duration % 3600) / 60);
$hours = (int)($duration / 3600);
$duration = $hours.":".$minutes.":".$seconds;

if ($_SESSION['calls_made']=="")
	$_SESSION['calls_made']=0;		
if ($_SESSION['calls_con']=="")
	$_SESSION['calls_con']=0;		
if ($_SESSION['conv_minutes']=="")
	$_SESSION['conv_minutes']=0;		
if ($_SESSION['email_sent']=="")
	$_SESSION['email_sent']=0;		
if ($_SESSION['email_recv']=="")
	$_SESSION['email_recv']=0;		
if ($_SESSION['sms_sent']=="")
	$_SESSION['sms_sent']=0;		
if ($_SESSION['sms_recv']=="")
	$_SESSION['sms_recv']=0;	
if ($_SESSION['ratio']=="")
	$_SESSION['ratio']=0;	

$new = 0;
if(isset($_SESSION['new'])) {
	$new = $_SESSION['new'];
}
$opened_emails = 0;
if(isset($_SESSION['opened_emails'])) {
	$opened_emails = $_SESSION['opened_emails'];
}

$clickthroughs = 0;
if(isset($_SESSION['clickthroughs'])) {
	$clickthroughs = $_SESSION['clickthroughs'];
}


$retry = 0;
if(isset($_SESSION['retry'])) {
	$retry = $_SESSION['retry'];
}

$today_task = 0;
if(isset($_SESSION['today_task'])) {
	$today_task = $_SESSION['today_task'];
}

$past_due = 0;
if(isset($_SESSION['past_due'])) {
	$past_due = $_SESSION['past_due'];
}


$delinquent = 0;
if(isset($_SESSION['delinquent'])) {
	$delinquent = $_SESSION['delinquent'];
}


$hot = 0;
if(isset($_SESSION['hot'])) {
	$hot = $_SESSION['hot'];
}


$warm = 0;
if(isset($_SESSION['warm'])) {
	$warm = $_SESSION['warm'];
}


$credit_check = 0;
if(isset($_SESSION['credit_check'])) {
	$credit_check = $_SESSION['credit_check'];
}




$credit_repair = 0;
if(isset($_SESSION['credit_repair'])) {
	$credit_repair = $_SESSION['credit_repair'];
}


$credit_ready = 0;
if(isset($_SESSION['credit_ready'])) {
	$credit_ready = $_SESSION['credit_ready'];
}


$pre_approved = 0;
if(isset($_SESSION['pre_approved'])) {
	$pre_approved = $_SESSION['pre_approved'];
}


$doc_sent = 0;
if(isset($_SESSION['doc_sent'])) {
	$doc_sent = $_SESSION['doc_sent'];
}

$pending_funding = 0;
if(isset($_SESSION['pending_funding'])) {
	$pending_funding = $_SESSION['pending_funding'];
}
$funded = 0;
if(isset($_SESSION['funded'])) {
	$funded = $_SESSION['funded'];
}
$fee_pending = 0;
if(isset($_SESSION['fee_pending'])) {
	$fee_pending = $_SESSION['fee_pending'];
}
$doc_sent = 0;
if(isset($_SESSION['doc_sent'])) {
	$doc_sent = $_SESSION['doc_sent'];
}
$thirty_day_funding = 0;
if(isset($_SESSION['thirty_day_funding'])) {
	$thirty_day_funding = $_SESSION['thirty_day_funding'];
}
$sixty_day_funding = 0;
if(isset($_SESSION['sixty_day_funding'])) {
	$sixty_day_funding = $_SESSION['sixty_day_funding'];
}
$sixty_ninety_day_fundings = 0;
if(isset($_SESSION['sixty_ninety_day_fundings'])) {
	$sixty_ninety_day_fundings = $_SESSION['sixty_ninety_day_fundings'];
}

$clients = 0;
if(isset($_SESSION['clients'])) {
	$clients = $_SESSION['clients'];
}


$other_opportunity = 0;
if(isset($_SESSION['other_opportunity'])) {
	$other_opportunity = $_SESSION['other_opportunity'];
}

$email = -1;
if(isset($_SESSION['email'])) {
	$email = $_SESSION['email'];
}

$call = 0;
if(isset($_SESSION['call'])) {
	$call = $_SESSION['call'];
}

$sms = 0;
if(isset($_SESSION['sms'])) {
	$sms = $_SESSION['sms'];
}

$undeliverable_emails = null;
if(isset($_SESSION['undeliverable_emails'])) {
	$undeliverable_emails = $_SESSION['undeliverable_emails'];
}

$undeliverable_sms = null;
if(isset($_SESSION['undeliverable_sms'])) {
	$undeliverable_sms = $_SESSION['undeliverable_sms'];
}


$undeliverable_emails_sms = null;
if(isset($_SESSION['undeliverable_emails_sms'])) {
	$undeliverable_emails_sms = $_SESSION['undeliverable_emails_sms'];
}

$timestamp = $_SESSION['last_login'];
$datetimeFormat = 'Y-m-d H:i:s';

$date = new DateTime();
// If you must have use time zones
// $date = new \DateTime('now', new \DateTimeZone('Europe/Helsinki'));
$date->setTimestamp($timestamp);
$date = $date->format($datetimeFormat);








	

// var_dump($today_task);die;
$sql="insert into agent_log_info (log_in,log_out,duration,dur_hour,dur_min,dur_sec,agent,call_made,call_conn,conv_min,eml_sent,eml_recv,sms_sent,sms_recv,ratio,new,opened_emails,clickthroughs,retry,today_task,past_due,delinquent,hot,warm,credit_check,credit_repair,credit_ready,pre_approved,doc_sent,pending_funding,funded,fee_pending,thirty_day_funding,sixty_day_funding,sixty_ninety_day_fundings,clients,other_opportunity,calls,email,sms,undeliverable_emails,undeliverable_sms,undeliverable_emails_sms) values ('". $date ."','" . $_SESSION['cur_logout_time'] ."','" . $duration ."','" . $hours ."','". $minutes ."','" . $seconds."','".$_SESSION['user_login']."','".$_SESSION['calls_made']."','".$_SESSION['calls_con']."','".$_SESSION['conv_minutes']."','".$_SESSION['email_sent']."','".$_SESSION['email_recv']."','".$_SESSION['sms_sent']."','".$_SESSION['sms_recv']."','".$_SESSION['ratio']."','".$new."','".$opened_emails."','".$clickthroughs."','".$retry."','".$today_task."','".$past_due."','".$delinquent."','".$hot."','".$warm."','".$credit_check."','".$credit_repair."','".$credit_ready."','".$pre_approved."','".$doc_sent."','".$pending_funding."','".$funded."','".$fee_pending."','".$thirty_day_funding."','".$sixty_day_funding."','".$sixty_ninety_day_fundings."','".$clients."','".$other_opportunity."','".$call."','".$email."','".$sms."','".$undeliverable_emails."','".$undeliverable_sms."','".$undeliverable_emails_sms."')";  
$res=mysqli_query($con, $sql) or die(mysqli_error($con)."11");

/*-----------------------------------------*/

/* set cur_login_time null */

$sql = sprintf("update admin_user set cur_login_time='%s' where user_id='%s'",$date,$_SESSION['user_login']);
$res=mysqli_query($con, $sql) or die(mysqli_error($con)."11");

session_destroy();
setcookie("cookie_login","",time()-3600);
setcookie("cookie_password","",time()-3600);
header("Location: index.php");
?>