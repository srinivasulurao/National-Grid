<div id="rn_<?= $this->instanceID ?>" class="<?= $this->classList ?>">
<label>Delivery Details</label>
<table class='actiontable' style='width:99%;margin:auto;margin-top:20px;font-size:12px;margin-left:0px'>
				<tr style='background:black !important;color:white;font-size:12px;'><th>&nbsp Delivery</th><th>Product Line <br>(Having Issue)</th><th>Delivery Date</th><th>Sold To Customer Name</th><th>Ship To Customer Name</th><th>Shipping Address</th><th>Billing Address</th></tr>
				<?php
				$delSize=sizeof($this->data['delivery_order_details']);
				$counter=0;
				for($i=0;$i<$delSize;$i++):
					$del=$this->data['delivery_order_details'][$i];
					$ship_addr=@implode(", ",array($del->ShipToCity,$del->ShipToStreet,$del->DestinationRegion,$del->DestinationCountry,$del->ShipToPostalCode));
					$ship_addr=trim($ship_addr,", ");
					$bill_addr=$del->SoldToCustomerRegion;
					echo "<tr><td>&nbsp{$del->Delivery}</td><td>{$del->DeliveryLineItem}</td><td>{$del->DeliveryGoodsIssueDate}</td><td>{$del->SoldToCustomerName}</td><td>{$del->ShipToCustomerName}</td><td>{$ship_addr}</td><td>{$bill_addr}</td></tr>";
                $counter++;
                endfor;
				if(!$counter)
				echo "<tr><td colspan='7' style='color:red'>Sorry,No Data found!</td></tr>";
				?>
				</table>
</table>


</div>
