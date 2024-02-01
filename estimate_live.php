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

$group = '';

if (isset($_POST['SubmitDelete']) and ($_POST['SubmitDelete']=='Delete'))
{
	$to_cnt=0;
	$customers_ary= explode(",", utf8_decode(rawurldecode($_POST['sel_del_customers'])));
	
	foreach($customers_ary as $del_id)	
	{		
		// delete record 
		$sql_del = sprintf("delete from estimates_info where auto_id=%d",$del_id);		
		$res_del = mysqli_query($con,$sql_del) or die(mysqli_error($con));	
			
		// save log					
		saveLog($del_id,'Estimate','Estimate','removed');		
		
	}			
	
}

$sqlpart = '';
$filter = "";
$extra_parameters = '';

$sql_first = "select count(*) as totalCount from estimates_info where estimate_type = 'Live'";
$order = " order by auto_id desc";

if (isset($_POST['search_value']))
{
	$tmp_val = trim($_POST['search_value']);
	$_SESSION['serach_value'] = $tmp_val;
}

if (isset($_SESSION['serach_value']) and strlen($_SESSION['serach_value'])>0)
{
	$tmp_val = $_SESSION['serach_value'];
	$sqlpart = " and ((name like ('%" . $tmp_val . "%')) or (mobile like ('%". $tmp_val."%'))  or (est_no =". $tmp_val.")) or (addr1 like ('%". $tmp_val."%')) or (email like ('%". $tmp_val."%')))";
	$_POST['search_value'] = $tmp_val;
}

$name = null;
if(isset($_GET['name'])) {
	$name = $_GET['name'];
}


if ($name== 'd') {
    $extra_parameters .= "&name=d";
    $order = " order by name desc";
}

if ($name== 'a') {
    $extra_parameters .= "&name=a";
    $order = " order by name asc";
}

$phone = null;
if(isset($_GET['phone'])) {
	$phone = $_GET['phone'];
}

if ($phone == 'd') {
    $extra_parameters .= "&phone=d";
    $order = " order by phone desc";
}

if ($phone == 'a') {
    $extra_parameters .= "&phone=a";
    $order = " order by phone asc";
}

$category = null;
if(isset($_GET['category'])) {
	$category = $_GET['category'];
}

if ($category == 'd') {
    $extra_parameters .= "&category=d";
    $order = " order by category desc";
}

if ($category == 'a') {
    $extra_parameters .= "&category=a";
    $order = " order by category asc";
}

$status = null;
if(isset($_GET['status'])) {
	$status = $_GET['status'];
}

if ($status == 'd') {
    $extra_parameters .= "&status=d";
    $order = " order by status desc";
}

if ($status == 'a') {
    $extra_parameters .= "&status=a";
    $order = " order by status asc";
}


$email = null;
if(isset($_GET['email'])) {
	$email = $_GET['email'];
}

if ($email == 'd') {
    $extra_parameters .= "&email=d";
    $order = " order by email desc";
}

if ($email == 'a') {
    $extra_parameters .= "&email=a";
    $order = " order by email asc";
}


$date_time = null;
if(isset($_GET['date_time'])) {
	$date_time = $_GET['date_time'];
}

if ($date_time == 'd') {
    $extra_parameters .= "&date_time=d";
    $order = " order by date_time desc";
}

if ($date_time == 'a') {
    $extra_parameters .= "&date_time=a";
    $order = " order by date_time asc";
}

$est_no  = null;
if(isset($_GET['est_no'])) {
	$est_no  = $_GET['est_no'];
}

$est_date   = null;
if(isset($_GET['est_date'])) {
	$est_date   = $_GET['est_date'];
}

$estimate_type    = null;
if(isset($_GET['estimate_type'])) {
	$estimate_type = $_GET['estimate_type'];
}


$addr1    = null;
if(isset($_GET['addr1'])) {
	$addr1 = $_GET['addr1'];
}


$sql = $sql_first.$sqlpart;

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
                    		<form class="form-horizontal" method="post" enctype="multipart/form-data" action="enquiries.php" style="margin-bottom: 10px;">
                    			<div class="row" style="marign:0px;margin-top: 10px">
                    				<center><h2>Live Estimates</h2></center>
                    			</div>
        	            		<input type="hidden" id="sel_del_customers"  name="sel_del_customers" value='0'/>
        	            		
        	            		<br>    		
        	        			<div class="row">
        	            			<div class="my-static-div">
        	                        	<div class="panel my-panel">
        	                        	 	<div class="panel-heading my-panel-heading" style="padding-bottom: 10px">                        	 		
        	                        	 		<table style="width:100%">
        	                        	 			<tr>	                        	 				
        	                        	 				<td style="width:60%">	  
        	                        	 					<label style="width:80px;text-align:right" for="p_eml1">Show By&nbsp;&nbsp;&nbsp;</label>
        						                            <select onchange="ChangeDisplayCnt();" id="display_recs" name="display_recs"  style="display:inline-block;width:80px"  class="form-control my-form-control" >
        						                            	<option value="10" <?php if($_REQUEST['display_recs']=="10"){echo 'selected';}?>>10</option>	
        			                            				<option value="20" <?php if($_REQUEST['display_recs']=="20"){echo 'selected';}?>>20</option>	
        														<option value="50" <?php if($_REQUEST['display_recs']=="50"){echo 'selected';}?>>50</option>
        					                                    <option value="100" <?php if($_REQUEST['display_recs']=="100"){echo 'selected';}?>>100</option>						                                                                                              
        					                                    <option value="All" <?php
        						                                    if ($_REQUEST['display_recs'] == "All") {
        						                                        echo 'selected';
        						                                    }
        						                                    ?>>All</option>				                                    
        					                                </select>	
        													<button type="submit" style="display:none" class="btn btn-primary" name="Submit" id="btnPage" value="btnPage"></button>		                       
        													<button type="button" class="btn btn-success btn-sm" id="del_btn" >Delete</button>
        													<button type="submit" style="display:none" class="btn btn-primary" name="SubmitDelete" id="btnDelete" value="Delete">Delete</button>		                       
        	                        	 				</td>
        	                        	 				<td align="right">		
        	                        	 					<form action="enquiries.php" method="POST" id="search_form">
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
        						  						<thead>
        						      						<tr>
        						      							<th style="width: 50px;padding:5px; text-align: center">
        							                              	<input type="hidden" name="selected_status_val" value="0" id="selected_status_val" />
        							                               	<a id="selectall_btn" href="javascript: Select_all_records();">Select</a>
        							                            </th>
        							                           
        							                             <th> 
        							                                <?php
        							                                if ($name == 'd') {
        							                                ?>
        							                                	<a href="enquiries.php?name=a">Name&nbsp;<img src="images/arrow_down.gif"> </a>
        							                                <?php
        															} else if ($name == 'a') {
        															?>
        																<a href="enquiries.php?name=d">Name&nbsp;<img src="images/arrow_up.gif"> </a>
        															<?php
        															} else {
        															?>
        																<a href="enquiries.php?name=d">Name&nbsp;</a>
        															<?php
        															}
        															?>
        							                            </th>
        							                             <th> 
        							                                <?php
        							                                if ($phone == 'd') {
        							                                ?>
        							                                	<a href="enquiries.php?phone=a">Mobile&nbsp;<img src="images/arrow_down.gif"> </a>
        							                                <?php
        															} else if ($phone == 'a') {
        															?>
        																<a href="enquiries.php?phone=d">Mobile&nbsp;<img src="images/arrow_up.gif"> </a>
        															<?php
        															} else {
        															?>
        																<a href="enquiries.php?phone=d">Mobile&nbsp;</a>
        															<?php
        															}
        															?>
        							                            </th>
        							                            <th > 
        							                                <?php
        							                                if ($email == 'd') {
        							                                ?>
        							                                	<a href="enquiries.php?email=a">Email&nbsp; <img src="images/arrow_down.gif"> </a>
        							                                <?php
        															} else if ($email == 'a') {
        															?>
        																<a href="enquiries.php?email=d">Email&nbsp; <img src="images/arrow_up.gif"> </a>
        															<?php
        															} else {
        															?>
        																<a href="enquiries.php?email=d">Email&nbsp; </a>
        															<?php
        															}
        															?>
        							                            </th>
        							                             <th > 
        							                                <?php
        							                                if ($est_no == 'd') {
        							                                ?>
        							                                	<a href="enquiries.php?est_no=a">Estimate No&nbsp; <img src="images/arrow_down.gif"> </a>
        							                                <?php
        															} else if ($est_no == 'a') {
        															?>
        																<a href="enquiries.php?est_no=d">Estimate No&nbsp; <img src="images/arrow_up.gif"> </a>
        															<?php
        															} else {
        															?>
        																<a href="enquiries.php?est_no=d">Estimate No&nbsp; </a>
        															<?php
        															}
        															?>
        							                            </th>
        							                            <th> 
        							                                <?php
        							                                if ($est_date == 'd') {
        							                                ?>
        							                                	<a href="enquiries.php?est_date=a">Estimate Date&nbsp;<img src="images/arrow_down.gif"> </a>
        							                                <?php
        															} else if ($est_date == 'a') {
        															?>
        																<a href="enquiries.php?est_date=d">Estimate Date&nbsp;<img src="images/arrow_up.gif"> </a>
        															<?php
        															} else {
        															?>
        																<a href="enquiries.php?est_date=d">Estimate Date&nbsp;</a>
        															<?php
        															}
        															?>
        							                            </th>
        							                             <th > 
        							                                <?php
        							                                if ($estimate_type == 'd') {
        							                                ?>
        							                                	<a href="enquiries.php?estimate_type=a">Estimate Type&nbsp; <img src="images/arrow_down.gif"> </a>
        							                                <?php
        															} else if ($estimate_type == 'a') {
        															?>
        																<a href="enquiries.php?estimate_type=d">Estimate Type&nbsp; <img src="images/arrow_up.gif"> </a>
        															<?php
        															} else {
        															?>
        																<a href="enquiries.php?estimate_type=d">Estimate Type&nbsp; </a>
        															<?php
        															}
        															?>
        							                            </th>
        							                            <th > 
        							                                <?php
        							                                if ($addr1 == 'd') {
        							                                ?>
        							                                	<a href="enquiries.php?addr1=a">Address&nbsp; <img src="images/arrow_down.gif"> </a>
        							                                <?php
        															} else if ($addr1 == 'a') {
        															?>
        																<a href="enquiries.php?addr1=d">Address&nbsp; <img src="images/arrow_up.gif"> </a>
        															<?php
        															} else {
        															?>
        																<a href="enquiries.php?addr1=d">Address&nbsp; </a>
        															<?php
        															}
        															?>
        							                            </th>
        						      						</tr>
        						      					</thead>
        						      					<tbody class="my-dashboard-table-font">
        					      						<?php
        					      							
        								                    if ($noofrecords == 0) 	
        								                    {
        						                        	?>
        						                        		<tr> <td colspan="8">Sorry, No Record Found.</td></tr>
        						                        	<?php
        								                    } else 
        								                    {
        								                       
        								                        
        								                        $sql_first_select = "select * from estimates_info where estimate_type = 'Live'";
        								                        
        								                        $sql_select = $sql_first_select . $sqlpart . $group. $order." limit $offset,$pagesize";					                      
        								                        $result = mysqli_query($con,$sql_select) or die(mysqli_error($con));
        								                        while ($seerec = mysqli_fetch_assoc($result))
        								                        {
        						                                    ?>
        						                                    <tr>
        						                                    	<td><input type="checkbox"  name="sel_customer" value="<?php echo $seerec['auto_id'];?>"</td>
        						                                    	<td ><label><a href="estimate.php?rid=<?php echo $seerec['auto_id']; ?>"><?php 
        						                                        	echo $seerec['name'];?></a></label></td>
        						                                        
        						                                        <td style="color:green"><?php echo $seerec['mobile']; ?></td>
        						                                        
        						                                        <td style="color:green"><?php echo $seerec['email']; ?></td>
        						                                        <td style="color:green"><?php echo $seerec['est_no']; ?></td>
        						                                        <td style="color:green"><?php 
        						                                        	$date = date('m/d/Y',strtotime($seerec['est_no']));
        						                                        	echo $date; ?></td>
        						                                        <td style="color:green"><?php echo $seerec['estimate_type']; ?></td>
        						                                        <td style="color:green"><?php echo $seerec['addr1']; ?></td>
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
        		  						<?php echo pagination(1, 'enquiries.php', $noofrecords, $pagesize, $page, $extra_parameters);?>
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

		