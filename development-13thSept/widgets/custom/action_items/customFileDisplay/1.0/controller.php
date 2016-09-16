<?php
namespace Custom\Widgets\action_items;

class customFileDisplay extends \RightNow\Libraries\Widget\Base
{
    function __construct($attrs) {
        parent::__construct($attrs);
    }

    function getData() {
       $ci=&get_instance();
	   if($this->attrs['type']->value=="action_items"):
	   $id=getUrlParm('ID');
	   $attachments=$ci->model('custom/CustomerFeedbackSystem')->getActionItemAttachment($id);
	   $this->data['attachments']=$attachments;
	   endif;

	   if($this->attrs['type']->value=="parent_incident"):
	   $i_id=getUrlParm('i_id');
	   $parent_id=$ci->model('custom/CustomerFeedbackSystem')->getParentIncidentId($i_id);
	   $attachments=$ci->model('custom/CustomerFeedbackSystem')->getIncident($parent_id)->FileAttachments;
	   $this->data['attachments']=$attachments;
	   endif;

        return parent::getData();

    }

    /**
     * Overridable methods from FileListDisplay:
     */
    // function($a, $b)
    // function getAttachments($input, array $commonAttachments)
}
