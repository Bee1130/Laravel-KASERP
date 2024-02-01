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
require_once("includes/MailChimp/inc/config.inc.php");
require_once("includes/MailChimp/inc/MCAPI.class.php");
  
	    	

if ($_POST['Submit'] == "Save") 
{
    
    $sql_ins = sprintf("insert into companies_info (name,shortname,type,address,city,state,zip_code,phone_number,email,website,fax_number,country,agent,cust_upd_dt) values ('%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s',sysdate())",mysqli_real_escape_string($con, $_POST['name']), mysqli_real_escape_string($con, $_POST['shortname']), mysqli_real_escape_string($con, $_POST['type']), mysqli_real_escape_string($con, $_POST['address']), mysqli_real_escape_string($con, $_POST['city']), mysqli_real_escape_string($con, $_POST['state']), mysqli_real_escape_string($con, $_POST['zip_code']), mysqli_real_escape_string($con, $_POST['phone_number']), mysqli_real_escape_string($con, $_POST['email']), mysqli_real_escape_string($con, $_POST['website']), mysqli_real_escape_string($con, $_POST['fax_number']), mysqli_real_escape_string($con, $_POST['country']), mysqli_real_escape_string($con, $_POST['agent']), mysqli_real_escape_string($con, $_POST['cust_upd_dt']));   
    mysqli_query($con, $sql_ins) or die(mysqli_error($con));
    
     $auto_id = mysqli_insert_id($con);
     
    // insert action logs into system_log_info
	$sql_log = sprintf("insert into system_log_info (action,agent,query,log_time) values ('%s','%s','%s',sysdate())","Insert new comapny : company_new.php ",$_SESSION['user_login'],mysqli_real_escape_string($con, $sql_ins));
	mysqli_query($con, $sql_log) or die(mysqli_error($con));	
    
    // Save log
	saveLog($auto_id,'Company','Company','added');
	
    header("Location: companies.php");
    exit();
}


if ($_POST['Submit'] == "Cancel") {
     header("Location: companies.php");
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
        <div class="container">
    	    <?php include("sidebar.php"); ?>
    	    <div class="main-content">
    	        <?php include("menu.php"); ?>
        		<div class="container">		
        			<div id="my-main-content">				
        				<br>
        				
        				<h2 style="margin-top:0px">&nbsp;&nbsp;New Company</h2>
        				<!-- Main content -->
        				<div class="row">            		
                    		<div class="col-md-6 col-lg-6">   	
        			            <div class="panel panel-inverse" data-sortable-id="ui-general-1">
        			            	<form method="POST" action="company_new.php">
        				               <div class="panel-heading" style="background: #555 !important;color: white">
        				                	<h4 class="panel-title">Main</h4>
        				                </div>
        				               <div class="panel-body" id="Main">
        			                        <div class="row">
        			                            <div class="col-lg-6">
        			                                <div class="form-group">
        			                                    <label for="name" class="control-label">Name</label>
        			                                    <input class="form-control target semi-bold" id="name" maxlength="20" name="name" type="text" 
        			                                    					value="<?php if (isset($_POST['name'])) {
        		                                                                    echo $_POST['name'];
        		                                                                } else if (isset($recb['name'])) {
        		                                                                    echo $recb['name'];
        		                                                                } ?>">
        			                                </div>
        			                            </div>
        			                            <div class="col-lg-2">
        			                                <div class="form-group"> 
        			                                    <label for="shortname" class="control-label">Abbr</label>
        			                                    <input class="form-control target semi-bold" id="shortname" maxlength="20" name="shortname" type="text" 
        			                                    					value="<?php if (isset($_POST['shortname'])) {
        		                                                                    echo $_POST['shortname'];
        		                                                                } else if (isset($recb['shortname'])) {
        		                                                                    echo $recb['shortname'];
        		                                                                } ?>">
        
        			                                </div>
        			                            </div>
        			                            <div class="col-lg-4">
        			                                <div class="form-group"> 
        			                                    <label for="{{ form.type.for_label" class="control-label">Type</label>
        			                                    <select class="form-control target semi-bold" id="type" name="type">
        													<option value=""></option>
        													<option value="Law Firm" <?php 
        																if ($_POST['type'] == 'Law Firm') {
        																		echo 'selected';
        																} else if ($recb['type'] == 'Law Firm') {
        																	echo 'selected';;
        																} ?>>Law Firm</option>
        													<option value="Transfer Agent" <?php 
        																if ($_POST['type'] == 'Transfer Agent') {
        																		echo 'selected';
        																} else if ($recb['type'] == 'Transfer Agent') {
        																	echo 'selected';;
        																} ?>>Transfer Agent</option>
        													<option value="Regulator" <?php 
        																if ($_POST['type'] == 'Regulator') {
        																		echo 'selected';
        																} else if ($recb['type'] == 'Regulator') {
        																	echo 'selected';;
        																} ?>>Regulator</option>
        												</select>
        			                                </div> 
        			                            </div>
        			                        </div>
        			                        <div class="row">
        			                            <div class="col-lg-8">
        			                                <div class="form-group"> 
        			                                    <label for="{{ form.address.for_label" class="control-label">Address</label>
        			                                    <textarea class="form-control target" cols="40" id="address" maxlength="200" name="address" rows="5"><?php 
        																 if (isset($_POST['address'])) {
        		                                                                    echo $_POST['address'];
        		                                                                } else if (isset($recb['address'])) {
        		                                                                    echo $recb['address'];
        		                                                                } ?></textarea>
        			                                </div>
        			                            </div>
        			                            <div class="col-lg-4">
        			                                <div class="form-group"> 
        			                                    <label for="{{ form.city.for_label" class="control-label">City</label>
        			                                    <input class="form-control target" id="city" maxlength="50" name="city" type="text" value="<?php 
        																 if (isset($_POST['city'])) {
        		                                                                    echo $_POST['city'];
        		                                                                } else if (isset($recb['city'])) {
        		                                                                    echo $recb['city'];
        		                                                                } ?>">
        			                                  
        			                                </div> 
        			                                <div class="form-group"> 
        			                                    <label for="state" class="control-label">State / Province / Region</label>
        			                                    <input class="form-control target" id="state" maxlength="50" name="state" type="text" value="<?php 
        																 if (isset($_POST['state'])) {
        		                                                                    echo $_POST['state'];
        		                                                                } else if (isset($recb['state'])) {
        		                                                                    echo $recb['state'];
        		                                                                } ?>">
        			                                </div> 
        			                            </div>
        			                        </div>
        			                        <div class="row">
        			                            <div class="col-lg-6">
        			                                <div class="form-group"> 
        			                                    <label for="zip_code" class="control-label">Postal Code</label>
        			                                    <input class="form-control target" id="zip_code" maxlength="50" name="zip_code" type="text" value="<?php 
        																 if (isset($_POST['zip_code'])) {
        		                                                                    echo $_POST['zip_code'];
        		                                                                } else if (isset($recb['zip_code'])) {
        		                                                                    echo $recb['zip_code'];
        		                                                                } ?>">
        			                                </div> 
        			                            </div>
        			                            <div class="col-lg-6">
        			                                <div class="form-group"> 
        			                                    <label for="country" class="control-label">Country</label>
        		                                     	<select class="form-control target" id="country" name="country">
        												<?php
        			                                    	$sql_sel_status = "select * from country_info";
                                                            $sql_res_status = mysqli_query($con, $sql_sel_status) or die(mysqli_error($con) . "go select error");
                                                            while ($sql_rec_status = mysqli_fetch_assoc($sql_res_status)) 
                                                            {
                                                            	$value = $sql_rec_status['value'];
                                                            	$text = $sql_rec_status['text'];
                                                            ?>
                                                            	<option value="<?php echo $text;?>" <?php 
        																if ($_POST['country'] == $text) {
        																		echo 'selected';
        																} else if ($recb['country'] == $text) {
        																	echo 'selected';
        																} ?>><?php echo $text;?></option>
                                                        	<?php
                                                            }
        			                                    ?>
        												</select>
        														
        			                                 
        			                                </div> 
        			                            </div>
        			                        </div>
        			                        <div class="row">
        			                            <div class="col-lg-6">
        			                                <div class="form-group"> 
        			                                    <label for="email" class="control-label">Email</label>
        			                                    <input class="form-control target" id="email" maxlength="254" name="email" type="email" value="<?php 
        																 if (isset($_POST['email'])) {
        		                                                                    echo $_POST['email'];
        		                                                                } else if (isset($recb['email'])) {
        		                                                                    echo $recb['email'];
        		                                                                } ?>">
        			                                </div> 
        			                            </div>
        			                            <div class="col-lg-6">
        			                                <div class="form-group"> 
        			                                    <label for="website" class="control-label">Website</label>
        			                                    <input class="form-control target" id="website" maxlength="100" name="website" type="text" value="<?php 
        																 if (isset($_POST['website'])) {
        		                                                                    echo $_POST['website'];
        		                                                                } else if (isset($recb['website'])) {
        		                                                                    echo $recb['website'];
        		                                                                } ?>">
        			                                </div> 
        			                            </div>
        			                        </div>
        			                        <div class="row">
        			                            <div class="col-lg-6">
        			                                <div class="form-group"> 
        			                                    <label for="phone_number" class="control-label">Phone</label>
        			                                    <input class="form-control target" id="phone_number" maxlength="50" name="phone_number" type="text" value="<?php 
        																 if (isset($_POST['phone_number'])) {
        		                                                                    echo my_phone_format2($_POST['phone_number']);
        		                                                                } else if (isset($recb['phone_number'])) {
        		                                                                    echo my_phone_format2($recb['phone_number']);
        		                                                                } ?>">
        			                                </div> 
        			                            </div>
        			                            <div class="col-lg-6">
        			                                <div class="form-group"> 
        			                                    <label for="fax_number" class="control-label">Fax</label>
        			                                     <input class="form-control target" id="fax_number" maxlength="50" name="fax_number" type="text" value="<?php 
        																 if (isset($_POST['fax_number'])) {
        		                                                                    echo my_phone_format2($_POST['fax_number']);
        		                                                                } else if (isset($recb['fax_number'])) {
        		                                                                    echo my_phone_format2($recb['fax_number']);
        		                                                                } ?>">
        		                                                                
        			                                    
        			                                </div> 
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
    </body>
</html>