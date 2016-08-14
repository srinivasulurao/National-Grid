<div id="rn_<?=$this->instanceID;?>" class="rn_TextInput rn_Input">
<body class="yui3-skin-sam" >
<?php
$widget_id="rn_TextInput_".$this->instanceID."_".$this->attrs['name']->value;
$widget_id=str_replace("DatePickerInput_","",$widget_id);
?>
<label for="rn_<?=$this->instanceID;?>_<?php echo $this->attrs['name']->value; ?>" id="<?=$this->instanceID;?>_Label" class="rn_Label"><?php echo $this->attrs['label_input']->value; ?><?php if($this->attrs['required']->value):  echo "<span class=\"rn_Required\"> *</span>"; endif; ?></label>
<input type='text' value='<?php echo $this->data['target_date']; ?>' readonly placeholder="Example : 2013-05-29" class='rn_Text yahoo_date_selected' <?php echo ($this->attrs['required']->value)?"required='true'":""; ?> name="<?php echo $this->attrs['name']->value; ?>" id='<?php echo $widget_id; ?>' style="width:200px;display:inline-block" >&nbsp<img style='height:20px;width:20px;top:5px;position: relative;cursor:pointer' id='toggleCalendar' src='/euf/assets/images/icons/calendar_icon1.png' srckk='http://eguidemagazine.com/wp-content/uploads/2016/06/calendar-icon-blue_sm.png'>
<div id="yahoo-calendar" name="yahoo-calendar" style='z-index:501;width:250px !important;display: none;position:absolute;'></div>
</body>
</div>
<script>
  var yahoo_yui=YUI();
   yahoo_yui.use('calendar', 'datatype-date', 'cssbutton', function (G) {
    
      calendar = new G.Calendar({
      contentBox: "#yahoo-calendar",
      width:'340px',
      showPrevMonth: false,
      showNextMonth: true,
      minimumDate: new Date()     
     }).render();

       var dtdate = G.DataType.Date;
       calendar.on("selectionChange", function (ev) {
       var newDate = ev.newSelection[0];
       G.one(".yahoo_date_selected").set('value',dtdate.format(newDate));
       G.one("#yahoo-calendar").toggleView();
    });

    G.all("#toggleCalendar,.yahoo_date_selected").on('click', function (ev) {
      G.one('#yahoo-calendar').toggleView();
      ev.preventDefault();
      calendar.set('showPrevMonth', !(calendar.get("showPrevMonth")));
    });

}); // G use ends here. 
</script>
