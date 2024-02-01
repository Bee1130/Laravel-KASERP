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

// determine condition of selecting users

$sqlpart = '';
$filter_display_cnt = 0;

if ($_POST['clearform'] == "Clear")
{
	$_POST['assigned'] = "";
	$_POST['casefile'] = "";
	$_POST['condition'] = "";
	$_POST['country'] = "";
	$_POST['email'] = "";
	$_POST['name'] = "";
	$_POST['phone'] = "";
	$_POST['source'] = "";
	$_POST['status'] = "";
	$_POST['stock'] = "";
	$_POST['with_phones'] = "";
	$_POST['with_stocks'] = "";
	$_POST['with_emails'] = "";
	$_POST['not_assigned'] = "";
	$_POST['not_dialed'] = "";
	$_POST['number'] = "";	
	$_POST['order_by']="";
	
	$_SESSION['searchresult_assigned'] ='';
	$_SESSION['searchresult_casefile'] ='';
	$_SESSION['searchresult_condition'] ='';
	$_SESSION['searchresult_country'] ='';
	$_SESSION['searchresult_email'] ='';
	$_SESSION['searchresult_name'] ='';
	$_SESSION['searchresult_phone'] ='';
	$_SESSION['searchresult_source'] ='';
	$_SESSION['searchresult_status'] ='';
	$_SESSION['searchresult_stock'] ='';
	$_SESSION['searchresult_with_phones'] ='';
	$_SESSION['searchresult_with_stocks'] ='';
	$_SESSION['searchresult_with_emails'] ='';
	$_SESSION['searchresult_not_assigned'] ='';
	$_SESSION['searchresult_filter_display_cnt'] ='';
	$_SESSION['searchresult_sqlpart'] = '';
	$_SESSION['searchresult_order'] = '';
	$_SESSION['searchresult_order_by'] = '';
	
	$_SESSION['search_value']='';
	$_POST['search_value'] = '';
}



if ($_POST['filterform'] == "Filter")
{
	$_SESSION['search_value']='';
	$_POST['search_value'] = '';
	$_SESSION['searchresult_assigned'] ='';
	$_SESSION['searchresult_casefile'] ='';
	$_SESSION['searchresult_condition'] ='';
	$_SESSION['searchresult_country'] ='';
	$_SESSION['searchresult_email'] ='';
	$_SESSION['searchresult_name'] ='';
	$_SESSION['searchresult_phone'] ='';
	$_SESSION['searchresult_source'] ='';
	$_SESSION['searchresult_status'] ='';
	$_SESSION['searchresult_stock'] ='';
	$_SESSION['searchresult_with_phones'] ='';
	$_SESSION['searchresult_with_stocks'] ='';
	$_SESSION['searchresult_with_emails'] ='';
	$_SESSION['searchresult_not_assigned'] ='';
	$_SESSION['searchresult_filter_display_cnt'] ='';
	$_SESSION['searchresult_sqlpart'] = '';
	$_SESSION['searchresult_order'] = '';
	$_SESSION['searchresult_order_by'] = '';
	if (isset($_POST['assigned']) and strlen($_POST['assigned'])>0)
	{
		$sqlpart .= $sqlpart . " and (ass_worker like ('%" . trim($_POST['assigned']) . "%'))";
		$_SESSION['searchresult_assigned'] = $_POST['assigned'];
	}	
	if (isset($_POST['casefile']) and strlen($_POST['casefile'])>0)
	{
		$sqlpart .= $sqlpart . " and (casefile like ('" . trim($_POST['casefile']) . "'))";
		$_SESSION['searchresult_casefile'] = $_POST['casefile'];
	}
	if (isset($_POST['condition']) and strlen($_POST['condition'])>0)
	{
		$sqlpart .= $sqlpart . " and (leads_condition like ('" . trim($_POST['condition']) . "'))";
		$_SESSION['searchresult_condition'] = $_POST['condition'];
	}
	if (isset($_POST['country']) and strlen($_POST['country'])>0)
	{
		$sqlpart .= $sqlpart . " and (country like ('" . trim($_POST['country']) . "'))";
		$_SESSION['searchresult_country'] = $_POST['country'];
	}
	if (isset($_POST['email']) and strlen($_POST['email'])>0)
	{
		$sqlpart .= $sqlpart . " and (eml_address like ('%" . trim($_POST['email']) . "%'))";
		$_SESSION['searchresult_email'] = $_POST['email'];
	}
	if (isset($_POST['name']) and strlen($_POST['name'])>0)
	{
		$sqlpart .= $sqlpart . " and (name like ('%" . trim($_POST['name']) . "%'))";
		$_SESSION['searchresult_name'] = $_POST['name'];
	}
	if (isset($_POST['phone']) and strlen($_POST['phone'])>0)
	{
		$sqlpart .= $sqlpart . " and (ph_number like ('%" . trim($_POST['phone']) . "%'))";
		$_SESSION['searchresult_phone'] = $_POST['phone'];
	}
	if (isset($_POST['source']) and strlen($_POST['source'])>0)
	{
		$sqlpart .= $sqlpart . " and (source like ('" . trim($_POST['source']) . "'))";
		$_SESSION['searchresult_source'] = $_POST['source'];
	}
	if (isset($_POST['status']) and strlen($_POST['status'])>0)
	{
		$sqlpart .= $sqlpart . " and (status like ('" . trim($_POST['status']) . "'))";
		$_SESSION['searchresult_status'] = $_POST['status'];
	}
	if (isset($_POST['stock']) and strlen($_POST['stock'])>0)
	{
		$sqlpart .= $sqlpart . " and (st_name like ('%" . trim($_POST['stock']) . "%'))";
		$_SESSION['searchresult_stock'] = $_POST['stock'];
	}
	if (isset($_POST['with_phones']) and ($_POST['with_phones']=='on'))
	{
		$sqlpart .= $sqlpart . " and (ph_number is not null) and (length(ph_number)>0) ";
		$_SESSION['searchresult_with_phones'] = $_POST['with_phones'];
	}
	if (isset($_POST['with_stocks']) and ($_POST['with_stocks']=='on'))
	{
		$sqlpart .= $sqlpart ." and (st_name is not null) and (length(st_name)>0) ";
		$_SESSION['searchresult_with_stocks'] = $_POST['with_stocks'];
	}
	if (isset($_POST['with_emails']) and ($_POST['with_emails']=='on'))
	{
		$sqlpart .= $sqlpart ." and (eml_address is not null) and (length(eml_address)>0) ";
		$_SESSION['searchresult_with_emails'] = $_POST['with_emails'];
	}
	/*if (isset($_POST['not_dialed']) and ($_POST['not_dialed']=='on'))
{
	$sqlpart .= $sqlpart . " and (ass_worker is null)";
}*/
	if (isset($_POST['not_assigned']) and ($_POST['not_assigned']=='on'))
	{
		$sqlpart .= $sqlpart . " and ((ass_worker is null) or (length(ass_worker)=0))";
		$_SESSION['searchresult_not_assigned'] = $_POST['not_assigned'];
	}
	
	if (isset($_POST['count']) and strlen($_POST['count'])>0)
	{
		$filter_display_cnt = (int)$_POST['count'];
		$_SESSION['searchresult_filter_display_cnt'] = $_POST['count'];
	}
	if (isset($_POST['order_by']) and ($_POST['order_by']=='Name'))
	{	
		$order = " order by name";
		$_SESSION['searchresult_order_by'] = $_POST['order_by'];
	}

	if (isset($_POST['order_by']) and ($_POST['order_by']=='Casefile'))
	{	
		$order = " order by casefile";
		$_SESSION['searchresult_order_by'] = $_POST['order_by'];
	}

	if (isset($_POST['order_by']) and ($_POST['order_by']=='Country'))
	{	
		$order = " order by country";
		$_SESSION['searchresult_order_by'] = $_POST['order_by'];
	}

	if (isset($_POST['order_by']) and ($_POST['order_by']=='Status'))
	{	
		$order = " order by status";
		$_SESSION['searchresult_order_by'] = $_POST['order_by'];
	}

	if (isset($_POST['order_by']) and ($_POST['order_by']=='Condition'))
	{	
		$order = " order by leads_condition";
		$_SESSION['searchresult_order_by'] = $_POST['order_by'];
	}

	$_SESSION['searchresult_sqlpart'] = $sqlpart;
	$_SESSION['searchresult_order'] = $order;
}else
{
	
	$_POST['assigned'] = $_SESSION['searchresult_assigned'];
	$_POST['casefile'] = $_SESSION['searchresult_casefile'];
	$_POST['condition'] = $_SESSION['searchresult_condition'];
	$_POST['country'] = $_SESSION['searchresult_country'];
	$_POST['email'] = $_SESSION['searchresult_email'];
	$_POST['name'] = $_SESSION['searchresult_name'];
	$_POST['phone'] = $_SESSION['searchresult_phone'];
	$_POST['source'] = $_SESSION['searchresult_source'];
	$_POST['status'] = $_SESSION['searchresult_status'];
	$_POST['stock'] = $_SESSION['searchresult_stock'];
	$_POST['with_phones'] = $_SESSION['searchresult_with_phones'];
	$_POST['with_stocks'] = $_SESSION['searchresult_with_stocks'];
	$_POST['with_emails'] = $_SESSION['searchresult_with_emails'];
	$_POST['not_assigned'] = $_SESSION['searchresult_not_assigned'];	
	$_POST['count'] = $_SESSION['searchresult_filter_display_cnt'];
	$_POST['order_by'] = $_SESSION['searchresult_order_by'];
	$filter_display_cnt = (int)$_POST['count'];
	$sqlpart=$_SESSION['searchresult_sqlpart'];
	$order=$_SESSION['searchresult_order'];
}

if (isset($_POST['search_value']) and strlen($_POST['search_value'])>0)
{
	$tmp_val = trim($_POST['search_value']);
	$_SESSION['search_value'] = $tmp_val;
}

if (isset($_SESSION['search_value']) and strlen($_SESSION['search_value'])>0)
{
	$tmp_val = $_SESSION['search_value'];
	$sqlpart .= " and ((name like ('%" . $tmp_val . "%')) or (casefile like ('%". $tmp_val."%')) or (country like ('%". $tmp_val."%')) or (status like ('%". $tmp_val."%')) or (leads_condition like ('%". $tmp_val."%')))";
	$_POST['search_value'] = $tmp_val;
}


$sql_first = "select count(distinct lead_id) as totalCount from simple_leads_info where is_deleted=0".$_SESSION['filter_search_user'];

/*if (strlen($sqlpart)>0)
	$_SESSION['searchresult_sqlpart'] = $sqlpart;

if (strlen($_SESSION['searchresult_sqlpart'])>0)
	$sqlpart = $_SESSION['searchresult_sqlpart'] ;*/
									                        	
$sql_cnt = $sql_first . $sqlpart;

$sql = $sql_first.$sqlpart;

$res_sel = mysqli_query($con, $sql) or die(mysqli_error($con) . "11111");
$customer_count_array = mysqli_fetch_array($res_sel);
$noofrecords = $customer_count_array['totalCount'];
if (($filter_display_cnt>0) and ($noofrecords > $filter_display_cnt))
	$noofrecords = $filter_display_cnt;
	
$_SESSION['total_records']=$noofrecords;	

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
	$extra_parameters .= "&display_recs=20";
	
if ($pagesize==0)
	$pagesize = 20;
	
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
						
            function submitForm()
            {
                $("#btnChangeSubmit").click();
            }
            
           	$(document).ready(function () {
           	
				$("#panel-fullscreen-Entries").click(function (e) {
			        $(this).closest('.panel').toggleClass('panel-fullscreen');
			    });		
            });       		
       		
		
			/**
			* Assign to leads
			*/
			function OnAssignTo(assign_id)
			{
				console.log("OnAssignTo");
				
				var filter_part = document.getElementById('filter_part').value;
				var filter_offset = document.getElementById('filter_offset').value;
				var filter_count = document.getElementById('filter_count').value;
				var filter_group = document.getElementById('filter_group').value;
				var filter_order = document.getElementById('filter_order').value;
								
				var data = {										
					"assign_id":assign_id,
					"filter_part":filter_part,
					"filter_offset":filter_offset,
					"filter_count":filter_count,
					"filter_group":filter_group,
					"filter_order":filter_order
				};
				console.log(data);       			
			    $.ajax({
			        url: 'assignTocontacts.php',
			        data: data,
			        type:"POST",
					dataType : "json",
			        success: function ( res ) {
			        	console.log("Success");
			        	console.log(res);
			        	if (res.status == "Success")
			        	{
			        		var str = res.assigned_leads + ' leads are asssigned!';
			        		alert(str);			        		
						}							
			        }
			    });
			    
			}
			
			/**
			* DeAssign to leads
			*/
			function OnDeAssignTo(assign_id)
			{
				console.log("OnAssignTo");
				var filter_part = document.getElementById('filter_part').value;
				var filter_offset = document.getElementById('filter_offset').value;
				var filter_count = document.getElementById('filter_count').value;
				var filter_group = document.getElementById('filter_group').value;
				var filter_order = document.getElementById('filter_order').value;
								
				var data = {										
					"assign_id":assign_id,
					"filter_part":filter_part,
					"filter_offset":filter_offset,
					"filter_count":filter_count,
					"filter_group":filter_group,
					"filter_order":filter_order
				};
				console.log(data);       			
			    $.ajax({
			        url: 'deassignTocontacts.php',
			        data: data,
			        type:"POST",
					dataType : "json",
			        success: function ( res ) {
			        	console.log("Success");
			        	console.log(res);
			        	if (res.status == "Success")
			        	{
			        		var str = res.unassigned_leads + ' leads are deasssigned!';
			        		alert(str);		
			        			        		
						}							
			        }
			    });
			}
			
        </script>       
		<style type="text/css">
			li {cursor: pointer}
		</style>
    </head>
    <body>
    	
    	
    	<?php include("layout.php");?>
	<?php include("menu.php");?>
    	
		<!-- main content -->
		<div class="container">
        	<div class="my-home-content">     
        		<form class="form-horizontal" method="post" enctype="multipart/form-data" action="searchresult.php" style="margin-bottom: 10px;">
        			<div class="row" style="marign:0px;margin-top: 10px">
        				<center><h2>Client's search</h2></center>
        			</div>
            		
            		<br>     		
        			<div class="row">
		                <!-- begin col-12 -->
		                <div class="col-md-12">
		                    <!-- begin panel -->

		                    <div class="panel panel-inverse">
		                        <div class="panel-heading">		                           
		                            <h4 class="panel-title">
		                            	Entries
								        <div class="panel-heading-btn">
					                        <a href="#" id="panel-fullscreen-Entries" role="button"  class="btn btn-xs btn-icon btn-circle btn-default" title="Toggle fullscreen"><i class="fa fa-expand"></i></a>
					                    </div> 
								       
				                   		<div class="pull-right">
			                             
			                               
			                                <div id="assign" class="btn-group">
			                                    <button aria-expanded="false" type="button" class="btn btn-success btn-xs dropdown-toggle" data-toggle="dropdown" style="margin-top: -2px;border-radius:3px">Assign
			                                        <span class="caret"></span>
			                                    </button>
			                                    <ul class="dropdown-menu dropdown-menu-right" role="menu">
			                                    	<li><a onclick="javascript:OnAssignTo('all')">All</a></li>
			                                    <?php
			                              	      	$sql_sel = "select auto_id,worker from assigned_worker_info";
                                                    $sql_res = mysqli_query($con, $sql_sel) or die(mysqli_error($con) . "go select error");
                                                    while ($sql_rec = mysqli_fetch_assoc($sql_res)) 
                                                    {		                                                    	
                                                    	$text = $sql_rec['worker'];
                                                    	$assigend_id = $sql_rec['auto_id'];
                                                    ?>
                                                    	<li><a onclick="javascript:OnAssignTo('<?php echo $assigend_id?>')"><?php echo $text;?></a></li>
                                                	<?php
                                                    }
			                                    ?>
			                                    </ul>
			                                </div>
			                                <div id="deassign" class="btn-group">
			                                    <button aria-expanded="false" type="button" class="btn btn-success btn-xs dropdown-toggle" data-toggle="dropdown" style="margin-top: -2px;border-radius:3px">Deassign
			                                        <span class="caret"></span>
			                                    </button>
			                                    <ul class="dropdown-menu dropdown-menu-right" role="menu">
			                                    	<li><a onclick="javascript:OnDeAssignTo('all')">All</a></li>
			                                    <?php
			                              	      	$sql_sel = "select auto_id,worker from assigned_worker_info";
                                                    $sql_res = mysqli_query($con, $sql_sel) or die(mysqli_error($con) . "go select error");
                                                    while ($sql_rec = mysqli_fetch_assoc($sql_res)) 
                                                    {		                                                    	
                                                    	$text = $sql_rec['worker'];
                                                    	$assigend_id = $sql_rec['auto_id'];
                                                    ?>
                                                    	<li><a onclick="javascript:OnDeAssignTo('<?php echo $assigend_id?>')"><?php echo $text;?></a></li>
                                                	<?php
                                                    }
			                                    ?>
			                                    </ul>
			                                </div>
			                               <!--  <div id="assigned" class="btn-group open">
			                                    <button aria-expanded="false" type="button" class="btn btn-success btn-xs dropdown-toggle" data-toggle="dropdown" style="margin-top: -2px;border-radius:3px">Assign
			                                        <span class="caret"></span>
			                                    </button>
			                                    <ul class="dropdown-menu dropdown-menu-right" role="menu">
	                                       		 <?php
			                              	      	$sql_sel = "select auto_id,worker from assigned_worker_info";
                                                    $sql_res = mysqli_query($con, $sql_sel) or die(mysqli_error($con) . "go select error");
                                                    while ($sql_rec = mysqli_fetch_assoc($sql_res)) 
                                                    {		                                                    	
                                                    	$text = $sql_rec['worker'];
                                                    	$assigend_id = $sql_rec['auto_id'];
                                                    ?>
                                                    	<li><a onclick="javascript:OnAssignTo('<?php echo $assigend_id?>')"><?php echo $text;?></a></li>
                                                	<?php
                                                    }
			                                    ?>
			                                       
			                                    </ul>
			                                </div>-->
			                            </div>
			                   		</h4>
		                        </div>
		                        <div class="panel-body">
		                            <div class="vertical-box">
		                                <div class="col-sm-3 width-sm p-15 bg-silver" style="max-width: 300px !important">
			                                <form id="filterform" class="form-horizontal" method="POST" action="searchresult.php">
			                                   <h5 class="m-t-0">Filters</h5>
			                                   
			                                   <div class="form-group">
			                                        <label class="col-md-4 control-label">Options</label>
			                                        <div class="col-md-8">
			                                            <div class="checkbox">
			                                                <label>
			                                                    <input name="with_phones" type="checkbox"  <?php 
																		if ($_POST['with_phones'] == 'on') {
																				echo 'checked';
																		}?>>
			                                                    With phones
			                                                </label>
			                                            </div>
			                                            <div class="checkbox">
			                                                <label>
			                                                    <input name="with_emails" type="checkbox" <?php 
																		if ($_POST['with_emails'] == 'on') {
																				echo 'checked';
																		}?>>
			                                                    With emails
			                                                </label>
			                                            </div>
			                                            <div class="checkbox">
			                                                <label>
			                                                    <input name="with_stocks" type="checkbox" <?php 
																		if ($_POST['with_stocks'] == 'on') {
																				echo 'checked';
																		}?>>
			                                                    With stocks
			                                                </label>
			                                            </div>
			                                            <div class="checkbox">
			                                                <label>
			                                                    <input name="not_assigned" type="checkbox" <?php 
																		if ($_POST['not_assigned'] == 'on') {
																				echo 'checked';
																		}?>>
			                                                    Not assigned
			                                                </label>
			                                            </div>
			                                            <div class="checkbox">
			                                                <label>
			                                                    <input name="not_dialed" type="checkbox" <?php 
																		if ($_POST['not_dialed'] == 'on') {
																				echo 'checked';
																		}?>>
			                                                    Robocall not dialed
			                                                </label>
			                                            </div>
			                                        </div>
			                                   </div>
			                                   <input type="hidden" class="form-control " id="search_value" name="search_value" style="height:34px !important" placeholder="Name or casefile" value="">
			                                   <div class="form-group">
				                                    <label class="col-md-4 control-label">Name</label>
				                                    <div class="col-md-8">  
				                                        <input type="text" class="form-control input-sm" name="name" value="<?php if (isset($_POST['name'])) echo $_POST['name'];?>">
				                                    </div>
				                                </div>
				                               <div class="form-group">
				                                    <label class="col-md-4 control-label">Casefile</label>
				                                    <div class="col-md-8">
				                                        <input type="number" class="form-control input-sm" name="casefile" value="<?php if (isset($_POST['casefile'])) echo $_POST['casefile'];?>">
				                                    </div>
				                                </div>
			                                
				                               <div class="form-group">
				                                    <label class="col-md-4 control-label">Country</label>
				                                    <div class="col-md-8">
				                                        <select class="form-control input-sm" name="country">
				                                        	<option selected=""></option>
				                                      	<?php
					                                    	$sql_sel = "select * from country_info";
		                                                    $sql_res = mysqli_query($con, $sql_sel) or die(mysqli_error($con) . "go select error");
		                                                    while ($sql_rec = mysqli_fetch_assoc($sql_res)) 
		                                                    {
		                                                    	$value = $sql_rec['value'];
		                                                    	$text = $sql_rec['text'];
		                                                    ?>
		                                                    	<option value="<?php echo $text;?>" <?php 
																		if ($_POST['country'] == $text) {
																				echo 'selected';
																		}?>><?php echo $text;?></option>
		                                                	<?php
		                                                    }
					                                    ?>
				                                        </select>
				                                    </div>
				                                </div>
				                               <div class="form-group">
				                                    <label class="col-md-4 control-label">Phone</label>
				                                    <div class="col-md-8">
				                                        <input type="text" class="form-control input-sm" name="phone" value="<?php if (isset($_POST['phone'])) echo $_POST['phone'];?>">
				                                    </div>
				                                </div>
				                               <div class="form-group">
				                                    <label class="col-md-4 control-label">Email</label>
				                                    <div class="col-md-8">
				                                        <input type="text" class="form-control input-sm" name="email" value="<?php if (isset($_POST['email'])) echo $_POST['email'];?>">
				                                    </div>
				                                </div>
				                               <div class="form-group">
				                                    <label class="col-md-4 control-label">Stock</label>
				                                    <div class="col-md-8">
				                                        <input type="text" class="form-control input-sm" name="stock" value="<?php if (isset($_POST['stock'])) echo $_POST['stock'];?>">
				                                    </div>
				                                </div>

				                               <div class="form-group">
				                                    <label class="col-md-4 control-label">Status</label>
				                                    <div class="col-md-8">
				                                        <select class="form-control input-sm" name="status">
				                                        	 <option selected=""></option>
				                                        <?php
					                                    	$sql_sel = "select * from status_info";
		                                                    $sql_res = mysqli_query($con, $sql_sel) or die(mysqli_error($con) . "go select error");
		                                                    while ($sql_rec = mysqli_fetch_assoc($sql_res)) 
		                                                    {
		                                                    	$value = $sql_rec['value'];
		                                                    	$text = $sql_rec['text'];
		                                                    ?>
		                                                    	<option value="<?php echo $text;?>" <?php 
																		if ($_POST['status'] == $text) {
																				echo 'selected';
																		}?>><?php echo $text;?></option>
		                                                	<?php
		                                                    }
					                                    ?>						
				                                            
				                                        </select>
				                                    </div>
				                                </div>
 				                               <div class="form-group">
				                                    <label class="col-md-4 control-label">Condition</label>
				                                    <div class="col-md-8">
				                                        <select class="form-control input-sm" name="condition">
				                                        	 <option selected=""></option>
				                                        <?php
					                                    	$sql_sel = "select * from condition_info";
		                                                    $sql_res = mysqli_query($con, $sql_sel) or die(mysqli_error($con) . "go select error");
		                                                    while ($sql_rec = mysqli_fetch_assoc($sql_res)) 
		                                                    {
		                                                    	$value = $sql_rec['value'];
		                                                    	$text = $sql_rec['text'];
		                                                    ?>
		                                                    	<option value="<?php echo $text;?>" <?php 
																		if ($_POST['condition'] == $text) {
																				echo 'selected';
																		}?>><?php echo $text;?></option>
		                                                	<?php
		                                                    }
					                                    ?>	
				                                          
				                                        </select>
				                                    </div>
				                                </div>
				                               <div class="form-group">
				                                    <label class="col-md-4 control-label">Source</label>
				                                    <div class="col-md-8">
				                                        <select class="form-control input-sm" name="source">
				                                        	 <option selected=""></option>
				                                        <?php
					                                    	$sql_sel = "select * from source_info";
		                                                    $sql_res = mysqli_query($con, $sql_sel) or die(mysqli_error($con) . "go select error");
		                                                    while ($sql_rec = mysqli_fetch_assoc($sql_res)) 
		                                                    {
		                                                    	$value = $sql_rec['value'];
		                                                    	$text = $sql_rec['text'];
		                                                    ?>
		                                                    	<option value="<?php echo $text;?>" <?php 
																		if ($_POST['source'] == $text) {
																				echo 'selected';
																		}?>><?php echo $text;?></option>
		                                                	<?php
		                                                    }
					                                    ?>
				                                        </select>
				                                    </div>
				                                </div>

				                               <div class="form-group">
				                                    <label class="col-md-4 control-label">Assigned</label>
				                                    <div class="col-md-8">
				                                    	 <select class="form-control input-sm" name="assigned">
				                                            <option selected=""></option>
				                                        <?php
					                                    	$sql_sel = "select worker from assigned_worker_info";
		                                                    $sql_res = mysqli_query($con, $sql_sel) or die(mysqli_error($con) . "go select error");
		                                                    while ($sql_rec = mysqli_fetch_assoc($sql_res)) 
		                                                    {		                                                    	
		                                                    	$text = $sql_rec['worker'];
		                                                    ?>
		                                                    	<option value="<?php echo $text;?>" <?php 
																		if ($_POST['assigned'] == $text) {
																				echo 'selected';
																		}?>><?php echo $text;?></option>
		                                                	<?php
		                                                    }
					                                    ?>
				                                        </select>
				                                    </div>
				                                </div>
				                               <div class="form-group">
				                                    <label class="col-md-4 control-label">Order by</label>
				                                    <div class="col-md-8">
				                                        <select class="form-control input-sm" name="order_by">
				                                            <option selected=""></option>
				                                            <option value="Name" <?php if ($_POST['order_by'] == 'Name') echo 'selected'; ?>>Name</option>
				                                            <option value="Casefile" <?php if ($_POST['order_by'] == 'Casefile') echo 'selected'; ?>>Casefile</option>
				                                            <option value="Country" <?php if ($_POST['order_by'] == 'Country') echo 'selected'; ?>>Country</option>
				                                            <option value="Status" <?php if ($_POST['order_by'] == 'Status') echo 'selected'; ?>>Status</option>
				                                            <option value="Condition" <?php if ($_POST['order_by'] == 'Condition') echo 'selected'; ?>>Condition</option>
				                                        </select>
				                                    </div>
				                                </div>
				                               <div class="form-group">
				                                    <label class="col-md-4 control-label">Count</label>
				                                    <div class="col-md-8">
				                                      <input type="number" class="form-control input-sm" name="count" value="<?php if (isset($_POST['count'])) echo $_POST['count'];?>">
				                                    </div>
				                                </div>
				                                
				                               <div class="form-group">
				                                    <div class="col-md-12">
			                                           
			                                            <button type="submit" id="filterform" name="filterform" value= "Filter"  class="btn btn-primary btn-block btn-sm"><i class="fa fa-refresh m-r-3"></i>Filter</button>
			                                             <button type="submit" id="clearform" name="clearform" value= "Clear" class="btn btn-default btn-block btn-sm"><i class="fa fa-trash m-r-3"></i>Clear</button>
				                                    </div>
				                               </div>
			                           		</form>
			                        	</div>
			                            <div class="p-15">
			                            	<div class="table-responsive" style="padding-left: 10px">
			                                    <table id="data-table" class="table table-condensed table-striped table-hover">
			                                        <thead>
			                                            <tr>
			                                                <th>Name</th>
			                                                <th>Casefile</th>
			                                                <th>Country</th>
			                                                <th>Status</th>
			                                                <th>Condition</th>
			                                            </tr>
			                                        </thead>
			                                        <tbody style="font-size: 14px;">
			                                        <?php
				                                		// real records					                   	
									                    if ($noofrecords == 0) 	
									                    {
							                        	?>
							                        		<tr> <td colspan="5">Sorry, No Clients Found.</td></tr>
							                        	<?php
									                    } else 
									                    {
									                       
									                        $group = ' group by lead_id';
									                        $sql_first_select = "select * from simple_leads_info where is_deleted=0".$_SESSION['filter_search_user'];	                        
											
															if ($filter_display_cnt > 0)
																$filter_display_cnt = $filter_display_cnt-$pagesize*($page-1);
															
															$pg_size= 0;
															
															
															if ($filter_display_cnt >= $pagesize)
															{												 
																 $pg_size = $pagesize;
															}else if ($filter_display_cnt > 0)
															{											
																 $pg_size = $filter_display_cnt;
																 $filter_display_cnt = 0;
															}else if (($filter_display_cnt == 0) and ($from_no == 0))
															{											
																 $pg_size = $pagesize;
															}
											
									                        if ($filter_display_cnt>0 and $pagesize>$filter_display_cnt)
									                        	$pagesize = $filter_display_cnt;
									                        	
									                        $sql_select = $sql_first_select . $sqlpart . $group. $order." limit $offset,$pg_size";	
									                        			                      
									                        $result = mysqli_query($con, $sql_select) or die(mysqli_error($con));
									                        while ($seerec = mysqli_fetch_assoc($result))
									                        {                                                                     
									                        ?>
						                                    <tr>
						                                        <td ><label><a href="contact.php?rid=<?php echo $seerec['lead_id']; ?>"><?php echo $seerec['name']; ?></a></label></td>
						                                        <td><?php echo $seerec['casefile']; ?></td>
						                                        <td><?php echo $seerec['country']; ?></td>
						                                        <td><?php echo $seerec['status']; ?></td>	
						                                        <td><?php echo $seerec['leads_condition']; ?></td>
						                                    </tr>
						                                   
															<?php        															
															}															
														}
													?>	
			                                        </tbody>
			                                    </table>
			                                </div>
			                                <div class="row mobile-row">
			                                	<input  type="hidden"  name="filter_part" id="filter_part" value="<?php echo $sqlpart;?>">
			                                    <input  type="hidden"  name="filter_offset" id="filter_offset" value="<?php echo $offset;?>">
			                                    <input  type="hidden"  name="filter_pgsize" id="filter_pgsize" value="<?php echo $pg_size;?>">
			                                    <input  type="hidden"  name="filter_order" id="filter_order" value="<?php echo $order;?>">
			                                    <input  type="hidden"  name="filter_group" id="filter_group" value="<?php echo $group;?>">
			                                    <input  type="hidden"  name="filter_count" id="filter_count" value="<?php echo $_SESSION['searchresult_filter_display_cnt'];?>">
							  					<ul class="pager" style="margin: 0px">
							  						<?php echo pagination(1, 'searchresult.php', $noofrecords, $pagesize, $page, $extra_parameters);?>
							  					</ul>  				
							  				</div>    
			                                
			                            </div>
			                        </div>
		                    	</div>
		                    <!-- end panel -->
		               		</div>
		                <!-- end col-12 -->
		            	</div>
		            </div>
				</form>				    
        	</div>	
           
		</div>            
		</div>
	</div>
    </body>
  
</html>

		