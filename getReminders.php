<?php 
@session_start();	
if (! isset($_COOKIE['cookie_login']) and !isset($_SESSION['user_login'])) {//session store admin name
    header("Location: index.php"); //login in AdminLogin.php
}
require_once("includes/dbconnect.php");
// check if this lead already exists in the database
$sql_sel= sprintf("select auto_id as id, start, end,active,case when all_day =0 then NULL else true end allDay, title,description,url from reminders_info where active =1");

$sql_res=mysqli_query($con, $sql_sel) or die(mysqli_error($con)."11");
$ind =0;
$res = array();
while ($sql_rec= mysqli_fetch_row($sql_res)) {
	$jsonData = array();
	$jsonData['id'] = $sql_rec[0];
	$jsonData['start'] = $sql_rec[1];
	$jsonData['end'] = $sql_rec[2];
	$jsonData['active'] = $sql_rec[3];
	$jsonData['allDay'] = $sql_rec[4];
	$jsonData['title'] = $sql_rec[5];
	$jsonData['description'] = $sql_rec[6];
	$jsonData['url'] = $sql_rec[7];
	$res[$ind] = $jsonData;
	$ind++;
    
}
//echo $res;
echo json_encode($res);
?>
