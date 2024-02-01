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

if (isset($_GET['type']))
{
	
	if (isset($_GET['mail_rcvr']))
	{
		$mail_rcvr = trim($_GET['mail_rcvr']);
		if ($_GET['type'] == 'inbox')	
		{
			$uid = $_GET['uid'];
			$sql_select = sprintf("select * from mail_inbox_info where mail_uid = '%d' and mail_rcvr like ('%s')",$uid,$mail_rcvr);
			$result = mysqli_query($con,$sql_select) or die(mysqli_error($con));
			if ($seerec = mysqli_fetch_assoc($result))
			{								    
				$from_info = str_replace('<','(',$seerec['from_nm']);
				$from_info = str_replace('>',')',$from_info);
				
				$to_info = $seerec['to_name'].' ('.$seerec['mail_rcvr'].')';
				$date = $seerec['log_time'];
				$body = $seerec['mail_body'];
				$subject = $seerec['mail_subject'];
				
				$attach_files = $seerec['attach_files'];
			}

		}else if ($_GET['type'] == 'junk')	
		{
			$uid = $_GET['uid'];
			$sql_select = sprintf("select * from mail_junk_info where mail_uid = '%d' and mail_rcvr like ('%s')",$uid,$mail_rcvr);
			$result = mysqli_query($con,$sql_select) or die(mysqli_error($con));
			if ($seerec = mysqli_fetch_assoc($result))
			{								    
				$from_info = str_replace('<','(',$seerec['from_nm']);
				$from_info = str_replace('>',')',$from_info);
				
				$to_info = $seerec['to_name'].' ('.$seerec['mail_rcvr'].')';
				$date = $seerec['log_time'];
				$body = $seerec['mail_body'];
				$subject = $seerec['mail_subject'];
				
				$attach_files = $seerec['attach_files'];
			}
		}
	}
	
}

if ($_POST['Submit'] == "Back") {
	
    header("Location: inbox.php");
    exit();
	
}	

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
    <head>
        <?php include("header.php");?>
		<script type="text/javascript">
		
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
			.vertical-box-column {
			    display: table-cell;
			    vertical-align: top;
			    height: 100%;
			}
			
			.p-15, .wrapper {
			    padding: 15px!important;
			}
			.email-btn-row {
			    margin-bottom: 15px;
			}
			.email-content {
			    background: #fff;
			    padding: 15px;
			}
			.underline {
			    border-bottom: 1px solid #e2e7eb!important;
			}

			.p-b-10 {
			    padding-bottom: 10px!important;
			}

			.m-b-15 {
			    margin-bottom: 15px!important;
			}

			.m-t-0 {
			    margin-top: 0!important;
			}

			h1, h2, h3, h4, h5, h6 {
			    font-weight: 500;
			    color: #242a30;
			}
			.m-b-20 {
			    margin-bottom: 20px!important;
			}
			.attached-document {
			    margin: 15px 0 0;
			    padding: 0;
			}
			
			.attached-document, .chats, .registered-users-list, .result-list, .sidebar .sub-menu, .theme-panel .theme-list, .timeline, .todolist, .top-menu .nav .sub-menu, .widget-chart-sidebar .chart-legend {
			    list-style-type: none;
			}
		</style>
    </head>
    <body>
    	<?php include("menu.php");?>
    	
		<!-- main content -->
		<div class="container">
  			
  			<div id="my-main-content">  		
  				
            	<div class="my-home-content">     
            		<form class="form-horizontal" method="post" enctype="multipart/form-data" action="mail_detail.php" style="margin-bottom: 10px;">
            			    		
	        			<div class="row">
	        				
	            			<div class="vertical-box-column p-15"> 
			                    <div class="email-btn-row">
			                    	<button type="submit" name="Submit" value="Back" class="btn btn-sm  btn-primary">Back</button>
			                    </div>
			                    <div class="email-content">
			                        <h4 class="m-b-15 m-t-0 p-b-10 underline"><?php echo $subject; ?></h4>
			                        <ul class="media-list underline m-b-20 p-b-15">
			                            <li class="media media-sm clearfix">
			                                <div class="media-body">
			                                    <span class="email-from f-w-600">
			                                        From: <?php echo $from_info; ?>
			                                    </span><br>
			                                    <span class="email-to">
			                                        To: <?php echo $to_info; ?>
			                                    </span><br>
			                                    <p></p>
			                                    <span class="text-muted">
			                                        <i class="fa fa-clock-o fa-fw"></i><?php echo $date; ?>
			                                    </span>
			                                </div>
			                            </li>
			                        </ul>
			                        <ul class="attached-document clearfix">
			                            
			                        </ul>
			                        <span style="white-space:pre-wrap"><?php echo $body; ?></span>
			                        <span style="white-space:pre-wrap"><?php echo $attach_files; ?></span>
										
			                    </div>
			                </div>					
						</div>	
					</form>				    
						
            	</div>	
            </div>
		</div>            
		
    </body>
  
</html>
