<?php
/****-------------------------------------------------------------------**************************	
		Purpose 	: 	Create Lease after admin login
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
		$res_sel = mysqli_query($con,$sql_sel) or die(mysqli_error($con)."11");
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
			user_group
			) 
			values
			 (
			 '".$_POST['username']."',
			 '".$_POST['pw']."',
			 '".$_POST['emailid']."',
			 '".$_POST['user_group']."'
			 )";
			mysqli_query($con,$sql_ins) or die(mysqli_error($con));
			$msg="Add lease login is successed!";
			
						
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
        						<h1>Add Lease Login</h1>
        						<br>
        					</center>
        				</div>
        			</div>
        		    <div class='row'>
        		    	<div class='col-sm-4 col-md-offset-4'>
        		          <form name="default_emplate" id="default_emplate" method="post" enctype="multipart/form-data" onsubmit="return stepcheck();">
        		          	<div class='form-row'>
        		              <div class='col-xs-12 form-group' style="margin-left:0px;margin-right:0px">
        		                <label class='control-label'>User Group</label>
        		                <select class='form-control' name="user_group" size="1">
        	                       <option value="Finance">Lease</option>
        	                      <option value="Lease2">Lease2</option>
        						  <option value="Lease3">Lease3</option>
        						  <option value="Lease4">Lease4</option>              
        	                    </select>
        		                
        		              </div>
        		            </div>
        		            
        		          	<div class='form-row'>
        		              <div class='col-xs-12 form-group' style="margin-left:0px;margin-right:0px">
        		                <label class='control-label'><span class="glyphicon glyphicon-user"></span>User Id</label>
        		                <input name="username" class='form-control' type="text" id="username" class="textbox_small" value="<?php if(isset($_POST['username'])){echo $_POST['username'];}?>">		                
        		              </div>
        		  			</div>
        		  			
        		  			
        		  			<div class='form-row'>
        		              <div class='col-xs-12 form-group' style="margin-left:0px;margin-right:0px">
        		                <label class='control-label'><span class="glyphicon glyphicon-envelope"></span>User Email</label>
        		                <input name="emailid" type="email" id="emailid"  class='form-control' value="<?php if(isset($_POST['emailid'])){echo $_POST['emailid'];}?>">
        		     		  </div>
        		            </div>
        		  
        		  
        		            <div class='form-row'>
        		              <div class='col-xs-12 form-group' style="margin-left:0px;margin-right:0px">
        		                <label class='control-label'>New password</label>
        		                <input name="pw" autocomplete='off' type="password" id="pw" class='form-control' value="<?php if(isset($_POST['pw'])){echo $_POST['pw'];}?>">		                
        		              </div>
        		            </div>
        		            
        		            <div class='form-row'>
        		              <div class='col-xs-12 form-group' style="margin-left:0px;margin-right:0px">
        		                <label class='control-label'>Confirm password</label>
        		                <input name="rpw" autocomplete='off' type="password" id="rpw" class="form-control" value="<?php if(isset($_POST['rpw'])){echo $_POST['rpw'];}?>">
        		                
        		              </div>
        		            </div>    
        		           
        		            <div class='form-row'>
        		              <div class='col-xs-12 form-group' style="margin-left:0px;margin-right:0px">
        		                <button class='form-control btn btn-primary submit-button' type='submit' name="Submit" value="Create">Create</button>                
        		              </div>
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