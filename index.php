<?php
/****-------------------------------------------------------------------**************************	
		Purpose 	: 	This page will act as the login to the system
		Project 	:	Sales Contact DB	
	 	Developer 	: 	Wilson Tan
	 	Create Date : 	27/12/2015     
****-------------------------------------------------------------------************************/

@session_start();

require_once("includes/dbconnect.php");
/* ------------------------------------------------------------- */
$msg = "";

if(isset($_COOKIE['cookie_login']) and $_COOKIE['cookie_login']!="")//session store admin name, modified 20151119
{
	$_SESSION['user_login']=$_COOKIE['cookie_login'];
	$_SESSION['user_password']=$_COOKIE['cookie_password'];
	
	header("Location: adminhome.php");//login in AdminLogin.php
}
if(isset($_POST['Submit']) and $_POST['Submit']=='Log in') // modified 20151119
{ 
	$login=trim($_POST['name']);
	$password=$_POST['pw'];
	
	$sql="select * from admin_user where user_id='".$login."'";  
	$res=mysqli_query($con, $sql) or die(mysqli_error($con)."11");
	
	if ($array_pass_check=mysqli_fetch_assoc($res))
	{
		$_SESSION['user_login']=$login;
		$_SESSION['user_password']=$password;
		$_SESSION['user_group']=$array_pass_check['user_group'];
		$_SESSION['user_email'] = $array_pass_check['e_mail'];
		$_SESSION['google_calendar_iframe'] = $array_pass_check['google_calendar_iframe'];
		$_SESSION['google_map_api'] = $array_pass_check['google_map_api'];
		
		// Twilio Info for Call, SMS
       /* $_SESSION['tw_account_sid'] = trim($array_pass_check['tw_account_sid']);
        $_SESSION['tw_auth_token'] = trim($array_pass_check['tw_auth_token']);
        $_SESSION['tw_app_sid'] = trim($array_pass_check['tw_app_sid']);
		$_SESSION['tw_number'] = trim($array_pass_check['tw_number']);
		$_SESSION['tw_token']=""; //twilio token*/
		
		$_SESSION['marketing_email']=trim($array_pass_check['marketing_email']);
	                
	}
	
	//checking whether the user id and password fields are empty 
	if($login=="")
		$msg="Name is empty!";
	else if($password=="")
		$msg="Password is empty!";		
	else if($array_pass_check['password']==$password)
	{	
		if($_POST['cookie']=='Y')
		{
			setcookie("cookie_login",$login,time()+31536000);
			setcookie("cookie_password",$password,time()+31536000);
			setcookie("cookie_group",$group,time()+31536000);
		}
		
		//selecting associated data from admin_user table where user id is sign id id
		
		$_SESSION['admin_login_time']=time();
		$_SESSION['last_login']=time();
		
 		/* it is true only when login*/
 		$_SESSION['is_first_serachbuyerpage']=1;
 		
 		
 		/* Init Supppression list id */
		$_SESSION['suppressionid']=0;
		
 		/*  if there is any modification */
		$_SESSION['is_modified']=1;
						
		/* filter agent */
		
		
		$_SESSION['filter_user'] ='';
		if (strcasecmp($_SESSION['user_group'],'Openers/Loaders')==0)
		{
			$_SESSION['filter_user'] .= " and (assigned='".$_SESSION['user_login']."')";
		}
		
		$_SESSION['filter_search_user'] ='';
		if (strcasecmp($_SESSION['user_group'],'Admin')==0)
		{
			$_SESSION['filter_search_user'] .= " and (assigned is not null) ";
		}else if (strcasecmp($_SESSION['user_group'],'Openers/Loaders')==0)
		{
			$_SESSION['filter_search_user'] .= " and (assigned='".$_SESSION['user_login']."')";
		}

		$_SESSION['filter_log'] = '';
		if (strcasecmp($_SESSION['user_group'],'Admin')==0)
		{
			$_SESSION['filter_log'] .= " and (agent like ('".$_SESSION['user_login']."')) ";
		}else if (strcasecmp($_SESSION['user_group'],'Openers/Loaders')==0)
		{
			$_SESSION['filter_log'] .= " and ((assigned is not null) and (length(assigned)>0) and (FIND_IN_SET('".$_SESSION['user_login']."',assigned)))";
		}
		
		// Call, Email, Sms while agent was logged out
		$_SESSION['email'] =-1;
		$_SESSION['logout_email_get_time'] =0;
	
		//init();
		
        if(isMobile()){
		    header("Location: schedule.php");
		}else            
			header("Location: schedule.php");
		
		exit();	
	}
	else
	{
		$msg="Wrong User ID or Password!";
	}			
}
function init()
{
/*	// Deleted assigned worker info		                        	                             	
	$sql_del = "truncate assigned_info";
	$sql_del_res = mysqli_query($sql_del) or die(mysqli_error());	        
	
	// Deleted assigned worker info		                        	                             	
	$sql_del = "truncate assigned_worker_info";
	$sql_del_res = mysqli_query($sql_del) or die(mysqli_error());	         
		
	$sql_select = "select ass_worker,ass_notify from leads_info where length(ass_worker)>0";      
    $result = mysqli_query($sql_select) or die(mysqli_error());
    
    while ($seerec = mysqli_fetch_assoc($result))
    {
    	$assigned_workers = $seerec['ass_worker'];
    	$assigned_notifys = $seerec['ass_notify'];
    	if (isset($assigned_workers) and strlen($assigned_workers)>0)
		{
			$workers = explode(';',$assigned_workers);		
			$notifys = explode(';',$assigned_notifys);			
        	foreach ($workers as $i => $worker)
            {	       	                           
            	$notify = $notifys[$i];	                                	                             	
            	$sql_ins = sprintf("insert INTO assigned_info (id,worker,notify) VALUES ('%d','%s','%s')",$auto_id,$worker,$notify);
				$sql_ins_res = mysqli_query($sql_ins) or die(mysqli_error());	         
            }
		}
	}	
	
	// Insert unique workers into assigned worker info		                        	                             	
	$sql_ins = "insert into assigned_worker_info (worker) (select distinct worker from assigned_info)";
	$sql_ins_res = mysqli_query($sql_ins) or die(mysqli_error());	        */ 
	

}
function isMobile() {
    return preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"]);
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
	<head>
		<?php include("header.php");?>
	</head>
	<body>
		<div class="container">
		    <div class="row mobile-row">
		       <div class="main">		       	
		       		<div class="row" align="center" style="margin-bottom: 20px;">
		       			<a href="#"><img class="img-responsive"  src="images/logo.png"></a>		
		       		</div>
		       		<div class="row" >      		
		          		<form class="form" name="login" method="post" style="">
		          			<div class='form-row'>
				              <div class='col-xs-12 form-group' style="margin-left:0px;margin-right:0px">
				                <label class='control-label' style="width:30%">Username</label>
				                <input type="text" class="form-control" id="name"  name="name"   placeholder="Enter User ID"   style="width:67%;display: inline-block;">
				              </div>
				  			</div>
				  			
				  			
				  			<div class='form-row' style="margin-bottom: 50px;height: 60px;">
				              <div class='col-xs-12 form-group' style="margin-left:0px;margin-right:0px">
				                <label class='control-label' style="width:30%">Password</label>
				                <input type="password" class="form-control" id="pw" name="pw"  placeholder="Enter Password"  style="width:67%;display: inline-block;">	
				     		  </div>
				            </div>
		            			
					        <div class="row mobile-row">
				             	<div class="col-xs-12" style="z-index: 9;">
		    						<div class="form-group">
		    							<center>
		    								<input type="submit" class="btn btn-primary btn-lg ladda-button" data-style="zoom-in"  name="Submit" value="Log in" style="color:rgb(250, 255, 189);">			                  
		    							</center>
					        	    </div>
					        	</div>					        	
					        </div>
					        <div class="row mobile-row">
								<div class="col-sm-12">
									<center>
										<h6 style="color:blue;"><?php echo $msg;?></h6>
										<br>
									</center>
								</div>
							</div>			        
			          	</form>
			        </div>	
		    	</div>
			</div>
		</div>
		
		<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
	    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
	    
		<!-- Visual Visitor -->
		<script type="text/javascript"> 
			var fesdpid = 'lbf7dvgQca'; 
			var fesdp_BaseURL = (("https:" == document.location.protocol) ? "https://fe.sitedataprocessing.com/fewv1/" : "http://fe.sitedataprocessing.com/fewv1/");
			(function () { 
			var va = document.createElement('script'); va.type = 'text/javascript'; va.async = true; 
			va.src = fesdp_BaseURL + 'Scripts/fewliveasync.js'; 
			var sv = document.getElementsByTagName('script')[0]; sv.parentNode.insertBefore(va, sv); 
			})(); 
		</script> 
	</body>
</html>
