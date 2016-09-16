<?php
namespace Custom\Widgets\customer_feedback;

class DeliveryLookupInput extends \RightNow\Libraries\Widget\Base {
    function __construct($attrs) {
        parent::__construct($attrs);
    }

    function getData() {
        $x = explode('.',$this->data['attrs']['name']);
		$this->data['js']['table'] = $x[0];
		$this->data['js']['name'] = $x[1];

       //Now we have to generate the value for autopopulate value;
       $incident_id=getUrlParm('i_id');
       if($incident_id):
       $ci=&get_instance();
       $incident=$ci->model('custom/CustomerFeedbackSystem')->getIncident($incident_id);
       $this->data['delivery_value']=(string)$incident->CustomFields->CFS->Delivery->Delivery;
       endif;
        return parent::getData();

    }
}