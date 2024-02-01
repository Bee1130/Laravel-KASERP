<?php
session_start();

header('Content-type: text/html; charset=utf-8');

require_once("dbconnect.php");
require_once("Services/Twilio/Capability.php");		// Twilio Call
require_once("Services/Twilio.php");	

if(!function_exists('curl_version')) die("PHP must have cURL support for this script. 
                                        Ask hosting for it as it should be a 100% available library.");

  
  /**
  * Disable Magic Quotes
  */

   if (get_magic_quotes_gpc()) {
    $process = array(&$_GET, &$_POST, &$_COOKIE, &$_REQUEST);
    while (list($key, $val) = each($process)) {
        foreach ($val as $k => $v) {
            unset($process[$key][$k]);
            if (is_array($v)) {
                $process[$key][stripslashes($k)] = $v;
                $process[] = &$process[$key][stripslashes($k)];
            } else {
                $process[$key][stripslashes($k)] = stripslashes($v);
            }
        }
    }
    unset($process);
  }
 
?>