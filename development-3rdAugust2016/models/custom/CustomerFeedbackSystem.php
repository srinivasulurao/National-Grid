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
        parent::__construct();
        $this->IncidentSecurity();
    }

    function getBusinessObjectInstance($package,$table,$field)
    {

        return $this->getBlank($package,$table,$field);

    }

    function IncidentSecurity(){
        $i_id=getUrlParm('i_id');
        $incident = RNCPHP\Incident::fetch($i_id);
        $incident_c_id=$incident->PrimaryContact->ID;

        $ci=&get_instance();
        $profile=$ci->session->getProfile();
        $profile_cid=$profile->c_id->value;

        if($profile_cid!=$incident_c_id){
         @header("Location:/app/error");
        }

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

        $qry = "RightNow\\Connect\\v1_2\\".$package."\\$table";
        $action = $qry ::fetch($id);
        // $action = RNCPHP\CFS\ActionItem::fetch($id);

        return $action->$field;

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

                $incident->save(RNCPHP\RNObject::SuppressAll);
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
                $incident->CustomFields->c->request_type=intval($formData['Incident.CustomFields.c.request_type']->value);
                $incident->CustomFields->CFS->Supplier=intval($formData['Name']->value);
                //$incident->CustomFields->c->DeliveryShipToCustomerName=$formData['DeliveryShipToCustomerName']->value;
                //$incident->PrimaryContact = RNCPHP\Contact::fetch($contact->ID);
                $incident->CustomFields->c->request_type=intval($formData['Incident.CustomFields.c.request_type']->value);
                $incident->CustomFields->c->supplier_order_number=$formData['Incident.CustomFields.c.supplier_order_number']->value;
                $incident->CustomFields->c->proposed_solution=$formData['Incident.CustomFields.c.proposed_solution']->value;
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

                $incident->save(RNCPHP\RNObject::SuppressAll);
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
            $incident = new RNCPHP\Incident();
            $incident->Subject = $formData['Incident.Subject']->value;
            $incident->PrimaryContact = RNCPHP\Contact::fetch($cid);
            $incident->CustomFields->c->complaint_type=intval($formData['Incident.CustomFields.c.complaint_type']->value);
            $incident->Category=intval($formData['Incident.Category']->value);
            $incident->CustomFields->c->product_returned=intval($formData['Incident.CustomFields.c.product_returned']->value);
            $incident->CustomFields->c->product_sample_taken=intval($formData['Incident.CustomFields.c.product_sample_taken']->value);
            $incident->CustomFields->c->product_sample_returned_to=$formData['Incident.CustomFields.c.product_sample_returned_to']->value;
            $incident->CustomFields->c->request_type=intval($formData['Incident.CustomFields.c.request_type']->value);
            if($formData['Incident.Threads']->value)
            {
                $incident->Threads = new RNCPHP\ThreadArray();
                $incident->Threads[0] = new RNCPHP\Thread();
                $incident->Threads[0]->EntryType = new RNCPHP\NamedIDOptList();
                $incident->Threads[0]->EntryType->ID = 3; // Used the ID here. See the Thread object for definition
                $incident->Threads[0]->Text =$formData['Incident.Threads']->value;
            }
            $incident->CustomFields->c->target_date=strtotime($formData['c$target_date']->value);
            $incident->CustomFields->c->sold_to_customer_name=$formData['Incident.CustomFields.c.sold_to_customer_name']->value;
            $incident->CustomFields->CFS->Delivery=intval($this->getDeliveryId($formData['CFS$Delivery']->value));
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

            $incident->save(RNCPHP\RNObject::SuppressAll);
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

    public function insertIncidentDeliveryLineItems($delivery_line_items,$incident_id){
        $items=explode(",",$delivery_line_items);
        foreach($items as $item){
            $idli=new RNCPHP\CFS\IncidentDeliveryItem();
            $idli->Incident=RNCPHP\Incident::fetch($incident_id);
            $idli->delivery_id=$item;
            $idli->save();
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
            endif;
        endforeach;

    }


    public function CustomerCompliantUpdate($data,$i_id){

        $arr=array();
        $formData = $this->processFields($data, $presentFields);

        try{
            $profile = $this->CI->session->getProfile();
            $cid=$profile->c_id->value;
            $incident = RNCPHP\Incident::fetch($i_id);
            $incident->Subject = $formData['Incident.Subject']->value;
            $incident->PrimaryContact = RNCPHP\Contact::fetch($cid);
            $incident->CustomFields->c->complaint_type=intval($formData['Incident.CustomFields.c.complaint_type']->value);
            $incident->Category=intval($formData['Incident.Category']->value);
            $incident->CustomFields->c->product_returned=intval($formData['Incident.CustomFields.c.product_returned']->value);
            $incident->CustomFields->c->product_sample_taken=intval($formData['Incident.CustomFields.c.product_sample_taken']->value);
            $incident->CustomFields->c->product_sample_returned_to=$formData['Incident.CustomFields.c.product_sample_returned_to']->value;
            $incident->CustomFields->c->request_type=intval($formData['Incident.CustomFields.c.request_type']->value);
            if($formData['Incident.Threads']->value)
            {
                $incident->Threads = new RNCPHP\ThreadArray();
                $incident->Threads[0] = new RNCPHP\Thread();
                $incident->Threads[0]->EntryType = new RNCPHP\NamedIDOptList();
                $incident->Threads[0]->EntryType->ID = 3; // Used the ID here. See the Thread object for definition
                $incident->Threads[0]->Text =$formData['Incident.Threads']->value;
            }
            $incident->CustomFields->c->target_date=strtotime($formData['c$target_date']->value);
            $incident->CustomFields->c->sold_to_customer_name=$formData['Incident.CustomFields.c.sold_to_customer_name']->value;
            $incident->CustomFields->CFS->Delivery=intval($this->getDeliveryId($formData['CFS$Delivery']->value));
            $this->updateIncidentDeliveryLineItems($formData['Incident.CustomFields.c.delivery_line_items']->value,$i_id);
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

            $incident->save(RNCPHP\RNObject::SuppressAll);
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

    public function getDeliveryId($del_id){
        $sql="SELECT * from CFS.Delivery WHERE CFS.Delivery.Delivery='$del_id'";
        $sql_instance = RNCPHP\ROQL::query($sql)->next();
        while($delivery = $sql_instance->next()){
            return $delivery['ID'];
        }

    }

    public function getIncident($incident_id){
        $incident = RNCPHP\Incident::fetch($incident_id);
        return $incident;
    }


    public function deliveryLookUp($search_term){
        $ci=get_instance();
        $user=$ci->session->getProfile();
        $org_id=$user->org_id->value;
        $org_id=2;
        if($search_term && $org_id):
            //$sql_instance = RNCPHP\ROQL::query("SELECT * from CFS.Delivery WHERE CFS.Delivery.Plant='$org_id' AND (CFS.Delivery.Delivery LIKE '%$search_term%' OR CFS.Delivery.CustomerPONumber LIKE '%$search_term%' OR CFS.Delivery.SoldToCustomerName LIKE '%$search_term%' OR CFS.Delivery.ShipToCustomerName LIKE '%$search_term%' OR CFS.Delivery.SoldToCustomerRegion LIKE '%$search_term%') GROUP BY CFS.Delivery.Delivery")->next();
            $sql_instance=RNCPHP\ROQL::query("SELECT * FROM CFS.Delivery WHERE CFS.Delivery.Organization='$org_id' AND CFS.Delivery.Delivery LIKE '%$search_term%' GROUP BY CFS.Delivery.Delivery")->next();
            $data=array();
            $counter=0;
            while($delivery = $sql_instance->next())
            {
                $data[$counter]['id']=$delivery['ID'];
                $data[$counter]['delivery']=$delivery['Delivery'];
                $counter++;
            }
            echo json_encode($data);
        endif;

    }

    public function deliveryDetailsLookupModel($delivery_no,$customer_po_no,$ship_to_customer,$sold_to_customer){
        $ci=get_instance();
        $user=$ci->session->getProfile();
        $org_id=$user->org_id->value;
        $org_id=1;

        if(($delivery_no or $customer_po_no or $ship_to_customer or $sold_to_customer) && $org_id):
            $sql="SELECT * from CFS.Delivery WHERE CFS.Delivery.Plant='$org_id' AND (";
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

            $sql_instance = RNCPHP\ROQL::query($sql)->next();
            $data="";
            $counter=0;
            while($delivery = $sql_instance->next())
            {
                $data.="<div style='border-bottom:1px solid lightgrey;'><span style='width:100px;display:inline-block;margin-right:10px;'><a href='javascript:void(0)' onclick=\"setDelivery('{$delivery['Delivery']}')\">{$delivery['Delivery']}</a></span><span>{$delivery['DeliveryGoodsIssueDate']}</span></div>";
                $counter++;
            }
            echo ($counter)?"<h3 style='display:block;width:100%;'><u>Deliverables</u></h3><br>".$data:"No Results found!";
        endif;

    }

    function deliveryLineItemListModel($delivery_id,$incident_id){

        $product_selected=array();
        if($incident_id):
            $dli_sql="SELECT * from CFS.IncidentDeliveryItem WHERE CFS.IncidentDeliveryItem.Incident='$incident_id'";
            $dli=RNCPHP\ROQL::query($dli_sql)->next();
            while($delivery_item=$dli->next()):
                $product_selected[]=$delivery_item['delivery_id'];
            endwhile;
        endif;

        if($delivery_id):
            $sql="select * from CFS.Delivery WHERE CFS.Delivery.Delivery='$delivery_id'";
            $sql_instance = RNCPHP\ROQL::query($sql)->next();
            $data="";
            while($delivery = $sql_instance->next())
            {
                $checked=(in_array($delivery['ID'],$product_selected))?"checked='checked'":"class='non_checked incident_delivery_item'";
                $data.="<span $checked><input type='checkbox' $checked class='incident_delivery_item' value='{$delivery['ID']}'>{$delivery['DeliveryLineItem']}"."</span><br>";
            }
        endif;
        if($data)
            $data="<div class='delivery_line_item_list_products'><label style='display:block !important'>Products (Having Issue)</label>".$data."</div>";
        echo $data;

    }

    function getDeliveryDetailsData($delivery_no){
        $data="";
        if($delivery_no){
            $sql="select * from CFS.Delivery WHERE CFS.Delivery.Delivery='$delivery_no'";
            $sql_instance = RNCPHP\ROQL::query($sql)->next();
            $data="";
            while($delivery = $sql_instance->next())
            {
                $sold_to_customer_name=($delivery['SoldToCustomerName'])?$delivery['SoldToCustomerName']:$delivery['SoldToCustomerRegion'];
                $data.="<tr><td>{$sold_to_customer_name}</td><td>{$delivery['ShipToCustomerName']}</td><td>{$delivery['DeliveryLineItem']}</td></tr>";
            }
        }

        echo ($data)?"<table width='100%' class='delivery_details_table'><tr><th>Sold To Customer Name</th><th>Ship To Customer Name</th><th>Product</th></tr>".$data."</table>":"Sorry, No results found !";
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

                $response['Res']=$Action_item->save(RNCPHP\RNObject::SuppressAll);
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
                $response->res=$Action_item->save(RNCPHP\RNObject::SuppressAll);
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

    function searchcontact($q)
    {

        $q=urldecode($q);

        $result = RNCPHP\ROQL::query("select * from Contact where Contact.Name.first LIKE '$q%' limit 0,10")->next();


        echo '<ul>';
        while($contactdata = $result->next())
        {
            // $data[] =$contactdata['LookupName']."-".$contactdata['ID'];
            ?>
            <li onclick='fill("<?php echo $contactdata['ID']."-".$contactdata['LookupName']; ?>")'><?php echo $contactdata['LookupName']; ?></li>
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


}
