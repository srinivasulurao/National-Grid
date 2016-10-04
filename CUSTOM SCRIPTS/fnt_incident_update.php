<?
/*
 * Author: Ryan Seltzer
 * Project Location: http://tools.src.rightnow.com/code/forward-and-track/git/nodes
 * Blob Hash: $Id: 07f906ee827c210de4154a90fa86db3828f86b01 $
 * Description: These are the pages for allowing incident updates via a direct link secured via token for a specific amount of time.  The security of these pages are as good as the security of the link itself during its valid time period in addition to IP addresses restrictions.
*/
// ----------------------------------------------------------------------------
//       File Name: fnt_incident_update.php
// Req. Parameters: p_i_id, p_created, p_tok, p_exp
// Opt. Parameters: none
//  Included Files: include/init.phph, custom/oracle/libraries/PSLog-2.0.php,
//                  custom/oracle/libraries/TokenAuth.phph
//         Purpose: input data to update incident by an SME
// ----------------------------------------------------------------------------

//ini_set('display_errors',true);
define("CUSTOM_SCRIPT", true);

$ConnectUser = "FNT_CWS";
$ConnectPass = "FNTRocks!";

$ip_dbreq = true;
require_once('include/init.phph');

//initialize CPHP and get configuration related information
require_once( get_cfg_var("doc_root")."/ConnectPHP/Connect_init.php" );
use RightNow\Connect\v1_2 as RNCPHP; //use CCOM 1_2 for 12.2 and greater

require_once("custom/src/oracle/libraries/PSLog-2.0.php");

list ($common_cfgid, $rnw_common_cfgid, $rnw_ui_cfgid, $ma_cfgid) = msg_init($p_cfgdir, 'config', array('common', 'rnw_common', 'rnw_ui', 'ma'));
list ($common_mbid, $rnw_mbid) = msg_init($p_cfgdir, 'msgbase', array('common', 'rnw'));

//change this to false to remove attachment functionality
$fattach_enabled = true;
//Inialize Logging framework
use PS\Log\v2\Log as PSLog;
use PS\Log\v2\Type as LogTypes;
use PS\Log\v2\Severity as LogSeverities;

$x = new PSLog(	LogTypes::ExternalEvent,	//	type
			"FNT - Inbound",		//	sub type
			null,					//	severity
			null,					//	$rntConfigs
			false,					//	$logToFile
			true,					//	$logToDb
			null,					//	$credentials
			null,					//	$source
			true,					//	$logSource
			null,					//	$connectNamespace
			LogSeverities::Debug,	//	$logThreshold
			null					//	$stdOutputThreshold
	);

PSLog::init(LogTypes::CustomAPI, "FNT - Incident Update Page");
//Ensure all security checks are passed before loading the page.
//START security checks
//First ensure valid ip making the request

$valid_hosts = cfg_get($common_cfgid, SEC_VALID_ADMIN_HOSTS);


if ($valid_hosts && (!$REMOTE_ADDR || !ipaddr_validate($valid_hosts, $REMOTE_ADDR)))
{
    printf("<head><title>%s</title>
                  <style>FONT { font-family: sans-serif }</style></head>
                  <font size=+2><b>%s</b></font>
                  <br /><hr size=1><br />
                  <font size=+1>%s<p>
                  <b>%s: </b>%s</font><p><br />\n",
           msg_get($common_mbid, RNT_FATAL_ERROR_LBL),
           msg_get($common_mbid, FATAL_ERROR_LBL),
           msg_get($common_mbid, ACCESS_DENIED_LBL),
           msg_get($common_mbid, REASON_LBL),
           msg_get($common_mbid, $REMOTE_ADDR ? CLIENT_ADDR_NOT_AUTH_MSG : NO_CLIENT_ADDR_SPEC_MSG));

	//Log the error
    PSLog::error("Attempt to access FNT Incident Update Page from an invalid IP Addr.  Valid IP addresses must be defined in SEC_VALID_ADMIN_HOSTS.  Invalid IP = $REMOTE_ADDR");
    exit;
}

try{
	//initConnectAPI();	//	this isn't working - we need to init with username password.
	initConnectAPI($ConnectUser, $ConnectPass);
}catch( RNCPHP\ConnectAPIError $err)
{
	print_r("ConnectAPIError: init failed: ".$err->getMessage());
}

//First initialize without creds at first to then get the config values from FNT$FNTConfig
try
{

    //There should always only be one record in this table
	//$confQueryResult = RNCPHP\ROQL::queryObject("SELECT FNT.Config FROM FNT.Config WHERE ID = 3 ORDER BY ID ASC LIMIT 1")->next();
	//$FNTConfig = $confQueryResult->next();
	$FNTConfig = RNCPHP\FNT\Config::fetch(1);

	if(!isset($FNTConfig->ID))
	{
    	print_r("No FNT\$FNTConfig has been setup in CX.  Create a configuration instance and populate all necessary information for forward and track");
		exit();
	}
	//printf("<pre>FNTCOnfig:\n%s</pre>", var_export($FNTConfig,1));

	$ADD_FATTACH_ENABLED  = $FNTConfig->AddAttachEnabled;
	$VIEW_FATTACH_ENABLED = $FNTConfig->ViewAttachEnabled;

	//$VIEW_FATTACH_ENABLED = true;
	//$ADD_FATTACH_ENABLED = true;

	//now pull down the username and password from FNT$FNTConfig and use them to re-initialize API
	//This is necessary for calling getAdminURL to allow downloading of file attachments from this page

	initConnectAPI($FNTConfig->CWSUserName, $FNTConfig->CWSPassword);
}
catch ( RNCPHP\ConnectAPIError $err )
{
    echo $temp = $err->getLine()." :: ".$err->getMessage();
    //echo "Error:  $temp\n";
    PSLog::error("sss ConnectAPIError: $temp");
    exit();
}


//TokenAuth will check for valid token and timestamp and return false if both conditions are not true
//Token is based on all GET parameters aside from the token itself
require_once('custom/oracle/libraries/TokenAuth.phph');
$token = TokenAuth::GetInstance($_GET, $FNTConfig->SecurityString); //	ChemFNT56
$valid_token = $token->Validate();

$valid_token=(base64_decode($_GET['p_tok'])==$FNTConfig->SecurityString && time() < $_GET['p_exp'])?1:0;
if(!($valid_token))
{
    print("Access Denied");
	//This is not necessarily a hacking attempt, it could just be an invalid token
    //PSLog::error("Error: Attempt to load page with an invalid token.");
    exit();
}


//END security checks
//get the incident record
try
{
    $incident = RNCPHP\Incident::fetch($p_i_id);
}
catch ( RNCPHP\ConnectAPIError $err )
{
    $temp = $err->getLine()." :: ".$err->getMessage();
    echo "Error:  $temp\n";
    PSLog::error("Error: $temp");
    exit();
}

if (!$incident)
{
    print("The incident with the i_id = ".$p_i_id." could not be found.<br>There could be following reasons for this:<br><br><li>The incident you are trying to update has been deleted in the meantime</li><li>You are creating this incident just now, in which case you will have to save it first.</li><li>The incident reference number came from a link in an email that may have been line wraped, in this case copy and paste the link back together.</li>");
    exit();
}

//do attachment stuff if enabled via fnt_config
if ($ADD_FATTACH_ENABLED)
{

	//add attachments to POST VARS
	if ($p_add_fattach || $p_new_fattach_size || $p_single_file)
	{
	    if (!$p_new_fattach_size)
		{
    		PSLog::error("Error: File attachment upload failed");
	        print("File attachment upload failed.");
	        exit;
		}
	    $tmp_name = basename(strval($p_new_fattach));
	    $tmp_name = substr($tmp_name, 3);
	    $tmp_name = '/tmp/'.$tmp_name;
	    move_uploaded_file($_FILES['p_new_fattach']['tmp_name'], $tmp_name);
	    chmod($tmp_name, 0666);
	    $p_fattach_tmpname = $tmp_name;
	    $p_fattach         = $_FILES['p_new_fattach']['name'];
	    $p_fattach_type    = $_FILES['p_new_fattach']['type'];
	    $p_fattach_size    = $_FILES['p_new_fattach']['size'];
	}

    if(isset($p_fattach))
	{
		try
		{
    		//ADD THE ATTACHMENTS TO THE INCIDENT
    		$incident->FileAttachments = new RNCPHP\FileAttachmentIncidentArray();
		    $fattach = new RNCPHP\FileAttachmentIncident();
		    $fattach->ContentType = $p_fattach_type;
		    $fattach->setFile($p_fattach_tmpname);
		    $fattach->FileName = $p_fattach;
		    $fattach->Private = false; //Client don't want the attachment to be private.
		    $incident->FileAttachments[] = $fattach;

			if($incident->save(RNCPHP\RNObject::SuppressAll)) {
				$FileStatusMessage  .= (!empty($FileStatusMessage) ? (",") : ("")).$p_fattach;
			}
		}
		catch ( RNCPHP\ConnectAPIError $err )
		{
    		$temp = $err->getMessage();
		    echo "SME Add Attachment Failure. Error:  $temp\n";
		    PSLog::error("SME Add Incident Attachment Failure: ERROR_MSG: $temp");
		}
	}
}//END IF attachments


//Check if this is the submission page with a new inc note.
if($p_response and !$p_fattach) //if they submitted then add the note
{
	try
	{
    	//ADD THE THREAD NOTE TO THE INCIDENT
//  $incident->StatusWithType->Status->ID = $config->UpdatedStatus;
		if($FileStatusMessage) {
			$extraText = "\n\n* Files added: ".$FileStatusMessage." ";
		}else{
			$extraText = '';
		}
        $third_party_email=base64_decode($_GET['p_created']);
    	$incident->Threads = new RNCPHP\ThreadArray();
    	$incident->Threads[0] = new RNCPHP\Thread();
    	$incident->Threads[0]->EntryType = new RNCPHP\NamedIDOptList();
    	$incident->Threads[0]->EntryType->ID  = 4; //Customer Proxy
		$incident->Threads[0]->ContentType=new RNCPHP\NamedIDOptList();
		$incident->Threads[0]->ContentType->ID=2;
		$question_asked="<span style='color:black;display:inline-block;text-decoration:underline'>".base64_decode($_GET['p_asked_by'])."</span> : ".base64_decode($_GET['p_ques'])."<br>";
    	$incident->Threads[0]->Text =$question_asked."<span style='color:black;display:inline-block;text-decoration:underline'>FNT User</span> : ".$p_response . "<br>* This entry was added by [{$third_party_email}] via forward and track".$extraText; //$p_response is a $_POST variable
        $incident->StatusWithType->Status->ID = $FNTConfig->statusid_responded;
        $incident->save();

    	print("Thank you for submitting your response. You input has been added to this incident. You may close this window.");
	}
	catch ( RNCPHP\ConnectAPIError $err )
	{
    	$temp = $err->getMessage().$err->getLine();
	    echo "SME Update Error:  $temp\n";
	    PSLog::error("SME Incident Update Error: ERROR_MSG: $temp");
	}
	exit(); //always exit now
}
        $p_refno = $incident->ReferenceNumber;

// ----------------------------------------------------------------------------
// -- Start Page Content ----------------------------------------------------
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<!-- Head ->>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>- -->
<head>
<title>Update Incident</title>
<script>

function _do_submit(form)
{
    if (form.p_response.value == '') {
        alert("Please enter a message.");
        form.p_response.focus();
        //return false;
    }else{
		form.p_add_fattach.value = 1;
		return true;
	}
}

</script>

<style>

div.banner {
    background:#DDDDDD;
    font-size:18px;
    margin-bottom:5px;
    margin-top:5px;
    padding:3px;
}
div.thrtext {
    padding-bottom:10px;
}
div.warning {
    background-color:#FFFFE0;
    border:1px solid #808080;
    color:#990000;
    margin:10px 0px 5px 0px;
    padding:8px 6px 8px 6px;
}
div.FileStatusOK {
	background-color: #00EEAA;
	border: 1px solid gray;
	font-weight: bold;
	margin: 4px;
	padding: 4px;
}

body{
    width: 980px;
    margin: auto;
    border: 1px solid lightgrey;
    padding: 20px;
    background: white;
    font-size:14px !important;
    font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
}
html{
background:#40526B;
}

</style>
<style>
#logo-home {
    font-family: arial,helvetica;
    font-size: 30pt;
    font-weight: bold;
    //height: 64px;
    //padding: 25px 0 0 22px;
    width: 700px;
}</style>
</head>

<!-- Body ->>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>- -->
<body class="bgcolor">

<div id="header">
                    <div id="logo-home">

                            <img src="/euf/assets/themes/standard/images/CPChem_logo.png"
                                alt="Logo Media-Saturn" /></a>
								CFS Third Party Response
					</div>
</div>
<?
  $querystring="p_tok=$p_tok&p_i_id=$p_i_id&p_exp=$p_exp&p_created=$p_created&p_ques={$_GET['p_ques']}&p_asked_by={$_GET['p_asked_by']}";
?>
<form
	name="_main"
	method="post"
	action="?<?=$querystring?>"
	enctype="multipart/form-data"

	<input type="hidden" name="p_add_fattach" value="" />
	<input type="hidden" name="p_single_file" value="0" />
<!-- Question Attributes ->>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>- -->

<div class="banner">
Reference No:&nbsp;&nbsp;<?= $p_refno ?>
</div>

<div>
<b>Subject:</b>&nbsp;&nbsp;<?= $incident->Subject ?>
</div>

<div>
<b>Primary Contact:</b>&nbsp;&nbsp;<?= $incident->PrimaryContact->Name->First ?>&nbsp;<?= $incident->PrimaryContact->Name->Last?>
</div>
<div>
<b>Contact Email Address:</b>&nbsp;&nbsp;<?= empty($incident->PrimaryContact->Emails[0]->Address) ? "n/a" : $incident->PrimaryContact->Emails[0]->Address ?>
</div>
<?
	if($contact->Phones) {
		$tel_email_body = NULL;

		foreach($contact->Phones as $ph_number){
			if(!empty($ph_number->Number)) {
				$tel_email_body .= "\t".rpad($ph_number->PhoneType->LookupName .":", " ", 24).$ph_number->Number."\n";
			}
		}
	}
?>
<div>
<b>Contact Phone number:</b>&nbsp;&nbsp;<?= empty($tel_email_body) ? "n/a" : nl2br($tel_email_body) ?>
</div>

<div>
<b>Assigned To:</b>&nbsp;&nbsp;<?= $incident->AssignedTo->StaffGroup->LookupName ?>&nbsp;|&nbsp;<?= $incident->AssignedTo->Account->LookupName?>
</div>
<br />

<div class="banner">
Comments
</div>

<div width="99%" style="border: 1px solid gray; padding: 5px;">
<?

    foreach($incident->Threads as $thread)
    {
if($thread->Text == base64_decode($_GET['p_ques']) || substr_count($thread->Text,"[".base64_decode($_GET['p_created']."]"))): //Client Question & User Answer only is allowerd.
		$entered_by = $thread->Account->LookupName;
		//printf("<pre>entry type: %s [%s]</pre>", $thread->EntryType->ID, $thread->EntryType->LookupName);

		//	bad idea to use LookupName as it will fail on language interfaces other than engtlish
		switch($thread->EntryType->ID)
		{
			case 1:
			case 'Note':
				$bgcolor = "#FFFFE0";
				$entered_by = $thread->Account->LookupName;
				break;
			case 2:
			case 'Staff Account':
				$bgcolor = "#E0EAD8";
				break;
			case 3:
			case 'Customer':
				$bgcolor = "#D7E9F6";
				if(isset($thread->Contact->LookupName))
				{
					$entered_by = $thread->Contact->LookupName;
				}
				break;
			case 4:
			case 'Customer Proxy':
				$bgcolor = "#D7E9F6";
				if(isset($thread->Contact->LookupName))
				{
					$entered_by = $thread->Contact->LookupName;
				}
				break;
			default:
				$bgcolor = "#CCCCCC";
				break;
		}

		printf("<div style=\"font-size:16px;background:%s;margin-top:5px;margin-bottom:5px;\">%s - %s<span style=\"float:right\">%s,  %s</span></div>", $bgcolor, $thread->EntryType->LookupName, $thread->Channel->LookupName, $entered_by, date("m/d/Y h:i:s A", $thread->CreatedTime));
		printf("<div class=\"thrtext\">%s</div>", str_replace("\n", "<br />", $thread->Text));
endif;
    } //end foreach
?>

</div>
<!-- Thread ->>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>- -->

<?
// ----------------------------------------------------------------------
//File attachment information
//IF THERE ARE ANY ATTACHMENTS TO THIS INCIDENT, DISPLAY THEM ON THE PAGE


if($VIEW_FATTACH_ENABLED==true && count($incident->FileAttachments) > 0)
{
	$nonprivatefilecount = 0;
	foreach($incident->FileAttachments as $file) {
		if(! $file->Private) {
			$nonprivatefilecount++;
		}
	}

	if($nonprivatefilecount > 0) {
		print("<div class=\"banner\">Attachments</div>\n");
	}

?>
<div><ul style="padding-top:0px;margin-top:0px;">
<?php

    try
	{
	    foreach($incident->FileAttachments as $file)
	    {
			if($file->Private) {
				continue;
			}

	        unset($url);
			//must call initConnectAPI with creds to use getAdminURL
	        $url = $file->getAdminURL();
	        //$url = "test.com";
	        printf("&nbsp;&nbsp;<li style=\"line-height:0.4em\"><a class=\"%s\" href=\"%s\">%s</a>\n",
	                $file->ContentType, $url, $file->FileName);

	        if ($file->Private)
			{
	            echo ("&nbsp;&nbsp; <font color=\"#ff0000\">Private</font><br />\n");
			}
			echo("</li>");
	    }//END FOREACH LOOP
	}
	catch(Exception $ex)
	{
		PSLog::error("ERROR: There was an error calling getAdminURL for Attachments.  Check your credentials in FNT\$Config for CWSUserName and CWSPassword\n\nERROR_MSG: $ex");
		print("Attachments are currently unavailable");
	}
?>
</ul></div>
<?
} //end if fileattachments
?>


<?
if($ADD_FATTACH_ENABLED)
{
?>
<div class="banner">Attach Additional Documents</div>
<? if(!empty($FileStatusMessage)) {
	printf("<div class='FileStatusOK'>Following file(s) have been attached:\n<br>%s</div>", $FileStatusMessage);
	printf("<input type='hidden' name='FileStatusMessage' value='%s' />", $FileStatusMessage);
}
?>
<input
	   name="p_new_fattach"
	   id="p_new_fattach"
	   type="file"
	   size="40"
	   onchange="document._main.add_fattach.disabled = (this.value.length == 0)"
	   />
&nbsp;&nbsp;
<input
		name="add_fattach"
		type="submit"
		class="btn"
		disabled
		value="Add Attachment"
		onclick="
			this.disabled=true;
			document._main.submit_btn.disabled=true;
			document._main.submit();"
		/>

<? } //end if($ADD_FATTACH_ENABLED)?>
<!-- New Information ->>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>- -->

<br />
<div class="banner">
Add Comments
</div>

<div>
<a name='message'></a>Your Message
    <textarea value='<?php echo $_POST['p_response']; ?>' name="p_response" id="p_response" style="width: 100%;font-family: arial; font-size: 14px; border: 1px solid grey; padding: 5px;" rows="8" wrap><?=$p_response?></textarea>
</div>

<!-- Submit ->>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>- -->

<div class="banner">When you are done:</div>
<input type="submit" class="btn" name="submit_btn" id="submit_btn" value="Submit" onclick="_do_submit(document._main)"/>
</form>
</body>
</html>
