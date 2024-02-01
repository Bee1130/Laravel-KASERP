<?php 
@session_start();
////ob_start();

//exit();
if(!isset($_SESSION['user_login']) and !isset($_COOKIE['cookie_login']))//session store admin name
{
    header("Location: index.php");//login in AdminLogin.php
}

require_once("includes/dbconnect.php");

$msg = '';


if(isset($_POST["submit"]) and $_POST["submit"] == "Import")
{
	$file = $_FILES['csv_file']['tmp_name'];
	$rec=0;
	
								
	if (isset($file) and $file != "")
	{
		if ($handle = fopen($file, "r"))
		{
			$is_first = 0;
			$cnt=0;
			$agent = $_SESSION['user_login'];
			$msg = "Importing now...";
										
			//while(($filesop = fgetcsv($handle, 1000, ",")) !== false)
			while(($filesop = fgetcsv($handle)) !== false)
			{
				if ($is_first==0)
				{
					$is_first=1;
				}else
				{
					
			        /* No	Name	Phone	Email	Google Address	Avatar */     
		           
		           	$name = mysqli_real_escape_string($con,$filesop[1]);
		           	$phone = mysqli_real_escape_string($con,$filesop[2]);
		           	$email = mysqli_real_escape_string($con,$filesop[3]);
		           	$address = mysqli_real_escape_string($con,$filesop[4]);
		           
		           
		           	$user_avatar = mysqli_real_escape_string($con,$filesop[5]);
		          
					if (!isset($user_avatar) or strlen($user_avatar)==0)
			     		$user_avatar = 'default.jpg';
			       
			        $sql = sprintf("insert into suppliers_info (name,phone_number,email,address,user_avatar,agent,cust_upd_dt) VALUES ('%s','%s','%s','%s','%s','%s',sysdate())",$name,$phone,$email,$address,$user_avatar,$_SESSION['user_login']);
					$sql_res = mysqli_query($con,$sql) or die(mysqli_error($con));	
					$cnt++;
					
				}
								
			}
			
			if($sql_res){
				$msg="You database has imported successfully. You have inserted ". $cnt ." recoreds";
				
			}else{
				$msg="Sorry! There is some problem.";		
			}
			fclose($handle);	
			
		}		
	}
	
}	
		
?>
<html>
<head>
	<?php include("header.php");?>
</head>
<body>
	<div class="container">
    	    <?php include("sidebar.php"); ?>
    	    <div class="main-content">
        		    <?php include('menu.php'); ?>
								
	<form name="frmSearch" method="post" enctype="multipart/form-data" action="import_suppliers.php">
		<div class="container">
			<div class="row" style="marign:0px;margin-top: 10px"> 
				<div class="col-sm-12">
					<center>
						<h2>Import From CSV</h2>
						<br>
					</center>
				</div>
			</div>			
	   		<div class="row">
	   			<div class="my-static-div" style="margin: auto;width:310px">
		   			<center>
		   				<div class="panel panel-primary my-panel">
							
							<div class="panel-body my-panel-body">
								<div class='form-row'>
									<input type="file" class="form-control" name="csv_file"/><br />
								</div>
								<div class='form-row'>
									<div class="col-sm-offset-3 col-sm-6 col-sm-offset-3">
										<center>
											<input type="submit" class='form-control btn btn-success submit-button' name="submit" value="Import" />		
										</center>
									</div>																	
								</div>								
							</div>
						</div>	
						
		   			</center>
	   			</div>	   			
	   		</div>
	   		<div class="row mobile-row">
				<div class="col-sm-12">
					<center>
						<h5 style="color:blue;"><?php echo $msg;?></h5>
						<br>
					</center>
				</div>
			</div>
	   	</div>
	</form>	
	</div>
	</div>
</body>
</html>
