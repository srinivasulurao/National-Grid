<?php
namespace Custom\Models;
use RightNow\Connect\v1_2 as RNCPHP;
//require_once( get_cfg_var( 'doc_root' ).'/include/ConnectPHP/Connect_init.phph' );
//initConnectAPI();
use RightNow\Utils\Connect,
    RightNow\Utils\Framework,
    RightNow\Utils\Text,
    RightNow\Utils\Config,
    RightNow\Api;

class CustomerFeedbackSystem extends \RightNow\Models\Base
{
    function __construct()
    {
        $this->IncidentSecurity();
        parent::__construct();
    }

    function getBusinessObjectInstance($package,$table,$field)
    {

        return $this->getBlank($package,$table,$field);

    }

    public function IncidentSecurity(){
        $i_id=getUrlParm('i_id');
        if($i_id):
            $incident = RNCPHP\Incident::fetch($i_id);
      			$incident_org_id=$incident->Organization->ID;
      			$incident_c_id=$incident->PrimaryContact->ID;
            $closer_c_id=0;

            $ci=&get_instance();
            $profile=$ci->session->getProfile();
            $profile_org_id=$profile->org_id->value;
			      $profile_c_id=$profile->c_id->value;

            foreach($incident->OtherContacts as $q):
              if($profile_c_id==$q->ID){
                $closer_c_id=$q->ID;
              }
            endforeach;


            $view_page=(int)substr_count($_SERVER['REQUEST_URI'],"view");
            $edit_page=(int)substr_count($_SERVER['REQUEST_URI'],"update");

            if($incident_c_id!=$profile_c_id && $edit_page){
                @header("Location:/app/error/error_id/4");
            }
            if($view_page && ($incident_org_id!=$profile_org_id && !$closer_c_id)){
              @header("Location:/app/error/error_id/4");
            }

        endif;

    }

    function getBusinessObjectField($package,$table,$field)
    {

        $middleLayerObject = $this->getBusinessObjectInstance($package,$table,$field);

        if($middleLayerObject === null)
            return null;

        if(substr($field, 0,3)=='cf_')
        {
            $cfField = substr($field,3);
            return $middleLayerObject->$cfField;
        }

        return $middleLayerObject->$field;
    }

    function getBlank($package,$table,$field)
    {
        try
        {
            $objIn = "RightNow\\Connect\\v1_2"."\\".$package."\\".$table;
            $custObj = new $objIn();
            if(substr($field, 0,3)=='cf_')
            {
                $cField = substr($field,3);
            }
            else
            {
                $cField = $field;
            }

            $this->formatOrgs(&$custObj ,$package,$table,$cField);
            $this->getBlankCF(&$custObj ,$package,$table,$cField);
        }
        catch (Exception $err )
        {
            return $err->getMessage();
        }

        return $custObj ;
    }

    function getBlankCF($Obj,$package,$table,$field)
    {
        try
        {
            $objIn = "RightNow\\Connect\\v1_2"."\\".$package."\\".$table;
            $cObj = $objIn::getMetadata();
            $custFieldsTypeName = $cObj->type_name;
            $custFieldsMetaData = $custFieldsTypeName::getMetadata();
            $customFields = array();
            foreach($custFieldsMetaData as $x){

                if($x->name ==  $field)
                {

                    $Obj->$field->data_type = $x->COM_type;
                    $Obj->$field->value = NULL;
                    $Obj->$field->default_value = $x->default;
                    $i=0;
                    while($z = $x->constraints[$i++])
                    {
                        if($z->kind == 4)// max_length
                            $Obj->$field->field_size = $z->value;
                    }
                    $Obj->$field->lang_name = $x->label;
                    $Obj->$field->required = $x->is_required_for_create;
                    $Obj->$field->readonly = $x->is_read_only_for_create;

                }
            }

        }
        catch (Exception $err )
        {
            return $err->getMessage();
        }
    }

    function getcontact($cid)
    {

        $contact = RNCPHP\Contact::fetch(intval($cid));
        $contact_name=$contact->Name->First." ".$contact->Name->Last;
        return $contact_name;

    }

    function getsupplier($table,$id)
    {

        if($id==0)
        {return ""; exit;}
        $qry = "RightNow\\Connect\\v1_2\\CFS\\$table";
        $action = $qry ::fetch(intval($id));
        return($action->Name);

    }

    function formatOrgs($custObj,$package,$table,$field)
    {
        try
        {
            $objIn = "RightNow\\Connect\\v1_2"."\\".$package."\\".$table;
            $custObj_meta = $objIn::getMetadata();

            //exit;
            $custObj->name->data_type = $custObj_meta->$field->COM_type;
            //echo"<pre>";
            //print_r( $custObj->name->data_type);
            $custObj->name->default_value = $custObj_meta->$field->default;
            /* echo"<pre>";
             print_r( $custObj->name->default_value);*/
            $i=0;
            while($x = $custObj_meta->$field->constraints[$i++])
            {
                if($x->kind == 4)// max_length
                    $custObj->name->field_size = $x->value;
            }
            $custObj->name->lang_name = $custObj_meta->$field->label;
            $custObj->name->required = $custObj_meta->$field->is_required_for_create;
            $custObj->name->readonly = $custObj_meta->$field->is_read_only_for_create;
            $custObj->name->value = NULL;
        }
        catch (Exception $err )
        {
            return $err->getMessage();
        }

    }
    function getExistingType($package,$table)
    {

        try
        {

            $qry = "RightNow\\Connect\\v1_2\\".$package."\\$table";

            $items = RNCPHP\ConnectAPI::getNamedValues($qry);

            if($items){
                foreach ($items as $item) {
                    $menuItems[$item->ID] = $item->LookupName ?: $item->Name;
                }
            }

        }
        catch ( RNCPHP_CO\ConnectAPIError $err )
        {

            $data['exception'] = $err->getMessage();
        }

        return $menuItems;
    }

    function  getdatavalues($package,$table,$field,$id)
    {
        if($id):
        $qry = "RightNow\\Connect\\v1_2\\".$package."\\$table";
        $action = $qry ::fetch($id);
        // $action = RNCPHP\CFS\ActionItem::fetch($id);

        return $action->$field;
      endif;

    }

    function  GetTargetDate($id)
    {

        $result = RNCPHP\ROQL::query("select CustomFields.c.target_date from Incident where id=$id limit 0,1")->next();
        while($supplierdata = $result->next())
        {
            $supplierdata['target_date']=str_replace('Z','',$supplierdata['target_date']);
            return strtotime($supplierdata['target_date']);

        }

    }

    function  getsuppliervalues($id)
    {
        $result = RNCPHP\ROQL::query("select CustomFields.CFS.Supplier as Supplier from Incident where id=$id limit 0,1")->next();
        while($supplierdata = $result->next())
        {

            return $supplierdata['Supplier'];

        }


    }
    /* Create supplier complaint */
    function SupplierCompliantCreation($formData, $listOfUpdateIDs = array(), $smartAssistant = false)
    {
        $formData = $this->processFields($formData, $presentFields);

        if(!empty($formData))
        {
            try{

                $profile = $this->CI->session->getProfile();
                $cid=$profile->c_id->value;
                $org_id=$profile->org_id->value;

                $incident = new RNCPHP\Incident();
                $incident->Subject = $formData['Incident.Subject']->value;
                $incident->PrimaryContact = RNCPHP\Contact::fetch($cid);
                $incident->CustomFields->c->complaint_type=intval($formData['Incident.CustomFields.c.complaint_type']->value);

                $incident->CustomFields->c->request_type=intval($formData['Incident.CustomFields.c.request_type']->value);
                $incident->CustomFields->CFS->Supplier=intval($formData['Name']->value);
                //$incident->CustomFields->c->DeliveryShipToCustomerName=$formData['DeliveryShipToCustomerName']->value;
                //$incident->PrimaryContact = RNCPHP\Contact::fetch($contact->ID);
                $incident->CustomFields->c->request_type=intval($formData['Incident.CustomFields.c.request_type']->value);
                $incident->CustomFields->c->supplier_order_number=$formData['Incident.CustomFields.c.supplier_order_number']->value;
                $incident->CustomFields->c->proposed_solution=$formData['Incident.CustomFields.c.proposed_solution']->value;
                $duedate=strtotime($formData['c$target_date']->value);   

                if($org_id){
                  $incident->Organization = RNCPHP\Organization::fetch($org_id);
                }
                if($duedate)
                {
                    $incident->CustomFields->c->target_date=$duedate;
                }
                $incident->Threads = new RNCPHP\ThreadArray();
                $incident->Threads[0] = new RNCPHP\Thread();
                $incident->Threads[0]->EntryType = new RNCPHP\NamedIDOptList();
                $incident->Threads[0]->EntryType->ID = 3; // Used the ID here. See the Thread object for definition
                $incident->Threads[0]->Text =$formData['Incident.Threads']->value;
                $incident->Delivery=1;


                $value = $formData['Incident.FileAttachments']->value;
                if($value)
                {
                    $i=0;
                    $incident->FileAttachments  = new RNCPHP\FileAttachmentIncidentArray();
                    foreach($formData["Incident.FileAttachments"] as $k => $file)
                    {

                        if($file[$i]->contentType)
                        {
                            $incident->FileAttachments[$i] = new RNCPHP\FileAttachmentIncident();
                            $incident->FileAttachments[$i]->ContentType = $file[$i]->contentType;
                            $incident->FileAttachments[$i]->FileName  = $file[$i]->userName;
                            $incident->FileAttachments[$i]->Data   = $file[$i]->localName;
                            $incident->FileAttachments[$i]->Private =(INT) 0;
                            $i++;
                        }
                    }
                }

                $incident->save();
                RNCPHP\ConnectAPI::commit();

                $arr['result']['transaction']['incident']['key']="i_id";
                $arr['result']['transaction']['incident']['value']=$incident->ID;
                $arr['result']['sessionParam']="/";
                //sessionParam
            }
            catch (Exception $err ){
                echo $err->getMessage();
            }

        }

        return json_encode($arr);


    }

    /* Edit supplier complaint */
    function SupplierCompliantEdit($formData, $listOfUpdateIDs = array(), $smartAssistant = false,$supplier_id)
    {
        $formData = $this->processFields($formData, $presentFields);

        if(!empty($formData))
        {
            try{

                $profile = $this->CI->session->getProfile();
                $cid=$profile->c_id->value;
                $incident = RNCPHP\Incident::fetch($supplier_id);
                $incident->Subject = $formData['Incident.Subject']->value;
                //$incident->PrimaryContact = RNCPHP\Contact::fetch($cid);
                $incident->CustomFields->c->complaint_type=intval($formData['Incident.CustomFields.c.complaint_type']->value);

                //$incident->CustomFields->CFS->Supplier=intval($formData['Name']->value);
                //$incident->CustomFields->c->DeliveryShipToCustomerName=$formData['DeliveryShipToCustomerName']->value;
                //$incident->PrimaryContact = RNCPHP\Contact::fetch($contact->ID);
                //$incident->CustomFields->c->request_type=intval($formData['Incident.CustomFields.c.request_type']->value);
                $incident->CustomFields->c->supplier_order_number=$formData['Incident.CustomFields.c.supplier_order_number']->value;
                $incident->CustomFields->c->proposed_solution=$formData['Incident.CustomFields.c.proposed_solution']->value;
                if($formData['Incident.CustomFields.c.complaint_resolved']->value):
                $incident->CustomFields->c->complaint_resolved=(int)$formData['Incident.CustomFields.c.complaint_resolved']->value;
                $incident->StatusWithType->Status=2;
                endif;

                $duedate=strtotime($formData['c$target_date']->value);

                if($duedate)
                {
                    $incident->CustomFields->c->target_date=$duedate;
                }
                if($formData['Incident.Threads']->value)
                {
                    $incident->Threads = new RNCPHP\ThreadArray();
                    $incident->Threads[0] = new RNCPHP\Thread();
                    $incident->Threads[0]->EntryType = new RNCPHP\NamedIDOptList();
                    $incident->Threads[0]->EntryType->ID = 3; // Used the ID here. See the Thread object for definition
                    $incident->Threads[0]->Text =$formData['Incident.Threads']->value;
                }


                $value = $formData['Incident.FileAttachments']->value;
                if($value)
                {
                    $i=0;
                    $incident->FileAttachments  = new RNCPHP\FileAttachmentIncidentArray();
                    foreach($formData["Incident.FileAttachments"] as $k => $file)
                    {

                        if($file[$i]->contentType)
                        {
                            $incident->FileAttachments[$i] = new RNCPHP\FileAttachmentIncident();
                            $incident->FileAttachments[$i]->ContentType = $file[$i]->contentType;
                            $incident->FileAttachments[$i]->FileName  = $file[$i]->userName;
                            $incident->FileAttachments[$i]->Data   = $file[$i]->localName;
                            $incident->FileAttachments[$i]->Private =(INT) 0;
                            $i++;
                        }
                    }
                }

                $incident->save();
                RNCPHP\ConnectAPI::commit();

                $arr['result']['transaction']['incident']['key']="i_id";
                $arr['result']['transaction']['incident']['value']=$incident->ID;
                $arr['result']['sessionParam']="/";
                //sessionParam
            }
            catch (Exception $err ){
                echo $err->getMessage();
            }

        }

        return json_encode($arr);


    }


    function CustomerCompliantCreation($data){

        $arr=array();
        $formData = $this->processFields($data, $presentFields);

        try{

            $profile = $this->CI->session->getProfile();
            $cid=$profile->c_id->value;
            $org_id=$profile->org_id->value;

            $incident = new RNCPHP\Incident();

            if($formData['Incident.Subject']->value)
                $incident->Subject = $formData['Incident.Subject']->value;
            $incident->PrimaryContact = RNCPHP\Contact::fetch($cid);
            if($formData['Incident.CustomFields.c.complaint_type']->value)
                $incident->CustomFields->c->complaint_type=intval($formData['Incident.CustomFields.c.complaint_type']->value);
			     if($formData['Incident.Product']->value)
                $incident->Product=intval($formData['Incident.Product']->value);
            if($formData['Incident.Category']->value)
                $incident->Category=intval($formData['Incident.Category']->value);
            if($formData['Incident.CustomFields.c.product_returned']->value)
                $incident->CustomFields->c->product_returned=intval($formData['Incident.CustomFields.c.product_returned']->value);
            if($formData['Incident.CustomFields.c.product_sample_taken']->value)
                $incident->CustomFields->c->product_sample_taken=intval($formData['Incident.CustomFields.c.product_sample_taken']->value);
            if($formData['Incident.CustomFields.c.product_sample_returned_to']->value)
                $incident->CustomFields->c->product_sample_returned_to=$formData['Incident.CustomFields.c.product_sample_returned_to']->value;
            if($formData['Incident.CustomFields.c.request_type']->value)
                $incident->CustomFields->c->request_type=intval($formData['Incident.CustomFields.c.request_type']->value);
            if($formData['Incident.CustomFields.c.draft']->value):
                $incident->CustomFields->c->draft=intval($formData['Incident.CustomFields.c.draft']->value);
                $incident->CustomFields->c->draft_opted=intval($formData['Incident.CustomFields.c.draft']->value);
                $incident->StatusWithType->Status=108; //Save as a draft option
            endif;
            if($formData['c$target_date'])
                $incident->CustomFields->c->target_date=strtotime($formData['c$target_date']->value);
			      if($formData['Incident.CustomFields.c.formal_response']->value)
                $incident->CustomFields->c->formal_response=(int)$formData['Incident.CustomFields.c.formal_response']->value;
            if($formData['CFS$Delivery']->value):
                $delivery=$this->getDelivery($formData['CFS$Delivery']->value);
                //$incident->CustomFields->c->sold_to_customer_name=$delivery['SoldToCustomerName'];
                $incident->CustomFields->CFS->Delivery=intval($delivery['ID']);
            endif;
            if($org_id){
              $incident->Organization = RNCPHP\Organization::fetch($org_id);
            }
            if($formData['Incident.CustomFields.c.sold_to_customer_name']->value)
                $incident->CustomFields->c->sold_to_customer_name=$formData['Incident.CustomFields.c.sold_to_customer_name']->value;
            if($formData['Incident.CustomFields.c.ship_to_customer_name']->value)
                $incident->CustomFields->c->ship_to_customer_name=$formData['Incident.CustomFields.c.ship_to_customer_name']->value;
            if($formData['Incident.CustomFields.c.product_no']->value)
                $incident->CustomFields->c->product_no=$formData['Incident.CustomFields.c.product_no']->value;

            if($formData['Incident.CustomFields.c.customer_contact_name']->value)
                $incident->CustomFields->c->customer_contact_name=$formData['Incident.CustomFields.c.customer_contact_name']->value;
            if($formData['Incident.CustomFields.c.customer_ph_no']->value)
                $incident->CustomFields->c->customer_ph_no=$formData['Incident.CustomFields.c.customer_ph_no']->value;
            if($formData['Incident.CustomFields.c.customer_contact_email']->value)
                $incident->CustomFields->c->customer_contact_email=$formData['Incident.CustomFields.c.customer_contact_email']->value;


            $value = $formData['Incident.FileAttachments']->value;
            if($formData['Incident.Threads']->value)
            {
                $incident->Threads = new RNCPHP\ThreadArray();
                $incident->Threads[0] = new RNCPHP\Thread();
                $incident->Threads[0]->EntryType = new RNCPHP\NamedIDOptList();
                $incident->Threads[0]->EntryType->ID = 3; // Used the ID here. See the Thread object for definition
                $incident->Threads[0]->Text =$formData['Incident.Threads']->value;
            }

            if(is_array($value) && count($value)){
                $incident->FileAttachments = new RNCPHP\FileAttachmentIncidentArray();
                foreach($value as $attachment){
                    if($tempFile =   get_cfg_var('upload_tmp_dir') . '/' . $attachment->localName) {
                        $file = $incident->FileAttachments[] = new RNCPHP\FileAttachmentIncident();
                        $file->ContentType = $attachment->contentType;
                        $file->setFile($tempFile);
                        $file->FileName = preg_replace("/[\r\n\/:*?\"<>|]+/", '_', $attachment->userName);
                    }
                }
            }

            $incident->save();
            RNCPHP\ConnectAPI::commit();

            if($formData['Incident.CustomFields.c.delivery_line_items']->value)
                $this->insertIncidentDeliveryLineItems($formData['Incident.CustomFields.c.delivery_line_items']->value,$incident->ID);   // If everything goes right,lets update the product Selection.

            $arr['result']['transaction']['incident']['key']="i_id";
            $arr['result']['transaction']['incident']['value']=$incident->ID;
            $arr['result']['sessionParam']="/";

        }
        catch(RNCPHP\ConnectAPIError $err){
            $arr['errorMessage']=$err->getMessage()."@".$err->getLine();
        }

        return json_encode($arr);
    }

    public function CustomerCompliantUpdate($data,$i_id){

        $arr=array();
        $formData = $this->processFields($data, $presentFields);
        try{
            $profile = $this->CI->session->getProfile();
            $cid=$profile->c_id->value;
            $incident = RNCPHP\Incident::fetch($i_id);

            if($formData['Incident.Subject']->value)
                $incident->Subject = $formData['Incident.Subject']->value;
            $incident->PrimaryContact = RNCPHP\Contact::fetch($cid);
            if($formData['Incident.CustomFields.c.complaint_type']->value)
                $incident->CustomFields->c->complaint_type=intval($formData['Incident.CustomFields.c.complaint_type']->value);
			if($formData['Incident.Product']->value)
                $incident->Product=intval($formData['Incident.Product']->value);
            if($formData['Incident.Category']->value)
                $incident->Category=intval($formData['Incident.Category']->value);
            if(1)
                $incident->CustomFields->c->product_returned=intval($formData['Incident.CustomFields.c.product_returned']->value);
            if(1)
                $incident->CustomFields->c->product_sample_taken=intval($formData['Incident.CustomFields.c.product_sample_taken']->value);
            if($formData['Incident.CustomFields.c.product_sample_returned_to']->value)
                $incident->CustomFields->c->product_sample_returned_to=$formData['Incident.CustomFields.c.product_sample_returned_to']->value;
            if($formData['Incident.CustomFields.c.request_type']->value)
                $incident->CustomFields->c->request_type=intval($formData['Incident.CustomFields.c.request_type']->value);
            if(1):
                $draft_id=$this->getStatusIdByStatusName('draft'); //draft status.
                $resolved_id=$this->getStatusIdByStatusName('resolved'); //Need assignment status. 
                $incident->CustomFields->c->draft=intval($formData['Incident.CustomFields.c.draft']->value);
                $incident->StatusWithType->Status=(intval($formData['Incident.CustomFields.c.draft']->value))?$draft_id:$resolved_id; //Draft.
            endif;
            if($formData['c$target_date']->value)
                $incident->CustomFields->c->target_date=strtotime($formData['c$target_date']->value);
			if($formData['Incident.CustomFields.c.formal_response']->value)
                $incident->CustomFields->c->formal_response=(int)$formData['Incident.CustomFields.c.formal_response']->value;
            if($formData['CFS$Delivery']->value):
                $delivery=$this->getDelivery($formData['CFS$Delivery']->value);
                //$incident->CustomFields->c->sold_to_customer_name=$delivery['SoldToCustomerName'];
                $incident->CustomFields->CFS->Delivery=intval($delivery['ID']);
            endif;
            if($formData['Incident.CustomFields.c.sold_to_customer_name']->value)
                $incident->CustomFields->c->sold_to_customer_name=$formData['Incident.CustomFields.c.sold_to_customer_name']->value;
            if($formData['Incident.CustomFields.c.ship_to_customer_name']->value)
                $incident->CustomFields->c->ship_to_customer_name=$formData['Incident.CustomFields.c.ship_to_customer_name']->value;
            if($formData['Incident.CustomFields.c.product_no']->value)
                $incident->CustomFields->c->product_no=$formData['Incident.CustomFields.c.product_no']->value;
            if(1)
                $this->updateIncidentDeliveryLineItems($formData['Incident.CustomFields.c.delivery_line_items']->value,$i_id);
            if($formData['Incident.CustomFields.c.complaint_resolved']->value):
            $incident->CustomFields->c->complaint_resolved=(int)$formData['Incident.CustomFields.c.complaint_resolved']->value;
            //$incident->StatusWithType->Status=2; //Complaint Closed.
            $this->resolveCustomerFeedbackAccordingly($i_id);
            endif;

            if($formData['Incident.CustomFields.c.customer_contact_name']->value)
                $incident->CustomFields->c->customer_contact_name=$formData['Incident.CustomFields.c.customer_contact_name']->value;
            if($formData['Incident.CustomFields.c.customer_ph_no']->value)
                $incident->CustomFields->c->customer_ph_no=$formData['Incident.CustomFields.c.customer_ph_no']->value;
            if($formData['Incident.CustomFields.c.customer_contact_email']->value)
                $incident->CustomFields->c->customer_contact_email=$formData['Incident.CustomFields.c.customer_contact_email']->value;

            $value = $formData['Incident.FileAttachments']->value;
            if($formData['Incident.Threads']->value)
            {
                $incident->Threads = new RNCPHP\ThreadArray();
                $incident->Threads[0] = new RNCPHP\Thread();
                $incident->Threads[0]->EntryType = new RNCPHP\NamedIDOptList();
                $incident->Threads[0]->EntryType->ID = 3; // Used the ID here. See the Thread object for definition
                $incident->Threads[0]->Text =$formData['Incident.Threads']->value;
            }


            if(is_array($value) && count($value)){
                $incident->FileAttachments = new RNCPHP\FileAttachmentIncidentArray();
                foreach($value as $attachment){
                    if($tempFile =   get_cfg_var('upload_tmp_dir') . '/' . $attachment->localName) {
                        $file = $incident->FileAttachments[] = new RNCPHP\FileAttachmentIncident();
                        $file->ContentType = $attachment->contentType;
                        $file->setFile($tempFile);
                        $file->FileName = preg_replace("/[\r\n\/:*?\"<>|]+/", '_', $attachment->userName);
                    }
                }
            }

            $incident->save();
            RNCPHP\ConnectAPI::commit();


            $arr['result']['transaction']['incident']['key']="i_id";
            $arr['result']['transaction']['incident']['value']=$incident->ID;
            $arr['result']['sessionParam']="/";

        }
        catch(RNCPHP\ConnectAPIError $err){
            $arr['errorMessage']=$err->getMessage()."@".$err->getLine();
        }

        return json_encode($arr);

    }

    public function resolveCustomerFeedbackAccordingly($parent_id){

        $parent_incident=RNCPHP\Incident::fetch($parent_id);
        $cost=(int)$parent_incident->CustomFields->c->cost;
        $formal_response=(int)$parent_incident->CustomFields->c->formal_response;
        $formal_response_completed=(int)$parent_incident->CustomFields->c->formal_response_completed;
        $allInvestigationClosed=true;
        $corrective_actions_pending=false;
        $child_incidents=array();

        ///Checking Part1.
                      $query=RNCPHP\ROQL::queryObject("SELECT Incident FROM Incident WHERE Incident.CustomFields.CFS.Incident.ID='$parent_id'")->next();
                        while($result=$query->next()):
                              if($result->StatusWithType->Status->ID!=2):
                              $allInvestigationClosed=false;
                              break;
                              endif;
                        endwhile;

                        while($result=$query->next()):
                        $child_incidents[]=$result->ID;
                        endwhile;

        //Checking Part 2.
                    
                        foreach($child_incidents as $child_incident):
                            $results=RNCPHP\ROQL::query("select count(*) as pending  from CFS.CorrectiveAction WHERE CFS.CorrectiveAction.Incident='{$child_incident}' AND CFS.CorrectiveAction.Complete='0'")->next();
                                while($result=$results->next()):
                                $corrective_actions_pending=(int)$result['pending'];
                                break;
                                endwhile;
                        endforeach;

                        if($corrective_actions_pending or !$allInvestigationClosed or !$cost or $formal_response or !$formal_response_completed):
                             
                             $parent_incident->StatusWithType->Status=106; //Resolved.
                             $parent_incident->save();
                             RNCPHP\ConnectAPI::commit();
                        endif;

                        ################### Make it Closed, #########################################
                        # 1). if the corrective Actions are remaining.
                        # 2). Cost Value is not empty.
                        # 3). Formal Response=0 & Formal Response Completed=1.
                        # 4). If all are true then you should close it.
                        ##############################################################################
                        
                        if(!$corrective_actions_pending && $allInvestigationClosed && $cost && !$formal_response && $formal_response_completed):
                             
                             $parent_incident->StatusWithType->Status=2; //closed.
                             $parent_incident->save();
                             RNCPHP\ConnectAPI::commit();
                        endif;
    }

    public function insertIncidentDeliveryLineItems($delivery_line_items,$incident_id){
        $items=explode(",",$delivery_line_items);
        foreach($items as $item){
            if($item):
                $idli=new RNCPHP\CFS\IncidentDeliveryItem();
                $idli->Incident=RNCPHP\Incident::fetch($incident_id);
                $idli->delivery_id=$item;
                $idli->save();
            endif;
        }
        RNCPHP\ConnectAPI::commit();
    }

    public function updateIncidentDeliveryLineItems($delivery_line_items,$incident_id){

        //First delete the delivery line items.
        $items=RNCPHP\ROQL::queryObject("SELECT CFS.IncidentDeliveryItem FROM CFS.IncidentDeliveryItem WHERE CFS.IncidentDeliveryItem.Incident='$incident_id'")->next();
        while($item=$items->next()) {
            $item->destroy();
        }
        RNCPHP\ConnectAPI::commit();

        //Now insert the delivery line items
        $this->insertIncidentDeliveryLineItems($delivery_line_items,$incident_id);

    }
    public function saveIncidentProductSelection($incident_id,$products_selected){

        foreach($products_selected as $delivery):
            if($delivery):
                $obj=$rma = new RNCPHP\CFS\IncidentDeliveryItem();
                $obj->incident_id=$incident_id;
                $obj->delivery_id=$delivery;
                $obj->save();
                RNCPHP\ConnectAPI::commit();
            endif;
        endforeach;

    }




    public function getDelivery($del_id){
        $sql="SELECT * from CFS.Delivery WHERE CFS.Delivery.Delivery='$del_id'";
        $sql_instance = RNCPHP\ROQL::query($sql)->next();
        while($delivery = $sql_instance->next()){
            return $delivery;
        }

    }

    public function getIncident($incident_id){
        $incident = RNCPHP\Incident::fetch($incident_id);
        return $incident;
    }

    public function getOrganizationCondition($org_id){
      $org=RNCPHP\Organization::fetch($org_id);
      if($org->Name=="CPC" or $org->Name=="cpc")
      return "CFS.Delivery.Organization<>''"; //Should not be empty.
      else
      return "CFS.Delivery.Organization='$org_id'";
    }
    public function deliveryLookUp($search_term){
        $ci=get_instance();
        $user=$ci->session->getProfile();
        $org_id=$user->org_id->value;

        if($search_term && $org_id):
           $organizationCondition=$this->getOrganizationCondition($org_id);
            $sql_instance=RNCPHP\ROQL::query("SELECT * FROM CFS.Delivery WHERE $organizationCondition AND CFS.Delivery.Delivery LIKE '%$search_term%' GROUP BY CFS.Delivery.Delivery LIMIT 0,10")->next();
            $data=array();
            $counter=0;
            while($delivery = $sql_instance->next())
            {
                $data[$counter]['id']=$delivery['ID'];
                $data[$counter]['delivery']=$delivery['Delivery'];
                $data[$counter]['sold_to_customer']=($delivery['SoldToCustomerName'])?$delivery['SoldToCustomerName']:"";
                $data[$counter]['ship_to_customer']=($delivery['ShipToCustomerName'])?$delivery['ShipToCustomerName']:"";
                $data[$counter]['prod_no']=($delivery['CustomerPONumber'])?$delivery['CustomerPONumber']:"";
                $counter++;
            }
            echo json_encode($data);
        endif;

    }

    public function deliveryDetailsLookupModel($delivery_no,$customer_po_no,$ship_to_customer,$sold_to_customer){
        $ci=get_instance();
        $user=$ci->session->getProfile();
        $org_id=$user->org_id->value;


        if(($delivery_no or $customer_po_no or $ship_to_customer or $sold_to_customer) && $org_id):
          $organizationCondition=$this->getOrganizationCondition($org_id);
            $sql="SELECT * from CFS.Delivery WHERE $organizationCondition AND (";
            if($delivery_no)
                $sql.=" OR CFS.Delivery.Delivery LIKE '%$delivery_no%' ";
            if($customer_po_no)
                $sql.=" OR CFS.Delivery.CustomerPONumber LIKE '%$customer_po_no%' ";
            if($sold_to_customer)
                $sql.=" OR CFS.Delivery.SoldToCustomerName LIKE '%$sold_to_customer%' ";
            if($ship_to_customer)
                $sql.=" OR CFS.Delivery.ShipToCustomerName LIKE '%$ship_to_customer%' OR CFS.Delivery.SoldToCustomerRegion LIKE '%$ship_to_customer%' ";

            $sql.=") ";
            $sql=str_replace("( OR","( ",$sql);

            $sql_instance = RNCPHP\ROQL::query($sql." ORDER BY CFS.Delivery.DeliveryGoodsIssueDate DESC  LIMIT 0,10")->next();
            $data="";
            $counter=0;
            while($delivery = $sql_instance->next())
            {
                $data.="<div style='border-bottom:1px solid lightgrey;'><span class='deliverable_span' style='font-size:12px;display:inline-block'><a href='javascript:void(0)' onclick=\"setDelivery('{$delivery['Delivery']}','{$delivery['SoldToCustomerName']}')\">{$delivery['Delivery']}</a></span><span class='deliverable_span'>{$delivery['SoldToCustomerName']}</span><span class='deliverable_span'>{$delivery['ShipToCustomerName']}</span><span class='deliverable_span' style='width:28% !important'>{$delivery['MaterialDescription']}</span><span class='deliverable_span'>{$delivery['DeliveryGoodsIssueDate']}</span></div>";
                $counter++;
            }
            echo ($counter)?"<h3 style='display:block;width:100%;margin-bottom:10px;'><u>Deliveries</u></h3><span class='deliverable_span dblue'>Delivery</span><span class='deliverable_span dblue'>Sold To</span><span class='deliverable_span dblue'>Ship To</span><span  class='deliverable_span dblue' style='width:28% !important'>Material Description</span><span class='deliverable_span dblue' >Delivery Date</span><br>".$data:"No Results found!";
        endif;

    }

    function deliveryLineItemListModel($delivery_id,$incident_id){

        $ci=get_instance();
        $user=$ci->session->getProfile();
        $org_id=$user->org_id->value;

        $product_selected=array();
        if($incident_id):
            $dli_sql="SELECT * from CFS.IncidentDeliveryItem WHERE CFS.IncidentDeliveryItem.Incident='$incident_id'";
            $dli=RNCPHP\ROQL::query($dli_sql)->next();
            while($delivery_item=$dli->next()):
                $product_selected[]=$delivery_item['delivery_id'];
            endwhile;
        endif;

        if($delivery_id):
            $sql="select * from CFS.Delivery WHERE CFS.Delivery.Delivery='$delivery_id' AND CFS.Delivery.Organization='{$org_id}'";
            $sql_instance = RNCPHP\ROQL::query($sql)->next();
            $data="";
            while($delivery = $sql_instance->next())
            {
                $checked=(in_array($delivery['ID'],$product_selected))?"checked='checked incident_delivery_item'":"class='non_checked incident_delivery_item'";

				$material=intval($delivery['Material']);
				$line_item_text="<tr><td><span $checked><input type='checkbox' $checked value='{$delivery['ID']}'></span></td><td>{$delivery['DeliveryLineItem']}</td><td>{$delivery['Delivery']}</td><td>{$material}</td><td>{$delivery['MaterialDescription']}</td><td>{$delivery['Batch']}</td><td>{$delivery['NetWeight']}</td></tr>";
                $data.=$line_item_text;
            }
        endif;
        if($data)
            $data="<div class='delivery_line_item_list_products'><label id='delivery_line_item_label' style='display:block !important'>Products (Having Issue)</label><table style='width:100%;font-size:14px'><tr style='background:black;color:white'><th>&nbsp<input type='checkbox' id='product_having_issue_checkall' onclick='checkAllProductsHavingIssue()'></td><th>Item</th><th>Delivery</th><th>Material</th><th>Material Description</th><th>Batch</th><th>Net Weight</th></tr>".$data."</table></div>";
        echo $data;

    }

    function getDeliveryDetailsData($delivery_no){
        $data="";
        $ci=get_instance();
        $user=$ci->session->getProfile();
        $org_id=$user->org_id->value; 
        $organizationCondition=$this->getOrganizationCondition($org_id);
        if($delivery_no){
            $sql="select * from CFS.Delivery WHERE CFS.Delivery.Delivery='$delivery_no' AND $organizationCondition LIMIT 0,10";
            $sql_instance = RNCPHP\ROQL::query($sql)->next(); 
            $data="";
            while($delivery = $sql_instance->next())
            {
                $sold_to_customer_name=($delivery['SoldToCustomerName'])?$delivery['SoldToCustomerName']:$delivery['SoldToCustomerRegion'];
                $data.="<tr><td>{$delivery['Delivery']}</td><td>{$delivery['SoldToCustomerName']}</td><td>{$delivery['ShipToCustomerName']}</td><td>{$delivery['MaterialDescription']}</td><td>{$delivery['DeliveryGoodsIssueDate']}</td></tr>";
            }
        }

        echo ($data)?"<table width='100%' class='delivery_details_table'><tr><th>Delivery</th><th>Sold To</th><th>Ship To</th><th style='width:30%'>Material Description</th><th>Delivery Date</th></tr>".$data."</table>":"Sorry, no results found !";
    }

    /* Create action item */
    function ActionItemCreation($formData, $listOfUpdateIDs = array(), $smartAssistant = false)
    {
        $formData = $this->processFields($formData, $presentFields);
        //print_r($formData);
        if(!empty($formData))
        {
            try{
                $Action_item= new RNCPHP\CFS\ActionItem();

                $Action_item->Contact = intval($formData['Contact']->value);
                $Action_item->Details = $formData['Details']->value;
                $Action_item->Description = $formData['Description']->value;
                $Action_item->Complete=0;
                $Action_item->Category = intval($formData['Category']->value);
                $completion_date=strtotime(date('Y-m-d'));
                $Action_item->CompletionDate=$completion_date;
                $duedate=strtotime($formData['DueDate']->value);
                $Action_item->DueDate = $duedate;
                $Action_item->Priority = intval($formData['Priority']->value);
                $Action_item->Status = intval($formData['Status']->value);
                $Action_item->ActionGroup=$formData['ActionGroup']->value;
                if($formData['Incident.Product']->value)
                    $Action_item->Product = intval($formData['Incident.Product']->value);
                $value = $formData['Attachments']->value;
                if(is_array($value) && count($value)){

                    $Action_item->FileAttachments = new RNCPHP\FileAttachmentArray();
                    foreach($value as $attachment){
                        if($tempFile =   get_cfg_var('upload_tmp_dir') . '/' . $attachment->localName) {
                            $file = $Action_item->FileAttachments[] = new RNCPHP\FileAttachment();
                            $file->ContentType = $attachment->contentType;
                            $file->setFile($tempFile);
                            $file->FileName = preg_replace("/[\r\n\/:*?\"<>|]+/", '_', $attachment->userName);
                        }
                    }

                }

                $response['Res']=$Action_item->save();
                RNCPHP\ConnectAPI::commit();
            }
            catch (Exception $err ){
                echo $err->getMessage();
            }

        }
        $arr['result']['transaction']['incident']['key']="iid";
        $arr['result']['transaction']['incident']['value']=$Action_item->ID;
        $arr['result']['sessionParam']="/";
        //sessionParam
        return json_encode($arr);

    }


    function ActionItemUpdate($formData, $listOfUpdateIDs = array(), $smartAssistant = false,$action_id)
    {

        $formData = $this->processFields($formData, $presentFields);

        if(!empty($formData))
        {
            try{

                $formData['ID']->value= $action_id;
                //print_r($formData); exit;
                $id=intval($formData['ID']->value);
                $Action_item= RNCPHP\CFS\ActionItem ::fetch($id);

                $Action_item->Contact = intval($formData['Contact']->value);
                $Action_item->Details = $formData['Details']->value;
                $Action_item->Description = $formData['Description']->value;
                $Action_item->Category = intval($formData['Category']->value);
                $Action_item->Complete=0;
                $completion_date=strtotime(date('Y-m-d'));

                $Action_item->CompletionDate=$completion_date;
                $duedate=strtotime($formData['DueDate']->value);
                $Action_item->DueDate = $duedate;

                $Action_item->Priority = intval($formData['Priority']->value);
                $Action_item->Status = intval($formData['Status']->value);
                $Action_item->Product = intval($formData['Incident.Product']->value);
                $Action_item->ActionGroup=$formData['ActionGroup']->value;
                $value = $formData['Attachments']->value;
                if(is_array($value) && count($value)){

                    $Action_item->FileAttachments = new RNCPHP\FileAttachmentArray();
                    foreach($value as $attachment){
                        if($tempFile =   get_cfg_var('upload_tmp_dir') . '/' . $attachment->localName) {
                            $file = $Action_item->FileAttachments[] = new RNCPHP\FileAttachment();
                            $file->ContentType = $attachment->contentType;
                            $file->setFile($tempFile);
                            $file->FileName = preg_replace("/[\r\n\/:*?\"<>|]+/", '_', $attachment->userName);
                        }
                    }

                }
                // print_r($Action_item->FileAttachments); exit;
                $response->res=$Action_item->save();
                RNCPHP\ConnectAPI::commit();

                $response->id = $Action_item->ID;
            }
            catch (Exception $err ){
                echo $err->getMessage();
            }

        }

        //return($this->getResponseObject($Action_item, 'is_object', null, $warnings));

        $arr['result']['transaction']['incident']['key']="iid";
        $arr['result']['transaction']['incident']['value']=$Action_item->ID;
        $arr['result']['sessionParam']="/";
        //sessionParam
        return json_encode($arr);



    }

    private function processFields(array $fields, &$presentFields = array()) {
        $return = array();

        foreach ($fields as $field) {
            $fieldName = $field->name;

            if (!is_string($fieldName) || $fieldName === '') continue;

            unset($field->name);
            $return[$fieldName] = $field;

            if ($objectName = strtolower(Text::getSubstringBefore($fieldName, '.'))) {
                $presentFields[$objectName] = true;
            }
        }

        return $return;
    }

    function searchsupplier($q)
    {
        $q=urldecode($q);
        $result = RNCPHP\ROQL::query("select * from CFS.Supplier where Name LIKE '$q%' limit 0,10")->next();
        echo "<ul>";
        while($supplierdata = $result->next())
        {
            // $data[] =$contactdata['LookupName']."-".$contactdata['ID'];
            ?>
            <li onclick='fill("<?php echo $supplierdata['ID']."-".$supplierdata['Name']; ?>")'><?php echo $supplierdata['Name']; ?></li>
            <?php

        }
        echo "</ul>";

    }

    function myLister()
    {
        initConnectAPI('srinivasulu','ATG2dfPQ');
        $ar= RNCPHP\AnalyticsReport::fetch(100066);
        $arr= $ar->run();
        $nrows= $arr->count();
        echo"<pre>";
        print_r($arr->next());
        echo "</pre>";
        if ( $nrows) {
            $row = $arr->next();
            // Emit the column headings
            // echo( join( ',', array_keys( $row ) ) ."<br>" );
            // Emit the rows in this report run
            for ( $ii = 0; $ii++ < $nrows; $row = $arr->next() ) {
                echo"<pre>";
                print_r($arr->next());
                echo "</pre>";
            }
        }

    }

    function soldToCustomerSuggestion($input){
        $results=RNCPHP\ROQL::query("select * from CFS.Delivery WHERE CFS.Delivery.SoldToCustomerName LIKE '%$input%' OR CFS.Delivery.SoldToCustomerRegion LIKE '%$input%'")->next();
        $data="";
        while($result=$results->next()):
            $name=($result['SoldToCustomerName'])?$result['SoldToCustomerName']:$result['SoldToCustomerRegion'];
            $data="<li onclick=\"setSoldToCustomer('$name')\" >$name</li>";
        endwhile;

        $html=($data && $input)?"<ul class='sold_to_customer_suggestion_list'>{$data}</ul>":"";
        echo $html;
    }


    function shipToCustomerSuggestion($input){
        $results=RNCPHP\ROQL::query("select * from CFS.Delivery WHERE CFS.Delivery.ShipToCustomerName LIKE '%$input%'")->next();
        $data="";
        while($result=$results->next()):
            $name=$result['ShipToCustomerName'];
            $data="<li onclick=\"setShipToCustomer('$name')\" >$name</li>";
        endwhile;

        $html=($data && $input)?"<ul class='sold_to_customer_suggestion_list'>{$data}</ul>":"";
        echo $html;
    }

    function productNoToCustomerSuggestion($input){
        $results=RNCPHP\ROQL::query("select * from CFS.Delivery WHERE CFS.Delivery.CustomerPONumber LIKE '%$input%'")->next();
        $data="";
        while($result=$results->next()):
            $name=$result['CustomerPONumber'];
            $data="<li onclick=\"setProductNoToCustomer('$name')\" >$name</li>";
        endwhile;

        $html=($data && $input)?"<ul class='sold_to_customer_suggestion_list'>{$data}</ul>":"";
        echo $html;
    }

    function searchcontact($q)
    {

        $q=urldecode($q);

        $result = RNCPHP\ROQL::query("select * from Contact where Contact.Name.first LIKE '%$q%' OR Contact.Name.last LIKE '%$q%' limit 0,10")->next();


        echo '<ul>';
        while($contactdata = $result->next())
        {
            // $data[] =$contactdata['LookupName']."-".$contactdata['ID'];
            ?>
            <li onclick='fill("<?php echo $contactdata['ID']."-".str_replace("'","&#39;",$contactdata['LookupName']); ?>")'><?php echo $contactdata['LookupName']; ?></li>
            <?php

        }
        echo "</ul>";


    }


    function getBusinessObjectFields($table,$field)
    {

        $middleLayerObject = $this->getBusinessObjectInstances($table,$field);
        if($middleLayerObject === null)
            return null;

        if(substr($field, 0,3)=='cf_')
        {
            $cfField = substr($field,3);
            return $middleLayerObject->$cfField;;
        }

        return $middleLayerObject->$field;
    }

    function getDeliveryOrderDetails($i_id){
        $incident = RNCPHP\Incident::fetch($i_id);
		$dlis=RNCPHP\ROQL::queryObject("Select CFS.IncidentDeliveryItem FROM CFS.IncidentDeliveryItem WHERE CFS.IncidentDeliveryItem.Incident='$i_id'")->next();

        // $results=array();
        // $results['SoldToCustomerName']=$incident->CustomFields->c->sold_to_customer_name;
        // $results['ShipToCustomerName']=$incident->CustomFields->c->ship_to_customer_name;
        // $results['ProductLine']=$incident->CustomFields->c->product_no;
		// $results['Category ']-$incident->Category

		$results=array();
		while($dli=$dlis->next()):
			$results[]=RNCPHP\CFS\Delivery::fetch($dli->delivery_id);
		endwhile;

    //We have to show atleast some data to the delivery Details, it can't be emty.
    if(!sizeof($results)):
      $results[]=$incident->CustomFields->CFS->Delivery;
    endif;

        return $results;
    }

    function getBusinessObjectInstances($table,$field)
    {

        if($table == "opportunities")
        {
            $this->CI->load->model("custom/Opportunity_model");
            if(getUrlParm('o_id') === null)
            {

                return $this->CI->Opportunity_model->getBlank($field);
            }
            else
            {
                $opportunityInstance = $this->CI->Opportunity_model->get($field);
                if($opportunityInstance === null)
                    return $this->CI->Opportunity_model->getBlank($field);
                return $opportunityInstance;
            }
        }
        else if($table == "orgs")
        {
            $this->CI->load->model("custom/Organization_model");
            if(getUrlParm('org_id') === null)
            {
                return $this->CI->Organization_model->getBlank($field);
            }
            else
            {
                $orgsInstance = $this->CI->Organization_model->get($field);
                if($orgsInstance === null)
                    return $this->CI->Organization_model->getBlank($field);
                return $orgsInstance;
            }
        }
        else if($table == "contacts")
        {

            $this->CI->load->model("custom/ContactMember_model");

            $pos = strpos($_SERVER['REQUEST_URI'],"/app/organization_detail");

            if($pos === false)
            {

                $pos1 = strpos($_SERVER['REQUEST_URI'],"app/account/profile");
                if($pos1 === false)
                {
                    if(getUrlParm('c_id') === null)
                    {
                        return $this->CI->ContactMember_model->getBlank($field);
                    }
                    else
                    {
                        $contactsInstance = $this->CI->ContactMember_model->get($field);
                        if($contactsInstance === null)
                            return $this->CI->ContactMember_model->getBlank($field);
                        return $contactsInstance;
                    }
                }
                else
                {

                    return $this->CI->ContactMember_model->getBroker($field);
                }

            }
            else
            {

                if(getUrlParm('org_id') === null)
                {
                    return $this->CI->ContactMember_model->getBlankOrg($field);
                }
                else
                {
                    $contactsInstance = $this->CI->ContactMember_model->getorg($field);
                    if($contactsInstance === null)
                        return $this->CI->ContactMember_model->getBlankOrg($field);
                    return $contactsInstance;
                }
            }

        }

        return null;
    }


//Investigation Page Widgets.

   public function getParentIncidentId($i_id){
   	$incident=RNCPHP\Incident::fetch($i_id);
	return $incident->CustomFields->CFS->Incident->ID;
	//return 222;
   }

   public function getChildIncidentIds($i_id){

     $incidents = RNCPHP\ROQl::query("SELECT * FROM Incident WHERE Incident.CustomFields.CFS.Incident='$i_id'")->next();
     $child_incidents=array();
   		while($incident=$incidents->next())
   		{
      $child_incidents[]=$incident['ID'];
   		}
      return (sizeof($child_incidents))?$child_incidents:0;
   }

   public function investigationDetails($i_id){
   	//We have to give the details of the parent incident, as
	$incident=RNCPHP\Incident::fetch($i_id);
	return $incident;
   }

   public function getDeliveryById($did){
   	$results=RNCPHP\ROQL::queryObject("select CFS.Delivery FROM CFS.Delivery WHERE CFS.Delivery.ID='$did'")->next();
	while($result=$results->next()):
	return $result;
	endwhile;
   }
   public function getDeliveryInvestigationDetails($i_id){
   	$delivery_details=array();
	$deliveryLineItems = RNCPHP\ROQL::query("select * from CFS.IncidentDeliveryItem WHERE CFS.IncidentDeliveryItem.Incident='$i_id'")->next();
	while($delivery=$deliveryLineItems->next()):
		$delivery_details[]=$this->getDeliveryById($delivery['delivery_id']);
	endwhile;

	return $delivery_details;
   }

  public function fetchCorrectiveActionsModel($i_id){
  	$correctiveActions=array();
  	$ca=RNCPHP\ROQL::query("Select * from CFS.CorrectiveAction WHERE CFS.CorrectiveAction.Incident='$i_id'")->next();
	while($result=$ca->next()):
		$correctiveActions[]=$result;
	endwhile;

	return $correctiveActions;
  }

  /*Change the status of the investigation if any of the correctiveActions are remaining.
  */
  function changeCorrectiveInvestigationStatus($i_id){

     $ca=RNCPHP\ROQL::query("SELECT count(*) as total from CFS.CorrectiveAction WHERE CFS.CorrectiveAction.Complete='0' AND CFS.CorrectiveAction.Incident='$i_id'")->next();
     while($result=$ca->next()){
       $total=$result['total'];
       break;
     }
     if($total){
     $investigation=RNCPHP\Incident::fetch($i_id);
     $status_id=$this->getStatusIdByStatusName('Resolved');
     $investigation->StatusWithType->Status=$status_id;
     $investigation->save();
     RNCPHP\ConnectAPI::commit();
     }
  }

  public function changeStatusCorrectiveActionsModel($caid,$status_id){
  	foreach($caid as $ca):
  	$ca_roql=RNCPHP\ROQL::queryObject("SELECT CFS.CorrectiveAction FROM CFS.CorrectiveAction WHERE CFS.CorrectiveAction.ID='$ca'")->next();
	$corrective_action=$ca_roql->next();
	$i_id=$corrective_action->Incident->ID;
	$corrective_action->Complete=$status_id;
	$corrective_action->save();
  RNCPHP\ConnectAPI::commit();
    endforeach;

  	$this->getCorrectiveActionsHTML($i_id);
  }

  public function deleteCorrectiveActionsModel($caid){
  	foreach($caid as $ca):
  	$ca_roql=RNCPHP\ROQL::queryObject("SELECT CFS.CorrectiveAction FROM CFS.CorrectiveAction WHERE CFS.CorrectiveAction.ID='$ca'")->next();
	$corrective_action=$ca_roql->next();
	$i_id=$corrective_action->Incident->ID;
	$corrective_action->destroy();
    endforeach;

	$this->getCorrectiveActionsHTML($i_id);
  }
  public function getCorrectiveActionsHTML($i_id){
    //$this->changeCorrectiveInvestigationStatus($i_id);
  	$correctiveActions=$this->fetchCorrectiveActionsModel($i_id);
	$html="";
	$html="<table style='width:100%' class='actiontable'><tr><th><input type='checkbox' id='centralCheck' onclick=\"centralCheck()\"></th><th>Description</th><th>Created</th><th>Due Date</th><th>Completed</th></tr>";
  	$counter=0;
	foreach($correctiveActions as $ca):
		$checkbox="<input type='checkbox' id='{$ca['ID']}' class='corrective_action_checkbox'>";
		$completed=($ca['Complete'])?"<span class='tick'>&#x2714;</span>":"<span class='untick'>&#x2718;</span>";
		$created=date("m/d/Y",strtotime($ca['CreatedTime']));
		$duedate=date("m/d/Y",strtotime($ca['DueDate']));
		$html.="<tr><td>$checkbox</td><td>{$ca['Description']}</td><td>{$created}</td><td>{$duedate}</td><td>$completed</td></tr>";
		$counter++;
	endforeach;

	if(!$counter)
	$html.="<tr><td colspan='5' style='color:red'>No Corrective Actions Added to this investigation!</td></tr>";

	$html.="</table>";
	echo $html;
  }

public function addCorrectiveActionModel(){

	$corrective_action=new RNCPHP\CFS\CorrectiveAction();
	$corrective_action->Complete=(int)$_POST['complete'];
	if($_POST['completion_date'])
	$corrective_action->CompletionDate=strtotime($_POST['completion_date']);
	if($_POST['due_date'])
	$corrective_action->DueDate=strtotime($_POST['due_date']);
	if($_POST['description'])
	$corrective_action->Description=substr($_POST['description'],0,255);
	if($_POST['detail'])
	$corrective_action->Details=substr($_POST['detail'],0,2000);
	if($_POST['i_id'])
	$corrective_action->Incident=RNCPHP\Incident::fetch($_POST['i_id']);

	$corrective_action->save();
  RNCPHP\ConnectAPI::commit();

	$this->getCorrectiveActionsHTML($_POST['i_id']);

}

public function editCorrectiveActionModel(){
	$corrective_action=RNCPHP\CFS\CorrectiveAction::fetch($_POST['edit_id']);

	$corrective_action->Complete=$_POST['complete'];

	$corrective_action->CompletionDate=($_POST['completion_date'])?strtotime($_POST['completion_date']):NULL;

	$corrective_action->DueDate=($_POST['due_date'])?strtotime($_POST['due_date']):NULL;

	$corrective_action->Description=substr($_POST['description'],0,255);

	$corrective_action->Details=substr($_POST['detail'],0,2000);
	if($_POST['i_id'])
	$corrective_action->Incident=RNCPHP\Incident::fetch($_POST['i_id']);

	$corrective_action->save();
  RNCPHP\ConnectAPI::commit();

	$this->getCorrectiveActionsHTML($_POST['i_id']);
}


public function SaveThreadSendMailModel($data,$i_id){

	    $arr=array();
        $formData = $this->processFields($data, $presentFields);
        try{
            $profile = $this->CI->session->getProfile();
            $cid=$profile->c_id->value;
            $incident = RNCPHP\Incident::fetch($i_id);


            if($formData['Incident.Threads']->value)
            {
                $incident->Threads = new RNCPHP\ThreadArray();
                $incident->Threads[0] = new RNCPHP\Thread();
                $incident->Threads[0]->EntryType = new RNCPHP\NamedIDOptList();
                $incident->Threads[0]->EntryType->ID = 3; // Used the ID here. See the Thread object for definition
                $incident->Threads[0]->Text =$formData['Incident.Threads']->value;
            }

			$value = $formData['Incident.FileAttachments']->value;
            if(is_array($value) && count($value)){
                $incident->FileAttachments = new RNCPHP\FileAttachmentIncidentArray();
                foreach($value as $attachment){
                    if($tempFile =   get_cfg_var('upload_tmp_dir') . '/' . $attachment->localName) {
                        $file = $incident->FileAttachments[] = new RNCPHP\FileAttachmentIncident();
                        $file->ContentType = $attachment->contentType;
                        $file->setFile($tempFile);
                        $file->FileName = preg_replace("/[\r\n\/:*?\"<>|]+/", '_', $attachment->userName);
                    }
                }
            }

			$incident->save();
      RNCPHP\ConnectAPI::commit();

			//Also the send a mail to the client.
			$client_mail=$formData['Contact.Emails.PRIMARY.Address']->value;
			if($client_mail){
				$FNTConfig = RNCPHP\FNT\Config::fetch(1);
				$p_exp=time()+($FNTConfig->LinkExpirationHours*3600);
				$p_created=str_replace("=","",base64_encode($client_mail));
				$p_tok=str_replace("=","",base64_encode($FNTConfig->SecurityString));
				$p_ques=str_replace("=","",base64_encode($formData['Incident.Threads']->value));
				$p_asked_by=str_replace("=","",base64_encode($profile->first_name->value." ".$profile->last_name->value));
                $site_host=($_SERVER['HTTP_HOST']=="cpchem.custhelp.com")?"http://cpchem.custhelp.com":"http://cpchem--pro.custhelp.com";
				
                $html_body=<<<xyz
				<div style='font-family:arial;line-height:30px'>
				<b>Hello User,</b><br>
				Your input is required to take further action for a customer feedback complaint (Ref-No: {$incident->LookupName}). <br>
				Please click the link below to provide your response.<br>
				<a href='$site_host/cgi-bin/cpchem.cfg/php/custom/oracle/fnt/fnt_incident_update.php?p_i_id={$incident->ID}&p_exp={$p_exp}&p_tok={$p_tok}&p_created={$p_created}&p_ques={$p_ques}&p_asked_by={$p_asked_by}'>Click To Respond</a>
				<br><br>
				Thanks a lot<br>
				CPChem Team<br>
				<img src='{$_SERVER['HTTP_HOST']}/euf/assets/themes/standard/images/CPChem_logo.png' style='width:100px'>
				</div>
xyz;
				    $mm = new RNCPHP\MailMessage();
				//set TO,CC,BCC fields as necessary
				    $mm->To->EmailAddresses = array($client_mail);
				//set subject
				    $mm->Subject = "CPCHEM-Forward & Tracking of Complaint No ".$incident->LookupName;
				//set body of the email
				    $mm->Body->Html = $html_body;
				//set marketing options
					$mm->Options->IncludeOECustomHeaders = false;
				//send email
				    $mm->send();
			}




            $arr['result']['transaction']['incident']['key']="i_id";
            $arr['result']['transaction']['incident']['value']=$incident->ID;
            $arr['result']['sessionParam']="/";

        }
        catch(RNCPHP\ConnectAPIError $err){
            $arr['errorMessage']=$err->getMessage()."@".$err->getLine();
        }

        return json_encode($arr);
}

public function contactLookUpSearchModel($input){
	if($input){
		$html="";
		$search=RNCPHP\ROQL::queryObject("SELECT Contact FROM Contact WHERE Contact.Emails.Address LIKE '%$input%' OR Contact.Login LIKE '%$input%' GROUP BY Contact.ID LIMIT 10")->next();
		$count=0;
		while($c=$search->next()){

			$html.="<li onclick=\"setContact('{$c->ID}','{$c->Emails[0]->Address}')\">{$c->Emails[0]->Address}</li>";
			$count++;
		}
		echo ($count)?"<div class='deliverableLists'><ul>".$html."</ul></div>":"<div style='display:none'></div>";
		$html.="";
	}
	else{
		echo "";
	}

}
public function getNonClosedCorrectiveActions($i_id){
 $investigation_closure=RNCPHP\ROQL::query("select count(*) as total from CFS.CorrectiveAction WHERE Complete='0' AND Incident='$i_id'")->next();
 while($result=$investigation_closure->next()){
   return $result['total'];
 }
}

public function setInvestigationClosureModel($data,$i_id){
	$arr=array();
        $formData = $this->processFields($data, $presentFields);

        try{
            $incident = RNCPHP\Incident::fetch($i_id);
            $incident->CustomFields->c->was_there_a_problem=(int)$formData['Incident.CustomFields.c.was_there_a_problem']->value;
      if($formData['Incident.CustomFields.c.was_there_a_problem']->value){
            $incident->CustomFields->c->was_there_a_problem=$formData['Incident.CustomFields.c.was_there_a_problem']->value;

            //Also change the status to closed,//Investigation Incident Closed.
            $resolved_status=$this->getStatusIdByStatusName('resolve');
            $closed_status=$this->getStatusIdByStatusName('close');
            $corrective_status_id=($this->getNonClosedCorrectiveActions($i_id))?$resolved_status:$closed_status;
            $incident->StatusWithType->Status=$corrective_status_id;
            $incident->CustomFields->c->complaint_resolved=1;
            //Close the parent Incident as well.
            $this->closeParentIncident($i_id);
      }
      else{
        $incident->CustomFields->c->was_there_a_problem=(int)$formData['Incident.CustomFields.c.was_there_a_problem']->value;
        //Also change the status to resolved.
        $resolved_id=$this->getStatusIdByStatusName("resolved");
        $incident->StatusWithType->Status=$resolved_id;
        $incident->CustomFields->c->complaint_resolved=0;

      }
			if($formData['Incident.CustomFields.c.why1']->value){
            $incident->CustomFields->c->why1=$formData['Incident.CustomFields.c.why1']->value;
            $incident->CustomFields->c->root_cause=$formData['Incident.CustomFields.c.why1']->value;
          }
			if($formData['Incident.CustomFields.c.why2']->value){
            $incident->CustomFields->c->why2=$formData['Incident.CustomFields.c.why2']->value;
            $incident->CustomFields->c->root_cause=$formData['Incident.CustomFields.c.why2']->value;
          }
			if($formData['Incident.CustomFields.c.why3']->value){
            $incident->CustomFields->c->why3=$formData['Incident.CustomFields.c.why3']->value;
            $incident->CustomFields->c->root_cause=$formData['Incident.CustomFields.c.why3']->value;
          }
      if($formData['Incident.CustomFields.c.why4']->value){
            $incident->CustomFields->c->why4=$formData['Incident.CustomFields.c.why4']->value;
            $incident->CustomFields->c->root_cause=$formData['Incident.CustomFields.c.why4']->value;
          }
			if($formData['Incident.CustomFields.c.why5']->value){
            $incident->CustomFields->c->why5=$formData['Incident.CustomFields.c.why5']->value;
		        $incident->CustomFields->c->root_cause=$formData['Incident.CustomFields.c.why5']->value;
		      }
			if($formData['Incident.CustomFields.c.root_cause_category']->value)
			$incident->CustomFields->c->root_cause_category=(int)$formData['Incident.CustomFields.c.root_cause_category']->value;


       $incident->save();
       //RNCPHP\ConnectAPI::commit();


            $arr['result']['transaction']['incident']['key']="i_id";
            $arr['result']['transaction']['incident']['value']=$incident->ID;
            $arr['result']['sessionParam']="/";

        }
        catch(RNCPHP\ConnectAPIError $err){
            $arr['errorMessage']=$err->getMessage()."@".$err->getLine();
        }

        return json_encode($arr);

}

private function closeParentIncident($i_id){
  $parent_id=$this->getParentIncidentId($i_id);
  $incident = RNCPHP\Incident::fetch($parent_id);
  $incident->StatusWithType->Status=2;
  $incident->CustomFields->c->complaint_resolved=1;
  $incident->save();
  //RNCPHP\ConnectAPI::commit();
}

function getStatusIdByStatusName($searchtext){
  if($searchtext):
    $status_list=RNCPHP\ConnectAPI::getNamedValues("RightNow\\Connect\\v1_2\\Incident.StatusWithType.Status");
    $searchtext=strtolower($searchtext);
    foreach($status_list as $status):
      $lookup_name=strtolower($status->LookupName);
      if(substr_count($lookup_name,$searchtext) > 0 ):
      return $status->ID;
      endif;
    endforeach;
endif;
    return 0;

}

function getIncidentStatusListModel(){
  $statusList=array();
  $status_list=RNCPHP\ConnectAPI::getNamedValues("RightNow\\Connect\\v1_2\\Incident.StatusWithType.Status");
    foreach($status_list as $status):
      $statusList[$status->ID]=$status->LookupName;
    endforeach;

  return $statusList;
}

function getActionItemListModel(){
  $query=RNCPHP\ROQL::queryObject("SELECT CFS.ActionItemStatus from CFS.ActionItemStatus")->next();
  while($result=$query->next()):
    $statusList[$result->ID]=$result->LookupName;
  endwhile;
  return $statusList;
}

function deliveryFind($delivery_no, $sold_to_customer_name){
	$deliverys=RNCPHP\ROQL::queryObject("SELECT CFS.Delivery FROM CFS.Delivery WHERE CFS.Delivery.Delivery='$delivery_no' AND CFS.Delivery.SoldToCustomerName='$sold_to_customer_name'")->next();
	while($delivery=$deliverys->next()):
		return $delivery;
	endwhile;
}

function getActionItemAttachment($id){
    initConnectAPI("FNT_CWS","FNTRocks!");
	$action_item=RNCPHP\CFS\ActionItem::fetch($id);
	//$action_item->FileAttachments[0]->getAdminURL();
	return $action_item->FileAttachments;

}

function getCorrectiveAction_Model($input){
	$corrective_action=RNCPHP\CFS\CorrectiveAction::fetch($input);
	$data=array();
	$data['ID']=$input;
	$data['Details']=$corrective_action->Details;
	$data['Description']=$corrective_action->Description;
	$data['Complete']=($corrective_action->Complete)?1:0;
	$data['CompletionDate']=$corrective_action->CompletionDate?date("Y-m-d",$corrective_action->CompletionDate):"";
	$data['DueDate']=$corrective_action->DueDate?date("Y-m-d",$corrective_action->DueDate):"";

	return $data;
}


function getProdOrgLinking(){
	$data=array();
	$orgToProd=RNCPHP\ROQL::query("SELECT * FROM CFS.OrgToProduct")->next();

	while($op=$orgToProd->next()):
		$data[$op['Organization']][]=$op['ProductID'];
	endwhile;

    return $data;

}

function getAllChildIncidents($i_id){
  $sql="SELECT Incident FROM Incident WHERE Incident.CustomFields.CFS.Incident='$i_id'";
  $child_incident_resource=RNCPHP\ROQL::queryObject($sql)->next();
  $child_incidents=array();
  while($ci=$child_incident_resource->next()):
    $child_incidents[]=$ci;
  endwhile;
  return $child_incidents;
}

public function getOrganizationSalesProduct($org_id){
  $products=RNCPHP\ROQL::query("SELECT * from CFS.OrgToProduct WHERE CFS.OrgToProduct.Organization='$org_id'")->next();
  $org_products=array();
  while($result=$products->next()){
  $org_products[]=$result['ProductID'];
  }

  return $org_products;
}

public function getServiceProductList(){

  $products=RNCPHP\ROQL::query("select * from ServiceProduct")->next();
  $productList=array();
  $counter=0;
  while($result=$products->next()):
    $productList[$counter]['ID']=$result['ID'];
    $productList[$counter]['LookupName']=$result['LookupName'];
    $counter++;
  endwhile;

  return $productList;
}

} //Class Ends Here !
