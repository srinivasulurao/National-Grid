<?php

namespace Custom\Controllers;
use RightNow\Utils\Framework,
    RightNow\Libraries\AbuseDetection,
    RightNow\Utils\Config,
    RightNow\Utils\Okcs;

class CustomerFeedbackSystem extends \RightNow\Controllers\Base
{
    //This is the constructor for the custom controller. Do not modify anything within
    //this function.
    function __construct()
    {
        parent::__construct();
    }

    /**
     * Sample function for ajaxCustom controller. This function can be called by sending
     * a request to /ci/ajaxCustom/ajaxFunctionHandler.
     */
    function ajaxFunctionHandler()
    {
        $postData = $this->input->post('post_data_name');
        //Perform logic on post data here
        echo $returnedInformation;
    }

    /**
     * Sample search function
     */
    function search () {
        $filters = json_decode($this->input->post('filters'), true);
        $filters['limit'] = array('value' => $this->input->request('limit'));
        $sourceID = $this->input->post('sourceID');

        $search = \RightNow\Libraries\Search::getInstance($sourceID);
        $search->addFilters($filters);

        echo json_encode($search->executeSearch());
    }

    function sendForm()
    {

        AbuseDetection::check($this->input->post('f_tok'));
        $data = json_decode($this->input->post('form'));
        if(!$data)
        {
            header("HTTP/1.1 400 Bad Request");
            // Pad the error message with spaces so IE will actually display it instead of a misleading, but pretty, error message.
            Framework::writeContentWithLengthAndExit(json_encode(Config::getMessage(END_REQS_BODY_REQUESTS_FORMATTED_MSG)) . str_repeat("\n", 512));
        }
        if($listOfUpdateRecordIDs = json_decode($this->input->post('updateIDs'), true)){
            $listOfUpdateRecordIDs = array_filter($listOfUpdateRecordIDs);
        }
        $smartAssistant = $this->input->post('smrt_asst');

        $response = $this->model('custom/CustomerFeedbackSystem')->ActionItemCreation($data, $listOfUpdateRecordIDs ?: array(), ($smartAssistant === 'true'));
        echo $response;

    }

    function SuppliersendForm()
    {

        AbuseDetection::check($this->input->post('f_tok'));
        $data = json_decode($this->input->post('form'));

        if(!$data)
        {
            header("HTTP/1.1 400 Bad Request");
            // Pad the error message with spaces so IE will actually display it instead of a misleading, but pretty, error message.
            Framework::writeContentWithLengthAndExit(json_encode(Config::getMessage(END_REQS_BODY_REQUESTS_FORMATTED_MSG)) . str_repeat("\n", 512));
        }
        if($listOfUpdateRecordIDs = json_decode($this->input->post('updateIDs'), true)){
            $listOfUpdateRecordIDs = array_filter($listOfUpdateRecordIDs);
        }
        $smartAssistant = $this->input->post('smrt_asst');

        $response = $this->model('custom/CustomerFeedbackSystem')->SupplierCompliantCreation($data, $listOfUpdateRecordIDs ?: array(), ($smartAssistant === 'true'));
        echo $response;

    }

    function customerComplaintSendForm(){

        AbuseDetection::check($this->input->post('f_tok'));
        $data=json_decode($this->input->post('form'));
        $updateRecordIdList=json_decode($this->input->post('updateIDs'));
        $response = $this->model('custom/CustomerFeedbackSystem')->CustomerCompliantCreation($data);
        echo $response;
    }

    function SupplierCompliantEdit($id)
    {

        AbuseDetection::check($this->input->post('f_tok'));
        $data = json_decode($this->input->post('form'));

        if(!$data)
        {
            header("HTTP/1.1 400 Bad Request");
            // Pad the error message with spaces so IE will actually display it instead of a misleading, but pretty, error message.
            Framework::writeContentWithLengthAndExit(json_encode(Config::getMessage(END_REQS_BODY_REQUESTS_FORMATTED_MSG)) . str_repeat("\n", 512));
        }
        if($listOfUpdateRecordIDs = json_decode($this->input->post('updateIDs'), true)){
            $listOfUpdateRecordIDs = array_filter($listOfUpdateRecordIDs);
        }
        $smartAssistant = $this->input->post('smrt_asst');
        $supplier_inc_id =$id;
        $response = $this->model('custom/CustomerFeedbackSystem')->SupplierCompliantEdit($data, $listOfUpdateRecordIDs ?: array(), ($smartAssistant === 'true'),$supplier_inc_id);
        echo $response;
    }


    function updateForm($id)
    {

        AbuseDetection::check($this->input->post('f_tok'));
        $data = json_decode($this->input->post('form'));

        if(!$data)
        {
            header("HTTP/1.1 400 Bad Request");
            // Pad the error message with spaces so IE will actually display it instead of a misleading, but pretty, error message.
            Framework::writeContentWithLengthAndExit(json_encode(Config::getMessage(END_REQS_BODY_REQUESTS_FORMATTED_MSG)) . str_repeat("\n", 512));
        }
        if($listOfUpdateRecordIDs = json_decode($this->input->post('updateIDs'), true)){
            $listOfUpdateRecordIDs = array_filter($listOfUpdateRecordIDs);
        }
        $smartAssistant = $this->input->post('smrt_asst');
        $action_id =$id;
        $response = $this->model('custom/CustomerFeedbackSystem')->ActionItemUpdate($data, $listOfUpdateRecordIDs ?: array(), ($smartAssistant === 'true'),$action_id);
        echo $response;
    }

    function supplier_search($srch)
    {
        $searchTerm = $srch;

        $this->model('custom/CustomerFeedbackSystem')->searchsupplier($searchTerm);

    }

    function contact_search()
    {
        $searchTerm = $_POST['str'];


        $this->model('custom/CustomerFeedbackSystem')->searchcontact($searchTerm);

    }

    function deliveryLookupSearch(){

        $search_term=$this->input->post('search_term');
        $this->model('custom/CustomerFeedbackSystem')->deliveryLookUp($search_term);
    }

    function checkDeliveryNumberExist(){
        $delivery_no=$_REQUEST['delivery_no'];      
        if($delivery_no) {
           $delivery=$this->model('custom/CustomerFeedbackSystem')->getDelivery($delivery_no);
            echo (int)$delivery['ID'];
        }
        else
        {
            echo 0;
        }

    }

    function deliveryDetailsLookup(){
        $delivery_no=$this->input->post('delivery_no');
        $ship_to_customer=$this->input->post('ship_to_customer');
        $sold_to_customer=$this->input->post('sold_to_customer');
        $customer_po_no=$this->input->post('customer_po_no');
        $this->model('custom/CustomerFeedbackSystem')->deliveryDetailsLookupModel($delivery_no,$customer_po_no,$ship_to_customer,$sold_to_customer);
    }

    function updateCustomerComplaintSendForm(){
        $i_id=$this->uri->segment(3);
        AbuseDetection::check($this->input->post('f_tok'));
        $data=json_decode($this->input->post('form'));
        $updateRecordIdList=json_decode($this->input->post('updateIDs'));
        $response = $this->model('custom/CustomerFeedbackSystem')->CustomerCompliantUpdate($data,$i_id);
        echo $response;
    }

    function deliveryLineItemList(){
        $delivery_id=$this->input->post('delivery_id');
        $incident_id=$this->input->post('incident_id');
        $this->model('custom/CustomerFeedbackSystem')->deliveryLineItemListModel($delivery_id,$incident_id);
    }

    function getDeliveryDetails(){
        $delivery_no=$this->input->post('delivery_no');
        $this->model('custom/CustomerFeedbackSystem')->getDeliveryDetailsData($delivery_no);
    }

    function soldToCustomerSuggest(){
        $input=$this->input->post('input');
        $this->model('custom/CustomerFeedbackSystem')->soldToCustomerSuggestion($input);
    }

    function shipToCustomerSuggest(){
        $input=$this->input->post('input');
        $this->model('custom/CustomerFeedbackSystem')->shipToCustomerSuggestion($input);
    }

    function productNoToCustomerSuggest(){
        $input=$this->input->post('input');
        $this->model('custom/CustomerFeedbackSystem')->productNoToCustomerSuggestion($input);
    }
	
	function addCorrectiveAction(){	
		$this->model('custom/CustomerFeedbackSystem')->addCorrectiveActionModel();
	}
	
	function deleteCorrectiveActions(){
		$input=$this->input->post('input');
		$delete_ids=explode("|",$input);
		$this->model('custom/CustomerFeedbackSystem')->deleteCorrectiveActionsModel($delete_ids);
	}
	
	function changeCorrectiveActionStatus(){
		
		$input=$this->input->post('input');
		$status=$this->input->post('status');
		$caid_ids=explode("|",$input);
		$this->model('custom/CustomerFeedbackSystem')->changeStatusCorrectiveActionsModel($caid_ids,$status);
	}

public function SaveThreadSendMail($param,$i_id){
	
	    //$i_id=$this->uri->segment(4);
	    $data=json_decode($this->input->post('form'));
        $response = $this->model('custom/CustomerFeedbackSystem')->SaveThreadSendMailModel($data,$i_id);
        echo $response;
		
}

public function showThread(){
	echo "<body>fdsfs<rn:widget path=\"output/IncidentThreadDisplay\" label='Communication History' name=\"Incident.Threads\" /></body>";
}

public function contactLookUpSearch(){
	
	$input=$this->input->post('input');
    $this->model('custom/CustomerFeedbackSystem')->contactLookUpSearchModel($input);
}


public function setInvestigationClosure($param,$i_id){
	$data=json_decode($this->input->post('form'));
	$response=$this->model('custom/CustomerFeedbackSystem')->setInvestigationClosureModel($data,$i_id);
	echo $response;
}

}// Controller Class Ends here !
