<?php
/****-------------------------------------------------------------------**************************	
		Purpose 	: 	Add Drip Campaign Messages
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

/*$response['user_id'] = array();
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
}*/

/* Save Drip Campaign Text */		
if($_POST['Submit'] == "Save" )
{ 
	/* update user campaign status */	
	$sql_up = sprintf("update drip_campaign_user_info set user_campaign_status='%s' where user='%s'",$_REQUEST['drip'],$_SESSION['user_login']);
	$res_sql = mysqli_query($con, $sql_up) or die(mysqli_error($con) . "go select error");
	$_SESSION['user_drip_campaign_status'] = $_REQUEST['drip'];
	
	/* save drip campaign text */
	$sql_del = sprintf("delete from drip_campaign_text_info where priority_opt = '%s' and language ='%s'",$_REQUEST['priority_opt'],$_REQUEST['language']);
	$res_sql = mysqli_query($con, $sql_del) or die(mysqli_error($con) . "go select error");
	
	$cnt = count($_POST['day']);
	for ($i=0;$i<count($_POST['day']); $i++)
	{
		if (isset($_POST['day'][$i]) and $_POST['day'][$i] != "")
		{
			$sql_ins = sprintf("insert into drip_campaign_text_info (priority_opt,day,sms_or_email,message,language) values ('%s','%s','%s','%s','%s')",$_POST['priority_opt'],$_POST['day'][$i],$_POST['sms_or_email'][$i],$_POST['message'][$i],$_POST['language']);
			$res_sel =  mysqli_query($con, $sql_ins) or die(mysqli_error($con) . "go select error");	
		}
	}
}

/* Get Campaign Priority */
$response = array();
$response['priority_opt'] = array();
$drip_prio_sql = "select priority_opt from drip_campaign_text_info group by priority_opt ";
$result_drip_prio_sql = mysqli_query($con, $drip_prio_sql) or die(mysqli_error($con));
$row_id=0;

while ($seerec = mysqli_fetch_assoc($result_drip_prio_sql)) {
	
    if ($seerec['priority_opt'] != '') 
      	$response['priority_opt'][$row_id]=$seerec['priority_opt'];
    else 
      	$response['priority_opt'][$row_id]="&nbsp;";      	
    $row_id++;
}

?>
<!DOCTYPE HTML>
<html>
    <head>
        <?php include("header.php");?>
        <style type="text/css">
        	@media screen and (max-width: 768px) {
			   .drip-campaign-text-table{
			   		height:350px;			   		
			   		overflow-y: scroll;
			   }		
			   .drip-campaign-width{
			   		width:100%;
			   }				       
			}
			@media screen and (min-width: 768px) {
			   .drip-campaign-text-table{
			   		height:600px;
			   		overflow-y: scroll;
			   		
			   }	
			    .drip-campaign-width{
			   		width:98%;
			   }				       
			}	
			.drip-campaign-text-table-td{
				vertical-align: middle !important;
				padding:1px;
			}	
			.drip-campaign-text-table-input{
				width: 100%;
			    outline: none;
			    border: 0px;
			    text-align: center;	
			}				
        </style>
        <script type="text/javascript">       	
			
			/**
			* whenever priority_opt is changed, the msg_body should be changed 
			*/
			function ChangePriority()
			{
				console.log("change agent");				
				document.getElementById("btnPriority").click();				
			}
			
			function ChangeLanguage()
			{
				console.log("change agent");				
				document.getElementById("btnPriority").click();				
			}
			
        </script>
    </head>
    <body>
		
		<div class="container">
    	    <?php include("sidebar.php"); ?>
    	    <div class="main-content">
    	        <?php include("menu.php"); ?>
        		<div class="container">
        			<center>
        				<br>
        				<div class="row mobile-row">
        					<div class="col-sm-12">
        						<center>
        							<h1>Drip Campaign</h1>
        							<br>
        						</center>
        					</div>
        				</div>
        				<br>
        				<form method="post" enctype="multipart/form-data" action="adddripmessage.php">
        					<div class='row mobile-row drip-campaign-width'>
        						
        						<div class="col-sm-3  form-group" >
        							<div class="col-sm-4 col-xs-4 form-group" >
        								<label class='control-label'>Drip</label>	
        								
        							</div>
        							<div class="col-sm-8 col-xs-8 form-group" >
        								
        								<select class="form-control  my-form-control"  name="drip" id="drip" size="1">
        									<option value="Yes" <?php 
        										if ($_POST['drip'] == "Yes") {
        												echo 'selected';
        										} else if ($recb['drip'] == "Yes") {
        											echo 'selected';
        										} ?>>Yes</option>
        									<option value="No" <?php if ($_POST['drip'] == "No") {
        											echo 'selected';
        										} else if ($recb['drip'] == "No") {
        											echo 'selected';
        										} ?>>No</option>	
        								</select>                     
        							</div>    
        						</div>
        						<div class="col-sm-4  form-group" >
        		      				<div class="col-sm-4 col-xs-4 form-group" >
        			      				<label class='control-label'>Priority</label>						
        							</div>
        							<div class="col-sm-8 col-xs-8 form-group" >
        								<select class="form-control  my-form-control" name="priority_opt" id="priority_opt" onchange="javascript:ChangePriority();" size="1">							
        									<option value="New" <?php 
        										if ($_POST['priority_opt'] == "New") {
        												echo 'selected';
        										} else if ($recb['priority_opt'] == "New") {
        											echo 'selected';
        										} ?>>New</option>
        									<option value="Hot" <?php if ($_POST['priority_opt'] == "Hot") {
        											echo 'selected';
        										} else if ($recb['priority_opt'] == "Hot") {
        											echo 'selected';
        										} ?>>Hot</option>
        									<option value="Credit Repair" <?php if ($_POST['priority_opt'] == "Credit Repair") {
        											echo 'selected';
        										} else if ($recb['priority_opt'] == "Credit Repair") {
        											echo 'selected';
        										} ?>>Credit Repair</option>
        									<option value="Credit Ready" <?php if ($_POST['priority_opt'] == "Credit Ready") {
        											echo 'selected';
        										} else if ($recb['priority_opt'] == "Credit Ready") {
        											echo 'selected';
        										} ?>>Credit Ready</option>
        									<option value="Pre-Approved" <?php if ($_POST['priority_opt'] == "Pre-Approved") {
        											echo 'selected';
        										} else if ($recb['priority_opt'] == "Pre-Approved") {
        											echo 'selected';
        										} ?>>Pre-Approved</option>
        									<option value="Doc Sent" <?php if ($_POST['priority_opt'] == "Doc Sent") {
        											echo 'selected';
        										} else if ($recb['priority_opt'] == "Doc Sent") {
        											echo 'selected';
        										} ?>>Doc Sent</option>
        									<option value="Funded" <?php if ($_POST['priority_opt'] == "Funded") {
        											echo 'selected';
        										} else if ($recb['priority_opt'] == "Funded") {
        											echo 'selected';
        										} ?>>Funded</option>
        
        									<option value="Clients" <?php if ($_POST['priority_opt'] == "Clients") {
        											echo 'selected';
        										} else if ($recb['priority_opt'] == "Clients") {
        											echo 'selected';
        										} ?>>Clients</option>
        									<option value="Partners" <?php if ($_POST['priority_opt'] == "Partners") {
        											echo 'selected';
        										} else if ($recb['priority_opt'] == "Partners") {
        											echo 'selected';
        										} ?>>Partners</option>
        									<option value="Inactive" <?php if ($_POST['priority_opt'] == "Inactive") {
        											echo 'selected';
        										} else if ($recb['priority_opt'] == "Inactive") {
        											echo 'selected';
        										} ?>>Inactive</option>
        									<option value="Not Interested" <?php if ($_POST['priority_opt'] == "Not Interested") {
        											echo 'selected';
        										} else if ($recb['priority_opt'] == "Not Interested") {
        											echo 'selected';
        										} ?>>Not Interested</option>
        								</select>         
        								<button type="submit" style="display:none" class="btn btn-primary" name="Submit" id="btnPriority" value="btnPriority"></button> 
        							</div>
        						</div>
        						<div class="col-sm-3  form-group" >
        							<div class="col-sm-5 col-xs-4 form-group" >
        								<label class='control-label'>Language</label>		
        							</div>
        							<div class="col-sm-7 col-xs-8 form-group" >								
        								<select class="form-control  my-form-control"  name="language" id="language" onchange="javascript:ChangeLanguage();"size="1">											<option value="English" <?php 
        										if ($_POST['language'] == "English") {
        												echo 'selected';
        										} else if ($recb['language'] == "English") {
        											echo 'selected';
        										} ?>>English</option>
        									<option value="Spanish" <?php if ($_POST['language'] == "Spanish") {
        											echo 'selected';
        										} else if ($recb['language'] == "Spanish") {
        											echo 'selected';
        										} ?>>Spanish</option>	
        									<option value="VN" <?php if ($_POST['language'] == "VN") {
        											echo 'selected';
        										} else if ($recb['language'] == "VN") {
        											echo 'selected';
        										} ?>>VN</option>										
                                    	</select>		                                    	                    
        							</div>    
        						</div>
        						<div class="col-sm-2 form-group"  align="center">	
        							<div class="col-sm-8 col-xs-offset-2 col-xs-8">
        								<button class='form-control btn btn-primary submit-button' type='submit' name="Submit" id="btnAddMsg" value="Save">Save</button>                	
        							</div>
        		              	</div>							
        						
        		            </div>	
        		            <br>
        				    <div class='row mobile-row drip-campaign-width'>
        						<div class="table-responsive drip-campaign-text-table ">
        							<table class="table" >
        			    				<thead>
        									<tr>
        										<th width="10%" style="padding:1px;text-align:center">Day</th>
        		                               	<th  width="10%" style="padding:1px;text-align:center">SMS or Email</th>
        		                               	<th style="padding:1px;text-align:center">Message</th>
        		                               <!--	<th  width="10%" style="padding:1px;text-align:center">Language</th>		-->		                               	
        									</tr>
        								</thead>
        								<tbody>
        								<?php 												
        									 $filter = "where 1=1";
        									 if (isset($_REQUEST['priority_opt']) and ($_REQUEST['priority_opt']!=""))
        									 	$filter .= " and priority_opt = '".trim($_REQUEST['priority_opt'])."'";
        									 
        									 if (isset($_REQUEST['language']) and ($_REQUEST['language']!=""))
        									 	$filter .= " and language = '".trim($_REQUEST['language'])."'";
        									 	
        								   	 $filter .= " ORDER BY auto_id";
        								   	 $sql_sel = "select * from drip_campaign_text_info $filter"; 
        								   	 
        									 $res_sel = mysqli_query($con, $sql_sel) or die(mysqli_error($con));												
        									
        									 $i=0;
        									 while ($i<25) 
        									 {			
        									   $response = mysqli_fetch_assoc($res_sel);
        									   
        									?>
        								   	  	<tr>
        									   	   <!--	<td  align="center">
        									   	  	    <input type="text"  style=" <?php if (isset($response) and isset($response['priority_opt']) and $response['opportunity']!='') echo 'border:0px;';?>" readonly/>
        									   	  	</td>     -->                                           
        		                                    <td width="10%"  class="drip-campaign-text-table-td">
        		                                   	 	<input type="text" name="day[]" maxlength="5" id="day[]" value="<?php 
        										   	  	 	if (($response!=false) and isset($response['day'])) {
        		                                            	echo $response['day'];
        		                                         	}else {
        		                                                    echo '';
        		                                            }?>" class="drip-campaign-text-table-input"/>	       
        		                                 	</td>
        		                                 	<td width="10%"  class="drip-campaign-text-table-td">
        		                                   	 	<select size="1"  style="padding-left:0px;padding-right:0px;padding-bottom:4px;"  name="sms_or_email[]" id="sms_or_email[]"  class="drip-campaign-text-table-input">                                                                                    
        									   	  	    	<option <?php if (($response!=false) and $response['language'] == 'SMS') { ?> selected <?php } ?> value="SMS">SMS</option>
        		                                            <option <?php if (($response!=false) and $response['language'] == 'Email') { ?> selected <?php } ?> value="Email">Email</option>		                                            
        		                                    	</select>  		                                    
        		                                 	</td>
        		                                 	<td  class="drip-campaign-text-table-td">
        		                                   	 	<textarea name="message[]" id="message[]" class="drip-campaign-text-table-input"><?php 
        										   	  	 	if (($response!=false) and isset($response['message'])) {
        		                                            	echo $response['message'];
        		                                         	}else {
        		                                                    echo '';
        		                                            }?></textarea>
        		                                 	</td>
        		                                 	<!--<td width="10%"  class="drip-campaign-text-table-td">
        									   	  	   <select size="1"  style="padding-left:0px;padding-right:0px;padding-bottom:4px;"  name="language[]" id="language[]"  class="drip-campaign-text-table-input">
        									   	  	    	<option <?php if (($response!=false) and $response['language'] == '') { ?> selected <?php } ?> value=""></option>
        		                                            <option <?php if (($response!=false) and $response['language'] == 'English') { ?> selected <?php } ?> value="English">English</option>
        		                                            <option <?php if (($response!=false) and $response['language'] == 'Spanish') { ?> selected <?php } ?> value="Spanish">Spanish</option>
        		                                            <option <?php if (($response!=false) and $response['language'] == 'VN') { ?> selected <?php } ?> value="VN">VN</option>
        		                                    	</select>                                                                  	
        		                                    </td>			-->		   	  	
        								   	  	</tr>
        									  <?php
        									  	$i++;
        									  }
        								  ?>	
        								</tbody>
        							</table>									
        						</div>
        					</div>
        					<div class='row mobile-row'>		
        		           		<center>
        		           	          			
        		           		</center>
        		  			</div>		
        				</form>
        				 
        				<div class="row mobile-row">
        					<div class="col-sm-12">
        						<center>
        							<h6 style="color:blue;"><?php echo $msg;?></h6>
        							<br>
        						</center>
        					</div>
        				</div>
        			</center>
		        </div>	
        	</div>
        </div>
				
    </body>
</html>