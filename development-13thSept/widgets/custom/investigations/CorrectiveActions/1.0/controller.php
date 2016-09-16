<?php
namespace Custom\Widgets\investigations;

class CorrectiveActions extends \RightNow\Libraries\Widget\Base {
	public $ci;
    function __construct($attrs) {
    	$this->ci=&get_instance();
        parent::__construct($attrs);
    }

    function getData() {
        $this->data['corrective_actions']=$this->fetchCorrectiveActions();
        return parent::getData();

    }
	
	function fetchCorrectiveActions(){
		$i_id=getUrlParm('i_id');
		$ca=$this->ci->model('custom/CustomerFeedbackSystem')->fetchCorrectiveActionsModel($i_id);
		//echo "<pre>";
		//print_r($ca);
		//echo "</pre>";
		return $ca;
	}
}