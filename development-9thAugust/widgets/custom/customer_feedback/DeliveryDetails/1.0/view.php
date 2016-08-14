<div id="rn_<?= $this->instanceID ?>" class="<?= $this->classList ?>">
<label>Delivery Details</label>    
<table style='width:99%'>   
<tr style='background:lightgrey;'><th> &nbsp Product#</th><th>Sold to Customer Name</th><th>Ship to Customer Name</th></tr>
<?php
if($this->data['delivery_order_details']['ProductNo'] || $this->data['delivery_order_details']['SoldToCustomerName'] || $this->data['delivery_order_details']['ShipToCustomerName'])
echo "<tr><td>&nbsp {$this->data['delivery_order_details']['ProductNo']}</td><td>{$this->data['delivery_order_details']['SoldToCustomerName']}</td><td>{$this->data['delivery_order_details']['ShipToCustomerName']}</td></tr>";
else
echo "<tr><td colspan='3' align='center' style='color:red !important'>No Details Entered !</td></tr>";
?>
</table>
   

</div>
