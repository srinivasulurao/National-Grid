<rn:meta title="Supplier Complaint" template="standard.php" clickstream="Edit" login_required="true"//>

<div class="rn_Hero">
    <div class="rn_HeroInner">
        <div class="rn_HeroCopy">
		<h1>Please raise a complaint</h1>
           <!-- <h1>#rn:msg:SUBMIT_QUESTION_OUR_SUPPORT_TEAM_CMD#</h1>
            <p>#rn:msg:OUR_DEDICATED_RESPOND_WITHIN_48_HOURS_MSG#</p>!-->
        </div>
        <div class="translucent">
            
        </div>
        <br>

    </div>
</div>
<?php 
 $ComplaintID=$action_id=getUrlParm(i_id);
?>
<div class="rn_PageContent rn_AskQuestion rn_Container">
    <form id="rn_QuestionSubmit" method="post" action="/cc/CustomerFeedbackSystem/SupplierCompliantEdit/<?php echo $ComplaintID; ?>">
        <div id="rn_ErrorLocation"></div>
			<rn:widget path="input/SelectionInput"  name="Incident.c$request_type" label_input="Request Type" read_only="true"  /> <br>
	<rn:widget path="input/SelectionInput"  name="Incident.c$complaint_type" default_value="6" /> <br>
	<rn:widget path="custom/supplier_feedback/TypeAheadSupplierLookup" name="CFS.Supplier.Name" read_only="true" required="true"  label_input="Supplier name" required="true"/><br/>
	<rn:widget path="input/TextInput"  name="Incident.c$supplier_order_number" label_input="Supplier Order number"  /> <br>
	<rn:widget path="input/TextInput"  name="Incident.Subject" label_input="Subject"  /> <br>
	<rn:widget path="output/IncidentThreadDisplay" name="Incident.Threads" /><br>
	<rn:widget path="input/TextInput"  name="Incident.Threads" label_input="'Add to thread"  /> <br>
	<rn:widget path="input/TextInput"  name="Incident.c$proposed_solution" label_input="Proposed Solution"  /> <br>
	<rn:widget path="custom/supplier_feedback/customDatePickerr"  name="Incident.c$target_date" label_input="Target Date"  /><br>

	<!--<rn:widget path="custom/action_items/customDatePickerr" name="Incident.c$target_date" label_input="Target Date" max_year="2100"/><br/>-->
	<rn:widget path="input/FileAttachmentUpload"  /> <br>
	<rn:widget path="input/FormSubmit" label_button="Submit " on_success_url="/app/ask_confirm" error_location="rn_ErrorLocation"/>
	
    </form>
</div>
