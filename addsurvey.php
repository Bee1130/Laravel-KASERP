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
		
		/* Survey Logo upload */
        $dest = 'survey_logo';
        if(!isset($_FILES['survey_logo']) || !is_uploaded_file($_FILES['survey_logo']['tmp_name'])){
            $survey_logo= 'default.png';
            move_uploaded_file($_FILES['survey_logo']['tmp_name'], "$dest/$survey_logo");
        }
        else{
            $RandomNum   = rand(0, 9999999999);
            $ImageName = str_replace(' ','-',strtolower($_FILES['survey_logo']['name']));
            $ImageType = $_FILES['survey_logo']['type'];
            $ImageExt = substr($ImageName, strrpos($ImageName, '.'));
            $ImageExt = str_replace('.','',$ImageExt);
            $ImageName = preg_replace("/\.[^.\s]{3,4}$/", "", $ImageName);
            $survey_logo = $ImageName.'-'.$RandomNum.'.'.$ImageExt;
            move_uploaded_file($_FILES['survey_logo']['tmp_name'], "$dest/$survey_logo");
        }
	    
	    $content = $_POST['email_body'];
	    $content .= "<br>";	
	    $content .= "<br>";	
	    $survey_logo_photo =  "http://www.limo-crm.com/survey_logo/".$survey_logo;	    
	    $content .="<img src='$survey_logo_photo'>";
	    	
		$sql_ins = sprintf("insert into survey_mails_info (subject,content,body,created_time,created_by,logo_file) values ('%s','%s','%s',sysdate(),'%s','%s')",mysqli_real_escape_string($con, $_POST['email_subject']),mysqli_real_escape_string($con, $content),mysqli_real_escape_string($con, $_POST['email_body']),$_SESSION['user_login'],mysqli_real_escape_string($con, $survey_logo));
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