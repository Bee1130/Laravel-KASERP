<?php 
@session_start();
//ob_start();

if(!isset($_SESSION['user_login']) and !isset($_COOKIE['cookie_login']))//session store admin name
{
    header("Location: index.php");//login in AdminLogin.php
}

require_once("includes/dbconnect.php");

$response = array();

$response['status']='Error';


$from_name = $_SESSION['user_login'];

	
/* -- Email Template -- */
$eml_white= 'default.png';
$eml_attach_file_sub_path = 'userprofile/userfiles/email_attaches/';
$eml_white_sub_path = 'userprofile/userfiles/email_white_labels/';

/* Email Template File upload */
$Destination = 'userprofile/userfiles/email_attaches';
$New_EmlTemlImageName="";
if(!isset($_FILES['user_bulk_email_attach_file_name']) || !is_uploaded_file($_FILES['user_bulk_email_attach_file_name']['tmp_name'])){
  
}
else{
     $RandomNum   = rand(0, 9999999999);
     $ImageName = str_replace(' ','-',strtolower($_FILES['user_bulk_email_attach_file_name']['name']));
     $ImageType = $_FILES['user_bulk_email_attach_file_name']['type'];
     $ImageExt = substr($ImageName, strrpos($ImageName, '.'));
     $ImageExt = str_replace('.','',$ImageExt);
     $ImageName = preg_replace("/\.[^.\s]{3,4}$/", "", $ImageName);
     $New_EmlTemlImageName = $ImageName.'-'.$RandomNum.'.'.$ImageExt;
     move_uploaded_file($_FILES['user_bulk_email_attach_file_name']['tmp_name'], "$Destination/$New_EmlTemlImageName");
}
	        
$sql_eml_templ = sprintf("select * from profile_info where user_username='%s'",$from_name);
$resb = mysqli_query($con, $sql_eml_templ) or die(mysqli_error($con));	
$eml_templ_white = "";
if( mysqli_num_rows($resb) > 0) 
{
	$recb = mysqli_fetch_assoc($resb);
	$eml_templ_att = $recb['eml_templ_att'];
	$eml_templ_cont = $recb['eml_templ_cont'];
	$eml_templ_subj = $recb['eml_templ_subj'];
	$eml_templ_white = $recb['eml_templ_white'];
}
if (isset($recb) and isset($recb['user_tw_number']))
	$recb['user_tw_number'] = '('.substr($recb['user_tw_number'],0,3).') '.substr($recb['user_tw_number'],3,3).'-'.substr($recb['user_tw_number'],6,4);
if ($eml_templ_white == "")
 $eml_templ_white = 'default.png';
$eml_white = $eml_white_sub_path.$eml_templ_white;

$eml_attach_file = '';
if ($New_EmlTemlImageName !="")
{
	$eml_attach_file = trim($New_EmlTemlImageName);
	$eml_attach_file =$eml_attach_file_sub_path.$eml_attach_file;
}		

/* -------------- Email Signature Template --------------- */
if (isset($recb))
{
	$eml_sig_mobile_ph=$recb['eml_sig_mobile_ph'];
	$eml_sig_office_ph=$recb['eml_sig_office_ph'];
    $eml_sig_eml1=$recb['eml_sig_eml1'];
    $eml_sig_eml2=$recb['eml_sig_eml2'];
    $eml_sig_buss_addr=$recb['eml_sig_buss_addr'];
    $eml_sig_fax=$recb['eml_sig_fax'];
    $eml_sig_logo=$recb['eml_sig_logo'];
    $eml_sig_photo=$recb['eml_sig_photo'];	        
	
    if (!isset($eml_sig_logo))
    {
		$eml_sig_logo = 'userprofile/userfiles/email_signatures/logos/default.gif';
	}
	if (!isset($eml_sig_photo))
    {
		$eml_sig_photo = 'userprofile/userfiles/email_signatures/photos/default.gif';
	}
	// Read image path, convert to base64 encoding
	$photo_imgData = base64_encode(file_get_contents($eml_sig_photo));
	$logo_imgData = base64_encode(file_get_contents($eml_sig_logo));

	// Format the image SRC:  data:{mime};base64,{data};
	$src_photo = 'data:'.'image/jpeg'.';base64,'.$photo_imgData;
	$src_logo = 'data:'.'image/jpeg'.';base64,'.$logo_imgData;
	
}

/* --------------------------------------------- */



$from_name = $_SESSION['user_login'];

$response['email_body'] = '';

	

//if(isset($_POST["email_from"]) && !empty($_POST["email_from"]))
{
	
		
//	$from_email = trim($_POST["email_from"]);
	//if (isset($_POST["email_to"]) && !empty($_POST["email_to"])) 
 	{
 		//$email_to = trim($_POST["email_to"]);
    	//$eml_to_tok = explode(';',$email_to);
    	//$eml_to_cnt = count($eml_to_tok);
		  
	    if (isset($_POST["bulk_email_body"]) && isset($_POST["bulk_email_subj"])) 
	    { 
	    	//send SMS
	    	
	    	
        	$email_body = $_POST["bulk_email_body"];
    		$email_subj = $_POST["bulk_email_subj"];
    		$to_name="";
    		/*$sql_sel_cus = sprintf("select p_fl_nm from customer_info where (p_eml1 like '%s') or (p_eml2 like '%s') or (p2_eml like '%s') or (p3_eml like '%s') limit 1",$eml_to_tok[0],$eml_to_tok[0],$eml_to_tok[0],$eml_to_tok[0]);
			$resb_sel_cus = mysqli_query($con, $sql_sel_cus) or die(mysqli_error($con));	
			if ($recb_cus = mysqli_fetch_assoc($resb_sel_cus))
			{
				$to_name = $recb_cus['p_fl_nm'];
			}*/

            // Read image path, convert to base64 encoding
            if ($eml_attach_file != '')
            {
				$attach_imgData = base64_encode(file_get_contents($eml_attach_file));
				$attach_src_photo = 'data:'.'image/jpeg'.';base64,'.$attach_imgData;
				$email_body .='<img src="'.$attach_src_photo.'">';
			}
					
			$email_body .= "<br>";
			$email_body .= "<br>";
			$email_body .= "<table cellpadding='0' cellspacing='0' width='450px' style='overflow:auto; overflow-x:scroll;'>";
			
			$email_body .= "<tr>";
			$email_body .= "<td rowspan='5'>";
			
			$email_body .='<img src="'.$src_photo.'">';
			
			$email_body .= "</td>";
			
			
			$email_body .= "<td colspan='2'>";
			$email_body .= "<span style='margin:0px;font-size:12px;font-weight:bold'>";
			if (($_SESSION['user_group'] == 'Admin')||($_SESSION['user_login'] == 'Duc'))
				$email_body .= $recb['user_firstname'].' '.$recb['user_lastname'].' | '.'Advisor';
			else
				$email_body .= $recb['user_firstname'].' '.$recb['user_lastname'].' | '.$_SESSION['user_group'];
			$email_body .= "</span>";
			$email_body .= "</td></tr>";
			
			$email_body .= "<tr><td colspan='3' style='padding: 0px;'>";
			$email_body .="<img src='".$src_logo."'>";
			
			$email_body .= "</td></tr>";	
			
			$email_body .= "<tr style='font-weight:bold;font-size:10px;'><td colspan='3'>".'<span>'.$recb['eml_sig_buss_addr'].'</span>';
			$email_body .= "</td></tr>";	
			
			$email_body .= "<tr style='font-weight:bold;font-size:10px;'><td colspan='3'>".'<span>'.'Office:'.'</span>'.$recb['eml_sig_office_ph'].' | '.'<span style="font-weight:bold">'.'Mobile:'.'</span>'.$recb['eml_sig_mobile_ph'].' | '.'<span style="font-weight:bold">'.'Fax:'.'</span>'.$recb['eml_sig_fax'];
			$email_body .= "</td></tr>";	
			
			if (isset($recb['eml_sig_eml2']))
			{
			  $email_body .= "<tr style='font-weight:bold;font-size:10px;'><td colspan='2'>".'<span>'.$recb['eml_sig_eml1']. ' | ' .$recb['eml_sig_eml2'].'</span>';
				
			}else
			{
				$email_body .= "<tr style='font-weight:bold;font-size:10px;'><td colspan='2'>".'<span>'.$recb['eml_sig_eml1'].'</span>';
			}
			$email_body .= "</td></tr></table>";		
			
			//if ($eml_to_cnt>1)
				$email_body = $_POST["bulk_email_sal"].' '.$to_name.','.'<br>'.$email_body;
					
			$response['status'] = 'Success';				
			$response['email_body']	= $email_body;			
   		}   
	}
}/*else
{
	$response['status'] = 'From Email Error';
}*/


echo json_encode($response);


//Function to check if the request is an AJAX request
function is_ajax() {
  return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
}
?>