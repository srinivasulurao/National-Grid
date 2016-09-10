<div id="rn_<?= $this->instanceID ?>" class="<?= $this->classList ?>" >
<form id='investigation_closure_form' action='/cc/CustomerFeedbackSystem/setInvestigationClosure/i_id/<?php echo getUrlParm('i_id'); ?>' method='post' class='inv-div'>
	<div id='rn_err_validation'></div>
	<rn:widget path='input/SelectionInput' name="Incident.c$was_there_a_problem" required='true' default_value='0'/>
	<div id='why_fields' style='display:none'>
		<span id='why1'><rn:widget path='input/TextInput' name="Incident.c$why1" label_input='Why #1<span class="rn_Required">*</span>'/></span>
		<span id='why2'><rn:widget path='input/TextInput' name="Incident.c$why2" label_input='Why #2<span class="rn_Required">*</span>' /></span>
		<span id='why3'><rn:widget path='input/TextInput' name="Incident.c$why3" label_input='Why #3<span class="rn_Required">*</span>' /></span>
		<rn:widget path='input/TextInput' name="Incident.c$why4" />
		<rn:widget path='input/TextInput' name="Incident.c$why5" />
		<span id='rcc'><rn:widget path='input/SelectionInput' name="Incident.c$root_cause_category" label_input='Root Cause Category<span class="rn_Required">*</span>' /></span>
	</div>
	<rn:widget path="input/FormSubmit" label_button="Close Investigation" on_success_url="none" error_location="rn_err_validation" label_on_success_banner='Investigation Closure Details saved successfully!' /><br><br>
</form>	
</d