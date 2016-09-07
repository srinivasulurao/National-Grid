<?php /* Originating Release: February 2016 */?>
<style>
.actiontable {font-size:14px;color:#000;width:100%;border-width: 0px;border-color: #002586;border-collapse: collapse;}
.actiontable th {font-size:14px;color:#FFF;background-color:#40526b;border-width: 0px;padding: 8px;border-color: #729ea5;text-align:left;}
.actiontable tr {background-color:#ffffff;}
.actiontable td {font-size:13px;border-width: 1px;padding: 8px;border-color:lightgrey;}
.rn_Paginator a,.rn_PageLinks a{
border-radius:3px;
background:lightgrey;
color:black;
padding:5px 10px;
}
.rn_CurrentPage{
border-radius:3px;
background:#40526B;
color:white;
padding:5px 10px;
}
</style>

<div id="rn_<?=$this->instanceID;?>" class="<?= $this->classList ?>">
    <rn:block id="top"/>
    <div id="rn_<?=$this->instanceID;?>_Alert" role="alert" class="rn_ScreenReaderOnly"></div>
    <rn:block id="preLoadingIndicator"/>
    <div id="rn_<?=$this->instanceID;?>_Loading"></div>
    <rn:block id="postLoadingIndicator"/>
    <div id="rn_<?=$this->instanceID;?>_Content" class="rn_Content">
        <rn:block id="topContent"/>
        <? if (is_array($this->data['reportData']['data']) && count($this->data['reportData']['data']) > 0): ?>
        <rn:block id="preResultList"/>
        <? if ($this->data['reportData']['row_num']): ?>
            <ol start="<?=$this->data['reportData']['start_num'];?>">
        <? else: ?>
            <table class='actiontable'>
        <? endif; ?>
        <rn:block id="topResultList"/>
        <?php  $reportColumns = count($this->data['reportData']['headers']);
		?>
		<tr>
		 <?php 
		 for($j = 0; $j < $reportColumns; $j++)
		 {  
		?>
		<th>
		<?php echo str_replace(":","",$this->getHeader($this->data['reportData']['headers'][$j])); ?>
		</th>
	 <?php } ?>
		</tr>
		<?php
           foreach ($this->data['reportData']['data'] as $value): ?>
            <rn:block id="resultListItem">
            <tr>
                <? for ($i = 0; $i < $reportColumns; $i++): ?>
                    <? $header = $this->data['reportData']['headers'][$i];
					?>
                    <? if ($this->showColumn($value[$i], $header)):
                        if ($i < 3): ?>
                            <td class="rn_Element<?=$i + 1?>"><?=$value[$i];?></td>
                        <? else: ?>
                            <span class="rn_ElementsHeader"><?php //echo $this->getHeader($header);?></span>
                            <span class="rn_ElementsData"><?php //echo $value[$i];?></span>
							 <td class="rn_Element<?=$i + 1?>"><?=$value[$i];?></td>
                        <? endif; ?>
                    <?
					else:?>
<td class="rn_Element<?=$i + 1?>"> </td>
					<? endif; ?>
                <? endfor; ?>
            </tr>
            </rn:block>
        <? endforeach; ?>
        <rn:block id="bottomResultList"/>
        <? if ($this->data['reportData']['row_num']): ?>
            </ol>
        <? else: ?>
            </table>
        <? endif; ?>
        <rn:block id="postResultList"/>
        <? else: ?>
        <rn:block id="noResultListItem"/>
        <? endif; ?>
        <rn:block id="bottomContent"/>
    </div>
    <rn:block id="bottom"/>
</div>
