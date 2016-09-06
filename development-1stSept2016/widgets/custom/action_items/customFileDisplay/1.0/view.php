<label>File Attachments</label>
<ul class='fileAttachment'>
<?php
$c=0;
foreach($this->data['attachments'] as $attachment):
	//echo "<pre>";
	//print_r($attachment);
	//echo "</pre>";
	$fileName=$attachment->FileName;
	$fileUrl="/ci/fattach/get/{$attachment->ID}/{$attachment->CreatedTime}/filename/".urlencode($attachment->FileName);
	$size=number_format(($attachment->Size/1024),2);
	$file="<span style='top:3px;position:relative'>&#186;</span> <a href='$fileUrl' target='_blank'>$fileName ($size KB)</a>";
	echo "<li>{$file}</li>";
	$c++;
endforeach;	
?>
</ul>
<br>