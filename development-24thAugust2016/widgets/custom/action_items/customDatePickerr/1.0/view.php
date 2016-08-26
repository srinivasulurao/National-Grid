<div id="rn_<?=$this->instanceID;?>" >
<style>

.yui3-custom {
    display: inline-table ;
    letter-spacing: normal;
    text-rendering: auto;
    vertical-align: top;
    word-spacing: normal;
	position:absolute;
	z-index:10003;
	font-size: 9pt;
}


table {background:none; border-spacing:0; border:0;}
table th{ text-align:center !important;}
</style>

<link type="text/css" rel="stylesheet" href="http://yui.yahooapis.com/combo?3.13.0/widget-base/assets/skins/sam/widget-base.css&3.13.0/calendar-base/assets/skins/sam/calendar-base.css&3.13.0/calendarnavigator/assets/skins/sam/calendarnavigator.css&3.13.0/calendar/assets/skins/sam/calendar.css&3.13.0/cssbutton/cssbutton-min.css" />
<link type="text/css" rel="stylesheet" href="http://yui.yahooapis.com/combo?3.13.0/widget-base/assets/skins/sam/widget-base.css&3.13.0/calendar-base/assets/skins/sam/calendar-base.css&3.13.0/calendarnavigator/assets/skins/sam/calendarnavigator.css&3.13.0/calendar/assets/skins/sam/calendar.css&3.13.0/cssbutton/cssbutton-min.css" />
<link type="text/css" rel="stylesheet" href="http://yui.yahooapis.com/3.9.0/build/cssgrids/cssgrids-min.css" />



<div id="demo" class="yui3-skin-sam yui3-g" style="width:50%"> <!-- You need this skin class -->
 
  <div id="leftcolumn" class="yui3-u">
       <div id="links" style="margin-top: 37px;  padding-bottom: 15px;">
	   <?php 
	   $str_custom_object=$this->data['attrs']['name'];
	   $str_custom_object= str_replace('.','_',$str_custom_object); ?>
	  <label id="rn_duedate" class="rn_Label" for="rn_TypeAheadContactLookup_34_Contact"> 
	<div class='label_input'><?=$this->data['attrs']['label_input'];?>
                <? if($this->data['attrs']['required']):?>
                    <span class="rn_Required"> * </span><span class="rn_ScreenReaderOnly"><?=getMessage(REQUIRED_LBL)?></span>
                <? endif;?>
            </div>
			</label>
<input style="width:202px;" type="text" id="rn_<?=$this->instanceID;?>_<?=$str_custom_object;?>" name="rn1_<?=$this->instanceID;?>_<?=$this->data['attrs']['name'];?>" value="<?=$this->data['duedate'];?>"  />
  
  <input type="hidden" id="name="rn_<?=$this->instanceID;?>_<?=$this->data['attrs']['name'];?>"" name="name="rn_<?=$this->instanceID;?>_<?=$this->data['attrs']['name'];?>"" value="<?=$this->data['value'];?>"/>
   <span style="display: inline-table;float: left; margin-left: 205px; margin-top: -46px;"> <img style="height:30px"  alt="Calender" src="/euf/assets/images/icons/calendar_icon1.png" id="calendar_icon"></span>
      <!-- The buttons are created simply by assigning the correct CSS class 
      <button id="togglePrevMonth" class="yui3-button">Toggle Previous Month's Dates</button><br>
      <button id="toggleNextMonth" class="yui3-button">Toggle Next Month's Dates</button><br> -->
     
   </div>
   <input type="hidden" id="hid_date_due_std" name="hid_date_due" value="rn_<?=$this->instanceID;?>_<?=$str_custom_object;?>"/>
   <input type="hidden" id="hid_date_due" name="hid_date_due" value="#rn_<?=$this->instanceID;?>_<?=$str_custom_object;?>"/>
  </div>
  <div id="rightcolumn" class="yui3-u" >
   <!-- Container for the calendar -->
     <div id="mycalendar" > </div>
<?php
$dt=new DateTime();
$currentTimeStamp=$dt->getTimeStamp();
$thirty_days_off=$currentTimeStamp+(30*24*3600);
$dt->setTimeStamp($thirty_days_off);
?>
<input type='hidden' id='minimum_date' value="<?php echo $dt->format("Y,m,d"); ?>"/>
  </div>
</div>
</div>

