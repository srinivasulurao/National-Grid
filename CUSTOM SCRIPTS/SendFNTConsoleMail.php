<?php
set_time_limit(0);

ignore_user_abort(true);

ini_set('display_errors', 1);

require_once( get_cfg_var("doc_root")."/ConnectPHP/Connect_init.php");

use RightNow\Connect\v1_2 as RNCPHP;

initConnectAPI("FNT_CWS","FNTRocks!");


$client_mail=$_REQUEST['to'];
$message=$_REQUEST['message'];
$i_id=$_REQUEST['i_id'];
    

$incident = RNCPHP\Incident::fetch($i_id);
    $incident->Threads = new RNCPHP\ThreadArray();
    $incident->Threads[0] = new RNCPHP\Thread();
    $incident->Threads[0]->EntryType = new RNCPHP\NamedIDOptList();
    $incident->Threads[0]->EntryType->ID = 3; // Used the ID here. See the Thread object for definition
    $incident->Threads[0]->Text =$message;
    $incident->save();

    //Now Send the mail to the client.
    if($client_mail):
    $FNTConfig = RNCPHP\FNT\Config::fetch(1);
                $p_exp=time()+($FNTConfig->LinkExpirationHours*3600);
                $p_created=str_replace("=","",base64_encode($client_mail));
                $p_tok=str_replace("=","",base64_encode($FNTConfig->SecurityString));
                $p_ques=str_replace("=","",base64_encode($message));
                $p_asked_by=str_replace("=","",base64_encode("Chevron Admin"));
                $site_host=($_SERVER['HTTP_HOST']=="cpchem.custhelp.com")?"http://cpchem.custhelp.com":"http://cpchem--pro.custhelp.com";
                
                $html_body=<<<xyz
                <div style='font-family:arial;line-height:30px'>
                <b>Hello User,</b><br>
                Your input is required to take further action for a customer feedback complaint (Ref-No: {$incident->LookupName}). <br>
                Please click the link below to provide your response.<br>
                <a href='$site_host/cgi-bin/cpchem.cfg/php/custom/oracle/fnt/fnt_incident_update.php?p_i_id={$incident->ID}&p_exp={$p_exp}&p_tok={$p_tok}&p_created={$p_created}&p_ques={$p_ques}&p_asked_by={$p_asked_by}'>Click To Respond</a>
                <br><br>
                Thanks a lot<br>
                CPChem Team<br>
                <img src='{$_SERVER['HTTP_HOST']}/euf/assets/themes/standard/images/CPChem_logo.png' style='width:100px'>
                </div>
xyz;

$client_message=<<<xyz
                <div style='font-family:arial;line-height:30px'>
                $message <br>
                <a href='$site_host/cgi-bin/cpchem.cfg/php/custom/oracle/fnt/fnt_incident_update.php?p_i_id={$incident->ID}&p_exp={$p_exp}&p_tok={$p_tok}&p_created={$p_created}&p_ques={$p_ques}&p_asked_by={$p_asked_by}'>Click To Respond</a>
                <br><br>
                Thanks a lot<br>
                CPChem Team<br>
                <img src='{$_SERVER['HTTP_HOST']}/euf/assets/themes/standard/images/CPChem_logo.png' style='width:100px'>
                </div>
xyz;


                
                    $mm = new RNCPHP\MailMessage();
                //set TO,CC,BCC fields as necessary
                    $mm->To->EmailAddresses = array($client_mail);
                //set subject
                    $mm->Subject = "CPCHEM-Forward & Tracking of Complaint No ".$incident->LookupName;
                //set body of the email
                    $mm->Body->Html = (!$message)?$html_body:$client_message;
                //set marketing options
                    $mm->Options->IncludeOECustomHeaders = false;
                //send email
                    $mm->send();
                    echo "Mail Sent Successfully !";
               endif;     

?>
