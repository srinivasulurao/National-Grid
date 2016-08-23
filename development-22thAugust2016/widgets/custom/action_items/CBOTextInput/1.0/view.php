<?php /* Originating Release: May 2013 */?>
<? if ($this->data['readOnly']): ?>
    <rn:widget path="output/FieldDisplay" left_justify="true"/>
<? else: ?>
<?php if($this->data['attrs']['datefield']){?>
 <script>
jQuery.noConflict();
jQuery(function() {
jQuery( ".datefield" ).datepicker();
});
</script>

<?php }?>
<div id="rn_<?= $this->instanceID ?>" class="<?= $this->classList ?> rn_TextInput">
<rn:block id="top"/>
<? if ($this->data['attrs']['label_input']): ?>
    <label for="rn_<?=$this->instanceID;?>_<?=$this->data['js']['name'];?>" id="rn_<?=$this->instanceID;?>_Label" class="rn_Label">
        <div class="label_container">
            
            
            <div class='label_input'><?=$this->data['attrs']['label_input'];?>
                <? if($this->data['attrs']['required']):?>
                    <span class="rn_Required"> * </span><span class="rn_ScreenReaderOnly"><?=getMessage(REQUIRED_LBL)?></span>
                <? endif;?>
            </div>
            <? if($this->data['js']['hint']):?>
                <span class="rn_ScreenReaderOnly"> <?=$this->data['js']['hint']?></span>
            <? endif;?>
            <? if($this->data['attrs']['showna'] == "true"):?>
                <div class="nacheck"><input type="checkbox" id="rn_<?=$this->instanceID;?>_nacheck" name="N/A" value="N/A"> N/A</input></div>
            <? endif;?>
        </div>
    </label>
	<?php if($this->data['attrs']['datefield']){?>
	<input type="text" id="rn_<?=$this->instanceID;?>_<?=$this->data['attrs']['name'];?>" name="rn_<?=$this->instanceID;?>_<?=$this->data['attrs']['name'];?>" class="rn_Text datefield" <?=tabIndex($this->data['attrs']['tabindex'], 1);?> <? if($this->data['attrs']['max_length']): echo('maxlength="' . $this->data['attrs']['max_length'] . '"'); endif;?> value="<?=$this->data['value'];?>"/>
	<?php }else{?>
	<? if ($this->data['displayType'] === 'Textarea'){?>
	 <textarea id="rn_<?= $this->instanceID ?>_<?=$this->data['attrs']['name'];?>" class="rn_TextArea" rows="7" cols="60" name="rn_<?=$this->instanceID;?>_<?=$this->data['attrs']['name'];?>" class="rn_Text" <?=tabIndex($this->data['attrs']['tabindex'], 1);?> ><?= $this->data['value'] ?></textarea>

	<?php }else{ ?>
    <input type="text" id="rn_<?=$this->instanceID;?>_<?=$this->data['attrs']['name'];?>" name="rn_<?=$this->instanceID;?>_<?=$this->data['attrs']['name'];?>" class="rn_Text" <?=tabIndex($this->data['attrs']['tabindex'], 1);?> <? if($this->data['attrs']['max_length']): echo('maxlength="' . $this->data['attrs']['max_length'] . '"'); endif;?> value="<?=$this->data['value'];?>"/>
	<?php } }?>	
<? endif; ?>
<? if ($this->data['displayType'] === 'Textarea'): ?>
<rn:block id="preInput"/>
    <!--<textarea id="rn_<?= $this->instanceID ?>_<?= $this->data['js']['name'] ?>" class="rn_TextArea" rows="7" cols="60" name="<?= $this->data['inputName'] ?>" <?= $this->outputConstraints(); ?> ><?= $this->data['value'] ?></textarea>-->
<rn:block id="postInput"/>
<? if ($this->data['attrs']['hint'] && $this->data['attrs']['always_show_hint']): ?>
    <rn:block id="preHint"/>
    <span class="rn_HintText"><?= $this->data['attrs']['hint'] ?></span>
    <rn:block id="postHint"/>
<? endif; ?>
<? else: ?>
<rn:block id="preInput"/>

<rn:block id="postInput"/>
<? if ($this->data['attrs']['hint'] && $this->data['attrs']['always_show_hint']): ?>
    <rn:block id="preHint"/>
    <span class="rn_HintText"><?= $this->data['attrs']['hint'] ?></span>
    <rn:block id="postHint"/>
<? endif; ?>
    <? if ($this->data['attrs']['require_validation']): ?>
    <div class="rn_TextInputValidate">
        <rn:block id="preValidateLabel"/>
        <label for="rn_<?= $this->instanceID ?>_<?= $this->data['js']['name'] ?>_Validate" id="rn_<?= $this->instanceID ?>_<?= $this->data['js']['name'] ?>_LabelValidate" class="rn_Label"><?printf($this->data['attrs']['label_validation'], $this->data['attrs']['label_input']) ?>
        <rn:block id="postValidateLabel"/>
        <? if ($this->data['attrs']['required']): ?>
            <rn:block id="preValidateRequired"/>
            <span class="rn_Required"><?= \RightNow\Utils\Config::getMessage(FIELD_REQUIRED_MARK_LBL) ?></span><span class="rn_ScreenReaderOnly"> <?= \RightNow\Utils\Config::getMessage(REQUIRED_LBL) ?></span>
            <rn:block id="postValidateRequired"/>
        <? endif; ?>
        </label>
        <rn:block id="preValidateInput"/>
        <input type="<?= $this->data['inputType'] ?>" id="rn_<?= $this->instanceID ?>_<?= $this->data['js']['name'] ?>_Validate" name="<?= $this->data['inputName'] ?>_Validation" class="rn_<?=$this->data['displayType']?> rn_Validation" <?= $this->outputConstraints(); ?> value="<?= $this->data['value'] ?>"/>
        <rn:block id="postValidateInput"/>
    </div>
   <? endif; ?>
<? endif; ?>
<rn:block id="bottom"/>
</div>
<? endif; ?>