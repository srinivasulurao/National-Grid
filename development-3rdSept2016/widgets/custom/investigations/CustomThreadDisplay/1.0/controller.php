<?php
namespace Custom\Widgets\investigations;

class CustomThreadDisplay extends \RightNow\Libraries\Widget\Base {
    function __construct($attrs) {
        parent::__construct($attrs);
    }

    function getData() {

     $ci=&get_instance();
	   if($this->attrs['type']->value=="parent_incident"):
	   $i_id=getUrlParm('i_id');
     $parent_id=$ci->model('custom/CustomerFeedbackSystem')->getParentIncidentId($i_id);
	   $threads=$ci->model('custom/CustomerFeedbackSystem')->getIncident($parent_id);
	   $this->data['threads']=$threads->Threads;
	   endif;

        return parent::getData();

    }
}
