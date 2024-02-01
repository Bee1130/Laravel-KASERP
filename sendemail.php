<?php 
@session_start();
//ob_start();

if(!isset($_SESSION['user_login']) and !isset($_COOKIE['cookie_login']))//session store admin name
{
    header("Location: index.php");//login in AdminLogin.php
}
require_once("includes/dbconnect.php");

$response = array();
$response['status']='Success';
$response['msg']='The email is sent successfully!';
if (is_ajax())
{
	$from_email = '';
	$from_name = '';
	// From name usally a person's name or company name (required)			
	$from_auto_id = $_POST['email_from'];	
	$sql_sel_from = sprintf("select * from assigned_worker_info where auto_id='%d'",$from_auto_id);
 	$sql_res_from = mysqli_query($con, $sql_sel_from) or die(mysqli_error($con) . "go select error");
    while ($sql_rec_from = mysqli_fetch_assoc($sql_res_from)) 
    {
    	$from_email = $sql_rec_from['email'];
    	$from_name = $sql_rec_from['name'];	
    	$email_sign = $sql_rec_from['email_signature'];	
    }
    
    if (strlen($from_email)>0)
    {
		$email_to = $_POST['email_to'];
		$email_body = str_replace(array("\r\n", "\n\r", "\r", "\n"), "<br />", $_POST['email_body']);
		$email_subj = $_POST['email_subj'];
		
		$Destination = 'userprofile/userfiles/email_attaches';
		$New_EmlTemlImageName="";
		if(!isset($_FILES['email_attach_file_name']) || !is_uploaded_file($_FILES['email_attach_file_name']['tmp_name'])){
		  
		}
		else{
		    
		    $attach_name = $_FILES['email_attach_file_name']['name'];
		    move_uploaded_file($_FILES['email_attach_file_name']['tmp_name'], "$Destination/$attach_name");
			$email_attach_file = $Destination.'/'.urlencode($attach_name);		
		}


		if ($email_attach_file != "")
		{						
			$email_attach_file = "https://ourworks.co/".$email_attach_file;										
			//$email_attach_file = "https://88e19992.ngrok.io/ChrisS/".$email_attach_file;										
		}
		
		
		$htmlmsg .=  $email_body;
		
		
		if ($email_attach_file != '')
			$sent = sendMailWithAttachment($email_to,$email_subj,$htmlmsg,$from_email,$email_attach_file);
		else
			$sent = sendMail($email_to,$email_subj,$htmlmsg,$from_email);
		
		
		// Save email logs in Notes
		{
			$lead_id = $_POST['email_lead_id'];			
			$buyer = "select * from leads_info where auto_id=".$lead_id;
			$resb = mysqli_query($con, $buyer) or die(mysqli_error($con) . "11");	
		                                                	
			if ($recb = mysqli_fetch_assoc($resb))
			{
				$comments = $recb['note_comment'];
				$users = $recb['note_by'];
				$whens = $recb['note_date'];
			}else
			{
				$comments = "";
				$users = "";
				$whens = "";
			}
			
			$note_new_when = date('M d, Y, h:i a');
			$note_new_user = $_SESSION['user_login'];
			
			$note_new_comment = 'Email sent from '.$from_name.':<br />'.'<br />';
			$note_new_comment .= $email_body;
			
			if (strlen($comments)>1)
			{
				$comments .= ';'.$note_new_comment;
				$users .= ';'.$note_new_user;	
				$whens .= ';'.$note_new_when;	
			}else
			{
				$comments = $note_new_comment;
				$users = $note_new_user;
				$whens = $note_new_when;	
			}
			
			$sql_upd = "update leads_info set 
	 					  note_comment = '" . mysqli_real_escape_string($con, $comments) . "',
	 					  note_by = '" . mysqli_real_escape_string($con, $users) . "',
	 					  note_date = '" . mysqli_real_escape_string($con, $whens) . "',
						  cust_upd_dt= curdate()										  
				  where auto_id =" . $lead_id . "";
	   
	    	mysqli_query($con, $sql_upd) or die(mysqli_error($con));
		}
		
		
		
		if ($sent)
			$response['msg']='The email is sent successfully!';
		else
			$response['msg']='The email is failed!';
		
	}
}


echo json_encode($response);

// Requires cURL extension installed and SimpleXML.
function FetchUrl($url = '',$postFields = '')
{
	if (function_exists('curl_init'))
    {
        
    	if(!$ch = curl_init())
    	{
    	    die("Could not init cURL session.\n"); 
    	}
    
    	curl_setopt($ch, CURLOPT_URL, $url);
    	//curl_setopt($ch, CURLOPT_POST, true);
    	
    	if (!empty($postFields))
    	curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
    	
    	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    	curl_setopt($ch, CURLOPT_TIMEOUT, 60);
    	
    	$result = curl_exec($ch);
    	curl_close($ch);
     }
     else
     {
        die("cUrl not installed");
        //echo 'Cur'
        $result = file_get_contents($url);
        
     }
	
	return $result;
}

function sendMail($to,$subject,$message,$from) {   
	$message = str_replace("{{server}}","http://directfunder.com",$message);
	$header = "From:".$from." \r\n";
	$header .= "MIME-Version: 1.0\r\n";
	$header .= "Content-Type: text/html; charset=UTF-8\r\n";
	$retval = mail ($to,$subject,$message,$header);
	sleep(2);
	return $retval;
}
function sendMailWithAttachment($to,$subject,$message,$from,$attachfile) {   
	$file_name =$attachfile;
	$messagehtml = str_replace("{{server}}","http://directfunder.com",$message);
	$boundary = md5(date('r', time())); 
	$header .= "MIME-Version: 1.0\r\n";
	$headers = "From: ".$from."\r\nReply-To: ".$from; 
	$headers .= "\r\nContent-Type: multipart/mixed; boundary = $boundary\r\n\r\n"; 
	$encoded_content = chunk_split(base64_encode(file_get_contents($file_name))); 
	//define the body of the message. 
	//plain text 
    $body = "--$boundary\r\n";
    $body .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
    $body .= "Content-Transfer-Encoding: base64\r\n\r\n"; 
    $body .= chunk_split(base64_encode($message)); 
    
    //attachment
    $body .= "--$boundary\r\n";
    $body .="Content-Type: application/pdf; name=\"$file_name\"\r\n";
    $body .="Content-Disposition: attachment; filename=\"$file_name\"\r\n";
    $body .="Content-Transfer-Encoding: base64\r\n";
    $body .="X-Attachment-Id: ".rand(1000,99999)."\r\n\r\n"; 
    $body .= $encoded_content; 
	//send the email 
	$retval = @mail( $to, $subject, $body, $headers ); 
	return $retval;
}
	
//Function to check if the request is an AJAX request
function is_ajax() {
  return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
}
?>