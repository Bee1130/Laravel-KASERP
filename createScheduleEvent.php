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
		$event_id = trim($_POST['event_id']);	
		
	$event_start = trim($_POST['event_start']);	
	$event_end = trim($_POST['event_end']);		
	$event_description = trim($_POST['event_description']);	
	
	$event_public = 1;
	$event_all_day =0;
	if (isset($_POST['event_public']))
		$event_public = trim($_POST['event_public']);	
	if (isset($_POST['event_all_day']))
		$event_all_day = trim($_POST['event_all_day']);	
	
	$event_start_old = $event_start;
	$event_end_old = $event_end;
	if (strlen($event_description) >= 0)
	{
		$event_start = date('Y-m-d H:i',strtotime($event_start));
		$event_end = date('Y-m-d H:i',strtotime($event_end));
		
		// check if this lead already exists in the database
		/*$sql_del= sprintf("delete from reminders_info where start='%s' and end = '%s' and public=%d and all_day =%d",$event_start,$event_end,$event_public,$event_all_day);*/
		if ($event_id > 0)
		{
			$sql_del= sprintf("delete from reminders_info where auto_id=%d",$event_id);
			$sql_del_res=mysqli_query($con, $sql_del) or die(mysqli_error($con)."11");	
		}else
		{
			$sql_del= sprintf("delete from reminders_info where start='%s' and end = '%s'",$event_start,$event_end);
			$sql_del_res=mysqli_query($con, $sql_del) or die(mysqli_error($con)."11");
		}
		
	
		
		saveLog('','Event',$event_description,'added');
		
		$title = $event_description;
		
		//sprintf($url,"<a href='#' onclick='editEvent(%d,%d,'%s')'
					
		$data = array('start'=>$event_start,'end'=>$event_end,'all_day'=>$event_all_day,'public'=>$event_public,'title'=>mysqli_real_escape_string($con, $title),'description'=>mysqli_real_escape_string($con,$event_description),'active'=>1,'set_time'=>date('Y-m-d H:i:s'),'url'=>'#');
		
		$fa_icon = 'fa fa-calendar';
		$sql_ins = sprintf("insert into reminders_info (client_id,start,end,all_day,public,title,description,active,set_time,user,url,fa_icon) values ('%d','%s','%s','%d','%d','%s','%s','%d','%s','%s','%s','%s')",$data['client_id'] ?? 0,$data['start'],$data['end'],$data['all_day'],$data['public'],$data['title'],$data['description'],$data['active'],$data['set_time'],$_SESSION['user_login'],$data['url'],$fa_icon);
		mysqli_query($con,$sql_ins) or die(mysqli_error($con));
		
		$event_id = mysqli_insert_id($con);
		
		
		$url = sprintf("javascript:editEvent('%d','%s','%s','%s')",$event_id,$event_start_old,$event_end_old,$event_description);
		
		$sql_upd = 'update reminders_info set url="'.$url.'" where auto_id='.$event_id;
		mysqli_query($con, $sql_upd) or die(mysqli_error($con));
		
	}
}


//echo $res;
echo json_encode($response);

//Function to check if the request is an AJAX request
function is_ajax() {
  return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
}
?>
