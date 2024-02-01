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

$next_id = -1;
$prev_id = -1;	    	
/********************buyer information select start***************************** */
if (isset($_GET['rid']))
{
	$buyer = "select * from quotes_info where auto_id=".$_GET['rid'];
	$resb = mysqli_query($con, $buyer) or die(mysqli_error($con) . "11");
	$recb = mysqli_fetch_assoc($resb);
	
	if ($recb)
	{
		$sql_previous = "SELECT auto_id FROM quotes_info WHERE auto_id<" . $recb['auto_id'] ." order by auto_id desc LIMIT 1";
		$result_previous = mysqli_query($con, $sql_previous) or die(mysqli_error($con));
		if ($row_previous = mysqli_fetch_assoc($result_previous))
			$next_id = $row_previous['auto_id'];

		$sql_next = "SELECT auto_id FROM quotes_info WHERE auto_id>" . $recb['auto_id']. " LIMIT 1";
		$result_next = mysqli_query($con, $sql_next) or die(mysqli_error($con));
		if ($row_next = mysqli_fetch_assoc($result_next))
			$prev_id = $row_next['auto_id'];
			
		if (isset($_GET['notify']))
		{
		
			// Set notification as viewed for log info
			$sql_upd = sprintf("update log_info set is_viewed='%d' where is_viewed='%d' and customer_id='%d' and agent != '%s'",1,0,$_GET['rid'],$_SESSION['user_login']);
			$res=mysqli_query($con, $sql_upd) or die(mysqli_error($con)."11");
			
			$sql_log = sprintf("insert into system_log_info (action,agent,query,log_time) values ('%s','%s','%s',sysdate())","Update log info for viewed notification : quote.php ",$_SESSION['user_login'],mysqli_real_escape_string($con,$sql_upd));
			mysqli_query($con, $sql_log) or die(mysqli_error($con));	
		}
	}
	
	
}

if ($_POST['Submit'] == "Save") 
{
    $client_id = 0;
    $buyer = "select * from contacts_info where name='".mysqli_real_escape_string($con,$_POST['author'])."'";
	$resb = mysqli_query($con, $buyer) or die(mysqli_error($con) . "11");
	if ($recb = mysqli_fetch_assoc($resb))
	{
		$client_id = $recb['auto_id'];
	}
    
    $sql_upd = "update quotes_info set 
 					  author = '" . mysqli_real_escape_string($con,$_POST['author']) . "',
 					  quote = '" . mysqli_real_escape_string($con,$_POST['quote']) . "',
 					  client_id = " . $client_id. ",
 					  date_time = sysdate()										  
			  where auto_id =" . $_GET['rid'] . "";
   
    mysqli_query($con, $sql_upd) or die(mysqli_error($con));
    
   
     // insert action logs into system_log_info
	$sql_log = sprintf("insert into system_log_info (action,agent,query,log_time) values ('%s','%s','%s',sysdate())","Update Quote : quote.php ",$_SESSION['user_login'],mysqli_real_escape_string($con,$sql_upd));
	mysqli_query($con, $sql_log) or die(mysqli_error($con));	
	
	// Save log
	saveLog($_GET['rid'],'Quote','Quote','changed');
	
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
			               
		                	<form method="POST" action="<?php echo 'quote.php?rid='.$_GET['rid']; ?>">
			                    
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