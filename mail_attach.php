<?php
/* * **-------------------------------------------------------------------**************************    

  Purpose 	: 	Where user can search the buyer detail

  Project 	:	Sales Contact DB

  Developer 	: 	Kelvin 

  Create Date : 	30/11/2015

 * ***-------------------------------------------------------------------*********************** */
header("Content-Description: File Transfer");
header("Content-Type: application/octet-stream");
require_once("includes/dbconnect.php");

if (isset($_GET['mail_account']))
{
	$mail_account = $_GET['mail_account'];
	$mail_uid = $_GET['mail_uid'];
	$attach_id = $_GET['attach_id'];
	$sql_select = sprintf("select content,file_name from mail_attachments_info where mail_account = '%s' and attach_id = '%d' and mail_uid = '%d' ",mysqli_real_escape_string($con, $mail_account),$attach_id,$mail_uid);
	$result = mysqli_query($con, $sql_select) or die(mysqli_error($con));
	while ($seerec = mysqli_fetch_assoc($result))
	{		
		$message = $seerec['content'];
		$filename = $seerec['file_name'];
		
		
	    header("Content-Disposition: attachment; filename=" . $filename);	    
	    header("Content-Transfer-Encoding: binary");
	    header("Expires: 0");
	    header("Cache-Control: must-revalidate");
	    header("Pragma: public");
	    echo $message;
	}
}
?>