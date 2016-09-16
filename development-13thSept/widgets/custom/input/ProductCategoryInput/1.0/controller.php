<?php
namespace Custom\Widgets\input;

use RightNow\Utils\Url,
    RightNow\Utils\Text;

class ProductCategoryInput extends \RightNow\Widgets\ProductCategoryInput {
    function __construct($attrs) {
        parent::__construct($attrs);
    }

    function getData() {

        #return parent::getData();

        if (parent::getData() === false) return false;

        if($this->data['attrs']['set_button']) {
            $this->data['js']['f_tok'] = \RightNow\Utils\Framework::createTokenWithExpiration(0);
        }

        $this->data['js']['table'] = $this->table;
        $dataType = $this->data['js']['data_type'] = (Text::stringContains(strtolower($this->fieldName), 'prod'))
            ? self::PRODUCT
            : self::CATEGORY;
        $isProduct = ($dataType === self::PRODUCT);
        $this->data['js']['readableProdcatIds'] = $this->data['js']['readableProdcatIdsWithChildren'] = $this->data['js']['permissionedProdcatList'] = $this->data['js']['permissionedProdcatIds'] = array();

        if ($this->data['js']['data_type'] === self::CATEGORY) {
            $this->data['attrs']['label_all_values'] =
                ($this->data['attrs']['label_all_values'] === \RightNow\Utils\Config::getMessage(ALL_PRODUCTS_LBL))
                ? \RightNow\Utils\Config::getMessage(ALL_CATEGORIES_LBL)
                : $this->data['attrs']['label_all_values'];

            $this->data['attrs']['label_input'] =
                ($this->data['attrs']['label_input'] === \RightNow\Utils\Config::getMessage(PRODUCT_LBL))
                ? \RightNow\Utils\Config::getMessage(CATEGORY_LBL)
                : $this->data['attrs']['label_input'];

            $this->data['attrs']['label_nothing_selected'] =
                ($this->data['attrs']['label_nothing_selected'] === \RightNow\Utils\Config::getMessage(SELECT_A_PRODUCT_LBL))
                ? \RightNow\Utils\Config::getMessage(SELECT_A_CATEGORY_LBL)
                : $this->data['attrs']['label_nothing_selected'];
        }

        if ($this->data['js']['table'] === 'Contact') {
            echo $this->reportError(sprintf(\RightNow\Utils\Config::getMessage(NO_LONGER_SUPPORTED_PART_ATTRIBUTE_MSG), 'Contact', 'Object', 'name'));
            return false;
        }

        if (!in_array($this->dataType, array('ServiceProduct', 'ServiceCategory'))) {
            echo $this->reportError(sprintf(\RightNow\Utils\Config::getMessage(DATA_TYPE_PCT_S_APPR_PROD_S_CAT_MSG), $this->fieldName));
            return false;
        }

        if($this->data['attrs']['required_lvl'] > $this->data['attrs']['max_lvl']) {
            echo $this->reportError(sprintf(\RightNow\Utils\Config::getMessage(VAL_PCT_S_EXCEEDS_PCT_S_PCT_S_SET_MSG), "required_lvl", "max_lvl", "max_lvl", "required_lvl", $this->data['attrs']['required_lvl']));
            $this->data['attrs']['max_lvl'] = $this->data['attrs']['required_lvl'];
        }

        if($this->data['attrs']['hint'] && strlen(trim($this->data['attrs']['hint']))){
            $this->data['js']['hint'] = $this->data['attrs']['hint'];
        }

        $this->data['js']['linkingOn'] = $this->data['attrs']['linking_off'] ? 0 : $this->CI->model('Prodcat')->getLinkingMode();
        $this->data['js']['hm_type'] = $isProduct ? HM_PRODUCTS : HM_CATEGORIES;

        // Build up a tree of the default data set given a default chain. If there is not a default chain and linking
        // is off, just return the top level products or categories. If linking is on and this is the category
        // widget, return all of the linked categories.
        $maxLevel = $this->data['attrs']['max_lvl'];
        $defaultChain = $this->getDefaultChain();
        if($this->data['js']['linkingOn'] && !$isProduct) {
            $defaultProductID = $this->CI->model('Prodcat')->getDefaultProductID() ?: null;
            $this->data['js']['link_map'] = $defaultHierMap = $this->CI->model('Prodcat')->getFormattedTree($dataType, $defaultChain, true, $defaultProductID, $maxLevel)->result;
            $this->data['js']['hierDataNone'] = $this->CI->model('Prodcat')->getFormattedTree($dataType, array(), true, null, $maxLevel)->result;
            array_unshift($this->data['js']['hierDataNone'][0], array('id' => 0, 'label' => $this->data['attrs']['label_all_values']));
            array_unshift($this->data['js']['link_map'][0], array('id' => 0, 'label' => $this->data['attrs']['label_all_values']));
        }
        else {
            if($isProduct) {
                $this->CI->model('Prodcat')->setDefaultProductID(end($defaultChain));
				$this->data['js']['hierData']=$this->FilterByOrgId($this->data['js']['hierData']);
            }

            $defaultHierMap = $this->CI->model('Prodcat')->getFormattedTree($dataType, $defaultChain, false, null, $maxLevel)->result;

        }

        if($this->data['attrs']['verify_permissions'] !== 'None') {
            $permissionMethod = 'getPermissionedListSocialQuestion' . $this->data['attrs']['verify_permissions'];
            $permissionedHierarchy = $this->CI->model('Prodcat')->$permissionMethod($isProduct)->result;
            //Not permissioned to view any prodcats
            if(is_null($permissionedHierarchy))
                return false;

            if(is_array($permissionedHierarchy)) {
                $this->data['js']['permissionedProdcatList'] = $permissionedHierarchy;
                $this->data['js']['permissionedProdcatIds'] = $this->buildListOfPermissionedProdcatIds();
                list($this->data['js']['readableProdcatIds'], $this->data['js']['readableProdcatIdsWithChildren']) = $this->getProdcatInfoFromPermissionedHierarchies($this->data['js']['permissionedProdcatList']);
                $defaultHierMap = $this->pruneEmptyPaths($defaultHierMap, $defaultChain);
                if($this->data['js']['linkingOn'] && !$isProduct && $this->data['js']['hierDataNone']) {
                    $this->data['js']['hierDataNone'] = $this->pruneEmptyPaths($this->data['js']['hierDataNone']);
                }
                $this->updateProdcatsForReadPermissions($defaultHierMap, $this->data['js']['readableProdcatIds'], $this->data['js']['readableProdcatIdsWithChildren']);
                if((!empty($this->data['js']['readableProdcatIds'])) && $this->data['attrs']['required_lvl'] === 0) {
                    $this->data['attrs']['required_lvl'] = 1;
                }
            }
        }

        //travis.cable - CP3 Migration - filter list of items
	    if(!empty($this->data['attrs']['only_display']))
	    {
    	    $filterList = $this->_getFilterList();

            $defaultHierMapFiltered[0] = array();

            foreach($defaultHierMap[0] as $value)
            {
    			if($this->_isInList($value['id'], $filterList))
    			{
    				array_push($defaultHierMapFiltered[0], $value);
    			}
            }

            //Add in the all values label
            array_unshift($defaultHierMapFiltered[0], array('id' => 0, 'label' => $this->data['attrs']['label_all_values']));
            $this->data['js']['hierData'] = $defaultHierMapFiltered;
	    }
	    else
	    {
            // Add in the all values label
            array_unshift($defaultHierMap[0], array('id' => 0, 'label' => $this->data['attrs']['label_all_values']));
            $this->data['js']['hierData'] = ($isProduct)?$this->FilterByOrgId($defaultHierMap):$defaultHierMap;
	    }
    }

    /**
     * Overridable methods from ProductCategoryInput:
     */
    // protected function getDefaultChain()
    // protected function pruneEmptyPaths(array $hierMap, array $defaultChain = array())
    // protected function buildListOfPermissionedProdcatIds()
    // protected function getProdcatInfoFromPermissionedHierarchies(array $prodcatHierarchies)
    // protected function updateProdcatsForReadPermissions(array &$prodcats, array $readableProdcatIds, array $readableProdcatIdsWithChildren)

    /**
     * New methods:
     */

    private function FilterByOrgId($hierData){
    	$ci=&get_instance();
		$op_link=$ci->model('custom/CustomerFeedbackSystem')->getProdOrgLinking();
		$profile=$ci->session->getProfile();
		$org_id=$profile->org_id->value;

		$hs=sizeof($hierData);
		$newHierData=array();
    if(sizeof($op_link[$org_id])):
  		for($i=0;$i<$hs;$i++):
    			foreach($hierData[$i] as $hd):
    				if(in_array($hd['id'],$op_link[$org_id]))
    				$newHierData[$i][]=$hd;
    			endforeach;
  		endfor;
  endif;

  // echo "<pre>";
  // print_r($hierData);
  // echo "</pre>";

  if(!sizeof($newHierData)){
    $newHierData=array();
    $newHierData[0][0]=array('id'=>"","label"=>"No Products Found !");
  }

		return $newHierData;
    }
	private function _getFilterList()
	{
		$retList = array();

		$onlyDisplay = $this->data['attrs']['only_display'];

		if ($onlyDisplay === null || $onlyDisplay === '')
		   return $retList;

        $dataType = $this->data['js']['data_type'] = (Text::stringContains(strtolower($this->fieldName), 'prod'))
            ? self::PRODUCT
            : self::CATEGORY;

        if($dataType === self::PRODUCT)
        {
			$retList = split(",", $onlyDisplay);
		}
		else if($dataType === self::CATEGORY)
		{
			$displayIds = split(",", $onlyDisplay);
			foreach($displayIds as $idItem)
			{
                $catData = $this->CI->model('Prodcat')->getLinkedCategories($idItem);

				foreach($catData->result as $catItemGroup)
				{
    				foreach($catItemGroup as $catItem)
    				{
    					$retList[] = $catItem['id'];
    				}
				}
			}
		}

		return $retList;
	}

	private function _isInList($id, $filterArray)
	{
	    $bRet = false;

		foreach($filterArray as $idItem)
		{
			if($id == $idItem)
			{
				$bRet = true;
				break;
			}
		}

		return $bRet;
	}
}
