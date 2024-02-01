<?php
/****-------------------------------------------------------------------**************************	
		Purpose 	: 	Admin home page where the user will first get after login
		Project 	:	Sales Contact DB	
	 	Developer 	: 	Wilson Tan
	 	Create Date : 	27/04/2012   
****-------------------------------------------------------------------************************/
session_start();
if(!isset($_SESSION['user_login']) and !isset($_COOKIE['cookie_login']))//session store admin name
{
	header("Location: index.php");//login in AdminLogin.php
}
require_once("includes/dbconnect.php");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Sales Contact DB</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="css/css.css" rel="stylesheet" type="text/css"/>
</head>
<body>
<table width="100%">
<tr><td align="center">
<table width="90%">
<!--heading space-->
        <tr> 
          <td height="100px"> 
            <?php include("header.php");?>
          </td>
        </tr>
        <!--heading space end-->
<!--header menu space-->
<tr>
          <td height="50px" class="up_menu"><div align="center" class="hyperlink"><?php 
include("admheadmenu.php");
?></div></td>
        </tr>
<!--header menu space end-->
<tr><td height="50px"></td></tr>
<tr><td height="473" valign="top">
		<table width="100%">
		<tr>
		<td width="163" height="459" class="left_bar"></td>
		<td width="281"></td>
		<td width="439" valign="top">
				<br><br><br>
				<!--sign in to the system-->
				
				<!--sign in to the system end-->
		</td>
		<td width="6" class="right_bar"></td>
		</tr>
		</table>

</td></tr>
<tr><td height="14"></td></tr>
<!-- footer-->
		<tr> 
          <td><?php include("footer.php");?></td>
        </tr>
		<!-- footer end-->
</table>

</td></tr>
</table>
</body>
</html>
