<?php
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
   function readTemplate($path) {
	   $cnt ="";
	   $file = @fopen($path, "r") or die("Unable to open file!");
		$cnt = fread($file,filesize($path));
		fclose($file);
		return $cnt;
   }
   function get_client_ip() {
    $ipaddress = '';
    if (getenv('HTTP_CLIENT_IP'))
        $ipaddress = getenv('HTTP_CLIENT_IP');
    else if(getenv('HTTP_X_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
    else if(getenv('HTTP_X_FORWARDED'))
        $ipaddress = getenv('HTTP_X_FORWARDED');
    else if(getenv('HTTP_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_FORWARDED_FOR');
    else if(getenv('HTTP_FORWARDED'))
       $ipaddress = getenv('HTTP_FORWARDED');
    else if(getenv('REMOTE_ADDR'))
        $ipaddress = getenv('REMOTE_ADDR');
    else
        $ipaddress = 'UNKNOWN';
    return $ipaddress;
}
?>
