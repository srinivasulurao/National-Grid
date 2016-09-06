<?php
namespace Custom\Widgets\action_items;

class customFileDisplay extends \RightNow\Libraries\Widget\Base
{
    function __construct($attrs) {
        parent::__construct($attrs);
    }

    function getData() {
       $ci=&get_instance();
	   $id=getUrlParm('ID');
	   $attachments=$ci->model('custom/CustomerFeedbackSystem')->getActionItemAttachment($id);
	   $this->data['attachments']=$attachments;
        return parent::getData();

    }

    /**
     * Overridable methods from FileListDisplay:
     */
    // function($a, $b)
    // function getAttachments($input, array $commonAttachments)
}