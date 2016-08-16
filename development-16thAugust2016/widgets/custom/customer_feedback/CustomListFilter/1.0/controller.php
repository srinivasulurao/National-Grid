<?php
namespace Custom\Widgets\customer_feedback;

class CustomListFilter extends \RightNow\Libraries\Widget\Base {
    function __construct($attrs) {
        parent::__construct($attrs);
    }

    function getData() {
    	$ci=&get_instance();
		//print_r($_POST);
		if(isset($_POST['submit_filter_search'])):
			$filter=array('complaint_filter'=>'search_text','search_text'=>$_POST['searchText'],"incident_sort_by"=>$_POST['incident_sort_by'],"incident_order_by"=>$_POST['incident_order_by']);
			$ci->session->setSessionData($filter);
		endif;
				
		 if(isset($_POST['reset_filter_search'])):
			$filter=array("complaint_filter"=>"c_id","search_text"=>"","incident_sort_by"=>"","incident_order_by"=>""); 
			$ci->session->setSessionData($filter);
		 endif;	
		 	 
        $this->data['search_text']=$ci->session->getSessionData('search_text');
		$this->data['sort_by']=$ci->session->getSessionData('incident_sort_by');
		$this->data['order_by']=$ci->session->getSessionData('incident_order_by');
		
		//################################## Fix the Headers for different Pages.#########################################
		$this->data['action']=$this->data['attrs']['page_action'];
		if($this->data['attrs']['page_entity']=="customer_feedback")
		$this->data['headers']=array('Reference #'=>'2_1',"Subject"=>"3_2","Contact Name"=>"4_3","Customer Name"=>"5_4","Product"=>"6_5","Target Date"=>"7_6","Status"=>"8_7");
        if($this->data['attrs']['page_entity']=="supplier_feedback")
		$this->data['headers']=array('Reference #'=>'1_0',"Subject"=>"2_1","Contact Name"=>"3_2","Supplier Name"=>"4_3","PO Number"=>"6_5","Target Date"=>"5_4","Status"=>"7_6");
		if($this->data['attrs']['page_entity']=="investigations")
		$this->data['headers']=array('Reference #'=>'3_2',"Subject"=>"4_3","Customer Name"=>"5_4","Category"=>"6_5","Status"=>"7_6");
        return parent::getData();

    }
}