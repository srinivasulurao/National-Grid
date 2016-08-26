<?php
namespace Custom\Widgets\customer_feedback;

class DeliveryDetails extends \RightNow\Libraries\Widget\Base {
    function __construct($attrs) {
        parent::__construct($attrs);
    }

    function getData() {
        $ci=&get_instance();
        $i_id=getUrlParm('i_id');
        $order_details=$ci->model('custom/CustomerFeedbackSystem')->getDeliveryOrderDetails($i_id);
        $this->data['delivery_order_details']=$order_details;
        return parent::getData();
     
    }
}