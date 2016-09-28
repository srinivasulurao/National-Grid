<?php
namespace Custom\Widgets\investigations;

class ChildInvestigations extends \RightNow\Libraries\Widget\Base {
    function __construct($attrs) {
        parent::__construct($attrs);
    }

    function getData() {
     $ci=&get_instance();
     $i_id=getUrlParm('i_id');
     $this->data['child_investigations']=$ci->model('custom/CustomerFeedbackSystem')->getAllChildIncidents($i_id);
     //print_r($this->data['child_investigations']);
     return parent::getData();
    }
}
