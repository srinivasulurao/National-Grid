<?php
namespace Custom\Widgets\customer_feedback;

class CustomListFilter extends \RightNow\Libraries\Widget\Base {
    function __construct($attrs) {
        parent::__construct($attrs);
    }

    function getData() {
    	$ci=&get_instance();
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
		$this->data['headers']=$this->getSortingArray($this->data['attrs']['report_id']);
        return parent::getData();

    }


    function getSortingArray($report_id){
      $reportToken = \RightNow\Utils\Framework::createToken($report_id);
      $format = array(
          'truncate_size' => $this->data['attrs']['truncate_size'],
          'max_wordbreak_trunc' => $this->data['attrs']['max_wordbreak_trunc'],
          'emphasisHighlight' => $this->data['attrs']['highlight'],
          'dateFormat' => $this->data['attrs']['date_format'],
          'urlParms' => \RightNow\Utils\Url::getParametersFromList($this->data['attrs']['add_params_to_url']),
      );
      $filters = array('recordKeywordSearch' => true);
      $results = $this->CI->model('custom/ReportCustomModel')->getDataHTML($report_id, $reportToken, $filters, $format)->result;
      $headers=array();
      foreach($results['headers'] as $header):
        if($header['heading']!="Edit")
        $headers[$header['heading']]=$header['col_id']."_".$header['order'];
      endforeach;

      return $headers;
    }
}
