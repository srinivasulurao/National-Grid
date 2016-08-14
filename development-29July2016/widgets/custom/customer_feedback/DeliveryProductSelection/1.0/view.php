<div id="rn_<?= $this->instanceID ?>" class="<?= $this->classList ?>">
<div class='delivery_line_item_list' id='delivery_line_item_list' style='display:none'></div>
<rn:widget path="input/TextInput" name='Incident.c$delivery_line_items' default_value='' required='false'>
<input type='hidden' id="form_incident_id" value="<?php echo getUrlParm('i_id');?>">
</div>