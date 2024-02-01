<?php 
@session_start();
//ob_start();

if(!isset($_SESSION['user_login']) and !isset($_COOKIE['cookie_login']))//session store admin name
{
    header("Location: index.php");//login in AdminLogin.php
}
require_once("includes/dbconnect.php");

$response = array();
$response['status'] = 'Success';
$response['unassigned_leads']=0;
if (is_ajax()) 
{
	if (isset($_POST["assign_id"]))
 	{
 		$assign_id = $_POST["assign_id"];
 		$sqlpart = $_POST["filter_part"];
 		$offset = $_POST["filter_offset"];
 		$cnt = intval($_POST["filter_count"]);
 		$order = $_POST["filter_order"];
 		$group = $_POST["filter_group"];
 		
 		if ($assign_id == 'all')
 		{
 			$sql_first_select = "select lead_id from simple_leads_info where is_deleted=0".$_SESSION['filter_search_user'];
 			
 			if ($cnt>0)
	 			$sql_select_lead = $sql_first_select . $sqlpart . $group. $order." limit $offset,$cnt";		
	 		else
	 			$sql_select_lead = $sql_first_select . $sqlpart . $group. $order;		
	 			
			$resb_lead = mysqli_query($con, $sql_select_lead) or die(mysqli_error($con) . "11");	
			while ($recb_lead = mysqli_fetch_assoc($resb_lead))
			{
				$auto_id = $recb_lead['lead_id'];
				
				$workers = '';
				$notifys = '';
				$sql_upd = "update leads_info set 
					 					  ass_worker = '" . mysqli_real_escape_string($con,$workers) . "',
					 					  ass_notify = '" . mysqli_real_escape_string($con,$notifys) . "',
										  cust_upd_dt= curdate()										  
								  where auto_id =" . $auto_id . "";
					   
				mysqli_query($con, $sql_upd) or die(mysqli_error($con));
				
				// Set Assigned
		    	$sql_select_assigned = "select ass_worker,ass_notify,name,casefile,country,status,leads_condition,ph_number,eml_address,source,st_name,ass_worker from leads_info where auto_id=".$auto_id;
			    $result_assigned = mysqli_query($con, $sql_select_assigned) or die(mysqli_error($con));
			    if ($seerec_assigned = mysqli_fetch_assoc($result_assigned))
			    {	
			    	$assigned_workers = $seerec_assigned['ass_worker'];
			    	$assigned_notifys = $seerec_assigned['ass_notify'];
			    	$assigned_name = $seerec_assigned['name'];
			    	$assigned_casefile = $seerec_assigned['casefile'];
			    	$assigned_country = $seerec_assigned['country'];
			    	$assigned_status = $seerec_assigned['status'];
			    	$assigned_leads_condition = $seerec_assigned['leads_condition'];
			    	
			    	$assigned_ph_number = $seerec_assigned['ph_number'];
			    	$assigned_eml_address = $seerec_assigned['eml_address'];
			    	$assigned_source = $seerec_assigned['source'];
			    	$assigned_st_name = $seerec_assigned['st_name'];
			    	$assigned_ass_worker = $seerec_assigned['ass_worker'];
    	
			    	
			    	
			    	// delete old simple_leads_info
			        $sql_del_assigned= sprintf("delete from simple_leads_info where lead_id = '%d'",$auto_id);
					$sql_del_res = mysqli_query($con, $sql_del_assigned) or die(mysqli_error($con));	
					
					// delete old assigned_leads_info
			        $sql_del_assigned= sprintf("delete from assigned_leads_info where lead_id = '%d'",$auto_id);
					$sql_del_res = mysqli_query($con, $sql_del_assigned) or die(mysqli_error($con));	
					
			    	if (isset($assigned_workers) and strlen($assigned_workers)>0)
					{
						$assigned = '';
						$workers = explode(';',$assigned_workers);		
						
			        	foreach ($workers as $i => $worker)
			            {	   
			            	$assigned_tmp = strchr($worker,'(');
			            	$assigned_len = strpos($assigned_tmp,')');
			            	$assigned_tmp = trim(substr($assigned_tmp,1,$assigned_len-1));
			            	$assigned .= $assigned_tmp.',';
			            	
			            	// insert simple_leads_info
			            	$sql_ins_assigned= sprintf("insert into simple_leads_info (lead_id,assigned,name,casefile,country,status,leads_condition,ph_number,eml_address,source,st_name,ass_worker) values ('%d','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s')",$auto_id,mysqli_real_escape_string($con,$assigned_tmp),mysqli_real_escape_string($con,$assigned_name),mysqli_real_escape_string($con,$assigned_casefile),mysqli_real_escape_string($con,$assigned_country),mysqli_real_escape_string($con,$assigned_status),mysqli_real_escape_string($con,$assigned_leads_condition),mysqli_real_escape_string($con,$assigned_ph_number),mysqli_real_escape_string($con,$assigned_eml_address),mysqli_real_escape_string($con,$assigned_source),mysqli_real_escape_string($con,$assigned_st_name),mysqli_real_escape_string($con,$assigned_ass_worker));
							$sql_ins_res = mysqli_query($con, $sql_ins_assigned) or die(mysqli_error($con));	    
				
			            	
							// insert assigned_leads_info
			            	$sql_ins_assigned= sprintf("insert into assigned_leads_info (lead_id,assigned,name,casefile,country,status,leads_condition) values ('%d','%s','%s','%s','%s','%s','%s')",$auto_id,mysqli_real_escape_string($con,$assigned_tmp),mysqli_real_escape_string($con,$assigned_name),mysqli_real_escape_string($con,$assigned_casefile),mysqli_real_escape_string($con,$assigned_country),mysqli_real_escape_string($con,$assigned_status),mysqli_real_escape_string($con,$assigned_leads_condition));
							$sql_ins_res = mysqli_query($con, $sql_ins_assigned) or die(mysqli_error($con));	   
							    
			            }
			            if (strlen($assigned)>0)
			            {
							$assigned = substr($assigned,0,strlen($assigned)-1);
			            	$sql_upd = sprintf("update leads_info set assigned='%s' where auto_id='%d'",$assigned,$auto_id);
							$sql_upd_res = mysqli_query($con, $sql_upd) or die(mysqli_error($con));	        
						}
					}else
					{
						// insert simple_leads_info
		            	$sql_ins_assigned= sprintf("insert into simple_leads_info (lead_id,name,casefile,country,status,leads_condition,ph_number,eml_address,source,st_name,ass_worker) values ('%d','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s')",$auto_id,mysqli_real_escape_string($con,$assigned_name),mysqli_real_escape_string($con,$assigned_casefile),mysqli_real_escape_string($con,$assigned_country),mysqli_real_escape_string($con,$assigned_status),mysqli_real_escape_string($con,$assigned_leads_condition),mysqli_real_escape_string($con,$assigned_ph_number),mysqli_real_escape_string($con,$assigned_eml_address),mysqli_real_escape_string($con,$assigned_source),mysqli_real_escape_string($con,$assigned_st_name),mysqli_real_escape_string($con,$assigned_ass_worker));
						$sql_ins_res = mysqli_query($con, $sql_ins_assigned) or die(mysqli_error($con));
					}
				}	

				$response['unassigned_leads']++;		
		        
			}	
 		}else
 		{
			$sql_select = sprintf("select worker from assigned_worker_info where auto_id='%d'",$assign_id);
	 		$resb = mysqli_query($con, $sql_select) or die(mysqli_error($con) . "11");	
			if ($recb = mysqli_fetch_assoc($resb))
			{
				$sel_worker = trim($recb['worker']);
			}
	 		
	 		$sql_first_select = "select lead_id from simple_leads_info where is_deleted=0".$_SESSION['filter_search_user'];
	 		
	 		if ($cnt>0)
	 			$sql_select_lead = $sql_first_select . $sqlpart . $group. $order." limit $offset,$cnt";		
	 		else
	 			$sql_select_lead = $sql_first_select . $sqlpart . $group. $order;		
	 			
	 		
			$resb_lead = mysqli_query($con, $sql_select_lead) or die(mysqli_error($con) . "11");	
			while ($recb_lead = mysqli_fetch_assoc($resb_lead))
			{
				$auto_id = $recb_lead['lead_id'];
				$sql_select = sprintf("select ass_worker,ass_notify from leads_info where auto_id='%d'",$auto_id);
		 		$resb = mysqli_query($con, $sql_select) or die(mysqli_error($con) . "11");	
				if ($recb = mysqli_fetch_assoc($resb))
				{
					$workers = $recb['ass_worker'];
					$notifys = $recb['ass_notify'];	
					if (strlen($workers)>1)
					{
						$workers = "";
						$notifys = "";	
						
						$workers_ary = explode(';',$recb['ass_worker']);
						$notifys_ary = explode(';',$recb['ass_notify']);	
						
				    	foreach ($workers_ary as $i => $worker)
				        {	
				        	$notify = $notifys_ary[$i];  	
				        	
				        	if (strcasecmp($worker,$sel_worker)!=0)                                         	
				        	{
								$workers .= $worker.';';
								$notifys.= $notify.';';
							}
				        }
				        
				        $workers = substr($workers,0,strlen($workers)-1);
			        	$notifys = substr($notifys,0,strlen($notifys)-1);
			        	
			        	$sql_upd = "update leads_info set 
				 					  ass_worker = '" . mysqli_real_escape_string($con,$workers) . "',
				 					  ass_notify = '" . mysqli_real_escape_string($con,$notifys) . "',
									  cust_upd_dt= curdate()										  
							  where auto_id =" . $auto_id . "";
				   
				    	mysqli_query($con, $sql_upd) or die(mysqli_error($con));
				    	
				    	// Set Assigned
				    	$sql_select_assigned = "select ass_worker,ass_notify,name,casefile,country,status,leads_condition,ph_number,eml_address,source,st_name,ass_worker from leads_info where auto_id=".$auto_id;
					    $result_assigned = mysqli_query($con, $sql_select_assigned) or die(mysqli_error($con));
					    if ($seerec_assigned = mysqli_fetch_assoc($result_assigned))
					    {	
					    	$assigned_workers = $seerec_assigned['ass_worker'];
					    	$assigned_notifys = $seerec_assigned['ass_notify'];
					    	$assigned_name = $seerec_assigned['name'];
					    	$assigned_casefile = $seerec_assigned['casefile'];
					    	$assigned_country = $seerec_assigned['country'];
					    	$assigned_status = $seerec_assigned['status'];
					    	$assigned_leads_condition = $seerec_assigned['leads_condition'];
					    	
					    	$assigned_ph_number = $seerec_assigned['ph_number'];
					    	$assigned_eml_address = $seerec_assigned['eml_address'];
					    	$assigned_source = $seerec_assigned['source'];
					    	$assigned_st_name = $seerec_assigned['st_name'];
					    	$assigned_ass_worker = $seerec_assigned['ass_worker'];
		    	
					    	
					    	
					    	// delete old simple_leads_info
					        $sql_del_assigned= sprintf("delete from simple_leads_info where lead_id = '%d'",$auto_id);
							$sql_del_res = mysqli_query($con, $sql_del_assigned) or die(mysqli_error($con));	
							
							// delete old assigned_leads_info
					        $sql_del_assigned= sprintf("delete from assigned_leads_info where lead_id = '%d'",$auto_id);
							$sql_del_res = mysqli_query($con, $sql_del_assigned) or die(mysqli_error($con));	
							
					    	if (isset($assigned_workers) and strlen($assigned_workers)>0)
							{
								$assigned = '';
								$workers = explode(';',$assigned_workers);		
								
					        	foreach ($workers as $i => $worker)
					            {	   
					            	$assigned_tmp = strchr($worker,'(');
					            	$assigned_len = strpos($assigned_tmp,')');
					            	$assigned_tmp = trim(substr($assigned_tmp,1,$assigned_len-1));
					            	$assigned .= $assigned_tmp.',';
					            	
					            	// insert simple_leads_info
					            	$sql_ins_assigned= sprintf("insert into simple_leads_info (lead_id,assigned,name,casefile,country,status,leads_condition,ph_number,eml_address,source,st_name,ass_worker) values ('%d','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s')",$auto_id,mysqli_real_escape_string($con,$assigned_tmp),mysqli_real_escape_string($con,$assigned_name),mysqli_real_escape_string($con,$assigned_casefile),mysqli_real_escape_string($con,$assigned_country),mysqli_real_escape_string($con,$assigned_status),mysqli_real_escape_string($con,$assigned_leads_condition),mysqli_real_escape_string($con,$assigned_ph_number),mysqli_real_escape_string($con,$assigned_eml_address),mysqli_real_escape_string($con,$assigned_source),mysqli_real_escape_string($con,$assigned_st_name),mysqli_real_escape_string($con,$assigned_ass_worker));
									$sql_ins_res = mysqli_query($con, $sql_ins_assigned) or die(mysqli_error($con));	    
						
					            	
									// insert assigned_leads_info
					            	$sql_ins_assigned= sprintf("insert into assigned_leads_info (lead_id,assigned,name,casefile,country,status,leads_condition) values ('%d','%s','%s','%s','%s','%s','%s')",$auto_id,mysqli_real_escape_string($con,$assigned_tmp),mysqli_real_escape_string($con,$assigned_name),mysqli_real_escape_string($con,$assigned_casefile),mysqli_real_escape_string($con,$assigned_country),mysqli_real_escape_string($con,$assigned_status),mysqli_real_escape_string($con,$assigned_leads_condition));
									$sql_ins_res = mysqli_query($con, $sql_ins_assigned) or die(mysqli_error($con));	   
									    
					            }
					            if (strlen($assigned)>0)
					            {
									$assigned = substr($assigned,0,strlen($assigned)-1);
					            	$sql_upd = sprintf("update leads_info set assigned='%s' where auto_id='%d'",$assigned,$auto_id);
									$sql_upd_res = mysqli_query($con, $sql_upd) or die(mysqli_error($con));	        
								}
							}else
							{
								// insert simple_leads_info
				            	$sql_ins_assigned= sprintf("insert into simple_leads_info (lead_id,name,casefile,country,status,leads_condition,ph_number,eml_address,source,st_name,ass_worker) values ('%d','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s')",$auto_id,mysqli_real_escape_string($con,$assigned_name),mysqli_real_escape_string($con,$assigned_casefile),mysqli_real_escape_string($con,$assigned_country),mysqli_real_escape_string($con,$assigned_status),mysqli_real_escape_string($con,$assigned_leads_condition),mysqli_real_escape_string($con,$assigned_ph_number),mysqli_real_escape_string($con,$assigned_eml_address),mysqli_real_escape_string($con,$assigned_source),mysqli_real_escape_string($con,$assigned_st_name),mysqli_real_escape_string($con,$assigned_ass_worker));
								$sql_ins_res = mysqli_query($con, $sql_ins_assigned) or die(mysqli_error($con));
							}
						}	

						$response['unassigned_leads']++;
				       
					}else
					{
						break;
					}
				}
			}
		}
 		
	}
}


echo json_encode($response);


//Function to check if the request is an AJAX request
function is_ajax() {
  return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
}


?>