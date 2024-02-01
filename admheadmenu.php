<?php

/****-------------------------------------------------------------------**************************	

		Purpose 	: 	This page caontains header menu with the syatem navigation

		Project 	:	Sales Contact DB	

	 	Developer 	: 	Wilson Tan

	 	Create Date : 	27/04/2012   

****-------------------------------------------------------------------************************/

// session_start();

if(!isset($_SESSION['user_login']) and !isset($_COOKIE['cookie_login']))//session store admin name

{

	header("Location: index.php");//login in AdminLogin.php

}

require_once("includes/dbconnect.php");

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>

<link rel="stylesheet" type="text/css" href="css/systemshm.css" />

<script type="text/javascript" src="jss/systemshm.js">

</script>

<body>

<?php

if($_SESSION['user_group']=="Admin")

{

?>

<div class="horizontalcssmenu">

<ul id="cssmenu1">

	 

    <li><a href="contacts.php">Search</a></li> 

    <li><a href="contact.php?action=add">Add</a></li>

	<li><a href="notepad.php">Note Pad</a></li>

	<li><a href="#">Mail</a> 

      <ul>

        <li><a href="draftmail.php">Make Draft</a></li>

        <li><a href="sendmail.php">Send Mail</a></li>

        <li><a href="sendquote.php">Send Quotes</a></li>

        <li><a href="sentmail.php">Sent Mail</a></li>

      </ul>

    </li>

    <li><a href="#">Profile</a> 

      <ul>

        <li> <a href="passchange.php">Change Password</a></li>

        <li> <a href="addagent.php">Add Sales Agent</a></li>

        <li> <a href="addlease.php">Add Lease Login</a></li>

        <li> <a href="changeaccess.php">Change Login Credential</a></li>

      </ul>

    </li>
    
    <li><a href="contacts.php?rst=1">Home</a></li> 
	<li><a href="adminlogout.php">Logout</a></li>

</ul>

</li>

</ul>

<br style="clear: left;"/>

</div>

<?php

}

else

{

?>

<div class="horizontalcssmenu">

<ul id="cssmenu1">

	 

    <li><a href="contacts.php">Search</a></li>

	<li><a href="notepad.php">Note Pad</a></li>

	<li><a href="#">Mail</a> 

      <ul>

        <li><a href="draftmail.php">Make Draft</a></li>

        <li><a href="sendmail.php">Send Mail</a></li>

        <li><a href="sendquote.php">Send Quotes</a></li>

        <li><a href="sentmail.php">Sent Mail</a></li>

      </ul>

    </li>

    <li><a href="#">Profile</a> 

      <ul>

        <li> <a href="passchange.php">Change Password</a></li>

      </ul>

    </li>

	<li><a href="adminlogout.php">Logout</a></li>

</ul>

</li>

</ul>

<br style="clear: left;"/>

</div>

<?php

}

?>

</body>

</html>