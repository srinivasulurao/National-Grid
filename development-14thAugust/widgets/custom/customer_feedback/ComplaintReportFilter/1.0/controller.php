<?php
namespace Custom\Widgets\customer_feedback;

class ComplaintReportFilter extends \RightNow\Libraries\Widget\Base {
    function __construct($attrs) {
        parent::__construct($attrs);
    }

    function getData() {
       $ci=& get_instance();

       
       if($ci->session->getSessionData('complaint_filter_individual')==""):
       $ci->session->setSessionData(array('complaint_filter_individual'=>'c_id'));
       endif;
       
       if($_POST['complaint_filter_individual']):
       $filter=array('complaint_filter_individual'=>$_POST['complaint_filter_individual']);
       $ci->session->setSessionData($filter);
       endif;
       
      
       $this->data['complaint_filter_individual']=$ci->session->getSessionData('complaint_filter_individual');
        return parent::getData();

    }
}