<?php
/* * **-------------------------------------------------------------------**************************    

  Purpose 	: 	Where user can search the buyer detail

  Project 	:	Sales Contact DB

  Developer 	: 	Kelvin 

  Create Date : 	30/11/2015

 * ***-------------------------------------------------------------------*********************** */
//phpinfo();
@session_start();	
if (! isset($_COOKIE['cookie_login']) and !isset($_SESSION['user_login'])) {//session store admin name
    header("Location: index.php"); //login in AdminLogin.php
}
require_once("includes/dbconnect.php");


?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
    <head>
        <?php include("header.php");?>
        <link href='css/fullcalendar/fullcalendar.css' rel='stylesheet' />
		<link href='css/fullcalendar/fullcalendar.print.css' rel='stylesheet' media='print' />		
		<script type="text/javascript" src="js/fullcalendar/fullcalendar.js"></script>
		<script type="text/javascript">
			$(document).ready(function() {
		       
		       
		    });
			
        
			/**
			* Search option			
			*/
			function ChangeSearchValue()
			{
				document.getElementById('val').value = document.getElementById('search_value').value;
				console.log(document.getElementById('val').value);
				$('#btnSearch').click();
			}
			
			/**
			* Change records count per pagge
			*/
			function ChangeDisplayCnt()
			{
				console.log("ChangeDisplayCnt");
				$("#btnPage").click();
			}
        </script>      
        <style type="text/css">
			.table-striped>tbody>tr:nth-child(odd)>td, .table-striped>tbody>tr:nth-child(odd)>th 
			{
			    background: #f0f3f5;
			}
		</style> 
    </head>
    <body>
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
        				
                    	<div class="my-home-content">     
                    		<form class="form-horizontal" method="post" enctype="multipart/form-data" action="schedule.php" style="margin-bottom: 10px;">
                    			<!--<div class="row" style="marign:0px;margin-top: 10px">
                    				<center><h2>Google Calendar</h2></center>
                    			</div>-->
        	            		
        	            		<br>    		
        	        			<div class="row">
        			               
        			                <div class="col-md-12">
        			                  
        			                    <div class="panel panel-inverse">
        			                        <div class="panel-heading">
        			                            <h4 class="panel-title">Google Calendar</h4>
        			                        </div>
        			                        <div class="panel-body" align="center">
        			                        	
        			                        	<?php 
        			                        		if (isset($_SESSION['google_calendar_iframe']))
        			                        			echo $_SESSION['google_calendar_iframe'];
        			                        	?>
        			                        	
        			                          	<!-- <iframe src="https://calendar.google.com/calendar/embed?height=800&amp;wkst=1&amp;bgcolor=%23FFFFFF&amp;src=dariuzrubin%40gmail.com&amp;color=%231B887A&amp;ctz=America%2FNew_York" style="border-width:0;width: 100%;" height="650" frameborder="0" scrolling="no"></iframe> -->
        			                        </div>
        			                    </div>
        			                    <!-- end panel -->
        			                </div>
        			                <!-- end col-12 -->
        			            </div>
        					</form>				    
        							
                    	</div>	
                    </div>
        		</div>
        	</div>
        </div>
    </body>
</html>

		