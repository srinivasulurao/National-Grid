<?php
namespace Custom\Widgets\action_items;
use RightNow\Utils\Connect,
    RightNow\Utils\Config;


class CBOSelectionInput extends \RightNow\Widgets\SelectionInput {
    function __construct($attrs) {
        parent::__construct($attrs);
    }

    function getData() {

      //  return parent::getData();
	  $validAttributes = explode('.',$this->data['attrs']['name']);

		$cacheKey = 'Input_' . $this->data['attrs']['name'];
        $cacheResults = checkCache($cacheKey);
        if(is_array($cacheResults))
		{
            list($this->field, $this->table, $this->fieldName, $this->data) = $cacheResults;
			$this->field = unserialize($this->field);

            return;
		}
		$pack = $validAttributes[0];
       $this->table = $validAttributes[1];
       $this->fieldName = $validAttributes[2];
	   $table=$this->table.$this->fieldName;
	   $this->CI->load->model('custom/CustomerFeedbackSystem');

	    $this->data['field'] = $this->CI->CustomerFeedbackSystem->getBusinessObjectField($pack,$this->table,$this->fieldName );

		$this->dataType = $this->data['field']->data_type;
		if(empty($this->data['field']->default_value))
		{

			$this->data['field']->default_value="Please select ".$this->data['field']->lang_name;
		}

	   $url_id=getUrlParm(ID);

	   if($url_id)
	   {
	   $data_value = $this->CI->CustomerFeedbackSystem->getdatavalues($pack,$this->table,$this->fieldName,$url_id);
	  $this->data['field']->value=intval($data_value);
	   }

	    $this->data['js']['type'] = 'NamedIDLabel';
		 $this->data['js']['table'] = $this->table;
        $this->data['js']['name'] = $this->fieldName;
		$this->data['constraints'] = array();
		$this->data['js']['constraints'] = $this->data['constraints'];
		 if($this->fieldName === 'Category')
		 {

			if($this->data['field']->data_type == 'CFS\ActionItemCategory')
			{

			$this->data['inputType']='Select';
			$this->data['displayType']='Select';

			$result = $this->CI->CustomerFeedbackSystem->getExistingType($pack,$table);
			$this->data['menuItems']=$result;

			}


			if($this->data['field']->value)
			{

			$this->data['value'] = $this->data['field']->value;

			}
			else
			{

			$this->data['value'] = $this->data['field']->default_value;
			}

	   }

	   if($this->fieldName === 'Category')
		 {

			if($this->data['field']->data_type == 'CFS\ActionItemCategory')
			{

			$this->data['inputType']='Select';
			$this->data['displayType']='Select';

			$result = $this->CI->CustomerFeedbackSystem->getExistingType($pack,$table);
			$this->data['menuItems']=$result;

			}


			if($this->data['field']->value)
			{

			$this->data['value'] = $this->data['field']->value;

			}
			else
			{

			$this->data['value'] = $this->data['field']->default_value;
			}

	   }

	   if($this->fieldName === 'DueDate')
		 {

			if($this->data['field']->data_type == 'CFS\ActionItemDueDate')
			{

			$this->data['inputType']='Select';
			$this->data['displayType']='Select';

			$result = $this->CI->CustomerFeedbackSystem->getExistingType($pack,$table);
			$this->data['menuItems']=$result;

			}


			if($this->data['field']->value)
			{

			$this->data['value'] = $this->data['field']->value;

			}
			else
			{

			$this->data['value'] = $this->data['field']->default_value;
			}

	   }

	     if($this->fieldName === 'Priority')
		 {

			if($this->data['field']->data_type == 'CFS\ActionItemPriority')
			{

			$this->data['inputType']='Select';
			$this->data['displayType']='Select';

			$result = $this->CI->CustomerFeedbackSystem->getExistingType($pack,$table);
			$this->data['menuItems']=$result;

			}


			if($this->data['field']->value)
			{

			$this->data['value'] = $this->data['field']->value;
			//$this->data['attrs']['default_value']=$this->data['field']->value;
			}
			else
			{

			$this->data['value'] = $this->data['field']->default_value;
			}

	   }

	    if($this->fieldName === 'Status')
		 {

			if($this->data['field']->data_type == 'CFS\ActionItemStatus')
			{

			$this->data['inputType']='Select';
			$this->data['displayType']='Select';

			$result = $this->CI->CustomerFeedbackSystem->getExistingType($pack,$table);
			$this->data['menuItems']=$result;

			}


			if($this->data['field']->value)
			{

			$this->data['value'] = $this->data['field']->value;

			}
			else
			{

			$this->data['value'] = $this->data['field']->default_value;
			}

	   }
//Common way to select the value.
$this->data['val_selected']=$result = $this->CI->CustomerFeedbackSystem->getdatavalues("CFS","ActionItem",$this->fieldName,$url_id)->ID;
    }




}
