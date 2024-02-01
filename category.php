<?php
//phpinfo();
/* * **-------------------------------------------------------------------**************************    

  Purpose     : 	Buyer Information Detail Page

  Project 	:	Sales Category DB

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
	$buyer = "select * from categories_info where auto_id=".$_GET['rid'];
	$resb = mysqli_query($con,$buyer) or die(mysqli_error($con) . "11");
	$recb = mysqli_fetch_assoc($resb);
	
	
	$sql_previous = "SELECT auto_id FROM categories_info WHERE auto_id<" . $recb['auto_id']." order by auto_id desc LIMIT 1";
	$result_previous = mysqli_query($con,$sql_previous) or die(mysqli_error($con));
	if ($row_previous = mysqli_fetch_assoc($result_previous))
		$next_id = $row_previous['auto_id'];

	$sql_next = "SELECT auto_id FROM categories_info WHERE auto_id>" . $recb['auto_id']. " LIMIT 1";
	$result_next = mysqli_query($con,$sql_next) or die(mysqli_error($con));
	if ($row_next = mysqli_fetch_assoc($result_next))
		$prev_id = $row_next['auto_id'];
		
	if (isset($_GET['notify']))
	{
	
		// Set notification as viewed for log info
		$sql_upd = sprintf("update log_info set is_viewed='%d' where is_viewed='%d' and customer_id='%d' and agent != '%s'",1,0,$_GET['rid'],$_SESSION['user_login']);
		$res=mysqli_query($con,$sql_upd) or die(mysqli_error($con)."11");
		
		$sql_log = sprintf("insert into system_log_info (action,agent,query,log_time) values ('%s','%s','%s',sysdate())","Update log info for viewed notification : category.php ",$_SESSION['user_login'],mysqli_real_escape_string($con, $sql_upd));
		mysqli_query($con,$sql_log) or die(mysqli_error($con));	
	}
}

if ($_POST['Submit'] == "Save") 
{
	 
    $sql_upd = "update categories_info set 
 					  name = '" . mysqli_real_escape_string($con, $_POST['name']) . "',
 					  description = '" . mysqli_real_escape_string($con, $_POST['description']) . "'
			  where auto_id =" . $_GET['rid'] . "";
   
    mysqli_query($con,$sql_upd) or die(mysqli_error($con));
    
   
     // insert action logs into system_log_info
	$sql_log = sprintf("insert into system_log_info (action,agent,query,log_time) values ('%s','%s','%s',sysdate())","Update Category : category.php ",$_SESSION['user_login'],mysqli_real_escape_string($con, $sql_upd));
	mysqli_query($con,$sql_log) or die(mysqli_error($con));	
	
	// Save log
	saveLog($_GET['rid'],'Category','Category','changed');
	
    header("Location: category.php?rid=" . $_GET['rid']);
    exit();
      
}


if ($_POST['Submit'] == "Cancel") {
	
    header("Location: categories.php");
    exit();
	
}	

// get latitude, longitude and formatted address
if (isset($recb['address']) and strlen($recb['address'])>0) 
{
    $data_arr = geocode($recb['address']);	
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
					<li><a href="category.php?rid=<?php echo $next_id;?>">Previous</a></li>	
					<?php
					}
                ?>
              
                <?php
                	if ($prev_id != -1)
                	{
                	?>
					<li><a href="category.php?rid=<?php echo $prev_id;?>">Next</a></li>	
					<?php
					}
                ?>  
       			</ol>
				<h2 style="margin-top:0px">&nbsp;&nbsp;Category</h2>
				
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
			               
		                	
			                <form action="<?php echo 'category.php?rid='.$_GET['rid']; ?>" method="post" enctype="multipart/form-data" id="UploadForm">
					            <div id="collapseMain"  class="panel-collapse collapse in">
				                	<div class="panel-body" id="Main">	
				                		
				                        <div class="row">
				                            <div class="col-lg-12">
				                                <div class="form-group">
				                                    <label for="{{ form.name.for_label" class="control-label">Name</label>
				                                    <input class="form-control  category-form-control target semi-bold" id="name" maxlength="100" name="name" type="text"  
				                                    					value="<?php if (isset($_POST['name'])) {
			                                                                    echo $_POST['name'];
			                                                                } else if (isset($recb['name'])) {
			                                                                    echo $recb['name'];
			                                                                } ?>">
				                                </div>
				                            </div>
				                          
				                        </div>
									
				                        <div class="row">
			                                <div class="form-group">
			                                    <label for="{{ form.description.for_label" class="control-label">Description</label>
			                                    <textarea class="form-control  contact-form-control target" cols="40" id="description" maxlength="2500" name="description" rows="5"><?php 
														if (isset($_POST['description'])) {
                                                            echo $_POST['description'];
                                                        } else if (isset($recb['description'])) {
                                                            echo $recb['description'];
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