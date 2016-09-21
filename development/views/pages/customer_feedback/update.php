<rn:meta title="Update Customer Feedback" template="standard.php" login_required="true"/>
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
            <h1>Update Customer Feedback</h1>
            <!-- <h1>#rn:msg:SUBMIT_QUESTION_OUR_SUPPORT_TEAM_CMD#</h1>
             <p>#rn:msg:OUR_DEDICATED_RESPOND_WITHIN_48_HOURS_MSG#</p>!-->
        </div>
        <div class="translucent">

        </div>
        <br>

    </div>
</div>
<?php
$ci=&get_instance();
$i_id=getUrlParm('i_id');
$review_id=$ci->model('custom/CustomerFeedbackSystem')->getStatusIdByStatusName("Review");
$customer_complaint=$ci->model('custom/CustomerFeedbackSystem')->getIncident($i_id);
$review_state=($review_id==$customer_complaint->StatusWithType->Status->ID)?1:0;
$draft_opted=($customer_complaint->CustomFields->c->draft_opted && $customer_complaint->CustomFields->c->draft); 
 ?>
<div class="rn_PageContent rn_AskQuestion rn_Container">
    <form id="rn_QuestionSubmit" method="post" action="/cc/CustomerFeedbackSystem/updateCustomerComplaintSendForm/<?php echo getUrlParm('i_id'); ?>">
        <div id="rn_ErrorLocation"></div>
                <rn:widget path="input/SelectionInput"  name="Incident.c$request_type" default_value="" label_input="Request Type"/>
                <div class="rn_Hidden">
                    <rn:widget path="input/SelectionInput"  name="Incident.c$complaint_type" default_value="3" required="true" />
                </div>
                <rn:widget path="custom/customer_feedback/DeliveryLookupInput"  name="Incident.CFS$Delivery" label_input='Delivery' required="true"/>
                <rn:widget path="custom/customer_feedback/DeliveryDetailsGrid">
                <rn:widget path="input/TextInput"  name="Incident.c$sold_to_customer_name" default_value="" label_input="Sold to Customer Name" required="true" />
                <rn:widget path="input/TextInput"  name="Incident.c$customer_contact_name" default_value="" label_input="Customer Contact Name" />
                <rn:widget path="input/TextInput"  name="Incident.c$customer_ph_no" default_value="" label_input="Customer Phone Number" />
                <rn:widget path="input/TextInput"  name="Incident.c$customer_contact_email" default_value="" label_input="Customer Contact Email" />

                <span id='sold_to_customer_suggestions'></span>
<!--                 <rn:widget path="input/TextInput"  name="Incident.c$ship_to_customer_name" default_value="" label_input="Ship to Customer Name" required="true" /> -->
                <span id='ship_to_customer_suggestions'></span>
<!--                 <rn:widget path="input/TextInput"  name="Incident.c$product_no" default_value="" label_input="Product Number" required="true" /> -->
                <span id='product_number_suggestions'></span>
        	    <rn:widget path="custom/input/ProductCategoryInput" name="Incident.Product" label_input="Product Line" default_value="" required="true" only_display="" />
         	    <span class='prod_cat_sel'><rn:widget path="input/ProductCategoryInput"  name="Incident.Category" default_value="" required="true" /></span>
                <rn:widget path="custom/customer_feedback/DeliveryProductSelection" required="true">
                <span id='product_related_fields' style='display:none'>
                <rn:widget path="input/SelectionInput"  name="Incident.c$product_returned" default_value=""   />
                <rn:widget path="input/SelectionInput"  name="Incident.c$product_sample_taken" default_value=""  />
                <span id='prod_sample_ret' style='display:none'><rn:widget path="input/TextInput"  name="Incident.c$product_sample_returned_to" default_value="" /></span>
                </span>
                <rn:widget path="input/TextInput"  name="Incident.Subject" default_value="" label_input="Subject" required="true" />
                <rn:widget path="output/IncidentThreadDisplay" name="Incident.Threads" label="Comments" />
                <rn:widget path="input/TextInput"  name="Incident.Threads" default_value="" label_input="Add Comments"/>
                <rn:widget path="custom/customer_feedback/DatePickerInput"  name="Incident.c$target_date" label_input="Target Date" required="true"/>
                <rn:widget path="input/SelectionInput"  name="Incident.c$formal_response" label_input="Formal Response" default_value='0' required="true"/>
                <?php if($draft_opted): ?><rn:widget path="input/SelectionInput"  name="Incident.c$draft" default_value="0"  label_input="Save as Draft"/><?php endif; ?>
                <?php if($review_state): ?><rn:widget path='input/SelectionInput' name='Incident.c$complaint_resolved' label_input="Complaint Resolved" /><?php endif; ?>

                <rn:widget path="output/FileListDisplay"  name="Incident.fattach" display_thumbnail="false"/><br>
                <rn:widget path="input/FileAttachmentUpload"/>
                <rn:widget path="custom/customer_feedback/FormFunctionalityProvider" /> <br>


                <!-- Submit button implemented -->
                <rn:widget path="input/FormSubmit" label_button="Submit Complaint" on_success_url="/app/customer_feedback/confirm" error_location="rn_ErrorLocation"/><br><br>

    </form>
</div>
