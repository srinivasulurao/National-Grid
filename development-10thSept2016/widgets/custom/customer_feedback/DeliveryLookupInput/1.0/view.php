<div id="rn_<?= $this->instanceID ?>" class="<?= $this->classList ?>">
<?php
$widget_id="rn_TextInput_".$this->instanceID."_".$this->attrs['name']->value;
$widget_id=str_replace("DeliveryLookupInput_","",$widget_id);
?>
<label for="rn_<?=$this->instanceID;?>_<?php echo $this->attrs['name']->value; ?>" id="<?=$this->instanceID;?>_Label" class="rn_Label"><?php echo $this->attrs['label_input']->value; ?><?php if($this->attrs['required']->value):  echo "<span class=\"rn_Required\"> *</span>"; endif; ?></label>
<input type='text' value="<?php echo $this->data['delivery_value']; ?>" style='width:70%' autocomplete='off' <?php echo ($this->attrs['required']->value)?"required='true'":""; ?>   id='<?php echo $widget_id; ?>' name="<?php echo $this->attrs['name']->value; ?>" class='rn_Text delivery_lookup'/>
<span id='autocomplete_delivery'></span>
</div>

<div id="rn_<?= $this->instanceID ?>" class="<?= $this->classList ?>" style="float:right">
<button type='button' id='ddgb' style='height:37px !important'>Delivery Lookup</button>
<div class='deliveryDetailsGrid' id="deliveryDetailsGrid">
<div id='DeliverySearchPlay'>
<label>Delivery #</label>
<input type='text' id='delivery_no' >
<label>Customer PO Number</label>
<input type='text' id="customer_po_no">
<label>Sold to Customer Name</label>
<input type='text' id="sold_to_customer">
<label>Ship to Customer Name</label>
<input type='text' id="ship_to_customer">
</div>
<button type='button' id='search_delivery' class='button'>Search</button>
<button type='button'  id='search_delivery_reset' class='button'>Reset</button>
<button type='button' id='close_delivery_lookup_butt' class=''>Close</button>
</div>
</div>
