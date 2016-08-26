<div id="rn_<?= $this->instanceID ?>" class="<?= $this->classList ?> inv-div">
<a href="javascript:void(0)" id="add_thread" class="btn info_button"> + Add</a>
<form action="/cc/CustomerFeedbackSystem/SaveThreadSendMail/i_id/<?php echo getUrlParm('i_id'); ?>" method="post" id="thread_submit" style='display:none'>
	<div id='rn_ErrorLocation2'></div>	
<rn:widget path="input/TextInput"  name="Incident.Threads" default_value="" label_input="Thread"  required="true"/>
<rn:widget path="input/FormInput"  name='contacts.email' default_value="" label_input="Request From" default_value='' required="true"/>
<rn:widget path="input/FormSubmit" label_button="Submit " on_success_url='/app/customer_feedback/investigations/update' error_location="rn_ErrorLocation2" label_confirm_dialog="Thread Saved Successfully, Further a mail has been sent to forward & track the incident !"  />
</form>
<div id='thread_display' style='font-size:12px'><rn:widget path="output/IncidentThreadDisplay" label='Communication History' name="Incident.Threads" /></div>
</div>t path="output/FileListDisplay" label='File Attachments' name="Incidents.fattach" />
	<rn:widget path="output/IncidentThreadDisplay" label='Communication History' name="Incident.Threads" />
	</div>
</div>