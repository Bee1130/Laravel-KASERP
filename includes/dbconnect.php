<?php

$db_name = "kaserp";



//$link=mysql_connect("localhost","root","") or die("Could not connect to database");
 $con=mysqli_connect("localhost","kaserp","mye47iMRwBfx4s3a", $db_name) or die("Could not connect to database");
// $link=mysql_connect("localhost:3306","kaserp_user","Tahoor_9858") or die("Could not connect to database");

// set character 
$link = mysqli_set_charset($con, "utf8");

mysqli_select_db($con, $db_name) or die("Could not select database");

date_default_timezone_set("America/New_York");

mysqli_query($con, "SET time_zone = '-05:00'");

set_time_limit(0);

function saveLog($rid,$type,$what,$action)
{
	$name = "";
	$assigned = "";
	$url="";
	$fa_icon = 'fa-comment';
	if ($type == 'Contact')
	{
		$sql_sel = "select name from contacts_info where auto_id=".$rid;
		$sql_res = mysqli_query($GLOBALS['con'], $sql_sel) or die(mysqli_error($GLOBALS['con']) . "11");	
		if ($sql_rec = mysqli_fetch_assoc($sql_res))
		{
			$name = $sql_rec['name'];	
		}
		
		
	}else if ($type == 'Company')
	{
		$sql_sel = "select name from companies_info where auto_id=".$rid;
		$sql_res = mysqli_query($GLOBALS['con'], $sql_sel) or die(mysqli_error($GLOBALS['con']) . "11");	
		if ($sql_rec = mysqli_fetch_assoc($sql_res))
		{
			$name = $sql_rec['name'];
			$assigned = $sql_rec['assigned'];	
		}
		$url = 'company.php?rid='.$rid.'&notify=1';
	}
	
	if ($what == 'Reminder')
		$fa_icon = 'fa-calendar';
	
	if (strlen($name)>0)
	{		
		$log = $what.' for '.$name.' was '.$action.' by '.$_SESSION['user_login'];
			
		$url = "contact.php?rid=".$rid."&notify=1";
		$sql_log = sprintf("insert into log_info (log_data,log_time,agent,log_type,customer_id,url,fa_icon) values ('%s',sysdate(),'%s','%s','%d','%s','%s')",mysqli_real_escape_string($GLOBALS['con'],$log),$_SESSION['user_login'],$type,$rid,$url,$fa_icon);
		mysqli_query($GLOBALS['con'],$sql_log) or die(mysqli_error($GLOBALS['con']));			
			
		
		
	}
}
function my_date_format($date,$format)
{
	if ($format == 'Y-m-d')
	{
		if ((int)(preg_replace("/[^0-9]*/s", "",$date)) == 0)
			$date=NULL;
		if (isset($date))
		{			
		   	$follow_up = explode("/",$date);
	       	$date = $follow_up[2] . '-' . $follow_up[0] . '-' . $follow_up[1];  		
		}else
		{
			$date = '0000-00-00';
		}
	}else if ($format == 'm/d/Y')
	{
		if ((int)(preg_replace("/[^0-9]*/s", "",$date)) == 0)
			$date=NULL;
		if (isset($date))
		{			
		   	$follow_up = explode("-",$date);
	       	$date = $follow_up[1] . '/' . $follow_up[2] . '/' . $follow_up[0];  		
		}
	}
	return $date;
}
function my_phone_format($phone)
{
	if (isset($phone))
		$phone = preg_replace("/[^0-9]*/s", "",$phone);
	return $phone;
}
// get phone like +1 (646) 757-1673
function my_phone_format2($phone)
{
	if (isset($phone))
		$phone = preg_replace("/[^0-9]*/s", "",$phone);
	
	
	$areaCode = substr($phone, 0, 3);
    $nextThree = substr($phone, 3, 3);
    $lastFour = substr($phone, 6, 4);

	$res = $areaCode.'-'.$nextThree.'-'.$lastFour;
	
	return $res;
}
function phpAlert($msg) 
{
    echo '<script type="text/javascript">alert("' . $msg . '")</script>';
} 
/* Pagination */
function pagination($adjacents, $targetpage, $total_pages, $limit, $page, $extra_parameters) {
 
    if ($page)
        $start = ($page - 1) * $limit;    //first item to display on this page
    else
        $start = 0;

    if ($page == 0)
        $page = 1;     //if no page var is given, default to 1.
    $prev = $page - 1;       //previous page is page - 1
    $next = $page + 1;       //next page is page + 1
    if ($limit!=0)
		$lastpage = ceil($total_pages / $limit);
	else
		$lastpage = 0;
    $lpm1 = $lastpage - 1;
    $pagination = "";
    if ($lastpage > 0) {
        $pagination.= "<div class=\"pagination\">";
        //previous button
        if ($page > 1)
            $pagination.= "<a href=\"$targetpage?total_pages=$total_pages&page=$prev" . $extra_parameters . "\">«prev</a>";
        else
            $pagination.= "<span class=\"disabled\">«prev</span>";

        //pages	
        if ($lastpage < 7 + ($adjacents * 2)) { //not enough pages to bother breaking it up
            for ($counter = 1; $counter <= $lastpage; $counter++) {

                if ($counter == $page)
                    $pagination.= "<span class=\"current\">$counter</span>";
                else
                    $pagination.= "<a href=\"$targetpage?total_pages=$total_pages&page=$counter" . $extra_parameters . "\">$counter</a>";
            }
        }
        elseif ($lastpage > 5 + ($adjacents * 2)) { //enough pages to hide some
            //close to beginning; only hide later pages
            if ($page < 1 + ($adjacents * 2)) {

                for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++) {
                    if ($counter == $page)
                        $pagination.= "<span class=\"current\">$counter</span>";
                    else
                        $pagination.= "<a href=\"$targetpage?total_pages=$total_pages&page=$counter" . $extra_parameters . "\">$counter</a>";
                }
                $pagination.= "...";
                $pagination.= "<a href=\"$targetpage?total_pages=$total_pages&page=$lpm1" . $extra_parameters . "\">$lpm1</a>";
                $pagination.= "<a href=\"$targetpage?total_pages=$total_pages&page=$lastpage" . $extra_parameters ."\">$lastpage</a>";
            }
            //in middle; hide some front and some back
            elseif ($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2)) {

                $pagination.= "<a href=\"$targetpage?total_pages=$total_pages&page=1" . $extra_parameters . "\">1</a>";
                $pagination.= "<a href=\"$targetpage?total_pages=$total_pages&page=2" . $extra_parameters . "\">2</a>";
                $pagination.= "...";
                for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++) {
                    if ($counter == $page)
                        $pagination.= "<span class=\"current\">$counter</span>";
                    else
                        $pagination.= "<a href=\"$targetpage?total_pages=$total_pages&page=$counter" . $extra_parameters ."\">$counter</a>";
                }
                $pagination.= "...";
                $pagination.= "<a href=\"$targetpage?total_pages=$total_pages&page=$lpm1" . $extra_parameters . "\">$lpm1</a>";
                $pagination.= "<a href=\"$targetpage?total_pages=$total_pages&page=$lastpage" . $extra_parameters . "\">$lastpage</a>";
            }
            //close to end; only hide early pages
            else {

                $pagination.= "<a href=\"$targetpage?total_pages=$total_pages&page=1" . $extra_parameters ."\">1</a>";
                $pagination.= "<a href=\"$targetpage?total_pages=$total_pages&page=2" . $extra_parameters ."\">2</a>";
                $pagination.= "...";
                for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++) {
                    if ($counter == $page)
                        $pagination.= "<span class=\"current\">$counter</span>";
                    else
                        $pagination.= "<a href=\"$targetpage?total_pages=$total_pages&page=$counter" . $extra_parameters . "\">$counter</a>";
                }
            }
        }
        //next button
        if ($page < $counter - 1)
            $pagination.= "<a href=\"$targetpage?total_pages=$total_pages&page=$next" . $extra_parameters . "\">next»</a>";
        else
            $pagination.= "<span class=\"disabled\">next»</span>";
        $pagination.= "</div>\n";
    }
    return $pagination;
}

// function to geocode address, it will return false if unable to geocode address
function geocode($address){
 
    // url encode the address
    $address = urlencode($address);
     
    // google map geocode api url
    $url = "http://maps.google.com/maps/api/geocode/json?address={$address}";
 
    // get the json response
    $resp_json = file_get_contents($url);
     
    // decode the json
    $resp = json_decode($resp_json, true);
 
    // response status will be 'OK', if able to geocode given address 
    if($resp['status']=='OK'){
 
        // get the important data
        $lati = $resp['results'][0]['geometry']['location']['lat'];
        $longi = $resp['results'][0]['geometry']['location']['lng'];
        $formatted_address = $resp['results'][0]['formatted_address'];
         
        // verify if data is complete
        if($lati && $longi && $formatted_address){
         
            // put the data in the array
            $data_arr = array();            
             
            array_push(
                $data_arr, 
                    $lati, 
                    $longi, 
                    $formatted_address
                );
             
            return $data_arr;
             
        }else{
            return false;
        }
         
    }else{
        return false;
    }
}

// get real values
function getRealValue($val)
{
	$ex = preg_replace("/[^0-9.]*/s", "",$val); 
	if (strlen($ex) == 0)
		return 0;
	$ex = floatval($ex);
    return $ex;
}
?>