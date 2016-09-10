
<script>
function ShowContactName(str)
{

	 var xhttp;
  if (str.length == 0) {
    document.getElementById("display").innerHTML = "";
	var hid_cid="rn_<?=$this->instanceID;?>_<?=$this->data['attrs']['name'];?>";
	document.getElementById(hid_cid).value="";
    return;
  }
  xhttp = new XMLHttpRequest();
  xhttp.onreadystatechange = function() {
    if (xhttp.readyState == 4 && xhttp.status == 200) {
		
	var hid_cid="rn_<?=$this->instanceID;?>_<?=$this->data['attrs']['name'];?>";
	document.getElementById(hid_cid).value="";
		
		if(xhttp.responseText=='<ul></ul>')
		{
			document.getElementById("display").style.display = "block";
			document.getElementById("display").innerHTML = '<ul style="margin:0px 0px 0px 0px;"><li>No Result found</li></ul>';
			
		}else{
		
			document.getElementById("display").style.display = "block";
			document.getElementById("display").innerHTML = xhttp.responseText;
			
		}
      
    }
  };
 //xhttp.open("GET", "/cc/CustomerFeedbackSystem/contact_search/"+str, true);
  //xhttp.send();
    var parameters="str="+str;
	xhttp.open("POST", "/cc/CustomerFeedbackSystem/contact_search/", true)
	xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded")
	xhttp.send(parameters)
}
function fill(str)
{

//$('#name').val(Value);
var res = str.split("-",2);
var hid_cid="rn_<?=$this->instanceID;?>_<?=$this->data['attrs']['name'];?>";
document.getElementById(hid_cid).value = res[0];
document.getElementById("contact_name").value = res[1];

document.getElementById("display").style.display = "none";


}
</script>
<style>
ul
{
list-style: none;
margin: 0px 0px 0px 0px;
width: 330px;
}
li
{
display: block;
padding: 5px;
background-color: #ccc;
border-bottom: 1px solid #367;
}
#display
{
	Display:none;
	height:70px;
    overflow-y: scroll;
    width: 32%;
	overflow-x: none;
	
}
</style>

<? if ($this->data['readOnly']): ?>
    <rn:widget path="output/FieldDisplay" left_justify="true"/>
<? else: ?>
<?php if($this->data['attrs']['datefield']){?>


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
	<div class="ui-widget">
    <input type="text" id="contact_name" name="contact_name" class="rn_Text" <?=tabIndex($this->data['attrs']['tabindex'], 1);?> <? if($this->data['attrs']['max_length']): echo('maxlength="' . $this->data['attrs']['max_length'] . '"'); endif;?> value="<?=$this->data['cname'];?>" onKeyUp="ShowContactName(this.value)" autocomplete="off"/>
	<input type="hidden" id="rn_<?=$this->instanceID;?>_<?=$this->data['attrs']['name'];?>" name="rn_<?=$this->instanceID;?>_<?=$this->data['attrs']['name'];?>" value="<?=$this->data['value'];?>"/>
	<div id="display"></div>
	</div>
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