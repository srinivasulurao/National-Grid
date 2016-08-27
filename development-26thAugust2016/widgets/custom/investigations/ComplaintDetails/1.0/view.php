<div id="rn_<?= $this->instanceID ?>" class="<?= $this->classList ?> inv-div" >
		<div style='border-bottom:1px solid lightgrey'>
		<div class='tabs tab-active' id='tab1' onclick="showTabContent(1)">Complaint</div>
		<div class='tabs' id='tab2' onclick="showTabContent(2)">Customer</div>
		<div class='tabs' id='tab3' onclick="showTabContent(3)">Delivery</div>
		</div>

		<div class='tab_playground'>
			<div id='investigation_content'  >
				<table class='actiontable' style='width:70%;margin:auto;margin-top:20px;border:1px solid lightgrey'>
				<?php foreach ($this->data['incident_details'] as $key => $value) {
					if($value)
					echo "<tr><td style='width:50%;padding-left:10px'>$key</td><td>$value</td></tr>";
				}	
				?>	
				</table>
				<div id='thread_display' style='font-size:12px'>
	<rn:widget path="output/IncidentThreadDisplay" label='Communication History' name="Incident.Threads" />
	</div>
    
			</div>
			
			
			<div id='customer_content' style='display:none' >
				<table class='actiontable' style='width:70%;margin:auto;margin-top:20px;border:1px solid lightgrey'>
				<?php foreach ($this->data['customer_details'] as $key => $value) {
					if($value)
					echo "<tr><td style='width:50%;padding-left:10px'>$key</td><td>$value</td></tr>";
				}	
				?>	
				</table>
			</div>
			
			<div id='delivery_content' style='display:none'  >
				<table class='actiontable' style='width:100%;margin:auto;margin-top:20px;border:1px solid lightgrey'>
				<tr><th>Delivery</th><th>Product Line <br>(Having Issue)</th><th>Delivery Date</th><th>Sold To Customer Name</th><th>Ship To Customer Name</th><th>Shipping Address</th><th>Billing Address</th></tr>	
				<?php 
				$delSize=sizeof($this->data['delivery_details']);
				$counter=0;
				for($i=0;$i<$delSize;$i++):
					$del=$this->data['delivery_details'][$i];					
					$ship_addr=@implode(", ",array($del->ShipToCity,$del->ShipToStreet,$del->DestinationRegion,$del->DestinationCountry,$del->ShipToPostalCode));
					$ship_addr=trim($ship_addr,", ");
					$bill_addr=$del->SoldToCustomerRegion;
					echo "<tr><td>{$del->Delivery}</td><td>{$del->DeliveryLineItem}</td><td>{$del->DeliveryGoodsIssueDate}</td><td>{$del->SoldToCustomerName}</td><td>{$del->ShipToCustomerName}</td><td>{$ship_addr}</td><td>{$bill_addr}</td></tr>";
                $counter++;
                endfor;		
				if(!$counter)
				echo "<tr><td colspan='7' style='color:red'>Sorry,No Data found!</td></tr>";
				?>	
				</table>
			</div>
			
		</div>
</div>

