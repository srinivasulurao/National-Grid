<rn:meta title="Customer Complaint" template="standard.php" login_required="true"/>
<style>
    .rn_Label{
        display:block !important;
    }
.rn_DataLabel{
font-weight:bold;
}
</style>
<div class="rn_Hero">
    <div class="rn_HeroInner">
        <div class="rn_HeroCopy">
            <h1>Modify Complaint Details</h1>
            <!-- <h1>#rn:msg:SUBMIT_QUESTION_OUR_SUPPORT_TEAM_CMD#</h1>
             <p>#rn:msg:OUR_DEDICATED_RESPOND_WITHIN_48_HOURS_MSG#</p>!-->
        </div>
        <div class="translucent">

        </div>
        <br>

    </div>
</div>

<div class="rn_PageContent rn_AskQuestion rn_Container">
    <form id="rn_QuestionSubmit" method="post" action="/cc/CustomerFeedbackSystem/updateCustomerComplaintSendForm/<?php echo getUrlParm('i_id'); ?>">
        <div id="rn_ErrorLocation"></div>
        <rn:widget path="input/SelectionInput"  name="Incident.c$complaint_type" default_value="3" required="true" />
        <rn:widget path="custom/customer_feedback/DeliveryLookupInput"  name="Incident.CFS$Delivery" label_input='Delivery' required="true"/>
        <rn:widget path="custom/customer_feedback/DeliveryDetailsGrid">
            <rn:widget path="input/ProductCategoryInput"  name="Incident.Category" default_value="" required="true" />
            <rn:widget path="custom/customer_feedback/DeliveryProductSelection" required="true">
                <rn:widget path="input/SelectionInput"  name="Incident.c$product_returned" default_value=""  required="true" />
                <rn:widget path="input/SelectionInput"  name="Incident.c$product_sample_taken" default_value=""  required="true"/>
                <rn:widget path="input/TextInput"  name="Incident.c$product_sample_returned_to" default_value=""  required="true"/>
                <rn:widget path="input/SelectionInput"  name="Incident.c$request_type" default_value="" label_input="Request Type"/>
                <rn:widget path="input/TextInput"  name="Incident.Subject" default_value="" label_input="Subject" required="true" />
                <rn:widget path="input/TextInput"  name="Incident.Threads" default_value="" label_input="Thread"  required="true"/>
                <rn:widget path="custom/customer_feedback/DatePickerInput"  name="Incident.c$target_date" label_input="Target Date" required="true"/> <br>
                <rn:widget path="input/FileAttachmentUpload"  />
                <rn:widget path="input/TextInput"  name="Incident.c$sold_to_customer_name" default_value="" label_input="Sold to Customer Name" required="true" />


                <!-- Submit button implemented -->
                <rn:widget path="input/FormSubmit" label_button="Submit Complaint" on_success_url="/app/ask_confirm" error_location="rn_ErrorLocation"/><br><br>

    </form>
</div>



