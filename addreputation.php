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
	if (isset($_POST['email_body']) and isset($_POST['email_subject']))
	{
		/* Reputation Logo upload */
        $dest = 'reputation_logo';
        if(!isset($_FILES['reputation_logo']) || !is_uploaded_file($_FILES['reputation_logo']['tmp_name'])){
            $reputation_logo= 'default.png';
            move_uploaded_file($_FILES['reputation_logo']['tmp_name'], "$dest/$reputation_logo");
        }
        else{
            $RandomNum   = rand(0, 9999999999);
            $ImageName = str_replace(' ','-',strtolower($_FILES['reputation_logo']['name']));
            $ImageType = $_FILES['reputation_logo']['type'];
            $ImageExt = substr($ImageName, strrpos($ImageName, '.'));
            $ImageExt = str_replace('.','',$ImageExt);
            $ImageName = preg_replace("/\.[^.\s]{3,4}$/", "", $ImageName);
            $reputation_logo = $ImageName.'-'.$RandomNum.'.'.$ImageExt;
            move_uploaded_file($_FILES['reputation_logo']['tmp_name'], "$dest/$reputation_logo");
        }
	    
	    $content = $_POST['email_body'];
	    $content .= "<br>";	
	    $content .= "<br>";	
	    $reputation_logo_photo =  "http://www.limo-crm.com/reputation_logo/".$reputation_logo;
	    //$reputation_logo_photo =  "http://dccf9c68.ngrok.io/limocrm/reputation_logo/".$reputation_logo;
	    $content .="<img src='$reputation_logo_photo'>";
	    
		$sql_ins = sprintf("insert into reputation_mails_info (subject,content,body,created_time,created_by,logo_file) values ('%s','%s','%s',sysdate(),'%s','%s')",mysqli_real_escape_string($con, $_POST['email_subject']),mysqli_real_escape_string($con, $content),mysqli_real_escape_string($con, $_POST['email_body']),$_SESSION['user_login'],mysqli_real_escape_string($con, $reputation_logo));
		mysqli_query($con, $sql_ins) or die(mysqli_error($con));
		$response['status'] = "success";
	}
}
echo json_encode($response);

//Function to check if the request is an AJAX request
function is_ajax() {
  return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
}
?>