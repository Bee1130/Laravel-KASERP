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

$submit = null;
if(isset($_POST['Submit'])) {
	$submit = $_POST['Submit'];
}
if ($submit == "Save") 
{    
	$_POST['mobile'] = my_phone_format($_POST['mobile']);
	$_POST['contact_tel'] = my_phone_format($_POST['contact_tel']);

	$estdate = date('Y-m-d',strtotime($_POST['est_date']));
   
    $sql_ins = sprintf("insert into estimates_info ( name,est_date,addr1,email,est_no,contact,post_code,mobile,contact_tel,job_description,labour_val,strip1_val,strip2_val,strip3_val,strip4_val,bonding1_val,bonding2_val,plumbing_first1_val,plumbing_firts2_val,plumbing_first3_val,plumbing_first4_val,plumbing_first5_val,plumbing_snd1_val,plumbing_snd2_val,plumbing_snd3_val,plumbing_snd4_val,plumbing_snd5_val,plumbing_snd6_val,plumbing_snd7_val,plumbing_snd8_val,painting_val,tiling1_val,tiling2_val,tiling3_val,tiling4_val,carpentry1_val,carpentry2_val,painting1_val,painting2_val,other1_val,other2_val,other3_val,other4_val,other5_val,skip_val,material1_val,material2_val,material3_val,material4_val,not_include_val,bathroom1_val,bathroom2_val,bathroom3_val,bathroom4_val,bathroom5_val,bathroom6_val,bathroom7_val,bathroom8_val,tiling_snd_val,painting_snd_val,term_condition,estimate_type) values ('%s','%s','%s','%s','%d','%s','%s','%s','%s','%s','%f','%f','%f','%f','%f','%f','%f','%f','%f','%f','%f','%f','%f','%f','%f','%f','%f','%f','%f','%f','%f','%f','%f','%f','%f','%f','%f','%f','%f','%f','%f','%f','%f','%f','%f','%f','%f','%f','%f','%f','%f','%f','%f','%f','%f','%f','%f','%f','%f','%f','%s','%s')",mysqli_real_escape_string($con, $_POST['name']),$estdate,mysqli_real_escape_string($con, $_POST['addr1']),mysqli_real_escape_string($con, $_POST['email']),$_POST['est_no'],mysqli_real_escape_string($con, $_POST['contact']),$_POST['post_code'],$_POST['mobile'],$_POST['contact_tel'],mysqli_real_escape_string($con, $_POST['job_description']),$_POST['labour_val'],$_POST['strip1_val'],$_POST['strip2_val'],$_POST['strip3_val'],$_POST['strip4_val'],$_POST['bonding1_val'],$_POST['bonding2_val'],$_POST['plumbing_first1_val'],$_POST['plumbing_firts2_val'],$_POST['plumbing_first3_val'],$_POST['plumbing_first4_val'],$_POST['plumbing_first5_val'],$_POST['plumbing_snd1_val'],$_POST['plumbing_snd2_val'],$_POST['plumbing_snd3_val'],$_POST['plumbing_snd4_val'],$_POST['plumbing_snd5_val'],$_POST['plumbing_snd6_val'],$_POST['plumbing_snd7_val'],$_POST['plumbing_snd8_val'],$_POST['painting_val'],$_POST['tiling1_val'],$_POST['tiling2_val'],$_POST['tiling3_val'],$_POST['tiling4_val'],$_POST['carpentry1_val'],$_POST['carpentry2_val'],$_POST['painting1_val'],$_POST['painting2_val'],$_POST['other1_val'],$_POST['other2_val'],$_POST['other3_val'],$_POST['other4_val'],$_POST['other5_val'],$_POST['skip_val'],$_POST['material1_val'],$_POST['material2_val'],$_POST['material3_val'],$_POST['material4_val'],$_POST['not_include_val'],$_POST['bathroom1_val'],$_POST['bathroom2_val'],$_POST['bathroom3_val'],$_POST['bathroom4_val'],$_POST['bathroom5_val'],$_POST['bathroom6_val'],$_POST['bathroom7_val'],$_POST['bathroom8_val'],$_POST['tiling_snd_val'],$_POST['painting_snd_val'],mysqli_real_escape_string($con, $_POST['term_condition']),$_POST['estimate_type']);   
    mysqli_query($con, $sql_ins) or die(mysqli_error($con));
    
    
    $rid= mysqli_insert_id($con);
   
    $Destination = 'userprofile/userfiles/logos';
    if(!isset($_FILES['ImageFile']) || !is_uploaded_file($_FILES['ImageFile']['tmp_name'])){
        $NewImageName= 'default.jpg';
        move_uploaded_file($_FILES['ImageFile']['tmp_name'], "$Destination/$NewImageName");
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
    }
    
    $sql_upd_logo="UPDATE estimates_info SET logo='$NewImageName' WHERE auto_id = ".$rid;
    $sql_upd_res = mysqli_query($con, $sql_upd_logo) or die(mysqli_error($con)); 
    
     // insert action logs into system_log_info
	$sql_log = sprintf("insert into system_log_info (action,agent,query,log_time) values ('%s','%s','%s',sysdate())","Update Staff : estimate.php ",$_SESSION['user_login'],mysqli_real_escape_string($con, $sql_upd));
	mysqli_query($con, $sql_log) or die(mysqli_error($con));	
	
	// Save log
	saveLog($rid,'Estimate','Estimate','added');
	
    header("Location: estimate.php?rid=" . $rid);
    exit();
      
}


if ($submit == "Cancel") {
	
    header("Location: estimates.php");
    exit();
	
}	

function DisplayGBP($val)
{
	$val = trim($val);
	if ($val>0)
	{
		$val = 'GBP'.number_format($val,2);
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
			    margin-left: 5px;
			   
			}
			
		
			.create-skus-form input {
			    width: 100%;
			    float: left;
			    font-weight: normal;
			    color: black;
			    font-size: 11px;
			    
			    padding: 2px;
			    margin-left: 5px;
			    height: 26px;
			}
			
			.create-skus-form select {
			    width: 100%;
			    float: left;
			    font-weight: normal;
			    color: black;
			    font-size: 11px;
			    
			    padding: 2px;
			    margin-left: 5px;
			    height: 26px;
			}
			
			.create-skus-form label {
			    
			    padding: 2px;
			    
			}

			.table>tbody>tr>td{
				padding-top: 2px;
				padding-bottom: 2px;
			}

		</style>
	   
	   <script type="text/javascript">
	      
            $(document).ready(function () { 
			    $("#panel-fullscreen-Main").click(function (e) {
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
        <script type="text/javascript" src="popcalendar.js"></script>
		<?php
			$rid = isset($_GET['rid'])?$_GET['rid']: '';
			// var_dump($rid);die;
		?>
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
        					<li><a href="estimate.php?rid=<?php echo $next_id;?>">Previous</a></li>	
        					<?php
        					}
                        ?>
                      
                        <?php
                        	if ($prev_id != -1)
                        	{
                        	?>
        					<li><a href="estimate.php?rid=<?php echo $prev_id;?>">Next</a></li>	
        					<?php
        					}
                        ?>  
               			</ol>
               			
        				
        				
        			
                    	<div class="row" style="width: 90%;margin:auto">       
                    		<div class="col-md-12 col-lg-12"> 
                    			<form action="estimate_new.php" method="post" enctype="multipart/form-data" id="CalcWageForm">
                    				<div class="panel panel-inverse" data-sortable-id="ui-general-7">
        								<div class="panel-heading">
        				                	<h4 class="panel-title">
        				                		<a>Estimate</a>
        								        <div class="panel-heading-btn">
        					                        <a href="#" id="panel-fullscreen-Meeting" role="button"  class="btn btn-xs btn-icon btn-circle btn-default" title="Toggle fullscreen"><i class="fa fa-expand"></i></a>
        					                        <a href="#collapseMeeting" data-toggle="collapse" data-target="#collapseMeeting" class="btn btn-xs btn-icon btn-circle btn-warning collapsed" ><i class="fa fa-minus"></i></a>
        					                    </div> 
        								        <div class="pull-right">
        											<button class="btn btn-xs btn-success" style="font-size: 14px;font-weight: normal;padding-top: 0px;  padding-bottom: 0px;" type="submit" name="Submit" id="btn_save" value="Save">Save</button>  
        				                   		</div>
        								    </h4>
        				                </div>
        				                <div id="collapseMeeting"  class="panel-collapse collapse in" style="background-color: f3f5ef">
        				                	<div class="panel-body animated fadeIn" id="Meeting">		
        				                		<div class="row table_placeholder">
        					                    	<div class="table-responsive">
        												<table width="97%" border="0" cellspacing="0" cellpadding="0" class="create-skus-form table" id="bnk_tbl" style="font-size:12px">
        													<thead>
        														<tr>														
        															<th style="text-align: center;width:55%;border:0;vertical-align: middle;"><h1>Estimate</h1></th>
        															<th style="text-align: center;width:20%;border:0"> 
        																<img src="userprofile/userfiles/logos/<?php 
        							                            					if (isset($recb['logo'])) 
        							                            						echo $recb['logo'];
        							                            					else 
        							                            						echo 'logo.png';?>" alt="" class="img-thumbnail" >
        							                            		<input name="ImageFile" type="file" id="uploadFile"/></th>
        															<th style="border:0" colspan="2"></th>
        														</tr>
        														<tbody>
        															<?php
        																$total_labour_material_val =0;
        															?>
        															<tr>
        																<td style="border:0;width: 55%;font-weight: bold;padding-left:2px;padding-right:2px"><u>Customer Details</u></td>
        																<td  style="border:0;"></td>
        																<td style="border:0;font-weight: bold;padding-left:2px;padding-right:2px"><u>Details</u></td>
        																<td  style="border:0;"width="15%"></td>
        															</tr>
        															<tr><td colspan="4">&nbsp;</td></tr>
        															<tr>
        																<td style="border:0;font-weight: bold;padding-left:2px;padding-right:2px">Name</td>
        																<td style="border:0">
        																	 <input  id="name" maxlength="100" name="name" type="text"  
        				                                    					value="<?php if (isset($_POST['name'])) {
        			                                                                    echo $_POST['name'];
        			                                                                } else if (isset($recb['name'])) {
        			                                                                    echo $recb['name'];
        			                                                                } ?>">
        																</td>
        																<td style="border:0;font-weight: bold;padding-left:2px;padding-right:2px">Date</td>
        																<td style="border:0">
        																	 <input class="datetimePicker" id="est_date" name="est_date" type="text" value="<?php if (isset($_POST['est_date'])) {
        			                                                                    echo $_POST['est_date'];
        			                                                                } else if (isset($recb['est_date'])) {
        			                                                                	$date = date('m/d/Y',strtotime($recb['est_date']));
        			                                                                    echo $date;
        			                                                                } ?>">
        			                                                    </td>
        															</tr>
        															
        															<tr>
        																<td style="border:0;font-weight: bold;padding-left:2px;padding-right:2px">Address</td>
        																<td style="border:0">
        																	 <input  id="addr1" maxlength="100" name="addr1" type="text"  
        				                                    					value="<?php if (isset($_POST['addr1'])) {
        			                                                                    echo $_POST['addr1'];
        			                                                                } else if (isset($recb['addr1'])) {
        			                                                                    echo $recb['addr1'];
        			                                                                } ?>">
        																</td>
        																<td style="border:0;font-weight: bold;padding-left:2px;padding-right:2px">Estimate No.</td>
        																<td style="border:0">
        																	 <input  id="est_no" maxlength="100" name="est_no" type="text"  
        				                                    					value="<?php if (isset($_POST['est_no'])) {
        			                                                                    echo $_POST['est_no'];
        			                                                                } else if (isset($recb['est_no'])) {
        			                                                                    echo $recb['est_no'];
        			                                                                } ?>">
        			                                                    </td>
        															</tr>
        															
        															<tr>
        																<td style="border:0;font-weight: bold;padding-left:2px;padding-right:2px">Email</td>
        																<td style="border:0">
        																	 <input  id="email" maxlength="100" name="email" type="text"  
        				                                    					value="<?php if (isset($_POST['email'])) {
        			                                                                    echo $_POST['email'];
        			                                                                } else if (isset($recb['email'])) {
        			                                                                    echo $recb['email'];
        			                                                                } ?>">
        																</td>
        																<td style="border:0;font-weight: bold;padding-left:2px;padding-right:2px">Estimate Type</td>
        																<td style="border:0">
        																	<select  id="estimate_type" name="estimate_type" style="width: 100%">
        						                                            	<option value=""></option>
        				                                                    	<option value="Live" <?php 
        																				if ((isset($_POST['estimate_type']))?$_POST['estimate_type'] : '' == "Live") {
        																						echo 'selected';
        																				} else if(isset($recb['estimate_type'])){
																						if ($recb['estimate_type'] == "Live") {
        																					echo 'selected';
        																				}} ?>>Live</option>
        						                                                <option value="Archived" <?php 
        																				if ((isset($_POST['estimate_type'] ))?$_POST['estimate_type'] : ''== "Archived") {
        																						echo 'selected';
        																				} else if(isset($recb['estimate_type'])){
																						if ($recb['estimate_type'] == "Archived") {
        																					echo 'selected';
        																				}} ?>>Archived</option>
        						                                                
        																	</select>
        			                                                    </td>
        															</tr>
        															
        															<tr>
        																<td style="border:0;font-weight: bold;padding-left:2px;padding-right:2px">Town</td>
        																<td style="border:0">
        																	 <input  id="town" maxlength="100" name="town" type="text"  
        				                                    					value="<?php if (isset($_POST['town'])) {
        			                                                                    echo $_POST['town'];
        			                                                                } else if (isset($recb['town'])) {
        			                                                                    echo $recb['town'];
        			                                                                } ?>">
        																</td>
        																<td style="border:0;font-weight: bold;padding-left:2px;padding-right:2px">Contact</td>
        																<td style="border:0">
        																	 <input  id="contact" maxlength="100" name="contact" type="text"  
        				                                    					value="<?php if (isset($_POST['contact'])) {
        			                                                                    echo $_POST['contact'];
        			                                                                } else if (isset($recb['contact'])) {
        			                                                                    echo $recb['contact'];
        			                                                                } ?>">
        																</td>
        															</tr>
        															
        															<tr>
        																<td style="border:0;font-weight: bold;padding-left:2px;padding-right:2px">Post Code</td>
        																<td style="border:0">
        																	 <input  id="post_code" maxlength="100" name="post_code" type="text"  
        				                                    					value="<?php if (isset($_POST['post_code'])) {
        			                                                                    echo $_POST['post_code'];
        			                                                                } else if (isset($recb['post_code'])) {
        			                                                                    echo $recb['post_code'];
        			                                                                } ?>">
        																</td>
        																<td style="border:0;font-weight: bold;padding-left:2px;padding-right:2px">Mobile</td>
        																<td style="border:0">
        																	 <input  id="mobile" maxlength="100" name="mobile" type="text"  
        				                                    					value="<?php if (isset($_POST['mobile'])) {
        			                                                                    echo $_POST['mobile'];
        			                                                                } else if (isset($recb['mobile'])) {
        			                                                                    echo $recb['mobile'];
        			                                                                } ?>">
        																</td>
        															</tr>
        															
        															<tr>
        																<td style="border:0;font-weight: bold;padding-left:2px;padding-right:2px">Contact Tel</td>
        																<td style="border:0">
        																	 <input  id="contact_tel" maxlength="100" name="contact_tel" type="text"  
        				                                    					value="<?php if (isset($_POST['contact_tel'])) {
        			                                                                    echo $_POST['contact_tel'];
        			                                                                } else if (isset($recb['contact_tel'])) {
        			                                                                    echo $recb['contact_tel'];
        			                                                                } ?>">
        																</td>
        																<td colspan="2" style="border: 0"></td>
        															</tr>
        															
        															<tr><td colspan="4">&nbsp;</td></tr>
        															<tr>
        																<td style="border:0;width: 55%;font-weight: bold;padding-left:2px;padding-right:2px"><u>Job Description</u></td>
        																<td  style="border:0;" colspan="3"></td>
        															</tr>
        															<tr>
        																<td style="border:0;width: 55%;font-weight: bold;padding-left:2px;padding-right:2px">
        																	<input  id="job_description" maxlength="255" name="job_description" type="text"  
        				                                    					value="<?php if (isset($_POST['job_description'])) {
        			                                                                    echo $_POST['job_description'];
        			                                                                } else if (isset($recb['job_description'])) {
        			                                                                    echo $recb['job_description'];
        			                                                                } ?>"></td>
        																<td  style="border:0;" colspan="3"></td>
        															</tr>
        															
        															<tr><td colspan="4">&nbsp;</td></tr>
        															<tr>
        																<td style="border:0;width: 55%;font-weight: bold;padding-left:2px;padding-right:2px"><u>Labour and Materials</u></td>
        																<td  style="border:0;" colspan="3"></td>
        															</tr>
        															<tr>
        																<td style="border:0;width: 55%;font-weight: bold;padding-left:2px;padding-right:2px">Bathroom Dimensions: 1.2m x 2.9m x 2.3m = 19sqm approx</td>
        																<td style="border: 0" colspan="2"></td>
        																<td  style="border:0;"><input  id="labour_val" name="labour_val" type="text"  
        				                                    					value="<?php if (isset($_POST['labour_val'])) {
        				                                    							$total_labour_material_val += getRealValue($_POST['labour_val']);
        			                                                                    DisplayGBP($_POST['labour_val']);
        			                                                                } else if (isset($recb['labour_val'])) {
        			                                                                	$total_labour_material_val += getRealValue($recb['labour_val']);
        			                                                                    DisplayGBP($recb['labour_val']);
        			                                                                } ?>"></td>
        															</tr>
        															
        															<tr><td colspan="4">&nbsp;</td></tr>
        															<tr>
        																<td style="border:0;width: 55%;font-weight: bold;padding-left:2px;padding-right:2px"><u>Strip Out and removal of the following:</u></td>
        																<td  style="border:0;" colspan="3"></td>
        															</tr>
        															<tr>
        																<td style="border:0;width: 55%;font-weight: bold;padding-left:2px;padding-right:2px">Drain down system and cap pipes / install 15mm isolation valves on H&C</td>
        																<td style="border: 0" colspan="2"></td>
        																<td  style="border:0;"><input  id="strip1_val" name="strip1_val" type="text"  
        				                                    					value="<?php if (isset($_POST['strip1_val'])) {
        				                                    							$total_labour_material_val += getRealValue($_POST['strip1_val']);
        			                                                                    DisplayGBP($_POST['strip1_val']);
        			                                                                } else if (isset($recb['strip1_val'])) {
        			                                                                	$total_labour_material_val += getRealValue($recb['strip1_val']);
        			                                                                    DisplayGBP($recb['strip1_val']);
        			                                                                } ?>"></td>
        															</tr>
        															<tr>
        																<td style="border:0;width: 55%;font-weight: bold;padding-left:2px;padding-right:2px">Existing bathroom furniture including toilet, taps, basin, shower valve, tray, screen and towel rail</td>
        																<td style="border: 0" colspan="2"></td>
        																<td  style="border:0;"><input  id="strip2_val" name="strip2_val" type="text"  
        				                                    					value="<?php if (isset($_POST['strip2_val'])) {
        				                                    							$total_labour_material_val += getRealValue($_POST['strip2_val']);
        			                                                                    DisplayGBP($_POST['strip2_val']);
        			                                                                } else if (isset($recb['strip2_val'])) {
        			                                                                	$total_labour_material_val += getRealValue($recb['strip2_val']);
        			                                                                    DisplayGBP($recb['strip2_val']);
        			                                                                } ?>"></td>
        															</tr>
        															<tr>
        																<td style="border:0;width: 55%;font-weight: bold;padding-left:2px;padding-right:2px">Existing wall tiles </td>
        																<td style="border: 0" colspan="2"></td>
        																<td  style="border:0;"><input  id="strip3_val" name="strip3_val" type="text"  
        				                                    					value="<?php if (isset($_POST['strip3_val'])) {
        				                                    							$total_labour_material_val += getRealValue($_POST['strip3_val']);
        			                                                                    DisplayGBP($_POST['strip3_val']);
        			                                                                } else if (isset($recb['strip3_val'])) {
        			                                                                	$total_labour_material_val += getRealValue($recb['strip3_val']);
        			                                                                    DisplayGBP($recb['strip3_val']);
        			                                                                } ?>"></td>
        															</tr>
        															<tr>
        																<td style="border:0;width: 55%;font-weight: bold;padding-left:2px;padding-right:2px">Existing floor tiles </td>
        																<td style="border: 0" colspan="2"></td>
        																<td  style="border:0;"><input  id="strip4_val" name="strip4_val" type="text"  
        				                                    					value="<?php if (isset($_POST['strip4_val'])) {
        				                                    							$total_labour_material_val += getRealValue($_POST['strip4_val']);
        			                                                                    DisplayGBP($_POST['strip4_val']);
        			                                                                } else if (isset($recb['strip4_val'])) {
        			                                                                	$total_labour_material_val += getRealValue($recb['strip4_val']);
        			                                                                    DisplayGBP($recb['strip4_val']);
        			                                                                } ?>"></td>
        															</tr>
        															
        															<tr><td colspan="4">&nbsp;</td></tr>
        															<tr>
        																<td style="border:0;width: 55%;font-weight: bold;padding-left:2px;padding-right:2px"><u>Bonding and plastering</u></td>
        																<td  style="border:0;" colspan="3"></td>
        															</tr>
        															<tr>
        																<td style="border:0;width: 55%;font-weight: bold;padding-left:2px;padding-right:2px">Bond and plaster walls (19sqm approx)</td>
        																<td style="border: 0" colspan="2"></td>
        																<td  style="border:0;"><input  id="bonding1_val" name="bonding1_val" type="text"  
        				                                    					value="<?php if (isset($_POST['bonding1_val'])) {
        				                                    							$total_labour_material_val += getRealValue($_POST['bonding1_val']);
        			                                                                    DisplayGBP($_POST['bonding1_val']);
        			                                                                } else if (isset($recb['bonding1_val'])) {
        			                                                                	$total_labour_material_val += getRealValue($recb['bonding1_val']);
        			                                                                    DisplayGBP($recb['bonding1_val']);
        			                                                                } ?>"></td>
        															</tr>
        															<tr>
        																<td style="border:0;width: 55%;font-weight: bold;padding-left:2px;padding-right:2px">Plaster Ceiling (3.5sqm approx)</td>
        																<td style="border: 0" colspan="2"></td>
        																<td  style="border:0;"><input  id="bonding2_val" name="bonding2_val" type="text"  
        				                                    					value="<?php if (isset($_POST['bonding2_val'])) {
        				                                    							$total_labour_material_val += getRealValue($_POST['bonding2_val']);
        			                                                                    DisplayGBP($_POST['bonding2_val']);
        			                                                                } else if (isset($recb['bonding2_val'])) {
        			                                                                	$total_labour_material_val += getRealValue($recb['bonding2_val']);
        			                                                                    DisplayGBP($recb['bonding2_val']);
        			                                                                } ?>"></td>
        															</tr>
        															<tr><td colspan="4">&nbsp;</td></tr>
        															<tr>
        																<td style="border:0;width: 55%;font-weight: bold;padding-left:2px;padding-right:2px"><u>Plumbing 1st & 2nd Fix</u></td>
        																<td  style="border:0;" colspan="3"></td>
        															</tr>
        															<tr>
        																<td style="border:0;width: 55%;font-weight: bold;padding-left:2px;padding-right:2px">1st Fix - </td>
        																<td  style="border:0;" colspan="3"></td>
        															</tr>
        															<tr>
        																<td style="border:0;width: 55%;padding-left:2px;padding-right:2px">Shower Valve - amend pipework to suit new valve</td>
        																<td style="border: 0" colspan="2"></td>
        																<td  style="border:0;"><input  id="plumbing_first1_val" name="plumbing_first1_val" type="text"  
        				                                    					value="<?php if (isset($_POST['plumbing_first1_val'])) {
        				                                    							$total_labour_material_val += getRealValue($_POST['plumbing_first1_val']);
        			                                                                    DisplayGBP($_POST['plumbing_first1_val']);
        			                                                                } else if (isset($recb['plumbing_first1_val'])) {
        			                                                                	$total_labour_material_val += getRealValue($recb['plumbing_first1_val']);
        			                                                                    DisplayGBP($recb['plumbing_first1_val']);
        			                                                                } ?>"></td>
        															</tr>
        															<tr>
        																<td style="border:0;width: 55%;padding-left:2px;padding-right:2px">Shower Tray - New waste</td>
        																<td style="border: 0" colspan="2"></td>
        																<td  style="border:0;"><input  id="plumbing_firts2_val" name="plumbing_firts2_val" type="text"  
        				                                    					value="<?php if (isset($_POST['plumbing_firts2_val'])) {
        				                                    							$total_labour_material_val += getRealValue($_POST['plumbing_firts2_val']);
        			                                                                    DisplayGBP($_POST['plumbing_firts2_val']);
        			                                                                } else if (isset($recb['plumbing_firts2_val'])) {
        			                                                                	$total_labour_material_val += getRealValue($recb['plumbing_firts2_val']);
        			                                                                    DisplayGBP($recb['plumbing_firts2_val']);
        			                                                                } ?>"></td>
        															</tr>
        															<tr>
        																<td style="border:0;width: 55%;padding-left:2px;padding-right:2px">Taps - New H&C Feeds in 15mm with isolator valves</td>
        																<td style="border: 0" colspan="2"></td>
        																<td  style="border:0;"><input  id="plumbing_first3_val" name="plumbing_first3_val" type="text"  
        				                                    					value="<?php if (isset($_POST['plumbing_first3_val'])) {
        				                                    							$total_labour_material_val += getRealValue($_POST['plumbing_first3_val']);
        			                                                                    DisplayGBP($_POST['plumbing_first3_val']);
        			                                                                } else if (isset($recb['plumbing_first3_val'])) {
        			                                                                	$total_labour_material_val += getRealValue($recb['plumbing_first3_val']);
        			                                                                    DisplayGBP($recb['plumbing_first3_val']);
        			                                                                } ?>"></td>
        															</tr>
        															<tr>
        																<td style="border:0;width: 55%;padding-left:2px;padding-right:2px">Toilet - New cold feed in 15mm with isolator valve / new cistern</td>
        																<td style="border: 0" colspan="2"></td>
        																<td  style="border:0;"><input  id="plumbing_first4_val" name="plumbing_first4_val" type="text"  
        				                                    					value="<?php if (isset($_POST['plumbing_first4_val'])) {
        				                                    							$total_labour_material_val += getRealValue($_POST['plumbing_first4_val']);
        			                                                                    DisplayGBP($_POST['plumbing_first4_val']);
        			                                                                } else if (isset($recb['plumbing_first4_val'])) {
        			                                                                	$total_labour_material_val += getRealValue($recb['plumbing_first4_val']);
        			                                                                    DisplayGBP($recb['plumbing_first4_val']);
        			                                                                } ?>"></td>
        															</tr>
        															<tr>
        																<td style="border:0;width: 55%;padding-left:2px;padding-right:2px">Toilet - New cold feed in 15mm with isolator valve / new cistern</td>
        																<td style="border: 0" colspan="2"></td>
        																<td  style="border:0;"><input  id="plumbing_first5_val" name="plumbing_first5_val" type="text"  
        				                                    					value="<?php if (isset($_POST['plumbing_first5_val'])) {
        				                                    							$total_labour_material_val += getRealValue($_POST['plumbing_first5_val']);
        			                                                                    DisplayGBP($_POST['plumbing_first5_val']);
        			                                                                } else if (isset($recb['plumbing_first5_val'])) {
        			                                                                	$total_labour_material_val += getRealValue($recb['plumbing_first5_val']);
        			                                                                    DisplayGBP($recb['plumbing_first5_val']);
        			                                                                } ?>"></td>
        															</tr>
        															
        															<tr><td colspan="4">&nbsp;</td></tr>
        															
        															<tr>
        																<td style="border:0;width: 55%;padding-left:2px;padding-right:2px"><b>2nd Fix -</b></td>
        																<td  style="border:0;" colspan="3"></td>
        															</tr>
        															<tr>
        																<td style="border:0;width: 55%;padding-left:2px;padding-right:2px">Thermostatic Shower Valve, shower head (from ceiling)</td>
        																<td style="border: 0" colspan="2"></td>
        																<td  style="border:0;"><input  id="plumbing_snd1_val" name="plumbing_snd1_val" type="text"  
        				                                    					value="<?php if (isset($_POST['plumbing_snd1_val'])) {
        				                                    							$total_labour_material_val += getRealValue($_POST['plumbing_snd1_val']);
        			                                                                    DisplayGBP($_POST['plumbing_snd1_val']);
        			                                                                } else if (isset($recb['plumbing_snd1_val'])) {
        			                                                                	$total_labour_material_val += getRealValue($recb['plumbing_snd1_val']);
        			                                                                    DisplayGBP($recb['plumbing_snd1_val']);
        			                                                                } ?>"></td>
        															</tr>
        															<tr>
        																<td style="border:0;width: 55%;padding-left:2px;padding-right:2px">Ceramic Tray</td>
        																<td style="border: 0" colspan="2"></td>
        																<td  style="border:0;"><input  id="plumbing_snd2_val" name="plumbing_snd2_val" type="text"  
        				                                    					value="<?php if (isset($_POST['plumbing_snd2_val'])) {
        				                                    							$total_labour_material_val += getRealValue($_POST['plumbing_snd2_val']);
        			                                                                    DisplayGBP($_POST['plumbing_snd2_val']);
        			                                                                } else if (isset($recb['plumbing_snd2_val'])) {
        			                                                                	$total_labour_material_val += getRealValue($recb['plumbing_snd2_val']);
        			                                                                    DisplayGBP($recb['plumbing_snd2_val']);
        			                                                                } ?>"></td>
        															</tr>
        															<tr>
        																<td style="border:0;width: 55%;padding-left:2px;padding-right:2px">Shower Screen</td>
        																<td style="border: 0" colspan="2"></td>
        																<td  style="border:0;"><input  id="plumbing_snd3_val" name="plumbing_snd3_val" type="text"  
        				                                    					value="<?php if (isset($_POST['plumbing_snd3_val'])) {
        				                                    							$total_labour_material_val += getRealValue($_POST['plumbing_snd3_val']);
        			                                                                    DisplayGBP($_POST['plumbing_snd3_val']);
        			                                                                } else if (isset($recb['plumbing_snd3_val'])) {
        			                                                                	$total_labour_material_val += getRealValue($recb['plumbing_snd3_val']);
        			                                                                    DisplayGBP($recb['plumbing_snd3_val']);
        			                                                                } ?>"></td>
        															</tr>
        															<tr>
        																<td style="border:0;width: 55%;padding-left:2px;padding-right:2px">Basin and Taps</td>
        																<td style="border: 0" colspan="2"></td>
        																<td  style="border:0;"><input  id="plumbing_snd4_val" name="plumbing_snd4_val" type="text"  
        				                                    					value="<?php if (isset($_POST['plumbing_snd4_val'])) {
        				                                    							$total_labour_material_val += getRealValue($_POST['plumbing_snd4_val']);
        			                                                                    DisplayGBP($_POST['plumbing_snd4_val']);
        			                                                                } else if (isset($recb['plumbing_snd4_val'])) {
        			                                                                	$total_labour_material_val += getRealValue($recb['plumbing_snd4_val']);
        			                                                                    DisplayGBP($recb['plumbing_snd4_val']);
        			                                                                } ?>"></td>
        															</tr>
        															<tr>
        																<td style="border:0;width: 55%;padding-left:2px;padding-right:2px">Wall hung toilet or similar</td>
        																<td style="border: 0" colspan="2"></td>
        																<td  style="border:0;"><input  id="plumbing_snd5_val" name="plumbing_snd5_val" type="text"  
        				                                    					value="<?php if (isset($_POST['plumbing_snd5_val'])) {
        				                                    							$total_labour_material_val += getRealValue($_POST['plumbing_snd5_val']);
        			                                                                    DisplayGBP($_POST['plumbing_snd5_val']);
        			                                                                } else if (isset($recb['plumbing_snd5_val'])) {
        			                                                                	$total_labour_material_val += getRealValue($recb['plumbing_snd5_val']);
        			                                                                    DisplayGBP($recb['plumbing_snd5_val']);
        			                                                                } ?>"></td>
        															</tr>
        															<tr>
        																<td style="border:0;width: 55%;padding-left:2px;padding-right:2px">Towel Rail in White / Pipe cover in White </td>
        																<td style="border: 0" colspan="2"></td>
        																<td  style="border:0;"><input  id="plumbing_snd6_val" name="plumbing_snd6_val" type="text"  
        				                                    					value="<?php if (isset($_POST['plumbing_snd6_val'])) {
        				                                    							$total_labour_material_val += getRealValue($_POST['plumbing_snd6_val']);
        			                                                                    DisplayGBP($_POST['plumbing_snd6_val']);
        			                                                                } else if (isset($recb['plumbing_snd6_val'])) {
        			                                                                	$total_labour_material_val += getRealValue($recb['plumbing_snd6_val']);
        			                                                                    DisplayGBP($recb['plumbing_snd6_val']);
        			                                                                } ?>"></td>
        															</tr>
        															<tr>
        																<td style="border:0;width: 55%;padding-left:2px;padding-right:2px">Install accessories</td>
        																<td style="border: 0" colspan="2"></td>
        																<td  style="border:0;"><input  id="plumbing_snd7_val" name="plumbing_snd7_val" type="text"  
        				                                    					value="<?php if (isset($_POST['plumbing_snd7_val'])) {
        				                                    							$total_labour_material_val += getRealValue($_POST['plumbing_snd7_val']);
        			                                                                    DisplayGBP($_POST['plumbing_snd7_val']);
        			                                                                } else if (isset($recb['plumbing_snd7_val'])) {
        			                                                                	$total_labour_material_val += getRealValue($recb['plumbing_snd7_val']);
        			                                                                    DisplayGBP($recb['plumbing_snd7_val']);
        			                                                                } ?>"></td>
        															</tr>
        															<tr>
        																<td style="border:0;width: 55%;padding-left:2px;padding-right:2px">Fill system and test </td>
        																<td style="border: 0" colspan="2"></td>
        																<td  style="border:0;"><input  id="plumbing_snd8_val" name="plumbing_snd8_val" type="text"  
        				                                    					value="<?php if (isset($_POST['plumbing_snd8_val'])) {
        				                                    							$total_labour_material_val += getRealValue($_POST['plumbing_snd8_val']);
        			                                                                    DisplayGBP($_POST['plumbing_snd8_val']);
        			                                                                } else if (isset($recb['plumbing_snd8_val'])) {
        			                                                                	$total_labour_material_val += getRealValue($recb['plumbing_snd8_val']);
        			                                                                    DisplayGBP($recb['plumbing_snd8_val']);
        			                                                                } ?>"></td>
        															</tr>
        															
        															<tr><td colspan="4">&nbsp;</td></tr>
        															<tr>
        																<td style="border:0;width: 55%;padding-left:2px;padding-right:2px"><b><u>Painting</u></b></td>
        																<td  style="border:0;" colspan="3"></td>
        															</tr>
        															<tr>
        																<td style="border:0;width: 55%;padding-left:2px;padding-right:2px">Paint ceiling in white bathroom paint</td>
        																<td style="border: 0" colspan="2"></td>
        																<td  style="border:0;"><input  id="painting_val" name="painting_val" type="text"  
        				                                    					value="<?php if (isset($_POST['painting_val'])) {
        				                                    							$total_labour_material_val += getRealValue($_POST['painting_val']);
        			                                                                    DisplayGBP($_POST['painting_val']);
        			                                                                } else if (isset($recb['painting_val'])) {
        			                                                                	$total_labour_material_val += getRealValue($recb['painting_val']);
        			                                                                    DisplayGBP($recb['painting_val']);
        			                                                                } ?>"></td>
        															</tr>
        															
        															<tr><td colspan="4">&nbsp;</td></tr>
        															<tr>
        																<td style="border:0;width: 55%;padding-left:2px;padding-right:2px"><b><u>Tiling - Ceramic tiles</u></b></td>
        																<td  style="border:0;" colspan="3"></td>
        															</tr>
        															<tr>
        																<td style="border:0;width: 55%;padding-left:2px;padding-right:2px">Tile shower area only (7sqm approx)</td>
        																<td style="border: 0" colspan="2"></td>
        																<td  style="border:0;"><input  id="tiling1_val" name="tiling1_val" type="text"  
        				                                    					value="<?php if (isset($_POST['tiling1_val'])) {
        				                                    							$total_labour_material_val += getRealValue($_POST['tiling1_val']);
        			                                                                    DisplayGBP($_POST['tiling1_val']);
        			                                                                } else if (isset($recb['tiling1_val'])) {
        			                                                                	$total_labour_material_val += getRealValue($recb['tiling1_val']);
        			                                                                    DisplayGBP($recb['tiling1_val']);
        			                                                                } ?>"></td>
        															</tr>
        															<tr>
        																<td style="border:0;width: 55%;padding-left:2px;padding-right:2px">Install tile trims</td>
        																<td style="border: 0" colspan="2"></td>
        																<td  style="border:0;"><input  id="tiling2_val" name="tiling2_val" type="text"  
        				                                    					value="<?php if (isset($_POST['tiling2_val'])) {
        				                                    							$total_labour_material_val += getRealValue($_POST['tiling2_val']);
        			                                                                    DisplayGBP($_POST['tiling2_val']);
        			                                                                } else if (isset($recb['tiling2_val'])) {
        			                                                                	$total_labour_material_val += getRealValue($recb['tiling2_val']);
        			                                                                    DisplayGBP($recb['tiling2_val']);
        			                                                                } ?>"></td>
        															</tr>
        															<tr>
        																<td style="border:0;width: 55%;padding-left:2px;padding-right:2px">Tile floor area (3sqm approx)</td>
        																<td style="border: 0" colspan="2"></td>
        																<td  style="border:0;"><input  id="tiling3_val" name="tiling3_val" type="text"  
        				                                    					value="<?php if (isset($_POST['tiling3_val'])) {
        				                                    							$total_labour_material_val += getRealValue($_POST['tiling3_val']);
        			                                                                    DisplayGBP($_POST['tiling3_val']);
        			                                                                } else if (isset($recb['tiling3_val'])) {
        			                                                                	$total_labour_material_val += getRealValue($recb['tiling3_val']);
        			                                                                    DisplayGBP($recb['tiling3_val']);
        			                                                                } ?>"></td>
        															</tr>
        															<tr>
        																<td style="border:0;width: 55%;padding-left:2px;padding-right:2px">Grout Walls and floor</td>
        																<td style="border: 0" colspan="2"></td>
        																<td  style="border:0;"><input  id="tiling4_val" name="tiling4_val" type="text"  
        				                                    					value="<?php if (isset($_POST['tiling4_val'])) {
        				                                    							$total_labour_material_val += getRealValue($_POST['tiling4_val']);
        			                                                                    DisplayGBP($_POST['tiling4_val']);
        			                                                                } else if (isset($recb['tiling4_val'])) {
        			                                                                	$total_labour_material_val += getRealValue($recb['tiling4_val']);
        			                                                                    DisplayGBP($recb['tiling4_val']);
        			                                                                } ?>"></td>
        															</tr>
        															
        															<tr><td colspan="4">&nbsp;</td></tr>
        															<tr>
        																<td style="border:0;width: 55%;padding-left:2px;padding-right:2px"><b><u>Carpentry</u></b></td>
        																<td  style="border:0;" colspan="3"></td>
        															</tr>
        															<tr>
        																<td style="border:0;width: 55%;padding-left:2px;padding-right:2px">Wall hung / Floor cabinet (assemble included if required)</td>
        																<td style="border: 0" colspan="2"></td>
        																<td  style="border:0;"><input  id="carpentry1_val" name="carpentry1_val" type="text"  
        				                                    					value="<?php if (isset($_POST['carpentry1_val'])) {
        				                                    							$total_labour_material_val += getRealValue($_POST['carpentry1_val']);
        			                                                                    DisplayGBP($_POST['carpentry1_val']);
        			                                                                } else if (isset($recb['carpentry1_val'])) {
        			                                                                	$total_labour_material_val += getRealValue($recb['carpentry1_val']);
        			                                                                    DisplayGBP($recb['carpentry1_val']);
        			                                                                } ?>"></td>
        															</tr>
        															<tr>
        																<td style="border:0;width: 55%;padding-left:2px;padding-right:2px">Architrave if required</td>
        																<td style="border: 0" colspan="2"></td>
        																<td  style="border:0;"><input  id="carpentry2_val" name="carpentry2_val" type="text"  
        				                                    					value="<?php if (isset($_POST['carpentry2_val'])) {
        				                                    							$total_labour_material_val += getRealValue($_POST['carpentry2_val']);
        			                                                                    DisplayGBP($_POST['carpentry2_val']);
        			                                                                } else if (isset($recb['carpentry2_val'])) {
        			                                                                	$total_labour_material_val += getRealValue($recb['carpentry2_val']);
        			                                                                    DisplayGBP($recb['carpentry2_val']);
        			                                                                } ?>"></td>
        															</tr>
        															
        															<tr><td colspan="4">&nbsp;</td></tr>
        															<tr>
        																<td style="border:0;width: 55%;padding-left:2px;padding-right:2px"><b><u>Painting</u></b></td>
        																<td  style="border:0;" colspan="3"></td>
        															</tr>
        															<tr>
        																<td style="border:0;width: 55%;padding-left:2px;padding-right:2px">Paint remaining walls in bathroom paint ( customer to choose and supply paint)</td>
        																<td style="border: 0" colspan="2"></td>
        																<td  style="border:0;"><input  id="painting1_val" name="painting1_val" type="text"  
        				                                    					value="<?php if (isset($_POST['painting1_val'])) {
        				                                    							$total_labour_material_val += getRealValue($_POST['painting1_val']);
        			                                                                    DisplayGBP($_POST['painting1_val']);
        			                                                                } else if (isset($recb['painting1_val'])) {
        			                                                                	$total_labour_material_val += getRealValue($recb['painting1_val']);
        			                                                                    DisplayGBP($recb['painting1_val']);
        			                                                                } ?>"></td>
        															</tr>
        															<tr>
        																<td style="border:0;width: 55%;padding-left:2px;padding-right:2px">Seal all required area with waterproof mastic</td>
        																<td style="border: 0" colspan="2"></td>
        																<td  style="border:0;"><input  id="painting2_val" name="painting2_val" type="text"  
        				                                    					value="<?php if (isset($_POST['painting2_val'])) {
        				                                    							$total_labour_material_val += getRealValue($_POST['painting2_val']);
        			                                                                    DisplayGBP($_POST['painting2_val']);
        			                                                                } else if (isset($recb['painting2_val'])) {
        			                                                                	$total_labour_material_val += getRealValue($recb['painting2_val']);
        			                                                                    DisplayGBP($recb['painting2_val']);
        			                                                                } ?>"></td>
        															</tr>
        															
        															<tr><td colspan="4">&nbsp;</td></tr>
        															<tr>
        																<td style="border:0;width: 55%;padding-left:2px;padding-right:2px"><b><u>Other</u></b></td>
        																<td  style="border:0;" colspan="3"></td>
        															</tr>
        															<tr>
        																<td style="border:0;width: 55%;padding-left:2px;padding-right:2px">Remove loose plaster from wall in hallway back to brick, remove skirting and architrave where required</td>
        																<td style="border: 0" colspan="2"></td>
        																<td  style="border:0;"><input  id="other1_val" name="other1_val" type="text"  
        				                                    					value="<?php if (isset($_POST['other1_val'])) {
        				                                    							$total_labour_material_val += getRealValue($_POST['other1_val']);
        			                                                                    DisplayGBP($_POST['other1_val']);
        			                                                                } else if (isset($recb['other1_val'])) {
        			                                                                	$total_labour_material_val += getRealValue($recb['other1_val']);
        			                                                                    DisplayGBP($recb['other1_val']);
        			                                                                } ?>"></td>
        															</tr>
        															<tr>
        																<td style="border:0;width: 55%;padding-left:2px;padding-right:2px">Bond wall</td>
        																<td style="border: 0" colspan="2"></td>
        																<td  style="border:0;"><input  id="other2_val" name="other2_val" type="text"  
        				                                    					value="<?php if (isset($_POST['other2_val'])) {
        				                                    							$total_labour_material_val += getRealValue($_POST['other2_val']);
        			                                                                    DisplayGBP($_POST['other2_val']);
        			                                                                } else if (isset($recb['other2_val'])) {
        			                                                                	$total_labour_material_val += getRealValue($recb['other2_val']);
        			                                                                    DisplayGBP($recb['other2_val']);
        			                                                                } ?>"></td>
        															</tr>
        															<tr>
        																<td style="border:0;width: 55%;padding-left:2px;padding-right:2px">Plaster wall 2 coats</td>
        																<td style="border: 0" colspan="2"></td>
        																<td  style="border:0;"><input  id="other3_val" name="other3_val" type="text"  
        				                                    					value="<?php if (isset($_POST['other3_val'])) {
        				                                    							$total_labour_material_val += getRealValue($_POST['other3_val']);
        			                                                                    DisplayGBP($_POST['other3_val']);
        			                                                                } else if (isset($recb['other3_val'])) {
        			                                                                	$total_labour_material_val += getRealValue($recb['other3_val']);
        			                                                                    DisplayGBP($recb['other3_val']);
        			                                                                } ?>"></td>
        															</tr>
        															<tr>
        																<td style="border:0;width: 55%;padding-left:2px;padding-right:2px">Fit new skirting and architrave where required</td>
        																<td style="border: 0" colspan="2"></td>
        																<td  style="border:0;"><input  id="other4_val" name="other4_val" type="text"  
        				                                    					value="<?php if (isset($_POST['other4_val'])) {
        				                                    							$total_labour_material_val += getRealValue($_POST['other4_val']);
        			                                                                    DisplayGBP($_POST['other4_val']);
        			                                                                } else if (isset($recb['other4_val'])) {
        			                                                                	$total_labour_material_val += getRealValue($recb['other4_val']);
        			                                                                    DisplayGBP($recb['other4_val']);
        			                                                                } ?>"></td>
        															</tr>
        															<tr>
        																<td style="border:0;width: 55%;padding-left:2px;padding-right:2px">Paint wall (Customer to choose and supply paint)</td>
        																<td style="border: 0" colspan="2"></td>
        																<td  style="border:0;"><input  id="other5_val" name="other5_val" type="text"  
        				                                    					value="<?php if (isset($_POST['other5_val'])) {
        				                                    							$total_labour_material_val += getRealValue($_POST['other5_val']);
        			                                                                    DisplayGBP($_POST['other5_val']);
        			                                                                } else if (isset($recb['other5_val'])) {
        			                                                                	$total_labour_material_val += getRealValue($recb['other5_val']);
        			                                                                    DisplayGBP($recb['other5_val']);
        			                                                                } ?>"></td>
        															</tr>
        															
        															<tr><td colspan="4">&nbsp;</td></tr>
        															<tr>
        																<td style="border:0;width: 55%;padding-left:2px;padding-right:2px"><b><u>Skip</u></b></td>
        																<td style="border: 0" colspan="2"></td>
        																<td  style="border:0;"><input  id="skip_val" name="skip_val" type="text"  
        				                                    					value="<?php if (isset($_POST['skip_val'])) {
        				                                    							$total_labour_material_val += getRealValue($_POST['skip_val']);
        			                                                                    DisplayGBP($_POST['skip_val']);
        			                                                                } else if (isset($recb['skip_val'])) {
        			                                                                	$total_labour_material_val += getRealValue($recb['skip_val']);
        			                                                                    DisplayGBP($recb['skip_val']);
        			                                                                } ?>"></td>
        															</tr>
        															
        															<tr><td colspan="4">&nbsp;</td></tr>
        															<tr>
        																<td style="border:0;width: 55%;padding-left:2px;padding-right:2px"><b><u>Materials - included</u></b></td>
        																<td  style="border:0;" colspan="3"></td>
        															</tr>
        															<tr>
        																<td style="border:0;width: 55%;padding-left:2px;padding-right:2px">Plumbing materials</td>
        																<td style="border: 0" colspan="2"></td>
        																<td  style="border:0;"><input  id="material1_val" name="material1_val" type="text"  
        				                                    					value="<?php if (isset($_POST['material1_val'])) {
        				                                    							$total_labour_material_val += getRealValue($_POST['material1_val']);
        			                                                                    DisplayGBP($_POST['material1_val']);
        			                                                                } else if (isset($recb['material1_val'])) {
        			                                                                	$total_labour_material_val += getRealValue($recb['material1_val']);
        			                                                                    DisplayGBP($recb['material1_val']);
        			                                                                } ?>"></td>
        															</tr>
        															<tr>
        																<td style="border:0;width: 55%;padding-left:2px;padding-right:2px">Plastering materials</td>
        																<td style="border: 0" colspan="2"></td>
        																<td  style="border:0;"><input  id="material2_val" name="material2_val" type="text"  
        				                                    					value="<?php if (isset($_POST['material2_val'])) {
        				                                    							$total_labour_material_val += getRealValue($_POST['material2_val']);
        			                                                                    DisplayGBP($_POST['material2_val']);
        			                                                                } else if (isset($recb['material2_val'])) {
        			                                                                	$total_labour_material_val += getRealValue($recb['material2_val']);
        			                                                                    DisplayGBP($recb['material2_val']);
        			                                                                } ?>"></td>
        															</tr>
        															<tr>
        																<td style="border:0;width: 55%;padding-left:2px;padding-right:2px">Paint</td>
        																<td style="border: 0" colspan="2"></td>
        																<td  style="border:0;"><input  id="material3_val" name="material3_val" type="text"  
        				                                    					value="<?php if (isset($_POST['material3_val'])) {
        				                                    							$total_labour_material_val += getRealValue($_POST['material3_val']);
        			                                                                    DisplayGBP($_POST['material3_val']);
        			                                                                } else if (isset($recb['material3_val'])) {
        			                                                                	$total_labour_material_val += getRealValue($recb['material3_val']);
        			                                                                    DisplayGBP($recb['material3_val']);
        			                                                                } ?>"></td>
        															</tr>
        															<tr>
        																<td style="border:0;width: 55%;padding-left:2px;padding-right:2px">Floor protection</td>
        																<td style="border: 0" colspan="2"></td>
        																<td  style="border:0;"><input  id="material4_val" name="material4_val" type="text"  
        				                                    					value="<?php if (isset($_POST['material4_val'])) {
        				                                    							$total_labour_material_val += getRealValue($_POST['material4_val']);
        			                                                                    DisplayGBP($_POST['material4_val']);
        			                                                                } else if (isset($recb['material4_val'])) {
        			                                                                	$total_labour_material_val += getRealValue($recb['material4_val']);
        			                                                                    DisplayGBP($recb['material4_val']);
        			                                                                } ?>"></td>
        															</tr>
        															
        															<tr><td colspan="4">&nbsp;</td></tr>
        															<tr>
        																<td style="border:0;width: 55%;padding-left:2px;padding-right:2px"><b><u>Not Included within quote:</u></b></td>
        																<td style="border: 0" colspan="2"></td>
        																<td  style="border:0;"><input  id="not_include_val" name="not_include_val" type="text"  
        				                                    					value="<?php if (isset($_POST['not_include_val'])) {
        				                                    							$total_labour_material_val += getRealValue($_POST['not_include_val']);
        			                                                                    DisplayGBP($_POST['not_include_val']);
        			                                                                } else if (isset($recb['not_include_val'])) {
        			                                                                	$total_labour_material_val += getRealValue($recb['not_include_val']);
        			                                                                    DisplayGBP($recb['not_include_val']);
        			                                                                } ?>"></td>
        															</tr>
        															
        															
        															<tr><td colspan="4">&nbsp;</td></tr>
        															<tr>
        																<td style="border:0;width: 55%;padding-left:2px;padding-right:2px"><b><u>Bathroom Furniture and Sanitryware</u></b></td>
        																<td  style="border:0;" colspan="3"></td>
        															</tr>
        															<tr>
        																<td style="border:0;width: 55%;padding-left:2px;padding-right:2px">Thermostatic Shower Valve / Taps</td>
        																<td style="border: 0" colspan="2"></td>
        																<td  style="border:0;"><input  id="bathroom1_val" name="bathroom1_val" type="text"  
        				                                    					value="<?php if (isset($_POST['bathroom1_val'])) {
        				                                    							$total_labour_material_val += getRealValue($_POST['bathroom1_val']);
        			                                                                    DisplayGBP($_POST['bathroom1_val']);
        			                                                                } else if (isset($recb['bathroom1_val'])) {
        			                                                                	$total_labour_material_val += getRealValue($recb['bathroom1_val']);
        			                                                                    DisplayGBP($recb['bathroom1_val']);
        			                                                                } ?>"></td>
        															</tr>
        															<tr>
        																<td style="border:0;width: 55%;padding-left:2px;padding-right:2px">Wall hung / Floor cabinet</td>
        																<td style="border: 0" colspan="2"></td>
        																<td  style="border:0;"><input  id="bathroom2_val" name="bathroom2_val" type="text"  
        				                                    					value="<?php if (isset($_POST['bathroom2_val'])) {
        				                                    							$total_labour_material_val += getRealValue($_POST['bathroom2_val']);
        			                                                                    DisplayGBP($_POST['bathroom2_val']);
        			                                                                } else if (isset($recb['bathroom2_val'])) {
        			                                                                	$total_labour_material_val += getRealValue($recb['bathroom2_val']);
        			                                                                    DisplayGBP($recb['bathroom2_val']);
        			                                                                } ?>"></td>
        															</tr>
        															<tr>
        																<td style="border:0;width: 55%;padding-left:2px;padding-right:2px">New hand basin</td>
        																<td style="border: 0" colspan="2"></td>
        																<td  style="border:0;"><input  id="bathroom3_val" name="bathroom3_val" type="text"  
        				                                    					value="<?php if (isset($_POST['bathroom3_val'])) {
        				                                    							$total_labour_material_val += getRealValue($_POST['bathroom3_val']);
        			                                                                    DisplayGBP($_POST['bathroom3_val']);
        			                                                                } else if (isset($recb['bathroom3_val'])) {
        			                                                                	$total_labour_material_val += getRealValue($recb['bathroom3_val']);
        			                                                                    DisplayGBP($recb['bathroom3_val']);
        			                                                                } ?>"></td>
        															</tr>
        															<tr>
        																<td style="border:0;width: 55%;padding-left:2px;padding-right:2px">Shower Screen</td>
        																<td style="border: 0" colspan="2"></td>
        																<td  style="border:0;"><input  id="bathroom4_val" name="bathroom4_val" type="text"  
        				                                    					value="<?php if (isset($_POST['bathroom4_val'])) {
        				                                    							$total_labour_material_val += getRealValue($_POST['bathroom4_val']);
        			                                                                    DisplayGBP($_POST['bathroom4_val']);
        			                                                                } else if (isset($recb['bathroom4_val'])) {
        			                                                                	$total_labour_material_val += getRealValue($recb['bathroom4_val']);
        			                                                                    DisplayGBP($recb['bathroom4_val']);
        			                                                                } ?>"></td>
        															</tr>
        															<tr>
        																<td style="border:0;width: 55%;padding-left:2px;padding-right:2px">Toilet and Cistern</td>
        																<td style="border: 0" colspan="2"></td>
        																<td  style="border:0;"><input  id="bathroom5_val" name="bathroom5_val" type="text"  
        				                                    					value="<?php if (isset($_POST['bathroom5_val'])) {
        				                                    							$total_labour_material_val += getRealValue($_POST['bathroom5_val']);
        			                                                                    DisplayGBP($_POST['bathroom5_val']);
        			                                                                } else if (isset($recb['bathroom5_val'])) {
        			                                                                	$total_labour_material_val += getRealValue($recb['bathroom5_val']);
        			                                                                    DisplayGBP($recb['bathroom5_val']);
        			                                                                } ?>"></td>
        															</tr>
        															<tr>
        																<td style="border:0;width: 55%;padding-left:2px;padding-right:2px">Towel Rail and Thermostatic  Valves</td>
        																<td style="border: 0" colspan="2"></td>
        																<td  style="border:0;"><input  id="bathroom6_val" name="bathroom6_val" type="text"  
        				                                    					value="<?php if (isset($_POST['bathroom6_val'])) {
        				                                    							$total_labour_material_val += getRealValue($_POST['bathroom6_val']);
        			                                                                    DisplayGBP($_POST['bathroom6_val']);
        			                                                                } else if (isset($recb['bathroom6_val'])) {
        			                                                                	$total_labour_material_val += getRealValue($recb['bathroom6_val']);
        			                                                                    DisplayGBP($recb['bathroom6_val']);
        			                                                                } ?>"></td>
        															</tr>
        															<tr>
        																<td style="border:0;width: 55%;padding-left:2px;padding-right:2px">Shower Head (from ceiling)</td>
        																<td style="border: 0" colspan="2"></td>
        																<td  style="border:0;"><input  id="bathroom7_val" name="bathroom7_val" type="text"  
        				                                    					value="<?php if (isset($_POST['bathroom7_val'])) {
        				                                    							$total_labour_material_val += getRealValue($_POST['bathroom7_val']);
        			                                                                    DisplayGBP($_POST['bathroom7_val']);
        			                                                                } else if (isset($recb['bathroom7_val'])) {
        			                                                                	$total_labour_material_val += getRealValue($recb['bathroom7_val']);
        			                                                                    DisplayGBP($recb['bathroom7_val']);
        			                                                                } ?>"></td>
        															</tr>
        															<tr>
        																<td style="border:0;width: 55%;padding-left:2px;padding-right:2px">Accessories</td>
        																<td style="border: 0" colspan="2"></td>
        																<td  style="border:0;"><input  id="bathroom8_val" name="bathroom8_val" type="text"  
        				                                    					value="<?php if (isset($_POST['bathroom8_val'])) {
        				                                    							$total_labour_material_val += getRealValue($_POST['bathroom8_val']);
        			                                                                    DisplayGBP($_POST['bathroom8_val']);
        			                                                                } else if (isset($recb['bathroom8_val'])) {
        			                                                                	$total_labour_material_val += getRealValue($recb['bathroom8_val']);
        			                                                                    DisplayGBP($recb['bathroom8_val']);
        			                                                                } ?>"></td>
        															</tr>
        															
        															<tr><td colspan="4">&nbsp;</td></tr>
        															<tr>
        																<td style="border:0;width: 55%;padding-left:2px;padding-right:2px"><b><u>Tiling</u></b></td>
        																<td  style="border:0;" colspan="3"></td>
        															</tr>
        															<tr>
        																<td style="border:0;width: 55%;padding-left:2px;padding-right:2px">Wall and Floor Tiles, Adhesive, Trims and Grout</td>
        																<td style="border: 0" colspan="2"></td>
        																<td  style="border:0;"><input  id="tiling_snd_val" name="tiling_snd_val" type="text"  
        				                                    					value="<?php if (isset($_POST['tiling_snd_val'])) {
        				                                    							$total_labour_material_val += getRealValue($_POST['tiling_snd_val']);
        			                                                                    DisplayGBP($_POST['tiling_snd_val']);
        			                                                                } else if (isset($recb['tiling_snd_val'])) {
        			                                                                	$total_labour_material_val += getRealValue($recb['tiling_snd_val']);
        			                                                                    DisplayGBP($recb['tiling_snd_val']);
        			                                                                } ?>"></td>
        															</tr>
        															
        															<tr><td colspan="4">&nbsp;</td></tr>
        															<tr>
        																<td style="border:0;width: 55%;padding-left:2px;padding-right:2px"><b><u>Painting</u></b></td>
        																<td  style="border:0;" colspan="3"></td>
        															</tr>
        															<tr>
        																<td style="border:0;width: 55%;padding-left:2px;padding-right:2px">Wall paint</td>
        																<td style="border: 0" colspan="2"></td>
        																<td  style="border:0;"><input  id="painting_snd_val" name="painting_snd_val" type="text"  
        				                                    					value="<?php if (isset($_POST['painting_snd_val'])) {
        				                                    							$total_labour_material_val += getRealValue($_POST['painting_snd_val']);
        			                                                                    DisplayGBP($_POST['painting_snd_val']);
        			                                                                } else if (isset($recb['painting_snd_val'])) {
        			                                                                	$total_labour_material_val += getRealValue($recb['painting_snd_val']);
        			                                                                    DisplayGBP($recb['painting_snd_val']);
        			                                                                } ?>"></td>
        															</tr>
        															
        															<tr><td colspan="4" style="border: 0">&nbsp;</td></tr>
        															<tr><td colspan="4" style="border: 0">&nbsp;</td></tr>
        															<tr>
        																<td style="border:0;width: 55%;padding-left:2px;padding-right:2px;text-align: right"><b><u>Labour and Materials</u></b></td>
        																<td  style="border:0;" colspan="2"></td>
        																<td  style="border:0;"><?php DisplayGBP($total_labour_material_val);?></td>
        															</tr>
        															
        															
        															<tr><td colspan="4" style="border: 0">&nbsp;</td></tr>
        															<tr>
        																<td style="border:0;width: 55%;padding-left:2px;padding-right:2px;"><b>Terms and Conditioins</b></td>
        																<td  style="border:0;" colspan="3"></td>
        															</tr>
        															<tr>
        																<td style="border:0;width: 55%;padding-left:2px;padding-right:2px"><textarea id="term_condition" name="term_condition" cols="40"  rows="5"><?php 
        																		if (isset($_POST['term_condition'])) {
        				                                                            echo $_POST['term_condition'];
        				                                                        } else if (isset($recb['term_condition'])) {
        				                                                            echo $recb['term_condition'];
        				                                                        } ?></textarea></td>
        																<td style="border: 0" colspan="3"></td>
        																
        															</tr>
        															
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