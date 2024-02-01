<?php
/* * **-------------------------------------------------------------------**************************    

  Purpose 	: 	Where user can search the buyer detail

  Project 	:	Sales Material DB

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



$sqlpart = '';
$filter = "";
$extra_parameters = '';
$group = '';
$pagesize = 0;

$sql_first = "select count(*) as totalCount from materials_info";
$order = " order by auto_id desc";

if (isset($_POST['search_value']))
{
	$tmp_val = trim($_POST['search_value']);
	$_SESSION['serach_value'] = $tmp_val;
}

if (isset($_SESSION['serach_value']) and strlen($_SESSION['serach_value'])>0)
{
	$tmp_val = $_SESSION['serach_value'];
	$sqlpart = " where ((supplier like ('%" . $tmp_val . "%')) or (category like ('%" . $tmp_val . "%'))  or (price_net like ('%" . $tmp_val . "%'))   or (inc_vat like ('%" . $tmp_val . "%'))  or (description like ('%" . $tmp_val . "%')) or (code like ('%". $tmp_val."%')))";
	$_POST['search_value'] = $tmp_val;
}

$inc_vat = '';
if(isset($_GET['inc_vat'])) {
	$inc_vat = $_GET['inc_vat'];
}

if ($inc_vat == 'd') {
    $extra_parameters .= "&inc_vat=d";
    $order = " order by inc_vat desc";
}

if ($inc_vat == 'a') {
    $extra_parameters .= "&inc_vat=a";
    $order = " order by inc_vat asc";
}

$category = '';
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


$supplier = '';
if(isset($_GET['supplier'])) {
	$supplier = $_GET['supplier'];
}

if ($supplier == 'd') {
    $extra_parameters .= "&supplier=d";
    $order = " order by supplier desc";
}

if ($supplier == 'a') {
    $extra_parameters .= "&supplier=a";
    $order = " order by supplier asc";
}

$unit = '';
if(isset($_GET['unit'])) {
	$unit = $_GET['unit'];
}

if ($unit == 'd') {
    $extra_parameters .= "&unit=d";
    $order = " order by unit desc";
}

if ($unit == 'a') {
    $extra_parameters .= "&unit=a";
    $order = " order by unit asc";
}

$price_net = '';
if(isset($_GET['price_net'])) {
	$price_net = $_GET['price_net'];
}


if ($price_net == 'd') {
    $extra_parameters .= "&price_net=d";
    $order = " order by price_net desc";
}

if ($price_net == 'a') {
    $extra_parameters .= "&price_net=a";
    $order = " order by price_net asc";
}

$code = '';
if(isset($_GET['code'])) {
	$code = $_GET['code'];
}

$status = '';
if(isset($_GET['status'])) {
	$status = $_GET['status'];
}

$sql = $sql_first.$sqlpart;

$res_sel = mysqli_query($con, $sql) or die(mysqli_error($con) . "11111");
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
                    		<form class="form-horizontal" method="post" enctype="multipart/form-data" action="materials.php" style="margin-bottom: 10px;">
                    			<div class="row" style="marign:0px;margin-top: 10px">
                    				<center><h2>Materials</h2></center>
                    			</div>
        	            		
        	            		
        	            		<br>    		
        	        			<div class="row">
        	            			<div class="my-static-div">
        	                        	<div class="panel my-panel">
        	                        	 	<div class="panel-heading my-panel-heading" style="padding-bottom: 10px">                        	 		
        	                        	 		<table style="width:100%">
        	                        	 			<tr>
        	                        	 				
        	                        	 				<td style="width:60%">
														 <?php
																$dis_rec = 0;
																if(isset($_REQUEST['display_recs'])) {
																	$dis_rec = $_REQUEST['display_recs'];
																}
															?>	  
        	                        	 					<label style="width:80px;text-align:right" for="p_eml1">Show By&nbsp;&nbsp;&nbsp;</label>
        						                            <select onchange="ChangeDisplayCnt();" id="display_recs" name="display_recs"  style="display:inline-block;width:80px"  class="form-control my-form-control" >
        						                            	<option value="10" <?php if($dis_rec =="10"){echo 'selected';}?>>10</option>	
        			                            				<option value="20" <?php if($dis_rec =="20"){echo 'selected';}?>>20</option>	
        														<option value="50" <?php if($dis_rec =="50"){echo 'selected';}?>>50</option>
        					                                    <option value="100" <?php if($dis_rec =="100"){echo 'selected';}?>>100</option>						                                                                                              
        					                                    <option value="All" <?php
        						                                    if ($dis_rec == "All") {
        						                                        echo 'selected';
        						                                    }
        						                                    ?>>All</option>				                                    
        					                                </select>	
        													<button type="submit" style="display:none" class="btn btn-primary" name="Submit" id="btnPage" value="btnPage"></button>		                       
        													<a href="export_materials.php" target="_blank" style="text-decoration: none;color:white"><button type="button" class="btn btn-success btn-sm" name="Submit" id="btnExport" value="Export" style="margin-top: -4px">Export</button></a>	         
        													<a href="import_materials.php" target="_blank" style="text-decoration: none;color:white"><button type="button" class="btn btn-success btn-sm" name="Submit" id="btnImport" value="Import" style="margin-top: -4px">Import</button></a>		                       
        	                        	 				</td>
        	                        	 				<td align="right">		
        	                        	 					<form action="materials.php" method="POST" id="search_form">
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
        						  						<thead style="text-transform: uppercase">
        						      						<tr>
        						      							<th > 
        							                                <?php
        							                                if ($supplier == 'd') {
        							                                ?>
        							                                	<a href="materials.php?supplier=a">Supplier&nbsp; <img src="images/arrow_down.gif"> </a>
        							                                <?php
        															} else if ($supplier == 'a') {
        															?>
        																<a href="materials.php?supplier=d">Supplier&nbsp; <img src="images/arrow_up.gif"> </a>
        															<?php
        															} else {
        															?>
        																<a href="materials.php?supplier=d">Supplier&nbsp; </a>
        															<?php
        															}
        															?>
        							                            </th>
        							                            <th > 
        							                                <?php
        							                                if ($code == 'd') {
        							                                ?>
        							                                	<a href="materials.php?code=a">Item Code&nbsp; <img src="images/arrow_down.gif"> </a>
        							                                <?php
        															} else if ($code == 'a') {
        															?>
        																<a href="materials.php?code=d">Item Code&nbsp; <img src="images/arrow_up.gif"> </a>
        															<?php
        															} else {
        															?>
        																<a href="materials.php?code=d">Item Code&nbsp; </a>
        															<?php
        															}
        															?>
        							                            </th>
        							                             <th > 
        							                                <?php
        							                                if ($category == 'd') {
        							                                ?>
        							                                	<a href="materials.php?category=a">Item Type&nbsp; <img src="images/arrow_down.gif"> </a>
        							                                <?php
        															} else if ($category == 'a') {
        															?>
        																<a href="materials.php?category=d">Item Type&nbsp; <img src="images/arrow_up.gif"> </a>
        															<?php
        															} else {
        															?>
        																<a href="materials.php?category=d">Item Type&nbsp; </a>
        															<?php
        															}
        															?>
        							                            </th>
        							                            <th> <a href="#">Item Description&nbsp;</a>
        							                            </th>
        							                           
        							                            <th > 
        							                                <?php
        							                                if ($price_net == 'd') {
        							                                ?>
        							                                	<a href="materials.php?price_net=a">Price Net&nbsp; <img src="images/arrow_down.gif"> </a>
        							                                <?php
        															} else if ($price_net == 'a') {
        															?>
        																<a href="materials.php?price_net=d">Price Net&nbsp; <img src="images/arrow_up.gif"> </a>
        															<?php
        															} else {
        															?>
        																<a href="materials.php?price_net=d">Price Net&nbsp; </a>
        															<?php
        															}
        															?>
        							                            </th>
        							                            <th > 
        							                                <?php
        							                                if ($inc_vat == 'd') {
        							                                ?>
        							                                	<a href="materials.php?inc_vat=a">Inc Vat&nbsp; <img src="images/arrow_down.gif"> </a>
        							                                <?php
        															} else if ($inc_vat == 'a') {
        															?>
        																<a href="materials.php?inc_vat=d">Inc Vat&nbsp; <img src="images/arrow_up.gif"> </a>
        															<?php
        															} else {
        															?>
        																<a href="materials.php?inc_vat=d">Inc Vat&nbsp; </a>
        															<?php
        															}
        															?>
        							                            </th>
        							                            <th > 
        							                                <?php
        							                                if ($unit == 'd') {
        							                                ?>
        							                                	<a href="materials.php?unit=a">Unit&nbsp; <img src="images/arrow_down.gif"> </a>
        							                                <?php
        															} else if ($status == 'a') {
        															?>
        																<a href="materials.php?unit=d">Unit&nbsp; <img src="images/arrow_up.gif"> </a>
        															<?php
        															} else {
        															?>
        																<a href="materials.php?unit=d">Unit&nbsp; </a>
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
        						                        		<tr> <td colspan="5">Sorry, No Record Found.</td></tr>
        						                        	<?php
        								                    } else 
        								                    {
        								                       
        								                        
        								                        $sql_first_select = "select * from materials_info";
        								                        
        								                        $sql_select = $sql_first_select . $sqlpart . $group. $order." limit $offset,$pagesize";					                      
        								                        $result = mysqli_query($con, $sql_select) or die(mysqli_error($con));
        								                        while ($seerec = mysqli_fetch_assoc($result))
        								                        {
        						                                    ?>
        						                                    <tr>
        						                                        <td ><label><a href="material.php?rid=<?php echo $seerec['auto_id']; ?>"><?php echo $seerec['supplier']; ?></a></label></td>
        						                                        <td style="color:green"><?php echo $seerec['code']; ?></td>
        						                                        <td style="color:green"><?php echo $seerec['category']; ?></td>
        						                                        <td style="color:green"><?php echo $seerec['description']; ?></td>
        						                                        <td style="color:green"><i class="fa fa-gbp" aria-hidden="true"></i>&nbsp;&nbsp;<?php echo $seerec['price_net']; ?></td>
        						                                        <td style="color:green"><?php echo $seerec['inc_vat']; ?></td>
        						                                        <td style="color:green"><?php echo $seerec['unit']; ?></td>
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
        		  						<?php echo pagination(1, 'materials.php', $noofrecords, $pagesize, $page, $extra_parameters);?>
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

		