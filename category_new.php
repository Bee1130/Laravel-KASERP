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
if ($_POST['Submit'] == "Save") 
{ 
    
    $sql_ins = sprintf("insert into categories_info (name,description) values ('%s','%s')",mysqli_real_escape_string($con, $_POST['name']), mysqli_real_escape_string($con, $_POST['description']));   
    mysqli_query($con, $sql_ins) or die(mysqli_error($con));
  
    // insert action logs into system_log_info
	$sql_log = sprintf("insert into system_log_info (action,agent,query,log_time) values ('%s','%s','%s',sysdate())","Insert new Category : category_new.php ",$_SESSION['user_login'],mysqli_real_escape_string($con, $sql_ins));
	mysqli_query($con, $sql_log) or die(mysqli_error($con));	
    
     // Save log
	saveLog($auto_id,'Category','Category','added');
	
    header("Location: categories.php");
    exit();
}


if ($_POST['Submit'] == "Cancel") {
    header("Location: categories.php");
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
        				
        				<h2 style="margin-top:0px">&nbsp;&nbsp;Category</h2>
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
        			                	<form action="category_new.php" method="post" enctype="multipart/form-data" id="UploadForm">
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