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
	if (isset($_POST['company_name']) and isset($_POST['company_name']))
	{
		
		/* Survey Logo upload */
        $dest = 'survey/logo';
        if(!isset($_FILES['logic_logo']) || !is_uploaded_file($_FILES['logic_logo']['tmp_name'])){
            $logic_logo= 'default.jpg';
            move_uploaded_file($_FILES['logic_logo']['tmp_name'], "$dest/$logic_logo");
        }
        else{
            $RandomNum   = rand(0, 9999999999);
            $ImageName = str_replace(' ','-',strtolower($_FILES['logic_logo']['name']));
            $ImageType = $_FILES['logic_logo']['type'];
            $ImageExt = substr($ImageName, strrpos($ImageName, '.'));
            $ImageExt = str_replace('.','',$ImageExt);
            $ImageName = preg_replace("/\.[^.\s]{3,4}$/", "", $ImageName);
            $logic_logo = $ImageName.'-'.$RandomNum.'.'.$ImageExt;
            move_uploaded_file($_FILES['logic_logo']['tmp_name'], "$dest/$logic_logo");
        }
    	
		$sql_ins = sprintf("insert into survey_logic_info (user_id,company_name,logo) values ('%s','%s','%s')",$_SESSION['user_login'],mysql_real_escape_string($_POST['company_name']),mysql_real_escape_string($logic_logo));
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