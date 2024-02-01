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

$submit = null;
if(isset($_POST['Submit'])) {
	$submit = $_POST['Submit'];
}

if ($submit == "Save") 
{
    
    $_POST['phone'] = my_phone_format($_POST['phone']);
    $date_time = date('Y-m-d H:i',strtotime($_POST['date_time']));
    
    $sql_ins = sprintf("insert into enquiries_info (name,phone,email,subject,category,enquiry,status,handeld_by,date_time) values ('%s','%s','%s','%s','%s','%s','%s','%s','%s')",mysqli_real_escape_string($con, $_POST['name']), $_POST['phone'], mysqli_real_escape_string($con, $_POST['email']), mysqli_real_escape_string($con, $_POST['subject']), mysqli_real_escape_string($con, $_POST['category']), mysqli_real_escape_string($con, $_POST['enquiry']), mysqli_real_escape_string($con, $_POST['status']), mysqli_real_escape_string($con, $_POST['handeld_by']),$date_time);   
    mysqli_query($con, $sql_ins) or die(mysqli_error($con));
  
  	$auto_id = mysqli_insert_id($con);
  	
    // insert action logs into system_log_info
	$sql_log = sprintf("insert into system_log_info (action,agent,query,log_time) values ('%s','%s','%s',sysdate())","Insert new Enquiry : enquiry_new.php ",$_SESSION['user_login'],mysqli_real_escape_string($con, $sql_ins));
	mysqli_query($con, $sql_log) or die(mysqli_error($con));	
    
     // Save log
	saveLog($auto_id,'Enquiry','Enquiry','added');
	
    header("Location: enquiries.php");
    exit();
}



if ($submit== "Cancel") {
	
    header("Location: enquiries.php");
    exit();
	
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
			   
			    $("#panel-fullscreen-Meeting").click(function (e) {
			        $(this).closest('.panel').toggleClass('panel-fullscreen');
			    });
			    $("#panel-fullscreen-Notes").click(function (e) {
			        $(this).closest('.panel').toggleClass('panel-fullscreen');
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
			
	      
        </script>   
    </head>
    <body>
		<?php
			$rid = isset($_GET['rid'])?$_GET['rid']: '';
			// var_dump($rid);die;
		?>
        <script type="text/javascript" src="popcalendar.js"></script>
        <div>	    	
	    	<input type="hidden" name="sel_customer_id" id ="sel_customer_id" value="<?php echo $_SESSION['user_login'].':'.$rid ?>">	    	
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
        			               
        		                	<form method="POST" action="enquiry_new.php">
        			                    
        					            <div id="collapseMain"  class="panel-collapse collapse in">
        				                	<div class="panel-body" id="Main">	
        				                        <div class="row">
        				                        	 <div class="col-lg-2">
        				                                <div class="form-group">
        				                                    <label for="{{ form.date_time.for_label" class="control-label">Date Time</label>
        				                                    <input class="datetimePicker form-control contact-form-control target semi-bold" id="date_time" name="date_time" type="text" value="<?php if (isset($_POST['date_time'])) {
        			                                                                    echo $_POST['date_time'];
        			                                                                } else if (isset($recb['date_time'])) {
        			                                                                    echo $recb['date_time'];
        			                                                                } ?>">
        				                                    
        				                                  
        				                                </div>
        				                            </div>
        				                            
        				                            <div class="col-lg-3">
        				                                <div class="form-group">
        				                                    <label for="{{ form.name.for_label" class="control-label">Name</label>
        				                                    <input class="form-control  contact-form-control target semi-bold" id="name" maxlength="100" name="name" type="text"  
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
        				                                    <input class="form-control  contact-form-control target semi-bold" id="phone" maxlength="100" name="phone" type="text"  
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
        				                                    <input class="form-control  contact-form-control target semi-bold" id="email" maxlength="100" name="email" type="text"  
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
        				                                    <select class="form-control  contact-form-control target" id="handeld_by" name="handeld_by">
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
        				                                    <input class="form-control  contact-form-control target semi-bold" id="subject" maxlength="100" name="subject" type="text"  
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
        		                                           <select class="form-control  contact-form-control target" id="category" name="category">
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
        				                                    <select class="form-control  contact-form-control target" id="status" name="status">
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
        			                                    <textarea class="form-control  contact-form-control target" cols="40" id="enquiry" maxlength="2500" name="enquiry" rows="5"><?php 
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
                    		
                    	</div>
        			</div>
                </div>
            </div>
        </div>
    </body>
</html>