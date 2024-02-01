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

$sql = "select count(*) as totalCount from mail_inbox_info where mail_type != 'deleted'";

$order = " order by start_utime desc";


$res_sel = mysqli_query($con, $sql) or die(mysqli_error($con) . "11111");
$customer_count_array = mysqli_fetch_array($res_sel);
$noofrecords = $customer_count_array['totalCount'];


/* Pagination */
function pagination1($adjacents, $targetpage, $total_pages, $limit, $page, $extra_parameters) {
 
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
    	<?php include("layout.php");?>
	<?php include("menu.php");?>
    	
		<!-- main content -->
		<div class="container">
  			
  			<div id="my-main-content">  		
  				
            	<div class="my-home-content">     
            		<form class="form-horizontal" method="post" enctype="multipart/form-data" action="inbox.php" style="margin-bottom: 10px;">
            			<div class="row" style="marign:0px;margin-top: 10px">
            				<center><h2>Inbox</h2></center>
            			</div>
	            		
	            	
	            		<br>    		
	        			<div class="row">
	            			<div class="my-static-div">
	                        	<div class="panel my-panel">
	                        	 	<div class="panel-heading my-panel-heading" style="padding-bottom: 10px">                        	 		
	                        	 		<table style="width:100%">
	                        	 			<tr>
	                        	 				
	                        	 				<td style="width:80%">	                        	 					
	                        	 					<div style="padding:5px 0px 0px 0px" align="left" class="col-sm-1 col-xs-4">Show By&nbsp;&nbsp;</div>
													<div class="col-sm-11 col-xs-8" style="padding:4px 0px 0px 0px">
							                            <select onchange="ChangeDisplayCnt();" id="display_recs" name="display_recs">
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
							                        </div>
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
														<th style="width:30%">Subject</th>
														<th style="width:20%">From</th>
														<th style="width:20%">To</th>
														<th >Date</th>
						      						</tr>
						      					</thead>
						      					<tbody class="my-dashboard-table-font">
					      						<?php
					      							
								                    if ($noofrecords == 0) 	
								                    {
						                        	?>
						                        		<tr> <td colspan="4">Sorry, No Inbox.</td></tr>
						                        	<?php
								                    } else 
								                    {
								                        $sql_first_select = "select * from mail_inbox_info where mail_type != 'deleted'";
								                        $sql_select = $sql_first_select . $order . " limit $offset,$pagesize";					                      
								                        $result = mysqli_query($con, $sql_select) or die(mysqli_error($con));
								                        while ($seerec = mysqli_fetch_assoc($result))
								                        {								    
								                        	$from_info = str_replace('<','(',$seerec['from_nm']);
								                        	$from_info = str_replace('>',')',$from_info);
								                        	
						                                    ?>
						                                    <tr>
						                                    	
						                                        <td style="width:30%"><label><a href="mail_detail.php?type=inbox&uid=<?php echo $seerec['mail_uid']; ?>&mail_rcvr=<?php echo $seerec['mail_rcvr']; ?>"><?php echo $seerec['mail_subject']; ?></a></label></td>
						                                        <td style="width:20%"><?php echo $from_info; ?></td>
						                                        <td style="width:20%"><?php echo $seerec['to_name'].' ('.$seerec['mail_rcvr'].')'; ?></td>
						                                        <td style="width:20%"><?php echo $seerec['log_time']; ?></td>
						                                       
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
		  						<?php echo pagination1(1, 'inbox.php', $noofrecords, $pagesize, $page, $extra_parameters);?>
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
