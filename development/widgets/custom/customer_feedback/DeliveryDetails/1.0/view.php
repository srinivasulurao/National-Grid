<div id="rn_<?= $this->instanceID ?>" class="<?= $this->classList ?>">
<label>Delivery Details</label>
<table class='actiontable' style='width:99%;margin:auto;margin-top:20px;font-size:12px;margin-left:0px'>

				<?php
				$delSize=sizeof($this->data['delivery_order_details']);
				$counter=0;
				for($i=0;$i<$delSize;$i++):
					$del=$this->data['delivery_order_details'][$i];
					echo "<tr style='background:black !important;color:white;font-size:12px;'><th style='padding-left:20px'>Delivery - {$del->Delivery}</th></tr>";
					echo"<tr style='background:white !important;color:black;'><td style='border-bottom:0px;padding-bottom:0px'><span class='delivery_col_header'>Material Description</span><span class='delivery_col_value'>{$del->MaterialDescription}</span><br>";
					echo"<span class='delivery_col_header'>Material Group</span><span class='delivery_col_value'>{$del->MaterialGroup}</span><br>";
					echo"<span class='delivery_col_header'>Material</span><span class='delivery_col_value'>{$del->Material}</span><br>";
					echo"<span class='delivery_col_header'>Batch</span><span class='delivery_col_value'>{$del->Batch}</span><br>";
					echo"<span class='delivery_col_header'>Plant Name</span><span class='delivery_col_value'>{$del->PlantName}</span><br>";
					echo"<span class='delivery_col_header'>Plant</span><span class='delivery_col_value'>{$del->Plant}</span><br>";
					echo"<span class='delivery_col_header'>Mode Of Transport Description</span><span class='delivery_col_value'>{$del->ModeOfTransportDescription}</span><br>";
					echo"<span class='delivery_col_header'>Means Of Transport ID</span><span class='delivery_col_value'>{$del->MeansOfTransportID}</span><br>";
					echo"<span class='delivery_col_header'>Route</span><span class='delivery_col_value'>{$del->Route}</span><br>";
					echo"<span class='delivery_col_header'>Net Weight</span><span class='delivery_col_value'>{$del->NetWeight}</span><br>";
					echo"<span class='delivery_col_header'>Weight UOM</span><span class='delivery_col_value'>{$del->WeightUOM}</span><br>";
					echo"<span class='delivery_col_header'>Sold To Customer Name</span><span class='delivery_col_value'>{$del->SoldToCustomerName}</span><br>";
					echo"<span class='delivery_col_header'>Ship To Customer Name</span><span class='delivery_col_value'>{$del->ShipToCustomerName}</span><br>";
					echo"<span class='delivery_col_header'>Ship To City</span><span class='delivery_col_value'>{$del->ShipToCity}</span><br>";
					echo"<span class='delivery_col_header'>Destination Region</span><span class='delivery_col_value'>{$del->DestinationRegion}</span><br>";
					echo"<span class='delivery_col_header'>Ship To Postal Code</span><span class='delivery_col_value'>{$del->ShipToPostalCode}</span><br>";
					echo"<span class='delivery_col_header'>Destination Country</span><span class='delivery_col_value'>{$del->DestinationCountry}</span><br>";
					echo"<span class='delivery_col_header'>Delivery</span><span class='delivery_col_value'>{$del->Delivery}</span><br>";
					echo"<span class='delivery_col_header'>Delivery Line Item</span><span class='delivery_col_value'>{$del->DeliveryLineItem}</span><br>";
					echo"<span class='delivery_col_header'>Reference Doc</span><span class='delivery_col_value'>{$del->ReferenceDoc}</span><br>";
					echo"<span class='delivery_col_header'>Invoice ValueIn Inv Currency</span><span class='delivery_col_value'>{$del->InvoiceValueInInvCurrency}</span><br>";
					echo"<span class='delivery_col_header'>Invoice Currency</span><span class='delivery_col_value'>{$del->InvoiceCurrency}</span><br>";
					echo"<span class='delivery_col_header'>Delivery Goods Issue Date</span><span class='delivery_col_value'>{$del->DeliveryGoodsIssueDate}</span><br>";
					echo"<span class='delivery_col_header'>Sales Office</span><span class='delivery_col_value'>{$del->SalesOffice}</span><br>";
					echo"<span class='delivery_col_header'>Sales District Description</span><span class='delivery_col_value'>{$del->SalesDistrictDescription}</span>";
					echo "</td></tr>";
								$counter++;
                endfor;
				if(!$counter)
				echo "<tr><td colspan='7' style='color:red'>Sorry, no data found!</td></tr>";
				?>
				</table>
</table>


</div>
