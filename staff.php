<?php
//phpinfo();
/* * **-------------------------------------------------------------------**************************    

  Purpose     : 	Buyer Information Detail Page

  Project 	:	Sales Staff DB

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
/********************buyer information select start***************************** */
if (isset($_GET['rid']))
{
	$buyer = "select * from staffs_info where auto_id=".$_GET['rid'];
	$resb = mysqli_query($con, $buyer) or die(mysqli_error($con) . "11");
	$recb = mysqli_fetch_assoc($resb);
	
	
	$sql_previous = "SELECT auto_id FROM staffs_info WHERE auto_id<" . $recb['auto_id'] . $_SESSION['filter_user']." order by auto_id desc LIMIT 1";
	$result_previous = mysqli_query($con, $sql_previous) or die(mysqli_error($con));
	if ($row_previous = mysqli_fetch_assoc($result_previous))
		$next_id = $row_previous['auto_id'];

	$sql_next = "SELECT auto_id FROM staffs_info WHERE auto_id>" . $recb['auto_id'] .$_SESSION['filter_user']. " LIMIT 1";
	$result_next = mysqli_query($con, $sql_next) or die(mysqli_error($con));
	if ($row_next = mysqli_fetch_assoc($result_next))
		$prev_id = $row_next['auto_id'];
		
	if (isset($_GET['notify']))
	{
	
		// Set notification as viewed for log info
		$sql_upd = sprintf("update log_info set is_viewed='%d' where is_viewed='%d' and customer_id='%d' and agent != '%s'",1,0,$_GET['rid'],$_SESSION['user_login']);
		$res=mysqli_query($con, $sql_upd) or die(mysqli_error($con)."11");
		
		$sql_log = sprintf("insert into system_log_info (action,agent,query,log_time) values ('%s','%s','%s',sysdate())","Update log info for viewed notification : staff.php ",$_SESSION['user_login'],mysqli_real_escape_string($con, $sql_upd));
		mysqli_query($con, $sql_log) or die(mysqli_error($con));	
	}
}

if ($_POST['Submit'] == "Save") 
{
	
	$Destination = 'userprofile/userfiles/avatars';
    if(!isset($_FILES['ImageFile']) || !is_uploaded_file($_FILES['ImageFile']['tmp_name'])){
    	$prev_avatar = "";
    	$sql_sel_avatar="select user_avatar from staffs_info WHERE auto_id = ".$_GET['rid'];
    	$sql_sel_res = mysqli_query($con, $sql_sel_avatar) or die(mysqli_error($con)); 
    	if ($sql_sel_rec = mysqli_fetch_assoc($sql_sel_res))
    	{
			$prev_avatar = $sql_sel_rec['user_avatar'];
			
		}
    	if (strlen($prev_avatar) == 0)
    	{
			$NewImageName= 'default.jpg';
        	move_uploaded_file($_FILES['ImageFile']['tmp_name'], "$Destination/$NewImageName");	
        	$sql_upd_avatar="UPDATE staffs_info SET user_avatar='$NewImageName' WHERE auto_id = ".$_GET['rid'];
	    	$sql_upd_res = mysqli_query($con, $sql_upd_avatar) or die(mysqli_error($con)); 
		}
    }
    else{
        $RandomNum   = rand(0, 9999999999);
        $ImageName = str_replace(' ','-',strtolower($_FILES['ImageFile']['name']));
        $ImageType = $_FILES['ImageFile']['type'];
        $ImageExt = substr($ImageName, strrpos($ImageName, '.'));
        $ImageExt = str_replace('.','',$ImageExt);
        $ImageName = preg_replace("/\.[^.\s]{3,4}$/", "", $ImageName);
        $NewImageName = $ImageName.'-'.$RandomNum.'.'.$ImageExt;
        move_uploaded_file($_FILES['ImageFile']['tmp_name'], "$Destination/$NewImageName");
        
        $sql_upd_avatar="UPDATE staffs_info SET user_avatar='$NewImageName' WHERE auto_id = ".$_GET['rid'];
    	$sql_upd_res = mysqli_query($con, $sql_upd_avatar) or die(mysqli_error($con)); 
    }
    
   
    
	$_POST['hm_ph'] = my_phone_format($_POST['hm_ph']);
   
    
    $sql_upd = "update staffs_info set 
 					  name = '" . mysqli_real_escape_string($con, $_POST['name']) . "',
 					  hm_ph = '" . $_POST['hm_ph'] . "', 					
 					  email = '" . mysqli_real_escape_string($con, $_POST['email']) . "',
 					  address = '" . mysqli_real_escape_string($con, $_POST['address']) . "', 					 
 					  website = '" . mysqli_real_escape_string($con, $_POST['website']) . "',
 					  job_title = '" . mysqli_real_escape_string($con, $_POST['job_title']) . "',
 					  position = '" . mysqli_real_escape_string($con, $_POST['position']) . "',
					  cust_upd_dt= curdate()										  
			  where auto_id =" . $_GET['rid'] . "";
   
    mysqli_query($con, $sql_upd) or die(mysqli_error($con));
    
   
     // insert action logs into system_log_info
	$sql_log = sprintf("insert into system_log_info (action,agent,query,log_time) values ('%s','%s','%s',sysdate())","Update Staff : staff.php ",$_SESSION['user_login'],mysqli_real_escape_string($con, $sql_upd));
	mysqli_query($con, $sql_log) or die(mysqli_error($con));	
	
	// Save log
	saveLog($_GET['rid'],'Staff','Staff','changed');
	
    header("Location: staff.php?rid=" . $_GET['rid']);
    exit();
      
}


if ($_POST['Submit'] == "Cancel") {
	
    header("Location: staffs.php");
    exit();
	
}	

// Wages Calculator
{
	if (($_POST['Submit'] == "CalcWage") and isset($_GET['rid']))
	{				
		$staff_id = $_GET['rid'];
		$agent = $_SESSION['user_login'];
		
		// OFFICE STAFF
		{
			// delete old OFFICE STAFF
			$sql_del = sprintf("delete from office_staff_info where staff_id = %d",$staff_id);
			mysqli_query($con, $sql_del) or die(mysqli_error($con));
		
			// insert new one
			for ($i=0;$i<3;$i++)
			{
				$rate = getRealValue($_POST['office_rate'][$i]);	
				$days = getRealValue($_POST['office_days'][$i]);	
				$rxd = getRealValue($_POST['office_rxd'][$i]);	
				$ded = getRealValue($_POST['office_ded'][$i]);	
				$total_wage = getRealValue($_POST['office_total_wage'][$i]);	
				$paid_to_worker = getRealValue($_POST['office_paid_to_worker'][$i]);	
				$outstanding = getRealValue($_POST['office_outstanding'][$i]);	
				$notes = mysqli_real_escape_string($con, $_POST['office_notes'][$i]);	
				$name = mysqli_real_escape_string($con, $_POST['office_name'][$i]);	
				$type = 'OFFICE STAFF';	
				$due_date = $_POST['office_due_date'][$i];	
				
				$sql_ins= sprintf("insert into office_staff_info (staff_id,type,name,rate,days,rxd,ded,total_wage,paid_to_worker,outstanding,due_date,notes,agent) values ('%d','%s','%s','%f','%d','%f','%f','%f','%f','%f','%s','%s','%s')",$staff_id,$type,$name,$rate,$days,$rxd,$ded,$total_wage,$paid_to_worker,$outstanding,$due_date,$notes,$agent);
				mysqli_query($con, $sql_ins) or die(mysqli_error($con));
		
			}
		}
		
		// SITE STAFF
		{
			// delete old SITE STAFF
			$sql_del = sprintf("delete from site_staff_info where staff_id = %d",$staff_id);
			mysqli_query($con, $sql_del) or die(mysqli_error($con));
		
			// insert new one
			for ($i=0;$i<7;$i++)
			{
				$rate = getRealValue($_POST['site_rate'][$i]);	
				$days = getRealValue($_POST['site_days'][$i]);	
				$rxd = getRealValue($_POST['site_rxd'][$i]);	
				$ded = getRealValue($_POST['site_ded'][$i]);	
				$total_wage = getRealValue($_POST['site_total_wage'][$i]);	
				$paid_to_worker = getRealValue($_POST['site_paid_to_worker'][$i]);	
				$outstanding = getRealValue($_POST['site_outstanding'][$i]);	
				$notes = mysqli_real_escape_string($con, $_POST['site_notes'][$i]);	
				$name = mysqli_real_escape_string($con, $_POST['site_name'][$i]);	
				$type = 'SITE STAFF';	
				$due_date = $_POST['site_due_date'][$i];	
				
				$sql_ins= sprintf("insert into site_staff_info (staff_id,type,name,rate,days,rxd,ded,total_wage,paid_to_worker,outstanding,due_date,notes,agent) values ('%d','%s','%s','%f','%d','%f','%f','%f','%f','%f','%s','%s','%s')",$staff_id,$type,$name,$rate,$days,$rxd,$ded,$total_wage,$paid_to_worker,$outstanding,$due_date,$notes,$agent);
				mysqli_query($con, $sql_ins) or die(mysqli_error($con));
		
			}
		}
		
		// BILL STAFF
		{
			// delete old BILL STAFF
			$sql_del = sprintf("delete from bill_staff_info where staff_id = %d",$staff_id);
			mysqli_query($con, $sql_del) or die(mysqli_error($con));
		
			// insert new one
			for ($i=0;$i<11;$i++)
			{
				$rate = getRealValue($_POST['bill_rate'][$i]);	
				$days = getRealValue($_POST['bill_days'][$i]);	
				$rxd = getRealValue($_POST['bill_rxd'][$i]);	
				$ded = getRealValue($_POST['bill_ded'][$i]);	
				$total_wage = getRealValue($_POST['bill_total_wage'][$i]);	
				$paid_to_worker = getRealValue($_POST['bill_paid_to_worker'][$i]);	
				$outstanding = getRealValue($_POST['bill_outstanding'][$i]);	
				$notes = mysqli_real_escape_string($con, $_POST['bill_notes'][$i]);	
				$name = mysqli_real_escape_string($con, $_POST['bill_name'][$i]);	
				$type = 'BILLS / OUTSTANDING SUMS';	
				$due_date = $_POST['bill_due_date'][$i];	
				
				$sql_ins= sprintf("insert into bill_staff_info (staff_id,type,name,rate,days,rxd,ded,total_wage,paid_to_worker,outstanding,due_date,notes,agent) values ('%d','%s','%s','%f','%d','%f','%f','%f','%f','%f','%s','%s','%s')",$staff_id,$type,$name,$rate,$days,$rxd,$ded,$total_wage,$paid_to_worker,$outstanding,$due_date,$notes,$agent);
				mysqli_query($con, $sql_ins) or die(mysqli_error($con));
		
			}
		}
		
		/*header("Location: staff.php?rid=" . $rid);
	    exit();*/
	}

	
}


// get latitude, longitude and formatted address
if (isset($recb['address']) and strlen($recb['address'])>0) 
{
    $data_arr = geocode($recb['address']);	
}

function DisplayGBP($val)
{
	$val = trim($val);
	$val = 'GBP'.number_format($val,2);
	echo $val;
	
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
			    margin-left: 5px;
			    height: 26px;
			    
			}
			
		
			.create-skus-form input {
			    width: 100%;
			    float: left;
			    font-weight: normal;
			    color: black;
			    font-size: 11px;
			    padding-left: 3px;
			    margin-left: 5px;
			    height: 26px;
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
		            format: 'M d, Y',
		            hour: '7:00 AM',
		            step: 30,
		            formatTime: 'g:i A',
		            allowTimes: ['7:00 AM', '7:30 AM', '8:00 AM', '8:30 AM', '9:00 AM', '9:30 AM', '10:00 AM', '10:30 AM', '11:00 AM', '11:30 AM', '12:00 PM', '12:30 PM', '1:00 PM', '1:30 PM', '2:00 PM', '2:30 PM', '3:00 PM', '3:30 PM', '4:00 PM', '4:30 PM', '5:00 PM', '5:30 PM', '6:00 PM', '6:30 PM', '7:00 PM'],
		        });
				
            });
			
        </script>   
    </head>
    <body>
        <script type="text/javascript" src="popcalendar.js"></script>
        <div>	    	
	    	<input type="hidden" name="sel_customer_id" id ="sel_customer_id" value="<?php echo $_SESSION['user_login'].':'.$_GET['rid']?>">	    	
    	</div>
    	
    	<?php include("layout.php");?>
	<?php include("menu.php");?>
			
		<!-- main content -->
		<div class="container">			
			<div id="my-main-content">				
				<br>
				<ol class="breadcrumb pull-right" style="margin-bottom: 5px;">
				  <?php
                	if ($next_id != -1)
                	{
                	?>
					<li><a href="staff.php?rid=<?php echo $next_id;?>">Previous</a></li>	
					<?php
					}
                ?>
              
                <?php
                	if ($prev_id != -1)
                	{
                	?>
					<li><a href="staff.php?rid=<?php echo $prev_id;?>">Next</a></li>	
					<?php
					}
                ?>  
       			</ol>
				<h2 style="margin-top:0px">&nbsp;&nbsp;Staff</h2>
				
				<!-- Main content -->
				<div class="row">            		
            		<div class="col-md-12 col-lg-12">   	
			             <div class="panel panel-inverse" data-sortable-id="ui-general-1">
			                <div class="panel-heading">
			                	<h4 class="panel-title">
			                		<a>Main</a>
							        <div class="panel-heading-btn">
				                        <a href="#" id="panel-fullscreen-Main" role="button"  class="btn btn-xs btn-icon btn-circle btn-default" title="Toggle fullscreen"><i class="fa fa-expand"></i></a>
				                        <a href="#collapseMain" data-toggle="collapse" data-target="#collapseMain" class="btn btn-xs btn-icon btn-circle btn-warning collapsed" ><i class="fa fa-minus"></i></a>
							       	</div>
							    </h4>
			                </div>
			                <form action="<?php echo 'staff.php?rid='.$_GET['rid']; ?>" method="post" enctype="multipart/form-data" id="UploadForm">
					            <div id="collapseMain"  class="panel-collapse collapse in">
				                	<div class="panel-body" id="Main">	
				                		<div class="row">
				                			<div class="form-group">
							                    <div  class="col-lg-5" style="max-width: 170px">
							                        <div class="shortpreview">
							                        	<label for="{{ form.uploadFile.for_label" class="control-label">Avatar</label>
							                            <br> 
							                            <img src="userprofile/userfiles/avatars/<?php 
							                            					if (isset($recb['user_avatar'])) 
							                            						echo $recb['user_avatar'];
							                            					else 
							                            						echo 'default.jpg';?>" alt="" class="img-thumbnail" style="max-width: 150px">
							                            <input name="ImageFile" type="file" id="uploadFile" value="<?php 
							                            					if (isset($recb['user_avatar'])) 
							                            						echo $recb['user_avatar'];
							                            					else 
							                            						echo 'default.jpg';?>"/>
							                        </div>
							                    </div>
											</div>
				                		</div>
				                        <div class="row">
				                            <div class="col-lg-5">
				                                <div class="form-group">
				                                    <label for="{{ form.name.for_label" class="control-label">Name</label>
				                                    <input class="form-control  staff-form-control target semi-bold" id="name" maxlength="100" name="name" type="text"  
				                                    					value="<?php if (isset($_POST['name'])) {
			                                                                    echo $_POST['name'];
			                                                                } else if (isset($recb['name'])) {
			                                                                    echo $recb['name'];
			                                                                } ?>">
				                                </div>
				                            </div>
				                            <div class="col-lg-3">
				                                <div class="form-group">
				                                    <label for="{{ form.hm_ph.for_label" class="control-label">Phone</label>
				                                    <input class="form-control  staff-form-control target semi-bold" id="hm_ph" maxlength="100" name="hm_ph" type="text"  
				                                    					value="<?php if (isset($_POST['hm_ph'])) {
			                                                                    echo my_phone_format2($_POST['hm_ph']);
			                                                                } else if (isset($recb['hm_ph'])) {
			                                                                    echo my_phone_format2($recb['hm_ph']);
			                                                                } ?>">
				                                </div>
				                            </div>
				                           
				                            <div class="col-lg-4">
				                                <div class="form-group">
				                                    <label for="{{ form.email.for_label" class="control-label">Email</label>
				                                    <input class="form-control  staff-form-control target semi-bold" id="email" maxlength="100" name="email" type="text"  
				                                    					value="<?php if (isset($_POST['email'])) {
			                                                                    echo $_POST['email'];
			                                                                } else if (isset($recb['email'])) {
			                                                                    echo $recb['email'];
			                                                                } ?>">
				                                </div>
				                            </div>
				                        </div>
				                        <div class="row">
				                        	<div class="col-lg-4">
		                                        <div class="form-group">
		                                            <label for="{{ form.website.for_label" class="control-label">Website</label>
		                                            <input class="form-control  staff-form-control target" id="website" maxlength="50" name="website" type="text" value="<?php 
															 if (isset($_POST['website'])) {
	                                                                    echo $_POST['website'];
	                                                                } else if (isset($recb['website'])) {
	                                                                    echo $recb['website'];
	                                                                } ?>">
		                                        </div>
		                                    </div>
		                                    <div class="col-lg-4">
		                                        <div class="form-group">
		                                            <label for="{{ form.job_title.for_label" class="control-label">Title</label>
		                                            <input class="form-control  staff-form-control target" id="job_title" maxlength="50" name="job_title" type="text" value="<?php 
															 if (isset($_POST['job_title'])) {
	                                                                    echo $_POST['job_title'];
	                                                                } else if (isset($recb['job_title'])) {
	                                                                    echo $recb['job_title'];
	                                                                } ?>">
		                                        </div>
		                                    </div>
		                                    <div class="col-lg-4">
		                                        <div class="form-group">
		                                            <label for="{{ form.position.for_label" class="control-label">Position</label>
		                                            <input class="form-control  staff-form-control target" id="position" maxlength="50" name="position" type="text" value="<?php 
															 if (isset($_POST['position'])) {
	                                                                    echo $_POST['position'];
	                                                                } else if (isset($recb['position'])) {
	                                                                    echo $recb['position'];
	                                                                } ?>">
		                                        </div>
		                                    </div>
		                                </div>
				                        <div class="row">
			                                <div class="form-group">
			                                    <label for="{{ form.address.for_label" class="control-label">Google Address</label>
			                                    <textarea class="form-control  staff-form-control target" cols="40" id="address" maxlength="2500" name="address" rows="5"><?php 
																 if (isset($_POST['address'])) {
		                                                                    echo $_POST['address'];
		                                                                } else if (isset($recb['address'])) {
		                                                                    echo $recb['address'];
		                                                                } ?></textarea>
			                                </div>
				                        </div>
										<div class="row">
									<?php 
									if($data_arr)
									{
								        $latitude = $data_arr[0];
								        $longitude = $data_arr[1];
								        $formatted_address = $data_arr[2];
								    ?>
										 
									    <!-- google map will be shown here -->
									    <div id="gmap_canvas">Loading map...</div>
									    <div id='map-label'>Map shows approximate location.</div>
									 
									    <!-- JavaScript to show google map -->
									  <!-- <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBtoszzkxQBlYhRcG8svsr3m-ogX6Z1WgM&libraries=places&callback=initMap"async defer></script>-->
									   <!--<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBtoszzkxQBlYhRcG8svsr3m-ogX6Z1WgM&libraries=places"></script>-->
									   <script type="text/javascript" src="<?php echo 'https://maps.googleapis.com/maps/api/js?key='.$_SESSION['google_map_api'].'&libraries=places';?>"></script>
									    <script type="text/javascript">
									        function init_map() {
									        	console.log('init_map');
									            var myOptions = {
									                zoom: 14,
									                center: new google.maps.LatLng(<?php echo $latitude; ?>, <?php echo $longitude; ?>),
									                mapTypeId: google.maps.MapTypeId.ROADMAP
									            };
									            map = new google.maps.Map(document.getElementById("gmap_canvas"), myOptions);
									            marker = new google.maps.Marker({
									                map: map,
									                position: new google.maps.LatLng(<?php echo $latitude; ?>, <?php echo $longitude; ?>)
									            });
									            infowindow = new google.maps.InfoWindow({
									                content: "<?php echo $formatted_address; ?>"
									            });
									            google.maps.event.addListener(marker, "click", function () {
									                infowindow.open(map, marker);
									            });
									            infowindow.open(map, marker);
									        }
									        google.maps.event.addDomListener(window, 'load', init_map);
									    </script>
									 
									    <?php
									 
									    // if unable to geocode the address
									    }else{
									        echo "No map found.";
									    }
										
										?>	
										</div>
					                   
			                		</div>
									<div class="panel-footer">
		                	
		                				<button type="submit" id="save-main" name="Submit" value="Save" class="btn btn-sm  btn-primary">Save</button> 
		                				<button type="submit" id="cancel-main" name="Submit" value="Cancel" class="btn btn-sm  btn-default">Cancel</button> 
								    </div>	
								</div>
						    </form>                
						</div>	
            		</div>
            	</div>
            	<div class="row">       
            		<div class="col-md-12 col-lg-12"> 
            			<form action="<?php echo 'staff.php?rid='.$_GET['rid']; ?>" method="post" enctype="multipart/form-data" id="CalcWageForm">
            				<div class="panel panel-inverse" data-sortable-id="ui-general-7">
								<div class="panel-heading">
				                	<h4 class="panel-title">
				                		<a>Installation Wages Calculator</a>
								        <div class="panel-heading-btn">
					                        <a href="#" id="panel-fullscreen-Meeting" role="button"  class="btn btn-xs btn-icon btn-circle btn-default" title="Toggle fullscreen"><i class="fa fa-expand"></i></a>
					                        <a href="#collapseMeeting" data-toggle="collapse" data-target="#collapseMeeting" class="btn btn-xs btn-icon btn-circle btn-warning collapsed" ><i class="fa fa-minus"></i></a>
					                    </div> 
								        <div class="pull-right">
											<button class="btn btn-xs btn-success" style="font-size: 14px;font-weight: normal;padding-top: 0px;  padding-bottom: 0px;" type="submit" name="Submit" id="btn_calc_wages" value="CalcWage">Calc</button>  
				                   		</div>
								    </h4>
				                </div>
				                <div id="collapseMeeting"  class="panel-collapse collapse in">
				                	<div class="panel-body animated fadeIn" id="Meeting">		
				                		<div class="row table_placeholder">
					                    	<div class="table-responsive">
												<table width="97%" border="0" cellspacing="0" cellpadding="0" class="create-skus-form margin_10 bold" id="bnk_tbl">
													<thead>
														<tr>														
															<th style="text-align: center;width:8%;border:0">TYPE</th>
															<th style="text-align: center;width:15%;border:0">NAME</th>
															<th style="text-align: center;width:8%;border:0">RATE</th>
															<th style="text-align: center;width:8%;border:0">DAYS</th>
															<th style="text-align: center;width:8%;border:0">R X D</th>
															<th style="text-align: center;width:8%;border:0">DED</th>
															<th style="text-align: center;width:8%;border:0">TOTAL WAGE</th>
															<th style="text-align: center;width:8%;border:0">PAID TO WORKER</th>
															<th style="text-align: center;width:8%;border:0">OUTSTANDING</th>
															<th style="text-align: center;width:8%;border:0">DUE DATE</th>
															<th style="text-align: center;width:8%;border:0">NOTES</th>
														</tr>
														<tbody>
														<?php
															$sql_sel = sprintf("select * from office_staff_info where staff_id=%d",$_GET['rid']);
															$res_sel = mysqli_query($con, $sql_sel) or die(mysqli_error($con));
															
															$total_office = 0;
															$cnt = 0;
															while($cnt<3)
															{
																$cnt++;
																$response = mysqli_fetch_assoc($res_sel);
																if ($response)
																{
																	$total_office += getRealValue($response['outstanding']);
																}
																
														?>
															<tr>
																<td style="text-align: center;border:0;">
																	<input type="text"   style="text-align:center;font-weight:bold"   name="office_type[]"  id="office_type[]" readonly="true" value="<?php	
														   	  	    	if ($cnt == 1)
														   	  	    	{ 
														   	  	    		echo 'OFFICE STAFF';
														   	  	    	}else  
														   	  	    	{ 
														   	  	    		echo ''; 
														   	  	    	}	
														   	  	    
														   	  	    ?>"/>
																</td>
															
																<td style="text-align: center;border:0">
																	<input type="text"   style="text-align:left;font-weight:bold"   name="office_name[]"  id="office_name[]" value="<?php if ($response!=false)
														   	  	    {	
														   	  	    	if (isset($response['name'])) 
														   	  	    	{ 
														   	  	    		echo $response['name']; 
														   	  	    	}else  
														   	  	    	{ 
														   	  	    		echo ''; 
														   	  	    	}	
														   	  	    }
														   	  	    ?>"/>
																</td>
																<td style="text-align: center;border:0;">
																	<input type="text"   style="text-align:center"   name="office_rate[]"  id="office_rate[]" value="<?php if ($response!=false)
														   	  	    {	
														   	  	    	if (isset($response['rate'])) 
														   	  	    	{ 
														   	  	    		DisplayGBP($response['rate']);
														   	  	    	}else  
														   	  	    	{ 
														   	  	    		echo ''; 
														   	  	    	}	
														   	  	    }
														   	  	    ?>"/>
																</td>
																<td style="text-align: center;border:0;">
																	<input type="text"  style="text-align:center"   name="office_days[]"  id="office_days[]" value="<?php if ($response!=false)
														   	  	    {	
														   	  	    	if (isset($response['days'])) 
														   	  	    	{ 
														   	  	    		echo $response['days'];
														   	  	    	}else  
														   	  	    	{ 
														   	  	    		echo ''; 
														   	  	    	}	
														   	  	    }
														   	  	    ?>"/>
																</td>
																<td style="text-align: center;border:0;">
																	<input type="text"   style="text-align:center"   name="office_rxd[]"  id="office_rxd[]" value="<?php if ($response!=false)
														   	  	    {	
														   	  	    	if (isset($response['rxd'])) 
														   	  	    	{ 
														   	  	    		DisplayGBP($response['rxd']);
														   	  	    	}else  
														   	  	    	{ 
														   	  	    		echo ''; 
														   	  	    	}	
														   	  	    }
														   	  	    ?>"/>
																</td>
																<td style="text-align: center;border:0;">
																	<input type="text"   style="text-align:center"   name="office_ded[]"  id="office_ded[]" value="<?php if ($response!=false)
														   	  	    {	
														   	  	    	if (isset($response['ded'])) 
														   	  	    	{ 
														   	  	    		DisplayGBP($response['ded']);
														   	  	    	}else  
														   	  	    	{ 
														   	  	    		echo ''; 
														   	  	    	}	
														   	  	    }
														   	  	    ?>"/>
																</td>
																<td style="text-align: center;border:0;">
																	<input type="text"   style="text-align:center"   name="office_total_wage[]"  id="office_total_wage[]" value="<?php if ($response!=false)
														   	  	    {	
														   	  	    	if (isset($response['total_wage'])) 
														   	  	    	{ 
														   	  	    		DisplayGBP($response['total_wage']);
														   	  	    	}else  
														   	  	    	{ 
														   	  	    		echo ''; 
														   	  	    	}	
														   	  	    }
														   	  	    ?>"/>
																</td>
																<td style="text-align: center;border:0;">
																	<input type="text"   style="text-align:center"   name="office_paid_to_worker[]"  id="office_paid_to_worker[]" value="<?php if ($response!=false)
														   	  	    {	
														   	  	    	if (isset($response['paid_to_worker'])) 
														   	  	    	{ 
														   	  	    		DisplayGBP($response['paid_to_worker']);
														   	  	    	}else  
														   	  	    	{ 
														   	  	    		echo ''; 
														   	  	    	}	
														   	  	    }
														   	  	    ?>"/>
																</td>
																<td style="text-align: center;border:0;">
																	<input type="text"   style="text-align:center"   name="office_outstanding[]"  id="office_outstanding[]" value="<?php if ($response!=false)
														   	  	    {	
														   	  	    	if (isset($response['outstanding'])) 
														   	  	    	{ 
														   	  	    		DisplayGBP($response['outstanding']);
														   	  	    	}else  
														   	  	    	{ 
														   	  	    		echo ''; 
														   	  	    	}	
														   	  	    }
														   	  	    ?>"/>
																</td>
																<td style="text-align: center;border:0;">
																   <input class="datetimePicker" id="office_due_date[]" name="office_due_date[]" type="text" value="<?php if ($response!=false)
														   	  	    {	
														   	  	    	if (isset($response['due_date'])) 
														   	  	    	{ 
														   	  	    		echo $response['due_date'];
														   	  	    	}else  
														   	  	    	{ 
														   	  	    		echo ''; 
														   	  	    	}	
														   	  	    }
														   	  	    ?>"/>
																</td>
																<td style="text-align: left;border:0;">
																   <textarea  rows="1"  name="office_notes[]" id="office_notes[]"  ><?php if ($response!=false)
														   	  	    {	
														   	  	    	if (isset($response['notes'])) 
														   	  	    	{ 
														   	  	    		echo $response['notes'];
														   	  	    	}else  
														   	  	    	{ 
														   	  	    		echo ''; 
														   	  	    	}	
														   	  	    }
														   	  	    ?></textarea>
																</td>
															</tr>
														<?php
															}
														?>
															<tr>
																<td colspan="8"></td>
																<td style="text-align: center;border:0;">
																	<input type="text"   style="text-align:center;color: black;font-weight:bold;"   name="office_total"  id="office_total[]" value="TOTAL OFFICE"/>
																</td>
																<td style="text-align: center;border:0;">
																	<input type="text"   style="text-align:center;color: black;font-weight:bold;"   name="office_total_val"  id="office_total_val[]" value="<?php DisplayGBP($total_office);?>"/>
																</td>
																<td></td>
															</tr>
															
															<tr>
																<td colspan="11">&nbsp;</td>
															</tr>
														<?php
															$sql_sel = sprintf("select * from site_staff_info where staff_id=%d",$_GET['rid']);
															$res_sel = mysqli_query($con, $sql_sel) or die(mysqli_error($con));
															
															$total_site = 0;
															$ciu_due = 0;
															$cnt = 0;
															while($cnt<7)
															{
																$cnt++;
																$response = mysqli_fetch_assoc($res_sel);
																if ($response)
																{
																	$total_site += getRealValue($response['outstanding']);
																	$ciu_due += getRealValue($response['ded']);
																}
																
														?>
															<tr>
																<td style="text-align: center;border:0;">
																	<input type="text"   style="text-align:center;font-weight:bold"   name="site_type[]"  id="site_type[]"  readonly="true" value="<?php	
														   	  	    	if ($cnt == 1)
														   	  	    	{ 
														   	  	    		echo 'SITE STAFF';
														   	  	    	}else  
														   	  	    	{ 
														   	  	    		echo ''; 
														   	  	    	}	
														   	  	    
														   	  	    ?>"/>
																</td>
															
																<td style="text-align: center;border:0">
																	<input type="text"   style="text-align:left;font-weight:bold"   name="site_name[]"  id="site_name[]" value="<?php if ($response!=false)
														   	  	    {	
														   	  	    	if (isset($response['name'])) 
														   	  	    	{ 
														   	  	    		echo $response['name']; 
														   	  	    	}else  
														   	  	    	{ 
														   	  	    		echo ''; 
														   	  	    	}	
														   	  	    }
														   	  	    ?>"/>
																</td>
																<td style="text-align: center;border:0;">
																	<input type="text"   style="text-align:center"   name="site_rate[]"  id="site_rate[]" value="<?php if ($response!=false)
														   	  	    {	
														   	  	    	if (isset($response['rate'])) 
														   	  	    	{ 
														   	  	    		DisplayGBP($response['rate']);
														   	  	    	}else  
														   	  	    	{ 
														   	  	    		echo ''; 
														   	  	    	}	
														   	  	    }
														   	  	    ?>"/>
																</td>
																<td style="text-align: center;border:0;">
																	<input type="text"  style="text-align:center"   name="site_days[]"  id="site_days[]" value="<?php if ($response!=false)
														   	  	    {	
														   	  	    	if (isset($response['days'])) 
														   	  	    	{ 
														   	  	    		echo $response['days'];
														   	  	    	}else  
														   	  	    	{ 
														   	  	    		echo ''; 
														   	  	    	}	
														   	  	    }
														   	  	    ?>"/>
																</td>
																<td style="text-align: center;border:0;">
																	<input type="text"   style="text-align:center"   name="site_rxd[]"  id="site_rxd[]" value="<?php if ($response!=false)
														   	  	    {	
														   	  	    	if (isset($response['rxd'])) 
														   	  	    	{ 
														   	  	    		DisplayGBP($response['rxd']);
														   	  	    	}else  
														   	  	    	{ 
														   	  	    		echo ''; 
														   	  	    	}	
														   	  	    }
														   	  	    ?>"/>
																</td>
																<td style="text-align: center;border:0;">
																	<input type="text"   style="text-align:center"   name="site_ded[]"  id="site_ded[]" value="<?php if ($response!=false)
														   	  	    {	
														   	  	    	if (isset($response['ded'])) 
														   	  	    	{ 
														   	  	    		DisplayGBP($response['ded']);
														   	  	    	}else  
														   	  	    	{ 
														   	  	    		echo ''; 
														   	  	    	}	
														   	  	    }
														   	  	    ?>"/>
																</td>
																<td style="text-align: center;border:0;">
																	<input type="text"   style="text-align:center"   name="site_total_wage[]"  id="site_total_wage[]" value="<?php if ($response!=false)
														   	  	    {	
														   	  	    	if (isset($response['total_wage'])) 
														   	  	    	{ 
														   	  	    		DisplayGBP($response['total_wage']);
														   	  	    	}else  
														   	  	    	{ 
														   	  	    		echo ''; 
														   	  	    	}	
														   	  	    }
														   	  	    ?>"/>
																</td>
																<td style="text-align: center;border:0;">
																	<input type="text"   style="text-align:center"   name="site_paid_to_worker[]"  id="site_paid_to_worker[]" value="<?php if ($response!=false)
														   	  	    {	
														   	  	    	if (isset($response['paid_to_worker'])) 
														   	  	    	{ 
														   	  	    		DisplayGBP($response['paid_to_worker']);
														   	  	    	}else  
														   	  	    	{ 
														   	  	    		echo ''; 
														   	  	    	}	
														   	  	    }
														   	  	    ?>"/>
																</td>
																<td style="text-align: center;border:0;">
																	<input type="text"   style="text-align:center"   name="site_outstanding[]"  id="site_outstanding[]" value="<?php if ($response!=false)
														   	  	    {	
														   	  	    	if (isset($response['outstanding'])) 
														   	  	    	{ 
														   	  	    		DisplayGBP($response['outstanding']);
														   	  	    	}else  
														   	  	    	{ 
														   	  	    		echo ''; 
														   	  	    	}	
														   	  	    }
														   	  	    ?>"/>
																</td>
																<td style="text-align: center;border:0;">
																   <input class="datetimePicker" id="site_due_date[]" name="site_due_date[]" type="text" value="<?php if ($response!=false)
														   	  	    {	
														   	  	    	if (isset($response['due_date'])) 
														   	  	    	{ 
														   	  	    		echo $response['due_date'];
														   	  	    	}else  
														   	  	    	{ 
														   	  	    		echo ''; 
														   	  	    	}	
														   	  	    }
														   	  	    ?>"/>
																</td>
																<td style="text-align: left;border:0;">
																   <textarea  rows="1"  name="site_notes[]" id="site_notes[]"  ><?php if ($response!=false)
														   	  	    {	
														   	  	    	if (isset($response['notes'])) 
														   	  	    	{ 
														   	  	    		echo $response['notes'];
														   	  	    	}else  
														   	  	    	{ 
														   	  	    		echo ''; 
														   	  	    	}	
														   	  	    }
														   	  	    ?></textarea>
																</td>
															</tr>
														<?php
															}
														?>
															<tr>
																<td colspan="4"></td>
																
																<td style="text-align: center;border:0;">
																	<input type="text"   style="text-align:center;color: black;font-weight:bold;"   name="site_total"  id="site_total[]" value="CIS DUE"/>
																</td>
																<td style="text-align: center;border:0;">
																	<input type="text"   style="text-align:center;color: black;font-weight:bold;"   name="site_total_val"  id="site_total_val[]" value="<?php DisplayGBP($ciu_due);?>"/>
																</td>
																<td colspan="2"></td>
																<td style="text-align: center;border:0;">
																	<input type="text"   style="text-align:center;color: black;font-weight:bold;"   name="site_total"  id="site_total[]" value="TOTAL SUBCON"/>
																</td>
																<td style="text-align: center;border:0;">
																	<input type="text"   style="text-align:center;color: black;font-weight:bold;"   name="site_total_val"  id="site_total_val[]" value="<?php DisplayGBP($total_site);?>"/>
																</td>
																<td></td>
															</tr>
														</tbody>
													</thead>
												</table>
										
											</div>
										</div>
										<div class="row table_placeholder">
					                    	<div class="table-responsive">
												<table width="97%" border="0" cellspacing="0" cellpadding="0" class="create-skus-form margin_10 bold" id="bnk_tbl">
													<thead>
														<tr>														
															<th style="text-align: left;width:23%;border:0;padding-left: 8px" colspan='2'>BILLS / OUTSTANDING SUMS</th>
															
															<th style="text-align: center;width:8%;border:0"></th>
															<th style="text-align: center;width:8%;border:0"></th>
															<th style="text-align: center;width:8%;border:0"></th>
															<th style="text-align: center;width:8%;border:0"></th>
															<th style="text-align: center;width:8%;border:0"></th>
															<th style="text-align: center;width:8%;border:0"></th>
															
															<th style="text-align: center;width:8%;border:0">NOTES</th>
															<th style="text-align: center;width:8%;border:0"></th>
															
															<th style="text-align: center;width:8%;border:0"></th>
														</tr>
														<tbody>
														<?php
															$sql_sel = sprintf("select * from bill_staff_info where staff_id=%d",$_GET['rid']);
															$res_sel = mysqli_query($con, $sql_sel) or die(mysqli_error($con));
															
															$supplier_due = 0;
															$cnt = 0;
															while($cnt<11)
															{
																$cnt++;
																$response = mysqli_fetch_assoc($res_sel);
																if ($response)
																{
																	$supplier_due += getRealValue($response['total_wage']);
																}
																
														?>
															<tr>
																<td style="text-align: center;border:0;width:8%;">
																	<input type="text"   style="text-align:center;font-weight:bold"   name="bill_type[]"  id="bill_type[]"  readonly="true" value=""/>
																</td>
															
																<td style="text-align: center;border:0">
																	<input type="text"   style="text-align:left;font-weight:bold"   name="bill_name[]"  id="bill_name[]" value="<?php if ($response!=false)
														   	  	    {	
														   	  	    	if (isset($response['name'])) 
														   	  	    	{ 
														   	  	    		echo $response['name']; 
														   	  	    	}else  
														   	  	    	{ 
														   	  	    		echo ''; 
														   	  	    	}	
														   	  	    }
														   	  	    ?>"/>
																</td>
																<td style="text-align: center;border:0;">
																	<input type="text"   style="text-align:center"   name="bill_rate[]"  id="bill_rate[]" value="<?php if ($response!=false)
														   	  	    {	
														   	  	    	if (isset($response['rate'])) 
														   	  	    	{ 
														   	  	    		DisplayGBP($response['rate']);
														   	  	    	}else  
														   	  	    	{ 
														   	  	    		echo ''; 
														   	  	    	}	
														   	  	    }
														   	  	    ?>"/>
																</td>
																<td style="text-align: center;border:0;">
																	<input type="text"  style="text-align:center"   name="bill_days[]"  id="bill_days[]" value="<?php if ($response!=false)
														   	  	    {	
														   	  	    	if (isset($response['days'])) 
														   	  	    	{ 
														   	  	    		echo $response['days'];
														   	  	    	}else  
														   	  	    	{ 
														   	  	    		echo ''; 
														   	  	    	}	
														   	  	    }
														   	  	    ?>"/>
																</td>
																<td style="text-align: center;border:0;">
																	<input type="text"   style="text-align:center"   name="bill_rxd[]"  id="bill_rxd[]" value="<?php if ($response!=false)
														   	  	    {	
														   	  	    	if (isset($response['rxd'])) 
														   	  	    	{ 
														   	  	    		DisplayGBP($response['rxd']);
														   	  	    	}else  
														   	  	    	{ 
														   	  	    		echo ''; 
														   	  	    	}	
														   	  	    }
														   	  	    ?>"/>
																</td>
																<td style="text-align: center;border:0;">
																	<input type="text"   style="text-align:center"   name="bill_ded[]"  id="bill_ded[]" value="<?php if ($response!=false)
														   	  	    {	
														   	  	    	if (isset($response['ded'])) 
														   	  	    	{ 
														   	  	    		DisplayGBP($response['ded']);
														   	  	    	}else  
														   	  	    	{ 
														   	  	    		echo ''; 
														   	  	    	}	
														   	  	    }
														   	  	    ?>"/>
																</td>
																<td style="text-align: center;border:0;">
																	<input type="text"   style="text-align:center"   name="bill_total_wage[]"  id="bill_total_wage[]" value="<?php if ($response!=false)
														   	  	    {	
														   	  	    	if (isset($response['total_wage'])) 
														   	  	    	{ 
														   	  	    		DisplayGBP($response['total_wage']);
														   	  	    	}else  
														   	  	    	{ 
														   	  	    		echo ''; 
														   	  	    	}	
														   	  	    }
														   	  	    ?>"/>
																</td>
																<td style="text-align: center;border:0;">
																	<input type="text"   style="text-align:center"   name="bill_paid_to_worker[]"  id="bill_paid_to_worker[]" value="<?php if ($response!=false)
														   	  	    {	
														   	  	    	if (isset($response['paid_to_worker'])) 
														   	  	    	{ 
														   	  	    		DisplayGBP($response['paid_to_worker']);
														   	  	    	}else  
														   	  	    	{ 
														   	  	    		echo ''; 
														   	  	    	}	
														   	  	    }
														   	  	    ?>"/>
																</td>
																
																<td style="text-align: left;border:0;">
																   <textarea  rows="1"  name="bill_notes[]" id="bill_notes[]"  ><?php if ($response!=false)
														   	  	    {	
														   	  	    	if (isset($response['notes'])) 
														   	  	    	{ 
														   	  	    		echo $response['notes'];
														   	  	    	}else  
														   	  	    	{ 
														   	  	    		echo ''; 
														   	  	    	}	
														   	  	    }
														   	  	    ?></textarea>
																</td>
																<td style="text-align: left;border:0;" colspan="2"></td>
															</tr>
														<?php
															}
														?>
															<tr>
																<td colspan="8"></td>
																<td style="text-align: center;border:0;">
																	<input type="text"   style="text-align:center;color: black;font-weight:bold;"   name="bill_total"  id="bill_total[]" value="Supplier's DUE"/>
																</td>
																<td style="text-align: center;border:0;">
																	<input type="text"   style="text-align:center;color: black;font-weight:bold;"   name="bill_total_val"  id="bill_total_val[]" value="<?php DisplayGBP($supplier_due);?>"/>
																</td>
																<td></td>
															</tr>
															
														</tbody>
													</thead>
												</table>
										
											</div>
										</div>
										<div class="row table_placeholder">
					                    	<div class="table-responsive">
												<table width="97%" border="0" cellspacing="0" cellpadding="0" class="create-skus-form margin_10 bold" id="bnk_tbl">
													<thead>
														<tr>						
															<th style="text-align: center;width:9%;border:0"></th>								
															<th style="text-align: center;width:30%;border:0;" colspan="2"><u>MONEY REQUIRED AS FOLLOWS</u></th>
															
															<th style="text-align: center;border:0" colspan="8"></th>
														</tr>
														<tbody>
														<?php
															$sql_sel = sprintf("select * from money_staff_info where staff_id=%d",$_GET['rid']);
															$res_sel = mysqli_query($con, $sql_sel) or die(mysqli_error($con));
															
															$total_wage_requried = 0;
															$response = mysqli_fetch_assoc($res_sel);
															{
																
														?>
															<tr>
																<td style="text-align: center;border:0;width:9%;">&nbsp;</td>
																<td style="text-align: center;border:0;width:20%;">
																	<input type="text"   style="text-align:left;font-weight:bold"   name="money_salary_name"  id="money_salary_name" value="OFFICE STAFF AND DIRECTORS SALARY"/>
																</td>
																<td style="text-align: center;border:0;width:10%;">
																	<input type="text"   style="text-align:center;font-weight:bold"   name="money_salary"  id="money_salary" value="<?php 
		   	  	    		DisplayGBP($total_office);
		   	  	    		$total_wage_requried += getRealValue($total_office);
														   	  	    ?>"/>
																</td>
																<td style="text-align: center;border:0;" colspan="8">&nbsp;</td>
															</tr>
															<tr>
																<td style="text-align: center;border:0;width:9%;">&nbsp;</td>
																<td style="text-align: center;border:0;width:20%;">
																	<input type="text"   style="text-align:left;font-weight:bold"   name="money_fitter_balance_name"  id="money_fitter_balance_name" value="FITTERS BALANCE"/>
																</td>
																<td style="text-align: center;border:0;width:10%;">
																	<input type="text"   style="text-align:center;font-weight:bold"   name="money_fitter_balance"  id="money_fitter_balance" value="<?php 
																	DisplayGBP($total_site);
		   	  	    												$total_wage_requried += getRealValue($total_site);
														   	  	    ?>"/>
																</td>
																<td style="text-align: center;border:0;" colspan="8">&nbsp;</td>
															</tr>
															<tr>
																<td style="text-align: center;border:0;width:9%;">&nbsp;</td>
																<td style="text-align: center;border:0;width:20%;">
																	<input type="text"   style="text-align:left;font-weight:bold"   name="money_cis_balance_name"  id="money_cis_balance_name" value="CIS BALANCE"/>
																</td>
																<td style="text-align: center;border:0;width:10%;">
																	<input type="text"   style="text-align:center;font-weight:bold"   name="money_cis_balance"  id="money_cis_balance" value="<?php 
																	DisplayGBP($ciu_due);
		   	  	    												$total_wage_requried += getRealValue($ciu_due);
														   	  	    ?>"/>
																</td>
																<td style="text-align: center;border:0;" colspan="8">&nbsp;</td>
															</tr>
															<tr>
																<td style="text-align: center;border:0;width:9%;">&nbsp;</td>
																<td style="text-align: center;border:0;width:20%;">
																	<input type="text" style="text-align:left;font-weight:bold;text-decoration: underline;"   name="money_total_wage_required_name"  id="money_total_wage_required_name" value="TOTAL WAGES REQUIRED FOR PAY DAY"/>
																</td>
																<td style="text-align: center;border:0;width:10%;">
																	<input type="text"   style="text-align:center;font-weight:bold"   name="money_total_wage_required"  id="money_total_wage_required" value="<?php DisplayGBP($total_wage_requried);?>"/>
																</td>
																<td style="text-align: center;border:0;" colspan="8">&nbsp;</td>
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