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
		
		
    
        <style type="text/css">
			.table-striped>tbody>tr:nth-child(odd)>td, .table-striped>tbody>tr:nth-child(odd)>th 
			{
			    background: #f5f3ee;
			}
			
			.panel-body {
			    background-color: #f5f3ef !important;
			}
			
			.panel-heading {
			    background-color: #f5f3ef !important;
			}
			
			.btn {
			    color: green;
			    background-color: #efece4 !important;
			    padding-left: 20px;
			    padding-right: 20px;
			    border-radius: 5px;
			}
			
			.btn:hover {
			    color: orange;
			}
			
			.panel-title {
			    color: #f6f4ef;
			}
			
			
		</style> 
    </head>
    <body>
		<?php
			$rid = isset($_GET['rid'])?$_GET['rid']: '';
			// var_dump($rid);die;
		?>
    	<div>	    	
	    	<input type="hidden" name="sel_customer_id" id ="sel_customer_id" value="<?php echo $_SESSION['user_login'].':' .$rid?>">	
    	</div>    
    	<div class="container">
    	    <?php include("sidebar.php"); ?>
    	    <div class="main-content">
        		<div class="container">
        		    <?php include('menu.php'); ?>
        			<div id="my-main-content">				
        				<br>
        				<div class="row">            		
                    		<div class="col-md-12 col-lg-12">  
                                <form class="form-horizontal" method="post" enctype="multipart/form-data" action="schedule.php" style="margin-bottom: 10px;">
            	            		<br>    		
            	        			<div class="row">			               
            			                <div class="col-md-12">			                  
            			                    <div class="panel panel-inverse">
            			                        <div class="panel-heading">
            			                            <h4 class="panel-title">
            			                            	Schedule <a href="#" onclick="javascript:newEvent()" style="color:white"><button class="btn" type="button" name="btn_add_schedule" id="btn_add_schedule" style="font-weight: bold;font-size: 14px">+</button></a> 					                
            				                            <div class="panel-heading-btn" style="padding-top: 2px">
            						                        <a href="#" id="panel-fullscreen-Main" role="button"  class="btn btn-icon btn-circle" title="Toggle fullscreen"><i class="fa fa-expand"></i></a>	
            						                               
            								       		</div>
            								       		<div class="pull-right">
            												<a href="export_schedule.php" target="_blank" style="text-decoration: none;color:white"><button class="btn" type="button" name="btn_export_schedule" id="btn_export_schedule" style="font-size: 14px;font-weight: 400">Export</button></a>  
            					                   		</div>
            								       	</h4>
            			                        </div>
            			                        <div class="panel-body">
            			                           <div id="calendar" class="calendar fc fc-ltr">
            			                           </div>
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
        </div>
		<script type="text/javascript" src="js/fullcalendar/fullcalendar.js"></script>
		
		

		<script type="text/javascript">
			function editEvent(event_id,start,end,description)
			{		
				console.log(event_id);		
				$("#event_id").val(event_id);
				$("#event_start").val(start);
                $("#event_description").val(description);
                $("#event_end").val(end);
		        $("#dialog_create_event").modal();		
				
			}
			$(document).ready(function() {
		        var date = new Date();
		        var d = date.getDate();
		        var m = date.getMonth();
		        var y = date.getFullYear();

		        var formatDate = function(dateString) {
		            var parsedDate = $.fullCalendar.parseDate(dateString);
		            
		            return $.fullCalendar.formatDate(parsedDate, 'MMM dd, yyyy, HH:mm tt');
		        }

		        var calendar = $('#calendar').fullCalendar({
		        	header: {
		                left: 'prev,next today',
		                center: 'title',
		                right: 'month,agendaWeek,agendaDay'
		            },
					
				    defaultView: 'month',
					eventRender: function(event, element, calEvent) {
						mediaObject = '<i class="fa fa-clock-o"></i>';
						var description = (event.description) ? event.description : '';
			            element.find(".fc-event-title").after($("<span class=\"fc-event-icons\"></span>").html(mediaObject));
			            element.find(".fc-event-title").append('<small>'+ description +'</small>');
			            element.attr("data-ajax", "true");
			            element.attr("data-success", "loadContent");
			        },
					events: 'getReminders.php',
					
		            select: function(start, end, allDay) {
		            	console.log(allDay);
		            	$("#dialog_create_event").modal();		                
		                $("#event_start").val(formatDate(start));
		               // $("#event_all_day").val(allDay);
		                $("#event_end").val(formatDate(end));
		            },
		            eventClick: function(event) {
		            	console.log('event click');
		            	$("#dialog_create_event").modal();	
		            },
		            firstHour: 08,
		            
		            aspectRatio: 1.5,
		            selectable: true,
		            selectHelper: true,
		            editable: true,
		            droppable: true,
		             editable: true,
		             
		              eventStartEditable: true
		        });
		       
		        $("#panel-fullscreen-Main").click(function (e) {
			        $(this).closest('.panel').toggleClass('panel-fullscreen');
			    });
			    
			
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
				console.log("ChangeDisplayCnt");td
				$("#btnPage").click();
			}
        </script>  
    </body>
</html>

		