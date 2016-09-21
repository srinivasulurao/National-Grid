<?php
namespace Custom\Widgets\action_items;

class StatusFilter extends \RightNow\Libraries\Widget\Base {
    function __construct($attrs) {
        parent::__construct($attrs);
    }

    function getData() {
      $ci=&get_instance();
      if(isset($_POST['complaint_status_filter'])):
        $filter=array('complaint_status_filter'=>$_POST['complaint_status_filter']);
        $ci->session->setSessionData($filter);
      endif;
      $getStatusList=($this->attrs['entity_type']->value=="action_item")?$ci->model('custom/CustomerFeedbackSystem')->getActionItemListModel():$ci->model('custom/CustomerFeedbackSystem')->getIncidentStatusListModel();
      //$this->data['status_list']=$getStatusList;
      $this->data['status_filter_selected']=$ci->session->getSessionData('complaint_status_filter');
      $this->data['status_list']=($this->attrs['entity_type']->value=="action_item")?array("open"=>"Open","closed"=>"Closed"):$ci->model('custom/CustomerFeedbackSystem')->getIncidentStatusListModel();
      return parent::getData();

    }
}
