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
            <h1>View Complaint Details</h1>
            <!-- <h1>#rn:msg:SUBMIT_QUESTION_OUR_SUPPORT_TEAM_CMD#</h1>
             <p>#rn:msg:OUR_DEDICATED_RESPOND_WITHIN_48_HOURS_MSG#</p>!-->
        </div>
        <div class="translucent">

        </div>
        <br>

    </div>
</div>
<style type="text/css">
 
</style>
<form id="rn_QuestionSubmit" name="rn_QuestionSubmit" method="post">
<div class="rn_PageContent rn_AskQuestion rn_Container">
    
        <div id="rn_ErrorLocation"></div>
                <rn:widget path="output/DataDisplay"  name="Incident.ref_no" default_value="3" required="true"  />
                <rn:widget path="output/DataDisplay"  name="Incident.c$complaint_type" default_value="3" required="true" readonly="true" />
				<rn:widget path="custom/supplier_feedback/TypeAheadSupplierOutput" name="CFS.Supplier.Name"   label_input="Supplier name" required="true"/>
  
                <rn:widget path="output/ProductCategoryDisplay"  name="Incident.Category" default_value="" required="true" />
                <rn:widget path="output/FieldDisplay" name="Incident.c$supplier_order_number" required="true">
                <rn:widget path="output/FieldDisplay"  name="Incident.c$product_returned" default_value=""  required="true" />
                <rn:widget path="output/FieldDisplay"  name="Incident.c$product_sample_taken" default_value=""  required="true"/>
                <rn:widget path="output/FieldDisplay"  name="Incident.c$proposed_solution" default_value=""  required="true"/>
                <rn:widget path="output/FieldDisplay"  name="Incident.c$request_type" default_value="" label_input="Request Type"/>
                <rn:widget path="output/FieldDisplay"  name="Incident.Subject" default_value="" label_input="Subject" required="true" />
                <rn:widget path="output/IncidentThreadDisplay" name="Incident.Threads" label_input="<b>Threads</b>"/>
                <rn:widget path="output/FieldDisplay"  name="Incident.c$target_date" label_input="Target Date" required="true"/>               
                <rn:widget path="output/FieldDisplay"  name="Incident.c$sold_to_customer_name" default_value="" label_input="Sold to Customer Name" required="true" />
                <rn:widget path="output/FileListDisplay"  name="Incident.fattach" />
				<br>
				<a href='/app/supplier_feedback/detail/<?php echo getUrlParm('i_id'); ?>' class='btn primary_button' >Edit</a>

</div>
</form>
<script>
document.forms['rn_QuestionSubmit']['inputName'].readOnly = true;
</script>

