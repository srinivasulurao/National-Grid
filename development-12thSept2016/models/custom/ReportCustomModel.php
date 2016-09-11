<?php /* Originating Release: May 2016 */

namespace Custom\Models;
require_once CPCORE . 'Models/Report.php';
/**
 * Methods for the retrieval and manipulation of analytics reports.
 */
class ReportCustomModel extends \RightNow\Models\Report
{

	    public function getDataHTML($reportID, $reportToken, $filters, $format, $useSubReport = true, $forceCacheBust = false, $cleanFilters = true)
    {
        if ($filters && $cleanFilters) {
            $preFilterCleanHookData = array("filters" => $filters, "cleanFilterFunctionsMap" => $this->getCleanFilterFunctions());
            \RightNow\Libraries\Hooks::callHook('pre_report_filter_clean', $preFilterCleanHookData);
            $filters = $this->cleanFilterValues($preFilterCleanHookData["filters"], $preFilterCleanHookData["cleanFilterFunctionsMap"]);
        }
        $subReportMap = false;
        if ($useSubReport) {
           $preHookData = self::getSubReportMapping();
            \RightNow\Libraries\Hooks::callHook('pre_sub_report_check', $preHookData);
            $subReportMap = $preHookData[$reportID];
        }
        if ($useSubReport && $subReportMap) {
            return $this->getDataHTMLUsingSubReports($reportID, $reportToken, $filters, $format, $subReportMap, $forceCacheBust);
        }

        if($this->preProcessData($reportID, $reportToken, $filters, $format))
        {
            $this->getData($format['hiddenColumns'], $forceCacheBust);
            $this->formatData(true, $format['hiddenColumns']);
            $this->getOtherKnowledgeBaseData();
        }
        return $this->getResponseObject($this->returnData, 'is_array');


	}


	private function getCleanFilterFunctions() {
        $cleanModerationDateFilter = function($dateFilterValue) {
            if (empty($dateFilterValue)) {
                return null;
            }
            $dateFormatObj = Text::getDateFormatFromDateOrderConfig();
            $dateFormat = $dateFormatObj["short"];
            $dateIntervals = array("day", "year", "month", "week", "hour");
            $dateIntervals = array_merge($dateIntervals, array_map(function ($interval) {
                return $interval . "s";
            }, $dateIntervals)
            );
            $dateValueParts = explode("_", $dateFilterValue);
            $interval = strtolower($dateValueParts[2]);
            $isHour = $interval === 'hours' || $interval === 'hour';
            if (count($dateValueParts) === 3 && $dateValueParts[0] === "last" && intval($dateValueParts[1]) && array_search($interval, $dateIntervals)) {
                $dateExpression = "-$dateValueParts[1] " . strtolower($dateValueParts[2]);
                $dateValue = $isHour ? strtotime($dateExpression) : strtotime("midnight", strtotime($dateExpression));
            }
            $dateValue = $dateValue ? $dateValue . "|" : Text::validateDateRange($dateFilterValue, $dateFormat, "|", true);
            $dateValue = $dateValue ?: null;
            return $dateValue;
        };

        return array("questions.updated" => $cleanModerationDateFilter,
            "comments.updated" => $cleanModerationDateFilter);
    }

	   private static function getSubReportMapping(){
        static $subReportMapping = array();
        if (!$subReportMapping) {
            $subReportMapping = array(15100 => array("SubReportMapping" => array(4 => array("SubReportID" => 15144, "SubReportColID" => 2),
                        "question_content_flags.flag4" => array("SubReportID" => 15145, "SubReportColID" => 2),
                        "p4" => array("SubReportID" => 15146, "SubReportColID" => 2),
                        "c4" => array("SubReportID" => 15147, "SubReportColID" => 2),
                        ".+4" => array("SubReportID" => 15123, "SubReportColID" => 2),
                        8 => array("SubReportID" => 15140, "SubReportColID" => 2),
                        "question_content_flags.flag8" => array("SubReportID" => 15141, "SubReportColID" => 2),
                        "p8" => array("SubReportID" => 15142, "SubReportColID" => 2),
                        "c8" => array("SubReportID" => 15143, "SubReportColID" => 2),
                        ".+8" => array("SubReportID" => 15122, "SubReportColID" => 2),
                    ), "MainReportIDFilter" => "question_id", "FilterNamesOnJoins" => array('p','c','question_content_flags.flag')
                ),
                15101 => array("SubReportMapping" => array(5 => array("SubReportID" => 15152, "SubReportColID" => 2),
                        "comment_cnt_flgs.flag5" => array("SubReportID" => 15153, "SubReportColID" => 2),
                        "p5" => array("SubReportID" => 15154, "SubReportColID" => 2),
                        "c5" => array("SubReportID" => 15155, "SubReportColID" => 2),
                        ".+5" => array("SubReportID" => 15125, "SubReportColID" => 2),
                        9 => array("SubReportID" => 15148, "SubReportColID" => 2),
                        "comment_cnt_flgs.flag9" => array("SubReportID" => 15149, "SubReportColID" => 2),
                        "p9" => array("SubReportID" => 15150, "SubReportColID" => 2),
                        "c9" => array("SubReportID" => 15151, "SubReportColID" => 2),
                        ".+9" => array("SubReportID" => 15124, "SubReportColID" => 2),
                    ), "MainReportIDFilter" => "comment_id", "FilterNamesOnJoins" => array('p','c','comment_cnt_flgs.flag')
                ),
                15102 => array("SubReportMapping" => array(1 => array("SubReportID" => 15115, "SubReportColID" => 1),
                        3 => array("SubReportID" => 15115, "SubReportColID" => 2),
                        4 => array("SubReportID" => 15133, "SubReportColID" => 2),
                        5 => array("SubReportID" => 15132, "SubReportColID" => 2),
                        6 => array("SubReportID" => 15116, "SubReportColID" => 2),
                        7 => array("SubReportID" => 15117, "SubReportColID" => 2),
                        8 => array("SubReportID" => 15118, "SubReportColID" => 2),
                        9 => array("SubReportID" => 15119, "SubReportColID" => 2),
                        10 => array("SubReportID" => 15120, "SubReportColID" => 2),
                        11 => array("SubReportID" => 15121, "SubReportColID" => 2),
                        12 => array("SubReportID" => 15133, "SubReportColID" => 3)
                    ), "MainReportIDFilter" => "user_id"
                )
            );
        }
        return $subReportMapping;
    }

	protected function filtersToSearchArgs()
    {
        $searchArgs = array();
        if(isset($this->appliedFilters['search']) && ($this->appliedFilters['search'] === '0' || $this->appliedFilters['search'] === 0) && !$this->appliedFilters['no_truncate'])
        {
            return $searchArgs;
        }

        $keywordValue = "";
        $seenKeyword = false;
        $seenSearchType = false;
        $searchTypeName = "";
        $searchTypeOperator = "";
        $contactData = false;
        $count = 0;

        if(is_array($this->appliedFilters)){
            foreach($this->appliedFilters as $key => $value)
            {
                // these are search filters
                if(!isset($value->filters->rnSearchType)){
                    continue;
                }
                // map to new events
                if(isset($value->filters->data->fltr_id))
                    $value->filters->fltr_id = $value->filters->data->fltr_id;
                if(isset($value->filters->data->oper_id))
                    $value->filters->oper_id = $value->filters->data->oper_id;
                if(isset($value->filters->data->val))
                    $value->filters->data = $value->filters->data->val;

                if($value->filters->fltr_id && !$this->isFilterIDValid($value->filters->fltr_id)) {
                    continue;
                }

				//print_r($key);
                // handle keyword term
                if($key === 'keyword')
                {
                    $seenKeyword = true;
                    $keywordValue = $value->filters->data;
                    $this->returnData['search_term'] = $keywordValue;
                    if($searchTypeName && $keywordValue)
                    {
                        $this->returnData['search'] = true;
                    }
                    if($seenKeyword && $seenSearchType)
                    {
                        $keywordValue = $this->cleanKeywordValue($searchTypeName, $keywordValue);
                        $searchArgs['search_field' . $count++] = $this->toFilterArray($searchTypeName, intval($searchTypeOperator), $keywordValue);
                    }
                }
                // handle search types
                else if($key === 'searchType')
                {
                    $seenSearchType = true;
                    $searchTypeName = $value->filters->fltr_id;
                    $searchTypeOperator = $value->filters->oper_id;
                    $this->returnData['search_type'] = $value->filters->fltr_id;
                    if($searchTypeName && $keywordValue)
                    {
                        $this->returnData['search'] = true;
                    }
                    if($seenKeyword && $seenSearchType)
                    {
                        $keywordValue = $this->cleanKeywordValue($searchTypeName, $keywordValue);
                        $searchArgs['search_field' . $count++] = $this->toFilterArray($searchTypeName, intval($searchTypeOperator), $keywordValue);
                    }
                }
                else if($key === 'org')
                {
                    if($value->filters->fltr_id)
                    {
                        $contactData = true;
                        $searchArgs['search_field' . $count++] = $this->toFilterArray(strval($value->filters->fltr_id),
                            intval($value->filters->oper_id),
                            strval($value->filters->val) ? $value->filters->val : $value->filters->data);
                    }
                    else
                    {
                        continue;
                    }
                }
                else if($key === 'pc')
                {
                    $valArray = $value->filters->data;
                    $val = null;
                    if(is_array($valArray) && $valArray[0] !== null)
                    {
                        $val = end($valArray[0]);
                    }
                    else if(is_string($valArray))
                    {
                        $data = explode(',', $valArray);
                        $val = end($data);
                    }
                    $searchArgs['search_field' . $count++] = $this->toFilterArray($value->filters->fltr_id, $value->filters->oper_id, $val ?: ANY_FILTER_VALUE);
                }
                else
                {
                    $vals = "";
                    $values = $value->filters->data;
                    if(count($values))
                    {
                        if(!is_array($values))
                        {
                            $values = array($values);
                        }

                        foreach($values as $k => $v)
                        {
                            if($value->filters->rnSearchType === 'menufilter')
                            {
                                $size = count($v);
                                if((int)$v === -1 || (int)$v[0] === -1) {
                                    //construct the filter value for "No Value"
                                    $vals = '1.u0';
                                    break;
                                }
                                else if(is_array($v) && $v[$size - 1] && $v[$size - 1] > 0)
                                {
                                    $vals .= "{$size}." . $v[$size - 1] . ";";
                                }
                                else if(is_array($v))
                                {
                                    for($i = $size - 1; $i >= 0; $i--)
                                    {
                                        if($v[$i] != null && $v[$i] != "")
                                        {
                                            $vals .= ($i + 1) . "." . $v[$i] . ";";
                                            break;
                                        }
                                    }
                                }
                                else if(is_string($v))
                                {
                                    $temp = explode(',', $v);
                                    $s = count($temp);
                                    $last = 0;
                                    $num = 0;
                                    for($i = 0; $i < $s; $i++)
                                    {
                                        if($temp[$i])
                                        {
                                            $last = $temp[$i];
                                            $num = $i + 1;
                                        }
                                    }
                                    if($last > 0)
                                    {
                                        $vals .= "$num.$last;";
                                    }
                                }
                                else if($v)
                                {
                                    foreach($v as $node => $data)
                                    {
                                        if($node == '0')
                                        {
                                            if(is_string($data))
                                                $data = explode(',', $data);
                                            $s = count($data);
                                            $last = $num = 0;
                                            for($i = 0; $i < $s; $i++)
                                            {
                                                if($data[$i])
                                                {
                                                    $last = $data[$i];
                                                    $num = $i + 1;
                                                }
                                            }
                                            if($last > 0)
                                            {
                                                $vals .= "$num.$last;";
                                            }
                                        }
                                    }
                                }
                                // error check for bad data
                                $temp = explode('.', $vals);
                                if(!intval($temp[0]) || !intval($temp[1]))
                                    $vals = null;
                            }
                            else
                            {
                                $vals = ($v->fltr_id || $v->oper_id || $v->val) ? $v->val : $v;
                            }
                        }
                        if($vals || $vals === '0')
                        {
                            $searchArgs['search_field' . $count++] = $this->toFilterArray($value->filters->fltr_id, $value->filters->oper_id, $vals);
                        }
                        else
                        {
                            $searchArgs['search_field' . $count++] = $this->toFilterArray($value->filters->fltr_id, $value->filters->oper_id, ANY_FILTER_VALUE);
                        }
                    }
                    else
                    {
                        $searchArgs['search_field' . $count++] = $this->toFilterArray($value->filters->fltr_id, $value->filters->oper_id, ANY_FILTER_VALUE);
                    }
                }
            }
        }

	    ######################################################################################
	    #########################Custom Incident filter ######################################
	    ######################################################################################

        $searchArgs=array();
        $ci=&get_instance();
        $profile=$ci->session->getProfile();
        $org_id=$profile->org_id->value;
        $c_id=$profile->c_id->value;
		$Uri_result=explode('/',$_SERVER['REQUEST_URI']);

		#########################################################################
		#########################Advanced Search Filter#########################
		#########################################################################

		if($Uri_result[2]=='customer_feedback')
		{

			$srch=$ci->session->getSessionData('search_text');
			$status_id=$ci->model('custom/CustomerFeedbackSystem')->getStatusIdByStatusName($srch);
      $report_id=100089;

			if($ci->session->getSessionData('complaint_filter_individual')=='org_id' && $org_id){
					$searchArgs['search_field0']=array("name"=>"contacts.org_id","oper"=>1,"val"=>$org_id);
			}
			if($ci->session->getSessionData('complaint_filter_individual')=="c_id" && $c_id){
					$searchArgs['search_field0']=array("name"=>"contacts.c_id","oper"=>1,"val"=>$c_id);
			}

			if(!empty($srch) && $ci->session->getSessionData('complaint_filter')=='search_text')
			{
				$columns=array('Contact Name','Sold To Customer','Ship To Customer Name','Subject','Status','Product Line Item','Plant','Reference Number','Material Description','Batch');
				$counter=1;
				foreach($columns as $column):
				$filter=$this->getFilterByName($report_id,$column);
				$val=($column=="Status")?$status_id:$srch;
				$searchArgs['search_field'.$counter]=array("name"=>$filter->result['fltr_id'],"oper"=>7,"val"=>"%$val%");
				$counter++;
				endforeach;
			}

		}

			if($Uri_result[2]=='supplier_feedback')
			{
				$srch=$ci->session->getSessionData('search_text');
				$status_id=$ci->model('custom/CustomerFeedbackSystem')->getStatusIdByStatusName($srch);
        $report_id=100088;

				if($ci->session->getSessionData('complaint_filter_individual')=='org_id' && $org_id){
            $searchArgs['search_field0']=array("name"=>"contacts.org_id","oper"=>1,"val"=>$org_id);
        }
        if($ci->session->getSessionData('complaint_filter_individual')=="c_id" && $c_id){
            $searchArgs['search_field0']=array("name"=>"contacts.c_id","oper"=>1,"val"=>$c_id);
        }

				if(!empty($srch) && $ci->session->getSessionData('complaint_filter')=="search_text")
				{

          $columns=array('Coordinator','Subject','Supplier Name','Subject','Status','PO Number','Plant','Reference Number');
          $counter=1;
          foreach($columns as $column):
      		$filter=$this->getFilterByName($report_id,$column);
          $val=($column=="Status")?$status_id:$srch;
          $searchArgs['search_field'.$counter]=array("name"=>$filter->result['fltr_id'],"oper"=>7,"val"=>"%$val%");
          $counter++;
          endforeach;
				}
			}

			if($Uri_result[2]=='investigations')
			{

				$srch=$ci->session->getSessionData('search_text');
				$status_id=$ci->model('custom/CustomerFeedbackSystem')->getStatusIdByStatusName($srch);
				$report_id=100012;
				//echo $status_id;
				if(1){
					$status_id=$ci->model('custom/CustomerFeedbackSystem')->getStatusIdByStatusName('closed'); //2, Closed Status
				  $searchArgs=array();
					$filter=$this->getFilterByName($report_id,'Status');
				  $searchArgs['search_field0']=array("name"=>"incidents.c_id","oper"=>1,"val"=>$c_id);
				  $searchArgs['search_field1']=array("name"=>$filter->result['fltr_id'],"oper"=>7,"val"=>"%$status_id%"); //status.
				}

				if(!empty($srch) && $ci->session->getSessionData('complaint_filter')=='search_text')
				{
          $columns=array('Subject','Reference Number','Customer Name','Status','Category');
          $counter=2;
          foreach($columns as $column):
      		$filter=$this->getFilterByName($report_id,$column);
          $val=($column=="Status")?$status_id:$srch;
          $searchArgs['search_field'.$counter]=array("name"=>$filter->result['fltr_id'],"oper"=>7,"val"=>"%$val%");
          $counter++;
          endforeach;
				}
			}

			if($Uri_result[2]=='action_items')
			{
				//Show the list just created by him.
				$srch=$ci->session->getSessionData('search_text');
				$status_id=$ci->model('custom/CustomerFeedbackSystem')->getStatusIdByStatusName($srch);
				$report_id=100041;

				if($ci->session->getSessionData('complaint_filter_individual')=='org_id' && $org_id){
						$searchArgs['search_field0']=array("name"=>"contacts.org_id","oper"=>1,"val"=>$org_id);
				}
				if($ci->session->getSessionData('complaint_filter_individual')=="c_id" && $c_id){
						$searchArgs['search_field0']=array("name"=>"contacts.c_id","oper"=>1,"val"=>$c_id);
				}

				if($ci->session->getSessionData('complaint_status_filter')){
					  $filter=$this->getFilterByName($report_id,'Status');
						$searchArgs['search_field1']=array("name"=>$filter->result['fltr_id'],"oper"=>1,"val"=>$ci->session->getSessionData('complaint_status_filter'));
				}

				if(!empty($srch) && $ci->session->getSessionData('complaint_filter')=='search_text')
				{
          $columns=array('Description','ID','Category','Group','Contact','Organization','Status');
          $counter=2;
          foreach($columns as $column):
      		$filter=$this->getFilterByName($report_id,$column);
          $val=($column=="Status")?$status_id:$srch;
          $searchArgs['search_field'.$counter]=array("name"=>$filter->result['fltr_id'],"oper"=>7,"val"=>"%$val%");
          $counter++;
          endforeach;
				}
			}

		   //$this->d($status_id);
       //$this->d($searchArgs);

        return $searchArgs;
    }



	public function d($data){
		echo "<pre>";
		print_r($data);
		echo "</pre>";
	}


	public function setSortArgsColumn (array $filters, $colID, $sortOrder = null, $sortDirection = null) {
        if (is_object($filters["sort_args"])) {
            if ($filters["sort_args"]->filters->data->col_id) {
                $target = $filters["sort_args"]->filters->data;
            }
            else if ($filters["sort_args"]->filters->col_id) {
                $target = $filters["sort_args"]->filters;
            }
            if ($target) {
                $target->col_id = $colID;
                if ($sortOrder !== null) {
                    $target->sort_order = $sortOrder;
                }
                if ($sortDirection !== null) {
                    $target->sort_direction = $sortDirection;
                }
            }
        }
        else {
            $filters["sort_args"]["filters"]["col_id"] = $colID;
            if ($sortOrder !== null) {
                $filters["sort_args"]["filters"]["sort_order"] = $sortOrder;
            }
            if ($sortDirection !== null) {
                $filters["sort_args"]["filters"]["sort_direction"] = $sortDirection;
            }
        }
        return $filters;
    }


}
