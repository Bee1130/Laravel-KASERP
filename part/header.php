<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<meta name="description" content="">
<link rel="shortcut icon" href="images/fav.png">
<title>Kas ERP</title>
<link rel="stylesheet" href="assets/css/dashlite.css">

<script type="text/javascript" src="assets/js/bundle.js"></script>
<script type="text/javascript" src="assets/js/scripts.js"></script>

<link href="css/jquery.datetimepicker.css" rel="stylesheet" />
<script src="js/jquery.datetimepicker.js"></script>

<script type="text/javascript">
	function OnSearch()
	{
		alert("adf");
		console.log('OnSearch');
		var search_val = document.getElementById('search_value').value;
		console.log(search_val);
		//window.location.href="searchresult.php?val="+search_val;	  
	}
	
	function newEvent()
	{
    	console.log('newEvent');
    	$("#dialog_create_event").modal();		                
        /*$("#event_start").val();
       
        $("#event_end").val();		*/
	   
    }
			
	$(document).ready(function () { 
		setInterval(getNotification,1000*60);
		getNotification();
		function getNotification() {
			console.log("getNotification");
			var d = new Date()
		    var off_az = -7;
			var utc = d.getTime()+(d.getTimezoneOffset()*60000);
			
			var gmt = new Date(utc+(3600000*(1)));
			
			gmpt_str = toTimeString(gmt);
			
			document.getElementById("New_York_z161").innerHTML = gmpt_str;
			
			    
		   	$.ajax({
				type:"POST",
				dataType : "json",
				url : "getNotification.php",						
				success : function(res){
					console.log(res);				
					/*$("#live_notify_list li").remove();
					if (res.status == "Success")
					{
					   	cnt = parseInt(res.cnt);	
					   	console.log(cnt);
					   	var str = "";		
					   	if (cnt>0)
					   	{
					   		$("#live_notify_badge").addClass("bg-red");	
					   		$("#live_notify_badge").text(cnt);
					   		str += "<li class='dropdown-header text-center'>Notifications ("+cnt+")</li>";
					   		for (var i=res.res_data.length-1;i>=0;i--)
					   		{
								str += '<li class="media">';								
									str += '<a href="'+res.res_url[i]+'" data-ajax="true" data-success="loadContent">';
									str += '<div class="media-left"><i class="fa media-object bg-blue '+res.res_fa_icon[i]+'"></i></div>';
									str += '<div class="media-body">';
										str += '<h6 class="media-heading">'+res.res_data[i]+'</h6>';
										str += '<div class="text-muted f-s-11">'+res.res_time[i]+'</div>';
									str += '</div>';
									str += '</a>';
								str += '</li>';
							}
							$("#live_notify_list").prepend(str);
							
					   	}else
					   	{
					   		
						    $("#live_notify_badge").removeClass("bg-red");	
						    document.getElementById("live_notify_badge").innerHTML=0;
					   	}			
					   
					}*/
				},
				error:function(res)
				{
					console.log(res);
					clearInterval();
				}
		   });
		}

		$('.datetimePicker').datetimepicker(							
	     {
            dayOfWeekStart: 0,
            format: 'M d, Y, h:i a',
            hour: '7:00 AM',
            step: 30,
            formatTime: 'g:i A',
            allowTimes: ['7:00 AM', '7:30 AM', '8:00 AM', '8:30 AM', '9:00 AM', '9:30 AM', '10:00 AM', '10:30 AM', '11:00 AM', '11:30 AM', '12:00 PM', '12:30 PM', '1:00 PM', '1:30 PM', '2:00 PM', '2:30 PM', '3:00 PM', '3:30 PM', '4:00 PM', '4:30 PM', '5:00 PM', '5:30 PM', '6:00 PM', '6:30 PM', '7:00 PM'],
        });
		
		
        
        $("#btnCreateEvent").click(function(){
        	var event_description,event_all_day,event_public;
			console.log("createEvent");
	        if ($("#event_description").val() == '') {
                alert('Pleae enter a title');
                return;
            }
            event_description = $("#event_description").val();
            
            if ($('#event_all_day').is(':checked') == true) {
                event_all_day = 1;
            } else {
                event_all_day = 0;
            }
            if ($('#event_public').is(':checked') == true) {
                event_public = 1;
            } else {
                event_public = 0;
            }
            
            var data = {					
            	"event_start":$("#event_start").val(),
            	"event_id":$("#event_id").val(),
            	"event_end":$("#event_end").val(),
				//"event_public":event_public,
				//"event_all_day":event_all_day,
				"event_description":event_description
			};
            
            $.ajax({
		        url: 'createScheduleEvent.php',
		        data: data,
		        type:"POST",
				dataType : "json",
		        success: function ( res ) {
		        	console.log("Success");
		        	console.log(res);
		        	if (res.status == "Success")
		        	{			
		        		 $("#dialog_create_event").modal('hide');
						location.href='schedule.php';
					}							
		        },
		        error : function(res){
					console.log("fail");
		        	console.log(res);						
				}
		    });			          			
		   
	    });
	    $("#btnDeleteEvent").click(function(){
        	
			console.log("btnDeleteEvent");
	        
            var data = {					
            	
            	"event_id":$("#event_id").val()
            	
			};
            
            $.ajax({
		        url: 'deleteScheduleEvent.php',
		        data: data,
		        type:"POST",
				dataType : "json",
		        success: function ( res ) {
		        	console.log("Success");
		        	console.log(res);
		        	if (res.status == "Success")
		        	{			
		        		$("#dialog_create_event").modal('hide');
						location.href='schedule.php';					
					}							
		        },
		        error : function(res){
					console.log("fail");
		        	console.log(res);						
				}
		    });			          			
		   
	    });
	});
            
    function toTimeString(d)		
	{
		var hour    = d.getHours();  /* Returns the hour (from 0-23) */
		var minutes     = d.getMinutes();  /* Returns the minutes (from 0-59) */
		var result  = hour;
		var ext     = '';

		
	    if(hour > 12){
	        ext = 'PM';
	        hour = (hour - 12);

	        if(hour < 10){
	            result = "0" + hour;
	        }else if(hour == 12){
	            hour = "00";
	            ext = 'AM';
	        }
	    }
	    else if(hour < 12){
	        result = ((hour < 10) ? "0" + hour : hour);
	        ext = 'AM';
	    }else if(hour == 12){
	        ext = 'PM';
	    }
		

		if(minutes < 10){
		    minutes = "0" + minutes; 
		}

		result = result + ":" + minutes + ' ' + ext; 
		return result;
	}
</script>

<div id="dialog_create_event" class="modal fade" style="z-index:9998;display:none;" title="">
    <div class="modal-dialog modal-md">
    	<div class="modal-content">
    		<div class="modal-header">
    	        <button type="button" class="close" data-dismiss="modal">&times;</button>
    	        <h4 class="modal-title">Edit Event</h4>				        
          	</div>
          	<div class="modal-body" style="overflow:auto" >
          		<input class="form-control" id="event_id" name="event_id" type="hidden" value="0">
          		<div class="form-group">
                	<label for="submission_from"> Start</label>						            	
                	<input class="datetimePicker form-control" id="event_start" name="event_start" type="text">
                </div>
                <div class="form-group">
                	<label for="submission_subj">End</label>
                	<input class="datetimePicker form-control" id="event_end" name="event_end" type="text">
       			</div>		
       			
       			<div class="form-group">
                	<label for="submission_subj">Description</label>
                	<textarea class="form-control" id="event_description" name="event_description" rows="6" ></textarea>
       			</div>		
       		<!--	<div class="form-group">
       				<label for="all_day" style="font-size: 17px">All day</label>&nbsp;&nbsp;<input type="checkbox" id="event_all_day" name="event_all_day" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label for="public" style="font-size: 17px">Public</label>&nbsp;&nbsp;<input type="checkbox" id="event_public" name="event_public">
       			</div>		-->
          	</div>	
          	<div class="modal-footer">
          		<div class="row row">
          			<center>
    	      			<div class="col-xs-4">
    	      				<button type="button" id="btnCreateEvent"  name="btnCreateEvent" class="btn btn-primary btn-block">Create</button>			        	 
    	      			</div>
    	      			<div class="col-xs-4">
    	      				<button type="button"  id="btnDeleteEvent"  name="btnDeleteEvent" class="btn btn-danger btn-block">Delete</button>			        	 
    	      			</div>
    	      			<div class="col-xs-4">
    	      				<button type="button" data-dismiss="modal" class="btn btn-default btn-success btn-block">Close</button>	
    	      			</div>
          			</center>
          		</div>	
          	</div>		    
    	</div>
    </div>
</div> 	