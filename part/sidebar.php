<div class="nk-sidebar nk-sidebar-fixed is-light " data-content="sidebarMenu">
    <div class="nk-sidebar-element nk-sidebar-head">
        <div class="nk-sidebar-brand">
            <a href="html/index.html" class="logo-link nk-sidebar-logo">
                <img class="logo-light logo-img" src="./images/logo_small.png" alt="logo">
                <img class="logo-dark logo-img" src="./images/logo_small.png" alt="logo-dark">
                <img class="logo-small logo-img logo-img-small" src="./images/logo_small.png" alt="logo-small">
            </a>
        </div>
        <div class="nk-menu-trigger mr-n2">
            <a href="#" class="nk-nav-toggle nk-quick-nav-icon d-xl-none" data-target="sidebarMenu"><em class="icon ni ni-arrow-left"></em></a>
            <a href="#" class="nk-nav-compact nk-quick-nav-icon d-none d-xl-inline-flex" data-target="sidebarMenu"><em class="icon ni ni-menu"></em></a>
        </div>
    </div><!-- .nk-sidebar-element -->
    <div class="nk-sidebar-element">
        <div class="nk-sidebar-content">
            <div class="nk-sidebar-menu" data-simplebar>
                <ul class="nk-menu">
                    <li class="nk-menu-heading">
                        <h6 class="overline-title text-primary-alt">Dashboards</h6>
                    </li><!-- .nk-menu-item -->
                    <li class="nk-menu-item">
                        <a href="googlecalendar.php" class="nk-menu-link">
                            <span class="nk-menu-icon"><em class="icon ni ni-calendar-fill"></em></span>
                            <span class="nk-menu-text">Calendar</span>
                        </a>
                    </li>
                    <li class="nk-menu-item has-sub">
                        <a href="#" class="nk-menu-link nk-menu-toggle">
                            <span class="nk-menu-icon"><em class="icon ni ni-calender-date-fill"></em></span>
                            <span class="nk-menu-text">Schedule</span>
                        </a>
                        <ul class="nk-menu-sub">
                            <li class="nk-menu-item">
                                <a href="schedule.php" class="nk-menu-link"><span class="nk-menu-text">Calendar</span></a>
                            </li>
                            <li class="nk-menu-item">
                                <a href="#" class="nk-menu-link" onclick="javascript:newEvent()"><span class="nk-menu-text">New</span></a>
                            </li>
                        </ul><!-- .nk-menu-sub -->
                    </li><!-- .nk-menu-item -->
                    <li class="nk-menu-item has-sub">
                        <a href="#" class="nk-menu-link nk-menu-toggle">
                            <span class="nk-menu-icon"><em class="icon ni ni-users-fill"></em></span>
                            <span class="nk-menu-text">Contact</span>
                        </a>
                        <ul class="nk-menu-sub">
                            <li class="nk-menu-item">
                                <a href="contact_new.php" class="nk-menu-link"><span class="nk-menu-text">Add</span></a>
                            </li>
                            <li class="nk-menu-item">
                                <a href="contacts.php?contact_type=All" class="nk-menu-link"><span class="nk-menu-text">All</span></a>
                            </li>
                            <li class="nk-menu-item">
                                <a href="contacts.php?contact_type=Client" class="nk-menu-link"><span class="nk-menu-text">Clients</span></a>
                            </li>
                            <li class="nk-menu-item">
                                <a href="contacts.php?contact_type=Contractor" class="nk-menu-link"><span class="nk-menu-text">Contractors</span></a>
                            </li>
                            <li class="nk-menu-item">
                                <a href="contacts.php?contact_type=New" class="nk-menu-link"><span class="nk-menu-text">New</span></a>
                            </li>
                            <li class="nk-menu-item">
                                <a href="contacts.php?contact_type=Other" class="nk-menu-link"><span class="nk-menu-text">Other</span></a>
                            </li>
                        </ul><!-- .nk-menu-sub -->
                    </li><!-- .nk-menu-item -->
                    
                      <!-- <li class="dropdown">
	               		<a class="dropdown-toggle" data-toggle="dropdown" href="#" class="my-menu-a" style="color:white"><i class="fa fa-bank"></i>&nbsp;Staffs&nbsp;<span class="caret"></span></a>
	                	<ul class="dropdown-menu" style="background: #555 !important;">   
	                		<li><a href="staffs.php" style="color:white">List</a></li>		                		
	                		<li><a href="staff_new.php" style="color:white">New</a></li>
	                    </ul>
			        </li>    -->
                    <li class="nk-menu-item has-sub">
                        <a href="#" class="nk-menu-link nk-menu-toggle">
                            <span class="nk-menu-icon"><em class="icon ni ni-users-fill"></em></span>
                            <span class="nk-menu-text">Enquiries</span>
                        </a>
                        <ul class="nk-menu-sub">
                            <li class="nk-menu-item">
                                <a href="enquiry_new.php" class="nk-menu-link"><span class="nk-menu-text">New</span></a>
                            </li>
                            <li class="nk-menu-item">
                                <a href="enquiries.php" class="nk-menu-link"><span class="nk-menu-text">List</span></a>
                            </li>
                            <li class="nk-menu-item">
                                <a href="export_enquiries.php" class="nk-menu-link"><span class="nk-menu-text">Export</span></a>
                            </li>
                        </ul><!-- .nk-menu-sub -->
                    </li><!-- .nk-menu-item -->
                    <li class="nk-menu-item has-sub">
                        <a href="#" class="nk-menu-link nk-menu-toggle">
                            <span class="nk-menu-icon"><em class="icon ni ni-users-fill"></em></span>
                            <span class="nk-menu-text">Estimates</span>
                        </a>
                        <ul class="nk-menu-sub">
                            <li class="nk-menu-item">
                                <a href="estimate_new.php" class="nk-menu-link"><span class="nk-menu-text">Create Estimate</span></a>
                            </li>
                            <li class="nk-menu-item">
                                <a href="estimate_live.php" class="nk-menu-link"><span class="nk-menu-text">Live Estimates</span></a>
                            </li>
                            <li class="nk-menu-item">
                                <a href="estimate_archived.php" class="nk-menu-link"><span class="nk-menu-text">Achived Estimates</span></a>
                            </li>
                            <li class="nk-menu-item">
                                <a href="export_estimate.php" class="nk-menu-link"><span class="nk-menu-text">Export</span></a>
                            </li>
                        </ul><!-- .nk-menu-sub -->
                    </li><!-- .nk-menu-item -->
                    <!--  <li class="dropdown">
	               		<a class="dropdown-toggle" data-toggle="dropdown" href="#" class="my-menu-a" style="color:white"><i class="fa fa-weixin"></i>&nbsp;Invoicing&nbsp;<span class="caret"></span></a>
	                	<ul class="dropdown-menu" style="background: #555 !important;">   
	                		<li><a href="quotes.php" style="color:white">List</a></li>		                		
	                		<li><a href="quote_new.php" style="color:white">New</a></li>
	                    </ul>
			        </li>-->
                    <li class="nk-menu-item">
                        <a href="financials.php" class="nk-menu-link">
                            <span class="nk-menu-icon"><em class="icon ni ni-calendar-fill"></em></span>
                            <span class="nk-menu-text">Financials</span>
                        </a>
                    </li>
                    <!--<li class="dropdown">
	               		<a class="dropdown-toggle" data-toggle="dropdown" href="#" class="my-menu-a" style="color:white"><i class="fa fa-weixin"></i>&nbsp;Quotes&nbsp;<span class="caret"></span></a>
	                	<ul class="dropdown-menu" style="background: #555 !important;">   
	                		<li><a href="quotes.php" style="color:white">List</a></li>		                		
	                		<li><a href="quote_new.php" style="color:white">New</a></li>
	                    </ul>
			        </li>-->
                    <li class="nk-menu-item has-sub">
                        <a href="#" class="nk-menu-link nk-menu-toggle">
                            <span class="nk-menu-icon"><em class="icon ni ni-users-fill"></em></span>
                            <span class="nk-menu-text">Suppliers</span>
                        </a>
                        <ul class="nk-menu-sub">
                            <li class="nk-menu-item">
                                <a href="suppliers.php" class="nk-menu-link"><span class="nk-menu-text">List</span></a>
                            </li>
                            <li class="nk-menu-item">
                                <a href="supplier_new.php" class="nk-menu-link"><span class="nk-menu-text">New</span></a>
                            </li>
                        </ul><!-- .nk-menu-sub -->
                    </li><!-- .nk-menu-item -->
                    <li class="nk-menu-item has-sub">
                        <a href="#" class="nk-menu-link nk-menu-toggle">
                            <span class="nk-menu-icon"><em class="icon ni ni-users-fill"></em></span>
                            <span class="nk-menu-text">Materials</span>
                        </a>
                        <ul class="nk-menu-sub">
                            <li class="nk-menu-item">
                                <a href="materials.php" class="nk-menu-link"><span class="nk-menu-text">List</span></a>
                            </li>
                            <li class="nk-menu-item">
                                <a href="material_new.php" class="nk-menu-link"><span class="nk-menu-text">New</span></a>
                            </li>
                            <li class="nk-menu-item">
                                <a href="categories.php" class="nk-menu-link"><span class="nk-menu-text">Category List</span></a>
                            </li>
                            <li class="nk-menu-item">
                                <a href="category_new.php" class="nk-menu-link"><span class="nk-menu-text">Category New</span></a>
                            </li>
                        </ul><!-- .nk-menu-sub -->
                    </li><!-- .nk-menu-item -->
                </ul><!-- .nk-menu -->
            </div><!-- .nk-sidebar-menu -->
        </div><!-- .nk-sidebar-content -->
    </div><!-- .nk-sidebar-element -->
            </div>