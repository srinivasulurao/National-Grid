<?php
set_time_limit(0);

ignore_user_abort(true);

ini_set('display_errors', 1);

require_once( get_cfg_var("doc_root")."/ConnectPHP/Connect_init.php");

use RightNow\Connect\v1_2 as RNCPHP;

initConnectAPI("FNT_CWS","FNTRocks!");

/*Thirty Days expire date */
$thirty_day_expire = date('Y-m-d\Z', strtotime("+30 days"));

/* Seven Days expire date */
$seven_day_expire = date('Y-m-d\Z', strtotime("+7 days"));

/* One Day expire date */
$one_day_expire = date('Y-m-d\Z', strtotime("+1 days"));


if(!empty($thirty_day_expire) && !empty($seven_day_expire) && !empty($one_day_expire))
{
        $action_query="select CFS.ActionItem.ID as ActionID,CFS.ActionItem.Description,CFS.ActionItem.DueDate,CFS.ActionItem.Product,CFS.ActionItem.Product.Name as Product,CFS.ActionItem.Location.Name as Location,CFS.ActionItem.Details,CFS.ActionItem.Category.LookupName as Category,CFS.ActionItem.Priority.LookupName as Priority,CFS.ActionItem.Status.LookupName as status,CFS.ActionItem.Contact.ID as c_id  from CFS.ActionItem where (DueDate='$thirty_day_expire' OR DueDate='$seven_day_expire' OR DueDate='$one_day_expire') AND CFS.ActionItem.Status.id !='3'";
//$action_query="select CFS.ActionItem.ID as ActionID,CFS.ActionItem.Description,CFS.ActionItem.DueDate,CFS.ActionItem.Product,CFS.ActionItem.Product.Name as Product,CFS.ActionItem.Location.Name as Location,CFS.ActionItem.Details,CFS.ActionItem.Category.LookupName as Category,CFS.ActionItem.Priority.LookupName as Priority,CFS.ActionItem.Status.LookupName as status,CFS.ActionItem.Contact.ID as c_id  from CFS.ActionItem where DueDate='$seven_day_expire' AND CFS.ActionItem.Status.id !='4'";

$result = RNCPHP\ROQL::query($action_query)->next();


	while($data = $result->next())
	{
        
		if($data['DueDate']==$thirty_day_expire)
		{
			send_nofication_email($data,30);

		}

		if($data['DueDate']==$seven_day_expire)
		{
 
			send_nofication_email($data,7);

		}

		if($data['DueDate']==$one_day_expire)
		{

			send_nofication_email($data,1);

		}


	}


}


/* Below function is used to send email notification  */

function send_nofication_email($result='',$days)
{
	$Action_Item_id = $result['ActionID'];
	$Action_Item_Description = $result['Description'];
	$Action_Item_Due = str_replace('Z','',$result['DueDate']);
	$Action_Item_Product = $result['Product'];
	$Action_Item_Location = $result['Location'];
	$Action_Item_Details = $result['Details'];
	$Action_Item_Category = $result['Category'];
	$Action_Item_Priority = $result['Priority'];
	$Action_Item_Status = $result['status'];
	$Action_Item_Contact_Id = $result['c_id'];

	/* Fetching the contact email address */
	if($Action_Item_Contact_Id)
	{
		$contact = RNCPHP\Contact::fetch($Action_Item_Contact_Id);
		if($contact->Emails[0]->Address)
		{
			$contact_email = $contact->Emails[0]->Address;
		}
	}
	//This constant value is set for testing
	$contact_email='srini.cpchem@gmail.com';


	/* Checking the contact email address */
	if(!empty($contact_email))
	{

		try
		{
		$html_body="<style>
					.actiontable {font-size:14px;color:#000;width:80%;border-width: 2px;border-color: #002586;border-collapse: collapse;}
					.actiontable th {font-size:14px;color:#FFF;background-color:#002586;border-width: 2px;padding: 8px;border-style: solid;border-color: #729ea5;text-align:left;}
					.actiontable tr {background-color:#ffffff;}
					.actiontable td {font-size:13px;border-width: 2px;padding: 8px;border-style: solid;border-color: #002586;}
					</style>
					<br/><br/>
					Incomplete Action Item  <a href='https://cpchem.custhelp.com/app/action_items/update/id/$Action_Item_id' target='_blank'>Click here</a> to view online
					<br/><br/>
					<table class='actiontable' border='1'>
					<tr><th>Name</th><th>Details</th></tr>
					<tr><td>Description:</td><td>$Action_Item_Description</td></tr>
					<tr><td>Due Date:</td><td>$Action_Item_Due</td></tr>
					<tr><td>Product Line:</td><td>$Action_Item_Product</td></tr>
					<tr><td>Location:</td><td>$Action_Item_Location</td></tr>
					<tr><td>Details:</td><td>$Action_Item_Details</td></tr>
					<tr><td>Category:</td><td>$Action_Item_Category</td></tr>
					<tr><td>Priority:</td><td>$Action_Item_Priority</td></tr>
					<tr><td>Status:</td><td>$Action_Item_Status</td></tr>
					</table>";


		$mm = new RNCPHP\MailMessage();

		//set TO fields as necessary
		$mm->To->EmailAddresses = array($contact_email);


		//set subject
		$mm->Subject = "Incompleted Action Item {$result['ActionID']}- due in $days days";

		//set body of the email
		//$mm->Body->Text = $text_body;
		$mm->Body->Html = $html_body;

		//set marketing options
		$mm->Options->IncludeOECustomHeaders = false;

		//send email
		$mm->send();

		}
			catch ( \Exception $err ){
			echo "<br><b>Exception</b>: line ".__LINE__.": ".$err->getMessage()."</br>";
		}


	}

}

echo "All email send successfully";
