<rn:meta title="Supplier Complaint List" template="standard.php" clickstream="list" login_required="true"//>

<div class="rn_Hero">
    <div class="rn_HeroInner">
        <div class="rn_HeroCopy">
		<h1>Supplier Complaint List</h1>
           <!-- <h1>#rn:msg:SUBMIT_QUESTION_OUR_SUPPORT_TEAM_CMD#</h1>
            <p>#rn:msg:OUR_DEDICATED_RESPOND_WITHIN_48_HOURS_MSG#</p>!-->
        </div>
        <div class="translucent">
            
        </div>
		
        <br>

    </div>
</div>
<!-- <rn:widget path="search/FilterDropdown" filter_name="Contact" report_id='100056'/>-->

<div class="rn_PageContent rn_AskQuestion rn_Container">
<b><a href="/app/supplier_feedback/new" class='link_button'>Create New</a></b>
			<div style="float:right;">
			 </div>
			<rn:widget path="custom/action_items/CBOMultilinelist" report_id="100056" per_page="5"/>
			<div style="float:right;">
            <rn:widget path="reports/BasicPaginator" report_id="100056" per_page="5"/>
			 </div>
</div>
