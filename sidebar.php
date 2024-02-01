<div id="main_sidebar">
    <div id="logo">
        <img src="images/logo_small.png" style="max-width: 200px;" />
    </div>
    <div id="left_sidebar">
        <ul>
            <li>
                <a href="googlecalendar.php"><i class="fa fa-calendar"></i>&nbsp;&nbsp;Calendar</a>
            </li>
            <li>
                <a href="#scheduleSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle"><i class="fa fa-calendar-o"></i>&nbsp;&nbsp;Schedule&nbsp;<span class="caret"></span></a>
                <ul class="collapse" id="scheduleSubmenu">
                    <li>
                        <a href="schedule.php">Calendar</a>
                    </li>
                    <li>
                        <a href="#" onclick="javascript:newEvent()"">New</a>
                    </li>
                </ul>
            </li>
            <li>
                <a href="#contactSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle"><i class="fa fa-user"></i>&nbsp;&nbsp;Contacts&nbsp;<span class="caret"></span></a>
                <ul class="collapse" id="contactSubmenu">
                	<li><a href="contact_new.php">Add</a></li>
                	<li><a href="contacts.php?contact_type=All">All</a></li>
                	<li><a href="contacts.php?contact_type=Client">Clients</a></li>
                	<li><a href="contacts.php?contact_type=Contractor">Contractors</a></li>
                	<li><a href="contacts.php?contact_type=New">New</a></li>
                	<li><a href="contacts.php?contact_type=Other">Other</a></li>
                </ul>
            </li>
            <!-- <li class="dropdown">
	               		<a class="dropdown-toggle" data-toggle="dropdown" href="#" class="my-menu-a" style="color:white"><i class="fa fa-bank"></i>&nbsp;Staffs&nbsp;<span class="caret"></span></a>
	                	<ul class="dropdown-menu" style="background: #555 !important;">   
	                		<li><a href="staffs.php" style="color:white">List</a></li>		                		
	                		<li><a href="staff_new.php" style="color:white">New</a></li>
	                    </ul>
			        </li>    -->
            <li>
                <a href="#enquiresSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle"><i class="fa fa-cubes"></i>&nbsp;&nbsp;Enquiries&nbsp;<span class="caret"></span></a>
                <ul class="collapse" id="enquiresSubmenu">
                	<li><a href="enquiry_new.php">New</a></li>
                	<li><a href="enquiries.php">List</a></li>
                	<li><a href="export_enquiries.php">Export</a></li>
                </ul>
            </li>
             <li>
                <a href="#estimateSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle"><i class="fa fa-bank"></i>&nbsp;&nbsp;Estimates&nbsp;<span class="caret"></span></a>
                <ul class="collapse" id="estimateSubmenu">
                	<li><a href="estimate_new.php">Create Estimate</a></li>
                	<li><a href="estimate_live.php">Live Estimates</a></li>
                	<li><a href="estimate_archived.php">Achived Estimates</a></li>
                	<li><a href="export_estimate.php">Export</a></li>
                </ul>
            </li>
            <!--  <li class="dropdown">
	               		<a class="dropdown-toggle" data-toggle="dropdown" href="#" class="my-menu-a" style="color:white"><i class="fa fa-weixin"></i>&nbsp;Invoicing&nbsp;<span class="caret"></span></a>
	                	<ul class="dropdown-menu" style="background: #555 !important;">   
	                		<li><a href="quotes.php" style="color:white">List</a></li>		                		
	                		<li><a href="quote_new.php" style="color:white">New</a></li>
	                    </ul>
			        </li>-->
            <li><a href="financials.php"><i class="fa fa-spinner"></i><span> Financials</span></a></li>
            <!--<li class="dropdown">
	               		<a class="dropdown-toggle" data-toggle="dropdown" href="#" class="my-menu-a" style="color:white"><i class="fa fa-weixin"></i>&nbsp;Quotes&nbsp;<span class="caret"></span></a>
	                	<ul class="dropdown-menu" style="background: #555 !important;">   
	                		<li><a href="quotes.php" style="color:white">List</a></li>		                		
	                		<li><a href="quote_new.php" style="color:white">New</a></li>
	                    </ul>
			        </li>-->
            <li>
                <a href="#supplySubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle"><i class="fa fa-ticket" aria-hidden="true"></i>&nbsp;&nbsp;Suppliers&nbsp;<span class="caret"></span></a>
                <ul class="collapse" id="supplySubmenu">
                	<li><a href="suppliers.php">List</a></li>
                	<li><a href="supplier_new.php">New</a></li>
                	<!--<li><a href="materials.php" style="color:white">Material List</a></li>		                		
	                		<li><a href="material_new.php" style="color:white">Material New</a></li>
	                		<li><a href="categories.php" style="color:white">Category List</a></li>
	                		<li><a href="category_new.php" style="color:white">Category New</a></li>-->
                </ul>
            </li>
            <li>
                <a href="#materialSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle"><i class="fa fa-gift"></i>&nbsp;&nbsp;Materials&nbsp;<span class="caret"></span></a>
                <ul class="collapse" id="materialSubmenu">
                	<li><a href="materials.php">List</a></li>		                		
            		<li><a href="material_new.php">New</a></li>
            		<li><a href="categories.php">Category List</a></li>
            		<li><a href="category_new.php">Category New</a></li>
                </ul>
            </li>
     
        </ul>
    </div>
</div>