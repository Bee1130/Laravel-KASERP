<meta charset="utf-8"/>
<meta http-equiv="X-UA-Compatible" content="IE=edge"/>
<meta name="viewport" content="width=device-width, initial-scale=1"/>

<title>Kas ERP </title>


<!-- Bootstrap -->
<!--<meta name="viewport" content="width=device-width, initial-scale=1"/> -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css"/>
<link href="css/font-awesome.css" rel="stylesheet"/>

<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js" ></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js" ></script>		
<link rel="shortcut icon" href="images/fav.png" type="image/x-icon" />
	
	<link rel="icon" type="image/png" sizes="32x32" href="images/fav.png">
	<link rel="icon" type="image/png" sizes="16x16" href="images/fav.png">

<link href="css/jquery.datetimepicker.css" rel="stylesheet" />
<script src="js/jquery.datetimepicker.js"></script>
		
		


<style type="text/css">

.nav>li>a:focus, .nav>li>a:hover {
    background: #347362 !important;
}

.nav .open>a, .nav .open>a:focus, .nav .open>a:hover {
    background: #347362 !important;
}

.dropdown-menu>li>a:focus, .dropdown-menu>li>a:hover {
    background-color: #347362 !important;
}
ul.dropdown-menu {
    background: #edcb50 !important;
}



.dropdown-submenu {
    position: relative;
}
.dropdown-submenu>a:after {
    display: block;
    content: " ";
    float: right;
    width: 0;
    height: 0;
    border-color: transparent;
    border-style: solid;
    border-width: 5px 0 5px 5px;
    border-left-color: black;
    margin-top: 5px;
    /* margin-right: -10px; */
}
	a{
		font-weight: normal;
	}
	.row{
		margin: auto;
	}
	body {
	    background-color: #F3F3F3;
	    /*background: #d9e0e7;*/
	    font-size: 12px;
	    font-family: 'Open Sans',"Helvetica Neue",Helvetica,Arial,sans-serif;
	    color: #333;
	}
	label{
		font-weight: 400;
	}
	#page-header {
	    padding-top: 10px;
	}
	.container {	  		  
    	padding: 0px;
    	margin: auto;	  	
	}
 	.container-fluid{
	  	width: 100%;
	    padding: 0px;
	    margin:0px;
  	}
	@media (max-width: 1200px) {
	  .container {	  	
	  	width: 100%;  	
	  }
	}
	@media (min-width: 1201px) {
	  .container {	  	
	  	width: 100%; 	   	
	  }
	}
	
	/* Panel */
	.panel-inverse>.panel-heading {
	    background: #edcb50 !important;
	    color: white;
	}
	.panel-toolbar {
	    border-bottom: 1px solid #eee;
	    padding: 10px 15px;
	}
	
	.panel-footer, .panel-toolbar {
	    background: #fff;
	}
	
	.panel-heading+.slimScrollDiv, .panel-heading+.table, .panel-toolbar {
	    border-top: 1px solid #eee;
	}
	/* Dashboard Table */
	.newtable, .fbtable {
	    border-collapse: collapse;
	    border-spacing: 0;
	    margin-bottom: 10px;
	    margin-top: 5px;
	    border-left: 1px dotted #f5f5f5;
	    border-right: 1px dotted #f5f5f5;
	   
	}

	/* My Panel */
	.my-table-head{
		text-align: center;  		
		background-color: #6099B7;
    	color: white;
  		
	}
	.my-table-head a{
		color:white;
	}
	.my-static-div{
		border-radius: 10px;		
		border-color:#ddd;		
		border: 1px solid #ddd;		
		box-shadow: 1px 1px 1px rgba(0, 0, 0, 0.3);
		background-color: #fff;
		margin-bottom:10px;
		padding:10px;
	}
	.my-panel{
		margin: 0px;
	    padding: 0px;
	    border: 0px;
	    -webkit-box-shadow: 0 0px 0px rgba(0,0,0,.05);
	}
	.my-panel-body{
		padding: 6px;
	}
	.my-panel-heading{
		
		font-weight: bold;
		font-size: 16px;
		border-bottom: 1px solid #ddd;	
		padding: 5px;
	}
	
	/* Home Page */
	.my-home-content{
		margin: auto;
		width: 99%;
	}
	
	
	
	
	.badge, .label {
	   
	    font-weight: 600;
	}
	
	.badge {
	    display: inline-block;
	    min-width: 10px;
	    padding: 3px 7px;
	    font-size: 12px;
	    font-weight: 700;
	    
	    color: #fff;
	    text-align: center;
	    white-space: nowrap;
	    vertical-align: middle;
	    background-color: #777;
	    border-radius: 10px;
	}
	.badge {
	    line-height: 1.25;
	}
	
	/* -- Qualification Guidelines Input Text -- */
	.qauli-text{ 
		outline:none;
		width:100%;
		border:0px;
		text-align: center;
	}
	

	/*-- Pagination --*/
	@media screen and (max-width: 768px) {
		div.pagination
		{
			padding: 2px;
		    margin: 2px;
		}

		div.pagination a
		{
		    padding: 2px;
		    margin: 2px;
		    border: 1px solid #AAAADD;

		    text-decoration: none; /* no underline */
		    color: #000099;
		 }
		 div.pagination a:hover, div.pagination a:active
		  {
		    border: 1px solid #000099;
		    color: #000;
		}
		div.pagination span.current 
		{
			padding: 2px;
			margin: 2px;
			border: 1px solid #3399cc;
			font-weight: bold;
			background-color: #3399cc;
			color: #FFF;
		}
		div.pagination span.disabled
		{
			padding: 2px;
			margin: 2px;
			border: 1px solid #e9eaef;
			
			color: #e9eaef;
		}
	}
	@media screen and (min-width: 768px) {
		div.pagination
		{
			padding: 5px;
		    margin: 5px;
		}

		div.pagination a
		{
		    padding: 5px;
		    margin: 5px;
		    border: 1px solid #AAAADD;

		    text-decoration: none; /* no underline */
		    color: #000099;
		 }
		 div.pagination a:hover, div.pagination a:active
		  {
		    border: 1px solid #000099;
		    color: #000;
		}
		div.pagination span.current 
		{
			padding: 5px;
			margin: 5px;
			border: 1px solid #3399cc;
			font-weight: bold;
			background-color: #3399cc;
			color: #FFF;
		}
		div.pagination span.disabled
		{
			padding: 2px;
			margin: 2px;
			border: 1px solid #e9eaef;
			
			color: #e9eaef;
		}
		
	
	}			
	
	/*-- Login Page --*/

	.main {
	    max-width: 400px;
	    margin: 0 auto;
	    padding-top: 200px;
	  }
	.login-or {
	    position: relative;
	    font-size: 18px;
	    color: #aaa;
	    margin-top: 10px;
	            margin-bottom: 10px;
	    padding-top: 10px;
	    padding-bottom: 10px;
	  }
	.span-or {
	    display: block;
	    position: absolute;
	    left: 50%;
	    top: -2px;
	    margin-left: -25px;
	    background-color: #fff;
	    width: 50px;
	    text-align: center;
	  }
	.hr-or {
	    background-color: #cdcdcd;
	    height: 1px;
	    margin-top: 0px !important;
	    margin-bottom: 0px !important;
	  }
	 h3 {
	    text-align: center;
	    line-height: 300%;
	  }

	.form-control-with-text {
	    margin-bottom: 5px;
	    display: inline-block;
	    width: 40%;
	    height: 34px;
	    padding: 6px 12px;
	    font-size: 14px;
	    line-height: 1.42857143;
	    color: #edcb50;
	    background-color: #c7c8ed;
	    /* background-image: none; */
	    /* border: 1px solid #ccc; */
	    border-radius: 4px;
	    -webkit-box-shadow: inset 0 1px 1px rgba(0, 0, 0, .075);
	    box-shadow: inset 0 1px 1px rgba(0, 0, 0, .075);
	    -webkit-transition: border-color ease-in-out .15s, -webkit-box-shadow ease-in-out .15s;
	    -o-transition: border-color ease-in-out .15s, box-shadow ease-in-out .15s;
	    /* transition: border-color ease-in-out .15s, box-shadow ease-in-out .15s; */
	}

	/* Last Login, Duration, Calls Made/Connected, ... , More Information */
	@media (max-width: 767px) {
	  .head-box{
	    color: #fff;
	    width:100px;
		height:52px;   
	    float:left;
	    margin: 10px 5px;
	    font-weight:bold;
	    font-size:12px;
	    font-family: sans-serif;
	    padding: 13px 0 13px 0 ;
	    border: 1px solid transparent;
	    border-radius: 6px;
	    border-bottom: 1px solid transparent;    
	    box-shadow: 0 1px 1px rgba(0,0,0,.05);
	  }
	}
	@media (min-width: 767px) {
		.head-box {
		    color: #fff;
		    width: 100%;
		    height: 52px;
		    /* float: left; */
		    /* margin: 10px 5px; */
		    font-weight: bold;
		    font-size: 14px;
		    font-family: sans-serif;
		    padding: 13px 0 13px 0;
		    border: 1px solid transparent;
		    border-radius: 6px;
		    border-bottom: 1px solid transparent;
		    box-shadow: 0 1px 1px rgba(0,0,0,.05);
		}	
	 	.mobile-info-box {
		    width: 100%;
		}
	}

	/* dashboard icon (save, prev, next, print) */
	.dashbord{
		width:100%;
		float:left;
		margin-top:5px;
	}
	.dashbord-content{
		width:99%;
		float:left;
		border:solid 1px #248bc3;
		margin:1px;
	}
	.dashbord-icon-outer{
		width:100%;
		float:left;
		background:url(../buyer_details_img/dash-bg.jpg) repeat-x left top;
		padding:3px 0;
	}
	.dashbord-icon{
		
		float:left;
		margin:0 5px;
		font-size: 30px;
	}
	.dashbord-icon-text{
		float:left;
		margin:0 5px;
		font:bold 12px ;
		color:#000000;
		text-align:center;
		padding:3px 0 0 0;
	}
	
	@media (max-width: 767px) {
	  .my-search-box{
	  	width:70% !important;
	  }
	}
	@media (min-width: 767px) {
	 .my-search-box{
	  	width:90% !important;
	  }
	}
			

	/* Menu Separator */
	.divder-new {
	    height: 1px;
	    margin: 0px;
	    overflow: hidden;
	    background-color: #e5e5e5;
	}
	
	/* Menu */
	.logo {
	    margin-left: 15px;
	    width: 50px;
	    height: 50px;
	    /*background-image: url(images/logo_small.png);*/
	    background-image: url(images/small_log.png);
	    /* background-size: cover; */
	    background-size: 100% 100%;
	    margin-bottom: 10px;
	}
	.floatL {
	    float: left;
	}
	.floatR {
	    float: right;
	}
	.navbar-inverse .navbar-nav>li>a {
	    color: white;
	}
	.navbar-nav>li>a {
	    padding-top: 10px; 
	    padding-bottom: 10px; 
	}

	.navbar{
		margin-bottom: 0px;
		border: 0px;
	}
	.navbar-brand {
	    color: #0d3a4f;
	    font-family: "Lucida Grande", "Lucida Sans Unicode", "Helvetica Neue", Helvetica, Arial, Verdana, sans-serif;
	    font-size: 30px;
	    margin: 0;
	    line-height: 20px;
	    text-shadow: 0px 1px 0px #fff;
	    font-weight: bold;
	}
	
	#util-nav {
	    color: #668899;
	    text-shadow: 0 1px 0 #fff;
	    position: absolute;
	    right: 10px;
	    top: 5px;
	    line-height: 25px;
	    font-size: 16px;
	}
	#main-nav {
	    width: 100%;
	    display: block;
	    height: 44px;
	    font-size: 14px;
	    position: relative;
	    z-index: 999;
	    background: #edcb50 !important;
	    /* background: -webkit-gradient(linear, left top, left bottom, from(#434343), to(#191919)); */
	    background: -moz-linear-gradient(top, #434343, #191919);
	    display: block;
	    -moz-border-radius: 5px 5px 0 0;
	    -webkit-border-top-left-radius: 5px;
	    -webkit-border-top-right-radius: 5px;
	    -khtml-border-top-left-radius: 5px;
	    -khtml-border-top-right-radius: 5px;
	    border-radius: 5px;
	    border-top: 1px solid #edcb50;
	    border-left: 1px solid #edcb50;
	    border-right: 1px solid #edcb50;
	}
	.my-menu-search{
		margin: auto;
/*	    margin-right: 20px;*/
    	/*width: 250px;*/
	    margin-top:2px;
	}
	.navbar-form .btn-search {
	    position: absolute;
	    right: 15px;
	    top: 2px;
	    height: 30px;
	    padding-top: 5px;
	    padding-bottom: 5px;
	    border: none;
	    background: 0 0;
	    -webkit-border-radius: 0 30px 30px 0;
	    -moz-border-radius: 0 30px 30px 0;
	    border-radius: 0 30px 30px 0;
	}
	.navbar-form {
	    margin: 12px 0;
	    margin-top: 2px;
	}
	.fade .navbar-form .form-control {
	    -webkit-animation: none;
	}
	
	.navbar-form .form-control {
	    /* width: 200px; */
	    /* padding: 5px 15px; */
	    /* height: 30px; */
	    -webkit-border-radius: 30px;
	    -moz-border-radius: 30px;
	    /* border-radius: 30px; */
	}
	.dropdown-header
	{
		font-size: 15px;
    	font-weight: 700;
	    color: #242a30;
	    /* padding: 10px; */
	    margin: 10px;
    }
    .dropdown-menu.dropdown-menu-lg.nav>li>a
    {
        padding: 0;
        background: 0 0;
        line-height: 24px;
    }
	.dropdown-menu.media-list>.media .media-object {
	    height: 36px;
	    width: 36px;
	    line-height: 36px;
	    font-size: 14px;
	    color: #fff;
	    text-align: center;
	    -webkit-border-radius: 50%;
	    -moz-border-radius: 50%;
	    border-radius: 50%;
	}
	.dropdown-menu.media-list {
        max-width: 500px;
        padding: 0
    }.dropdown-menu.media-list p {
        text-overflow: ellipsis;
        overflow: hidden;
        margin-bottom: 4px;
        max-width: 200px
    }.dropdown-menu.media-list.dropdown-header {
        padding: 10px 20px!important;
        background: #fafafa;
    }.dropdown-menu.media-list > .media {
        margin-top: 0;
        border-top: 1px solid #eee;
        border-bottom: 1px solid #eee;
        margin-bottom: -1px
    }.dropdown-menu.media-list > .media > a {
        display: block;
        padding: 10px 20px!important;
    }.dropdown-menu.media-list > .media.media-left {
        padding-right: 10px;
    }.dropdown-menu.media-list > .media.media-right {
        padding-left: 10px;
    }.dropdown-menu.media-list > .media.media-object {
        height: 36px;
        width: 36px;
        line-height: 36px;
        font-size: 14px;
        color: #fff;
        text-align: center;
        -webkit-border-radius: 50%;
        -moz-border-radius: 50%;
        border-radius: 50%;
    }
	.navbar-nav>li>.dropdown-menu.media-list .media-heading {
	    font-weight: 600;
	}
	.navbar-nav > li > .dropdown-menu.media-list.media-heading {
        font-weight: 600
    }
    .navbar-nav > li > a.label {
        position: absolute;
        top: 7px;
        right: 3px;
        display: block;
        background: #ff5b57;
        line-height: 12px;
        font-weight: 300;
        padding: .3em .6em;
        -webkit-border-radius: 20px;
        -moz-border-radius: 20px;
        border-radius: 20px;
    }
    .navbar-user img {
        float: left;
        width: 30px;
        height: 30px;
        margin: -5px 10px 0 0;
        -webkit-border-radius: 30px;
        -moz-border-radius: 30px;
        border-radius: 30px;
    }
    .navbar-default.navbar-nav.open.dropdown-menu > li > a {
        color: #333;
    }
    .navbar .navbar-nav>li.divider
    {
    	height:34px;
    	margin-top:10px;
    	background:#e2e7eb;
    	width: 1px;
    }
    .navbar.navbar-inverse.navbar-nav > li.divider {
        background: #3F4B55
    }
    .sidebar,.sidebar-bg
    {
    	top:0;
    	bottom:0;
    	background:#2d353c;
    	left: 0;
    	width: 220px;
    }
    .media a: not(.btn).media-heading: focus, .media a: not(.btn).media-heading: hover, .media a: not(.btn): focus, .media a: not(.btn): focus.media-heading, .media a: not(.btn): hover, .media a: not(.btn): hover.media-heading 
    {
	    color: #242a30;
	    text-decoration:none
	}
	.bwizard-steps li a,ul.tagit li.tagit-choice .tagit-close:focus,ul.tagit li.tagit-choice .tagit-close:hover
	{
		text-decoration:none!important
	}
	.media-list.media-list-with-divider>li+li{
		border-top:1px solid #eee;
		padding-top: 20px;
	}
	.f-s-8 {
   	 font-size: 8 px!important
	}
	.f-s-9 {
	    font-size: 9 px!important
	}.f-s-10 {
	    font-size: 10px!important
	}.f-s-11 {
	    font-size: 11px!important
	}.f-s-12 {
	    font-size: 12 px!important
	}.f-s-13 {
	    font-size: 13 px!important
	}.f-s-14 {
	    font-size: 14 px!important
	}.f-s-15 {
	    font-size: 15 px!important
	}.f-s-16 {
	    font-size: 16px!important
	}.f-s-17 {
	    font-size: 17 px!important
	}.f-s-18 {
	    font-size: 18 px!important
	}.f-s-19 {
	    font-size: 19 px!important
	}.f-s-20 {
	    font-size: 20px!important
	}.text-center {
	    text-align: center!important
	}.text-left {
	    text-align: left!important
	}.text-right {
	    text-align: right!important
	}.pull-left {
	    float: left!important
	}.pull-right {
	    float: right!important
	}.pull-none {
	    float: none!important
	}.f-w-100 {
	    font-weight: 100!important
	}.f-w-200 {
	    font-weight: 200!important
	}.f-w-300 {
	    font-weight: 300!important
	}.f-w-400 {
	    font-weight: 400!important
	}.f-w-500 {
	    font-weight: 500!important
	}.f-w-600 {
	    font-weight: 600!important
	}.f-w-700 {
	    font-weight: 700!important
	}
	@media (min-width: 768px) {
	  .mobile-my-menu {
	    display: none !important;
	  }
	  
	  /*.mobile-info-box {			    
	    width:20% !important;
	  }*/
	  
	  .head-box-layer{
	  	width: 60%;
    	margin: auto;
	  	
	  }
	  .desktop-my-menu {
	    display:auto !important;
	  }
	  .mobile-container {
	  	 width: 99% !important;
	  }
	}
	@media (max-width: 767px) {
	   .mobile-my-menu  {
	    display:auto !important;
	    font-size:18px !important;
	  }
	  
	  .head-box-layer{
	  	width:100%;
	  	margin: auto;
	  }
	  
	  .desktop-my-menu {
	    display: none!important;
	  }
	  
	  .mobile-info-box {
	    width:30% !important;
	  }
	  .mobile-container {
	  	margin:1px;
	  	width:100% !important;;
	  	padding-left:5px;
	  	padding-right:5px
	  }
	   .mobile-row{
	  	margin: 1px;
	  }
	}
	@media (max-width: 400px) {
	   .mobile-my-menu  {
	    display:auto !important;
	    font-size:18px !important;
	  }
	  
	  .head-box-layer{
	  	width:100%;
	  }
	  
	  .desktop-my-menu {
	    display: none!important;
	  }
	  
	  .mobile-info-box {
	    width: 32% !important;
	    padding: 0px;
	    margin-left: 1px;
	    margin-right: 1px;
	  }
	  
	  .container-fluid{
	  	width: 99% !important;
	    padding: 5px;
	    margin:0px;
	  }
	   .mobile-container {
	  	margin:1px;
	  	width:100% !important;
	  	padding-left:5px;
	  	padding-right:5px
	  }
	  .mobile-row{
	  	margin: 1px;
	  }
	}

	/* Main Content */
	@media (min-width: 768px) {
	  #my-main-content{
	  	width:95%;
	  	margin: auto;
	  }
	  #my-main-content-left{
	    width: 260px;
	    float: left;
	    border: solid 1px #248bc3;
	    margin : 32px 1px 1px 1px;
	    font-size: 15px;
	  }
	   
	  .my-switch-vscroll{
	  	overflow-y: hidden !important;	
	  }
	  
	  #my-main-content-right{
	  	/*margin:0 0 0 275px;*/
	  	font-size:16px !important;
	  	font-weight:600 !important;
	  	color:black !important;
	  	margin: auto;
	  	width: 95%;
	  }
	  #my-main-content-right-content{
	  	width: 100%;
	    float: left;
	  }
	  .my-main-content-right-table{
	  	/*font-size:16px !important;*/
	  }
	}
	@media (max-width: 768px) {
	  #my-main-content{
	  	width:100%;  
	  }
	  #my-main-content-left{
	  }
	  .my-switch-vscroll{
	  	
	  }
	  #my-main-content-right{
	  	margin:0px;
	  	font-size:20px !important;
	  	font-weight:100 !important;
	  }
	  #my-main-content-right-content{
	  	width: 100%;
	    float: left;
	  }
	  .my-main-content-right-table{
	  /*	font-size:20px !important;
	  	font-weight:100;*/
	  }
	   
	}

	/* form control without padding and low height */
	.my-form-control {
	   /* padding:0px !important;*/
	    height:30px !important;
	   /* font-size:18px !important;*/
	    font-weight:300 !important;
	    color:black !important;
	}
	.my-form-control-left-text {
		margin-top:3px !important;
		padding-left:2px !important;
		padding-right:2px !important;
	    
	}

	/* text area without padding and low height */
	.my-textarea-control {
	    padding:0px !important;
	    font-size:18px !important;
	    font-weight:300 !important;
	    color:black !important;
	}

	/* table font with black */
	.my-table-font {
	   color:black !important;
	   font-weight:300 !important;
	   font-size:15px;
	}
	.my-dashboard-table-head {
	    background-color: #E6E4E4;
	    height: 25px;
	    font-weight: 400;
	    font-size: 13px;
	}
	.my-dashboard-table-font {
	    font-size: 12px;
	}

	/* Scrollable Drop Menu */
	.my-scrollable-menu {
	    height: auto;
	    width:300px;
	    max-height: 350px;
	    overflow-x: hidden;
	    font-size:18px !important;
	}

	/* Seach Box */
	
	.dropdown.dropdown-lg .dropdown-menu {
	    margin-top: -1px;
	    padding: 6px 20px;
	     font-size : 16px !important;
	}

	.input-group-btn .btn-group {
	    display: flex !important;
	}
	.btn-group .btn {
	    border-radius: 0;
	    margin-left: -1px;
	}
	.btn-group .btn:last-child {
	    border-top-right-radius: 4px;
	    border-bottom-right-radius: 4px;
	}
	.btn-group .form-horizontal .btn[type="submit"] {
	  border-top-left-radius: 4px;
	  border-bottom-left-radius: 4px;
	}
	.form-horizontal .form-group {
	    margin-left: 0;
	    margin-right: 0;
	}
	.form-group .form-control:last-child {
	    border-top-left-radius: 4px;
	    border-bottom-left-radius: 4px;
	}

	.my-form-group {
	    margin:0px !important;
	}
	@media screen and (min-width: 768px) {
	    #adv-search {
	        width: 600px;
	        margin: 0 auto;
	    }
	    .dropdown.dropdown-lg {
	        position: static !important;
	    }
	    .dropdown.dropdown-lg .dropdown-menu {
	        min-width: 300px;
	    }
	}
	@media screen and (max-width: 768px) {
	    #adv-search {
	        width: 300px;
	        margin: 0 auto;
	        font-size:18px !important;
	    }
	    .dropdown.dropdown-lg {
	        position: static !important;
	    }
	    .dropdown.dropdown-lg .dropdown-menu {
	        min-width: 300px;
	    }
	}
	/* Kelvin */
	
	/* Client Search */
	.vertical-box {
	    display: table;
	    table-layout: fixed;
	    border-spacing: 0;
	    height: 100%;
	    width: 100%;
	}
	.width-sm {
	    width: 300px!important;
	}
	
	.bg-silver {
	    background: #f0f3f4!important;
	}
	
	.vertical-box-column {
	    display: table-cell;
	    vertical-align: top;
	    height: 100%;
	}
	
	.p-15, .wrapper {
	    padding: 15px!important;
	}
	
	
	@media screen and (min-width: 768px) {
		.form-horizontal .control-label {
		    padding-top: 7px;
		    margin-bottom: 0;
		    text-align: right;
		}
	}
	/* SMS, EMAIL Button */
	.my-sms-button{
		height:30px;padding-top:4px;
	}
	
	/* 5.15 Plugins-Fullcalendar */

	.external-event {
	    padding: 15px !important;
	    margin-bottom: 5px !important;
	    color: #fff !important;
	    color: rgba(255,255,255,0.7) !important;
	    -webkit-border-radius: 3px !important;
	    -moz-border-radius: 3px !important;
	    border-radius: 3px !important;
	}
	.calendar-event .external-event h5 {
	    color: #fff !important;
	    margin: 0 0 5px !important;
	}
	.calendar-event .external-event p {
	    margin: 0 !important;
	    line-height: 16px !important;
	    font-weight: 300 !important;
	}
	.fc-content {
	    clear: none !important;
	}
	.fc-state-highlight {
	    background: #f0f3f4 !important;
	}
	.fc-widget-header, .fc-widget-content {
	    border-color: #e2e7eb !important;
	}
	.fc-widget-header {
	    color: #242a30 !important;
	    font-weight: 600 !important;
	    padding: 3px 15px !important;
	}
	.fc-grid .fc-day-number {
	    padding: 3px 5px !important;
	}
	.fc-content .fc-event {
	    border: none !important;
	    padding: 5px !important;
	    text-align: center !important;
	    /*background: #2d353c;*/
	    background: #348fe2;
	    
	}
	.fc-event-time {
	    font-size: 14px !important;
	    margin-right: 5px !important;
	}
	.fc-event .fc-event-title {
	    font-size: 14px !important;
	    display: block !important;
	}
	.fc-event .fc-event-title small {
	    display: block !important;
	    font-size: 12px !important;
	    font-weight: 300 !important;
	    line-height: 16px !important;
	    color: #ccc !important;
	    color: rgba(255,255,255,0.8) !important;
	}
	.fc-event .fc-event-icons {
	    font-size: 18px !important;
	    display: block !important;
	}
	.fc-event-container a:hover,
	.fc-event-container a:focus {
	    color: #fff !important;
	    text-decoration: underline !important;
	}
	.fc-state-default {
	    background: #fff !important;
	    border: 1px solid #ccc !important;
	    line-height: 1.42857143 !important;
	    padding: 6px 12px !important;
	    color: #333 !important;
	    font-weight: normal !important;
	    height: auto !important;
	}
	.fc-header .fc-button {
	    -webkit-box-shadow: none !important;
	    box-shadow: none !important;
	    margin-bottom: 15px !important;
	}
	.fc-header .fc-button:not(.fc-state-disabled):hover,
	.fc-header .fc-button:not(.fc-state-disabled):focus {
	    background: #eee !important;
	}
	.fc-header .fc-button.fc-state-down, 
	.fc-header .fc-button.fc-state-active {
	    background: #eee !important;
	    -webkit-box-shadow: inset 0 3px 5px rgba(0,0,0,.125) !important;
	    box-shadow: inset 0 3px 5px rgba(0,0,0,.125) !important;
	}
	.fc-text-arrow {
	    font-size: 14px !important;
	    line-height: 16px !important;
	}
	.fc-header-title h2 {
	    line-height: 31px !important;
	    font-size: 24px !important;
	}
	
	.bg-white { background: #ffffff !important; }
	.bg-silver-lighter { background: #f4f6f7 !important; }
	.bg-silver { background: #f0f3f4 !important; }
	.bg-silver-darker { background: #b4b6b7 !important; }

	.bg-black { background: #2d353c !important; }
	.bg-black-darker { background: #242a30 !important; }
	.bg-black-lighter { background: #575d63 !important; }

	.bg-grey { background: #b6c2c9 !important; }
	.bg-grey-darker { background: #929ba1 !important; }
	.bg-grey-lighter { background: #c5ced4 !important; }

	.bg-red { background: #ff5b57 !important; }
	.bg-red-darker { background: #cc4946 !important; }
	.bg-red-lighter { background: #ff7c79 !important; }

	.bg-orange { background: #f59c1a !important; }
	.bg-orange-darker { background: #c47d15 !important; }
	.bg-orange-lighter { background: #f7b048 !important; }

	.bg-yellow { background: #e3fa3e !important; }
	.bg-yellow-darker { background: #b6c832 !important; }
	.bg-yellow-lighter { background: #e9fb65 !important; }

	.bg-green { background: #00acac !important; }
	.bg-green-darker { background: #008a8a !important; }
	.bg-green-lighter { background: #33bdbd !important; }

	.bg-blue { background: #348fe2 !important; }
	.bg-blue-darker { background: #2a72b5 !important; }
	.bg-blue-lighter { background: #5da5e8 !important; }

	.bg-aqua { background: #49b6d6 !important; }
	.bg-aqua-darker { background: #3a92ab !important; }
	.bg-aqua-lighter { background: #6dc5de !important; }

	.bg-purple { background: #727cb6 !important; }
	.bg-purple-darker { background: #5b6392 !important; }
	.bg-purple-lighter { background: #8e96c5 !important; }
	
	.panel-heading-btn {
	    float: right;
	}
	.btn.btn-default {
	    color: #fff;
	    background: #b6c2c9;
	    border-color: #b6c2c9;
	}
	
	.btn-group-xs>.btn, .btn-xs {
	    padding: 1px 5px;
	    font-size: 12px;
	    line-height: 1.5;
	    border-radius: 3px;
	   
	}
	
	.btn {
	    font-weight: 300;
	    -webkit-border-radius: 3px;
	    -moz-border-radius: 3px;
	    border-radius: 3px;
	}
	
	
	
	.panel-heading-btn>a {
	    margin-left: 8px;
	}
	
	
	.btn-icon, .btn.btn-icon {
	    display: inline-block;
	    width: 28px;
	    height: 28px;
	    padding: 0;
	    border: none;
	    line-height: 28px;
	    text-align: center;
	    font-size: 14px;
	}
	
	.btn-icon.btn-xs {
	    width: 16px;
	    height: 16px;
	    font-size: 8px;
	    line-height: 16px;
	}
	
	.btn-circle, .btn.btn-circle {
	    -webkit-border-radius: 50%;
	    -moz-border-radius: 50%;
	    border-radius: 50%;
	     padding-top: 4px;
	}
	
	.panel-actions {
	  margin-top: -20px;
	  margin-bottom: 0;
	  text-align: right;
	}
	.panel-actions a {
	  color:#333;
	}
	
	.panel.panel-fullscreen {
	    position: fixed;
	    top: 0;
	    left: 0;
	    right: 0;
	    bottom: 0;
	    margin: 0;
	    overflow: hidden;
	    z-index: 1080;
	}
	
	/* Assigend Clients Search */
	.dataTables_wrapper .dataTables_filter label {
	    font-weight: normal;
	    white-space: nowrap;
	    text-align: left;
	}

	.dataTables_wrapper .dataTables_filter 
	{
		text-align: right;
	}
	.dataTables_wrapper .dataTables_filter input 
	{
	    margin-left: 0.5em;
	    display: inline-block;
	    width: auto;
	}
	
	.form-inline .form-control {
	    display: inline-block;
	    width: auto;
	    vertical-align: middle;
	}
	
	
	input[type=search] {
	    -webkit-appearance: none;
	}
	
	.table-condensed{
		font-size:12px;	
	}
	.table-hover tr {
	    cursor: pointer;
	}
	.form-control{
		border: 1px solid #ccd0d4;
	    -webkit-box-shadow: none;
	    box-shadow: none;
	    font-size: 12px;
	    border-radius: 3px;
	    -webkit-border-radius: 3px;
	    -moz-border-radius: 3px;
	    color: black;
	}
	.table-hover>tbody>tr:hover>td, .table-hover>tbody>tr:hover>th {
	    background: #e8ecf1!important;
	}
	
	.table-condensed>tbody>tr>td, .table-condensed>tbody>tr>th, .table-condensed>tfoot>tr>td, .table-condensed>tfoot>tr>th, .table-condensed>thead>tr>td, .table-condensed>thead>tr>th {
	    padding: 7px 15px;
	}
	
	.table>tbody>tr>td, .table>tbody>tr>th, .table>tfoot>tr>td, .table>tfoot>tr>th, .table>thead>tr>td, .table>thead>tr>th {
	    border-color: #e2e7eb;
	    padding: 10px 15px;
	}
	
	#collapseMain .col-lg-5{
		padding: 2px;
	}
	#collapseMain .col-lg-12{
		padding: 2px;
	}
	#collapseMain .col-lg-6{
		padding: 2px;
	}
	#collapseMain .col-lg-3{
		padding: 2px;
	}
	#collapseMain .col-lg-2{
		padding: 2px;
	}
	#collapseMain .col-lg-8{
		padding: 2px;
	}
	#collapseMain .col-lg-10{
		padding: 2px;
	}
	#collapseMain .col-lg-4{
		padding: 2px;
	}
	.contact-form-control{
		padding-left:5px;
		padding-right: 5px;
	}
</style>
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