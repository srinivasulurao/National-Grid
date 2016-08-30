<rn:meta title="Add Action Item" template="standard.php" clickstream="actionitem_create" login_required="true"/>

<div class="rn_Hero">
    <div class="rn_HeroInner">
        <div class="rn_HeroCopy">
		<h1>Create New Action Item</h1>
           <!-- <h1>#rn:msg:SUBMIT_QUESTION_OUR_SUPPORT_TEAM_CMD#</h1>
            <p>#rn:msg:OUR_DEDICATED_RESPOND_WITHIN_48_HOURS_MSG#</p>!-->
        </div>
        <div class="translucent">
            
        </div>
        <br>

    </div>
</div>

<div class="rn_PageContent rn_AskQuestion rn_Container">
    <form id="rn_QuestionSubmit" method="post" action="/cc/CustomerFeedbackSystem/sendForm">
        <div id="rn_ErrorLocation"> </div>
            <rn:widget path="input/ProductCategoryInput" name="Incident.Product" required="true" label_input='Product Line'/></br>
            <rn:widget path="custom/action_items/CBOSelectionInput" name="CFS.ActionItem.Category" required="true"  label_input="Action Category" required="true"/>
            <!--<rn:widget path="custom/action_items/CBOSelectionInput" name="CFS.ActionItem.Location" required="true"  label_input="Please enter Location" required="true"/><br/> -->
            <rn:widget path="custom/action_items/CBOTextInput" name="CFS.ActionItem.Description" required="true"  label_input="Description" required="true"/>
            <rn:widget path="custom/action_items/CBOTextInput" name="CFS.ActionItem.Details" required="true"  label_input="Details" required="true" minimum_length="10" minimum_value="5" textarea="true"/>
            <rn:widget path="custom/action_items/TypeAheadContactLookup" name="CFS.ActionItem.Contact" required="true"  label_input="Contact" required="true"/>
            <rn:widget path="custom/action_items/CBOTextInput" name="CFS.ActionItem.ActionGroup"  required="true"  label_input="Action Group" required="true" minimum_length="10" minimum_value="5" textarea="true"/>
            <!--<rn:widget path="custom/action_items/CBOSelectionInput" name="CFS.ActionItem.DueDate" required="true" label_input="Due Date" required="true"/><br/>-->
            <rn:widget path="custom/action_items/customDatePickerr" name="CFS.ActionItem.DueDate"  label_input="Due Date" max_year="2100" required="true"/><br/>
            <rn:widget path="custom/action_items/CBOSelectionInput" name="CFS.ActionItem.Priority" required="true"  label_input="Priority" required="true"/>
            <rn:widget path="custom/action_items/CBOSelectionInput" name="CFS.ActionItem.Status" required="true"  label_input="Status" required="true"/>
            <rn:widget path="custom/action_items/CBOFileAttachmentUpload" name="CFS.ActionItem.Attachments"/><br/>
            <rn:widget path="input/FormSubmit" label_button="Submit Your Action Item" on_success_url="/app/action_items/list" error_location="rn_ErrorLocation"/>
     
    </form>
</div>
