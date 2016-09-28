<div id="rn_<?= $this->instanceID ?>" class="<?= $this->classList ?>">

<form method='post' action='<?php echo $this->attrs['action']->value; ?>' name='complaint_filter_form'>
<label><?php echo $this->attrs['label_name']->value; ?></label>
<select name='complaint_filter_individual' id='complaint_filter_individual'>
<!--<option value='all'  >All</option> -->
<option value='c_id' <?php echo ($this->data['complaint_filter_individual']=="c_id")?"selected='selected'":""; ?> ><?php echo($this->attrs['entity_type']->value=="action_item")?"Assigned To Me":"Created By Me"; ?></option>
<option value='org_id' <?php echo ($this->data['complaint_filter_individual']=="org_id" )?"selected='selected'":""; ?> >By Organization</option>
</select>
</form>
</div>
