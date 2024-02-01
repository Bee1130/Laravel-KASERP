<?php
/* * **-------------------------------------------------------------------**************************    

  Purpose 	: 	Where user can search the buyer detail

  Project 	:	Sales Contact DB

  Developer 	: 	Kelvin 

  Create Date : 	30/11/2015

 * ***-------------------------------------------------------------------*********************** */
//phpinfo();
@session_start();	
if (! isset($_COOKIE['cookie_login']) and !isset($_SESSION['user_login'])) {//session store admin name
    header("Location: index.php"); //login in AdminLogin.php
}

require_once("includes/dbconnect.php");
// require_once("PHPMailer-master/PHPMailerAutoload.php");	// Email
require_once("PHPMailer-master/src/PHPMailer.php");	// Email
require_once("includes/Services/Twilio/Capability.php"); // Twilio Call

$sql_contact_filter = '';

if (isset($_POST['SubmitDelete']) and ($_POST['SubmitDelete']=='Delete'))
{
	$to_cnt=0;
	$customers_ary= explode(",", utf8_decode(rawurldecode($_POST['sel_del_customers'])));
	
	foreach($customers_ary as $del_id)	
	{		
		if ($del_id>0)
		{
			// delete record 
			$sql_del = sprintf("delete from contacts_info where auto_id=%d",$del_id);		
			$res_del = mysqli_query($con, $sql_del) or die(mysqli_error($con));	
				
			// save log					
			saveLog($del_id,'Contact','Contact','removed');		
		}
		
	}			
}

$sqlpart = '';
$filter = "";
$extra_parameters = '';
$pagesize = 0;
$group = '';

if (isset($_REQUEST['contact_type'])) 
{	
	$_SESSION['contact_type'] = $_REQUEST['contact_type'];
}

if (isset($_SESSION['contact_type']) and strlen($_SESSION['contact_type'])>0) 
{
	$_REQUEST['contact_type'] = $_SESSION['contact_type'];
}

if(isset($_SESSION['contact_type'])) {
	if ($_SESSION['contact_type'] == 'All')
	$sql_contact_filter = '';
else
	$sql_contact_filter = " and (contact_type like ('".$_SESSION['contact_type']."'))";
}


		
$sql_first = "select count(*) as totalCount from contacts_info where is_deleted=0".$_SESSION['filter_user'];
$order = " order by auto_id desc";

if (isset($_POST['search_value']))
{
	$tmp_val = trim($_POST['search_value']);
	$_SESSION['serach_value'] = $tmp_val;
}

if (isset($_SESSION['serach_value']) and strlen($_SESSION['serach_value'])>0)
{
	$tmp_val = $_SESSION['serach_value'];
	$sqlpart = " and ((name like ('%" . $tmp_val . "%')) or (address like ('%". $tmp_val."%'))  or (email like ('%". $tmp_val."%')) or (hm_ph like ('%". $tmp_val."%')) or (website like ('%". $tmp_val."%')))";
	$_POST['search_value'] = $tmp_val;
}

$gname = null;
if(isset($_GET['name'])) {
	$gname = $_GET['name'];
}

$gemail = null;
if(isset($_GET['email'])) {
	$gemail = $_GET['email'];
}

$hm_ph = null;
if(isset($_GET['hm_ph'])) {
	$hm_ph = $_GET['hm_ph'];
}

$website = null;
if(isset($_GET['website'])) {
	$website = $_GET['website'];
}

$address = null;
if(isset($_GET['address'])) {
	$address = $_GET['address'];
}

$landline = null;
if(isset($_GET['landline'])) {
	$landline = $_GET['landline'];
}

$policy_number = null;
if(isset($_GET['policy_number'])) {
	$policy_number = $_GET['policy_number'];
}

$status = null;
if(isset($_GET['status'])) {
	$status = $_GET['status'];
}

$national_ins = null;
if(isset($_GET['national_ins'])) {
	$national_ins = $_GET['national_ins'];
}

$utr = null;
if(isset($_GET['utr'])) {
	$utr = $_GET['utr'];
}

$bank_details = null;
if(isset($_GET['bank_details'])) {
	$bank_details = $_GET['bank_details'];
}





if ($gname == 'd') {
    $extra_parameters .= "&name=d";
    $order = " order by name desc";
}

if ($gname == 'a') {
    $extra_parameters .= "&name=a";
    $order = " order by name asc";
}

if ($gemail == 'd') {
    $extra_parameters .= "&email=d";
    $order = " order by email desc";
}

if ($gemail == 'a') {
    $extra_parameters .= "&email=a";
    $order = " order by email asc";
}

if ($hm_ph == 'd') {
    $extra_parameters .= "&hm_ph=d";
    $order = " order by hm_ph desc";
}

if ($hm_ph == 'a') {
    $extra_parameters .= "&hm_ph=a";
    $order = " order by hm_ph asc";
}

if ($website == 'd') {
    $extra_parameters .= "&website=d";
    $order = " order by website desc";
}

if ($website == 'a') {
    $extra_parameters .= "&website=a";
    $order = " order by website asc";
}


if ($address == 'd') {
    $extra_parameters .= "&address=d";
    $order = " order by address desc";
}

if ($address == 'a') {
    $extra_parameters .= "&address=a";
    $order = " order by address asc";
}



$sql = $sql_first.$sqlpart.$sql_contact_filter;

$res_sel = mysqli_query($con,$sql) or die(mysqli_error($con) . "11111");
$customer_count_array = mysqli_fetch_array($res_sel);
$noofrecords = $customer_count_array['totalCount'];
$_SESSION['total_records']=$noofrecords;	


if (isset($_REQUEST['display_recs'])) 
{	
	$_SESSION['display_recs'] = $_REQUEST['display_recs'];
}

if (isset($_SESSION['display_recs']) and strlen($_SESSION['display_recs'])>0) 
{
	$_REQUEST['display_recs'] = $_SESSION['display_recs'];
}

if (isset($_REQUEST['display_recs']))
	$extra_parameters .= "&display_recs=".$_REQUEST['display_recs'];
else
	$extra_parameters .= "&display_recs=10";

if (isset($_REQUEST['display_recs']) and ($_REQUEST['display_recs']!=""))
{
	if (trim($_REQUEST['display_recs']) == 'All')
		$pagesize = $noofrecords;
	else
		$pagesize = $_REQUEST['display_recs'];
}
	
if ($pagesize==0)
	$pagesize = 10;
	
if ($pagesize!=0)
	$total_pages = ceil($noofrecords / $pagesize);
else
	$total_pages = 0;
	

if (empty($_GET['page'])) {
    $page = 1;
} elseif (isset($_GET['page'])) {
    $page = $_GET['page'];
}
$_SESSION['page'] = $page;
$offset = (($page - 1) * $pagesize);
////////////////////////////////

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
    <head>
        <?php include("header.php");?>
		<script type="text/javascript">
			
			$(document).ready(function () { 
				/**
				* Delete Records
				*/						   
				$("#del_btn").on("click", function() {		
					console.log('[del_btn]');
					
					var chk_ary = document.getElementsByName('sel_customer');
					console.log(chk_ary);
					var arrchecked = [];
					for (var i=0;i<chk_ary.length;i++)
					{
						console.log(chk_ary[i].value);
						if (chk_ary[i].checked)
							arrchecked.push(chk_ary[i].value);					
					}
					
					document.getElementById("sel_del_customers").value = encodeURIComponent(arrchecked.toString());
					console.log(document.getElementById("sel_del_customers").value);
					$("#btnDelete").click();					
				});	
			});		
        
			/**
			* Search option			
			*/
			function ChangeSearchValue()
			{
				document.getElementById('val').value = document.getElementById('search_value').value;
				console.log(document.getElementById('val').value);
				$('#btnSearch').click();
			}
			
			/**
			* Change records count per pagge
			*/
			function ChangeDisplayCnt()
			{
				console.log("ChangeDisplayCnt");
				$("#btnPage").click();
			}
			
			/**
			* Change records count per pagge
			*/
			function ChangeContactType()
			{
				var contact_type;
				contact_type = document.getElementById('contact_type').value
				location.href = "contacts.php?contact_type="+contact_type;
				
			}
			
			function Select_all_records()
			{
				console.log("Selected all records");
				sel_stat = document.getElementById('selected_status_val').value;
				var chk_ary = document.getElementsByName('sel_customer');
				console.log(chk_ary);
				for (var i=0;i<chk_ary.length;i++)
				{
					chk_ary[i].checked = 1-sel_stat;	
				}
				document.getElementById('selected_status_val').value = 1-sel_stat;				
			}
        </script>      
        <style type="text/css">
			.table-striped>tbody>tr:nth-child(odd)>td, .table-striped>tbody>tr:nth-child(odd)>th 
			{
			    background: #f0f3f5;
			}
		</style> 
    </head>
    <body>
    	
    	<div class="container">
    	    <?php include("sidebar.php"); ?>
    	    <div class="main-content">
        		<div class="container">
        		    <?php include('menu.php'); ?>
  			
          			<div id="my-main-content">  		
          							  
        				<!-- delete confirmation dialog -->
        		        <div id="dialog_delete_yes_no" class="modal fade" style="z-index:1000000014;display:none;" title="">
        		        	<div class="modal-dialog modal-md">
        						<div class="modal-content">
        							<div class="modal-header">
        						        <button type="button" class="close" data-dismiss="modal">&times;</button>
        						        <h4 class="modal-title">Delete</h4>				        
        					      	</div>
        					      	<div class="modal-body" style="overflow:auto" id="bulk_email_preview_div">
        					      		<div class="form-group" style="margin-bottom:0px">
                            				<center>
                                    			<p>Are you sure you want to delete these records?</p>         					
                            				</center>
        								</div>
        					      	</div>	
        					      	<div class="modal-footer">
        					      		<div class="row mobile-row">
        					      			<center>
        						      			<div class="col-xs-offset-2 col-xs-4">
        						      				<button type="button" id="btnYesDelete"  name="btnYesDelete" class="btn btn-default btn-success btn-block">Yes</button>			        	 
        						      			</div>
        						      			<div class="col-xs-4">
        						      				<button type="button" data-dismiss="modal" class="btn btn-default btn-success btn-block">No</button>		        	 
        						      			</div>
        					      			</center>
        					      		</div>	
        					      	</div>		    
        						</div>
        					</div>
        				</div> 
        		
                    	<div class="my-home-content">     
                    		<form class="form-horizontal" method="post" enctype="multipart/form-data" action="contacts.php" style="margin-bottom: 10px;">
                    			<div class="row" style="marign:0px;margin-top: 10px">
                    				<center><h2>Contacts</h2></center>
                    			</div>
        	            		
        	            		<input type="hidden" id="sel_del_customers"  name="sel_del_customers" value='0'/>
        	            		<br>    		
        	        			<div class="row">
        	            			<div class="my-static-div">
        	                        	<div class="panel my-panel">
        	                        	 	<div class="panel-heading my-panel-heading" style="padding: 20px">                        	 		
        	                        	 		<table style="width:100%">
        	                        	 			<tr>
        	                        	 				
        	                        	 				<td style="width:70%">	  
        	                        	 					<label style="width:150px;text-align:LEFT" for="p_eml1">CONTRACTORS&nbsp;&nbsp;&nbsp;</label>
															<?php
															$ctype = '';
															if(isset($_REQUEST['contact_type'])) {
																$ctype = $_REQUEST['contact_type'];
															}
															?>
        	                        	 					<select onchange="ChangeContactType();" id="contact_type" name="contact_type"  style="display:inline-block;width:120px"  class="form-control my-form-control" >
        						                            	<option value="All" <?php if($ctype=="All"){echo 'selected';}?>>All</option>	
        			                            				<option value="Client" <?php if($ctype=="Client"){echo 'selected';}?>>Client</option>	
        														<option value="Contractor" <?php if($ctype=="Contractor"){echo 'selected';}?>>Contractor</option>
        					                                       
        					                                    <option value="New" <?php if($ctype=="New"){echo 'selected';}?>>New</option>						                                    <option value="Other" <?php if($ctype=="Other"){echo 'selected';}?>>Other</option>						                                                                                     
        					                                   	                                    
        					                                </select>	&nbsp;&nbsp;&nbsp;

															<?php
																$dis_rec = 0;
																if(isset($_REQUEST['display_recs'])) {
																	$dis_rec = $_REQUEST['display_recs'];
																}
															?>
        					                                
        	                        	 					<label style="width:80px;text-align:right" for="p_eml1">Show By&nbsp;&nbsp;&nbsp;</label>
        						                            <select onchange="ChangeDisplayCnt();" id="display_recs" name="display_recs"  style="display:inline-block;width:80px"  class="form-control my-form-control" >
        						                            	<option value="10" <?php if($dis_rec=="10"){echo 'selected';}?>>10</option>	
        			                            				<option value="20" <?php if($dis_rec=="20"){echo 'selected';}?>>20</option>	
        														<option value="50" <?php if($dis_rec=="50"){echo 'selected';}?>>50</option>
        					                                    <option value="100" <?php if($dis_rec=="100"){echo 'selected';}?>>100</option>						                                                                                              
        					                                    <option value="All" <?php
        						                                    if ($dis_rec == "All") {
        						                                        echo 'selected';
        						                                    }
        						                                    ?>>All</option>				                                    
        					                                </select>	
        													<button type="submit" style="display:none" class="btn btn-primary" name="Submit" id="btnPage" value="btnPage"></button>		                       
        													<button type="button" class="btn btn-success btn-sm" id="del_btn"  style="margin-top: -4px">Delete</button>
        													<button type="submit" style="display:none" class="btn btn-primary" name="SubmitDelete" id="btnDelete" value="Delete">Delete</button>
        													<a href="export_contacts.php" target="_blank" style="text-decoration: none;color:white"><button type="button" class="btn btn-success btn-sm" name="Submit" id="btnExport" value="Export" style="margin-top: -4px">Export</button></a>	         
        													<a href="importfromcsv.php" target="panel-collapse collapse in_blank" style="text-decoration: none; color: white; background-color: #f5f3ef"><button type="button" class="btn btn-success btn-sm" name="Submit" id="btnImport" value="Import" style="margin-top: -4px">Import</button></a>		                       
        	                        	 				</td>
        	                        	 				<td align="right">		
        	                        	 					<form action="contacts.php" method="POST" id="search_form">
        														<div class="form-group">
        															<label style="width:60px;text-align:right" for="search_value">Search:&nbsp;&nbsp;&nbsp;</label>
        	                        	 							<input type="text"  style="display:inline-block;width:180px"  class="form-control my-form-control"  id="search_value" name="search_value" value="<?php if(isset($_POST['search_value']) and strlen($_POST['search_value'])>0) {echo $_POST['search_value'];}?>">
        														</div>
        													</form>														
        	                        	 				</td>
        	                        	 			</tr>
        	                        	 		</table>                        	 		
        	                        	 	</div>
        								    <div class="panel-body my-panel-body">
        								    	<input type="hidden" name="p_fl_nm" id="p_fl_nm" value='0'/>	
        						  				<div class="table-responsive">    
        						  					<table width="100%" class="table-condensed table-striped table-hover dataTable no-footer">
        						  						<thead style="text-transform: uppercase;">
        						      						<tr>
        						      							<th style="width: 50px;padding:5px; text-align: center">
        							                              	<input type="hidden" name="selected_status_val" value="0" id="selected_status_val" />
        							                               	<a id="selectall_btn" href="javascript: Select_all_records();">Select</a>
        							                            </th>
        							                            <th style="width: 10%;font-weight:bold;padding-left:5px;padding-right:0px;">  
        							                                <?php
        							                                if ($gname == 'd') {
        							                                ?>
        							                                	<a href="contacts.php?name=a" style="font-weight:bold">Name&nbsp;<img src="images/arrow_down.gif"> </a>
        							                                <?php
        															} else if ($gname == 'a') {
        															?>
        																<a href="contacts.php?name=d" style="font-weight:bold">Name&nbsp;<img src="images/arrow_up.gif"> </a>
        															<?php
        															} else {
        															?>
        																<a href="contacts.php?name=d" style="font-weight:bold">Name&nbsp;</a>
        															<?php
        															}
        															?>
        							                            </th>
        							                            <th style="width: 10%;font-weight:bold;padding-left:0px;padding-right:0px;">  
        							                                <?php
        							                                if ($address == 'd') {
        							                                ?>
        							                                	<a href="contacts.php?address=a" style="font-weight:bold">Address&nbsp; <img src="images/arrow_down.gif"> </a>
        							                                <?php
        															} else if ($address == 'a') {
        															?>
        																<a href="contacts.php?address=d" style="font-weight:bold">Address&nbsp; <img src="images/arrow_up.gif"> </a>
        															<?php
        															} else {
        															?>
        																<a href="contacts.php?address=d" style="font-weight:bold">Address&nbsp; </a>
        															<?php
        															}
        															?>
        							                            </th>
        							                            <th style="width: 10%;font-weight:bold;padding-left:0px;padding-right:0px;">  
        							                                <?php
        							                                if ($hm_ph == 'd') {
        							                                ?>
        							                                	<a href="contacts.php?hm_ph=a" style="font-weight:bold">Mobile&nbsp; <img src="images/arrow_down.gif"> </a>
        							                                <?php
        															} else if ($hm_ph == 'a') {
        															?>
        																<a href="contacts.php?hm_ph=d" style="font-weight:bold">Mobile&nbsp; <img src="images/arrow_up.gif"> </a>
        															<?php
        															} else {
        															?>
        																<a href="contacts.php?hm_ph=d" style="font-weight:bold">Mobile&nbsp; </a>
        															<?php
        															}
        															?>
        							                            </th>
        							                            <th style="width: 10%;font-weight:bold;padding-left:0px;padding-right:0px;">  
        							                                <?php
        							                                if ($landline == 'd') {
        							                                ?>
        							                                	<a href="contacts.php?landline=a" style="font-weight:bold">Landline&nbsp; <img src="images/arrow_down.gif"> </a>
        							                                <?php
        															} else if ($landline == 'a') {
        															?>
        																<a href="contacts.php?landline=d" style="font-weight:bold">Landline&nbsp; <img src="images/arrow_up.gif"> </a>
        															<?php
        															} else {
        															?>
        																<a href="contacts.php?landline=d" style="font-weight:bold">Landline&nbsp; </a>
        															<?php
        															}
        															?>
        							                            </th>
        							                            <th style="width: 10%;font-weight:bold;padding-left:0px;padding-right:0px;">  
        							                                <?php
        							                                if ($gemail == 'd') {
        							                                ?>
        							                                	<a href="contacts.php?email=a" style="font-weight:bold">Email&nbsp; <img src="images/arrow_down.gif"> </a>
        							                                <?php
        															} else if ($gemail == 'a') {
        															?>
        																<a href="contacts.php?email=d" style="font-weight:bold">Email&nbsp; <img src="images/arrow_up.gif"> </a>
        															<?php
        															} else {
        															?>
        																<a href="contacts.php?email=d" style="font-weight:bold">Email&nbsp; </a>
        															<?php
        															}
        															?>
        							                            </th>
        							                           
        							                            <th style="width: 10%;font-weight:bold;padding-left:0px;padding-right:0px;">  
        							                                <?php
        							                                if ($policy_number == 'd') {
        							                                ?>
        							                                	<a href="contacts.php?policy_number=a" style="font-weight:bold">LIABILITY INSURANCE POLICY NUMBER&nbsp; <img src="images/arrow_down.gif"> </a>
        							                                <?php
        															} else if ($status == 'a') {
        															?>
        																<a href="contacts.php?policy_number=d" style="font-weight:bold">LIABILITY INSURANCE POLICY NUMBER&nbsp; <img src="images/arrow_up.gif"> </a>
        															<?php
        															} else {
        															?>
        																<a href="contacts.php?policy_number=d" style="font-weight:bold">LIABILITY INSURANCE POLICY NUMBER&nbsp; </a>
        															<?php
        															}
        															?>
        							                            </th>
        							                            <th style="width: 10%;font-weight:bold;padding-left:0px;padding-right:0px;">  
        							                                <?php
        							                                if ($national_ins == 'd') {
        							                                ?>
        							                                	<a href="contacts.php?national_ins=a" style="font-weight:bold">National Ins&nbsp; <img src="images/arrow_down.gif"> </a>
        							                                <?php
        															} else if ($national_ins == 'a') {
        															?>
        																<a href="contacts.php?national_ins=d" style="font-weight:bold">National Ins&nbsp; <img src="images/arrow_up.gif"> </a>
        															<?php
        															} else {
        															?>
        																<a href="contacts.php?national_ins=d" style="font-weight:bold">National Ins&nbsp; </a>
        															<?php
        															}
        															?>
        							                            </th>
        							                             <th style="width: 10%;font-weight:bold;padding-left:0px;padding-right:0px;">  
        							                                <?php
        							                                if ($utr == 'd') {
        							                                ?>
        							                                	<a href="contacts.php?utr=a" style="font-weight:bold">Utr&nbsp; <img src="images/arrow_down.gif"> </a>
        							                                <?php
        															} else if ($utr == 'a') {
        															?>
        																<a href="contacts.php?utr=d" style="font-weight:bold">Website&nbsp; <img src="images/arrow_up.gif"> </a>
        															<?php
        															} else {
        															?>
        																<a href="contacts.php?utr=d" style="font-weight:bold">Website&nbsp; </a>
        															<?php
        															}
        															?>
        							                            </th>
        							                        	<th style="width: 10%;font-weight:bold;padding-left:1px;padding-right:1px">  
        							                                <?php
        							                                if ($bank_details == 'd') {
        							                                ?>
        							                                	<a href="contacts.php?bank_details=a" style="font-weight:bold">Bank Details&nbsp; <img src="images/arrow_down.gif"> </a>
        							                                <?php
        															} else if ($bank_details == 'a') {
        															?>
        																<a href="contacts.php?bank_details=d" style="font-weight:bold">Bank Details&nbsp; <img src="images/arrow_up.gif"> </a>
        															<?php
        															} else {
        															?>
        																<a href="contacts.php?bank_details=d" style="font-weight:bold">Bank Details&nbsp; </a>
        															<?php
        															}
        															?>
        							                            </th>
        							                            <th style="color: #337ab7;padding-left:1px;padding-right:1px">Documents(Linked)</th>
        						      						</tr>
        						      					</thead>
        						      					<tbody class="my-dashboard-table-font">
        					      						<?php
        					      							
        								                    if ($noofrecords == 0) 	
        								                    {
        						                        	?>
        						                        		<tr> <td colspan="5">Sorry, No Record Found.</td></tr>
        						                        	<?php
        								                    } else 
        								                    {
        								                       
        								                        
        								                        $sql_first_select = "select * from contacts_info where is_deleted=0".$_SESSION['filter_user'];
        								                        
        								                        $sql_select = $sql_first_select . $sqlpart.$sql_contact_filter . $group. $order." limit $offset,$pagesize";					                      
        								                        $result = mysqli_query($con,$sql_select) or die(mysqli_error($con));
        								                        while ($seerec = mysqli_fetch_assoc($result))
        								                        {
        						                                    ?>
        						                                    <tr>
        						                                    	<td><input type="checkbox"  name="sel_customer" value="<?php echo $seerec['auto_id']; ?>"</td>
        						                                        <td style="width: 10%;padding-left:1px;padding-right:1px"><label><a href="contact.php?rid=<?php echo $seerec['auto_id']; ?>"><?php echo $seerec['name']; ?></a></label></td>
        						                                        
        						                                        <td style="width:10%;padding-left:1px;padding-right:1px"><?php echo $seerec['address']; ?></td>
        						                                        <td style="width:10%;padding-left:1px;padding-right:1px"><?php echo $seerec['hm_ph']; ?></td>
        						                                        <td style="width:10%;padding-left:1px;padding-right:1px"><?php echo $seerec['landline']; ?></td>
        						                                        <td style="width:10%;padding-left:1px;padding-right:1px"><?php echo $seerec['email']; ?></td>
        						                                        <td style="width:10%;padding-left:1px;padding-right:1px"><?php echo $seerec['policy_number']; ?></td>
        						                                        <td style="width:10%;padding-left:1px;padding-right:1px"><?php 
																		$secins = null;
																		if(isset($seerec['nationial_ins'])){
																			$secins = $seerec['nationial_ins'];
																		};
																		echo $secins; ?></td>
        						                                        <td style="width:10%;padding-left:1px;padding-right:1px"><?php echo $seerec['utr']; ?></td>
        						                                        <td style="width:10%;padding-left:1px;padding-right:1px"><?php echo $seerec['bank_details']; ?></td>
        						                                        <td style="width:10%;padding-left:1px;padding-right:1px"><?php echo $seerec['fil_filename']; ?></td>
        						                                        
        						                                        
        						                                    </tr>
        														<?php        															
        														}
        														?>								                       
        													<?php
        													}
        													?>			
        						      					</tbody>
        						  					</table>
        						  				</div>
        								    </div>
        								</div>
        							</div>						
        						</div>	
        					</form>				    
        					<div class="row mobile-row">							
        		  				<div class="row mobile-row">
        		  					<ul class="pager">
        		  						<?php echo pagination(1, 'contacts.php', $noofrecords, $pagesize, $page, $extra_parameters);?>
        		  					</ul>  				
        		  				</div>
        		            </div>           			
                    	</div>	
                    </div>
        		</div>            
        	</div>
	    </div>
    </body>
</html>

		