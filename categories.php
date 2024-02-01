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
require_once("PHPMailer-master/PHPMailerAutoload.php");	// Email
require_once("includes/Services/Twilio/Capability.php"); // Twilio Call



$sqlpart = '';
$filter = "";
$extra_parameters = '';

$sql_first = "select count(*) as totalCount from categories_info";
$order = " order by auto_id desc";

if (isset($_POST['search_value']))
{
	$tmp_val = trim($_POST['search_value']);
	$_SESSION['serach_value'] = $tmp_val;
}

if (isset($_SESSION['serach_value']) and strlen($_SESSION['serach_value'])>0)
{
	$tmp_val = $_SESSION['serach_value'];
	$sqlpart = " where ((name like ('%" . $tmp_val . "%')) or (description like ('%". $tmp_val."%')))";
	$_POST['search_value'] = $tmp_val;
}

if ($_GET['name'] == 'd') {
    $extra_parameters .= "&name=d";
    $order = " order by name desc";
}

if ($_GET['name'] == 'a') {
    $extra_parameters .= "&name=a";
    $order = " order by name asc";
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
                    		<form class="form-horizontal" method="post" enctype="multipart/form-data" action="categories.php" style="margin-bottom: 10px;">
                    			<div class="row" style="marign:0px;margin-top: 10px">
                    				<center><h2>Categories</h2></center>
                    			</div>
        	            		
        	            		
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
        													<a href="export_categories.php" target="_blank" style="text-decoration: none;color:white"><button type="button" class="btn btn-success btn-sm" name="Submit" id="btnExport" value="Export" style="margin-top: -4px">Export</button></a>	         
        													<a href="import_categories.php" target="_blank" style="text-decoration: none;color:white"><button type="button" class="btn btn-success btn-sm" name="Submit" id="btnImport" value="Import" style="margin-top: -4px">Import</button></a>		                       
        	                        	 				</td>
        	                        	 				<td align="right">		
        	                        	 					<form action="categories.php" method="POST" id="search_form">
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
        							                            <th> 
        							                                <?php
        							                                if ($_GET['name'] == 'd') {
        							                                ?>
        							                                	<a href="categories.php?name=a">Name&nbsp;<img src="images/arrow_down.gif"> </a>
        							                                <?php
        															} else if ($_GET['name'] == 'a') {
        															?>
        																<a href="categories.php?name=d">Name&nbsp;<img src="images/arrow_up.gif"> </a>
        															<?php
        															} else {
        															?>
        																<a href="categories.php?name=d">Name&nbsp;</a>
        															<?php
        															}
        															?>
        							                            </th>
        							                            <th> Description&nbsp; </th>							                           
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
        								                       
        								                        
        								                        $sql_first_select = "select * from categories_info";
        								                        
        								                        $sql_select = $sql_first_select . $sqlpart . $group. $order." limit $offset,$pagesize";					                      
        								                        $result = mysqli_query($con, $sql_select) or die(mysqli_error($con));
        								                        while ($seerec = mysqli_fetch_assoc($result))
        								                        {
        						                                    ?>
        						                                    <tr>
        						                                        <td ><label><a href="category.php?rid=<?php echo $seerec['auto_id']; ?>"><?php echo $seerec['name']; ?></a></label></td>
        						                                        <td style="color:green"><?php echo $seerec['description']; ?></td>						                                        
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
        		  						<?php echo pagination(1, 'categories.php', $noofrecords, $pagesize, $page, $extra_parameters);?>
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

		