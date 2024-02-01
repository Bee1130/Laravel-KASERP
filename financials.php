<?php
//phpinfo();
/* * **-------------------------------------------------------------------**************************    

  Purpose     : 	Buyer Information Detail Page

  Project 	:	Sales Financial DB

  Developer 	: 	Wilson Tan

  Create Date : 	05/10/2016

 * ***-------------------------------------------------------------------*********************** */
session_start();
if (!isset($_SESSION['user_login']) and ! isset($_COOKIE['cookie_login'])) {//session store admin name
    header("Location: index.php"); //login in AdminLogin.php
}
require_once("includes/dbconnect.php");

$next_id = -1;
$prev_id = -1;
$submit = '';
if(isset($_POST['Submit'])) {
	$submit = $_POST['Submit'];
}

if ($submit == "Save") 
{
   // delete old SITE STAFF
	$sql_del = "truncate financial_info";
	mysqli_query($con,$sql_del) or die(mysqli_error($con));

	// insert new one
	foreach ($_POST['name'] as $i => $name)
	{
		/*if (strlen($name)>0)
		{*/
			$type = $_POST['type'][$i];	
			$rate = getRealValue($_POST['rate'][$i]);	
			$days = getRealValue($_POST['days'][$i]);	
			$rxd = getRealValue($_POST['rxd'][$i]);	
			$admin = getRealValue($_POST['admin'][$i]);	
			$rural_tx = getRealValue($_POST['rural_tx'][$i]);	
			$vans = getRealValue($_POST['vans'][$i]);	
			$deduction = getRealValue($_POST['deduction'][$i]);	
			$total_wage = getRealValue($_POST['total_wage'][$i]);	
			$amount_paid = getRealValue($_POST['amount_paid'][$i]);	
			$outstanding = getRealValue($_POST['outstanding'][$i]);	
			
			$due_date = my_date_format($_POST['due_date'][$i],'Y-m-d');
			$notes = mysqli_real_escape_string($con,$_POST['notes'][$i]);	
			
			
			$sql_ins= sprintf("insert into financial_info (type,name,rate,days,rxd,admin,rural_tx,vans,deduction,total_wage,amount_paid,outstanding,due_date,notes) values ('%s','%s','%f','%d','%f','%f','%f','%f','%f','%f','%f','%f','%s','%s')",$type,$name,$rate,$days,$rxd,$admin,$rural_tx,$vans,$deduction,$total_wage,$amount_paid,$outstanding,$due_date,$notes);
			mysqli_query($con,$sql_ins) or die(mysqli_error($con));	
		/*}*/
	}
    
	
	// Save log
	saveLog($_GET['rid'],'Financial','Financial','changed');
	
    header("Location: financials.php");
    exit();
      
}


if ($submit == "Cancel") {
	
    header("Location: financial.php");
    exit();
	
}	

// get latitude, longitude and formatted address
if (isset($recb['address']) and strlen($recb['address'])>0) 
{
    $data_arr = geocode($recb['address']);	
}

function DisplayGBP($val)
{
	$val = trim($val);
	if ($val>0)
	{
		//$val = '<i class="fa fa-gbp" aria-hidden="true"></i> '.number_format($val,2);
		$val = '£ '.number_format($val,2);
		
		echo $val;
	}else
		echo '';
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
    <head>
    	<?php include("header.php");?>
        
        <!--<script type="text/javascript" src="popcalendar.js"></script>-->
		<script type="text/javascript" src="js/jquery.cookie.js"></script>
	    <link href="css/jquery.datetimepicker.css" rel="stylesheet" />
		<script src="js/jquery.datetimepicker.js"></script>
		
		<style type="text/css">
			#gmap_canvas{
				width:100%;
				height:30em;
			}
			
			#map-label,
			#address-examples{
				margin:1em 0;
			}
			.bold {
			    font-weight: bold;
			}
			
			.margin_10 {
			    margin: 10px;
			}
			
			.create-skus-form {
			    font-size: 12px;
			    color: black;
			}
			.create-skus-form textarea {
			    width: 100%;
			    float: left;
			    font-weight: normal;
			    color: black;
			    font-size: 11px;
			    padding-left: 3px;
			    margin-left: 0px;
			    height: 26px;
			    
			}
			
		
			.create-skus-form input {
			    width: 100%;
			    float: left;
			    font-weight: normal;
			    color: black;
			    font-size: 11px;
			    padding-left: 3px;
			     margin-left: 0px;
			    height: 26px;
			    border: 0px;
			}
			td, th{
				
				padding:2px;
			}

		</style>
	    <script type="text/javascript">
	      
            $(document).ready(function () { 
			    $("#panel-fullscreen-Main").click(function (e) {
			        $(this).closest('.panel').toggleClass('panel-fullscreen');
			    });
			   
			    $("#panel-fullscreen-Meeting").click(function (e) {
			        $(this).closest('.panel').toggleClass('panel-fullscreen');
			    });
			    $("#panel-fullscreen-Notes").click(function (e) {
			        $(this).closest('.panel').toggleClass('panel-fullscreen');
			    });
			    
				
				
				
				$('.datetimePicker').datetimepicker(							
			     {
		            dayOfWeekStart: 0,
		            format: 'm/d/Y',
		            hour: '7:00 AM',
		            step: 30,
		            formatTime: 'g:i A',
		            allowTimes: ['7:00 AM', '7:30 AM', '8:00 AM', '8:30 AM', '9:00 AM', '9:30 AM', '10:00 AM', '10:30 AM', '11:00 AM', '11:30 AM', '12:00 PM', '12:30 PM', '1:00 PM', '1:30 PM', '2:00 PM', '2:30 PM', '3:00 PM', '3:30 PM', '4:00 PM', '4:30 PM', '5:00 PM', '5:30 PM', '6:00 PM', '6:30 PM', '7:00 PM'],
		        });
				
            });
			
        </script>   
    </head>
    <body>
		<?php
			$rid = isset($_GET['rid'])?$_GET['rid']: '';
			// var_dump($rid);die;
		?>
        <script type="text/javascript" src="popcalendar.js"></script>
        <div>	    	
	    	<input type="hidden" name="sel_customer_id" id ="sel_customer_id" value="<?php echo $_SESSION['user_login'].':'.$rid?>">	    	
    	</div>
    	
    	<div class="container">
    	    <?php include("sidebar.php"); ?>
    	    <div class="main-content">
        		<div class="container">
        		    <?php include('menu.php'); ?>			
        			<div id="my-main-content">				
        				<br>
        				<ol class="breadcrumb pull-right" style="margin-bottom: 5px;">
        				  <?php
                        	if ($next_id != -1)
                        	{
                        	?>
        					<li><a href="financials.php?rid=<?php echo $next_id;?>">Previous</a></li>	
        					<?php
        					}
                        ?>
                      
                        <?php
                        	if ($prev_id != -1)
                        	{
                        	?>
        					<li><a href="financials.php?rid=<?php echo $prev_id;?>">Next</a></li>	
        					<?php
        					}
                        ?>  
               			</ol>
        								
        				<!-- Main content -->
        				
                    	<div class="row">       
                    		<div class="col-md-12 col-lg-12" style="padding: 0px"> 
                    			<form action="financials.php" method="post" enctype="multipart/form-data" id="CalcWageForm">
                    				<div class="panel panel-inverse" data-sortable-id="ui-general-7">
        								<div class="panel-heading">
        				                	<h4 class="panel-title">
        				                		<a>Financials</a>
        								        <div class="panel-heading-btn">
        					                        <a href="#" id="panel-fullscreen-Meeting" role="button"  class="btn btn-xs btn-icon btn-circle btn-default" title="Toggle fullscreen"><i class="fa fa-expand"></i></a>
        					                        <a href="#collapseMeeting" data-toggle="collapse" data-target="#collapseMeeting" class="btn btn-xs btn-icon btn-circle btn-warning collapsed" ><i class="fa fa-minus"></i></a>
        					                    </div> 
        								        <div class="pull-right">
        											<button class="btn btn-xs btn-success" style="font-size: 14px;font-weight: normal;padding-top: 0px;  padding-bottom: 0px;" type="submit" name="Submit" id="btn_save" value="Save">Save</button>  
        				                   		</div>
        								    </h4>
        				                </div>
        				                <div id="collapseMeeting"  class="panel-collapse collapse in">
        				                	<div class="panel-body animated fadeIn" id="Meeting" style="padding: 0px">		
        				                		<div class="row table_placeholder">
        					                    	<div class="table-responsive" style="background-color: #f3f5ef">
        												<table width="99%"  class="create-skus-form margin_10 bold" id="direct_basic_tbl">
        													<thead style="text-transform: uppercase;">
        														<tr>														
        															<th style="text-align: center;width:10%;border: 1px solid gray"></th>
        															<th style="text-align: center;width:10%;border: 1px solid gray">Name</th>
        															<th style="text-align: center;border: 1px solid gray">Rate</th>
        															<th style="text-align: center;width:6%;border: 1px solid gray">DAYS</th>
        															<th style="text-align: center;width:6%;border: 1px solid gray">RXD</th>
        															<th style="text-align: center;width:6%;border: 1px solid gray">ADMIN</th>
        															<th style="text-align: center;width:6%;border: 1px solid gray">RURAL +TAX</th>
        															<th style="text-align: center;width:6%;border: 1px solid gray">VANS</th>
        															<th style="text-align: center;width:6%;border: 1px solid gray">DEDUCTIONS PAYE</th>
        															<th style="text-align: center;width:6%;border: 1px solid gray">TOTAL WAGE</th>
        															<th style="text-align: center;width:6%;border: 1px solid gray">AMOUNT PAID</th>
        															<th style="text-align: center;width:6%;border: 1px solid gray">OUTSTANDING</th>
        															<th style="text-align: center;width:6%;border: 1px solid gray">DUE DATE</th>
        															<th style="text-align: center;width:10%;border: 1px solid gray">NOTES</th>
        														</tr>
        													</thead>
        													<tbody>
        														<?php
        															$sql_sel = "select * from financial_info where type='DIRECT BACS PAYMENTS'";
        															$res_sel = mysqli_query($con,$sql_sel) or die(mysqli_error($con));
        															$cnt = 0;
        															while($response = mysqli_fetch_assoc($res_sel))
        															{
        																$cnt++;																
        														?>
        															<tr>
        																
        																<td style="border: 1px solid gray;"><?php	if ($cnt == 1) echo 'DIRECT BACS PAYMENTS'; ?></td>
        																<td style="border: 1px solid gray"><input type="hidden" style="text-align:center"   name="type[]"  id="type[]" value="<?php echo $response['type'];?>"/><input type="text" style="text-align:center"   name="name[]"  id="name[]" value="<?php echo $response['name'];?>"/></td>
        																
        																<td style="border: 1px solid gray;"><input type="text" style="text-align:center"   name="rate[]"  id="rate[]" value="<?php DisplayGBP($response['rate']);?>"/></td>
        																<td style="border: 1px solid gray;"><input type="text" style="text-align:center"   name="days[]"  id="days[]" value="<?php echo $response['days'];?>"/></td>
        																<td style="border: 1px solid gray;"><input type="text" style="text-align:center"   name="rxd[]"  id="rxd[]" value="<?php DisplayGBP($response['rxd']);?>"/></td>
        																<td style="border: 1px solid gray;"><input type="text" style="text-align:center"   name="admin[]"  id="admin[]" value="<?php DisplayGBP($response['admin']);?>"/></td>
        																<td style="border: 1px solid gray;"><input type="text" style="text-align:center"   name="rural_tx[]"  id="rural_tx[]" value="<?php DisplayGBP($response['rural_tx']);?>"/></td>
        																<td style="border: 1px solid gray;"><input type="text" style="text-align:center"   name="vans[]"  id="vans[]" value="<?php DisplayGBP($response['vans']);?>"/></td>
        																<td style="border: 1px solid gray;"><input type="text" style="text-align:center"   name="deduction[]"  id="deduction[]" value="<?php DisplayGBP($response['deduction']);?>"/></td>
        																<td style="border: 1px solid gray;"><input type="text" style="text-align:center"   name="total_wage[]"  id="total_wage[]" value="<?php DisplayGBP($response['total_wage']);?>"/></td>
        																<td style="border: 1px solid gray;"><input type="text" style="text-align:center"   name="amount_paid[]"  id="amount_paid[]" value="<?php DisplayGBP($response['amount_paid']);?>"/></td>
        																<td style="border: 1px solid gray;"><input type="text" style="text-align:center"   name="outstanding[]"  id="outstanding[]" value="<?php DisplayGBP($response['outstanding']);?>"/></td>
        																
        																
        														   	  	    
        																<td style="border: 1px solid gray"><input class="datetimePicker" id="due_date[]" name="due_date[]" type="text" value="<?php echo my_date_format($response['due_date'],'m/d/Y');
        														   	  	    ?>"/></td>
        																<td style="border: 1px solid gray"><input type="text" style="text-align:center"   name="notes[]"  id="notes[]" value="<?php echo $response['notes'];?>"/></td>
        															</tr>
        														<?php
        															}
        															?>
        													</tbody>
        												</table>
        												<div class="row" style="margin-left: 22px;margin-bottom: 10px">
        													<u><a style='color:blue' onclick='$("<tr><td style=\"border: 1px solid gray;width:10%;\"></td><td style=\"border: 1px solid gray;width:10%\"><input type=\"hidden\" style=\"text-align:center\" name=\"type[]\"  id=\"type[]\"  value=\"DIRECT BACS PAYMENTS\"/><input type=\"text\" style=\"text-align:center\"   name=\"name[]\"  id=\"name[]\"/></td><td style=\"border: 1px solid gray;\"><input type=\"text\" style=\"text-align:center\"   name=\"rate[]\"  id=\"rate[]\"/></td><td style=\"border: 1px solid gray;width:6%;\"><input type=\"text\" style=\"text-align:center\"   name=\"days[]\"  id=\"days[]\"/></td><td style=\"border: 1px solid gray;width:6%;\"><input type=\"text\" style=\"text-align:center\"   name=\"rxd[]\"  id=\"rxd[]\"/></td><td style=\"border: 1px solid gray;width:6%;\"><input type=\"text\" style=\"text-align:center\"   name=\"admin[]\"  id=\"admin[]\"/></td><td style=\"border: 1px solid gray;width:6%;\"><input type=\"text\" style=\"text-align:center\"   name=\"rural_tx[]\"  id=\"rural_tx[]\"/></td><td style=\"border: 1px solid gray;width:6%;\"><input type=\"text\" style=\"text-align:center\"   name=\"vans[]\"  id=\"vans[]\"/></td><td style=\"border: 1px solid gray;width:6%;\"><input type=\"text\" style=\"text-align:center\"   name=\"deduction[]\"  id=\"deduction[]\" /></td><td style=\"border: 1px solid gray;width:6%;\"><input type=\"text\" style=\"text-align:center\"   name=\"total_wage[]\"  id=\"total_wage[]\" /></td><td style=\"border: 1px solid gray;width:6%;\"><input type=\"text\" style=\"text-align:center\"   name=\"amount_paid[]\"  id=\"amount_paid[]\" /></td><td style=\"border: 1px solid gray;width:6%;\"><input type=\"text\" style=\"text-align:center\"   name=\"outstanding[]\"  id=\"outstanding[]\" /></td><td style=\"border: 1px solid gray;width:6%;\"><input class=\"datetimePicker\" id=\"due_date[]\" name=\"due_date[]\" type=\"text\" /></td><td style=\"border: 1px solid gray;width:10%;\"><input type=\"text\" style=\"text-align:center\"   name=\"notes[]\"  id=\"notes[]\" /></td></tr>").appendTo($("#direct_basic_tbl")); return false;'>Add</a></u>	
          												</div>
        												<table width="99%"  class="create-skus-form margin_10 bold" id="accountant_tbl">
        													<tbody>															
        															<tr><td colspan="14" style="border: 1px solid gray;">OTHER [OVERHEADS]</td></tr>
        															
        														<?php
        															$sql_sel = "select * from financial_info where type='ACCOUNTANT'";
        															$res_sel = mysqli_query($con,$sql_sel) or die(mysqli_error($con));
        															$cnt = 0;
        															while($response = mysqli_fetch_assoc($res_sel))
        															{
        																$cnt++;
        														?>
        															<tr>
        																<td style="border: 1px solid gray;width:10%;"><?php	if ($cnt == 1) echo 'ACCOUNTANT'; ?></td>
        																<td style="border: 1px solid gray;width:10%;"><input type="hidden" style="text-align:center"   name="type[]"  id="type[]" value="<?php echo $response['type'];?>"/><input type="text" style="text-align:center"   name="name[]"  id="name[]" value="<?php echo $response['name'];?>"/></td>
        																<td style="border: 1px solid gray;"><input type="text" style="text-align:center"   name="rate[]"  id="rate[]" value="<?php DisplayGBP($response['rate']);?>"/></td>
        																
        																<td style="border: 1px solid gray;width:6%;"><input type="text" style="text-align:center"   name="days[]"  id="days[]" value="<?php echo $response['days'];?>"/></td>
        																
        																<td style="border: 1px solid gray;width:6%;"><input type="text" style="text-align:center"   name="rxd[]"  id="rxd[]" value="<?php DisplayGBP($response['rxd']);?>"/></td>
        																
        																<td style="border: 1px solid gray;width:6%;"><input type="text" style="text-align:center"   name="admin[]"  id="admin[]" value="<?php DisplayGBP($response['admin']);?>"/></td>
        																
        																<td style="border: 1px solid gray;width:6%;"><input type="text" style="text-align:center"   name="rural_tx[]"  id="rural_tx[]" value="<?php DisplayGBP($response['rural_tx']);?>"/></td>
        																
        																<td style="border: 1px solid gray;width:6%;"><input type="text" style="text-align:center"   name="vans[]"  id="vans[]" value="<?php DisplayGBP($response['vans']);?>"/></td>
        																
        																<td style="border: 1px solid gray;width:6%;"><input type="text" style="text-align:center"   name="deduction[]"  id="deduction[]" value="<?php DisplayGBP($response['deduction']);?>"/></td>
        																
        																<td style="border: 1px solid gray;width:6%;"><input type="text" style="text-align:center"   name="total_wage[]"  id="total_wage[]" value="<?php DisplayGBP($response['total_wage']);?>"/></td>
        																
        																<td style="border: 1px solid gray;width:6%;"><input type="text" style="text-align:center"   name="amount_paid[]"  id="amount_paid[]" value="<?php DisplayGBP($response['amount_paid']);?>"/></td>
        																
        																<td style="border: 1px solid gray;width:6%;"><input type="text" style="text-align:center"   name="outstanding[]"  id="outstanding[]" value="<?php DisplayGBP($response['outstanding']);?>"/></td>
        																
        														   	  	    
        																<td style="border: 1px solid gray;width:6%;"><input class="datetimePicker" id="due_date[]" name="due_date[]" type="text" value="<?php														   	  	    	echo my_date_format($response['due_date'],'m/d/Y');
        														   	  	    ?>"/></td>
        																<td style="border: 1px solid gray;width:10%;"><input type="text" style="text-align:center"   name="notes[]"  id="notes[]" value="<?php echo $response['notes'];?>"/></td>
        																
        															</tr>
        														<?php
        															}
        														?>
        														</tbody>
        												</table>
        												<div class="row" style="margin-left: 22px;margin-bottom: 10px">
        													<u><a style='color:blue' onclick='$("<tr><td style=\"border: 1px solid gray;width:10%;\"></td><td style=\"border: 1px solid gray;width:10%\"><input type=\"hidden\" style=\"text-align:center\" name=\"type[]\"  id=\"type[]\"  value=\"ACCOUNTANT\"/><input type=\"text\" style=\"text-align:center\"   name=\"name[]\"  id=\"name[]\"/></td><td style=\"border: 1px solid gray;\"><input type=\"text\" style=\"text-align:center\"   name=\"rate[]\"  id=\"rate[]\"/></td><td style=\"border: 1px solid gray;width:6%;\"><input type=\"text\" style=\"text-align:center\"   name=\"days[]\"  id=\"days[]\"/></td><td style=\"border: 1px solid gray;width:6%;\"><input type=\"text\" style=\"text-align:center\"   name=\"rxd[]\"  id=\"rxd[]\"/></td><td style=\"border: 1px solid gray;width:6%;\"><input type=\"text\" style=\"text-align:center\"   name=\"admin[]\"  id=\"admin[]\"/></td><td style=\"border: 1px solid gray;width:6%;\"><input type=\"text\" style=\"text-align:center\"   name=\"rural_tx[]\"  id=\"rural_tx[]\"/></td><td style=\"border: 1px solid gray;width:6%;\"><input type=\"text\" style=\"text-align:center\"   name=\"vans[]\"  id=\"vans[]\"/></td><td style=\"border: 1px solid gray;width:6%;\"><input type=\"text\" style=\"text-align:center\"   name=\"deduction[]\"  id=\"deduction[]\" /></td><td style=\"border: 1px solid gray;width:6%;\"><input type=\"text\" style=\"text-align:center\"   name=\"total_wage[]\"  id=\"total_wage[]\" /></td><td style=\"border: 1px solid gray;width:6%;\"><input type=\"text\" style=\"text-align:center\"   name=\"amount_paid[]\"  id=\"amount_paid[]\" /></td><td style=\"border: 1px solid gray;width:6%;\"><input type=\"text\" style=\"text-align:center\"   name=\"outstanding[]\"  id=\"outstanding[]\" /></td><td style=\"border: 1px solid gray;width:6%;\"><input class=\"datetimePicker\" id=\"due_date[]\" name=\"due_date[]\" type=\"text\" /></td><td style=\"border: 1px solid gray;width:10%;\"><input type=\"text\" style=\"text-align:center\"   name=\"notes[]\"  id=\"notes[]\" /></td></tr>").appendTo($("#accountant_tbl")); return false;'>Add</a></u>	
          												</div>
        												<table width="99%"  class="create-skus-form margin_10 bold" id="direct_debits_tbl">
        													<tbody>			
        														<?php
        															$sql_sel = "select * from financial_info where type='DIRECT DEBITS'";
        															$res_sel = mysqli_query($con,$sql_sel) or die(mysqli_error($con));
        															$cnt = 0;
        															while($response = mysqli_fetch_assoc($res_sel))
        															{
        																$cnt++;
        														?>
        															<tr>
        																<td style="border: 1px solid gray;width:10%"><?php	if ($cnt == 1) echo 'DIRECT DEBITS'; ?></td>
        																<td style="border: 1px solid gray;width:10%"><input type="hidden" style="text-align:center"   name="type[]"  id="type[]" value="<?php echo $response['type'];?>"/><input type="text" style="text-align:center"   name="name[]"  id="name[]" value="<?php echo $response['name'];?>"/></td>
        																<td style="border: 1px solid gray;"><input type="text" style="text-align:center"   name="rate[]"  id="rate[]" value="<?php DisplayGBP($response['rate']);?>"/></td>
        																<td style="border: 1px solid gray;width:6%;"><input type="text" style="text-align:center"   name="days[]"  id="days[]" value="<?php echo $response['days'];?>"/></td>
        																<td style="border: 1px solid gray;width:6%;"><input type="text" style="text-align:center"   name="rxd[]"  id="rxd[]" value="<?php DisplayGBP($response['rxd']);?>"/></td>
        																<td style="border: 1px solid gray;width:6%;"><input type="text" style="text-align:center"   name="admin[]"  id="admin[]" value="<?php DisplayGBP($response['admin']);?>"/></td>
        																<td style="border: 1px solid gray;width:6%;"><input type="text" style="text-align:center"   name="rural_tx[]"  id="rural_tx[]" value="<?php DisplayGBP($response['rural_tx']);?>"/></td>
        																<td style="border: 1px solid gray;width:6%;"><input type="text" style="text-align:center"   name="vans[]"  id="vans[]" value="<?php DisplayGBP($response['vans']);?>"/></td>
        																<td style="border: 1px solid gray;width:6%;"><input type="text" style="text-align:center"   name="deduction[]"  id="deduction[]" value="<?php DisplayGBP($response['deduction']);?>"/></td>
        																<td style="border: 1px solid gray;width:6%;"><input type="text" style="text-align:center"   name="total_wage[]"  id="total_wage[]" value="<?php DisplayGBP($response['total_wage']);?>"/></td>
        																<td style="border: 1px solid gray;width:6%;"><input type="text" style="text-align:center"   name="amount_paid[]"  id="amount_paid[]" value="<?php DisplayGBP($response['amount_paid']);?>"/></td>
        																<td style="border: 1px solid gray;width:6%;"><input type="text" style="text-align:center"   name="outstanding[]"  id="outstanding[]" value="<?php DisplayGBP($response['outstanding']);?>"/></td>
        																<td style="border: 1px solid gray;width:6%;"><input class="datetimePicker" id="due_date[]" name="due_date[]" type="text" value="<?php														   	  	    	echo my_date_format($response['due_date'],'m/d/Y');
        														   	  	    ?>"/></td>
        																<td style="border: 1px solid gray;width:10%;"><input type="text" style="text-align:center"   name="notes[]"  id="notes[]" value="<?php echo $response['notes'];?>"/></td>
        															</tr>
        														<?php
        															}
        														?>
        														</tbody>
        												</table>
        												<div class="row" style="margin-left: 22px;margin-bottom: 10px">
        													<u><a style='color:blue' onclick='$("<tr><td style=\"border: 1px solid gray;width:10%;\"></td><td style=\"border: 1px solid gray;width:10%\"><input type=\"hidden\" style=\"text-align:center\" name=\"type[]\"  id=\"type[]\"  value=\"DIRECT DEBITS\"/><input type=\"text\" style=\"text-align:center\"   name=\"name[]\"  id=\"name[]\"/></td><td style=\"border: 1px solid gray;\"><input type=\"text\" style=\"text-align:center\"   name=\"rate[]\"  id=\"rate[]\"/></td><td style=\"border: 1px solid gray;width:6%;\"><input type=\"text\" style=\"text-align:center\"   name=\"days[]\"  id=\"days[]\"/></td><td style=\"border: 1px solid gray;width:6%;\"><input type=\"text\" style=\"text-align:center\"   name=\"rxd[]\"  id=\"rxd[]\"/></td><td style=\"border: 1px solid gray;width:6%;\"><input type=\"text\" style=\"text-align:center\"   name=\"admin[]\"  id=\"admin[]\"/></td><td style=\"border: 1px solid gray;width:6%;\"><input type=\"text\" style=\"text-align:center\"   name=\"rural_tx[]\"  id=\"rural_tx[]\"/></td><td style=\"border: 1px solid gray;width:6%;\"><input type=\"text\" style=\"text-align:center\"   name=\"vans[]\"  id=\"vans[]\"/></td><td style=\"border: 1px solid gray;width:6%;\"><input type=\"text\" style=\"text-align:center\"   name=\"deduction[]\"  id=\"deduction[]\" /></td><td style=\"border: 1px solid gray;width:6%;\"><input type=\"text\" style=\"text-align:center\"   name=\"total_wage[]\"  id=\"total_wage[]\" /></td><td style=\"border: 1px solid gray;width:6%;\"><input type=\"text\" style=\"text-align:center\"   name=\"amount_paid[]\"  id=\"amount_paid[]\" /></td><td style=\"border: 1px solid gray;width:6%;\"><input type=\"text\" style=\"text-align:center\"   name=\"outstanding[]\"  id=\"outstanding[]\" /></td><td style=\"border: 1px solid gray;width:6%;\"><input class=\"datetimePicker\" id=\"due_date[]\" name=\"due_date[]\" type=\"text\" /></td><td style=\"border: 1px solid gray;width:10%;\"><input type=\"text\" style=\"text-align:center\"   name=\"notes[]\"  id=\"notes[]\" /></td></tr>").appendTo($("#direct_debits_tbl")); return false;'>Add</a></u>	
          												</div>
        												<table width="99%"  class="create-skus-form margin_10 bold" id="office_staff_tbl">
        													<tbody>			
        														<?php
        															$sql_sel = "select * from financial_info where type='OFFICE STAFF'";
        															$res_sel = mysqli_query($con,$sql_sel) or die(mysqli_error($con));
        															$cnt = 0;
        															while($response = mysqli_fetch_assoc($res_sel))
        															{
        																$cnt++;
        														?>
        															<tr>
        																<td style="border: 1px solid gray;width:10%"><?php	if ($cnt == 1) echo 'OFFICE STAFF'; ?></td>
        																<td style="border: 1px solid gray;width:10%"><input type="hidden" style="text-align:center"   name="type[]"  id="type[]" value="<?php echo $response['type'];?>"/><input type="text" style="text-align:center"   name="name[]"  id="name[]" value="<?php echo $response['name'];?>"/></td>
        																<td style="border: 1px solid gray;"><input type="text" style="text-align:center"   name="rate[]"  id="rate[]" value="<?php DisplayGBP($response['rate']);?>"/></td>
        																<td style="border: 1px solid gray;width:6%;"><input type="text" style="text-align:center"   name="days[]"  id="days[]" value="<?php echo $response['days'];?>"/></td>
        																<td style="border: 1px solid gray;width:6%;"><input type="text" style="text-align:center"   name="rxd[]"  id="rxd[]" value="<?php DisplayGBP($response['rxd']);?>"/></td>
        																<td style="border: 1px solid gray;width:6%;"><input type="text" style="text-align:center"   name="admin[]"  id="admin[]" value="<?php DisplayGBP($response['admin']);?>"/></td>
        																<td style="border: 1px solid gray;width:6%;"><input type="text" style="text-align:center"   name="rural_tx[]"  id="rural_tx[]" value="<?php DisplayGBP($response['rural_tx']);?>"/></td>
        																<td style="border: 1px solid gray;width:6%;"><input type="text" style="text-align:center"   name="vans[]"  id="vans[]" value="<?php DisplayGBP($response['vans']);?>"/></td>
        																<td style="border: 1px solid gray;width:6%;"><input type="text" style="text-align:center"   name="deduction[]"  id="deduction[]" value="<?php DisplayGBP($response['deduction']);?>"/></td>
        																<td style="border: 1px solid gray;width:6%;"><input type="text" style="text-align:center"   name="total_wage[]"  id="total_wage[]" value="<?php DisplayGBP($response['total_wage']);?>"/></td>
        																<td style="border: 1px solid gray;width:6%;"><input type="text" style="text-align:center"   name="amount_paid[]"  id="amount_paid[]" value="<?php DisplayGBP($response['amount_paid']);?>"/></td>
        																<td style="border: 1px solid gray;width:6%;"><input type="text" style="text-align:center"   name="outstanding[]"  id="outstanding[]" value="<?php DisplayGBP($response['outstanding']);?>"/></td>
        																<td style="border: 1px solid gray;width:6%;"><input class="datetimePicker" id="due_date[]" name="due_date[]" type="text" value="<?php														   	  	    	echo my_date_format($response['due_date'],'m/d/Y');
        														   	  	    ?>"/></td>
        																<td style="border: 1px solid gray;width:10%;"><input type="text" style="text-align:center"   name="notes[]"  id="notes[]" value="<?php echo $response['notes'];?>"/></td>
        															</tr>
        														<?php
        															}
        														?>
        													</tbody>
        												</table>
        												<div class="row" style="margin-left: 22px;margin-bottom: 10px">
        													<u><a style='color:blue' onclick='$("<tr><td style=\"border: 1px solid gray;width:10%;\"></td><td style=\"border: 1px solid gray;width:10%\"><input type=\"hidden\" style=\"text-align:center\" name=\"type[]\"  id=\"type[]\"  value=\"OFFICE STAFF\"/><input type=\"text\" style=\"text-align:center\"   name=\"name[]\"  id=\"name[]\"/></td><td style=\"border: 1px solid gray;\"><input type=\"text\" style=\"text-align:center\"   name=\"rate[]\"  id=\"rate[]\"/></td><td style=\"border: 1px solid gray;width:6%;\"><input type=\"text\" style=\"text-align:center\"   name=\"days[]\"  id=\"days[]\"/></td><td style=\"border: 1px solid gray;width:6%;\"><input type=\"text\" style=\"text-align:center\"   name=\"rxd[]\"  id=\"rxd[]\"/></td><td style=\"border: 1px solid gray;width:6%;\"><input type=\"text\" style=\"text-align:center\"   name=\"admin[]\"  id=\"admin[]\"/></td><td style=\"border: 1px solid gray;width:6%;\"><input type=\"text\" style=\"text-align:center\"   name=\"rural_tx[]\"  id=\"rural_tx[]\"/></td><td style=\"border: 1px solid gray;width:6%;\"><input type=\"text\" style=\"text-align:center\"   name=\"vans[]\"  id=\"vans[]\"/></td><td style=\"border: 1px solid gray;width:6%;\"><input type=\"text\" style=\"text-align:center\"   name=\"deduction[]\"  id=\"deduction[]\" /></td><td style=\"border: 1px solid gray;width:6%;\"><input type=\"text\" style=\"text-align:center\"   name=\"total_wage[]\"  id=\"total_wage[]\" /></td><td style=\"border: 1px solid gray;width:6%;\"><input type=\"text\" style=\"text-align:center\"   name=\"amount_paid[]\"  id=\"amount_paid[]\" /></td><td style=\"border: 1px solid gray;width:6%;\"><input type=\"text\" style=\"text-align:center\"   name=\"outstanding[]\"  id=\"outstanding[]\" /></td><td style=\"border: 1px solid gray;width:6%;\"><input class=\"datetimePicker\" id=\"due_date[]\" name=\"due_date[]\" type=\"text\" /></td><td style=\"border: 1px solid gray;width:10%;\"><input type=\"text\" style=\"text-align:center\"   name=\"notes[]\"  id=\"notes[]\" /></td></tr>").appendTo($("#office_staff_tbl")); return false;'>Add</a></u>	
          												</div>
          												
        												<table width="99%"  class="create-skus-form margin_10 bold" id="supplier_tbl">
        													<tbody>															
        														<?php
        															$sql_sel = "select * from financial_info where type='SUPPLIERS'";
        															$res_sel = mysqli_query($con,$sql_sel) or die(mysqli_error($con));
        															
        															
        															$cnt = 0;
        															while($response = mysqli_fetch_assoc($res_sel))
        															{
        																$cnt++;
        																
        														?>
        															<tr>
        																<td style="border: 1px solid gray;width:10%;"><?php	if ($cnt == 1) echo 'SUPPLIERS'; ?></td>
        																<td style="border: 1px solid gray;width:10%"><input type="hidden" style="text-align:center"   name="type[]"  id="type[]" value="<?php echo $response['type'];?>"/><input type="text" style="text-align:center"   name="name[]"  id="name[]" value="<?php echo $response['name'];?>"/></td>
        																<td style="border: 1px solid gray;"><input type="text" style="text-align:center"   name="rate[]"  id="rate[]" value="<?php DisplayGBP($response['rate']);?>"/></td>
        																<td style="border: 1px solid gray;width:6%;"><input type="text" style="text-align:center"   name="days[]"  id="days[]" value="<?php echo $response['days'];?>"/></td>
        																<td style="border: 1px solid gray;width:6%;"><input type="text" style="text-align:center"   name="rxd[]"  id="rxd[]" value="<?php DisplayGBP($response['rxd']);?>"/></td>
        																<td style="border: 1px solid gray;width:6%;"><input type="text" style="text-align:center"   name="admin[]"  id="admin[]" value="<?php DisplayGBP($response['admin']);?>"/></td>
        																<td style="border: 1px solid gray;width:6%;"><input type="text" style="text-align:center"   name="rural_tx[]"  id="rural_tx[]" value="<?php DisplayGBP($response['rural_tx']);?>"/></td>
        																<td style="border: 1px solid gray;width:6%;"><input type="text" style="text-align:center"   name="vans[]"  id="vans[]" value="<?php DisplayGBP($response['vans']);?>"/></td>
        																<td style="border: 1px solid gray;width:6%;"><input type="text" style="text-align:center"   name="deduction[]"  id="deduction[]" value="<?php DisplayGBP($response['deduction']);?>"/></td>
        																<td style="border: 1px solid gray;width:6%;"><input type="text" style="text-align:center"   name="total_wage[]"  id="total_wage[]" value="<?php DisplayGBP($response['total_wage']);?>"/></td>
        																<td style="border: 1px solid gray;width:6%;"><input type="text" style="text-align:center"   name="amount_paid[]"  id="amount_paid[]" value="<?php DisplayGBP($response['amount_paid']);?>"/></td>
        																<td style="border: 1px solid gray;width:6%;"><input type="text" style="text-align:center"   name="outstanding[]"  id="outstanding[]" value="<?php DisplayGBP($response['outstanding']);?>"/></td>
        																<td style="border: 1px solid gray;width:6%;"><input class="datetimePicker" id="due_date[]" name="due_date[]" type="text" value="<?php														   	  	    	echo my_date_format($response['due_date'],'m/d/Y');
        														   	  	    ?>"/></td>
        																<td style="border: 1px solid gray;width:10%;"><input type="text" style="text-align:center"   name="notes[]"  id="notes[]" value="<?php echo $response['notes'];?>"/></td>
        															</tr>
        														<?php
        															}
        														?>
        													</tbody>
        												</table>
        												<div class="row" style="margin-left: 22px;margin-bottom: 10px">
        													<u><a style='color:blue' onclick='$("<tr><td style=\"border: 1px solid gray;width:10%;\"></td><td style=\"border: 1px solid gray;width:10%\"><input type=\"hidden\" style=\"text-align:center\" name=\"type[]\"  id=\"type[]\"  value=\"SUPPLIERS\"/><input type=\"text\" style=\"text-align:center\"   name=\"name[]\"  id=\"name[]\"/></td><td style=\"border: 1px solid gray;\"><input type=\"text\" style=\"text-align:center\"   name=\"rate[]\"  id=\"rate[]\"/></td><td style=\"border: 1px solid gray;width:6%;\"><input type=\"text\" style=\"text-align:center\"   name=\"days[]\"  id=\"days[]\"/></td><td style=\"border: 1px solid gray;width:6%;\"><input type=\"text\" style=\"text-align:center\"   name=\"rxd[]\"  id=\"rxd[]\"/></td><td style=\"border: 1px solid gray;width:6%;\"><input type=\"text\" style=\"text-align:center\"   name=\"admin[]\"  id=\"admin[]\"/></td><td style=\"border: 1px solid gray;width:6%;\"><input type=\"text\" style=\"text-align:center\"   name=\"rural_tx[]\"  id=\"rural_tx[]\"/></td><td style=\"border: 1px solid gray;width:6%;\"><input type=\"text\" style=\"text-align:center\"   name=\"vans[]\"  id=\"vans[]\"/></td><td style=\"border: 1px solid gray;width:6%;\"><input type=\"text\" style=\"text-align:center\"   name=\"deduction[]\"  id=\"deduction[]\" /></td><td style=\"border: 1px solid gray;width:6%;\"><input type=\"text\" style=\"text-align:center\"   name=\"total_wage[]\"  id=\"total_wage[]\" /></td><td style=\"border: 1px solid gray;width:6%;\"><input type=\"text\" style=\"text-align:center\"   name=\"amount_paid[]\"  id=\"amount_paid[]\" /></td><td style=\"border: 1px solid gray;width:6%;\"><input type=\"text\" style=\"text-align:center\"   name=\"outstanding[]\"  id=\"outstanding[]\" /></td><td style=\"border: 1px solid gray;width:6%;\"><input class=\"datetimePicker\" id=\"due_date[]\" name=\"due_date[]\" type=\"text\" /></td><td style=\"border: 1px solid gray;width:10%;\"><input type=\"text\" style=\"text-align:center\"   name=\"notes[]\"  id=\"notes[]\" /></td></tr>").appendTo($("#supplier_tbl")); return false;'>Add</a></u>
        												</div>
        												<table width="99%"  class="create-skus-form margin_10 bold" id="credit_cards_tbl">
        													<tbody>	
        														<?php
        															$sql_sel = "select * from financial_info where type='CREDIT CARDS'";
        															$res_sel = mysqli_query($con,$sql_sel) or die(mysqli_error($con));
        															
        															
        															$cnt = 0;
        															while($response = mysqli_fetch_assoc($res_sel))
        															{
        																$cnt++;
        														?>
        															<tr>
        																<td style="border: 1px solid gray;width:10%;"><?php	if ($cnt == 1) echo 'CREDIT CARDS'; ?></td>	
        																<td style="border: 1px solid gray;width:10%"><input type="hidden" style="text-align:center"   name="type[]"  id="type[]" value="<?php echo $response['type'];?>"/><input type="text" style="text-align:center"   name="name[]"  id="name[]" value="<?php echo $response['name'];?>"/></td>
        																<td style="border: 1px solid gray;"><input type="text" style="text-align:center"   name="rate[]"  id="rate[]" value="<?php DisplayGBP($response['rate']);?>"/></td>
        																<td style="border: 1px solid gray;width:6%;"><input type="text" style="text-align:center"   name="days[]"  id="days[]" value="<?php echo $response['days'];?>"/></td>
        																<td style="border: 1px solid gray;width:6%;"><input type="text" style="text-align:center"   name="rxd[]"  id="rxd[]" value="<?php DisplayGBP($response['rxd']);?>"/></td>
        																<td style="border: 1px solid gray;width:6%;"><input type="text" style="text-align:center"   name="admin[]"  id="admin[]" value="<?php DisplayGBP($response['admin']);?>"/></td>
        																<td style="border: 1px solid gray;width:6%;"><input type="text" style="text-align:center"   name="rural_tx[]"  id="rural_tx[]" value="<?php DisplayGBP($response['rural_tx']);?>"/></td>
        																<td style="border: 1px solid gray;width:6%;"><input type="text" style="text-align:center"   name="vans[]"  id="vans[]" value="<?php DisplayGBP($response['vans']);?>"/></td>
        																<td style="border: 1px solid gray;width:6%;"><input type="text" style="text-align:center"   name="deduction[]"  id="deduction[]" value="<?php DisplayGBP($response['deduction']);?>"/></td>
        																<td style="border: 1px solid gray;width:6%;"><input type="text" style="text-align:center"   name="total_wage[]"  id="total_wage[]" value="<?php DisplayGBP($response['total_wage']);?>"/></td>
        																<td style="border: 1px solid gray;width:6%;"><input type="text" style="text-align:center"   name="amount_paid[]"  id="amount_paid[]" value="<?php DisplayGBP($response['amount_paid']);?>"/></td>
        																<td style="border: 1px solid gray;width:6%;"><input type="text" style="text-align:center"   name="outstanding[]"  id="outstanding[]" value="<?php DisplayGBP($response['outstanding']);?>"/></td>
        																<td style="border: 1px solid gray;width:6%"><input class="datetimePicker" id="due_date[]" name="due_date[]" type="text" value="<?php														   	  	    	echo my_date_format($response['due_date'],'m/d/Y');
        														   	  	    ?>"/></td>
        																<td style="border: 1px solid gray;width:10%"><input type="text" style="text-align:center"   name="notes[]"  id="notes[]" value="<?php echo $response['notes'];?>"/></td>
        															</tr>
        														<?php
        															}
        														?>
        														</tbody>
        												</table>
        												<div class="row" style="margin-left: 22px;margin-bottom: 10px">
        													<u><a style='color:blue' onclick='$("<tr><td style=\"border: 1px solid gray;width:10%;\"></td><td style=\"border: 1px solid gray;width:10%\"><input type=\"hidden\" style=\"text-align:center\" name=\"type[]\"  id=\"type[]\"  value=\"CREDIT CARDS\"/><input type=\"text\" style=\"text-align:center\"   name=\"name[]\"  id=\"name[]\"/></td><td style=\"border: 1px solid gray;\"><input type=\"text\" style=\"text-align:center\"   name=\"rate[]\"  id=\"rate[]\"/></td><td style=\"border: 1px solid gray;width:6%;\"><input type=\"text\" style=\"text-align:center\"   name=\"days[]\"  id=\"days[]\"/></td><td style=\"border: 1px solid gray;width:6%;\"><input type=\"text\" style=\"text-align:center\"   name=\"rxd[]\"  id=\"rxd[]\"/></td><td style=\"border: 1px solid gray;width:6%;\"><input type=\"text\" style=\"text-align:center\"   name=\"admin[]\"  id=\"admin[]\"/></td><td style=\"border: 1px solid gray;width:6%;\"><input type=\"text\" style=\"text-align:center\"   name=\"rural_tx[]\"  id=\"rural_tx[]\"/></td><td style=\"border: 1px solid gray;width:6%;\"><input type=\"text\" style=\"text-align:center\"   name=\"vans[]\"  id=\"vans[]\"/></td><td style=\"border: 1px solid gray;width:6%;\"><input type=\"text\" style=\"text-align:center\"   name=\"deduction[]\"  id=\"deduction[]\" /></td><td style=\"border: 1px solid gray;width:6%;\"><input type=\"text\" style=\"text-align:center\"   name=\"total_wage[]\"  id=\"total_wage[]\" /></td><td style=\"border: 1px solid gray;width:6%;\"><input type=\"text\" style=\"text-align:center\"   name=\"amount_paid[]\"  id=\"amount_paid[]\" /></td><td style=\"border: 1px solid gray;width:6%;\"><input type=\"text\" style=\"text-align:center\"   name=\"outstanding[]\"  id=\"outstanding[]\" /></td><td style=\"border: 1px solid gray;width:6%;\"><input class=\"datetimePicker\" id=\"due_date[]\" name=\"due_date[]\" type=\"text\" /></td><td style=\"border: 1px solid gray;width:10%;\"><input type=\"text\" style=\"text-align:center\"   name=\"notes[]\"  id=\"notes[]\" /></td></tr>").appendTo($("#credit_cards_tbl")); return false;'>Add</a></u>
        												</div>
        												<table width="99%"  class="create-skus-form margin_10 bold" id="hsbc_tbl">
        													<tbody>			
        														<?php
        															$sql_sel = "select * from financial_info where type='HSBC'";
        															$res_sel = mysqli_query($con,$sql_sel) or die(mysqli_error($con));
        															$cnt = 0;
        															while($response = mysqli_fetch_assoc($res_sel))
        															{
        																$cnt++;
        														?>
        															<tr>
        																<td style="border: 1px solid gray;width:10%"><?php	if ($cnt == 1) echo 'HSBC'; ?></td>	
        																<td style="border: 1px solid gray;width:10%"><input type="hidden" style="text-align:center"   name="type[]"  id="type[]" value="<?php echo $response['type'];?>"/><input type="text" style="text-align:center"   name="name[]"  id="name[]" value="<?php echo $response['name'];?>"/></td>
        																<td style="border: 1px solid gray;"><input type="text" style="text-align:center"   name="rate[]"  id="rate[]" value="<?php DisplayGBP($response['rate']);?>"/></td>
        																<td style="border: 1px solid gray;width:6%"><input type="text" style="text-align:center"   name="days[]"  id="days[]" value="<?php echo $response['days'];?>"/></td>
        																<td style="border: 1px solid gray;width:6%"><input type="text" style="text-align:center"   name="rxd[]"  id="rxd[]" value="<?php DisplayGBP($response['rxd']);?>"/></td>
        																<td style="border: 1px solid gray;width:6%"><input type="text" style="text-align:center"   name="admin[]"  id="admin[]" value="<?php DisplayGBP($response['admin']);?>"/></td>
        																<td style="border: 1px solid gray;width:6%;"><input type="text" style="text-align:center"   name="rural_tx[]"  id="rural_tx[]" value="<?php DisplayGBP($response['rural_tx']);?>"/></td>
        																<td style="border: 1px solid gray;width:6%;"><input type="text" style="text-align:center"   name="vans[]"  id="vans[]" value="<?php DisplayGBP($response['vans']);?>"/></td>
        																<td style="border: 1px solid gray;width:6%;"><input type="text" style="text-align:center"   name="deduction[]"  id="deduction[]" value="<?php DisplayGBP($response['deduction']);?>"/></td>
        																<td style="border: 1px solid gray;width:6%;"><input type="text" style="text-align:center"   name="total_wage[]"  id="total_wage[]" value="<?php DisplayGBP($response['total_wage']);?>"/></td>
        																<td style="border: 1px solid gray;width:6%;"><input type="text" style="text-align:center"   name="amount_paid[]"  id="amount_paid[]" value="<?php DisplayGBP($response['amount_paid']);?>"/></td>
        																<td style="border: 1px solid gray;width:6%;"><input type="text" style="text-align:center"   name="outstanding[]"  id="outstanding[]" value="<?php DisplayGBP($response['outstanding']);?>"/></td>
        																<td style="border: 1px solid gray;width:6%"><?php echo my_date_format($response['due_date'],'m/d/Y');; ?></td>
        																<td style="border: 1px solid gray;width:10%"><input type="text" style="text-align:center"   name="notes[]"  id="notes[]" value="<?php echo $response['notes'];?>"/></td>
        															</tr>
        														<?php
        															}
        														?>
        														</tbody>
        												</table>
        												<div class="row" style="margin-left: 22px;margin-bottom: 10px">
        													<u><a style='color:blue' onclick='$("<tr><td style=\"border: 1px solid gray;width:10%;\"></td><td style=\"border: 1px solid gray;width:10%\"><input type=\"hidden\" style=\"text-align:center\" name=\"type[]\"  id=\"type[]\"  value=\"HSBC\"/><input type=\"text\" style=\"text-align:center\"   name=\"name[]\"  id=\"name[]\"/></td><td style=\"border: 1px solid gray;\"><input type=\"text\" style=\"text-align:center\"   name=\"rate[]\"  id=\"rate[]\"/></td><td style=\"border: 1px solid gray;width:6%;\"><input type=\"text\" style=\"text-align:center\"   name=\"days[]\"  id=\"days[]\"/></td><td style=\"border: 1px solid gray;width:6%;\"><input type=\"text\" style=\"text-align:center\"   name=\"rxd[]\"  id=\"rxd[]\"/></td><td style=\"border: 1px solid gray;width:6%;\"><input type=\"text\" style=\"text-align:center\"   name=\"admin[]\"  id=\"admin[]\"/></td><td style=\"border: 1px solid gray;width:6%;\"><input type=\"text\" style=\"text-align:center\"   name=\"rural_tx[]\"  id=\"rural_tx[]\"/></td><td style=\"border: 1px solid gray;width:6%;\"><input type=\"text\" style=\"text-align:center\"   name=\"vans[]\"  id=\"vans[]\"/></td><td style=\"border: 1px solid gray;width:6%;\"><input type=\"text\" style=\"text-align:center\"   name=\"deduction[]\"  id=\"deduction[]\" /></td><td style=\"border: 1px solid gray;width:6%;\"><input type=\"text\" style=\"text-align:center\"   name=\"total_wage[]\"  id=\"total_wage[]\" /></td><td style=\"border: 1px solid gray;width:6%;\"><input type=\"text\" style=\"text-align:center\"   name=\"amount_paid[]\"  id=\"amount_paid[]\" /></td><td style=\"border: 1px solid gray;width:6%;\"><input type=\"text\" style=\"text-align:center\"   name=\"outstanding[]\"  id=\"outstanding[]\" /></td><td style=\"border: 1px solid gray;width:6%;\"><input class=\"datetimePicker\" id=\"due_date[]\" name=\"due_date[]\" type=\"text\" /></td><td style=\"border: 1px solid gray;width:10%;\"><input type=\"text\" style=\"text-align:center\"   name=\"notes[]\"  id=\"notes[]\" /></td></tr>").appendTo($("#hsbc_tbl")); return false;'>Add</a></u>
        												</div>
        												<table width="99%"  class="create-skus-form margin_10 bold" id="lloyds_tbl">
        													<tbody>			
        														<?php
        															$sql_sel = "select * from financial_info where type='LLOYDS'";
        															$res_sel = mysqli_query($con,$sql_sel) or die(mysqli_error($con));
        															$cnt = 0;
        															while($response = mysqli_fetch_assoc($res_sel))
        															{
        																$cnt++;
        														?>
        															<tr>
        																<td style="border: 1px solid gray;width:10%;"><?php	if ($cnt == 1) echo 'LLOYDS'; ?></td>	
        																<td style="border: 1px solid gray;width:10%"><input type="hidden" style="text-align:center"   name="type[]"  id="type[]" value="<?php echo $response['type'];?>"/><input type="text" style="text-align:center"   name="name[]"  id="name[]" value="<?php echo $response['name'];?>"/></td>
        																<td style="border: 1px solid gray;"><input type="text" style="text-align:center"   name="rate[]"  id="rate[]" value="<?php DisplayGBP($response['rate']);?>"/></td>
        																<td style="border: 1px solid gray;width:6%;"><input type="text" style="text-align:center"   name="days[]"  id="days[]" value="<?php echo $response['days'];?>"/></td>
        																<td style="border: 1px solid gray;width:6%;"><input type="text" style="text-align:center"   name="rxd[]"  id="rxd[]" value="<?php DisplayGBP($response['rxd']);?>"/></td>
        																<td style="border: 1px solid gray;width:6%;"><input type="text" style="text-align:center"   name="admin[]"  id="admin[]" value="<?php DisplayGBP($response['admin']);?>"/></td>
        																<td style="border: 1px solid gray;width:6%;"><input type="text" style="text-align:center"   name="rural_tx[]"  id="rural_tx[]" value="<?php DisplayGBP($response['rural_tx']);?>"/></td>
        																<td style="border: 1px solid gray;width:6%;"><input type="text" style="text-align:center"   name="vans[]"  id="vans[]" value="<?php DisplayGBP($response['vans']);?>"/></td>
        																<td style="border: 1px solid gray;width:6%;"><input type="text" style="text-align:center"   name="deduction[]"  id="deduction[]" value="<?php DisplayGBP($response['deduction']);?>"/></td>
        																<td style="border: 1px solid gray;width:6%;"><input type="text" style="text-align:center"   name="total_wage[]"  id="total_wage[]" value="<?php DisplayGBP($response['total_wage']);?>"/></td>
        																<td style="border: 1px solid gray;width:6%;"><input type="text" style="text-align:center"   name="amount_paid[]"  id="amount_paid[]" value="<?php DisplayGBP($response['amount_paid']);?>"/></td>
        																<td style="border: 1px solid gray;width:6%;"><input type="text" style="text-align:center"   name="outstanding[]"  id="outstanding[]" value="<?php DisplayGBP($response['outstanding']);?>"/></td>
        																<td style="border: 1px solid gray;width:6%"><input class="datetimePicker" id="due_date[]" name="due_date[]" type="text" value="<?php														   	  	    	echo my_date_format($response['due_date'],'m/d/Y');
        														   	  	    ?>"/></td>
        																<td style="border: 1px solid gray;width:10%"><input type="text" style="text-align:center"   name="notes[]"  id="notes[]" value="<?php echo $response['notes'];?>"/></td>
        															</tr>
        														<?php
        															}
        														?>
        													
        														</tbody>
        												</table>
        												<div class="row" style="margin-left: 22px;margin-bottom: 10px">
        													<u><a style='color:blue' onclick='$("<tr><td style=\"border: 1px solid gray;width:10%;\"></td><td style=\"border: 1px solid gray;width:10%\"><input type=\"hidden\" style=\"text-align:center\" name=\"type[]\"  id=\"type[]\"  value=\"LLOYDS\"/><input type=\"text\" style=\"text-align:center\"   name=\"name[]\"  id=\"name[]\"/></td><td style=\"border: 1px solid gray;\"><input type=\"text\" style=\"text-align:center\"   name=\"rate[]\"  id=\"rate[]\"/></td><td style=\"border: 1px solid gray;width:6%;\"><input type=\"text\" style=\"text-align:center\"   name=\"days[]\"  id=\"days[]\"/></td><td style=\"border: 1px solid gray;width:6%;\"><input type=\"text\" style=\"text-align:center\"   name=\"rxd[]\"  id=\"rxd[]\"/></td><td style=\"border: 1px solid gray;width:6%;\"><input type=\"text\" style=\"text-align:center\"   name=\"admin[]\"  id=\"admin[]\"/></td><td style=\"border: 1px solid gray;width:6%;\"><input type=\"text\" style=\"text-align:center\"   name=\"rural_tx[]\"  id=\"rural_tx[]\"/></td><td style=\"border: 1px solid gray;width:6%;\"><input type=\"text\" style=\"text-align:center\"   name=\"vans[]\"  id=\"vans[]\"/></td><td style=\"border: 1px solid gray;width:6%;\"><input type=\"text\" style=\"text-align:center\"   name=\"deduction[]\"  id=\"deduction[]\" /></td><td style=\"border: 1px solid gray;width:6%;\"><input type=\"text\" style=\"text-align:center\"   name=\"total_wage[]\"  id=\"total_wage[]\" /></td><td style=\"border: 1px solid gray;width:6%;\"><input type=\"text\" style=\"text-align:center\"   name=\"amount_paid[]\"  id=\"amount_paid[]\" /></td><td style=\"border: 1px solid gray;width:6%;\"><input type=\"text\" style=\"text-align:center\"   name=\"outstanding[]\"  id=\"outstanding[]\" /></td><td style=\"border: 1px solid gray;width:6%;\"><input class=\"datetimePicker\" id=\"due_date[]\" name=\"due_date[]\" type=\"text\" /></td><td style=\"border: 1px solid gray;width:10%;\"><input type=\"text\" style=\"text-align:center\"   name=\"notes[]\"  id=\"notes[]\" /></td></tr>").appendTo($("#lloyds_tbl")); return false;'>Add</a></u>
        												</div>
        												<table width="99%"  class="create-skus-form margin_10 bold" id="accountant_tbl">
        													<tbody>			
        															<tr><td colspan="14" style="border: 1px solid gray;">OUT GOING PAYMENTS SUMMARY</td></tr>
        															<tr><td colspan="14" style="border: 1px solid gray;">&nbsp;</td></tr>
        														<?php
        															$sql_sel = "select sum(rate) as sum_rate, sum(days) as sum_days, sum(rxd) as sum_rxd, sum(admin) as sum_admin, sum(rural_tx) as sum_rural_tx, sum(vans) as sum_vans, sum(deduction) as sum_deduction, sum(total_wage) as sum_total_wage, sum(amount_paid) as sum_amount_paid, sum(outstanding) as sum_outstanding from financial_info where type='CASH'";
        															$res_sel = mysqli_query($con,$sql_sel) or die(mysqli_error($con));
        															
        															
        															$cnt = 0;
        															while($response = mysqli_fetch_assoc($res_sel))
        															{
        																$cnt++;
        																
        														?>
        															<tr>
        																<td style="border: 1px solid gray;width:10%;">CASH</td>							
        																<td style="border: 1px solid gray;width:10%;"></td>
        																<td style="border: 1px solid gray;"><?php DisplayGBP($response['sum_rate']); ?></td>
        																<td style="border: 1px solid gray;width:6%;"><?php DisplayGBP($response['sum_days']); ?></td>
        																<td style="border: 1px solid gray;width:6%;"><?php DisplayGBP($response['sum_rxd']); ?></td>
        																<td style="border: 1px solid gray;width:6%;"><?php DisplayGBP($response['sum_admin']); ?></td>
        																<td style="border: 1px solid gray;width:6%;"><?php DisplayGBP($response['sum_rural_tx']); ?></td>
        																<td style="border: 1px solid gray;width:6%;"><?php DisplayGBP($response['sum_vans']); ?></td>
        																<td style="border: 1px solid gray;width:6%;"><?php DisplayGBP($response['sum_deduction']); ?></td>
        																<td style="border: 1px solid gray;width:6%;"><?php DisplayGBP($response['sum_total_wage']); ?></td>
        																<td style="border: 1px solid gray;width:6%;"><?php DisplayGBP($response['sum_amount_paid']); ?></td>
        																<td style="border: 1px solid gray;width:6%;"><?php DisplayGBP($response['sum_outstanding']); ?></td>
        																<td style="border: 1px solid gray;width:6%"></td>
        																<td style="border: 1px solid gray;width:10%"></td>
        															</tr>
        														<?php
        															}
        														?>
        														<?php
        															$sql_sel = "select sum(rate) as sum_rate, sum(days) as sum_days, sum(rxd) as sum_rxd, sum(admin) as sum_admin, sum(rural_tx) as sum_rural_tx, sum(vans) as sum_vans, sum(deduction) as sum_deduction, sum(total_wage) as sum_total_wage, sum(amount_paid) as sum_amount_paid, sum(outstanding) as sum_outstanding from financial_info where type='DIRECT BACS PAYMENTS'";
        															$res_sel = mysqli_query($con,$sql_sel) or die(mysqli_error($con));
        															$cnt = 0;
        															while($response = mysqli_fetch_assoc($res_sel))
        															{
        																$cnt++;
        																
        														?>
        															<tr>
        																<td style="border: 1px solid gray;">BACS</td>
        																<td style="border: 1px solid gray"></td>
        																<td style="border: 1px solid gray;"><?php DisplayGBP($response['sum_rate']); ?></td>
        																<td style="border: 1px solid gray;"><?php DisplayGBP($response['sum_days']); ?></td>
        																<td style="border: 1px solid gray;"><?php DisplayGBP($response['sum_rxd']); ?></td>
        																<td style="border: 1px solid gray;"><?php DisplayGBP($response['sum_admin']); ?></td>
        																<td style="border: 1px solid gray;"><?php DisplayGBP($response['sum_rural_tx']); ?></td>
        																<td style="border: 1px solid gray;"><?php DisplayGBP($response['sum_vans']); ?></td>
        																<td style="border: 1px solid gray;"><?php DisplayGBP($response['sum_deduction']); ?></td>
        																<td style="border: 1px solid gray;"><?php DisplayGBP($response['sum_total_wage']); ?></td>
        																<td style="border: 1px solid gray;"><?php DisplayGBP($response['sum_amount_paid']); ?></td>
        																<td style="border: 1px solid gray;"><?php DisplayGBP($response['sum_outstanding']); ?></td>
        																<td style="border: 1px solid gray"></td>
        																<td style="border: 1px solid gray"></td>
        															</tr>
        														<?php
        															}
        														?>
        														
        														<?php
        															$sql_sel = "select sum(rate) as sum_rate, sum(days) as sum_days, sum(rxd) as sum_rxd, sum(admin) as sum_admin, sum(rural_tx) as sum_rural_tx, sum(vans) as sum_vans, sum(deduction) as sum_deduction, sum(total_wage) as sum_total_wage, sum(amount_paid) as sum_amount_paid, sum(outstanding) as sum_outstanding from financial_info where type='ACCOUNTANT'";
        															$res_sel = mysqli_query($con,$sql_sel) or die(mysqli_error($con));
        															
        															$cnt = 0;
        															while($response = mysqli_fetch_assoc($res_sel))
        															{
        																$cnt++;
        														?>
        															<tr>
        																<td style="border: 1px solid gray;">ACCOUNTANT</td>
        																<td style="border: 1px solid gray"></td>
        																<td style="border: 1px solid gray;"><?php DisplayGBP($response['sum_rate']); ?></td>
        																<td style="border: 1px solid gray;"><?php DisplayGBP($response['sum_days']); ?></td>
        																<td style="border: 1px solid gray;"><?php DisplayGBP($response['sum_rxd']); ?></td>
        																<td style="border: 1px solid gray;"><?php DisplayGBP($response['sum_admin']); ?></td>
        																<td style="border: 1px solid gray;"><?php DisplayGBP($response['sum_rural_tx']); ?></td>
        																<td style="border: 1px solid gray;"><?php DisplayGBP($response['sum_vans']); ?></td>
        																<td style="border: 1px solid gray;"><?php DisplayGBP($response['sum_deduction']); ?></td>
        																<td style="border: 1px solid gray;"><?php DisplayGBP($response['sum_total_wage']); ?></td>
        																<td style="border: 1px solid gray;"><?php DisplayGBP($response['sum_amount_paid']); ?></td>
        																<td style="border: 1px solid gray;"><?php DisplayGBP($response['sum_outstanding']); ?></td>
        																<td style="border: 1px solid gray"></td>
        																<td style="border: 1px solid gray"></td>
        															</tr>
        														<?php
        															}
        														?>
        														
        														<?php
        															$sql_sel = "select sum(rate) as sum_rate, sum(days) as sum_days, sum(rxd) as sum_rxd, sum(admin) as sum_admin, sum(rural_tx) as sum_rural_tx, sum(vans) as sum_vans, sum(deduction) as sum_deduction, sum(total_wage) as sum_total_wage, sum(amount_paid) as sum_amount_paid, sum(outstanding) as sum_outstanding from financial_info where type='DIRECT DEBITS'";
        															$res_sel = mysqli_query($con,$sql_sel) or die(mysqli_error($con));
        															
        															$cnt = 0;
        															while($response = mysqli_fetch_assoc($res_sel))
        															{
        																$cnt++;
        														?>
        															<tr>
        																<td style="border: 1px solid gray;">DIRECT DEBITS</td>
        																<td style="border: 1px solid gray"></td>
        																<td style="border: 1px solid gray;"><?php DisplayGBP($response['sum_rate']); ?></td>
        																<td style="border: 1px solid gray;"><?php DisplayGBP($response['sum_days']); ?></td>
        																<td style="border: 1px solid gray;"><?php DisplayGBP($response['sum_rxd']); ?></td>
        																<td style="border: 1px solid gray;"><?php DisplayGBP($response['sum_admin']); ?></td>
        																<td style="border: 1px solid gray;"><?php DisplayGBP($response['sum_rural_tx']); ?></td>
        																<td style="border: 1px solid gray;"><?php DisplayGBP($response['sum_vans']); ?></td>
        																<td style="border: 1px solid gray;"><?php DisplayGBP($response['sum_deduction']); ?></td>
        																<td style="border: 1px solid gray;"><?php DisplayGBP($response['sum_total_wage']); ?></td>
        																<td style="border: 1px solid gray;"><?php DisplayGBP($response['sum_amount_paid']); ?></td>
        																<td style="border: 1px solid gray;"><?php DisplayGBP($response['sum_outstanding']); ?></td>
        																<td style="border: 1px solid gray"></td>
        																<td style="border: 1px solid gray"></td>
        															</tr>
        														<?php
        															}
        														?>
        														
        														<?php
        															$sql_sel = "select sum(rate) as sum_rate, sum(days) as sum_days, sum(rxd) as sum_rxd, sum(admin) as sum_admin, sum(rural_tx) as sum_rural_tx, sum(vans) as sum_vans, sum(deduction) as sum_deduction, sum(total_wage) as sum_total_wage, sum(amount_paid) as sum_amount_paid, sum(outstanding) as sum_outstanding from financial_info where type='SUPPLIERS'";
        															$res_sel = mysqli_query($con,$sql_sel) or die(mysqli_error($con));
        															
        															$cnt = 0;
        															while($response = mysqli_fetch_assoc($res_sel))
        															{
        																$cnt++;
        														?>
        															<tr>
        																<td style="border: 1px solid gray;">SUPPLIERS</td>
        																<td style="border: 1px solid gray"></td>
        																<td style="border: 1px solid gray;"><?php DisplayGBP($response['sum_rate']); ?></td>
        																<td style="border: 1px solid gray;"><?php DisplayGBP($response['sum_days']); ?></td>
        																<td style="border: 1px solid gray;"><?php DisplayGBP($response['sum_rxd']); ?></td>
        																<td style="border: 1px solid gray;"><?php DisplayGBP($response['sum_admin']); ?></td>
        																<td style="border: 1px solid gray;"><?php DisplayGBP($response['sum_rural_tx']); ?></td>
        																<td style="border: 1px solid gray;"><?php DisplayGBP($response['sum_vans']); ?></td>
        																<td style="border: 1px solid gray;"><?php DisplayGBP($response['sum_deduction']); ?></td>
        																<td style="border: 1px solid gray;"><?php DisplayGBP($response['sum_total_wage']); ?></td>
        																<td style="border: 1px solid gray;"><?php DisplayGBP($response['sum_amount_paid']); ?></td>
        																<td style="border: 1px solid gray;"><?php DisplayGBP($response['sum_outstanding']); ?></td>
        																<td style="border: 1px solid gray"></td>
        																<td style="border: 1px solid gray"></td>
        															</tr>
        														<?php
        															}
        														?>
        														
        														<?php
        															$sql_sel = "select sum(rate) as sum_rate, sum(days) as sum_days, sum(rxd) as sum_rxd, sum(admin) as sum_admin, sum(rural_tx) as sum_rural_tx, sum(vans) as sum_vans, sum(deduction) as sum_deduction, sum(total_wage) as sum_total_wage, sum(amount_paid) as sum_amount_paid, sum(outstanding) as sum_outstanding from financial_info where type='CREDIT CARDS'";
        															$res_sel = mysqli_query($con,$sql_sel) or die(mysqli_error($con));
        															
        															$cnt = 0;
        															while($response = mysqli_fetch_assoc($res_sel))
        															{
        																$cnt++;
        														?>
        															<tr>
        																<td style="border: 1px solid gray;">CREDIT CARDS</td>
        																<td style="border: 1px solid gray"></td>
        																<td style="border: 1px solid gray;"><?php DisplayGBP($response['sum_rate']); ?></td>
        																<td style="border: 1px solid gray;"><?php DisplayGBP($response['sum_days']); ?></td>
        																<td style="border: 1px solid gray;"><?php DisplayGBP($response['sum_rxd']); ?></td>
        																<td style="border: 1px solid gray;"><?php DisplayGBP($response['sum_admin']); ?></td>
        																<td style="border: 1px solid gray;"><?php DisplayGBP($response['sum_rural_tx']); ?></td>
        																<td style="border: 1px solid gray;"><?php DisplayGBP($response['sum_vans']); ?></td>
        																<td style="border: 1px solid gray;"><?php DisplayGBP($response['sum_deduction']); ?></td>
        																<td style="border: 1px solid gray;"><?php DisplayGBP($response['sum_total_wage']); ?></td>
        																<td style="border: 1px solid gray;"><?php DisplayGBP($response['sum_amount_paid']); ?></td>
        																<td style="border: 1px solid gray;"><?php DisplayGBP($response['sum_outstanding']); ?></td>
        																<td style="border: 1px solid gray"></td>
        																<td style="border: 1px solid gray"></td>
        															</tr>
        														<?php
        															}
        														?>
        														</tbody>
        													</thead>
        												</table>
        										
        											</div>
        										</div>
        										
        					                </div>
        				            	</div>
        				            </div>
                    			</form>  
        						
        					
        						
                    		</div>
                    	</div>
        			</div>
                </div>
            </div>
        </div>
    </body>
</html>