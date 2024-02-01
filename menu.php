<div style="width: 100%; background-color: #808080; border-radius: 5px; height: 40px;padding-right: 50px; display: flex; align-items: center;" id="top_menu_bar">
    <div style="display: flex; width: 60%;" id="newyork_time">
        <div style="font-size: 20px; margin-left: 20px; font-family: 'Goldplay_menu';">
            <a style="text-decoration: none; color: white;" href="http://time.is/New_York" id="time_is_link" rel="nofollow"  style="color: white;">Date and Time: <?php echo date('m/d/y');?> <span id="New_York_z161"></span></a>
        </div>
        <div style="font-size: 20px; margin-left: 20px;font-family: 'Goldplay_menu';">
            <a style="text-decoration: none; color: white;" href="acp/index.php" target="_blank" style="color:white;"></a>
        </div>
    </div>
    <div style="display:flex; width: 40%; justify-content: flex-end;">
        <!--<div style="color: white;font-size: 20px; border-radius: 5px; background-color: rgb(43, 100, 87); padding:8px 8px;width: 36px; height: 36px;margin-right: 15px;">-->
        <!--    <i class="fa fa-comment"></i>-->
        <!--</div>-->
        <!--<div style="color: white;font-size: 20px; border-radius: 5px; background-color: rgb(43, 100, 87); padding:8px 8px;width: 36px; height: 36px;margin-right: 15px;">-->
        <!--    <i class="fa fa-bell"></i>-->
        <!--</div>-->
        <div>
    		<ul id="top-menu" class="nav navbar-nav">
    			<li class="dropdown" style="float: right">
               		<a class="dropdown-toggle" data-toggle="dropdown" href="#" class="my-menu-a" style="color:white; font-family: 'Goldplay_menu'; font-size: 18px;"><span class="glyphicon glyphicon-user"></span>&nbsp;Users&nbsp;<span class="caret"></span></a>
                	<ul class="dropdown-menu" style="background: #fff  !important;">   
                		<li><a href="addagent.php" >&nbsp;Add</a></li>	
                		<li><a href="changeaccess.php">&nbsp;Change</a></li>
                		<li><a href="logs.php" >&nbsp;Logs</a></li>	
                		<li><a href="adminlogout.php">&nbsp;Log Out</a></li>
    	            </ul>
    	        </li>               
    	    </ul>
    	</div>
    </div>
</div>
		    
		    <!--<ul id="util-nav" class="nav navbar-nav">		-->
		  <!--  	<li class="hidden-xs">-->
				<!--	<a href="http://time.is/New_York" id="time_is_link" rel="nofollow"  style="color: #585663;">GMT: <?php echo date('m/d/y');?> <span id="New_York_z161"></span></a>-->
				<!--</li>		    	-->
			  	<!--<li id="lbg" class="dropdown">
					<a href="#" data-toggle="dropdown" class="dropdown-toggle f-s-14" aria-expanded="false" style="color: #585663;">
						<i class="fa fa-bell-o"></i>
						<span id="live_notify_badge" class="badge"></span>
					</a>							
					<ul id="live_notify_list" class="dropdown-menu media-list pull-right animated fadeInDown">
					</ul>
				</li>-->
			  	<!--<li style="float: right"><a href="acp/index.php" target="_blank" style="color:#585663;"><i class="fa fa-life-ring"></i><span> Customer Portal</span></a></li>-->
            <!--</ul>		-->
			   	                
	
<!-- mobile Icon menu -->
<nav class="navbar-top-bar navbar-inverse navbar-fixed-top mobile-my-menu">
	<div class="container-fluid">
		<div class="row mobile-row" id="bottomNav">
	    	<div class="col-xs-3 text-center" >
	    		<a href="schedule.php" style="color:white"><i class="fa fa-calendar"></i> <span>Schedule</span></a>
			</div>
			<div class="col-xs-2 text-center" >
				<a href="contacts.php" style="color:white"><i class="fa fa-user"></i> <span>Leads</span></a>
			</div>
			<div class="col-xs-3 text-center">
	    		<a href="adminlogout.php" style="color:white"><span class="glyphicon glyphicon-log-out"></span><br>Logout</a>
			</div>				    
		</div>			   
	</div>
</nav>
