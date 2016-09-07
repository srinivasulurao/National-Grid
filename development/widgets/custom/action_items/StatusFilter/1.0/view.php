<div id="rn_<?= $this->instanceID ?>" class="<?= $this->classList ?>">
  <form method='post' action='<?php echo $this->attrs['action']->value; ?>' name='status_filter_form'>
  <label><?php echo $this->attrs['label_name']->value; ?></label>
  <select name='complaint_status_filter' id='complaint_status_filter'>
  <option value=''  >--SELECT--</option>
  <?php
foreach($this->data['status_list'] as $key=>$value):
  $selected=($this->data['status_filter_selected']==$key)?"selected='selected'":"";
  echo "<option value='$key' $selected>$value</option>";
endforeach;
   ?>
  </select>
  </form>
</div>
