<rn:meta title="Update Investigation" template="standard.php" clickstream="list" login_required="true" //>

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
	<h1>Complaint Details <span id='plus_minus1' onclick='playToggle(1)'>+</span></h1>
    <div class='collapsible-div investigation-playstation-1' style='display:none'><rn:widget path="custom/investigations/ComplaintDetails" /></div>
        
    <h1>Comments <span id='plus_minus2' onclick='playToggle(2)'>+</span></h1>     
    <div class='collapsible-div investigation-playstation-2' style='display:none'><rn:widget path="custom/investigations/UpdateInvestigation" /></div>
    
    <h1>Corrective Actions <span id='total_corrective_actions'></span> <span id='plus_minus3' onclick='playToggle(3)'>+</span></h1>     
    <div class='collapsible-div investigation-playstation-3' style='display:none'><rn:widget path="custom/investigations/CorrectiveActions" /></div>
  
    <h1>Investigation Closure <span id='plus_minus4' onclick='playToggle(4)'>+</span></h1>     
    <div class='collapsible-div investigation-playstation-4' style='display:none'><rn:widget path="custom/investigations/InvestigationClosure" /></div>
    
</div>
