<div id="rn_<?= $this->instanceID ?>" class="<?= $this->classList ?>">

 <label style="margin-bottom:0px;">Communication History</label>
 <div width="100%" class="rn_IncidentThreadDisplay rn_Output">
 <? foreach($this->data['threads'] as $thread) {
 switch($thread->EntryType->ID) {
 case 1: case 'Note': $bgcolor = "#FFFFE0";
 $entered_by = $thread->Account->LookupName;
 break;
 case 2: case 'Staff Account': $bgcolor = "#E0EAD8";
 break;
 case 3: case 'Customer': $bgcolor = "#D7E9F6";
 if(isset($thread->Contact->LookupName)) {
 $entered_by = $thread->Contact->LookupName;
 }
 break;
 case 4: case 'Customer Proxy': $bgcolor = "#D7E9F6";
 if(isset($thread->Contact->LookupName)) {
 $entered_by = $thread->Contact->LookupName;
 }
 break;
 default: $bgcolor = "#CCCCCC";
 break;
 }
 printf("<div class='rn_ThreadHeader rn_Customer rn_ThreadAuthor'>%s - %s<span style=\"float:right\">%s  %s</span></div>",$thread->EntryType->LookupName,$entered_by, $thread->Channel->LookupName, date("m/d/Y h:i:s A", $thread->CreatedTime));
 printf("<div class=\"rn_ThreadContent\">%s</div>", str_replace("\n", "<br />", $thread->Text));
 }
 ?>
 </div>
</div>
