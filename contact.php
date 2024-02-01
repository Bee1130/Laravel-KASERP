<?php
//phpinfo();
/* * **-------------------------------------------------------------------**************************    

  Purpose     : 	Buyer Information Detail Page

  Project 	:	Sales Contact DB

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
	$buyer = "select * from contacts_info where auto_id=".$_GET['rid'];
	$resb = mysqli_query($con, $buyer) or die(mysqli_error($con) . "11");
	$recb = mysqli_fetch_assoc($resb);
	
	
	$sql_previous = "SELECT auto_id FROM contacts_info WHERE auto_id<" . $recb['auto_id'] . $_SESSION['filter_user']." order by auto_id desc LIMIT 1";
	$result_previous = mysqli_query($con, $sql_previous) or die(mysqli_error($con));
	if ($row_previous = mysqli_fetch_assoc($result_previous))
		$next_id = $row_previous['auto_id'];

	$sql_next = "SELECT auto_id FROM contacts_info WHERE auto_id>" . $recb['auto_id'] .$_SESSION['filter_user']. " LIMIT 1";
	$result_next = mysqli_query($con, $sql_next) or die(mysqli_error($con));
	if ($row_next = mysqli_fetch_assoc($result_next))
		$prev_id = $row_next['auto_id'];
		
	if (isset($_GET['notify']))
	{
	
		// Set notification as viewed for log info
		$sql_upd = sprintf("update log_info set is_viewed='%d' where is_viewed='%d' and customer_id='%d' and agent != '%s'",1,0,$_GET['rid'],$_SESSION['user_login']);
		$res=mysqli_query($con, $sql_upd) or die(mysqli_error($con)."11");
		
		$sql_log = sprintf("insert into system_log_info (action,agent,query,log_time) values ('%s','%s','%s',sysdate())","Update log info for viewed notification : contact.php ",$_SESSION['user_login'],mysqli_real_escape_string($con,$sql_upd));
		mysqli_query($con, $sql_log) or die(mysqli_error($con));	
	}
}

$contact_type = null;
if(isset($_POST['contact_type'])) {
	$contact_type = $_POST['contact_type'];
}

$submit = null;
if(isset($_POST['Submit'])) {
	$submit = $_POST['Submit'];
}
$ReminderSave = null;
if(isset($_POST['ReminderSave'])) {
	$ReminderSave = $_POST['ReminderSave'];
}

$ReminderDelete = null;
if(isset($_POST['ReminderDelete'])) {
	$ReminderDelete = $_POST['ReminderDelete'];
}
if ($submit == "Save") 
{
	
	$Destination = 'userprofile/userfiles/avatars';
    if(!isset($_FILES['ImageFile']) || !is_uploaded_file($_FILES['ImageFile']['tmp_name'])){
    	$prev_avatar = "";
    	$sql_sel_avatar="select user_avatar from contacts_info WHERE auto_id = ".$_GET['rid'];
    	$sql_sel_res = mysqli_query($con, $sql_sel_avatar) or die(mysqli_error($con)); 
    	if ($sql_sel_rec = mysqli_fetch_assoc($sql_sel_res))
    	{
			$prev_avatar = $sql_sel_rec['user_avatar'];
			
		}
    	if (strlen($prev_avatar) == 0)
    	{
			$NewImageName= 'default.jpg';
        	move_uploaded_file($_FILES['ImageFile']['tmp_name'], "$Destination/$NewImageName");	
        	$sql_upd_avatar="UPDATE contacts_info SET user_avatar='$NewImageName' WHERE auto_id = ".$_GET['rid'];
	    	$sql_upd_res = mysqli_query($con, $sql_upd_avatar) or die(mysqli_error($con)); 
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
        
        $sql_upd_avatar="UPDATE contacts_info SET user_avatar='$NewImageName' WHERE auto_id = ".$_GET['rid'];
    	$sql_upd_res = mysqli_query($con, $sql_upd_avatar) or die(mysqli_error($con)); 
    }
    
   
    
	$_POST['hm_ph'] = my_phone_format($_POST['hm_ph']);
   
    
    $sql_upd = "update contacts_info set 
 					  name = '" . mysqli_real_escape_string($con,$_POST['name']) . "',
 					  hm_ph = '" . $_POST['hm_ph'] . "', 					
 					  email = '" . mysqli_real_escape_string($con,$_POST['email']) . "',
 					  address = '" . mysqli_real_escape_string($con,$_POST['address']) . "', 					 
 					  landline = '" . mysqli_real_escape_string($con,$_POST['landline']) . "',
 					  national_ins = '" . mysqli_real_escape_string($con,$_POST['national_ins']) . "',
 					  utr = '" . mysqli_real_escape_string($con,$_POST['utr']) . "',
 					  bank_details = '" . mysqli_real_escape_string($con,$_POST['bank_details']) . "',
 					  contact_type = '" . mysqli_real_escape_string($con,$_POST['contact_type']) . "',
 					  policy_number = '" . mysqli_real_escape_string($con,$_POST['policy_number']) . "', 					  
					  cust_upd_dt= curdate()										  
			  where auto_id =" . $_GET['rid'] . "";
   
    mysqli_query($con, $sql_upd) or die(mysqli_error($con));
    
   
     // insert action logs into system_log_info
	$sql_log = sprintf("insert into system_log_info (action,agent,query,log_time) values ('%s','%s','%s',sysdate())","Update Contact : contact.php ",$_SESSION['user_login'],mysqli_real_escape_string($con,$sql_upd));
	mysqli_query($con, $sql_log) or die(mysqli_error($con));	
	
	// Save log
	saveLog($_GET['rid'],'Contact','Contact','changed');
	
    header("Location: contact.php?rid=" . $_GET['rid']);
    exit();
      
}


if ($submit == "Cancel") {
	
    header("Location: contacts.php");
    exit();
	
}	

// Meeting
{
	if ($ReminderSave == "Save")
	{				
		$name = "";		
		$buyer = "select name from contacts_info where auto_id=".$_POST['reminder_auto_id'];
		$resb = mysqli_query($con, $buyer) or die(mysqli_error($con) . "11");	
		if ($recb = mysqli_fetch_assoc($resb))
		{
			$name  = $recb['name'];			
		}		
		$reminder_new_comment = trim($_POST['reminder_new_comment']);	
		$reminder_new_when = trim($_POST['reminder_new_when']);	
		$reminder_new_by = $_SESSION['user_login'];		
		if (strlen($reminder_new_comment) >= 0)
		{
			// Save log
			$reminder_value_ind = intval($_POST['reminder_value_ind']);
			if ($reminder_value_ind >= 0)
			{
				$sql_del = sprintf("delete from reminders_info where auto_id='%d'",$reminder_value_ind);
				mysqli_query($con, $sql_del) or die(mysqli_error($con));
							
				saveLog($_POST['reminder_auto_id'],'Contact','Reminder','changed');
			}else
				saveLog($_POST['reminder_auto_id'],'Contact','Reminder','added');
			
			$title = 'Meeting for '.$name;
						
			$data = array('client_id'=>$_POST['reminder_auto_id'],'start'=>date('Y-m-d H:i',strtotime($reminder_new_when)),'end'=>date('Y-m-d H:i',strtotime('+15 minutes',strtotime($reminder_new_when))),'all_day'=>0,'title'=>mysqli_real_escape_string($con,$title),'description'=>mysqli_real_escape_string($con,$reminder_new_comment),'active'=>1,'set_time'=>date('Y-m-d H:i:s'),'url'=>'contact.php?rid='.$_POST['reminder_auto_id'].'&notify=1');
			
			$fa_icon = 'fa fa-calendar';
			$sql_ins = sprintf("insert into reminders_info (client_id,start,end,all_day,title,description,active,set_time,user,url,fa_icon) values ('%d','%s','%s','%d','%s','%s','%d','%s','%s','%s','%s')",$data['client_id'],$data['start'],$data['end'],$data['all_day'],$data['title'],$data['description'],$data['active'],$data['set_time'],$_SESSION['user_login'],$data['url'],$fa_icon);
			mysqli_query($con, $sql_ins) or die(mysqli_error($con));
			
		}
	    
		header("Location: contact.php?rid=" . $_POST['reminder_auto_id']);
	    exit();
	}

	if ($ReminderDelete == "Delete")
	{		
    	// Save log
		saveLog($_POST['reminder_auto_id'],'Contact','Reminder','removed');		
		
		//delete old one
		$sql_del = sprintf("delete from reminders_info where client_id='%d' and start='%s'",$_POST['reminder_auto_id'],date('Y-m-d H:i',strtotime(trim($_POST['reminder_old_when']))));
		mysqli_query($con, $sql_del) or die(mysqli_error($con));
				
		header("Location: contact.php?rid=" . $_POST['reminder_auto_id']);
	    exit();
	}
}

// Quotes
{
	$QuoteSave = null;
	if(isset($_POST['QuoteSave'])) {
		$QuoteSave = $_POST['QuoteSave'];
	}
	if ($QuoteSave == "Save")
	{				
		$quote_new_comment = trim($_POST['quote_new_comment']);	
		$quote_value_ind = intval($_POST['quote_value_ind']);
		if ($quote_value_ind >= 0)
		{
			$sql_del = sprintf("delete from quotes_info where auto_id='%d'",$quote_value_ind);
			mysqli_query($con, $sql_del) or die(mysqli_error($con));
						
			saveLog($_POST['quote_auto_id'],'Contact','Quote','changed');		
		}else
			saveLog($_POST['quote_auto_id'],'Contact','Quote','added');		
			
	    // Save log
		if (strlen($quote_new_comment)>1)
		{
			$rid = intval($_POST['quote_auto_id']);
			
			$buyer = "select * from contacts_info where auto_id=".$rid;
			$resb = mysqli_query($con, $buyer) or die(mysqli_error($con) . "11");
			if ($recb = mysqli_fetch_assoc($resb))
			{
				$sql_ins = sprintf("insert into quotes_info (client_id,quote,author,date_time) values ('%d','%s','%s',sysdate())",$_POST['quote_auto_id'],mysqli_real_escape_string($con,$quote_new_comment),mysqli_real_escape_string($con,$recb['name']));
				mysqli_query($con, $sql_ins) or die(mysqli_error($con));
			}
		}
	    
		header("Location: contact.php?rid=" . $_POST['quote_auto_id']);
	    exit();
	}

	$QuoteDelete = null;
	if(isset($_POST['QuoteDelete'])) {
		$QuoteDelete = $_POST['QuoteDelete'];
	}

	if ($QuoteDelete == "Delete")
	{
		$quote_value_ind = intval($_POST['quote_value_ind']);
		
		// Save log
		saveLog($_POST['quote_auto_id'],'Contact','Quote','removed');		
		
		if ($quote_value_ind >= 0)
		{
			$sql_del = sprintf("delete from quotes_info where auto_id='%d'",$quote_value_ind);
			mysqli_query($con, $sql_del) or die(mysqli_error($con));
		}
				
		
		header("Location: contact.php?rid=" . $_POST['quote_auto_id']);
	    exit();
	}
}


// Notes
{
	$NoteSave = null;
	if(isset($_POST['NoteSave'])) {
		$NoteSave = $_POST['NoteSave'];
	}
	if ($NoteSave == "Save")
	{				
		$note_new_comment = trim($_POST['note_new_comment']);	
		$note_new_when = date('M d, Y, h:i a');
		$note_new_user = $_SESSION['user_login'];		
		$note_value_ind = intval($_POST['note_value_ind']);
		if ($note_value_ind >= 0)
		{
			$sql_del = sprintf("delete from notes_info where auto_id='%d'",$note_value_ind);
			mysqli_query($con, $sql_del) or die(mysqli_error($con));
						
			saveLog($_POST['note_auto_id'],'Contact','Note','changed');		
		}else
			saveLog($_POST['note_auto_id'],'Contact','Note','added');		
			
	    // Save log
		if (strlen($note_new_comment)>1)
		{
			
			$sql_ins = sprintf("insert into notes_info (client_id,comment,note_by,note_date) values ('%d','%s','%s','%s')",$_POST['note_auto_id'],mysqli_real_escape_string($con,$note_new_comment),$note_new_user,$note_new_when);
			mysqli_query($con, $sql_ins) or die(mysqli_error($con));
			
		}
	    
		header("Location: contact.php?rid=" . $_POST['note_auto_id']);
	    exit();
	}

	$NoteDelete = null;
	if(isset($_POST['NoteDelete'])) {
		$NoteDelete = $_POST['NoteDelete'];
	}

	if ($NoteDelete == "Delete")
	{
		$note_value_ind = intval($_POST['note_value_ind']);
		
		// Save log
		saveLog($_POST['note_auto_id'],'Contact','Note','removed');		
		
		if ($note_value_ind >= 0)
		{
			$sql_del = sprintf("delete from notes_info where auto_id='%d'",$note_value_ind);
			mysqli_query($con, $sql_del) or die(mysqli_error($con));
		}
				
		
		header("Location: contact.php?rid=" . $_POST['note_auto_id']);
	    exit();
	}
}

// File
{
	$FileSave = null;
	if(isset($_POST['FileSave'])) {
		$FileSave = $_POST['FileSave'];
	}
	if ($FileSave == "Save")
	{		
				
		$buyer = "select * from contacts_info where auto_id=".$_POST['file_auto_id'];
		$resb = mysqli_query($con, $buyer) or die(mysqli_error($con) . "11");	
		
		if ($recb = mysqli_fetch_assoc($resb))
		{
			$filenames = $recb['fil_filename'];
			$filepaths = $recb['fil_filepath'];
			$dates = $recb['fil_date'];
		}else
		{
			$filenames = "";
			$filepaths = "";
			$dates = "";
		}
		
		$file_new_filename = trim($_POST['file_new_filename']);	
		$file_new_date = date('M d, Y, h:i a');
		$file_new_filepath = '';
		if(isset($_FILES['file_new_upload']) and is_uploaded_file($_FILES['file_new_upload']['tmp_name']))
		{			
			$Destination = 'uploads';
			$attach_file_nm=str_replace(' ','-',strtolower($_FILES['file_new_upload']['name']));
			move_uploaded_file($_FILES['file_new_upload']['tmp_name'], "$Destination/$attach_file_nm");			
			$file_new_filepath = $Destination.'/'.urlencode($attach_file_nm);
		}
		
		$file_value_ind = intval($_POST['file_value_ind']);
		if ($file_value_ind >= 0)
		{
			$filenames = "";
			$filepaths = "";
			$dates = "";
			
			$filenames_ary = explode(';',$recb['fil_filename']);
			$filepaths_ary = explode(';',$recb['fil_filepath']);
			$dates_ary = explode(';',$recb['fil_date']);
			    									
					
			
	    	foreach ($filenames_ary as $i => $filename)
	        {	
	        	$filepath = $filepaths_ary[$i];  
	        	$date = $dates_ary[$i];  
	        	
	        	if ($i != $file_value_ind)                                              	
	        	{
					$filenames .= $filename.';';
					$filepaths.= $filepath.';';
					$dates.= $date.';';
				}else
				{
					$filenames .= $file_new_filename.';';
					$filepaths .= $file_new_filepath.';';
					$dates .= $file_new_date.';';
				}
	        }
	        $filenames = substr($filenames,0,strlen($filenames)-1);
	        $filepaths = substr($filepaths,0,strlen($filepaths)-1);
	        $dates = substr($dates,0,strlen($dates)-1);
	        
	        // Save log
			saveLog($_POST['file_auto_id'],'Contact','File','changed');
		}else
		{
			if (strlen($filenames)>1)
			{
				$filenames .= ';'.$file_new_filename;
				$filepaths .= ';'.$file_new_filepath;	
				$dates .= ';'.$file_new_date;	
			}else
			{
				$filenames = $file_new_filename;
				$filepaths = $file_new_filepath;
				$dates = $file_new_date;	
			}
			
			// Save log
			saveLog($_POST['file_auto_id'],'Contact','File','added');
			
		}
		
		if (strlen($filenames)>1)
		{
			$sql_upd = "update contacts_info set 
	 					  fil_filename = '" . mysqli_real_escape_string($con,$filenames) . "',
	 					  fil_filepath = '" . mysqli_real_escape_string($con,$filepaths) . "',
	 					  fil_date = '" . mysqli_real_escape_string($con,$dates) . "',
						  cust_upd_dt= curdate()										  
				  where auto_id =" . $_POST['file_auto_id'] . "";
	   
	    	mysqli_query($con, $sql_upd) or die(mysqli_error($con));
		}
	    
		header("Location: contact.php?rid=" . $_POST['file_auto_id']);
	    exit();
	}

	$FileDelete = null;
	if(isset($_POST['FileDelete'])) {
		$FileDelete = $_POST['FileDelete'];
	}

	if ($FileDelete == "Delete")
	{
		$buyer = "select * from contacts_info where auto_id=".$_POST['file_auto_id'];
		$resb = mysqli_query($con, $buyer) or die(mysqli_error($con) . "11");	
		if ($recb = mysqli_fetch_assoc($resb))
		{
			$filenames = $recb['fil_filename'];
			$filepaths = $recb['fil_filepath'];
			$dates = $recb['fil_date'];
			
			$file_value_ind = intval($_POST['file_value_ind']);
			
			if ($file_value_ind >= 0)
			{
				$filenames = "";
				$filepaths = "";
				$dates = "";
				
				$filenames_ary = explode(';',$recb['fil_filename']);
				$filepaths_ary = explode(';',$recb['fil_filepath']);
				$dates_ary = explode(';',$recb['fil_date']);
				
		    	foreach ($filenames_ary as $i => $filename)
		        {	
		        	$filepath = $filepaths_ary[$i];  
		        	$date = $dates_ary[$i];  
		        	
		        	if ($i != $file_value_ind)                                              	
		        	{                                   	
			        	$filenames .= $filename.';';
						$filepaths.= $filepath.';';
						$dates.= $date.';';
					}
		        }
		        
		         $filenames = substr($filenames,0,strlen($filenames)-1);
		        $filepaths = substr($filepaths,0,strlen($filepaths)-1);
		        $dates = substr($dates,0,strlen($dates)-1);
	        	
	        	
				$sql_upd = "update contacts_info set 
	 					  fil_filename = '" . mysqli_real_escape_string($con,$filenames) . "',
	 					  fil_filepath = '" . mysqli_real_escape_string($con,$filepaths) . "',
	 					  fil_date = '" . mysqli_real_escape_string($con,$dates) . "',
						  cust_upd_dt= curdate()										  
				  where auto_id =" . $_POST['file_auto_id'] . "";
	   
		    	mysqli_query($con, $sql_upd) or die(mysqli_error($con));	
		    	
		    	// Save log
				saveLog($_POST['file_auto_id'],'Contact','File','removed');			
			}
		}		
		header("Location: contact.php?rid=" . $_POST['file_auto_id']);
	    exit();
	}
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
			
			.btn:hover {
			    color: orange;
    background-color: #efece4 !important;
    padding-left: 20px;
    padding-right: 20px;
    border-radius: 5px;
			}
			
			.btn {
		            color: green;
    background-color: #efece4 !important;
    padding-left: 20px;
    padding-right: 20px;
    border-radius: 5px;
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
			    $("#panel-fullscreen-Files").click(function (e) {
			        $(this).closest('.panel').toggleClass('panel-fullscreen');
			    });
				
				// Reminder
				$("#btn_add_reminder").click(function(event){				 	
	        		$("#reminder_value_ind").val(-1);
	        		
				 	$("#reminder_form").show();
				});
				
				$("#btn_cancel_reminder").click(function(event){
				 	$("#reminder_form").hide();
				 	
	        		$("#reminder_value_ind").val(-1);
				});
				
				// Quote
				$("#btn_add_quote").click(function(event){				 	
	        		$("#quote_value_ind").val(-1);
	        		
				 	$("#quote_form").show();
				});
				
				$("#btn_cancel_quote").click(function(event){
				 	$("#quote_form").hide();
				 	
	        		$("#quote_value_ind").val(-1);
				});
				
				// Note
				$("#btn_add_note").click(function(event){				 	
	        		$("#note_value_ind").val(-1);
	        		
				 	$("#note_form").show();
				});
				
				$("#btn_cancel_note").click(function(event){
				 	$("#note_form").hide();
				 	
	        		$("#note_value_ind").val(-1);
				});
				
				// File
				$("#btn_add_file").click(function(event){				 	
	        		$("#file_value_ind").val(-1);
	        		$("#file_new_filename").val("");
				 	$("#file_form").show();
				});
				
				$("#btn_cancel_file").click(function(event){
				 	$("#file_form").hide();
				 	$("#file_new_filename").val("");
				 	
	        		$("#file_value_ind").val(-1);
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
			
	        function EditReminder(auto_id)
	        {
	        	console.log("EditReminder");
	        	console.log(auto_id);
	        	
	        	var data = {"auto_id":auto_id};
				console.log(data);       			
			    $.ajax({
			        url: 'getMeetingContent.php',
			        data: data,
			        type:"POST",
					dataType : "json",
			        success: function ( res ) {
			        	console.log("Success");
			        	console.log(res);
			        	if (res.status == "Success")
			        	{
			        		$("#reminder_new_comment").val(res.reminder_new_comment);							        		
			        		$("#reminder_new_when").val(res.reminder_new_when);		
			        		$("#reminder_old_when").val(res.reminder_new_when);					        		
						}							
			        }
			    });	        	
	        	$("#reminder_value_ind").val(auto_id);	    
	        	$("#reminder_form").show();        
	        }   
	        
	        function EditNote(auto_id)
	        {
	        	console.log("EditNote");
	        	console.log(auto_id);
	        	
	        	var data = {"auto_id":auto_id};
				console.log(data);       			
			    $.ajax({
			        url: 'getNoteContent.php',
			        data: data,
			        type:"POST",
					dataType : "json",
			        success: function ( res ) {
			        	console.log("Success");
			        	console.log(res);
			        	if (res.status == "Success")
			        	{
			        		$("#note_new_comment").val(res.note_new_comment);
						}							
			        }
			    });	        	
	        	$("#note_value_ind").val(auto_id);	        	
	        	$("#note_form").show();        
	        } 
	        
	        function EditQuote(auto_id)
	        {
	        	console.log("EditQuote");
	        	console.log(auto_id);
	        	
	        	var data = {"auto_id":auto_id};
				console.log(data);       			
			    $.ajax({
			        url: 'getQuoteContent.php',
			        data: data,
			        type:"POST",
					dataType : "json",
			        success: function ( res ) {
			        	console.log("Success");
			        	console.log(res);
			        	if (res.status == "Success")
			        	{
			        		$("#quote_new_comment").val(res.quote_new_comment);
						}							
			        }
			    });	        	
	        	$("#quote_value_ind").val(auto_id);	        	
	        	$("#quote_form").show();        
	        } 
	        
	        function EditFile(auto_id,value_ind)
	        {
	        	console.log("EditFile");
	        	console.log(auto_id);
	        	
	        	var data = {					
					"type":"File",
					"auto_id":auto_id,
					"value_ind":value_ind
				};
				console.log(data);       			
			    $.ajax({
			        url: 'getCustomerContent.php',
			        data: data,
			        type:"POST",
					dataType : "json",
			        success: function ( res ) {
			        	console.log("Success");
			        	console.log(res);
			        	if (res.status == "Success")
			        	{
			        		var current_path = 'Currently: ';
			        		current_path = current_path + "<a href='"+res.file_new_filepath+"'>"+res.file_new_filepath+"</a>" +"<br/>"+"Change:";
			        		$("#file_new_filepath").html(current_path);
			        		$("#file_new_filename").val(res.file_new_filename);
						}							
			        }
			    });
	        	
	        	$("#file_value_ind").val(value_ind);	        	
	        	$("#file_form").show();        
	        }  
	        
        </script>   
    </head>
    <body>
        <script type="text/javascript" src="popcalendar.js"></script>
        <div>	    	
	    	<input type="hidden" name="sel_customer_id" id ="sel_customer_id" value="<?php echo $_SESSION['user_login'].':'.$_GET['rid']?>">	    	
    	</div>
    	
    	<div class="container">
    	    <?php include("sidebar.php"); ?>
    	    <div class="main-content">
    	        <?php include("menu.php"); ?>
        		<div class="container">		
        			<div id="my-main-content">				
        				<br>
        				<ol class="breadcrumb pull-right" style="margin-bottom: 5px;">
        				  <?php
                        	if ($next_id != -1)
                        	{
                        	?>
        					<li><a href="contact.php?rid=<?php echo $next_id;?>">Previous</a></li>	
        					<?php
        					}
                        ?>
                      
                        <?php
                        	if ($prev_id != -1)
                        	{
                        	?>
        					<li><a href="contact.php?rid=<?php echo $prev_id;?>">Next</a></li>	
        					<?php
        					}
                        ?>  
               			</ol>
        				<h2 style="margin-top:0px">&nbsp;&nbsp;Contact</h2>
        				
        				<!-- Main content -->
        				<div class="row">            		
                    		<div class="col-md-6 col-lg-6">   	
        			             <div class="panel panel-inverse" data-sortable-id="ui-general-1" style="background-color: #f5f3ef">
        			                <div class="panel-heading" style="padding: 20px">
        			                	<h4 class="panel-title">
        			                		<a>Main</a>
        							        <div class="panel-heading-btn">
        				                        <a href="#" id="panel-fullscreen-Main" role="button"  class="btn btn-xs btn-icon btn-circle btn-default" title="Toggle fullscreen"><i class="fa fa-expand"></i></a>
        				                        <a href="#collapseMain"style="background-color:#f5f3ef" data-toggle="collapse" data-target="#collapseMain" class="btn btn-xs btn-icon btn-circle btn-warning collapsed" ><i class="fa fa-minus"></i></a>
        							       	</div>
        							    </h4>
        			                </div>
        			               
        		                	
        			                <form action="<?php echo 'contact.php?rid='.$_GET['rid']; ?>" method="post" enctype="multipart/form-data" id="UploadForm">
        					            <div id="collapseMain"  class="panel-collapse collapse in"  style="background-color: #f5f3ef">
        				                	<div class="panel-body" id="Main">	
        				                		<div class="row">
        				                			<div class="form-group" style="max-width: 170px;margin: auto">
        							                    <div  class="col-lg-12">
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
        				                            <div class="col-lg-6">
        				                                <div class="form-group">
        				                                    <label for="{{ form.name.for_label" class="control-label">Name</label>
        				                                    <input class="form-control  contact-form-control target semi-bold" id="name" maxlength="100" name="name" type="text"  
        				                                    					value="<?php if (isset($_POST['name'])) {
        			                                                                    echo $_POST['name'];
        			                                                                } else if (isset($recb['name'])) {
        			                                                                    echo $recb['name'];
        			                                                                } ?>">
        				                                </div>
        				                            </div>
        				                             <div class="col-lg-6">
        				                                <div class="form-group">
        				                                    <label for="{{ form.contact_type.for_label" class="control-label">Name</label>
        				                                    <select id="contact_type" name="contact_type"   class="form-control  contact-form-control target semi-bol" >						                            			                            				<option value="Client" <?php if($contact_type=="Client"){echo 'selected';}
        						                            							else if(isset($recb['contact_type'])) {
																							if($recb['contact_type']=="Client")
        						                            									{echo 'selected';}
																						}
																						
        						                            							?>>Client</option>	
        														<option value="Contractor" <?php if($contact_type=="Contractor"){echo 'selected';}
        						                            							else  if(isset($recb['contact_type'])) {
																						if($recb['contact_type']=="Contractor")
        						                            									{echo 'selected';}
																						}
        						                            							?>>Contractor</option>
        					                                       
        					                                    <option value="New" <?php if($contact_type=="New"){echo 'selected';}
        						                            							else  if(isset($recb['contact_type'])) {
																						if($recb['contact_type']=="New")
        						                            									{echo 'selected';}
																						}
        						                            							?>>New</option>						                                
        						                            	<option value="Other" <?php if($contact_type=="Other"){echo 'selected';}
        						                            							else  if(isset($recb['contact_type'])) {
																						if($recb['contact_type']=="Other")
        						                            									{echo 'selected';}
																						}
        						                            							?>>Other</option>						             
        					                                </select>
        				                                </div>
        				                            </div>
        					                    </div>
        				                        <div class="row">
        				                            <div class="col-lg-6">
        				                                <div class="form-group">
        				                                    <label for="{{ form.hm_ph.for_label" class="control-label">Mobile</label>
        				                                    <input class="form-control  contact-form-control target semi-bold" id="hm_ph" maxlength="100" name="hm_ph" type="text"  
        				                                    					value="<?php if (isset($_POST['hm_ph'])) {
        			                                                                    echo my_phone_format2($_POST['hm_ph']);
        			                                                                } else if (isset($recb['hm_ph'])) {
        			                                                                    echo my_phone_format2($recb['hm_ph']);
        			                                                                } ?>">
        				                                </div>
        				                            </div>
        				                            <div class="col-lg-6">
        				                                <div class="form-group">
        				                                    <label for="{{ form.email.for_label" class="control-label">Email</label>
        				                                    <input class="form-control  contact-form-control target semi-bold" id="email" maxlength="100" name="email" type="text"  
        				                                    					value="<?php if (isset($_POST['email'])) {
        			                                                                    echo $_POST['email'];
        			                                                                } else if (isset($recb['email'])) {
        			                                                                    echo $recb['email'];
        			                                                                } ?>">
        				                                </div>
        				                            </div>
        				                        </div>
        										<div class="row">
        				                        	<div class="col-lg-6">
        		                                        <div class="form-group">
        		                                            <label for="{{ form.landline.for_label" class="control-label">Land Line</label>
        		                                            <input class="form-control  contact-form-control target" id="landline" maxlength="50" name="landline" type="text" value="<?php 
        															 if (isset($_POST['landline'])) {
        	                                                                    echo $_POST['landline'];
        	                                                                } else if (isset($recb['landline'])) {
        	                                                                    echo $recb['landline'];
        	                                                                } ?>">
        		                                        </div>
        		                                    </div>
        		                                    <div class="col-lg-6">
        		                                        <div class="form-group">
        		                                            <label for="{{ form.policy_number.for_label" class="control-label">Liability Insurance Policy Number</label>
        		                                            <input class="form-control  contact-form-control target" id="policy_number" maxlength="50" name="policy_number" type="text" value="<?php 
        															 if (isset($_POST['policy_number'])) {
        	                                                                    echo $_POST['policy_number'];
        	                                                                } else if (isset($recb['policy_number'])) {
        	                                                                    echo $recb['policy_number'];
        	                                                                } ?>">
        		                                        </div>
        		                                    </div>
        		                                </div>
        										<div class="row">
        				                        	<div class="col-lg-6">
        		                                        <div class="form-group">
        		                                            <label for="{{ form.national_ins.for_label" class="control-label">National Ins</label>
        		                                            <input class="form-control  contact-form-control target" id="national_ins" maxlength="50" name="national_ins" type="text" value="<?php 
        															 if (isset($_POST['national_ins'])) {
        	                                                                    echo $_POST['national_ins'];
        	                                                                } else if (isset($recb['national_ins'])) {
        	                                                                    echo $recb['national_ins'];
        	                                                                } ?>">
        		                                        </div>
        		                                    </div>
        		                                    <div class="col-lg-6">
        		                                        <div class="form-group">
        		                                            <label for="{{ form.utr.for_label" class="control-label">Website</label>
        		                                            <input class="form-control  contact-form-control target" id="utr" maxlength="50" name="utr" type="text" value="<?php 
        															 if (isset($_POST['utr'])) {
        	                                                                    echo $_POST['utr'];
        	                                                                } else if (isset($recb['utr'])) {
        	                                                                    echo $recb['utr'];
        	                                                                } ?>">
        		                                        </div>
        		                                    </div>
        		                                </div>
        		                               	<div class="row">
        				                        	 <div class="col-lg-12">
        		                                        <div class="form-group">
        		                                            <label for="{{ form.bank_details.for_label" class="control-label">Bank Details</label>
        		                                            <input class="form-control  contact-form-control target" id="bank_details" maxlength="50" name="bank_details" type="text" value="<?php 
        															 if (isset($_POST['bank_details'])) {
        	                                                                    echo $_POST['bank_details'];
        	                                                                } else if (isset($recb['bank_details'])) {
        	                                                                    echo $recb['bank_details'];
        	                                                                } ?>">
        		                                        </div>
        		                                    </div>
        		                                </div>
        				                      	<div class="row">
        				                        	 <div class="col-lg-12">
        				                                <div class="form-group">
        				                                    <label for="{{ form.address.for_label" class="control-label">Google Address</label>
        				                                    <textarea class="form-control  contact-form-control target" cols="40" id="address" maxlength="2500" name="address" rows="2"><?php 
        																	 if (isset($_POST['address'])) {
        			                                                                    echo $_POST['address'];
        			                                                                } else if (isset($recb['address'])) {
        			                                                                    echo $recb['address'];
        			                                                                } ?></textarea>
        				                                </div>
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
        									<div class="panel-footer"  style="background-color: #f5f3ef">
        		                	
        		                				<button type="submit" id="save-main" name="Submit" value="Save" class="btn btn-sm  btn-primary">Save</button> 
        		                				<button type="submit" id="cancel-main" name="Submit" value="Cancel" class="btn btn-sm  btn-default">Cancel</button> 
        								    </div>	
        								</div>
        						    </form>                
        						</div>	
                    		</div>
                    		<div class="col-md-6 col-lg-6">   	
                    			
        						<div class="panel panel-inverse" data-sortable-id="ui-general-7">
        							<div class="panel-heading">
        			                	<h4 class="panel-title">
        			                		<a>Meeting</a>
        							        <div class="panel-heading-btn">
        				                        <a href="#" id="panel-fullscreen-Meeting" role="button"  class="btn btn-xs btn-icon btn-circle btn-default" title="Toggle fullscreen"><i class="fa fa-expand"></i></a>
        				                        <a href="#collapseMeeting" data-toggle="collapse" data-target="#collapseMeeting" class="btn btn-xs btn-icon btn-circle btn-warning collapsed" ><i class="fa fa-minus"></i></a>
        				                    </div> 
        							        <div class="pull-right">
        										<button class="btn btn-xs add-more btn-success" type="button" name="btn_add_reminder" id="btn_add_reminder"><i class="fa fa-plus"></i> Add</button>  
        			                   		</div>
        							    </h4>
        			                </div>
        			                <div id="collapseMeeting"  class="panel-collapse collapse in" style="background-color: #f5f3ef">
        			                	<div class="panel-body animated fadeIn" id="Meeting">		
        			                		<div class="form_placeholder" id = "reminder_form" style="display: none">		
        										<form class="form-horizontal" method="post" action="contact.php" >	
        			                			    <fieldset>
        										        <input type="hidden" name="reminder_auto_id"  id="reminder_auto_id" value="<?php echo $_GET['rid']?>">											      	<input type="hidden" name="reminder_value_ind" id="reminder_value_ind">
        										        
        										        <div class="form-group">                  
        										            <label class="col-md-3 control-label">Comment:</label>
        										            <div class="col-md-9"> 
        										                <textarea class="form-control" cols="40" id="reminder_new_comment" maxlength="200" name="reminder_new_comment" rows="10"></textarea>
        										            </div>
        										        </div>
        										        <div class="form-group">                  
        										            <label class="col-md-3 control-label">When:</label>
        										            <div class="col-md-9"> 										            	
        										            	  <input class="datetimePicker form-control" id="reminder_new_when" name="reminder_new_when" type="text">
        										            	  <input class="datetimePicker form-control" id="reminder_old_when" name="reminder_old_when" type="hidden">
        										            </div>
        										        </div>
        										        <div class="form-group">
        										            <div class="col-md-8 col-md-offset-4">
        										                <button class="save-form btn btn-sm btn-primary m-r-5" name="ReminderSave" type="submit" value="Save">Save</button>
        											            <button class="delete-form btn btn-sm btn-danger m-r-5" name="ReminderDelete" type="submit" value="Delete">Delete</button>
        											            <button class="close-form btn btn-sm btn-default"  type="button" name="btn_cancel_reminder" id="btn_cancel_reminder">Cancel</button>
        										            </div>
        										        </div>    
        										    </fieldset> 
        										</form>										
        									</div>                    
        				                    <div class="table_placeholder">
        				                    	<div class="table-responsive">
        											<table class="table table-condensed table-responsive table-hover table-striped">
        											    <thead>
        											        <tr>
        											            <th>Comment</th>											            
        											            <th style="width:90px;padding-left:10px;padding-right:10px">User</th>
        											            <th style="width:165px;padding-left:10px;padding-right:10px">When</th>
        											        </tr>
        											    </thead>
        											    <tbody>
        											    <?php
        											    	$sql_sel_meeting = sprintf("select auto_id,description,user,start from reminders_info where client_id = '%d' order by start desc",$_GET['rid']);
        											    	$res_sel_meeting = mysqli_query($con, $sql_sel_meeting) or die(mysqli_error($con) . "11");	
        											    	$i=0;
        													while ($recb_sel_meeting = mysqli_fetch_assoc($res_sel_meeting))
        													{	
        														$auto_id = $recb_sel_meeting['auto_id'];
        														$comment = $recb_sel_meeting['description'];						
        														$by = $recb_sel_meeting['user'];													
        														$date = $recb_sel_meeting['start'];	
        														$date = date('M d, Y, h:i a',strtotime($date));
        	                                                ?>
        		                                                 <tr>
        		                                                 	<td style="white-space: pre-wrap;word-wrap: break-word; "><a style="color: inherit;text-decoration: none;" onclick="javascript:EditReminder('<?php echo $auto_id;?>')"><?php echo $comment;?></a></td>
        												            <td style="width:90px;padding-left:10px;padding-right:10px"><?php echo $by;?></td>
        												            <td style="width:165px;padding-left:10px;padding-right:10px"><?php echo $date;?></td>												        	
        												        </tr>
        	                                            	<?php
        	                                            		$i++;
        	                                                }
        				                                ?>  
        			    								
        											    </tbody>
        											</table>
        											<div class="table-responsive"></div>
        										</div>
        									</div>
        				                </div>
        			            	</div>
        			            </div>
        			            <div class="panel panel-inverse" data-sortable-id="ui-general-8">
        							<div class="panel-heading">
        			                	<h4 class="panel-title">
        			                		<a>Quotes</a>
        							        <div class="panel-heading-btn">
        				                        <a href="#" id="panel-fullscreen-Quotes" role="button"  class="btn btn-xs btn-icon btn-circle btn-default" title="Toggle fullscreen"><i class="fa fa-expand"></i></a>
        				                        <a href="#collapseQuotes" data-toggle="collapse" data-target="#collapseQuotes" class="btn btn-xs btn-icon btn-circle btn-warning collapsed" ><i class="fa fa-minus"></i></a>
        				                    </div>
        							        <div class="pull-right">
        										<button class="btn btn-xs add-more btn-success" type="button" name="btn_add_quote" id="btn_add_quote"><i class="fa fa-plus"></i> Add</button>  	
        			                   		</div>
        							    </h4>
        			                </div>
        			                <div id="collapseQuotes"  class="panel-collapse collapse in"  style="background-color: #f5f3ef">
        			                	<div class="panel-body animated fadeIn" id="Quotes">	
        			                		<div class="form_placeholder" id = "quote_form" style="display: none">		
        										<form class="form-horizontal" method="post" action="contact.php?rid=<?php echo $_GET['rid']?>" >	
        										    <fieldset>
        										        <input type="hidden" name="quote_auto_id"  id="quote_auto_id" value="<?php echo $_GET['rid']?>">											      		<input type="hidden" name="quote_value_ind" id="quote_value_ind">
        										       	<div class="form-group">                  
        											         <label class="col-md-3 control-label">Quote:</label>
        											         <div class="col-md-9"> 
        											            <textarea class="form-control" cols="40" id="quote_new_comment" maxlength="100000" name="quote_new_comment" rows="10"></textarea>
        											        </div>
        											    </div>
        											    <div class="form-group">
        											        <div class="col-md-8 col-md-offset-4">
        											            <button class="save-form btn btn-sm btn-primary m-r-5" name="QuoteSave" type="submit" value="Save">Save</button>
        											            <button class="delete-form btn btn-sm btn-danger m-r-5" name="QuoteDelete" type="submit" value="Delete">Delete</button>
        											            <button class="close-form btn btn-sm btn-default"  type="button" name="btn_cancel_quote" id="btn_cancel_quote">Cancel</button>
        											        </div>
        											    </div>    
        										    </fieldset> 
        										</form>
        									</div>		                    
        				                    <div class="table_placeholder">
        				                    	<div class="table-responsive">
        											<table class="table table-condensed table-responsive table-hover table-striped" style="word-wrap:break-word;">
        											    <thead>
        											        <tr>
        											            <th>Qutoe</th>											            
        											        </tr>
        											    </thead>
        											    <tbody>
        			    								<?php
        			    									$sql_sel_quote = sprintf("select auto_id,quote from quotes_info where client_id = '%d' order by auto_id desc",$_GET['rid']);
        											    	$res_sel_quote = mysqli_query($con, $sql_sel_quote) or die(mysqli_error($con) . "11");	
        											    	$i=0;
        													while ($recb_sel_quote = mysqli_fetch_assoc($res_sel_quote))
        													{	
        														$auto_id = $recb_sel_quote['auto_id'];
        														$quote = $recb_sel_quote['quote'];						
        	                                                ?>
        		                                                 <tr>		                                                 	
        												            <td style="white-space: pre-wrap;word-wrap: break-word; max-width: 460px;"><a style="color: inherit;text-decoration: none;" onclick="javascript:EditQuote('<?php echo $auto_id;?>')"><?php echo $quote;?></a></td>
        												            										        	
        												        </tr>
        	                                            	<?php
        	                                                }
        				                                ?>  
        											    </tbody>
        											</table>
        											<div class="table-responsive"></div>
        										</div>
        									</div>
        				                </div>
        			            	</div>
        			            </div>
        						<div class="panel panel-inverse" data-sortable-id="ui-general-8">
        							<div class="panel-heading">
        								
        			                	<h4 class="panel-title">
        			                		<a>Notes</a>
        							        <div class="panel-heading-btn">
        				                        <a href="#" id="panel-fullscreen-Notes" role="button"  class="btn btn-xs btn-icon btn-circle btn-default" title="Toggle fullscreen"><i class="fa fa-expand"></i></a>
        				                        <a href="#collapseNotes" data-toggle="collapse" data-target="#collapseNotes" class="btn btn-xs btn-icon btn-circle btn-warning collapsed" ><i class="fa fa-minus"></i></a>
        				                    </div>
        							        <div class="pull-right">
        										<button class="btn btn-xs add-more btn-success" type="button" name="btn_add_note" id="btn_add_note"><i class="fa fa-plus"></i> Add</button>  	
        			                   		</div>
        							    </h4>
        			                </div>
        			                <div id="collapseNotes"  class="panel-collapse collapse in"  style="background-color: #f5f3ef>
        			                	<div class="panel-body animated fadeIn" id="Notes">	
        			                		<div class="form_placeholder" id = "note_form" style="display: none">		
        										<form class="form-horizontal" method="post" action="contact.php" >	
        										    <fieldset>
        										        <input type="hidden" name="note_auto_id"  id="note_auto_id" value="<?php echo $_GET['rid']?>">											      	<input type="hidden" name="note_value_ind" id="note_value_ind">
        										       	<div class="form-group">                  
        											         <label class="col-md-3 control-label">Comment:</label>
        											         <div class="col-md-9"> 
        											            <textarea class="form-control" cols="40" id="note_new_comment" maxlength="100000" name="note_new_comment" rows="10"></textarea>
        											        </div>
        											    </div>
        											    <div class="form-group">
        											        <div class="col-md-8 col-md-offset-4">
        											            <button class="save-form btn btn-sm btn-primary m-r-5" name="NoteSave" type="submit" value="Save">Save</button>
        											            <button class="delete-form btn btn-sm btn-danger m-r-5" name="NoteDelete" type="submit" value="Delete">Delete</button>
        											            <button class="close-form btn btn-sm btn-default"  type="button" name="btn_cancel_note" id="btn_cancel_note">Cancel</button>
        											        </div>
        											    </div>    
        										    </fieldset> 
        										</form>
        									</div>		                    
        				                    <div class="table_placeholder">
        				                    	<div class="table-responsive">
        											<table class="table table-condensed table-responsive table-hover table-striped" style="word-wrap:break-word;">
        											    <thead>
        											        <tr>
        											            <th>Comment</th>											            
        											            <th style="width:90px;padding-left:10px;padding-right:10px">By</th>
        											            <th style="width:165px;padding-left:10px;padding-right:10px">Date</th>
        											        </tr>
        											    </thead>
        											    <tbody>
        			    								<?php
        			    									$sql_sel_note = sprintf("select auto_id,comment,note_by,note_date from notes_info where client_id = '%d' order by note_date desc",$_GET['rid']);
        											    	$res_sel_note = mysqli_query($con, $sql_sel_note) or die(mysqli_error($con) . "11");	
        											    	$i=0;
        													while ($recb_sel_note = mysqli_fetch_assoc($res_sel_note))
        													{	
        														$auto_id = $recb_sel_note['auto_id'];
        														$comment = $recb_sel_note['comment'];						
        														$user = $recb_sel_note['note_by'];													
        														$date = $recb_sel_note['note_date'];	
        														$when = date('M d, Y, h:i a',strtotime($date));
        	                                                ?>
        		                                                 <tr>
        		                                                 	
        												            <td style="white-space: pre-wrap;word-wrap: break-word; max-width: 460px;"><a style="color: inherit;text-decoration: none;" onclick="javascript:EditNote('<?php echo $auto_id;?>')"><?php echo $comment;?></a></td>
        												            <td style="width:90px;padding-left:10px;padding-right:10px"><?php echo $user;?></td>
        												            <td style="width:165px;padding-left:10px;padding-right:10px"><?php echo $when;?></td>												        	
        												        </tr>
        	                                            	<?php
        	                                                }
        				                                ?>  
        											    </tbody>
        											</table>
        											<div class="table-responsive"></div>
        										</div>
        									</div>
        				                </div>
        			            	</div>
        			            </div>
        						<div class="panel panel-inverse" data-sortable-id="ui-general-9">
        							<div class="panel-heading">
        			                	<h4 class="panel-title">
        			                		<a>Documents</a>
        							        <div class="panel-heading-btn">
        				                        <a href="#" id="panel-fullscreen-Files" role="button"  class="btn btn-xs btn-icon btn-circle btn-default" title="Toggle fullscreen"><i class="fa fa-expand"></i></a>
        				                        <a href="#collapseFiles" data-toggle="collapse" data-target="#collapseFiles" class="btn btn-xs btn-icon btn-circle btn-warning collapsed" ><i class="fa fa-minus"></i></a>
        				                    </div> 
        							        <div class="pull-right">
        							 	
        										<button class="btn btn-xs add-more btn-success" type="button" name="btn_add_file" id="btn_add_file"><i class="fa fa-plus"></i> Add</button>     
        									
        			                   		</div>
        							    </h4>
        			                </div>
        			                <div id="collapseFiles"  class="panel-collapse collapse in" style="background-color: #f5f3ef">
        			                	<div class="panel-body animated fadeIn" id="Files">	
        			                		<div class="form_placeholder" id = "file_form" style="display: none">		
        										<form class="form-horizontal" enctype="multipart/form-data" method="post" action="contact.php" >	
        										
        										    <fieldset>
        										        <input type="hidden" name="file_auto_id"  id="file_auto_id" value="<?php echo $_GET['rid']?>">											      		<input type="hidden" name="file_value_ind" id="file_value_ind">
        										        <div class="form-group">                  
        										           <label class="col-md-3 control-label">File:</label>
        										           <div class="col-md-9"> 
        										            	<div id = "file_new_filepath" name = "file_new_filepath"></div>
        										            	<input class="form-control" id="file_new_upload" name="file_new_upload" type="file">
        										            </div>
        										        </div>
        										        <div class="form-group">                  
        										           <label class="col-md-3 control-label">Name:</label>
        										           <div class="col-md-9"> 
        										            <input class="form-control" id="file_new_filename" maxlength="200" name="file_new_filename" type="text">
        										            </div>
        										        </div>
        												<div class="form-group">
        												    <div class="col-md-8 col-md-offset-4">
        												        <button class="save-form btn btn-sm btn-primary m-r-5" name="FileSave" type="submit" value="Save">Save</button>
        											            <button class="delete-form btn btn-sm btn-danger m-r-5" name="FileDelete" type="submit" value="Delete">Delete</button>
        											            <button class="close-form btn btn-sm btn-default"  type="button" name="btn_cancel_file" id="btn_cancel_file">Cancel</button>
        												    </div>
        												</div>    
        											</fieldset> 
        										</form>
        									</div>		                    
        				                    <div class="table_placeholder" style="background-color: #f3f5ef">
        				                    	<div class="table-responsive">
        											<table class="table table-condensed table-responsive table-hover table-striped">
        											    <thead>
        											        <tr>
        											            <th>Filename</th>     
        											            <th>Date</th>
        											        </tr>
        											    </thead>
        											    <tbody>
        			    								<?php
        			    									$filenames = explode(';',$recb['fil_filename']);
        			    									$dates = explode(';',$recb['fil_date']);
        			    									
        			    									for ($i=count($filenames)-1;$i>=0;$i--)
        	                                                {	       	        
        	                                                	$filename = $filenames[$i];            	                             	
        	                                                	$date = $dates[$i];	
        	                                                ?>
        		                                                 <tr>
        												            <td style="width:70%">								
        		                                                 		<a style="color: inherit;text-decoration: none;" onclick="javascript:EditFile('<?php echo $_GET['rid'];?>','<?php echo $i;?>')"><?php echo $filename;?></a>
        		                    							</td>
        												            <td><?php echo $date;?></td>												        	
        												        </tr>
        	                                            	<?php
        	                                                }
        				                                ?>  
        											    </tbody>
        											</table>
        											<div class="table-responsive"></div>
        										</div>
        									</div>
        				                </div>
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