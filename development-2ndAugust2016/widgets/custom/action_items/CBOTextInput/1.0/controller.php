<?php /* Originating Release: May 2013 */

namespace Custom\Widgets\action_items;

use RightNow\Utils\Connect;

class CBOTextInput extends \RightNow\Libraries\Widget\Input {
    function __construct($attrs) {
        parent::__construct($attrs);
    }

    function getData() {
       // if (parent::getData() === false) return false;
	   
	  /* generic code block starts here*/
		$validAttributes = explode('.',$this->data['attrs']['name']);
	
	
		$pack = $validAttributes[0];
       $this->table = $validAttributes[1];
       $this->fieldName = $validAttributes[2];
	   
	    $this->CI->load->model('custom/CustomerFeedbackSystem');
	 //  $this->data['field'] = $this->CI->CustomerFeedbackSystem->getBusinessObjectField($pack,$this->table,$this->fieldName );
	   
	   $url_id=getUrlParm(ID);
	  
	   if($url_id)
	   {
	   $data_value = $this->CI->CustomerFeedbackSystem->getdatavalues($pack,$this->table,$this->fieldName,$url_id);
	   $this->data['field']->value=$data_value;
	   }
		if($this->data['field']->data_type =="Date")
		{
	  	 $this->dataType = "String";
	   	 $this->data['js']['type'] = "String";
	   }
	   else{
	  	$this->dataType = $this->data['field']->data_type;
	    $this->data['js']['type'] = $this->data['field']->data_type;
	   }
	    $this->data['displayType'] = $this->dataType;
        $this->data['js']['table'] = $this->table;
        $this->data['js']['name'] = $this->fieldName;
		$this->data['constraints'] = array();
		$this->data['js']['constraints'] = $this->data['constraints'];	
		
		 if($this->data['attrs']['textarea'])
		 {
		 $displayType = $this->data['displayType'] = 'Textarea';
		 }else
		 {
	   if($this->data['field']->data_type === "String")
		 $this->data['inputType']="text";
	}
		 
		
	
	 
		 if($this->data['field']->value)
		{
		$this->data['value'] = $this->data['field']->value;
		$this->data['attrs']['default_value']=$this->data['field']->value;
		}
		else
		{
		$this->data['value'] = $this->data['field']->default_value;
		}
		if($this->data['attrs']['label_input']!="{default_label}"){
		$this->data['attrs']['label_input'] = $this->data['attrs']['label_input'];
		}
		else
		{
		$this->data['attrs']['label_input'] = $this->data['field']->lang_name;
		}

        $this->data['checkreadonly'] = false;
       if($this->data['field']->readonly==1)
        {
		 
             $this->data['checkreadonly'] = true;
        }

	   
	

        if (!$this->data['readOnly']) {
            //Only set the max length of the attribute if it's less than the DB required max length
            if($this->data['attrs']['maximum_length'] > 0){
                $this->constraints['maxLength'] = ($this->constraints['maxLength'] > 0) ? min($this->constraints['maxLength'], $this->data['attrs']['maximum_length']) : $this->data['attrs']['maximum_length'];
                $this->data['js']['constraints']['maxLength'] = $this->data['constraints']['maxLength'] = $this->constraints['maxLength'];
            }
            //If a minimum length is set, that also means the user has to input some content, thereby making it required
            if($this->data['attrs']['minimum_length'] > 0){
                if($this->constraints['maxLength'] > 0 && ($this->data['attrs']['minimum_length'] > $this->constraints['maxLength'])){
                    echo $this->reportError(sprintf(\RightNow\Utils\Config::getMessage(FLD_PCT_S_MNIMUM_LNG_PCT_D_MAX_LNG_MSG), $this->fieldName, $this->data['attrs']['minimum_length'], $this->constraints['maxLength']));
                    return false;
                }
                $this->constraints['minLength'] = $this->data['constraints']['minLength'] = $this->data['js']['constraints']['minLength'] = $this->data['attrs']['minimum_length'];
                $this->data['attrs']['required'] = $this->data['constraints']['required'] = true;
            }

            if(isset($this->data['attrs']['maximum_value'])){
                $this->constraints['maxValue'] = (isset($this->constraints['maxValue'])) ? min($this->constraints['maxValue'], $this->data['attrs']['maximum_value']) : $this->data['attrs']['maximum_value'];
                $this->data['js']['constraints']['maxValue'] = $this->data['constraints']['maxValue'] = $this->constraints['maxValue'];
            }

            if(isset($this->data['attrs']['minimum_value'])){
                $this->constraints['minValue'] = (isset($this->constraints['minValue'])) ? max($this->constraints['minValue'], $this->data['attrs']['minimum_value']) : $this->data['attrs']['minimum_value'];
                if(isset($this->constraints['maxValue']) && ($this->data['attrs']['minimum_value'] > $this->constraints['maxValue'])){
                    echo $this->reportError(sprintf(\RightNow\Utils\Config::getMessage(FLD_PCT_S_MINIMUM_VAL_PCT_D_MAX_VAL_MSG), $this->fieldName, $this->data['attrs']['minimum_value'], $this->constraints['maxValue']));
                    return false;
                }
                $this->data['js']['constraints']['minValue'] = $this->data['constraints']['minValue'] = $this->constraints['minValue'];
            }
        }
		  if($this->data['attrs']['require_validation']) {
            $this->data['constraints']['requireValidation'] = true;
        }

      

    }

    /**
     * Returns HTML attributes for an input element based on the field's
     * constraints.
     * @return string HTML attributes
     */
    public function outputConstraints() {
        $attributes = '';
        if (array_key_exists('maxLength', $this->constraints)) {
            $attributes .= "maxlength='{$this->constraints['maxLength']}' ";
        }
      if (array_key_exists('maxValue', $this->constraints)) {
            $attributes .= "max='{$this->constraints['maxValue']}' ";
        }
        if (array_key_exists('minValue', $this->constraints)) {
            $attributes .= "min='{$this->constraints['minValue']}' ";
        }
        if ($this->data['attrs']['required']) {
            $attributes .= "required ";
        }
        if ($this->data['inputName'] === 'Contact.Login') {
            $attributes .= "autocorrect='off' autocapitalize='off' ";
        }
        return trim($attributes);
    }

    /**
     * Determines the type of input for the field.
     * @param string $fieldName Field name
     * @param string $dataType Data type of the field
     * @param array $constraints Field's contraints
     * @return string One of Email|Url|Number|Textarea|Text
     */
    protected function determineDisplayType($fieldName, $dataType, $constraints) {
        if (\RightNow\Utils\Text::beginsWith($fieldName, 'Contact.Emails.') || $this->data['js']['email']) {
            return 'Email';
        }
        if ($this->data['js']['url']) {
            return 'Url';
        }
        if ($dataType === 'Integer') {
            return 'Number';
        }
		 if ($dataType === 'Integer') {
            return 'String';
        }
        if ($dataType === 'Thread') {
            return 'Textarea';
        }
        if ($constraints) {
            foreach ($constraints as $name => $constraint) {
                if ($name === 'maxLength' && $constraint <= 300) {
                    return 'Text';
                }
            }
        }

        return 'Textarea';
    }
}
