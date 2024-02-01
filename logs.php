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

$extra_parameters = '';

$sql = "select count(*) as totalCount from log_info where 1=1 ".$_SESSION['filter_log'];

$order = " order by auto_id desc";


$res_sel = mysqli_query($con, $sql) or die(mysqli_error($con) . "11111");
$customer_count_array = mysqli_fetch_array($res_sel);
$noofrecords = $customer_count_array['totalCount'];
$pagesize = 0;

if (isset($_REQUEST['display_recs']) and ($_REQUEST['display_recs']!=""))
{
	if (trim($_REQUEST['display_recs']) == 'All')
		$pagesize = $noofrecords;
	else
		$pagesize = $_REQUEST['display_recs'];
}

if (isset($_REQUEST['display_recs'])) {
    $extra_parameters .= "&display_recs=".$_REQUEST['display_recs'];
}else
	$extra_parameters .= "&display_recs=10";
	
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
          			<br>
          			<div id="my-main-content">  		
          				
                    	<div class="my-home-content">     
                    		<form class="form-horizontal" method="post" enctype="multipart/form-data" action="logs.php" style="margin-bottom: 10px;">
                    			<div class="row" style="marign:0px;margin-top: 10px">
                    				<center><h2>Logs</h2></center>
                    			</div>
        	            		
        	            		<br> 
        	            		<br>    		
        	        			<div class="row">
        	            			<div class="my-static-div">
        	                        	<div class="panel my-panel">
        	                        	 	<div class="panel-heading my-panel-heading" style="padding-bottom: 10px">                        	 		
        	                        	 		<table style="width:100%">
        	                        	 			<tr>
														<?php
															$dis_rec = 0;
															if(isset($_REQUEST['display_recs'])) {
																$dis_rec = $_REQUEST['display_recs'];
															}
														?>
        	                        	 				
        	                        	 				<td style="width:80%">	                        	 					
        	                        	 					<div style="padding:5px 0px 0px 0px" align="left" class="col-sm-1 col-xs-4">Show By&nbsp;&nbsp;</div>
        													<div class="col-sm-11 col-xs-8" style="padding:4px 0px 0px 0px">
        							                           <select onchange="ChangeDisplayCnt();" id="display_recs" name="display_recs">
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
        							                        </div>
        	                        	 				</td>
        	                        	 				
        	                        	 			</tr>
        	                        	 		</table>                        	 		
        	                        	 	</div>
        								    <div class="panel-body my-panel-body">
        								    	
        						  				<div class="table-responsive">    
        						  					<table width="100%" class="table-condensed table-striped table-hover dataTable no-footer">
        						  						<thead>
        						      						<tr>
        														<th style="width: 70%">Description:</th>
        														<th>Date:</th>
        						      						</tr>
        						      					</thead>
        						      					<tbody class="my-dashboard-table-font">
        					      						<?php
        					      							
        								                    if ($noofrecords == 0) 	
        								                    {
        						                        	?>
        						                        		<tr> <td colspan="2">Sorry, No Logs Found.</td></tr>
        						                        	<?php
        								                    } else 
        								                    {
        								                       
        								                       
        								                        $sql_select = "select * from log_info where 1=1 ".$_SESSION['filter_log'];
        								                        
        								                        $sql_select = $sql_select . $order . " limit $offset,$pagesize";					                      
        								                        $result = mysqli_query($con,$sql_select) or die(mysqli_error($con));
        								                        
        								                        while ($seerec = mysqli_fetch_assoc($result))
        								                        {
        								                        	$href_url = "";
        								                        	if ($seerec['log_type'] == 'Contact')
        								                        	{
        								                        		if ($seerec['customer_id'] >= 0)	
        																	$href_url = 'contact.php?rid='.$seerec['customer_id'];
        																
        															}else if ($seerec['log_type'] == 'Company')
        								                        	{
        								                        		if ($seerec['customer_id'] >= 0)	
        																	$href_url = 'company.php?rid='.$seerec['customer_id'];	
        															}else
        															{
        																$href_url = $seerec['url'];	
        															}
        								                        	
        						                                    ?>
        						                                    <tr>
        						                                 
        						                                        <td style="width: 70%"><label><a href="<?php echo $href_url; ?>" style="color: grey"><?php echo $seerec['log_data']; ?></a></label></td>
        						                                        <td ><label><a href="<?php echo $href_url; ?>" style="color: grey"><?php echo $seerec['log_time']; ?></a></label></td>						                                        
        						                                       
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
        		  						<?php echo pagination(1, 'logs.php', $noofrecords, $pagesize, $page, $extra_parameters);?>
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

		