<?php
namespace Custom\Widgets\action_items;

class CBOFileAttachmentUpload extends \RightNow\Widgets\FileAttachmentUpload {
    function __construct($attrs) {
        parent::__construct($attrs);
    }

    function getData() {

       // return parent::getData();
	    $validAttributes = explode('.',$this->data['attrs']['name']);
	   $pack = $validAttributes[0];
       $this->table = $validAttributes[1];
       $this->fieldName = $validAttributes[2];
	 
	    $this->CI->load->model('custom/CustomerFeedbackSystem');
	  // $this->data['field'] = $this->CI->CustomerFeedbackSystem->getBusinessObjectField($pack,$this->table,$this->fieldName );

		
	  	$this->dataType = $this->data['field']->data_type;
	    $this->data['js']['type'] = $this->data['field']->data_type;
	  
	    $this->data['displayType'] = $this->dataType;
        $this->data['js']['table'] = $this->table;
        $this->data['js']['name'] = $this->fieldName;
		
		 if($primaryObject = \RightNow\Utils\Connect::getObjectInstance($this->table))
        {
            $this->data['js']['attachmentCount'] = ($primaryObject->FileAttachments) ? count($primaryObject->FileAttachments) : 0;
        }

        if($this->data['attrs']['max_attachments'] !== 0 && $this->data['attrs']['min_required_attachments'] > $this->data['attrs']['max_attachments'])
        {
            echo $this->reportError(sprintf(\RightNow\Utils\Config::getMessage(PCT_S_PCT_S_LBL), 'min_required_attachments', 'max_attachments'));
            return false;
        }

    }
}