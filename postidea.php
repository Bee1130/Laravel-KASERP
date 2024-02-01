<?php
	@session_start();
	//ob_start();
	
	if(!isset($_SESSION['user_login']) and !isset($_COOKIE['cookie_login']))//session store admin name
	{
	    header("Location: index.php");//login in AdminLogin.php
	}
	require_once("includes/dbconnect.php");
	
	
    $response = array();
    $response['status'] = 'Error';	
    if (is_ajax()) 
	{	
    	$post_content=$_POST['post_content'];    
    	   
        $sql_ins=sprintf("INSERT INTO post_ideas (user_name,user_group,post_time,post_content) values ('%s', '%s', sysdate(), '%s');",$_SESSION['user_login'], $_SESSION['user_group'], addslashes($post_content));
    	mysqli_query($con, $sql_ins)or die(mysqli_error($con));    	
    	
    	$response['status'] = 'Success';	          
    }        
    echo json_encode($response);
    
    //Function to check if the request is an AJAX request
function is_ajax() {
  return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
}
?>