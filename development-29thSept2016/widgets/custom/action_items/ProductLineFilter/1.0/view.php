<div id="rn_<?= $this->instanceID ?>" class="<?= $this->classList ?>">
  <form method='post' action='<?php echo $this->attrs['action']->value; ?>' name='productline_filter_form'>
  <label><?php echo $this->attrs['label_name']->value; ?></label>
  <select name='complaint_productline_filter' id='complaint_productline_filter'>
  <option value=''>--SELECT--</option>
  <?php

  $mapping=$this->data['OrgToProductMapping'];
  foreach($this->data['product_list'] as $key):
  $selected=($this->data['productline_filter_selected']==$key['ID'])?"selected='selected'":"";
  if(in_array($key['ID'],$mapping))
  echo "<option value='{$key['ID']}' $selected >{$key['LookupName']}</option>";
  endforeach;
   ?>
  </select>
  </form>
</div>
