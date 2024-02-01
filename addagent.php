<?php
/****-------------------------------------------------------------------**************************	
		Purpose 	: 	Create Agent after admin login
		Project 	:	Sales Contact DB	
	 	Developer 	: 	Kelvin
	 	Create Date : 	09/01/2016   
****-------------------------------------------------------------------************************/
session_start();
if(!isset($_SESSION['user_login']) and !isset($_COOKIE['cookie_login']))//session store admin name
{
	header("Location: index.php");//login in AdminLogin.php
}

require_once("includes/dbconnect.php");

$response['user_id'] = array();
$sql_sel = "select user_id from admin_user where user_group='Manager'";
$res_sel = mysqli_query($con, $sql_sel) or die(mysqli_error($con)."11");
$row_id=0;
if (mysqli_num_rows($res_sel) == '0'){	

}else 
{
   while ($res_rec = mysqli_fetch_assoc($res_sel)) {
      $response['user_id'][$row_id]=$res_rec['user_id'];
      $row_id++;
	}
}

$msg = '';

$submit = null;
if(isset($_POST['Submit'])) {
	$submit = $_POST['Submit'];
}
		
if($submit == "Create" )
{ 
	if($_POST['username'] == "")
	{
		$msg="Please enter User Id.";
	}
	elseif($_POST['pw'] == "")
	{
		$msg="Please enter a password.";
	}	
	elseif($_POST['pw'] != $_POST['rpw'])
	{
		$msg="The passwords you entered did not match.";
	}
	else
	{
		$sql_sel = "select * from admin_user where user_id='".$_POST['username']."'";
		$res_sel = mysqli_query($con, $sql_sel) or die(mysqli_error($con)."11");
		if(mysqli_num_rows($res_sel) != 0)
		{
		?>
		<script type="text/javascript">
		
		window.alert('This User Id already present.');
		
		</script>
		<?php
		}
		else
		{
			
			$sql_ins = "insert into admin_user
			(
			user_id,
			password,
			e_mail,			
			user_group,
			phone,
			google_calendar_iframe,
			google_map_api,
			marketing_email			
			) 
			values
			 (
			 '".trim($_POST['username'])."',
			 '".trim($_POST['pw'])."',
			 '".trim($_POST['e_mail'])."',
			 '".trim($_POST['user_group'])."',
			 '".trim($_POST['phone'])."',
			 '".mysqli_real_escape_string($con, trim($_POST['google_calendar_iframe']))."',
			 '".mysqli_real_escape_string($con, trim($_POST['google_map_api']))."',
			 '".trim($_POST['marketing_email'])."'	 
			 )";
			mysqli_query($con, $sql_ins) or die(mysqli_error($con));
			$msg="The user is added succesfully";
			
			// insert action logs
			$log = 'User'.' for '.$_POST['username'].' was added by '.$_SESSION['user_login'];	
			$type = 'User';
			$fa_icon = 'fa-user';
			$rid='';
			$url='changecredential.php?uid='.trim($_POST['username']);				
			$sql_log = sprintf("insert into log_info (log_data,log_time,agent,log_type,customer_id,url,fa_icon) values ('%s',sysdate(),'%s','%s','%d','%s','%s')",mysqli_real_escape_string($con, $log),$_SESSION['user_login'],$type,$rid,$url,$fa_icon);
			mysqli_query($con, $sql_log) or die(mysqli_error($con));	
		
			// insert user into aut_dial_status_info
			/*$sql_ins = sprintf("insert into auto_dial_status_info(user_id,auto_status) values ('%s','%d')",trim($_POST['username']),0);
			mysqli_query($con, $sql_ins) or die(mysqli_error($con));*/
			//$msg="User is created successfully.";				 				
		}
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
        		<div class="container">
        			<br>
        			<div class="row mobile-row">
        				<div class="col-sm-12">
        					<center>
        						<h1>Add User</h1>
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
        												<input name="username" id="username" class="form-control  my-form-control" style="display:inline-block;width:55%" type="text"    value="<?php if(isset($_POST['username'])){echo $_POST['username'];}?>" />
        											</center>
        										</div>
        									</div>
        									<div class="row mobile-row">
        										<div class="form-group" >
        											<center>
        												<label style="width:40%;text-align:left" for="pw">Password</label>
        												<input name="pw" id="pw" class="form-control  my-form-control" autocomplete='off'  style="display:inline-block;width:55%" type="password"  value="<?php if(isset($_POST['pw'])){echo $_POST['pw'];}?>"/>
        											</center>
        										</div>
        									</div>
        									<div class="row mobile-row">
        										<div class="form-group" >
        											<center>
        												<label style="width:40%;text-align:left" for="rpw">Confirm Password</label>
        												<input name="rpw" id="rpw" class="form-control  my-form-control" autocomplete='off'  style="display:inline-block;width:55%" type="password" value="<?php if(isset($_POST['rpw'])){echo $_POST['rpw'];}?>"/>
        											</center>
        										</div>
        									</div>                    				
        	            					
        									<div class="row mobile-row">
        										<div class="form-group" >
        											<center>
        												<label style="width:40%;text-align:left" for="e_mail">Pesonal Email</label>
        												<input name="e_mail" id="e_mail" class="form-control  my-form-control" style="display:inline-block;width:55%" type="text" value="<?php if(isset($_POST['e_mail'])){echo $_POST['e_mail'];}?>"/>
        											</center>
        										</div>
        									</div>
        									<div class="row mobile-row">
        										<div class="form-group" >
        											<center>
        												<label style="width:40%;text-align:left" for="phone">Pesonal Phone</label>
        												<input name="phone" id="phone" class="form-control  my-form-control" style="display:inline-block;width:55%" type="text" value="<?php if(isset($_POST['phone'])){echo $_POST['phone'];}?>"/>
        											</center>
        										</div>
        									</div>
        									<div class="row mobile-row">
        										<div class="form-group" >
        											<center>
        												<label style="width:40%;text-align:left" for="google_map_api">Google MAP API</label>
        												<input name="google_map_api" id="google_map_api" class="form-control  my-form-control" style="display:inline-block;width:55%" type="text" value="<?php if(isset($_POST['google_map_api'])){echo $_POST['google_map_api'];}?>"/>
        											</center>
        										</div>
        									</div>
        									<div class="row mobile-row">
        										<div class="form-group" >
        											<center>
        												<label style="width:40%;text-align:left" for="google_calendar_iframe">Google Calendar Iframe</label>
        												<textarea class="form-control" cols="40" rows="5"  id="google_calendar_iframe" maxlength="2500" name="google_calendar_iframe" style="display:inline-block;width:55%"><?php if(isset($_POST['google_calendar_iframe'])){echo $_POST['google_calendar_iframe'];}?></textarea>
        											</center>
        										</div>
        									</div>
        									
        								
        	                                                                
        									<!--<div class="row mobile-row">
        										<div class="form-group" >
        											<center>
        												<label style="width:40%;text-align:left" for="tw_account_sid">Twilio Account SID</label>
        												<input name="tw_account_sid" id="tw_account_sid" class="form-control  my-form-control" style="display:inline-block;width:55%" type="text"    value="<?php if(isset($_POST['tw_account_sid'])){echo $_POST['tw_account_sid'];}?>" />
        											</center>
        										</div>
        									</div>
        									<div class="row mobile-row">
        										<div class="form-group" >
        											<center>
        												<label style="width:40%;text-align:left" for="tw_auth_token">Twilio Account Token</label>
        												<input name="tw_auth_token" id="tw_auth_token" class="form-control  my-form-control" style="display:inline-block;width:55%" type="text"    value="<?php if(isset($_POST['tw_auth_token'])){echo $_POST['tw_auth_token'];}?>" />
        											</center>
        										</div>
        									</div>
        									<div class="row mobile-row">
        										<div class="form-group" >
        											<center>
        												<label style="width:40%;text-align:left" for="tw_number">Twilio Phone Number</label>
        												<input name="tw_number" id="tw_number" class="form-control  my-form-control" style="display:inline-block;width:55%" type="text"    value="<?php if(isset($_POST['tw_number'])){echo $_POST['tw_number'];}?>" />
        											</center>
        										</div>
        									</div>
        									<div class="row mobile-row">
        										<div class="form-group" >
        											<center>
        												<label style="width:40%;text-align:left" for="tw_app_sid">TWIML APP SID</label>
        												<input name="tw_app_sid" id="tw_app_sid" class="form-control  my-form-control" style="display:inline-block;width:55%" type="text"    value="<?php if(isset($_POST['tw_app_sid'])){echo $_POST['tw_app_sid'];}?>" />
        											</center>
        										</div>
        									</div>-->
        									<div class="row mobile-row">
        										<div class="form-group" >
        											<center>
        												<label style="width:40%;text-align:left" for="marketing_email">Marketing Email</label>
        												<input name="marketing_email" id="marketing_email" class="form-control  my-form-control" style="display:inline-block;width:55%" type="text"    value="<?php if(isset($_POST['marketing_email'])){echo $_POST['marketing_email'];}?>" />
        											</center>
        										</div>
        									</div>
        									
        	            					<div class="row mobile-row">
        										<div class="form-group" >
        											<center>
        												<label style="width:40%;text-align:left" for="user_group">Group</label>
        												<select class='form-control my-form-control' style="display:inline-block;width:55%"  name="user_group" id="user_group" size="1">
        							                    	<option value="Super User">Full Access</option>
        							                    	<option value="Admin">Limit Access</option>
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
        			                			<button class='form-control btn btn-primary submit-button' type='submit' name="Submit" value="Create">Create</button>
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