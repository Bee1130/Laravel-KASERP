<?php
//phpinfo();
/* * **-------------------------------------------------------------------**************************    

  Purpose     : 	Buyer Information Detail Page

  Project 	:	Sales Supplier DB

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
	$buyer = "select * from suppliers_info where auto_id=".$_GET['rid'];
	$resb = mysqli_query($con,$buyer) or die(mysqli_error($con) . "11");
	$recb = mysqli_fetch_assoc($resb);
	
	
	$sql_previous = "SELECT auto_id FROM suppliers_info WHERE auto_id<" . $recb['auto_id'] . $_SESSION['filter_user']." order by auto_id desc LIMIT 1";
	$result_previous = mysqli_query($con,$sql_previous) or die(mysqli_error($con));
	if ($row_previous = mysqli_fetch_assoc($result_previous))
		$next_id = $row_previous['auto_id'];

	$sql_next = "SELECT auto_id FROM suppliers_info WHERE auto_id>" . $recb['auto_id'] .$_SESSION['filter_user']. " LIMIT 1";
	$result_next = mysqli_query($con,$sql_next) or die(mysqli_error($con));
	if ($row_next = mysqli_fetch_assoc($result_next))
		$prev_id = $row_next['auto_id'];
		
	if (isset($_GET['notify']))
	{
	
		// Set notification as viewed for log info
		$sql_upd = sprintf("update log_info set is_viewed='%d' where is_viewed='%d' and customer_id='%d' and agent != '%s'",1,0,$_GET['rid'],$_SESSION['user_login']);
		$res=mysqli_query($con,$sql_upd) or die(mysqli_error($con)."11");
		
		$sql_log = sprintf("insert into system_log_info (action,agent,query,log_time) values ('%s','%s','%s',sysdate())","Update log info for viewed notification : supplier.php ",$_SESSION['user_login'],mysqli_real_escape_string($con,$sql_upd));
		mysqli_query($con,$sql_log) or die(mysqli_error($con));	
	}
}

$submit = null;
if(isset($_POST['Submit'])) {
	$submit = $_POST['Submit'];
}

if ($submit == "Save") 
{
	
	$Destination = 'userprofile/userfiles/avatars';
    if(!isset($_FILES['ImageFile']) || !is_uploaded_file($_FILES['ImageFile']['tmp_name'])){
    	$prev_avatar = "";
    	$sql_sel_avatar="select user_avatar from suppliers_info WHERE auto_id = ".$_GET['rid'];
    	$sql_sel_res = mysqli_query($con,$sql_sel_avatar) or die(mysqli_error($con)); 
    	if ($sql_sel_rec = mysqli_fetch_assoc($sql_sel_res))
    	{
			$prev_avatar = $sql_sel_rec['user_avatar'];
			
		}
    	if (strlen($prev_avatar) == 0)
    	{
			$NewImageName= 'default.jpg';
        	move_uploaded_file($_FILES['ImageFile']['tmp_name'], "$Destination/$NewImageName");	
        	$sql_upd_avatar="UPDATE suppliers_info SET user_avatar='$NewImageName' WHERE auto_id = ".$_GET['rid'];
	    	$sql_upd_res = mysqli_query($con,$sql_upd_avatar) or die(mysqli_error($con)); 
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
        
        $sql_upd_avatar="UPDATE suppliers_info SET user_avatar='$NewImageName' WHERE auto_id = ".$_GET['rid'];
    	$sql_upd_res = mysqli_query($con,$sql_upd_avatar) or die(mysqli_error($con)); 
    }
    
   
    
	$_POST['phone_number'] = my_phone_format($_POST['phone_number']);
   
    
    $sql_upd = "update suppliers_info set 
 					  name = '" . mysqli_real_escape_string($con,$_POST['name']) . "',
 					  phone_number = '" . $_POST['phone_number'] . "', 					
 					  email = '" . mysqli_real_escape_string($con,$_POST['email']) . "',
 					  address = '" . mysqli_real_escape_string($con,$_POST['address']) . "', 					  					 
					  cust_upd_dt= curdate()										  
			  where auto_id =" . $_GET['rid'] . "";
   
    mysqli_query($con,$sql_upd) or die(mysqli_error($con));
    
   
     // insert action logs into system_log_info
	$sql_log = sprintf("insert into system_log_info (action,agent,query,log_time) values ('%s','%s','%s',sysdate())","Update Supplier : supplier.php ",$_SESSION['user_login'],mysqli_real_escape_string($con,$sql_upd));
	mysqli_query($con,$sql_log) or die(mysqli_error($con));	
	
	// Save log
	saveLog($_GET['rid'],'Supplier','Supplier','changed');
	
    header("Location: supplier.php?rid=" . $_GET['rid']);
    exit();
      
}


if ($submit == "Cancel") {
	
    header("Location: suppliers.php");
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
					<li><a href="supplier.php?rid=<?php echo $next_id;?>">Previous</a></li>	
					<?php
					}
                ?>
              
                <?php
                	if ($prev_id != -1)
                	{
                	?>
					<li><a href="supplier.php?rid=<?php echo $prev_id;?>">Next</a></li>	
					<?php
					}
                ?>  
       			</ol>
				<h2 style="margin-top:0px">&nbsp;&nbsp;Supplier</h2>
				
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
			               
		                	
			                <form action="<?php echo 'supplier.php?rid='.$_GET['rid']; ?>" method="post" enctype="multipart/form-data" id="UploadForm">
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
				                                    <input class="form-control  supplier-form-control target semi-bold" id="name" maxlength="100" name="name" type="text"  
				                                    					value="<?php if (isset($_POST['name'])) {
			                                                                    echo $_POST['name'];
			                                                                } else if (isset($recb['name'])) {
			                                                                    echo $recb['name'];
			                                                                } ?>">
				                                </div>
				                            </div>
				                            <div class="col-lg-3">
				                                <div class="form-group">
				                                    <label for="{{ form.phone_number.for_label" class="control-label">Phone</label>
				                                    <input class="form-control  supplier-form-control target semi-bold" id="phone_number" maxlength="100" name="phone_number" type="text"  
				                                    					value="<?php if (isset($_POST['phone_number'])) {
			                                                                    echo my_phone_format2($_POST['phone_number']);
			                                                                } else if (isset($recb['phone_number'])) {
			                                                                    echo my_phone_format2($recb['phone_number']);
			                                                                } ?>">
				                                </div>
				                            </div>
				                           
				                            <div class="col-lg-4">
				                                <div class="form-group">
				                                    <label for="{{ form.email.for_label" class="control-label">Email</label>
				                                    <input class="form-control  supplier-form-control target semi-bold" id="email" maxlength="100" name="email" type="text"  
				                                    					value="<?php if (isset($_POST['email'])) {
			                                                                    echo $_POST['email'];
			                                                                } else if (isset($recb['email'])) {
			                                                                    echo $recb['email'];
			                                                                } ?>">
				                                </div>
				                            </div>
				                        </div>
				                    
				                        <div class="row">
			                                <div class="form-group">
			                                    <label for="{{ form.address.for_label" class="control-label">Google Address</label>
			                                    <textarea class="form-control  supplier-form-control target" cols="40" id="address" maxlength="2500" name="address" rows="5"><?php 
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
			</div>
        </div>
    </body>
</html>