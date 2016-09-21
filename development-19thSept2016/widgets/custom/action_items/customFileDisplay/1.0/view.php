<?php
$c=0;
if(sizeof($this->data['attachments'])):
echo "<label>File Attachments</label><ul class='fileAttachment'>";
foreach($this->data['attachments'] as $attachment):
	$fileName=$attachment->FileName;
  if($this->attrs['type']->value=="action_items"){
	//$fileUrl=$attachment->getAdminURL();
	$fileUrl="/ci/fattach/get/{$attachment->ID}/{$attachment->CreatedTime}/filename/".urlencode($attachment->FileName);
	}
	else{
	$fileUrl="/ci/fattach/get/{$attachment->ID}/{$attachment->CreatedTime}/filename/".urlencode($attachment->FileName);
  }
	$size=number_format(($attachment->Size/1024),2);
	$file="<span style='top:3px;position:relative;display:none'>&#186;</span> <a href='$fileUrl' target='_blank'>$fileName ($size KB)</a>";
	echo "<li>{$file}</li>";
	$c++;
endforeach;
endif;
?>
</ul>
<br>
