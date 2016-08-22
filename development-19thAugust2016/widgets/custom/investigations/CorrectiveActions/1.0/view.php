<div id="rn_<?= $this->instanceID ?>" class="<?= $this->classList ?> inv-div">
	<a href='javascript:void(0)' class='btn success_button' id='complete_corrective_actions' style='float:right;margin:5px;margin-left:0'>Complete</a>
	<a href='javascript:void(0)' class='btn primary_button' id='incomplete_corrective_actions' style='float:right;margin:5px;margin-left:0'>Not Complete</a>
	<a href='javascript:void(0)' class='btn danger_button'  id='delete_corrective_actions' style='float:right;margin:5px;margin-left:0'>Delete</a>
<div id='correctiveActionList'>
    <table style='width:100%' class='actiontable'>
	<tr><th><input type='checkbox' id='centralCheck' onclick="centralCheck()"></th><th>Description</th><th>Created</th><th>Due Date</th><th>Completed</th></tr>
	<?php
	$counter=0;
	foreach($this->data['corrective_actions'] as $ca):
		$checkbox="<input type='checkbox' id='{$ca['ID']}' class='corrective_action_checkbox'>";
		$completed=($ca['Complete'])?"<span class='tick'>&#x2714;</span>":"<span class='untick'>&#x2718;</span>";
		$created=date("m/d/y h:i A",strtotime($ca['CreatedTime']));
		$duedate=date("m/d/y h:i A",strtotime($ca['DueDate']));
		echo "<tr><td>$checkbox</td><td>{$ca['Description']}</td><td>{$created}</td><td>{$duedate}</td><td>$completed</td></tr>";
		$counter++;
	endforeach;	
	if(!$counter)
	echo "<tr><td colspan='5' style='color:red'>No Corrective Actions Added to this investigation!</td></tr>";
	?>
</table>
</div>

<button type='button' id='add_tca' class='btn info_button'> + Add</button> 
<form method='action' id='add_corrective_action' style='display:none' onsubmit="return false;">
	<label>Details</label>
	<input type='text' id='corrective_actions_details' required="required">
	<label>Description</label>
	<input type='text' id='corrective_actions_description' required="required">
	<label>Complete</label>
	<select id='corrective_actions_complete'>
		<option value='0'>No</option>
		<option value='1'>Yes</option>
	</select>
    <label>Completion Date</label>
    <input type='text' id='corrective_actions_completion_date' style="display: inline-block;width: 97%;margin-right: 7px;" placeholder="mm-dd-yyy"><img style="height:20px;width:20px;top:5px;position: relative;cursor:pointer" id="toggleCalendar1" src="/euf/assets/images/icons/calendar_icon1.png" srckk="http://eguidemagazine.com/wp-content/uploads/2016/06/calendar-icon-blue_sm.png">
    <div id="cacd" style='z-index:501;width:250px !important;display: none;position:absolute;'></div>
    <label>Due Date</label>
    <input type='text' id='corrective_actions_due_date' style="display: inline-block;width: 97%;margin-right: 7px;" placeholder="mm-dd-yyy"><img style="height:20px;width:20px;top:5px;position: relative;cursor:pointer" id="toggleCalendar2" src="/euf/assets/images/icons/calendar_icon1.png" srckk="http://eguidemagazine.com/wp-content/uploads/2016/06/calendar-icon-blue_sm.png">		
    <div id="cadd" style='z-index:501;width:250px !important;display: none;position:absolute;'></div>
	</select>
	<button id='submitCorrectiveAction' type="submit">Submit</button>
	<input type='hidden' value='<?php echo getUrlParm('i_id'); ?>' id='corrective_actions_iid' />
</form>
</div>
