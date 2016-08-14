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
            echo (int)$this->model('custom/CustomerFeedbackSystem')->getDeliveryId($delivery_no);
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

}// Controller Class Ends here !
