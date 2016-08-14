<div id="rn_<?= $this->instanceID ?>" class="<?= $this->classList ?>">
<form method='post' action='<?php echo $this->attrs['action']->value; ?>' name='complaint_filer_form'>
<label><?php echo $this->attrs['label_name']->value; ?></label>
<select name='complaint_filter' id='complaint_filter'>
<option value='c_id' <?php echo ($this->data['complaint_filter']=="c_id")?"selected='selected'":""; ?> >Created By Me</option>
<option value='org_id' <?php echo ($this->data['complaint_filter']=="org_id")?"selected='selected'":""; ?> >By Organization</option>
</select>
</form>
</div>