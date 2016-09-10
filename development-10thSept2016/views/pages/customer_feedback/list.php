<rn:meta title="Customer Complaints List" template="standard.php" clickstream="list" login_required="true"//>

<div class="rn_Hero">
    <div class="rn_HeroInner">
        <div class="rn_HeroCopy">
		<h1>Customer Feedback List</h1>
           <!-- <h1>#rn:msg:SUBMIT_QUESTION_OUR_SUPPORT_TEAM_CMD#</h1>
            <p>#rn:msg:OUR_DEDICATED_RESPOND_WITHIN_48_HOURS_MSG#</p>!-->
        </div>
        <div class="translucent">
            
        </div>
        <br>

    </div>
</div>
 <rn:container report_id="100089">
<div class="rn_PageContent rn_AskQuestion rn_Container cfl" style='min-height:600px'>
                  <rn:widget path='custom/customer_feedback/CustomListFilter' page_entity="customer_feedback" page_action='/app/customer_feedback/list/' />
    <a href='/app/customer_feedback/new' class='link_button' style='float:right'>Create New</a>
    <rn:widget path='custom/customer_feedback/ComplaintReportFilter' action="/app/customer_feedback/list" label_name="Choose Complaints" />
<rn:widget path="custom/action_items/CBOMultilinelist"  per_page="10"/>
    <div style="float:right;clear:both">
	<!--<rn:widget path="reports/BasicPaginator" report_id="100066" per_page="5"/> -->
	<rn:widget path="custom/action_items/CBOPaginator"  per_page="10"/>
	
	</div>

</div>
  </rn:container>

