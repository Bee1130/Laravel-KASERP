<?php
/****-------------------------------------------------------------------****
		Purpose 	: 	Change login credential after admin login
		Project 	:	Sales Contact DB	
	 	Developer 	: 	Kelvin
	 	Create Date : 	09/01/2016   
****-------------------------------------------------------------------****/
session_start();
if(!isset($_SESSION['user_login']) and !isset($_COOKIE['cookie_login']))//session store admin name
{
	header("Location: index.php");//login in AdminLogin.php
}
require_once("includes/dbconnect.php");

$sql = "select * from admin_user where user_id='".$_GET['uid']."'";
$res = mysqli_query($con, $sql) or die(mysqli_error($con)."777");
$response = mysqli_fetch_assoc($res);
$sql_ins = '';
$msg = '';

$submit = null;
if(isset($_POST['Submit'])) {
	$submit = $_POST['Submit'];
}
if($submit == "Change" )
{
	if($_POST['password'] == "")
	{
		$msg="Please enter a password.";
	}	
	elseif($_POST['password'] != $_POST['rpw'])
	{
		$msg="The passwords you entered did not match.";
	}
	else
	{		
		/* delete old record */	
	
		
		$sql_upd = "update admin_user set password = '".trim($_POST['password'])."',user_id='".trim($_POST['user_id'])."',e_mail='".trim($_POST['e_mail'])."',phone='".trim($_POST['phone'])."',google_calendar_iframe= '".mysqli_real_escape_string($con,trim($_POST['google_calendar_iframe']))."',google_map_api= '".mysqli_real_escape_string($con,trim($_POST['google_map_api']))."',marketing_email= '".trim($_POST['marketing_email'])."',user_group='".trim($_POST['user_group'])."' where user_id = '".trim($_GET['uid'])."'";	
		
		
		mysqli_query($con, $sql_upd) or die(mysqli_error($con));
		$msg="Change sales agent is successed!";
		
		// insert action logs into system_log_info
		$sql_log = sprintf("insert into system_log_info (action,agent,query,log_time) values ('%s','%s','%s',sysdate())","Change user : chagnecredential.php ",$_SESSION['user_login'],mysqli_real_escape_string($con,$sql_ins));
		mysqli_query($con, $sql_log) or die(mysqli_error($con));	
		
		
		// insert action logs
		$log = 'User'.' for '.$_GET['uid'].' was changed by '.$_SESSION['user_login'];	
		$type = 'User';
		$fa_icon = 'fa-user';
		$rid='';
		$url='changecredential.php?uid='.trim($_POST['user_id']);				
		$sql_log = sprintf("insert into log_info (log_data,log_time,agent,log_type,customer_id,url,fa_icon) values ('%s',sysdate(),'%s','%s','%d','%s','%s')",mysqli_real_escape_string($con,$log),$_SESSION['user_login'],$type,$rid,$url,$fa_icon);
		mysqli_query($con, $sql_log) or die(mysqli_error($con));	
			
		// select new user
		$sql = "select * from admin_user where user_id='".$_POST['user_id']."'";
		$res = mysqli_query($con, $sql) or die(mysqli_error($con)."777");
		$response = mysqli_fetch_assoc($res);
		
		/*header("Location: changecredential.php?uid=".$_GET['uid']);
		exit();*/
	}	
}
?>
<!DOCTYPE HTML>
<html>
    <head>
       <?php include("header.php");?>
    </head>
    <body>
		<div class="container">
    	    <?php include("sidebar.php"); ?>
    	    <div class="main-content">
    	        <?php include("menu.php"); ?>
        		<div class="container mobile-container">
        			<br>
        			<div class="row mobile-row">
        				<div class="col-sm-12">
        					<center>
        						<h1>Change Credential</h1>
        						<br>
        					</center>
        				</div>
        			</div>
        			<div class="row mobile-row" >
        		    	<div class="col-sm-6 col-sm-offset-3 col-md-6 col-md-offset-3 col-lg-4 col-lg-offset-4">
        		    		<form name="default_emplate" id="default_emplate" method="post" enctype="multipart/form-data">
        		    			<div class="panel-group">
        	                		<div class="panel panel-default" id="basic_panel">
        	                			<div class="panel-body">
        	                				<div class="row mobile-row">
        										<div class="form-group" >
        											<center>
        												<label style="width:40%;text-align:left" for="apply_dt">Name</label>
        												<input name="user_id" id="user_id" class="form-control  my-form-control" style="display:inline-block;width:55%" type="text"    value="<?php if(isset($response['user_id'])){echo $response['user_id'];}?>" />
        											</center>
        										</div>
        									</div>
        									<div class="row mobile-row">
        										<div class="form-group" >
        											<center>
        												<label style="width:40%;text-align:left" for="password">Password</label>
        												<input name="password" id="password" class="form-control  my-form-control" autocomplete='off'  style="display:inline-block;width:55%" type="password"/>
        											</center>
        										</div>
        									</div>
        									<div class="row mobile-row">
        										<div class="form-group" >
        											<center>
        												<label style="width:40%;text-align:left" for="rpw">Confirm Password</label>
        												<input name="rpw" id="rpw" class="form-control  my-form-control" autocomplete='off'  style="display:inline-block;width:55%" type="password"/>
        											</center>
        										</div>
        									</div>                    				
        	            					<div class="row mobile-row">
        										<div class="form-group" >
        											<center>
        												<label style="width:40%;text-align:left" for="e_mail">Pesonal Email</label>
        												<input name="e_mail" id="e_mail" class="form-control  my-form-control" style="display:inline-block;width:55%" type="text" value="<?php if(isset($response['e_mail'])){echo $response['e_mail'];}?>"/>
        											</center>
        										</div>
        									</div>
        									<div class="row mobile-row">
        										<div class="form-group" >
        											<center>
        												<label style="width:40%;text-align:left" for="phone">Pesonal Phone</label>
        												<input name="phone" id="phone" class="form-control  my-form-control" style="display:inline-block;width:55%" type="text" value="<?php if(isset($response['phone'])){echo $response['phone'];}?>"/>
        											</center>
        										</div>
        									</div>
        									<div class="row mobile-row">
        										<div class="form-group" >
        											<center>
        												<label style="width:40%;text-align:left" for="google_map_api">Google MAP API</label>
        												<input name="google_map_api" id="google_map_api" class="form-control  my-form-control" style="display:inline-block;width:55%" type="text" value="<?php if(isset($response['google_map_api'])){echo $response['google_map_api'];}?>"/>
        											</center>
        										</div>
        									</div>
        									<div class="row mobile-row">
        										<div class="form-group" >
        											<center>
        												<label style="width:40%;text-align:left" for="google_calendar_iframe">Google Calendar Iframe</label>
        												<textarea class="form-control" cols="40" rows="5"  id="google_calendar_iframe" maxlength="2500" name="google_calendar_iframe" style="display:inline-block;width:55%"><?php if(isset($response['google_calendar_iframe'])){echo $response['google_calendar_iframe'];}?></textarea>
        											</center>
        										</div>
        									</div>
        									
        								<!--	<div class="row mobile-row">
        										<div class="form-group" >
        											<center>
        												<label style="width:40%;text-align:left" for="tw_account_sid">Twilio Account SID</label>
        												<input name="tw_account_sid" id="tw_account_sid" class="form-control  my-form-control" style="display:inline-block;width:55%" type="text"    value="<?php if(isset($response['tw_account_sid'])){echo $response['tw_account_sid'];}?>" />
        											</center>
        										</div>
        									</div>
        									<div class="row mobile-row">
        										<div class="form-group" >
        											<center>
        												<label style="width:40%;text-align:left" for="tw_auth_token">Twilio Account Token</label>
        												<input name="tw_auth_token" id="tw_auth_token" class="form-control  my-form-control" style="display:inline-block;width:55%" type="text"    value="<?php if(isset($response['tw_auth_token'])){echo $response['tw_auth_token'];}?>" />
        											</center>
        										</div>
        									</div>
        									<div class="row mobile-row">
        										<div class="form-group" >
        											<center>
        												<label style="width:40%;text-align:left" for="tw_number">Twilio Phone Number</label>
        												<input name="tw_number" id="tw_number" class="form-control  my-form-control" style="display:inline-block;width:55%" type="text"    value="<?php if(isset($response['tw_number'])){echo $response['tw_number'];}?>" />
        											</center>
        										</div>
        									</div>
        									<div class="row mobile-row">
        										<div class="form-group" >
        											<center>
        												<label style="width:40%;text-align:left" for="tw_app_sid">TWIML APP SID</label>
        												<input name="tw_app_sid" id="tw_app_sid" class="form-control  my-form-control" style="display:inline-block;width:55%" type="text"    value="<?php if(isset($response['tw_app_sid'])){echo $response['tw_app_sid'];}?>" />
        											</center>
        										</div>
        									</div>-->
        									<div class="row mobile-row">
        										<div class="form-group" >
        											<center>
        												<label style="width:40%;text-align:left" for="marketing_email">Marketing Email</label>
        												<input name="marketing_email" id="marketing_email" class="form-control  my-form-control" style="display:inline-block;width:55%" type="text"    value="<?php if(isset($response['marketing_email'])){echo $response['marketing_email'];}?>" />
        											</center>
        										</div>
        									</div>
        	            					<div class="row mobile-row">
        										<div class="form-group" >
        											<center>
        												<label style="width:40%;text-align:left" for="user_group">Group</label>
        												<select class='form-control my-form-control' style="display:inline-block;width:55%"  name="user_group" id="user_group" size="1">
        							                    	<option value="Super User" <?php if(isset($response['user_group']) and ($response['user_group']=="Super User")) {echo 'selected';}?>>Full Access</option>
        							                    	<option value="Admin" <?php if(isset($response['user_group']) and ($response['user_group']=="Admin")) {echo 'selected';}?>>Limit Access</option>								                      	       
        							      
        							                    </select>													
        											</center>
        										</div>
        									</div>	
        	                			</div>
        	                		</div>                    		
        	                	</div>
        						<div class="row mobile-row">
        			           		<center>
        			           			<div class="col-sm-offset-5 col-sm-2 col-xs-offset-4 col-xs-4" style="padding-left:0px;padding-right:0px;">
        			           				<div class="form-group" >
        			                			<button class='form-control btn btn-primary submit-button' type='submit' name="Submit" value="Change">Change</button>
        			              			</div>		           			
        			           			</div>			           			
        			           		</center>           
        			  			</div>
        		        	</form>	       
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
        		</div>	
    		</div>
    	</div>
    </body>
</html>
