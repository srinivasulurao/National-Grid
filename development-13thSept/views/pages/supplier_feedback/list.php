<rn:meta title="Supplier Complaint List" template="standard.php" clickstream="list" login_required="true"/>

<div class="rn_Hero">
    <div class="rn_HeroInner">
        <div class="rn_HeroCopy">
		<h1>Supplier Feedback List</h1>
           <!-- <h1>#rn:msg:SUBMIT_QUESTION_OUR_SUPPORT_TEAM_CMD#</h1>
            <p>#rn:msg:OUR_DEDICATED_RESPOND_WITHIN_48_HOURS_MSG#</p>!-->
        </div>
        <div class="translucent">
            
        </div>
		
        <br>

    </div>
</div>
 <rn:container report_id="100088">
 
  
<!-- <rn:widget path="search/FilterDropdown" filter_name="Contact" report_id='100088'/>-->

<div class="rn_PageContent rn_AskQuestion rn_Container supp_fl">
  <!--<form onsubmit="return false;">
                    <div class="rn_SearchInput">
                        <rn:widget path="search/KeywordText" label_text="#rn:msg:FIND_THE_ANSWER_TO_YOUR_QUESTION_CMD#" initial_focus="true"/>
                    </div>
                    <rn:widget path="search/SearchButton" force_page_flip="true"/>
             </form>-->
<rn:widget path='custom/customer_feedback/CustomListFilter' page_entity="supplier_feedback" page_action='/app/supplier_feedback/list/' />             
<a href="/app/supplier_feedback/new" class='link_button' style='float:right'>Create New</a>
		<rn:widget path='custom/customer_feedback/ComplaintReportFilter' action="/app/supplier_feedback/list" label_name="Choose Complaints"/>
			<div style="float:right;">
			 </div>
			<rn:widget path="custom/action_items/CBOMultilinelist"  per_page="10"/>
			<div style="float:right;">
            <!--<rn:widget path="reports/BasicPaginator" report_id="100056" per_page="1"/>-->
			<rn:widget path="custom/action_items/CBOPaginator" report_id="100088" per_page="10"/>
			
			 </div>
</div>

  </rn:container>
