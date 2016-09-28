<?php
namespace Custom\Widgets\action_items;

class ProductLineFilter extends \RightNow\Libraries\Widget\Base {
    function __construct($attrs) {
        parent::__construct($attrs);
    }

    function getData() {
        $ci=&get_instance();
        $profile=$ci->session->getProfile();
        $org_id=$profile->org_id->value;


        if(isset($_POST['complaint_productline_filter'])):
          $filter=array('complaint_productline_filter'=>$_POST['complaint_productline_filter']);
          $ci->session->setSessionData($filter);
        endif;
        $this->data['productline_filter_selected']=$ci->session->getSessionData('complaint_productline_filter');
        $this->data['OrgToProductMapping']=$ci->model('custom/CustomerFeedbackSystem')->getOrganizationSalesProduct($org_id);
        $this->data['product_list']=$ci->model('custom/CustomerFeedbackSystem')->getServiceProductList();
        return parent::getData();

    }
}
