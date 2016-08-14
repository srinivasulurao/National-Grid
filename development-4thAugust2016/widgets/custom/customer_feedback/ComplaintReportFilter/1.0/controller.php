<?php
namespace Custom\Widgets\customer_feedback;

class ComplaintReportFilter extends \RightNow\Libraries\Widget\Base {
    function __construct($attrs) {
        parent::__construct($attrs);
    }

    function getData() {
       $ci=& get_instance();

       
       if($ci->session->getSessionData('complaint_filter')==""):
       $ci->session->setSessionData(array('complaint_filter'=>'c_id'));
       endif;
       
       if($_POST['complaint_filter']):
       $filter=array('complaint_filter'=>$_POST['complaint_filter']);
       $ci->session->setSessionData($filter);
       endif;
       
      
       $this->data['complaint_filter']=$ci->session->getSessionData('complaint_filter');
        return parent::getData();

    }
}