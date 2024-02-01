<?php
//phpinfo();
/* * **-------------------------------------------------------------------**************************    

  Purpose     : 	Buyer Information Detail Page

  Project 	:	Sales Contact DB

  Developer 	: 	Wilson Tan

  Create Date : 	05/10/2016

 * ***-------------------------------------------------------------------*********************** */
session_start();
if (!isset($_SESSION['user_login']) and ! isset($_COOKIE['cookie_login'])) {//session store admin name
    header("Location: index.php"); //login in AdminLogin.php
}
require_once("includes/dbconnect.php");
	
$msg = '';

$submit = null;
if(isset($_POST['Submit'])) {
	$submit = $_POST['Submit'];
} 

$contact_type = null;

if(isset($_POST['contact_type'])) {
	$contact_type = $_POST['contact_type'];
}

if ($submit == "Save") 
{
   
    
    $_POST['hm_ph'] = my_phone_format($_POST['hm_ph']);
   
    
    $sql_ins = sprintf("insert into contacts_info (name,hm_ph,email,address,agent,cust_upd_dt,landline,policy_number,national_ins,utr,bank_details,contact_type) values ('%s','%s','%s','%s','%s',sysdate(),'%s','%s','%s','%s','%s','%s')",mysqli_real_escape_string($con,$_POST['name']), $_POST['hm_ph'], mysqli_real_escape_string($con,$_POST['email']), mysqli_real_escape_string($con,$_POST['address']),$_SESSION['user_login'], mysqli_real_escape_string($con,$_POST['landline']), mysqli_real_escape_string($con,$_POST['policy_number']), mysqli_real_escape_string($con,$_POST['national_ins']), mysqli_real_escape_string($con,$_POST['utr']), mysqli_real_escape_string($con,$_POST['bank_details']), mysqli_real_escape_string($con,$_POST['contact_type']));   
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
    
    $sql_upd_avatar="UPDATE contacts_info SET user_avatar='$NewImageName' WHERE auto_id = ".$auto_id;
    $sql_upd_res = mysqli_query($con, $sql_upd_avatar) or die(mysqli_error($con)); 
    
    // insert action logs into system_log_info
	$sql_log = sprintf("insert into system_log_info (action,agent,query,log_time) values ('%s','%s','%s',sysdate())","Insert new Contact : contact_new.php ",$_SESSION['user_login'],mysqli_real_escape_string($con,$sql_ins));
	mysqli_query($con, $sql_log) or die(mysqli_error($con));	
    
     // Save log
	saveLog($auto_id,'Contact',mysqli_real_escape_string($con,$_POST['contact_type']).' Contact','added');
	
    header("Location: contacts.php");
    exit();
}




if ($submit == "Cancel") {
    header("Location: contacts.php");
    exit();
}	

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
    <head>
    	<?php include("header.php");?>      
		<script type="text/javascript" src="js/jquery.cookie.js"></script>
		<style type="text/css">
		    .btn {
		            color: green;
    background-color: #efece4 !important;
    padding-left: 20px;
    padding-right: 20px;
    border-radius: 5px;
		    }
		    .btn:hover {
		            color: orange;
    background-color: #efece4 !important;
    padding-left: 20px;
    padding-right: 20px;
    border-radius: 5px;
		    }
		</style>
	  
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
        				
        				<!-- Main content -->
        				<div class="row" style="max-width: 600px;margin:auto">            		
                    		<div class="col-md-12 col-lg-12">   	
        			            <div class="panel panel-inverse" data-sortable-id="ui-general-1">
        			                <div class="panel-heading">
        			                	<h4 class="panel-title">
        							        <a data-toggle="collapse" data-target="#collapseMain" 
        							           href="#collapseMain" class="collapsed">Main</a>
        							    </h4>
        			                </div>
        			                <div id="collapseMain"  class="panel-collapse collapse in" style="background-color: #f5f3ef">
        			                	<form action="contact_new.php" method="post" enctype="multipart/form-data" id="UploadForm" style="background-color: #f5f3ef">
        				                	<div class="panel-body" id="Main" style="background-color: #f5f3ef !important">	
        				                		<div class="row">
        				                			<div class="form-group" style="max-width: 170px;margin: auto">
        							                    <div  class="col-lg-12">
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
        				                            <div class="col-lg-6">
        				                                <div class="form-group">
        				                                    <label for="{{ form.name.for_label" class="control-label">Name</label>
        				                                    <input class="form-control  contact-form-control target semi-bold" id="name" maxlength="100" name="name" type="text"  
        				                                    					value="<?php if (isset($_POST['name'])) {
        			                                                                    echo $_POST['name'];
        			                                                                } else if (isset($recb['name'])) {
        			                                                                    echo $recb['name'];
        			                                                                } ?>">
        				                                </div>
        				                            </div>
        				                             <div class="col-lg-6">
        				                                <div class="form-group">
        				                                    <label for="{{ form.contact_type.for_label" class="control-label">Name</label>
        				                                    <select id="contact_type" name="contact_type"   class="form-control  contact-form-control target semi-bol" >						                            	
        			                            				<option value="Client" <?php if($contact_type=="Client"){echo 'selected';}
        						                            							else if(isset($recb['contact_type'])) {
																							if($recb['contact_type']=="Client")
        						                            									{echo 'selected';}
																						}
																						
        						                            							?>>Client</option>	
        														<option value="Contractor" <?php if($contact_type=="Contractor"){echo 'selected';}
        						                            							else  if(isset($recb['contact_type'])) {
																						if($recb['contact_type']=="Contractor")
        						                            									{echo 'selected';}
																						}
        						                            							?>>Contractor</option>
        					                                       
        					                                    <option value="New" <?php if($contact_type=="New"){echo 'selected';}
        						                            							else  if(isset($recb['contact_type'])) {
																						if($recb['contact_type']=="New")
        						                            									{echo 'selected';}
																						}
        						                            							?>>New</option>						                                
        						                            	<option value="Other" <?php if($contact_type=="Other"){echo 'selected';}
        						                            							else  if(isset($recb['contact_type'])) {
																						if($recb['contact_type']=="Other")
        						                            									{echo 'selected';}
																						}
        						                            							?>>Other</option>						             
        					                                </select>
        				                                </div>
        				                            </div>
        					                    </div>
        				                        <div class="row">
        				                            <div class="col-lg-6">
        				                                <div class="form-group">
        				                                    <label for="{{ form.hm_ph.for_label" class="control-label">Mobile</label>
        				                                    <input class="form-control  contact-form-control target semi-bold" id="hm_ph" maxlength="100" name="hm_ph" type="text"  
        				                                    					value="<?php if (isset($_POST['hm_ph'])) {
        			                                                                    echo my_phone_format2($_POST['hm_ph']);
        			                                                                } else if (isset($recb['hm_ph'])) {
        			                                                                    echo my_phone_format2($recb['hm_ph']);
        			                                                                } ?>">
        				                                </div>
        				                            </div>
        				                            <div class="col-lg-6">
        				                                <div class="form-group">
        				                                    <label for="{{ form.email.for_label" class="control-label">Email</label>
        				                                    <input class="form-control  contact-form-control target semi-bold" id="email" maxlength="100" name="email" type="text"  
        				                                    					value="<?php if (isset($_POST['email'])) {
        			                                                                    echo $_POST['email'];
        			                                                                } else if (isset($recb['email'])) {
        			                                                                    echo $recb['email'];
        			                                                                } ?>">
        				                                </div>
        				                            </div>
        				                        </div>
        										<div class="row">
        				                        	<div class="col-lg-6">
        		                                        <div class="form-group">
        		                                            <label for="{{ form.landline.for_label" class="control-label">Land Line</label>
        		                                            <input class="form-control  contact-form-control target" id="landline" maxlength="50" name="landline" type="text" value="<?php 
        															 if (isset($_POST['landline'])) {
        	                                                                    echo $_POST['landline'];
        	                                                                } else if (isset($recb['landline'])) {
        	                                                                    echo $recb['landline'];
        	                                                                } ?>">
        		                                        </div>
        		                                    </div>
        		                                    <div class="col-lg-6">
        		                                        <div class="form-group">
        		                                            <label for="{{ form.policy_number.for_label" class="control-label">Liability Insurance Policy Number</label>
        		                                            <input class="form-control  contact-form-control target" id="policy_number" maxlength="50" name="policy_number" type="text" value="<?php 
        															 if (isset($_POST['policy_number'])) {
        	                                                                    echo $_POST['policy_number'];
        	                                                                } else if (isset($recb['policy_number'])) {
        	                                                                    echo $recb['policy_number'];
        	                                                                } ?>">
        		                                        </div>
        		                                    </div>
        		                                </div>
        										<div class="row">
        				                        	<div class="col-lg-6">
        		                                        <div class="form-group">
        		                                            <label for="{{ form.national_ins.for_label" class="control-label">National Ins</label>
        		                                            <input class="form-control  contact-form-control target" id="national_ins" maxlength="50" name="national_ins" type="text" value="<?php 
        															 if (isset($_POST['national_ins'])) {
        	                                                                    echo $_POST['national_ins'];
        	                                                                } else if (isset($recb['national_ins'])) {
        	                                                                    echo $recb['national_ins'];
        	                                                                } ?>">
        		                                        </div>
        		                                    </div>
        		                                    <div class="col-lg-6">
        		                                        <div class="form-group">
        		                                            <label for="{{ form.utr.for_label" class="control-label">Website</label>
        		                                            <input class="form-control  contact-form-control target" id="utr" maxlength="50" name="utr" type="text" value="<?php 
        															 if (isset($_POST['utr'])) {
        	                                                                    echo $_POST['utr'];
        	                                                                } else if (isset($recb['utr'])) {
        	                                                                    echo $recb['utr'];
        	                                                                } ?>">
        		                                        </div>
        		                                    </div>
        		                                </div>
        		                               	<div class="row">
        				                        	 <div class="col-lg-12">
        		                                        <div class="form-group">
        		                                            <label for="{{ form.bank_details.for_label" class="control-label">Bank Details</label>
        		                                            <input class="form-control  contact-form-control target" id="bank_details" maxlength="50" name="bank_details" type="text" value="<?php 
        															 if (isset($_POST['bank_details'])) {
        	                                                                    echo $_POST['bank_details'];
        	                                                                } else if (isset($recb['bank_details'])) {
        	                                                                    echo $recb['bank_details'];
        	                                                                } ?>">
        		                                        </div>
        		                                    </div>
        		                                </div>
        				                      	<div class="row">
        				                        	 <div class="col-lg-12">
        				                                <div class="form-group">
        				                                    <label for="{{ form.address.for_label" class="control-label">Google Address</label>
        				                                    <textarea class="form-control  contact-form-control target" cols="40" id="address" maxlength="2500" name="address" rows="2"><?php 
        																	 if (isset($_POST['address'])) {
        			                                                                    echo $_POST['address'];
        			                                                                } else if (isset($recb['address'])) {
        			                                                                    echo $recb['address'];
        			                                                                } ?></textarea>
        				                                </div>
        					                        </div>
        		                                   
        		                                </div>
        					                    		                
        			                		</div>
        		                			<div class="panel-footer" style="background-color: #f5f3ef">
        		                				<button type="submit" id="save-main" name="Submit" value="Save" class="btn ">Save</button>
        		                				<button type="submit" id="cancel-main" name="Submit" value="Cancel" class="btn ">Cancel</button> 
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