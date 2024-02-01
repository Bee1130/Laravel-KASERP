<?php
//phpinfo();
/* * **-------------------------------------------------------------------**************************    

  Purpose     : 	Buyer Information Detail Page

  Project 	:	Sales Quote DB

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
    $client_id = 0;
    $buyer = "select * from contacts_info where name='".mysqli_real_escape_string($con, $_POST['author'])."'";
	$resb = mysqli_query($con, $buyer) or die(mysqli_error($con) . "11");
	if ($recb = mysqli_fetch_assoc($resb))
	{
		$client_id = $recb['auto_id'];
	}
	
    $sql_ins = sprintf("insert into quotes_info (author,quote,date_time,client_id) values ('%s','%s',sysdate(),'%d')",mysqli_real_escape_string($con, $_POST['author']), mysqli_real_escape_string($con, $_POST['quote']),$client_id);   
    mysqli_query($con, $sql_ins) or die(mysqli_error($con));
  
  	$auto_id = mysqli_insert_id($con);
  	
    // insert action logs into system_log_info
	$sql_log = sprintf("insert into system_log_info (action,agent,query,log_time) values ('%s','%s','%s',sysdate())","Insert new Quote : quote_new.php ",$_SESSION['user_login'],mysqli_real_escape_string($con, $sql_ins));
	mysqli_query($con, $sql_log) or die(mysqli_error($con));	
    
     // Save log
	saveLog($auto_id,'Quote','Quote','added');
	
    header("Location: quotes.php");
    exit();
}



if ($_POST['Submit'] == "Cancel") {
	
    header("Location: quotes.php");
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
					<li><a href="quote.php?rid=<?php echo $next_id;?>">Previous</a></li>	
					<?php
					}
                ?>
              
                <?php
                	if ($prev_id != -1)
                	{
                	?>
					<li><a href="quote.php?rid=<?php echo $prev_id;?>">Next</a></li>	
					<?php
					}
                ?>  
       			</ol>
				<h2 style="margin-top:0px">&nbsp;&nbsp;Quote</h2>
				
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
			               
		                	<form method="POST" action="quote_new.php">
			                    
					            <div id="collapseMain"  class="panel-collapse collapse in">
				                	<div class="panel-body" id="Main">	
				                        <div class="row">
				                			 <div class="col-lg-4 col-sm-4">
				                                <div class="form-group">
				                                    <label for="{{ form.email.for_label" class="control-label">Author</label>
				                                    <input class="form-control contact-form-control target semi-bold" id="author" maxlength="100" name="author" type="text"  
				                                    					value="<?php if (isset($_POST['author'])) {
			                                                                    echo $_POST['author'];
			                                                                } else if (isset($recb['author'])) {
			                                                                    echo $recb['author'];
			                                                                } ?>">
				                                </div>
				                            </div>
				                		</div>
				                		
				                        <div class="row">
			                                <div class="form-group">
			                                    <label for="{{ form.quote.for_label" class="control-label">Quote</label>
			                                    <textarea class="form-control  contact-form-control target" cols="40" id="quote" maxlength="2500" name="quote" rows="5"><?php 
														if (isset($_POST['quote'])) {
                                                            echo $_POST['quote'];
                                                        } else if (isset($recb['quote'])) {
                                                            echo $recb['quote'];
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