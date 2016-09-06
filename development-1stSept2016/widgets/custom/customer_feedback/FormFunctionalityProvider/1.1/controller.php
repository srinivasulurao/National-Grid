<?php
namespace Custom\Widgets\customer_feedback;

class FormFunctionalityProvider extends \RightNow\Libraries\Widget\Base {
    function __construct($attrs) {
        parent::__construct($attrs);
    }

    function getData() {
echo "hello";
        return parent::getData();

    }
}