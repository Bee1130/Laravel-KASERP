<?php
/****-------------------------------------------------------------------****
		Purpose 	: 	Change login credential after admin login
		Project 	:	Sales Contact DB	
	 	Developer 	: 	Kelvin
	 	Create Date : 	09/01/2016   
****-------------------------------------------------------------------****/
session_start();
if(!isset($_SESSION['user_login']) and !isset($_COOKIE['cookie_login']))//session store admin name
{
	header("Location: index.php");//login in AdminLogin.php
}

require_once("includes/dbconnect.php");
$submit = null;
if(isset($_POST['Submit'])) {
	$submit = $_POST['Submit'];
}
if ($submit == 'deleteRecord')
{			
	/* delete record */
	$sql_del = sprintf("delete from admin_user where user_id='%s'",$_POST['sel_id']);	
	$res_del = mysqli_query($con, $sql_del) or die(mysqli_error($con));		
}

?>
<!DOCTYPE HTML>
<html>
    <head>
        <?php include("header.php");?>
        <script type="text/javascript">
        				
			function delRecord(recid)
			{		
				console.log('[del_btn]');			
				
				document.getElementById("sel_id").value = recid;
				console.log(document.getElementById("sel_id").value);
				
				$("#dialog_delete_yes_no").modal();					
			}				
			 
	        function YesDelete()
	        {	
				console.log('[btnYesDelete]');	
				$("#dialog_delete_yes_no").modal('hide');
				$("#deleteRecord").click();					
			}
		</script>		
    </head>
    <body>
		<div class="container">
    	    <?php include("sidebar.php"); ?>
    	    <div class="main-content">
        		<div class="container">
        		    <?php include('menu.php'); ?>
        			<br>
        			<div class="row mobile-row">
        				<div class="col-sm-12">
        					<center>
        						<h1>Control User Permission</h1>
        						<br>
        					</center>
        				</div>
        			</div>
        		    <div class='row'>
        		    	<div class="my-static-div" style="margin:auto;width:80%">
        		           	<form name="default_emplate" id="default_emplate" method="post" enctype="multipart/form-data" onsubmit="return stepcheck();">
        		           		<button type="submit" style="display:none" class="btn btn-primary" name="Submit" id="deleteRecord" value="deleteRecord"></button>
        		           		<input type="hidden" id="sel_id"  name="sel_id" value='0'/>
        		           		<div class="table-responsive" style="height:500px; overflow:auto; overflow-y:scroll;">    
        		           			<table class="table table-striped">
        		           				<thead>
        		      						<tr style="font-size:16px">
        										<th class="col-xs-3">User Id</th>
        										<th class="col-xs-3">Password</th>
        										
        										<th class="col-xs-3">Group</th>
        										<th class="col-xs-3">Action</th>													        										
        									</tr>      						
        		      					</thead>
        		      					<tbody>
        		      					<?php
        									$sql_sel = "select * from admin_user";
        									$res_sel = mysqli_query($con,$sql_sel) or die(mysqli_error($con));
        									while($v_sel = mysqli_fetch_assoc($res_sel))
        									{
        								?>
        				                      <tr style="font-size:14px"> 
        				                        <td class="col-xs-3">&nbsp;<?php echo $v_sel['user_id'];?></td>
        				                        <td class="col-xs-3"><?php echo $v_sel['password'];?></td>
        				                        
        				                        <td class="col-xs-3"><?php echo $v_sel['user_group'];?></td>
        				                        <td class="col-xs-3">
        				                        	<a href="changecredential.php?uid=<?php echo $v_sel['user_id']?>" onClick="picopen('changecredential.php?uid=<?php echo $v_sel['user_id']?>','popup', 300, 300); return false;" target="popup" class="main_txt_red">Change&nbsp;&nbsp;/&nbsp;&nbsp;</a><a href="#" onclick="javascript:delRecord('<?php echo $v_sel['user_id']?>');">Delete</a>
        				                        </td>
        				                        
        				                      </tr>
        			                    <?php
        									}
        								?>
        		      					</tbody>								
        		           			</table>
        		           		</div>
        		            </form>
        		        </div>
        		    </div>	
        		    <!-- delete confirmation dialog -->
        	        <div id="dialog_delete_yes_no" class="modal fade" style="z-index:1000000014;display:none;" title="">
        	        	<div class="modal-dialog modal-md">
        					<div class="modal-content">
        						<div class="modal-header">
        					        <button type="button" class="close" data-dismiss="modal">&times;</button>
        					        <h4 class="modal-title">Delete</h4>				        
        				      	</div>
        				      	<div class="modal-body" style="overflow:auto" id="bulk_email_preview_div">
        				      		<div class="form-group" style="margin-bottom:0px">
                        				<center>
                                			<p>Are you sure you want to delete these records?</p>         					
                        				</center>
        							</div>
        				      	</div>	
        				      	<div class="modal-footer">
        				      		<div class="row mobile-row">
        				      			<center>
        					      			<div class="col-xs-offset-2 col-xs-4">
        					      				<button type="button" id="btnYesDelete"  name="btnYesDelete" onclick="YesDelete()" class="btn btn-default btn-success btn-block">Yes</button>			        	 
        					      			</div>
        					      			<div class="col-xs-4">
        					      				<button type="button" data-dismiss="modal" class="btn btn-default btn-success btn-block">No</button>		        	 
        					      			</div>
        				      			</center>
        				      		</div>	
        				      	</div>		    
        					</div>
        				</div>
        			</div> 
			    </div>
		    </div>	
		</div>
    </body>
</html>