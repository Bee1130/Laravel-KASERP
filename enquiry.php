<?php
//phpinfo();
/* * **-------------------------------------------------------------------**************************    

  Purpose     : 	Buyer Information Detail Page

  Project 	:	Sales Enquiry DB

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
	$buyer = "select * from enquiries_info where auto_id=".$_GET['rid'];
	$resb = mysqli_query($con, $buyer) or die(mysqli_error($con) . "11");
	$recb = mysqli_fetch_assoc($resb);
	
	if ($recb)
	{
		$sql_previous = "SELECT auto_id FROM enquiries_info WHERE auto_id<" . $recb['auto_id'] . $_SESSION['filter_user']." order by auto_id desc LIMIT 1";
		$result_previous = mysqli_query($con, $sql_previous) or die(mysqli_error($con));
		if ($row_previous = mysqli_fetch_assoc($result_previous))
			$next_id = $row_previous['auto_id'];

		$sql_next = "SELECT auto_id FROM enquiries_info WHERE auto_id>" . $recb['auto_id'] .$_SESSION['filter_user']. " LIMIT 1";
		$result_next = mysqli_query($con, $sql_next) or die(mysqli_error($con));
		if ($row_next = mysqli_fetch_assoc($result_next))
			$prev_id = $row_next['auto_id'];
			
		if (isset($_GET['notify']))
		{
		
			// Set notification as viewed for log info
			$sql_upd = sprintf("update log_info set is_viewed='%d' where is_viewed='%d' and customer_id='%d' and agent != '%s'",1,0,$_GET['rid'],$_SESSION['user_login']);
			$res=mysqli_query($con, $sql_upd) or die(mysqli_error($con)."11");
			
			$sql_log = sprintf("insert into system_log_info (action,agent,query,log_time) values ('%s','%s','%s',sysdate())","Update log info for viewed notification : enquiry.php ",$_SESSION['user_login'],mysqli_real_escape_string($con, $sql_upd));
			mysqli_query($con, $sql_log) or die(mysqli_error($con));	
		}
	}
	
	
}

$submit = null;
if(isset($_POST['Submit'])) {
	$submit = $_POST['Submit'];
}

if ($submit == "Save") 
{
   
    $_POST['phone'] = my_phone_format($_POST['phone']);
    $date_time = date('Y-m-d H:i',strtotime($_POST['date_time']));
    
    $sql_upd = "update enquiries_info set 
 					  name = '" . mysqli_real_escape_string($con, $_POST['name']) . "',
 					  phone = '" . $_POST['phone'] . "',
 					  email = '" . mysqli_real_escape_string($con, $_POST['email']) . "',
 					  subject = '" . mysqli_real_escape_string($con, $_POST['subject']) . "',
 					  category = '" . mysqli_real_escape_string($con, $_POST['category']) . "',
 					  enquiry = '" . mysqli_real_escape_string($con, $_POST['enquiry']) . "',
 					  status = '" . mysqli_real_escape_string($con, $_POST['status']) . "',
 					  handeld_by = '" . mysqli_real_escape_string($con, $_POST['handeld_by']) . "',
 					  date_time =  '".$date_time."'										  
			  where auto_id =" . $_GET['rid'] . "";
   
    mysqli_query($con, $sql_upd) or die(mysqli_error($con));
    
   
     // insert action logs into system_log_info
	$sql_log = sprintf("insert into system_log_info (action,agent,query,log_time) values ('%s','%s','%s',sysdate())","Update Enquiry : enquiry.php ",$_SESSION['user_login'],mysqli_real_escape_string($con, $sql_upd));
	mysqli_query($con, $sql_log) or die(mysqli_error($con));	
	
	// Save log
	saveLog($_GET['rid'],'Enquiry','Enquiry','changed');
	
    header("Location: enquiries.php");
    exit();
    
}



if ($submit == "Cancel") {
	
    header("Location: enquiries.php");
    exit();	
}	

// File
{
	$FileSave = null;
	if(isset($_POST['FileSave'])) {
		$FileSave = $_POST['FileSave'];
	}
	if ($FileSave == "Save")
	{		
				
		$buyer = "select * from enquiries_info where auto_id=".$_POST['file_auto_id'];
		$resb = mysqli_query($con, $buyer) or die(mysqli_error($con) . "11");	
		
		if ($recb = mysqli_fetch_assoc($resb))
		{
			$filenames = $recb['fil_filename'];
			$filepaths = $recb['fil_filepath'];
			$dates = $recb['fil_date'];
		}else
		{
			$filenames = "";
			$filepaths = "";
			$dates = "";
		}
		
		$file_new_filename = trim($_POST['file_new_filename']);	
		$file_new_date = date('M d, Y, h:i a');
		$file_new_filepath = '';
		if(isset($_FILES['file_new_upload']) and is_uploaded_file($_FILES['file_new_upload']['tmp_name']))
		{			
			$Destination = 'uploads';
			$attach_file_nm=str_replace(' ','-',strtolower($_FILES['file_new_upload']['name']));
			move_uploaded_file($_FILES['file_new_upload']['tmp_name'], "$Destination/$attach_file_nm");			
			$file_new_filepath = $Destination.'/'.urlencode($attach_file_nm);
		}
		
		$file_value_ind = intval($_POST['file_value_ind']);
		if ($file_value_ind >= 0)
		{
			$filenames = "";
			$filepaths = "";
			$dates = "";
			
			$filenames_ary = explode(';',$recb['fil_filename']);
			$filepaths_ary = explode(';',$recb['fil_filepath']);
			$dates_ary = explode(';',$recb['fil_date']);
			    									
					
			
	    	foreach ($filenames_ary as $i => $filename)
	        {	
	        	$filepath = $filepaths_ary[$i];  
	        	$date = $dates_ary[$i];  
	        	
	        	if ($i != $file_value_ind)                                              	
	        	{
					$filenames .= $filename.';';
					$filepaths.= $filepath.';';
					$dates.= $date.';';
				}else
				{
					$filenames .= $file_new_filename.';';
					$filepaths .= $file_new_filepath.';';
					$dates .= $file_new_date.';';
				}
	        }
	        $filenames = substr($filenames,0,strlen($filenames)-1);
	        $filepaths = substr($filepaths,0,strlen($filepaths)-1);
	        $dates = substr($dates,0,strlen($dates)-1);
	        
	        // Save log
			saveLog($_POST['file_auto_id'],'Enquiry','File','changed');
		}else
		{
			if (strlen($filenames)>1)
			{
				$filenames .= ';'.$file_new_filename;
				$filepaths .= ';'.$file_new_filepath;	
				$dates .= ';'.$file_new_date;	
			}else
			{
				$filenames = $file_new_filename;
				$filepaths = $file_new_filepath;
				$dates = $file_new_date;	
			}
			
			// Save log
			saveLog($_POST['file_auto_id'],'Enquiry','File','added');
			
		}
		
		if (strlen($filenames)>1)
		{
			$sql_upd = "update enquiries_info set 
	 					  fil_filename = '" . mysqli_real_escape_string($con, $filenames) . "',
	 					  fil_filepath = '" . mysqli_real_escape_string($con, $filepaths) . "',
	 					  fil_date = '" . mysqli_real_escape_string($con, $dates) . "',
						  cust_upd_dt= curdate()										  
				  where auto_id =" . $_POST['file_auto_id'] . "";
	   
	    	mysqli_query($con, $sql_upd) or die(mysqli_error($con));
		}
	    
		header("Location: enquiry.php?rid=" . $_POST['file_auto_id']);
	    exit();
	}

	$FileDelete = null;
	if(isset($_POST['FileDelete'])) {
		$FileDelete = $_POST['FileDelete'];
	}

	if ($FileDelete == "Delete")
	{
		$buyer = "select * from enquiries_info where auto_id=".$_POST['file_auto_id'];
		$resb = mysqli_query($con, $buyer) or die(mysqli_error($con) . "11");	
		if ($recb = mysqli_fetch_assoc($resb))
		{
			$filenames = $recb['fil_filename'];
			$filepaths = $recb['fil_filepath'];
			$dates = $recb['fil_date'];
			
			$file_value_ind = intval($_POST['file_value_ind']);
			
			if ($file_value_ind >= 0)
			{
				$filenames = "";
				$filepaths = "";
				$dates = "";
				
				$filenames_ary = explode(';',$recb['fil_filename']);
				$filepaths_ary = explode(';',$recb['fil_filepath']);
				$dates_ary = explode(';',$recb['fil_date']);
				
		    	foreach ($filenames_ary as $i => $filename)
		        {	
		        	$filepath = $filepaths_ary[$i];  
		        	$date = $dates_ary[$i];  
		        	
		        	if ($i != $file_value_ind)                                              	
		        	{                                   	
			        	$filenames .= $filename.';';
						$filepaths.= $filepath.';';
						$dates.= $date.';';
					}
		        }
		        
		         $filenames = substr($filenames,0,strlen($filenames)-1);
		        $filepaths = substr($filepaths,0,strlen($filepaths)-1);
		        $dates = substr($dates,0,strlen($dates)-1);
	        	
	        	
				$sql_upd = "update enquiries_info set 
	 					  fil_filename = '" . mysqli_real_escape_string($con, $filenames) . "',
	 					  fil_filepath = '" . mysqli_real_escape_string($con, $filepaths) . "',
	 					  fil_date = '" . mysqli_real_escape_string($con, $dates) . "',
						  cust_upd_dt= curdate()										  
				  where auto_id =" . $_POST['file_auto_id'] . "";
	   
		    	mysqli_query($con, $sql_upd) or die(mysqli_error($con));	
		    	
		    	// Save log
				saveLog($_POST['file_auto_id'],'Enquiry','File','removed');			
			}
		}		
		header("Location: enquiry.php?rid=" . $_POST['file_auto_id']);
	    exit();
	}
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
		
	    <script type="text/javascript">
	      
            $(document).ready(function () { 
			    $("#panel-fullscreen-Main").click(function (e) {
			        $(this).closest('.panel').toggleClass('panel-fullscreen');
			    });
			   
			    $("#panel-fullscreen-Files").click(function (e) {
			        $(this).closest('.panel').toggleClass('panel-fullscreen');
			    });
			    
			    // File
				$("#btn_add_file").click(function(event){				 	
	        		$("#file_value_ind").val(-1);
	        		$("#file_new_filename").val("");
				 	$("#file_form").show();
				});
				
				$("#btn_cancel_file").click(function(event){
				 	$("#file_form").hide();
				 	$("#file_new_filename").val("");
				 	
	        		$("#file_value_ind").val(-1);
				});
			    
			    $('.datetimePicker').datetimepicker(							
			     {
		            dayOfWeekStart: 0,
		            format: 'M d, Y, h:i a',
		            hour: '7:00 AM',
		            step: 30,
		            formatTime: 'g:i A',
		            allowTimes: ['7:00 AM', '7:30 AM', '8:00 AM', '8:30 AM', '9:00 AM', '9:30 AM', '10:00 AM', '10:30 AM', '11:00 AM', '11:30 AM', '12:00 PM', '12:30 PM', '1:00 PM', '1:30 PM', '2:00 PM', '2:30 PM', '3:00 PM', '3:30 PM', '4:00 PM', '4:30 PM', '5:00 PM', '5:30 PM', '6:00 PM', '6:30 PM', '7:00 PM'],
		        });
            });
			
			function EditFile(auto_id,value_ind)
	        {
	        	console.log("EditFile");
	        	console.log(auto_id);
	        	
	        	var data = {					
					"type":"File",
					"auto_id":auto_id,
					"value_ind":value_ind
				};
				console.log(data);       			
			    $.ajax({
			        url: 'getEnquiryContent.php',
			        data: data,
			        type:"POST",
					dataType : "json",
			        success: function ( res ) {
			        	console.log("Success");
			        	console.log(res);
			        	if (res.status == "Success")
			        	{
			        		var current_path = 'Currently: ';
			        		current_path = current_path + "<a href='"+res.file_new_filepath+"'>"+res.file_new_filepath+"</a>" +"<br/>"+"Change:";
			        		$("#file_new_filepath").html(current_path);
			        		$("#file_new_filename").val(res.file_new_filename);
						}							
			        }
			    });
	        	
	        	$("#file_value_ind").val(value_ind);	        	
	        	$("#file_form").show();        
	        }  
	      
        </script>   
    </head>
    <body>
        <script type="text/javascript" src="popcalendar.js"></script>
        <div>	    	
	    	<input type="hidden" name="sel_customer_id" id ="sel_customer_id" value="<?php echo $_SESSION['user_login'].':'.$_GET['rid']?>">	    	
    	</div>
    	
    	<div class="container">
    	    <?php include("sidebar.php"); ?>
    	    <div style="margin-left: 285px;">
        		<div class="container">
        		    <?php include('menu.php'); ?>			
        			<div id="my-main-content">				
        				<br>
        				<ol class="breadcrumb pull-right" style="margin-bottom: 5px;">
        				  <?php
                        	if ($next_id != -1)
                        	{
                        	?>
        					<li><a href="enquiry.php?rid=<?php echo $next_id;?>">Previous</a></li>	
        					<?php
        					}
                        ?>
                      
                        <?php
                        	if ($prev_id != -1)
                        	{
                        	?>
        					<li><a href="enquiry.php?rid=<?php echo $prev_id;?>">Next</a></li>	
        					<?php
        					}
                        ?>  
               			</ol>
        				<h2 style="margin-top:0px">&nbsp;&nbsp;Enquiry</h2>
        				
        				<!-- Main content -->
        				<div class="row">            		
                    		<div class="col-md-6 col-lg-6">   	
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
        			               
        		                	<form method="POST" action="<?php echo 'enquiry.php?rid='.$_GET['rid']; ?>">
        			                    
        					            <div id="collapseMain"  class="panel-collapse collapse in">
        				                	<div class="panel-body" id="Main">	
        				                        <div class="row">
        				                        	 <div class="col-lg-2">
        				                                <div class="form-group">
        				                                    <label for="{{ form.date_time.for_label" class="control-label">Date Time</label>
        				                                    <input class="datetimePicker form-control enquiry-form-control target semi-bold" id="date_time" name="date_time" type="text" value="<?php if (isset($_POST['date_time'])) {
        			                                                                    echo $_POST['date_time'];
        			                                                                } else if (isset($recb['date_time'])) {
        			                                                                	$date = date('M d, Y, h:i a',strtotime($recb['date_time']));
        			                                                                    echo $date;
        			                                                                } ?>">
        				                                    
        				                                  
        				                                </div>
        				                            </div>
        				                            
        				                            <div class="col-lg-3">
        				                                <div class="form-group">
        				                                    <label for="{{ form.name.for_label" class="control-label">Name</label>
        				                                    <input class="form-control  enquiry-form-control target semi-bold" id="name" maxlength="100" name="name" type="text"  
        				                                    					value="<?php if (isset($_POST['name'])) {
        			                                                                    echo $_POST['name'];
        			                                                                } else if (isset($recb['name'])) {
        			                                                                    echo $recb['name'];
        			                                                                } ?>">
        				                                </div>
        				                            </div>
        				                           
        				                            <div class="col-lg-2">
        				                                <div class="form-group">
        				                                    <label for="{{ form.phone.for_label" class="control-label">Mobile</label>
        				                                    <input class="form-control  enquiry-form-control target semi-bold" id="phone" maxlength="100" name="phone" type="text"  
        				                                    					value="<?php if (isset($_POST['phone'])) {
        			                                                                    echo my_phone_format2($_POST['phone']);
        			                                                                } else if (isset($recb['phone'])) {
        			                                                                    echo my_phone_format2($recb['phone']);
        			                                                                } ?>">
        				                                </div>
        				                            </div>
        				                          
        				                            <div class="col-lg-3">
        				                                <div class="form-group">
        				                                    <label for="{{ form.email.for_label" class="control-label">Email</label>
        				                                    <input class="form-control  enquiry-form-control target semi-bold" id="email" maxlength="100" name="email" type="text"  
        				                                    					value="<?php if (isset($_POST['email'])) {
        			                                                                    echo $_POST['email'];
        			                                                                } else if (isset($recb['email'])) {
        			                                                                    echo $recb['email'];
        			                                                                } ?>">
        				                                </div>
        				                            </div>
        				                              <div class="col-lg-2">
        				                                <div class="form-group">
        				                                    <label for="{{ form.handeld_by.for_label" class="control-label">Handled by</label>
        				                                    <select class="form-control  enquiry-form-control target" id="handeld_by" name="handeld_by">
        		                                            	<?php
        					                                    	$sql_sel_status = "select * from admin_user";
        		                                                    $sql_res_status = mysqli_query($con, $sql_sel_status) or die(mysqli_error($con) . "go select error");
        		                                                    while ($sql_rec_status = mysqli_fetch_assoc($sql_res_status)) 
        		                                                    {
        		                                                    	$value = $sql_rec_status['user_id'];
        		                                                    	$text = $sql_rec_status['user_id'];
        		                                                    ?>
        		                                                    	<option value="<?php echo $text;?>" <?php 
        																		if ((isset($_POST['handeld_by'])? $_POST['handeld_by']  : '') == $text) {
        																				echo 'selected';
        																		} else if(isset($recb['handeld_by'])){
																				if ($recb['handeld_by'] == $text) {
        																			echo 'selected';
        																		}
																				} ?>><?php echo $text;?></option>
        		                                                	<?php
        		                                                    }
        					                                    ?>
        													</select>
        				                                </div>
        				                            </div>
        				                        </div>
        				                        <div class="row">
        				                        	 <div class="col-lg-8">
        				                                <div class="form-group">
        				                                    <label for="{{ form.subject.for_label" class="control-label">Subject</label>
        				                                    <input class="form-control  enquiry-form-control target semi-bold" id="subject" maxlength="100" name="subject" type="text"  
        				                                    					value="<?php if (isset($_POST['subject'])) {
        			                                                                    echo $_POST['subject'];
        			                                                                } else if (isset($recb['subject'])) {
        			                                                                    echo $recb['subject'];
        			                                                                } ?>">
        				                                </div>
        				                            </div>
        				                        	 <div class="col-lg-2">
        				                                <div class="form-group">
        				                                   <label for="{{ form.category.for_label" class="control-label">Category</label>
        		                                           <select class="form-control  enquiry-form-control target" id="category" name="category">
        		                                            	<option value=""></option>
        		                                            	<?php
        					                                    	$sql_sel_status = "select * from enquiry_category_info";
        		                                                    $sql_res_status = mysqli_query($con, $sql_sel_status) or die(mysqli_error($con) . "go select error");
        		                                                    while ($sql_rec_status = mysqli_fetch_assoc($sql_res_status)) 
        		                                                    {
        		                                                    	$value = $sql_rec_status['value'];
        		                                                    	$text = $sql_rec_status['text'];
        		                                                    ?>
        		                                                    	<option value="<?php echo $text;?>" <?php 
        																		if ((isset($_POST['category']))?$_POST['category'] : '' == $text) {
        																				echo 'selected';
        																		} else if(isset($recb['category'])) {
																				if ($recb['category'] == $text) {
        																			echo 'selected';
        																		}} ?>><?php echo $text;?></option>
        		                                                	<?php
        		                                                    }
        					                                    ?>
        													</select>
        				                                </div>
        				                            </div>
        			                                <div class="col-lg-2">
        				                                <div class="form-group">
        				                                    <label for="{{ form.status.for_label" class="control-label">Status</label>
        				                                    <select class="form-control  enquiry-form-control target" id="status" name="status">
        		                                            	<option value=""></option>
        		                                            	<?php
        					                                    	$sql_sel_status = "select * from enquiry_status_info";
        		                                                    $sql_res_status = mysqli_query($con, $sql_sel_status) or die(mysqli_error($con) . "go select error");
        		                                                    while ($sql_rec_status = mysqli_fetch_assoc($sql_res_status)) 
        		                                                    {
        		                                                    	$value = $sql_rec_status['value'];
        		                                                    	$text = $sql_rec_status['text'];
        		                                                    ?>
        		                                                    	<option value="<?php echo $text;?>" <?php 
        																		if ((isset($_POST['status']))?$_POST['status'] : '' == $text) {
        																				echo 'selected';
        																		} else if(isset($recb['status'])) {
																				if ($recb['status'] == $text) {
        																			echo 'selected';
        																		}} ?>><?php echo $text;?></option>
        		                                                	<?php
        		                                                    }
        					                                    ?>
        													</select>
        				                                </div>
        				                            </div>
        				                        </div>
        				                        <div class="row">
        			                                <div class="form-group">
        			                                    <label for="{{ form.enquiry.for_label" class="control-label">Enquriy</label>
        			                                    <textarea class="form-control  enquiry-form-control target" cols="40" id="enquiry" maxlength="2500" name="enquiry" rows="5"><?php 
        														if (isset($_POST['enquiry'])) {
                                                                    echo $_POST['enquiry'];
                                                                } else if (isset($recb['enquiry'])) {
                                                                    echo $recb['enquiry'];
                                                                } ?></textarea>
        			                                </div>
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
                    		<div class="col-md-6 col-lg-6">  
                    			<div class="panel panel-inverse" data-sortable-id="ui-general-9">
        							<div class="panel-heading">
        			                	<h4 class="panel-title">
        			                		<a>Files</a>
        							        <div class="panel-heading-btn">
        				                        <a href="#" id="panel-fullscreen-Files" role="button"  class="btn btn-xs btn-icon btn-circle btn-default" title="Toggle fullscreen"><i class="fa fa-expand"></i></a>
        				                        <a href="#collapseFiles" data-toggle="collapse" data-target="#collapseFiles" class="btn btn-xs btn-icon btn-circle btn-warning collapsed" ><i class="fa fa-minus"></i></a>
        				                    </div> 
        							        <div class="pull-right">
        							 	
        										<button class="btn btn-xs add-more btn-success" type="button" name="btn_add_file" id="btn_add_file"><i class="fa fa-plus"></i> Add</button>     
        									
        			                   		</div>
        							    </h4>
        			                </div>
        			                <div id="collapseFiles"  class="panel-collapse collapse in">
        			                	<div class="panel-body animated fadeIn" id="Files">	
        			                		<div class="form_placeholder" id = "file_form" style="display: none">		
        										<form class="form-horizontal" enctype="multipart/form-data" method="post" action="enquiry.php" >	
        										
        										    <fieldset>
        										        <input type="hidden" name="file_auto_id"  id="file_auto_id" value="<?php echo $_GET['rid']?>">											      		<input type="hidden" name="file_value_ind" id="file_value_ind">
        										        <div class="form-group">                  
        										           <label class="col-md-3 control-label">File:</label>
        										           <div class="col-md-9"> 
        										            	<div id = "file_new_filepath" name = "file_new_filepath"></div>
        										            	<input class="form-control" id="file_new_upload" name="file_new_upload" type="file">
        										            </div>
        										        </div>
        										        <div class="form-group">                  
        										           <label class="col-md-3 control-label">Name:</label>
        										           <div class="col-md-9"> 
        										            <input class="form-control" id="file_new_filename" maxlength="200" name="file_new_filename" type="text">
        										            </div>
        										        </div>
        												<div class="form-group">
        												    <div class="col-md-8 col-md-offset-4">
        												        <button class="save-form btn btn-sm btn-primary m-r-5" name="FileSave" type="submit" value="Save">Save</button>
        											            <button class="delete-form btn btn-sm btn-danger m-r-5" name="FileDelete" type="submit" value="Delete">Delete</button>
        											            <button class="close-form btn btn-sm btn-default"  type="button" name="btn_cancel_file" id="btn_cancel_file">Cancel</button>
        												    </div>
        												</div>    
        											</fieldset> 
        										</form>
        									</div>		                    
        				                    <div class="table_placeholder">
        				                    	<div class="table-responsive">
        											<table class="table table-condensed table-responsive table-hover table-striped">
        											    <thead>
        											        <tr>
        											            <th>Filename</th>     
        											            <th>Date</th>
        											        </tr>
        											    </thead>
        											    <tbody>
        			    								<?php
        			    									$filenames = explode(';',$recb['fil_filename']);
        			    									$dates = explode(';',$recb['fil_date']);
        			    									
        			    									for ($i=count($filenames)-1;$i>=0;$i--)
        	                                                {	       	        
        	                                                	$filename = $filenames[$i];            	                             	
        	                                                	$date = $dates[$i];	
        	                                                ?>
        		                                                 <tr>
        												            <td style="width:70%">								
        		                                                 		<a style="color: inherit;text-decoration: none;" onclick="javascript:EditFile('<?php echo $_GET['rid'];?>','<?php echo $i;?>')"><?php echo $filename;?></a>
        		                    							</td>
        												            <td><?php echo $date;?></td>												        	
        												        </tr>
        	                                            	<?php
        	                                                }
        				                                ?>  
        											    </tbody>
        											</table>
        											<div class="table-responsive"></div>
        										</div>
        									</div>
        				                </div>
        			            	</div>
        			            </div>
                    		</div>
                    	</div>
        			</div>
                </div>
            </div>
        </div>
    </body>
</html>