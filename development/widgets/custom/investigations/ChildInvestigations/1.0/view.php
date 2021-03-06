<div id="rn_<?= $this->instanceID ?>" class="<?= $this->classList ?> rn_Output">
<label class='rn_DataLabel'>Investigations</label>
<?php
$counter=0;
$html="<table class='actiontable' style='width:99%;font-size:12px;background:white;'>";
$html.="<tr style='background:black !important;font-size:12px;color:white'><th>&nbsp Reference #</th><th width='30%'>Subject</th><th>Investigator</th><th width='7%'>Problem?</th><th>Root Cause</th><th>Root Cause Category</th><th>Status</th></tr>";
if(sizeof($this->data['child_investigations'])){
foreach($this->data['child_investigations'] as $ci):
  $was_there_a_problem=($ci->CustomFields->c->was_there_a_problem == 1)?"Yes":((is_null($ci->CustomFields->c->was_there_a_problem))?"":"No");
  #$investigator=($ci->AssignedTo->Account->LookupName)?$ci->AssignedTo->Account->LookupName:"None";
  $investigator=($ci->PrimaryContact->LookupName)?$ci->PrimaryContact->LookupName:"None";
  $root_cause_category=($ci->CustomFields->c->root_cause_category->LookupName)?$ci->CustomFields->c->root_cause_category->LookupName:"None";
  $status=$ci->StatusWithType->Status->LookupName;
  $html.="<tr style='color:#333'><td >&nbsp<a href='/app/investigations/view/i_id/{$ci->ID}' style='background:none' target='_blank'>{$ci->LookupName}</a></td><td>{$ci->Subject}</td><td>$investigator</td><td>$was_there_a_problem</td><td>{$ci->CustomFields->c->root_cause}</td><td>$root_cause_category</td><td>$status</td></tr>";
  $counter++;
endforeach;
}
else
  $html.="<tr><td colspan='7' style='color:red;padding-left:10px'>Sorry, no investigation has been started for this complaint.</td></tr>";
  $html.="</table>";
?>
<?php echo $html; ?>
</div>
