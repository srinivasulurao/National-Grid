<div id="rn_<?= $this->instanceID ?>" class="<?= $this->classList ?> inv-div">
<a href="javascript:void(0)" id="add_thread" class="btn info_button"> + Add</a>
<a href="/app/investigations/update/i_id/<?php echo getUrlParm('i_id'); ?>"  class="btn success_button">Refresh</a>
<form action="/cc/CustomerFeedbackSystem/SaveThreadSendMail/i_id/<?php echo getUrlParm('i_id'); ?>" method="post" id="thread_submit" style='display:none'>
	<div id='rn_ErrorLocation2'></div>
<rn:widget path="input/TextInput"  name="Incident.Threads" default_value="" label_input="Thread"  required="true"/>
<rn:widget path="input/FormInput"  name='contacts.email'  label_input="Request From Third Party"  required="true"/>
<rn:widget path="input/FileAttachmentUpload"  />
<span id='contact_look_up' style='position:relative;bottom:20px;'></span><br>
<rn:widget path="input/FormSubmit" label_button="Submit " on_success_url='none' error_location="rn_ErrorLocation2" label_on_success_banner="Updated Successfully !"  />
</form>

<div id='thread_display' style='font-size:12px'>
	<rn:widget path="output/FileListDisplay" label='File Attachments' name="Incidents.fattach" display_thumbnail="false"/>
	<div style='clear:both'></div>
	<rn:widget path="output/IncidentThreadDisplay" label='Communication History' name="Incident.Threads" />
	</div>
</div>
