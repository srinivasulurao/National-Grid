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
    $page=$_SERVER['REQUEST_URI'];
    if(substr_count($page,'update')):
      $filter=array("last_incident_page_visited"=>"update");
			$ci->session->setSessionData($filter);
    endif;

    if(substr_count($page,'new')):
      $filter=array("last_incident_page_visited"=>"new");
			$ci->session->setSessionData($filter);
    endif;

        return parent::getData();

    }
}
