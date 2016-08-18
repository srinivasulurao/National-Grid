<rn:meta title="Action Items List" template="standard.php" clickstream="actionitem_create" login_required="true"/>

<div class="rn_Hero">
    <div class="rn_HeroInner">
        <div class="rn_HeroCopy">
		<h1>Action Items List</h1>
           <!-- <h1>#rn:msg:SUBMIT_QUESTION_OUR_SUPPORT_TEAM_CMD#</h1>
            <p>#rn:msg:OUR_DEDICATED_RESPOND_WITHIN_48_HOURS_MSG#</p>!-->
        </div>
        <div class="translucent">
            
        </div>
        <br>

    </div>
</div>

<div class="rn_PageContent rn_AskQuestion rn_Container cfl">
<rn:widget path='custom/customer_feedback/CustomListFilter' action="/app/action_items/list" page_entity="action_items" />
<b><a href="/app/action_items/new" class='link_button' style='float:right;bottom: 7px;position: relative;'>New Action Items</a></b>

<!--<rn:widget path='custom/customer_feedback/ComplaintReportFilter' action="/app/action_items/list" label_name="Choose Items" />--></rn:widget>
			<div style="float:right;">
			 </div>
			<rn:widget path="custom/action_items/CBOMultilinelist" report_id="100041" per_page="10"/>
			<div style="float:right;">
			<rn:widget path="custom/action_items/CBOPaginator" report_id="100041" per_page="10"/>
            <!--<rn:widget path="reports/BasicPaginator" report_id="100041" per_page="5"/>-->
			 </div>
</div>
