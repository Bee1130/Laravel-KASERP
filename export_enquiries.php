<?php
session_start();
if(!isset($_SESSION['user_login']) and !isset($_COOKIE['cookie_login']))//session store admin name
{
	header("Location: index.php");
}
require_once("includes/dbconnect.php");
//Optional: print out title to top of Excel or Word file with Timestamp for when file was generated:
//set $Use_Titel = 1 to generate title, 0 not to use title
$Use_Title = 0;

//define date for title: EDIT this to create the time-format you need
$now_date = date('m/d/Y H:i');

//define title for .doc or .xls file: EDIT this if you want
$title = "Items";

$w=2;

$file_type = "vnd.ms-excel";
$file_ending = "xls";
$file_name= "enquireies".$now_date;

//header info for browser: determines file type ('.doc' or '.xls')
header("Content-Type: application/$file_type");
header("Content-Disposition: attachment; filename=$file_name.$file_ending");
header("Pragma: no-cache");
header("Expires: 0");

if ($Use_Title == 1)
{
	echo("$title\n");
}

//define separator (defines columns in excel & tabs in word)
$sep = "\t"; //tabbed character

//start of printing column names as names of MySQL fields

echo 'No'. "\t";
echo 'Name'. "\t";
echo 'Mobile'. "\t";
echo 'Email'. "\t";
echo 'Subject'. "\t";
echo 'Category'. "\t";
echo 'Documents'. "\t";
echo 'Document Paths'. "\t";
echo 'Contact Type'. "\t";
echo 'Avatar'. "\t";

print("\n");
//end of printing column names

//start while loop to get data
$rec_cnt = 0;
$sql_sel = "select count(*) as total_cnt from contacts_info";
$result = mysqli_query($con,$sql_sel) or die(mysqli_error($con));
if ($seerec = mysqli_fetch_assoc($result))
	$rec_cnt = $seerec['total_cnt'];

$id = 0;
$offset = 0;
if ($rec_cnt > 0)
{
		
	$sql_sel_rec = "select name,address,hm_ph,landline,email,policy_number,national_ins,utr,bank_details,fil_filename,fil_filepath,contact_type, user_avatar from contacts_info";
	$result_rec = mysqli_query($con,$sql_sel_rec) or die(mysqli_error($con));
	
	while ($row_rec = mysqli_fetch_row($result_rec))
    {
    	$schema_insert = "";
		$id++;
		$schema_insert .= $id.$sep;

    	for($j=0; $j<mysqli_num_fields($result_rec);$j++)
	    {
			if(!isset($row_rec[$j]))
			{
				$schema_insert .= "".$sep;
			}elseif ($row_rec[$j] != "")
			{
				$schema_insert .= "$row_rec[$j]".$sep;
			}else
			{
				$schema_insert .= "".$sep;
			}
	    }
	    
	    $schema_insert = preg_replace("/\r\n|\n\r|\n|\r/", " ", $schema_insert);
	    $schema_insert .= "\t";
	    print $schema_insert;
	    print "\n";
	    
	    $offset++;
		if ($offset > $rec_cnt)
			break;
    }
}
?>