<?php
namespace Custom\Widgets\customer_feedback;

use RightNow\Utils\Config,
    RightNow\Utils\Text;

class DatePickerInput extends \RightNow\Libraries\Widget\Input {
     function __construct($attrs) {
        parent::__construct($attrs);
    }

    function getData() {
        $x = explode('.',$this->data['attrs']['name']);
		$this->data['js']['table'] = $x[0];
		$this->data['js']['name'] = $x[1];

       //Now we have to generate the value for autopopulate value;
       $incident_id=getUrlParm('i_id');
       if($incident_id){
         $ci=&get_instance();
         $incident=$ci->model('custom/CustomerFeedbackSystem')->getIncident($incident_id);
         $this->data['target_date']=($incident->CustomFields->c->target_date)?date("Y-m-d",$incident->CustomFields->c->target_date):"";
       }
       else{
          $ts=(time())+(3600*24*30);
          $this->data['target_date']=date("Y-m-d",$ts);
       }
    }
}
