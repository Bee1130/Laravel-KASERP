<?php
//phpinfo();
/* * **-------------------------------------------------------------------**************************    

  Purpose     : 	Buyer Information Detail Page

  Project 	:	Sales Material DB

  Developer 	: 	Wilson Tan

  Create Date : 	05/10/2016

 * ***-------------------------------------------------------------------*********************** */
session_start();
if (!isset($_SESSION['user_login']) and ! isset($_COOKIE['cookie_login'])) {//session store admin name
    header("Location: index.php"); //login in AdminLogin.php
}
require_once("includes/dbconnect.php");
	
$submit = null;
if(isset($_POST['Submit'])) {
	$submit = $_POST['Submit'];
}

if ($submit == "Save") 
{
    $_POST['unit'] = getRealValue($_POST['unit']);   
    $_POST['price_net'] = getRealValue($_POST['price_net']);   
    
    $sql_ins = sprintf("insert into materials_info (name,code,category,unit,price_net,supplier,description,inc_vat) values ('%s','%s','%s','%f','%f','%s','%s','%s')",mysqli_real_escape_string($con,$_POST['name']),mysqli_real_escape_string($con,$_POST['code']), mysqli_real_escape_string($con,$_POST['category']), $_POST['unit'],$_POST['price_net'], mysqli_real_escape_string($con,$_POST['supplier']), mysqli_real_escape_string($con,$_POST['description']), mysqli_real_escape_string($con,$_POST['inc_vat']));   
    mysqli_query($con,$sql_ins) or die(mysqli_error($con));
  
  	$auto_id = mysqli_insert_id($con);
  	
  	$Destination = 'userprofile/userfiles/avatars';
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
    
    $sql_upd_avatar="UPDATE materials_info SET picture='$NewImageName' WHERE auto_id = ".$auto_id;
    $sql_upd_res = mysqli_query($con,$sql_upd_avatar) or die(mysqli_error($con)); 
    
    // insert action logs into system_log_info
	$sql_log = sprintf("insert into system_log_info (action,agent,query,log_time) values ('%s','%s','%s',sysdate())","Insert new Material : material_new.php ",$_SESSION['user_login'],mysqli_real_escape_string($con,$sql_ins));
	mysqli_query($con,$sql_log) or die(mysqli_error($con));	
    
     // Save log
	saveLog($auto_id,'Material','Material','added');
	
    header("Location: materials.php");
    exit();
}


if ($submit == "Cancel") {
    header("Location: materials.php");
    exit();
}	

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
    <head>
    	<?php include("header.php");?>      
		<script type="text/javascript" src="js/jquery.cookie.js"></script>
	  
    </head>
    <body>
        <script type="text/javascript" src="popcalendar.js"></script>
        
    	<div class="container">
    	    <?php include("sidebar.php"); ?>
    	    <div class="main-content">
        		<div class="container">
        		    <?php include('menu.php'); ?>			
        			<div id="my-main-content">				
        				<br>
        				
        				<h2 style="margin-top:0px">&nbsp;&nbsp;Material</h2>
        				<!-- Main content -->
        				<div class="row">            		
                    		<div class="col-md-12 col-lg-12">   	
        			            <div class="panel panel-inverse" data-sortable-id="ui-general-1">
        			                <div class="panel-heading">
        			                	<h4 class="panel-title">
        							        <a data-toggle="collapse" data-target="#collapseMain" 
        							           href="#collapseMain" class="collapsed">Main</a>
        							    </h4>
        			                </div>
        			                <div id="collapseMain"  class="panel-collapse collapse in">
        			                	<form action="material_new.php" method="post" enctype="multipart/form-data" id="UploadForm">
        				                	<div class="panel-body" id="Main">	
        				                		<div class="row">
        				                			<div class="form-group">
        							                    <div  class="col-lg-5" style="max-width: 170px">
        							                        <div class="shortpreview">
        							                        	<label for="{{ form.uploadFile.for_label" class="control-label">Picture</label>
        							                            <br> 
        							                            <img src="userprofile/userfiles/avatars/<?php 
        							                            					if (isset($recb['picture'])) 
        							                            						echo $recb['picture'];
        							                            					else 
        							                            						echo 'default.jpg';?>" alt="" class="img-thumbnail" style="max-width: 150px">
        							                            <input name="ImageFile" type="file" id="uploadFile"/>
        							                        </div>
        							                    </div>
        											</div>
        				                		</div>
        				                         <div class="row">
        				                            <div class="col-lg-12">
        				                                <div class="form-group">
        				                                    <label for="{{ form.name.for_label" class="control-label">Name</label>
        				                                    <input class="form-control  material-form-control target semi-bold" id="name" maxlength="100" name="name" type="text"  
        				                                    					value="<?php if (isset($_POST['name'])) {
        			                                                                    echo $_POST['name'];
        			                                                                } else if (isset($recb['name'])) {
        			                                                                    echo $recb['name'];
        			                                                                } ?>">
        				                                </div>
        				                            </div>
        				                          
        				                        </div>
        										<div class="row">
        											<div class="col-lg-4">
        				                                <div class="form-group">
        				                                    <label for="{{ form.code.for_label" class="control-label">Item Code</label>
        				                                    <input class="form-control  material-form-control target semi-bold" id="code" maxlength="100" name="code" type="text"  
        				                                    					value="<?php if (isset($_POST['code'])) {
        			                                                                    echo $_POST['code'];
        			                                                                } else if (isset($recb['code'])) {
        			                                                                    echo $recb['code'];
        			                                                                } ?>">
        				                                </div>
        				                            </div>
        				                             <div class="col-lg-4">
        		                                        <div class="form-group">
        		                                            <label for="{{ form.price_net.for_label" class="control-label">Price Net</label>
        		                                            <input class="form-control  material-form-control target" id="price_net" maxlength="50" name="price_net" type="text" value="<?php 
        															 if (isset($_POST['price_net'])) {
        	                                                                    echo $_POST['price_net'];
        	                                                                } else if (isset($recb['price_net'])) {
        	                                                                    echo $recb['price_net'];
        	                                                                } ?>">
        		                                        </div>
        		                                    </div>
        				                        	<div class="col-lg-4">
        		                                        <div class="form-group">
        		                                            <label for="{{ form.unit.for_label" class="control-label">Unit</label>
        		                                            <input class="form-control  material-form-control target" id="unit" maxlength="50" name="unit" type="text" value="<?php 
        															 if (isset($_POST['unit'])) {
        	                                                                    echo $_POST['unit'];
        	                                                                } else if (isset($recb['unit'])) {
        	                                                                    echo $recb['unit'];
        	                                                                } ?>">
        		                                        </div>
        		                                    </div>
        		                                   
        		                                   
        		                                </div>
        				                        <div class="row">
        				                        	<div class="col-lg-4">
        		                                        <div class="form-group">
        		                                            <label for="{{ form.inc_vat.for_label" class="control-label">Inc VAT</label>
        		                                            <input class="form-control  material-form-control target" id="inc_vat" maxlength="50" name="inc_vat" type="text" value="<?php 
        															 if (isset($_POST['inc_vat'])) {
        	                                                                    echo $_POST['inc_vat'];
        	                                                                } else if (isset($recb['inc_vat'])) {
        	                                                                    echo $recb['inc_vat'];
        	                                                                } ?>">
        		                                        </div>
        		                                    </div>
        				                        	<div class="col-lg-4">
														<?php
														$sql_sel_status = "select * from categories_info";
														$sql_res_status = mysqli_query($con,$sql_sel_status) or die(mysqli_error($con) . "go select error");
														?>
        				                        		<div class="form-group">
        				                                  	<label for="{{ form.category.for_label" class="control-label">Item Type</label>
        				                                    <select class="form-control  contact-form-control target" id="category" name="category">
        		                                            	<?php
        					                                    	
        		                                                    while ($sql_rec_status = mysqli_fetch_assoc($sql_res_status)) 
        		                                                    {
        		                                                    	$auto_id = $sql_rec_status['auto_id'];
        		                                                    	$name = $sql_rec_status['name'];
        		                                                    ?>
        		                                                    	<option value="<?php echo $name;?>" <?php 
        																		if ((isset($_POST['category']) ? $_POST['category'] : '') == $name) {
        																				echo 'selected';
        																		} else if ((isset($recb['category']))?$recb['category']:'' == $name) {
        																			echo 'selected';
        																		} ?>><?php echo $name;?></option>
        		                                                	<?php
        		                                                    }
        					                                    ?>
        													</select>
        			                               		</div>
        				                        	</div>
        				                        	<div class="col-lg-4">
        				                        		<div class="form-group">
        				                                  	<label for="{{ form.supplier.for_label" class="control-label">Supplier</label>
        				                                    <select class="form-control  contact-form-control target" id="supplier" name="supplier">
        		                                            	<?php
        					                                    	$sql_sel_status = "select * from suppliers_info";
        		                                                    $sql_res_status = mysqli_query($con,$sql_sel_status) or die(mysqli_error($con) . "go select error");
        		                                                    while ($sql_rec_status = mysqli_fetch_assoc($sql_res_status)) 
        		                                                    {
        		                                                    	$auto_id = $sql_rec_status['auto_id'];
        		                                                    	$name = $sql_rec_status['name'];
        		                                                    ?>
        		                                                    	<option value="<?php echo $name;?>" <?php 
        																		if ((isset($_POST['supplier']))?$_POST['supplier']:'' == $name) {
        																				echo 'selected';
        																		} else if (isset($recb['supplier'])?$recb['supplier']:'' == $name) {
        																			echo 'selected';
        																		} ?>><?php echo $name;?></option>
        		                                                	<?php
        		                                                    }
        					                                    ?>
        													</select>
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
        							    </form>                
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