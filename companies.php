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


$sqlpart = '';
$extra_parameters = '';


$sql_first = "select count(*) as totalCount from companies_info where is_deleted=0";

$order = " order by auto_id desc";

if (isset($_POST['search_value_company']))
{
	$tmp_val = trim($_POST['search_value_company']);
	$_SESSION['search_value_company'] = $tmp_val;
}

if (isset($_SESSION['search_value_company']) and strlen($_SESSION['search_value_company'])>0)
{
	$tmp_val = $_SESSION['search_value_company'];
	$sqlpart = " and ((name like ('%" . $tmp_val . "%')) or (type like ('%". $tmp_val."%')) or (country like ('%". $tmp_val."%')) or (city like ('%". $tmp_val."%')))";
	$_POST['search_value_company'] = $tmp_val;
}

if ($_GET['name'] == 'd') {
    $extra_parameters .= "&name=d";
    $order = " order by name desc";
}

if ($_GET['name'] == 'a') {
    $extra_parameters .= "&name=a";
    $order = " order by name asc";
}

if ($_GET['type'] == 'd') {
    $extra_parameters .= "&type=d";
    $order = " order by type desc";
}

if ($_GET['type'] == 'a') {
    $extra_parameters .= "&type=a";
    $order = " order by type asc";
}

if ($_GET['country'] == 'd') {
    $extra_parameters .= "&country=d";
    $order = " order by country desc";
}

if ($_GET['country'] == 'a') {
    $extra_parameters .= "&country=a";
    $order = " order by country asc";
}


if ($_GET['city'] == 'd') {
    $extra_parameters .= "&city=d";
    $order = " order by city desc";
}

if ($_GET['city'] == 'a') {
    $extra_parameters .= "&city=a";
    $order = " order by city asc";
}

$sql = $sql_first.$sqlpart;

$res_sel = mysqli_query($con, $sql) or die(mysqli_error($con) . "11111");
$customer_count_array = mysqli_fetch_array($res_sel);
$noofrecords = $customer_count_array['totalCount'];


/* Pagination */
function pagination($adjacents, $targetpage, $total_pages, $limit, $page, $extra_parameters) {
 
    if ($page)
        $start = ($page - 1) * $limit;    //first item to display on this page
    else
        $start = 0;

    if ($page == 0)
        $page = 1;     //if no page var is given, default to 1.
    $prev = $page - 1;       //previous page is page - 1
    $next = $page + 1;       //next page is page + 1
    if ($limit!=0)
		$lastpage = ceil($total_pages / $limit);
	else
		$lastpage = 0;
    $lpm1 = $lastpage - 1;
    $pagination = "";
    if ($lastpage > 0) {
        $pagination.= "<div class=\"pagination\">";
        //previous button
        if ($page > 1)
            $pagination.= "<a href=\"$targetpage?total_pages=$total_pages&page=$prev" . $extra_parameters . "\">«prev</a>";
        else
            $pagination.= "<span class=\"disabled\">«prev</span>";

        //pages	
        if ($lastpage < 7 + ($adjacents * 2)) { //not enough pages to bother breaking it up
            for ($counter = 1; $counter <= $lastpage; $counter++) {

                if ($counter == $page)
                    $pagination.= "<span class=\"current\">$counter</span>";
                else
                    $pagination.= "<a href=\"$targetpage?total_pages=$total_pages&page=$counter" . $extra_parameters . "\">$counter</a>";
            }
        }
        elseif ($lastpage > 5 + ($adjacents * 2)) { //enough pages to hide some
            //close to beginning; only hide later pages
            if ($page < 1 + ($adjacents * 2)) {

                for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++) {
                    if ($counter == $page)
                        $pagination.= "<span class=\"current\">$counter</span>";
                    else
                        $pagination.= "<a href=\"$targetpage?total_pages=$total_pages&page=$counter" . $extra_parameters . "\">$counter</a>";
                }
                $pagination.= "...";
                $pagination.= "<a href=\"$targetpage?total_pages=$total_pages&page=$lpm1" . $extra_parameters . "\">$lpm1</a>";
                $pagination.= "<a href=\"$targetpage?total_pages=$total_pages&page=$lastpage" . $extra_parameters ."\">$lastpage</a>";
            }
            //in middle; hide some front and some back
            elseif ($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2)) {

                $pagination.= "<a href=\"$targetpage?total_pages=$total_pages&page=1" . $extra_parameters . "\">1</a>";
                $pagination.= "<a href=\"$targetpage?total_pages=$total_pages&page=2" . $extra_parameters . "\">2</a>";
                $pagination.= "...";
                for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++) {
                    if ($counter == $page)
                        $pagination.= "<span class=\"current\">$counter</span>";
                    else
                        $pagination.= "<a href=\"$targetpage?total_pages=$total_pages&page=$counter" . $extra_parameters ."\">$counter</a>";
                }
                $pagination.= "...";
                $pagination.= "<a href=\"$targetpage?total_pages=$total_pages&page=$lpm1" . $extra_parameters . "\">$lpm1</a>";
                $pagination.= "<a href=\"$targetpage?total_pages=$total_pages&page=$lastpage" . $extra_parameters . "\">$lastpage</a>";
            }
            //close to end; only hide early pages
            else {

                $pagination.= "<a href=\"$targetpage?total_pages=$total_pages&page=1" . $extra_parameters ."\">1</a>";
                $pagination.= "<a href=\"$targetpage?total_pages=$total_pages&page=2" . $extra_parameters ."\">2</a>";
                $pagination.= "...";
                for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++) {
                    if ($counter == $page)
                        $pagination.= "<span class=\"current\">$counter</span>";
                    else
                        $pagination.= "<a href=\"$targetpage?total_pages=$total_pages&page=$counter" . $extra_parameters . "\">$counter</a>";
                }
            }
        }
        //next button
        if ($page < $counter - 1)
            $pagination.= "<a href=\"$targetpage?total_pages=$total_pages&page=$next" . $extra_parameters . "\">next»</a>";
        else
            $pagination.= "<span class=\"disabled\">next»</span>";
        $pagination.= "</div>\n";
    }
    return $pagination;
}

if (isset($_REQUEST['display_recs'])) 
{	
	$_SESSION['display_recs_company'] = $_REQUEST['display_recs'];
}

if (isset($_SESSION['display_recs_company']) and strlen($_SESSION['display_recs_company'])>0) 
{
	$_REQUEST['display_recs'] = $_SESSION['display_recs_company'];
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
    	        <?php include("menu.php"); ?>
        		<div class="container">
  			
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
                    		<form class="form-horizontal" method="post" enctype="multipart/form-data" action="companies.php" style="margin-bottom: 10px;">
                    			<div class="row" style="marign:0px;margin-top: 10px">
                    				<center><h2>Companies</h2></center>
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
        	                        	 				</td>
        	                        	 				<td align="right">		
        	                        	 					<form action="companies.php" method="POST" id="search_form">
        														<div class="form-group">
        															<label style="width:60px;text-align:right" for="search_value_company">Search:&nbsp;&nbsp;&nbsp;</label>
        	                        	 							<input type="text"  style="display:inline-block;width:180px"  class="form-control my-form-control"  id="search_value_company" name="search_value_company" value="<?php if(isset($_POST['search_value_company']) and strlen($_POST['search_value_company'])>0) {echo $_POST['search_value_company'];}?>">
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
        						      							<th style="width:30%"> 
        							                                <?php
        							                                if ($_GET['name'] == 'd') {
        							                                ?>
        							                                	<a href="companies.php?name=a">Name&nbsp;<img src="images/arrow_down.gif"> </a>
        							                                <?php
        															} else if ($_GET['name'] == 'a') {
        															?>
        																<a href="companies.php?name=d">Name&nbsp;<img src="images/arrow_up.gif"> </a>
        															<?php
        															} else {
        															?>
        																<a href="companies.php?name=d">Name&nbsp;</a>
        															<?php
        															}
        															?>
        							                            </th>
        							                            <th style="width:20%"> 
        							                                <?php
        							                                if ($_GET['type'] == 'd') {
        							                                ?>
        							                                	<a href="companies.php?type=a">Type&nbsp; <img src="images/arrow_down.gif"> </a>
        							                                <?php
        															} else if ($_GET['type'] == 'a') {
        															?>
        																<a href="companies.php?type=d">Type&nbsp; <img src="images/arrow_up.gif"> </a>
        															<?php
        															} else {
        															?>
        																<a href="companies.php?type=d">Type&nbsp; </a>
        															<?php
        															}
        															?>
        							                            </th>
        							                            <th style="width:20%"> 
        							                                <?php
        							                                if ($_GET['country'] == 'd') {
        							                                ?>
        							                                	<a href="companies.php?country=a">Country&nbsp; <img src="images/arrow_down.gif"> </a>
        							                                <?php
        															} else if ($_GET['country'] == 'a') {
        															?>
        																<a href="companies.php?country=d">Country&nbsp; <img src="images/arrow_up.gif"> </a>
        															<?php
        															} else {
        															?>
        																<a href="companies.php?country=d">Country&nbsp; </a>
        															<?php
        															}
        															?>
        							                            </th>
        							                             <th > 
        							                                <?php
        							                                if ($_GET['city'] == 'd') {
        							                                ?>
        							                                	<a href="companies.php?city=a">City&nbsp; <img src="images/arrow_down.gif"> </a>
        							                                <?php
        															} else if ($_GET['country'] == 'a') {
        															?>
        																<a href="companies.php?city=d">City&nbsp; <img src="images/arrow_up.gif"> </a>
        															<?php
        															} else {
        															?>
        																<a href="companies.php?city=d">City&nbsp; </a>
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
        						                        		<tr> <td colspan="4">Sorry, No Record Found.</td></tr>
        						                        	<?php
        								                    } else 
        								                    {
        								                       
        								                       
        								                        $sql_first_select = "select * from companies_info where is_deleted=0";
        								                        
        								                        $sql_select = $sql_first_select . $sqlpart . $order . " limit $offset,$pagesize";					                      
        								                        $result = mysqli_query($con, $sql_select) or die(mysqli_error($con));
        								                        while ($seerec = mysqli_fetch_assoc($result))
        								                        {								                        	
        						                                    ?>
        
        						                                    
        						                                    <tr>
        						                                    	
        						                                        <td style="width:30%"><label><a href="company.php?rid=<?php echo $seerec['auto_id']; ?>"><?php echo $seerec['name']; ?></a></label></td>
        						                                        <td style="color:green;width:20%"><?php echo $seerec['type']; ?></td>
        						                                        <td style="color:green;width:20%"><?php echo $seerec['country']; ?></td>
        						                                        <td style="color:green;width:20%"><?php echo $seerec['city']; ?></td>
        						                                       
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
        		  						<?php echo pagination(1, 'companies.php', $noofrecords, $pagesize, $page, $extra_parameters);?>
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

		