<?php
namespace Custom\Widgets\investigations;

class ComplaintDetails extends \RightNow\Libraries\Widget\Base {
	public $ci_instance;
	
    function __construct($attrs) {
    	$this->ci_instance=&get_instance();
        parent::__construct($attrs);
    }

    function getData() {
       //All the data will come in a tabular column.
       $this->data['incident_details']=$this->getIncidentDetails();
	   $this->data['customer_details']=$this->getCustomerDetails();
	   $this->data['delivery_details']=$this->getDeliveryDetails();
	   $this->data['investigation_details']=$this->getInvestigationDetails();
        return parent::getData();

    }
	
	function getIncidentDetails(){
		//Show the Details of the parent incident.
		$i_id=getUrlParm('i_id'); 
		$parent_id=$this->ci_instance->model('custom/CustomerFeedbackSystem')->getParentIncidentId($i_id);
		$incident=$this->ci_instance->model('custom/CustomerFeedbackSystem')->investigationDetails($parent_id);
		//$this->d($incident->PrimaryContact->Phones);
		$displayParams=array();
		
		$displayParams['Complaint No']=$incident->LookupName;
		$displayParams['Subject']=$incident->Subject;
		$displayParams['Status']=$incident->StatusWithType->StatusType->LookupName;
		$displayParams['Source']=$incident->Source->LookupName;
		$displayParams['Assigned To']=$incident->AssignedTo->LookupName;
		$displayParams['Created On']=date("Y-m-d H:i A",$incident->CreatedTime);
		$displayParams['Severity']=$incident->Severity;
		$displayParams['Interface']=$incident->Interface->LookupName;
		
		
		return $displayParams;
	}

    function getInvestigationDetails(){
    	$displayParams=array();
		$i_id=getUrlParm('i_id'); 
		
		$incident=$this->ci_instance->model('custom/CustomerFeedbackSystem')->investigationDetails($i_id);
		$displayParams['Complaint No']=$incident->LookupName;
		$displayParams['Subject']=$incident->Subject;
		$displayParams['Status']=$incident->StatusWithType->StatusType->LookupName;
		$displayParams['Source']=$incident->Source->LookupName;
		$displayParams['Assigned To']=$incident->AssignedTo->LookupName;
		$displayParams['Created On']=date("Y-m-d H:i A",$incident->CreatedTime);
		$displayParams['Severity']=$incident->Severity;
		$displayParams['Interface']=$incident->Interface->LookupName;
		
		
		return $displayParams;
    }
	
	function getCustomerDetails(){
		$i_id=getUrlParm('i_id');
		$parent_id=$this->ci_instance->model('custom/CustomerFeedbackSystem')->getParentIncidentId($i_id);
		$incident=$this->ci_instance->model('custom/CustomerFeedbackSystem')->investigationDetails($parent_id);
		$customer=array();
		$customer['Sold To Customer Name']=$incident->CustomFields->c->sold_to_customer_name;
		
		//Sold To Customer data needs to there.
		
		$df=$this->ci_instance->model('custom/CustomerFeedbackSystem')->deliveryFind($incident->CustomFields->CFS->Delivery->Delivery, $incident->CustomFields->c->sold_to_customer_name);
		
		$customer['Sold To Customer Region']=$df->SoldToCustomerRegion;
		$customer['Ship To Customer Name']=$df->ship_to_customer_name;
		$customer['City']=$df->ShipToCity;
		$customer['Country']=$df->DestinationCountry;
		$customer['Zip']=$df->ShipToPostalCode;
		$customer['State']=$df->DestinationRegion;
		$customer['Street']=$df->ShipToStreet;
		
		
		return $customer;
	}
	
	function getDeliveryDetails(){
		$i_id=getUrlParm('i_id');
		$parent_id=$this->ci_instance->model('custom/CustomerFeedbackSystem')->getParentIncidentId($i_id);
		$delivery=$this->ci_instance->model('custom/CustomerFeedbackSystem')->getDeliveryInvestigationDetails($parent_id);
		//$this->d($delivery);
		return $delivery;
		
	}
	
	
	function d($data){
		echo"<pre>";
		print_r($data);
		echo "</pre>";
	}
}