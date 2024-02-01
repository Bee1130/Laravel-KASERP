<?php
//phpinfo();
/* * **-------------------------------------------------------------------**************************    

  Purpose     : 	Buyer Information Detail Page

  Project 	:	Sales Supplier DB

  Developer 	: 	Wilson Tan

  Create Date : 	05/10/2016

 * ***-------------------------------------------------------------------*********************** */
session_start();
if (!isset($_SESSION['user_login']) and ! isset($_COOKIE['cookie_login'])) {//session store admin name
    header("Location: index.php"); //login in AdminLogin.php
}
require_once("includes/dbconnect.php");
	
$submit = null;
if(isset($_POST['Submit'])) {
	$submit = $_POST['Submit'];
}  	

if ($submit  == "Save") 
{
   
    
    $_POST['phone_number'] = my_phone_format($_POST['phone_number']);
   
    
    $sql_ins = sprintf("insert into suppliers_info (name,phone_number,email,address,agent,cust_upd_dt) values ('%s','%s','%s','%s','%s',sysdate())",mysqli_real_escape_string($con,$_POST['name']), $_POST['phone_number'], mysqli_real_escape_string($con,$_POST['email']), mysqli_real_escape_string($con,$_POST['address']),$_SESSION['user_login']);   
    mysqli_query($con,$sql_ins) or die(mysqli_error($con));
  
  	$auto_id = mysqli_insert_id($con);
  	
  	$Destination = 'userprofile/userfiles/avatars';
    if(!isset($_FILES['ImageFile']) || !is_uploaded_file($_FILES['ImageFile']['tmp_name'])){
        $NewImageName= 'default.jpg';
        move_uploaded_file($_FILES['ImageFile']['tmp_name'], "$Destination/$NewImageName");
    }
    else{
        $RandomNum   = rand(0, 9999999999);
        $ImageName = str_replace(' ','-',strtolower($_FILES['ImageFile']['name']));
        $ImageType = $_FILES['ImageFile']['type'];
        $ImageExt = substr($ImageName, strrpos($ImageName, '.'));
        $ImageExt = str_replace('.','',$ImageExt);
        $ImageName = preg_replace("/\.[^.\s]{3,4}$/", "", $ImageName);
        $NewImageName = $ImageName.'-'.$RandomNum.'.'.$ImageExt;
        move_uploaded_file($_FILES['ImageFile']['tmp_name'], "$Destination/$NewImageName");
    }
    
    $sql_upd_avatar="UPDATE suppliers_info SET user_avatar='$NewImageName' WHERE auto_id = ".$auto_id;
    $sql_upd_res = mysqli_query($con,$sql_upd_avatar) or die(mysqli_error($con)); 
    
    // insert action logs into system_log_info
	$sql_log = sprintf("insert into system_log_info (action,agent,query,log_time) values ('%s','%s','%s',sysdate())","Insert new Supplier : supplier_new.php ",$_SESSION['user_login'],mysqli_real_escape_string($con,$sql_ins));
	mysqli_query($con,$sql_log) or die(mysqli_error($con));	
    
     // Save log
	saveLog($auto_id,'Supplier','Supplier','added');
	
    header("Location: suppliers.php");
    exit();
}


if ($submit  == "Cancel") {
    header("Location: suppliers.php");
    exit();
}	

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
    <head>
    	<?php include("header.php");?>      
		<script type="text/javascript" src="js/jquery.cookie.js"></script>
	  
    </head>
    <body>
        <script type="text/javascript" src="popcalendar.js"></script>
        <div class="container">
    	    <?php include("sidebar.php"); ?>
    	    <div class="main-content">
        		<div class="container">
        		    <?php include('menu.php'); ?>		
        			<div id="my-main-content">				
        				<br>
        				
        				<h2 style="margin-top:0px">&nbsp;&nbsp;Supplier</h2>
        				<!-- Main content -->
        				<div class="row">            		
                    		<div class="col-md-12 col-lg-12">   	
        			            <div class="panel panel-inverse" data-sortable-id="ui-general-1">
        			                <div class="panel-heading">
        			                	<h4 class="panel-title">
        							        <a data-toggle="collapse" data-target="#collapseMain" 
        							           href="#collapseMain" class="collapsed">Main</a>
        							    </h4>
        			                </div>
        			                <div id="collapseMain"  class="panel-collapse collapse in">
        			                	<form action="supplier_new.php" method="post" enctype="multipart/form-data" id="UploadForm">
        				                	<div class="panel-body" id="Main">	
        				                		<div class="row">
        				                			<div class="form-group">
        							                    <div  class="col-lg-5" style="max-width: 170px">
        							                        <div class="shortpreview">
        							                        	<label for="{{ form.uploadFile.for_label" class="control-label">Avatar</label>
        							                            <br> 
        							                            <img src="userprofile/userfiles/avatars/<?php 
        							                            					if (isset($recb['user_avatar'])) 
        							                            						echo $recb['user_avatar'];
        							                            					else 
        							                            						echo 'default.jpg';?>" alt="" class="img-thumbnail" style="max-width: 150px">
        							                            <input name="ImageFile" type="file" id="uploadFile"/>
        							                        </div>
        							                    </div>
        											</div>
        				                		</div>
        				                        <div class="row">
        				                            <div class="col-lg-5">
        				                                <div class="form-group">
        				                                    <label for="{{ form.name.for_label" class="control-label">Name</label>
        				                                    <input class="form-control  supplier-form-control target semi-bold" id="name" maxlength="100" name="name" type="text"  
        				                                    					value="<?php if (isset($_POST['name'])) {
        			                                                                    echo $_POST['name'];
        			                                                                } else if (isset($recb['name'])) {
        			                                                                    echo $recb['name'];
        			                                                                } ?>">
        				                                </div>
        				                            </div>
        				                            <div class="col-lg-3">
        				                                <div class="form-group">
        				                                    <label for="{{ form.phone_number.for_label" class="control-label">Phone</label>
        				                                    <input class="form-control  supplier-form-control target semi-bold" id="phone_number" maxlength="100" name="phone_number" type="text"  
        				                                    					value="<?php if (isset($_POST['phone_number'])) {
        			                                                                    echo my_phone_format2($_POST['phone_number']);
        			                                                                } else if (isset($recb['phone_number'])) {
        			                                                                    echo my_phone_format2($recb['phone_number']);
        			                                                                } ?>">
        				                                </div>
        				                            </div>
        				                            <div class="col-lg-4">
        				                                <div class="form-group">
        				                                    <label for="{{ form.email.for_label" class="control-label">Email</label>
        				                                    <input class="form-control  supplier-form-control target semi-bold" id="email" maxlength="100" name="email" type="text"  
        				                                    					value="<?php if (isset($_POST['email'])) {
        			                                                                    echo $_POST['email'];
        			                                                                } else if (isset($recb['email'])) {
        			                                                                    echo $recb['email'];
        			                                                                } ?>">
        				                                </div>
        				                            </div>
        				                        </div>
        										
        				                        <div class="row">
        			                                <div class="form-group">
        			                                    <label for="{{ form.address.for_label" class="control-label">Google Address</label>
        			                                    <textarea class="form-control  supplier-form-control target" cols="40" id="address" maxlength="2500" name="address" rows="5"><?php 
        																 if (isset($_POST['address'])) {
        		                                                                    echo $_POST['address'];
        		                                                                } else if (isset($recb['address'])) {
        		                                                                    echo $recb['address'];
        		                                                                } ?></textarea>
        			                                </div>
        				                        </div>
        					                    		                
        			                		</div>
        		                			<div class="panel-footer">
        		                				<button type="submit" id="save-main" name="Submit" value="Save" class="btn btn-sm  btn-primary">Save</button>
        		                				<button type="submit" id="cancel-main" name="Submit" value="Cancel" class="btn btn-sm  btn-default">Cancel</button> 
        								    </div>	
        							    </form>                
        			                </div>
        						</div>	
                    		</div>
                    		
                    	</div>
        			</div>
                </div>
            </div>
        </div>
    </body>
</html>