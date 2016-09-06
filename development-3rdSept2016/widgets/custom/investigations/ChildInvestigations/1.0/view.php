<div id="rn_<?= $this->instanceID ?>" class="<?= $this->classList ?> rn_Output">
<label class='rn_DataLabel'>Investigations</label>
<?php
$counter=0;
$html="";
if(sizeof($this->data['child_investigations'])):
foreach($this->data['child_investigations'] as $ci):
  $html.="<li style='border-bottom:1px dashed lightgrey;background: white;padding: 10px;width: 99%;'>&#x2611; <a href='/app/investigations/update/i_id/{$ci['ID']}'  target='_blank'>{$ci['Subject']}</a><li>";
  $counter++;
endforeach;
endif;
echo ($counter)?"<ul class='child_investigations'>".$html."</ul>":"<font color='red'>Sorry, No Investigation has been started for this complaint !</font>";
?>
</div>
