<?php
namespace Custom\Widgets\customer_feedback;

class FormFunctionalityProvider extends \RightNow\Libraries\Widget\Base {
    function __construct($attrs) {
        parent::__construct($attrs);
    }

    function getData() {
        $ci=&get_instance();
		$i_id=getUrlParm('i_id');
		if($i_id):
		$incident=$ci->model('custom/CustomerFeedbackSystem')->getIncident($i_id);
		$this->data['save_as_draft_recorded']=$incident->CustomFields->c->draft;
		endif;
        return parent::getData();

    }
}
