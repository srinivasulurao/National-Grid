<rn:meta title="Investigation of Complaints" template="standard.php" clickstream="list" login_required="true" //>

<div class="rn_Hero">
    <div class="rn_HeroInner">
        <div class="rn_HeroCopy">
		<h1>Complaint Investigations</h1>
           <!-- <h1>#rn:msg:SUBMIT_QUESTION_OUR_SUPPORT_TEAM_CMD#</h1>
            <p>#rn:msg:OUR_DEDICATED_RESPOND_WITHIN_48_HOURS_MSG#</p>!-->
        </div>
        
        <br><br>      
    </div>
</div>

<div class="rn_PageContent rn_AskQuestion rn_Container investigations">
	<a href='/app/customer_feedback/investigations/list' style='float:right' class='link_button'>Back</a>
	<h1>Complaint Details</h1>
         <div class='collapsible-div investigation-playstation-1'><rn:widget path="custom/investigations/ComplaintDetails" /></div>
    <h1>Corrective Actions <span id='total_corrective_actions'></span></h1>     
    <div class='collapsible-div investigation-playstation-2'><rn:widget path="custom/investigations/CorrectiveActions" /></div>
    
    <h1>Thread</h1>     
    <div class='collapsible-div investigation-playstation-2'><rn:widget path="custom/investigations/UpdateInvestigation" /></div>
</div>

<style>
	.investigations h1{
		    background: #40526B;
    color: white;
    padding: 10px;
    margin: 20px 0 20px 0;
    display: block !important;
    width: 100%;
    font-size:20px;
    border-radius: 4px;
    font-weight: normal;
    box-shadow: 0px 0 10px 0px black;
    width:100%;
	}
</style>