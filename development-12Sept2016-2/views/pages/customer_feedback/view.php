<rn:meta title="View Customer Complaint" template="standard.php" login_required="true"/>
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
            <h1>View Customer Feedback</h1>
            <!-- <h1>#rn:msg:SUBMIT_QUESTION_OUR_SUPPORT_TEAM_CMD#</h1>
             <p>#rn:msg:OUR_DEDICATED_RESPOND_WITHIN_48_HOURS_MSG#</p>!-->
        </div>
        <div class="translucent">

        </div>
        <br>

    </div>
</div>
<form id="rn_QuestionSubmit" method="post">
<div class="rn_PageContent rn_AskQuestion rn_Container">

        <div id="rn_ErrorLocation"></div>
                <rn:widget path="output/DataDisplay"  name="Incident.ref_no" default_value="3" required="true" />
                <rn:widget path="output/FieldDisplay"  name="Incident.PrimaryContact.LookupName" label='Contact Name' />
                <rn:widget path="output/DataDisplay"  name="Incident.c$complaint_type" default_value="3" required="true" />
                <div class='rn_Output'><rn:widget path="custom/customer_feedback/DeliveryLookupInput"  name="Incident.CFS$Delivery" label_input='Delivery'/></div>
                <div class='rn_Output'><rn:widget path="custom/customer_feedback/DeliveryDetails" /></div>
                <rn:widget path="output/ProductCategoryDisplay"  name="Incident.Category" default_value="" required="true" />
                <span class='rn_Output'><rn:widget path="custom/customer_feedback/DeliveryProductSelection" required="true"></span>
                <rn:widget path="output/FieldDisplay"  name="Incident.c$product_returned" default_value=""  required="true" />
                <rn:widget path="output/FieldDisplay"  name="Incident.c$product_sample_taken" default_value=""  required="true"/>
                <rn:widget path="output/FieldDisplay"  name="Incident.c$product_sample_returned_to" default_value=""  required="true"/>
                <rn:widget path="output/FieldDisplay"  name="Incident.c$request_type" default_value="" label_input="Request Type"/>
                <rn:widget path="output/FieldDisplay"  name="Incident.Subject" default_value="" label_input="Subject" required="true" />
                <rn:widget path="output/IncidentThreadDisplay" name="Incident.Threads" label="Comments"/>
                <rn:widget path="output/FieldDisplay"  name="Incident.c$target_date" label_input="Target Date" required="true"/>
                <rn:widget path="output/FieldDisplay"  name="Incident.c$sold_to_customer_name" default_value="" label_input="Sold to Customer Name" required="true" />
                <rn:widget path="output/FileListDisplay"  name="Incident.fattach" />
                <rn:widget path="custom/investigations/ChildInvestigations" />
                <div style='clear:both'></div><br>
                <a href='/app/customer_feedback/update/i_id/<?php echo getUrlParm('i_id'); ?>' class='btn primary_button' >Edit</a>


<!--<rn:widget path="input/FormSubmit" label_button="Submit Complaint" on_success_url="/app/ask_confirm" error_location="rn_ErrorLocation"/> --> <br><br>

</div>
</form>
